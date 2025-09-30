<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ChatbotComponent extends Component
{
    public $isOpen = false;
    public $messages = [];
    public $currentMessage = '';
    public $loading = false;
    public $welcomeMessage = '';

    protected $listeners = ['openChatbot' => 'openChatbot'];

    public function mount()
    {
        $this->addWelcomeMessage();
    }

    public function openChatbot()
    {
        $this->isOpen = true;
    }

    public function closeChatbot()
    {
        $this->isOpen = false;
        $this->currentMessage = '';
    }

    public function sendMessage()
    {
        if (empty(trim($this->currentMessage))) {
            return;
        }

        $message = trim($this->currentMessage);
        $this->addUserMessage($message);
        $this->currentMessage = '';

        // Check if it's a book recommendation request
        if ($this->isBookRecommendationRequest($message)) {
            $this->handleBookRecommendation($message);
        } else {
            $this->handleBotResponse($message);
        }
    }

    public function addUserMessage($message)
    {
        $this->messages[] = [
            'type' => 'user',
            'content' => $message,
            'time' => now()->format('H:i')
        ];
    }

    public function addBotMessage($message)
    {
        $this->messages[] = [
            'type' => 'bot',
            'content' => $message,
            'time' => now()->format('H:i')
        ];
    }

    public function addWelcomeMessage()
    {
        $welcomeMessages = [
            "Xin chào! Tôi có thể giúp gì cho bạn hôm nay?"
        ];

        $this->welcomeMessage = $welcomeMessages[array_rand($welcomeMessages)];
    }

    public function isBookRecommendationRequest($message)
    {
        $bookKeywords = [
            'sách', 'truyện', 'tiểu thuyết', 'tác giả', 'nhà văn', 'thể loại',
            'chủ đề', 'nội dung', 'câu chuyện', 'nhân vật', 'tình huống',
            'gợi ý', 'tìm', 'muốn', 'cần', 'phù hợp', 'hay', 'thú vị',
            'cảm động', 'xúc động', 'hài hước', 'kinh dị', 'tình cảm',
            'lịch sử', 'khoa học', 'kinh doanh', 'kỹ năng', 'phát triển',
            'thơ', 'thơ ca', 'tập thơ', 'nhà thơ', 'bài thơ', 'thơ văn',
            'văn học', 'văn chương', 'tác phẩm', 'sáng tác'
        ];

        $lowerMessage = strtolower($message);
        return collect($bookKeywords)->some(function ($keyword) use ($lowerMessage) {
            return str_contains($lowerMessage, $keyword);
        });
    }

    public function handleBookRecommendation($query)
    {
        $this->loading = true;

        try {
            $books = Book::with(['category', 'author', 'publisher', 'discount'])
            ->whereIn('status', ['active', 'activate'])
            ->get()
            ->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'slug' => $book->slug,
                    'description' => strip_tags($book->description),
                    'price' => $book->price,
                    'quantity' => $book->quantity,
                    'category' => $book->category ? $book->category->name : null,
                    'author' => $book->author ? $book->author->name : null,
                    'publisher' => $book->publisher ? $book->publisher->name : null,
                    'discount_percent' => $book->discount ? $book->discount->discount_percent : null,
                    'final_price' => $book->discount ?
                        $book->price * (1 - $book->discount->discount_percent / 100) :
                        $book->price,
                    'image_url' => asset($book->image_url)
                ];
            });

            // Tìm kiếm sách phù hợp dựa trên từ khóa trong $query
            $keywords = collect(explode(' ', mb_strtolower($query)));
            $recommendations = [];

            foreach ($books as $book) {
                $bookText = mb_strtolower(
                    ($book['title'] ?? '') . ' ' .
                    ($book['description'] ?? '') . ' ' .
                    ($book['author'] ?? '') . ' ' .
                    ($book['category'] ?? '')
                );

                $score = 0;
                foreach ($keywords as $keyword) {
                    if (mb_strlen($keyword) > 2 && mb_strpos($bookText, $keyword) !== false) {
                        $score += 2;
                    }
                }

                // Ưu tiên nếu có từ khóa trong tiêu đề hoặc tác giả
                foreach ($keywords as $keyword) {
                    if (mb_strlen($keyword) > 2 && (mb_strpos(mb_strtolower($book['title'] ?? ''), $keyword) !== false || mb_strpos(mb_strtolower($book['author'] ?? ''), $keyword) !== false)) {
                        $score += 3;
                    }
                }

                if ($score > 0) {
                    $recommendations[] = [
                        'book' => $book,
                        'reason' => 'Sách phù hợp với yêu cầu của bạn',
                        'match_score' => min($score, 10)
                    ];
                }
            }

            // Sắp xếp theo điểm số giảm dần
            usort($recommendations, function ($a, $b) {
                return $b['match_score'] <=> $a['match_score'];
            });

            // Chỉ gợi ý sách có match_score > 7
            $recommendations = array_filter($recommendations, function ($rec) {
                return $rec['match_score'] > 7;
            });

            // Lấy tối đa 5 gợi ý
            // $recommendations = array_slice($recommendations, 0, 5);

            if (count($recommendations) > 0) {
                $summary = 'Đây là những cuốn sách phù hợp nhất với yêu cầu của bạn.';
                $this->addBookRecommendations($recommendations, $summary, false);
            } else {
                $this->addBotMessage("Xin lỗi, tôi không tìm thấy sách phù hợp với yêu cầu của bạn. Bạn có thể thử mô tả cụ thể hơn không?");
            }
        } catch (\Exception $e) {
            $this->addBotMessage("Xin lỗi, có lỗi xảy ra khi tìm kiếm sách: " . $e->getMessage() . ". Vui lòng thử lại sau.");
        } finally {
            $this->loading = false;
        }
    }

    public function addBookRecommendations($recommendations, $summary, $aiPowered)
    {
        // Add summary message
        $this->addBotMessage($summary);

        // Add each recommendation
        foreach ($recommendations as $rec) {
            $book = $rec['book'];
            $reason = $rec['reason'];
            $score = $rec['match_score'];

            $stars = str_repeat('★', floor($score / 2)) . str_repeat('☆', 5 - floor($score / 2));

            $recommendationHtml = "
                <div class='book-recommendation-card'>
                    <div class='book-info'>
                        <h4 class='book-title'>{$book['title']}</h4>
                        <p class='book-author'>Tác giả: " . ($book['author'] ?? 'Chưa xác định') . "</p>
                        <p class='book-category'>Thể loại: " . ($book['category'] ?? 'Chưa xác định') . "</p>
                        <div class='book-price'>
                            <span class='current-price'>" . number_format($book['final_price'], 0, ',', '.') . "đ</span>
                            " . ($book['discount_percent'] ? "<span class='original-price'>" . number_format($book['price'], 0, ',', '.') . "đ</span>" : '') . "
                        </div>
                    </div>
                    <div class='book-actions'>
                        <a href='/product/{$book['slug']}' class='view-book-btn' target='_blank'>
                            <i class='fas fa-eye'></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            ";

            $this->messages[] = [
                'type' => 'bot',
                'content' => $recommendationHtml,
                'time' => now()->format('H:i'),
                'is_recommendation' => true
            ];
        }

        // Add AI powered indicator
        if ($aiPowered) {
            $this->messages[] = [
                'type' => 'bot',
                'content' => "<p><i class='fas fa-robot'></i> Gợi ý được tạo bởi AI Gemini</p>",
                'time' => now()->format('H:i'),
                'is_ai_indicator' => true
            ];
        }
    }

    public function handleBotResponse($userMessage)
    {
        $responses = $this->getBotResponses(strtolower($userMessage));
        $response = $responses[array_rand($responses)];

        $this->loading = true;

        // Simulate typing delay
        $this->dispatch('addTypingIndicator');

        // Add delay for realistic typing
        $this->dispatch('delayedResponse', response: $response);
    }

    public function getBotResponses($message)
    {
        $responses = [
            'hello' => [
                "Xin chào! Tôi có thể giúp gì cho bạn?",
                "Chào bạn! Bạn đang tìm kiếm sách gì?",
                "Hi! Tôi ở đây để hỗ trợ bạn!"
            ],
            'help' => [
                "Tôi có thể giúp bạn tìm sách theo danh mục, tác giả hoặc từ khóa.",
                "Bạn có thể hỏi tôi về sách bestseller, sách mới, hoặc sách theo chủ đề cụ thể.",
                "Hãy cho tôi biết bạn quan tâm đến loại sách nào nhé!"
            ],
            'book' => [
                "Chúng tôi có rất nhiều sách hay! Bạn quan tâm đến chủ đề gì?",
                "Bạn có thể xem các danh mục sách như Văn học, Kinh doanh, Kỹ năng sống...",
                "Tôi có thể gợi ý cho bạn những cuốn sách bestseller hoặc sách mới phát hành."
            ],
            'price' => [
                "Chúng tôi có nhiều mức giá phù hợp với mọi người.",
                "Bạn có thể xem các chương trình khuyến mãi và giảm giá hiện tại.",
                "Nhiều sách đang được giảm giá đặc biệt, bạn có muốn xem không?"
            ],
            'order' => [
                "Bạn có thể đặt hàng trực tuyến và thanh toán qua VNPay.",
                "Chúng tôi hỗ trợ giao hàng toàn quốc với phí ship hợp lý.",
                "Sau khi đặt hàng, bạn sẽ nhận được email xác nhận."
            ],
            'contact' => [
                "Bạn có thể liên hệ với chúng tôi qua email hoặc hotline.",
                "Đội ngũ hỗ trợ khách hàng luôn sẵn sàng giúp đỡ bạn.",
                "Nếu có thắc mắc gì, đừng ngại hỏi tôi nhé!"
            ],
            'default' => [
                "Tôi hiểu bạn đang hỏi về: " . $message . ". Bạn có thể cho tôi biết thêm chi tiết không?",
                "Đó là một câu hỏi thú vị! Tôi có thể giúp bạn tìm hiểu thêm về chủ đề này.",
                "Cảm ơn bạn đã hỏi! Tôi sẽ cố gắng hỗ trợ bạn tốt nhất có thể.",
                "Bạn có muốn tôi gợi ý một số sách liên quan đến chủ đề này không?",
                "Tôi có thể giúp bạn tìm sách phù hợp với sở thích của mình."
            ]
        ];

        // Check for keywords
        foreach ($responses as $keyword => $responseList) {
            if ($keyword !== 'default' && str_contains($message, $keyword)) {
                return $responseList;
            }
        }

        return $responses['default'];
    }

    public function render()
    {
        return view('livewire.chatbot-component');
    }
}
