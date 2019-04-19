<?php

namespace InetStudio\TagsPackage\Tags\Contracts\Services\Front;

use Illuminate\Support\Collection;
use InetStudio\AdminPanel\Base\Contracts\Services\BaseServiceContract;

/**
 * Interface ItemsServiceContract.
 */
interface ItemsServiceContract extends BaseServiceContract
{
    /**
     * Возвращаем объекты, привязанные к материалам.
     *
     * @param  Collection  $materials
     *
     * @return Collection
     */
    public function getItemsByMaterials(Collection $materials): Collection;
}
