<?php

declare(strict_types=1);

namespace App\Module\Common\Web;

use App\Endpoint\Web\HomeController;
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
    ) {}

    #[Route(route: '/setup', name: self::ROUTE_SETUP, methods: ['GET'])]
    public function setup(
        ?RelationConfig $user,
    ): string {
        $page = match (true) {
            $user === null => 'setup/relation',
            default => [
                'setup/llm',
                'setup/personal-data',
            ][\mt_rand(0, 1)],
        };

        return $this->views->render($page);
    }

    #[Route(route: '/setup/relation', methods: ['POST'])]
    public function setupRelation(StartForm $form, ConfigService $configService): ResponseInterface
    {
        $config = new RelationConfig(
            userName: $form->userName,
            womanName: $form->partnerName,
            relationType: $form->relationType,
        );

        $configService->persistConfig($config, true);

        return $this->response->redirect($this->router->uri(HomeController::ROUTE_INDEX));
    }
}
