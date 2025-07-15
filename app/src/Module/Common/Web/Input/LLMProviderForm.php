<?php

declare(strict_types=1);

namespace App\Module\Common\Web\Input;

use App\Module\LLM\Config\Platforms;
use Spiral\Filters\Attribute\Input\Post;
use Spiral\Filters\Model\Filter;

final class LLMProviderForm extends Filter
{
    #[Post(key: 'llm_provider')]
    public Platforms $provider;

    /**
     * @var non-empty-string
     */
    #[Post(key: 'api_token')]
    public string $apiToken;

    /**
     * @var non-empty-string|null
     */
    #[Post(key: 'model_name')]
    public ?string $model = null;
}
