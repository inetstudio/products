<?php

namespace InetStudio\ProductsPackage\ProductsItems\View\Components\Widgets\Front;

use Illuminate\View\Component;

class ProductsItemsList extends Component
{
    public string $type;

    public string $message;

    public function __construct($type, $message)
    {
        $this->type = $type;
        $this->message = $message;
    }

    public function render()
    {
        return view('components.alert');
    }
}
