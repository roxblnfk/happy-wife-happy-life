<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var \Spiral\Router\RouterInterface $router
 * @var \App\Module\Common\Config\GlobalStateConfig $globalState
 * @var null|\App\Module\LLM\Config\LLMConfig $LLMConfig
 * @var list<Model> $models Available models for the selected platform
 */

use App\Feature\Setup\Controller;
use App\Module\LLM\Config\Platforms;
use Symfony\AI\Platform\Model;

?>

<div class="mt-4">
    <h5 class="mb-3">2. Выбор модели</h5>
    <div class="alert alert-success" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        Подключение к <?= \htmlspecialchars($LLMConfig->platform->name) ?> успешно установлено
    </div>

    <form hx-post="/setup/llm" hx-target="#app-content" hx-swap="innerHTML">
        <input hidden="hidden" name="llm_provider" value="<?= \htmlspecialchars($LLMConfig->platform->value) ?>" />
        <input hidden="hidden" name="api_token" value="<?= \htmlspecialchars($LLMConfig->apiKey) ?>" />
        <div class="form-floating mb-3">
            <?php if ($LLMConfig->platform === Platforms::Local): ?>
            <input type="text" class="form-control" id="modelName" name="model_name"
                   value="<?= \htmlspecialchars($LLMConfig->model ?? '') ?>" required>
            <?php else: ?>
            <select class="form-select" id="modelName" name="model_name" required>
                <option value="">Выберите модель</option>
                <?php foreach ($models as $model): ?>
                    <option value="<?= \htmlspecialchars($model->getName()) ?>" <?= $model->getName() === $LLMConfig->model ? 'selected' : '' ?>>
                        <?= \htmlspecialchars($model->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>
            <label for="modelName">Модель</label>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary"
                    hx-get="<?= $router->uri(Controller::ROUTE_SETUP, $globalState->configured ? [] : ['page' => 'relation']) ?>"
                    hx-target="#app-content">
                Назад
            </button>
            <button type="submit" class="btn btn-primary btn-next">
                Сохранить
                <span class="htmx-indicator spinner-border spinner-border-sm ms-2" role="status"></span>
            </button>
        </div>
    </form>
</div>
