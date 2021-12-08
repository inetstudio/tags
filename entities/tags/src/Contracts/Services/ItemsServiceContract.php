<?php

namespace InetStudio\TagsPackage\Tags\Contracts\Services;

use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;

interface ItemsServiceContract
{
    public function getModel(): TagModelContract;
}
