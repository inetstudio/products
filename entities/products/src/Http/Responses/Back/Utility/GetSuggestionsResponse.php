<?php

namespace InetStudio\ProductsPackage\Products\Http\Responses\Back\Utility;

use InetStudio\ProductsPackage\Products\Contracts\Services\Back\UtilityServiceContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Responses\Back\Utility\GetSuggestionsResponseContract;

class GetSuggestionsResponse implements GetSuggestionsResponseContract
{
    protected UtilityServiceContract $utilityService;

    public function __construct(UtilityServiceContract $utilityService)
    {
        $this->utilityService = $utilityService;
    }

    public function toResponse($request)
    {
        $search = $request->get('q', '') ?? '';
        $type = $request->get('type', '') ?? '';

        $resource = $this->utilityService->getSuggestions($search);

        return resolve(
            'InetStudio\ProductsPackage\Products\Contracts\Http\Resources\Back\Utility\Suggestions\ItemsCollectionContract',
            compact('resource', 'type')
        );
    }
}
