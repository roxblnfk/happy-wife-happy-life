<?php
/**
 * @var array $models - Array of available models
 * @var null|\App\Module\Common\Config\LLMConfig $LLMConfig
 */
?>

<div class="mt-4">
    <h5 class="mb-3">2. Выбор модели</h5>
    <div class="alert alert-success" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        Подключение к <?= htmlspecialchars($LLMConfig->provider->name) ?> успешно установлено
    </div>

    <form hx-post="/setup/llm/model" hx-target="#app-content" hx-swap="innerHTML">
        <div class="form-floating mb-3">
            <select class="form-select" id="modelName" name="model_name" required>
                <option value="">Выберите модель</option>
                <?php foreach ($models as $model): ?>
                    <option value="<?= htmlspecialchars($model['id']) ?>">
                        <?= htmlspecialchars($model['name']) ?>
                        <?php if (isset($model['description'])): ?>
                            - <?= htmlspecialchars($model['description']) ?>
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="modelName">Модель</label>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" onclick="location.reload()">
                Изменить провайдера
            </button>
            <button type="submit" class="btn btn-primary btn-next">
                Сохранить
                <span class="htmx-indicator spinner-border spinner-border-sm ms-2" role="status"></span>
            </button>
        </div>
    </form>
</div>
