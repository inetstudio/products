<?php

namespace InetStudio\Products\Providers;

use Collective\Html\FormBuilder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Class ProductsServiceProvider.
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
        $this->registerFormComponents();
        $this->registerBladeDirectives();
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
                'InetStudio\Products\Console\Commands\SetupCommand',
                'InetStudio\Products\Console\Commands\ProcessGoogleFeeds',
                'InetStudio\Products\Console\Commands\ProcessYandexFeeds',
                'InetStudio\Products\Console\Commands\CreateFoldersCommand',
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
     * Регистрация компонентов форм.
     *
     * @return void
     */
    protected function registerFormComponents()
    {
        FormBuilder::component('products', 'admin.module.products::back.forms.blocks.products', ['name' => null, 'value' => null, 'attributes' => null]);
    }

    /**
     * Регистрация директив blade.
     *
     * @return void
     */
    protected function registerBladeDirectives()
    {
        Blade::directive('productLink', function ($expression) {
            $params = explode(',', $expression, 2);
            $params = array_map('trim', $params, array_fill(0, count($params),"' \t\n\r\0\x0B"));

            return view('admin.module.products::front.partials.content.productLink_directive', [
                'id' => $params[0],
                'word' => $params[1],
            ]);
        });

        Blade::directive('productButton', function ($expression) {
            $params = explode(',', $expression, 2);

            return view('admin.module.products::front.partials.content.productButton_directive', [
                'id' => $params[0],
            ]);
        });
    }
}
