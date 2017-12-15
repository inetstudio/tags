<?php

namespace InetStudio\Tags\Services;

use InetStudio\Tags\Models\TagModel;
use Illuminate\Support\Facades\Cache;

/**
 * Class TagsService
 * @package InetStudio\Tags\Services
 */
class TagsService
{
    /**
     * Получаем тег по slug.
     *
     * @param string $slug
     * @return TagModel
     */
    public function getTagBySlug(string $slug): TagModel
    {
        $cacheKey = 'TagsService_getTagBySlug_'.md5($slug);

        $tags =  Cache::tags(['tags'])->remember($cacheKey, 1440, function () use ($slug) {
            return TagModel::select(['id', 'title', 'slug', 'content'])
                ->with(['meta' => function ($query) {
                    $query->select(['metable_id', 'metable_type', 'key', 'value']);
                }, 'media' => function ($query) {
                    $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
                }])
                ->whereSlug($slug)
                ->get();
        });

        if ($tags->count() == 0) {
            abort(404);
        }

        return $tags->first();
    }
}
