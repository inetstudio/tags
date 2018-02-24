<?php

namespace InetStudio\Tags\Repositories\Back;

use Illuminate\Support\Collection;
use InetStudio\Tags\Contracts\Models\TagModelContract;
use InetStudio\Tags\Contracts\Repositories\Back\TagsRepositoryContract;
use InetStudio\Tags\Contracts\Http\Requests\Back\SaveTagRequestContract;

/**
 * Class TagsRepository.
 */
class TagsRepository implements TagsRepositoryContract
{
    /**
     * @var TagModelContract
     */
    private $model;

    /**
     * TagsRepository constructor.
     *
     * @param TagModelContract $model
     */
    public function __construct(TagModelContract $model)
    {
        $this->model = $model;
    }

    /**
     * Возвращаем объект по id, либо создаем новый.
     *
     * @param int $id
     *
     * @return TagModelContract
     */
    public function getItemByID(int $id): TagModelContract
    {
        if (! (! is_null($id) && $id > 0 && $item = $this->model::find($id))) {
            $item = $this->model;
        }

        return $item;
    }

    /**
     * Возвращаем объекты по списку id.
     *
     * @param $ids
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getItemsByIDs($ids, bool $returnBuilder = false)
    {
        $builder = $this->model::select(['id', 'name', 'slug'])
            ->whereIn('id', (array) $ids);

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
    }

    /**
     * Сохраняем объект.
     *
     * @param SaveTagRequestContract $request
     * @param int $id
     *
     * @return TagModelContract
     */
    public function save(SaveTagRequestContract $request, int $id): TagModelContract
    {
        $item = $this->getItemByID($id);

        $item->name = strip_tags($request->get('name'));
        $item->slug = strip_tags($request->get('slug'));
        $item->title = strip_tags($request->get('title'));
        $item->content = $request->input('content.text');
        $item->save();

        return $item;
    }

    /**
     * Удаляем объект.
     *
     * @param int $id
     *
     * @return bool
     */
    public function destroy($id): ?bool
    {
        return $this->getItemByID($id)->delete();
    }

    /**
     * Ищем объекты.
     *
     * @param string $field
     * @param $value
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function searchItemsByField(string $field, string $value, bool $returnBuilder = false)
    {
        $builder = $this->model::select(['id', 'name', 'title', 'slug'])
            ->where($field, 'LIKE', '%'.$value.'%');

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
    }

    /**
     * Получаем все объекты.
     *
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getAllItems(bool $returnBuilder = false)
    {
        $builder = $this->model::select(['id', 'name', 'slug', 'created_at', 'updated_at'])
            ->orderBy('created_at', 'desc');

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
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
            return (method_exists($item, 'tags')) ? $item->tags : [];
        })->filter()->collapse()->unique('id');
    }

    /**
     * Получаем объекты по slug.
     *
     * @param string $slug
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getItemBySlug(string $slug, bool $returnBuilder = false)
    {
        $builder = $this->model::select(['id', 'title', 'content', 'slug'])
            ->with(['meta' => function ($query) {
                $query->select(['metable_id', 'metable_type', 'key', 'value']);
            }, 'media' => function ($query) {
                $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
            }])
            ->whereSlug($slug);

        if ($returnBuilder) {
            return $builder;
        }

        $item = $builder->first();

        return $item;
    }
}
