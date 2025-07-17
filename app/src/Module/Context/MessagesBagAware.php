<?php

declare(strict_types=1);

namespace App\Module\Context;

use Symfony\AI\Platform\Message\MessageBagInterface;

interface MessagesBagAware
{
    /**
     * Gets the message bag.
     *
     * @return MessageBagInterface The current message bag.
     */
    public function getMessageBag(): MessageBagInterface;
}
