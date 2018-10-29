<?php

namespace InetStudio\Products\Events\Back;

use Illuminate\Queue\SerializesModels;
use InetStudio\Products\Contracts\Events\Back\ModifyProductItemEventContract;

/**
 * Class ModifyProductItemEvent.
 */
class ModifyProductItemEvent implements ModifyProductItemEventContract
{
    use SerializesModels;

    public $object;

    /**
     * Create a new event instance.
     *
     * ModifyProductItemEvent constructor.
     *
     * @param $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }
}
