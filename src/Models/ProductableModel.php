<?php

namespace InetStudio\Products\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * InetStudio\Products\Models\ProductableModel.
 *
 * @property \Carbon\Carbon|null $created_at
 * @property int $product_model_id
 * @property int $productable_id
 * @property string $productable_type
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \InetStudio\Products\Models\ProductModel $product
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $productable
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductableModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductableModel whereProductModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductableModel whereProductableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductableModel whereProductableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductableModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductableModel extends Model
{
    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'productables';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'product_model_id',
        'productable_id',
        'productable_type',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Обратное отношение "один ко многим" с моделью продукта.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_model_id');
    }

    /**
     * Полиморфное отношение с остальными моделями.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function productable()
    {
        return $this->morphTo();
    }
}
