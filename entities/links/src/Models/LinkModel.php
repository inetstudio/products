<?php

namespace InetStudio\ProductsPackage\Links\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use InetStudio\ProductsPackage\Products\Models\Traits\Product;
use InetStudio\ProductsPackage\Links\Database\Factories\LinkFactory;
use InetStudio\ProductsPackage\Links\Contracts\Models\LinkModelContract;

class LinkModel extends Model implements LinkModelContract
{
    use Product;
    use HasFactory;

    const ENTITY_TYPE = 'product_link';

    protected $table = 'products_links';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function getShopClassAttribute(): string
    {
        $href = $this->getAttribute('href');

        if (! is_string($href)) {
            return 'default';
        }

        $url = parse_url($href);

        if (! isset($url['host'])) {
            return 'default';
        }

        if (! preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $url['host'], $regs)) {
            return 'default';
        }

        return strtok($regs['domain'], '.');
    }

    public function getTypeAttribute(): string
    {
        return self::ENTITY_TYPE;
    }

    protected static function newFactory()
    {
        return LinkFactory::new();
    }
}
