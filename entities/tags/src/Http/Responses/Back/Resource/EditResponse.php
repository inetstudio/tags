<?php

namespace InetStudio\TagsPackage\Tags\Http\Responses\Back\Resource;

use InetStudio\TagsPackage\Tags\Contracts\Services\Back\ResourceServiceContract;
use InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\EditResponseContract;

class EditResponse implements EditResponseContract
{
    protected ResourceServiceContract $resourceService;

    public function __construct(ResourceServiceContract $resourceService)
    {
        $this->resourceService = $resourceService;
    }

    public function toResponse($request)
    {
        $id = $request->route('tag', 0);

        $item = $this->resourceService->show($id);

        return response()->view('admin.module.tags::back.pages.form', compact('item'));
    }
}
