window.tinymce.PluginManager.add('products_package_products', function (editor) {
    editor.addButton('add_product_link', {
        title: 'Продукты',
        icon: 'fa fa-shopping-cart',
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
        icon: 'fa fa-money-bill-alt',
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
});
