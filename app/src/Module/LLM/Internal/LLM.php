<?php

declare(strict_types=1);

namespace App\Module\LLM\Internal;

use App\Application\Process\Process;
use App\Module\LLM\Internal\Domain\Request;
use App\Module\LLM\Internal\Domain\RequestStatus;
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

        $cacheId = $request->uuid->toString();

        $this->process->defer((function (ResponsePromise $response, string $cacheId): \Generator {
            try {
                $generator = $response->asStream();
                while ($generator->valid()) {
                    $chunk = $generator->current();
                    if ($chunk === null || $chunk === '') {
                        yield;
                        $generator->next();
                        continue;
                    }

                    tr($chunk);

                    # Cache chunk
                    $this->cache->write($cacheId, $chunk, true);
                    yield;
                }

                #
            } catch (\Throwable $e) {
                tr($e);
            } finally {
                // Finalize the stream cache
                $request = Request::findByPK($cacheId);
                // $this->cache->delete($cacheId);

                if ($request !== null) {
                    $request->status === RequestStatus::Pending and $request->status = isset($e)
                        ? RequestStatus::Failed
                        : RequestStatus::Completed;

                    $content = $response->getResponse()->getContent();
                    tr(response: $response, content: $content);
                    $request->output = is_array($content)
                        ? $response->getResponse()->getContent()
                        : (string) $content;
                    $request->save();
                }
            }

        })($response, $cacheId));

        return $request;
    }
}
