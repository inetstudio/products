<?php

namespace InetStudio\ProductsPackage\ProductsItems\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use InetStudio\ProductsPackage\ProductsItems\Tests\TestCase;
use InetStudio\ProductsPackage\ProductsItems\Models\ProductItemModel;

class ProductItemModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_product_item_has_a_title()
    {
        $productItem = ProductItemModel::factory()->create(['title' => 'Test title']);

        $this->assertEquals('Test title', $productItem->title);
    }

    /** @test */
    function a_product_item_has_a_content()
    {
        $productItem = ProductItemModel::factory()->create(['content' => 'Test content']);

        $this->assertEquals('Test content', $productItem->content);
    }
}
