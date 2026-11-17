<template>
  <span
    style="display: block"
    @mouseover="show_edit_button = true"
    @mouseleave="show_edit_button = false"
  >
    <span
      v-if="!edit_mode"
      class="lr-inline-edit"
      :title="'Click to change ' + column"
      @click="enableEditMode"
    >
      <span v-if="display === 'pill'" class="lr-pill" :class="pillClass">{{
        renderedValue || "—"
      }}</span>
      <span v-else-if="display === 'user'" class="lr-user">
        <template v-if="renderedValue">
          <span class="lr-avatar lr-avatar-sm" :style="userAvatarStyle">{{
            userInitials
          }}</span>
          {{ renderedValue }}
        </template>
        <span v-else class="lr-unassigned">Unassigned</span>
      </span>
      <span v-else>{{ renderedValue }}</span>
      <slot></slot>
      <i class="bx bx-edit-alt lr-edit-hint"></i>
    </span>
    <div v-else class="lr-inline-form" @click.stop>
      <select
        class="custom-select form-control"
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
      <i
        class="bx bx-check-circle text-success"
        title="Save"
        @click="partialUpdate"
      ></i>
      <i
        @click="resetEditMode"
        title="Cancel"
        class="bx bx-x-circle text-danger"
      ></i>
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
    /** "text" (default), "pill" for status badges, "user" for assignee avatars */
    display: {
      required: false,
      type: String,
      default: "text",
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
    pillClass() {
      const value = String(this.rendered_value || "").toLowerCase();
      if (value === "won") return "lr-pill--green";
      if (value === "new") return "lr-pill--blue";
      if (value.includes("appointment")) return "lr-pill--purple";
      if (value.includes("junk") || value.includes("lost")) return "lr-pill--red";
      if (value.includes("interested") || value.includes("follow up")) return "lr-pill--amber";
      if (value === "contacted" || value.includes("future")) return "lr-pill--teal";
      return "lr-pill--gray";
    },
    userInitials() {
      const parts = String(this.renderedValue || "").trim().split(/\s+/);
      return ((parts[0] || "")[0] || "").concat((parts[1] || "")[0] || "").toUpperCase() || "?";
    },
    userAvatarStyle() {
      const palette = [
        ["#e6efff", "#2f62d6"],
        ["#e3f6e8", "#1e8a43"],
        ["#fff3d1", "#a26a00"],
        ["#efe8ff", "#6b46c1"],
        ["#def6f3", "#11806f"],
        ["#fde6ef", "#b83268"],
      ];
      const value = String(this.renderedValue || "");
      let hash = 0;
      for (let i = 0; i < value.length; i++) {
        hash = (hash * 31 + value.charCodeAt(i)) >>> 0;
      }
      const [background, color] = palette[hash % palette.length];
      return { background, color };
    },
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
