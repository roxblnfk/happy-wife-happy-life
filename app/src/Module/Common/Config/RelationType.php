<?php

declare(strict_types=1);

namespace App\Module\Common\Config;

enum RelationType: string
{
    case Dating = 'dating';
    case Engaged = 'engaged';
    case Married = 'married';
    case LongTerm = 'longterm';
}
