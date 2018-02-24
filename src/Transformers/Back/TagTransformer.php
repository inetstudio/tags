<?php

namespace InetStudio\Tags\Transformers\Back;

use League\Fractal\TransformerAbstract;
use InetStudio\Tags\Contracts\Models\TagModelContract;
use InetStudio\Hashtags\Contracts\Transformers\Back\Tags\TagTransformerContract;

/**
 * Class TagTransformer.
 */
class TagTransformer extends TransformerAbstract implements TagTransformerContract
{
    /**
     * Подготовка данных для отображения в таблице.
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
            'id' => (int) $tag->id,
            'name' => (string) $tag->name,
            'taggables_count' => (int) $tag->taggables_count,
            'created_at' => (string) $tag->created_at,
            'updated_at' => (string) $tag->updated_at,
            'actions' => view('admin.module.tags::back.partials.datatables.actions', [
                'id' => $tag->id,
                'href' => $tag->href,
            ])->render(),
        ];
    }
}
