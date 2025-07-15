<?php

declare(strict_types=1);

namespace App\Endpoint\Web;

use Spiral\Router\Annotation\Route;
use Spiral\Views\ViewsInterface;

/**
 * Simple home page controller. It renders home page template.
 */
final class HomeController
{
    public function __construct(
        private readonly ViewsInterface $views,
    ) {}

    #[Route(route: '/')]
    public function home(): string
    {
        return $this->views->render('template');
    }
}
