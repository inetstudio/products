<?php

namespace InetStudio\Products\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use InetStudio\Products\Models\ProductModel;
use InetStudio\Products\Models\ProductLinkModel;

/**
 * Class ProcessGoogleFeeds.
 */
class ProcessGoogleFeeds extends Command
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:products:feeds:google';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Process google feeds';

    /**
     * Запуск команды.
     */
    public function handle(): void
    {
        $tempPath = Storage::disk('temp')->getDriver()->getAdapter()->getPathPrefix();

        if (config('products.feeds.google')) {
            foreach (config('products.feeds.google') as $productsBrand => $url) {
                $this->info(PHP_EOL.'Обработка бренда: '.$productsBrand);

                $feedHash = md5($url);

                $context = stream_context_create(['http' => ['header' => 'Accept: application/xml']]);

                try {
                    $contents = file_get_contents($url, false, $context);
                    $contents = $this->utf8_for_xml($contents);
                } catch (\Exception $e) {
                    $this->error('Фид недоступен: '.$url);
                }

                if (! isset($contents)) {
                    continue;
                }

                $contents = str_replace(['&nbsp;', 'nbsp'], [' ', ' '], $contents);
                $xml = simplexml_load_string($contents);

                $products = [];

                $bar = $this->output->createProgressBar(count($xml->channel->item));

                foreach ($xml->channel->item as $item) {
                    $isModified = false;
                    $isNew = false;

                    $product = $item->children('g', true);

                    $savedProduct = $this->getProduct($feedHash, trim($product->id));
                    if ($savedProduct && $savedProduct->update == 0) {
                        $products[] = $savedProduct->g_id;

                        continue;
                    }

                    $products[] = trim($product->id);

                    $deleteProduct = ProductModel::onlyTrashed()->where('feed_hash', $feedHash)->where('g_id', trim($product->id))->first();
                    if ($deleteProduct) {
                        $deleteProduct->restore();

                        $isModified = true;
                    }

                    $productObj = ProductModel::where('feed_hash', $feedHash)->where('g_id', trim($product->id))->first();

                    $productData = [
                        'feed_hash' => $feedHash,
                        'g_id' => trim($product->id),
                        'title' => (isset($product->title)) ? trim($product->title) : ((isset($item->title)) ? trim($item->title) : ''),
                        'description' => (isset($product->description)) ? trim($product->description) : ((isset($item->description)) ? trim($item->description) : ''),
                        'price' => (isset($product->price)) ? trim($product->price) : ((isset($item->price)) ? trim($item->price) : ''),
                        'condition' => (isset($product->condition)) ? trim($product->condition) : ((isset($item->condition)) ? trim($item->condition) : ''),
                        'availability' => (isset($product->availability)) ? trim($product->availability) : ((isset($item->availability)) ? trim($item->availability) : ''),
                        'brand' => (isset($product->brand)) ? trim($product->brand) : ((isset($item->brand)) ? trim($item->brand) : ''),
                        'product_type' => (isset($product->product_type)) ? trim($product->product_type) : ((isset($item->product_type)) ? trim($item->product_type) : ''),
                    ];

                    if ($productObj) {
                        $productObj->fill($productData);

                        $isModified = $productObj->isDirty();

                        $productObj->save();
                    } else {
                        $productObj = ProductModel::create($productData);

                        $isNew = true;
                    }

                    $imageLink = (isset($product->image_link)) ? trim($product->image_link) : ((isset($item->image_link)) ? trim($item->image_link) : '');
                    if ($imageLink) {
                        if (! $productObj->hasMedia('preview')) {
                            $tempFile = $tempPath.'/'.basename($imageLink);

                            $this->grabImage($imageLink, $tempFile);

                            try {
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
                            } catch (\Exception $error) {
                                $this->info(PHP_EOL.'Image error: '.$imageLink);
                            }
                        } else {
                            if (! $productObj->getFirstMedia('preview')->hasCustomProperty('processed')) {
                                $productObj->clearMediaCollection('preview');
                                continue;
                            }
                        }
                    }

                    $isLinksModified = false;
                    if (isset($product->link) || isset($item->link)) {
                        $productLink = (isset($product->link)) ? $product->link : ((isset($item->link)) ? $item->link : '');
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

                    $bar->advance();
                }

                $bar->finish();

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
            if (! $link) {
                continue;
            }

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

    private function utf8_for_xml($string)
    {
        return preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $string);
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
