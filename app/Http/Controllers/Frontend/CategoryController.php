<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function bestSellers()
    {
        $books = Book::with(['author', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('pages.categories.best_sellers', compact('books'));
    }

    public function newReleases()
    {
        $books = Book::with(['author', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('pages.categories.new_releases', compact('books'));
    }

    public function recommendations()
    {
        $books = Book::with(['author', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('pages.categories.recommendations', compact('books'));
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
        
        $books = Book::with(['author', 'category'])
            ->where('category_id', $category->id)
            ->paginate(12);

        return view('pages.categories.show', compact('category', 'books'));
    }
}
