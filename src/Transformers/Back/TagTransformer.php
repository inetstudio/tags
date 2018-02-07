<?php

namespace InetStudio\Tags\Transformers\Back;

use InetStudio\Tags\Models\TagModel;
use League\Fractal\TransformerAbstract;

/**
 * Class TagTransformer
 * @package InetStudio\Tags\Transformers\Back
 */
class TagTransformer extends TransformerAbstract
{
    /**
     * Подготовка данных для отображения в таблице.
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
