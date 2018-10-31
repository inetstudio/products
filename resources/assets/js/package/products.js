$(document).ready(function() {
    if ($('.products_wrapper').length > 0) {
        let productsComponents = [];

        $('.products_wrapper .products-list').each(function () {
            let wrapper = $(this).closest('.products_wrapper'),
                name = $(this).attr('id'),
                items = JSON.parse($(this).attr('data-items'));

            productsComponents[name] = new Vue({
                el: '#'+name,
                data: {
                    items: items
                },
                methods: {
                    remove: function (index) {
                        this.$delete(this.items, index);
                    }
                },
                computed: {
                    itemTitles: function() {
                        return this.items.map(function(item) {
                            return item.name;
                        });
                    },
                    itemIds: function() {
                        return this.items.map(function(item) {
                            return item.id;
                        });
                    }
                }
            });

            wrapper.find('table').on('click', '.product-add', function (event) {
                event.preventDefault();

                let id = $(this).attr('data-product'),
                    title = $(this).closest('tr').children('td').eq(2).text();

                if (! productsComponents[name].itemIds.includes(id)) {
                    productsComponents[name].items.push({
                        id: id,
                        name: title
                    });
                }
            });
        });
    }

    $('#submit-analytics-filter').on('click', function (event) {
        event.preventDefault();

        $('#dataTableBuilder').DataTable().ajax.reload();
    });

    $('#product_item_modal').on('hidden.bs.modal', function (e) {
        let modal = $(this);

        modal.find('.form-group').removeClass('has-error');
        modal.find('span.help-block').remove();

        modal.find('.modal-header h1').text('Создание продукта');
        modal.find('form').attr('action', route('back.products.items.store'));
        modal.find('input[name=_method]').val('POST');
        modal.find('input[name=product_item_id]').val('');

        modal.find('input').filter('[name!=_token][name!=_method][name!=product_item_id]').val('');

        window.tinymce.get('modal_product_item_content').setContent('');

        modal.find('.start-cropper').removeClass('btn-primary').addClass('btn-default');
        modal.find('.start-cropper').attr('data-crop-button', '');
        modal.find('.crop_buttons').hide();
        modal.find('.additional_fields').hide();

        modal.find('.preview img').attr('src', '').attr('data-src', 'holder.js/100px200?auto=yes&font=FontAwesome&text=');

        Holder.run({
            images: modal.find('.preview img').get(0)
        });

        Admin.containers.images['modal_product_item_content'].images = [];
    });

    $('#products_list_modal').on('hidden.bs.modal', function (e) {
        let modal = $(this);

        modal.find('select.select2').val('checklist');
        modal.find('select.select2').trigger('change');
        modal.find('.products-list tr[class!=product_item-tr-template]').remove();
    });
});
