<?php

namespace InetStudio\ProductsPackage\ProductsItems\Http\Resources\Back\Resource;

use Illuminate\Http\Resources\Json\JsonResource;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Resources\Back\Resource\DestroyResourceContract;

class DestroyResource extends JsonResource implements DestroyResourceContract
{
    public function toArray($request)
    {
        return [
            'success' => ($this->resource > 0),
        ];
    }
}
