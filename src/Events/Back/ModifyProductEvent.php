<?php

namespace InetStudio\Products\Events\Back;

use Illuminate\Queue\SerializesModels;
use InetStudio\Products\Contracts\Events\Back\ModifyProductEventContract;

/**
 * Class ModifyProductEvent.
 */
class ModifyProductEvent implements ModifyProductEventContract
{
    use SerializesModels;

    public $object;

    /**
     * Create a new event instance.
     *
     * ModifyProductEvent constructor.
     *
     * @param $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }
}
