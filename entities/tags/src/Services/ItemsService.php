<?php

namespace InetStudio\TagsPackage\Tags\Services;

use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;
use InetStudio\TagsPackage\Tags\Contracts\Services\ItemsServiceContract;

class ItemsService implements ItemsServiceContract
{
    public function __construct(
        protected TagModelContract $model
    ) {}

    public function getModel(): TagModelContract
    {
        return $this->model;
    }
}
