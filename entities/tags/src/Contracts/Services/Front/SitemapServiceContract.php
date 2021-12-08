<?php

namespace InetStudio\TagsPackage\Tags\Contracts\Services\Front;

use InetStudio\AdminPanel\Base\Contracts\Services\BaseServiceContract;

interface SitemapServiceContract extends BaseServiceContract
{
    public function getItems(): array;
}
