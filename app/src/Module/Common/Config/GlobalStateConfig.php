<?php

declare(strict_types=1);

namespace App\Module\Common\Config;

use App\Module\Config\Attribute\Config;

#[Config(name: 'global_state')]
class GlobalStateConfig implements \JsonSerializable
{
    public function __construct(
        public bool $configured = false,
    ) {}

    #[\Override]
    public function jsonSerialize(): array
    {
        return \get_object_vars($this);
    }
}
