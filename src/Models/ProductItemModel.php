<?php

namespace InetStudio\Products\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use InetStudio\Uploads\Models\Traits\HasImages;
use InetStudio\Products\Contracts\Models\ProductItemModelContract;

/**
 * Class ProductItemModel.
 */
class ProductItemModel extends Model implements ProductItemModelContract, HasMedia, Auditable
{
    use HasImages;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $images = [
        'config' => 'products',
        'model' => 'product_item',
    ];

    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'products_items';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content'
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
     * Сеттер атрибута title.
     *
     * @param $value
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = trim(strip_tags($value));
    }

    /**
     * Сеттер атрибута content.
     *
     * @param $value
     */
    public function setContentAttribute($value)
    {
        $this->attributes['content'] = trim(str_replace("&nbsp;", ' ', (isset($value['text'])) ? $value['text'] : (! is_array($value) ? $value : '')));
    }
}
