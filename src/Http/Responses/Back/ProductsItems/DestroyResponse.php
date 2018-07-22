<?php

namespace InetStudio\Products\Http\Responses\Back\ProductsItems;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Responsable;
use InetStudio\Products\Contracts\Http\Responses\Back\ProductsItems\DestroyResponseContract;

/**
 * Class DestroyResponse.
 */
class DestroyResponse implements DestroyResponseContract, Responsable
{
    /**
     * @var bool
     */
    private $result;

    /**
     * DestroyResponse constructor.
     *
     * @param bool $result
     */
    public function __construct(bool $result)
    {
        $this->result = $result;
    }

    /**
     * Возвращаем ответ при удалении объекта.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'success' => $this->result,
        ]);
    }
}
