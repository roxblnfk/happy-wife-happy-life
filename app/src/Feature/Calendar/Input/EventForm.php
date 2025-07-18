<?php

declare(strict_types=1);

namespace App\Feature\Calendar\Input;

use App\Application\Value\Date;
use Spiral\Filters\Attribute\Input\Post;
use Spiral\Filters\Model\Filter;

final class EventForm extends Filter
{
    #[Post(key: 'title')]
    public string $title;

    #[Post(key: 'date')]
    public Date $date;

    #[Post(key: 'period')]
    public ?string $period = null;

    #[Post(key: 'description')]
    public ?string $description = null;
}
