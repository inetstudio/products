<?php

namespace InetStudio\ProductsPackage\Products\Http\Responses\Back\Data;

use InetStudio\ProductsPackage\Products\Contracts\Services\Back\DataTables\ModalServiceContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Data\GetModalDataResponseContract;

class GetModalDataResponse implements GetModalDataResponseContract
{
    protected ModalServiceContract $dataService;

    public function __construct(ModalServiceContract $dataService)
    {
        $this->dataService = $dataService;
    }

    public function toResponse($request)
    {
        return $this->dataService->ajax();
    }
}
