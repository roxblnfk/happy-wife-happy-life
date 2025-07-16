<?php

declare(strict_types=1);

namespace App\Module\Chat;

use App\Application\AppScope;
use App\Module\Chat\Internal\StreamCache;
use Spiral\Boot\Bootloader\Bootloader as BaseBootloader;
use Spiral\Core\BinderInterface;
use Spiral\Core\Config\Proxy;

final class ChatBootloader extends BaseBootloader
{
    public function init(
        BinderInterface $binder,
    ): void {
        $binder->bind(StreamCache::class, new Proxy(
            StreamCache::class,
            singleton: false,
            fallbackFactory: static fn() => new StreamCache\FileCache(directory('runtime') . '/chat'),
        ));

        $binder->getBinder(AppScope::Boson)->bind(
            StreamCache::class,
            new StreamCache\ArrayCache(),
        );
    }
}
