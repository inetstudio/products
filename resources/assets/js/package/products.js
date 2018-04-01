$(document).ready(function() {
    if ($('.products_wrapper').length > 0) {
        var wrapper = $(this),
            productsComponents = [];

        $('.products_wrapper .products-list').each(function () {
            var name = $(this).attr('id'),
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

                var id = $(this).attr('data-product'),
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
});
