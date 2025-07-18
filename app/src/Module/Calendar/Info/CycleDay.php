<?php

declare(strict_types=1);

namespace App\Module\Calendar\Info;

use App\Application\Value\Date;

/**
 * Represents information about a specific day in a woman's menstrual cycle.
 * Contains danger levels and characteristics for men to understand the day's specifics.
 */
final class CycleDay implements \Stringable
{
    public function __construct(
        public readonly Date $date,
        public readonly int $dayOfCycle,
        public readonly CyclePhase $phase,
        public readonly DangerLevel $dangerLevel,
        public readonly string $moodDescription,
        public readonly string $recommendation,
        public readonly bool $isPMS = false,
    ) {}

    /**
     * Check if this day is during menstruation.
     */
    public function isPeriod(): bool
    {
        return $this->phase === CyclePhase::Menstrual;
    }

    /**
     * Check if this day is during ovulation.
     */
    public function isOvulation(): bool
    {
        return $this->phase === CyclePhase::Ovulation;
    }

    /**
     * Get CSS class for styling based on danger level.
     */
    public function getCssClass(): string
    {
        return $this->dangerLevel->getCssClass();
    }

    /**
     * Get icon for the day type.
     */
    public function getIcon(): string
    {
        return match (true) {
            $this->isPeriod() => 'bi-droplet-fill',
            $this->isOvulation() => 'bi-heart-fill',
            $this->isPMS => 'bi-exclamation-triangle-fill',
            default => 'bi-circle',
        };
    }

    /**
     * Get color for the danger level.
     */
    public function getColor(): string
    {
        return $this->dangerLevel->getColor();
    }

    /**
     * Get human-readable phase name in Russian.
     */
    public function getPhaseName(): string
    {
        return $this->phase->getLabel();
    }

    /**
     * Get human-readable danger level in Russian.
     */
    public function getDangerLevelName(): string
    {
        return $this->dangerLevel->getLabel();
    }

    public function __toString(): string
    {
        return <<<MARKDOWN
            Women's Cycle Day Information:
            For Date: {$this->date->__toString()};
            Day of Cycle: {$this->dayOfCycle};
            Cycle Phase: {$this->phase->name};
            Danger Level: {$this->dangerLevel->name};
            Mood Description: {$this->moodDescription};
            Recommendation: {$this->recommendation};
            Is PMS: " . ($this->isPMS ? 'Yes' : 'No') . ";
            MARKDOWN;
    }
}
