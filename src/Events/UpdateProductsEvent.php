<?php

namespace InetStudio\Products\Events;

use Illuminate\Queue\SerializesModels;

class UpdateProductsEvent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * UpdateProductsEvent constructor.
     */
    public function __construct()
    {
    }
}
