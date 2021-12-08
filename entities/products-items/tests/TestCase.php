<?php

namespace InetStudio\ProductsPackage\ProductsItems\Tests;

use Illuminate\Support\Facades\Config;
use InetStudio\ACL\Users\Models\UserModel;
use InetStudio\ACL\Roles\Models\RoleModel;
use Orchestra\Testbench\TestCase as BaseTestCase;
use InetStudio\ProductsPackage\ProductsItems\Models\ProductItemModel;

class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            'InetStudio\ACL\Providers\BindingsServiceProvider',
            'InetStudio\ACL\Providers\ServiceProvider',
            'InetStudio\ACL\Users\Providers\BindingsServiceProvider',
            'InetStudio\ACL\Users\Providers\ServiceProvider',
            'InetStudio\ACL\Permissions\Providers\BindingsServiceProvider',
            'InetStudio\ACL\Permissions\Providers\ServiceProvider',
            'InetStudio\ACL\Roles\Providers\BindingsServiceProvider',
            'InetStudio\ACL\Roles\Providers\ServiceProvider',
            'InetStudio\ProductsPackage\ProductsItems\Providers\ServiceProvider',
            'InetStudio\ProductsPackage\ProductsItems\Providers\BindingsServiceProvider',
            'InetStudio\WidgetsPackage\Widgets\Providers\BindingsServiceProvider',
            'InetStudio\WidgetsPackage\Widgets\Providers\ServiceProvider',
            'Laratrust\LaratrustServiceProvider'
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        include_once __DIR__ . '/../database/migrations/create_products_package_products_items_tables.php.stub';
        include_once __DIR__ . '/../database/migrations/create_users_table.php.stub';
        include_once __DIR__ . '/../database/migrations/create_laratrust_tables.php.stub';
        include_once __DIR__ . '/../database/migrations/create_widgets_tables.php.stub';

        (new \CreateProductsPackageProductsItemsTables)->up();
        (new \CreateUsersTable)->up();
        (new \CreateLaratrustTables)->up();
        (new \CreateWidgetsTables)->up();
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

    protected function getUserWithRole(string $role): UserModel
    {
        Config::set('laratrust.user_models', [
            'users' => 'InetStudio\ACL\Users\Models\UserModel',
        ]);
        Config::set('laratrust.models', [
            'role' => 'InetStudio\ACL\Roles\Models\RoleModel',
            'permission' => 'InetStudio\ACL\Permissions\Models\PermissionModel',
        ]);

        $user = UserModel::factory()->create();
        $roleObject = RoleModel::create(
            [
                'name' => $role,
                'display_name' => $role,
            ]
        );

        $user->attachRole($roleObject);

        return $user;
    }
}
