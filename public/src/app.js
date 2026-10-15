import Vue, { createApp, configureCompat, reactive } from "vue";
window.Vue = Vue;
window._ = require("lodash");
window.axios = require("axios");

// Configure Vue compat mode (MODE: 2 defaults to Vue 2 behavior for unmigrated components)
configureCompat({
  MODE: 2,
});

import ClipboardPlugin from "./library/ClipboardPlugin.js";
import Helpers from "./library/Helpers.js";
import VueCookies from "vue-cookies";
import SweetalertPlugin from "./library/SweetalertPlugin.js";
import "sweetalert2/dist/sweetalert2.min.css";
import vueDebounce from "vue-debounce";
import AnalyticsPlugin from "./library/AnalyticsPlugin.js";
import router from "./router";
import store from "./store";
import MainFooter from "./components/Layout/Footer.vue";
import MainHeader from "./components/Layout/Header.vue";
import SideBar from "./components/Layout/Menu.vue";
import VSelectAdapter from "./components/Common/VSelectAdapter.vue";
import DatePickerAdapter from "./components/Common/DatePickerAdapter.vue";
import StarRatingAdapter from "./components/Common/StarRatingAdapter.vue";
import SliderAdapter from "./components/Common/SliderAdapter.vue";
import RouterLinkAdapter from "./components/Common/RouterLinkAdapter.vue";
import RouterViewAdapter from "./components/Common/RouterViewAdapter.vue";
import QuillEditor from "./components/Common/QuillEditor.vue";
import draggable from "vuedraggable";

// Helpers plugin adapter
const HelpersPlugin = {
  install(app) {
    if (app && app.config && app.config.globalProperties) {
      app.config.globalProperties.$helpers = Helpers;
    }
    // Retain compatibility bridge for Vue 2 / @vue/compat
    if (Vue) {
      Vue.helpers = Helpers;
      if (Vue.prototype) {
        Vue.prototype.$helpers = Helpers;
      }
    }
  },
};

// VueCookies plugin adapter
const VueCookiesPlugin = {
  install(app) {
    if (app && app.config && app.config.globalProperties) {
      app.config.globalProperties.$cookies = VueCookies;
    }
    window.$cookies = VueCookies;
    // Retain compatibility bridge for Vue 2 / @vue/compat
    if (Vue) {
      Vue.$cookies = VueCookies;
      if (Vue.prototype) {
        Vue.prototype.$cookies = VueCookies;
      }
    }
  },
};

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

const truncate = function(text, length, clamp) {
  return Helpers.truncate(text, length, clamp);
};

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

// 1. Install plugins once in a safe, deterministic order
app.use(store);
app.use(router);
app.use(HelpersPlugin);
app.use(VueCookiesPlugin);
app.use(SweetalertPlugin);
app.use(ClipboardPlugin);
app.use(AnalyticsPlugin, {
  config: { id: "G-3RLC81KJ0D" },
});

// 2. Shared instance properties on app.config.globalProperties
app.config.globalProperties.$helpers = Helpers;
app.config.globalProperties.$cookies = VueCookies;
app.config.globalProperties.$store = store;
app.config.globalProperties.$router = router;
app.config.globalProperties.$event = window.Event;
app.config.globalProperties.$filters = {
  truncate,
};
if (!Object.prototype.hasOwnProperty.call(app.config.globalProperties, "$route")) {
  Object.defineProperty(app.config.globalProperties, "$route", {
    get() {
      return router.currentRoute.value;
    },
    configurable: true,
  });
}

// 3. Intentionally retained compatibility bridges for Vue 2 / @vue/compat
if (Vue && Vue.prototype) {
  Vue.prototype.$router = router;
  Vue.prototype.$store = store;
  if (!Object.prototype.hasOwnProperty.call(Vue.prototype, "$route")) {
    Object.defineProperty(Vue.prototype, "$route", {
      get() {
        return router.currentRoute.value;
      },
      configurable: true,
    });
  }
}
if (Vue && typeof Vue.filter === "function") {
  Vue.filter("truncate", truncate);
}

// 4. Register global components on app.component (with compatibility bridge on Vue.component)
const globalComponents = {
  SideBar,
  MainHeader,
  MainFooter,
  "v-select": VSelectAdapter,
  VSelect: VSelectAdapter,
  "date-picker": DatePickerAdapter,
  DatePicker: DatePickerAdapter,
  "star-rating": StarRatingAdapter,
  StarRating: StarRatingAdapter,
  "vue-slider": SliderAdapter,
  VueSlider: SliderAdapter,
  "router-link": RouterLinkAdapter,
  RouterLink: RouterLinkAdapter,
  "router-view": RouterViewAdapter,
  RouterView: RouterViewAdapter,
  "quill-editor": QuillEditor,
  QuillEditor: QuillEditor,
  draggable: draggable,
  Draggable: draggable,
};

for (const [name, component] of Object.entries(globalComponents)) {
  app.component(name, component);
  if (Vue && typeof Vue.component === "function") {
    Vue.component(name, component);
  }
}

// 5. Register global directives on app.directive (with compatibility bridge on Vue.directive)
const globalDirectives = {
  uppercase: uppercaseDirective,
  integer: integerDirective,
  decimal: decimalDirective,
  debounce: debounceDirective,
};

for (const [name, directive] of Object.entries(globalDirectives)) {
  app.directive(name, directive);
  if (Vue && typeof Vue.directive === "function") {
    Vue.directive(name, directive);
  }
}

// 6. Mount application
app.mount("#app");
