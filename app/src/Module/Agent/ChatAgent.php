<?php

declare(strict_types=1);

namespace App\Module\Agent;

use App\Module\Chat\Domain\Chat;
use Ramsey\Uuid\UuidInterface;

/**
 * Interface for chat agents
 */
interface ChatAgent
{
    /**
     * Get agent card information for display
     */
    public static function getCard(): AgentCard;

    /**
     * Initialize the chat with this agent
     * This method is called when user clicks on agent card
     * Should send initial message(s) to establish context
     *
     * @param Chat $chat The chat session to initialize
     */
    public function chatInit(Chat $chat): void;

    /**
     * Process a message(s) in an active chat session
     *
     * @param Chat $chat The chat session
     * @param UuidInterface $messageUuid UUID of the message to process
     */
    public function chatProcess(Chat $chat, UuidInterface $messageUuid): void;

    /**
     * Check if this agent can handle the given chat
     * Used to determine which agent should process a message
     *
     * @param Chat $chat The chat session
     */
    public function canHandle(Chat $chat): bool;
}
