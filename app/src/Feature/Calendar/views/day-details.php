<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var \App\Module\Calendar\Info\CycleDay $cycleDay
 * @var \App\Application\Value\Date $date
 * @var array<\App\Module\Agent\AgentCard> $agents
 */

// Определяем подходящих агентов для текущего дня
$suitableAgents = [];

// Агенты в зависимости от фазы цикла
switch ($cycleDay->phase) {
    case \App\Module\Calendar\Info\CyclePhase::Menstrual:
        $suitableAgents = \array_merge($suitableAgents, [
            'culinary_helper', // помощь с едой для комфорта
            'care_specialist', // особая забота
            'surprise_planner', // небольшие сюрпризы для поднятия настроения
        ]);
        break;

    case \App\Module\Calendar\Info\CyclePhase::Follicular:
        $suitableAgents = \array_merge($suitableAgents, [
            'event_manager', // планирование активностей
            'vacation_planner', // планирование поездок
            'compliment_master', // поддержка позитивного настроения
        ]);
        break;

    case \App\Module\Calendar\Info\CyclePhase::Ovulation:
        $suitableAgents = \array_merge($suitableAgents, [
            'romantic_planner', // романтические планы
            'surprise_planner', // сюрпризы
            'gift_guide', // подарки для особого времени
        ]);
        break;

    case \App\Module\Calendar\Info\CyclePhase::Luteal:
        $suitableAgents = \array_merge($suitableAgents, [
            'signal_translator', // понимание сигналов
            'diplomat', // деликатное общение
            'compliment_master', // поддержка настроения
        ]);
        break;
}

// Агенты в зависимости от уровня опасности
switch ($cycleDay->dangerLevel) {
    case \App\Module\Calendar\Info\DangerLevel::Safe:
        $suitableAgents = \array_merge($suitableAgents, [
            'compliment_master',
            'romantic_planner',
            'gift_guide',
        ]);
        break;

    case \App\Module\Calendar\Info\DangerLevel::Caution:
        $suitableAgents = \array_merge($suitableAgents, [
            'signal_translator',
            'culinary_helper',
            'surprise_planner',
        ]);
        break;

    case \App\Module\Calendar\Info\DangerLevel::High:
        $suitableAgents = \array_merge($suitableAgents, [
            'diplomat',
            'apology_expert',
            'dispute_mediator',
        ]);
        break;

    case \App\Module\Calendar\Info\DangerLevel::Extreme:
        $suitableAgents = \array_merge($suitableAgents, [
            'crisis_manager',
            'sos_consultant',
            'couple_therapist',
        ]);
        break;
}

// Убираем дубликаты и получаем карточки агентов
$suitableAgents = \array_unique($suitableAgents);
$agentCards = [];
foreach ($suitableAgents as $agentName) {
    isset($agents[$agentName]) and $agentCards[] = $agents[$agentName];
}
?>

<div class="text-center mb-3">
    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2"
         style="width: 60px; height: 60px; background-color: <?= $cycleDay->getColor() ?>20; border: 2px solid <?= $cycleDay->getColor() ?>;">
        <i class="<?= $cycleDay->getIcon() ?>" style="font-size: 1.5rem; color: <?= $cycleDay->getColor() ?>;"></i>
    </div>
    <h6 class="mb-1"><?= $date->format('d.m.Y') ?></h6>
    <small class="text-muted">День <?= $cycleDay->dayOfCycle ?> цикла</small>
</div>

