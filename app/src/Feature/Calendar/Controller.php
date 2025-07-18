<?php

declare(strict_types=1);

namespace App\Feature\Calendar;

use App\Application\Value\Date;
use App\Feature\Agent\Planning\EventManagerAgent;
use App\Feature\Calendar\Internal\CalendarInfoService;
use App\Feature\Chat\Controller as ChatController;
use App\Module\Calendar\Calendar;
use App\Module\Chat\ChatService;
use Psr\Http\Message\ResponseInterface;
use Spiral\Http\Request\InputManager;
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

    public function __construct(
        private readonly ViewsInterface $views,
        private readonly Calendar $calendar,
        private readonly ChatService $chatService,
        private readonly CalendarInfoService $calendarInfoService,
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
    public function cycleDay(string $date): string
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
        ]);
    }

    #[Route(route: '/calendar/help-agent/<date>', name: self::ROUTE_HELP_AGENT, methods: ['GET'])]
    public function startHelpAgent(string $date, EventManagerAgent $agent, ChatController $chats): ResponseInterface
    {
        $date = Date::fromString($date);

        $events = $this->calendar->getUpcomingEvents($date);
        $events === [] and throw new \InvalidArgumentException(
            'There are no events for the specified date: ' . $date->__toString(),
        );

        $agent = $agent->withEvent(...$events);

        # Start chat with agent
        $chat = $this->chatService->createChat(
            title: 'События ' . $date->__toString(),
            agent: $agent,
        );

        return $this->response->redirect(
            $this->router->uri($chats::ROUTE_CHAT, ['uuid' => $chat->uuid]),
        );
    }
}
