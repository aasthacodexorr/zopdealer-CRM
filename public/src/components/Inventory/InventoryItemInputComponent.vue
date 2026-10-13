<template>
  <span
    @mouseover="show_edit_button = true"
    @mouseleave="show_edit_button = false"
  >
    <span v-if="!edit_mode" style="cursor: pointer" @click="enableEditMode"
      >{{ renderedValue }}<slot></slot>
      <i
        v-show="show_edit_button"
        style="font-size: 1.4em; vertical-align: bottom"
        class="bx text-secondary bx bx-edit-alt"
      ></i
    ></span>
    <div v-else>
      <input
        type="text"
        class="form-control"
        :class="{
          'is-invalid': errors.hasOwnProperty(column),
        }"
        @keyup="delete errors[column]"
        :title="errors[column]"
        v-model="new_value"
      />
      <span>
        <i
          style="font-size: 1.4em; cursor: pointer"
          class="bx text-success bx bx-save mt-50 mr-1"
          @click="partialUpdate"
        ></i>
        <i
          @click="resetEditMode"
          style="font-size: 1.4em; cursor: pointer"
          class="bx text-danger bx bx-x-circle mt-50"
        ></i>
      </span>
    </div>
  </span>
</template>
<script>
import Toast from "../../library/Toast";
import { mapActions } from "vuex";

export default {
  name: "InventoryItemInputComponent",
  props: {
    column: {
      required: true,
    },
    current_value: {
      required: true,
    },
    stock_no: {
      required: true,
    },
    render_format: {
      required: false,
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      edit_mode: false,
      new_value: 0,
      errors: [],
      rendered_value: 0,
      show_edit_button: false,
    };
  },
  computed: {
    renderedValue() {
      return this.rendered_value == null || this.rendered_value == ""
        ? ""
        : new Intl.NumberFormat("en-US", this.$props.render_format).format(
            this.rendered_value
          );
    },
  },
  methods: {
    ...mapActions("inventoryView", ["commitOnlyEntities"]),
    resetEditMode() {
      this.new_value = this.$props.current_value;
      this.edit_mode = false;
      this.errors = [];
    },
    enableEditMode() {
      this.new_value = this.$props.current_value;
      this.edit_mode = true;
    },
    partialUpdate() {
      const vm = this;

      let formData = new FormData();
      formData.append("stock_no", this.stock_no);
      formData.append("column", this.column);
      formData.append("value", this.new_value);

      let request_url = `${window.location.origin}/api/business/inventory/partial/update`;
      const toast = new Toast(`Updating inventory ...`);
      toast.fire();
      axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
          this.edit_mode = false;
          this.errors = [];
          this.rendered_value = this.new_value;
          this.commitOnlyEntities();
        })
        .catch(function (error) {
          let error_message = `There was some error updating the inventory`;
          vm.$data.errors = error.response.data;
          toast.fail(error_message);
          toast.close();
        });
    },
  },
  created() {
    this.rendered_value = this.current_value;
  },
};
</script>