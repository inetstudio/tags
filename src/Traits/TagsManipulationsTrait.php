<?php

namespace InetStudio\Tags\Traits;

use InetStudio\Tags\Models\TagModel;

trait TagsManipulationsTrait
{
    /**
     * Сохраняем теги.
     *
     * @param $item
     * @param $request
     */
    private function saveTags($item, $request)
    {
        if ($request->filled('tags')) {
            $item->syncTags(TagModel::whereIn('id', (array) $request->get('tags'))->get());
        } else {
            $item->detachTags($item->tags);
        }
    }
}
