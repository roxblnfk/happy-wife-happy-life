<?php

declare(strict_types=1);

namespace App\Module\Common\Config;

use App\Module\Config\Attribute\Config;

#[Config(name: 'user-info')]
class UserInfo implements \JsonSerializable, \Stringable
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public string $name,
    ) {}

    #[\Override]
    public function jsonSerialize(): array
    {
        return \get_object_vars($this);
    }

    public function __toString(): string
    {
        return <<<TEXT
            ---
            User Info:
            Name: {$this->name}
            ---
            TEXT;
    }
}
