<?php

namespace InetStudio\ProductsPackage\Products\Services\Back\DataTables;

use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;
use InetStudio\ProductsPackage\Products\Contracts\Models\ProductModelContract;
use InetStudio\ProductsPackage\Products\Contracts\Services\Back\DataTables\ModalServiceContract;
use InetStudio\ProductsPackage\Products\Contracts\Http\Resources\Back\Data\ModalItemResourceContract;

class ModalService extends DataTable implements ModalServiceContract
{
    protected ProductModelContract $model;

    protected ModalItemResourceContract $resource;

    public function __construct(ProductModelContract $model)
    {
        $this->model = $model;
        $this->resource = resolve(
            ModalItemResourceContract::class,
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
        return $this->model->query();
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
            ['data' => 'id', 'name' => 'id', 'title' => 'ID', 'orderable' => false, 'searchable' => false, 'visible' => false],
            ['data' => 'preview', 'name' => 'preview', 'title' => 'Изображение', 'orderable' => false, 'searchable' => false],
            ['data' => 'brand', 'name' => 'brand', 'title' => 'Бренд'],
            ['data' => 'title', 'name' => 'title', 'title' => 'Название'],
            ['data' => 'description', 'name' => 'description', 'title' => 'Описание'],
            ['data' => 'actions', 'name' => 'actions', 'title' => 'Действия', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function getAjaxOptions(): array
    {
        return [
            'url' => route('back.products-package.products.data.modal'),
            'type' => 'POST',
        ];
    }

    protected function getParameters(): array
    {
        $translation = trans('admin::datatables');

        return [
            'order' => [2, 'desc'],
            'paging' => true,
            'pagingType' => 'full_numbers',
            'searching' => true,
            'info' => false,
            'searchDelay' => 350,
            'language' => $translation,
        ];
    }
}
