<?php

namespace InetStudio\ProductsPackage\ProductsItems\Tests\Unit;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InetStudio\ProductsPackage\ProductsItems\Tests\TestCase;
use InetStudio\ProductsPackage\ProductsItems\Models\ProductItemModel;
use InetStudio\ProductsPackage\ProductsItems\Queries\FetchItemsByIds;
use InetStudio\ProductsPackage\ProductsItems\DTO\Queries\FetchItemsByIdsData;

class QueriesTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /** @test */
    function fetch_single_products_item_by_fetch_by_ids_query()
    {
        $productItem = ProductItemModel::factory()->create();

        $ids = [$productItem->id];

        $data = new FetchItemsByIdsData([
            'ids' => $ids,
        ]);

        $result = (new FetchItemsByIds(new ProductItemModel))->execute($data);

        $this->assertDatabaseCount('products_items', 1);
        $this->assertCount(1, $result);
        $this->assertEquals($ids, $result->pluck('id')->toArray());
    }

    /** @test */
    function fetch_multiple_products_items_by_fetch_by_ids_query()
    {
        $productItems = ProductItemModel::factory()->count(50)->create();

        $ids = $productItems->pluck('id')->toArray();

        $data = new FetchItemsByIdsData([
            'ids' => $ids,
        ]);

        $result = (new FetchItemsByIds(new ProductItemModel))->execute($data);

        $this->assertDatabaseCount('products_items', 50);
        $this->assertCount(50, $result);
        $this->assertEquals($ids, $result->pluck('id')->toArray());
    }

    /** @test */
    function fetch_non_existing_products_items_by_fetch_by_ids_query()
    {
        $data = new FetchItemsByIdsData([
            'ids' => [$this->faker->randomNumber()],
        ]);

        $result = (new FetchItemsByIds(new ProductItemModel))->execute($data);

        $this->assertDatabaseCount('products_items', 0);
        $this->assertCount(0, $result);
    }

    /** @test */
    function fetch_multiple_with_non_existing_products_items_by_fetch_by_ids_query()
    {
        $productItems = ProductItemModel::factory()->count(50)->create();

        $ids = $productItems->pluck('id')->toArray();

        for ($i = 1; $i <= 50; $i++) {
            $fakeIds[] = $this->faker->unique()->numberBetween(100, 200);
        }

        $data = new FetchItemsByIdsData([
            'ids' => array_merge($ids, $fakeIds),
        ]);

        $result = (new FetchItemsByIds(new ProductItemModel))->execute($data);

        $this->assertDatabaseCount('products_items', 50);
        $this->assertCount(50, $result);
        $this->assertEquals($ids, $result->pluck('id')->toArray());
    }
}
