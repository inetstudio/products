<?php

namespace InetStudio\ProductsPackage\Products\Http\Responses\Back\Resource;

use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Resource\IndexResponseContract;
use InetStudio\ProductsPackage\Products\Contracts\Services\Back\DataTables\IndexServiceContract as DataTableServiceContract;

class IndexResponse implements IndexResponseContract
{
    protected DataTableServiceContract $datatableService;

    public function __construct(DataTableServiceContract $datatableService)
    {
        $this->datatableService = $datatableService;
    }

    public function toResponse($request)
    {
        $table = $this->datatableService->html();

        return view('admin.module.products-package.products::back.pages.index', compact('table'));
    }
}
