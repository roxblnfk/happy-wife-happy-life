<?php

declare(strict_types=1);

namespace App\Module\Common\Web;

use App\Module\Common\Config\LLMProvider;
use Spiral\Filters\Attribute\Input\Post;
use Spiral\Filters\Model\Filter;

final class LLMProviderForm extends Filter
{
    #[Post(key: 'llm_provider')]
    public LLMProvider $provider;

    /**
     * @var non-empty-string
     */
    #[Post(key: 'api_token')]
    public string $apiToken;
}
