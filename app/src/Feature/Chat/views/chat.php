<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var Chat $chat
 * @var array<\App\Module\Agent\AgentCard> $agents
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

            <!-- Agent Prompt Buttons - Show only when no messages -->
            <div id="agent-prompts" class="agent-prompts-container">
                <div class="text-center mb-4">
                    <h6 class="text-muted">Выберите помощника для начала работы</h6>
                </div>

                <div class="row g-3">
                    <?php foreach ($agents as $agent): ?>
                        <div class="col-md-6">
                            <button type="button"
                                    class="agent-prompt-btn card h-100 w-100 border-0 shadow-sm"
                                    hx-post="/chat/init-agent/<?= $chat->uuid->toString() ?>/<?= $agent->alias ?>"
                            >
                                <div class="card-body text-start">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="<?= $agent->icon ?> me-2" style="font-size: 1.5rem;"></i>
                                        <h6 class="card-title mb-0 <?= $agent->color ?>"><?= \htmlspecialchars($agent->name) ?></h6>
                                    </div>
                                    <p class="card-text text-muted small mb-0">
                                        <?= \htmlspecialchars($agent->description) ?>
                                    </p>
                                </div>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

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
        position: relative;
    }

    .message-user {
        margin-left: auto;
    }

    .message-ai {
        margin-right: auto;
    }

    /* Контейнер для содержимого сообщения и кнопки удаления */
    .message-bubble {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .message-content {
        /* render all the line breaks and spaces */
        white-space: pre-wrap; /* Preserve whitespace and line breaks */
        word-break: break-word; /* Break long words to prevent overflow */
        display: block;
        width: 100%;
    }

    .message-user .message-content {
        background-color: #0d6efd;
        color: white;
        border-radius: 18px 18px 4px 18px;
        padding: 12px 16px;
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

    /* Кнопка удаления сообщения в правом верхнем углу */
    .message-delete-btn {
        position: absolute;
        top: 4px;
        right: 4px;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid #dee2e6;
        color: #dc3545;
        font-size: 12px;
        font-weight: bold;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
        padding: 2px 6px;
        border-radius: 50%;
        line-height: 1;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        z-index: 10;
    }

    .message-delete-btn:hover {
        color: #a71e2a;
        background-color: rgba(220, 53, 69, 0.1);
        border-color: #dc3545;
        transform: scale(1.1);
    }

    /* Показываем кнопку удаления при наведении на сообщение */
    .message:hover .message-delete-btn {
        opacity: 1;
        visibility: visible;
    }

    /* Для AI сообщений используем более светлую кнопку */
    .message-ai .message-delete-btn {
        background: rgba(248, 249, 250, 0.95);
        color: #6c757d;
        border-color: #dee2e6;
    }

    .message-ai .message-delete-btn:hover {
        color: #495057;
        background-color: rgba(108, 117, 125, 0.1);
        border-color: #6c757d;
    }

    /* Для пользовательских сообщений */
    .message-user .message-delete-btn {
        background: rgba(255, 255, 255, 0.9);
        color: #dc3545;
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

    /* Agent prompt buttons styling */
    .agent-prompts-container {
        padding: 2rem 1rem;
    }

    .agent-prompt-btn {
        background: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .agent-prompt-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
        border-color: #0d6efd !important;
    }

    .agent-prompt-btn:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .agent-prompt-btn .card-body {
        padding: 1.25rem;
    }

    .agent-prompt-btn .card-title {
        color: #212529;
        font-weight: 600;
    }

    .agent-prompt-btn:hover .card-title {
        color: #0d6efd;
    }

    /* Hide agent prompts when messages are present */
    .has-messages .agent-prompts-container {
        display: none;
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

            // Function to check if chat has messages and toggle agent prompts
            function toggleAgentPrompts() {
                const messages = messagesList.querySelectorAll('.message[data-message-uuid]');
                const agentPromptsContainer = document.getElementById('agent-prompts');

                if (messages.length > 0) {
                    // Hide agent prompts when messages exist
                    messagesList.classList.add('has-messages');
                    if (agentPromptsContainer) {
                        agentPromptsContainer.style.display = 'none';
                    }
                } else {
                    // Show agent prompts when no messages
                    messagesList.classList.remove('has-messages');
                    if (agentPromptsContainer) {
                        agentPromptsContainer.style.display = 'block';
                    }
                }
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

                            // Check and toggle agent prompts visibility
                            toggleAgentPrompts();

                            setTimeout(scrollToBottom, 50);
                        } else {
                            // No messages returned, ensure agent prompts are visible
                            toggleAgentPrompts();
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

            // Handle agent prompt button clicks
            document.addEventListener('htmx:afterRequest', function(evt) {
                if (evt.detail.target.id === 'messages-list') {
                    // Check if this was an agent initialization request
                    if (evt.detail.xhr.responseURL && evt.detail.xhr.responseURL.includes('/init-agent/')) {
                        // Agent was initialized, hide prompts and trigger message polling
                        toggleAgentPrompts();
                        setTimeout(pollNewMessages, 100);
                    }
                }
            });

            // Immediate initial load of messages
            pollNewMessages();

            // Start polling intervals
            messagesPollingInterval = setInterval(pollNewMessages, 300);
            tokensPollingInterval = setInterval(pollPendingMessages, 300);

            // Initial check for agent prompts visibility
            setTimeout(toggleAgentPrompts, 100);

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
