<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use Boson\Application;
use Boson\ApplicationCreateInfo;
use Boson\Dispatcher\EventListenerProviderInterface;
use Boson\WebView\WebViewCreateInfo;
use Boson\Window\WindowCreateInfo;
use Monolog\Level;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Http\Middleware\ErrorHandlerMiddleware;
use Spiral\Monolog\Bootloader\MonologBootloader;
use Spiral\Monolog\Config\MonologConfig;

/**
 * The bootloader is responsible for configuring the application specific loggers.
 *
 * @link https://spiral.dev/docs/basics-logging
 */
final class BosonBootloader extends Bootloader
{
    public function defineSingletons(): array
    {
        return [
            EventListenerProviderInterface::class => Application::class,
            Application::class => $this->createApplication(),
        ];
    }

    public function init(MonologBootloader $monolog): void
    {
        // HTTP level errors
        $monolog->addHandler(
            channel: ErrorHandlerMiddleware::class,
            handler: $monolog->logRotate(
                directory('runtime') . 'logs/http.log',
            ),
        );

        // app level errors
        $monolog->addHandler(
            channel: MonologConfig::DEFAULT_CHANNEL,
            handler: $monolog->logRotate(
                filename: directory('runtime') . 'logs/error.log',
                level: Level::Error,
                maxFiles: 25,
                bubble: false,
            ),
        );

        // debug and info messages via global LoggerInterface
        $monolog->addHandler(
            channel: MonologConfig::DEFAULT_CHANNEL,
            handler: $monolog->logRotate(
                filename: directory('runtime') . 'logs/debug.log',
            ),
        );
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
