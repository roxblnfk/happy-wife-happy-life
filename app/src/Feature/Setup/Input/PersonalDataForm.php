<?php

declare(strict_types=1);

namespace App\Feature\Setup\Input;

use Spiral\Filters\Attribute\Input\Post;
use Spiral\Filters\Model\Filter;

final class PersonalDataForm extends Filter
{
    #[Post(key: 'preferences')]
    public string $preferences;

    #[Post(key: 'triggers')]
    public string $triggers;
}
