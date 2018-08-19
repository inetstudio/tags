<?php

namespace InetStudio\Tags\Services\Back;

use League\Fractal\Manager;
use Illuminate\Support\Facades\Session;
use League\Fractal\Serializer\DataArraySerializer;
use InetStudio\Tags\Contracts\Models\TagModelContract;
use InetStudio\Tags\Contracts\Services\Back\TagsServiceContract;
use InetStudio\Tags\Contracts\Http\Requests\Back\SaveTagRequestContract;

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
     * Получаем объект модели.
     *
     * @param int $id
     *
     * @return TagModelContract
     */
    public function getTagObject(int $id = 0)
    {
        return $this->repository->getItemByID($id);
    }

    /**
     * Получаем объекты по списку id.
     *
     * @param array|int $ids
     * @param array $params
     *
     * @return mixed
     */
    public function getTagsByIDs($ids, array $params = [])
    {
        return $this->repository->getItemsByIDs($ids, $params);
    }

    /**
     * Сохраняем модель.
     *
     * @param SaveTagRequestContract $request
     * @param int $id
     *
     * @return TagModelContract
     */
    public function save(SaveTagRequestContract $request, int $id): TagModelContract
    {
        $action = ($id) ? 'отредактирован' : 'создан';
        $item = $this->repository->save($request->only($this->repository->getModel()->getFillable()), $id);

        app()->make('InetStudio\Meta\Contracts\Services\Back\MetaServiceContract')
            ->attachToObject($request, $item);

        $images = (config('tags.images.conversions.tag')) ? array_keys(config('tags.images.conversions.tag')) : [];
        app()->make('InetStudio\Uploads\Contracts\Services\Back\ImagesServiceContract')
            ->attachToObject($request, $item, $images, 'tags', 'tag');

        $this->attachToObject($request, $item);

        $item->searchable();

        event(app()->makeWith('InetStudio\Tags\Contracts\Events\Back\ModifyTagEventContract', [
            'object' => $item,
        ]));

        Session::flash('success', 'Тег «'.$item->name.'» успешно '.$action);

        return $item;
    }

    /**
     * Удаляем модель.
     *
     * @param $id
     *
     * @return bool
     */
    public function destroy(int $id): ?bool
    {
        $result = $this->repository->destroy($id);

        if ($result) {
            $trashedItem = $this->repository->getTrashedItemByID($id);

            event(app()->makeWith('InetStudio\Tags\Contracts\Events\Back\ModifyTagEventContract', [
                'object' => $trashedItem,
            ]));
        }

        return $result;
    }

    /**
     * Получаем подсказки.
     *
     * @param string $search
     * @param $type
     *
     * @return array
     */
    public function getSuggestions(string $search, $type): array
    {
        $items = $this->repository->searchItems([['name', 'LIKE', '%'.$search.'%']]);

        $resource = (app()->makeWith('InetStudio\Tags\Contracts\Transformers\Back\SuggestionTransformerContract', [
            'type' => $type,
        ]))->transformCollection($items);

        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());

        $transformation = $manager->createData($resource)->toArray();

        if ($type && $type == 'autocomplete') {
            $data['suggestions'] = $transformation['data'];
        } else {
            $data['items'] = $transformation['data'];
        }

        return $data;
    }

    /**
     * Присваиваем теги объекту.
     *
     * @param $request
     *
     * @param $item
     */
    public function attachToObject($request, $item)
    {
        if ($request->filled('tags')) {
            $item->syncTags($this->repository->getItemsByIDs((array) $request->get('tags')));
        } else {
            $item->detachTags($item->tags);
        }
    }
}
