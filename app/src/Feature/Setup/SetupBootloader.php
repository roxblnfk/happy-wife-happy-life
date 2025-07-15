<?php

declare(strict_types=1);

namespace App\Feature\Setup;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Views\Bootloader\ViewsBootloader;

final class SetupBootloader extends Bootloader
{
    public function defineDependencies(): array
    {
        return [
            ViewsBootloader::class,
        ];
    }

    public function boot(ViewsBootloader $views): void
    {
        $views->addDirectory('setup', __DIR__ . '/views');
    }
}
