<?php

namespace InetStudio\ProductsPackage\Products\Providers;

use Collective\Html\FormBuilder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerFormComponents();
        $this->registerBladeDirectives();
    }

    protected function registerConsoleCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands(
            [
                'InetStudio\ProductsPackage\Products\Console\Commands\CreateFoldersCommand',
                'InetStudio\ProductsPackage\Products\Console\Commands\SetupCommand',
                'InetStudio\ProductsPackage\Products\Contracts\Console\Commands\ProcessFeedsCommandContract',
            ]
        );
    }

    protected function registerPublishes(): void
    {
        $this->publishes(
            [
                __DIR__.'/../../config/products_package_products.php' => config_path('products_package_products.php'),
            ], 'config'
        );

        $this->mergeConfigFrom(
            __DIR__.'/../../config/filesystems.php', 'filesystems.disks'
        );

        if (! $this->app->runningInConsole()) {
            return;
        }

        if (Schema::hasTable('products')) {
            return;
        }

        $timestamp = date('Y_m_d_His', time());
        $this->publishes(
            [
                __DIR__.'/../../database/migrations/create_products_package_products_tables.php.stub' => database_path(
                    'migrations/'.$timestamp.'_create_products_package_products_tables.php'
                ),
            ],
            'migrations'
        );
    }

    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin.module.products-package.products');
    }

    protected function registerFormComponents()
    {
        FormBuilder::component(
            'products',
            'admin.module.products-package.products::back.forms.blocks.products',
            ['name' => null, 'value' => null, 'attributes' => null]
        );
    }

    protected function registerBladeDirectives()
    {
        Blade::directive('productLink', function ($expression) {
            $params = explode(',', $expression, 2);
            $params = array_map('trim', $params, array_fill(0, count($params),"' \t\n\r\0\x0B"));

            return view('admin.module.products-package.products::front.partials.content.productLink_directive', [
                'id' => $params[0],
                'word' => $params[1],
            ]);
        });

        Blade::directive('productButton', function ($expression) {
            $params = explode(',', $expression, 2);
            $params = array_map('trim', $params, array_fill(0, count($params),"' \t\n\r\0\x0B"));

            return view('admin.module.products-package.products::front.partials.content.productButton_directive', [
                'id' => $params[0],
            ]);
        });
    }
}
