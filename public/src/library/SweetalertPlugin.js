import Swal from "sweetalert2";

export const createSwal = (options) => {
  const swalLocalInstance = options ? Swal.mixin(options) : Swal;
  const swalFunction = function (...args) {
    return swalLocalInstance.fire(...args);
  };

  for (const methodName in swalLocalInstance) {
    if (
      typeof swalLocalInstance[methodName] === "function" &&
      Object.prototype.hasOwnProperty.call(swalLocalInstance, methodName)
    ) {
      swalFunction[methodName] = (...args) => swalLocalInstance[methodName](...args);
    }
  }

  return swalFunction;
};

export const SweetalertPlugin = {
  install(app, options) {
    const swal = createSwal(options);

    if (app && app.config && app.config.globalProperties) {
      app.config.globalProperties.$swal = swal;
      app.config.globalProperties.swal = swal;
    }

    if (typeof window !== "undefined") {
      window.$swal = swal;
      window.Swal = Swal;
      if (window.Vue && window.Vue.prototype) {
        window.Vue.prototype.$swal = swal;
        window.Vue.swal = swal;
      }
    }
  },
};

export default SweetalertPlugin;
