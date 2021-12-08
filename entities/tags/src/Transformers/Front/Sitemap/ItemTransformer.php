<?php

namespace InetStudio\TagsPackage\Tags\Transformers\Front\Sitemap;

use Carbon\Carbon;
use InetStudio\AdminPanel\Base\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection as FractalCollection;
use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;
use InetStudio\TagsPackage\Tags\Contracts\Transformers\Front\Sitemap\ItemTransformerContract;

class ItemTransformer extends BaseTransformer implements ItemTransformerContract
{
    public function transform(TagModelContract $item): array
    {
        /** @var Carbon $updatedAt */
        $updatedAt = $item['updated_at'];

        return [
            'loc' => $item['href'],
            'lastmod' => $updatedAt->toW3cString(),
            'priority' => '0.7',
            'freq' => 'monthly',
        ];
    }

    public function transformCollection($items): FractalCollection
    {
        return new FractalCollection($items, $this);
    }
}
