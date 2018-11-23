<?php

namespace InetStudio\Products\Http\Responses\Back\Products;

use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Support\Responsable;
use InetStudio\Products\Contracts\Models\ProductModelContract;
use InetStudio\Products\Contracts\Http\Responses\Back\Products\SaveResponseContract;

/**
 * Class SaveResponse.
 */
class SaveResponse implements SaveResponseContract, Responsable
{
    /**
     * @var ProductModelContract
     */
    private $item;

    /**
     * SaveResponse constructor.
     *
     * @param ProductModelContract $item
     */
    public function __construct(ProductModelContract $item)
    {
        $this->item = $item;
    }

    /**
     * Возвращаем ответ при сохранении объекта.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return RedirectResponse
     */
    public function toResponse($request): RedirectResponse
    {
        return response()->redirectToRoute('back.products.edit', [
            $this->item->fresh()->id,
        ]);
    }
}
