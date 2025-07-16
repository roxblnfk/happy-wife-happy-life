<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 */
?>

<div class="container-fluid h-100">
    <div class="row h-100">
        <!-- Sidebar with chat list -->
        <div class="col-md-4 col-lg-3 border-end p-0">
            <div class="d-flex flex-column h-100">
                <div class="p-2">
                    <a href="/" class="btn btn-outline-default w-100">
                        <i class="bi bi-arrow-left-circle me-2"></i>Назад
                    </a>
                </div>

                <!-- New Chat Button -->
                <div class="p-2 border-bottom">
                    <button class="btn btn-outline-primary w-100"
                            hx-post="/chat/create"
                            hx-target="#chat-area"
                            hx-swap="innerHTML">
                        <i class="bi bi-plus-circle me-2"></i>Новый чат
                    </button>
                </div>

                <!-- Chat List -->
                <div class="flex-grow-1 overflow-auto" id="chat-list"
                     hx-get="/chat/list"
                     hx-trigger="load, every 1s"
                     hx-swap="innerHTML">
                    <!-- Chat items will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="col-md-8 col-lg-9 p-0">
            <div id="chat-area" class="h-100 "><!-- d-flex align-items-center justify-content-center-->
                <div class="text-center text-muted">
                    <i class="bi bi-chat-dots" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h4 class="mt-3">Добро пожаловать в AI Помощника</h4>
                    <p>Выберите чат из списка или создайте новый</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.chat-item {
    cursor: pointer;
    transition: background-color 0.2s;
}

.chat-item:hover {
    background-color: #f8f9fa;
}

.chat-item.active {
    background-color: #e3f2fd;
    border-left: 4px solid #0d6efd;
}

.chat-item h6 {
    font-size: 0.95rem;
    font-weight: 600;
}

.chat-item p {
    font-size: 0.85rem;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

#chat-area {
    background-color: #fafafa;
}

/* Mobile responsive */
@media (max-width: 767.98px) {
    .col-md-4 {
        display: none;
    }

    .show-sidebar .col-md-4 {
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1050;
        background: white;
    }

    .show-sidebar .col-md-8 {
        display: none;
    }
}

/* Scrollbar */
#chat-list::-webkit-scrollbar {
    width: 4px;
}

#chat-list::-webkit-scrollbar-track {
    background: #f1f1f1;
}

#chat-list::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

/* Loading indicator for chat list */
.htmx-request #chat-list {
    position: relative;
}

.htmx-request #chat-list::after {
    content: '';
    position: absolute;
    top: 0;
    right: 8px;
    width: 16px;
    height: 16px;
    border: 2px solid #e9ecef;
    border-radius: 50%;
    border-top-color: #0d6efd;
    animation: spin 1s linear infinite;
    z-index: 10;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle chat item selection
    document.addEventListener('click', function(e) {
        const chatItem = e.target.closest('.chat-item');
        if (chatItem) {
            // Remove active class from all items
            document.querySelectorAll('.chat-item').forEach(item => {
                item.classList.remove('active');
            });

            // Add active class to clicked item
            chatItem.classList.add('active');
        }
    });

    // Mobile sidebar toggle
    function toggleSidebar() {
        document.body.classList.toggle('show-sidebar');
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 767 && document.body.classList.contains('show-sidebar')) {
            if (!e.target.closest('.col-md-4')) {
                document.body.classList.remove('show-sidebar');
            }
        }
    });

    // Preserve active chat selection after list updates
    let activeChat = null;

    // Before chat list update, remember active chat
    document.body.addEventListener('htmx:beforeSwap', function(evt) {
        if (evt.detail.target.id === 'chat-list') {
            const activeItem = document.querySelector('.chat-item.active');
            if (activeItem) {
                // Extract chat UUID from hx-get attribute or data attribute
                const hxGet = activeItem.getAttribute('hx-get');
                if (hxGet) {
                    const match = hxGet.match(/\/chat\/([a-f0-9-]+)/);
                    if (match) {
                        activeChat = match[1];
                    }
                }
            }
        }
    });

    // After chat list update, restore active chat
    document.body.addEventListener('htmx:afterSwap', function(evt) {
        if (evt.detail.target.id === 'chat-list' && activeChat) {
            // Find and mark the previously active chat
            const chatItems = document.querySelectorAll('.chat-item[hx-get]');
            chatItems.forEach(item => {
                const hxGet = item.getAttribute('hx-get');
                if (hxGet && hxGet.includes(activeChat)) {
                    item.classList.add('active');
                }
            });
        }
    });
});
</script>
