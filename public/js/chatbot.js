// Chatbot functionality
class ChatbotManager {
    constructor() {
        this.isOpen = false;
        this.messages = [];
        this.init();
    }

    init() {
        this.toggleButton = document.getElementById('chatbot-toggle');
        this.modal = document.getElementById('chatbot-modal');
        this.closeButton = document.getElementById('chatbot-close');
        this.sendButton = document.getElementById('chatbot-send');
        this.input = document.getElementById('chatbot-input');
        this.messagesContainer = document.getElementById('chatbot-messages');

        this.bindEvents();
        this.addWelcomeMessage();
        this.testConnection();
    }

    bindEvents() {
        // Toggle modal
        this.toggleButton.addEventListener('click', () => this.handleToggle());
        this.toggleButton.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.handleToggle();
            }
        });

        // Close modal
        this.closeButton.addEventListener('click', () => this.handleClose());

        // Send message
        this.sendButton.addEventListener('click', () => this.handleSendMessage());
        this.input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.handleSendMessage();
            }
        });

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (this.isOpen && !this.modal.contains(e.target) && !this.toggleButton.contains(e.target)) {
                this.handleClose();
            }
        });

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.handleClose();
            }
        });
    }

    handleToggle() {
        if (this.isOpen) {
            this.handleClose();
        } else {
            this.handleOpen();
        }
    }

    handleOpen() {
        this.isOpen = true;
        this.modal.classList.add('active');
        this.toggleButton.setAttribute('aria-expanded', 'true');
        this.input.focus();

        // Add animation to button
        this.toggleButton.style.transform = 'scale(1.1)';
        setTimeout(() => {
            this.toggleButton.style.transform = '';
        }, 200);
    }

    handleClose() {
        this.isOpen = false;
        this.modal.classList.remove('active');
        this.toggleButton.setAttribute('aria-expanded', 'false');
        this.input.value = '';
    }

    addWelcomeMessage() {
        const welcomeMessages = [
            "Xin chào! Tôi có thể giúp gì cho bạn hôm nay?",
            "Chào bạn! Tôi ở đây để hỗ trợ bạn tìm kiếm sách phù hợp.",
            "Hi! Bạn cần tìm sách gì không? Tôi có thể giúp bạn!"
        ];

        const randomMessage = welcomeMessages[Math.floor(Math.random() * welcomeMessages.length)];
        this.addBotMessage(randomMessage);
    }

    async handleSendMessage() {
        const message = this.input.value.trim();
        if (!message) return;

        this.addUserMessage(message);
        this.input.value = '';
        this.sendButton.disabled = true;

        // Check if message is asking for book recommendations
        if (this.isBookRecommendationRequest(message)) {
            await this.handleBookRecommendation(message);
        } else {
            // Regular bot response
            setTimeout(() => {
                this.handleBotResponse(message);
                this.sendButton.disabled = false;
            }, 1000 + Math.random() * 2000);
        }
    }

    isBookRecommendationRequest(message) {
        const bookKeywords = [
            'sách', 'truyện', 'tiểu thuyết', 'tác giả', 'nhà văn', 'thể loại',
            'chủ đề', 'nội dung', 'câu chuyện', 'nhân vật', 'tình huống',
            'gợi ý', 'tìm', 'muốn', 'cần', 'phù hợp', 'hay', 'thú vị',
            'cảm động', 'xúc động', 'hài hước', 'kinh dị', 'tình cảm',
            'lịch sử', 'khoa học', 'kinh doanh', 'kỹ năng', 'phát triển',
            'thơ', 'thơ ca', 'tập thơ', 'nhà thơ', 'bài thơ', 'thơ văn',
            'văn học', 'văn chương', 'tác phẩm', 'sáng tác'
        ];

        const lowerMessage = message.toLowerCase();
        return bookKeywords.some(keyword => lowerMessage.includes(keyword));
    }

    addUserMessage(message) {
        const messageElement = this.createMessageElement(message, 'user');
        this.messagesContainer.appendChild(messageElement);
        this.scrollToBottom();
    }

    addBotMessage(message) {
        const messageElement = this.createMessageElement(message, 'bot');
        this.messagesContainer.appendChild(messageElement);
        this.scrollToBottom();
    }

    createMessageElement(message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chatbot-message chatbot-message-${type}`;

        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';
        contentDiv.innerHTML = `<p>${this.escapeHtml(message)}</p>`;

        const timeDiv = document.createElement('div');
        timeDiv.className = 'message-time';
        timeDiv.textContent = this.getCurrentTime();

        messageDiv.appendChild(contentDiv);
        messageDiv.appendChild(timeDiv);

        return messageDiv;
    }

    async handleBookRecommendation(query) {
        try {
            // Add typing indicator
            this.addTypingIndicator();

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                throw new Error('CSRF token not found');
            }

            console.log('Sending request to:', '/api/chatbot/recommendations');
            console.log('Query:', query);

            // Call Gemini AI API
            const response = await fetch('/api/chatbot/recommendations', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ query: query })
            });

            console.log('Response status:', response.status);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Response data:', data);

            // Remove typing indicator
            this.removeTypingIndicator();

            if (data.success && data.recommendations && data.recommendations.length > 0) {
                this.addBookRecommendations(data.recommendations, data.summary, data.ai_powered);
            } else {
                this.addBotMessage(data.summary || "Xin lỗi, tôi không tìm thấy sách phù hợp với yêu cầu của bạn. Bạn có thể thử mô tả cụ thể hơn không?");
            }
        } catch (error) {
            console.error('Error getting book recommendations:', error);
            this.removeTypingIndicator();
            this.addBotMessage(`Xin lỗi, có lỗi xảy ra khi tìm kiếm sách: ${error.message}. Vui lòng thử lại sau.`);
        } finally {
            this.sendButton.disabled = false;
        }
    }

    addBookRecommendations(recommendations, summary, aiPowered) {
        // Add summary message
        this.addBotMessage(summary);

        // Add each recommendation
        recommendations.forEach((rec, index) => {
            const book = rec.book;
            const reason = rec.reason;
            const score = rec.match_score;

            const messageDiv = document.createElement('div');
            messageDiv.className = 'chatbot-message chatbot-message-bot book-recommendation';

            const contentDiv = document.createElement('div');
            contentDiv.className = 'message-content book-card-content';

            const stars = '★'.repeat(Math.floor(score / 2)) + '☆'.repeat(5 - Math.floor(score / 2));

            contentDiv.innerHTML = `
                <div class="book-recommendation-card">
                    <div class="book-info">
                        <h4 class="book-title">${this.escapeHtml(book.title)}</h4>
                        <p class="book-author">Tác giả: ${this.escapeHtml(book.author || 'Chưa xác định')}</p>
                        <p class="book-category">Thể loại: ${this.escapeHtml(book.category || 'Chưa xác định')}</p>
                        <div class="book-price">
                            <span class="current-price">${this.formatPrice(book.final_price)}đ</span>
                            ${book.discount_percent ? `<span class="original-price">${this.formatPrice(book.price)}đ</span>` : ''}
                        </div>
                        <div class="book-rating">
                            <span class="stars">${stars}</span>
                            <span class="score">${score}/10</span>
                        </div>
                        <p class="recommendation-reason">${this.escapeHtml(reason)}</p>
                    </div>
                    <div class="book-actions">
                        <a href="/product/${book.slug}" class="view-book-btn" target="_blank">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            `;

            const timeDiv = document.createElement('div');
            timeDiv.className = 'message-time';
            timeDiv.textContent = this.getCurrentTime();

            messageDiv.appendChild(contentDiv);
            messageDiv.appendChild(timeDiv);
            this.messagesContainer.appendChild(messageDiv);
        });

        // Add AI powered indicator
        if (aiPowered) {
            const aiIndicator = document.createElement('div');
            aiIndicator.className = 'chatbot-message chatbot-message-bot ai-indicator';
            aiIndicator.innerHTML = `
                <div class="message-content">
                    <p><i class="fas fa-robot"></i> Gợi ý được tạo bởi AI Gemini</p>
                </div>
            `;
            this.messagesContainer.appendChild(aiIndicator);
        }

        this.scrollToBottom();
    }

    handleBotResponse(userMessage) {
        const responses = this.getBotResponses(userMessage.toLowerCase());
        const response = responses[Math.floor(Math.random() * responses.length)];

        // Add typing indicator
        this.addTypingIndicator();

        setTimeout(() => {
            this.removeTypingIndicator();
            this.addBotMessage(response);
        }, 1500);
    }

    getBotResponses(message) {
        const responses = {
            'hello': [
                "Xin chào! Tôi có thể giúp gì cho bạn?",
                "Chào bạn! Bạn đang tìm kiếm sách gì?",
                "Hi! Tôi ở đây để hỗ trợ bạn!"
            ],
            'help': [
                "Tôi có thể giúp bạn tìm sách theo danh mục, tác giả hoặc từ khóa.",
                "Bạn có thể hỏi tôi về sách bestseller, sách mới, hoặc sách theo chủ đề cụ thể.",
                "Hãy cho tôi biết bạn quan tâm đến loại sách nào nhé!"
            ],
            'book': [
                "Chúng tôi có rất nhiều sách hay! Bạn quan tâm đến chủ đề gì?",
                "Bạn có thể xem các danh mục sách như Văn học, Kinh doanh, Kỹ năng sống...",
                "Tôi có thể gợi ý cho bạn những cuốn sách bestseller hoặc sách mới phát hành."
            ],
            'price': [
                "Chúng tôi có nhiều mức giá phù hợp với mọi người.",
                "Bạn có thể xem các chương trình khuyến mãi và giảm giá hiện tại.",
                "Nhiều sách đang được giảm giá đặc biệt, bạn có muốn xem không?"
            ],
            'order': [
                "Bạn có thể đặt hàng trực tuyến và thanh toán qua VNPay.",
                "Chúng tôi hỗ trợ giao hàng toàn quốc với phí ship hợp lý.",
                "Sau khi đặt hàng, bạn sẽ nhận được email xác nhận."
            ],
            'contact': [
                "Bạn có thể liên hệ với chúng tôi qua email hoặc hotline.",
                "Đội ngũ hỗ trợ khách hàng luôn sẵn sàng giúp đỡ bạn.",
                "Nếu có thắc mắc gì, đừng ngại hỏi tôi nhé!"
            ],
            'default': [
                "Tôi hiểu bạn đang hỏi về: " + message + ". Bạn có thể cho tôi biết thêm chi tiết không?",
                "Đó là một câu hỏi thú vị! Tôi có thể giúp bạn tìm hiểu thêm về chủ đề này.",
                "Cảm ơn bạn đã hỏi! Tôi sẽ cố gắng hỗ trợ bạn tốt nhất có thể.",
                "Bạn có muốn tôi gợi ý một số sách liên quan đến chủ đề này không?",
                "Tôi có thể giúp bạn tìm sách phù hợp với sở thích của mình."
            ]
        };

        // Check for keywords
        for (const [keyword, responseList] of Object.entries(responses)) {
            if (keyword !== 'default' && message.includes(keyword)) {
                return responseList;
            }
        }

        return responses.default;
    }

    addTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chatbot-message chatbot-message-bot typing-indicator';
        typingDiv.id = 'typing-indicator';

        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';
        contentDiv.innerHTML = `
            <div class="typing-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        `;

        typingDiv.appendChild(contentDiv);
        this.messagesContainer.appendChild(typingDiv);
        this.scrollToBottom();
    }

    removeTypingIndicator() {
        const typingIndicator = document.getElementById('typing-indicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    scrollToBottom() {
        this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
    }

    getCurrentTime() {
        const now = new Date();
        return now.toLocaleTimeString('vi-VN', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }
}

// Initialize chatbot when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.ChatbotManager = new ChatbotManager();
});

