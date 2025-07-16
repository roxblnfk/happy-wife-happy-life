<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var \Spiral\Router\RouterInterface $router
 */

use App\Feature\Chat\Controller as ChatController;

?>

<!-- Навигация -->
<nav class="navbar navbar-expand-lg navbar-light bg-white mb-4" style="border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    <div class="container-fluid px-4">
        <span class="navbar-brand mb-0 h1">Happy Wife - Happy Life</span>
        <div class="navbar-nav ms-auto">
            <span class="nav-text text-muted">
                Добро пожаловать, <strong><?= \htmlspecialchars($userName ?? 'Пользователь') ?></strong>
            </span>
        </div>
    </div>
</nav>

<div class="row">
    <!-- Боковая панель -->
    <div class="col-md-3">
        <div class="sidebar">
            <h6 class="mb-3">Сегодня</h6>
            <div class="mb-3">
                <div class="d-flex align-items-center mb-2">
                    <div class="mood-indicator mood-good me-2"></div>
                    <small class="text-muted">Хорошее настроение</small>
                </div>
                <div class="small text-muted">
                    13 день цикла • Фолликулярная фаза
                </div>
            </div>

            <hr>

            <h6 class="mb-3">Ближайшие события</h6>
            <div class="mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <small>День рождения</small>
                    <span class="badge bg-primary">3 дня</span>
                </div>
            </div>
            <div class="mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <small>ПМС период</small>
                    <span class="badge bg-warning">7 дней</span>
                </div>
            </div>

            <hr>

            <div class="d-grid gap-2">
                <button class="btn btn-primary btn-sm"
                        hx-get="<?= $router->uri(ChatController::ROUTE_CHATS)->__toString() ?>"
                        hx-target="#app-content"
                >Чаты</button>
                <button class="btn btn-outline-secondary btn-sm" hx-get="/setup" hx-target="#app-content">
                    Настройки
                </button>
            </div>
        </div>
    </div>

    <!-- Основной контент -->
    <div class="col-md-9">
        <div class="content-area" id="main-content">
            <!-- Рекомендации на сегодня -->
            <div class="mb-4">
                <h4 class="mb-3">Рекомендации на сегодня</h4>
                <div class="alert alert-success" role="alert">
                    <h6 class="alert-heading">✨ Отличное время для общения!</h6>
                    <p class="mb-2">Сегодня <?= \htmlspecialchars($partnerName ?? 'ваша спутница') ?> находится в хорошем настроении. Это отличное время для:</p>
                    <ul class="mb-0">
                        <li>Обсуждения планов на выходные</li>
                        <li>Романтического ужина дома</li>
                        <li>Совместных активностей</li>
                    </ul>
                </div>
            </div>

            <!-- Календарь настроения -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Календарь настроения</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary active">Июль</button>
                        <button type="button" class="btn btn-outline-secondary">Август</button>
                    </div>
                </div>

                <div class="calendar-container">
                    <div class="row text-center text-muted mb-2">
                        <div class="col">Пн</div>
                        <div class="col">Вт</div>
                        <div class="col">Ср</div>
                        <div class="col">Чт</div>
                        <div class="col">Пт</div>
                        <div class="col">Сб</div>
                        <div class="col">Вс</div>
                    </div>

                    <!-- Первая неделя -->
                    <div class="row mb-2">
                        <div class="col text-center">
                            <div class="calendar-day">1</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-good"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">2</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-great"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">3</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-neutral"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">4</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-bad"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">5</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-terrible"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">6</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-bad"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">7</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-neutral"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Вторая неделя -->
                    <div class="row mb-2">
                        <div class="col text-center">
                            <div class="calendar-day">8</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-good"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">9</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-great"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">10</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-great"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">11</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-good"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">12</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-good"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day bg-primary text-white">13</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-great"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">14</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-great"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Третья неделя -->
                    <div class="row mb-2">
                        <div class="col text-center">
                            <div class="calendar-day">15</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-good"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">16</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-neutral"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">17</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-neutral"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">18</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-bad"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">19</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-bad"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">20</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-terrible"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">21</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-terrible"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Четвертая неделя -->
                    <div class="row mb-2">
                        <div class="col text-center">
                            <div class="calendar-day">22</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-bad"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">23</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-neutral"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">24</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-good"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">25</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-good"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">26</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-great"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">27</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-great"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">28</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-good"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Пятая неделя -->
                    <div class="row">
                        <div class="col text-center">
                            <div class="calendar-day">29</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-neutral"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">30</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-bad"></div>
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="calendar-day">31</div>
                            <div class="d-flex justify-content-center">
                                <div class="mood-indicator mood-terrible"></div>
                            </div>
                        </div>
                        <div class="col"></div>
                        <div class="col"></div>
                        <div class="col"></div>
                        <div class="col"></div>
                    </div>
                </div>

                <!-- Легенда -->
                <div class="mt-3">
                    <small class="text-muted">Легенда: </small>
                    <span class="me-3"><div class="mood-indicator mood-great d-inline-block me-1"></div><small>Отлично</small></span>
                    <span class="me-3"><div class="mood-indicator mood-good d-inline-block me-1"></div><small>Хорошо</small></span>
                    <span class="me-3"><div class="mood-indicator mood-neutral d-inline-block me-1"></div><small>Нейтрально</small></span>
                    <span class="me-3"><div class="mood-indicator mood-bad d-inline-block me-1"></div><small>Плохо</small></span>
                    <span><div class="mood-indicator mood-terrible d-inline-block me-1"></div><small>Очень плохо</small></span>
                </div>
            </div>

            <!-- Быстрые действия -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">🎁 Подарки и сюрпризы</h6>
                            <p class="card-text small text-muted">Получите персональные рекомендации по подаркам</p>
                            <button class="btn btn-outline-primary btn-sm" hx-get="/gifts" hx-target="#main-content">
                                Идеи подарков
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">💬 Помощь в общении</h6>
                            <p class="card-text small text-muted">Советы для сложных разговоров</p>
                            <button class="btn btn-outline-primary btn-sm" hx-get="/communication" hx-target="#main-content">
                                Помощь в диалоге
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
