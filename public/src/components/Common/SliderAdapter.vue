<template>
  <VueSlider
    ref="innerSlider"
    v-bind="forwardAttrs"
    :model-value="currentValue"
    @update:model-value="onUpdate"
    @change="onChange"
    @drag-start="onDragStart"
    @dragging="onDragging"
    @drag-end="onDragEnd"
    @error="onError"
  >
    <template v-for="(_, slotName) in $slots" :key="slotName" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps || {}"></slot>
    </template>
  </VueSlider>
</template>

<script>
import VueSlider from "vue-slider-component/dist/vue-slider-component.common.js";

export default {
  name: "SliderAdapter",
  inheritAttrs: false,
  compatConfig: {
    MODE: 3,
  },
  components: {
    VueSlider,
  },
  props: {
    value: {
      type: [Number, String, Array],
      required: false,
      default: undefined,
    },
    modelValue: {
      type: [Number, String, Array],
      required: false,
      default: undefined,
    },
  },
  emits: [
    "update:modelValue",
    "update:model-value",
    "input",
    "change",
    "drag-start",
    "dragging",
    "drag-end",
    "error",
  ],
  computed: {
    currentValue() {
      if (this.modelValue !== undefined && this.modelValue !== null) {
        return this.modelValue;
      }
      if (this.value !== undefined && this.value !== null) {
        return this.value;
      }
      return 0;
    },
    forwardAttrs() {
      return this.$attrs;
    },
  },
  methods: {
    onUpdate(val) {
      this.$emit("update:modelValue", val);
      this.$emit("update:model-value", val);
      this.$emit("input", val);
    },
    onChange(val) {
      this.$emit("change", val);
    },
    onDragStart(val) {
      this.$emit("drag-start", val);
    },
    onDragging(val) {
      this.$emit("dragging", val);
    },
    onDragEnd(val) {
      this.$emit("drag-end", val);
    },
    onError(type, message) {
      this.$emit("error", type, message);
    },
    setValue(val) {
      if (this.$refs.innerSlider && this.$refs.innerSlider.setValue) {
        return this.$refs.innerSlider.setValue(val);
      }
    },
    getValue() {
      if (this.$refs.innerSlider && this.$refs.innerSlider.getValue) {
        return this.$refs.innerSlider.getValue();
      }
      return this.currentValue;
    },
    getIndex() {
      if (this.$refs.innerSlider && this.$refs.innerSlider.getIndex) {
        return this.$refs.innerSlider.getIndex();
      }
    },
  },
};
</script>
