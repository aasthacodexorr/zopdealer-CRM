<template>
  <div class="quill-editor">
    <slot name="toolbar"></slot>
    <div :id="elementId">
      <span class="ql-formats">
        <select class="ql-font"></select>
        <select class="ql-size"></select>
      </span>
      <span class="ql-formats">
        <button class="ql-bold"></button>
        <button class="ql-italic"></button>
        <button class="ql-underline"></button>
        <button class="ql-strike"></button>
      </span>
      <span class="ql-formats">
        <select class="ql-color"></select>
        <select class="ql-background"></select>
      </span>
      <span class="ql-formats">
        <button class="ql-script" value="sub"></button>
        <button class="ql-script" value="super"></button>
      </span>
      <span class="ql-formats">
        <button class="ql-header" value="1"></button>
        <button class="ql-header" value="2"></button>
        <button class="ql-blockquote"></button>
        <button class="ql-code-block"></button>
      </span>
      <span class="ql-formats">
        <button class="ql-list" value="ordered"></button>
        <button class="ql-list" value="bullet"></button>
        <button class="ql-indent" value="-1"></button>
        <button class="ql-indent" value="+1"></button>
      </span>
      <span class="ql-formats">
        <button class="ql-direction" value="rtl"></button>
        <select class="ql-align"></select>
      </span>
      <span class="ql-formats">
        <button class="ql-link"></button>
        <button class="ql-image"></button>
        <button class="ql-video"></button>
        <button class="ql-formula"></button>
      </span>
      <span class="ql-formats">
        <button class="ql-clean"></button>
      </span>
      <span class="ql-formats">
        <slot name="placeholder"></slot>
      </span>
    </div>
    <div ref="editor" :style="editor_style"></div>
  </div>
</template>

<script>
// require sources

import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";

import "quill/dist/quill.bubble.css";

import _Quill from "quill";
const Quill = window.Quill || _Quill;
import getPlaceholderModule from "quill-placeholder-module";
import getAutocompleteModule from "quill-placeholder-autocomplete-module";

Quill.register(
  "modules/placeholder",
  getPlaceholderModule(Quill, {
    className: "ql-placeholder-content", // default
  })
);
Quill.register("modules/autocomplete", getAutocompleteModule(Quill));

//const elementId = Math.floor(Math.random() * Math.floor(Math.random() * Date.now()));

const defaultOptions = {
  theme: "snow",
  boundary: document.body,
  placeholder: "Insert text here ...",
  readOnly: false,
};
// pollfill
if (typeof Object.assign != "function") {
  Object.defineProperty(Object, "assign", {
    value(target, varArgs) {
      if (target == null) {
        throw new TypeError("Cannot convert undefined or null to object");
      }
      const to = Object(target);
      for (let index = 1; index < arguments.length; index++) {
        const nextSource = arguments[index];
        if (nextSource != null) {
          for (const nextKey in nextSource) {
            if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
              to[nextKey] = nextSource[nextKey];
            }
          }
        }
      }
      return to;
    },
    writable: true,
    configurable: true,
  });
}
// export
export default {
  name: "GenericTextEditor",
  data() {
    return {
      _options: {},
      _content: "",
      defaultOptions,
    };
  },
  props: {
    content: String,
    value: String,
    editor_style: {
      type: String,
      required: false,
      default: "",
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    options: {
      type: Object,
      required: false,
      default: () => ({}),
    },
    globalOptions: {
      type: Object,
      required: false,
      default: () => ({}),
    },
    elementId: {
      type: String,
      required: true,
    },
  },
  mounted() {
    this.initialize();
  },
  beforeDestroy() {
    this.quill = null;
    delete this.quill;
  },
  methods: {
    // Init Quill instance
    initialize() {
      if (this.$el) {
        // Options
        //this.defaultOptions.modules.toolbar = "#" + this.elementId;
        this._options = Object.assign(
          {},
          this.defaultOptions,
          this.globalOptions,
          this.options
        );

        this._options.modules.toolbar = "#" + this.elementId;
        // Instance
        this.quill = new Quill(this.$refs.editor, this._options);

        this.quill.enable(false);
        // Set editor content
        if (this.value || this.content) {
          this.quill.pasteHTML(this.value || this.content);
        }
        // Disabled editor
        if (!this.disabled) {
          this.quill.enable(true);
        }
        // Mark model as touched if editor lost focus
        this.quill.on("selection-change", (range) => {
          if (!range) {
            this.$emit("blur", this.quill);
          } else {
            this.$emit("focus", this.quill);
          }
        });
        // Update model if text changes
        this.quill.on("text-change", (delta, oldDelta, source) => {
          let html = this.$refs.editor.children[0].innerHTML;
          const quill = this.quill;
          const text = this.quill.getText();
          if (html === "<p><br></p>") html = "";
          this._content = html;
          this.$emit("input", this._content);
          this.$emit("change", { html, text, quill });
        });
        // Emit ready event
        this.$emit("ready", this.quill);
      }
    },
  },
  watch: {
    // Watch content change
    content(newVal, oldVal) {
      if (this.quill) {
        if (newVal && newVal !== this._content) {
          this._content = newVal;
          this.quill.pasteHTML(newVal);
        } else if (!newVal) {
          this.quill.setText("");
        }
      }
    },
    // Watch content change
    value(newVal, oldVal) {
      if (this.quill) {
        if (newVal && newVal !== this._content) {
          this._content = newVal;
          const html = newVal;
          const delta = this.quill.clipboard.convert(html);
          this.quill.setContents(delta);
          // this.quill.pasteHTML(newVal);
        } else if (!newVal) {
          this.quill.setText("");
        }
      }
    },
    // Watch disabled change
    disabled(newVal, oldVal) {
      if (this.quill) {
        this.quill.enable(!newVal);
      }
    },
  },
};
</script>
<style lang="scss">
.ql-picker.ql-placeholder {
  width: 118px;
}

.ql-picker.ql-placeholder > span.ql-picker-label::before {
  content: "Placeholders";
}

.ql-picker.ql-placeholder
  > span.ql-picker-options
  > span.ql-picker-item::before {
  content: attr(data-label);
}

.ql-placeholder > span:last-child {
  height: auto;
  overflow-y: scroll;
}
</style>
