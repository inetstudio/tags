<?php

namespace InetStudio\TagsPackage\Tags\Contracts\Transformers\Front\Sitemap;

use League\Fractal\Resource\Collection as FractalCollection;
use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;

interface ItemTransformerContract
{
    public function transform(TagModelContract $item): array;

    public function transformCollection($items): FractalCollection;
}
