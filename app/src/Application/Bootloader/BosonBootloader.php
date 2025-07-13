<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use Boson\Application;
use Boson\ApplicationCreateInfo;
use Boson\Dispatcher\EventListenerProviderInterface;
use Boson\WebView\WebViewCreateInfo;
use Boson\Window\WindowCreateInfo;
use Spiral\Boot\Bootloader\Bootloader;

final class BosonBootloader extends Bootloader
{
    public function defineSingletons(): array
    {
        return [
            EventListenerProviderInterface::class => Application::class,
            Application::class => $this->createApplication(),
        ];
    }

    private function createApplication(): Application
    {
        return new Application(
            new ApplicationCreateInfo(
                schemes: ['app'],
                debug: false,
                window: new WindowCreateInfo(
                    title: 'Happy Wife – Happy Life',
                    width: 800,
                    height: 600,
                    resizable: true,
                    webview: new WebViewCreateInfo(
                        contextMenu: true,
                    ),
                ),
            ),
        );
    }
}
