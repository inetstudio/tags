<?php

namespace InetStudio\TagsPackage\Tags\Services\Front\Traits;

use Illuminate\Support\Collection;

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
     * @return Collection
     */
    public function getItemsByTag(string $slug, array $params = []): Collection
    {
        return $this->model->buildQuery($params)->withTags($slug)->get();
    }
}
