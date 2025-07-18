<?php

declare(strict_types=1);

namespace App\Feature\Setup\Input\Calendar;

use App\Application\Value\Date;
use App\Feature\Calendar\Input\ImportantDateForm;
use Spiral\Filters\Attribute\Input\Post;
use Spiral\Filters\Attribute\NestedArray;
use Spiral\Filters\Model\Filter;

final class CalendarForm extends Filter
{
    #[Post(key: 'cycle_length')]
    public int $cycleLength;

    #[Post(key: 'period_length')]
    public int $periodLength;

    #[Post(key: 'last_period_start')]
    public Date $lastPeriodStart;

    #[Post(key: 'birthday')]
    public ?Date $birthday;

    #[Post(key: 'anniversary')]
    public ?Date $anniversary;

    // /** @var array<ImportantDateForm> */
    // #[NestedArray(
    //     class: ImportantDateForm::class,
    //     input: new Post('important_date'),
    //     // prefix: 'important_date',
    // )]
    // public array $importantDates = [];
}
