import Vue, { createApp, configureCompat, reactive } from "vue";
window.Vue = Vue;
window._ = require("lodash");
window.axios = require("axios");

// Configure Vue compat mode (MODE: 2 defaults to Vue 2 behavior for unmigrated components)
configureCompat({
  MODE: 2,
});

import ClipboardPlugin from "./library/ClipboardPlugin.js";
Vue.use(ClipboardPlugin);

import Helpers from "./library/Helpers.js";
const plugin = {
  install(app) {
    Vue.helpers = Helpers;
    Vue.prototype.$helpers = Helpers;
    if (app && app.config && app.config.globalProperties) {
      app.config.globalProperties.$helpers = Helpers;
    }
  },
};
Vue.use(plugin);


import VueCookies from "vue-cookies";
Vue.use(VueCookies);
window.$cookies = VueCookies;

import SweetalertPlugin from "./library/SweetalertPlugin.js";
import "sweetalert2/dist/sweetalert2.min.css";
Vue.use(SweetalertPlugin);

import vueDebounce from "vue-debounce";
const rawDebounce = typeof vueDebounce === "function" ? vueDebounce() : (vueDebounce.default ? vueDebounce.default() : vueDebounce);
const debounceDirective = {
  ...rawDebounce,
  bind(el, binding, vnode) {
    if (rawDebounce && rawDebounce.created) {
      rawDebounce.created(el, binding, vnode);
    }
  },
  inserted(el, binding, vnode) {
    if (rawDebounce && rawDebounce.mounted) {
      rawDebounce.mounted(el, binding, vnode);
    }
  },
  created(el, binding, vnode) {
    if (rawDebounce && rawDebounce.created) {
      rawDebounce.created(el, binding, vnode);
    }
  },
  mounted(el, binding, vnode) {
    if (rawDebounce && rawDebounce.mounted) {
      rawDebounce.mounted(el, binding, vnode);
    }
  },
};
Vue.directive("debounce", debounceDirective);

import AnalyticsPlugin from "./library/AnalyticsPlugin.js";
Vue.use(AnalyticsPlugin, {
  config: { id: "G-3RLC81KJ0D" },
});

const truncate = function(text, length, clamp) {
  return Helpers.truncate(text, length, clamp);
};
Vue.filter("truncate", truncate);

function triggerInputEvent(element) {
  const event = document.createEvent("Event");
  event.initEvent("input", true, true);
  element.dispatchEvent(event);
}

const uppercaseDirective = {
  mounted(el) {
    const handler = function() {
      const formattedValue = el.value.toUpperCase();
      if (el.value !== formattedValue) {
        el.value = formattedValue;
        triggerInputEvent(el);
      }
    };
    el.__uppercaseHandler__ = handler;
    el.addEventListener("input", handler);
  },
  unmounted(el) {
    if (el.__uppercaseHandler__) {
      el.removeEventListener("input", el.__uppercaseHandler__);
      delete el.__uppercaseHandler__;
    }
  },
  inserted(el) {
    this.mounted(el);
  },
  unbind(el) {
    this.unmounted(el);
  },
};
Vue.directive("uppercase", uppercaseDirective);

const integerDirective = {
  beforeMount(el) {
    const handleInput = () => {
      const originalValue = el.value;
      const sanitizedValue = originalValue
        .replace(/[^\d-]/g, "")
        .replace(/(?!^)-/g, "");

      if (originalValue !== sanitizedValue) {
        el.value = sanitizedValue;
        triggerInputEvent(el);
      }
    };

    const handleBlur = () => {
      let default_value = el?.dataset?.defaultValue ?? 1;
      if (el.value === "" || el.value === "-") {
        el.value = default_value;
        triggerInputEvent(el);
      }
    };

    el.__integerInputHandler__ = handleInput;
    el.__integerBlurHandler__ = handleBlur;

    el.addEventListener("input", handleInput);
    el.addEventListener("blur", handleBlur);
  },
  mounted(el) {
    if (el.value === "" || el.value === "-") {
      el.value = "1";
      triggerInputEvent(el);
    }
  },
  unmounted(el) {
    if (el.__integerInputHandler__) {
      el.removeEventListener("input", el.__integerInputHandler__);
      delete el.__integerInputHandler__;
    }
    if (el.__integerBlurHandler__) {
      el.removeEventListener("blur", el.__integerBlurHandler__);
      delete el.__integerBlurHandler__;
    }
  },
  bind(el) {
    this.beforeMount(el);
  },
  inserted(el) {
    this.mounted(el);
  },
  unbind(el) {
    this.unmounted(el);
  },
};
Vue.directive("integer", integerDirective);

