<?php

namespace InetStudio\ProductsPackage\ProductsItems\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use InetStudio\ProductsPackage\ProductsItems\Models\ProductItemModel;

class ProductItemFactory extends Factory
{
    protected $model = ProductItemModel::class;

    public function definition()
    {
        return [
            'title' => $this->faker->text(255),
            'content' => $this->faker->realText(),
        ];
    }
}
