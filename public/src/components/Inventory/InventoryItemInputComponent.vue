<template>
  <span
    class="inventory-inline-editable"
    @mouseover="show_edit_button = true"
    @mouseleave="show_edit_button = false"
  >
    <span
      v-if="!edit_mode"
      class="inline-display-value"
      style="cursor: pointer"
      @click.stop="enableEditMode"
      title="Click to edit"
    >
      {{ renderedValue }}<slot></slot>
      <i
        v-show="show_edit_button"
        class="bx bx-edit-alt inline-pencil-icon"
      ></i>
    </span>
    <div v-else class="inline-edit-box" @click.stop>
      <input
        type="text"
        class="form-control form-control-sm inline-edit-input"
        :class="{
          'is-invalid': errors.hasOwnProperty(column),
        }"
        @keyup.enter="partialUpdate"
        @keyup.esc="resetEditMode"
        @keyup="delete errors[column]"
        :title="errors[column]"
        v-model="new_value"
        ref="inlineInput"
      />
      <span class="inline-edit-actions">
        <i
          class="bx bx-check text-success inline-action-btn"
          title="Save"
          @click.stop="partialUpdate"
        ></i>
        <i
          @click.stop="resetEditMode"
          class="bx bx-x text-danger inline-action-btn"
          title="Cancel"
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
  watch: {
    current_value(newVal) {
      this.rendered_value = newVal;
      this.new_value = newVal;
    },
  },
  computed: {
    renderedValue() {
      if (this.rendered_value == null || this.rendered_value === "") return "—";
      const num = Number(this.rendered_value);
      if (isNaN(num)) return this.rendered_value;
      if (this.$props.render_format && Object.keys(this.$props.render_format).length > 0) {
        return new Intl.NumberFormat("en-US", this.$props.render_format).format(num);
      }
      return new Intl.NumberFormat("en-US").format(num);
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
      this.$nextTick(() => {
        if (this.$refs.inlineInput) {
          this.$refs.inlineInput.focus();
        }
      });
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
          vm.$data.errors = (error.response && error.response.data) || {};
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

<style scoped lang="scss">
.inventory-inline-editable {
  display: inline-flex;
  align-items: center;
  position: relative;
  max-width: 100%;
}

.inline-display-value {
  display: inline-flex;
  align-items: center;
  gap: 3px;
  cursor: pointer;

  &:hover {
    color: #2563eb;
  }
}

.inline-pencil-icon {
  font-size: 13px;
  color: #64748b;
  margin-left: 2px;
  transition: color 0.15s ease;

  &:hover {
    color: #2563eb;
  }
}

.inline-edit-box {
  display: inline-flex;
  align-items: center;
  gap: 3px;
  background: #ffffff;
  z-index: 10;
}

.inline-edit-input {
  height: 24px !important;
  font-size: 0.8rem !important;
  padding: 1px 5px !important;
  width: 75px !important;
  border-radius: 4px !important;
  border: 1px solid #94a3b8 !important;
  color: #0f172a !important;
  background: #ffffff !important;

  &:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.2) !important;
  }
}

.inline-edit-actions {
  display: inline-flex;
  align-items: center;
  gap: 2px;
}

.inline-action-btn {
  font-size: 16px;
  cursor: pointer;
  line-height: 1;
  transition: transform 0.1s ease;

  &:hover {
    transform: scale(1.15);
  }
}
</style>
