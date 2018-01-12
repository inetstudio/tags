<?php

namespace InetStudio\Tags\Transformers\Front;

use InetStudio\Tags\Models\TagModel;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection as FractalCollection;

/**
 * Class TagsSiteMapTransformer
 * @package InetStudio\Tags\Transformers\Front
 */
class TagsSiteMapTransformer extends TransformerAbstract
{
    /**
     * Подготовка данных для отображения в карте сайта.
     *
     * @param TagModel $tag
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(TagModel $tag): array
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
