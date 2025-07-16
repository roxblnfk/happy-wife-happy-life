<?php

declare(strict_types=1);

namespace App\Application\Bootloader;

use App\Application\Process\Process;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\FinalizerInterface;

final class AppBootloader extends Bootloader
{
    public function init(Process $process, FinalizerInterface $finalizer): void
    {
        $finalizer->addFinalizer(static fn(bool $terminate) => $terminate and $process->finalize());
    }
}
