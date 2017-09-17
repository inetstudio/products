<?php

namespace InetStudio\Products\Commands;

use Illuminate\Console\Command;
use Sabre\Xml\Reader as XMLReader;
use Sabre\Xml\Service as XMLService;
use Illuminate\Support\Facades\Storage;
use InetStudio\Products\Models\ProductModel;
use InetStudio\Products\Models\ProductLinkModel;

class ProcessGoogleFeeds extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'inetstudio:products:feeds:google';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process google feeds';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $tempPath = Storage::disk('temp')->getDriver()->getAdapter()->getPathPrefix();

        if (config('products.feeds.google')) {
            foreach (config('products.feeds.google') as $url) {
                $feedHash = md5($url);
                $xml = file_get_contents($url);

                $feed = new XMLService();
                $feed->elementMap = [
                    '{}channel' => function(XMLReader $reader) {
                        return \Sabre\Xml\Deserializer\repeatingElements($reader, '{}item');
                    },
                    '{}item' => function(XMLReader $reader) {
                        return \Sabre\Xml\Deserializer\keyValue($reader, 'http://base.google.com/ns/1.0');
                    },
                    '{}links' => function(XMLReader $reader) {
                        return \Sabre\Xml\Deserializer\repeatingElements($reader, '{}link');
                    },
                    '{http://base.google.com/ns/1.0}link' => function(XMLReader $reader) {
                        return \Sabre\Xml\Deserializer\keyValue($reader, 'http://base.google.com/ns/1.0');
                    },
                ];

                $result = $feed->parse($xml);

                $products = [];

                foreach ($result[0]['value'] as $product) {
                    $products[] = trim($product['id']);

                    $productObj = ProductModel::updateOrCreate([
                        'feed_hash' => $feedHash,
                        'g_id' => trim($product['id']),
                    ], [
                        'title' => (isset($product['title'])) ? trim($product['title']) : ((isset($product['{}title'])) ? trim($product['{}title']) : ''),
                        'description' => (isset($product['description'])) ? trim($product['description']) : ((isset($product['{}description'])) ? trim($product['{}description']) : ''),
                        'price' => (isset($product['price'])) ? trim($product['price']) : ((isset($product['{}price'])) ? trim($product['{}price']) : ''),
                        'condition' => (isset($product['condition'])) ? trim($product['condition']) : ((isset($product['{}condition'])) ? trim($product['{}condition']) : ''),
                        'availability' => (isset($product['availability'])) ? trim($product['availability']) : ((isset($product['{}availability'])) ? trim($product['{}availability']) : ''),
                        'brand' => (isset($product['brand'])) ? trim($product['brand']) : ((isset($product['{}brand'])) ? trim($product['{}brand']) : ''),
                        'product_type' => (isset($product['product_type'])) ? trim($product['product_type']) : ((isset($product['{}product_type'])) ? trim($product['{}product_type']) : ''),
                    ]);

                    $imageLink = (isset($product['image_link'])) ? trim($product['image_link']) : ((isset($product['{}image_link'])) ? trim($product['{}image_link']) : '');
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
                        } else {
                            if (! $productObj->getFirstMedia('preview')->hasCustomProperty('processed')) {
                                $productObj->clearMediaCollection('preview');
                                continue;
                            }
                        }
                    }

                    if (isset($product['link']) or isset($product['{}link'])) {
                        $productLink = (isset($product['link'])) ? $product['link'] : ((isset($product['{}link'])) ? $product['{}link'] : '');
                        if ($productLink) {
                            ProductLinkModel::updateOrCreate([
                                'product_id' => $productObj->id,
                            ], [
                                'link' => trim($productLink),
                            ]);
                        }
                    } elseif (isset($product['links'])) {
                        ProductLinkModel::where('product_id', $productObj->id)->delete();

                        foreach ($product['links'] as $link) {
                            ProductLinkModel::create([
                                'product_id' => $productObj->id,
                                'link' => trim($link['value']['href']),
                            ]);
                        }
                    }
                }

                ProductModel::where('feed_hash', $feedHash)->whereNotIn('g_id', $products)->forceDelete();
            }
        }
    }

    private function grabImage($url, $saveto)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        $raw = curl_exec($ch);
        curl_close($ch);

        if (file_exists($saveto)) {
            unlink($saveto);
        }

        $fp = fopen($saveto, 'x');
        fwrite($fp, $raw);
        fclose($fp);
    }
}
