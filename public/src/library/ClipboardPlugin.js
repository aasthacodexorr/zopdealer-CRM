export const copyText = (text, container = (typeof document !== "undefined" ? document.body : null)) => {
  return new Promise((resolve, reject) => {
    if (typeof window === "undefined" || typeof document === "undefined") {
      reject(new Error("Clipboard API is not available in non-browser environment."));
      return;
    }

    const valueToCopy = text === undefined || text === null ? "" : String(text);

    // 1. Try modern navigator.clipboard.writeText
    if (
      typeof navigator !== "undefined" &&
      navigator.clipboard &&
      typeof navigator.clipboard.writeText === "function" &&
      window.isSecureContext !== false
    ) {
      navigator.clipboard
        .writeText(valueToCopy)
        .then(() => resolve({ text: valueToCopy, action: "copy" }))
        .catch((err) => {
          // If navigator.clipboard fails, attempt fallback
          tryFallback(valueToCopy, container, resolve, reject, err);
        });
      return;
    }

    // 2. Fallback to execCommand
    tryFallback(valueToCopy, container, resolve, reject);
  });
};

function tryFallback(text, container, resolve, reject, originalError) {
  try {
    const fakeElem = document.createElement("textarea");
    fakeElem.style.fontSize = "12pt";
    fakeElem.style.border = "0";
    fakeElem.style.padding = "0";
    fakeElem.style.margin = "0";
    fakeElem.style.position = "fixed";
    fakeElem.style.left = "-9999px";
    fakeElem.style.top = `${window.pageYOffset || document.documentElement.scrollTop}px`;
    fakeElem.setAttribute("readonly", "");
    fakeElem.value = text;

    const targetContainer = container || document.body;
    targetContainer.appendChild(fakeElem);

    fakeElem.focus();
    fakeElem.select();
    fakeElem.setSelectionRange(0, fakeElem.value.length);

    const successful = document.execCommand("copy");
    targetContainer.removeChild(fakeElem);

    if (successful) {
      resolve({ text, action: "copy" });
    } else {
      reject(originalError || new Error("execCommand('copy') failed"));
    }
  } catch (err) {
    reject(originalError || err);
  }
}

export const ClipboardPlugin = {
  install(app) {
    if (app && app.config && app.config.globalProperties) {
      app.config.globalProperties.$copyText = copyText;
    }

    if (typeof window !== "undefined") {
      window.$copyText = copyText;
      if (window.Vue && window.Vue.prototype) {
        window.Vue.prototype.$copyText = copyText;
      }
    }
  },
};

export default ClipboardPlugin;
