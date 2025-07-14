<?php

declare(strict_types=1);

namespace App\Application\Forms\Caster;

use Spiral\Filters\Model\FilterInterface;
use Spiral\Filters\Model\Mapper\CasterInterface;

final class DateTime implements CasterInterface
{
    public function supports(\ReflectionNamedType $type): bool
    {
        return $type->getName() === \DateTimeInterface::class or $type->getName() === \DateTimeImmutable::class;
    }

    public function setValue(FilterInterface $filter, \ReflectionProperty $property, mixed $value): void
    {
        $property->setValue($filter, new \DateTimeImmutable($value));
    }
}
