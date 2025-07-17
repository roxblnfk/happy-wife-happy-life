<?php

declare(strict_types=1);

namespace App\Feature\Agent\Relationship;

use App\Module\Agent\AgentCard;
use App\Module\Agent\ChatAgent;
use App\Module\Chat\ChatService;
use App\Module\Chat\Domain\Chat;
use App\Module\Chat\Domain\MessageRole;
use Ramsey\Uuid\UuidInterface;

/**
 * DisputeMediatorAgent helps resolve conflicts and disagreements
 * in relationships through neutral mediation and communication guidance.
 */
final class DisputeMediatorAgent implements ChatAgent
{
    private const PROMPT_SYSTEM = <<<'PROMPT'
You are a neutral relationship mediator specializing in helping couples resolve conflicts constructively. Your approach focuses on:

1. Providing neutral, unbiased perspective on relationship disputes
2. Teaching effective communication techniques during disagreements
3. Helping both partners understand each other's perspectives
4. Identifying underlying needs and concerns behind surface conflicts
5. Suggesting compromise solutions that honor both partners' needs
6. Timing conflict resolution based on emotional readiness
7. Preventing escalation and promoting de-escalation techniques

Key mediation principles:
- Maintaining neutrality while validating both perspectives
- Focusing on the issue, not attacking character
- Teaching "I" statements vs. "you" accusations
- Helping identify core needs vs. positional demands
- Encouraging active listening and empathy
- Finding win-win solutions rather than winners and losers
- Addressing timing - when to discuss vs. when to cool down

Always provide specific scripts for difficult conversations, de-escalation techniques, and guidance on creating safe spaces for honest communication.
PROMPT;
    private const PROMPT_HELLO = <<<'PROMPT'
⚖️ Привет! Я посредник в спорах и помогу найти мирное решение ваших разногласий.

Давайте разберём:
• В чём суть конфликта?
• Какие потребности у каждого из вас?
• Что уже пробовали для решения?
• Готовы ли оба к конструктивному диалогу?

Найдём компромисс, который укрепит ваши отношения! 🤝
PROMPT;

    public function __construct(
        private readonly ChatService $chatService,
    ) {}

    public static function getCard(): AgentCard
    {
        return new AgentCard(
            alias: 'dispute_mediator',
            name: 'Посредник в спорах',
            description: 'Нейтральная помощь в разрешении конфликтов',
            icon: 'bi bi-peace text-success',
            color: 'text-warning',
        );
    }

    public function chatInit(Chat $chat): void
    {
        $this->chatService->sendMessage(
            $chat,
            self::PROMPT_SYSTEM,
            role: MessageRole::System,
        );
        $this->chatService->sendMessage(
            $chat,
            self::PROMPT_HELLO,
            role: MessageRole::Agent,
        );
    }

    public function chatProcess(Chat $chat, UuidInterface $messageUuid): void {}

    public function canHandle(Chat $chat): bool
    {
        return false;
    }
}
