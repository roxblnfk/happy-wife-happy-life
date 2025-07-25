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

$stepIndicator = 4;
include __DIR__ . '/step-indicator.php';
?>

<div class="setup-card">
    <h3 class="mb-4">Шаг 4: Характеристика</h3>
    <p class="text-muted mb-4">
        Расскажите о предпочтениях и особенностях вашей спутницы. Это поможет AI-помощнику лучше её понимать.
    </p>

    <form hx-post="/setup/personal" hx-target="#app-content" hx-swap="innerHTML">
        <!-- Предпочтения и особенности -->
        <h5 class="mb-3">Характер, особенности и предпочтения</h5>
        <div class="form-floating mb-3">
            <textarea class="form-control" id="preferences" name="preferences" style="height: 100px"
            ><?= \htmlspecialchars($womenInfo?->preferences ?? 'Любит котиков и внимание. Аллергия на шоколад.') ?></textarea>
            <label for="preferences">Что важно учитывать?</label>
        </div>

        <div class="form-floating mb-4">
            <textarea class="form-control" id="triggers" name="triggers" style="height: 100px"
            ><?= \htmlspecialchars($womenInfo?->triggers ?? 'Обсуждение веса.') ?></textarea>
            <label for="triggers">Что может расстроить или разозлить?</label>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary"
                    hx-get="<?= $router->uri(Controller::ROUTE_SETUP, $globalState->configured ? [] : ['page' => 'calendar']) ?>"
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
