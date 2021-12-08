<?php

namespace InetStudio\ProductsPackage\ProductsItems\Contracts\Models;

use Spatie\MediaLibrary\HasMedia;
use OwenIt\Auditing\Contracts\Auditable;

interface ProductItemModelContract extends HasMedia, Auditable
{
}
