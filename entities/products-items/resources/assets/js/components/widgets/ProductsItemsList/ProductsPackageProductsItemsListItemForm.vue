<template>
    <div id="products_items_list_item_form_modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal inmodal fade" ref="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                      <span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span>
                    </button>
                    <h1 class="modal-title">Создание продукта</h1>
                </div>
                <div class="modal-body">
                    <div class="ibox-content" v-bind:class="{ 'sk-loading': options.loading }">
                        <div class="sk-spinner sk-spinner-double-bounce">
                            <div class="sk-double-bounce1"></div>
                            <div class="sk-double-bounce2"></div>
                        </div>

                        <base-input-text
                            label = "Заголовок"
                            name = "title"
                            v-bind:value.sync = "productItem.model.title"
                        />

                        <template v-if="options.ready">
                          <image-uploader
                              label="Превью"
                              name="preview"
                              :image-prop="productItem.model.preview"
                              v-on:update:image="updateImage"
                          />
                        </template>

                        <base-wysiwyg
                            label="Контент"
                            name="product_item_content"
                            v-bind:value.sync="productItem.model.content"
                            v-bind:attributes="{
                                'id': 'product_item_content',
                                'cols': '50',
                                'rows': '10',
                                'hasImages': true
                            }"
                        />

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                    <a href="#" class="btn btn-primary" v-on:click.prevent="save">Сохранить</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import hash from 'object-hash';

  export default {
    name: 'ProductsPackageProductsItemsListItemForm',
    data() {
      return {
        productItem: {},
        options: {
          loading: true,
          ready: false
        },
      };
    },
    watch: {
      'productItem.model': {
        handler: function(newValue, oldValue) {
          this.productItem.isModified = !(!newValue || this.productItem.hash === hash(newValue));
        },
        deep: true,
      }
    },
    methods: {
      initComponent: function() {
        let component = this;

        component.loadObject();
        component.loadPreviewProperties();
      },
      loadObject() {
        let component = this;

        component.productItem = _.cloneDeep(window.Admin.vue.stores['products_package_products_items'].state.productItem);
        component.options.loading = false;
      },
      loadPreviewProperties: function () {
        let component = this;

        axios
            .post(route('back.admin-panel.config.get', 'products_package_products_items.images.properties.preview'))
            .then(response => {
              component.productItem.model.preview.properties = response.data.map(property => { property.value = ''; return property; });

              return axios.post(route('back.admin-panel.config.get', 'products_package_products_items.images.crops.product_item.preview'));
            })
            .then(response => {
              component.productItem.model.preview.manipulations.crops = response.data.map(crop => { crop.value = ''; return crop; });

              component.options.loading = false;
              component.options.ready = true;
            });
      },
      save() {
        let component = this;

        if (component.question.isModified && component.question.model.title.trim() !== '' && component.question.model.answer.trim() !== '') {
          component.options.loading = true;

          window.Admin.vue.stores['products_package_products_items'].commit('setQuestion', JSON.parse(JSON.stringify(component.question)));
          window.Admin.vue.stores['products_package_products_items'].commit('setMode', 'save_list_item');

          component.options.loading = false;
        }

        $(component.$refs.modal).modal('hide');
      },
      updateImage(payload) {
        let component = this;

        component.productItem.model[payload.name] = payload.image;
      }
    },
    created: function() {
      this.initComponent();
    },
    mounted() {
      let component = this;

      this.$nextTick(function() {
        $(component.$refs.modal).on('show.bs.modal', function () {
          component.loadObject();

          window.tinymce.get('product_item_content').setContent(component.productItem.model.content);
        });
      });
    }
  };
</script>

<style scoped>

</style>
