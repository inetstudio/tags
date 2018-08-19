<?php

namespace InetStudio\Tags\Repositories\Traits;

/**
 * Trait TagsRepositoryTrait.
 */
trait TagsRepositoryTrait
{
    /**
     * Получаем объекты по тегу.
     *
     * @param string $slug
     * @param array $params
     *
     * @return mixed
     */
    public function getItemsByTag(string $slug, array $params = [])
    {
        $builder = $this->getItemsQuery($params)
            ->withTags($slug);

        return $builder->get();
    }
}
