<?php

namespace InetStudio\Products\Http\Responses\Back\ProductsItems;

use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Responsable;
use InetStudio\Products\Contracts\Models\ProductItemModelContract;
use InetStudio\Products\Contracts\Http\Responses\Back\ProductsItems\SaveResponseContract;

/**
 * Class SaveResponse.
 */
class SaveResponse implements SaveResponseContract, Responsable
{
    /**
     * @var ProductItemModelContract
     */
    private $item;

    /**
     * SaveResponse constructor.
     *
     * @param ProductItemModelContract $item
     */
    public function __construct(ProductItemModelContract $item)
    {
        $this->item = $item;
    }

    /**
     * Возвращаем ответ при сохранении объекта.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function toResponse($request)
    {
        $this->item = $this->item->fresh();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'id' => $this->item->id,
                'title' => ($this->item->title) ? $this->item->title : Str::limit($this->item->message, '100', '...'),
            ], 200);
        }
    }
}
