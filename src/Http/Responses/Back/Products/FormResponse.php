<?php

namespace InetStudio\Products\Http\Responses\Back\Products;

use Illuminate\View\View;
use Illuminate\Contracts\Support\Responsable;
use InetStudio\Products\Contracts\Http\Responses\Back\Products\FormResponseContract;

/**
 * Class FormResponse.
 */
class FormResponse implements FormResponseContract, Responsable
{
    /**
     * @var array
     */
    private $data;

    /**
     * FormResponse constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Возвращаем ответ при открытии формы объекта.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return View
     */
    public function toResponse($request): View
    {
        return view('admin.module.products::back.pages.form', $this->data);
    }
}
