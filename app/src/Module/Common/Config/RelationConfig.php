<?php

declare(strict_types=1);

namespace App\Module\Common\Config;

use App\Module\Config\Attribute\Config;

#[Config(name: 'user')]
class RelationConfig implements \JsonSerializable
{
    /**
     * @param non-empty-string $userName
     * @param non-empty-string $womanName
     */
    public function __construct(
        public string $userName,
        public string $womanName,
        public RelationType $relationType,
    ) {}

    #[\Override]
    public function jsonSerialize(): array
    {
        return \get_object_vars($this);
    }
}
