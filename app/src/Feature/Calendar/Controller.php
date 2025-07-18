<?php

declare(strict_types=1);

namespace App\Feature\Calendar;

use App\Application\Value\Date;
use App\Feature\Agent\Planning\EventManagerAgent;
use App\Feature\Chat\Controller as ChatController;
use App\Module\Calendar\Calendar;
use App\Module\Chat\ChatService;
use Psr\Http\Message\ResponseInterface;
use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Router\Annotation\Route;
use Spiral\Views\ViewsInterface;

/**
 * Calendar feature controller.
 */
final class Controller
{
    use PrototypeTrait;

    public const ROUTE_INDEX = 'calendar';
    public const ROUTE_HELP_AGENT = 'calendar-start-help-agent';

    public function __construct(
        private readonly ViewsInterface $views,
        private readonly Calendar $calendar,
        private readonly ChatService $chatService,
    ) {}

    #[Route(route: '/calendar/closest-dates', name: self::ROUTE_INDEX, methods: ['GET'])]
    public function closestDates(): string
    {
        $events = $this->calendar->getUpcomingEvents(interval: '1 month');

        return $this->views->render('calendar:index', [
            'router' => $this->router,
            'events' => $events,
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
