<?php

namespace InetStudio\Products\Http\Responses\Back\ProductsItems;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Responsable;
use InetStudio\Products\Contracts\Models\ProductItemModelContract;
use InetStudio\Products\Contracts\Http\Responses\Back\ProductsItems\ShowResponseContract;

/**
 * Class ShowResponse.
 */
class ShowResponse implements ShowResponseContract, Responsable
{
    /**
     * @var ProductItemModelContract
     */
    private $item;

    /**
     * ShowResponse constructor.
     *
     * @param ProductItemModelContract $item
     */
    public function __construct(ProductItemModelContract $item)
    {
        $this->item = $item;
    }

    /**
     * Возвращаем ответ при получении объекта.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'id' => $this->item->id,
            'title' => $this->item->title,
            'content' => $this->item->content,
            'preview' => $this->getPreviewImage(),
            'content_images' => $this->getContentImages(),
        ]);
    }

    /**
     * Получаем превью.
     *
     * @return array
     */
    public function getPreviewImage(): array
    {
        $preview = $this->item->getFirstMedia('preview');

        $media = [];

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
        }

        return $media;
    }

    /**
     * Получаем контентные изображения.
     *
     * @return array
     */
    public function getContentImages(): array
    {
        $images = $this->item->getMedia('content');

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
