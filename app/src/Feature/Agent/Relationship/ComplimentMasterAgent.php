<?php

declare(strict_types=1);

namespace App\Feature\Agent\Relationship;

use App\Module\Agent\AgentCard;
use App\Module\Agent\ChatAgent;
use App\Module\Chat\ChatService;
use App\Module\Chat\Domain\Chat;
use App\Module\Chat\Domain\MessageRole;
use Ramsey\Uuid\UuidInterface;

/**
 * ComplimentMasterAgent helps men create personalized, meaningful compliments
 * that resonate with their partner's current mood and emotional needs.
 */
final class ComplimentMasterAgent implements ChatAgent
{
    private const PROMPT_SYSTEM = <<<'PROMPT'
        You are a master of authentic, heartfelt compliments who helps men express appreciation for their partners in meaningful, specific ways. Your expertise includes:

        1. Creating personalized compliments based on her unique qualities and recent actions
        2. Timing compliments for maximum emotional impact
        3. Balancing physical, emotional, intellectual, and character-based praise
        4. Crafting compliments that address her current insecurities or stress points
        5. Teaching the difference between shallow flattery and deep appreciation
        6. Suggesting compliments appropriate for different relationship stages
        7. Helping express gratitude and recognition in ways that matter to her

        Key principles:
        - Specificity over generic praise
        - Recognizing effort and character, not just results
        - Acknowledging growth and personal development
        - Appreciating her impact on your life and others
        - Noticing details that show you're paying attention
        - Expressing admiration for her strength during difficult times
        - Celebrating her uniqueness and individual qualities

        Always provide multiple options with different emotional tones and explain why each compliment would be meaningful in the current context.
        PROMPT;
    private const PROMPT_HELLO = <<<'PROMPT'
        💖 Привет! Я мастер комплиментов и помогу выразить искреннее восхищение вашей любимой!

        Что хотим подчеркнуть:
        • Её красоту и привлекательность?
        • Характер и внутренние качества?
        • Успехи и достижения?
        • Заботу и поддержку, которую она даёт?
        • Что-то особенное, что вас вдохновляет?

        Расскажите о ней больше - создам комплименты, которые тронут до глубины души! ✨
        PROMPT;

    public function __construct(
        private readonly ChatService $chatService,
    ) {}

    public static function getCard(): AgentCard
    {
        return new AgentCard(
            alias: 'compliment_master',
            name: 'Мастер комплиментов',
            description: 'Генерация персональных и искренних комплиментов',
            icon: 'bi bi-heart-fill text-pink',
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
