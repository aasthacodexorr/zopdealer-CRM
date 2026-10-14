<template>
  <VueRouterView v-slot="{ Component, route }" :name="name">
    <component
      v-if="Component"
      :is="Component"
      :key="route.fullPath"
      v-bind="$attrs"
    />
  </VueRouterView>
</template>

<script>
import { RouterView as VueRouterView } from "vue-router";

export default {
  name: "RouterViewAdapter",
  inheritAttrs: false,
  compatConfig: {
    MODE: 3,
  },
  components: {
    VueRouterView,
  },
  props: {
    name: {
      type: String,
      default: undefined,
    },
  },
};
</script>
