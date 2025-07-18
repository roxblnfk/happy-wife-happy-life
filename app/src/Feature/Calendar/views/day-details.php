<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var \App\Module\Calendar\Info\CycleDay $cycleDay
 * @var \App\Application\Value\Date $date
 */
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
                <p class="card-text mb-0"><?= htmlspecialchars($cycleDay->moodDescription) ?></p>
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
                <p class="card-text mb-0"><?= htmlspecialchars($cycleDay->recommendation) ?></p>
            </div>
        </div>
    </div>

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
