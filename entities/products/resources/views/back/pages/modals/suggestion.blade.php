@inject('modalProductsDataTableService', 'InetStudio\Products\Contracts\Services\Back\ModalProductsDataTableServiceContract')

@php
    $modalProductsTable = $modalProductsDataTableService->html();
@endphp

@pushonce('modals:choose_product')
    <div id="choose_product_modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal inmodal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h1 class="modal-title">Выберите продукт</h1>
                </div>

                <div class="modal-body">
                    <div class="ibox-content">
                        <div class="row">

                            {{ $modalProductsTable->table(['class' => 'table table-striped table-bordered table-hover dataTable']) }}

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
@endpushonce

@pushonce('scripts:datatable_products_modal')
    {!! $modalProductsTable->scripts() !!}
@endpushonce
