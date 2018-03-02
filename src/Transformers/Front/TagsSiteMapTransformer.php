<?php

namespace InetStudio\Tags\Transformers\Front;

use League\Fractal\TransformerAbstract;
use InetStudio\Tags\Contracts\Models\TagModelContract;
use League\Fractal\Resource\Collection as FractalCollection;
use InetStudio\Tags\Contracts\Transformers\Front\TagsSiteMapTransformerContract;

/**
 * Class TagsSiteMapTransformer.
 */
class TagsSiteMapTransformer extends TransformerAbstract implements TagsSiteMapTransformerContract
{
    /**
     * Подготовка данных для отображения в карте сайта.
     *
     * @param TagModelContract $item
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(TagModelContract $item): array
    {
        return [
            'loc' => $item->href,
            'lastmod' => $item->updated_at->toW3cString(),
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
