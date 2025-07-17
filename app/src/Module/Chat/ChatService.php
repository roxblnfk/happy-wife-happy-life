<?php

declare(strict_types=1);

namespace App\Module\Chat;

use App\Module\Chat\Domain\Chat;
use App\Module\Chat\Domain\Message;
use App\Module\Chat\Domain\MessageStatus;
use App\Module\Chat\Internal\StreamCache;
use App\Module\LLM\Internal\Domain\Request;
use App\Module\LLM\Internal\Domain\RequestStatus;
use App\Module\LLM\LLM;
use Ramsey\Uuid\UuidInterface;
use Spiral\Core\Attribute\Proxy;
use Symfony\AI\Platform\Message\MessageBag;

final class ChatService
{
    public function __construct(
        private readonly StreamCache $cache,
        #[Proxy]
        private readonly LLM $llm,
    ) {}

    public function createChat(): Chat
    {
        $chat = Chat::create();
        $chat->saveOrFail();
        return $chat;
    }

    /**
     * Deletes a chat.
     *
     * @param non-empty-string|UuidInterface|Chat $chat Chat identifier or Chat object.
     * @return bool True if the chat was deleted successfully, false otherwise.
     */
    public function deleteChat(string|UuidInterface|Chat $chat): bool
    {
        $chat instanceof Chat or $chat = Chat::findByPK($chat);
        return $chat?->delete() ?? true;
    }

    /**
     * Sends a message to a chat.
     *
     * @param non-empty-string|UuidInterface|Chat $chat Chat identifier or Chat object.
     * @param non-empty-string $message The message content.
     * @return Message The created message object.
     */
    public function sendMessage(
        string|UuidInterface|Chat $chat,
        string $message,
        bool $isHuman = true,
    ): Message {
        $chat instanceof Chat or $chat = Chat::findByPK($chat) ?? throw new \InvalidArgumentException(
            'Chat not found.',
        );

        $message = Message::create($chat, $message, $isHuman);
        $isHuman and $message->status = MessageStatus::Completed;

        $message->saveOrFail();

        if ($isHuman) {
            $aiMessage = Message::create($chat, null, false);
            $messageId = $aiMessage->uuid;
            $aiMessage->saveOrFail();
            $request = $this->llm->request(
                new MessageBag(\Symfony\AI\Platform\Message\Message::ofUser($message->message)),
                options: [],
                onProgress: function (UuidInterface $requestUuid, string $chunk) use ($messageId): void {
                    $this->cache->write($messageId->toString(), $chunk, true);
                },
                onError: tr(...),
                onFinish: function (Request $request) use ($messageId): void {
                    $msg = Message::findByPK($messageId);
                    $msg->message = $request->output;
                    $msg->status = $request->status === RequestStatus::Completed
                        ? MessageStatus::Completed
                        : MessageStatus::Failed;
                    $msg->save();
                    $this->cache->delete($messageId->toString());
                },
            );
            $message->requestUuid = $request->uuid;
        }

        return $message;
    }

    /**
     * Retrieves new tokens for a message.
     *
     * @param non-empty-string|UuidInterface|Message $message Message identifier or Message object.
     * @param int<0, max> $offset Offset for token retrieval.
     *
     * @return string A string of new tokens.
     */
    public function getNewTokens(string|UuidInterface|Message $message, int $offset = 0): string
    {
        $offset >= 0 or throw new \InvalidArgumentException('Position must be greater than zero.');
        $message instanceof Message or $message = Message::findByPK($message) ?? throw new \InvalidArgumentException(
            'Message not found.',
        );

        if ($message->requestUuid === null) {
            if ($message->status === MessageStatus::Pending or $message->status = MessageStatus::Cancelled) {
                $message->saveOrFail();
            }

            return '';
        }

        return $this->cache->read($message->requestUuid->toString(), $offset);
    }
}
