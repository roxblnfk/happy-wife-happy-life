<?php

declare(strict_types=1);

namespace App\Endpoint\Cli;

use App\Application\AppScope;
use Boson\Application;
use Spiral\Core\Container;
use Spiral\Core\Scope;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand('start')]
class StartCommand extends \Spiral\Console\Command
{
    public function __invoke(Container $core): int
    {
        $core->runScope(
            new Scope(
                name: AppScope::Boson,
            ),
            static function (Application $app): void {
                // Set the initial URL
                $app->webview->url = 'http://localhost/index';
                $app->run();
            },
        );

        return Command::SUCCESS;
    }
}
