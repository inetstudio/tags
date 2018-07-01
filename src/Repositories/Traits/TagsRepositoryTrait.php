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
     * @param array $extColumns
     * @param array $with
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getItemsByTag(string $slug, array $extColumns = [], array $with = [], bool $returnBuilder = false)
    {
        $builder = $this->getItemsQuery($extColumns, $with)->withTags($slug);

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
    }
}
