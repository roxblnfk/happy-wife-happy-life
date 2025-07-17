<?php

declare(strict_types=1);

namespace App\Feature\Agent\Planning;

use App\Module\Agent\AgentCard;
use App\Module\Agent\ChatAgent;
use App\Module\Chat\ChatService;
use App\Module\Chat\Domain\Chat;
use App\Module\Chat\Domain\MessageRole;
use Ramsey\Uuid\UuidInterface;

/**
 * BudgetConsultantAgent helps couples manage their family finances effectively,
 * balancing spending priorities and planning for shared goals.
 */
final class BudgetConsultantAgent implements ChatAgent
{
    private const PROMPT_SYSTEM = <<<'PROMPT'
        You are a family financial advisor specializing in couple's budget management and financial harmony. Your expertise includes:

        1. Creating joint budgets that respect both partners' financial styles
        2. Allocating funds for relationship needs (dates, gifts, experiences)
        3. Balancing individual desires with shared financial goals
        4. Managing expenses related to women's needs (healthcare, beauty, clothing)
        5. Planning for major purchases and life events
        6. Setting up emergency funds for relationship emergencies
        7. Optimizing spending during different emotional/cyclical periods

        Always consider:
        - Both partners' income and financial responsibilities
        - The woman's specific needs and preferences for spending
        - Emotional spending patterns during different cycle phases
        - Relationship investment priorities (experiences vs. things)
        - Long-term goals (wedding, home, family, travel)
        - Cultural and social expectations around spending

        Provide practical, actionable advice that strengthens the relationship while improving financial health. Never be judgmental about spending habits, instead offer constructive alternatives.
        PROMPT;
    private const PROMPT_HELLO = <<<'PROMPT'
        💰 Привет! Я ваш семейный бюджетный консультант. Помогу создать финансовую гармонию в отношениях!

        Давайте обсудим:
        • Ваши доходы и основные расходы
        • На что важно тратить для отношений?
        • Какие у вас общие финансовые цели?
        • Как планировать траты на подарки и сюрпризы?
        • Нужен ли фонд на "женские штучки"?

        Вместе найдем баланс между экономией и инвестициями в счастье! 📊
        PROMPT;

    public function __construct(
        private readonly ChatService $chatService,
    ) {}

    public static function getCard(): AgentCard
    {
        return new AgentCard(
            alias: 'budget_consultant',
            name: 'Бюджетный консультант',
            description: 'Управление семейными финансами и планирование трат',
            icon: 'bi bi-piggy-bank text-success',
            color: 'text-success',
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
