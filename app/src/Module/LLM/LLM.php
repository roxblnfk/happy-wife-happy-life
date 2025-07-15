<?php

declare(strict_types=1);

namespace App\Module\LLM;

use Symfony\AI\Platform\Response\ResponsePromise;

interface LLM
{
    /**
     * @param array<mixed>|string|object $input
     * @param array<string, mixed> $options
     */
    public function request(array|string|object $input, array $options = []): ResponsePromise;
}
