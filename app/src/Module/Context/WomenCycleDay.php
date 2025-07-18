<?php

declare(strict_types=1);

namespace App\Module\Context;

use App\Application\Value\Date;
use App\Module\Calendar\CycleCalendar;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\AI\Platform\Message\MessageBagInterface;

class WomenCycleDay implements MessagesBagAware
{
    private Date $date;

    public function __construct(
        private readonly CycleCalendar $cycleCalendar,
    ) {
        $this->date = Date::today();
    }

    public function forDay(Date $date): self
    {
        $self = clone $this;
        $self->date = $date;
        return $self;
    }

    public function getMessageBag(): MessageBagInterface
    {
        $day = $this->cycleCalendar->calculateCycleDay($this->date);
        $this->bag = new MessageBag(
            Message::forSystem("
            ---
            $day
            ---
            "),
        );

        return $this->bag;
    }
}
