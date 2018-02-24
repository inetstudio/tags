<?php

namespace InetStudio\Tags\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use InetStudio\Tags\Contracts\Services\Back\TagsDataTableServiceContract;
use InetStudio\Tags\Contracts\Http\Controllers\Back\TagsDataControllerContract;

/**
 * Class TagsDataController.
 */
class TagsDataController extends Controller implements TagsDataControllerContract
{
    /**
     * Получаем данные для отображения в таблице.
     *
     * @param TagsDataTableServiceContract $dataTableService
     *
     * @return mixed
     */
    public function data(TagsDataTableServiceContract $dataTableService)
    {
        return $dataTableService->ajax();
    }
}
