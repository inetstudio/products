<?php

namespace InetStudio\ProductsPackage\Products\Contracts\Http\Resources\Back\Data;

use ArrayAccess;
use JsonSerializable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\Routing\UrlRoutable;

interface ModalItemResourceContract extends ArrayAccess, JsonSerializable, Responsable, UrlRoutable
{
}
