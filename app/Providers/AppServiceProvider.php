<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Sử dụng Bootstrap pagination
        Paginator::useBootstrap();

        $categories = Category::with('parent')->where('parent_id',null)->orderByDesc('parent_id')->get();
        $publishers = Publisher::all();

        View::share('categories', $categories);
        View::share('publishers', $publishers);
    }
}
