<?php

namespace InetStudio\Tags\Services\Back;

use InetStudio\Tags\Models\TagModel;
use InetStudio\Tags\Contracts\Services\Back\TagsServiceContract;

/**
 * Class TagsService.
 */
class TagsService implements TagsServiceContract
{
    public function attachToObject($request, $item)
    {
        if ($request->filled('tags')) {
            $item->syncTags(TagModel::whereIn('id', (array) $request->get('tags'))->get());
        } else {
            $item->detachTags($item->tags);
        }
    }
}
