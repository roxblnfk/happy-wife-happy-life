<?php

declare(strict_types=1);

namespace App\Module\Config;

use App\Module\Config\Internal\ConfigRegistry;
use App\Module\Config\Internal\Persistance\Entity;
use Spiral\Core\Attribute\Singleton;

#[Singleton]
final class ConfigService
{
    public function __construct(
        private readonly ConfigRegistry $configListener,
    ) {}

    public function getConfig(string $class): ?object
    {
        $name = $this->configListener->getName($class);
        $name === null or $entity = Entity::findByPK($name);

        if (!isset($name, $entity)) {
            return null;
        }

        return \unserialize($entity->value);
    }

    public function persistConfig(object $config, bool $override = false): void
    {
        $name = $this->configListener->getName($config::class) ?? throw new \InvalidArgumentException(
            "Config class does not have a valid name attribute.",
        );

        /** @var null|Entity $entity */
        $entity = Entity::findByPK($name);
        $entity === null or $override and $entity->delete();

        $entity = Entity::make([
            'name' => $name,
            // 'value' => \json_encode($config, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            'value' => \serialize($config),
        ]);
        $entity->saveOrFail();
    }
}
