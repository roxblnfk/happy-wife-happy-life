<?php

declare(strict_types=1);

namespace App\Module\Calendar;

use App\Module\Calendar\Info\Event as EventDTO;
use App\Module\Calendar\Internal\Domain\Event as EventEntity;
use Ramsey\Uuid\UuidInterface;

final class EventService
{
    /**
     * Create a new event from DTO.
     */
    public function create(EventDTO $event): EventDTO
    {
        $entity = EventEntity::create(
            date: $event->date,
            title: $event->title,
            period: $event->period,
            description: $event->description,
        );
        $entity->saveOrFail();
        return $entity->toDTO();
    }

    /**
     * Update an existing event from DTO.
     */
    public function update(EventDTO $event): EventDTO
    {
        $event->uuid === null and throw new \InvalidArgumentException('Event UUID is required for update operation.');

        $entity = EventEntity::findByPK($event->uuid) ?? throw new \RuntimeException(
            "Event with UUID {$event->uuid->toString()} not found.",
        );

        $entity->date = $event->date;
        $entity->title = $event->title;
        $entity->period = $event->period;
        $entity->description = $event->description;

        $entity->saveOrFail();

        return $entity->toDTO();
    }

    /**
     * Delete an event by UUID.
     */
    public function delete(UuidInterface|EventDTO $event): void
    {
        $event instanceof EventDTO and $event = $event->uuid ?? throw new \InvalidArgumentException(
            'Event UUID is required for delete operation',
        );

        $entity = EventEntity::findByPK($event) ?? throw new \RuntimeException(
            "Event with UUID $event not found",
        );
        $entity->deleteOrFail();
    }
}
