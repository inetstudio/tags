<?php

namespace InetStudio\TagsPackage\Tags\Http\Responses\Back\Resource;

use Illuminate\Support\Facades\Session;
use InetStudio\TagsPackage\Tags\DTO\Back\Resource\Save\ItemData;
use InetStudio\TagsPackage\Tags\Contracts\Services\Back\ResourceServiceContract;
use InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\StoreResponseContract;

class StoreResponse implements StoreResponseContract
{

    protected ResourceServiceContract $resourceService;

    public function __construct(ResourceServiceContract $resourceService)
    {
        $this->resourceService = $resourceService;
    }

    public function toResponse($request)
    {
        $data = ItemData::fromRequest($request);

        $item = $this->resourceService->save($data);

        Session::flash('success', 'Тег «'.$item['name'].'» успешно создан');

        return response()->redirectToRoute('back.tags.edit', $item['id']);
    }
}
