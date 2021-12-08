<?php

namespace InetStudio\ProductsPackage\Links\Tests\Unit;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InetStudio\ProductsPackage\Links\Tests\TestCase;
use InetStudio\ProductsPackage\Links\Models\LinkModel;

class LinkModelTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /** @test */
    function a_link_has_a_product_id()
    {
        $productId = $this->faker->randomNumber();

        $link = LinkModel::factory()->create(['product_id' => $productId]);

        $this->assertEquals($productId, $link->product_id);
    }

    /** @test */
    function a_link_has_a_href()
    {
        $href = $this->faker->url();

        $link = LinkModel::factory()->create(['href' => $href]);

        $this->assertEquals($href, $link->href);
    }
}
