<?php

namespace InetStudio\ProductsPackage\Links\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use InetStudio\ProductsPackage\Links\Models\LinkModel;

class LinkFactory extends Factory
{
    protected $model = LinkModel::class;

    public function definition()
    {
        return [
            'product_id' => $this->faker->randomNumber(),
            'href' => $this->faker->url(),
        ];
    }
}
