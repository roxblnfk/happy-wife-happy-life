<?php

declare(strict_types=1);

namespace App\Module\LLM\Internal;

use Symfony\AI\Platform\Model;
use Symfony\AI\Platform\PlatformInterface;
use Symfony\AI\Platform\Response\ResponsePromise;

final class Platform
{
    private function __construct(
        /**
         * Handler for the request to the LLM platform.
         *
         * @var \Closure(Model, array<string, mixed>|string|object, array<string, mixed>): ResponsePromise
         */
        private readonly \Closure $handler,
    ) {}

    public static function createFromAIPlatform(PlatformInterface $platform): self
    {
        return new self(
            static function (
                Model $model,
                array|string|object $input,
                array $options = [],
            ) use ($platform): ResponsePromise {
                return $platform->request(
                    $model,
                    $input,
                    $options + $model->getOptions(),
                );
            },
        );
    }

    /**
     * @param array<mixed>|string|object $input
     * @param array<string, mixed>       $options
     */
    public function request(Model $model, array|string|object $input, array $options = []): ResponsePromise
    {
        return ($this->handler)($model, $input, $options);
    }
}
