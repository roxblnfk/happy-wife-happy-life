<?php

declare(strict_types=1);

namespace App\Module\Calendar;

use App\Application\Value\Date;
use App\Module\Calendar\Info\Event;
use App\Module\Calendar\Internal\Domain\Event as EventEntity;
use Ramsey\Uuid\UuidInterface;

final class EventRepository
{
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
        $startDate ??= Date::today();
        $deadline = $startDate->withInterval($interval);

        $events = EventEntity::query()
            ->orderBy('date', 'ASC')
            ->where('date', '>=', $startDate->__toString())
            ->where('date', '<=', $deadline->__toString())
            ->fetchAll();

        return $this->mapArray($events);
    }

    /**
     * Get all events.
     *
     * @return array<Event> An array of all events.
     */
    public function getAll(): array
    {
        return $this->mapArray(EventEntity::query()->orderBy('date', 'ASC')->fetchAll());
    }

    /**
     * Find an event by UUID.
     *
     * @param UuidInterface $uuid The UUID of the event to find.
     * @return Event|null The event DTO if found, null otherwise.
     */
    public function findByUuid(UuidInterface $uuid): ?Event
    {
        $entity = EventEntity::findByPK($uuid);

        return $entity?->toDTO();
    }

    /**
     * Map an array of EventEntity objects to an array of Event DTOs.
     *
     * @param array<EventEntity> $entities The array of EventEntity objects to map.
     * @return array<Event> An array of Event DTOs.
     */
    private function mapArray(array $entities): array
    {
        return \array_map(
            static fn(EventEntity $entity): Event => $entity->toDTO(),
            $entities,
        );
    }
}
