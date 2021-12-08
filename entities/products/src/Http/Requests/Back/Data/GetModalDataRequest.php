<?php

namespace InetStudio\ProductsPackage\Products\Http\Requests\Back\Data;

use Illuminate\Foundation\Http\FormRequest;
use InetStudio\ProductsPackage\Products\Contracts\Http\Requests\Back\Data\GetModalDataRequestContract;

class GetModalDataRequest extends FormRequest implements GetModalDataRequestContract
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
}
