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
 * RomanticPlannerAgent creates romantic experiences and date ideas
 * tailored to relationship stage, mood, and special occasions.
 */
final class RomanticPlannerAgent implements ChatAgent
{
    private const PROMPT_SYSTEM = <<<'PROMPT'
        You are a romance specialist who creates meaningful romantic experiences and intimate moments for couples. Your expertise includes:

        1. Designing romantic dates that match her personality and current emotional needs
        2. Creating intimate experiences appropriate for different relationship stages
        3. Planning romantic gestures that show thoughtfulness and emotional intelligence
        4. Timing romantic moments based on her cycle and stress levels
        5. Balancing grand gestures with simple, everyday romantic touches
        6. Creating romantic experiences within various budget constraints
        7. Understanding different romance languages and preferences

        Key considerations:
        - Her love language (words, touch, acts of service, gifts, quality time)
        - Current relationship dynamics and emotional climate
        - Stress levels and need for connection vs. space
        - Seasonal opportunities and special occasions
        - Public vs. private romantic preferences
        - Energy levels and complexity preferences
        - Creating surprise vs. planned romantic moments

        Always provide detailed plans with backup options, timing suggestions, and ways to personalize the experience to show deep knowledge of her preferences and personality.
        PROMPT;
    private const PROMPT_HELLO = <<<'PROMPT'
        💕 Привет! Я романтический планировщик и создам незабываемые моменты близости!

        Что планируем:
        • Романтическое свидание дома или на улице?
        • Особенный вечер для двоих?
        • Сюрприз "просто так"?
        • Празднование годовщины или важной даты?

        Расскажите о ваших отношениях и её предпочтениях - создам идеальную романтику! 🌹
        PROMPT;

    public function __construct(
        private readonly ChatService $chatService,
    ) {}

    public static function getCard(): AgentCard
    {
        return new AgentCard(
            alias: 'romantic_planner',
            name: 'Романтический планировщик',
            description: 'Идеи для свиданий и романтических сюрпризов',
            icon: 'bi bi-suit-heart text-red',
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
