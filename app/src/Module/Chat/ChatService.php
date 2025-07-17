<?php

declare(strict_types=1);

namespace App\Module\Chat;

use App\Module\Chat\Domain\Chat;
use App\Module\Chat\Domain\Message;
use App\Module\Chat\Domain\MessageRole;
use App\Module\Chat\Domain\MessageStatus;
use App\Module\Chat\Internal\StreamCache;
use App\Module\Context\ChatHistory;
use App\Module\Context\FullInfo;
use App\Module\LLM\Internal\Domain\Request;
use App\Module\LLM\Internal\Domain\RequestStatus;
use App\Module\LLM\LLM;
use Ramsey\Uuid\UuidInterface;
use Spiral\Core\Attribute\Proxy;
use Spiral\Core\FactoryInterface;
use Symfony\AI\Platform\Message\Message as SynfonyMessage;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\AI\Platform\Message\MessageBagInterface;
use Symfony\AI\Platform\Message\SystemMessage;

final class ChatService
{
    public function __construct(
        private readonly StreamCache $cache,
        #[Proxy]
        private readonly LLM $llm,
        private readonly FactoryInterface $factory,
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
        MessageRole $role = MessageRole::User,
    ): Message {
        $chat instanceof Chat or $chat = Chat::findByPK($chat) ?? throw new \InvalidArgumentException(
            'Chat not found.',
        );
        $message = Message::create($chat, $message, $role);
        $message->status = MessageStatus::Completed;

        $message->saveOrFail();

        if ($role === MessageRole::User) {
            # Compose the message bag for the LLM request
            #
            # Get context from Info files
            $fullInfo = $this->factory->make(FullInfo::class)->getMessageBag();
            # Load all the messages from the chat history
            $history = $this->factory->make(ChatHistory::class, ['chat' => $chat])->getMessageBag();
            $bag = $this->mergeBags($fullInfo, $history);

            $aiMessage = Message::create($chat, null, MessageRole::Assistant);
            $messageId = $aiMessage->uuid;
            $aiMessage->saveOrFail();

            $request = $this->llm->request(
                $bag,
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

    /**
     * @return void
     * @throws \Throwable
     */
    public function deleteMessage(string|UuidInterface|Chat $chat, string|UuidInterface|Message $message): void
    {
        $chat instanceof Chat or $chat = Chat::findByPK($chat) ?? throw new \InvalidArgumentException(
            'Chat not found.',
        );
        $message instanceof Message or $message = Message::findByPK($message) ?? throw new \InvalidArgumentException(
            'Message not found.',
        );

        $message->chatUuid->equals($chat->uuid) or throw new \InvalidArgumentException(
            'Message does not belong to the specified chat.',
        );

        $message->deleteOrFail();
        $this->cache->delete($message->uuid->toString());
    }

    private function mergeBags(MessageBagInterface ...$bags): MessageBagInterface
    {
        $final = new MessageBag();
        $systemPrompts = [];
        foreach ($bags as $bag) {
            foreach ($bag->getMessages() as $message) {
                if ($message instanceof SystemMessage) {
                    $systemPrompts[] = $message->content;
                    continue;
                }

                $final->add($message);
            }
        }

        $systemPrompts === [] or $final = $final->prepend(SynfonyMessage::forSystem(\implode("\n\n", $systemPrompts)));

        return $final;
    }
}
