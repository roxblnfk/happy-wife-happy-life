<?php

declare(strict_types=1);

namespace App\Feature\Index;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Views\Bootloader\ViewsBootloader;

final class IndexBootloader extends Bootloader
{
    public function defineDependencies(): array
    {
        return [
            ViewsBootloader::class,
        ];
    }

    public function boot(ViewsBootloader $views): void
    {
        $views->addDirectory('index', __DIR__ . '/views');
    }
}
