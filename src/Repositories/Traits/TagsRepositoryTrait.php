<?php

namespace InetStudio\Tags\Repositories\Traits;

/**
 * Trait TagsRepositoryTrait.
 */
trait TagsRepositoryTrait
{
    /**
     * Получаем объекты по категории.
     *
     * @param string $slug
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getItemsByTag(string $slug, bool $returnBuilder = false)
    {
        $builder = $this->model::select(['id', 'title', 'description', 'slug'])
            ->with(['meta' => function ($query) {
                $query->select(['metable_id', 'metable_type', 'key', 'value']);
            }, 'media' => function ($query) {
                $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
            }])
            ->withTags($slug);

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
    }
}
