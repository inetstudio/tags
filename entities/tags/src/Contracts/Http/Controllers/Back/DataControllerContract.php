<?php

namespace InetStudio\TagsPackage\Tags\Contracts\Http\Controllers\Back;

use Illuminate\Http\JsonResponse;
use InetStudio\TagsPackage\Tags\Contracts\Services\Back\DataTableServiceContract;

interface DataControllerContract
{
    public function data(DataTableServiceContract $dataTableService): JsonResponse;
}
