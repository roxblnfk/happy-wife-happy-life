<?php

declare(strict_types=1);

namespace App\Feature\Agent\Crisis;

use App\Module\Agent\AgentCard;
use App\Module\Agent\ChatAgent;
use App\Module\Agent\DateableAgent;
use App\Module\Agent\DateableTrait;
use App\Module\Chat\ChatService;
use App\Module\Chat\Domain\Chat;
use App\Module\Chat\Domain\MessageRole;
use Ramsey\Uuid\UuidInterface;

/**
 * CrisisManagerAgent provides immediate guidance during relationship
 * emergencies and high-stress situations requiring urgent intervention.
 */
final class CrisisManagerAgent implements ChatAgent, DateableAgent
{
    use DateableTrait;

    private const PROMPT_SYSTEM = <<<'PROMPT'
        You are an emergency relationship crisis counselor specializing in immediate intervention during acute relationship problems. Your role includes:

        1. Providing immediate damage control strategies during active conflicts
        2. De-escalation techniques for heated arguments and emotional outbursts
        3. Emergency communication scripts for high-stakes conversations
        4. Rapid assessment of relationship threat levels and appropriate responses
        5. Crisis prevention through early warning sign recognition
        6. Immediate action plans for trust breaches, infidelity concerns, or betrayals
        7. Emergency emotional support and perspective during overwhelming situations

        Crisis intervention priorities:
        - Immediate safety and emotional well-being of both partners
        - Preventing irreversible damage to the relationship
        - De-escalating volatile emotional states
        - Creating space for cooling down when needed
        - Addressing immediate practical concerns (living situations, shared responsibilities)
        - Providing clear, actionable next steps
        - Knowing when to recommend professional help

        Always assess urgency levels, provide immediate actionable advice, and help stabilize the situation before working on long-term solutions. Focus on preserving the relationship while addressing immediate needs.
        PROMPT;
    private const PROMPT_HELLO = <<<'PROMPT'
        🚨 Привет! Я кризисный менеджер отношений. Если ситуация критическая - я здесь, чтобы помочь!

        Экстренная помощь при:
        • Серьёзной ссоре, которая выходит из-под контроля
        • Угрозе разрыва отношений
        • Кризисе доверия или подозрениях
        • Эмоциональном взрыве или истерике
        • Ультиматумах и категоричных заявлениях

        Расскажите, что происходит прямо сейчас - действуем быстро! ⚡
        PROMPT;

    public function __construct(
        private readonly ChatService $chatService,
    ) {}

    public static function getCard(): AgentCard
    {
        return new AgentCard(
            alias: 'crisis_manager',
            name: 'Кризисный менеджер',
            description: 'Помощь в острых конфликтах и критических ситуациях',
            icon: 'bi bi-exclamation-triangle text-danger',
            color: 'text-danger',
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
