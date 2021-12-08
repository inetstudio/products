<?php

namespace InetStudio\ProductsPackage\Products\Contracts\Http\Controllers\Back;

use InetStudio\ProductsPackage\Products\Contracts\Http\Requests\Back\Utility\GetSuggestionsRequestContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Utility\GetSuggestionsResponseContract;

interface UtilityControllerContract
{
    public function getSuggestions(GetSuggestionsRequestContract $request, GetSuggestionsResponseContract $response): GetSuggestionsResponseContract;
}
