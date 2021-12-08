<?php

namespace InetStudio\TagsPackage\Tags\Services\Back;

use Illuminate\Support\Collection;
use InetStudio\AdminPanel\Base\Services\BaseService;
use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;
use InetStudio\TagsPackage\Tags\Contracts\Services\Back\UtilityServiceContract;

class UtilityService extends BaseService implements UtilityServiceContract
{
    public function __construct(TagModelContract $model)
    {
        parent::__construct($model);
    }

    public function getSuggestions(string $search): Collection
    {
        $items = $this->model::where(
            [
                ['name', 'LIKE', '%'.$search.'%'],
            ]
        )->get();

        return $items;
    }
}
