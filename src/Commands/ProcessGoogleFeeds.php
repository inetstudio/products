<?php

namespace InetStudio\Products\Commands;

use Illuminate\Console\Command;
use Sabre\Xml\Reader as XMLReader;
use Sabre\Xml\Service as XMLService;
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
        if (config('products.feeds.google')) {
            foreach (config('products.feeds.google') as $url) {
                $feedHash = md5($url);
                $xml = file_get_contents($url);

                $feed = new XMLService();
                $feed->elementMap = [
                    '{}item' => function(XMLReader $reader) {
                        return \Sabre\Xml\Deserializer\keyValue($reader, 'http://base.google.com/ns/1.0');
                    },
                    '{}channel' => function(XMLReader $reader) {
                        return \Sabre\Xml\Deserializer\repeatingElements($reader, '{}item');
                    },
                ];

                $result = $feed->parse($xml);

                $products = [];
                foreach ($result[0]['value'] as $product) {
                    $products[] = $product['id'];

                    $productObj = ProductModel::updateOrCreate([
                        'feed_hash' => $feedHash,
                        'g_id' => $product['id'],
                    ], [
                        'title' => (isset($product['title'])) ? $product['title'] : ((isset($product['{}title'])) ? $product['{}title'] : ''),
                        'description' => (isset($product['description'])) ? $product['description'] : ((isset($product['{}description'])) ? $product['{}description'] : ''),
                        'price' => (isset($product['price'])) ? $product['price'] : ((isset($product['{}price'])) ? $product['{}price'] : ''),
                        'condition' => (isset($product['condition'])) ? $product['condition'] : ((isset($product['{}condition'])) ? $product['{}condition'] : ''),
                        'availability' => (isset($product['availability'])) ? $product['availability'] : ((isset($product['{}availability'])) ? $product['{}availability'] : ''),
                        'brand' => (isset($product['brand'])) ? $product['brand'] : ((isset($product['{}brand'])) ? $product['{}brand'] : ''),
                        'product_type' => (isset($product['product_type'])) ? $product['product_type'] : ((isset($product['{}product_type'])) ? $product['{}product_type'] : ''),
                    ]);

                    $imageLink = (isset($product['image_link'])) ? $product['image_link'] : ((isset($product['{}image_link'])) ? $product['{}image_link'] : '');
                    if ($imageLink) {
                        if (! $productObj->hasMedia('preview')) {
                            $img = $this->checkImage($imageLink);
                            if ($img) {
                                $productObj->addMediaFromBase64($img)->toMediaCollection('preview', 'products');
                            }
                        }
                    }

                    $productLink = (isset($product['link'])) ? $product['link'] : ((isset($product['{}link'])) ? $product['{}link'] : '');
                    if ($productLink) {
                        ProductLinkModel::updateOrCreate([
                            'product_id' => $productObj->id,
                        ], [
                            'link' => $productLink,
                        ]);
                    }
                }

                ProductModel::where('feed_hash', $feedHash)->whereNotIn('g_id', $products)->forceDelete();
            }
        }
    }

    private function checkImage($image_file)
    {
        $img = file_get_contents($image_file);

        $ext = strtolower(pathinfo($image_file, PATHINFO_EXTENSION));
        if ($ext === 'jpg') {
            $ext = 'jpeg';
        }
        $function = 'imagecreatefrom' . $ext;
        if (function_exists($function) && @$function($img) === FALSE) {
            return false;
        }

        return base64_encode($img);
    }
}
