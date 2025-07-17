<?php

declare(strict_types=1);

namespace App\Module\Common\Config;

use App\Application\Value\Date;
use App\Module\Config\Attribute\Config;

#[Config(name: 'women-info')]
class WomenInfo implements \JsonSerializable, \Stringable
{
    public function __construct(
        public string $name,
        public ?Date $birthday = null,

        /**
         * @var string Preferences, likes, and interests.
         */
        public string $preferences = '',

        /**
         * @var string What can upset or anger.
         */
        public string $triggers = '',
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
            Partner Info (women):
            Name: {$this->name};
            Birthday: {$this->birthday}
            ---
            TEXT;
    }
}
