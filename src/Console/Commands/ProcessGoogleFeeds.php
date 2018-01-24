<?php

namespace InetStudio\Products\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use InetStudio\Products\Models\ProductModel;
use InetStudio\Products\Models\ProductLinkModel;
use InetStudio\Products\Events\UpdateProductsEvent;
use InetStudio\AdminPanel\Events\Images\UpdateImageEvent;

/**
 * Class ProcessGoogleFeeds
 * @package InetStudio\Products\Console\Commands
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
     *
     * @return void
     */
    public function handle(): void
    {
        $tempPath = Storage::disk('temp')->getDriver()->getAdapter()->getPathPrefix();

        if (config('products.feeds.google')) {
            foreach (config('products.feeds.google') as $productsBrand => $url) {
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

                    foreach ($xml->channel->item as $item) {
                        $product = $item->children('g', true);

                        $products[] = trim($product->id);

                        $deleteProduct = ProductModel::onlyTrashed()->where('feed_hash', $feedHash)->where('g_id', trim($product->id))->first();
                        if ($deleteProduct) {
                            $deleteProduct->restore();
                        }

                        $productObj = ProductModel::updateOrCreate([
                            'feed_hash' => $feedHash,
                            'g_id' => trim($product->id),
                        ], [
                            'title' => (isset($product->title)) ? trim($product->title) : ((isset($item->title)) ? trim($item->title) : ''),
                            'description' => (isset($product->description)) ? trim($product->description) : ((isset($item->description)) ? trim($item->description) : ''),
                            'price' => (isset($product->price)) ? trim($product->price) : ((isset($item->price)) ? trim($item->price) : ''),
                            'condition' => (isset($product->condition)) ? trim($product->condition) : ((isset($item->condition)) ? trim($item->condition) : ''),
                            'availability' => (isset($product->availability)) ? trim($product->availability) : ((isset($item->availability)) ? trim($item->availability) : ''),
                            'brand' => (isset($product->brand)) ? trim($product->brand) : ((isset($item->brand)) ? trim($item->brand) : ''),
                            'product_type' => (isset($product->product_type)) ? trim($product->product_type) : ((isset($item->product_type)) ? trim($item->product_type) : ''),
                        ]);

                        $imageLink = (isset($product->image_link)) ? trim($product->image_link) : ((isset($item->image_link)) ? trim($item->image_link) : '');
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

                        if (isset($product->link) or isset($item->link)) {
                            $productLink = (isset($product->link)) ? $product->link : ((isset($item->link)) ? $item->link : '');
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
