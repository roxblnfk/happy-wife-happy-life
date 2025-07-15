<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var \App\Module\Common\Config\GlobalStateConfig $globalState
 * @var null|\App\Module\Common\Config\RelationConfig $relationConfig
 * @var null|\App\Module\Common\Config\UserConfig $userConfig
 * @var null|\App\Module\Common\Config\WomenPersonConfig $womenPersonalConfig
 * @var null|\App\Module\LLM\Config\LLMConfig $LLMConfig
 * @var null|\App\Module\Common\Config\WomenCycleConfig $womenCycleConfig
 */

$stepIndicator = 4;
include __DIR__ . '/step-indicator.php';
?>

<div class="setup-card">
    <h3 class="mb-4">Шаг 4: Персональные данные</h3>
    <p class="text-muted mb-4">Последний шаг - расскажите о важных датах и особенностях цикла вашей спутницы.</p>

    <form hx-post="/setup/personal" hx-target="#app-content" hx-swap="innerHTML">
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
            <button type="button" class="btn btn-outline-secondary" hx-get="/setup/calendar" hx-target="#app-content">
                Назад
            </button>
            <?php endif; ?>

            <button type="submit" class="btn btn-success btn-next">
                Сохранить
                <span class="htmx-indicator spinner-border spinner-border-sm ms-2" role="status"></span>
            </button>
        </div>
    </form>
</div>
