<?php

namespace InetStudio\Products\Http\Requests\Back;

use Illuminate\Foundation\Http\FormRequest;
use InetStudio\Uploads\Validation\Rules\CropSize;
use InetStudio\Products\Contracts\Http\Requests\Back\SaveProductItemRequestContract;

/**
 * Class SaveProductItemRequest.
 */
class SaveProductItemRequest extends FormRequest implements SaveProductItemRequestContract
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

    public function messages(): array
    {
        $messages = [
            'title.required' => 'Поле «Заголовок» обязательно для заполнения',
            'title.max' => 'Поле «Заголовок» не должно превышать 255 символов',
        ];

        if ($this->input('preview.filepath')) {
            $previewCrops = config('products.images.crops.product_item.preview') ?? [];

            $cropMessages = [];

            foreach ($previewCrops as $previewCrop) {
                $cropMessages['preview.crop.'.$previewCrop['name'].'.required'] = 'Необходимо выбрать область отображения '.$previewCrop['ratio'];
                $cropMessages['preview.crop.'.$previewCrop['name'].'.json'] = 'Область отображения '.$previewCrop['ratio'].' должна быть представлена в виде JSON';
            }

            $messages = array_merge($messages, [
                'preview.file.required' => 'Поле «Превью» обязательно для заполнения',
                'preview.description.max' => 'Поле «Описание» не должно превышать 255 символов',
                'preview.copyright.max' => 'Поле «Copyright» не должно превышать 255 символов',
                'preview.alt.required' => 'Поле «Alt» обязательно для заполнения',
                'preview.alt.max' => 'Поле «Alt» не должно превышать 255 символов',
            ], $cropMessages);
        }

        return $messages;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|max:255',
        ];

        if ($this->input('preview.filepath')) {
            $previewCrops = config('products.images.crops.product_item.preview') ?? [];

            $cropRules = [];

            foreach ($previewCrops as $previewCrop) {
                $cropRules['preview.crop.'.$previewCrop['name']] = [
                    'nullable', 'json',
                    new CropSize($previewCrop['size']['width'], $previewCrop['size']['height'], $previewCrop['size']['type'], $previewCrop['title']),
                ];
            }

            $rules = array_merge($rules, [
                'preview.description' => 'max:255',
                'preview.copyright' => 'max:255',
                'preview.alt' => 'max:255',
            ], $cropRules);
        }

        return $rules;
    }
}
