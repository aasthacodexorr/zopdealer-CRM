<template>
  <div
    class="tab-pane"
    id="customer-deals-panel"
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
                  <th scope="col" class="text-center">Inventory Details</th>
                  <th scope="col" class="text-center">Type</th>
                  <th scope="col" class="text-center">Wh/Retail</th>
                  <th scope="col" class="text-center">Status</th>
                  <th scope="col" class="text-center">Date</th>
                  <th scope="col" class="text-center"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="deal in deals" :key="`deal_` + deal.id">
                  <td class="text-center" v-html="inventoryDetails(deal)"></td>
                  <td class="text-center">{{deal.type}}</td>
                  <td class="text-center">{{RtlWh(deal.retail_or_wholesale_flag)}}</td>
                  <td class="text-center" v-html="switchStatus(deal.status)"></td>
                  <td class="text-center">{{ parseDate(deal.date) }}</td>
                  <td >
                  <span v-show="hasInventories(deal)">
                    <span v-for="inventory in deal.inventories" :key="`inventory` + inventory.id">
                      <span v-if="inventoryHasDetails(inventory)">
                        <router-link 
                        tag="i"
                        title="View Inventory"
                        style="cursor: pointer; display:inline-block;"
                        :to="{ name: 'inventory-update', params:  {id : inventory.details.inventory_id}}"
                        exact
                        class="bx bx-car btn btn-info" >
                        </router-link>
                      </span>
                    </span>

                  </span>
                  <router-link 
                  tag="i"
                  style="cursor: pointer; display:inline-block;"
                  title="View Deal"
                  :to="{ name: 'deal-update', params: { id: deal.id } }"
                  exact
                  class="bx bxs-briefcase-alt-2 btn btn-primary" >
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
  name: "CustomerDeal",
  data() {
    return {
      deals: [],
    };
  },
  computed: {
    updateView() {
      return this.$route.params.id && this.$route.name == "customer-update";
    },
  },
  methods: {
    switchStatus(status){
      switch (status) {
        case 'Delivered':
          return `<span class='badge badge-light-success'>${status}</span>`;
          break;
        case 'Open':
          return `<span class='badge badge-light-primary'>${status}</span>`;
          break;
        case 'Lost':
          return `<span class='badge badge-light-danger'>${status}</span>`;
          break;
      
        default:
          return `<span class='badge badge-light-secondary'>${status}</span>`;
          break;
      }
    },
    inventoryHasDetails(inventory){
      if(inventory.hasOwnProperty('details') && inventory['details']){ 
         return inventory.details.hasOwnProperty('inventory_id');
      }
      return false;
    },
    hasInventories(deal){
      return deal.hasOwnProperty('inventories') && deal['inventories'];
    },
    inventoryDetails(deal){
      let markup = ``;
      try {

      if(deal.hasOwnProperty('inventories')){
        for (const key in deal['inventories']) {
          let element = deal['inventories'][key];
          
          if(element.hasOwnProperty('details')){
             let inventory = element['details'];
             markup += `<h6" >${inventory['year']} ${inventory['make']} ${inventory['model']} </h6>
             <i style="position:relative;top:0.1em;" class="bx-info-circle bx" title="VIN \n${inventory['vin']} \nStock No \n${inventory['stock_no']}"></i>`;
          }

        }

      }
        
      } catch (error) {
        
      }
      
      return markup;
    },
    inventoryRouting(){
      console.log('hello world');
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
    getAllCustomerDeals(customer_id) {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/customers/" +
          customer_id +
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
      vm.getAllCustomerDeals(this.$route.params.id);
    }
  },
  beforeDestroy() {
    Event.vue.$off("deal_count", null);
  }
};
</script>

<style lang="scss" scoped>
</style>