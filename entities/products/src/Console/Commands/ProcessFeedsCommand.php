<?php

namespace InetStudio\ProductsPackage\Products\Console\Commands;

use Exception;
use SimpleXMLElement;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use GuzzleHttp\Exception\ClientException;
use InetStudio\ProductsPackage\Products\Contracts\Models\ProductModelContract;
use InetStudio\ProductsPackage\Products\Contracts\Console\Commands\ProcessFeedsCommandContract;
//use InetStudio\Classifiers\Groups\Contracts\Services\Back\ItemsServiceContract as GroupsServiceContract;
//use InetStudio\ProductsPackage\Links\Contracts\Services\Back\ItemsServiceContract as LinksServiceContract;
//use InetStudio\Classifiers\Entries\Contracts\Services\Back\ItemsServiceContract as EntriesServiceContract;
use InetStudio\ProductsPackage\Products\Contracts\Services\Back\ItemsServiceContract as ProductsServiceContract;

/**
 * Class ProcessFeeds.
 */
class ProcessFeedsCommand extends Command implements ProcessFeedsCommandContract
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:products-finder:products:feeds';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Process products feeds';

   // protected $linksService;

    /**
     * @var ProductsServiceContract
     */
    protected $productsService;

    //protected $classifiersGroupsService;

    //protected $classifiersEntriesService;

    /**
     * ProcessFeeds constructor.
     *
     * @param  ProductsServiceContract  $productsService
     * @param  GroupsServiceContract  $groupsService
     */
    public function __construct(
        //LinksServiceContract $linksService,
        ProductsServiceContract $productsService,
        //GroupsServiceContract $groupsService,
        //EntriesServiceContract $entriesService
    ) {
        parent::__construct();

        //$this->linksService = $linksService;
        $this->productsService = $productsService;
        //$this->classifiersGroupsService = $groupsService;
        //$this->classifiersEntriesService = $entriesService;
    }

    /**
     * Запуск команды.
     */
    public function handle(): void
    {
        $feeds = config('products_finder_products.feeds', []);

        foreach ($feeds as $productsFeed => $url) {
            $this->info(PHP_EOL.'Обработка фида: '.$productsFeed);

            $xml = $this->getFeedContent($url);

            if (! $xml) {
                continue;
            }

            $bar = $this->output->createProgressBar(count($xml->channel->item ?? []));

            $productsEANs = [];
            foreach ($xml->channel->item ?? [] as $item) {
                $productData = $this->getProductData($url, $item);
                $productObject = $this->getProduct($productData['feed_hash'], $productData['ean']);

                $this->updateProduct($item, $productObject, $productData);

                $productsEANs[] = $productData['ean'];

                $bar->advance();
            }

            $this->removeLinks($url, $productsEANs);

            $bar->finish();
        }
    }

    /**
     * Получаем фид в виде массива.
     *
     * @param  string  $url
     *
     * @return SimpleXMLElement|null
     */
    protected function getFeedContent(string $url): ?SimpleXMLElement
    {
        $client = new Client();

        try {
            $response = $client->get($url);
        } catch (ClientException $e) {
            $this->error('Фид недоступен: '.$url);

            return null;
        }

        $content = $response->getBody()->getContents();
        $content = $this->prepareFeedContent($content);

        $responseXml = simplexml_load_string($content);

        return ($responseXml) ? $responseXml : null;
    }

    /**
     * @param  string  $content
     *
     * @return string
     */
    protected function prepareFeedContent(string $content): string
    {
        return preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $content);
    }

    /**
     * Подготовка данных для продукта.
     *
     * @param  string  $url
     * @param  SimpleXMLElement  $item
     *
     * @return array
     */
    protected function getProductData(string $url, SimpleXMLElement $item): array
    {
        $productData = [
            'feed_hash' => md5($url),
            'ean' => trim($this->getNodeValue('id', $item)),
            'brand' => trim($this->getNodeValue('brand', $item)),
            'series' => trim($this->getNodeValue('series', $item)),
            'group_name' => trim($this->getNodeValue('products_group_name', $item)),
            'shade' => trim($this->getNodeValue('shade', $item)),
            'title' => trim($this->getNodeValue('title', $item)),
            'description' => trim($this->getNodeValue('description', $item)),
            'benefits' => trim($this->getNodeValue('benefits', $item)),
            'how_to_use' => trim($this->getNodeValue('how_to_use', $item)),
            'features' => trim($this->getNodeValue('composition_features', $item)),
            'volume' => trim($this->getNodeValue('volume', $item)),
        ];

        return $productData;
    }

    /**
     * Получаем продукт.
     *
     * @param  string  $feedHash
     * @param  string  $productId
     *
     * @return ProductModelContract|null
     */
    protected function getProduct(string $feedHash, string $productId): ?ProductModelContract
    {
        return $this->productsService->getModel()::query()
            ->where('feed_hash', $feedHash)
            ->where('ean', trim($productId))
            ->first();
    }

    /**
     * Обновляем продукт.
     *
     * @param  SimpleXMLElement  $item
     * @param $productObject
     * @param  array  $productData
     *
     * @return ProductModelContract
     */
    protected function updateProduct(SimpleXMLElement $item, $productObject, array $productData): ProductModelContract
    {
        if ($productObject && $productObject['update'] == 0) {
            return $productObject;
        }

        $productObject = $this->productsService->saveModel($productData, $productObject['id'] ?? 0);

        $this->attachMedia($productObject, $item);
        $this->attachLinks($productObject, $item);
        $this->attachRecommendations($productObject, $item);
        $this->attachClassifiers($productObject, $item);

        event(
            app()->makeWith(
                'InetStudio\ProductsPackage\Products\Contracts\Events\Back\ModifyItemEventContract', [
                    'item' => $productObject,
                ]
            )
        );

        return $productObject;
    }

    /**
     * Получаем значение элемента дерева.
     *
     * @param  string  $property
     * @param  SimpleXMLElement  $node
     *
     * @return mixed
     */
    protected function getNodeValue(string $property, SimpleXMLElement $node)
    {
        $gNode = $node->children('g', true);

        $items = [$node, $gNode];

        foreach ($items as $item) {
            if (isset($item->$property)) {
                return $item->$property;
            }
        }

        return '';
    }

    /**
     * Сохраняем изображение продукта.
     *
     * @param  ProductModelContract  $productObject
     * @param  SimpleXMLElement  $item
     *
     * @return Media|null
     */
    protected function attachMedia(ProductModelContract $productObject, SimpleXMLElement $item): ?Media
    {
        $imageLink = trim($this->getNodeValue('image_link', $item));

        if (! $imageLink || $productObject->hasMedia('preview')) {
            return null;
        }

        try {
            return $productObject->addMediaFromUrl($imageLink)
                ->withCustomProperties(['source' => $imageLink])
                ->toMediaCollection('preview', 'products_finder_products');
        } catch (Exception $error) {
            $this->info(PHP_EOL.'Image error: '.$imageLink);

            return null;
        }
    }

    /**
     * Сохраняем ссылки.
     *
     * @param  ProductModelContract  $productObject
     * @param  SimpleXMLElement  $item
     */
    protected function attachLinks(ProductModelContract $productObject, SimpleXMLElement $item): void
    {
        $hrefArr = [];

        $link = $this->getNodeValue('link', $item);
        $links = $this->getNodeValue('links', $item);
        $videoLinks = $this->getNodeValue('video_link', $item);

        $hrefArr['shop'][] = trim($link);

        foreach ($links->link ?? [] as $link) {
            $hrefArr['shop'][] = trim($link->href);
        }

        if ((string) $videoLinks) {
            $hrefArr['video'] = explode(',', $videoLinks);
        }

        $hrefArr['shop'] = array_filter($hrefArr['shop']);

        $this->linksService->getModel()::where('product_id', $productObject['id'])
            ->whereNotIn('href', collect($hrefArr)->flatten()->toArray())
            ->delete();

        foreach ($hrefArr as $hrefsType => $hrefs) {
            foreach ($hrefs as $href) {
                $linkObject = $this->linksService->getModel()::where('product_id', $productObject['id'])
                    ->where('href', $href)
                    ->first();

                $linkData = [
                    'type' => $hrefsType,
                    'product_id' => $productObject['id'],
                    'href' => $href,
                ];

                $this->linksService->save($linkData, $linkObject->id ?? 0);
            }
        }
    }

    /**
     * Сохраняем рекомендации.
     *
     * @param  ProductModelContract  $productObject
     * @param  SimpleXMLElement  $item
     */
    protected function attachRecommendations(ProductModelContract $productObject, SimpleXMLElement $item): void
    {
        $recommendationsNodes = $item->recommendations;

        $recommendations = [
            $recommendationsNodes->recommendation1,
            $recommendationsNodes->recommendation2,
            $recommendationsNodes->recommendation3,
        ];

        $recommendationsIDs = [];

        foreach ($recommendations as $recommendation) {
            $ean = (string) $recommendation;

            if ($ean) {
                $recommendationObject = $this->productsService->getModel()::where('ean', $ean)->first();

                if ($recommendationObject) {
                    $recommendationsIDs[] = $recommendationObject->id;
                }
            }
        }

        $productObject->recommendations()->sync($recommendationsIDs);
    }

    /**
     * Сохраняем классификаторы.
     *
     * @param  ProductModelContract  $productObject
     * @param  SimpleXMLElement  $item
     */
    protected function attachClassifiers(ProductModelContract $productObject, SimpleXMLElement $item): void
    {
        $groups = [
            'scope_of_use' => 'products_finder_scopes_of_use',
            'type' => 'products_finder_types',
            'age' => 'products_finder_age',
            'sex' => 'products_finder_sex',
            'country' => 'products_finder_countries',
            'skin_type' => 'products_finder_skin_types',
            'color' => 'products_finder_colors',
            'texture' => 'products_finder_textures',
            'spf' => 'products_finder_spf',
            'face_application' => 'products_finder_face_applications',
            'hair_application' => 'products_finder_hair_applications',
        ];

        $classifiersIDs = [];
        foreach ($groups as $field => $groupAlias) {
            $value = trim($this->getNodeValue($field, $item));
            $value = str_replace([',', '/', ';', '+'], '##', $value);

            $values = explode('##', $value);
            $values = array_unique(array_filter($values));

            $group = $this->classifiersGroupsService->getModel()::where('alias', '=', $groupAlias)->first();

            $entriesIDs = [];
            foreach ($values as $entryValue) {
                $alias = 'products_finder_'.$field.'_'.md5(Str::ucfirst(trim($entryValue)));

                $entry = $this->classifiersEntriesService->getModel()::updateOrCreate(
                    [
                        'alias' => $alias,
                    ],
                    [
                        'value' => Str::ucfirst(trim($entryValue)),
                    ]
                );

                $entriesIDs[] = $entry->id;
                $classifiersIDs[] = $entry->id;
            }

            $currentEntriesIDs = $group->entries()
                ->pluck('classifiers_entries.id')
                ->toArray();

            $entriesIDs = array_unique(array_merge($entriesIDs, $currentEntriesIDs));
            $group->entries()->sync($entriesIDs);
        }

        $productObject->syncClassifiers($classifiersIDs);
    }

    /**
     * Удаляем ссылки.
     *
     * @param  string  $url
     * @param  array  $productsEANs
     */
    protected function removeLinks(string $url, array $productsEANs): void
    {
        $ids = $this->productsService->getModel()::where('feed_hash', md5($url))
            ->whereNotIn('ean', $productsEANs)
            ->pluck('id')
            ->toArray();

        $this->linksService->getModel()::whereIn('product_id', $ids)
            ->where('type', 'shop')
            ->delete();
    }
}
