<?php

declare(strict_types=1);

namespace App\Module\Calendar\DTO;

/**
 * Danger levels for cycle days to help understand the risk level of interaction.
 */
enum DangerLevel: string
{
    case Safe = 'safe';
    case Caution = 'caution';
    case High = 'high';
    case Extreme = 'extreme';

    /**
     * Get CSS class for styling based on danger level.
     */
    public function getCssClass(): string
    {
        return match ($this) {
            self::Safe => 'cycle-safe',
            self::Caution => 'cycle-caution',
            self::High => 'cycle-high',
            self::Extreme => 'cycle-extreme',
        };
    }

    /**
     * Get color for the danger level.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::Safe => '#28a745',      // Green
            self::Caution => '#ffc107',   // Yellow
            self::High => '#fd7e14',      // Orange
            self::Extreme => '#dc3545',   // Red
        };
    }

    /**
     * Get human-readable danger level in Russian.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::Safe => 'Безопасно',
            self::Caution => 'Осторожно',
            self::High => 'Высокая опасность',
            self::Extreme => 'Крайняя опасность',
        };
    }
}
