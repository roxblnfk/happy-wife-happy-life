<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var Chat $chat
 */

use App\Module\Chat\Domain\Chat;

?>

<div class="d-flex flex-column h-100" id="chat-container" data-chat-uuid="<?= $chat->uuid ?>">
    <!-- Chat Header -->
    <div class="bg-white border-bottom p-3">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                     style="width: 40px; height: 40px;">
                    <i class="bi bi-robot text-white"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <h5 class="mb-0"><?= \htmlspecialchars($chat->title ?? 'Новый чат') ?></h5>
                <small class="text-muted">Онлайн</small>
            </div>
            <div class="ms-2">
                <button type="button"
                        class="btn btn-outline-danger btn-sm"
                        hx-delete="/chat/<?= $chat->uuid->toString() ?>/delete"
                        hx-confirm="Вы уверены, что хотите удалить этот чат?"
                        hx-target="closest #chat-container"
                        hx-swap="outerHTML"
                        title="Удалить чат">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Messages Container -->
    <div class="flex-grow-1 overflow-auto p-3" id="messages-container" style="background-color: #f8f9fa;">
        <div id="messages-list"
             data-chat-uuid="<?= $chat->uuid->toString() ?>"
             hx-get="/chat/<?= $chat->uuid->toString() ?>/messages">
<!--             hx-trigger="load"-->
<!--             hx-swap="innerHTML">-->
            <!-- Messages will be loaded here -->
        </div>
    </div>

    <!-- Message Input -->
    <div class="bg-white border-top p-3">
        <form id="message-form"
              hx-post="/chat/<?= $chat->uuid->toString() ?>/send"
              hx-target="#messages-list"
              hx-swap="beforeend"
              class="d-flex gap-2">
            <div class="flex-grow-1">
                <textarea class="form-control"
                          name="message"
                          id="message-textarea"
                          placeholder="Введите ваше сообщение..."
                          rows="1"
                          style="resize: none; min-height: 40px;"
                          required></textarea>
            </div>
            <button type="submit" class="btn btn-primary px-3" id="send-button">
                <i class="bi bi-send"></i>
            </button>
        </form>
    </div>
</div>

