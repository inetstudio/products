<?php

namespace InetStudio\ProductsPackage\Products\Models;

use Illuminate\Support\Arr;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use InetStudio\UploadsPackage\Uploads\Models\Traits\HasMedia;
use InetStudio\ProductsPackage\Links\Models\Traits\Links;
use InetStudio\ProductsPackage\Products\Models\ProductableModel;
use InetStudio\ProductsPackage\Products\Contracts\Models\ProductModelContract;

class ProductModel extends Model implements ProductModelContract
{
    use Links;
    use Auditable;
    use HasMedia;
    use Searchable;
    use SoftDeletes;

    const ENTITY_TYPE = 'product';

    protected bool $auditTimestamps = true;

    protected $table = 'products';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getTypeAttribute(): string
    {
        return self::ENTITY_TYPE;
    }

    public function productables()
    {
        return $this->hasMany(ProductableModel::class, 'product_model_id');
    }

    public function toSearchableArray()
    {
        return Arr::only($this->toArray(), ['id', 'title', 'description', 'price', 'condition', 'availability', 'brand', 'product_type']);
    }

    public function getMediaConfig(): array
    {
        return config('products.media', []);
    }
}
