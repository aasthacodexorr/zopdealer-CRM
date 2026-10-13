<template>
  <div class="quill-editor">
    <slot name="toolbar"></slot>
    <div :id="elementId">
      <span class="ql-formats">
        <select class="ql-header" defaultValue="">
          <option value="1"></option>
          <option value="2"></option>
          <option value="3"></option>
          <option value=""></option>
        </select>
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
        <button class="ql-link" type="button"></button>
      </span>
      <span class="ql-formats">
        <button class="ql-clean"></button>
      </span>
      <span class="ql-formats">
        <select class="ql-placeholder">
          <option value="Year">Year</option>
          <option value="Make">Make</option>
          <option value="Model">Model</option>
          <option value="Trim">Trim</option>
          <option value="Vin">Vin</option>
          <option value="Stock Number">Stock Number</option>
          <option value="Body Type">Body Type</option>
          <option value="Door">Door</option>
          <option value="Displacement">Displacement</option>
          <option value="Cylinders">Cylinders</option>
          <option value="Transmission">Transmission</option>
          <option value="Drivetrain">Drivetrain</option>
          <option value="Passengers">Passengers</option>
          <option value="Fuel Type">Fuel Type</option>
          <option value="Fuel Consumption City">Fuel Consumption City</option>
          <option value="Fuel Consumption HWY">Fuel Consumption HWY</option>
          <option value="Odometer">Odometer</option>
          <option value="Exterior Colour">Exterior Colour</option>
          <option value="Interior Colour">Interior Colour</option>
          <option value="Price">Price</option>
          <option value="Special Price">Special Price</option>
          <option value="Location">Location</option>
          <option value="Selling Price">Selling Price</option>
          <option value="Vehicle Tags">Vehicle Tags</option>
          <option value="Business Name">Business Name</option>
          <option value="Dealership Email">Dealership Email</option>
          <option value="Dealership Phone">Dealership Phone</option>
          <option value="Dealership Address">Dealership Address</option>

        </select>
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
    className: "ql-placeholder-content",
  })
);
Quill.register("modules/autocomplete", getAutocompleteModule(Quill));

const defaultOptions = {
  theme: "snow",
  boundary: document.body,
  modules: {
    placeholder: {
      placeholders: [
        { id: "Year", label: "Year" },
        { id: "Make", label: "Make" },
        { id: "Model", label: "Model" },
        { id: "Trim", label: "Trim" },
        { id: "Vin", label: "Vin" },
        { id: "Stock Number", label: "Stock Number" },
        { id: "Body Type", label: "Body Type" },
        { id: "Door", label: "Door" },
        { id: "Displacement", label: "Displacement" },
        { id: "Cylinders", label: "Cylinders" },
        { id: "Transmission", label: "Transmission" },
        { id: "Drivetrain", label: "Drivetrain" },
        { id: "Passengers", label: "Passengers" },
        { id: "Fuel Type", label: "Fuel Type" },
        { id: "Fuel Consumption City", label: "Fuel Consumption City" },
        { id: "Fuel Consumption HWY", label: "Fuel Consumption HWY" },
        { id: "Odometer", label: "Odometer" },
        { id: "Exterior Colour", label: "Exterior Colour" },
        { id: "Interior Colour", label: "Interior Colour" },
        { id: "Price", label: "Price" },
        { id: "Special Price", label: "Special Price" },
        { id: "Location", label: "Location" },
        { id: "Selling Price", label: "Selling Price" },
        { id: "Vehicle Tags", label: "Vehicle Tags" },
        { id: "Business Name", label: "Business Name" },
        { id: "Dealership Email", label: "Dealership Email" },
        { id: "Dealership Phone", label: "Dealership Phone" },
        { id: "Dealership Address", label: "Dealership Address" },
      ],
    },
  },
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
  name: "TextEditor",
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
        this.defaultOptions.modules.toolbar = "#" + this.elementId;
        this._options = Object.assign(
          {},
          this.defaultOptions,
          this.globalOptions,
          this.options
        );
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
  height: 300px;
  overflow-y: scroll;
}
</style>
