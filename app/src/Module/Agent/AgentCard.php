<?php

declare(strict_types=1);

namespace App\Module\Agent;

/**
 * Data Transfer Object for agent card display information.
 *
 * Represents the visual and descriptive properties of a chat agent
 * that are used to render agent selection cards in the UI.
 */
final class AgentCard
{
    /**
     * Create a new agent card instance.
     *
     * @param non-empty-string $alias Unique identifier for the agent (used in URLs and routing)
     * @param non-empty-string $name Display name shown on the agent card
     * @param non-empty-string $description Brief description of what the agent helps with
     * @param non-empty-string $icon Bootstrap icon class (e.g., 'bi-calendar-heart', 'bi-heart-pulse')
     * @param string $color Bootstrap color theme (primary, secondary, success, danger, warning, info)
     */
    public function __construct(
        public readonly string $alias,
        public readonly string $name,
        public readonly string $description,
        public readonly string $icon,
        public readonly string $color,
    ) {}
}
