<?php

namespace InetStudio\ProductsPackage\ProductsItems\Models;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use InetStudio\UploadsPackage\Uploads\Models\Traits\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use InetStudio\WidgetsPackage\Widgets\Models\Traits\HasWidgets;
use InetStudio\ProductsPackage\ProductsItems\Database\Factories\ProductItemFactory;
use InetStudio\ProductsPackage\ProductsItems\Contracts\Models\ProductItemModelContract;

class ProductItemModel extends Model implements ProductItemModelContract
{
    use Auditable;
    use HasMedia;
    use HasFactory;
    use HasWidgets;
    use SoftDeletes;

    const ENTITY_TYPE = 'product_item';

    protected $table = 'products_items';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected bool $auditTimestamps = true;

    public function getTypeAttribute(): string
    {
        return self::ENTITY_TYPE;
    }

    protected static function newFactory()
    {
        return ProductItemFactory::new();
    }

    public function getMediaConfig(): array
    {
        return config('products_package_products_items.media', []);
    }
}
