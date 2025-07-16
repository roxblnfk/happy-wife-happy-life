<?php

declare(strict_types=1);

namespace App\Application\Process;

use Spiral\Core\Attribute\Finalize;
use Spiral\Core\Attribute\Singleton;
use Spiral\Exceptions\ExceptionHandlerInterface;

/**
 * Handler for managing process execution.
 */
#[Singleton]
#[Finalize('finalize')]
final class Process
{
    /** @var array<\Generator> */
    private array $handlers = [];

    public function __construct(
        private readonly ExceptionHandlerInterface $exceptionHandler,
    ) {}

    /**
     * Defer the execution of the provided handler.
     *
     * This method allows you to defer the execution of a handler until after the
     * current request has been processed. The handler should be a generator that
     * yields control back to the process manager.
     *
     * @param \Generator $handler The handler to be deferred.
     */
    public function defer(\Generator $handler): void
    {
        $this->handlers[] = $handler;
    }

    /**
     * Process all handlers.
     */
    public function handle(): void
    {
        foreach ($this->handlers as $key => $handler) {
            try {
                if ($handler->valid()) {
                    // Execute the handler until it yields control.
                    $handler->next();
                }
            } catch (\Throwable $e) {
                unset($this->handlers[$key]);
                $this->exceptionHandler->report($e);
            }
        }
    }

    /**
     * Finalize the process by cleaning up any remaining handlers.
     */
    public function finalize(): void
    {
        while ($this->handlers !== []) {
            $this->handle();
        }
    }
}
