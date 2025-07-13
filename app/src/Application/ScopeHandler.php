<?php

declare(strict_types=1);

namespace App\Application;

use Spiral\Core\Container;
use Spiral\Core\Scope;

/**
 * @template TRequest
 * @template-covariant TResponse of object
 */
final class ScopeHandler
{
    private function __construct(
        private readonly \Fiber $fiber,
    ) {}

    /**
     * @template TReq
     * @template TRes
     *
     * @param Container $container Core container.
     * @param string|\BackedEnum|null $scope Container scope name.
     * @param \Closure(mixed ...): (callable(TReq): TRes) $factory Handler factory that returns an instance of a handler
     * @param \Closure(\Throwable): TRes|null $errorHandler Optional error handler that will be called if an exception is thrown
     *
     * @return static<TReq, TRes>
     */
    public static function create(
        Container $container,
        string|\BackedEnum|null $scope,
        \Closure $factory,
        ?\Closure $errorHandler = null,
    ): self {
        $fiber = new \Fiber(static function (
            Container $container,
            string|\BackedEnum|null $scope,
            \Closure $factory,
            ?\Closure $errorHandler,
        ): void {
            $container->runScope(
                new Scope(
                    name: $scope,
                ),
                static function (Container $container) use ($factory, $errorHandler): void {
                    $handler = $container->invoke($factory);
                    $response = null;
                    while (true) {
                        $request = \Fiber::suspend($response);
                        try {
                            $response = $handler($request);
                        } catch (\Throwable $e) {
                            if ($errorHandler === null) {
                                throw $e;
                            }

                            $response = $errorHandler($e);
                        }
                    }
                },
            );
        });

        $fiber->start($container, $scope, $factory, $errorHandler);

        return new self($fiber);
    }

    /**
     * Handles a request and returns a response.
     *
     * Sends the request to the handler and waits for a response.
     *
     * @param TRequest $request The request object.
     * @return TResponse The response object.
     */
    public function __invoke(mixed $request): mixed
    {
        return $this->fiber->resume($request);
    }
}
