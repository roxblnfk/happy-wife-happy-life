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
        private readonly WomenInfo $womenInfo,
    ) {}

    /**
     * Get the upcoming events.
     *
     * @return array<non-empty-string, Event> An array of upcoming events within the specified interval with keys as
     *         closest dates in 'Y-m-d' format and values as Event objects.
     */
    public function getUpcomingEvents(int|string|\DateInterval $interval): array
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

        $today = Date::today();
        $deadline = $today->withInterval($interval);

        $result = [];
        foreach ($events as $event) {
            $closest = $event->getClosestDate();
            $closest->isBetween($today, $deadline) and $result[$closest->__toString()] = $event;
        }

        \ksort($result);

        return $result;
    }
}
