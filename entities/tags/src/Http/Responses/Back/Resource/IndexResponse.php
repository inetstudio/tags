<?php

namespace InetStudio\TagsPackage\Tags\Http\Responses\Back\Resource;

use InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Resource\IndexResponseContract;
use InetStudio\TagsPackage\Tags\Contracts\Services\Back\DataTables\IndexServiceContract as DataTableServiceContract;

class IndexResponse implements IndexResponseContract
{
    protected DataTableServiceContract $datatableService;

    public function __construct(DataTableServiceContract $datatableService)
    {
        $this->datatableService = $datatableService;
    }

    public function toResponse($request)
    {
        $table = $this->datatableService->html();

        return view('admin.module.tags::back.pages.index', compact('table'));
    }
}
