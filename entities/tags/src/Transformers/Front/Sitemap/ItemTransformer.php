<?php

namespace InetStudio\TagsPackage\Tags\Transformers\Front\Sitemap;

use Throwable;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection as FractalCollection;
use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;
use InetStudio\TagsPackage\Tags\Contracts\Transformers\Front\Sitemap\ItemTransformerContract;

/**
 * Class ItemTransformer.
 */
class ItemTransformer extends TransformerAbstract implements ItemTransformerContract
{
    /**
     * Трансформация данных.
     *
     * @param  TagModelContract  $item
     *
     * @return array
     *
     * @throws Throwable
     */
    public function transform(TagModelContract $item): array
    {
        return [
            'loc' => $item['href'],
            'lastmod' => $item['updated_at']->toW3cString(),
            'priority' => '0.7',
            'freq' => 'monthly',
        ];
    }

    /**
     * Обработка коллекции объектов.
     *
     * @param $items
     *
     * @return FractalCollection
     */
    public function transformCollection($items): FractalCollection
    {
        return new FractalCollection($items, $this);
    }
}
