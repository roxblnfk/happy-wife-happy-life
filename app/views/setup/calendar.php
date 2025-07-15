<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var \App\Module\Common\Config\GlobalStateConfig $globalState
 * @var null|\App\Module\Common\Config\RelationConfig $relationConfig
 * @var null|\App\Module\LLM\Config\LLMConfig $LLMConfig
 * @var null|\App\Module\Common\Config\CalendarConfig $calendarConfig
 */

use App\Module\Common\Config\RelationType;

$stepIndicator = 3;
include __DIR__ . '/step-indicator.php';
?>

<div class="setup-card">
    <h3 class="mb-4">Шаг 3: Персональные данные</h3>
    <p class="text-muted mb-4">Последний шаг - расскажите о важных датах и особенностях цикла вашей спутницы.</p>

    <form hx-post="/setup/complete" hx-target="#app-content" hx-swap="innerHTML">
        <!-- Менструальный цикл -->
        <h5 class="mb-3">Менструальный цикл</h5>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="cycleLength" name="cycle_length" placeholder="28" min="21" max="35" value="28" required>
                    <label for="cycleLength">Длительность цикла (дни)</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="periodLength" name="period_length" placeholder="5" min="3" max="8" value="5" required>
                    <label for="periodLength">Длительность месячных (дни)</label>
                </div>
            </div>
        </div>

        <div class="form-floating mb-4">
            <input type="date" class="form-control" id="lastPeriodStart" name="last_period_start" required>
            <label for="lastPeriodStart">Дата начала последних месячных</label>
        </div>

        <!-- Важные даты -->
        <h5 class="mb-3">Важные даты</h5>
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="partnerBirthday" name="partner_birthday">
                    <label for="partnerBirthday">День рождения спутницы</label>
                </div>
            </div>
            <?php if ($relationConfig?->relationType === RelationType::Married): ?>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="anniversary" name="anniversary">
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

        <!-- Предпочтения и особенности -->
        <h5 class="mb-3">Особенности и предпочтения</h5>
        <div class="form-floating mb-3">
            <textarea class="form-control" id="preferences" name="preferences" style="height: 100px" placeholder="Например: любит шоколад во время ПМС, не переносит шум по утрам..."></textarea>
            <label for="preferences">Что важно учитывать?</label>
        </div>

        <div class="form-floating mb-4">
            <textarea class="form-control" id="triggers" name="triggers" style="height: 100px" placeholder="Например: критика внешности, обсуждение веса, упоминание бывших..."></textarea>
            <label for="triggers">Что может расстроить или разозлить?</label>
        </div>

        <div class="d-flex justify-content-between">
            <?php if (!$globalState->configured): ?>
            <button type="button" class="btn btn-outline-secondary" hx-get="/setup/llm" hx-target="#app-content">
                Назад
            </button>
            <?php endif; ?>

            <button type="submit" class="btn btn-success btn-next">
                Завершить настройку
                <span class="htmx-indicator spinner-border spinner-border-sm ms-2" role="status"></span>
            </button>
        </div>
    </form>
</div>

<script>
let customDateCount = 1;

function addCustomDate() {
    customDateCount++;
    const container = document.getElementById('additional-dates');
    const newRow = document.createElement('div');
    newRow.className = 'row';
    newRow.innerHTML = `
        <div class="col-md-4">
            <div class="form-floating mb-3">
                <input type="date" class="form-control" id="customDateValue${customDateCount}" name="custom_date_values[]">
                <label for="customDateValue${customDateCount}">Дата</label>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="customDate${customDateCount}" name="custom_dates[]" placeholder="Название события">
                <label for="customDate${customDateCount}">Дополнительная важная дата</label>
            </div>
        </div>
    `;
    container.appendChild(newRow);
}

// Автоматически устанавливаем сегодняшнюю дату как максимальную для последних месячных
document.getElementById('lastPeriodStart').max = new Date().toISOString().split('T')[0];
</script>
