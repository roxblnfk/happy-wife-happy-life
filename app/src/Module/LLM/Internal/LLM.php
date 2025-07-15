<?php

declare(strict_types=1);

namespace App\Module\LLM\Internal;

use Symfony\AI\Platform\Model;
use Symfony\AI\Platform\Response\ResponsePromise;

final class LLM implements \App\Module\LLM\LLM
{
    public function __construct(
        private readonly Platform $platform,
        private readonly Model $model,
    ) {}

    public function request(array|string|object $input, array $options = []): ResponsePromise
    {
        return $this->platform->request($this->model, $input, $options);
    }
}
