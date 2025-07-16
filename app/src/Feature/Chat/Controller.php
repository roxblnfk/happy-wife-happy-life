<?php

declare(strict_types=1);

namespace App\Feature\Chat;

use App\Module\Chat\ChatService;
use App\Module\Chat\Domain\Chat;
use App\Module\Chat\Domain\Message;
use App\Module\Config\ConfigService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Router\Annotation\Route;
use Spiral\Views\ViewsInterface;

/**
 * Chat controller for AI chat sessions with HTMX frontend
 */
final class Controller
{
    use PrototypeTrait;

    public const ROUTE_CHATS = 'chat-chats';
    public const ROUTE_CREATE = 'chat-create';
    public const ROUTE_DELETE = 'chat-delete';
    public const ROUTE_CHAT = 'chat-view';
    public const ROUTE_LIST = 'chat-list';
    public const ROUTE_SEND = 'chat-send';
    public const ROUTE_MESSAGES = 'chat-messages';
    public const ROUTE_MESSAGES_SINCE = 'chat-messages-since';
    public const ROUTE_MESSAGE_TOKENS = 'chat-message-tokens';

    public function __construct(
        private readonly ChatService $chatService,
        private readonly ViewsInterface $views,
        private readonly ConfigService $configService,
    ) {}

    /**
     * Renders the chats list page with sidebar
     */
    #[Route(route: '/chats', name: self::ROUTE_CHATS, methods: ['GET'])]
    public function chats(): mixed
    {
        return $this->views->render('chat:chats');
    }

    /**
     * Returns HTML list of all chat items for sidebar
     * Used by HTMX: GET /chat/list (polling every 1s)
     * Response: HTML fragments with chat list items
     */
    #[Route(route: '/chat/list', name: self::ROUTE_LIST, methods: ['GET'])]
    public function listChats(): string
    {
        return $this->views->render('chat:chat-list', [
            'chats' => Chat::findAll(),
        ]);
    }

    /**
     * Creates a new chat session and returns chat interface HTML
     * Used by HTMX: POST /chat/create
     * Response: HTML content for chat area
     */
    #[Route(route: '/chat/create', name: self::ROUTE_CREATE, methods: ['POST'])]
    public function createChat(): string
    {
        return $this->views->render('chat:chat', [
            'chat' => $this->chatService->createChat(),
        ]);
    }

    /**
     * Creates a new chat session and returns chat interface HTML
     * Used by HTMX: POST /chat/create
     * Response: HTML content for chat area
     */
    #[Route(route: '/chat/<uuid>/delete', name: self::ROUTE_DELETE, methods: ['DELETE'])]
    public function deleteChat(string $uuid): ResponseInterface
    {
        $this->chatService->deleteChat($uuid);
        return $this->response->create(200);
    }

    /**
     * Returns specific chat interface HTML
     * Used by HTMX: GET /chat/{uuid}
     * Response: HTML content for chat area
     */
    #[Route(route: '/chat/<uuid>', name: self::ROUTE_CHAT, methods: ['GET'])]
    public function getChat(string $uuid): string
    {
        $chat = Chat::findByPK($uuid) ?? throw new \RuntimeException('Chat not found.');
        return $this->views->render('chat:chat', [
            'chat' => $chat,
        ]);
    }

    /**
     * Returns new messages HTML since specified message UUID
     * Used by HTMX: GET /chat/{uuid}/messages/{last_uuid} (polling every 300ms)
     * Response: HTML fragments with new messages or empty content
     *
     * @param non-empty-string $uuid Chat UUID
     * @param non-empty-string $lastUuid Last message UUID to get messages since
     */
    #[Route(route: '/chat/<uuid>/messages[/<lastUuid>]', name: self::ROUTE_MESSAGES_SINCE, methods: ['GET'])]
    public function getMessagesSince(string $uuid, ?string $lastUuid = null): ResponseInterface|string
    {
        $q = Message::query()->where('chatUuid', $uuid);
        $lastUuid === null or $q->where('uuid', '>', $lastUuid);
        $messages = $q->orderBy('createdAt', 'ASC')->limit(10)->fetchAll();

        if ($messages === []) {
            return '';
        }

        return $this->views->render('chat:messages', [
            'messages' => $messages,
        ]);
    }

    /**
     * Sends a new message to the chat and returns updated messages HTML
     * Used by HTMX: POST /chat/{uuid}/send
     * Request body: form data with 'message' field
     * Response: HTML content for messages list
     */
    #[Route(route: '/chat/<uuid>/send', name: self::ROUTE_SEND, methods: ['POST'])]
    public function sendMessage(string $uuid, ServerRequestInterface $request): string
    {
        $data = $request->getParsedBody();
        $this->chatService->sendMessage($uuid, $data['message'], isHuman: true);

        return $this->views->render('chat:messages', [
            'messages' => [],
        ]);
    }

    /**
     * Returns current tokens and status for a pending message
     * Used by JavaScript polling: GET /chat/message/{uuid}/tokens (every 300ms)
     * Response: JSON {"tokens": "partial response text", "status": "pending|completed", "append": true< "position": 24}
     */
    #[Route(route: '/chat/message/<uuid>/tokens[/<offset>]', name: self::ROUTE_MESSAGE_TOKENS, methods: ['GET'])]
    public function getMessageTokens(string $uuid, ?string $offset = null): array
    {
        $offset = (int) $offset;
        $message = Message::findByPK($uuid) ?? throw new \RuntimeException('Message not found.');
        $tokens = $this->chatService->getNewTokens($message, $offset);
        return [
            'tokens' => $tokens,
            'status' => $message->status->value,
            'append' => true,
            'offset' => \strlen($tokens) + $offset,
        ];
    }
}
