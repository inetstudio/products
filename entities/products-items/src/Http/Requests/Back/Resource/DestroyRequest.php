<?php

namespace InetStudio\ProductsPackage\ProductsItems\Http\Requests\Back\Resource;

use Illuminate\Foundation\Http\FormRequest;
use InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Actions\Back\Resource\DestroyItemDataContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Requests\Back\Resource\DestroyRequestContract;

class DestroyRequest extends FormRequest implements DestroyRequestContract
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

    public function getDataObject(): DestroyItemDataContract
    {
        $data = [
            'id' => $this->route('products_item'),
        ];

        return resolve(
            DestroyItemDataContract::class,
            [
                'args' => $data,
            ]
        );
    }
}
