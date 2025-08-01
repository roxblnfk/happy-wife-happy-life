<?php

declare(strict_types=1);

namespace App\Module\LLM\Internal;

use App\Module\LLM\Config\LLMConfig;
use App\Module\LLM\Config\Platforms;
use Symfony\AI\Platform\Bridge\Anthropic\Claude;
use Symfony\AI\Platform\Bridge\Anthropic\PlatformFactory as AnthropicPlatformFactory;
use Symfony\AI\Platform\Bridge\Gemini\Gemini;
use Symfony\AI\Platform\Bridge\Gemini\PlatformFactory as GoogleFactoryAlias;
use Symfony\AI\Platform\Bridge\Ollama\PlatformFactory as OllamaPlatformFactory;
use Symfony\AI\Platform\Bridge\OpenAi\Gpt;
use Symfony\AI\Platform\Bridge\OpenAi\PlatformFactory as OpenAIPlatformFactory;
use Symfony\AI\Platform\Model;
use Symfony\AI\Platform\PlatformInterface;

final class AIPlatformBridge
{
    public function getPlatform(LLMConfig $config): PlatformInterface
    {
        return match ($config->platform) {
            Platforms::OpenAI => OpenAIPlatformFactory::create($config->apiKey),
            Platforms::Anthropic => AnthropicPlatformFactory::create($config->apiKey),
            Platforms::Google => GoogleFactoryAlias::create($config->apiKey),
            Platforms::Local => OllamaPlatformFactory::create(),
        };
    }

    /**
     * @return list<Model>
     */
    public function getModels(Platforms $platform): array
    {
        return match ($platform) {
            Platforms::OpenAI => $this->getOpenAIModels(),
            Platforms::Anthropic => $this->getAnthropicModels(),
            Platforms::Google => $this->getGoogleModels(),
            Platforms::Local => [],
        };
    }

    /**
     * @return list<Gpt>
     */
    private function getOpenAIModels(): array
    {
        $result = [];

        $constants = (new \ReflectionClass(Gpt::class))->getConstants(\ReflectionClassConstant::IS_PUBLIC);
        foreach ($constants as $value) {
            $result[] = new Gpt($value);
        }

        return $result;
    }

    /**
     * @return list<Claude>
     */
    private function getAnthropicModels(): array
    {
        $result = [];

        $constants = (new \ReflectionClass(Claude::class))->getConstants(\ReflectionClassConstant::IS_PUBLIC);
        foreach ($constants as $value) {
            $result[] = new Claude($value);
        }

        return $result;
    }

    /**
     * @return list<Gemini>
     */
    private function getGoogleModels(): array
    {
        $result = [];

        $constants = (new \ReflectionClass(Gemini::class))->getConstants(\ReflectionClassConstant::IS_PUBLIC);
        foreach ($constants as $value) {
            $result[] = new Gemini($value);
        }

        return $result;
    }
}
