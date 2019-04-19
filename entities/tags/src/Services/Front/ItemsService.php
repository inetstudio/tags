<?php

namespace InetStudio\TagsPackage\Tags\Services\Front;

use Illuminate\Support\Collection;
use InetStudio\AdminPanel\Base\Services\BaseService;
use InetStudio\AdminPanel\Base\Services\Traits\SlugsServiceTrait;
use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;
use InetStudio\TagsPackage\Tags\Services\Front\Traits\TagsServiceTrait;
use InetStudio\TagsPackage\Tags\Contracts\Services\Front\ItemsServiceContract;

/**
 * Class ItemsService.
 */
class ItemsService extends BaseService implements ItemsServiceContract
{
    use TagsServiceTrait;
    use SlugsServiceTrait;

    /**
     * ItemsService constructor.
     *
     * @param  TagModelContract  $model
     */
    public function __construct(TagModelContract $model)
    {
        parent::__construct($model);
    }

    /**
     * Возвращаем объекты, привязанные к материалам.
     *
     * @param  Collection  $materials
     *
     * @return Collection
     */
    public function getItemsByMaterials(Collection $materials): Collection
    {
        return $materials->map(
                function ($item) {
                    return (isset($item['tags'])) ? $item['tags'] : [];
                }
            )
            ->filter()
            ->collapse()
            ->unique('id');
    }
}
