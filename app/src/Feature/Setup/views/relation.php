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

$stepIndicator = 1;
include __DIR__ . '/step-indicator.php';
?>

<div class="setup-card">
    <h3 class="mb-4">Шаг 1: Анкетные данные</h3>
    <p class="text-muted mb-4">Давайте знакомиться! Расскажите немного о себе и вашей спутнице.</p>
    <p class="text-muted mb-4">Приложение хранит все данные локально, но учтите, что они всё-равно утекут в LLM.</p>

    <form hx-post="/setup/relation" hx-target="#app-content" hx-swap="innerHTML">
        <div class="row">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="userName" name="user_name" required
                           placeholder="Ваше имя"
                           value="<?= \htmlspecialchars($userInfo?->name ?? '') ?>">
                    <label for="userName">Ваше имя</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="partnerName" name="partner_name" required
                           placeholder="Имя спутницы"
                           value="<?= \htmlspecialchars($womenInfo?->name ?? '') ?>">
                    <label for="partnerName">Имя вашей спутницы</label>
                </div>
            </div>
        </div>

        <div class="form-floating mb-4">
            <select class="form-select" id="relationshipType" name="relationship_type" required>
                <option value="dating" <?= ($relationInfo?->relationType === RelationType::Dating) ? 'selected' : '' ?>>Встречаемся</option>
                <option value="engaged" <?= ($relationInfo?->relationType === RelationType::Engaged) ? 'selected' : '' ?>>Помолвлены</option>
                <option value="married" <?= ($relationInfo?->relationType === RelationType::Married) ? 'selected' : '' ?>>Женаты</option>
                <option value="longterm" <?= ($relationInfo?->relationType === RelationType::LongTerm) ? 'selected' : '' ?>>Длительные отношения</option>
            </select>
            <label for="relationshipType">Тип отношений</label>
        </div>

        <div class="form-floating mb-4">
            <textarea class="form-control" id="description" name="description" style="height: 100px"
            ><?= \htmlspecialchars($relationInfo?->description ?? '') ?></textarea>
            <label for="description">
                Расскажите о ваших отношениях
                <small class="text-muted"> (характер отношений, детали совместной жизни, общие интересы и т.д.)</small>
            </label>
        </div>

        <div class="d-flex justify-content-between">
            <?php if ($globalState->configured): ?>
                <button type="button" class="btn btn-outline-secondary"
                        hx-get="<?= $router->uri(Controller::ROUTE_SETUP) ?>" hx-target="#app-content"
                >Назад</button>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary btn-next">
                Сохранить
                <span class="htmx-indicator spinner-border spinner-border-sm ms-2" role="status"></span>
            </button>
        </div>
    </form>
</div>
