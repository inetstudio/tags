<?php

namespace InetStudio\TagsPackage\Tags\Contracts\Transformers\Front\Sitemap;

use League\Fractal\Resource\Collection as FractalCollection;
use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;

/**
 * Interface ItemTransformerContract.
 */
interface ItemTransformerContract
{
    /**
     * Трансформация данных.
     *
     * @param  TagModelContract  $item
     *
     * @return array
     */
    public function transform(TagModelContract $item): array;

    /**
     * Обработка коллекции объектов.
     *
     * @param $items
     *
     * @return FractalCollection
     */
    public function transformCollection($items): FractalCollection;
}
