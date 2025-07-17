<?php

declare(strict_types=1);

namespace App\Module\Common\Config;

use App\Application\Value\Date;
use App\Module\Config\Attribute\Config;

#[Config(name: 'relationship-info')]
class RelationshipInfo implements \JsonSerializable, \Stringable
{
    public function __construct(
        public RelationType $relationType,
        public ?Date $anniversary = null,
        public string $description = '',
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
            Relationship Info:
            Type: {$this->relationType->value};
            Anniversary date: {$this->anniversary};
            Description: {$this->description}
            ---
            TEXT;
    }
}
