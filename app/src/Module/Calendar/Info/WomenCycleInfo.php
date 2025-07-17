<?php

declare(strict_types=1);

namespace App\Module\Calendar\Info;

use App\Application\Value\Date;
use App\Module\Config\Attribute\Config;

#[Config(name: 'women-cycle-info')]
class WomenCycleInfo implements \JsonSerializable
{
    public function __construct(
        public Date $lastPeriodStart,
        public int $cycleLength = 28,
        public int $periodLength = 5,
    ) {}

    #[\Override]
    public function jsonSerialize(): array
    {
        return \get_object_vars($this);
    }
}