// Add typing animation CSS
const style = document.createElement('style');
style.textContent = `
    .typing-indicator .message-content {
        background: var(--sage-100);
        padding: 12px 16px;
        border-radius: 18px;
        border-bottom-left-radius: 4px;
    }

    .typing-dots {
        display: flex;
        gap: 4px;
        align-items: center;
    }

    .typing-dots span {
        width: 8px;
        height: 8px;
        background: var(--warm-gray-400);
        border-radius: 50%;
        animation: typing 1.4s infinite ease-in-out;
    }

    .typing-dots span:nth-child(1) {
        animation-delay: -0.32s;
    }

    .typing-dots span:nth-child(2) {
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

    /* Book recommendation styles */
    .book-recommendation-card {
        background: white;
        border: 1px solid var(--sage-200);
        border-radius: 12px;
        padding: 16px;
        margin: 8px 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .book-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--warm-gray-900);
        margin: 0 0 8px 0;
        line-height: 1.4;
    }

    .book-author, .book-category {
        font-size: 14px;
        color: var(--warm-gray-600);
        margin: 4px 0;
    }

    .book-price {
        margin: 8px 0;
    }

    .current-price {
        font-size: 16px;
        font-weight: 600;
        color: var(--terracotta-600);
    }

    .original-price {
        font-size: 14px;
        color: var(--warm-gray-400);
        text-decoration: line-through;
        margin-left: 8px;
    }

    .book-rating {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 8px 0;
    }

    .stars {
        color: #fbbf24;
        font-size: 14px;
    }

    .score {
        font-size: 12px;
        color: var(--warm-gray-600);
        background: var(--sage-100);
        padding: 2px 6px;
        border-radius: 4px;
    }

    .recommendation-reason {
        font-size: 14px;
        color: var(--warm-gray-700);
        font-style: italic;
        margin: 8px 0;
        line-height: 1.4;
    }

    .book-actions {
        margin-top: 12px;
    }

    .view-book-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--terracotta-600);
        color: white;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .view-book-btn:hover {
        background: var(--terracotta-700);
        transform: translateY(-1px);
    }

    .ai-indicator {
        opacity: 0.8;
    }

    .ai-indicator .message-content {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 12px;
        padding: 8px 12px;
    }

    .ai-indicator i {
        margin-right: 4px;
    }
`;
document.head.appendChild(style);
