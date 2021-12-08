<?php

namespace InetStudio\ProductsPackage\ProductsItems\Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InetStudio\ProductsPackage\ProductsItems\Tests\TestCase;
use InetStudio\ProductsPackage\ProductsItems\Models\ProductItemModel;

class StoreProductItemTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /** @test */
    function admin_users_can_store_a_product_item()
    {
        $user = $this->getUserWithRole('admin');

        $this->assertDatabaseCount('products_items', 0);

        $data = [
            'title' => $this->faker->text(255),
            'content'  => $this->faker->realText(),
        ];

        $this->actingAs($user)
            ->post(
                route('back.products-package.products-items.store'),
                $data
            )
            ->assertOk()
            ->assertJsonFragment(
                [
                    'success' => true,
                ]
            );

        $this->assertDatabaseCount('products_items', 1);

        tap(ProductItemModel::first(), function ($productItem) use ($data) {
            $this->assertEquals($data['title'], $productItem->title);
            $this->assertEquals($data['content'], $productItem->content);
        });
    }

    /** @test */
    function redirect_guests_to_login_page_when_storing_product_item()
    {
        $this->assertFalse(auth()->check());

        $this->post(
            route('back.products-package.products-items.store'),
            [
                'title' => $this->faker->text(255),
                'content' => $this->faker->realText(),
            ]
        )->assertStatus(302);
    }
}
