<template>
  <div
    class="tab-pane active"
    id="work-order-create-panel"
    aria-labelledby="Work Order Create or Update"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="work-order-create-section">
      <div class="row match-height">
        <div class="col-12 col-xs-12">
          <div class="card">
            <div class="card-content">
              <div class="card-body pb-1 mx-25">
                <!-- Check In Section : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Check In</h5>
                  </div>
                  <div class="row">
                    <!-- check_in_date : START -->
                    <div class="col-md-4 col-xs-12">
                      <div class="form-group">
                        <label for="check_in_date">Date</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <date-picker
                            :format="'MM/DD/YYYY'"
                            :value-type="'MM/DD/YYYY'"
                            type="date"
                            :clearable="false"
                            placeholder="Date"
                            v-model="form.check_in_date"
                            id="check_in_date"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('check_in_date'),
                            }"
                            @change="delete errors.check_in_date"
                            :title="errors.check_in_date"
                          ></date-picker>
                        </fieldset>
                        <small
                          id="check_in_date-error"
                          class="form-text invalid-feedback"
                          >{{ errors.check_in_date }}</small
                        >
                      </div>
                    </div>
                    <!-- check_in_date : END -->

                    <!-- check_in_time : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="check_in_time">Time</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="check_in_time"
                            type="time"
                            placeholder="Time"
                            v-model="form.check_in_time"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('check_in_time'),
                            }"
                            @keyup="delete errors.check_in_time"
                            :title="errors.check_in_time"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-time font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="check_in_time-error"
                          class="form-text invalid-feedback"
                          >{{ errors.check_in_time }}</small
                        >
                      </div>
                    </div>
                    <!-- check_in_time : END -->

                    <!-- check_in_odometer : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="check_in_odometer">Odometer</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="check_in_odometer"
                            type="text"
                            placeholder="Odometer"
                            v-model="form.check_in_odometer"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('check_in_odometer'),
                            }"
                            @keyup="delete errors.check_in_odometer"
                            :title="errors.check_in_odometer"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dashboard font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="check_in_odometer-error"
                          class="form-text invalid-feedback"
                          >{{ errors.check_in_odometer }}</small
                        >
                      </div>
                    </div>
                    <!-- check_in_odometer : END -->
                  </div>
                </div>
                <!-- Check In Section : END -->

                <!-- Details Section : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Details</h5>
                  </div>
                  <div class="row">
                    <!-- type : start -->
                    <div class="col-md-4 col-xs-12 form-group">
                      <label for="type">Type</label>
                      <select
                        id="type"
                        class="custom-select"
                        name="type"
                        v-model="form.type"
                      >
                        <option disabled value="">-- Select Type --</option>
                        <option
                          :value="wotype"
                          v-for="wotype in getTypes"
                          :key="wotype"
                        >
                          {{ wotype }}
                        </option>
                      </select>

                      <small
                        id="type-error"
                        class="form-text invalid-feedback"
                        >{{ errors.type }}</small
                      >
                    </div>
                    <!-- type : end -->

                    <!-- status : start -->
                    <div class="col-md-4 col-xs-12 form-group">
                      <label for="status">Status</label>
                      <select
                        id="status"
                        class="custom-select"
                        name="status"
                        v-model="form.status"
                      >
                        <option disabled value="">-- Select Status --</option>
                        <option
                          v-for="status in getStatuses"
                          :key="status"
                          :value="status"
                        >
                          {{ status }}
                        </option>
                      </select>

                      <small
                        id="type-error"
                        class="form-text invalid-feedback"
                        >{{ errors.status }}</small
                      >
                    </div>
                    <!-- status : end -->

                    <!-- auto_save_Flag : START -->
                    <div class="col-md-4 col-xs-12 form-group">
                      <label>Sync Expenses</label>
                      <div class="custom-control custom-switch">
                        <input
                          type="checkbox"
                          id="switchAutoSyncSave"
                          v-model="form.auto_sync_expense_flag"
                          name="auto_sync_expense_flag"
                          true-value="1"
                          false-value="0"
                          checked="checked"
                          class="custom-control-input"
                        />
                        <label
                          for="switchAutoSyncSave"
                          class="custom-control-label"
                        >
                          <span class="switch-icon-left">No</span>
                          <span class="switch-icon-right">Yes</span>
                        </label>
                      </div>
                    </div>
                    <!-- auto_save_Flag : END -->

                    <!-- assigned_mechanic : start -->
                    <div class="col-md-4 col-xs-12 form-group">
                      <label for="assigned_mechanic">Assigned Mechanic</label>
                      <select
                        id="assigned_mechanic"
                        class="custom-select"
                        name="assigned_mechanic"
                        v-model="form.assigned_mechanic"
                      >
                        <option disabled value="">-- Select Mechanic --</option>
                        <option
                          :value="mechanic"
                          v-for="mechanic in getListOfMechanics"
                          :key="mechanic"
                        >
                          {{ mechanic }}
                        </option>
                      </select>

                      <small
                        id="type-error"
                        class="form-text invalid-feedback"
                        >{{ errors.assigned_mechanic }}</small
                      >
                    </div>
                    <!-- assigned_mechanic : end -->

                    <!-- assigned_advisor : start -->
                    <div class="col-md-4 col-xs-12 form-group">
                      <label for="assigned_advisor">Assigned Advisor</label>
                      <select
                        id="assigned_advisor"
                        class="custom-select"
                        name="assigned_advisor"
                        v-model="form.assigned_advisor"
                      >
                        <option disabled value="">-- Select Advisor --</option>
                        <option
                          :value="user.user_id"
                          v-for="user in allUsers"
                          :key="user.user_id + '_advisor'"
                          v-text="user.name"
                        ></option>
                      </select>

                      <small
                        id="type-error"
                        class="form-text invalid-feedback"
                        >{{ errors.assigned_advisor }}</small
                      >
                    </div>
                    <!-- assigned_advisor : end -->

                    <!-- inventory : START -->
                    <div class="col-md-6 col-xs-12">
                      <!-- inventory : START -->
                      <div class="form-group">
                        <label for="inventory">Inventory: </label>
                        <v-select
                          id="inventory"
                          placeholder="Inventory"
                          label="label"
                          v-model="selected_inventory"
                          clearable
                          :options="createIndexForInventories"
                        >
                        </v-select>
                      </div>

                      <!-- inventory : END -->
                    </div>
                    <!-- inventory : END -->

                    <!-- customer : START -->
                    <div
                      class="col-md-6 col-xs-12"
                      v-show="form.type === 'External'"
                    >
                      <!-- customer 1 : START -->
                      <div class="form-group">
                        <label for="customer">Customer: </label>
                        <v-select
                          id="customer"
                          placeholder="Customer"
                          label="label"
                          v-model="selected_customer"
                          clearable
                          :options="createIndexForCustomers"
                        >
                        </v-select>
                      </div>

                      <!-- customer 1 : END -->
                    </div>
                    <!-- customer : END -->

                    <div class="w-100"></div>

                    <!-- route to add new inventory or show existing inventory selected : START -->
                    <div
                      class="col-md-6 col-xs-12 text-center"
                      v-if="
                        inventory == null || Object.keys(inventory).length === 0
                      "
                    >
                      <router-link
                        tag="span"
                        :to="{ name: 'inventory-create' }"
                        class="d-inline-block mr-1"
                        exact
                      >
                        <a class="btn btn-link">Add New Inventory</a>
                      </router-link>
                    </div>
                    <div class="col-md-6 col-xs-12 text-left" v-else>
                      <div class="card-body">
                        <h4 class="card-title text-left">
                          <i class="bx bxs-car mr-1"></i>
                          {{ inventory.year }} {{ inventory.make }}
                          {{ inventory.model }} #{{ inventory.stock_no }}
                        </h4>
                        <li class="list-group-item">
                          TRIM :
                          {{ inventory.trim | truncate(20, "...") }}
                        </li>
                        <li class="list-group-item">
                          VIN :
                          {{ inventory.vin }}
                        </li>
                        <li class="list-group-item">
                          <i class="bx bxs-dashboard mr-1"></i>
                          {{ inventory.odometer }} {{ inventory.km_miles }}
                        </li>
                      </div>
                    </div>
                    <!-- route to add new inventory or show existing inventory selected : END -->

                    <!-- route to add new customer or show existing customer selected : START -->
                    <div
                      v-show="form.type === 'External'"
                      class="col-md-6 col-xs-12 text-center"
                      v-if="
                        customer == null || Object.keys(customer).length === 0
                      "
                    >
                      <router-link
                        tag="span"
                        :to="{ name: 'customer-create' }"
                        class="d-inline-block mr-1"
                        exact
                      >
                        <a class="btn btn-link">Add New Customer</a>
                      </router-link>
                    </div>
                    <div
                      class="col-md-6 col-xs-12 text-left"
                      v-show="form.type === 'External'"
                      v-else
                    >
                      <div class="card-body">
                        <h4
                          class="card-title text-left mb-25"
                          v-if="customer.customer_type == '0'"
                        >
                          <div class="avatar bg-warning mr-1 mb-0">
                            <div class="avatar-content">IND</div>
                          </div>
                          {{ customer.first_name }}
                          {{ customer.last_name }}
                        </h4>
                        <h4 class="card-title text-left mb-25" v-else>
                          <div class="avatar bg-danger mr-1 mb-0">
                            <div class="avatar-content">COM</div>
                          </div>
                          {{ customer.company_name }}
                        </h4>
                        <li class="list-group-item">
                          <span v-if="customer.rin_or_dl_flag == '0'"
                            >RIN : {{ customer.rin }}</span
                          >
                          <span v-else>DL : {{ customer.driver_licence }}</span>
                        </li>
                        <li
                          class="list-group-item"
                          v-show="
                            customer.phone != '' && customer.phone != null
                          "
                        >
                          <i class="bx bx-phone-call phone-icon mr-1"></i>
                          {{ phoneFormat(customer.phone) }}
                        </li>
                        <li
                          class="list-group-item"
                          v-show="
                            customer.mobile != '' && customer.mobile != null
                          "
                        >
                          <i class="bx bxs-mobile text-danger mr-1"></i>
                          {{ phoneFormat(customer.mobile) }}
                        </li>
                        <li
                          class="list-group-item"
                          v-show="
                            customer.email != '' && customer.email != null
                          "
                        >
                          <i class="bx bxs-inbox text-success mr-1"></i>
                          {{ customer.email }}
                        </li>
                      </div>
                    </div>
                    <!-- route to add new customer or show existing customer selected : END -->
                  </div>
                </div>
                <!-- Details Section : START -->

                <!-- Check Out Section : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Check Out</h5>
                  </div>
                  <div class="row">
                    <!-- check_out_date : START -->
                    <div class="col-md-4 col-xs-12">
                      <div class="form-group">
                        <label for="check_out_date">Date</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <date-picker
                            :format="'MM/DD/YYYY'"
                            :value-type="'MM/DD/YYYY'"
                            type="date"
                            :clearable="false"
                            placeholder="Date"
                            v-model="form.check_out_date"
                            id="check_out_date"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('check_out_date'),
                            }"
                            @change="delete errors.check_out_date"
                            :title="errors.check_out_date"
                          ></date-picker>
                        </fieldset>
                        <small
                          id="check_out_date-error"
                          class="form-text invalid-feedback"
                          >{{ errors.check_out_date }}</small
                        >
                      </div>
                    </div>
                    <!-- check_out_date : END -->

                    <!-- check_out_time : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="check_out_time">Time</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="check_out_time"
                            type="time"
                            placeholder="Time"
                            v-model="form.check_out_time"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('check_out_time'),
                            }"
                            @keyup="delete errors.check_out_time"
                            :title="errors.check_out_time"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-time font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="check_out_time-error"
                          class="form-text invalid-feedback"
                          >{{ errors.check_out_time }}</small
                        >
                      </div>
                    </div>
                    <!-- check_out_time : END -->

                    <!-- check_out_odometer : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="check_out_odometer">Odometer</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="check_out_odometer"
                            type="text"
                            placeholder="Odometer"
                            v-model="form.check_out_odometer"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('check_out_odometer'),
                            }"
                            @keyup="delete errors.check_out_odometer"
                            :title="errors.check_out_odometer"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dashboard font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="check_out_odometer-error"
                          class="form-text invalid-feedback"
                          >{{ errors.check_out_odometer }}</small
                        >
                      </div>
                    </div>
                    <!-- check_out_odometer : END -->
                  </div>
                </div>
                <!-- Check Out Section : END -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import Toast from "../../../library/Toast";
