<?php

declare(strict_types=1);

namespace App\Module\LLM\Internal\Domain;

/**
 * AI Message Status
 */
enum RequestStatus: string
{
    case Completed = 'completed';
    case Failed = 'failed';
    case Pending = 'pending';
    case Cancelled = 'cancelled';
}
