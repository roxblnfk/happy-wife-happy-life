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

    public function __toString(): string
    {
        return \sprintf('%04d-%02d-%02d', $this->year, $this->month, $this->day);
    }
}
