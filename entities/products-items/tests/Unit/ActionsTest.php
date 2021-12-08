<?php

namespace InetStudio\ProductsPackage\ProductsItems\Tests\Unit;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InetStudio\ProductsPackage\ProductsItems\Tests\TestCase;
use InetStudio\ProductsPackage\ProductsItems\Models\ProductItemModel;
use InetStudio\ProductsPackage\ProductsItems\Actions\Back\Resource\StoreAction;
use InetStudio\ProductsPackage\ProductsItems\Actions\Back\Resource\UpdateAction;
use InetStudio\ProductsPackage\ProductsItems\Actions\Back\Resource\DestroyAction;
use InetStudio\ProductsPackage\ProductsItems\DTO\Actions\Back\Resource\StoreItemData;
use InetStudio\ProductsPackage\ProductsItems\DTO\Actions\Back\Resource\UpdateItemData;
use InetStudio\ProductsPackage\ProductsItems\DTO\Actions\Back\Resource\DestroyItemData;

class ActionsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /** @test */
    function a_product_item_stored_by_action()
    {
        $data = new StoreItemData([
            'title' => $this->faker->text(255),
            'content' => $this->faker->realText(),
        ]);

        $result = (new StoreAction(new ProductItemModel))->execute($data);

        $this->assertDatabaseCount('products_items', 1);
        $this->assertEquals($data->title, $result->title);
        $this->assertEquals($data->content, $result->content);
    }

    /** @test */
    function a_product_item_updated_by_action()
    {
        $productItem = ProductItemModel::factory()->create();

        $data = new UpdateItemData([
            'id' => $productItem->id,
            'title' => $this->faker->text(255),
            'content' => $this->faker->realText(),
        ]);

        $result = (new UpdateAction(new ProductItemModel))->execute($data);

        $this->assertDatabaseCount('products_items', 1);
        $this->assertEquals($data->title, $result->title);
        $this->assertEquals($data->content, $result->content);
    }

    /** @test */
    function not_update_non_existent_product_item()
    {
        $data = new UpdateItemData([
            'id' => $this->faker->randomNumber(),
            'title' => $this->faker->text(255),
            'content' => $this->faker->realText(),
        ]);

        $result = (new UpdateAction(new ProductItemModel))->execute($data);

        $this->assertNull($result);
    }

    /** @test */
    function a_product_item_destroyed_by_action()
    {
        $productItem = ProductItemModel::factory()->create();

        $data = new DestroyItemData([
            'id' => $productItem->id,
        ]);

        $result = (new DestroyAction(new ProductItemModel))->execute($data);

        $this->assertCount(0, ProductItemModel::all());
        $this->assertEquals(1, $result);
    }

    /** @test */
    function not_destroy_non_existent_product_item()
    {
        ProductItemModel::factory()->create();

        $data = new DestroyItemData([
            'id' => 0,
        ]);

        $result = (new DestroyAction(new ProductItemModel))->execute($data);

        $this->assertEquals(0, $result);
        $this->assertCount(1, ProductItemModel::all());
    }
}
