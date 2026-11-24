<template>
  <div
    class="tab-pane"
    id="inventory-deals-panel"
    aria-labelledby="Customer Deals"
    role="tabpanel"
  >
    <section id="customer-deals" class="dl-page">
      <div class="dl-card">
        <div class="table-responsive">
          <table class="table dl-table">
            <thead>
              <tr>
                <th scope="col">Customer Details</th>
                <th scope="col">Type</th>
                <th scope="col">Wh/Retail</th>
                <th scope="col" class="text-center">Status</th>
                <th scope="col">Date</th>
                <th scope="col" class="text-right"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="deal in deals" :key="`deal_` + deal.id">
                <td class="dl-customers" v-html="customerDetails(deal)"></td>
                <td>
                  <span class="dl-chip">{{ deal.type }}</span>
                </td>
                <td class="dl-muted">
                  {{ RtlWh(deal.retail_or_wholesale_flag) }}
                </td>
                <td
                  class="text-center dl-status"
                  v-html="switchStatus(deal.status)"
                ></td>
                <td class="dl-muted dl-nowrap">{{ parseDate(deal.date) }}</td>
                <td class="text-right dl-actions">
                  <span v-show="hasCustomers(deal)">
                    <span
                      v-for="customer in deal.customers"
                      :key="`customer` + customer.id"
                    >
                      <span v-if="customerHasDetails(customer)">
                        <router-link
                          tag="i"
                          title="View Customer"
                          style="cursor: pointer; display: inline-block"
                          :to="{
                            name: 'customer-update',
                            params: { id: customer.details.id },
                          }"
                          exact
                          class="bx bxs-user btn btn-info dl-btn dl-btn--ghost"
                        >
                        </router-link>
                      </span>
                    </span>
                  </span>
                  <router-link
                    tag="i"
                    style="cursor: pointer; display: inline-block"
                    title="View Deal"
                    :to="{ name: 'deal-update', params: { id: deal.id } }"
                    exact
                    class="bx bxs-briefcase-alt-2 btn btn-primary dl-btn dl-btn--primary"
                  >
                  </router-link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
</template>
  <script>
const moment = require("moment");
export default {
  name: "InventoryManageDeal",
  data() {
    return {
      deals: [],
    };
  },
  computed: {
    updateView() {
      return this.$route.params.id && this.$route.name == "inventory-update";
    },
  },
  methods: {
    switchStatus(status) {
      switch (status) {
        case "Delivered":
          return `<span class='badge badge-light-success'>${status}</span>`;
          break;
        case "Open":
          return `<span class='badge badge-light-primary'>${status}</span>`;
          break;
        case "Lost":
          return `<span class='badge badge-light-danger'>${status}</span>`;
          break;

        default:
          return `<span class='badge badge-light-secondary'>${status}</span>`;
          break;
      }
    },
    customerHasDetails(customer) {
      if (customer.hasOwnProperty("details") && customer["details"]) {
        return customer.details.hasOwnProperty("id");
      }
      return false;
    },
    hasCustomers(deal) {
      return deal.hasOwnProperty("customers") && deal["customers"];
    },
    customerDetails(deal) {
      let primary_buyer = ``;
      let co_buyer = "";
      try {
        if (deal.hasOwnProperty("customers")) {
          for (const key in deal["customers"]) {
            let element = deal["customers"][key];

            if (element.hasOwnProperty("details")) {
              let customer = element["details"];

              if (element["customer_type"] === "primary_customer") {
                primary_buyer = `<br><h6" >${customer["first_name"]} ${customer["last_name"]} </h6>
               <i style="position:relative;top:0.1em;" class="bx-info-circle bx" title="Primary Customer"></i>`;
              } else {
                co_buyer = `<br><h6" >${customer["first_name"]} ${customer["last_name"]} </h6>
               <i style="position:relative;top:0.1em;" class="bx-info-circle bx" title="Co-Buyer"></i>`;
              }
            }
          }
        }
      } catch (error) {}

      console.log(`${primary_buyer} ${co_buyer}`);

      return `${primary_buyer} ${co_buyer}`;
    },
    inventoryRouting() {
      console.log("hello world");
    },
    parseDate(date) {
      if (moment(date).isValid()) {
        return moment(date).format("MM/DD/YYYY");
      } else {
        return "";
      }
    },
    RtlWh(flag) {
      return flag == "0" ? "Retail" : "Wholesale";
    },
    getAllInventoryDeals(inventory_id) {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/inventory/" +
          inventory_id +
          "/deals",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.deals = data.deals;
          Event.fire("deal_count", this.deals.length);
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
  },
  created() {
    const vm = this;
    if (vm.updateView) {
      vm.getAllInventoryDeals(this.$route.params.id);
    }
  },
  beforeUnmount() {
    Event.vue.$off("deal_count", null);
  },
};
</script>
  
  <style lang="scss" scoped>
</style>
<style lang="scss">
/* Inventory — Deals tab (look only). */
#inventory-deals-panel .dl-page {
  padding: 8px 20px 96px;
  color: #0f172a;

  .dl-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
  }
  .table-responsive { margin: 0; }
  .dl-table {
    margin: 0;
    th {
      padding: 12px 16px;
      background: #f8fafc;
      border-top: 0;
      border-bottom: 1px solid #e2e8f0;
      font-size: 12px;
      font-weight: 600;
      color: #475569;
      text-transform: none;
      letter-spacing: 0;
      white-space: nowrap;
    }
    td {
      padding: 12px 16px;
      border-top: 1px solid #f1f5f9;
      vertical-align: middle;
      font-size: 13px;
      color: #1e293b;
    }
    tbody tr:first-child td { border-top: 0; }
    tbody tr:hover td { background: #fafbfd; }
  }
  .dl-muted { color: #64748b; }
  .dl-nowrap { white-space: nowrap; }

  /* customer names come from the existing HTML helper */
  .dl-customers {
    br:first-child { display: none; }
    br { display: none; }
    h6 {
      display: inline;
      margin: 0;
      font-size: 14px;
      font-weight: 600;
      color: #0f172a;
    }
    i.bx {
      margin: 0 10px 0 4px;
      color: #94a3b8;
      font-size: 14px;
      top: 0.15em !important;
    }
  }
  .dl-chip {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 999px;
    background: #f1f5f9;
    color: #334155;
    font-size: 12px;
    font-weight: 500;
  }
  .dl-status .badge {
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.02em;
  }
  .dl-actions { white-space: nowrap; }
  .dl-btn {
    width: 34px;
    height: 34px;
    padding: 0;
    margin-left: 6px;
    border-radius: 8px;
    font-size: 16px;
    line-height: 34px;
    text-align: center;
    box-shadow: none !important;
    &--primary {
      background: #2563eb !important;
      border-color: #2563eb !important;
      color: #ffffff !important;
    }
    &--ghost {
      background: #ffffff !important;
      border: 1px solid #e2e8f0 !important;
      color: #475569 !important;
      &:hover { background: #f8fafc !important; color: #0f172a !important; }
    }
  }
}
</style>
