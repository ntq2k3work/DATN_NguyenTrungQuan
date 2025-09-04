<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function bestSellers()
    {
        $books = Book::with(['author', 'category', 'publisher'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Get publishers for filter
        $publishers = Publisher::orderBy('name')->get();

        // Get all categories for sidebar
        $categories = Category::orderBy('name')->get();

        return view('pages.categories.best_sellers', compact('books', 'publishers', 'categories'));
    }

    public function newReleases()
    {
        $books = Book::with(['author', 'category', 'publisher'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Get publishers for filter
        $publishers = Publisher::orderBy('name')->get();

        // Get all categories for sidebar
        $categories = Category::orderBy('name')->get();

        return view('pages.categories.new_releases', compact('books', 'publishers', 'categories'));
    }

    public function recommendations()
    {
        $books = Book::with(['author', 'category', 'publisher'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Get publishers for filter
        $publishers = Publisher::orderBy('name')->get();

        // Get all categories for sidebar
        $categories = Category::orderBy('name')->get();

        return view('pages.categories.recommendations', compact('books', 'publishers', 'categories'));
    }

    public function topSelling()
    {
        $books = Book::with(['author', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('pages.categories.top_selling', compact('books'));
    }

    public function showBySlug($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $books = Book::with(['author', 'category', 'publisher'])
            ->where('category_id', $category->id)
            ->paginate(12);

        // Get publishers for filter
        $publishers = Publisher::orderBy('name')->get();

        // Get all categories for sidebar
        $categories = Category::orderBy('name')->get();

        return view('pages.categories.show', compact('category', 'books', 'publishers', 'categories'));
    }

        public function filterBooks(Request $request, $slug)
    {
        try {
            $category = Category::where('slug', $slug)->firstOrFail();

            $query = Book::with(['author', 'category', 'publisher'])
                ->where('category_id', $category->id);

            // Filter by publishers
            if ($request->has('publishers') && !empty($request->publishers)) {
                $query->whereIn('publisher_id', $request->publishers);
            }

                    // Filter by price range
        if ($request->has('price_ranges') && !empty($request->price_ranges)) {
            $query->where(function($q) use ($request) {
                foreach ($request->price_ranges as $range) {
                    $prices = explode('-', $range);
                    if (count($prices) === 2) {
                        $minPrice = (int) $prices[0];
                        $maxPrice = (int) $prices[1];

                        if ($maxPrice === 999999999) {
                            $q->orWhere('price', '>=', $minPrice);
                        } else {
                            $q->orWhereBetween('price', [$minPrice, $maxPrice]);
                        }
                    }
                }
            });
        }

        // Filter by custom price range
        if ($request->has('custom_price_min') && $request->has('custom_price_max')) {
            $minPrice = (int) $request->custom_price_min;
            $maxPrice = (int) $request->custom_price_max;

            if ($minPrice > 0 || $maxPrice > 0) {
                if ($minPrice > 0 && $maxPrice > 0) {
                    $query->whereBetween('price', [$minPrice, $maxPrice]);
                } elseif ($minPrice > 0) {
                    $query->where('price', '>=', $minPrice);
                } elseif ($maxPrice > 0) {
                    $query->where('price', '<=', $maxPrice);
                }
            }
        }

        // Apply sorting
        if ($request->has('sort') && !empty($request->sort)) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('title', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('title', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

            $books = $query->paginate(12);

            if ($request->ajax() || $request->wantsJson()) {
                $html = view('partials.books-grid', compact('books', 'category'))->render();
                return response()->json([
                    'html' => $html,
                    'total' => $books->total(),
                    'current_page' => $books->currentPage(),
                    'last_page' => $books->lastPage()
                ]);
            }

            return redirect()->route('categories.show', $slug);
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ], 500);
            }
            throw $e;
        }
    }

    public function filterBestSellers(Request $request)
    {
        try {
            $query = Book::with(['author', 'category', 'publisher']);

            // Filter by publishers
            if ($request->has('publishers') && !empty($request->publishers)) {
                $query->whereIn('publisher_id', $request->publishers);
            }

            // Filter by price range
            if ($request->has('price_ranges') && !empty($request->price_ranges)) {
                $query->where(function($q) use ($request) {
                    foreach ($request->price_ranges as $range) {
                        $prices = explode('-', $range);
                        if (count($prices) === 2) {
                            $minPrice = (int) $prices[0];
                            $maxPrice = (int) $prices[1];

                            if ($maxPrice === 999999999) {
                                $q->orWhere('price', '>=', $minPrice);
                            } else {
                                $q->orWhereBetween('price', [$minPrice, $maxPrice]);
                            }
                        }
                    }
                });
            }

            // Filter by custom price range
            if ($request->has('custom_price_min') && $request->has('custom_price_max')) {
                $minPrice = (int) $request->custom_price_min;
                $maxPrice = (int) $request->custom_price_max;

                if ($minPrice > 0 || $maxPrice > 0) {
                    if ($minPrice > 0 && $maxPrice > 0) {
                        $query->whereBetween('price', [$minPrice, $maxPrice]);
                    } elseif ($minPrice > 0) {
                        $query->where('price', '>=', $minPrice);
                    } elseif ($maxPrice > 0) {
                        $query->where('price', '<=', $maxPrice);
                    }
                }
            }

            // Apply sorting
            if ($request->has('sort') && !empty($request->sort)) {
                switch ($request->sort) {
                    case 'price_asc':
                        $query->orderBy('price', 'asc');
                        break;
                    case 'price_desc':
                        $query->orderBy('price', 'desc');
                        break;
                    case 'name_asc':
                        $query->orderBy('title', 'asc');
                        break;
                    case 'name_desc':
                        $query->orderBy('title', 'desc');
                        break;
                    case 'newest':
                        $query->orderBy('created_at', 'desc');
                        break;
                    case 'oldest':
                        $query->orderBy('created_at', 'asc');
                        break;
                    default:
                        $query->orderBy('created_at', 'desc');
                        break;
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $books = $query->paginate(12);

            if ($request->ajax() || $request->wantsJson()) {
                $html = view('partials.books-grid-best-sellers', compact('books'))->render();
                return response()->json([
                    'html' => $html,
                    'total' => $books->total(),
                    'current_page' => $books->currentPage(),
                    'last_page' => $books->lastPage()
                ]);
            }

            return redirect()->route('categories.best-sellers');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ], 500);
            }
            throw $e;
        }
    }

    public function filterNewReleases(Request $request)
    {
        try {
            $query = Book::with(['author', 'category', 'publisher']);

            // Filter by publishers
            if ($request->has('publishers') && !empty($request->publishers)) {
                $query->whereIn('publisher_id', $request->publishers);
            }

            // Filter by price range
            if ($request->has('price_ranges') && !empty($request->price_ranges)) {
                $query->where(function($q) use ($request) {
                    foreach ($request->price_ranges as $range) {
                        $prices = explode('-', $range);
                        if (count($prices) === 2) {
                            $minPrice = (int) $prices[0];
                            $maxPrice = (int) $prices[1];

                            if ($maxPrice === 999999999) {
                                $q->orWhere('price', '>=', $minPrice);
                            } else {
                                $q->orWhereBetween('price', [$minPrice, $maxPrice]);
                            }
                        }
                    }
                });
            }

            // Filter by custom price range
            if ($request->has('custom_price_min') && $request->has('custom_price_max')) {
                $minPrice = (int) $request->custom_price_min;
                $maxPrice = (int) $request->custom_price_max;

                if ($minPrice > 0 || $maxPrice > 0) {
                    if ($minPrice > 0 && $maxPrice > 0) {
                        $query->whereBetween('price', [$minPrice, $maxPrice]);
                    } elseif ($minPrice > 0) {
                        $query->where('price', '>=', $minPrice);
                    } elseif ($maxPrice > 0) {
                        $query->where('price', '<=', $maxPrice);
                    }
                }
            }

            // Apply sorting
            if ($request->has('sort') && !empty($request->sort)) {
                switch ($request->sort) {
                    case 'price_asc':
                        $query->orderBy('price', 'asc');
                        break;
                    case 'price_desc':
                        $query->orderBy('price', 'desc');
                        break;
                    case 'name_asc':
                        $query->orderBy('title', 'asc');
                        break;
                    case 'name_desc':
                        $query->orderBy('title', 'desc');
                        break;
                    case 'newest':
                        $query->orderBy('created_at', 'desc');
                        break;
                    case 'oldest':
                        $query->orderBy('created_at', 'asc');
                        break;
                    default:
                        $query->orderBy('created_at', 'desc');
                        break;
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $books = $query->paginate(12);

            if ($request->ajax() || $request->wantsJson()) {
                $html = view('partials.books-grid-new-releases', compact('books'))->render();
                return response()->json([
                    'html' => $html,
                    'total' => $books->total(),
                    'current_page' => $books->currentPage(),
                    'last_page' => $books->lastPage()
                ]);
            }

            return redirect()->route('categories.new-releases');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ], 500);
            }
            throw $e;
        }
    }

    public function filterRecommendations(Request $request)
    {
        try {
            $query = Book::with(['author', 'category', 'publisher']);

            // Filter by publishers
            if ($request->has('publishers') && !empty($request->publishers)) {
                $query->whereIn('publisher_id', $request->publishers);
            }

            // Filter by price range
            if ($request->has('price_ranges') && !empty($request->price_ranges)) {
                $query->where(function($q) use ($request) {
                    foreach ($request->price_ranges as $range) {
                        $prices = explode('-', $range);
                        if (count($prices) === 2) {
                            $minPrice = (int) $prices[0];
                            $maxPrice = (int) $prices[1];

                            if ($maxPrice === 999999999) {
                                $q->orWhere('price', '>=', $minPrice);
                            } else {
                                $q->orWhereBetween('price', [$minPrice, $maxPrice]);
                            }
                        }
                    }
                });
            }

            // Filter by custom price range
            if ($request->has('custom_price_min') && $request->has('custom_price_max')) {
                $minPrice = (int) $request->custom_price_min;
                $maxPrice = (int) $request->custom_price_max;

                if ($minPrice > 0 || $maxPrice > 0) {
                    if ($minPrice > 0 && $maxPrice > 0) {
                        $query->whereBetween('price', [$minPrice, $maxPrice]);
                    } elseif ($minPrice > 0) {
                        $query->where('price', '>=', $minPrice);
                    } elseif ($maxPrice > 0) {
                        $query->where('price', '<=', $maxPrice);
                    }
                }
            }

            // Apply sorting
            if ($request->has('sort') && !empty($request->sort)) {
                switch ($request->sort) {
                    case 'price_asc':
                        $query->orderBy('price', 'asc');
                        break;
                    case 'price_desc':
                        $query->orderBy('price', 'desc');
                        break;
                    case 'name_asc':
                        $query->orderBy('title', 'asc');
                        break;
                    case 'name_desc':
                        $query->orderBy('title', 'desc');
                        break;
                    case 'newest':
                        $query->orderBy('created_at', 'desc');
                        break;
                    case 'oldest':
                        $query->orderBy('created_at', 'asc');
                        break;
                    default:
                        $query->orderBy('created_at', 'desc');
                        break;
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $books = $query->paginate(12);

            if ($request->ajax() || $request->wantsJson()) {
                $html = view('partials.books-grid-recommendations', compact('books'))->render();
                return response()->json([
                    'html' => $html,
                    'total' => $books->total(),
                    'current_page' => $books->currentPage(),
                    'last_page' => $books->lastPage()
                ]);
            }

            return redirect()->route('categories.recommendations');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ], 500);
            }
            throw $e;
        }
    }
}
