<?php

declare(strict_types=1);

namespace App\Module\Context;

use App\Module\Chat\Domain\Chat;
use App\Module\Chat\Domain\Message;
use App\Module\Chat\Domain\MessageRole;
use App\Module\Chat\Domain\MessageStatus;
use Symfony\AI\Platform\Message\Message as SFMessage;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\AI\Platform\Message\MessageBagInterface;
use Symfony\AI\Platform\Message\MessageInterface;

class ChatHistory implements MessagesBagAware
{
    private MessageBag $bag;

    public function __construct(Chat $chat)
    {
        $messages = $chat->messages;
        $bag = new MessageBag();
        foreach ($messages as $message) {
            $msg = $this->convertMessage($message);
            $msg === null or $bag->add($msg);
        }

        $this->bag = $bag;
    }

    public function getMessageBag(): MessageBagInterface
    {
        return $this->bag;
    }

    private function convertMessage(Message $message): ?MessageInterface
    {
        $content = (string) $message->message;
        if ($content === '' || $message->status !== MessageStatus::Completed) {
            return null;
        }

        return match ($message->role) {
            MessageRole::System => SFMessage::forSystem($content),
            // MessageRole::Agent,  # TODO remove
            MessageRole::Assistant, # TODO remove
            MessageRole::User => SFMessage::ofUser($content),
            // MessageRole::Agent, MessageRole::Assistant => SFMessage::ofAssistant($content, []),
            // MessageRole::Tool => SFMessage::ofToolCall()
            default => null,
        };
    }
}
