<?php

declare(strict_types=1);

namespace App\Module\Config\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Config
{
    /**
     * @param non-empty-string $name Configuration name.
     */
    public function __construct(
        public string $name,
    ) {}
}
