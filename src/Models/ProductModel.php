<?php

namespace InetStudio\Products\Models;

use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Image\Exceptions\InvalidManipulation;
use Venturecraft\Revisionable\RevisionableTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use InetStudio\SimpleCounters\Models\Traits\HasSimpleCountersTrait;

/**
 * InetStudio\Products\Models\ProductModel
 *
 * @property string $availability
 * @property string $brand
 * @property string $condition
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property string|null $description
 * @property string $feed_hash
 * @property string $g_id
 * @property int $id
 * @property string $price
 * @property string $product_type
 * @property string $title
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\InetStudio\SimpleCounters\Models\SimpleCounterModel[] $counters
 * @property-read \Illuminate\Database\Eloquent\Collection|\InetStudio\Products\Models\ProductLinkModel[] $links
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Media[] $media
 * @property-read \Illuminate\Database\Eloquent\Collection|\InetStudio\Products\Models\ProductableModel[] $productables
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Products\Models\ProductModel onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductModel whereAvailability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductModel whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductModel whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductModel whereFeedHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductModel whereGId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductModel wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductModel whereProductType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductModel whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Products\Models\ProductModel withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Products\Models\ProductModel withoutTrashed()
 * @mixin \Eloquent
 */
class ProductModel extends Model implements HasMediaConversions
{
    use Searchable;
    use SoftDeletes;
    use HasMediaTrait;
    use RevisionableTrait;
    use HasSimpleCountersTrait;

    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'feed_hash', 'g_id', 'title', 'description', 'price', 'condition',
        'availability', 'brand', 'product_type',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $revisionCreationsEnabled = true;

    /**
     * Отношение "один ко многим" с моделью ссылок.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function links()
    {
        return $this->hasMany(ProductLinkModel::class, 'product_id', 'id');
    }

    /**
     * Отношение "один ко многим" с моделью "ссылок" на материалы.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productables()
    {
        return $this->hasMany(ProductableModel::class, 'product_model_id');
    }

    /**
     * Настройка полей для поиска.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $arr = array_only($this->toArray(), ['id', 'title', 'description', 'price', 'condition', 'availability', 'brand', 'product_type']);

        return $arr;
    }

    /**
     * Регистрируем преобразования изображений.
     *
     * @param Media|null $media
     *
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $quality = (config('products.images.quality')) ? config('products.images.quality') : 75;

        if (config('products.images.conversions')) {
            foreach (config('products.images.conversions') as $collection => $image) {
                foreach ($image as $crop) {
                    foreach ($crop as $conversion) {
                        $imageConversion = $this->addMediaConversion($conversion['name']);

                        if (isset($conversion['size']['width'])) {
                            $imageConversion->width($conversion['size']['width']);
                        }

                        if (isset($conversion['size']['height'])) {
                            $imageConversion->height($conversion['size']['height']);
                        }

                        if (isset($conversion['crop']['width']) && isset($conversion['crop']['height'])) {
                            $imageConversion->crop('crop-center', $conversion['crop']['width'], $conversion['crop']['height']);
                        }

                        if (isset($conversion['fit']['width']) && isset($conversion['fit']['height'])) {
                            $imageConversion->fit('fill', $conversion['fit']['width'], $conversion['fit']['height']);

                            if (isset($conversion['fit']['background'])) {
                                $imageConversion->background($conversion['fit']['background']);
                            }
                        }

                        if (isset($conversion['quality'])) {
                            $imageConversion->quality($conversion['quality']);
                            $imageConversion->optimize();
                        } else {
                            $imageConversion->quality($quality);
                        }

                        $imageConversion->performOnCollections($collection);
                    }
                }
            }
        }
    }
}
