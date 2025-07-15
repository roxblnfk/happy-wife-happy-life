<?php

declare(strict_types=1);

namespace App\Module\LLM;

use App\Module\LLM\Config\LLMConfig;
use App\Module\LLM\Config\Platforms;
use App\Module\LLM\Internal\AIPlatformBridge;
use App\Module\LLM\Internal\Platform;
use Symfony\AI\Platform\Model;

class LLMProvider
{
    public function __construct(
        private readonly AIPlatformBridge $platformBridge,
    ) {}

    public function getLLM(LLMConfig $config): LLM
    {
        $platform = $this->getPlatform($config);
        $models = $this->getPlatformModels($config->platform);

        $config->model ?? throw new \LogicException('Platform model not configured.');

        # Find the model
        $model = \array_find(
            $models,
            static fn(Model $model): bool => $model->getName() === $config->model,
        ) ?? throw new \InvalidArgumentException(
            "Model `{$config->model}` not found for platform `{$config->platform->value}`.",
        );

        return new \App\Module\LLM\Internal\LLM($platform, $model);
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
