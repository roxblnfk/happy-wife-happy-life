<?php

declare(strict_types=1);

use Spiral\Views\Engine\Native\NativeEngine;

return [
    'cache' => [
        'enabled' => !env('DEBUG', false),
        'directory' => directory('cache') . 'views',
    ],
    'namespaces' => [
        'default' => [
            directory('views'),
        ],
    ],
    'dependencies' => [],
    'engines' => [
        NativeEngine::class,
    ],
    'globalVariables' => [
        'some_var' => env('SOME_VALUE'),
    ],
];
