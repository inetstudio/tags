<?php

namespace InetStudio\TagsPackage\Tags\Http\Responses\Back\Resource;

use InetStudio\TagsPackage\Tags\Contracts\Services\Back\ResourceServiceContract;
use InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\CreateResponseContract;

class CreateResponse implements CreateResponseContract
{
    protected ResourceServiceContract $resourceService;

    public function __construct(ResourceServiceContract $resourceService)
    {
        $this->resourceService = $resourceService;
    }

    public function toResponse($request)
    {
        $item = $this->resourceService->create();

        return response()->view('admin.module.tags::back.pages.form', compact('item'));
    }
}
