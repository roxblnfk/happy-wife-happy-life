<?php

declare(strict_types=1);

namespace App\Module\Common\Config;

enum LLMProvider: string
{
    case OpenAI = 'openai';
    case Ollama = 'ollama';
    case Azure = 'azure';
    case Google = 'google';
    case Anthropic = 'anthropic';
    case Mistral = 'mistral';
    case Local = 'local';
}