const decimalDirective = {
  beforeMount(el) {
    const handleInput = () => {
      const originalValue = el.value;

      let sanitizedValue = originalValue
        .replace(/[^\d.-]/g, "")
        .replace(/(?!^)-/g, "");

      const parts = sanitizedValue.split(".");
      if (parts.length > 2) {
        sanitizedValue = `${parts.shift()}.${parts.join("")}`;
      }

      if (originalValue !== sanitizedValue) {
        el.value = sanitizedValue;
        triggerInputEvent(el);
      }
    };

    const handleBlur = () => {
      let default_value = el?.dataset?.defaultValue ?? 1;

      if (
        el.value === "" ||
        el.value === "-" ||
        el.value === "." ||
        el.value === "-."
      ) {
        el.value = default_value;
        triggerInputEvent(el);
      }
    };

    el.__decimalInputHandler__ = handleInput;
    el.__decimalBlurHandler__ = handleBlur;

    el.addEventListener("input", handleInput);
    el.addEventListener("blur", handleBlur);
  },
  mounted(el) {
    if (el.value === "" || el.value === "-") {
      el.value = "1";
      triggerInputEvent(el);
    }
  },
  unmounted(el) {
    if (el.__decimalInputHandler__) {
      el.removeEventListener("input", el.__decimalInputHandler__);
      delete el.__decimalInputHandler__;
    }
    if (el.__decimalBlurHandler__) {
      el.removeEventListener("blur", el.__decimalBlurHandler__);
      delete el.__decimalBlurHandler__;
    }
  },
  bind(el) {
    this.beforeMount(el);
  },
  inserted(el) {
    this.mounted(el);
  },
  unbind(el) {
    this.unmounted(el);
  },
};
Vue.directive("decimal", decimalDirective);

import router from "./router";
import MainFooter from "./components/Layout/Footer.vue";
import MainHeader from "./components/Layout/Header.vue";
import SideBar from "./components/Layout/Menu.vue";
import store from "./store";
import VSelectAdapter from "./components/Common/VSelectAdapter.vue";
Vue.component("v-select", VSelectAdapter);
Vue.component("VSelect", VSelectAdapter);
import DatePickerAdapter from "./components/Common/DatePickerAdapter.vue";
Vue.component("date-picker", DatePickerAdapter);
Vue.component("DatePicker", DatePickerAdapter);
import StarRatingAdapter from "./components/Common/StarRatingAdapter.vue";
Vue.component("star-rating", StarRatingAdapter);
Vue.component("StarRating", StarRatingAdapter);
import SliderAdapter from "./components/Common/SliderAdapter.vue";
Vue.component("vue-slider", SliderAdapter);
Vue.component("VueSlider", SliderAdapter);
import RouterLinkAdapter from "./components/Common/RouterLinkAdapter.vue";
Vue.component("router-link", RouterLinkAdapter);
Vue.component("RouterLink", RouterLinkAdapter);
import RouterViewAdapter from "./components/Common/RouterViewAdapter.vue";
Vue.component("router-view", RouterViewAdapter);
Vue.component("RouterView", RouterViewAdapter);
import QuillEditor from "./components/Common/QuillEditor.vue";
Vue.component("quill-editor", QuillEditor);
Vue.component("QuillEditor", QuillEditor);
import draggable from "vuedraggable";
Vue.component("draggable", draggable);
Vue.component("Draggable", draggable);

Vue.prototype.$router = router;
Object.defineProperty(Vue.prototype, "$route", {
  get() {
    return router.currentRoute.value;
  },
  configurable: true,
});

/***Declaring variables here **/
const base_url = window.location.origin;
let global = reactive({
  guest: true,
  user: "",
  token: "",
  app_metadata: {},
  user_metadata: {},
  base_url: base_url,
  hostname: window.location.hostname,
  cdn: "",
});
window.global = global;

/**
 * Universal EventBus Implementation (Compatible with Vue 2 and Vue 3)
 **/
class EventBus {
  constructor() {
    this.events = Object.create(null);
    this.vue = {
      $emit: (event, ...args) => this.fire(event, ...args),
      $on: (event, callback) => this.listen(event, callback),
      $off: (event, callback) => this.off(event, callback),
      $once: (event, callback) => {
        const onceWrapper = (...args) => {
          this.off(event, onceWrapper);
          callback(...args);
        };
        this.listen(event, onceWrapper);
      },
    };
  }

  fire(event, ...args) {
    if (!this.events[event]) return;
    const listeners = [...this.events[event]];
    for (let i = 0; i < listeners.length; i++) {
      try {
        listeners[i](...args);
      } catch (err) {
        console.error(`Error in event listener for "${event}":`, err);
      }
    }
  }

