<?php

namespace Inetstudio\Tags\Transformers;

use InetStudio\Tags\Models\TagModel;
use League\Fractal\TransformerAbstract;

class TagTransformer extends TransformerAbstract
{
    /**
     * @param TagModel $tag
     * @return array
     */
    public function transform(TagModel $tag)
    {
        return [
            'id' => (int) $tag->id,
            'name' => (string) $tag->name,
            'taggables_count' => (int) $tag->taggables_count,
            'created_at' => (string) $tag->created_at,
            'updated_at' => (string) $tag->updated_at,
            'actions' => view('admin.module.tags::partials.datatables.actions', [
                'id' => $tag->id,
                'href' => $tag->href,
            ])->render(),
        ];
    }
}
