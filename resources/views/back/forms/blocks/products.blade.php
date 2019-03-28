@inject('embeddedProductsDataTableService', 'InetStudio\Products\Contracts\Services\Back\EmbeddedProductsDataTableServiceContract')

@php
    $productsTable = $embeddedProductsDataTableService->html();

    $items = ($value) ? $value->map(function ($item) {
        return [
            'id' => $item->id,
            'name' => $item->title,
        ];
    })->toArray() : [];

    $items = json_encode($items);
@endphp

<div class="panel panel-default products_wrapper">
    <div class="panel-heading">
        <h5 class="panel-title">
            <a data-toggle="collapse" data-parent="#mainAccordion" href="#collapseProducts" aria-expanded="false" class="collapsed">E-com</a>
        </h5>
    </div>
    <div id="collapseProducts" class="collapse" aria-expanded="false">
        <div class="panel-body">

            @if (isset($attributes['item']) && method_exists($attributes['item'], 'getCustomField'))
                {!! Form::string('custom[ecom_header]', $attributes['item']->getCustomField('ecom_header'), [
                     'label' => [
                         'title' => 'Заголовок',
                     ],
                 ]) !!}
            @endif

            <div class="m-b-md">
                <ul class="products-package products-list m-t small-list" id="{{ $name }}_list" data-items="{{ $items }}">
                    <li v-for="(item, index) in items">
                        <div class="row">
                            <div class="col-10">
                                <span class="m-l-xs">@{{ itemTitles[index] }}</span>
                            </div>
                            <div class="col-2">
                                <div class="btn-group float-right">
                                    <a href="#" class="btn btn-xs btn-danger delete" @click.prevent="remove(index)"><i class="fa fa-times"></i></a>
                                </div>
                                <input :name="'{{ $name }}[' + index + '][id]'" type="hidden" :value="item.id">
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="table-responsive products-table">
                {{ $productsTable->table(['class' => 'table table-striped table-bordered table-hover dataTable']) }}
            </div>
        </div>
    </div>
</div>

@pushonce('scripts:datatable_products_embedded')
    {!! $productsTable->scripts() !!}
@endpushonce
