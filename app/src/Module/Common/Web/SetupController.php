<?php

declare(strict_types=1);

namespace App\Module\Common\Web;

use App\Endpoint\Web\HomeController;
use App\Module\Common\Config\CalendarConfig;
use App\Module\Common\Config\GlobalStateConfig;
use App\Module\Common\Config\LLMConfig;
use App\Module\Common\Config\RelationConfig;
use App\Module\Config\ConfigService;
use Psr\Http\Message\ResponseInterface;
use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Router\Annotation\Route;
use Spiral\Views\ViewsInterface;

/**
 * Simple home page controller. It renders home page template and also provides
 * an example of exception page.
 */
final class SetupController
{
    use PrototypeTrait;

    public const ROUTE_SETUP = 'setup';

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

    #[Route(route: '/setup/llm/provider', methods: ['POST'])]
    public function checkLLMProvider(LLMProviderForm $form): mixed
    {
        $LLMConfig = new LLMConfig(
            provider: $form->provider,
            token: $form->apiToken,
        );

        # Rewrite the config
        $this->configService->persistConfig($LLMConfig, true);

        try {
            # Check connection to the LLM provider
            // todo
            throw new \Exception('Not implemented yet');
        } catch (\Throwable $e) {
            return $this->views->render('setup/llm/connection-error', [
                'exception' => $e,
                'LLMConfig' => $LLMConfig,
            ]);
        }

        return $this->views->render('setup/llm/model-selection', [
            'models' => [
                ['id' => 1, 'name' => 'foo', 'description' => 'bar']
            ],
            'LLMConfig' => $LLMConfig,
        ]);
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