  listen(event, callback) {
    if (!this.events[event]) {
      this.events[event] = [];
    }
    this.events[event].push(callback);
    return () => this.off(event, callback);
  }

  off(event, callback) {
    if (!event) {
      this.events = Object.create(null);
      return;
    }
    if (!callback) {
      delete this.events[event];
      return;
    }
    if (this.events[event]) {
      this.events[event] = this.events[event].filter((cb) => cb !== callback);
      if (this.events[event].length === 0) {
        delete this.events[event];
      }
    }
  }
}

window.Event = new EventBus();

router.beforeEach((to, from, next) => {
  if (!window.hasOwnProperty("cdn")) {
    axios({
      method: "get",
      url: window.location.origin + "/api/admin/get/cdn",
    })
      .then(({ data }) => {
        window.global.cdn = data.name;
      })
      .catch((e) => {
        console.log(e);
      });
  }

  //this will check for all the path
  if (window.$cookies && window.$cookies.isKey("_token")) {
    let auth = base_url + "/api/admin/details";
    axios({
      method: "get",
      url: auth,
      headers: {
        Authorization: "Bearer " + window.$cookies.get("_token"),
        Accept: "application/json",
      },
    })
      .then(function(response) {
        window.global.app_metadata = response.data.app_metadata;
        window.global.user_metadata = response.data.user_metadata;
        window.global.guest = false;
        window.global.user = response.data;
        if (to.path == "/api/admin/login") {
          next("/");
        } else {
          next();
        }
      })
      .catch((error) => {
        if (window.$cookies) {
          window.$cookies.set("_token", "", "0", "/", true);
          window.$cookies.set("_access_token", "", "0", "/", true);
        }
        window.global.user = "";
        window.global.guest = true;
        if (to.path == "/api/admin/login") {
          next();
        } else {
          window.location = base_url + "/api/admin/login";
          next(false);
        }
      });
  } else {
    if (to.path == "/api/admin/login") {
      next();
    } else {
      window.location = base_url + "/api/admin/login";
      next(false);
    }
  }
});

const RootComponent = {
  components: {
    SideBar,
    MainHeader,
    MainFooter,
  },
  data() {
    return {
      global: window.global,
    };
  },
  router,
  store,
  computed: {
    application() {
      let application_name = "";
      let g = window.global || this.global;
      if (g && g.hostname) {
        let hostname = g.hostname.split(".");
        let org = (g.app_metadata && g.app_metadata.organization) ? g.app_metadata.organization : "";
        if (hostname.length > 2 && hostname[0] != "www") {
          application_name =
            (hostname[0] == org || org == "zopdealer")
              ? "tenant"
              : "";
        } else {
          application_name =
            (org == "zopdealer") ? "main" : "";
        }
        return application_name;
      }
      return "";
    },
    app_metadata() {
      let g = window.global || this.global;
      return (g && g.app_metadata) ? g.app_metadata : {};
    },
    user_metadata() {
      let g = window.global || this.global;
      return (g && g.user_metadata) ? g.user_metadata : {};
    },
  },
  created() {},
};

const app = createApp(RootComponent);

app.use(router);
app.use(store);
app.use(SweetalertPlugin);
app.use(ClipboardPlugin);
app.use(AnalyticsPlugin, {
  config: { id: "G-3RLC81KJ0D" },
});

if (Vue && Vue.prototype) {
  Vue.prototype.$store = store;
}

// Ensure global properties are bound to Vue 3 app instance
app.config.globalProperties.$helpers = Helpers;
if (window.$cookies) {
  app.config.globalProperties.$cookies = window.$cookies;
}

app.component("SideBar", SideBar);
app.component("MainHeader", MainHeader);
app.component("MainFooter", MainFooter);
app.component("v-select", VSelectAdapter);
app.component("VSelect", VSelectAdapter);
app.component("date-picker", DatePickerAdapter);
app.component("DatePicker", DatePickerAdapter);
app.component("star-rating", StarRatingAdapter);
app.component("StarRating", StarRatingAdapter);
app.component("vue-slider", SliderAdapter);
app.component("VueSlider", SliderAdapter);
app.component("RouterLink", RouterLinkAdapter);
app.component("router-link", RouterLinkAdapter);
app.component("RouterView", RouterViewAdapter);
app.component("router-view", RouterViewAdapter);
app.component("quill-editor", QuillEditor);
app.component("QuillEditor", QuillEditor);
app.component("draggable", draggable);
app.component("Draggable", draggable);

app.directive("uppercase", uppercaseDirective);
app.directive("integer", integerDirective);
app.directive("decimal", decimalDirective);
app.directive("debounce", debounceDirective);

app.mount("#app");
