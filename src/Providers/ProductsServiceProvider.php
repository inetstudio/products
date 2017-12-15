<?php

namespace InetStudio\Products\Providers;

use Illuminate\Support\ServiceProvider;
use InetStudio\Products\Console\Commands\SetupCommand;
use InetStudio\Products\Console\Commands\ProcessGoogleFeeds;
use InetStudio\Products\Console\Commands\CreateFoldersCommand;

class ProductsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../public' => public_path(),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin.module.products');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->publishes([
            __DIR__ . '/../config/products.php' => config_path('products.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__ . '/../config/filesystems.php', 'filesystems.disks'
        );

        if ($this->app->runningInConsole()) {
            if (! class_exists('CreateProductsTables')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_products_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_products_tables.php'),
                ], 'migrations');
            }

            $this->commands([
                SetupCommand::class,
                CreateFoldersCommand::class,
                ProcessGoogleFeeds::class,
            ]);
        }

        \Form::component('products', 'admin.module.products::back.forms.blocks.products', ['name' => null, 'value' => null, 'attributes' => null]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
