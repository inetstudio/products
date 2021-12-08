<?php

namespace InetStudio\ProductsPackage\ProductsItems\Http\Responses\Back\Resource;

use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Responses\Back\Resource\DestroyResponseContract;

class DestroyResponse implements DestroyResponseContract
{
    public function __construct(
        protected int $result = 0
    ){}

    public function setResult(int $result): void
    {
        $this->result = $result;
    }

    public function toResponse($request)
    {
        $resource = $this->result;

        return resolve(
            'InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Resources\Back\Resource\DestroyResourceContract',
            compact('resource')
        );
    }
}
