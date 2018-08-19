<?php

namespace InetStudio\Tags\Services\Front;

use League\Fractal\Manager;
use Illuminate\Support\Collection;
use League\Fractal\Serializer\DataArraySerializer;
use InetStudio\Tags\Contracts\Services\Front\TagsServiceContract;

/**
 * Class TagsService.
 */
class TagsService implements TagsServiceContract
{
    /**
     * @var
     */
    public $repository;

    /**
     * TagsService constructor.
     */
    public function __construct()
    {
        $this->repository = app()->make('InetStudio\Tags\Contracts\Repositories\TagsRepositoryContract');
    }

    /**
     * Получаем объект по slug.
     *
     * @param string $slug
     * @param array $params
     *
     * @return mixed
     */
    public function getTagBySlug(string $slug, array $params = [])
    {
        return $this->repository->getItemBySlug($slug, $params);
    }

    /**
     * Возвращаем объекты, привязанные к материалам.
     *
     * @param Collection $materials
     *
     * @return Collection
     */
    public function getTagsByMaterials(Collection $materials): Collection
    {
        return $materials->map(function ($item) {
            return (method_exists($item, 'tags')) ? $item->tags : [];
        })->filter()->collapse()->unique('id');
    }

    /**
     * Получаем информацию по объектам для карты сайта.
     *
     * @return array
     */
    public function getSiteMapItems(): array
    {
        $items = $this->repository->getAllItems();

        $resource = app()->make('InetStudio\Tags\Contracts\Transformers\Front\TagsSiteMapTransformerContract')
            ->transformCollection($items);

        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());

        $transformation = $manager->createData($resource)->toArray();

        return $transformation['data'];
    }
}
