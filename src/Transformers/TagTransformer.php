<?php

namespace Inetstudio\Tags\Transformers;

use League\Fractal\TransformerAbstract;
use InetStudio\Tags\Models\TagModel;

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
            'name' => $tag->name,
            'created_at' => (string) $tag->created_at,
            'updated_at' => (string) $tag->updated_at,
            'actions' => view('admin.module.tags::pages.tags.datatables.actions', ['id' => $tag->id])->render(),
        ];
    }
}
