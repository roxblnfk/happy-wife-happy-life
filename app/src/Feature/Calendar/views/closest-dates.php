<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var \Spiral\Router\RouterInterface $router
 * @var list<Event> $events
 */

use App\Application\Value\Date;
use App\Feature\Calendar\Controller;
use App\Module\Calendar\Info\Event;

if ($events === []) {
    return;
}
?>

<div class="card h-100" style="border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0;">
        <h5 class="mb-0">
            <i class="bi bi-calendar-event me-2"></i>
            Ближайшие события
        </h5>
        <small class="text-white-50">
            <?= \count($events) ?> событий
        </small>
    </div>

    <div class="card-body p-0">
        <?php if (empty($events)): ?>
            <div class="text-center py-5">
                <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3 mb-0">Нет предстоящих событий</p>
                <small class="text-muted">Добавьте важные даты в настройках</small>
            </div>
        <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($events as $event): ?>
                    <?php
                    $closestDate = $event->getClosestDate();
                    $today = Date::today();
                    $daysUntil = $today->daysTo($closestDate);

                    // Форматируем дату для отображения
                    $closestDateTime = \DateTime::createFromFormat('Y-m-d', $closestDate->__toString());
                    $formattedDate = $closestDateTime ? $closestDateTime->format('d.m') : $closestDate->__toString();

                    $urgencyText = match (true) {
                        $daysUntil === 0 => 'Сегодня',
                        $daysUntil === 1 => 'Завтра',
                        $daysUntil <= 7 => "Через {$daysUntil} дн.",
                        default => $formattedDate,
                    };

                    // Для годовых событий вычисляем количество лет
                    $yearsSince = null;
                    if ($event->period === Event::PERIOD_ANNUAL) {
                        $yearsSince = $event->date->yearsTo($closestDate);
                    }

                    // Определяем цвет текста в зависимости от близости события
                    $urgencyTextClass = match (true) {
                        $daysUntil === 0 => 'text-danger fw-bold',
                        $daysUntil <= 3 => 'text-warning fw-semibold',
                        $daysUntil <= 7 => 'text-info fw-semibold',
                        default => 'text-muted',
                    };
                    ?>

                    <div class="list-group-item list-group-item-action border-0 py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <!-- Дата и количество лет -->
                                <div class="text-center me-3" style="min-width: 60px;">
                                    <div class="<?= $urgencyTextClass ?> fw-bold" style="font-size: 0.9rem;">
                                        <?= $urgencyText ?>
                                    </div>
                                    <?php if ($yearsSince !== null): ?>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            <?= $yearsSince === 0 ? '1-й раз' : "{$yearsSince} лет" ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Заголовок и описание -->
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-semibold"><?= \htmlspecialchars($event->title) ?></h6>
                                    <?php if (!empty($event->description)): ?>
                                        <small class="text-muted"><?= \htmlspecialchars($event->description) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Кнопка помощи с модальным окном -->
                            <div class="flex-shrink-0">
                                <button class="btn btn-outline-secondary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#aiAgentModal"
                                        hx-get="<?= $router->uri(Controller::ROUTE_HELP_AGENT, ['date' => $closestDate->__toString()])->__toString() ?>"
                                        hx-target="#agent-chat-content"
                                        hx-trigger="click"
                                        data-event-title="<?= \htmlspecialchars($event->title) ?>"
                                        data-event-date="<?= $closestDate->__toString() ?>">
                                    <i class="bi bi-robot" style="font-size: 0.8rem;"></i>
                                    <span class="ms-1 d-none d-md-inline">Помощь</span>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Модальное окно AI-агента -->
<div class="modal fade" id="aiAgentModal" tabindex="-1" aria-labelledby="aiAgentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <!-- Заголовок модального окна -->
            <div class="modal-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0;">
                <h5 class="modal-title text-white" id="aiAgentModalLabel">
                    <i class="bi bi-robot me-2"></i>
                    AI-помощник
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>

            <!-- Тело модального окна с чатом -->
            <div class="modal-body p-0" style="height: 500px;">
                <div id="agent-chat-content" class="h-100 d-flex flex-column">
                    <!-- Контент чата будет загружен сюда через HTMX -->
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div class="text-center">
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="visually-hidden">Загрузка...</span>
                            </div>
                            <p class="text-muted">Запуск AI-помощника...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Нижняя часть модального окна -->
            <div class="modal-footer border-0" style="border-radius: 0 0 15px 15px;">
                <small class="text-muted me-auto">
                    <i class="bi bi-shield-check me-1"></i>
                    Данные обрабатываются конфиденциально
                </small>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i>
                    Закрыть
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обработка событий модального окна
    const agentModal = document.getElementById('aiAgentModal');

    agentModal.addEventListener('show.bs.modal', function (event) {
        // Кнопка, которая вызвала модальное окно
        const button = event.relatedTarget;
        const eventTitle = button.getAttribute('data-event-title');
        const eventDate = button.getAttribute('data-event-date');

        // Обновляем заголовок модального окна
        const modalTitle = agentModal.querySelector('#aiAgentModalLabel');
        modalTitle.innerHTML = `<i class="bi bi-robot me-2"></i>Помощь по событию: ${eventTitle}`;
    });

    agentModal.addEventListener('hidden.bs.modal', function (event) {
        // Очищаем содержимое чата при закрытии
        const chatContent = document.getElementById('agent-chat-content');
        chatContent.innerHTML = `
            <div class="d-flex justify-content-center align-items-center h-100">
                <div class="text-center">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                    <p class="text-muted">Запуск AI-помощника...</p>
                </div>
            </div>
        `;
    });
});
</script>

<style>
/* Дополнительные стили для модального окна */
.modal-backdrop {
    backdrop-filter: blur(5px);
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        transform: scale(0.8) translateY(-20px);
        opacity: 0;
    }
    to {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
}

/* Стили для чата */
#agent-chat-content {
    overflow-y: auto;
}

#agent-chat-content::-webkit-scrollbar {
    width: 6px;
}

#agent-chat-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#agent-chat-content::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

#agent-chat-content::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
