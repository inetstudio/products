<?php

namespace InetStudio\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class ProductModel extends Model implements HasMediaConversions
{
    use SoftDeletes;
    use HasMediaTrait;
    use RevisionableTrait;

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

    public function links()
    {
        return $this->hasMany(ProductLinkModel::class);
    }

    public function registerMediaConversions()
    {
        $quality = (config('products.images.quality')) ? config('products.images.quality') : 75;

        if (config('products.images.sizes.product')) {
            foreach (config('products.images.sizes.product') as $name => $size) {
                $this->addMediaConversion($name.'_thumb')
                    ->crop('crop-center', $size['width'], $size['height'])
                    //->quality($quality)
                    ->performOnCollections('preview');
            }
        }
    }
}
