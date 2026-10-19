<template>
  <span v-if="option">
    <span v-if="current_module === 'Inventory'">
      <span v-if="option.year != null && option.year !== ''">
        {{ option.year }}
      </span>
      <span v-if="option.make != null && option.make !== ''">
        {{ option.make }}
      </span>
      <span v-if="option.model != null && option.model !== ''">
        {{ option.model }}
      </span>
      <span v-if="option.stock_no != null && option.stock_no !== ''">
        {{ `< ${option.stock_no} >` }}
      </span>
      <span v-if="option.vin != null && option.vin !== ''">
        {{ `< ${option.vin} >` }}
      </span>
    </span>

    <span v-if="current_module === 'Deal'">
      <span v-if="option.id != null && option.id !== ''">
        {{ `< D - ${option.id} >` }}
      </span>
      <span v-if="option.first_name != null && option.first_name !== ''">
        {{ option.first_name }}
      </span>
      <span v-if="option.last_name != null && option.last_name !== ''">
        {{ option.last_name }}
      </span>
      <span v-if="option.phone != null && option.phone !== ''">
        {{ `< ${option.phone} >` }}
      </span>
      <span v-if="option.mobile != null && option.mobile !== ''">
        {{ `< ${option.mobile} >` }}
      </span>
      <span v-if="option.email != null && option.email !== ''">
        {{ `< ${option.email} >` }}
      </span>
    </span>

    <span v-if="current_module === 'Vendor'">
      <span v-if="option.id != null && option.id !== ''">
        {{ `< V - ${option.id} >` }}
      </span>
      <span v-if="option.first_name != null">
        {{ option.first_name }}
      </span>
      <span v-if="option.last_name != null">
        {{ option.last_name }}
      </span>
      <span v-if="option.company_name != null && option.company_name !== ''">
        {{ `< ${option.company_name} >` }}
      </span>
      <span v-if="option.phone != null && option.phone !== ''">
        {{ `< ${option.phone} >` }}
      </span>
      <span v-if="option.email != null && option.email !== ''">
        {{ `< ${option.email} >` }}
      </span>
    </span>

    <span v-if="current_module === 'Lead'">
      <span v-if="option.id != null && option.id !== ''">
        {{ `< L - ${option.id} >` }}
      </span>
      <span v-if="option.first_name != null">
        {{ option.first_name }}
      </span>
      <span v-if="option.last_name != null">
        {{ option.last_name }}
      </span>
      <span v-if="option.phone != null && option.phone !== ''">
        {{ `< ${option.phone} >` }}
      </span>
      <span v-if="option.email != null && option.email !== ''">
        {{ `< ${option.email} >` }}
      </span>
    </span>

    <span v-if="current_module === 'Customer'">
      <span v-if="option.id != null && option.id !== ''">
        {{ `< C - ${option.id} >` }}
      </span>
      <span v-if="option.first_name != null">
        {{ option.first_name }}
      </span>
      <span v-if="option.last_name != null">
        {{ option.last_name }}
      </span>
      <span v-if="option.company_name != null && option.company_name !== ''">
        {{ `< ${option.company_name} >` }}
      </span>
      <span v-if="option.phone != null && option.phone !== ''">
        {{ `< ${option.phone} >` }}
      </span>
      <span v-if="option.email != null && option.email !== ''">
        {{ `< ${option.email} >` }}
      </span>
    </span>
  </span>
</template>
<script>
export default {
  props: {
    current_module: {
      type: String,
      default: "",
    },
    option: {
      default: () => ({}),
    },
  },
  name: "CalendarEntityOption",
};
</script>