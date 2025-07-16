<?php

declare(strict_types=1);

namespace App\Module\LLM\Internal;

use App\Application\Process\Process;
use App\Module\LLM\Internal\Domain\Request;
use App\Module\LLM\Internal\Domain\RequestStatus;
use Ramsey\Uuid\UuidInterface;
use Symfony\AI\Platform\Model;
use Symfony\AI\Platform\Response\ResponsePromise;

final class LLM implements \App\Module\LLM\LLM
{
    public function __construct(
        private readonly Platform $platform,
        private readonly Model $model,
        private readonly Process $process,
    ) {}

    public function rawRequest(array|string|object $input, array $options = []): ResponsePromise
    {
        return $this->platform->request($this->model, $input, $options);
    }

    public function request(
        array|string|object $input,
        array $options = [],
        ?callable $onProgress = null,
        ?callable $onError = null,
        ?callable $onComplete = null,
        ?callable $onFinish = null,
    ): Request {
        $response = $this->rawRequest($input, $options);
        $request = Request::create(
            $this->model->getName(),
            $input,
            $options,
        );
        $request->saveOrFail();

        $this->process->defer((static function (ResponsePromise $response, UuidInterface $uuid) use (
            $onProgress,
            $onError,
            $onComplete,
            $onFinish,
        ): \Generator {
            $request = null;
            try {
                # todo Wait generators from Symfony side
                /*
                $generator = $response->asStream();
                while ($generator->valid()) {
                    $chunk = $generator->current();
                    if ($chunk === null || $chunk === '') {
                        yield;
                        $generator->next();
                        continue;
                    }

                    try {
                        $onProgress === null or $onProgress($uuid, $chunk);
                    } catch (\Throwable) {
                        # Do nothing
                    }
                    yield;
                }
                /*/
                yield
                $chunk = $response->asText();
                try {
                    $onProgress === null or $onProgress($uuid, $chunk);
                } catch (\Throwable) {
                    # Do nothing
                }
                // */

                $request = Request::findByPK($uuid) ?? throw new \RuntimeException(
                    "Request with UUID `{$uuid}` not found.",
                );
                $request->output = $response->asText();
                $request->status = RequestStatus::Completed;
                $request->saveOrFail();

                $onComplete === null or $onComplete($request, $response);
            } catch (\Throwable $e) {
                $request = Request::findByPK($uuid) ?? throw new \RuntimeException(
                    "Request with UUID `{$uuid}` not found.",
                );
                $request->status = RequestStatus::Failed;

                $onError === null or $onError($uuid, $e);
            } finally {
                $onFinish === null or $onFinish($request, $response);
            }
        })($response, $request->uuid));

        return $request;
    }
}
