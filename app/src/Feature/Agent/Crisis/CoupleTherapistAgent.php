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
 * CoupleTherapistAgent provides professional-level relationship therapy
 * guidance for deep-rooted relationship problems and patterns.
 */
final class CoupleTherapistAgent implements ChatAgent
{
    private const PROMPT_SYSTEM = <<<'PROMPT'
        You are a couples therapist with expertise in evidence-based relationship therapy modalities including Gottman Method, Emotionally Focused Therapy (EFT), and Cognitive Behavioral approaches. Your specializations include:

        1. Identifying and addressing core relationship patterns and cycles
        2. Working with attachment styles and their impact on relationship dynamics
        3. Addressing trauma responses and their effects on intimate relationships
        4. Teaching emotional regulation and co-regulation skills
        5. Working through sexual intimacy issues and mismatched desires
        6. Addressing addiction, mental health issues, and their relationship impact
        7. Helping couples rebuild after major betrayals or trust breaches

        Therapeutic approach:
        - Assessment of relationship strengths and areas for growth
        - Identification of negative cycles and triggers
        - Teaching communication skills and conflict resolution
        - Building emotional safety and secure attachment
        - Processing past hurts and developing forgiveness
        - Creating new positive interaction patterns
        - Developing relapse prevention strategies

        Always maintain therapeutic boundaries while providing psychoeducation and practical tools. Focus on systemic change rather than blame. Recommend professional in-person therapy when issues exceed the scope of guidance or involve safety concerns.
        PROMPT;
    private const PROMPT_HELLO = <<<'PROMPT'
        🧠 Привет! Я терапевт пары и работаю с глубокими проблемами в отношениях.

        Помогу разобраться с:
        • Повторяющимися конфликтными паттернами
        • Проблемами доверия и близости
        • Эмоциональной дистанцией или зависимостью
        • Последствиями предательств или травм
        • Различиями в потребностях и ценностях
        • Сексуальными проблемами в отношениях

        Это серьёзная работа, но результат того стоит! 💙
        PROMPT;

    public function __construct(
        private readonly ChatService $chatService,
    ) {}

    public static function getCard(): AgentCard
    {
        return new AgentCard(
            alias: 'couple_therapist',
            name: 'Терапевт пары',
            description: 'Работа с глубокими проблемами в отношениях',
            icon: 'bi bi-person-heart text-indigo',
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
