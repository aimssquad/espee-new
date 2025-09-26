<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

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
        // Share categories with subcategories globally
        View::composer('layouts.app', function ($view) {
            $categories = Category::with('subcategories')->get();
            $view->with('categories', $categories);
        });
    }
}
