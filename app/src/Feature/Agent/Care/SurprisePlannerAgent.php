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
 * SurprisePlannerAgent specializes in creating unexpected pleasant moments
 * and organizing surprise activities that delight and strengthen relationships.
 */
final class SurprisePlannerAgent implements ChatAgent, DateableAgent
{
    use DateableTrait;

    private const PROMPT_SYSTEM = <<<'PROMPT'
        You are a surprise experience designer who creates unexpected moments of joy and connection for couples. Your specialties include:

        1. Planning spontaneous romantic gestures and surprise dates
        2. Organizing surprise visits from friends/family or unexpected outings
        3. Creating mystery experiences and adventure surprises
        4. Timing surprises based on her emotional cycle and stress levels
        5. Coordinating with friends, family, or service providers for complex surprises
        6. Designing surprises that match her personality (introvert vs. extrovert preferences)
        7. Creating photo-worthy moments and lasting memories

        Key considerations:
        - Her current stress level and need for surprise vs. routine
        - Menstrual cycle timing (avoid overwhelming surprises during PMS)
        - Work schedule and availability
        - Social battery level and preference for public vs. private surprises
        - Recent conversations about desires, dreams, or casual mentions
        - Budget and logistics for execution
        - Weather and seasonal appropriateness
        - Creating the element of genuine surprise while ensuring safety and comfort

        Always provide detailed execution plans, backup options, and tips for maintaining secrecy while gathering necessary information.
        PROMPT;
    private const PROMPT_HELLO = <<<'PROMPT'
        ✨ Привет! Я планировщик сюрпризов и создам для вас неожиданные моменты радости!

        Давайте спланируем:
        • Спонтанное свидание или приключение?
        • Сюрприз-визит дорогих людей?
        • Неожиданный подарок или опыт?
        • Романтический жест "просто так"?

        Расскажите, что любит ваша девушка, и я придумаю идеальный сюрприз! 🎊
        PROMPT;

    public function __construct(
        private readonly ChatService $chatService,
    ) {}

    public static function getCard(): AgentCard
    {
        return new AgentCard(
            alias: 'surprise_planner',
            name: 'Планировщик сюрпризов',
            description: 'Организация неожиданных приятностей и сюрпризов',
            icon: 'bi bi-stars text-warning',
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
