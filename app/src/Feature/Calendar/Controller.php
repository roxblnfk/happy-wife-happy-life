<?php

declare(strict_types=1);

namespace App\Feature\Calendar;

use App\Module\Calendar\Calendar;
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

    public function __construct(
        private readonly ViewsInterface $views,
        private readonly Calendar $calendar,
    ) {}

    #[Route(route: '/calendar/closest-dates', name: self::ROUTE_INDEX, methods: ['GET'])]
    public function closestDates(): string
    {
        $events = $this->calendar->getUpcomingEvents('1 month');

        return $this->views->render('calendar:index', [
            'router' => $this->router,
            'events' => $events,
        ]);
    }
}
