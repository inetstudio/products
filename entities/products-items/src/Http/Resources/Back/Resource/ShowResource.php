<?php

namespace InetStudio\ProductsPackage\ProductsItems\Http\Resources\Back\Resource;

use Illuminate\Http\Resources\Json\JsonResource;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Http\Resources\Back\Resource\ShowResourceContract;

class ShowResource extends JsonResource implements ShowResourceContract
{
    public function toArray($request)
    {
        return [
            'id' => $this['id'],
            'title' => $this['title'],
            'content' => $this['content'],
            'preview' => $this->getPreviewImage(),
            'content_images' => $this->getContentImages(),
        ];
    }

    public function getPreviewImage(): ?array
    {
        $preview = $this->resource->getFirstMedia('preview');

        if ($preview) {
            $media = [
                'filepath' => url($preview->getUrl()),
                'filename' => $preview->file_name,
                'additional_info' => [
                    'alt' => $preview->getCustomProperty('alt'),
                    'description' => $preview->getCustomProperty('description'),
                    'copyright' => $preview->getCustomProperty('copyright'),
                ],
                'crop' => $preview->getCustomProperty('crop.vertical'),
            ];
        } else {
            return null;
        }

        return $media;
    }

    public function getContentImages(): array
    {
        $images = $this->resource->getMedia('content');

        $media = [];

        foreach ($images as $mediaItem) {
            $data = [
                'id' => $mediaItem->id,
                'src' => url($mediaItem->getUrl()),
                'thumb' => ($mediaItem->getUrl('content_admin')) ? url($mediaItem->getUrl('content_admin')) : url($mediaItem->getUrl()),
                'properties' => $mediaItem->custom_properties,
            ];

            $media[] = $data;
        }

        return $media;
    }
}
