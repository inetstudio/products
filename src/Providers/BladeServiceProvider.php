<?php

namespace InetStudio\Products\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        Blade::directive('productLink', function ($expression) {
            list($id, $word) = array_map('trim', explode(',',str_replace(['(',')', "'"], '', $expression), 2));

            return view('admin.module.products::back.partials.content.product_link', compact('id', 'word'));
        });
    }

    /**
     * Регистрация привязки в контейнере.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }
}
