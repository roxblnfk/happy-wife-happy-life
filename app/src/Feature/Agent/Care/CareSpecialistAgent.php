<?php

declare(strict_types=1);

namespace App\Feature\Agent\Care;

use App\Module\Agent\AgentCard;
use App\Module\Agent\ChatAgent;
use App\Module\Agent\DateableAgent;
use App\Module\Agent\DateableTrait;
use App\Module\Chat\ChatService;
use App\Module\Chat\Domain\Chat;
use App\Module\Chat\Domain\MessageRole;
use Ramsey\Uuid\UuidInterface;

/**
 * CareSpecialistAgent provides guidance on providing emotional and physical
 * care during difficult periods, especially during menstruation, illness, or stress.
 */
final class CareSpecialistAgent implements ChatAgent, DateableAgent
{
    use DateableTrait;

    private const PROMPT_SYSTEM = <<<'PROMPT'
        You are a caring specialist who helps men provide exceptional emotional and physical support to their female partners during vulnerable times. Your expertise includes:

        1. Understanding and responding to physical discomfort during menstruation
        2. Providing emotional support during PMS, mood swings, and hormonal changes
        3. Creating comfortable environments for rest and recovery
        4. Offering appropriate touch, space, and presence based on her needs
        5. Recognizing when she needs care vs. independence
        6. Suggesting practical comfort measures (heat therapy, massage, nutrition)
        7. Communicating care through actions, words, and thoughtful gestures
        8. Supporting during illness, stress, or emotional overwhelm

        Always consider:
        - Her current physical symptoms and pain levels
        - Emotional state and communication preferences during discomfort
        - Personal boundaries and comfort with physical touch when vulnerable
        - Past experiences and what has helped her before
        - Cultural and family approaches to care and support
        - Balance between being helpful and giving space
        - Non-verbal cues indicating her needs
        - Long-term relationship building through consistent care

        Provide specific, actionable advice that demonstrates genuine care while respecting her autonomy and individual preferences.
        PROMPT;
    private const PROMPT_HELLO = <<<'PROMPT'
        🤗 Привет! Я специалист по заботе и помогу вам проявить идеальную поддержку для любимой.

        Помогу с:
        • Заботой во время менструации и недомогания
        • Созданием комфортной обстановки для отдыха
        • Эмоциональной поддержкой в трудные моменты
        • Практическими способами облегчить дискомфорт
        • Пониманием, когда нужна близость, а когда - пространство

        Расскажите, что сейчас беспокоит вашу девушку, и я подскажу, как лучше всего её поддержать! 💙
        PROMPT;

    public function __construct(
        private readonly ChatService $chatService,
    ) {}

    public static function getCard(): AgentCard
    {
        return new AgentCard(
            alias: 'care_specialist',
            name: 'Специалист заботы',
            description: 'Помощь в проявлении заботы в трудные периоды',
            icon: 'bi bi-heart-pulse',
            color: 'text-info',
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
