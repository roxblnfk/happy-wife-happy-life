<?php

declare(strict_types=1);

namespace App\Module\Context;

use App\Module\Common\Config\RelationshipInfo;
use App\Module\Common\Config\UserInfo;
use App\Module\Common\Config\WomenInfo;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\AI\Platform\Message\MessageBagInterface;

class FullInfo implements MessagesBagAware
{
    private MessageBag $bag;

    public function __construct(
        UserInfo $userInfo,
        RelationshipInfo $relationInfo,
        WomenInfo $womenInfo,
    ) {
        $this->bag = new MessageBag(
            Message::forSystem("$userInfo $relationInfo $womenInfo"),
        );
    }

    public function getMessageBag(): MessageBagInterface
    {
        return $this->bag;
    }
}
