<?php

declare(strict_types=1);

namespace App\Module\LLM\Internal;

use App\Application\Process\Process;
use App\Module\LLM\Internal\Domain\Request;
use Symfony\AI\Platform\Model;
use Symfony\AI\Platform\Response\ResponsePromise;

final class LLM implements \App\Module\LLM\LLM
{
    public function __construct(
        private readonly Platform $platform,
        private readonly Model $model,
        private readonly Process $process,
        private readonly StreamCache $cache,
    ) {}

    public function rawRequest(array|string|object $input, array $options = []): ResponsePromise
    {
        return $this->platform->request($this->model, $input, $options);
    }

    public function request(
        array|string|object $input,
        array $options = [],
    ): Request {
        $response = $this->rawRequest($input, $options);
        $request = Request::create(
            $this->model->getName(),
            $input,
            $options,
        );
        $request->saveOrFail();

        $this->process->defer((function (ResponsePromise $response): \Generator {
            try {
                $generator = $response->asStream();
                while ($generator->valid()) {
                    $chunk = $generator->current();
                    if ($chunk === null) {
                        break;
                    }

                    // Cache the chunk for streaming responses
                    yield $this->cache->cacheChunk($chunk);
                    $generator->next();
                }
            } catch (\Throwable $e) {
                // Handle any exceptions that occur during streaming
                yield $this->cache->cacheError($e);
            } finally {
                // Finalize the stream cache
                yield $this->cache->delete()
            }

        })($response));

        return $request;
    }
}
