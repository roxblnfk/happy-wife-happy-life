<?php

declare(strict_types=1);

namespace App\Module\LLM;

use App\Application\Process\Process;
use App\Module\LLM\Config\LLMConfig;
use App\Module\LLM\Config\Platforms;
use App\Module\LLM\Internal\AIPlatformBridge;
use App\Module\LLM\Internal\Platform;
use Spiral\Core\Attribute\Singleton;
use Symfony\AI\Platform\Model;

#[Singleton]
class LLMProvider
{
    public function __construct(
        private readonly AIPlatformBridge $platformBridge,
        private readonly Process $process,
    ) {}

    public function getLLM(LLMConfig $config): LLM
    {
        $platform = $this->getPlatform($config);

        $config->model ?? throw new \LogicException('Platform model not configured.');

        if ($config->platform === Platforms::Local) {
            $model = new Model($config->model);
        } else {
            $models = $this->getPlatformModels($config->platform);
            # Find the model
            $model = \array_find(
                $models,
                static fn(Model $model): bool => $model->getName() === $config->model,
            ) ?? throw new \InvalidArgumentException(
                "Model `{$config->model}` not found for platform `{$config->platform->value}`.",
            );
        }

        return new \App\Module\LLM\Internal\LLM($platform, $model, $this->process);
    }

    /**
     * @return list<Model>
     */
    public function getPlatformModels(Platforms $platform): array
    {
        return $this->platformBridge->getModels($platform);
    }

    private function getPlatform(
        LLMConfig $config,
    ): Platform {
        $platform = $this->platformBridge->getPlatform($config);
        return Platform::createFromAIPlatform($platform);
    }
}
