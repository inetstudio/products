let productsList = $('#products_list_modal'),
    productItemModal = $('#product_item_modal'),
    template = productsList.find('.product_item-tr-template'),
    productsListWidgetID = '',
    contentEditor = undefined;

productsList.find('.save').on('click', function (event) {
    event.preventDefault();

    let ids = _.compact(productsList.find('input').map(function () {
            return $(this).val();
        }).get()),
        titles = _.compact(productsList.find('.product_item-title span').map(function () {
            return $.trim($(this).text());
        }).get());

    let style = productsList.find('select[name=style]').val();

    if (ids.length !== 0) {
        window.Admin.modules.widgets.saveWidget(productsListWidgetID, {
            view: 'admin.module.products::front.partials.content.products_list_widget',
            params: {
                style: style,
                ids: ids
            },
            additional_info : {
                titles: titles
            }
        }, {
            editor: contentEditor,
            type: 'products.list',
            alt: 'Виджет-подборка'
        });
    }

    $('#products_list_modal').modal('hide');
});

productsList.find('.add_product_item').on('click', function (event) {
    event.preventDefault();

    $('#product_item_modal').modal();
});

productsList.find('table').on('click', '.edit-product_item', function (event) {
    event.preventDefault();

    $('#products_list_modal').find('.widget-content.ibox-content').toggleClass('sk-loading');

    let productItemID = $(this).parents('tr').find('input').first().val();

    $.ajax({
        url: route('back.products.items.show', productItemID),
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            if (typeof data.id !== 'undefined') {
                productItemModal.find('.modal-header h1').text('Редактирование продукта');
                productItemModal.find('form').attr('action', route('back.products.items.update', data.id));
                productItemModal.find('input[name=_method]').val('PUT');
                productItemModal.find('input[name=product_item_id]').val(data.id);
                productItemModal.find('input[name=title]').val(data.title);
                window.tinymce.get('modal_product_item_content').setContent(data.content);

                productItemModal.find('.preview img').removeAttr('data-src').attr('src', data.preview.filepath);

                productItemModal.find('.crop_buttons').show();
                productItemModal.find('.start-cropper').removeClass('btn-default').addClass('btn-primary');
                productItemModal.find('[name="preview[crop][vertical]"]').val(JSON.stringify(data.preview.crop));

                productItemModal.find('[name="preview[description]"]').val(data.preview.additional_info.description);
                productItemModal.find('[name="preview[copyright]"]').val(data.preview.additional_info.copyright);
                productItemModal.find('[name="preview[alt]"]').val(data.preview.additional_info.alt);
                productItemModal.find('.additional_fields').show();

                Admin.containers.images['modal_product_item_content'].images = data.content_images;

                $('#products_list_modal').find('.widget-content.ibox-content').toggleClass('sk-loading');

                $('#product_item_modal').modal();
            }
        },
        error: function () {
            $('#products_list_modal').find('.widget-content.ibox-content').toggleClass('sk-loading');

            swal({
                title: "Ошибка",
                text: "При получении продукта произошла ошибка",
                type: "error"
            });
        }
    });
});

productsList.find('table').on('click', '.delete-product_item', function (event) {
    event.preventDefault();

    let button = $(this);

    button.parents('tr').remove();
});

