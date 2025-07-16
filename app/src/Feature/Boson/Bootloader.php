<?php

declare(strict_types=1);

namespace App\Feature\Boson;

use App\Application\AppScope;
use Boson\Application;
use Boson\ApplicationCreateInfo;
use Boson\Bridge\Psr\Http\Psr7HttpAdapter;
use Boson\WebView\Api\Schemes\Event\SchemeRequestReceived;
use Boson\WebView\WebViewCreateInfo;
use Boson\Window\WindowCreateInfo;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spiral\Boot\Bootloader\Bootloader as SpiralBootloader;
use Spiral\Boot\FinalizerInterface;
use Spiral\Bootloader\Http\HttpBootloader;
use Spiral\Core\BinderInterface;
use Spiral\Core\Container;
use Spiral\Exceptions\ExceptionHandlerInterface;
use Spiral\Framework\Spiral;
use Spiral\Http\Http;

final class Bootloader extends SpiralBootloader
{
    public function defineDependencies(): array
    {
        return [
            HttpBootloader::class,
        ];
    }

    public function init(BinderInterface $binder): void
    {
        $binder = $binder->getBinder(AppScope::Boson);

        $binder->bindSingleton(Application::class, $this->createApplication(...));
    }

    private function createApplication(
        ServerRequestFactoryInterface $factory,
        Container $container,
        ExceptionHandlerInterface $exceptionHandler,
    ): Application {
        $app = new Application(
            new ApplicationCreateInfo(
                schemes: ['app', 'public', 'http'],
                debug: false,
                window: new WindowCreateInfo(
                    title: 'Happy Wife – Happy Life',
                    width: 980,
                    height: 768,
                    resizable: true,
                    webview: new WebViewCreateInfo(
                        contextMenu: true,
                    ),
                ),
            ),
        );

        // Create PSR-7 HTTP adapter
        $psr7 = new Psr7HttpAdapter(
            requestFactory: $factory,
        );
        /** @var \Closure(ServerRequestInterface $request): ResponseInterface $httpHandler */
        $httpHandler = ScopeHandler::create(
            $container,
            Spiral::Http,
            static fn(
                Http $http,
                ExceptionHandlerInterface $exceptionHandler,
                FinalizerInterface $finalizer,
            ) => static function (ServerRequestInterface $request) use (
                $http,
                $exceptionHandler,
                $finalizer,
            ): ?ResponseInterface {
                try {
                    return $http->handle($request);
                } catch (\Throwable $e) {
                    $exceptionHandler->report($e);
                    return null;
                } finally {
                    $finalizer->finalize();
                }
            },
        );

        // Subscribe to receive a request
        $app->on(static function (SchemeRequestReceived $e) use ($psr7, $httpHandler): void {
            $request = $psr7->createRequest($e->request);
            $response = $httpHandler($request);
            $response === null or $e->response = $psr7->createResponse($response);
        });

        return $app;
    }
}
