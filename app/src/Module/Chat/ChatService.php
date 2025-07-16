<?php

declare(strict_types=1);

namespace App\Module\Chat;

use App\Module\Chat\Domain\Chat;
use App\Module\Chat\Domain\Message;
use App\Module\Chat\Domain\MessageStatus;
use Ramsey\Uuid\UuidInterface;

class ChatService
{
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
        $message->saveOrFail();

        // if ($isHuman) {
        // TODO init LLM request
        // }

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
        $offset > 0 or throw new \InvalidArgumentException('Position must be greater than zero.');
        $message instanceof Message or $message = Message::findByPK($message) ?? throw new \InvalidArgumentException(
            'Message not found.',
        );

        if ($message->requestUuid === null) {
            if ($message->status === MessageStatus::Pending or $message->status = MessageStatus::Cancelled) {
                $message->saveOrFail();
            }

            return '';
        }

        // TODO get request

        // Return random string for now
        return \substr(\md5(\microtime()), 0, \mt_rand(10, 20));
    }
}
