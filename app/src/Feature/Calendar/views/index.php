<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var \Spiral\Router\RouterInterface $router
 * @var array<non-empty-string, Event> $events
 */

use App\Application\Value\Date;
use App\Feature\Chat\Controller as ChatController;
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
                <?php foreach ($events as $date => $event): ?>
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

                            <!-- Кнопка помощи -->
                            <div class="flex-shrink-0">
                                <button class="btn btn-outline-secondary btn-sm"
                                        hx-get="<?= $router->uri(ChatController::ROUTE_CHATS)->__toString() ?>"
                                        hx-target="#app-content"
                                        hx-vals='{"agent": "event_manager", "context": "<?= \htmlspecialchars($event->title) ?>"}'>
                                    <i class="bi bi-robot" style="font-size: 0.8rem;"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
