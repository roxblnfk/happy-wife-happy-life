<?php

declare(strict_types=1);

namespace App\Endpoint\Web;

use App\Module\Common\Config\GlobalStateConfig;
use App\Module\Common\Web\SetupController;
use App\Module\Config\ConfigService;
use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Router\Annotation\Route;
use Spiral\Views\ViewsInterface;

/**
 * Simple home page controller. It renders home page template.
 */
final class HomeController
{
    use PrototypeTrait;

    public const ROUTE_INDEX = 'index';

    public function __construct(
        private readonly ViewsInterface $views,
        private readonly ConfigService $configService,
    ) {}

    #[Route(route: '/')]
    public function home(): string
    {
        return $this->views->render('template');
    }

    #[Route(route: '/index', name: self::ROUTE_INDEX, methods: ['GET'])]
    public function index(?GlobalStateConfig $globalState): mixed
    {
        if ($globalState === null) {
            $globalState = new GlobalStateConfig();
            $this->configService->persistConfig($globalState);
        }

        if (!$globalState->configured) {
            // Go to the configure page if the user is not configured
            return $this->response->redirect($this->router->uri(SetupController::ROUTE_SETUP));
        }

        return $this->views->render('index');
    }
}
