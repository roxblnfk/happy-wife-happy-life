<?php

declare(strict_types=1);

namespace App\Feature\Index;

use App\Feature\Setup\Controller as SetupController;
use App\Module\Common\Config\GlobalStateConfig;
use App\Module\Common\Config\UserInfo;
use App\Module\Config\ConfigService;
use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Router\Annotation\Route;
use Spiral\Views\ViewsInterface;

/**
 * Simple home page controller. It renders home page template.
 */
final class Controller
{
    use PrototypeTrait;

    public const ROUTE_INDEX = 'index';

    public function __construct(
        private readonly ViewsInterface $views,
        private readonly ConfigService $configService,
    ) {}

    #[Route(route: '/index', name: self::ROUTE_INDEX, methods: ['GET'])]
    public function index(?GlobalStateConfig $globalState, ?UserInfo $userInfo): mixed
    {
        if ($globalState === null) {
            $globalState = new GlobalStateConfig();
            $this->configService->persistConfig($globalState);
        }

        if (!$globalState->configured) {
            // Go to the configure page if the user is not configured
            return $this->response->redirect($this->router->uri(SetupController::ROUTE_SETUP));
        }

        return $this->views->render('index:index', [
            'router' => $this->router,
            'userInfo' => $userInfo,
        ]);
    }
}
