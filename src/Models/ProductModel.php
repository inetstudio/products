<?php

namespace InetStudio\Products\Models;

use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use InetStudio\Uploads\Models\Traits\HasImages;
use InetStudio\Products\Contracts\Models\ProductModelContract;
use InetStudio\AdminPanel\Base\Models\Traits\HasDynamicRelations;
use InetStudio\AdminPanel\Base\Models\Traits\Scopes\BuildQueryScopeTrait;
use InetStudio\SimpleCounters\Counters\Models\Traits\HasSimpleCountersTrait;

class ProductModel extends Model implements ProductModelContract, HasMedia, Auditable
{
    use HasImages;
    use Searchable;
    use SoftDeletes;
    use HasDynamicRelations;
    use BuildQueryScopeTrait;
    use HasSimpleCountersTrait;
    use \OwenIt\Auditing\Auditable;

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
        'availability', 'brand', 'product_type', 'update',
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

    /**
     * Should the timestamps be audited?
     *
     * @var bool
     */
    protected $auditTimestamps = true;

    /**
     * Загрузка модели.
     */
    protected static function boot()
    {
        parent::boot();

        self::$buildQueryScopeDefaults['columns'] = [
            'id', 'title', 'brand',
        ];

        self::$buildQueryScopeDefaults['relations'] = [
            'media' => function ($query) {
                $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
            },

            'links' => function ($linksQuery) {
                $linksQuery->select(['id', 'product_id', 'link']);
            },
        ];
    }

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
        $arr = Arr::only($this->toArray(), ['id', 'title', 'description', 'price', 'condition', 'availability', 'brand', 'product_type']);

        return $arr;
    }
}
