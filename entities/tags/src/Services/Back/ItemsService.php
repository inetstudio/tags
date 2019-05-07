<?php

namespace InetStudio\TagsPackage\Tags\Services\Back;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use InetStudio\AdminPanel\Base\Services\BaseService;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;
use InetStudio\TagsPackage\Tags\Contracts\Services\Back\ItemsServiceContract;

/**
 * Class ItemsService.
 */
class ItemsService extends BaseService implements ItemsServiceContract
{
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
     * Сохраняем модель.
     *
     * @param  array  $data
     * @param  int  $id
     *
     * @return TagModelContract
     *
     * @throws BindingResolutionException
     */
    public function save(array $data, int $id): TagModelContract
    {
        $action = ($id) ? 'отредактирован' : 'создан';

        $itemData = Arr::only($data, $this->model->getFillable());
        $item = $this->saveModel($itemData, $id);

        $metaData = Arr::get($data, 'meta', []);
        app()->make('InetStudio\MetaPackage\Meta\Contracts\Services\Back\ItemsServiceContract')
            ->attachToObject($metaData, $item);

        $images = (config('tags.images.conversions.tag')) ? array_keys(config('tags.images.conversions.tag')) : [];
        app()->make('InetStudio\Uploads\Contracts\Services\Back\ImagesServiceContract')
            ->attachToObject(request(), $item, $images, 'tags', 'tag');

        $this->attachToObject(request(), $item);

        $item->searchable();

        event(
            app()->makeWith(
                'InetStudio\TagsPackage\Tags\Contracts\Events\Back\ModifyItemEventContract',
                compact('item')
            )
        );

        Session::flash('success', 'Тег «'.$item->name.'» успешно '.$action);

        return $item;
    }

    /**
     * Присваиваем теги объекту.
     *
     * @param $tags
     * @param $item
     */
    public function attachToObject($tags, $item): void
    {
        if ($tags instanceof Request) {
            $tags = $tags->get('tags', []);
        } else {
            $tags = (array) $tags;
        }

        if (! empty($tags)) {
            $item->syncTags($this->model::whereIn('id', $tags)->get());
        } else {
            $item->detachTags($item->tags);
        }
    }
}
