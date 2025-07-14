<?php

declare(strict_types=1);

namespace App\Module\Common\Web;

use App\Module\Common\Config\RelationType;
use Spiral\Filters\Attribute\Input\Post;
use Spiral\Filters\Model\Filter;

final class RelationshipForm extends Filter
{
    /**
     * @var non-empty-string
     */
    #[Post(key: 'user_name')]
    public string $userName;

    /**
     * @var non-empty-string
     */
    #[Post(key: 'partner_name')]
    public string $partnerName;

    #[Post(key: 'relationship_type')]
    public RelationType $relationType;
}
