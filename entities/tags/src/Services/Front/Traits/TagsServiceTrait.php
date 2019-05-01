<?php

namespace InetStudio\TagsPackage\Tags\Services\Front\Traits;

/**
 * Trait TagsServiceTrait.
 */
trait TagsServiceTrait
{
    /**
     * Получаем объекты по тегу.
     *
     * @param  string  $slug
     * @param  array  $params
     *
     * @return mixed
     */
    public function getItemsByTag(string $slug, array $params = [])
    {
        return $this->model
            ->buildQuery($params)
            ->withTags($slug);
    }
}
