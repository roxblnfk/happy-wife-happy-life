<?php

declare(strict_types=1);

namespace App\Feature\Agent\Planning;

use App\Application\Value\Date;
use App\Module\Agent\AgentCard;
use App\Module\Agent\ChatAgent;
use App\Module\Calendar\Info\Event;
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

    /** @var array<Event> */
    private array $events = [];

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
        # Build System prompt
        $this->chatService->sendMessage(
            $chat,
            $this->buildSystemPrompt(),
            role: MessageRole::System,
        );
        $this->chatService->sendMessage(
            $chat,
            $this->buildGreetingMessage(),
            role: MessageRole::Agent,
        );
    }

    public function chatProcess(Chat $chat, UuidInterface $messageUuid): void {}

    public function canHandle(Chat $chat): bool
    {
        return false;
    }

    public function withEvent(Event ...$events): self
    {
        $self = clone $this;

        foreach ($events as $event) {
            $closest = $event->getClosestDate();
            $self->events[$closest->__toString()] = $event;
        }

        return $self;
    }

    private function buildSystemPrompt(): string
    {
        if ($this->events === []) {
            return self::PROMPT_SYSTEM;
        }

        $eventsList = [];
        $i = 1;
        $today = Date::today();
        foreach ($this->events as $event) {
            # Prepare event details
            $closest = $event->getClosestDate()->__toString();
            $period = $event->period ?? 'One-time event';
            $start = $event->date->__toString() === $closest
                ? "- It's the first occurrence of this event."
                : "- Start date: {$event->date}";
            $description = "- Description: $event->description" ?? '';
            $daysTo = $today->daysTo($event->getClosestDate());

            # Format the event details for Markdown
            $eventsList[] = <<<MARKDOWN
                $i. **{$event->title}** on *{$closest}*:
                - Days until: {$daysTo} days.
                $start
                - Period: {$period}.
                $description
                MARKDOWN;
            ++$i;
        }

        $eventsList = \implode("\n", $eventsList);

        return self::PROMPT_SYSTEM . <<<MARKDOOWN
            ---

            We are planning the following events:

            {$eventsList}
            MARKDOOWN;

    }

    private function buildGreetingMessage(): string
    {
        $baseGreeting = <<<'GREETING'
            🎉 Привет! Я ваш event-менеджер и помогу организовать незабываемые семейные мероприятия!
            GREETING;

        if ($this->events === []) {
            return $baseGreeting . <<<'GREETING'

                Что планируем:
                • День рождения или годовщину?
                • Семейный праздник или встречу с друзьями?
                • Романтическое свидание или сюрприз?
                • Особенное событие в вашей жизни?

                Расскажите о бюджете, предпочтениях и дате - я создам идеальный план! 🎊
                GREETING;
        }

        $eventsInfo = [];
        $today = Date::today();
        foreach ($this->events as $event) {
            $closest = $event->getClosestDate();
            $daysUntil = $today->daysTo($closest);

            if ($daysUntil === 0) {
                $timing = "сегодня";
            } elseif ($daysUntil === 1) {
                $timing = "завтра";
            } elseif ($daysUntil <= 7) {
                $timing = "через {$daysUntil} дн.";
            } elseif ($daysUntil <= 30) {
                $timing = "через " . \ceil($daysUntil / 7) . " нед.";
            } else {
                $timing = $closest->__toString();
            }

            $eventsInfo[] = "• **{$event->title}** ({$timing})";
        }

        $eventsText = \implode("\n", $eventsInfo);

        return $baseGreeting . <<<GREETING

            Вижу у вас запланированы важные события:
            {$eventsText}

            Давайте обсудим детали организации! Расскажите о ваших предпочтениях, бюджете и особых пожеланиях - я помогу создать идеальный план для каждого мероприятия! 🎊
            GREETING;
    }
}
