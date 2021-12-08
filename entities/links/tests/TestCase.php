<?php

namespace InetStudio\ProductsPackage\Links\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use InetStudio\ProductsPackage\Links\Providers\ServiceProvider as LinksServiceProvider;
use InetStudio\ProductsPackage\Links\Providers\BindingsServiceProvider as LinksBindingsServiceProvider;

class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LinksBindingsServiceProvider::class,
            LinksServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        include_once __DIR__ . '/../database/migrations/create_products_package_links_tables.php.stub';

        (new \CreateProductsPackageLinksTables)->up();
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('app.key', 'base64:2fl+Ktvkfl+Fuz4Qp/A75G2RTiWVA/ZoKZvp6fiiM10=');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
