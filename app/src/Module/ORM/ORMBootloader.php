<?php

declare(strict_types=1);

namespace App\Module\ORM;

use Cycle\ActiveRecord\Bridge\Spiral\Bootloader\ActiveRecordBootloader;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Cycle\Bootloader as CycleBridge;

final class ORMBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        # Database
        CycleBridge\DatabaseBootloader::class,
        CycleBridge\MigrationsBootloader::class,
        # ORM
        CycleBridge\SchemaBootloader::class,
        CycleBridge\CycleOrmBootloader::class,
        CycleBridge\AnnotatedBootloader::class,
        CycleBridge\EntityBehaviorBootloader::class,
        # ActiveRecord
        ActiveRecordBootloader::class,
        # Console commands
        CycleBridge\CommandBootloader::class,
        CycleBridge\ScaffolderBootloader::class,
    ];
}
