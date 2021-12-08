<?php

namespace InetStudio\ProductsPackage\Products\Http\Responses\Back\Data;

use InetStudio\ProductsPackage\Products\Contracts\Services\Back\DataTables\EmbeddedServiceContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Data\GetEmbeddedDataResponseContract;

class GetEmbeddedDataResponse implements GetEmbeddedDataResponseContract
{
    protected EmbeddedServiceContract $dataService;

    public function __construct(EmbeddedServiceContract $dataService)
    {
        $this->dataService = $dataService;
    }

    public function toResponse($request)
    {
        return $this->dataService->ajax();
    }
}
