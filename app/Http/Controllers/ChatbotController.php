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
        
        return "You are a professional book consultant at an online Vietnamese bookstore.

        TASK: Analyze the customer’s request and suggest the most suitable books from the available list, strictly following the user's requirements.

        CUSTOMER REQUEST: \"{$query}\"

        AVAILABLE BOOK LIST:
        {$booksJson}

        RECOMMENDATION RULES:
        1. If the customer's request is a general book suggestion (for example, the request is 'gợi ý sách', 'gợi ý vài cuốn sách', 'suggest some books', or similar), select and recommend 5 random books from the available list. For each book, provide a brief, friendly reason for the suggestion (e.g., 'Đây là một cuốn sách nổi bật, phù hợp để bạn khám phá.').
        2. If the customer requests books by a specific author (for example, 'sách của tác giả ...'), you must check the 'author' field of each book. Only recommend books where the 'author' field exactly matches the requested author name.
        3. If no books match the requested author, return an empty recommendations array.
        4. If the customer request contains a phrase like 'sách nào mà có mô tả ...' (for example: 'sách nào mà có mô tả về tình bạn'), extract the content after 'mô tả' and search for books whose 'description' field contains or is similar to that content (it does not need to be an exact match, partial matches are acceptable). Only recommend books whose 'description' matches the extracted content.
        5. Otherwise, extract keywords from the customer's request and compare them with the keywords found in each book's title, description, category, and author fields.
        6. Rank and recommend the books that have the highest number of matching keywords with the customer's request. The more keywords matched, the higher the book should be ranked.
        7. For each recommended book, provide a specific and convincing reason, mentioning which keywords matched between the request and the book.
        8. Prioritize books with good prices and availability (in stock).
        9. Ensure diversity in authors and publishers when possible.
        10. Pay special attention to Vietnamese literary authors such as Chế Lan Viên, Xuân Quỳnh, Tố Hữu, Nguyễn Nhật Ánh, Nam Cao, Vũ Trọng Phụng, Thạch Lam, Nguyễn Tuân, Tô Hoài, and Hồ Chí Minh.
        11. Understand that terms like “first poetry collection” may refer to a debut work, e.g., “Điêu tàn” is the debut poetry collection of Chế Lan Viên.

        RESPONSE FORMAT:
        Return JSON with the following structure:
        {
          \"recommendations\": [
            {
              \"book_id\": [Book ID],
              \"reason\": \"Why this book matches the request\"
            }
          ],
          \"summary\": \"A short summary explaining the recommendations and selection reasons\"
        }

        NOTES:
        - Return JSON only, no extra text.
        - Ensure that book_id exists in the provided list.
        - Reasons must be specific and convincing, and should mention the matched keywords if possible.
        - If the customer asks for a specific author (for example, 'sách của tác giả ...'), only return books where the 'author' field exactly matches the requested author.
        - If the customer asks for books with a description containing specific content (for example, 'sách nào mà có mô tả ...'), only return books whose 'description' field contains or is similar to that content.
        - Suggest only truly relevant books, not necessarily 3 if fewer are suitable.
        - For other requests, always prioritize books with the most keyword matches between the request and the book's information.";
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
