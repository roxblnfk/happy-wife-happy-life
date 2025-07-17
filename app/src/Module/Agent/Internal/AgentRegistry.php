<?php

declare(strict_types=1);

namespace App\Module\Agent\Internal;

use App\Module\Agent\AgentCard;
use App\Module\Agent\AgentProvider;
use App\Module\Agent\ChatAgent;
use Psr\Container\ContainerInterface;
use Spiral\Core\Attribute\Proxy;
use Spiral\Core\Attribute\Singleton;
use Spiral\Tokenizer\Attribute\TargetClass;
use Spiral\Tokenizer\TokenizationListenerInterface;

#[Singleton]
#[TargetClass(ChatAgent::class)]
class AgentRegistry implements TokenizationListenerInterface, AgentProvider
{
    /**
     * @var array<non-empty-string, class-string<ChatAgent>>
     */
    private array $agents = [];

    public function __construct(
        #[Proxy]
        private readonly ContainerInterface $container,
    ) {}

    public function buildAgent(string $name): ChatAgent
    {
        $class = $this->getClassByName($name) ?? throw new \InvalidArgumentException(
            "Agent class for name '{$name}' not found.",
        );

        return $this->container->get($class);
    }

    public function getAgentClasses(): array
    {
        return $this->agents;
    }

    public function getAgentCards(): array
    {
        return \array_map(static fn(string $class): AgentCard => $class::getCard(), $this->agents);
    }

    public function getClassByName(string $name): ?string
    {
        return $this->agents[$name] ?? null;
    }

    public function listen(\ReflectionClass $class): void
    {
        if ($class->isAbstract()) {
            return;
        }

        /**
         * @var \ReflectionClass<ChatAgent> $class
         * @var AgentCard $card
         */
        $card = $class->getMethod('getCard')->invoke(null);
        $this->agents[$card->alias] = $class->getName();
    }

    public function finalize(): void {}
}
