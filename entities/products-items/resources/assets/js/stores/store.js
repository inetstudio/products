import hash from 'object-hash';
import { v4 as uuidv4 } from 'uuid';

window.Admin.vue.stores['products_package_products_items'] = new window.Vuex.Store({
    state: {
        productItem: {
            model: {},
            errors: {},
            hash: '',
        },
        productsItems: [],
        mode: '',
    },
    mutations: {
        setProductItem(state, productItem) {
            let productItemCopy = JSON.parse(JSON.stringify(productItem));

            state.productItem.model = (productItemCopy.hasOwnProperty('model')) ? productItemCopy.model : productItemCopy;
            state.productItem.hash = hash(state.productItem.model);
        },
        newProductItem(state) {
            let productItemId = uuidv4();

            let productItem = {
                id: productItemId,
                title: '',
                preview: {
                    id: 0,
                    src: {
                        temp: {
                            path: '',
                            name: ''
                        },
                        file: {
                            path: '',
                            name: ''
                        }
                    },
                    manipulations: {
                        crops: []
                    },
                    properties: []
                },
                content: '',
            };

            this.commit('setProductItem', productItem);
        },
        setProductsItems(state, productsItems) {
            state.productsItems = productsItems;
        },
        setMode(state, mode) {
            state.mode = mode;
        },
        reset(state) {
            state.mode = '';
            state.productItem = {
                model: {},
                errors: {},
                hash: ''
            };
            state.productsItems = [];
        }
    }
});
