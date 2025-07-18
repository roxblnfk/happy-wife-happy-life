<?php

declare(strict_types=1);

namespace App\Feature\Agent\Relationship;

use App\Module\Agent\AgentCard;
use App\Module\Agent\ChatAgent;
use App\Module\Agent\DateableAgent;
use App\Module\Agent\DateableTrait;
use App\Module\Chat\ChatService;
use App\Module\Chat\Domain\Chat;
use App\Module\Chat\Domain\MessageRole;
use Ramsey\Uuid\UuidInterface;

/**
 * ApologyExpertAgent specializes in helping men apologize effectively,
 * take responsibility, and rebuild trust after relationship conflicts.
 */
final class ApologyExpertAgent implements ChatAgent, DateableAgent
{
    use DateableTrait;

    private const PROMPT_SYSTEM = <<<'PROMPT'
        You are an expert in sincere apologies and relationship repair, specializing in helping men take accountability and rebuild trust with their partners. Your expertise includes:

        1. Crafting genuine, specific apologies that acknowledge harm and take responsibility
        2. Understanding the difference between explanation and excuse-making
        3. Timing apologies appropriately based on her emotional state and cycle
        4. Creating action plans that demonstrate commitment to change
        5. Helping men understand the impact of their actions on their partner's emotions
        6. Rebuilding trust through consistent follow-through on promises
        7. Addressing underlying patterns that led to the need for apology

        Key components of effective apologies:
        - Specific acknowledgment of what went wrong
        - Taking full responsibility without deflecting or minimizing
        - Recognizing the emotional impact on her
        - Expressing genuine remorse and regret
        - Committing to specific behavioral changes
        - Following through with actions, not just words
        - Understanding that forgiveness is a process, not an immediate result

        Always help craft apologies that are authentic, avoid defensiveness, and focus on repair rather than self-justification. Include guidance on how to demonstrate change through actions.
        PROMPT;
    private const PROMPT_HELLO = <<<'PROMPT'
        🙏 Привет! Я эксперт по извинениям и помогу исправить ситуацию в ваших отношениях.

        Давайте разберём:
        • Что именно произошло?
        • Как ваши действия повлияли на неё?
        • Что вы готовы изменить в поведении?
        • Как лучше выразить искреннее сожаление?

        Вместе создадим извинение, которое поможет восстановить доверие и близость! 💙
        PROMPT;

    public function __construct(
        private readonly ChatService $chatService,
    ) {}

    public static function getCard(): AgentCard
    {
        return new AgentCard(
            alias: 'apology_expert',
            name: 'Эксперт по извинениям',
            description: 'Как правильно просить прощение и восстанавливать доверие',
            icon: 'bi bi-hand-thumbs-up text-primary',
            color: 'text-warning',
        );
    }

    public function chatInit(Chat $chat): void
    {
        $this->chatService->sendMessage(
            $chat,
            self::PROMPT_SYSTEM,
            role: MessageRole::System,
        );
        $this->chatService->sendMessage(
            $chat,
            self::PROMPT_HELLO,
            role: MessageRole::Agent,
        );
    }

    public function chatProcess(Chat $chat, UuidInterface $messageUuid): void {}

    public function canHandle(Chat $chat): bool
    {
        return false;
    }
}
