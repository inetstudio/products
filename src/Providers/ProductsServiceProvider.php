<?php

namespace InetStudio\Products\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use InetStudio\Products\Events\UpdateProductsEvent;
use InetStudio\Products\Console\Commands\SetupCommand;
use InetStudio\Products\Console\Commands\ProcessGoogleFeeds;
use InetStudio\Products\Console\Commands\ProcessYandexFeeds;
use InetStudio\Products\Listeners\ClearProductsCacheListener;
use InetStudio\Products\Console\Commands\CreateFoldersCommand;
use InetStudio\Products\Services\Back\ProductsAnalyticsService;
use InetStudio\Products\Contracts\Services\ProductsAnalyticsServiceContract;

/**
 * Class ProductsServiceProvider
 * @package InetStudio\Products\Providers
 */
class ProductsServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerEvents();
    }

    /**
     * Регистрация привязки в контейнере.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerBindings();
    }

    /**
     * Регистрация команд.
     *
     * @return void
     */
    protected function registerConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SetupCommand::class,
                ProcessGoogleFeeds::class,
                ProcessYandexFeeds::class,
                CreateFoldersCommand::class,
            ]);
        }
    }

    /**
     * Регистрация ресурсов.
     *
     * @return void
     */
    protected function registerPublishes(): void
    {
        $this->publishes([
            __DIR__.'/../../public' => public_path(),
        ], 'public');

        $this->publishes([
            __DIR__.'/../../config/products.php' => config_path('products.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../../config/filesystems.php', 'filesystems.disks'
        );

        if ($this->app->runningInConsole()) {
            if (! class_exists('CreateProductsTables')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__.'/../../database/migrations/create_products_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_products_tables.php'),
                ], 'migrations');
            }
        }
    }

    /**
     * Регистрация путей.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin.module.products');
    }

    /**
     * Регистрация событий.
     *
     * @return void
     */
    protected function registerEvents(): void
    {
        Event::listen(UpdateProductsEvent::class, ClearProductsCacheListener::class);
    }

    /**
     * Регистрация привязок, алиасов и сторонних провайдеров сервисов.
     *
     * @return void
     */
    public function registerBindings(): void
    {
        $this->app->bind(ProductsAnalyticsServiceContract::class, ProductsAnalyticsService::class);
    }
}
