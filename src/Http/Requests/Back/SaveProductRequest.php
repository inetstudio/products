<?php

namespace InetStudio\Products\Http\Requests\Back;

use Illuminate\Foundation\Http\FormRequest;
use InetStudio\Products\Contracts\Http\Requests\Back\SaveProductRequestContract;

/**
 * Class SaveProductRequest.
 */
class SaveProductRequest extends FormRequest implements SaveProductRequestContract
{
    /**
     * Определить, авторизован ли пользователь для этого запроса.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Сообщения об ошибках.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
        ];
    }

    /**
     * Правила проверки запроса.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
        ];
    }
}
