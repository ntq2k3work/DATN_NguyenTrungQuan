<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Test endpoint to check if chatbot is working
     */
    public function test(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Chatbot API is working!',
            'timestamp' => now()->toDateTimeString()
        ]);
    }

    /**
     * Get all books data for Gemini AI analysis
     */
    public function getBooksData(): JsonResponse
    {
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
                            $book->price
                    ];
                });

            return response()->json([
                'success' => true,
                'books' => $books,
                'count' => $books->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('getBooksData Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Có lỗi xảy ra khi lấy dữ liệu sách',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get book recommendations using Gemini AI
     */
    public function getRecommendations(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'query' => 'required|string|max:500'
            ]);

            $query = $request->input('query');
            
            // Call Gemini AI with user query only
            $recommendations = $this->callGeminiAI($query);
            
            return response()->json([
                'success' => true,
                'recommendations' => $recommendations['recommendations'] ?? [],
                'summary' => $recommendations['summary'] ?? 'Đây là những cuốn sách phù hợp nhất với yêu cầu của bạn.',
                'ai_powered' => $recommendations['ai_powered'] ?? false,
                'query' => $query
            ]);
        } catch (\Exception $e) {
            Log::error('ChatbotController Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Có lỗi xảy ra khi xử lý yêu cầu',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Call Gemini AI API to get book recommendations
     */
    private function callGeminiAI(string $query): array
    {
        $apiKey = config('services.gemini.api_key');
        
        if (!$apiKey) {
            return $this->getFallbackRecommendations($query);
        }

        $prompt = $this->buildGeminiPrompt($query);
        
        try {
            $response = $this->makeGeminiRequest($apiKey, $prompt);
            return $this->parseGeminiResponse($response);
        } catch (\Exception $e) {
            Log::error('Gemini AI Error: ' . $e->getMessage());
            return $this->getFallbackRecommendations($query);
        }
    }

    /**
     * Build the prompt for Gemini AI
     */
    private function buildGeminiPrompt(string $query): string
    {
        // Get all books data
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
                        $book->price
                ];
            });

        $booksJson = json_encode($books->toArray(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        return "Bạn là một chuyên gia tư vấn sách tại một cửa hàng sách trực tuyến Việt Nam. 

NGHIỆM VỤ: Phân tích yêu cầu của khách hàng và gợi ý những cuốn sách phù hợp nhất từ danh sách sách có sẵn.

YÊU CẦU CỦA KHÁCH HÀNG: \"{$query}\"

DANH SÁCH SÁCH CÓ SẴN:
{$booksJson}

QUY TẮC GỢI Ý:
1. Phân tích kỹ lưỡng yêu cầu của khách hàng về thể loại, tác giả, chủ đề, phong cách viết
2. So sánh với mô tả, tiêu đề và thông tin của từng cuốn sách
4. Nếu không có sách nào phù hợp, trả về mảng recommendations rỗng
5. Ưu tiên sách có giá tốt và còn hàng
6. Đảm bảo đa dạng về tác giả và nhà xuất bản
7. Đặc biệt chú ý đến các tác giả văn học Việt Nam như Chế Lan Viên, Xuân Quỳnh, Tố Hữu, Nguyễn Nhật Ánh, Nam Cao, Vũ Trọng Phụng, Thạch Lam, Nguyễn Tuân, Tô Hoài, Hồ Chí Minh
8. Hiểu rằng \"tập thơ đầu tay\" có thể đề cập đến tác phẩm đầu tiên của một tác giả, ví dụ \"Điêu tàn\" là tập thơ đầu tay của Chế Lan Viên

ĐỊNH DẠNG TRẢ LỜI:
Trả về JSON với cấu trúc sau:
{
  \"recommendations\": [
    {
      \"book_id\": [ID của sách],
      \"reason\": \"Lý do tại sao sách này phù hợp với yêu cầu\",
      \"match_score\": [Điểm từ 1-10]
    }
  ],
  \"summary\": \"Tóm tắt ngắn gọn về các gợi ý và lý do chọn lựa\"
}

CHÚ Ý:
- Chỉ trả về JSON, không có text thêm
- Đảm bảo book_id tồn tại trong danh sách
- Lý do phải cụ thể và thuyết phục
- Nếu khách hàng hỏi về tác giả cụ thể, chỉ trả về sách của tác giả đó
- CHỈ gợi ý những cuốn sách thực sự phù hợp, không cần đủ 3 cuốn nếu không có sách phù hợp";
    }

    /**
     * Make request to Gemini AI API
     */
    private function makeGeminiRequest(string $apiKey, string $prompt): array
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";
        
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'topK' => 40,
                'topP' => 0.95,
                'maxOutputTokens' => 2048,
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \Exception("Gemini API request failed with HTTP code: {$httpCode}");
        }

        return json_decode($response, true);
    }

    /**
     * Parse Gemini AI response
     */
    private function parseGeminiResponse(array $response): array
    {
        if (!isset($response['candidates'][0]['content']['parts'][0]['text'])) {
            throw new \Exception("Invalid Gemini API response format");
        }

        $responseText = $response['candidates'][0]['content']['parts'][0]['text'];
        
        // Try to extract JSON from response
        $jsonMatch = preg_match('/\{.*\}/s', $responseText, $matches);
        
        if (!$jsonMatch) {
            throw new \Exception("No JSON found in Gemini response");
        }

        $parsedResponse = json_decode($matches[0], true);
        
        if (!$parsedResponse || !isset($parsedResponse['recommendations'])) {
            throw new \Exception("Invalid JSON structure in Gemini response");
        }

        // Get books data for validation
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
                        $book->price
                ];
            });

        // Validate and enrich recommendations
        $validRecommendations = [];
        $booksById = $books->keyBy('id');
        
        foreach ($parsedResponse['recommendations'] as $rec) {
            if (isset($rec['book_id']) && $booksById->has($rec['book_id'])) {
                $book = $booksById[$rec['book_id']];
                $validRecommendations[] = [
                    'book' => $book,
                    'reason' => $rec['reason'] ?? 'Phù hợp với yêu cầu của bạn',
                    'match_score' => $rec['match_score'] ?? 8
                ];
            }
        }

        return [
            'recommendations' => $validRecommendations,
            'summary' => $parsedResponse['summary'] ?? 'Đây là những cuốn sách phù hợp nhất với yêu cầu của bạn.',
            'ai_powered' => true
        ];
    }

    /**
     * Fallback recommendations when Gemini AI is not available
     */
    private function getFallbackRecommendations(string $query): array
    {
        // Get books data
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
                        $book->price
                ];
            });

        $booksData = $books->toArray();
        
        // Simple keyword matching fallback
        $keywords = explode(' ', strtolower($query));
        $scoredBooks = [];
        
        // Special handling for specific authors
        $authorMappings = [
            'chế lan viên' => ['chế lan viên', 'điêu tàn'],
            'xuân quỳnh' => ['xuân quỳnh', 'sóng'],
            'tố hữu' => ['tố hữu', 'việt bắc'],
            'nguyễn nhật ánh' => ['nguyễn nhật ánh', 'hoa vàng', 'cô gái'],
            'nam cao' => ['nam cao', 'chí phèo', 'lão hạc'],
            'vũ trọng phụng' => ['vũ trọng phụng', 'số đỏ'],
            'thạch lam' => ['thạch lam', 'hà nội'],
            'nguyễn tuân' => ['nguyễn tuân', 'vang bóng'],
            'tô hoài' => ['tô hoài', 'dế mèn'],
            'hồ chí minh' => ['hồ chí minh', 'nhật ký']
        ];
        
        foreach ($booksData as $book) {
            $score = 0;
            $bookText = strtolower($book['title'] . ' ' . $book['description'] . ' ' . $book['author'] . ' ' . $book['category']);
            
            // Check for author-specific matches first
            foreach ($authorMappings as $author => $terms) {
                $authorFound = false;
                foreach ($terms as $term) {
                    if (strpos($query, $term) !== false) {
                        $authorFound = true;
                        break;
                    }
                }
                
                if ($authorFound && strpos($bookText, $author) !== false) {
                    $score += 10; // High score for author match
                    break;
                }
            }
            
            // Regular keyword matching
            foreach ($keywords as $keyword) {
                if (strlen($keyword) > 2) {
                    $score += substr_count($bookText, $keyword);
                }
            }
            
            if ($score > 0) {
                $scoredBooks[] = [
                    'book' => $book,
                    'score' => $score,
                    'reason' => $score >= 10 ? 'Tác phẩm của tác giả bạn tìm kiếm' : 'Phù hợp với từ khóa bạn tìm kiếm'
                ];
            }
        }
        
        // Sort by score and take top 3
        usort($scoredBooks, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        // Only return books with score > 0 (actually relevant)
        $relevantBooks = array_filter($scoredBooks, function($item) {
            return $item['score'] > 0;
        });
        
        return [
            'recommendations' => array_map(function($item) {
                return [
                    'book' => $item['book'],
                    'reason' => $item['reason'],
                    'match_score' => min(10, $item['score'] * 2)
                ];
            }, $relevantBooks),
            'summary' => count($relevantBooks) > 0 ? 'Đây là những cuốn sách phù hợp nhất với yêu cầu của bạn.' : 'Không tìm thấy sách phù hợp với yêu cầu của bạn.',
            'ai_powered' => false
        ];
    }
}
