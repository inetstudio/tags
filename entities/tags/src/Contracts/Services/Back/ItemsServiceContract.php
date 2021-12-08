<?php

namespace InetStudio\TagsPackage\Tags\Contracts\Services\Back;

use InetStudio\TagsPackage\Tags\Contracts\Services\ItemsServiceContract as BaseItemsServiceContract;

interface ItemsServiceContract extends BaseItemsServiceContract
{
    public function attachToObject($tags, $item): void;
}