<style>
    .message {
        margin-bottom: 1rem;
        max-width: 80%;
    }

    .message-user {
        margin-left: auto;
    }

    .message-user .message-content {
        background-color: #0d6efd;
        color: white;
        border-radius: 18px 18px 4px 18px;
        padding: 12px 16px;
        text-align: right;
    }

    .message-ai {
        margin-right: auto;
    }

    .message-ai .message-content {
        background-color: white;
        color: #333;
        border-radius: 18px 18px 18px 4px;
        padding: 12px 16px;
        border: 1px solid #e9ecef;
    }

    .message-pending .message-content {
        background-color: #f8f9fa;
        border: 1px dashed #dee2e6;
        position: relative;
    }

    .message-failed .message-content {
        background-color: #b67f7f;
        border: 1px solid #b63636;
        position: relative;
    }

    .message-timestamp {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 4px;
    }

    .message-user .message-timestamp {
        text-align: right;
    }

    .message-ai .message-timestamp {
        text-align: left;
    }

    #messages-container {
        scrollbar-width: thin;
        scrollbar-color: #c1c1c1 #f1f1f1;
    }

    #messages-container::-webkit-scrollbar {
        width: 6px;
    }

    #messages-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    #messages-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    #messages-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Auto-resize textarea */
    textarea {
        transition: height 0.2s;
    }

    /* Typing indicator */
    .typing-indicator {
        display: inline-flex;
        align-items: center;
        margin-left: 8px;
    }

    .typing-indicator span {
        height: 6px;
        width: 6px;
        background-color: #999;
        border-radius: 50%;
        display: inline-block;
        margin: 0 1px;
        animation: typing 1.4s infinite ease-in-out;
    }

    .typing-indicator span:nth-child(1) {
        animation-delay: -0.32s;
    }

    .typing-indicator span:nth-child(2) {
        animation-delay: -0.16s;
    }

    @keyframes typing {
        0%, 80%, 100% {
            transform: scale(0.8);
            opacity: 0.5;
        }
        40% {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Error state for form */
    .form-error {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }

    .sending-state {
        opacity: 0.7;
        pointer-events: none;
    }
</style>

<script>
    // Немедленное выполнение без ожидания DOMContentLoaded
    (function() {

        const chatContainer = document.getElementById('chat-container');
        if (!chatContainer) {
            console.error('Chat container not found');
            return;
        }

        const chatUuid = chatContainer.dataset.chatUuid;

        let messagesPollingInterval = null;
        let tokensPollingInterval = null;
        let isInitialized = false;

        // Флаги для отслеживания состояния запросов
        let isPollingMessages = false;
        let isPollingTokens = false;

        // Form state tracking
        let pendingMessageText = '';
        let isFormSubmitting = false;

        // Position tracking for pending messages
        const messagePositions = new Map();

        function initializeChat() {
            if (isInitialized) {
                return;
            }

            isInitialized = true;

            const messagesContainer = document.getElementById('messages-container');
            const messagesList = document.getElementById('messages-list');
            const textarea = document.getElementById('message-textarea');
            const messageForm = document.getElementById('message-form');
            const sendButton = document.getElementById('send-button');

            if (!messagesContainer || !messagesList || !textarea || !messageForm) {
                console.error('Required chat elements not found');
                return;
            }

            // Auto-scroll to bottom when new messages arrive
            function scrollToBottom() {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Auto-resize textarea
            function adjustTextareaHeight() {
                textarea.style.height = 'auto';
                textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
            }

            textarea.addEventListener('input', adjustTextareaHeight);

            // Submit on Enter (but allow Shift+Enter for new line)
            textarea.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    if (this.value.trim() && !isFormSubmitting) {
                        htmx.trigger(messageForm, 'submit');
                    }
                }
            });

            // Handle form submission state
            function setFormSubmittingState(submitting) {
                isFormSubmitting = submitting;
                
                if (submitting) {
                    messageForm.classList.add('sending-state');
                    sendButton.disabled = true;
                    textarea.disabled = true;
                } else {
                    messageForm.classList.remove('sending-state');
                    sendButton.disabled = false;
                    textarea.disabled = false;
                }
            }

            // Clear form error state
            function clearFormError() {
                textarea.classList.remove('form-error');
            }

            // Set form error state
            function setFormError() {
                textarea.classList.add('form-error');
                setTimeout(clearFormError, 3000); // Clear error after 3 seconds
            }

            // Function to get last message UUID from DOM
            function getLastMessageUuid() {
                const messages = messagesList.querySelectorAll('.message[data-message-uuid]');
                if (messages.length > 0) {
                    return messages[messages.length - 1].dataset.messageUuid;
                }
                return null;
            }

            // Initialize positions for new pending messages
            function initializePendingMessagePositions() {
                const pendingMessages = messagesList.querySelectorAll('.message-pending[data-message-uuid]');

                pendingMessages.forEach(messageEl => {
                    const messageUuid = messageEl.dataset.messageUuid;
                    if (messageUuid && !messagePositions.has(messageUuid)) {
                        messagePositions.set(messageUuid, 0);
                    }
                });
            }

            // Clean up positions for completed or removed messages
            function cleanupMessagePositions() {
                const currentPendingUuids = new Set();
                const pendingMessages = messagesList.querySelectorAll('.message-pending[data-message-uuid]');

                pendingMessages.forEach(messageEl => {
                    const messageUuid = messageEl.dataset.messageUuid;
                    if (messageUuid) {
                        currentPendingUuids.add(messageUuid);
                    }
                });

                // Remove positions for messages that are no longer pending
                for (const [uuid] of messagePositions) {
                    if (!currentPendingUuids.has(uuid)) {
                        messagePositions.delete(uuid);
                    }
                }
            }

            // Poll for new messages
            function pollNewMessages() {
                // Пропускаем, если уже выполняется запрос
                if (isPollingMessages) {
                    return;
                }

                isPollingMessages = true;

                const currentLastUuid = getLastMessageUuid();
                let url = `/chat/${chatUuid}/messages`;

                // Only append last UUID if we have messages (for incremental loading)
                if (currentLastUuid) {
                    url += `/${currentLastUuid}`;
                }

                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        return response.text();
                    })
                    .then(html => {
                        if (html.trim()) {
                            if (currentLastUuid) {
                                // Append new messages for incremental updates
                                messagesList.insertAdjacentHTML('beforeend', html);
                            } else {
                                // Replace all content for initial load
                                messagesList.innerHTML = html;
                            }

                            // Initialize positions for any new pending messages
                            initializePendingMessagePositions();

                            setTimeout(scrollToBottom, 50);
                        }
                    })
                    .catch(error => {
                        console.error('Error polling new messages:', error);
                    })
                    .finally(() => {
                        isPollingMessages = false;
                    });
            }

            // Poll for pending message tokens
            function pollPendingMessages() {
                // Пропускаем, если уже выполняется запрос
                if (isPollingTokens) {
                    return;
                }

                const pendingMessages = messagesList.querySelectorAll('.message-pending[data-message-uuid]');

                if (pendingMessages.length === 0) {
                    return; // No pending messages, skip polling
                }

                // Initialize positions for any newly detected pending messages
                initializePendingMessagePositions();

                isPollingTokens = true;

                // Создаем массив промисов для всех pending сообщений
                const tokenPromises = Array.from(pendingMessages).map(messageEl => {
                    const messageUuid = messageEl.dataset.messageUuid;
                    if (!messageUuid) {
                        console.warn('Pending message without UUID found');
                        return Promise.resolve();
                    }

                    // Get current position for this message
                    const currentPosition = messagePositions.get(messageUuid) || 0;

                    // Build URL with position parameter
                    const url = `/chat/message/${messageUuid}/tokens/${currentPosition}`;

                    return fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            const contentEl = messageEl.querySelector('.message-content');
                            if (!contentEl) {
                                console.warn('Message content element not found');
                                return;
                            }

                            // Update position if provided in response
                            if (typeof data.position === 'number') {
                                messagePositions.set(messageUuid, data.position);
                            }

                            if (data.tokens) {
                                // Handle append flag
                                if (data.append) {
                                    // Append new tokens to existing content
                                    const typingIndicator = contentEl.querySelector('.typing-indicator');

                                    // Get current text without typing indicator
                                    const currentTextNode = Array.from(contentEl.childNodes)
                                        .find(node => node.nodeType === Node.TEXT_NODE);
                                    const currentText = currentTextNode ? currentTextNode.textContent : '';

                                    // Update text content by appending new tokens
                                    contentEl.innerHTML = '';
                                    contentEl.appendChild(document.createTextNode(currentText + data.tokens));

                                    // Re-add typing indicator if still pending
                                    if (data.status === 'pending' && typingIndicator) {
                                        contentEl.appendChild(typingIndicator.cloneNode(true));
                                    }
                                } else {
                                    // Replace entire content
                                    const typingIndicator = contentEl.querySelector('.typing-indicator');
                                    contentEl.innerHTML = '';
                                    contentEl.appendChild(document.createTextNode(data.tokens));

                                    // Re-add typing indicator if still pending
                                    if (data.status === 'pending' && typingIndicator) {
                                        contentEl.appendChild(typingIndicator.cloneNode(true));
                                    }
                                }
                            }

                            if (data.status === 'completed') {
                                messageEl.classList.remove('message-pending');

                                // Remove typing indicators and badges
                                const indicators = messageEl.querySelectorAll('.typing-indicator, .badge');
                                indicators.forEach(indicator => indicator.remove());

                                // Clean up position tracking for completed message
                                messagePositions.delete(messageUuid);

                                // Trigger a refresh of messages to get the final state
                                setTimeout(() => {
                                    pollNewMessages();
                                }, 100);
                            }
                        })
                        .catch(error => {
                            console.error(`Error polling tokens for message ${messageUuid}:`, error);
                        });
                });

                // Ждем завершения всех запросов токенов
                Promise.all(tokenPromises)
                    .then(() => {
                        // Clean up positions for any removed messages
                        cleanupMessagePositions();
                    })
                    .finally(() => {
                        isPollingTokens = false;
                    });
            }

            // Handle form submission events
            messageForm.addEventListener('htmx:beforeRequest', function(evt) {
                // Store the message text before sending
                pendingMessageText = textarea.value.trim();
                setFormSubmittingState(true);
                clearFormError();
            });

            messageForm.addEventListener('htmx:afterRequest', function(evt) {
                setFormSubmittingState(false);
                
                if (evt.detail.xhr.status === 200) {
                    // Success - clear form and stored text
                    textarea.value = '';
                    pendingMessageText = '';
                    adjustTextareaHeight();
                    
                    // Trigger immediate polling for new messages
                    setTimeout(pollNewMessages, 100);
                } else {
                    // Error - restore message text and show error
                    textarea.value = pendingMessageText;
                    adjustTextareaHeight();
                    setFormError();
                    
                    console.error('Message send failed with status:', evt.detail.xhr.status);
                }
            });

            // Immediate initial load of messages
            pollNewMessages();

            // Start polling intervals
            messagesPollingInterval = setInterval(pollNewMessages, 300);
            tokensPollingInterval = setInterval(pollPendingMessages, 300);

            // Initial scroll to bottom
            setTimeout(scrollToBottom, 200);
        }

        // Cleanup function
        function cleanup() {
            if (messagesPollingInterval) {
                clearInterval(messagesPollingInterval);
                messagesPollingInterval = null;
            }
            if (tokensPollingInterval) {
                clearInterval(tokensPollingInterval);
                tokensPollingInterval = null;
            }
            // Сбрасываем флаги состояния
            isPollingMessages = false;
            isPollingTokens = false;
            isFormSubmitting = false;
            isInitialized = false;

            // Clear stored state
            pendingMessageText = '';
            messagePositions.clear();
        }

        // Initialize immediately if elements are ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeChat);
        } else {
            initializeChat();
        }

        // Listen for HTMX events
        document.body.addEventListener('htmx:afterSwap', function(evt) {
            if (evt.detail.target.id === 'messages-list') {
                const messagesContainer = document.getElementById('messages-container');
                if (messagesContainer) {
                    setTimeout(() => {
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }, 50);
                }
            }
        });

        // Cleanup when page unloads
        window.addEventListener('beforeunload', cleanup);

        // Also cleanup when HTMX swaps out this content
        document.body.addEventListener('htmx:beforeSwap', function(evt) {
            if (evt.detail.target.id === 'chat-area') {
                cleanup();
            }
        });
    })();
</script>
