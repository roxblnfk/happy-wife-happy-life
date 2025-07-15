<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var null|\App\Module\LLM\Config\LLMConfig $LLMConfig
 * @var list<Model> $models Available models for the selected platform
 */

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
            <select class="form-select" id="modelName" name="model_name" required>
                <option value="">Выберите модель</option>
                <?php foreach ($models as $model): ?>
                    <option value="<?= \htmlspecialchars($model->getName()) ?>">
                        <?= \htmlspecialchars($model->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="modelName">Модель</label>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary btn-next">
                Сохранить
                <span class="htmx-indicator spinner-border spinner-border-sm ms-2" role="status"></span>
            </button>
        </div>
    </form>
</div>
