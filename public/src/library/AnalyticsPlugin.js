export const createGtag = (options = {}) => {
  const id = options?.config?.id || "G-3RLC81KJ0D";

  if (typeof window !== "undefined") {
    window.dataLayer = window.dataLayer || [];
    if (typeof window.gtag !== "function") {
      window.gtag = function () {
        window.dataLayer.push(arguments);
      };
      window.gtag("js", new Date());
      window.gtag("config", id, options.config?.params || {});
    }

    const scriptSrc = `https://www.googletagmanager.com/gtag/js?id=${id}`;
    if (!document.querySelector(`script[src*="${id}"]`)) {
      const script = document.createElement("script");
      script.async = true;
      script.src = scriptSrc;
      document.head.appendChild(script);
    }
  }

  const gtagApi = {
    event(action, params = {}) {
      if (typeof window !== "undefined" && typeof window.gtag === "function") {
        window.gtag("event", action, params);
      }
      return Promise.resolve();
    },
    pageview(params) {
      if (typeof window !== "undefined" && typeof window.gtag === "function") {
        window.gtag("event", "page_view", params);
      }
      return Promise.resolve();
    },
    query(...args) {
      if (typeof window !== "undefined" && typeof window.gtag === "function") {
        window.gtag(...args);
      }
      return Promise.resolve();
    },
    optIn() {
      if (typeof window !== "undefined") {
        window[`ga-disable-${id}`] = false;
      }
    },
    optOut() {
      if (typeof window !== "undefined") {
        window[`ga-disable-${id}`] = true;
      }
    },
  };

  return gtagApi;
};

export const AnalyticsPlugin = {
  install(app, options = {}) {
    const gtag = createGtag(options);

    if (app && app.config && app.config.globalProperties) {
      app.config.globalProperties.$gtag = gtag;
    }

    if (typeof window !== "undefined") {
      window.$gtag = gtag;
      if (window.Vue && window.Vue.prototype) {
        window.Vue.prototype.$gtag = gtag;
      }
    }
  },
};

export default AnalyticsPlugin;
