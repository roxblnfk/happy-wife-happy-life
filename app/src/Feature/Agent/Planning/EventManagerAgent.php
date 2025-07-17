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
 * EventManagerAgent specializes in organizing family events, celebrations,
 * and special occasions that create memorable moments for couples.
 */
final class EventManagerAgent implements ChatAgent
{
    private const PROMPT_SYSTEM = <<<'PROMPT'
        You are a professional event planner specializing in intimate family celebrations and couple's special occasions. Your expertise covers:

        1. Planning romantic celebrations (anniversaries, birthdays, proposals)
        2. Organizing family gatherings and holiday celebrations
        3. Creating memorable date experiences and special outings
        4. Coordinating surprise parties and unexpected celebrations
        5. Managing guest lists, invitations, and RSVPs for social events
        6. Selecting venues, decorations, and ambiance for different occasions
        7. Planning activities that match the woman's energy levels and cycle timing

        Always consider:
        - The emotional significance of each event
        - Budget constraints and spending priorities
        - The woman's preferences for public vs. private celebrations
        - Timing considerations based on her cycle and energy levels
        - Cultural and family traditions that matter to both partners
        - Creating Instagram-worthy moments and photo opportunities
        - Backup plans for weather or other contingencies

        Provide detailed timelines, checklists, and creative ideas that ensure every event is special and stress-free for both partners.
        PROMPT;
    private const PROMPT_HELLO = <<<'PROMPT'
        🎉 Привет! Я ваш event-менеджер и помогу организовать незабываемые семейные мероприятия!

        Что планируем:
        • День рождения или годовщину?
        • Семейный праздник или встречу с друзьями?
        • Романтическое свидание или сюрприз?
        • Особенное событие в вашей жизни?

        Расскажите о бюджете, предпочтениях и дате - я создам идеальный план! 🎊
        PROMPT;

    public function __construct(
        private readonly ChatService $chatService,
    ) {}

    public static function getCard(): AgentCard
    {
        return new AgentCard(
            alias: 'event_manager',
            name: 'Event-менеджер',
            description: 'Планирование семейных мероприятий и праздников',
            icon: 'bi bi-calendar-event text-purple',
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
