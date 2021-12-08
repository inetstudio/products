<?php

namespace InetStudio\ProductsPackage\ProductsItems\Tests\Unit;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use InetStudio\ProductsPackage\ProductsItems\Tests\TestCase;

class InstallProductsItemsTest extends TestCase
{
    /** @test */
    function the_install_command_copies_the_configuration()
    {
        if (File::exists(config_path('products_package_products_items.php'))) {
            unlink(config_path('products_package_products_items.php'));
        }

        $this->assertFalse(File::exists(config_path('products_package_products_items.php')));

        Artisan::call('inetstudio:products-package:products-items:setup');

        $this->assertTrue(File::exists(config_path('products_package_products_items.php')));
    }
}
