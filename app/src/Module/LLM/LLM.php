<?php

declare(strict_types=1);

namespace App\Module\LLM;

use App\Module\LLM\Internal\Domain\Request;
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
     */
    public function request(
        array|string|object $input,
        array $options = [],
    ): Request;
}
