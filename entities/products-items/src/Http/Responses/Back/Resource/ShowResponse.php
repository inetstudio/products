<?php

namespace InetStudio\ProductsPackage\ProductsItems\Http\Responses\Back\Resource;

use Illuminate\Database\Eloquent\Collection;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Models\ProductItemModelContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Responses\Back\Resource\ShowResponseContract;

class ShowResponse implements ShowResponseContract
{
    public function __construct(
        protected ?ProductItemModelContract $result = null
    ) {}

    public function setResult(Collection $result): void
    {
        $this->result = $result->first();
    }

    public function toResponse($request)
    {
        $resource = $this->result;

        if (! $resource) {
            abort(404);
        }

        return resolve(
            'InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Resources\Back\Resource\ShowResourceContract',
            compact('resource')
        );
    }
}
