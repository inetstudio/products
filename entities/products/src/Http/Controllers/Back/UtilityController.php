<?php

namespace InetStudio\ProductsPackage\Products\Http\Controllers\Back;

use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use InetStudio\ProductsPackage\Products\Contracts\Http\Controllers\Back\UtilityControllerContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Requests\Back\Utility\GetSuggestionsRequestContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Utility\GetSuggestionsResponseContract;

class UtilityController extends Controller implements UtilityControllerContract
{
    public function getSuggestions(GetSuggestionsRequestContract $request, GetSuggestionsResponseContract $response): GetSuggestionsResponseContract
    {
        return $response;
    }
}
