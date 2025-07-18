<?php

declare(strict_types=1);

namespace App\Feature\Calendar\Internal\DTO;

use App\Application\Value\Date;
use App\Module\Calendar\DTO\CycleDay;

/**
 * Calendar information DTO containing all data needed for calendar rendering.
 */
final class CalendarInfo
{
    /**
     * @param array<string, CycleDay> $cycleDays
     */
    public function __construct(
        public readonly int $year,
        public readonly int $month,
        public readonly array $cycleDays,
        public readonly CycleDay $currentCycleDay,
        public readonly Date $today,
        public readonly int $prevYear,
        public readonly int $prevMonth,
        public readonly int $nextYear,
        public readonly int $nextMonth,
        public readonly int $offset,
        public readonly int $daysInMonth,
    ) {}

    public function getMonthName(): string
    {
        return match ($this->month) {
            1 => 'Январь',
            2 => 'Февраль', 
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь',
        };
    }

    /**
     * @return array<string>
     */
    public function getDayNames(): array
    {
        return ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
    }

    public function getCycleDayForDate(Date $date): ?CycleDay
    {
        return $this->cycleDays[$date->__toString()] ?? null;
    }

    public function isToday(Date $date): bool
    {
        return $date->__toString() === $this->today->__toString();
    }
}
