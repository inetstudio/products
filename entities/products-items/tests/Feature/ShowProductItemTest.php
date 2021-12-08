<?php

namespace InetStudio\ProductsPackage\ProductsItems\Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InetStudio\ProductsPackage\ProductsItems\Tests\TestCase;
use InetStudio\ProductsPackage\ProductsItems\Models\ProductItemModel;

class ShowProductItemTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /** @test */
    function admin_users_can_see_a_product_item()
    {
        $user = $this->getUserWithRole('admin');

        $productItem = ProductItemModel::factory()->create();

        $this->actingAs($user)
            ->get(
                route(
                    'back.products-package.products-items.show',
                    ['products_item' => $productItem->id]
                )
            )
            ->assertOk()
            ->assertJson(['success' => true]);
    }

    /** @test */
    function admin_users_can_not_see_a_non_existing_product_item()
    {
        $user = $this->getUserWithRole('admin');

        ProductItemModel::factory()->create();

        $this->assertDatabaseCount('products_items', 1);

        $this->actingAs($user)
            ->get(
                route(
                    'back.products-package.products-items.show',
                    [
                        'products_item' => 0,
                    ]
                )
            )
            ->assertNotFound();
    }

    /** @test */
    function redirect_guests_to_login_page_when_show_product_item()
    {
        $productItem = ProductItemModel::factory()->create();

        $this->assertFalse(auth()->check());

        $this->get(
            route('back.products-package.products-items.show', ['products_item' => $productItem->id]),
        )->assertStatus(302);
    }
}
