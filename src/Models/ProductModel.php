<?php

namespace InetStudio\Products\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use InetStudio\Uploads\Models\Traits\HasImages;
use Venturecraft\Revisionable\RevisionableTrait;
use InetStudio\Products\Contracts\Models\ProductModelContract;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use InetStudio\SimpleCounters\Models\Traits\HasSimpleCountersTrait;
use InetStudio\Favorites\Contracts\Models\Traits\FavoritableContract;

class ProductModel extends Model implements ProductModelContract, HasMediaConversions, FavoritableContract
{
    use HasImages;
    use Searchable;
    use SoftDeletes;
    use RevisionableTrait;
    use HasSimpleCountersTrait;
    use \InetStudio\Favorites\Models\Traits\Favoritable;

    protected $images = [
        'config' => 'products',
        'model' => 'product',
    ];

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
}
