<?php

namespace InetStudio\TagsPackage\Tags\Contracts\Services\Front;

use Illuminate\Support\Collection;
use InetStudio\TagsPackage\Tags\Contracts\Services\ItemsServiceContract as BaseItemsServiceContract;

interface ItemsServiceContract extends BaseItemsServiceContract
{
    public function getItemsByMaterials(Collection $materials): Collection;
}
