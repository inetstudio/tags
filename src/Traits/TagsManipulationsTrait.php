<?php

use InetStudio\Tags\Models\TagModel;

trait TagsManipulationsTrait
{
    /**
     * @param $item
     * @param $request
     */
    private function saveTags($item, $request)
    {
        if ($request->has('tags')) {
            $item->syncTags(TagModel::whereIn('id', (array) $request->get('tags'))->get());
        } else {
            $item->detachTags($item->tags);
        }
    }
}
