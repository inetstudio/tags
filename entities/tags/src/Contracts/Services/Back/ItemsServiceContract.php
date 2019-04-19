<?php

namespace InetStudio\TagsPackage\Tags\Contracts\Services\Back;

use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;
use InetStudio\AdminPanel\Base\Contracts\Services\BaseServiceContract;

/**
 * Interface ItemsServiceContract.
 */
interface ItemsServiceContract extends BaseServiceContract
{
    /**
     * Сохраняем модель.
     *
     * @param  array  $data
     * @param  int  $id
     *
     * @return TagModelContract
     */
    public function save(array $data, int $id): TagModelContract;

    /**
     * Присваиваем теги объекту.
     *
     * @param $tags
     * @param $item
     */
    public function attachToObject($tags, $item): void;
}
