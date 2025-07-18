<?php

declare(strict_types=1);

namespace App\Feature\Calendar\Internal;

use App\Application\Value\Date;
use App\Feature\Calendar\Internal\DTO\CalendarInfo;
use App\Module\Calendar\CycleCalendar;

/**
 * Service for calculating calendar information and parameters.
 */
final class CalendarInfoService
{
    public function __construct(
        private readonly CycleCalendar $cycleCalendarService,
    ) {}

    public function getCalendarInfo(?int $year = null, ?int $month = null): CalendarInfo
    {
        $today = Date::today();
        $year ??= $today->year;
        $month ??= $today->month;

        // Validate and normalize month
        $month = $this->normalizeMonth($month, $today->month);

        $cycleDays = $this->cycleCalendarService->getMonthCycleDays($year, $month);
        $currentCycleDay = $this->cycleCalendarService->getCurrentCycleDay();

        [$prevYear, $prevMonth] = $this->calculatePreviousMonth($year, $month);
        [$nextYear, $nextMonth] = $this->calculateNextMonth($year, $month);

        $offset = $this->calculateCalendarOffset($year, $month);
        $daysInMonth = \cal_days_in_month(CAL_GREGORIAN, $month, $year);

        return new CalendarInfo(
            year: $year,
            month: $month,
            cycleDays: $cycleDays,
            currentCycleDay: $currentCycleDay,
            today: $today,
            prevYear: $prevYear,
            prevMonth: $prevMonth,
            nextYear: $nextYear,
            nextMonth: $nextMonth,
            offset: $offset,
            daysInMonth: $daysInMonth,
        );
    }

    private function normalizeMonth(int $month, int $fallbackMonth): int
    {
        return ($month < 1 || $month > 12) ? $fallbackMonth : $month;
    }

    /**
     * @return array{int, int}
     */
    private function calculatePreviousMonth(int $year, int $month): array
    {
        return $month === 1
            ? [$year - 1, 12]
            : [$year, $month - 1];
    }

    /**
     * @return array{int, int}
     */
    private function calculateNextMonth(int $year, int $month): array
    {
        return $month === 12
            ? [$year + 1, 1]
            : [$year, $month + 1];
    }

    private function calculateCalendarOffset(int $year, int $month): int
    {
        $firstDay = Date::fromNumbers($year, $month, 1);
        $firstDayDateTime = \DateTime::createFromFormat('Y-m-d', $firstDay->__toString());
        $dayOfWeek = (int) $firstDayDateTime->format('N'); // 1 = Monday, 7 = Sunday

        return $dayOfWeek - 1; // Offset for Monday start
    }
}
