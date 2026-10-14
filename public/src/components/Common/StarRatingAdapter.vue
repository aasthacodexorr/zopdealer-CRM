<template>
  <VueStarRating
    ref="innerRating"
    v-bind="$attrs"
    :rating="currentRating"
    @update:rating="onUpdateRating"
    @hover:rating="onHoverRating"
  >
    <template v-for="(_, slotName) in $slots" :key="slotName" #[slotName]="slotProps">
      <slot :name="slotName" v-bind="slotProps || {}"></slot>
    </template>
  </VueStarRating>
</template>

<script>
import VueStarRating from "vue-star-rating/dist/VueStarRating.common.js";

export default {
  name: "StarRatingAdapter",
  inheritAttrs: false,
  compatConfig: {
    MODE: 3,
  },
  components: {
    VueStarRating,
  },
  props: {
    rating: {
      type: [Number, String],
      required: false,
      default: undefined,
    },
    value: {
      type: [Number, String],
      required: false,
      default: undefined,
    },
    modelValue: {
      type: [Number, String],
      required: false,
      default: undefined,
    },
  },
  emits: [
    "update:rating",
    "update:modelValue",
    "input",
    "rating-selected",
    "hover:rating",
  ],
  computed: {
    currentRating() {
      if (this.rating !== undefined && this.rating !== null && this.rating !== "") {
        return Number(this.rating) || 0;
      }
      if (this.modelValue !== undefined && this.modelValue !== null && this.modelValue !== "") {
        return Number(this.modelValue) || 0;
      }
      if (this.value !== undefined && this.value !== null && this.value !== "") {
        return Number(this.value) || 0;
      }
      return 0;
    },
  },
  methods: {
    onUpdateRating(val) {
      this.$emit("update:rating", val);
      this.$emit("update:modelValue", val);
      this.$emit("input", val);
      this.$emit("rating-selected", val);
    },
    onHoverRating(val) {
      this.$emit("hover:rating", val);
    },
  },
};
</script>
