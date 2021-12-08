<?php

namespace InetStudio\ProductsPackage\Products\Contracts\Http\Controllers\Back;

use InetStudio\ProductsPackage\Products\Contracts\Http\Requests\Back\Data\GetIndexDataRequestContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Data\GetIndexDataResponseContract;

interface DataControllerContract
{
    public function getIndexData(GetIndexDataRequestContract $request, GetIndexDataResponseContract $response): GetIndexDataResponseContract;
}
