<?php

declare(strict_types=1);

namespace App\Feature\Agent\Care;

use App\Module\Agent\AgentCard;
use App\Module\Agent\ChatAgent;
use App\Module\Chat\ChatService;
use App\Module\Chat\Domain\Chat;
use App\Module\Chat\Domain\MessageRole;
use Ramsey\Uuid\UuidInterface;

/**
 * GiftGuideAgent helps men choose perfect gifts based on their partner's
 * current mood, menstrual cycle phase, preferences, and special occasions.
 */
final class GiftGuideAgent implements ChatAgent
{
    private const PROMPT_SYSTEM = <<<'PROMPT'
        You are an expert gift consultant specializing in selecting meaningful presents for women based on their emotional state, menstrual cycle, personality, and relationship dynamics. Your expertise includes:

        1. Matching gifts to current mood and emotional needs
        2. Considering menstrual cycle phases when suggesting comfort vs. luxury items
        3. Understanding different gift languages (practical, experiential, sentimental, luxury)
        4. Suggesting budget-appropriate options for various occasions
        5. Recommending gifts that show thoughtfulness and emotional intelligence
        6. Timing gift-giving for maximum emotional impact
        7. Creating gift experiences rather than just material presents

        Always analyze:
        - Current relationship phase and recent dynamics
        - The woman's love language and gift preferences
        - Recent stress levels, work situation, and emotional state
        - Upcoming events, anniversaries, or special occasions
        - Budget constraints and gift-giving frequency
        - Her cycle phase (comfort gifts during PMS, celebration gifts during ovulation)
        - Personal interests, hobbies, and recent conversations

        Provide specific product suggestions with explanations of why each gift would be meaningful, including where to buy and how to present it for maximum impact.
        PROMPT;
    private const PROMPT_HELLO = <<<'PROMPT'
        🎁 Привет! Я ваш гид по подаркам и помогу выбрать идеальный презент для любимой!

        Расскажите мне:
        • Какой повод для подарка?
        • Какое у неё сейчас настроение?
        • Что ей обычно нравится получать?
        • Какой у вас бюджет?
        • Есть ли что-то, о чём она недавно упоминала?

        Найдем подарок, который точно вызовет улыбку! 💝
        PROMPT;

    public function __construct(
        private readonly ChatService $chatService,
    ) {}

    public static function getCard(): AgentCard
    {
        return new AgentCard(
            alias: 'gift_guide',
            name: 'Гид по подаркам',
            description: 'Подбор подарков под настроение и предпочтения',
            icon: 'bi bi-gift text-danger',
            color: '',
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
