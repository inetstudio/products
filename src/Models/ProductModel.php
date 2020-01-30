<?php

namespace InetStudio\Products\Models;

use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use InetStudio\Uploads\Models\Traits\HasImages;
use InetStudio\Products\Contracts\Models\ProductModelContract;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\AdminPanel\Base\Models\Traits\Scopes\BuildQueryScopeTrait;
use InetStudio\SimpleCounters\Counters\Models\Traits\HasSimpleCountersTrait;

class ProductModel extends Model implements ProductModelContract, HasMedia, Auditable
{
    use HasImages;
    use Searchable;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasSimpleCountersTrait;
    use BuildQueryScopeTrait;

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

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     *
     * @return mixed
     *
     * @throws BindingResolutionException
     */
    public function __call($method, $parameters) {
        $config = implode( '.', ['products.relationships', $method]);

        if (Config::has($config)) {
            $data = Config::get($config);

            $model = isset($data['model']) ? [app()->make($data['model'])] : [];
            $params = $data['params'] ?? [];

            return call_user_func_array([$this, $data['relationship']], array_merge($model, $params));
        }

        return parent::__call($method, $parameters);
    }

    /**
     * Get an attribute from the model.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        $config = implode( '.', ['products.relationships', $key]);

        if (Config::has($config)) {
            return $this->getRelationValue($key);
        }

        return parent::getAttribute($key);
    }

    /**
     * Get a relationship.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getRelationValue($key)
    {
        if ($this->relationLoaded($key)) {
            return $this->relations[$key];
        }

        $config = implode( '.', ['products.relationships', $key]);

        if (Config::has($config)) {
            return $this->getRelationshipFromMethod($key);
        }

        return parent::getRelationValue($key);
    }
}
