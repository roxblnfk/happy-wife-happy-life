<?php

namespace App\Endpoint\Cli;

use Boson\Application;
use Boson\WebView\WebViewCreateInfo;
use Boson\Window\WindowCreateInfo;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand('start')]
class StartCommand extends \Spiral\Console\Command
{
    public function __invoke(Application $app): int
    {
        // Set the initial URL to load the menu
        $app->webview->url = 'soco://localhost/index.html';

        // Run the application
        $app->run();

        return Command::SUCCESS;
    }
}
