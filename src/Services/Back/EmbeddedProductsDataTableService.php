<?php

namespace InetStudio\Products\Services\Back;

use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;
use InetStudio\Products\Contracts\Repositories\ProductsRepositoryContract;
use InetStudio\Products\Contracts\Services\Back\EmbeddedProductsDataTableServiceContract;

/**
 * Class EmbeddedProductsDataTableService.
 */
class EmbeddedProductsDataTableService extends DataTable implements EmbeddedProductsDataTableServiceContract
{
    /**
     * @var ProductsRepositoryContract
     */
    private $repository;

    /**
     * EmbeddedProductsDataTableService constructor.
     *
     * @param ProductsRepositoryContract $repository
     */
    public function __construct(ProductsRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Запрос на получение данных таблицы.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function ajax()
    {
        $transformer = app()->make('InetStudio\Products\Contracts\Transformers\Back\EmbeddedProductTransformerContract');

        return DataTables::of($this->query())
            ->setTransformer($transformer)
            ->rawColumns(['preview', 'actions'])
            ->make();
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = $this->repository->getItemsQuery([
            'columns' => ['description'],
        ]);

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): Builder
    {
        $table = app('datatables.html');

        return $table
            ->columns($this->getColumns())
            ->ajax($this->getAjaxOptions())
            ->parameters($this->getParameters());
    }

    /**
     * Получаем колонки.
     *
     * @return array
     */
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

    /**
     * Свойства ajax datatables.
     *
     * @return array
     */
    protected function getAjaxOptions(): array
    {
        return [
            'url' => route('back.products.data.embedded'),
            'type' => 'POST',
        ];
    }

    /**
     * Свойства datatables.
     *
     * @return array
     */
    protected function getParameters(): array
    {
        $i18n = trans('admin::datatables');

        return [
            'paging' => true,
            'pagingType' => 'full_numbers',
            'searching' => true,
            'info' => false,
            'searchDelay' => 350,
            'language' => $i18n,
        ];
    }
}
