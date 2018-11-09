<?php

namespace InetStudio\Tags\Services\Front\Traits;

/**
 * Trait TagsServiceTrait.
 */
trait TagsServiceTrait
{
    /**
     * Получаем объекты по тегу.
     *
     * @param string $tagSlug
     * @param array $params
     *
     * @return mixed
     */
    public function getItemsByTag(string $tagSlug, array $params = [])
    {
        return $this->repository->getItemsByTag($tagSlug, $params);
    }
}
