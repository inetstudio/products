<?php

namespace InetStudio\ProductsPackage\ProductsItems\Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InetStudio\ProductsPackage\ProductsItems\Tests\TestCase;
use InetStudio\ProductsPackage\ProductsItems\Models\ProductItemModel;

class UpdateProductItemTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /** @test */
    function admin_users_can_update_a_product_item()
    {
        $user = $this->getUserWithRole('admin');

        $productItem = ProductItemModel::factory()->create();

        $data = [
            'title' => $this->faker->text(255),
            'content' => $this->faker->realText(),
        ];

        $this->actingAs($user)
            ->put(
                route(
                    'back.products-package.products-items.update',
                    ['products_item' => $productItem->id]
                ),
                $data
            )
            ->assertOk()
            ->assertJson(['success' => true]);

        tap(ProductItemModel::first(), function ($productItem) use ($data) {
            $this->assertEquals($data['title'], $productItem->title);
            $this->assertEquals($data['content'], $productItem->content);
        });
    }

    /** @test */
    function admin_users_can_not_update_a_non_existing_product_item()
    {
        $user = $this->getUserWithRole('admin');

        ProductItemModel::factory()->create();

        $this->assertDatabaseCount('products_items', 1);

        $data = [
            'title' => $this->faker->text(255),
            'content' => $this->faker->realText(),
        ];

        $this->actingAs($user)
            ->put(
                route(
                    'back.products-package.products-items.update',
                    [
                        'products_item' => 0,
                    ]
                ),
                $data
            )
            ->assertNotFound();

        $this->assertDatabaseCount('products_items', 1);
    }

    /** @test */
    function redirect_guests_to_login_page_when_updating_product_item()
    {
        $productItem = ProductItemModel::factory()->create();

        $this->assertFalse(auth()->check());

        $data = [
            'title' => $this->faker->text(255),
            'content' => $this->faker->realText(),
        ];

        $this->put(
            route('back.products-package.products-items.update', ['products_item' => $productItem->id]),
            $data
        )->assertStatus(302);
    }
}
