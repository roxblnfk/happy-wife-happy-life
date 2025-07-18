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
 */

use App\Feature\Setup\Controller;
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
        <div id="additional-dates">
        </div>

        <button type="button" class="btn btn-outline-secondary btn-sm mb-4" onclick="addCustomDate()">
            + Добавить ещё дату
        </button>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary"
                    hx-get="<?= $router->uri(Controller::ROUTE_SETUP, $globalState->configured ? [] : ['page' => 'llm']) ?>"
                    hx-target="#app-content">
                Назад
            </button>

            <button type="submit" class="btn btn-success btn-next">
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
    newRow.className = 'row';
    newRow.innerHTML = `
        <div class="col-md-4">
            <div class="form-floating mb-3">
                <input type="date" class="form-control" id="customDateValue${customDateCount}"
                       name="important_date[${customDateCount}][value]"
                />
                <label for="customDateValue${customDateCount}">Дата</label>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="customDate${customDateCount}"
                       name="important_date[${customDateCount}][title]"
                       placeholder="Название события"
                />
                <label for="customDate${customDateCount}">Дополнительная важная дата</label>
            </div>
        </div>
    `;
    customDateCount++;
    container.appendChild(newRow);
}

// Автоматически устанавливаем сегодняшнюю дату как максимальную для последних месячных
document.getElementById('lastPeriodStart').max = new Date().toISOString().split('T')[0];
</script>
