<?php

namespace InetStudio\TagsPackage\Tags\Http\Controllers\Back;

use Illuminate\Http\JsonResponse;
use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use InetStudio\TagsPackage\Tags\Contracts\Services\Back\DataTableServiceContract;
use InetStudio\TagsPackage\Tags\Contracts\Http\Controllers\Back\DataControllerContract;

/**
 * Class DataController.
 */
class DataController extends Controller implements DataControllerContract
{
    /**
     * Получаем данные для отображения в таблице.
     *
     * @param  DataTableServiceContract  $dataTableService
     *
     * @return JsonResponse
     */
    public function data(DataTableServiceContract $dataTableService): JsonResponse
    {
        return $dataTableService->ajax();
    }
}
