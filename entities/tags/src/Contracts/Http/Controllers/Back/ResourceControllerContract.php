<?php

namespace InetStudio\TagsPackage\Tags\Contracts\Http\Controllers\Back;

use InetStudio\TagsPackage\Tags\Contracts\Services\Back\ItemsServiceContract;
use InetStudio\TagsPackage\Tags\Contracts\Services\Back\DataTableServiceContract;
use InetStudio\TagsPackage\Tags\Contracts\Http\Requests\Back\SaveItemRequestContract;
use InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\FormResponseContract;
use InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\SaveResponseContract;
use InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\IndexResponseContract;
use InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\DestroyResponseContract;

interface ResourceControllerContract
{
    public function index(DataTableServiceContract $dataTableService): IndexResponseContract;

    public function create(ItemsServiceContract $resourceService): FormResponseContract;

    public function store(ItemsServiceContract $resourceService, SaveItemRequestContract $request): SaveResponseContract;

    public function edit(ItemsServiceContract $resourceService, int $id = 0): FormResponseContract;

    public function update(
        ItemsServiceContract $resourceService,
        SaveItemRequestContract $request,
        int $id = 0
    ): SaveResponseContract;

    public function destroy(ItemsServiceContract $resourceService, int $id = 0): DestroyResponseContract;
}
