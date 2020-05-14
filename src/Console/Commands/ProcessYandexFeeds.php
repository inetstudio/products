<?php

namespace InetStudio\Products\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use InetStudio\Products\Models\ProductModel;
use InetStudio\Products\Models\ProductLinkModel;

/**
 * Class ProcessYandexFeeds.
 */
class ProcessYandexFeeds extends Command
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:products:feeds:yandex';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Process yandex feeds';

    /**
     * Запуск команды.
     */
    public function handle(): void
    {
        $tempPath = Storage::disk('temp')->getDriver()->getAdapter()->getPathPrefix();

        if (config('products.feeds.yandex')) {
            foreach (config('products.feeds.yandex') as $productsBrand => $url) {
                $this->info('Обработка бренда: '.$productsBrand);

                $feedHash = md5($url);

                $context = stream_context_create(['http' => ['header' => 'Accept: application/xml']]);

                try {
                    $contents = file_get_contents($url, false, $context);
                } catch (\Exception $e) {
                    $this->error('Фид недоступен: '.$url);
                }

                if (! isset($contents)) {
                    continue;
                }

                $xml = simplexml_load_string($contents);

                $products = [];

                foreach ($xml->shop->offers->offer as $product) {
                    $isModified = false;
                    $isNew = false;

                    $savedProduct = $this->getProduct($feedHash, trim($product->vendorCode));
                    if ($savedProduct && $savedProduct->update == 0) {
                        $products[] = $savedProduct->g_id;

                        continue;
                    }

                    $products[] = trim($product->vendorCode);

                    $deleteProduct = ProductModel::onlyTrashed()->where('feed_hash', $feedHash)->where('g_id', trim($product->vendorCode))->first();
                    if ($deleteProduct) {
                        $deleteProduct->restore();

                        $isModified = true;
                    }

                    $productObj = ProductModel::where('feed_hash', $feedHash)->where('g_id', trim($product->vendorCode))->first();

                    $productData = [
                        'feed_hash' => $feedHash,
                        'g_id' => trim($product->vendorCode),
                        'title' => isset($product->model) ? trim($product->model) : '',
                        'description' => isset($product->description) ? trim($product->description) : '',
                        'brand' => isset($product->vendor) ? trim($product->vendor) : '',
                    ];

                    if ($productObj) {
                        $productObj->fill($productData);

                        $isModified = $productObj->isDirty();

                        $productObj->save();
                    } else {
                        $productObj = ProductModel::create($productData);

                        $isNew = true;
                    }

                    $imageLink = isset($product->picture) ? trim($product->picture) : '';
                    if ($imageLink) {
                        if (! $productObj->hasMedia('preview')) {
                            $tempFile = $tempPath.'/'.basename($imageLink);

                            $this->grabImage($imageLink, $tempFile);

                            $media = $productObj
                                ->addMedia($tempFile)
                                ->withCustomProperties(['source' => $imageLink])
                                ->toMediaCollection('preview', 'products');

                            $media->custom_properties = [
                                'processed' => true,
                                'source' => $imageLink,
                            ];
                            $media->save();

                            event(app()->makeWith('InetStudio\Uploads\Contracts\Events\Back\UpdateUploadEventContract', [
                                'object' => $productObj,
                                'collection' => 'preview',
                            ]));

                            $isModified = true;
                        } else {
                            if (! $productObj->getFirstMedia('preview')->hasCustomProperty('processed')) {
                                $productObj->clearMediaCollection('preview');
                                continue;
                            }
                        }
                    }

                    $isLinksModified = false;

                    if (isset($product->url)) {
                        $productLink = isset($product->url) ? $product->url : '';
                        if ($productLink) {
                            $isLinksModified = $this->createLinks($productObj, [trim($productLink)]);
                        }
                    } elseif (isset($product->links)) {
                        $productLinks = [];
                        foreach ($product->links->link as $link) {
                            $productLinks[] = trim($link->href);
                        }

                        $isLinksModified = $this->createLinks($productObj, $productLinks);
                    }

                    if ($isLinksModified) {
                        $isModified = true;
                    }

                    if (! $isNew && $isModified) {
                        event(app()->makeWith('InetStudio\Products\Contracts\Events\Back\ModifyProductEventContract', [
                            'object' => $productObj,
                        ]));
                    }
                }

                $this->deleteProducts($feedHash, $products);
            }
        }
    }

    /**
     * Создаем ссылку на продукт.
     *
     * @param ProductModel $productObject
     * @param array $links
     *
     * @return bool
     */
    protected function createLinks(ProductModel $productObject, array $links): bool
    {
        $productIsModified = false;

        $productObjectLinks = ProductLinkModel::where('product_id', $productObject->id)->whereNotIn('link', $links)->get();

        if ($productObjectLinks->count() > 0) {
            foreach ($productObjectLinks as $productObjectLink) {
                $productObjectLink->delete();
            }

            $productIsModified = true;
        }

        foreach ($links as $link) {
            $productObjectLink = ProductLinkModel::where('product_id', $productObject->id)->where('link', $link)->first();

            $linkData = [
                'product_id' => $productObject->id,
                'link' => $link,
            ];

            if ($productObjectLink) {
                $productObjectLink->fill($linkData);
                $updateFlag = $productObjectLink->isDirty();
                $productObjectLink->save();

                if ($updateFlag) {
                    $productIsModified = true;
                }
            } else {
                ProductLinkModel::create($linkData);

                $productIsModified = true;
            }
        }

        return $productIsModified;
    }

    /**
     * Удаляем продукты.
     *
     * @param string $feedHash
     * @param array $products
     */
    protected function deleteProducts(string $feedHash, array $products)
    {
        ProductModel::where('feed_hash', $feedHash)->whereNotIn('g_id', $products)->delete();

        $deletedProducts = ProductModel::onlyTrashed()
            ->where('feed_hash', $feedHash)
            ->whereNotIn('g_id', $products)
            ->get();

        foreach ($deletedProducts as $product) {
            event(app()->makeWith('InetStudio\Products\Contracts\Events\Back\ModifyProductEventContract', [
                'object' => $product,
            ]));
        }
    }

    /**
     * Получаем продукт.
     *
     * @param $feedHash
     * @param $productId
     *
     * @return mixed
     */
    protected function getProduct($feedHash, $productId)
    {
        $product = ProductModel::withTrashed()->where('feed_hash', $feedHash)->where('g_id', trim($productId))->first();

        return $product;
    }

    /**
     * Сохраняем изображение.
     *
     * @param $url
     * @param $saveTo
     */
    private function grabImage($url, $saveTo): void
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $raw = curl_exec($ch);
        curl_close($ch);

        if (file_exists($saveTo)) {
            unlink($saveTo);
        }

        $fp = fopen($saveTo, 'x');
        fwrite($fp, $raw);
        fclose($fp);
    }
}