import vSelect from "vue-select";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

import { mapGetters, mapActions } from "vuex";
export default {
  name: "WorkOrderCreate",
  props: {
    id: { required: false },
    allUsers: { required: true },
    prev_name: { required: false },
    app_metadata: { required: true },
  },
  data() {
    return {
      errors: [],
      load: false,
      inventory: {},
      customer: {},
      selected_customer: null,
      selected_inventory: null,
      customers: [],
      inventories: [],
      form: {
        status: "Open",
        auto_sync_expense_flag: 0,
        type: "Internal",

        check_in_date: null,
        check_in_time: null,
        check_out_date: null,
        check_out_time: null,

        check_in_odometer: "",
        check_out_odometer: "",

        assigned_mechanic: "",
        assigned_advisor: "",
      },
    };
  },
  components: {
    "v-select": vSelect,
    DatePicker,
  },
  watch: {
    "form.check_in_odometer": function (newVal, oldVal) {
      this.form.check_in_odometer = this.$helpers.coerceToInteger(
        this.form.check_in_odometer
      );
    },
    "form.check_out_odometer": function (newVal, oldVal) {
      this.form.check_out_odometer = this.$helpers.coerceToInteger(
        this.form.check_out_odometer
      );
    },
    selected_inventory: function (newVal, oldVal) {
      if (newVal) {
        this.inventory = newVal;
      }
    },
    selected_customer: function (newVal, oldVal) {
      if (newVal) {
        this.customer = newVal;
      }
    },
  },
  computed: {
    ...mapGetters("workOrder", [
      "getStatuses",
      "getListOfMechanics",
      "getTypes",
    ]),
    createIndexForCustomers() {
      if (this.customers.length > 0) {
        for (var index in this.customers) {
          let label =
            this.customers[index].first_name != "" &&
            this.customers[index].first_name != null
              ? `${this.customers[index].first_name} `
              : "";

          label +=
            this.customers[index].last_name != "" &&
            this.customers[index].last_name != null
              ? `${this.customers[index].last_name}`
              : "";

          label +=
            this.customers[index].company_name != "" &&
            this.customers[index].company_name != null
              ? ` < ${this.customers[index].company_name} >`
              : "";

          this.customers[index].label = label;
        }

        this.$forceUpdate();
      }
      return this.customers;
    },
    createIndexForInventories() {
      if (this.inventories.length > 0) {
        for (var index in this.inventories) {
          let label =
            this.inventories[index].year != "" &&
            this.inventories[index].year != null
              ? `${this.inventories[index].year} `
              : "";

          label +=
            this.inventories[index].make != "" &&
            this.inventories[index].make != null
              ? `${this.inventories[index].make} `
              : "";

          label +=
            this.inventories[index].model != "" &&
            this.inventories[index].model != null
              ? `${this.inventories[index].model} `
              : "";

          label +=
            this.inventories[index].stock_no != "" &&
            this.inventories[index].stock_no != null
              ? `< ${this.inventories[index].stock_no} >`
              : "";

          label +=
            this.inventories[index].vin != "" &&
            this.inventories[index].vin != null
              ? `< ${this.inventories[index].vin} >`
              : "";

          this.inventories[index].label = label;
        }

        this.$forceUpdate();
      }
      return this.inventories;
    },
  },
  methods: {
    ...mapActions("workOrder", ["commitListOfMechanics"]),
    phoneFormat(phoneText) {
      if (phoneText == null) {
        return null;
      }
      var x = phoneText.replace(/\D/g, "").match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
      return !x[2] ? x[1] : "(" + x[1] + ") " + x[2] + (x[3] ? "-" + x[3] : "");
    },
    async findByStockNumber(stock_no) {
      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          "/api/business/inventory?all=true&zm_stock_no[]=" +
          stock_no,
      })
        .then(({ data }) => {
          if (data.hasOwnProperty("inventory")) {
            if (data["inventory"].hasOwnProperty("0")) {
              this.inventory = data["inventory"]["0"];
            }
          }
        })
        .catch(() => {
          let error =
            "There were some errors in retrieving the inventory details.";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
    async findCustomerById(id) {
      await axios({
        method: "get",
        url: window.location.origin + "/api/business/customers/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.customer = data;
        })
        .catch(() => {
          let error =
            "There were some errors in retrieving the customer details.";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
    getCustomers() {
      const vm = this;
      axios({
        method: "get",
        url: window.location.origin + "/api/business/customers?paginate=false",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          vm.customers = data.customer_listing;
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    getInventories() {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/inventory?paginate=false&all=true&zm_status[]=Instock",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          vm.inventories = data.inventory;
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    async getWorkOrder(id) {
      const vm = this;
      const allPromises = [];
      await axios({
        method: "get",
        url: window.location.origin + "/api/business/work-orders/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }

          let check_in_date_time = moment(data.check_in_date_time);
          this.form.check_in_date = check_in_date_time.isValid()
            ? check_in_date_time.format("MM/DD/YYYY")
            : null;

          this.form.check_in_time = check_in_date_time.isValid()
            ? check_in_date_time.format("HH:mm")
            : null;

          let check_out_date_time = moment(data.check_out_date_time);
          this.form.check_out_date = check_out_date_time.isValid()
            ? check_out_date_time.format("MM/DD/YYYY")
            : null;

          this.form.check_out_time = check_out_date_time.isValid()
            ? check_out_date_time.format("HH:mm")
            : null;

          if (data.inventory && data.inventory.length > 0) {
            data.inventory.forEach((element) => {
              allPromises.push(this.findByStockNumber(element["stock_no"]));
            });
          }

          if (data.customer && data.customer.length > 0) {
            data.customer.forEach((single_customer) => {
              allPromises.push(
                this.findCustomerById(single_customer["customer_id"])
              );
            });
          }
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the vendor";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });

      await Promise.all(allPromises);
    },
    save() {
      const vm = this;

      //create data
      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && this.form[key] != null) {
          formData.append(key, this.form[key]);
        }
      }

      let check_in_date_time = moment(
        this.form.check_in_date + " " + this.form.check_in_time,
        "MM/DD/YYYY HH:mm"
      );

      formData.append(
        "check_in_date_time",
        check_in_date_time.isValid() ? check_in_date_time : null
      );

      let check_out_date_time = moment(
        this.form.check_out_date + " " + this.form.check_out_time,
        "MM/DD/YYYY HH:mm"
      );

      formData.append(
        "check_out_date_time",
        check_out_date_time.isValid() ? check_out_date_time : null
      );

      if (this.customer != null && this.customer != {}) {
        if (this.customer.hasOwnProperty("id")) {
          formData.append("customer", this.customer["id"]);
        }
      }

      if (this.inventory != null && this.inventory != {}) {
        if (this.inventory.hasOwnProperty("stock_no")) {
          formData.append("inventory", this.inventory["stock_no"]);
        }
      }

      let request_url = window.location.origin;
      let update_request =
        this.$route.params.id && this.$route.name == "work-order-update";

      //success message setting
      let success_message = update_request
        ? `Work Order updated successfully.`
        : `New work order added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/work-orders/update/${this.$route.params.id}`
        : `${request_url}/api/business/work-orders/create`;

      //message during request
      let request_message = update_request
        ? `Updating work order ...`
        : `Adding new work order ...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();

          setTimeout(function () {
            Event.fire("save_work_order_complete", data);
          }, 500);
        })
        .catch(function (error) {
          let update_request =
            vm.$route.params.id && vm.$route.name == "work-order-update";
          let error_message = update_request
            ? "There were errors while updating the work order."
            : "There were some errors while adding new work order.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();
          setTimeout(function () {
            Event.fire("save_work_order_complete", null);
          }, 500);
        });
    },
  },
  created() {
    this.commitListOfMechanics();
    this.getCustomers();
    this.getInventories();

    if (this.$route.params.id && this.$route.name == "work-order-update") {
      this.getWorkOrder(this.$route.params.id);
    } else {
      this.form.check_in_date = moment(new Date()).format("MM/DD/YYYY");
      this.form.check_in_time = moment(new Date()).format("HH:mm");
      this.form.assigned_advisor = this.app_metadata.id;
    }
  },
  mounted() {
    const vm = this;
    Event.listen("save_work_order", (data) => {
      vm.save();
    });
  },
  beforeDestroy() {
    Event.vue.$off("save_work_order", null);
  },
};
</script>
<style lang="scss">
#work-order-create-section {
  .vdpComponent {
    height: 3.9em !important;
  }
  .vdpComponent input {
    padding-left: 2.2em !important;
    line-height: 1.4;
    color: #777;
    background-color: #ffffff !important;
    background-clip: padding-box;
    border: 1px solid #dfe3e7;
    border-radius: 0.267rem;
    font-size: 1.4em;
  }

  .vdpComponent input {
    background: white;
    border: 0px;
  }
  .vdpComponent input:read-only {
    background: white !important;
    padding-right: 0em;
    font-size: 1.4em;
    border: 0px;
    padding-left: 0px !important;
  }
}
</style>
