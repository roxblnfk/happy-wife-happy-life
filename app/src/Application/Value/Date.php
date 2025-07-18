<?php

declare(strict_types=1);

namespace App\Application\Value;

class Date implements \Stringable
{
    private function __construct(
        public int $year,
        public int $month,
        public int $day,
    ) {}

    public static function fromString(string $date): self
    {
        $parts = \explode('-', \str_replace('.', '-', $date));
        \count($parts) === 3 or throw new \InvalidArgumentException("Invalid date format, expected 'YYYY-MM-DD'");

        # Switch Year and Day if the date is in 'DD.MM.YYYY' format
        \strlen($parts[0]) === 2 and \strlen($parts[2]) === 4 and [$parts[0], $parts[2]] = [$parts[2], $parts[0]];

        return new self((int) $parts[0], (int) $parts[1], (int) $parts[2]);
    }

    public static function fromDateTime(\DateTimeInterface $dateTime): self
    {
        return self::fromString($dateTime->format('Y-m-d'));
    }

    public static function today(): self
    {
        return self::fromDateTime(new \DateTimeImmutable());
    }

    /**
     * Count the number of days between this date and another date.
     *
     * @return int<0, max>
     */
    public function daysTo(Date $date): int
    {
        $start = new \DateTimeImmutable($this->__toString());
        $end = new \DateTimeImmutable($date->__toString());

        return (int) $start->diff($end)->format('%a');
    }

    /**
     * Count the number of years between this date and another date.
     *
     * @return int<0, max>
     */
    public function yearsTo(Date $date): int
    {
        $start = new \DateTimeImmutable($this->__toString());
        $end = new \DateTimeImmutable($date->__toString());

        return (int) $start->diff($end)->format('%y');
    }

    /**
     * Calculate the next closest periodic date based on a period.
     */
    public function getClosestPeriodicDate(string|\DateInterval $period): Date
    {
        $start = \DateTime::createFromFormat('Y-m-d', $this->__toString()) or throw new \InvalidArgumentException(
            'Invalid date.',
        );
        $today = new \DateTimeImmutable();
        $interval = \is_string($period)
            ? (\DateInterval::createFromDateString($period) ?: throw new \InvalidArgumentException('Invalid period.'))
            : $period;

        while ($start < $today) {
            $start->add($interval);
        }

        return Date::fromDateTime($start);
    }

    /**
     * Create a new date with the specified interval added.
     *
     * @param int|string|\DateInterval $interval int days, 'P1D', '5 days', or DateInterval
     */
    public function withInterval(int|string|\DateInterval $interval): self
    {
        $interval = match (true) {
            \is_int($interval) => new \DateInterval('P' . $interval . 'D'),
            \is_string($interval) => \DateInterval::createFromDateString($interval) ?: throw new \InvalidArgumentException('Invalid interval string.'),
            $interval instanceof \DateInterval => $interval,
        };
        \assert($interval instanceof \DateInterval);

        $dateTime = \DateTime::createFromFormat('Y-m-d', $this->__toString()) or throw new \InvalidArgumentException(
            'Invalid date.',
        );

        $dateTime->add($interval);
        return new self(
            (int) $dateTime->format('Y'),
            (int) $dateTime->format('m'),
            (int) $dateTime->format('d'),
        );
    }

    /**
     * Check if this date is between two other dates.
     *
     * @param Date $start The start date of the range.
     * @param Date $end The end date of the range.
     * @return bool True if this date is between start and end, inclusive.
     */
    public function isBetween(Date $start, Date $end): bool
    {
        $thisDate = \DateTimeImmutable::createFromFormat('Y-m-d', $this->__toString());
        $startDate = \DateTimeImmutable::createFromFormat('Y-m-d', $start->__toString());
        $endDate = \DateTimeImmutable::createFromFormat('Y-m-d', $end->__toString());

        # Ensure startDate is always before endDate
        $startDate > $endDate and [$startDate, $endDate] = [$endDate, $startDate];

        return $thisDate >= $startDate && $thisDate <= $endDate;
    }

    public function __toString(): string
    {
        return \sprintf('%04d-%02d-%02d', $this->year, $this->month, $this->day);
    }
}
