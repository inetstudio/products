<?php

namespace InetStudio\Products\Events;

use Illuminate\Queue\SerializesModels;

/**
 * Class UpdateProductsEvent
 * @package InetStudio\Products\Events
 */
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