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

                //editor.execCommand('mceInsertContent', false, '{{ product(' + id + ', \'' + content + '\') }}');
                editor.execCommand('mceInsertContent', false, '@productLink(\'' + id + '\', \'' + content + '\')');

                $('#choose_product_modal').modal('hide');
            });

            $('#choose_product_modal').modal();
        }
    })
});
