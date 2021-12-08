<?php

namespace InetStudio\ProductsPackage\Products\Http\Resources\Back\Data;

use Illuminate\Http\Resources\Json\JsonResource;
use InetStudio\ProductsPackage\Products\Contracts\Http\Resources\Back\Data\IndexItemResourceContract;

class EmbeddedItemResource extends JsonResource implements IndexItemResourceContract
{
    public function toArray($request)
    {
        return [
            'id' => (int) $this['id'],
            'preview' => view(
                'admin.module.products-package.products::back.partials.datatables.preview',
                [
                    'src' => url($this->getFirstMediaUrl('preview', 'preview_admin')),
                ]
            )->render(),
            'title' => $this['title'],
            'brand' => $this['brand'],
            'description' => $this['description'],
            'created_at' => (string) $this['created_at'],
            'updated_at' => (string) $this['updated_at'],
            'actions' => view(
                    'admin.module.products-package.products::back.partials.datatables.actions',
                    [
                        'item' => $this,
                    ]
                )
                ->render(),
        ];
    }
}