<div class="row g-3">
    <!-- Фаза цикла -->
    <div class="col-12">
        <div class="d-flex align-items-center p-3 rounded" style="background-color: <?= $cycleDay->getColor() ?>10; border-left: 4px solid <?= $cycleDay->getColor() ?>;">
            <div class="flex-grow-1">
                <div class="fw-semibold text-dark"><?= $cycleDay->getPhaseName() ?></div>
                <small class="text-muted">Фаза цикла</small>
            </div>
        </div>
    </div>

    <!-- Уровень опасности -->
    <div class="col-12">
        <div class="d-flex align-items-center p-3 rounded bg-light">
            <div class="flex-grow-1">
                <div class="fw-semibold" style="color: <?= $cycleDay->getColor() ?>;"><?= $cycleDay->getDangerLevelName() ?></div>
                <small class="text-muted">Уровень внимания</small>
            </div>
            <div class="badge" style="background-color: <?= $cycleDay->getColor() ?>; color: white;">
                <?= $cycleDay->dangerLevel->name ?>
            </div>
        </div>
    </div>

    <!-- Настроение -->
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body p-3">
                <h6 class="card-title mb-2">
                    <i class="bi bi-emoji-smile me-2"></i>
                    Настроение
                </h6>
                <p class="card-text mb-0"><?= \htmlspecialchars($cycleDay->moodDescription) ?></p>
            </div>
        </div>
    </div>

    <!-- Рекомендации -->
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body p-3">
                <h6 class="card-title mb-2">
                    <i class="bi bi-lightbulb me-2"></i>
                    Рекомендации
                </h6>
                <p class="card-text mb-0"><?= \htmlspecialchars($cycleDay->recommendation) ?></p>
            </div>
        </div>
    </div>

    <!-- Подходящие AI агенты -->
    <?php if (!empty($agentCards)): ?>
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body p-3" id="agentCardsContainer">
                    <h6 class="card-title mb-3">
                        <i class="bi bi-robot me-2"></i>
                        Подходящие помощники
                    </h6>
                    <div class="row g-2">
                        <?php foreach ($agentCards as $card): ?>
                            <div class="col-6 col-md-4">
                                <button
                                    type="button"
                                    class="btn btn-outline-<?= $card->color ?> btn-sm w-100 h-100 d-flex flex-column align-items-center p-2 agent-card-btn"
                                    hx-get="/calendar/cycle-agent/<?= $card->alias ?>/<?= $date->format('Y-m-d') ?>"
                                    hx-target="#agentCardsContainer"
                                    hx-trigger="click"
                                    title="<?= \htmlspecialchars($card->description) ?>">
                                    <i class="<?= $card->icon ?> mb-1" style="font-size: 1.2rem;"></i>
                                    <small class="text-center lh-1"><?= \htmlspecialchars($card->name) ?></small>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($cycleDay->isPMS): ?>
        <!-- ПМС предупреждение -->
        <div class="col-12">
            <div class="alert alert-warning mb-0" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Внимание:</strong> Возможен ПМС синдром. Проявите особую деликатность и понимание.
            </div>
        </div>
    <?php endif; ?>

    <?php if ($cycleDay->isPeriod()): ?>
        <!-- Менструация информация -->
        <div class="col-12">
            <div class="alert alert-info mb-0" role="alert">
                <i class="bi bi-droplet-fill me-2"></i>
                <strong>Менструация:</strong> Время особой заботы и внимания. Избегайте физических нагрузок и стрессов.
            </div>
        </div>
    <?php endif; ?>

    <?php if ($cycleDay->isOvulation()): ?>
        <!-- Овуляция информация -->
        <div class="col-12">
            <div class="alert alert-success mb-0" role="alert">
                <i class="bi bi-heart-fill me-2"></i>
                <strong>Овуляция:</strong> Пик фертильности. Время максимальной энергии и позитивного настроения.
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="d-flex justify-content-center mt-4">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
</div>

<style>
    .agent-card-btn {
        min-height: 80px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .agent-card-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .agent-card-btn:active {
        transform: translateY(0);
    }

    .agent-card-btn small {
        font-size: 0.7rem;
        line-height: 1.1;
    }

    /* Стили для различных цветовых схем агентов */
    .btn-outline-primary:hover { background-color: var(--bs-primary); }
    .btn-outline-success:hover { background-color: var(--bs-success); }
    .btn-outline-warning:hover { background-color: var(--bs-warning); }
    .btn-outline-danger:hover { background-color: var(--bs-danger); }
    .btn-outline-info:hover { background-color: var(--bs-info); }
    .btn-outline-secondary:hover { background-color: var(--bs-secondary); }
</style>

<script>
    // Обработка событий для динамически загруженного контента через HTMX
    document.addEventListener('htmx:afterSwap', function(event) {
        // Переинициализация после загрузки контента агента
        if (event.detail.target.id === 'agentCardsContainer') {
            console.log('Agent content loaded in container');
        }
    });

    // Обработка ошибок загрузки агентов
    document.addEventListener('htmx:responseError', function(event) {
        if (event.detail.xhr.responseURL.includes('/calendar/cycle-agent/')) {
            document.getElementById('agentCardsContainer').innerHTML = `
            <h6 class="card-title mb-3">
                <i class="bi bi-robot me-2"></i>
                Подходящие помощники
            </h6>
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Ошибка загрузки:</strong> Не удалось загрузить агента. Попробуйте позже.
            </div>
        `;
        }
    });
</script>
