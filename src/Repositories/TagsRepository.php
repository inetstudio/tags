<?php

namespace InetStudio\Tags\Repositories;

use InetStudio\AdminPanel\Repositories\BaseRepository;
use InetStudio\Tags\Contracts\Models\TagModelContract;
use InetStudio\Tags\Contracts\Repositories\TagsRepositoryContract;
use InetStudio\AdminPanel\Repositories\Traits\SlugsRepositoryTrait;

/**
 * Class TagsRepository.
 */
class TagsRepository extends BaseRepository implements TagsRepositoryContract
{
    use SlugsRepositoryTrait;

    /**
     * TagsRepository constructor.
     *
     * @param TagModelContract $model
     */
    public function __construct(TagModelContract $model)
    {
        $this->model = $model;

        $this->defaultColumns = ['id', 'name', 'slug', 'created_at'];
        $this->relations = [
            'meta' => function ($query) {
                $query->select(['metable_id', 'metable_type', 'key', 'value']);
            },

            'media' => function ($query) {
                $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
            },

            'tags' => function ($query) {
                $query->select(['id', 'name', 'slug']);
            },
        ];
    }
}
