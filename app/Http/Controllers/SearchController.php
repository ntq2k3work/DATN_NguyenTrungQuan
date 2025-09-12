<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    /**
     * Tìm kiếm sản phẩm theo nhiều tiêu chí
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập từ khóa tìm kiếm',
                'data' => []
            ]);
        }

        try {
            // Tìm kiếm sách theo tên
            $books = Book::with(['category', 'author', 'publisher', 'discount'])
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->orWhereHas('category', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhereHas('author', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhereHas('publisher', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->where('quantity', '>', 0) // Chỉ hiển thị sách còn hàng
                ->limit(10)
                ->get();

            // Format dữ liệu trả về
            $results = $books->map(function ($book) {
                $discountPrice = $book->discount ? 
                    $book->price * (1 - $book->discount->percent / 100) : 
                    $book->price;

                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'slug' => $book->slug,
                    'price' => number_format($book->price, 0, ',', '.'),
                    'discount_price' => number_format($discountPrice, 0, ',', '.'),
                    'has_discount' => $book->discount ? true : false,
                    'discount_percent' => $book->discount ? $book->discount->percent : 0,
                    'image_url' => $book->image_url,
                    'category' => $book->category ? $book->category->name : 'N/A',
                    'author' => $book->author ? $book->author->name : 'N/A',
                    'publisher' => $book->publisher ? $book->publisher->name : 'N/A',
                    'url' => route('product.show', $book->slug)
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Tìm kiếm thành công',
                'data' => $results,
                'total' => $results->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tìm kiếm',
                'data' => []
            ], 500);
        }
    }

    /**
     * Tìm kiếm gợi ý (suggestions) khi người dùng đang gõ
     */
    public function suggestions(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        try {
            $suggestions = collect();

            // Gợi ý từ tên sách
            $bookTitles = Book::where('title', 'LIKE', "%{$query}%")
                ->where('quantity', '>', 0)
                ->limit(5)
                ->pluck('title')
                ->map(function ($title) {
                    return ['text' => $title, 'type' => 'book'];
                });

            // Gợi ý từ tên tác giả
            $authorNames = Author::where('name', 'LIKE', "%{$query}%")
                ->limit(3)
                ->pluck('name')
                ->map(function ($name) {
                    return ['text' => $name, 'type' => 'author'];
                });

            // Gợi ý từ tên nhà xuất bản
            $publisherNames = Publisher::where('name', 'LIKE', "%{$query}%")
                ->limit(3)
                ->pluck('name')
                ->map(function ($name) {
                    return ['text' => $name, 'type' => 'publisher'];
                });

            // Gợi ý từ tên danh mục
            $categoryNames = Category::where('name', 'LIKE', "%{$query}%")
                ->limit(3)
                ->pluck('name')
                ->map(function ($name) {
                    return ['text' => $name, 'type' => 'category'];
                });

            $suggestions = $suggestions
                ->merge($bookTitles)
                ->merge($authorNames)
                ->merge($publisherNames)
                ->merge($categoryNames)
                ->unique('text')
                ->take(8);

            return response()->json([
                'success' => true,
                'data' => $suggestions->values()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => []
            ], 500);
        }
    }

    /**
     * Hiển thị trang kết quả tìm kiếm đầy đủ
     */
    public function results(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return view('pages.search-results', [
                'query' => '',
                'books' => collect(),
                'totalResults' => 0,
                'categories' => Category::all(),
                'authors' => Author::all(),
                'publishers' => Publisher::all()
            ]);
        }

        try {
            $booksQuery = Book::with(['category', 'author', 'publisher', 'discount'])
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->orWhereHas('category', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhereHas('author', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhereHas('publisher', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->where('quantity', '>', 0);

            // Apply filters
            if ($request->has('category') && $request->category) {
                $booksQuery->where('category_id', $request->category);
            }

            if ($request->has('author') && $request->author) {
                $booksQuery->where('author_id', $request->author);
            }

            if ($request->has('publisher') && $request->publisher) {
                $booksQuery->where('publisher_id', $request->publisher);
            }

            if ($request->has('price') && $request->price) {
                $priceRange = explode('-', $request->price);
                if (count($priceRange) == 2) {
                    if ($priceRange[0] && $priceRange[1]) {
                        $booksQuery->whereBetween('price', [$priceRange[0], $priceRange[1]]);
                    } elseif ($priceRange[0]) {
                        $booksQuery->where('price', '>=', $priceRange[0]);
                    }
                }
            }

            // Apply sorting
            $sort = $request->get('sort', 'relevance');
            switch ($sort) {
                case 'price_asc':
                    $booksQuery->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $booksQuery->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $booksQuery->orderBy('title', 'asc');
                    break;
                case 'name_desc':
                    $booksQuery->orderBy('title', 'desc');
                    break;
                default:
                    // Relevance sorting - prioritize exact matches
                    $booksQuery->orderByRaw("
                        CASE 
                            WHEN title LIKE ? THEN 1
                            WHEN title LIKE ? THEN 2
                            WHEN description LIKE ? THEN 3
                            ELSE 4
                        END
                    ", ["{$query}", "%{$query}%", "%{$query}%"]);
                    break;
            }

            $books = $booksQuery->paginate(12);
            $totalResults = $booksQuery->count();

            return view('pages.search-results', [
                'query' => $query,
                'books' => $books,
                'totalResults' => $totalResults,
                'categories' => Category::all(),
                'authors' => Author::all(),
                'publishers' => Publisher::all()
            ]);

        } catch (\Exception $e) {
            return view('pages.search-results', [
                'query' => $query,
                'books' => collect(),
                'totalResults' => 0,
                'categories' => Category::all(),
                'authors' => Author::all(),
                'publishers' => Publisher::all(),
                'error' => 'Có lỗi xảy ra khi tìm kiếm'
            ]);
        }
    }
}
