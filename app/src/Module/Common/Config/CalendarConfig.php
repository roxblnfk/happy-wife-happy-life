<?php

declare(strict_types=1);

namespace App\Module\Common\Config;

use App\Module\Config\Attribute\Config;

#[Config(name: 'calendar')]
class CalendarConfig implements \JsonSerializable
{
    public function __construct() {}

    #[\Override]
    public function jsonSerialize(): array
    {
        return \get_object_vars($this);
    }
}
