<?php

declare(strict_types=1);

namespace App\Module\Common\Web\Input\Calendar;

use App\Application\Value\Date;
use Spiral\Filters\Attribute\Input\Post;
use Spiral\Filters\Model\Filter;

final class ImportantDateForm extends Filter
{
    #[Post(key: 'title')]
    public string $title;

    #[Post(key: 'value')]
    public ?Date $date;
}
