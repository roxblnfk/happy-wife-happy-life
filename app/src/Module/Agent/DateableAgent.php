<?php

declare(strict_types=1);

namespace App\Module\Agent;

use App\Application\Value\Date;

/**
 * Interface for chat agents
 */
interface DateableAgent extends ChatAgent
{
    public function getDate(): ?Date;

    public function forDate(Date $date): self;
}
