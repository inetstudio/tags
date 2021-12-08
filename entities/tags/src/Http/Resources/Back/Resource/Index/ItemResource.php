<?php

namespace InetStudio\TagsPackage\Tags\Http\Resources\Back\Resource\Index;

use Illuminate\Http\Resources\Json\JsonResource;
use InetStudio\TagsPackage\Tags\Contracts\Http\Resources\Back\Resource\Index\ItemResourceContract;

class ItemResource extends JsonResource implements ItemResourceContract
{
    public function toArray($request)
    {
        return [
            'id' => (int) $this['id'],
            'name' => (string) $this['name'],
            'taggables_count' => (int) $this['taggables_count'],
            'created_at' => (string) $this['created_at'],
            'updated_at' => (string) $this['updated_at'],
            'actions' => view(
                    'admin.module.tags::back.partials.datatables.actions',
                    [
                        'item' => $this,
                    ]
                )->render(),
        ];
    }
}
