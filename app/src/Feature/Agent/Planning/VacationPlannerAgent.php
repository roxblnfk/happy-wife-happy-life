<?php

declare(strict_types=1);

namespace App\Feature\Agent\Planning;

use App\Module\Agent\AgentCard;
use App\Module\Agent\ChatAgent;
use App\Module\Agent\DateableAgent;
use App\Module\Agent\DateableTrait;
use App\Module\Chat\ChatService;
use App\Module\Chat\Domain\Chat;
use App\Module\Chat\Domain\MessageRole;
use Ramsey\Uuid\UuidInterface;

/**
 * VacationPlannerAgent helps couples plan their vacations together,
 * considering preferences, budget, timing, and special occasions.
 */
final class VacationPlannerAgent implements ChatAgent, DateableAgent
{
    use DateableTrait;

    private const PROMPT_SYSTEM = <<<'PROMPT'
        You are an expert vacation and travel planner specializing in romantic getaways and couple trips. Your role is to help men plan perfect vacations with their partners by:

        1. Understanding both partners' preferences, interests, and travel styles
        2. Considering the woman's menstrual cycle for optimal timing
        3. Suggesting destinations that match mood, season, and relationship goals
        4. Planning romantic activities and special moments during the trip
        5. Managing budget constraints while maximizing experience value
        6. Coordinating logistics like accommodation, transportation, and reservations
        7. Creating backup plans for weather or other contingencies

        Always ask about:
        - Travel dates and flexibility
        - Budget range and spending priorities
        - Preferred activities (relaxation, adventure, culture, etc.)
        - Accommodation preferences
        - Any special occasions to celebrate
        - Health considerations and cycle timing
        - Previous travel experiences together

        Provide detailed, actionable recommendations with specific suggestions for restaurants, activities, and romantic touches that will make the trip memorable.
        PROMPT;
    private const PROMPT_HELLO = <<<'PROMPT'
        🏖️ Привет! Я ваш планировщик отпусков и готов помочь организовать идеальное путешествие для вас двоих!

        Расскажите мне:
        • Куда и когда планируете поехать?
        • Какой у вас бюджет?
        • Что больше нравится вашей спутнице - активный отдых или релакс?
        • Есть ли особые даты или события, которые хотите отметить в поездке?

        Вместе мы создадим незабываемый отпуск! ✈️
        PROMPT;

    public function __construct(
        private readonly ChatService $chatService,
    ) {}

    public static function getCard(): AgentCard
    {
        return new AgentCard(
            alias: 'vacation_planner',
            name: 'Планировщик отпусков',
            description: 'Организация совместного отдыха и романтических путешествий',
            icon: 'bi bi-airplane text-info',
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
