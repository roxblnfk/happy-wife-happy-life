<div class="app-header">
    <h1 class="app-title">Happy Wife - Happy Life</h1>
    <p class="app-subtitle">Помощник в построении гармоничных отношений</p>
</div>

<div class="step-indicator">
    <div class="step completed">1</div>
    <div class="step-line completed"></div>
    <div class="step active">2</div>
    <div class="step-line"></div>
    <div class="step">3</div>
</div>

<div class="setup-card">
    <h3 class="mb-4">Шаг 2: Настройка AI-помощника</h3>
    <p class="text-muted mb-4">Для работы приложения нужно настроить подключение к языковой модели.</p>
    
    <form hx-post="/setup/llm" hx-target="#app-content" hx-swap="innerHTML">
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
        
        <div id="provider-config">
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="apiToken" name="api_token" placeholder="API токен" required>
                <label for="apiToken">API токен</label>
            </div>
            
            <div class="form-floating mb-3">
                <select class="form-select" id="modelName" name="model_name" required>
                    <option value="">Выберите модель</option>
                    <option value="gpt-4">GPT-4</option>
                    <option value="gpt-3.5-turbo">GPT-3.5 Turbo</option>
                </select>
                <label for="modelName">Модель</label>
            </div>
            
            <div class="form-floating mb-4">
                <input type="url" class="form-control" id="apiEndpoint" name="api_endpoint" placeholder="https://api.openai.com/v1" value="https://api.openai.com/v1">
                <label for="apiEndpoint">API Endpoint (необязательно)</label>
            </div>
        </div>
        
        <div class="alert alert-info" role="alert">
            <h6 class="alert-heading">Безопасность данных</h6>
            <p class="mb-0">Все ваши данные хранятся локально на компьютере. API токен используется только для обращения к выбранной языковой модели.</p>
        </div>
        
        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary" hx-get="/setup/start" hx-target="#app-content">
                Назад
            </button>
            <button type="submit" class="btn btn-primary btn-next">
                Проверить и продолжить
                <span class="htmx-indicator spinner-border spinner-border-sm ms-2" role="status"></span>
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('llmProvider').addEventListener('change', function() {
    const provider = this.value;
    const modelSelect = document.getElementById('modelName');
    const endpointInput = document.getElementById('apiEndpoint');
    
    // Очищаем опции модели
    modelSelect.innerHTML = '<option value="">Выберите модель</option>';
    
    switch(provider) {
        case 'openai':
            modelSelect.innerHTML += '<option value="gpt-4">GPT-4</option>';
            modelSelect.innerHTML += '<option value="gpt-3.5-turbo">GPT-3.5 Turbo</option>';
            endpointInput.value = 'https://api.openai.com/v1';
            break;
        case 'anthropic':
            modelSelect.innerHTML += '<option value="claude-3-opus">Claude 3 Opus</option>';
            modelSelect.innerHTML += '<option value="claude-3-sonnet">Claude 3 Sonnet</option>';
            endpointInput.value = 'https://api.anthropic.com';
            break;
        case 'google':
            modelSelect.innerHTML += '<option value="gemini-pro">Gemini Pro</option>';
            modelSelect.innerHTML += '<option value="gemini-pro-vision">Gemini Pro Vision</option>';
            endpointInput.value = 'https://generativelanguage.googleapis.com/v1';
            break;
        case 'local':
            modelSelect.innerHTML += '<option value="llama2">Llama 2</option>';
            modelSelect.innerHTML += '<option value="mistral">Mistral</option>';
            modelSelect.innerHTML += '<option value="custom">Другая модель</option>';
            endpointInput.value = 'http://localhost:11434/v1';
            break;
    }
});
</script>