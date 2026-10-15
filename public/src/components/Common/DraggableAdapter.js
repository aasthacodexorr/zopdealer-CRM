import draggableOriginal from "vuedraggable-original";

const rawDraggable =
  draggableOriginal && draggableOriginal.default
    ? draggableOriginal.default
    : draggableOriginal;

function patchComponentStructure(structure) {
  if (!structure) return;
  const Proto = structure.constructor ? structure.constructor.prototype : null;
  if (Proto && !Proto.__safe_updated_patched__) {
    Proto.__safe_updated_patched__ = true;
    Proto.updated = function () {
      const { defaultNodes, realList } = this;
      if (!defaultNodes || !realList) return;
      defaultNodes.forEach((node, index) => {
        let domElement = null;
        if (node) {
          if (node.el && node.el.nodeType === 1) {
            domElement = node.el;
          } else if (
            node.component &&
            node.component.subTree &&
            node.component.subTree.el &&
            node.component.subTree.el.nodeType === 1
          ) {
            domElement = node.component.subTree.el;
          }
        }
        if (domElement) {
          domElement.__draggable_context = {
            element: realList[index],
            index,
          };
        }
      });
    };
  }
  structure.updated = function () {
    const { defaultNodes, realList } = this;
    if (!defaultNodes || !realList) return;
    defaultNodes.forEach((node, index) => {
      let domElement = null;
      if (node) {
        if (node.el && node.el.nodeType === 1) {
          domElement = node.el;
        } else if (
          node.component &&
          node.component.subTree &&
          node.component.subTree.el &&
          node.component.subTree.el.nodeType === 1
        ) {
          domElement = node.component.subTree.el;
        }
      }
      if (domElement) {
        domElement.__draggable_context = {
          element: realList[index],
          index,
        };
      }
    });
  };
}

const DraggableAdapter = {
  ...rawDraggable,
  name: "draggable",
  compatConfig: {
    MODE: 3,
  },
  props: {
    ...rawDraggable.props,
    value: {
      type: Array,
      required: false,
      default: null,
    },
  },
  emits: [...(rawDraggable.emits || []), "input"],
  computed: {
    ...rawDraggable.computed,
    realList() {
      if (this.list) {
        return this.list;
      }
      if (this.modelValue !== null && this.modelValue !== undefined) {
        return this.modelValue;
      }
      if (this.value !== null && this.value !== undefined) {
        return this.value;
      }
      return null;
    },
  },
  render() {
    let vnodes;
    try {
      this.error = false;
      vnodes = rawDraggable.render.call(this);
    } catch (err) {
      this.error = true;
      console.warn("Draggable render error:", err);
      return null;
    }
    patchComponentStructure(this.componentStructure);
    return vnodes;
  },
  mounted() {
    if (this.error || !this.componentStructure) {
      return;
    }
    patchComponentStructure(this.componentStructure);
    try {
      if (typeof rawDraggable.mounted === "function") {
        rawDraggable.mounted.call(this);
      }
    } catch (e) {
      console.warn("Draggable mounted warning:", e);
    }
    this.updateDomContext();
    if (this.$nextTick) {
      this.$nextTick(() => {
        this.updateDomContext();
      });
    }
  },
  updated() {
    if (this.error || !this.componentStructure) {
      return;
    }
    patchComponentStructure(this.componentStructure);
    try {
      if (typeof rawDraggable.updated === "function") {
        rawDraggable.updated.call(this);
      }
    } catch (e) {
      console.warn("Draggable updated warning:", e);
    }
    this.updateDomContext();
    if (this.$nextTick) {
      this.$nextTick(() => {
        this.updateDomContext();
      });
    }
  },
  methods: {
    ...(rawDraggable.methods || {}),
    updateDomContext() {
      const targetDomElement =
        this.targetDomElement ||
        (this.$el && this.$el.nodeType === 1
          ? this.$el
          : this.$el && this.$el.parentElement
          ? this.$el.parentElement
          : null);
      const realList = this.realList;
      if (targetDomElement && targetDomElement.children && realList) {
        const children = [...targetDomElement.children].filter(
          (el) =>
            el.nodeType === 1 &&
            !el.classList.contains("header") &&
            !el.classList.contains("footer")
        );
        children.forEach((child, index) => {
          if (realList[index] !== undefined) {
            child.__draggable_context = {
              element: realList[index],
              index,
            };
          }
        });
      }
    },
    getUnderlyingVm(domElement) {
      if (!domElement) return null;
      if (domElement.__draggable_context) {
        return domElement.__draggable_context;
      }
      if (this.componentStructure) {
        return this.componentStructure.getUnderlyingVm(domElement) || null;
      }
      return null;
    },
    getVmIndexFromDomIndex(domIndex) {
      const targetDomElement =
        this.targetDomElement ||
        (this.$el && this.$el.nodeType === 1
          ? this.$el
          : this.$el && this.$el.parentElement);
      if (targetDomElement && targetDomElement.children) {
        const domElement = targetDomElement.children.item(domIndex);
        if (domElement && domElement.__draggable_context) {
          return domElement.__draggable_context.index;
        }
      }
      if (this.componentStructure && targetDomElement) {
        return this.componentStructure.getVmIndexFromDomIndex(
          domIndex,
          targetDomElement
        );
      }
      return 0;
    },
    alterList(onList) {
      if (this.list) {
        onList(this.list);
        return;
      }
      const currentList = this.modelValue || this.value || [];
      const newList = [...currentList];
      onList(newList);
      this.$emit("update:modelValue", newList);
      this.$emit("input", newList);
    },
  },
};

export default DraggableAdapter;
