<?php

namespace InetStudio\ProductsPackage\ProductsItems\Http\Resources\Back\Resource;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Resources\Back\Resource\StoreResourceContract;

class StoreResource extends JsonResource implements StoreResourceContract
{
    public function toArray($request)
    {
        return [
            'success' => isset($this['id']),
            'data' => [
                'id' => $this['id'],
                'title' => $this['title'] ?? Str::limit($this['content'], '100', '...'),
            ],
        ];
    }
}
