<?php

namespace InetStudio\TagsPackage\Tags\Services\Back\DataTables;

use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;
use InetStudio\TagsPackage\Tags\Contracts\Models\TagModelContract;
use InetStudio\TagsPackage\Tags\Contracts\Services\Back\DataTables\IndexServiceContract;
use InetStudio\TagsPackage\Tags\Contracts\Http\Resources\Back\Resource\Index\ItemResourceContract;

class IndexService extends DataTable implements IndexServiceContract
{
    protected TagModelContract $model;

    protected ItemResourceContract $resource;

    public function __construct(TagModelContract $model)
    {
        $this->model = $model;
        $this->resource = resolve(
            ItemResourceContract::class,
            [
                'resource' => null,
            ]
        );
    }

    public function ajax(): JsonResponse
    {
        return DataTables::of($this->query())
            ->setTransformer(function ($item) {
                return $this->resource::make($item)->resolve();
            })
            ->rawColumns(['actions'])
            ->make();
    }

    public function query()
    {
        return $this->model->query()->withCount('related as taggables_count');
    }

    public function html(): Builder
    {
        /** @var Builder $table */
        $table = app('datatables.html');

        return $table
            ->columns($this->getColumns())
            ->ajax($this->getAjaxOptions())
            ->parameters($this->getParameters());
    }

    protected function getColumns(): array
    {
        return [
            ['data' => 'name', 'name' => 'name', 'title' => 'Название'],
            [
                'data' => 'taggables_count',
                'name' => 'taggables_count',
                'title' => 'Количество материалов',
                'searchable' => false,
            ],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Дата создания'],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Дата обновления'],
            [
                'data' => 'actions',
                'name' => 'actions',
                'title' => 'Действия',
                'orderable' => false,
                'searchable' => false,
            ],
        ];
    }

    protected function getAjaxOptions(): array
    {
        return [
            'url' => route('back.tags.data.index'),
            'type' => 'POST',
        ];
    }

    protected function getParameters(): array
    {
        $translation = trans('admin::datatables');

        return [
            'paging' => true,
            'pagingType' => 'full_numbers',
            'searching' => true,
            'info' => false,
            'searchDelay' => 350,
            'language' => $translation,
        ];
    }
}
