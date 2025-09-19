<div>
    <!-- Chatbot Button -->
    <div class="chatbot-container">
        <button
            wire:click="openChatbot"
            class="chatbot-button {{ $isOpen ? 'active' : '' }}"
            aria-label="Mở chatbot hỗ trợ"
            tabindex="0"
        >
            <i class="fas fa-comments"></i>
        </button>

        <!-- Chatbot Modal -->
        @if($isOpen)
            <div class="chatbot-modal active">
                <div class="chatbot-modal-content">
                    <div class="chatbot-header">
                        <div class="chatbot-header-info">
                            <div class="chatbot-avatar">
                                <i class="fas fa-robot"></i>
                            </div>
                            <div class="chatbot-title">
                                <h3>Hỗ trợ khách hàng</h3>
                                <p class="chatbot-status">Đang hoạt động</p>
                            </div>
                        </div>
                        <button
                            wire:click="closeChatbot"
                            class="chatbot-close"
                            aria-label="Đóng chatbot"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="chatbot-body">
                        <div class="chatbot-messages" id="chatbot-messages">
                            <!-- Welcome Message -->
                            <div class="chatbot-message chatbot-message-bot">
                                <div class="message-content">
                                    <p>{{ $welcomeMessage }}</p>
                                </div>
                                <div class="message-time">{{ now()->format('H:i') }}</div>
                            </div>

                            <!-- Chat Messages -->
                            @foreach($messages as $message)
                                <div class="chatbot-message chatbot-message-{{ $message['type'] }}">
                                    <div class="message-content {{ isset($message['is_recommendation']) ? 'book-card-content' : '' }} {{ isset($message['is_ai_indicator']) ? 'ai-indicator' : '' }}">
                                        @if(isset($message['is_recommendation']))
                                            {!! $message['content'] !!}
                                        @elseif(isset($message['is_ai_indicator']))
                                            {!! $message['content'] !!}
                                        @else
                                            <p>{{ $message['content'] }}</p>
                                        @endif
                                    </div>
                                    <div class="message-time">{{ $message['time'] }}</div>
                                </div>
                            @endforeach

                            <!-- Typing Indicator -->
                            @if($loading)
                                <div class="chatbot-message chatbot-message-bot typing-indicator">
                                    <div class="message-content">
                                        <div class="typing-dots">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="chatbot-input-container">
                            <div class="chatbot-input-wrapper">
                                <input
                                    type="text"
                                    wire:model="currentMessage"
                                    wire:keydown.enter="sendMessage"
                                    placeholder="Nhập tin nhắn của bạn..."
                                    class="chatbot-input"
                                >
                                <button
                                    wire:click="sendMessage"
                                    class="chatbot-send"
                                    aria-label="Gửi tin nhắn"
                                    {{ $loading ? 'disabled' : '' }}
                                >
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('delayedResponse', (event) => {
                setTimeout(() => {
                    Livewire.dispatch('removeTypingIndicator');
                    Livewire.dispatch('addBotMessage', { message: event.response });
                }, 1500);
            });

            Livewire.on('addTypingIndicator', () => {
                // Typing indicator is handled by Livewire loading state
            });

            Livewire.on('removeTypingIndicator', () => {
                // Typing indicator removal is handled by Livewire loading state
            });

            Livewire.on('addBotMessage', (event) => {
                // Bot message is handled by Livewire component
            });
        });

        // Auto scroll to bottom when new messages are added
        document.addEventListener('livewire:updated', () => {
            const messagesContainer = document.getElementById('chatbot-messages');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        });
    </script>
</div>
