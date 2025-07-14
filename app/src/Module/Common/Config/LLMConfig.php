<?php

declare(strict_types=1);

namespace App\Module\Common\Config;

use App\Module\Config\Attribute\Config;

#[Config(name: 'llm')]
class LLMConfig implements \JsonSerializable
{
    /**
     * @param non-empty-string $token
     */
    public function __construct(
        public LLMProvider $provider,
        public string $token,
    ) {}

    #[\Override]
    public function jsonSerialize(): array
    {
        return \get_object_vars($this);
    }
}
