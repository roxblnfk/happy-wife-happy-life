<?php

declare(strict_types=1);

namespace App\Module\Agent;

/**
 * Provides a list of agents.
 */
interface AgentProvider
{
    /**
     * Returns a list of available agents.
     *
     * @return array<non-empty-string, class-string<ChatAgent>> List of agent class names indexed by their names.
     */
    public function getAgentClasses(): array;

    /**
     * Returns a list of agent cards.
     *
     * @return array<AgentCard> List of agent cards.
     */
    public function getAgentCards(): array;

    /**
     * @param non-empty-string $name Agent class name.
     * @return class-string<ChatAgent>|null Returns the agent class if found, null otherwise.
     */
    public function getClassByName(string $name): ?string;

    /**
     * Builds an agent instance by its name.
     *
     * @param non-empty-string $name Agent class name.
     * @return ChatAgent Returns an instance of the agent.
     */
    public function buildAgent(string $name): ChatAgent;
}
