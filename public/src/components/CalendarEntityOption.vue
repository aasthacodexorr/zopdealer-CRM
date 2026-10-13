<template>
  <span>
    <span v-show="current_module === 'Inventory'">
      <span v-show="option.year !== null && option.year != ''">
        {{ option.year }}
      </span>
      <span v-show="option.make !== null && option.make != ''">
        {{ option.make }}
      </span>
      <span v-show="option.model !== null && option.model != ''">
        {{ option.model }}
      </span>
      <span v-show="option.stock_no !== null && option.stock_no != ''">
        {{ `< ${option.stock_no} >` }}
      </span>
      <span v-show="option.vin !== null && option.vin != ''">
        {{ `< ${option.vin} >` }}
      </span>
    </span>

    <span v-show="current_module === 'Deal'">
      <span v-show="option.id !== null && option.id != ''">
        {{ `< D - ${option.id} >` }}
      </span>
      <span v-show="option.first_name !== null && option.first_name != ''">
        {{ option.first_name }}
      </span>
      <span v-show="option.last_name !== null && option.last_name != ''">
        {{ option.last_name }}
      </span>
      <span v-show="option.phone !== null && option.phone != ''">
        {{ `< ${option.phone} >` }}
      </span>
      <span v-show="option.mobile !== null && option.mobile != ''">
        {{ `< ${option.mobile} >` }}
      </span>
      <span v-show="option.email !== null && option.email != ''">
        {{ `< ${option.email} >` }}
      </span>
    </span>

    <span v-show="current_module === 'Vendor'">
      <span v-show="option.id !== null && option.id != ''">
        {{ `< V - ${option.id} >` }}
      </span>
      <span v-show="option.first_name !== null">
        {{ option.first_name }}
      </span>
      <span v-show="option.last_name !== null">
        {{ option.last_name }}
      </span>
      <span v-show="option.company_name !== null && option.company_name !== ''">
        {{ `< ${option.company_name} >` }}
      </span>
      <span v-show="option.phone !== null && option.phone != ''">
        {{ `< ${option.phone} >` }}
      </span>
      <span v-show="option.email !== null && option.email != ''">
        {{ `< ${option.email} >` }}
      </span>
    </span>

    <span v-show="current_module === 'Lead'">
      <span v-show="option.id !== null && option.id != ''">
        {{ `< L - ${option.id} >` }}
      </span>
      <span v-show="option.first_name !== null">
        {{ option.first_name }}
      </span>
      <span v-show="option.last_name !== null">
        {{ option.last_name }}
      </span>
      <span v-show="option.phone !== null && option.phone != ''">
        {{ `< ${option.phone} >` }}
      </span>
      <span v-show="option.email !== null && option.email != ''">
        {{ `< ${option.email} >` }}
      </span>
    </span>

    <span v-show="current_module === 'Customer'">
      <span v-show="option.id !== null && option.id != ''">
        {{ `< C - ${option.id} >` }}
      </span>
      <span v-show="option.first_name !== null">
        {{ option.first_name }}
      </span>
      <span v-show="option.last_name !== null">
        {{ option.last_name }}
      </span>
      <span v-show="option.company_name !== null && option.company_name !== ''">
        {{ `< ${option.company_name} >` }}
      </span>
      <span v-show="option.phone !== null && option.phone != ''">
        {{ `< ${option.phone} >` }}
      </span>
      <span v-show="option.email !== null && option.email != ''">
        {{ `< ${option.email} >` }}
      </span>
    </span>
  </span>
</template>
<script>
export default {
  props: {
    current_module: {
      required: true,
    },
    option: {
      required: true,
      default: {}
    },
  },
  name: "CalendarEntityOption",
};
</script>