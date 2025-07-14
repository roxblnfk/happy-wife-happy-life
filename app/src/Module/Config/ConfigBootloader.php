<?php

declare(strict_types=1);

namespace App\Module\Config;

use App\Module\Config\Internal\ConfigRegistry;
use Spiral\Boot\Bootloader\Bootloader as BaseBootloader;
use Spiral\Core\BinderInterface;
use Spiral\Tokenizer\Bootloader\TokenizerListenerBootloader;
use Spiral\Tokenizer\TokenizerListenerRegistryInterface;

final class ConfigBootloader extends BaseBootloader
{
    public function defineDependencies(): array
    {
        return [
            TokenizerListenerBootloader::class,
        ];
    }

    public function init(
        TokenizerListenerRegistryInterface $listenerRegistry,
        ConfigRegistry $listener,
    ): void {
        $listenerRegistry->addListener($listener);
    }

    public function boot(BinderInterface $binder, ConfigRegistry $configs): void
    {
        foreach ($configs->getConfigs() as $config) {
            $binder->bind($config, static fn(ConfigService $provider): ?object => $provider->getConfig($config));
        }
    }
}
