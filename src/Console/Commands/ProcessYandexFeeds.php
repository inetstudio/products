<?php

namespace InetStudio\Products\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use InetStudio\Products\Models\ProductModel;
use InetStudio\Products\Models\ProductLinkModel;
use InetStudio\Products\Events\UpdateProductsEvent;
use InetStudio\AdminPanel\Events\Images\UpdateImageEvent;

/**
 * Class ProcessYandexFeeds
 * @package InetStudio\Products\Console\Commands
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
     *
     * @return void
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
                } finally {
                    $xml = simplexml_load_string($contents);

                    $products = [];

                    foreach ($xml->shop->offers->offer as $product) {
                        $products[] = trim($product->vendorCode);

                        $deleteProduct = ProductModel::onlyTrashed()->where('feed_hash', $feedHash)->where('g_id', trim($product->vendorCode))->first();
                        if ($deleteProduct) {
                            $deleteProduct->restore();
                        }

                        $productObj = ProductModel::updateOrCreate([
                            'feed_hash' => $feedHash,
                            'g_id' => trim($product->vendorCode),
                        ], [
                            'title' => isset($product->model) ? trim($product->model) : '',
                            'description' => isset($product->description) ? trim($product->description) : '',
                            'brand' => isset($product->vendor) ? trim($product->vendor) : '',
                        ]);

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

                                event(new UpdateImageEvent($productObj, 'preview'));
                            } else {
                                if (! $productObj->getFirstMedia('preview')->hasCustomProperty('processed')) {
                                    $productObj->clearMediaCollection('preview');
                                    continue;
                                }
                            }
                        }

                        if (isset($product->url)) {
                            $productLink = isset($product->url) ? $product->url : '';
                            if ($productLink) {
                                ProductLinkModel::updateOrCreate([
                                    'product_id' => $productObj->id,
                                ], [
                                    'link' => trim($productLink),
                                ]);
                            }
                        } elseif (isset($product->links)) {
                            ProductLinkModel::where('product_id', $productObj->id)->forceDelete();

                            foreach ($product->links->link as $link) {
                                ProductLinkModel::create([
                                    'product_id' => $productObj->id,
                                    'link' => trim($link->href),
                                ]);
                            }
                        }
                    }

                    ProductModel::where('feed_hash', $feedHash)->whereNotIn('g_id', $products)->delete();
                }
            }

            event(new UpdateProductsEvent());
        }
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
