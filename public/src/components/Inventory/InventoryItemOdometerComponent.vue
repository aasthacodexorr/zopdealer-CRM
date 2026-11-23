<template>
  <inventory-item-input-component
    :current_value="distance"
    :stock_no="stock_no"
    :column="column"
  >
    <span v-if="distance !== null && distance !== undefined && distance !== ''">
      &nbsp;{{ metric }}
    </span>
  </inventory-item-input-component>
</template>

<script>
import InventoryItemInputComponent from "./InventoryItemInputComponent.vue";

export default {
  components: { InventoryItemInputComponent },
  name: "InventoryItemOdometerComponent",
  props: {
    column: {
      required: true,
    },
    distance: {
      required: true,
    },
    stock_no: {
      required: true,
    },
    metric: {
      required: true,
    },
  },
};
</script>
