<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var \Spiral\Router\RouterInterface $router
 * @var \App\Module\Common\Config\GlobalStateConfig $globalState
 * @var null|\App\Module\Common\Config\RelationshipInfo $relationInfo
 * @var null|\App\Module\Common\Config\UserInfo $userInfo
 * @var null|\App\Module\Common\Config\WomenInfo $womenInfo
 * @var null|\App\Module\LLM\Config\LLMConfig $LLMConfig
 * @var null|\App\Module\Calendar\Info\WomenCycleInfo $womenCycleInfo
 * @var array<Event> $events
 */

use App\Feature\Setup\Controller;
use App\Module\Calendar\Info\Event;
use App\Module\Common\Config\RelationType;

$stepIndicator = 3;
include __DIR__ . '/step-indicator.php';
?>

<div class="setup-card">
    <h3 class="mb-4">Шаг 3: Важные даты</h3>
    <p class="text-muted mb-4">Данные, от которых зависит ваша жизнь.</p>

    <form hx-post="/setup/calendar" hx-target="#app-content" hx-swap="innerHTML">
        <!-- Менструальный цикл -->
        <h5 class="mb-3">Менструальный цикл</h5>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="cycleLength" name="cycle_length" placeholder="28"
                           min="21" max="35"
                           value="<?= $womenCycleInfo?->cycleLength ?? 28 ?>"
                           required />
                    <label for="cycleLength">Длительность цикла (дни)</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="periodLength" name="period_length" placeholder="5"
                           min="3" max="8"
                           value="<?= $womenCycleInfo?->periodLength ?? 5 ?>"
                           required />
                    <label for="periodLength">Длительность месячных (дни)</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="lastPeriodStart" name="last_period_start" required
                           value="<?= $womenCycleInfo?->lastPeriodStart?->__toString() ?? '' ?>" />
                    <label for="lastPeriodStart">Дата начала последних месячных</label>
                </div>
            </div>
        </div>

        <!-- Важные даты -->
        <h5 class="mb-3">Важные даты</h5>
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="partnerBirthday" name="birthday"
                           value="<?= $womenInfo?->birthday?->__toString() ?? '' ?>"
                    />
                    <label for="partnerBirthday">День рождения спутницы</label>
                </div>
            </div>
            <?php if ($relationInfo?->relationType === RelationType::Married): ?>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="anniversary" name="anniversary"
                           value="<?= $relationInfo?->anniversary?->__toString() ?? '' ?>"
                    />
                    <label for="anniversary">Годовщина свадьбы</label>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Дополнительные важные даты -->
        <h5 class="mb-3">Дополнительные важные даты</h5>

        <div id="existing-events">
            <?php foreach ($events as $event): ?>
            <div class="row mb-2 existing-event-row" id="event-row-<?= $event->uuid->toString() ?>">
                <div class="col-md-3">
                    <div class="form-control-plaintext fw-semibold text-primary">
                        <?= $event->date->format('d.m.Y') ?>
                        <?php if ($event->period): ?>
                            <small class="text-muted ms-2">
                                <i class="bi bi-arrow-repeat"></i>
                                <?php
                                echo match ($event->period) {
                                    Event::PERIOD_ANNUAL => 'ежегодно',
                                    '1 month' => 'ежемесячно',
                                    '1 week' => 'еженедельно',
                                    default => $event->period,
                                };
                            ?>
                            </small>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-control-plaintext">
                        <?= \htmlspecialchars($event->title) ?>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger btn-sm"
                            hx-delete="<?= $router->uri(Controller::ROUTE_SETUP_REMOVE_EVENT, ['uuid' => $event->uuid->toString()]) ?>"
                            hx-target="#event-row-<?= $event->uuid->toString() ?>"
                            hx-swap="outerHTML"
                            hx-confirm="Удалить событие '<?= \htmlspecialchars($event->title) ?>'?"
                            title="Удалить событие">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div id="additional-dates">
        </div>

        <button type="button" class="btn btn-outline-secondary btn-sm mb-4" onclick="addCustomDate()">
            <i class="bi bi-plus-lg me-1"></i>
            Добавить ещё дату
        </button>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary"
                    hx-get="<?= $router->uri(Controller::ROUTE_SETUP, $globalState->configured ? [] : ['page' => 'llm']) ?>"
                    hx-target="#app-content">
                <i class="bi bi-arrow-left me-1"></i>
                Назад
            </button>

            <button type="submit" class="btn btn-success btn-next">
                <i class="bi bi-check-lg me-1"></i>
                Сохранить
                <span class="htmx-indicator spinner-border spinner-border-sm ms-2" role="status"></span>
            </button>
        </div>
    </form>
</div>

<script>
let customDateCount = 1;

function addCustomDate() {
    const container = document.getElementById('additional-dates');
    const newRow = document.createElement('div');
    newRow.className = 'row custom-date-row';
    newRow.id = `custom-date-row-${customDateCount}`;
    newRow.innerHTML = `
        <div class="col-md-3">
            <div class="form-floating mb-3">
                <input type="date" class="form-control" id="customDateValue${customDateCount}" required
                       name="important_date[${customDateCount}][date]"
                />
                <label for="customDateValue${customDateCount}">Дата</label>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="customDate${customDateCount}" required
                       name="important_date[${customDateCount}][title]"
                       placeholder="Название события"
                />
                <label for="customDate${customDateCount}">Название события</label>
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-outline-danger btn-sm"
                    onclick="removeCustomDateRow('custom-date-row-${customDateCount}')"
                    title="Удалить">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <input type="hidden" name="important_date[${customDateCount}][period]" value="1 year"/>
        <input type="hidden" name="important_date[${customDateCount}][description]" value=""/>
    `;
    customDateCount++;
    container.appendChild(newRow);
}

function removeCustomDateRow(rowId) {
    const row = document.getElementById(rowId);
    if (row) {
        row.remove();
    }
}

// Автоматически устанавливаем сегодняшнюю дату как максимальную для последних месячных
document.getElementById('lastPeriodStart').max = new Date().toISOString().split('T')[0];
</script>
