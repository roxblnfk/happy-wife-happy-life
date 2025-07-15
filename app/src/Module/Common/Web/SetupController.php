<?php

declare(strict_types=1);

namespace App\Module\Common\Web;

use App\Endpoint\Web\HomeController;
use App\Module\Common\Config\CalendarConfig;
use App\Module\Common\Config\GlobalStateConfig;
use App\Module\Common\Config\RelationConfig;
use App\Module\Config\ConfigService;
use App\Module\LLM\Config\LLMConfig;
use App\Module\LLM\LLMProvider;
use Psr\Http\Message\ResponseInterface;
use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Router\Annotation\Route;
use Spiral\Views\ViewsInterface;
use Symfony\AI\Platform\Model;

/**
 * Simple home page controller. It renders home page template and also provides
 * an example of exception page.
 */
final class SetupController
{
    use PrototypeTrait;

    public const ROUTE_SETUP = 'setup';
    public const POST_SETUP_LLM = 'setup-llm';

    public function __construct(
        private readonly ViewsInterface $views,
        private readonly ConfigService $configService,
    ) {}

    #[Route(route: '/setup/relation', methods: ['POST'])]
    public function setupRelation(RelationshipForm $form): ResponseInterface
    {
        $config = new RelationConfig(
            userName: $form->userName,
            womanName: $form->partnerName,
            relationType: $form->relationType,
        );

        $this->configService->persistConfig($config, true);

        return $this->response->redirect($this->router->uri(HomeController::ROUTE_INDEX));
    }

    #[Route(route: '/setup/llm', name: self::POST_SETUP_LLM, methods: ['POST'])]
    public function setupLLM(
        GlobalStateConfig $globalState,LLMProviderForm $form, LLMProvider $LLMProvider): mixed
    {
        $LLMConfig = new LLMConfig(
            platform: $form->provider,
            apiKey: $form->apiToken,
            model: $form->model,
        );

        try {
            # Check connection to the LLM provider
            // todo

            # Get available models from the provider
            $models = $LLMProvider->getPlatformModels($LLMConfig->platform);

            if ($LLMConfig->model !== null) {
                # Check model
                $LLMConfig->model === null or \array_find(
                    $models,
                    static fn(Model $model): bool => $model->getName() === $LLMConfig->model,
                ) ?? throw new \InvalidArgumentException(
                    "Model '{$LLMConfig->model}' is not available for platform '{$LLMConfig->platform->name}'.",
                );

                # Rewrite the config
                $this->configService->persistConfig($LLMConfig, true);

                # Redirect to the home page
                return $this->response->redirect($this->router->uri(HomeController::ROUTE_INDEX));
            }

            # Render model selection page
            return $this->views->render('setup/llm/model-selection', [
                'globalState' => $globalState,
                'models' => $models,
                'LLMConfig' => $LLMConfig,
            ]);
        } catch (\Throwable $e) {
            # Render error page
            return $this->views->render('setup/llm/error', [
                'globalState' => $globalState,
                'exception' => $e,
                'LLMConfig' => $LLMConfig,
            ]);
        }
    }

    #[Route(route: '/setup[/<page>]', name: self::ROUTE_SETUP, methods: ['GET'])]
    public function setup(
        GlobalStateConfig $globalState,
        ?RelationConfig $relationConfig,
        ?LLMConfig $LLMConfig,
        ?CalendarConfig $calendarConfig,
        ?string $page = null,
    ): string {
        \in_array($page, ['relation', 'llm', 'calendar'], true)
            ? $page = "setup/$page"
            : $page = null;

        $page ??= match (true) {
            $relationConfig === null => 'setup/relation',
            $LLMConfig === null || $LLMConfig->model === null => 'setup/llm',
            $calendarConfig === null => 'setup/calendar',
            default => 'setup',
        };

        return $this->views->render($page, [
            'globalState' => $globalState,
            'relationConfig' => $relationConfig,
            'LLMConfig' => $LLMConfig,
            'calendarConfig' => $calendarConfig,
        ]);
    }
}
