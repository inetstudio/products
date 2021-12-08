<template>
    <tr class="product-item-tr">
        <td class="product-item-title">
            <span>{{ productItem.model.title }}</span>
        </td>
        <td width="10%">
            <div class="btn-nowrap">
                <a href="#" class="btn btn-xs btn-default edit-question m-r-xs" v-on:click.prevent.stop="edit"><i class="fa fa-pencil-alt"></i></a>
                <a href="#" class="btn btn-xs btn-danger delete-question" v-on:click.prevent.stop="remove"><i class="fa fa-times"></i></a>
            </div>
        </td>
    </tr>
</template>

<script>
  export default {
    name: 'ProductsPackageProductsItemsListItem',
    props: {
      productItem: {
        type: Object,
        required: true
      }
    },
    methods: {
      edit() {
        window.Admin.vue.helpers.initComponent('products_package_products_items', 'ProductsPackageProductsItemsListItemForm', {});

        window.Admin.vue.stores['products_package_products_items'].commit('setMode', 'edit_list_item');

        let productItem = JSON.parse(JSON.stringify(this.productItem));
        productItem.isModified = false;

        window.Admin.vue.stores['products_package_products_items'].commit('setProductItem', productItem);

        window.waitForElement('#questions_list_item_form_modal', function() {
          $('#questions_list_item_form_modal').modal();
        });
      },
      remove() {
        this.$emit('remove', {
          id: this.productItem.model.id,
        });
      },
    },
  };
</script>
