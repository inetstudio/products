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
            $params = explode(',', $expression, 2);
            $params = array_map('trim', $params, array_fill(0, count($params),"' \t\n\r\0\x0B"));

            return view('admin.module.products::front.partials.content.productLink_directive', [
                'id' => $params[0],
                'word' => $params[1],
            ]);
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
