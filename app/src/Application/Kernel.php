<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Bootloader\FormsBootloader;
use App\Feature;
use App\Module\Config\ConfigBootloader;
use App\Module\LLM\LLMBootloader;
use App\Module\ORM\ORMBootloader;
use Spiral\Boot\Bootloader\CoreBootloader;
use Spiral\Bootloader as Framework;
use Spiral\Bootloader\I18nBootloader;
use Spiral\Bootloader\Views\TranslatedCacheBootloader;
use Spiral\Debug\Bootloader\DumperBootloader;
use Spiral\DotEnv\Bootloader\DotenvBootloader;
use Spiral\Monolog\Bootloader\MonologBootloader;
use Spiral\Nyholm\Bootloader\NyholmBootloader;
use Spiral\Prototype\Bootloader\PrototypeBootloader;
use Spiral\Sapi\Bootloader\SapiBootloader;
use Spiral\Scaffolder\Bootloader\ScaffolderBootloader;
use Spiral\Sentry\Bootloader\SentryReporterBootloader;
use Spiral\Tokenizer\Bootloader\TokenizerListenerBootloader;
use Spiral\Views\Bootloader\ViewsBootloader;
use Spiral\YiiErrorHandler\Bootloader\YiiErrorHandlerBootloader;

/**
 * @psalm-suppress ClassMustBeFinal
 */
class Kernel extends \Spiral\Framework\Kernel
{
    #[\Override]
    public function defineSystemBootloaders(): array
    {
        return [
            CoreBootloader::class,
            DotenvBootloader::class,
            TokenizerListenerBootloader::class,

            DumperBootloader::class,
        ];
    }

    #[\Override]
    public function defineBootloaders(): array
    {
        return [
            // Logging and exceptions handling
            MonologBootloader::class,
            YiiErrorHandlerBootloader::class,
            Bootloader\ExceptionHandlerBootloader::class,

            // Application specific logs
            Bootloader\LoggingBootloader::class,

            // Core Services
            Framework\SnapshotsBootloader::class,
            SentryReporterBootloader::class,

            // Security and validation
            Framework\Security\EncrypterBootloader::class,
            Framework\Security\FiltersBootloader::class,
            FormsBootloader::class,

            // HTTP extensions
            SapiBootloader::class,
            Framework\Http\HttpBootloader::class,
            Framework\Http\RouterBootloader::class,
            Framework\Http\JsonPayloadsBootloader::class,
            Framework\Http\CookiesBootloader::class,
            Framework\Http\SessionBootloader::class,
            Framework\Http\CsrfBootloader::class,
            Framework\Http\PaginationBootloader::class,
            NyholmBootloader::class,

            // Views
            ViewsBootloader::class,

            // ORM
            ORMBootloader::class,

            // Internationalization
            I18nBootloader::class,
            TranslatedCacheBootloader::class,

            // Console commands
            Framework\CommandBootloader::class,
            ScaffolderBootloader::class,

            // Fast code prototyping
            PrototypeBootloader::class,

            Bootloader\RoutesBootloader::class,

            // LLM
            LLMBootloader::class,

            // Features
            Feature\Boson\Bootloader::class,
            Feature\Setup\Bootloader::class,
            Feature\Index\Bootloader::class,
            Feature\Chat\Bootloader::class,

            // Modules
            ConfigBootloader::class,
        ];
    }
}
