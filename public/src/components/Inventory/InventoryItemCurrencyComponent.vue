<template>
  <inventory-item-input-component
    :current_value="amount"
    :stock_no="stock_no"
    :column="column"
    :render_format="{
      style: 'currency',
      currency: 'USD',
    }"
  >
  </inventory-item-input-component>
</template>
<script>
import InventoryItemInputComponent from "./InventoryItemInputComponent.vue";

export default {
  components: { InventoryItemInputComponent },
  name: "InventoryItemCurrencyComponent",
  props: {
    column: {
      required: true,
    },
    amount: {
      required: true,
    },
    stock_no: {
      required: true,
    },
  },
};
</script>