<?php

namespace Inetstudio\Tags\Transformers;

use InetStudio\Tags\Models\TagModel;
use League\Fractal\TransformerAbstract;

/**
 * Class TagTransformer
 * @package Inetstudio\Tags\Transformers
 */
class TagTransformer extends TransformerAbstract
{
    /**
     * Подготовка данных для отображения в таблице.
     *
     * @param TagModel $tag
     * @return array
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
