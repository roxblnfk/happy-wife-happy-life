<?php

declare(strict_types=1);

namespace App\Module\Common\Config;

enum LLMProvider: string
{
    case OpenAI = 'openai';
    case Google = 'google';
    case Anthropic = 'anthropic';
    case Local = 'local';
}
