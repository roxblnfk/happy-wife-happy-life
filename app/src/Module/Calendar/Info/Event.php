<?php

declare(strict_types=1);

namespace App\Module\Calendar\Info;

use App\Application\Value\Date;
use Ramsey\Uuid\UuidInterface;

class Event implements \Stringable
{
    public const PERIOD_ANNUAL = '1 year';

    /**
     * Create a new Event instance.
     *
     * @param Date $date The date of the event.
     * @param string $title The title of the event.
     * @param string|null $period The periodicity of the event, if applicable.
     * @param string|null $description A description of the event, if applicable.
     * @param UuidInterface|null $uuid The UUID of the event, if it exists.
     */
    public function __construct(
        public readonly Date $date,
        public readonly string $title,
        public readonly ?string $period = null,
        public readonly ?string $description = null,
        public readonly ?UuidInterface $uuid = null,
    ) {}

    public function getClosestDate(): Date
    {
        return $this->period === null
            ? $this->date
            : $this->date->getClosestPeriodicDate($this->period);
    }

    public function __toString(): string
    {
        return \sprintf('%s on %s', $this->title, $this->date);
    }
}
