<template>
  <div
    class="tab-pane"
    id="inventory-deals-panel"
    aria-labelledby="Customer Deals"
    role="tabpanel"
  >
    <section id="customer-deals">
      <div class="row match-height m-1">
        <div class="col-12 col-xs-12">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col" class="text-center">Customer Details</th>
                  <th scope="col" class="text-center">Type</th>
                  <th scope="col" class="text-center">Wh/Retail</th>
                  <th scope="col" class="text-center">Status</th>
                  <th scope="col" class="text-center">Date</th>
                  <th scope="col" class="text-center"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="deal in deals" :key="`deal_` + deal.id">
                  <td class="text-center" v-html="customerDetails(deal)"></td>
                  <td class="text-center">{{ deal.type }}</td>
                  <td class="text-center">
                    {{ RtlWh(deal.retail_or_wholesale_flag) }}
                  </td>
                  <td
                    class="text-center"
                    v-html="switchStatus(deal.status)"
                  ></td>
                  <td class="text-center">{{ parseDate(deal.date) }}</td>
                  <td>
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
                            class="bx bxs-user btn btn-info mr-1"
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
                      class="bx bxs-briefcase-alt-2 btn btn-primary"
                    >
                    </router-link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
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
  beforeDestroy() {
    Event.vue.$off("deal_count", null);
  },
};
</script>
  
  <style lang="scss" scoped>
</style>