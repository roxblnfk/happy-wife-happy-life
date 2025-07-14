<?php

declare(strict_types=1);

namespace App\Module\Config\Internal;

use App\Module\Config\Attribute\Config;
use Spiral\Attributes\ReaderInterface;
use Spiral\Core\Attribute\Singleton;
use Spiral\Tokenizer\Attribute\TargetAttribute;
use Spiral\Tokenizer\TokenizationListenerInterface;

#[Singleton]
#[TargetAttribute(Config::class)]
class ConfigRegistry implements TokenizationListenerInterface
{
    /**
     * @var array<non-empty-string, class-string>
     */
    private array $configs = [];

    public function __construct(
        private readonly ReaderInterface $reader,
    ) {}

    /**
     * Returns the list of configuration classes.
     *
     * @return array<non-empty-string, class-string>
     */
    public function getConfigs(): array
    {
        return $this->configs;
    }

    /**
     * @param non-empty-string $name
     * @return class-string|null
     */
    public function getConfigClass(string $name): ?string
    {
        return $this->configs[$name] ?? null;
    }

    /**
     * @param class-string $class
     * @return non-empty-string|null
     */
    public function getName(string $class): ?string
    {
        foreach ($this->configs as $name => $configClass) {
            if ($configClass === $class) {
                return $name;
            }
        }

        return null;
    }

    public function listen(\ReflectionClass $class): void
    {
        $attribute = $this->reader->firstClassMetadata($class, Config::class);
        $attribute === null or $this->configs[$attribute->name] = $class->getName();
    }

    public function finalize(): void {}
}
