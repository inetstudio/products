<?php

namespace InetStudio\ProductsPackage\ProductsItems\Http\Requests\Back\Resource;

use Illuminate\Foundation\Http\FormRequest;
use InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Queries\FetchItemsByIdsDataContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Requests\Back\Resource\ShowRequestContract;

class ShowRequest extends FormRequest implements ShowRequestContract
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [];
    }

    public function rules(): array
    {
        return [];
    }

    public function getDataObject(): FetchItemsByIdsDataContract
    {
        $data = [
            'ids' => (array) $this->route('products_item'),
        ];

        return resolve(
            FetchItemsByIdsDataContract::class,
            [
                'args' => $data,
            ]
        );
    }
}
