<?php

namespace InetStudio\ProductsPackage\Products\Contracts\Models;

use Spatie\MediaLibrary\HasMedia;
use OwenIt\Auditing\Contracts\Auditable;
use InetStudio\AdminPanel\Base\Contracts\Models\BaseModelContract;

interface ProductModelContract extends BaseModelContract, Auditable, HasMedia
{
}
