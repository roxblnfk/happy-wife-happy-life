<?php

declare(strict_types=1);

namespace App\Module\Agent;

use App\Application\Value\Date;

/**
 * Trait for dateable agents.
 *
 * This trait can be used to provide common functionality for agents that
 * need to handle dates.
 *
 * @mixin DateableAgent
 */
trait DateableTrait
{
    /**
     * The date associated with the agent.
     */
    private ?Date $date = null;

    /**
     * Get the date associated with the agent.
     */
    public function getDate(): ?Date
    {
        return $this->date ?? Date::today();
    }

    /**
     * Set the date for the agent.
     */
    public function forDate(Date $date): self
    {
        $self = clone $this;
        $self->date = $date;
        return $self;
    }
}
