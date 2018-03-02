<?php

namespace InetStudio\Tags\Transformers\Back;

use League\Fractal\TransformerAbstract;
use InetStudio\Tags\Contracts\Models\TagModelContract;
use InetStudio\Tags\Contracts\Transformers\Back\TagTransformerContract;

/**
 * Class TagTransformer.
 */
class TagTransformer extends TransformerAbstract implements TagTransformerContract
{
    /**
     * Подготовка данных для отображения в таблице.
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
            'id' => (int) $item->id,
            'name' => (string) $item->name,
            'taggables_count' => (int) $item->taggables_count,
            'created_at' => (string) $item->created_at,
            'updated_at' => (string) $item->updated_at,
            'actions' => view('admin.module.tags::back.partials.datatables.actions', [
                'id' => $item->id,
                'href' => $item->href,
            ])->render(),
        ];
    }
}
