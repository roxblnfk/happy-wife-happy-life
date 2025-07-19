<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var \Spiral\Router\RouterInterface $router
 * @var UserInfo $userInfo
 */

use App\Feature\Calendar\Controller as CalendarController;
use App\Feature\Chat\Controller as ChatController;
use App\Module\Common\Config\UserInfo;

?>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white mb-4" style="border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <div class="container-fluid px-4">
        <span class="navbar-brand mb-0 h1">Happy Wife - Happy Life</span>

        <div class="navbar-nav me-auto">
            <button class="nav-link btn btn-link text-primary fw-medium px-3"
                    hx-get="<?= $router->uri(ChatController::ROUTE_CHATS)->__toString() ?>"
                    hx-target="#app-content"
                    style="border: none; background: none;">
                <i class="bi bi-chat-dots me-1"></i>Чаты
            </button>
        </div>

        <div class="navbar-nav ms-auto">
            <span class="nav-text text-muted me-3">
                Как дела, <strong><?= \htmlspecialchars($userInfo?->name ?? 'дружище') ?></strong>?
            </span>
        </div>
        <div class="navbar-nav">
            <button class="nav-link btn btn-link text-secondary"
                    hx-get="/setup"
                    hx-target="#app-content"
                    style="border: none; background: none;">
                <i class="bi bi-gear me-1"></i>Настройки
            </button>
        </div>
    </div>
</nav>

<!-- Main Dashboard -->
<div class="container-fluid">
    <div class="row g-4">
        <!-- Calendar Widget -->
        <div>
            <div hx-get="<?= $router->uri(CalendarController::ROUTE_CYCLE_CALENDAR) ?>"
                 hx-trigger="load"
                 hx-indicator="#calendar-spinner">
                <!-- Loading spinner -->
                <div id="calendar-spinner" class="card h-100 d-flex align-items-center justify-content-center" style="border-radius: 12px; min-height: 300px;">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Загрузка календаря...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0">Загрузка календаря...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Closest events Widget -->
        <div>
            <div hx-get="<?= $router->uri(CalendarController::ROUTE_CLOSEST_DATES)->__toString() ?>"
                 hx-trigger="load"
                 hx-indicator="#calendar-spinner">
                <!-- Loading spinner -->
                <div id="calendar-spinner" class="card h-100 d-flex align-items-center justify-content-center" style="border-radius: 12px; min-height: 300px;">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Загрузка событий...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0">Загрузка событий...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
