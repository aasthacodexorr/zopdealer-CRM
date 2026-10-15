<template>
  <div class="quill-editor">
    <slot name="toolbar"></slot>
    <div ref="editor"></div>
  </div>
</template>

<script>
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";
import _Quill from "quill";

const Quill = window.Quill || _Quill;

const defaultToolbar = [
  ["bold", "italic", "underline", "strike"],
  ["blockquote", "code-block"],
  [{ header: 1 }, { header: 2 }],
  [{ list: "ordered" }, { list: "bullet" }],
  [{ script: "sub" }, { script: "super" }],
  [{ indent: "-1" }, { indent: "+1" }],
  [{ direction: "rtl" }],
  [{ size: ["small", false, "large", "huge"] }],
  [{ header: [1, 2, 3, 4, 5, 6, false] }],
  [{ color: [] }, { background: [] }],
  [{ font: [] }],
  [{ align: [] }],
  ["clean"],
  ["link", "image", "video"],
];

const defaultOptions = {
  theme: "snow",
  boundary: document.body,
  modules: {
    toolbar: defaultToolbar,
  },
  placeholder: "Insert text here ...",
  readOnly: false,
};

const QuillEditorComponent = {
  name: "QuillEditor",
  props: {
    modelValue: {
      type: String,
      default: undefined,
    },
    value: {
      type: String,
      default: undefined,
    },
    content: {
      type: String,
      default: undefined,
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
    placeholder: {
      type: String,
      default: "",
    },
  },
  emits: ["update:modelValue", "input", "change", "blur", "focus", "ready"],
  data() {
    return {
      _options: {},
      _content: "",
      defaultOptions,
    };
  },
  mounted() {
    this.initialize();
  },
  beforeUnmount() {
    this.cleanUp();
  },
  beforeDestroy() {
    this.cleanUp();
  },
  methods: {
    cleanUp() {
      if (this.quill) {
        this.quill.off("text-change");
        this.quill.off("selection-change");
        this.quill = null;
        delete this.quill;
      }
    },
    initialize() {
      if (!this.$refs.editor) return;

      const mergedModules = Object.assign(
        {},
        this.defaultOptions.modules,
        (this.globalOptions && this.globalOptions.modules) || {},
        (this.options && this.options.modules) || {}
      );

      this._options = Object.assign(
        {},
        this.defaultOptions,
        this.globalOptions,
        this.options,
        { modules: mergedModules }
      );

      if (this.placeholder) {
        this._options.placeholder = this.placeholder;
      }

      this.quill = new Quill(this.$refs.editor, this._options);

      if (this.disabled) {
        this.quill.enable(false);
      }

      const initialContent =
        this.modelValue !== undefined
          ? this.modelValue
          : this.value !== undefined
          ? this.value
          : this.content;

      if (initialContent) {
        this.quill.pasteHTML(initialContent);
        let html =
          this.$refs.editor && this.$refs.editor.children[0]
            ? this.$refs.editor.children[0].innerHTML
            : "";
        if (html === "<p><br></p>") html = "";
        this._content = html || initialContent;
      }

      this.quill.on("selection-change", (range) => {
        if (!range) {
          this.$emit("blur", this.quill);
        } else {
          this.$emit("focus", this.quill);
        }
      });

      this.quill.on("text-change", (delta, oldDelta, source) => {
        let html =
          this.$refs.editor && this.$refs.editor.children[0]
            ? this.$refs.editor.children[0].innerHTML
            : "";
        const quill = this.quill;
        const text = this.quill.getText();
        if (html === "<p><br></p>") html = "";
        if (html !== this._content) {
          this._content = html;
          this.$emit("update:modelValue", this._content);
          this.$emit("input", this._content);
        }
        this.$emit("change", { html, text, quill });
      });

      this.$emit("ready", this.quill);
    },
    updateContent(newVal) {
      if (!this.quill) return;
      if (newVal && newVal !== this._content) {
        const delta = this.quill.clipboard.convert(newVal);
        this.quill.setContents(delta);
        let html =
          this.$refs.editor && this.$refs.editor.children[0]
            ? this.$refs.editor.children[0].innerHTML
            : "";
        if (html === "<p><br></p>") html = "";
        this._content = html || newVal;
      } else if (!newVal) {
        if (this._content !== "") {
          this._content = "";
          this.quill.setText("");
        }
      }
    },
  },
  watch: {
    modelValue(newVal) {
      this.updateContent(newVal);
    },
    value(newVal) {
      if (this.modelValue === undefined) {
        this.updateContent(newVal);
      }
    },
    content(newVal) {
      if (this.modelValue === undefined && this.value === undefined) {
        this.updateContent(newVal);
      }
    },
    disabled(newVal) {
      if (this.quill) {
        this.quill.enable(!newVal);
      }
    },
  },
};

export default QuillEditorComponent;
export { Quill, QuillEditorComponent as quillEditor };
</script>
