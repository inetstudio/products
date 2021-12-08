import {productsItems} from './package/init';

require('./plugins/tinymce/plugins/products_package_products_items');

require('../../../../../../widgets/entities/widgets/resources/assets/js/mixins/widget');

require('./stores/store');

window.Vue.component(
    'ProductsPackageProductsItemsList',
    () => import('./components/widgets/ProductsItemsList/ProductsPackageProductsItemsList.vue'),
);

window.Vue.component(
    'ProductsPackageProductsItemsListItem',
    () => import('./components/widgets/ProductsItemsList/ProductsPackageProductsItemsListItem.vue'),
);

window.Vue.component(
    'ProductsPackageProductsItemsListItemForm',
    () => import('./components/widgets/ProductsItemsList/ProductsPackageProductsItemsListItemForm.vue'),
);

productsItems.init();
