<?php

declare(strict_types=1);

namespace App\Module\LLM\Config;

use App\Module\Config\Attribute\Config;

#[Config(name: 'llm')]
class LLMConfig implements \JsonSerializable
{
    /**
     * @param non-empty-string $apiKey
     */
    public function __construct(
        public Platforms $platform,
        public string $apiKey,
        public ?string $model = null,
    ) {}

    #[\Override]
    public function jsonSerialize(): array
    {
        return \get_object_vars($this);
    }
}
