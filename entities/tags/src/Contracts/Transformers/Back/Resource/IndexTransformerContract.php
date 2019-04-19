<?php

namespace InetStudio\TagsPackage\Tags\Contracts\Transformers\Back\Resource;

use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;

/**
 * Interface IndexTransformerContract.
 */
interface IndexTransformerContract
{
    /**
     * Трансформация данных.
     *
     * @param  TagModelContract  $item
     *
     * @return array
     */
    public function transform(TagModelContract $item): array;
}
