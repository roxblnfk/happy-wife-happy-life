<?php

declare(strict_types=1);

namespace App\Module\Calendar\Service;

use App\Application\Value\Date;
use App\Module\Calendar\DTO\CycleDay;
use App\Module\Calendar\DTO\CyclePhase;
use App\Module\Calendar\DTO\DangerLevel;
use App\Module\Calendar\Info\WomenCycleInfo;

/**
 * Service for generating cycle day information based on women's cycle data.
 */
final class CycleCalendarService
{
    public function __construct(
        private readonly WomenCycleInfo $cycleInfo,
    ) {}

    /**
     * Generate cycle days for a specific month.
     *
     * @return array<string, CycleDay> Array with date string as key and CycleDay as value
     */
    public function getMonthCycleDays(int $year, int $month): array
    {
        $result = [];
        $daysInMonth = \cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Date::fromString("$year-$month-$day");
            $cycleDay = $this->calculateCycleDay($date);
            $result[$date->__toString()] = $cycleDay;
        }

        return $result;
    }

    /**
     * Get current cycle day information.
     */
    public function getCurrentCycleDay(): CycleDay
    {
        return $this->calculateCycleDay(Date::today());
    }

    /**
     * Calculate cycle day information for a specific date.
     */
    private function calculateCycleDay(Date $date): CycleDay
    {
        $daysSinceLastPeriod = $this->cycleInfo->lastPeriodStart->daysTo($date);
        $dayOfCycle = ($daysSinceLastPeriod % $this->cycleInfo->cycleLength) + 1;

        // Determine if it's period days
        $isPeriod = $dayOfCycle <= $this->cycleInfo->periodLength;

        // Determine ovulation (typically around day 14 for 28-day cycle)
        $ovulationDay = (int) \round($this->cycleInfo->cycleLength / 2);
        $isOvulation = \abs($dayOfCycle - $ovulationDay) <= 1;

        // Determine PMS (typically 5-7 days before next period)
        $pmsStart = $this->cycleInfo->cycleLength - 6;
        $isPMS = $dayOfCycle >= $pmsStart;

        // Determine phase
        $phase = $this->determinePhase($dayOfCycle);

        // Determine danger level and recommendations
        [$dangerLevel, $moodDescription, $recommendation] = $this->getDangerLevelAndRecommendations(
            $dayOfCycle,
            $isPeriod,
            $isOvulation,
            $isPMS,
        );

        return new CycleDay(
            date: $date,
            dayOfCycle: $dayOfCycle,
            phase: $phase,
            dangerLevel: $dangerLevel,
            moodDescription: $moodDescription,
            recommendation: $recommendation,
            isPMS: $isPMS,
        );
    }

    /**
     * Determine the menstrual cycle phase for a given day.
     */
    private function determinePhase(int $dayOfCycle): CyclePhase
    {
        $ovulationDay = (int) \round($this->cycleInfo->cycleLength / 2);

        return match (true) {
            $dayOfCycle <= $this->cycleInfo->periodLength => CyclePhase::Menstrual,
            $dayOfCycle <= $ovulationDay - 2 => CyclePhase::Follicular,
            $dayOfCycle <= $ovulationDay + 2 => CyclePhase::Ovulation,
            default => CyclePhase::Luteal,
        };
    }

    /**
     * Get danger level and recommendations based on cycle day characteristics.
     *
     * @return array{0: DangerLevel, 1: string, 2: string} [dangerLevel, moodDescription, recommendation]
     */
    private function getDangerLevelAndRecommendations(
        int $dayOfCycle,
        bool $isPeriod,
        bool $isOvulation,
        bool $isPMS,
    ): array {
        return match (true) {
            // Extreme danger - First days of period
            $isPeriod && $dayOfCycle <= 2 => [
                DangerLevel::Extreme,
                'Сильные боли, крайняя раздражительность',
                'Максимальная забота. Принести чай, обезболивающее. Избегать любых конфликтов!',
            ],

            // High danger - Rest of period days
            $isPeriod => [
                DangerLevel::High,
                'Дискомфорт, повышенная чувствительность',
                'Быть особенно внимательным. Помочь с домашними делами. Никаких споров!',
            ],

            // High danger - PMS days
            $isPMS => [
                DangerLevel::High,
                'ПМС: раздражительность, перепады настроения',
                'Максимальное терпение. Не спорить, больше поддержки и понимания',
            ],

            // Caution - Days around ovulation but not peak
            \abs($dayOfCycle - \round($this->cycleInfo->cycleLength / 2)) <= 3 && !$isOvulation => [
                DangerLevel::Caution,
                'Повышенная эмоциональность',
                'Быть внимательнее к настроению, предлагать больше заботы',
            ],

            // Safe - Ovulation peak (usually good mood)
            $isOvulation => [
                DangerLevel::Safe,
                'Пик энергии и хорошего настроения',
                'Отличное время для романтических планов и важных разговоров',
            ],

            // Safe - Post-menstrual days
            $dayOfCycle > $this->cycleInfo->periodLength && $dayOfCycle <= $this->cycleInfo->periodLength + 7 => [
                DangerLevel::Safe,
                'Стабильное настроение, хорошая энергия',
                'Хорошее время для активных планов и общения',
            ],

            // Caution - Mid-luteal phase
            default => [
                DangerLevel::Caution,
                'Умеренная эмоциональность',
                'Обычная осторожность, следить за настроением партнерши',
            ],
        };
    }
}
