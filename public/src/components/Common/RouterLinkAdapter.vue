<template>
  <component
    :is="actualTag"
    :class="computedClasses"
    :href="actualTag === 'a' ? href : undefined"
    :type="actualTag === 'button' ? (type || 'button') : undefined"
    v-bind="filteredAttrs"
    @click="handleClick"
  >
    <slot
      :href="href"
      :route="route"
      :navigate="navigate"
      :isActive="isActive"
      :isExactActive="isExactActive"
    />
  </component>
</template>

<script>
import { computed } from "vue";
import { useLink, useRouter } from "vue-router";

export default {
  name: "RouterLinkAdapter",
  inheritAttrs: false,
  compatConfig: {
    MODE: 3,
  },
  props: {
    to: {
      type: [String, Object],
      required: true,
    },
    replace: {
      type: Boolean,
      default: false,
    },
    tag: {
      type: String,
      default: "a",
    },
    exact: {
      type: Boolean,
      default: false,
    },
    activeClass: {
      type: String,
      default: undefined,
    },
    exactActiveClass: {
      type: String,
      default: undefined,
    },
    ariaCurrentValue: {
      type: String,
      default: "page",
    },
    event: {
      type: [String, Array],
      default: "click",
    },
    type: {
      type: String,
      default: undefined,
    },
  },
  setup(props, { attrs }) {
    const router = useRouter();
    const { route, href, isActive, isExactActive, navigate } = useLink(props);

    const actualTag = computed(() => props.tag || "a");

    const isLinkActive = computed(() => {
      return props.exact ? isExactActive.value : isActive.value;
    });

    const computedClasses = computed(() => {
      const activeCls =
        props.activeClass ||
        (router && router.options && router.options.linkActiveClass) ||
        "active";
      const exactActiveCls =
        props.exactActiveClass ||
        (router && router.options && router.options.linkExactActiveClass) ||
        "router-link-exact-active";

      const list = [];
      if (attrs.class) {
        list.push(attrs.class);
      }
      if (isLinkActive.value && activeCls) {
        list.push(activeCls);
      }
      if (isExactActive.value && exactActiveCls) {
        list.push(exactActiveCls);
      }
      return list.filter(Boolean).join(" ");
    });

    const filteredAttrs = computed(() => {
      const copy = { ...attrs };
      delete copy.class;
      return copy;
    });

    function handleClick(e) {
      if (attrs.onClick) {
        if (Array.isArray(attrs.onClick)) {
          attrs.onClick.forEach((fn) => fn(e));
        } else if (typeof attrs.onClick === "function") {
          attrs.onClick(e);
        }
      }
      if (e && !e.defaultPrevented) {
        navigate(e);
      }
    }

    return {
      actualTag,
      href,
      route,
      isActive,
      isExactActive,
      navigate,
      computedClasses,
      filteredAttrs,
      handleClick,
    };
  },
};
</script>
