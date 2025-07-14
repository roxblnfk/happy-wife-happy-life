<?php
/**
 * @var \App\Module\Common\Config\GlobalStateConfig $globalState
 * @var null|\App\Module\Common\Config\RelationConfig $relationConfig
 * @var null|\App\Module\Common\Config\LLMConfig $LLMConfig
 * @var null|\App\Module\Common\Config\CalendarConfig $calendarConfig
 */

$stepIndicator = 2;
include __DIR__ . '/step-indicator.php';
?>

<div class="setup-card">
    <h3 class="mb-4">Шаг 2: Настройка AI-помощника</h3>
    <p class="text-muted mb-4">Для работы приложения нужно настроить подключение к языковой модели.</p>

    <!-- Stage 1: Provider and Token Configuration -->
    <div id="provider-form">
        <h5 class="mb-3">1. Выбор провайдера</h5>
        <form hx-post="/setup/llm/provider" hx-target="#model-selection" hx-swap="innerHTML">
            <div class="form-floating mb-3">
                <select class="form-select" id="llmProvider" name="llm_provider" required>
                    <option value="">Выберите провайдера</option>
                    <option value="openai">OpenAI (GPT-4, GPT-3.5)</option>
                    <option value="anthropic">Anthropic (Claude)</option>
                    <option value="google">Google (Gemini)</option>
                    <option value="local">Локальная модель (Ollama)</option>
                </select>
                <label for="llmProvider">Провайдер LLM</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="apiToken" name="api_token" placeholder="API токен"  autocomplete="off" required>
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
        <!-- This will be populated by HTMX after successful connection check -->
    </div>
</div>
