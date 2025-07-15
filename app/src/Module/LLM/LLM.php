<?php

namespace App\Module\LLM;

use Symfony\AI\Platform\Model;
use Symfony\AI\Platform\Response\ResponsePromise;

interface LLM
{
    /**
     * @param array<mixed>|string|object $input
     * @param array<string, mixed> $options
     */
    public function request(array|string|object $input, array $options = []): ResponsePromise;
}
