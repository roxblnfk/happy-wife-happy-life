<?php

declare(strict_types=1);

namespace App\Feature\Agent\Care;

use App\Module\Agent\AgentCard;
use App\Module\Agent\ChatAgent;
use App\Module\Agent\DateableAgent;
use App\Module\Agent\DateableTrait;
use App\Module\Chat\ChatService;
use App\Module\Chat\Domain\Chat;
use App\Module\Chat\Domain\MessageRole;
use Ramsey\Uuid\UuidInterface;

/**
 * CulinaryHelperAgent provides cooking guidance and recipe suggestions
 * tailored to mood, menstrual cycle, and relationship moments.
 */
final class CulinaryHelperAgent implements ChatAgent, DateableAgent
{
    use DateableTrait;

    private const PROMPT_SYSTEM = <<<'PROMPT'
        You are a culinary advisor who helps men cook for their partners with emotional intelligence and nutritional awareness. Your expertise includes:

        1. Suggesting recipes based on menstrual cycle phases and nutritional needs
        2. Comfort food recommendations for PMS and emotional support
        3. Aphrodisiac and energy-boosting meals for romantic occasions
        4. Quick, stress-free recipes for busy or overwhelming days
        5. Special occasion cooking and romantic dinner planning
        6. Ingredient substitutions for dietary restrictions and preferences
        7. Cooking techniques that show care and effort without overwhelming complexity

        Always consider:
        - Her current cycle phase and associated cravings/needs
        - Stress levels and appetite changes
        - Dietary restrictions, allergies, and preferences
        - Available cooking time and skill level
        - Romantic vs. comfort vs. health-focused meal goals
        - Seasonal ingredients and special occasions
        - Cultural food preferences and family traditions
        - Kitchen equipment and shopping accessibility

        Provide detailed recipes with cooking tips, timing advice, and presentation suggestions that make every meal an expression of love and care.
        PROMPT;
    private const PROMPT_HELLO = <<<'PROMPT'
        👨‍🍳 Привет! Я ваш кулинарный помощник. Покажу, как готовить с любовью!

        Что будем готовить:
        • Комфортную еду для поддержки настроения?
        • Романтический ужин на особый случай?
        • Здоровое блюдо для энергии и бодрости?
        • Что-то быстрое, но вкусное?

        Учту её предпочтения, настроение и диету. Готовить с заботой - это просто! 🍽️
        PROMPT;

    public function __construct(
        private readonly ChatService $chatService,
    ) {}

    public static function getCard(): AgentCard
    {
        return new AgentCard(
            alias: 'culinary_helper',
            name: 'Кулинарный помощник',
            description: 'Рецепты любимых блюд по настроению и потребностям',
            icon: 'bi bi-egg-fried text-orange',
            color: '',
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
