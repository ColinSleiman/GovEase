<div id="gov-chat-widget-container" class="gov-chat-hidden">
    <!-- Floating Action Button -->
    <button id="gov-chat-fab" aria-label="Open Chat">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        <div id="gov-chat-unread-badge" style="display: none;">0</div>
    </button>

    <!-- Chat Panel -->
    <div id="gov-chat-panel">
        <div class="gov-chat-header">
            <h3>Messages</h3>
            <button id="gov-chat-close" aria-label="Close Chat">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="gov-chat-body">
            <!-- Sidebar / Contacts List -->
            <div id="gov-chat-sidebar" class="gov-chat-active-pane">
                <div class="gov-chat-sidebar-header">
                    <input type="text" id="gov-chat-search" placeholder="Search contacts...">
                </div>
                <div id="gov-chat-contacts-list">
                    <!-- Contacts injected via JS -->
                    <div class="gov-chat-loading">Loading contacts...</div>
                </div>
            </div>

            <!-- Active Chat Area -->
            <div id="gov-chat-main">
                <div class="gov-chat-main-header">
                    <button id="gov-chat-back" aria-label="Back to contacts">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </button>
                    <h4 id="gov-chat-active-name">Select a contact</h4>
                </div>
                
                <div id="gov-chat-messages-list">
                    <div class="gov-chat-empty-state">
                        <p>No messages yet.</p>
                    </div>
                </div>

                <div class="gov-chat-input-area">
                    <form id="gov-chat-form">
                        <input type="text" id="gov-chat-input" placeholder="Type a message..." autocomplete="off" disabled>
                        <button type="submit" id="gov-chat-send" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Chat Widget CSS */
    :root {
        --chat-primary: #2563eb;
        --chat-primary-hover: #1d4ed8;
        --chat-bg: #ffffff;
        --chat-bg-secondary: #f3f4f6;
        --chat-text: #1f2937;
        --chat-text-muted: #6b7280;
        --chat-border: #e5e7eb;
        --chat-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        --chat-radius: 16px;
        --chat-transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #gov-chat-widget-container {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* FAB */
    #gov-chat-fab {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--chat-primary), #4f46e5);
        color: white;
        border: none;
        box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.39);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s, box-shadow 0.2s;
        position: absolute;
        bottom: 0;
        right: 0;
    }

    #gov-chat-fab:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }

    #gov-chat-unread-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 11px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        border: 2px solid white;
    }

    /* Panel */
    #gov-chat-panel {
        position: absolute;
        bottom: 70px;
        right: 0;
        width: 400px;
        height: 600px;
        max-height: calc(100vh - 120px);
        background: var(--chat-bg);
        border-radius: var(--chat-radius);
        box-shadow: var(--chat-shadow);
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        opacity: 0;
        transform: translateY(20px) scale(0.95);
        pointer-events: none;
        transition: opacity var(--chat-transition), transform var(--chat-transition);
        transform-origin: bottom right;
    }

    #gov-chat-widget-container.gov-chat-open #gov-chat-panel {
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: all;
    }

    #gov-chat-widget-container.gov-chat-open #gov-chat-fab {
        display: none;
    }

    /* Header */
    .gov-chat-header {
        padding: 16px 20px;
        background: linear-gradient(135deg, var(--chat-primary), #4f46e5);
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .gov-chat-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    #gov-chat-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s;
    }

    #gov-chat-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Body */
    .gov-chat-body {
        display: flex;
        flex: 1;
        overflow: hidden;
        position: relative;
    }

    /* Sidebar */
    #gov-chat-sidebar {
        width: 100%;
        display: flex;
        flex-direction: column;
        background: var(--chat-bg);
        border-right: 1px solid var(--chat-border);
        transition: transform 0.3s ease;
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 2;
    }

    .gov-chat-sidebar-header {
        padding: 12px;
        border-bottom: 1px solid var(--chat-border);
    }

    #gov-chat-search {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--chat-border);
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        background: var(--chat-bg-secondary);
        transition: border-color 0.2s;
        box-sizing: border-box;
    }

    #gov-chat-search:focus {
        border-color: var(--chat-primary);
        background: var(--chat-bg);
    }

    #gov-chat-contacts-list {
        flex: 1;
        overflow-y: auto;
    }

    .gov-chat-contact {
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        border-bottom: 1px solid var(--chat-border);
        transition: background 0.2s;
    }

    .gov-chat-contact:hover {
        background: var(--chat-bg-secondary);
    }

    .gov-chat-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 16px;
    }

    .gov-chat-contact-info h4 {
        margin: 0 0 4px 0;
        font-size: 14px;
        color: var(--chat-text);
        font-weight: 600;
    }

    .gov-chat-contact-info p {
        margin: 0;
        font-size: 12px;
        color: var(--chat-text-muted);
    }

    .gov-chat-contact-badge {
        background: #ef4444;
        color: white;
        border-radius: 10px;
        padding: 2px 6px;
        font-size: 11px;
        font-weight: bold;
        margin-left: auto;
    }

    /* Main Chat Area */
    #gov-chat-main {
        width: 100%;
        display: flex;
        flex-direction: column;
        background: var(--chat-bg-secondary);
        transform: translateX(100%);
        transition: transform 0.3s ease;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 1;
    }

    /* Slide animation classes */
    .gov-chat-body.chat-active #gov-chat-sidebar {
        transform: translateX(-100%);
    }

    .gov-chat-body.chat-active #gov-chat-main {
        transform: translateX(0);
        z-index: 3;
    }

    .gov-chat-main-header {
        padding: 12px 16px;
        background: var(--chat-bg);
        border-bottom: 1px solid var(--chat-border);
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    #gov-chat-back {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--chat-text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4px;
        border-radius: 4px;
    }

    #gov-chat-back:hover {
        background: var(--chat-bg-secondary);
        color: var(--chat-text);
    }

    #gov-chat-active-name {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
        color: var(--chat-text);
    }

    #gov-chat-messages-list {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .gov-chat-message {
        max-width: 80%;
        padding: 10px 14px;
        border-radius: 16px;
        font-size: 14px;
        line-height: 1.4;
        position: relative;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .gov-chat-message-sent {
        align-self: flex-end;
        background: var(--chat-primary);
        color: white;
        border-bottom-right-radius: 4px;
    }

    .gov-chat-message-received {
        align-self: flex-start;
        background: var(--chat-bg);
        color: var(--chat-text);
        border: 1px solid var(--chat-border);
        border-bottom-left-radius: 4px;
    }

    .gov-chat-timestamp {
        font-size: 10px;
        opacity: 0.7;
        margin-top: 4px;
        display: block;
        text-align: right;
    }

    .gov-chat-input-area {
        padding: 16px;
        background: var(--chat-bg);
        border-top: 1px solid var(--chat-border);
    }

    #gov-chat-form {
        display: flex;
        gap: 8px;
    }

    #gov-chat-input {
        flex: 1;
        padding: 10px 16px;
        border: 1px solid var(--chat-border);
        border-radius: 20px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
    }

    #gov-chat-input:focus {
        border-color: var(--chat-primary);
    }

    #gov-chat-send {
        background: var(--chat-primary);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
    }

    #gov-chat-send:hover:not(:disabled) {
        background: var(--chat-primary-hover);
        transform: scale(1.05);
    }

    #gov-chat-send:disabled {
        background: #9ca3af;
        cursor: not-allowed;
    }

    .gov-chat-empty-state, .gov-chat-loading {
        text-align: center;
        color: var(--chat-text-muted);
        font-size: 14px;
        padding: 24px;
        margin: auto;
    }

    /* Scrollbar styling */
    #gov-chat-contacts-list::-webkit-scrollbar,
    #gov-chat-messages-list::-webkit-scrollbar {
        width: 6px;
    }

    #gov-chat-contacts-list::-webkit-scrollbar-track,
    #gov-chat-messages-list::-webkit-scrollbar-track {
        background: transparent;
    }

    #gov-chat-contacts-list::-webkit-scrollbar-thumb,
    #gov-chat-messages-list::-webkit-scrollbar-thumb {
        background: rgba(0,0,0,0.1);
        border-radius: 3px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('gov-chat-widget-container');
        const fab = document.getElementById('gov-chat-fab');
        const closeBtn = document.getElementById('gov-chat-close');
        const body = document.querySelector('.gov-chat-body');
        const backBtn = document.getElementById('gov-chat-back');
        const contactsList = document.getElementById('gov-chat-contacts-list');
        const searchInput = document.getElementById('gov-chat-search');
        const messagesList = document.getElementById('gov-chat-messages-list');
        const chatForm = document.getElementById('gov-chat-form');
        const chatInput = document.getElementById('gov-chat-input');
        const sendBtn = document.getElementById('gov-chat-send');
        const activeName = document.getElementById('gov-chat-active-name');
        const unreadBadge = document.getElementById('gov-chat-unread-badge');

        let contacts = [];
        let activeContactId = null;
        let pollInterval = null;
        let authUserId = {{ Auth::id() ?? 'null' }};

        // Open/Close Widget
        fab.addEventListener('click', () => {
            container.classList.add('gov-chat-open');
            loadContacts();
        });

        closeBtn.addEventListener('click', () => {
            container.classList.remove('gov-chat-open');
            stopPolling();
            fetchUnreadCount();
        });

        // Navigation
        backBtn.addEventListener('click', () => {
            body.classList.remove('chat-active');
            activeContactId = null;
            stopPolling();
        });

        // Search
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            const filtered = contacts.filter(c => 
                `${c.firstName} ${c.lastName}`.toLowerCase().includes(term)
            );
            renderContacts(filtered);
        });

        // API Calls
        async function loadContacts() {
            try {
                const res = await axios.get('/chat/contacts');
                contacts = res.data;
                renderContacts(contacts);
                fetchUnreadCount();
            } catch (err) {
                contactsList.innerHTML = '<div class="gov-chat-empty-state">Failed to load contacts.</div>';
                console.error('Error loading contacts:', err);
            }
        }

        async function loadMessages() {
            if (!activeContactId) return;
            try {
                const res = await axios.get(`/chat/messages/${activeContactId}`);
                renderMessages(res.data);
            } catch (err) {
                console.error('Error loading messages:', err);
            }
        }

        // Render Functions
        function renderContacts(data) {
            if (data.length === 0) {
                contactsList.innerHTML = '<div class="gov-chat-empty-state">No contacts found.</div>';
                return;
            }

            contactsList.innerHTML = data.map(contact => {
                const badge = contact.unread_count > 0 
                    ? `<div class="gov-chat-contact-badge">${contact.unread_count > 9 ? '9+' : contact.unread_count}</div>` 
                    : '';
                    
                return `
                <div class="gov-chat-contact" data-id="${contact.id}" data-name="${contact.firstName} ${contact.lastName}">
                    <div class="gov-chat-avatar">${contact.firstName.charAt(0)}${contact.lastName.charAt(0)}</div>
                    <div class="gov-chat-contact-info">
                        <h4>${contact.firstName} ${contact.lastName}</h4>
                        <p>Click to message</p>
                    </div>
                    ${badge}
                </div>
            `}).join('');

            // Add event listeners to newly rendered contacts
            document.querySelectorAll('.gov-chat-contact').forEach(el => {
                el.addEventListener('click', () => openChat(el.dataset.id, el.dataset.name));
            });
        }

        function renderMessages(messages) {
            if (messages.length === 0) {
                messagesList.innerHTML = '<div class="gov-chat-empty-state"><p>No messages yet. Say hi!</p></div>';
                return;
            }

            const wasAtBottom = messagesList.scrollHeight - messagesList.scrollTop <= messagesList.clientHeight + 10;

            messagesList.innerHTML = messages.map(msg => {
                const isSent = parseInt(msg.sender_id) === parseInt(authUserId);
                const time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                return `
                    <div class="gov-chat-message ${isSent ? 'gov-chat-message-sent' : 'gov-chat-message-received'}">
                        ${msg.message}
                        <span class="gov-chat-timestamp">${time}</span>
                    </div>
                `;
            }).join('');

            if (wasAtBottom) {
                scrollToBottom();
            }
        }

        function openChat(id, name) {
            activeContactId = id;
            activeName.textContent = name;
            body.classList.add('chat-active');
            chatInput.disabled = false;
            sendBtn.disabled = false;
            
            // Re-fetch contacts to clear the badge instantly when opening the chat
            loadContacts();
            
            messagesList.innerHTML = '<div class="gov-chat-loading">Loading messages...</div>';
            loadMessages().then(() => scrollToBottom());
            startPolling();
        }

        function scrollToBottom() {
            messagesList.scrollTop = messagesList.scrollHeight;
        }

        // Polling logic
        function startPolling() {
            stopPolling();
            pollInterval = setInterval(() => {
                if(container.classList.contains('gov-chat-open') && activeContactId) {
                    loadMessages();
                } else if (!container.classList.contains('gov-chat-open')) {
                    fetchUnreadCount();
                }
            }, 3000); // Poll every 3 seconds
        }

        function stopPolling() {
            if (pollInterval) {
                clearInterval(pollInterval);
                pollInterval = null;
            }
        }

        async function fetchUnreadCount() {
            if (container.classList.contains('gov-chat-open') && activeContactId) return;
            try {
                const res = await axios.get('/chat/unread');
                const count = res.data.unread_count;
                if (count > 0) {
                    unreadBadge.textContent = count > 9 ? '9+' : count;
                    unreadBadge.style.display = 'flex';
                } else {
                    unreadBadge.style.display = 'none';
                }
            } catch (err) {
                console.error('Error fetching unread count', err);
            }
        }

        // Start global polling for unread messages if chat is closed
        setInterval(() => {
            if (!container.classList.contains('gov-chat-open')) {
                fetchUnreadCount();
            }
        }, 10000); // Every 10 seconds background check
        fetchUnreadCount(); // Initial check

        // Send Message
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const msg = chatInput.value.trim();
            if (!msg || !activeContactId) return;

            chatInput.value = '';
            chatInput.disabled = true;
            sendBtn.disabled = true;

            // Optimistic UI update
            const tempHtml = `
                <div class="gov-chat-message gov-chat-message-sent" style="opacity: 0.7">
                    ${msg}
                    <span class="gov-chat-timestamp">Sending...</span>
                </div>
            `;
            if(messagesList.querySelector('.gov-chat-empty-state')) {
                messagesList.innerHTML = tempHtml;
            } else {
                messagesList.insertAdjacentHTML('beforeend', tempHtml);
            }
            scrollToBottom();

            try {
                await axios.post(`/chat/messages/${activeContactId}`, { message: msg });
                loadMessages(); // Reload accurate state
            } catch (err) {
                console.error('Error sending message:', err);
                alert('Failed to send message. Please try again.');
                loadMessages(); // Revert optimistic update
            } finally {
                chatInput.disabled = false;
                sendBtn.disabled = false;
                chatInput.focus();
            }
        });
    });
</script>
