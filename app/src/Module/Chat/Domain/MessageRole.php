<?php

declare(strict_types=1);

namespace App\Module\Chat\Domain;

/**
 * Sender role in a chat conversation.
 */
enum MessageRole: string
{
    case System = 'system';
    case Assistant = 'assistant';
    case Agent = 'agent';
    case User = 'user';
    case ToolCall = 'tool';
}
