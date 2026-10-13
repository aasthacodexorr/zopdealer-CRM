<template>
  <a
    class="btn btn-primary text-white"
    type="button"
    data-toggle="modal"
    :data-target="`#disclosureTemplateModal_${elementId}`"
    ><i class="bx bx-list-plus mr-50"></i>
    <span class="">Use Template</span>
  </a>
</template>
<script>
export default {
  props: {
    elementId: {
      type: String,
      required: false,
      default: "",
    },
  },
  name: "InventoryDisclosureTemplateCTA",
};
</script>