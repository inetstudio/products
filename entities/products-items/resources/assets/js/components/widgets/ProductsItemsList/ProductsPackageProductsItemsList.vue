<template>
  <div id="add_products_items_list_widget_modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal inmodal fade" ref="modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
          <h1 class="modal-title">Продуктовая подборка</h1>
        </div>

        <div class="modal-body">
          <div class="ibox-content" v-bind:class="{ 'sk-loading': options.loading }">
            <div class="sk-spinner sk-spinner-double-bounce">
              <div class="sk-double-bounce1"></div>
              <div class="sk-double-bounce2"></div>
            </div>

            <template v-if="options.ready">
              <base-dropdown
                  label="Оформление"
                  v-bind:attributes="{
                      label: 'text',
                      placeholder: 'Выберите тип оформления',
                      clearable: false,
                      reduce: option => option.value
                  }"
                  v-bind:options="options.listStyles"
                  v-bind:selected.sync="model.params.style"
              />
            </template>

            <a href="#" class="btn btn-xs btn-primary m-b-lg add_question" v-on:click.prevent="add">Добавить</a>

            <table class="table table-hover questions-list">
              <tbody>
              <template v-if="options.ready">
                <products-package-products-items-list-item
                    v-for="item in model.params.ids"
                    :key="item.model.id"
                    v-bind:item="item"
                    v-on:remove="remove"
                />
              </template>

              </tbody>
            </table>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
          <a href="#" class="btn btn-primary save">Сохранить</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import hash from 'object-hash';
import Swal from 'sweetalert2';

export default {
  name: 'ProductsPackageProductsItemsList',
  data() {
    return {
      model: this.getDefaultModel(),
      options: {
        loading: true,
        ready: false,
        listStyles: []
      }
    };
  },
  computed: {
    mode() {
      return window.Admin.vue.stores['products_package_products_items'].state.mode;
    }
  },
  watch: {
    mode: function(newMode, oldMode) {
      let component = this;

      if (newMode === 'save_list_item' && (oldMode === 'add_list_item' || oldMode === 'edit_list_item')) {
        component.saveItem();
      }
    }
  },
  methods: {
    getDefaultModel() {
      return _.merge(this.getDefaultWidgetModel(), {
        view: 'admin.module.products::front.partials.content.products_list_widget',
        params: {
          ids: [],
          style: ''
        }
      });
    },
    getDefaultStyle() {
      return _.get(_.head(this.options.listStyles), 'value', '');
    },
    initComponent() {
      let component = this;

      component.model = _.merge(component.model, this.widget.model);

      let url = route('back.admin-panel.config.get', 'products_package_products_items.list_styles');

      axios.post(url).then(response => {
        component.options.listStyles = response.data;
        component.model.params.style = this.getDefaultStyle();

        component.options.loading = false;
        component.options.ready = true;
      });
    },
    add() {
      window.Admin.vue.helpers.initComponent('products_package_products_items', 'ProductsPackageProductsItemsListItemForm', {});

      window.Admin.vue.stores['products_package_products_items'].commit('setMode', 'add_list_item');
      window.Admin.vue.stores['products_package_products_items'].commit('newProductItem');

      window.waitForElement('#products_items_list_item_form_modal', function() {
        $('#products_items_list_item_form_modal').modal();
      });
    },
    remove(payload) {
      Swal.fire({
        title: 'Вы уверены?',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Отмена',
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Да, удалить'
      }).then((result) => {
        if (result.value) {
          this.model.params.ids = _.remove(this.model.params.ids, function(question) {
            return question.model.id !== payload.id;
          });
        }
      });
    },
    saveItem() {
      let storeQuestion = JSON.parse(JSON.stringify(window.Admin.vue.stores['products_package_products_items'].state.question));
      storeQuestion.hash = hash(storeQuestion.model);

      let index = this.getQuestionIndex(storeQuestion.model.id);

      if (index > -1) {
        this.$set(this.model.params.ids, index, storeQuestion);
      } else {
        this.model.params.ids.push(storeQuestion);
      }
    },
    save() {
      let component = this;

      if (component.model.params.ids.length === 0) {
        $(component.$refs.modal).modal('hide');

        return;
      }

      component.saveWidget(function() {
        $(component.$refs.modal).modal('hide');
      });
    },
    getQuestionIndex(id) {
      return _.findIndex(this.model.params.ids, function(question) {
        return question.model.id === id;
      });
    }
  },
  created: function() {
    this.initComponent();
  },
  mounted() {
    let component = this;

    this.$nextTick(function() {
      $(component.$refs.modal).on('hide.bs.modal', function() {
        component.model.params.ids = [];
        component.model = component.getDefaultModel();
      });
    });
  },
  mixins: [
    window.Admin.vue.mixins['widget'],
  ]
};
</script>

<style scoped>

</style>
