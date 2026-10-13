<template>
  <span
    style="display: block"
    @mouseover="show_edit_button = true"
    @mouseleave="show_edit_button = false"
  >
    <span v-if="!edit_mode" style="cursor: pointer" @click="enableEditMode"
      >{{ renderedValue }} &nbsp;&nbsp;&nbsp;&nbsp;<slot></slot>
      <i
        v-show="show_edit_button"
        style="font-size: 1.4em; vertical-align: bottom"
        class="bx text-secondary bx bx-edit-alt"
      ></i
    ></span>
    <div v-else>
      <select
        class="custom-select form-control mr-1"
        :class="{
          'is-invalid': errors.hasOwnProperty(column),
        }"
        @keyup="delete errors[column]"
        :title="errors[column]"
        v-model="new_value"
      >
        <option value="" selected disabled>Select {{ column }}</option>
        <option
          :value="option.value"
          v-for="option in options"
          :key="option.value"
        >
          {{ option.label }}
        </option>
      </select>
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
import Toast from "../../../library/Toast";
import { mapActions } from "vuex";

export default {
  name: "LeadResponseItemSelectComponent",
  props: {
    column: {
      required: true,
    },
    current_value: {
      required: true,
    },
    lead_id: {
      required: true,
    },
    render_format: {
      required: false,
      type: Object,
      default: () => ({}),
    },
    options: {
      required: true,
      type: Array,
    },
  },
  data() {
    return {
      edit_mode: false,
      new_value: "",
      errors: [],
      rendered_value: "",
      show_edit_button: false,
    };
  },
  watch: {
    current_value: function(value) {
      this.rendered_value = value;
    },
  },
  computed: {
    renderedValue() {
      let label_from_options = this.options.filter(
        (option) => option.value == this.rendered_value,
      );

      if (label_from_options.length > 0) {
        return label_from_options[0]["label"];
      }

      return this.rendered_value;
    },
  },
  methods: {
    ...mapActions("leadResponseView", ["commitOnlyEntities"]),
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
      formData.append("lead_id", this.lead_id);
      formData.append("column", this.column);
      formData.append("value", this.new_value);

      let request_url = `${window.location.origin}/api/business/leads/responses/partial/update`;
      const toast = new Toast(`Updating lead ...`);
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
        .catch(function(error) {
          let error_message = `There was some error updating the lead`;
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
