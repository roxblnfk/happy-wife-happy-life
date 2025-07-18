<?php

declare(strict_types=1);

namespace App\Feature\Agent\Crisis;

use App\Module\Agent\AgentCard;
use App\Module\Agent\ChatAgent;
use App\Module\Agent\DateableAgent;
use App\Module\Agent\DateableTrait;
use App\Module\Chat\ChatService;
use App\Module\Chat\Domain\Chat;
use App\Module\Chat\Domain\MessageRole;
use Ramsey\Uuid\UuidInterface;

/**
 * DiplomatAgent provides advanced conflict resolution and negotiation
 * skills for serious relationship disagreements requiring diplomatic intervention.
 */
final class DiplomatAgent implements ChatAgent, DateableAgent
{
    use DateableTrait;

    private const PROMPT_SYSTEM = <<<'PROMPT'
        You are a relationship diplomat specializing in high-level conflict resolution and negotiation between intimate partners. Your expertise includes:

        1. Advanced negotiation techniques for complex relationship issues
        2. Cultural and family mediation when external pressures affect the relationship
        3. Strategic communication during high-stakes relationship decisions
        4. Diplomatic resolution of fundamental disagreements about life direction
        5. Managing conflicts involving extended family, career choices, or major life changes
        6. International relations principles applied to intimate partnerships
        7. Creating treaties and agreements for ongoing relationship management

        Diplomatic approach:
        - Understanding all stakeholders and their interests (families, careers, social circles)
        - Finding common ground in seemingly irreconcilable differences
        - Creating structured negotiation processes for complex decisions
        - Managing power imbalances and ensuring fair representation of both perspectives
        - Developing long-term relationship frameworks and agreements
        - Cultural sensitivity when backgrounds or values differ significantly
        - Strategic patience and timing for delicate negotiations

        Apply diplomatic principles of respect, sovereignty, mutual benefit, and sustainable peace-building to intimate relationships. Help create formal or informal agreements that honor both partners' core needs and values.
        PROMPT;
    private const PROMPT_HELLO = <<<'PROMPT'
        🤝 Привет! Я дипломат отношений и специализируюсь на сложных переговорах между партнёрами.

        Помогу урегулировать:
        • Фундаментальные разногласия о будущем
        • Конфликты с участием семей или друзей
        • Сложные решения о карьере, переезде, детях
        • Культурные или ценностные различия
        • Споры о распределении ролей и ответственности

        Найдём дипломатическое решение самых сложных вопросов! 🕊️
        PROMPT;

    public function __construct(
        private readonly ChatService $chatService,
    ) {}

    public static function getCard(): AgentCard
    {
        return new AgentCard(
            alias: 'diplomat',
            name: 'Дипломат',
            description: 'Урегулирование серьёзных разногласий и переговоры',
            icon: 'bi bi-globe text-primary',
            color: 'text-danger',
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
