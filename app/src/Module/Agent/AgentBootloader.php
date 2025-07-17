<?php

declare(strict_types=1);

namespace App\Module\Agent;

use App\Module\Agent\Internal\AgentRegistry;
use Spiral\Boot\Bootloader\Bootloader as SpiralBootloader;
use Spiral\Tokenizer\TokenizerListenerRegistryInterface;

final class AgentBootloader extends SpiralBootloader
{
    public function defineSingletons(): array
    {
        return [
            AgentProvider::class => AgentRegistry::class,
        ];
    }

    public function init(
        TokenizerListenerRegistryInterface $listenerRegistry,
        AgentRegistry $listener,
    ): void {
        $listenerRegistry->addListener($listener);
    }
}
