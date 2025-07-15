<?php

declare(strict_types=1);

namespace App\Module\LLM\Config;

enum Platforms: string
{
    case OpenAI = 'openai';
    case Google = 'google';
    case Anthropic = 'anthropic';
    case Local = 'local';
}
