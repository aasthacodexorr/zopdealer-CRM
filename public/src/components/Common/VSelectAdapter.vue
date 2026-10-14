<template>
  <VueSelect
    ref="innerSelect"
    v-bind="$attrs"
    :model-value="currentValue"
    @update:modelValue="onUpdate"
  >
    <template v-if="$slots.option" #option="slotProps">
      <slot name="option" v-bind="slotProps"></slot>
    </template>
    <template v-if="$slots['selected-option']" #selected-option="slotProps">
      <slot name="selected-option" v-bind="slotProps"></slot>
    </template>
    <template v-if="$slots['selected-option-container']" #selected-option-container="slotProps">
      <slot name="selected-option-container" v-bind="slotProps"></slot>
    </template>
    <template v-if="$slots['no-options']" #no-options="slotProps">
      <slot name="no-options" v-bind="slotProps"></slot>
    </template>
    <template v-if="$slots['open-indicator']" #open-indicator="slotProps">
      <slot name="open-indicator" v-bind="slotProps"></slot>
    </template>
    <template v-if="$slots.spinner" #spinner="slotProps">
      <slot name="spinner" v-bind="slotProps"></slot>
    </template>
    <template v-if="$slots.header" #header="slotProps">
      <slot name="header" v-bind="slotProps"></slot>
    </template>
    <template v-if="$slots.footer" #footer="slotProps">
      <slot name="footer" v-bind="slotProps"></slot>
    </template>
    <template v-if="$slots.search" #search="slotProps">
      <slot name="search" v-bind="slotProps"></slot>
    </template>
    <template v-if="$slots['list-header']" #list-header="slotProps">
      <slot name="list-header" v-bind="slotProps"></slot>
    </template>
    <template v-if="$slots['list-footer']" #list-footer="slotProps">
      <slot name="list-footer" v-bind="slotProps"></slot>
    </template>
  </VueSelect>
</template>

<script>
import VueSelect from "vue-select-original";

export default {
  name: "VSelectAdapter",
  inheritAttrs: false,
  compatConfig: {
    MODE: 3,
  },
  components: {
    VueSelect,
  },
  props: {
    modelValue: {
      required: false,
    },
    value: {
      required: false,
    },
  },
  emits: ["update:modelValue", "input", "change"],
  computed: {
    currentValue() {
      if (this.modelValue !== undefined) {
        return this.modelValue;
      }
      return this.value;
    },
  },
  methods: {
    onUpdate(val) {
      this.$emit("update:modelValue", val);
      this.$emit("input", val);
      this.$emit("change", val);
    },
    clearSelection() {
      if (this.$refs.innerSelect && typeof this.$refs.innerSelect.clearSelection === "function") {
        return this.$refs.innerSelect.clearSelection();
      }
    },
  },
};
</script>
