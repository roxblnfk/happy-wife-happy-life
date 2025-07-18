<?php

declare(strict_types=1);

namespace App\Module\Calendar\DTO;

/**
 * Menstrual cycle phases.
 */
enum CyclePhase: string
{
    case Menstrual = 'menstrual';
    case Follicular = 'follicular';
    case Ovulation = 'ovulation';
    case Luteal = 'luteal';

    /**
     * Get human-readable phase name in Russian.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Menstrual => 'Менструация',
            self::Follicular => 'Фолликулярная',
            self::Ovulation => 'Овуляция',
            self::Luteal => 'Лютеиновая',
        };
    }
}
