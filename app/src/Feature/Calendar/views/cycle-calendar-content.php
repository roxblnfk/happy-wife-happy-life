<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var \Spiral\Router\RouterInterface $router
 * @var \App\Feature\Calendar\Internal\DTO\CalendarInfo $calendarInfo
 */

use App\Application\Value\Date;
use App\Feature\Calendar\Controller;
?>

<!-- Current Day Info Header -->
<div class="card-header text-white" style="background: linear-gradient(135deg, <?= $calendarInfo->currentCycleDay->getColor() ?> 0%, <?= $calendarInfo->currentCycleDay->getColor() ?>CC 100%); border-radius: 12px 12px 0 0;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">
                <i class="<?= $calendarInfo->currentCycleDay->getIcon() ?> me-2"></i>
                Календарь безопасности
            </h5>
            <small class="text-white-75">
                Сегодня: <?= $calendarInfo->currentCycleDay->getDangerLevelName() ?> • День цикла: <?= $calendarInfo->currentCycleDay->dayOfCycle ?>
            </small>
        </div>
        <div class="text-end">
            <div class="badge bg-white text-dark fw-bold">
                <?= $calendarInfo->currentCycleDay->getPhaseName() ?>
            </div>
        </div>
    </div>

    <!-- Current Day Recommendation -->
    <div class="mt-2 p-2 rounded" style="background-color: rgba(255,255,255,0.1);">
        <small class="d-block mb-1">
            <strong>Настроение:</strong> <?= \htmlspecialchars($calendarInfo->currentCycleDay->moodDescription) ?>
        </small>
        <small class="d-block">
            <strong>Рекомендация:</strong> <?= \htmlspecialchars($calendarInfo->currentCycleDay->recommendation) ?>
        </small>
    </div>
</div>

