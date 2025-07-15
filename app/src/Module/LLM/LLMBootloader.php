<?php

declare(strict_types=1);

namespace App\Module\LLM;

use App\Module\LLM\Internal\LLM;
use Spiral\Boot\Bootloader\Bootloader;

final class LLMBootloader extends Bootloader
{
    public function defineSingletons(): array
    {
        return [
            LLM::class => [LLMProvider::class, 'getLLM'],
        ];
    }
}
