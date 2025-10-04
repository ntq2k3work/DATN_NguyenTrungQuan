<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Static pages
        $staticPages = [
            ['url' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => url('/books'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['url' => url('/categories/best-sellers'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => url('/categories/new-releases'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => url('/categories/recommendations'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => url('/categories/top-selling'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['url' => url('/search'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => url('/help/shopping-guide'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => url('/help/return-policy'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => url('/help/payment-methods'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => url('/help/shipping-info'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => url('/help/faq'), 'priority' => '0.6', 'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $page) {
            $sitemap .= $this->generateUrlEntry($page['url'], $page['priority'], $page['changefreq']);
        }

        // Category pages
        $categories = Category::all();
        foreach ($categories as $category) {
            $sitemap .= $this->generateUrlEntry(
                url('/categories/' . $category->slug),
                '0.8',
                'weekly',
                $category->updated_at
            );
        }

        // Book pages
        $books = Book::where('status', 'activate')->get();
        foreach ($books as $book) {
            $sitemap .= $this->generateUrlEntry(
                url('/product/' . $book->slug),
                '0.7',
                'monthly',
                $book->updated_at
            );
        }

        $sitemap .= '</urlset>';

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    private function generateUrlEntry(string $url, string $priority, string $changefreq, $lastmod = null): string
    {
        $entry = '<url>';
        $entry .= '<loc>' . htmlspecialchars($url) . '</loc>';
        $entry .= '<priority>' . $priority . '</priority>';
        $entry .= '<changefreq>' . $changefreq . '</changefreq>';

        if ($lastmod) {
            $entry .= '<lastmod>' . $lastmod->format('Y-m-d') . '</lastmod>';
        }

        $entry .= '</url>';

        return $entry;
    }
}
