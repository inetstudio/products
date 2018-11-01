@pushonce('modals:products_list')
<div id="products_list_modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal inmodal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                <h1 class="modal-title">Продуктовая подборка</h1>
            </div>
            <div class="modal-body">
                <div class="ibox-content form-horizontal">
                    <div class="sk-spinner sk-spinner-double-bounce">
                        <div class="sk-double-bounce1"></div>
                        <div class="sk-double-bounce2"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox">
                                <div class="ibox-content">
                                    <div class="row">

                                        {!! Form::dropdown('style', (! $item->id) ? 'checklist' : '', [
                                            'label' => [
                                                'title' => 'Оформление',
                                            ],
                                            'field' => [
                                                'class' => 'select2 form-control',
                                                'data-placeholder' => 'Выберите оформление',
                                                'style' => 'width: 100%',
                                            ],
                                            'options' => [
                                                'values' => [
                                                    'checklist' => 'Чек-лист',
                                                    'numlist' => 'Нумерованный список',
                                                    'h2' => 'h2',
                                                    'h3' => 'h3',
                                                ],
                                            ],
                                        ]) !!}

                                    </div>
                                    <div class="row">
                                        <a href="#" class="btn btn-sm btn-primary m-b-lg add_product_item">Добавить</a>
                                        <table class="table table-hover products-list">
                                            <tbody>
                                            <tr class="product_item-tr-template" style="display: none">
                                                <td class="product_item-title">
                                                    <span></span>
                                                </td>
                                                <td width="10%">
                                                    <a href="#" class="btn btn-xs btn-default edit-product_item m-r-xs"><i class="fa fa-pencil"></i></a>
                                                    <a href="#" class="btn btn-xs btn-danger delete-product_item"><i class="fa fa-times"></i></a>
                                                </td>
                                                <input name="product_item[]'" type="hidden" value="">
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                <a href="#" class="btn btn-primary save">Сохранить</a>
            </div>
        </div>
    </div>
</div>

<div id="product_item_modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal inmodal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                <h1 class="modal-title">Создание продукта</h1>
            </div>
            <div class="modal-body">
                <div class="ibox-content form-horizontal">
                    <div class="sk-spinner sk-spinner-double-bounce">
                        <div class="sk-double-bounce1"></div>
                        <div class="sk-double-bounce2"></div>
                    </div>
                    <div class="row">
                        {!! Form::open(['url' => route('back.products.items.store'), 'id' => 'product_itemModalForm', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) !!}

                        {{ method_field('POST') }}

                        {!! Form::hidden('product_item_id', '') !!}

                        {!! Form::string('title', '', [
                            'label' => [
                                'title' => 'Заголовок',
                                'class' => 'col-sm-2 control-label',
                            ],
                            'field' => [
                                'class' => 'form-control',
                            ],
                        ]) !!}

                        @php
                            $previewCrops = config('products.images.crops.product_item.preview') ?? [];

                            foreach ($previewCrops as &$previewCrop) {
                                $previewCrop['value'] = '';
                            }
                        @endphp

                        {!! Form::crop('preview', null, [
                            'label' => [
                                'title' => 'Превью',
                            ],
                            'image' => [
                                'filepath' => '',
                                'filename' => '',
                            ],
                            'crops' => $previewCrops,
                            'additional' => [
                                [
                                    'title' => 'Описание',
                                    'name' => 'description',
                                    'value' => '',
                                ],
                                [
                                    'title' => 'Copyright',
                                    'name' => 'copyright',
                                    'value' => '',
                                ],
                                [
                                    'title' => 'Alt',
                                    'name' => 'alt',
                                    'value' => '',
                                ],
                            ],
                        ]) !!}

                        {!! Form::wysiwyg('content', '', [
                            'label' => [
                                'title' => 'Контент',
                            ],
                            'field' => [
                                'class' => 'tinymce',
                                'id' => 'modal_product_item_content',
                                'hasImages' => true,
                            ],
                            'images' => [
                                'media' => [],
                                'fields' => [
                                    [
                                        'title' => 'Описание',
                                        'name' => 'description',
                                    ],
                                    [
                                        'title' => 'Copyright',
                                        'name' => 'copyright',
                                    ],
                                    [
                                        'title' => 'Alt',
                                        'name' => 'alt',
                                    ],
                                ],
                            ],
                        ]) !!}

                        {!! Form::close()!!}
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                <a href="#" class="btn btn-primary save">Сохранить</a>
            </div>
        </div>
    </div>
</div>
@endpushonce
