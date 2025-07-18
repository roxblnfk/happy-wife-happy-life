<?php

declare(strict_types=1);

namespace App\Module\Calendar\Internal\Domain;

use App\Application\Value\Date;
use App\Module\Calendar\Info\Event as EventDTO;
use App\Module\ORM\ActiveRecord;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity as EntityAttribute;
use Cycle\ORM\Entity\Behavior\CreatedAt;
use Cycle\ORM\Entity\Behavior\UpdatedAt;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid7;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Calendar event entity.
 */
#[EntityAttribute(
    table: 'calendar_event',
)]
#[Uuid7('uuid')]
#[CreatedAt('createdAt')]
#[UpdatedAt('updatedAt')]
final class Event extends ActiveRecord
{
    #[Column(type: 'uuid', primary: true, nullable: false, typecast: 'uuid')]
    public UuidInterface $uuid;

    #[Column(type: 'string', nullable: false)]
    public string $title;

    #[Column(type: 'date', nullable: false, typecast: Date::class)]
    public Date $date;

    #[Column(type: 'string', nullable: true)]
    public ?string $period = null;

    #[Column(type: 'text', nullable: true)]
    public ?string $description = null;

    public \DateTimeImmutable $createdAt;
    public \DateTimeImmutable $updatedAt;

    public static function create(
        Date $date,
        string $title,
        ?string $period = null,
        ?string $description = null,
    ): self {
        return self::make([
            'uuid' => Uuid::uuid7(),
            'date' => $date,
            'title' => $title,
            'period' => $period,
            'description' => $description,
            'createdAt' => new \DateTimeImmutable(),
            'updatedAt' => new \DateTimeImmutable(),
        ]);
    }

    /**
     * Convert entity to DTO.
     */
    public function toDTO(): EventDTO
    {
        return new EventDTO(
            date: $this->date,
            title: $this->title,
            period: $this->period,
            description: $this->description,
            uuid: $this->uuid,
        );
    }
}
