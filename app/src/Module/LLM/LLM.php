<?php

declare(strict_types=1);

namespace App\Module\LLM;

use App\Module\LLM\Internal\Domain\Request;
use Ramsey\Uuid\UuidInterface;
use Symfony\AI\Platform\Response\ResponsePromise;

interface LLM
{
    /**
     * Raw request to the LLM.
     *
     * @param array<mixed>|string|object $input
     * @param array<string, mixed> $options
     */
    public function rawRequest(array|string|object $input, array $options = []): ResponsePromise;

    /**
     * High-level request to the LLM.
     *
     * Persists requests and responses, handles retries, and manages rate limits.
     *
     * @param non-empty-string|object $input The input data for the request.
     * @param array<string, mixed> $options Additional options for the request.
     * @param null|callable(UuidInterface, non-empty-string): void $onProgress Callback for progress updates.
     *        Arguments:
     *          - UuidInterface: The UUID of the request.
     *          - non-empty-string: The chunk of data received.
     * @param null|callable(Request, \Throwable): void $onError Callback for error handling.
     *         Arguments:
     *           - UuidInterface: The UUID of the request.
     *           - \Throwable: The error that occurred.
     * @param null|callable(Request, ResponsePromise): void $onComplete Callback for completion handling.
     *         Arguments:
     *           - UuidInterface: The UUID of the request.
     *           - ResponsePromise: The response promise containing the result.
     * @param null|callable(null|Request, ResponsePromise): void $onFinish Callback for finalization.
     *          Arguments:
     *            - Request: Updated request object with status and response.
     *            - ResponsePromise: The response promise containing the result.
     */
    public function request(
        array|string|object $input,
        array $options = [],
        ?callable $onProgress = null,
        ?callable $onError = null,
        ?callable $onComplete = null,
        ?callable $onFinish = null,
    ): Request;
}
