<?php

namespace InetStudio\TagsPackage\Tags\Transformers\Back\Resource;

use Throwable;
use League\Fractal\TransformerAbstract;
use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;
use InetStudio\TagsPackage\Tags\Contracts\Transformers\Back\Resource\IndexTransformerContract;

/**
 * Class IndexTransformer.
 */
class IndexTransformer extends TransformerAbstract implements IndexTransformerContract
{
    /**
     * Подготовка данных для отображения в таблице.
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
            'id' => (int) $item['id'],
            'name' => (string) $item['name'],
            'taggables_count' => (int) $item['taggables_count'],
            'created_at' => (string) $item['created_at'],
            'updated_at' => (string) $item['updated_at'],
            'actions' => view(
                'admin.module.tags::back.partials.datatables.actions',
                [
                    'id' => $item['id'],
                    'href' => $item['href'],
                ]
            )->render(),
        ];
    }
}
