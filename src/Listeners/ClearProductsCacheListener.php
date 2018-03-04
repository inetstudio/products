<?php

namespace InetStudio\Products\Listeners;

use Illuminate\Support\Facades\Cache;

/**
 * Class ClearProductsCacheListener.
 */
class ClearProductsCacheListener
{
    /**
     * ClearProductsCacheListener constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param $event
     */
    public function handle($event): void
    {
        Cache::tags(['products'])->flush();
        Cache::tags(['materials'])->flush();
    }
}
