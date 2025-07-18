<?php

declare(strict_types=1);

namespace App\Feature\Calendar;

use App\Application\Value\Date;
use App\Feature\Agent\Planning\EventManagerAgent;
use App\Feature\Calendar\Input\EventForm;
use App\Feature\Calendar\Internal\CalendarInfoService;
use App\Feature\Chat\Controller as ChatController;
use App\Module\Agent\AgentProvider;
use App\Module\Agent\DateableAgent;
use App\Module\Calendar\Calendar;
use App\Module\Calendar\EventRepository;
use App\Module\Calendar\EventService;
use App\Module\Calendar\Info\Event;
use App\Module\Chat\ChatService;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Router\Annotation\Route;
use Spiral\Views\ViewsInterface;

/**
 * Calendar feature controller.
 */
final class Controller
{
    use PrototypeTrait;

    public const ROUTE_CLOSEST_DATES = 'calendar-closest-dates';
    public const ROUTE_CYCLE_CALENDAR = 'calendar-cycle-calendar';
    public const ROUTE_CYCLE_CALENDAR_CONTENT = 'calendar-cycle-calendar-content';
    public const ROUTE_HELP_AGENT = 'calendar-start-help-agent';
    public const ROUTE_CYCLE_DAY = 'calendar-cycle-day';
    public const ROUTE_CYCLE_AGENT = 'calendar-cycle-agent';
    public const ROUTE_EVENT_CREATE_FORM = 'calendar-event-create-form';
    public const ROUTE_EVENT_CREATE = 'calendar-event-create';
    public const ROUTE_EVENT_EDIT_FORM = 'calendar-event-edit-form';
    public const ROUTE_EVENT_UPDATE = 'calendar-event-update';
    public const ROUTE_EVENT_DELETE = 'calendar-event-delete';

    public function __construct(
        private readonly ViewsInterface $views,
        private readonly Calendar $calendar,
        private readonly ChatService $chatService,
        private readonly CalendarInfoService $calendarInfoService,
        private readonly EventService $eventService,
        private readonly EventRepository $eventRepository,
    ) {}

    #[Route(route: '/calendar/closest-dates', name: self::ROUTE_CLOSEST_DATES, methods: ['GET'])]
    public function closestDates(): string
    {
        $events = $this->calendar->getUpcomingEvents(interval: '1 month');

        return $this->views->render('calendar:closest-dates', [
            'router' => $this->router,
            'events' => $events,
        ]);
    }

    #[Route(route: '/calendar/cycle', name: self::ROUTE_CYCLE_CALENDAR, methods: ['GET'])]
    public function cycleCalendar(): string
    {
        $calendarInfo = $this->calendarInfoService->getCalendarInfo();

        // Return full calendar widget
        return $this->views->render('calendar:cycle-calendar', [
            'router' => $this->router,
            'calendarInfo' => $calendarInfo,
        ]);
    }

    #[Route(route: '/calendar/cycle/<year>/<month>', name: self::ROUTE_CYCLE_CALENDAR_CONTENT, methods: ['POST'])]
    public function cycleCalendarContent(string $year, string $month): string
    {
        $calendarInfo = $this->calendarInfoService->getCalendarInfo((int) $year, (int) $month);

        // Return only the calendar content for HTMX replacement
        return $this->views->render('calendar:cycle-calendar-content', [
            'router' => $this->router,
            'calendarInfo' => $calendarInfo,
        ]);
    }

    #[Route(route: '/calendar/day/<date>', name: self::ROUTE_CYCLE_DAY, methods: ['GET'])]
    public function cycleDay(string $date, AgentProvider $agentProvider): string
    {
        try {
            $dateObj = Date::fromString($date);
        } catch (\Exception $e) {
            return '<div class="alert alert-danger">Неверный формат даты</div>';
        }

        // Get calendar info for the date's month to access cycle data
        $calendarInfo = $this->calendarInfoService->getCalendarInfo($dateObj->year, $dateObj->month);
        $cycleDay = $calendarInfo->getCycleDayForDate($dateObj);

        if (!$cycleDay) {
            return '<div class="alert alert-warning">Нет данных по циклу для выбранной даты</div>';
        }

        return $this->views->render('calendar:day-details', [
            'cycleDay' => $cycleDay,
            'date' => $dateObj,
            'agents' => $agentProvider->getAgentCards(),
        ]);
    }

    #[Route(route: '/calendar/cycle-agent/<agent>/<date>', name: self::ROUTE_CYCLE_AGENT, methods: ['GET'])]
    public function cycleAgent(string $agent, string $date, AgentProvider $agentProvider, ChatController $chats): ResponseInterface
    {
        $date = Date::fromString($date);
        $agent = $agentProvider->buildAgent($agent);
        $agent instanceof DateableAgent and $agent = $agent->forDate($date);

        # Start chat with agent
        $chat = $this->chatService->createChat(
            title: "{$agent::getCard()->name} - {$date->__toString()}",
            agent: $agent,
        );

        return $this->response->redirect(
            $this->router->uri($chats::ROUTE_CHAT, ['uuid' => $chat->uuid]),
        );
    }

