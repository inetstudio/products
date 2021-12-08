<?php

namespace InetStudio\ProductsPackage\Products\Events\Back;

use Illuminate\Queue\SerializesModels;
use InetStudio\ProductsPackage\Products\Contracts\Models\ProductModelContract;
use InetStudio\ProductsPackage\Products\Contracts\Events\Back\ModifyItemEventContract;

class ModifyItemEvent implements ModifyItemEventContract
{
    use SerializesModels;

    public function __construct(
        public ProductModelContract $item
    ) {}
}
