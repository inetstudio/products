<?php

namespace InetStudio\ProductsPackage\ProductsItems\Http\Requests\Back\Resource;

use Illuminate\Foundation\Http\FormRequest;
use InetStudio\UploadsPackage\Uploads\Validation\Rules\CropSize;
use InetStudio\ProductsPackage\ProductsItems\Contracts\DTO\Actions\Back\Resource\UpdateItemDataContract;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Requests\Back\Resource\UpdateRequestContract;

class UpdateRequest extends FormRequest implements UpdateRequestContract
{
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
            $previewCrops = config('products_package_products_items.images.crops.product_item.preview') ?? [];

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
            'content' => 'string|nullable',
        ];

        if ($this->input('preview.filepath')) {
            $previewCrops = config('products_package_products_items.images.crops.product_item.preview') ?? [];

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

    public function getDataObject(): UpdateItemDataContract
    {
        $data = $this->validated();
        $data['id'] = $this->route('products_item');

        return resolve(
            UpdateItemDataContract::class,
            [
                'args' => $data,
            ]
        );
    }
}
