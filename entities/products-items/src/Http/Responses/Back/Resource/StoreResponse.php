<?php

namespace InetStudio\ProductsPackage\ProductsItems\Http\Responses\Back\Resource;

use InetStudio\ProductsPackage\ProductsItems\Contracts\Models\ProductItemModelContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Responses\Back\Resource\StoreResponseContract;

class StoreResponse implements StoreResponseContract
{
    public function __construct(
        protected ?ProductItemModelContract $result = null
    ) {}

    public function setResult(?ProductItemModelContract $result): void
    {
        $this->result = $result;
    }

    public function toResponse($request)
    {
        $resource = $this->result;

        if (! $resource) {
            abort(404);
        }

        return resolve(
            'InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Resources\Back\Resource\StoreResourceContract',
            compact('resource')
        );
    }
}
