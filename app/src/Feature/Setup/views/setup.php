<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var \Spiral\Router\RouterInterface $router
 */

use App\Feature\Setup\Controller;

?>

<div class="app-header">
    <h1 class="app-title">Настройки приложения</h1>
    <p class="app-subtitle">Быстрый доступ к основным параметрам</p>
</div>

<div class="row g-4">
    <!-- Анкетные данные -->
    <div class="col-md-6">
        <div class="card h-100" hx-get="<?= $router->uri(Controller::ROUTE_SETUP, ['page' => 'relation']) ?>" hx-target="#app-content" style="cursor: pointer">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="text-white" style="font-size: 24px;">👥</i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">Анкетные данные</h5>
                        <small class="text-muted">Имена, тип отношений, важные даты</small>
                    </div>
                </div>
                <p class="card-text">Основная информация о вас и вашей спутнице, включая тип отношений и дату их начала.</p>
            </div>
        </div>
    </div>

    <!-- Настройки LLM -->
    <div class="col-md-6">
        <div class="card h-100" hx-get="<?= $router->uri(Controller::ROUTE_SETUP, ['page' => 'llm']) ?>" hx-target="#app-content" style="cursor: pointer">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="text-white" style="font-size: 24px;">🤖</i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">AI-помощник</h5>
                        <small class="text-muted">Провайдер, модель, API ключи</small>
                    </div>
                </div>
                <p class="card-text">Настройка подключения к языковой модели для получения персонализированных советов.</p>
            </div>
        </div>
    </div>

    <!-- Важные даты -->
    <div class="col-md-6">
        <div class="card h-100" hx-get="<?= $router->uri(Controller::ROUTE_SETUP, ['page' => 'calendar']) ?>" hx-target="#app-content" style="cursor: pointer">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="text-white" style="font-size: 24px;">📅</i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">Цикл и даты</h5>
                        <small class="text-muted">Менструальный цикл, важные события</small>
                    </div>
                </div>
                <p class="card-text">Данные о женском цикле и важных датах.</p>
            </div>
        </div>
    </div>

    <!-- Важные даты -->
    <div class="col-md-6">
        <div class="card h-100" hx-get="<?= $router->uri(Controller::ROUTE_SETUP, ['page' => 'personal']) ?>" hx-target="#app-content" style="cursor: pointer">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-info d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="text-white" style="font-size: 24px;">📝</i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">Характер</h5>
                        <small class="text-muted">Предпочтения и особенности</small>
                    </div>
                </div>
                <p class="card-text">Важные аспекты характера и предпочтений вашей спутницы.</p>
            </div>
        </div>
    </div>

    <!-- Экспорт/Импорт -->
<!--    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-info d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="text-white" style="font-size: 24px;">💾</i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">Резервное копирование</h5>
                        <small class="text-muted">Экспорт и импорт данных</small>
                    </div>
                </div>
                <p class="card-text">Создание резервных копий и восстановление данных приложения.</p>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-info btn-sm">Экспорт</button>
                    <button class="btn btn-outline-info btn-sm">Импорт</button>
                </div>
            </div>
        </div>
    </div>-->
</div>

<!-- Быстрые действия -->
<!--
<div class="mt-5">
    <h4 class="mb-3">Быстрые действия</h4>
    <div class="row g-3">
        <div class="col-md-3">
            <div class="d-grid">
                <button class="btn btn-light border">
                    <div class="py-2">
                        <div style="font-size: 24px;">🔄</div>
                        <div class="mt-2">Синхронизация</div>
                    </div>
                </button>
            </div>
        </div>

        <div class="col-md-3">
            <div class="d-grid">
                <button class="btn btn-light border">
                    <div class="py-2">
                        <div style="font-size: 24px;">🔔</div>
                        <div class="mt-2">Уведомления</div>
                    </div>
                </button>
            </div>
        </div>

        <div class="col-md-3">
            <div class="d-grid">
                <button class="btn btn-light border">
                    <div class="py-2">
                        <div style="font-size: 24px;">🎨</div>
                        <div class="mt-2">Тема</div>
                    </div>
                </button>
            </div>
        </div>

        <div class="col-md-3">
            <div class="d-grid">
                <button class="btn btn-light border">
                    <div class="py-2">
                        <div style="font-size: 24px;">🌐</div>
                        <div class="mt-2">Язык</div>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>
-->

<!-- Кнопка возврата -->
<div class="d-flex justify-content-center mt-5">
    <button class="btn btn-secondary" hx-get="/index" hx-target="#app-content">
        Назад
    </button>
</div>
