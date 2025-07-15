<?php

declare(strict_types=1);

namespace App\Module\Common\Web;

use Spiral\Filters\Attribute\Input\Post;
use Spiral\Filters\Model\Filter;

final class LLMForm extends Filter
{
    /**
     * @var non-empty-string
     */
    #[Post(key: 'model_name')]
    public string $apiToken;
}