    #[Route(route: '/calendar/help-agent/<date>', name: self::ROUTE_HELP_AGENT, methods: ['GET'])]
    public function startHelpAgent(string $date, EventManagerAgent $agent, ChatController $chats): ResponseInterface
    {
        $date = Date::fromString($date);

        $events = $this->calendar->getUpcomingEvents($date);
        $events === [] and throw new \InvalidArgumentException(
            'There are no events for the specified date: ' . $date->__toString(),
        );

        $agent = $agent->withEvent(...$events)->forDate($date);

        # Start chat with agent
        $chat = $this->chatService->createChat(
            title: 'События ' . $date->__toString(),
            agent: $agent,
        );

        return $this->response->redirect(
            $this->router->uri($chats::ROUTE_CHAT, ['uuid' => $chat->uuid]),
        );
    }

    #[Route(route: '/calendar/event/create-form', name: self::ROUTE_EVENT_CREATE_FORM, methods: ['GET'])]
    public function eventCreateForm(): string
    {
        return $this->views->render('calendar:event-form', [
            'router' => $this->router,
            'form' => null,
            'event' => null,
            'action' => $this->router->uri(self::ROUTE_EVENT_CREATE),
        ]);
    }

    #[Route(route: '/calendar/event/create', name: self::ROUTE_EVENT_CREATE, methods: ['POST'])]
    public function eventCreate(EventForm $form): string
    {
        $event = new Event(
            date: $form->date,
            title: $form->title,
            period: $form->period,
            description: $form->description,
        );
        $this->eventService->create($event);

        // Return JavaScript to close modal and refresh widget
        return '<script>
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById("eventModal"));
            if (modal) {
                modal.hide();
            }

            // Refresh closest dates widget
            htmx.ajax("GET", "' . $this->router->uri(self::ROUTE_CLOSEST_DATES) . '", {
                target: "#closest-dates-widget",
                swap: "outerHTML"
            });

            // Show success toast
            if (typeof showToast === "function") {
                showToast("Событие успешно создано", "success");
            }
        </script>';
    }

    #[Route(route: '/calendar/event/<uuid>/edit-form', name: self::ROUTE_EVENT_EDIT_FORM, methods: ['GET'])]
    public function eventEditForm(string $uuid): string
    {
        try {
            $eventUuid = Uuid::fromString($uuid);
        } catch (\Exception $e) {
            return '<div class="alert alert-danger">Неверный формат UUID события</div>';
        }

        $event = $this->eventRepository->findByUuid($eventUuid);

        if (!$event) {
            return '<div class="alert alert-danger">Событие не найдено</div>';
        }

        return $this->views->render('calendar:event-form', [
            'router' => $this->router,
            'form' => null,
            'event' => $event,
            'action' => $this->router->uri(self::ROUTE_EVENT_UPDATE, ['uuid' => $uuid]),
        ]);
    }

    #[Route(route: '/calendar/event/<uuid>/update', name: self::ROUTE_EVENT_UPDATE, methods: ['POST'])]
    public function eventUpdate(string $uuid, EventForm $form): string
    {
        try {
            $eventUuid = Uuid::fromString($uuid);
        } catch (\Exception $e) {
            return '<div class="alert alert-danger">Неверный формат UUID события</div>';
        }

        $existingEvent = $this->eventRepository->findByUuid($eventUuid);

        if (!$existingEvent) {
            return '<div class="alert alert-danger">Событие не найдено</div>';
        }

        $updatedEvent = new Event(
            date: $form->date,
            title: $form->title,
            period: $form->period,
            description: $form->description,
            uuid: $eventUuid,
        );

        $this->eventService->update($updatedEvent);

        // Return JavaScript to close modal and refresh widget
        return '<script>
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById("eventModal"));
            if (modal) {
                modal.hide();
            }

            // Refresh closest dates widget
            htmx.ajax("GET", "' . $this->router->uri(self::ROUTE_CLOSEST_DATES) . '", {
                target: "#closest-dates-widget",
                swap: "outerHTML"
            });

            // Show success toast
            if (typeof showToast === "function") {
                showToast("Событие успешно обновлено", "success");
            }
        </script>';
    }

    #[Route(route: '/calendar/event/<uuid>/delete', name: self::ROUTE_EVENT_DELETE, methods: ['DELETE'])]
    public function eventDelete(string $uuid): string
    {
        try {
            $eventUuid = Uuid::fromString($uuid);
        } catch (\Exception $e) {
            return '<div class="alert alert-danger">Неверный формат UUID события</div>';
        }

        $event = $this->eventRepository->findByUuid($eventUuid);

        if (!$event) {
            return '<div class="alert alert-danger">Событие не найдено</div>';
        }

        $this->eventService->delete($eventUuid);

        // Return updated closest dates widget
        $events = $this->calendar->getUpcomingEvents(interval: '1 month');

        return $this->views->render('calendar:closest-dates', [
            'router' => $this->router,
            'events' => $events,
        ]) . '<script>
            // Show success toast
            if (typeof showToast === "function") {
                showToast("Событие успешно удалено", "success");
            }
        </script>';
    }
}
