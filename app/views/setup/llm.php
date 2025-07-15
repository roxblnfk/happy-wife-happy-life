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

use App\Module\LLM\Config\Platforms;

$stepIndicator = 2;
include __DIR__ . '/step-indicator.php';
?>

<div class="setup-card">
    <h3 class="mb-4">Шаг 2: Настройка AI-помощника</h3>
    <p class="text-muted mb-4">Для работы приложения нужно настроить подключение к языковой модели.</p>

    <!-- Stage 1: Provider and Token Configuration -->
    <div id="provider-form">
        <h5 class="mb-3">1. Выбор провайдера</h5>
        <form hx-post="/setup/llm" hx-target="#model-selection" hx-swap="innerHTML">
            <div class="form-floating mb-3">
                <select class="form-select" id="llmProvider" name="llm_provider" required>
                    <option value="" <?= empty($LLMConfig) ? 'selected' : '' ?>>Выберите провайдера</option>
                    <option value="openai" <?= ($LLMConfig?->platform === Platforms::OpenAI ? 'selected' : '') ?>>OpenAI (GPT-4, GPT-3.5)</option>
                    <option value="anthropic" <?= ($LLMConfig?->platform === Platforms::Anthropic ? 'selected' : '') ?>>Anthropic (Claude)</option>
                    <option value="google" <?= ($LLMConfig?->platform === Platforms::Google ? 'selected' : '') ?>>Google (Gemini)</option>
                    <option value="local" <?= ($LLMConfig?->platform === Platforms::Local ? 'selected' : '') ?>>Локальная модель (Ollama)</option>
                </select>
                <label for="llmProvider">Провайдер LLM</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="apiToken" name="api_token" placeholder="API токен"  autocomplete="off" required
                          value="<?= \htmlspecialchars($LLMConfig?->apiKey ?? '') ?>">
                <label for="apiToken">API токен</label>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">
                    Проверить подключение
                    <span class="htmx-indicator spinner-border spinner-border-sm ms-2" role="status"></span>
                </button>
            </div>
        </form>
    </div>

    <!-- Stage 2: Model Selection (populated after connection check) -->
    <div id="model-selection">
        <?php if ($LLMConfig?->model !== null): ?>
            <div class="card">
                <div class="card-body">
                    Выбрана модель: <strong><?= \htmlspecialchars($LLMConfig->model) ?></strong>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!$globalState->configured): ?>
            <button type="button" class="btn btn-outline-secondary" hx-get="/setup/relation" hx-target="#app-content">
                Назад
            </button>
        <?php endif; ?>
    </div>

</div>
