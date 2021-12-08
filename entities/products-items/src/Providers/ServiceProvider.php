<?php

namespace InetStudio\ProductsPackage\ProductsItems\Providers;

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
    }

    protected function registerConsoleCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands(
            [
                'InetStudio\ProductsPackage\ProductsItems\Console\Commands\SetupCommand',
            ]
        );
    }

    protected function registerPublishes(): void
    {
        $this->publishes(
            [
                __DIR__.'/../../config/config.php' => config_path('products_package_products_items.php'),
            ], 'config'
        );

        if (! $this->app->runningInConsole()) {
            return;
        }

        if (Schema::hasTable('products_items')) {
            return;
        }

        $timestamp = date('Y_m_d_His', time());
        $this->publishes(
            [
                __DIR__.'/../../database/migrations/create_products_package_products_items_tables.php.stub' => database_path(
                    'migrations/'.$timestamp.'_create_products_package_products_items_tables.php'
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
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin.module.products-package.products-items');
    }
}
