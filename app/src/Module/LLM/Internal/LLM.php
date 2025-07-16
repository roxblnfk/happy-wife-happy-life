<?php

declare(strict_types=1);

namespace App\Module\LLM\Internal;

use App\Application\Process\Process;
use App\Module\LLM\Internal\Domain\Request;
use App\Module\LLM\Internal\Domain\RequestStatus;
use Ramsey\Uuid\UuidInterface;
use Symfony\AI\Agent\Agent;
use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\MessageBagInterface;
use Symfony\AI\Platform\Model;
use Symfony\AI\Platform\PlatformInterface;
use Symfony\AI\Platform\Response\ResponseInterface;
use Symfony\AI\Platform\Response\ResponsePromise;

final class LLM implements \App\Module\LLM\LLM
{
    public function __construct(
        private readonly PlatformInterface $platform,
        private readonly Model $model,
        private readonly Process $process,
    ) {}

    public function agent(): AgentInterface
    {
        return new Agent($this->platform, $this->model);
    }

    public function callAgent(
        MessageBagInterface $messages,
        array $options = [],
        ?callable $onProgress = null,
        ?callable $onError = null,
        ?callable $onComplete = null,
        ?callable $onFinish = null,
    ): ResponseInterface {
        $request = Request::create(
            $this->model->getName(),
            $this->serializeMessageBag($messages),
            $options,
        );
        $request->saveOrFail();

        $agent = $this->agent();
        $response = $agent->call($messages, ['stream' => true] + $options);

        $this->process->defer((static function (ResponseInterface $response, UuidInterface $uuid) use (
            $onProgress,
            $onError,
            $onComplete,
            $onFinish,
        ): \Generator {
            $result = '';
            try {
                foreach ($response->getContent() as $chunk) {
                    $result .= $chunk;
                    try {
                        $onProgress === null or $onProgress($uuid, $chunk);
                    } catch (\Throwable) {
                        // Do nothing, just continue
                    }
                    yield;
                }

                $request = Request::findByPK($uuid) ?? throw new \RuntimeException(
                    "Request with UUID `{$uuid}` not found.",
                );
                $request->output = $result;
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
                $onFinish === null or $onFinish($uuid, $onProgress);
            }
        })($response, $request->uuid));

        return $response;
    }

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

    private function serializeMessageBag(MessageBagInterface $messageBag): string|array
    {
        return \serialize($messageBag);
    }
}
