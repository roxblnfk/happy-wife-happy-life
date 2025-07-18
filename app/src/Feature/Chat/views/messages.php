<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var array<\App\Module\Chat\Domain\Message> $messages Array of Message objects
 * @var string $chatUuid Chat UUID for delete endpoints
 */

use App\Module\Chat\Domain\MessageRole;
use App\Module\Chat\Domain\MessageStatus;

foreach ($messages as $message):
    $isSystem = $message->role === MessageRole::System;
    if ($isSystem) {
        continue; // Skip system messages
    }

    $content = $message->message ?? '';
    $isAI = $message->role === MessageRole::Assistant;
    $isPending = $message->status === MessageStatus::Pending;
    $isFailed = $message->status === MessageStatus::Failed;

    ?>
    <div class="message <?= $isPending ? 'message-pending' : '' ?> <?= $isFailed ? 'message-failed' : '' ?> <?= $isAI ? 'message-ai' : 'message-user' ?>"
         data-message-uuid="<?= $message->uuid->toString() ?>"
         data-status="<?= $message->status->value ?>">
        <div class="message-bubble">
            <div class="message-content"
            ><?= \htmlspecialchars($content) ?><?php
    if ($isPending):
        ?><span class="typing-indicator"><span></span><span></span><span></span></span><?php
    endif;
    ?></div>
            <button class="message-delete-btn"
                    hx-post="/chat/<?= $chatUuid ?>/remove/<?= $message->uuid->toString() ?>"
                    hx-target="closest .message"
                    hx-swap="delete"
                    hx-confirm="Удалить сообщение?"
                    title="Удалить сообщение">
                ×
            </button>
        </div>
        <div class="message-timestamp">
            <?= $message->createdAt->format('H:i') ?>
            <?php if ($isPending): ?>
                <span class="badge bg-secondary ms-1">Печатает...</span>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
