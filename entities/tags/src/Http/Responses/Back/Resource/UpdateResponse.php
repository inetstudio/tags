<?php

namespace InetStudio\TagsPackage\Tags\Http\Responses\Back\Resource;

use Illuminate\Support\Facades\Session;
use InetStudio\TagsPackage\Tags\DTO\Back\Resource\Save\ItemData;
use InetStudio\TagsPackage\Tags\Contracts\Services\Back\ResourceServiceContract;
use InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\UpdateResponseContract;

class UpdateResponse implements UpdateResponseContract
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

        Session::flash('success', 'Тег «'.$item['name'].'» успешно обновлен');

        return response()->redirectToRoute('back.tags.edit', $item['id']);
    }
}