<div class="card-body p-0">
    <!-- Calendar Navigation -->
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
        <button type="button"
                class="btn btn-outline-secondary btn-sm cycle-calendar-nav"
                hx-post="<?= $router->uri(Controller::ROUTE_CYCLE_CALENDAR_CONTENT, ['year' => $calendarInfo->prevYear, 'month' => $calendarInfo->prevMonth]) ?>"
                hx-target="#cycle-calendar-widget"
                hx-swap="innerHTML"
                hx-indicator=".htmx-indicator">
            <i class="bi bi-chevron-left"></i>
        </button>

        <h6 class="mb-0 fw-bold">
            <?= $calendarInfo->getMonthName() ?> <?= $calendarInfo->year ?>
            <span class="htmx-indicator spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
        </h6>

        <button type="button"
                class="btn btn-outline-secondary btn-sm cycle-calendar-nav"
                hx-post="<?= $router->uri(Controller::ROUTE_CYCLE_CALENDAR_CONTENT, ['year' => $calendarInfo->nextYear, 'month' => $calendarInfo->nextMonth]) ?>"
                hx-target="#cycle-calendar-widget"
                hx-swap="innerHTML"
                hx-indicator=".htmx-indicator">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>

    <!-- Calendar Grid -->
    <div class="p-3">
        <!-- Day Headers -->
        <div class="row g-1 mb-2">
            <?php foreach ($calendarInfo->getDayNames() as $dayName): ?>
                <div class="col text-center">
                    <small class="text-muted fw-semibold"><?= $dayName ?></small>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Calendar Days -->
        <div class="calendar-grid">
            <?php
            $dayCounter = 0;

            // Start calendar grid
            for ($week = 0; $week < 6; $week++): ?>
                <div class="row g-1 mb-1">
                    <?php for ($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++):
                        $cellIndex = $week * 7 + $dayOfWeek;
                        $dayNumber = $cellIndex - $calendarInfo->offset + 1;

                        if ($dayNumber >= 1 && $dayNumber <= $calendarInfo->daysInMonth):
                            $currentDate = Date::fromNumbers($calendarInfo->year, $calendarInfo->month, $dayNumber);
                            $dateString = $currentDate->__toString();
                            $cycleDay = $calendarInfo->getCycleDayForDate($currentDate);
                            $isToday = $calendarInfo->isToday($currentDate);
                            $dayCounter++;
                            ?>
                            <div class="col">
                                <div class="calendar-day w-100 d-flex flex-column justify-content-between position-relative p-2 text-center rounded <?= $cycleDay ? $cycleDay->getCssClass() : '' ?> <?= $isToday ? 'today' : '' ?>"
                                     style="min-height: 60px; aspect-ratio: 1; cursor: pointer; <?= $cycleDay ? 'background-color: ' . $cycleDay->getColor() . '20; border: 1px solid ' . $cycleDay->getColor() . '40;' : '' ?>"
                                     data-bs-toggle="tooltip"
                                     data-bs-placement="top"
                                     data-bs-html="true"
                                     data-date="<?= $dateString ?>"
                                     hx-get="<?= $router->uri(Controller::ROUTE_CYCLE_DAY, ['date' => $dateString]) ?>"
                                     hx-target="#dayDetailsContent"
                                     hx-swap="innerHTML"
                                     hx-trigger="click"
                                     onclick="openDayDetailsModal()"
                                     title="<?= $cycleDay ? '<strong>День ' . $cycleDay->dayOfCycle . ' цикла</strong><br>' . htmlspecialchars($cycleDay->getPhaseName()) . '<br>' . htmlspecialchars($cycleDay->getDangerLevelName()) : '' ?>">

                                    <!-- Top row with icon and day number -->
                                    <div class="d-flex justify-content-between align-items-start w-100">
                                        <?php if ($cycleDay): ?>
                                            <i class="<?= $cycleDay->getIcon() ?>" style="font-size: 0.7rem; color: <?= $cycleDay->getColor() ?>;"></i>
                                        <?php else: ?>
                                            <span></span>
                                        <?php endif; ?>

                                        <div class="fw-semibold <?= $isToday ? 'text-white' : '' ?>" style="font-size: 0.9rem;">
                                            <?= $dayNumber ?>
                                        </div>
                                    </div>

                                    <!-- Bottom row with cycle day number -->
                                    <div class="w-100">
                                        <?php if ($cycleDay): ?>
                                            <div style="font-size: 0.75rem; color: <?= $cycleDay->getColor() ?>; font-weight: 600;">
                                                <?= $cycleDay->dayOfCycle ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($isToday): ?>
                                        <div class="position-absolute top-0 start-0 w-100 h-100 rounded d-flex align-items-center justify-content-center"
                                             style="background-color: <?= $calendarInfo->currentCycleDay->getColor() ?>; opacity: 0.8;">
                                            <span class="text-white fw-bold" style="font-size: 0.7rem;">Сегодня</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="col">
                                <div class="calendar-day p-2 text-center" style="min-height: 60px; aspect-ratio: 1; opacity: 0.3;">
                                    <!-- Empty cell for days outside current month -->
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>

                <?php
                // Break if we've shown all days of the month
                if ($dayCounter >= $calendarInfo->daysInMonth) {
                    break;
                }
                ?>
            <?php endfor; ?>
        </div>
    </div>
</div>

<script>
    // Function to open modal (called by onclick)
    function openDayDetailsModal() {
        const modal = new bootstrap.Modal(document.getElementById('dayDetailsModal'));
        const modalContent = document.getElementById('dayDetailsContent');

        // Show loading state
        modalContent.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Загрузка...</span></div></div>';
        modal.show();
    }

    // Handle HTMX indicators
    document.addEventListener('htmx:beforeRequest', function(event) {
        if (event.target.closest('.cycle-calendar-nav')) {
            document.querySelectorAll('.htmx-indicator').forEach(el => el.classList.remove('d-none'));
        }
    });

    document.addEventListener('htmx:afterRequest', function(event) {
        if (event.target.closest('.cycle-calendar-nav')) {
            document.querySelectorAll('.htmx-indicator').forEach(el => el.classList.add('d-none'));
        }
    });
</script>
