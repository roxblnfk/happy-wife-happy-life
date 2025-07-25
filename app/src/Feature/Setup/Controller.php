<?php

declare(strict_types=1);

namespace App\Feature\Setup;

use App\Feature\Index\Controller as IndexController;
use App\Feature\Setup\Input\Calendar\CalendarForm;
use App\Feature\Setup\Input\LLMProviderForm;
use App\Feature\Setup\Input\PersonalDataForm;
use App\Feature\Setup\Input\RelationshipForm;
use App\Module\Calendar\EventRepository;
use App\Module\Calendar\EventService;
use App\Module\Calendar\Info\Event;
use App\Module\Calendar\Info\WomenCycleInfo;
use App\Module\Common\Config\GlobalStateConfig;
use App\Module\Common\Config\RelationshipInfo;
use App\Module\Common\Config\UserInfo;
use App\Module\Common\Config\WomenInfo;
use App\Module\Config\ConfigService;
use App\Module\LLM\Config\LLMConfig;
use App\Module\LLM\Config\Platforms;
use App\Module\LLM\LLMProvider;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Router\Annotation\Route;
use Spiral\Views\ViewsInterface;
use Symfony\AI\Platform\Model;

/**
 * Simple home page controller. It renders home page template and also provides
 * an example of exception page.
 */
final class Controller
{
    use PrototypeTrait;

    public const ROUTE_SETUP = 'setup';
    public const ROUTE_SETUP_RELATION = 'setup-relation';
    public const POST_SETUP_LLM = 'setup-llm';
    public const ROUTE_SETUP_REMOVE_EVENT = 'setup-remove-event';

    public function __construct(
        private readonly ViewsInterface $views,
        private readonly ConfigService $configService,
    ) {}

    #[Route(route: '/setup/relation', name: self::ROUTE_SETUP_RELATION, methods: ['POST'])]
    public function setupRelation(
        RelationshipForm $form,
        ?RelationshipInfo $relationInfo,
        ?UserInfo $userInfo,
        ?WomenInfo $womenInfo,
    ): ResponseInterface {
        # Get configs or create new ones if they do not exist
        $relationInfo ??= new RelationshipInfo(
            relationType: $form->relationType,
        );
        $userInfo ??= new UserInfo(
            name: $form->userName,
        );
        $womenInfo ??= new WomenInfo(
            name: $form->partnerName,
        );

        # Update configs with data from the form
        $relationInfo->relationType = $form->relationType;
        $relationInfo->description = $form->description;
        $userInfo->name = $form->userName;
        $womenInfo->name = $form->partnerName;

        # Persist configs
        $this->configService->persistConfig($relationInfo, true);
        $this->configService->persistConfig($womenInfo, true);
        $this->configService->persistConfig($userInfo, true);

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
                # Check model name
                $LLMConfig->platform === Platforms::Local or \array_find(
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
                'router' => $this->router,
                'globalState' => $globalState,
                'models' => $models,
                'LLMConfig' => $LLMConfig,
            ]);
        } catch (\Throwable $e) {
            # Render error page
            return $this->views->render('setup:llm/error', [
                'router' => $this->router,
                'globalState' => $globalState,
                'exception' => $e,
                'LLMConfig' => $LLMConfig,
            ]);
        }
    }

    #[Route(route: '/setup/calendar', methods: ['POST'])]
    public function setupCalendar(
        CalendarForm $form,
        ?WomenCycleInfo $womenCycleInfo,
        ?RelationshipInfo $relationInfo,
        ?WomenInfo $womenInfo,
        EventService $eventService,
    ): ResponseInterface {
        # Get configs or create new ones if they do not exist
        $womenCycleInfo ??= new WomenCycleInfo(
            lastPeriodStart: $form->lastPeriodStart,
        );

        # Update configs with data from the form
        $womenCycleInfo->lastPeriodStart = $form->lastPeriodStart;
        $womenCycleInfo->cycleLength = $form->cycleLength;
        $womenCycleInfo->periodLength = $form->periodLength;
        $relationInfo === null or $relationInfo->anniversary = $form->anniversary;
        $womenInfo === null or $womenInfo->birthday = $form->birthday;

        # Store important dates
        foreach ($form->importantDates as $date) {
            $eventService->create(
                new Event(
                    date: $date->date,
                    title: $date->title,
                    period: $date->period ?: null,
                    description: $date->description ?: null,
                ),
            );
        }

        # Persist configs
        $this->configService->persistConfig($womenCycleInfo, true);
        $relationInfo === null or $this->configService->persistConfig($relationInfo, true);
        $womenInfo === null or $this->configService->persistConfig($womenInfo, true);

        return $this->response->redirect($this->router->uri(IndexController::ROUTE_INDEX));
    }

    #[Route(route: '/setup/calendar/remove-event/<uuid>', name: self::ROUTE_SETUP_REMOVE_EVENT, methods: ['DELETE'])]
    public function removeEvent(string $uuid, EventService $eventService): string
    {
        $eventUuid = Uuid::fromString($uuid);
        $eventService->delete($eventUuid);
        return '';
    }

    #[Route(route: '/setup/personal', methods: ['POST'])]
    public function setupPersonal(
        PersonalDataForm $form,
        ?WomenInfo $womenInfo,
        GlobalStateConfig $globalState,
    ): ResponseInterface {
        if ($womenInfo === null) {
            return $this->response->redirect($this->router->uri(self::ROUTE_SETUP_RELATION));
        }

        $womenInfo->preferences = $form->preferences;
        $womenInfo->triggers = $form->triggers;
        $globalState->configured = true;

        # Persist configs
        $this->configService->persistConfig($womenInfo, true);
        $this->configService->persistConfig($globalState, true);

        return $this->response->redirect($this->router->uri(IndexController::ROUTE_INDEX));
    }

    #[Route(route: '/setup[/<page>]', name: self::ROUTE_SETUP, methods: ['GET'])]
    public function setup(
        GlobalStateConfig $globalState,
        ?RelationshipInfo $relationInfo,
        ?LLMConfig $LLMConfig,
        ?WomenCycleInfo $womenCycleInfo,
        ?UserInfo $userInfo,
        ?WomenInfo $womenInfo,
        ?string $page = null,
        EventRepository $eventRepository,
    ): string {
        \in_array($page, ['relation', 'llm', 'calendar', 'personal'], true)
            ? $page = "setup:$page"
            : $page = null;

        $page ??= match (true) {
            $relationInfo === null => 'setup:relation',
            $LLMConfig === null || $LLMConfig->model === null => 'setup:llm',
            $womenCycleInfo === null => 'setup:calendar',
            !$globalState->configured => 'setup:personal',
            default => 'setup:setup',
        };

        return $this->views->render($page, [
            'router' => $this->router,
            'globalState' => $globalState,
            'relationInfo' => $relationInfo,
            'userInfo' => $userInfo,
            'womenInfo' => $womenInfo,
            'LLMConfig' => $LLMConfig,
            'womenCycleInfo' => $womenCycleInfo,
            'events' => $page === 'setup:calendar' ? $eventRepository->getAll() : [],
        ]);
    }
}
