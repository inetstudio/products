<?php

namespace InetStudio\ProductsPackage\Products\Http\Responses\Back\Data;

use InetStudio\ProductsPackage\Products\Contracts\Services\Back\DataTables\IndexServiceContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Data\GetIndexDataResponseContract;

class GetIndexDataResponse implements GetIndexDataResponseContract
{
    protected IndexServiceContract $dataService;

    public function __construct(IndexServiceContract $dataService)
    {
        $this->dataService = $dataService;
    }

    public function toResponse($request)
    {
        return $this->dataService->ajax();
    }
}
