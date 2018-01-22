<?php

namespace InetStudio\Tags\Services\Front;

use League\Fractal\Manager;
use Illuminate\Support\Collection;
use InetStudio\Tags\Models\TagModel;
use Illuminate\Support\Facades\Cache;
use League\Fractal\Serializer\DataArraySerializer;
use InetStudio\Tags\Contracts\Services\TagsServiceContract;
use InetStudio\Tags\Transformers\Front\TagsSiteMapTransformer;

/**
 * Class TagsService
 * @package InetStudio\Tags\Services\Front
 */
class TagsService implements TagsServiceContract
{
    /**
     * Получаем тег по slug.
     *
     * @param string $slug
     *
     * @return TagModel
     */
    public function getTagBySlug(string $slug): TagModel
    {
        $cacheKey = 'TagsService_getTagBySlug_'.md5($slug);

        $tags =  Cache::tags(['tags'])->remember($cacheKey, 1440, function () use ($slug) {
            return TagModel::select(['id', 'title', 'slug', 'content'])
                ->with(['meta' => function ($query) {
                    $query->select(['metable_id', 'metable_type', 'key', 'value']);
                }, 'media' => function ($query) {
                    $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
                }])
                ->whereSlug($slug)
                ->get();
        });

        if ($tags->count() == 0) {
            abort(404);
        }

        return $tags->first();
    }

    /**
     * Возвращаем теги, привязанные к материалам.
     *
     * @param Collection $materials
     * @return Collection
     */
    public function getTagsByMaterials(Collection $materials): Collection
    {
        $objectHash = md5($materials->pluck('slug', 'id')->toJson());
        $cacheKey = 'IngredientsService_getTagsByMaterials_'.$objectHash;

        return Cache::tags(['tags'])->remember($cacheKey, 1440, function () use ($materials) {
            return $materials->map(function ($item) {
                return (method_exists($item, 'tags')) ? $item->tags : [];
            })->filter()->collapse()->unique('id');
        });
    }

    /**
     * Получаем информацию по тегам для карты сайта.
     *
     * @return array
     */
    public function getSiteMapItems(): array
    {
        $tags = TagModel::select(['slug', 'created_at', 'updated_at'])
            ->orderBy('created_at', 'desc')
            ->get();

        $resource = (new TagsSiteMapTransformer())->transformCollection($tags);

        return $this->serializeToArray($resource);
    }

    /**
     * Преобразовываем данные в массив.
     *
     * @param $resource
     *
     * @return array
     */
    private function serializeToArray($resource): array
    {
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());

        $transformation = $manager->createData($resource)->toArray();

        return $transformation['data'];
    }
}
