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
     * @param TagModelContract $tag
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(TagModelContract $tag): array
    {
        return [
            'loc' => $tag->href,
            'lastmod' => $tag->updated_at->toW3cString(),
            'priority' => '0.7',
            'freq' => 'monthly',
        ];
    }

    /**
     * Обработка коллекции тегов.
     *
     * @param $tags
     *
     * @return FractalCollection
     */
    public function transformCollection($tags): FractalCollection
    {
        return new FractalCollection($tags, $this);
    }
}
