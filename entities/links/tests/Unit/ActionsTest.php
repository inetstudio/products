<?php

namespace InetStudio\ProductsPackage\Links\Tests\Unit;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InetStudio\ProductsPackage\Links\Tests\TestCase;
use InetStudio\ProductsPackage\Links\Models\LinkModel;
use InetStudio\ProductsPackage\Links\DTO\Back\Resource\StoreItemData;
use InetStudio\ProductsPackage\Links\DTO\Back\Resource\UpdateItemData;
use InetStudio\ProductsPackage\Links\Actions\Back\Resource\StoreAction;
use InetStudio\ProductsPackage\Links\Actions\Back\Resource\UpdateAction;

class ActionsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /** @test */
    function a_link_stored_by_action()
    {
        $data = new StoreItemData([
            'productId' => $this->faker->randomNumber(),
            'href' => $this->faker->url(),
        ]);

        $result = (new StoreAction(new LinkModel))->execute($data);

        $this->assertDatabaseCount('products_links', 1);
        $this->assertEquals($data->productId, $result->product_id);
        $this->assertEquals($data->href, $result->href);
    }

    /** @test */
    function a_link_updated_by_action()
    {
        $link = LinkModel::factory()->create();

        $data = new UpdateItemData([
            'id' => $link->id,
            'productId' => $this->faker->randomNumber(),
            'href' => $this->faker->url(),
        ]);

        $result = (new UpdateAction(new LinkModel))->execute($data);

        $this->assertDatabaseCount('products_links', 1);
        $this->assertEquals($data->productId, $result->product_id);
        $this->assertEquals($data->href, $result->href);
    }

    /** @test */
    function not_update_non_existing_link()
    {
        $data = new UpdateItemData([
            'id' => $this->faker->randomNumber(),
            'productId' => $this->faker->randomNumber(),
            'href' => $this->faker->url(),
        ]);

        $result = (new UpdateAction(new LinkModel))->execute($data);

        $this->assertNull($result);
    }
}
