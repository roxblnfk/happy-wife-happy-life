<?php

declare(strict_types=1);

namespace App\Module\Calendar;

use App\Application\Value\Date;
use App\Module\Calendar\Info\Event;
use App\Module\Common\Config\RelationshipInfo;
use App\Module\Common\Config\WomenInfo;

final class Calendar
{
    public function __construct(
        private readonly RelationshipInfo $relationshipInfo,
        private readonly EventRepository $eventRepository,
        private readonly WomenInfo $womenInfo,
    ) {}

    /**
     * Get the upcoming events from the specified start date to the end of the given interval.
     *
     * @param int|string|\DateInterval $interval The interval to check for upcoming events. Can be a number of days,
     *        a string representing a time interval (e.g., '1 month'), or a \DateInterval object.
     * @param Date|null $startDate The start date from which to check for upcoming events. If null, the current date
     *        will be used.
     *
     * @return list<Event> An array of upcoming events from the specified start date to the end of the interval.
     */
    public function getUpcomingEvents(?Date $startDate = null, int|string|\DateInterval $interval = 0): array
    {
        /** @var list<Event> $events */
        $events = [];
        $this->womenInfo->birthday === null or $events[] = new Event(
            $this->womenInfo->birthday,
            'День рождения спутницы',
            Event::PERIOD_ANNUAL,
        );
        $this->relationshipInfo->anniversary === null or $events[] = new Event(
            $this->relationshipInfo->anniversary,
            'Годовщина свадьбы',
            Event::PERIOD_ANNUAL,
        );

        $startDate ??= Date::today();
        $deadline = $startDate->withInterval($interval);

        /** @var list<array{0: non-empty-string, 1: Event}> $events */
        $result = $this->eventRepository->getUpcomingEvents($startDate, $interval);
        foreach ($events as $event) {
            $closest = $event->getClosestDate();
            $closest->isBetween($startDate, $deadline) and $result[] = [$closest->__toString(), $event];
        }

        \usort($result, static fn(array $a, array $b): int => \strcmp($a[0], $b[0]));

        return \array_column($result, 1);
    }
}
