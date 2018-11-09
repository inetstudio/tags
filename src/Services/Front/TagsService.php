<?php

namespace InetStudio\Tags\Services\Front;

use League\Fractal\Manager;
use Illuminate\Support\Collection;
use League\Fractal\Serializer\DataArraySerializer;
use InetStudio\AdminPanel\Services\Front\BaseService;
use InetStudio\Tags\Contracts\Services\Front\TagsServiceContract;
use InetStudio\AdminPanel\Services\Front\Traits\SlugsServiceTrait;

/**
 * Class TagsService.
 */
class TagsService extends BaseService implements TagsServiceContract
{
    use SlugsServiceTrait;

    /**
     * TagsService constructor.
     */
    public function __construct()
    {
        parent::__construct(app()->make('InetStudio\Tags\Contracts\Repositories\TagsRepositoryContract'));
    }

    /**
     * Возвращаем объекты, привязанные к материалам.
     *
     * @param Collection $materials
     *
     * @return Collection
     */
    public function getItemsByMaterials(Collection $materials): Collection
    {
        return $materials->map(function ($item) {
            return (isset($item['tags'])) ? $item['tags'] : [];
        })->filter()->collapse()->unique('id');
    }

    /**
     * Получаем информацию по объектам для карты сайта.
     *
     * @return array
     */
    public function getSiteMapItems(): array
    {
        $items = $this->repository->getAllItems([
            'columns' => ['updated_at'],
        ]);

        $resource = app()->make('InetStudio\Tags\Contracts\Transformers\Front\TagsSiteMapTransformerContract')
            ->transformCollection($items);

        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());

        $transformation = $manager->createData($resource)->toArray();

        return $transformation['data'];
    }
}
