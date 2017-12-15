<?php

namespace InetStudio\Tags\Listeners;

use Illuminate\Support\Facades\Cache;

/**
 * Class ClearTagsCacheListener
 * @package InetStudio\Tags\Listeners
 */
class ClearTagsCacheListener
{
    /**
     * ClearTagsCacheListener constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Обработка события.
     *
     * @param $event
     */
    public function handle($event): void
    {
        $object = $event->object;

        Cache::tags(['tags'])->forget('TagsService_getTagBySlug_'.md5($object->slug));
        Cache::tags(['materials'])->flush();
    }
}
