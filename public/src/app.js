window.Vue = require("vue");
window._ = require("lodash");
window.axios = require("axios");

import VueClipboard from "vue-clipboard2";
Vue.use(VueClipboard);

import Helpers from "./library/Helpers.js";
const plugin = {
  install() {
    Vue.helpers = Helpers;
    Vue.prototype.$helpers = Helpers;
  },
};

Vue.use(plugin);

import VueRouter from "vue-router";
Vue.use(VueRouter);

import VueCookies from "vue-cookies";
Vue.use(VueCookies);

import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
Vue.use(VueSweetalert2);

import vueDebounce from "vue-debounce";
Vue.use(vueDebounce);

import VueGtag from "vue-gtag";
Vue.use(VueGtag, {
  config: { id: "G-3RLC81KJ0D" },
});

const truncate = function(text, length, clamp) {
  clamp = clamp || "...";
  var node = document.createElement("div");
  node.innerHTML = text;
  var content = node.textContent;
  return content.length > length ? content.slice(0, length) + clamp : content;
};
Vue.filter("truncate", truncate);

Vue.directive("uppercase", {
  inserted(el) {
    const handler = function() {
      const formattedValue = el.value.toUpperCase();
      if (el.value !== formattedValue) {
        el.value = formattedValue;

        // Compatible event dispatch for Vue 2 / older browsers
        const event = document.createEvent("HTMLEvents");
        event.initEvent("input", true, false);
        el.dispatchEvent(event);
      }
    };
    el.__uppercaseHandler__ = handler;
    el.addEventListener("input", handler);
  },
  unbind(el) {
    el.removeEventListener("input", el.__uppercaseHandler__);
    delete el.__uppercaseHandler__;
  },
});

function triggerInputEvent(element) {
  const event = document.createEvent("Event");
  event.initEvent("input", true, true);
  element.dispatchEvent(event);
}

Vue.directive("integer", {
  bind(el) {
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

  inserted(el) {
    if (el.value === "" || el.value === "-") {
      el.value = "1";
      triggerInputEvent(el);
    }
  },

  unbind(el) {
    el.removeEventListener("input", el.__integerInputHandler__);
    el.removeEventListener("blur", el.__integerBlurHandler__);

    delete el.__integerInputHandler__;
    delete el.__integerBlurHandler__;
  },
});

Vue.directive("decimal", {
  bind(el) {
    const handleInput = () => {
      const originalValue = el.value;

      let sanitizedValue = originalValue
        .replace(/[^\d.-]/g, "") // allow digits, minus and decimal point
        .replace(/(?!^)-/g, ""); // allow minus only at the beginning

      // Allow only one decimal point
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

  inserted(el) {
    if (el.value === "" || el.value === "-") {
      el.value = "1";
      triggerInputEvent(el);
    }
  },

  unbind(el) {
    el.removeEventListener("input", el.__decimalInputHandler__);
    el.removeEventListener("blur", el.__decimalBlurHandler__);

    delete el.__decimalInputHandler__;
    delete el.__decimalBlurHandler__;
  },
});

import router from "./router";
import MainFooter from "./components/Layout/Footer.vue";
import MainHeader from "./components/Layout/Header.vue";
import SideBar from "./components/Layout/Menu.vue";
import store from "./store";

// import VueQuill from 'vue-quill';
// Vue.use(VueQuill);

/***Declaring variables here **/
const base_url = window.location.origin;
let global = {
  guest: true,
  user: "",
  token: "",
  app_metadata: "",
  user_metadata: "",
  base_url: base_url,
  hostname: window.location.hostname,
  cdn: "",
};
window.global = global;

/**
 * Event Definition
 * **/
window.Event = new (class {
  constructor() {
    this.vue = new Vue();
  }
  fire(event, data = null) {
    this.vue.$emit(event, data);
  }
  listen(event, callback) {
    this.vue.$on(event, callback);
  }
})();

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
  if (window.$cookies.isKey("_token")) {
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
        // console.log(response);
        //check if the user is from the subdomain or from zopdealer.
        // If both of these conditions do not satisfy throw error.
        window.global.app_metadata = response.data.app_metadata;
        window.global.user_metadata = response.data.user_metadata;
        window.global.guest = false;
        window.global.user = response.data;
        if (to.path == "/api/admin/login") {
          //If user types login url then send it to dashboard
          next("/");
        } else {
          //goto the next route
          next();
        }
      })
      .catch((error) => {
        //Error remove the token.
        window.$cookies.set("_token", "", "0", "/", true);
        window.$cookies.set("_access_token", "", "0", "/", true);
        window.global.user = "";
        window.global.guest = true;
        if (to.path == "/api/admin/login") {
          //If user going to login let him go
          next();
        } else {
          //Other urls cannot be access without login so force him to login.
          window.location = base_url + "/api/admin/login";
        }
      });
  } else {
    if (to.path == "/api/admin/login") {
      //If user going to login let him go
      next();
    } else {
      //Other urls cannot be access without login so force him to login.
      window.location = base_url + "/api/admin/login";
    }
  }
});

new Vue({
  el: "#app",
  components: {
    SideBar,
    MainHeader,
    MainFooter,
  },
  data: {
    global: window.global,
  },
  router,
  store,
  computed: {
    application() {
      let application_name = "";
      if (this.global.hasOwnProperty("hostname")) {
        //find first dot and then remove the complete string.
        let hostname = this.global.hostname;
        hostname = hostname.split(".");
        if (hostname.length > 2 && hostname[0] != "www") {
          //check if the user belongs to the same tenant or is it zopdealer employee
          application_name =
            hostname[0] == this.global.app_metadata.organization ||
            this.global.app_metadata.organization == "zopdealer"
              ? "tenant"
              : "";
        } else {
          application_name =
            this.global.app_metadata.organization == "zopdealer" ? "main" : "";
        }
        return application_name;
      }
    },
    app_metadata() {
      let app_metadata = {};
      if (this.global.hasOwnProperty("app_metadata")) {
        app_metadata = this.global.app_metadata;
      }
      return app_metadata;
    },
    user_metadata() {
      let user_metadata = {};
      if (this.global.hasOwnProperty("user_metadata")) {
        user_metadata = this.global.user_metadata;
      }
      return user_metadata;
    },
  },
  created() {},
});