productItemModal.find('.save').on('click', function (event) {
    event.preventDefault();

    let form = productItemModal.find('form'),
        data = form.serializeJSON();

    data.content.text = window.tinymce.get('modal_product_item_content').getContent();

    productItemModal.find('.form-group').removeClass('has-error');
    productItemModal.find('span.form-text').remove();

    $('#product_item_modal').find('.widget-content.ibox-content').toggleClass('sk-loading');

    $.ajax({
        'url': form.attr('action'),
        'type': form.attr('method'),
        'data': data,
        'dataType': 'json',
        'success': function (data) {
            if (data.success === true) {
                let existElement = productsList.find('[data-id='+data.id+']');

                if (existElement.length > 0) {
                    existElement.find('.product_item-title span').text(data.title);
                } else {
                    addProductsToList(data);
                }

                $('#product_item_modal').find('.widget-content.ibox-content').toggleClass('sk-loading');

                $('#product_item_modal').modal('hide');
            }
        },
        'error': function (data) {
            for (let field in data.responseJSON.errors) {
                let fieldName = dotToArray(field);
                let input = productItemModal.find('[name="'+fieldName+'"]');

                if (input.length > 0) {
                    input.parents('.form-group').addClass('has-error');

                    let errorMessages = data.responseJSON.errors[field];

                    errorMessages.forEach(function (errorMessage) {
                        let errorElement = $('<span class="form-text m-b-none">'+errorMessage+'</span>');

                        errorElement.insertAfter(input);
                    })
                }
            }

            $('#product_item_modal').find('.widget-content.ibox-content').toggleClass('sk-loading');
        }
    });
});

window.tinymce.PluginManager.add('products', function (editor) {
    editor.addButton('add_product_link', {
        title: 'Продукты',
        image: '/admin/images/tinymce-button-products-add-widget.png',
        onclick: function () {
            editor.focus();

            let content = editor.selection.getContent();

            $('#choose_product_modal').off('click');
            $('#choose_product_modal').on('click', '.product-add', function (event) {
                event.preventDefault();

                let id = $(this).attr('data-product');

                editor.execCommand('mceInsertContent', false, '@productLink(\'' + id + '\', \'' + content + '\')');

                $('#choose_product_modal').modal('hide');
            });

            $('#choose_product_modal').modal();
        }
    });

    editor.addButton('add_product_button', {
        title: 'Продуктовая кнопка',
        image: '/admin/images/tinymce-button-products-add-button.png',
        onclick: function () {
            editor.focus();

            $('#choose_product_modal').off('click');
            $('#choose_product_modal').on('click', '.product-add', function (event) {
                event.preventDefault();

                let id = $(this).attr('data-product'),
                    product = $(this).attr('data-title');

                editor.execCommand('mceInsertContent', false, '@productButton(\'' + id + '\', \'' + product + '\')');

                $('#choose_product_modal').modal('hide');
            });

            $('#choose_product_modal').modal();
        }
    });

    editor.addButton('add_products_list', {
        title: 'Продуктовая подборка',
        icon: 'template',
        onclick: function() {
            let content = editor.selection.getContent();

            contentEditor = editor;

            if (content !== '' && ! /<img class="content-widget".+data-type="products.list".+\/>/g.test(content)) {
                swal({
                    title: "Ошибка",
                    text: "Необходимо выбрать виджет-подборка",
                    type: "error"
                });

                return false;
            } else if (content !== '') {
                $('#products_list_modal').find('.widget-content.ibox-content').toggleClass('sk-loading');

                productsListWidgetID = $(content).attr('data-id');

                window.Admin.modules.widgets.getWidget(productsListWidgetID, function (widget) {
                    let style = 'checklist';
                    if (typeof widget.params.style != 'undefined') {
                        style = widget.params.style;
                    }

                    productsList.find('select.select2').val(style);
                    productsList.find('select.select2').trigger('change');

                    let titles = widget.additional_info.titles;

                    widget.params.ids.forEach(function (id, index) {
                        addProductsToList({
                            id: id,
                            title: titles[index]
                        });
                    })

                    $('#products_list_modal').find('.widget-content.ibox-content').toggleClass('sk-loading');
                });
            } else {
                productsListWidgetID = '';
            }

            $('#products_list_modal').modal();
        }
    })
});

function addProductsToList(data) {
    let element = template.clone();

    element.attr('data-id', data.id);
    element.removeClass('product_item-tr-template');
    element.removeAttr('style');
    element.find('.product_item-title span').text(data.title);
    element.find('input').val(data.id);
    element.appendTo(productsList.find('.products-list > tbody'));
}

function dotToArray(field) {
    let parts = field.split('.'),
        result = '';

    parts.forEach(function (part, index) {
        result += part;

        result += (index === 0) ? '[' : '][';
    });

    return result.substr(0, result.length-1);
}
