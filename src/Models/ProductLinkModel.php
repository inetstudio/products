<?php

namespace InetStudio\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * InetStudio\Products\Models\ProductLinkModel
 *
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property int $id
 * @property string $link
 * @property int $product_id
 * @property \Carbon\Carbon|null $updated_at
 * @property-read string $shop_class
 * @property-read \InetStudio\Products\Models\ProductModel $product
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Products\Models\ProductLinkModel onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductLinkModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductLinkModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductLinkModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductLinkModel whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductLinkModel whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Products\Models\ProductLinkModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Products\Models\ProductLinkModel withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Products\Models\ProductLinkModel withoutTrashed()
 * @mixin \Eloquent
 */
class ProductLinkModel extends Model
{
    use SoftDeletes;

    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'products_links';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'link',
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
     * Обратное отношение "один ко многим" с моделью продукта.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(ProductModel::class);
    }

    /**
     * Получаем домен ссылки.
     *
     * @return string
     */
    public function getShopClassAttribute()
    {
        $url = parse_url($this->link);

        if (isset($url['host'])) {
            if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $url['host'], $regs)) {
                return strtok($regs['domain'], '.');
            }
        }

        return 'default';
    }
}
