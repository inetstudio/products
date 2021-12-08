<?php

namespace InetStudio\ProductsPackage\ProductsItems\Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InetStudio\ProductsPackage\ProductsItems\Tests\TestCase;
use InetStudio\ProductsPackage\ProductsItems\Models\ProductItemModel;

class DestroyProductItemTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /** @test */
    function admin_users_can_destroy_a_product_item()
    {
        $user = $this->getUserWithRole('admin');

        $productItem = ProductItemModel::factory()->create();

        $this->assertDatabaseCount('products_items', 1);

        $this->actingAs($user)
            ->delete(route('back.products-package.products-items.destroy', ['products_item' => $productItem->id]))
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertCount(0, ProductItemModel::all());
    }

    /** @test */
    function admin_users_can_not_destroy_a_non_existing_product_item()
    {
        $user = $this->getUserWithRole('admin');

        ProductItemModel::factory()->create();

        $this->assertDatabaseCount('products_items', 1);

        $this->actingAs($user)
            ->delete(
                route('back.products-package.products-items.destroy', ['products_item' => 0])
            )
            ->assertOk()
            ->assertJson(['success' => false]);

        $this->assertCount(1, ProductItemModel::all());
    }

    /** @test */
    function redirect_guests_to_login_page_when_destroying_product_item()
    {
        $productItem = ProductItemModel::factory()->create();

        $this->assertFalse(auth()->check());

        $this->delete(
                route(
                    'back.products-package.products-items.destroy',
                    [
                        'products_item' => $productItem->id,
                    ]
                ),
            )
            ->assertStatus(302);
    }
}
