<?php

declare(strict_types=1);

namespace App\Module\Chat\Domain;

/**
 * AI Message Status
 */
enum MessageStatus: string
{
    case Completed = 'completed';
    case Failed = 'failed';
    case Pending = 'pending';
}
