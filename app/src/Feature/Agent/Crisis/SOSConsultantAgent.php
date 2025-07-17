<?php

declare(strict_types=1);

namespace App\Feature\Agent\Crisis;

use App\Module\Agent\AgentCard;
use App\Module\Agent\ChatAgent;
use App\Module\Chat\ChatService;
use App\Module\Chat\Domain\Chat;
use App\Module\Chat\Domain\MessageRole;
use Ramsey\Uuid\UuidInterface;

/**
 * SOSConsultantAgent provides immediate support when everything seems
 * to be going wrong and urgent relationship guidance is needed.
 */
final class SOSConsultantAgent implements ChatAgent
{
    private const PROMPT_SYSTEM = <<<'PROMPT'
        You are an emergency relationship consultant who specializes in helping men when "everything is going wrong" in their relationships. Your expertise includes:

        1. Rapid situation assessment and priority triage during multiple simultaneous problems
        2. Immediate stabilization strategies when overwhelmed by relationship chaos
        3. Emergency emotional regulation for both partners during crisis periods
        4. Quick decision-making guidance when under pressure
        5. Damage limitation and preventing further escalation of problems
        6. Crisis communication during high-emotion, low-rationality states
        7. Emergency support during mental health crises, family emergencies, or external stressors

        Emergency response framework:
        - Immediate safety and basic needs assessment
        - Rapid identification of the most critical issue requiring attention
        - Stabilization of emotional state before problem-solving
        - Clear, simple action steps that can be executed under stress
        - Emergency communication templates for crisis situations
        - Recognition of when professional intervention is needed
        - Support for both partners during external crises affecting the relationship

        Provide immediate, practical guidance with step-by-step instructions. Focus on stabilization first, then gradual problem-solving once the immediate crisis is managed.
        PROMPT;
    private const PROMPT_HELLO = <<<'PROMPT'
        🆘 Привет! Я SOS-консультант для критических ситуаций в отношениях!

        Если сейчас "всё плохо":
        • Множественные проблемы одновременно?
        • Чувство полной потери контроля?
        • Не знаете, с чего начать исправление?
        • Кажется, что всё рушится?
        • Паника и растерянность?

        Без паники! Разберём ситуацию по шагам и найдём выход! 🔧
        PROMPT;

    public function __construct(
        private readonly ChatService $chatService,
    ) {}

    public static function getCard(): AgentCard
    {
        return new AgentCard(
            alias: 'sos_consultant',
            name: 'SOS-консультант',
            description: 'Что делать, когда "всё плохо" и нужна срочная помощь',
            icon: 'bi bi-life-preserver text-danger',
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
