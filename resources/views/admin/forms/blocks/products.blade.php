@php
    $productsTable = $attributes['table'];

    $items = $value->map(function ($item) {
        return [
            'id' => $item->id,
            'name' => $item->title,
        ];
    })->toArray();

    $items = json_encode($items);
@endphp

<div class="row products_wrapper">
    <div class="col-lg-12">
        <div class="panel-group float-e-margins" id="productsAccordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5 class="panel-title">
                        <a data-toggle="collapse" data-parent="#productsAccordion" href="#collapseProducts" aria-expanded="false" class="collapsed">E-com</a>
                    </h5>
                </div>
                <div id="collapseProducts" class="panel-collapse collapse" aria-expanded="false">
                    <div class="panel-body">
                        <div class="m-b-md">
                            <ul class="products-list m-t small-list" id="{{ $name }}_list" data-items="{{ $items }}">
                                <li v-for="(item, index) in items">
                                    <span class="m-l-xs">@{{ itemTitles[index] }}</span>
                                    <div class="btn-group pull-right">
                                        <a href="#" class="btn btn-xs btn-danger delete" @click.prevent="remove(index)"><i class="fa fa-times"></i></a>
                                    </div>
                                    <input :name="'{{ $name }}[' + index + '][id]'" type="hidden" :value="item.id">
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
        </div>
    </div>
</div>