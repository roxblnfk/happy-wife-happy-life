<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var array<\App\Module\Chat\Domain\Chat> $chats Array of Chat objects
 */
foreach ($chats as $chat):
    $chat->messages;
    $lastMessage = \end($chat->messages);
    $preview = $lastMessage ? \substr($lastMessage->message ?? '', 0, 50) : 'Новый чат';
    if (\strlen($preview) > 47) {
        $preview = \substr($preview, 0, 47) . '...';
    }
    ?>
    <div class="chat-item p-3 border-bottom"
         hx-get="/chat/<?= $chat->uuid->toString() ?>"
         hx-target="#chat-area"
         hx-swap="innerHTML">
        <div class="d-flex">
            <div class="me-3">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                     style="width: 40px; height: 40px;">
                    <i class="bi bi-chat-dots text-white"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-1"><?= \htmlspecialchars($chat->title ?? $chat->uuid->toString()) ?></h6>
                <p class="mb-1 text-muted small"><?= \htmlspecialchars($preview) ?></p>
                <small class="text-muted">
                    <?= $chat->createdAt->format('d.m.Y H:i') ?>
                </small>
            </div>
        </div>
    </div>
<?php endforeach; ?>
