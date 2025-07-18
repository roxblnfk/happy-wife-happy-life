<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var \Spiral\Router\RouterInterface $router
 * @var \App\Feature\Calendar\Input\EventForm|null $form
 * @var \App\Module\Calendar\Info\Event|null $event
 * @var string $action
 * @var array<string, string>|null $errors
 */

use App\Module\Calendar\Info\Event;

$errors = $errors ?? [];
$isEdit = $event !== null;
$title = $isEdit ? $event->title : ($form?->title ?? '');
$date = $isEdit ? $event->date->__toString() : ($form?->date?->__toString() ?? '');
$period = $isEdit ? $event->period : ($form?->period ?? '');
$description = $isEdit ? $event->description : ($form?->description ?? '');
$buttonText = $isEdit ? 'Сохранить изменения' : 'Создать событие';
$buttonIcon = $isEdit ? 'bi-check-lg' : 'bi-plus-lg';
?>

<form hx-post="<?= $action ?>" 
      hx-target="#event-form-container" 
      hx-swap="outerHTML"
      class="needs-validation" 
      novalidate>
    
    <div id="event-form-container">
        <!-- Title field -->
        <div class="mb-3">
            <label for="event-title" class="form-label">
                <i class="bi bi-calendar-event me-1"></i>
                Название события <span class="text-danger">*</span>
            </label>
            <input type="text" 
                   class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>" 
                   id="event-title" 
                   name="title" 
                   value="<?= \htmlspecialchars($title) ?>"
                   placeholder="Например: День рождения, Годовщина свадьбы"
                   required>
            <?php if (isset($errors['title'])): ?>
                <div class="invalid-feedback">
                    <?= \htmlspecialchars($errors['title']) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Date field -->
        <div class="mb-3">
            <label for="event-date" class="form-label">
                <i class="bi bi-calendar-date me-1"></i>
                Дата события <span class="text-danger">*</span>
            </label>
            <input type="date" 
                   class="form-control <?= isset($errors['date']) ? 'is-invalid' : '' ?>" 
                   id="event-date" 
                   name="date" 
                   value="<?= $date ?>"
                   required>
            <?php if (isset($errors['date'])): ?>
                <div class="invalid-feedback">
                    <?= \htmlspecialchars($errors['date']) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Period field -->
        <div class="mb-3">
            <label for="event-period" class="form-label">
                <i class="bi bi-arrow-repeat me-1"></i>
                Повторение
            </label>
            <select class="form-select <?= isset($errors['period']) ? 'is-invalid' : '' ?>" 
                    id="event-period" 
                    name="period">
                <option value="" <?= ($period === '') ? 'selected' : '' ?>>
                    Однократное событие
                </option>
                <option value="<?= Event::PERIOD_ANNUAL ?>" <?= ($period === Event::PERIOD_ANNUAL) ? 'selected' : '' ?>>
                    Ежегодно
                </option>
                <option value="1 month" <?= ($period === '1 month') ? 'selected' : '' ?>>
                    Ежемесячно
                </option>
                <option value="1 week" <?= ($period === '1 week') ? 'selected' : '' ?>>
                    Еженедельно
                </option>
            </select>
            <?php if (isset($errors['period'])): ?>
                <div class="invalid-feedback">
                    <?= \htmlspecialchars($errors['period']) ?>
                </div>
            <?php endif; ?>
            <div class="form-text">
                <i class="bi bi-info-circle me-1"></i>
                Повторяющиеся события будут автоматически отображаться в календаре
            </div>
        </div>

        <!-- Description field -->
        <div class="mb-3">
            <label for="event-description" class="form-label">
                <i class="bi bi-card-text me-1"></i>
                Описание
            </label>
            <textarea class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" 
                      id="event-description" 
                      name="description" 
                      rows="3" 
                      placeholder="Дополнительная информация о событии"><?= \htmlspecialchars($description) ?></textarea>
            <?php if (isset($errors['description'])): ?>
                <div class="invalid-feedback">
                    <?= \htmlspecialchars($errors['description']) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Error message -->
        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?= \htmlspecialchars($errors['general']) ?>
            </div>
        <?php endif; ?>

        <!-- Submit button -->
        <div class="d-flex justify-content-end gap-2">
            <button type="button" 
                    class="btn btn-secondary" 
                    data-bs-dismiss="modal">
                <i class="bi bi-x-lg me-1"></i>
                Отмена
            </button>
            <button type="submit" 
                    class="btn btn-primary">
                <i class="<?= $buttonIcon ?> me-1"></i>
                <?= $buttonText ?>
            </button>
        </div>
    </div>
</form>

<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var form = document.querySelector('.needs-validation');
        if (form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        }
    }, false);
})();
</script>
