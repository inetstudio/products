<?php

namespace InetStudio\Products\Providers;

use Collective\Html\FormBuilder;
use Illuminate\Support\ServiceProvider;

/**
 * Class FormBuilderServiceProvider
 * @package InetStudio\Products\Providers
 */
class FormBuilderServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        FormBuilder::component('products', 'admin.module.products::back.forms.blocks.products', ['name' => null, 'value' => null, 'attributes' => null]);
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
