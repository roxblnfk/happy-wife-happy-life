<?php

declare(strict_types=1);

namespace App\Module\LLM\Internal;

use App\Application\Process\Process;
use App\Module\LLM\Internal\Domain\Request;
use App\Module\LLM\Internal\Domain\RequestStatus;
use Ramsey\Uuid\UuidInterface;
use Symfony\AI\Agent\Agent;
use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\AI\Platform\Message\MessageBagInterface;
use Symfony\AI\Platform\Model;
use Symfony\AI\Platform\PlatformInterface;
use Symfony\AI\Platform\Result\ResultInterface;
use Symfony\AI\Platform\Result\ResultPromise;

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
    ): Request {
        $request = Request::create(
            $this->model->getName(),
            $this->serializeMessageBag($messages),
            $options,
        );
        $request->saveOrFail();

        $agent = $this->agent();
        $response = $agent->call($messages, ['stream' => true] + $options);

        $this->process->defer((static function (ResultInterface $response, UuidInterface $uuid) use (
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

                $onError === null or $onError($request, $e);
            } finally {
                $onFinish === null or $onFinish($request, $onProgress);
            }
        })($response, $request->uuid));

        return $request;
    }

    public function rawRequest(array|string|object $input, array $options = []): ResultPromise
    {
        return $this->platform->invoke($this->model, $input, $options);
    }

    public function request(
        string|MessageBagInterface $input,
        array $options = [],
        ?callable $onProgress = null,
        ?callable $onError = null,
        ?callable $onComplete = null,
        ?callable $onFinish = null,
    ): Request {
        \is_string($input) and $input = new MessageBag(Message::ofUser($input));

        $response = $this->rawRequest($input, ['stream' => true] + $options);
        $request = Request::create(
            $this->model->getName(),
            $this->serializeMessageBag($input),
            $options,
        );
        $request->saveOrFail();

        $this->process->defer((static function (ResultPromise $response, UuidInterface $uuid) use (
            $onProgress,
            $onError,
            $onComplete,
            $onFinish,
        ): \Generator {
            $request = null;
            $result = '';
            try {
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
                    $result .= $chunk;
                    yield;
                    $generator->next();
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
                $request->output = $result;
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
