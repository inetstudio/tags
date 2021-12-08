<?php

namespace InetStudio\TagsPackage\Tags\Http\Responses\Back\Data;

use InetStudio\TagsPackage\Tags\Contracts\Services\Back\DataTables\IndexServiceContract;
use InetStudio\TagsPackage\Tags\Contracts\Http\Responses\Back\Data\GetIndexDataResponseContract;

class GetIndexDataResponse implements GetIndexDataResponseContract
{
    protected IndexServiceContract $dataService;

    public function __construct(IndexServiceContract $dataService)
    {
        $this->dataService = $dataService;
    }

    public function toResponse($request)
    {
        return $this->dataService->ajax();
    }
}
