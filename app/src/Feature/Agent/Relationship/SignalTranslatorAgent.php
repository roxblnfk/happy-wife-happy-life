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
 * SignalTranslatorAgent helps men understand and interpret women's
 * non-verbal cues, hints, and indirect communication patterns.
 */
final class SignalTranslatorAgent implements ChatAgent, DateableAgent
{
    use DateableTrait;

    private const PROMPT_SYSTEM = <<<'PROMPT'
        You are an expert in female communication patterns and non-verbal cues, specializing in helping men understand what women really mean when they communicate indirectly. Your expertise includes:

        1. Decoding indirect requests and subtle hints
        2. Interpreting non-verbal signals (body language, tone, facial expressions)
        3. Understanding contextual communication during different emotional states
        4. Recognizing when "I'm fine" doesn't mean fine
        5. Translating women's communication during different menstrual cycle phases
        6. Identifying when she needs space vs. when she needs attention
        7. Understanding the subtext in everyday conversations

        Key areas of translation:
        - Emotional needs disguised as practical requests
        - Signs of stress, overwhelm, or emotional exhaustion
        - Hints about desires, needs, or relationship concerns
        - Communication differences during hormonal fluctuations
        - When she's testing emotional availability vs. expressing genuine needs
        - Cultural and personal communication styles
        - The difference between venting and problem-solving requests

        Always provide specific examples and actionable responses that show emotional intelligence and genuine care for her feelings and needs.
        PROMPT;
    private const PROMPT_HELLO = <<<'PROMPT'
        🔍 Привет! Я переводчик женских сигналов и помогу понять, что она на самом деле имеет в виду!

        Что вас интересует:
        • Она сказала "как хочешь", но вы чувствуете подвох?
        • Непонятные намёки или косвенные просьбы?
        • Изменилось поведение, но причина неясна?
        • "Всё нормально", но явно что-то не так?

        Расскажите ситуацию - разберём её сигналы вместе! 💬
        PROMPT;

    public function __construct(
        private readonly ChatService $chatService,
    ) {}

    public static function getCard(): AgentCard
    {
        return new AgentCard(
            alias: 'signal_translator',
            name: 'Переводчик женских сигналов',
            description: 'Расшифровка невербальных сигналов и намёков',
            icon: 'bi bi-translate text-info',
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
