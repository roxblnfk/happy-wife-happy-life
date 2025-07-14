<?php

declare(strict_types=1);

namespace App\Module\Common\Web;

use App\Module\Common\Config\RelationType;
use Spiral\Filters\Attribute\Input\Post;
use Spiral\Filters\Model\Filter;

final class StartForm extends Filter
{
    #[Post(key: 'user_name')]
    public ?string $userName = null;

    #[Post(key: 'partner_name')]
    public ?string $partnerName = null;

    #[Post(key: 'relationship_type')]
    public RelationType $relationType;
}
