<?php

namespace InetStudio\TagsPackage\Tags\Services\Back;

use Illuminate\Support\Arr;
use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;
use InetStudio\TagsPackage\Tags\Services\ItemsService as BaseItemsService;
use InetStudio\TagsPackage\Tags\Contracts\Services\Back\ResourceServiceContract;
use InetStudio\TagsPackage\Tags\Contracts\DTO\Back\Resource\Save\ItemDataContract;
use InetStudio\MetaPackage\Meta\Contracts\Services\Back\ItemsServiceContract as MetaServiceContract;

class ResourceService extends BaseItemsService implements ResourceServiceContract
{
    public function __construct(
        TagModelContract $model,
        protected MetaServiceContract $metaService
    )
    {
        parent::__construct($model);
    }

    public function create(): TagModelContract
    {
        return new $this->model;
    }

    public function show(int|string $id): TagModelContract
    {
        return $this->model::find($id);
    }

    public function save(ItemDataContract $data): TagModelContract
    {
        $item = $this->model::find($data->id) ?? new $this->model;

        $item->name = $data->name;
        $item->slug = $data->slug;
        $item->title = $data->title;
        $item->content = $data->content;

        $item->save();

        $metaData = Arr::get($data, 'meta', []);
        $this->metaService->attachToObject($metaData, $item);

        resolve(
            'InetStudio\UploadsPackage\Uploads\Contracts\Actions\AttachMediaToObjectActionContract',
            [
                'item' => $item,
                'media' => Arr::get($data, 'media', []),
            ]
        )->execute();

        event(
            resolve(
                'InetStudio\TagsPackage\Tags\Contracts\Events\Back\ModifyItemEventContract',
                compact('item')
            )
        );

        return $item;
    }

    public function destroy(int|string $id): int
    {
        return $this->model::destroy($id);
    }
}
