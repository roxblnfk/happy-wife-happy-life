<?php
// Проверяем, есть ли сохраненные настройки
$hasSettings = false; // TODO: проверить в БД

if (!$hasSettings) {
    // Начинаем процесс настройки
    echo '<div class="app-header">
        <h1 class="app-title">Happy Wife - Happy Life</h1>
        <p class="app-subtitle">Помощник в построении гармоничных отношений</p>
    </div>
    
    <div class="step-indicator">
        <div class="step active">1</div>
        <div class="step-line"></div>
        <div class="step">2</div>
        <div class="step-line"></div>
        <div class="step">3</div>
    </div>
    
    <div class="setup-card">
        <h3 class="mb-4">Шаг 1: Анкетные данные</h3>
        <p class="text-muted mb-4">Давайте знакомиться! Расскажите немного о себе и вашей спутнице.</p>
        
        <form hx-post="/setup/personal" hx-target="#app-content" hx-swap="innerHTML">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="userName" name="user_name" placeholder="Ваше имя" required>
                        <label for="userName">Ваше имя</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="partnerName" name="partner_name" placeholder="Имя спутницы" required>
                        <label for="partnerName">Имя вашей спутницы</label>
                    </div>
                </div>
            </div>
            
            <div class="form-floating mb-4">
                <select class="form-select" id="relationshipType" name="relationship_type" required>
                    <option value="">Выберите тип отношений</option>
                    <option value="dating">Встречаемся</option>
                    <option value="engaged">Помолвлены</option>
                    <option value="married">Женаты</option>
                    <option value="longterm">Длительные отношения</option>
                </select>
                <label for="relationshipType">Тип отношений</label>
            </div>
            
            <div class="form-floating mb-4">
                <input type="date" class="form-control" id="relationshipStart" name="relationship_start" required>
                <label for="relationshipStart">Дата начала отношений</label>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary btn-next">
                    Далее
                    <span class="htmx-indicator spinner-border spinner-border-sm ms-2" role="status"></span>
                </button>
            </div>
        </form>
    </div>';
} else {
    // Загружаем главную страницу приложения
    include 'main.php';
}
?>