<?php

declare(strict_types=1);

/**
 * @see \Boson\Bridge\Spiral\Config\BosonConfig
 */
return [
    /**
     * List of directories to serve static files from.
     */
    'static' => [
        'app/public',
    ],

    /**
     * The URL to initialize the application.
     */
    'init-url' => 'http://localhost/',

    /**
     * Application create configuration.
     */
    'application' => new \Boson\ApplicationCreateInfo(
        schemes: ['http'],
        debug: false,
        window: new \Boson\Window\WindowCreateInfo(
            title: 'Happy Wife – Happy Life',
            width: 980,
            height: 768,
            resizable: true,
            webview: new \Boson\WebView\WebViewCreateInfo(
                contextMenu: true,
            ),
        ),
    ),
];
