<?php

declare(strict_types=1);

namespace App\Endpoint\Web;

use Psr\Http\Message\ResponseInterface;
use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Router\Annotation\Route;
use Spiral\Views\ViewsInterface;

/**
 * Simple home page controller. It renders home page template and also provides
 * an example of exception page.
 */
final class HomeController
{
    Use PrototypeTrait;

    public function __construct(
        private readonly ViewsInterface $views,
    ) {}

    #[Route(route: '/', name: 'template')]
    public function template(): string
    {
        return $this->views->render('template');
    }

    #[Route(route: '/index', name: 'index')]
    public function index(): mixed
    {
        // if (true) {
            // Go to the configure page if the user is not configured
            // return $this->response->redirect($this->router->uri('configure'));
        // }

        return $this->views->render('index');
    }

    #[Route(route: '/configure', name: 'configure')]
    public function configure(): string
    {
        $page = [
            'setup/llm',
            'setup/start',
            'setup/personal-data',
        ][rand(0, 2)];
        // $page = 'main';
        return $this->views->render($page);
    }
}
