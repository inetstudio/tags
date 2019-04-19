<?php

namespace InetStudio\TagsPackage\Tags\Services\Front;

use League\Fractal\Manager;
use InetStudio\AdminPanel\Base\Services\BaseService;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;
use InetStudio\TagsPackage\Tags\Contracts\Services\Front\SitemapServiceContract;

/**
 * Class SitemapService.
 */
class SitemapService extends BaseService implements SitemapServiceContract
{
    /**
     * SitemapService constructor.
     *
     * @param  TagModelContract  $model
     */
    public function __construct(TagModelContract $model)
    {
        parent::__construct($model);
    }

    /**
     * Получаем информацию по объектам для карты сайта.
     *
     * @return array
     *
     * @throws BindingResolutionException
     */
    public function getItems(): array
    {
        $items = $this->model->buildQuery(
            [
                'columns' => ['created_at', 'updated_at'],
            ]
        )->get();

        $transformer = app()->make(
            'InetStudio\TagsPackage\Tags\Contracts\Transformers\Front\Sitemap\ItemTransformerContract'
        );

        $resource = $transformer->transformCollection($items);

        $manager = new Manager();
        $serializer = app()->make(
            'InetStudio\AdminPanel\Base\Contracts\Serializers\SimpleDataArraySerializerContract'
        );
        $manager->setSerializer($serializer);

        $data = $manager->createData($resource)->toArray();

        return $data;
    }
}
