<?php

declare(strict_types=1);

namespace App\Feature\Setup;

use App\Feature\Index\IndexController;
use App\Feature\Setup\Input\Calendar\CalendarForm;
use App\Feature\Setup\Input\LLMProviderForm;
use App\Feature\Setup\Input\PersonalDataForm;
use App\Feature\Setup\Input\RelationshipForm;
use App\Module\Common\Config\GlobalStateConfig;
use App\Module\Common\Config\RelationConfig;
use App\Module\Common\Config\UserConfig;
use App\Module\Common\Config\WomenCycleConfig;
use App\Module\Common\Config\WomenPersonConfig;
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
    public const ROUTE_SETUP_RELATION = 'setup-relation';
    public const POST_SETUP_LLM = 'setup-llm';

    public function __construct(
        private readonly ViewsInterface $views,
        private readonly ConfigService $configService,
    ) {}

    #[Route(route: '/setup/relation', name: self::ROUTE_SETUP_RELATION, methods: ['POST'])]
    public function setupRelation(
        RelationshipForm $form,
        ?RelationConfig $relationConfig,
        ?UserConfig $userConfig,
        ?WomenPersonConfig $womenPersonalConfig,
    ): ResponseInterface {
        # Get configs or create new ones if they do not exist
        $relationConfig ??= new RelationConfig(
            relationType: $form->relationType,
        );
        $userConfig ??= new UserConfig(
            name: $form->userName,
        );
        $womenPersonalConfig ??= new WomenPersonConfig(
            name: $form->partnerName,
        );

        # Update configs with data from the form
        $relationConfig->relationType = $form->relationType;
        $userConfig->name = $form->userName;
        $womenPersonalConfig->name = $form->partnerName;

        # Persist configs
        $this->configService->persistConfig($relationConfig, true);
        $this->configService->persistConfig($womenPersonalConfig, true);
        $this->configService->persistConfig($userConfig, true);

        return $this->response->redirect($this->router->uri(IndexController::ROUTE_INDEX));
    }

    #[Route(route: '/setup/llm', name: self::POST_SETUP_LLM, methods: ['POST'])]
    public function setupLLM(
        GlobalStateConfig $globalState,
        LLMProviderForm $form,
        LLMProvider $LLMProvider,
    ): mixed {
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
                return $this->response->redirect($this->router->uri(IndexController::ROUTE_INDEX));
            }

            # Render model selection page
            return $this->views->render('setup:llm/model-selection', [
                'globalState' => $globalState,
                'models' => $models,
                'LLMConfig' => $LLMConfig,
            ]);
        } catch (\Throwable $e) {
            # Render error page
            return $this->views->render('setup:llm/error', [
                'globalState' => $globalState,
                'exception' => $e,
                'LLMConfig' => $LLMConfig,
            ]);
        }
    }

    #[Route(route: '/setup/calendar', methods: ['POST'])]
    public function setupCalendar(
        CalendarForm $form,
        ?WomenCycleConfig $womenCycleConfig,
        ?RelationConfig $relationConfig,
        ?WomenPersonConfig $womenPersonalConfig,
    ): ResponseInterface {
        # Get configs or create new ones if they do not exist
        $womenCycleConfig ??= new WomenCycleConfig(
            lastPeriodStart: $form->lastPeriodStart,
        );

        # Update configs with data from the form
        $womenCycleConfig->lastPeriodStart = $form->lastPeriodStart;
        $womenCycleConfig->cycleLength = $form->cycleLength;
        $womenCycleConfig->periodLength = $form->periodLength;
        $relationConfig === null or $relationConfig->anniversary = $form->anniversary;
        $womenPersonalConfig === null or $womenPersonalConfig->birthday = $form->birthday;

        # Store important dates
        foreach ($form->importantDates as $date) {
            // todo
            // $date->
        }

        # Persist configs
        $this->configService->persistConfig($womenCycleConfig, true);
        $relationConfig === null or $this->configService->persistConfig($relationConfig, true);
        $womenPersonalConfig === null or $this->configService->persistConfig($womenPersonalConfig, true);

        return $this->response->redirect($this->router->uri(IndexController::ROUTE_INDEX));
    }

    #[Route(route: '/setup/personal', methods: ['POST'])]
    public function setupPersonal(
        PersonalDataForm $form,
        ?WomenPersonConfig $womenPersonalConfig,
        GlobalStateConfig $globalState,
    ): ResponseInterface {
        if ($womenPersonalConfig === null) {
            return $this->response->redirect($this->router->uri(self::ROUTE_SETUP_RELATION));
        }

        $womenPersonalConfig->preferences = $form->preferences;
        $womenPersonalConfig->triggers = $form->triggers;
        $globalState->configured = true;

        # Persist configs
        $this->configService->persistConfig($womenPersonalConfig, true);
        $this->configService->persistConfig($globalState, true);

        return $this->response->redirect($this->router->uri(IndexController::ROUTE_INDEX));
    }

    #[Route(route: '/setup[/<page>]', name: self::ROUTE_SETUP, methods: ['GET'])]
    public function setup(
        GlobalStateConfig $globalState,
        ?RelationConfig $relationConfig,
        ?LLMConfig $LLMConfig,
        ?WomenCycleConfig $womenCycleConfig,
        ?UserConfig $userConfig,
        ?WomenPersonConfig $womenPersonalConfig,
        ?string $page = null,
    ): string {
        \in_array($page, ['relation', 'llm', 'calendar'], true)
            ? $page = "setup:$page"
            : $page = null;

        $page ??= match (true) {
            $relationConfig === null => 'setup:relation',
            $LLMConfig === null || $LLMConfig->model === null => 'setup:llm',
            $womenCycleConfig === null => 'setup:calendar',
            !$globalState->configured => 'setup:personal',
            default => 'setup:setup',
        };

        return $this->views->render($page, [
            'globalState' => $globalState,
            'relationConfig' => $relationConfig,
            'userConfig' => $userConfig,
            'womenPersonalConfig' => $womenPersonalConfig,
            'LLMConfig' => $LLMConfig,
            'womenCycleConfig' => $womenCycleConfig,
        ]);
    }
}
