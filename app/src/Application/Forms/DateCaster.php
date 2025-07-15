<?php

declare(strict_types=1);

namespace App\Application\Forms;

use App\Application\Value\Date;
use Spiral\Filters\Model\FilterInterface;
use Spiral\Filters\Model\Mapper\CasterInterface;

final class DateCaster implements CasterInterface
{
    public function supports(\ReflectionNamedType $type): bool
    {
        return $type->getName() === Date::class;
    }

    public function setValue(FilterInterface $filter, \ReflectionProperty $property, mixed $value): void
    {
        if ($property->getType()?->allowsNull() && empty($value)) {
            $property->setValue($filter, null);
            return;
        }

        $property->setValue($filter, Date::fromString($value));
    }
}
