<template>
  <DatePickerNext
    ref="innerPicker"
    v-bind="forwardAttrs"
    :value="currentValue"
    @update:value="onUpdate"
    @change="onChange"
  >
    <template v-for="(_, slotName) in $slots" :key="slotName" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps || {}"></slot>
    </template>
  </DatePickerNext>
</template>

<script>
import DatePickerNext from "vue-datepicker-next";

const DatePickerAdapter = {
  name: "DatePickerAdapter",
  inheritAttrs: false,
  compatConfig: {
    MODE: 3,
  },
  components: {
    DatePickerNext,
  },
  props: {
    modelValue: {
      required: false,
    },
    value: {
      required: false,
    },
  },
  emits: ["update:modelValue", "input", "change", "update:value"],
  computed: {
    currentValue() {
      if (this.modelValue !== undefined) {
        return this.modelValue;
      }
      return this.value;
    },
    forwardAttrs() {
      const attrs = { ...this.$attrs };
      if (attrs.inputAttributes && !attrs.inputAttr) {
        attrs.inputAttr = attrs.inputAttributes;
        delete attrs.inputAttributes;
      }
      return attrs;
    },
  },
  methods: {
    onUpdate(val) {
      this.$emit("update:modelValue", val);
      this.$emit("input", val);
      this.$emit("update:value", val);
    },
    onChange(val, type) {
      this.$emit("change", val, type);
    },
    focus() {
      if (this.$refs.innerPicker && typeof this.$refs.innerPicker.focus === "function") {
        this.$refs.innerPicker.focus();
      }
    },
    blur() {
      if (this.$refs.innerPicker && typeof this.$refs.innerPicker.blur === "function") {
        this.$refs.innerPicker.blur();
      }
    },
    closePopup() {
      if (this.$refs.innerPicker && typeof this.$refs.innerPicker.closePopup === "function") {
        this.$refs.innerPicker.closePopup();
      }
    },
    openPopup() {
      if (this.$refs.innerPicker && typeof this.$refs.innerPicker.openPopup === "function") {
        this.$refs.innerPicker.openPopup();
      }
    },
  },
};

if (DatePickerNext) {
  if (typeof DatePickerNext.locale === "function") {
    DatePickerAdapter.locale = DatePickerNext.locale;
  }
  DatePickerAdapter.Calendar = DatePickerNext.Calendar;
  DatePickerAdapter.CalendarRange = DatePickerNext.CalendarRange;
  DatePickerAdapter.TimePanel = DatePickerNext.TimePanel;
  DatePickerAdapter.TimeRange = DatePickerNext.TimeRange;
  DatePickerAdapter.DateTime = DatePickerNext.DateTime;
  DatePickerAdapter.DateTimeRange = DatePickerNext.DateTimeRange;
}

export default DatePickerAdapter;
</script>
