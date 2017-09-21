<?php

namespace InetStudio\Products\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель "ссылки" продукт-материал
 *
 * Class Productable
 * @package App\Models
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
     * Обратное отношение "один ко многим" с моделью продукта
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_model_id');
    }

    /**
     * Получаем модель, определенную в поле productable_type с id = productable_id
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function item()
    {
        return $this->productable();
    }

    /**
     * Полиморфное отношение с остальными моделями
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function productable()
    {
        return $this->morphTo();
    }
}
