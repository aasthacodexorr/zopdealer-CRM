<template>
  <div v-if="tenantApp">
    <div class="app-content content fixed-height-block">
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <router-link
              title="Back To List"
              tag="a"
              :to="{ name: 'product-list' }"
              class="nav-link pr-0 pl-0 d-flex align-items-center"
              exact
            >
              <i class="bx bx-caret-left" style="font-size: 1.4em"></i>
            </router-link>
          </li>
          <li class="nav-item">
            <a
              class="nav-link d-flex align-items-center active"
              id="product-create"
              data-toggle="tab"
              href="#product-create"
              aria-controls="lead-view"
              role="tab"
              aria-selected="true"
            >
              <span class="">{{ button_title }}</span>
            </a>
          </li>

          <div class="btn-feild-box">
            <button class="btn btn-primary mr-1" @click.prevent="save()">
              Save
            </button>
          </div>
        </ul>
      </div>
    </div>
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="">
        <div class="content-body">
          <!-- form layout section start -->
          <div class="tab-content">
            <!-- single tab content goes here : START -->
            <div
              class="tab-pane active"
              id="product-create-tab"
              aria-labelledby="product-create"
              role="tabpanel"
            >
              <!-- form layout section start -->
              <section id="product-add-section">
                <div class="row match-height">
                  <div class="col-md-12 col-12">
                    <div class="card shadow-none mb-0">
                      <div class="card-content">
                        <div class="card-body">
                          <form class="form form-horizontal webapp-form">
                            <div class="form-body">
                              <!-- form : START -->

                              <!-- section : START -->
                              <div class="row">
                                <div class="col-md-12 col-12">
                                  <div class="m-width-s">
                                    <div class="row">
                                      <!-- name : START -->
                                      <div class="col-md-4">
                                        <label style="color: red">Name *</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <input
                                              type="text"
                                              aria-label="Name * "
                                              :class="{
                                                'is-invalid': errors.hasOwnProperty(
                                                  'name'
                                                ),
                                              }"
                                              v-model="form.name"
                                              class="form-control mr-1"
                                              placeholder="Name"
                                              @keyup="delete errors.name"
                                              :title="errors.name"
                                            />
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- name : END -->

                                      <!-- item_code : START -->
                                      <div class="col-md-4">
                                        <label style="color: red">Item Code *</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <input
                                              type="text"
                                              aria-label="Item Code"
                                              :class="{
                                                'is-invalid': errors.hasOwnProperty(
                                                  'item_code'
                                                ),
                                              }"
                                              v-model="form.item_code"
                                              class="form-control mr-1"
                                              placeholder="Item Code"
                                              @keyup="delete errors.item_code"
                                              :title="errors.item_code"
                                            />
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- item_code : END -->

                                      <!-- type & category : START -->
                                      <div class="col-md-4">
                                        <label>Type & Category</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <select
                                              class="custom-select form-control mr-1"
                                              id="type"
                                              v-model="form.type"
                                              name="type"
                                            >
                                              <option
                                                value=""
                                                selected
                                                disabled
                                              >
                                                - Select Type -
                                              </option>
                                              <option :value="ptype" :key="ptype" v-for="ptype in getTypes">{{ptype}}</option>
                                            </select>
                                            <select
                                              class="custom-select form-control"
                                              id="category"
                                              v-model="form.category"
                                              name="category"
                                            >
                                              <option
                                                value=""
                                                selected
                                                disabled
                                              >
                                                - Select Category -
                                              </option>
                                              <option :value="category" :key="category" v-for="category in getCategories">{{category}}</option>
                                            </select>
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- type & category : END -->

                                      <!-- description : START -->
                                      <div class="col-md-4">
                                        <label>Description</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <textarea
                                              type="text"
                                              aria-label="Description"
                                              :class="{
                                                'is-invalid': errors.hasOwnProperty(
                                                  'description'
                                                ),
                                              }"
                                              v-model="form.description"
                                              class="form-control mr-1"
                                              placeholder="Description"
                                              @keyup="delete errors.description"
                                              :title="errors.description"
                                            ></textarea>
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- description : END -->
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- section : END -->

                              <!-- section : START -->
                              <div class="row">
                                <div class="col-md-12 col-12">
                                  <div class="m-width-s">
                                    <div class="row">
                                      <div class="col-md-4">
                                        <label>Vendor Details</label>
                                      </div>

                                      <div class="col-md-4 form-group">
                                        <v-select
                                          taggable
                                          placeholder="Vendor Name"
                                          label="company_name"
                                          :reduce="
                                            (vendor) => vendor.company_name
                                          "
                                          v-model="form.vendor_name"
                                          clearable
                                          :options="allVendors"
                                        ></v-select>
                                      </div>

                                      <div class="col-md-4 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <input
                                              type="text"
                                              aria-label="Vendor Code"
                                              :class="{
                                                'is-invalid': errors.hasOwnProperty(
                                                  'vendor_code'
                                                ),
                                              }"
                                              v-model="form.vendor_code"
                                              class="form-control mr-1"
                                              placeholder="Vendor Code"
                                              @keyup="delete errors.vendor_code"
                                              :title="errors.vendor_code"
                                            />
                                          </div>
                                        </fieldset>
                                      </div>

                                      <template v-if="form.type !== 'Labour'">
                                      <div class="col-md-4">
                                        <label>Available Quantity</label>
                                      </div>
                                      <div class="col-md-4 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <input
                                              type="text"
                                              aria-label="Available Quantity"
                                              :class="{
                                                'is-invalid': errors.hasOwnProperty(
                                                  'available_quantity'
                                                ),
                                              }"
                                              v-model="form.available_quantity"
                                              class="form-control mr-1"
                                              placeholder="Available Quantity"
                                              @keyup="
                                                delete errors.available_quantity
                                              "
                                              :title="errors.available_quantity"
                                            />
                                          </div>
                                        </fieldset>
                                      </div>

                                      <div class="col-md-2">
                                        <label>Auto Update Qty</label>
                                      </div>

                                      <div class="col-md-2 form-group">
                                        <div
                                          class="custom-control custom-switch"
                                        >
                                          <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            id="autoUpdateQuantity"
                                            name="Auto Update Quantity"
                                            v-model="form.auto_update_quantity"
                                            :true-value="'1'"
                                            :false-value="'0'"
                                            checked
                                          />
                                          <label
                                            class="custom-control-label"
                                            for="autoUpdateQuantity"
                                          >
                                            <span class="switch-icon-left">
                                              ON</span
                                            >
                                            <span class="switch-icon-right"
                                              >OFF</span
                                            >
                                          </label>
                                        </div>
                                      </div>

                                      <div class="col-md-4">
                                        <label>Alert on Quantity</label>
                                      </div>
                                      <div class="col-md-4 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <input
                                              type="text"
                                              aria-label="Alert on Quantity"
                                              :class="{
                                                'is-invalid': errors.hasOwnProperty(
                                                  'alert_on_threshold_quantity'
                                                ),
                                              }"
                                              v-model="
                                                form.alert_on_threshold_quantity
                                              "
                                              class="form-control mr-1"
                                              placeholder="Alert on Quantity"
                                              @keyup="
                                                delete errors.alert_on_threshold_quantity
                                              "
                                              :title="
                                                errors.alert_on_threshold_quantity
                                              "
                                            />
                                          </div>
                                        </fieldset>
                                      </div>
                                    </template>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- section : END -->

                              <!-- section : START -->
                              <div class="row">
                                <div class="col-md-12 col-12">
                                  <div class="m-width-s">
                                    <div class="row">
                                      <div class="col-md-3">
                                        <label>Cost</label>
                                      </div>

                                      <!-- Cost : START-->
                                      <div class="col-md-3 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <input
                                              type="text"
                                              aria-label="Cost"
                                              :class="{
                                                'is-invalid': errors.hasOwnProperty(
                                                  'cost'
                                                ),
                                              }"
                                              v-model="form.cost"
                                              class="form-control mr-1"
                                              placeholder="Cost"
                                              @keyup="delete errors.cost"
                                              :title="errors.cost"
                                            />
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- Cost : END-->
                                      <!-- Tax 1 : START -->
                                      <div class="col-md-3 form-group">
                                        <select
                                          class="custom-select form-control mr-1"
                                          :class="{
                                            'is-invalid': errors.hasOwnProperty(
                                              'cost_tax_type'
                                            ),
                                          }"
                                          @keyup="delete errors.cost_tax_type"
                                          :title="errors.cost_tax_type"
                                          v-model="form.cost_tax_type"
                                          placeholder="Tax Type"
                                          id="cost_tax_type"
                                        >
                                          <option value="" selected disabled
                                            >Tax Type</option
                                          >
                                          <option
                                            :value="tax.name"
                                            v-for="tax in businessTaxSettings"
                                            :key="tax.id"
                                            >{{ tax.name }}</option
                                          >
                                          <option value="Other">Other</option>
                                        </select>
                                      </div>
                                      <!-- Tax 1 : END-->

                                      <!-- Tax 2 : START -->
                                      <div class="col-md-3 form-group">
                                        <select
                                          :disabled="
                                            form.cost_tax_type === 'Other'
                                          "
                                          class="custom-select form-control mr-1"
                                          :class="{
                                            'is-invalid': errors.hasOwnProperty(
                                              'cost_tax_type_secondary'
                                            ),
                                          }"
                                          @keyup="
                                            delete errors.cost_tax_type_secondary
                                          "
                                          :title="
                                            errors.cost_tax_type_secondary
                                          "
                                          v-model="form.cost_tax_type_secondary"
                                          placeholder="Tax Type"
                                          id="cost_tax_type_secondary"
                                        >
                                          <option value="" selected disabled
                                            >Tax Type</option
                                          >
                                          <option
                                            :value="tax.name"
                                            v-for="tax in businessTaxSettings"
                                            :key="tax.id"
                                            >{{ tax.name }}</option
                                          >
                                        </select>
                                      </div>
                                      <!-- Tax 2 : END-->

                                      <div class="col-md-3">
                                        <label>Total Paid Taxes</label>
                                      </div>

                                      <!-- Total Taxes : START-->
                                      <div class="col-md-3 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <input
                                              :readonly="
                                                form.cost_tax_type !== 'Other'
                                              "
                                              type="text"
                                              aria-label="Total Taxes"
                                              :class="{
                                                'is-invalid': errors.hasOwnProperty(
                                                  'cost_tax_amount_total'
                                                ),
                                              }"
                                              v-model="
                                                form.cost_tax_amount_total
                                              "
                                              class="form-control mr-1"
                                              placeholder="Total Taxes"
                                              @keyup="
                                                delete errors.cost_tax_amount_total
                                              "
                                              :title="
                                                errors.cost_tax_amount_total
                                              "
                                            />
                                          </div>
                                          <i
                                            v-show="cost_recalculation"
                                            style="position:absolute;top:10px;right:0px;"
                                            class="bx bx-loader-alt bx-spin text-primary"
                                          ></i>
                                        </fieldset>
                                      </div>
                                      <!-- Total Taxes : END-->
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- section : END -->

                              <!-- section : START -->
                              <div class="row">
                                <div class="col-md-12 col-12">
                                  <div class="m-width-s">
                                    <div class="row">
                                      <div class="col-md-3">
                                        <label>Retail Price</label>
                                      </div>

                                      <!-- Price : START-->
                                      <div class="col-md-3 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <input
                                              type="text"
                                              aria-label="Price"
                                              :class="{
                                                'is-invalid': errors.hasOwnProperty(
                                                  'price'
                                                ),
                                              }"
                                              v-model="form.price"
                                              class="form-control mr-1"
                                              placeholder="Price"
                                              @keyup="delete errors.price"
                                              :title="errors.price"
                                            />
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- Price : END-->

                                      <!-- Tax 1 : START -->
                                      <div class="col-md-3 form-group">
                                        <select
                                          class="custom-select form-control mr-1"
                                          :class="{
                                            'is-invalid': errors.hasOwnProperty(
                                              'tax_type'
                                            ),
                                          }"
                                          @keyup="delete errors.tax_type"
                                          :title="errors.tax_type"
                                          v-model="form.tax_type"
                                          placeholder="Tax Type"
                                          id="tax_type"
                                        >
                                          <option value="" selected disabled
                                            >Tax Type</option
                                          >
                                          <option
                                            :value="tax.name"
                                            v-for="tax in businessTaxSettings"
                                            :key="tax.id"
                                            >{{ tax.name }}</option
                                          >
                                          <option value="Other">Other</option>
                                        </select>
                                      </div>
                                      <!-- Tax 1 : END-->

                                      <!-- Tax 2 : START -->
                                      <div class="col-md-3 form-group">
                                        <select
                                          :disabled="form.tax_type === 'Other'"
                                          class="custom-select form-control mr-1"
                                          :class="{
                                            'is-invalid': errors.hasOwnProperty(
                                              'tax_type_secondary'
                                            ),
                                          }"
                                          @keyup="
                                            delete errors.tax_type_secondary
                                          "
                                          :title="errors.tax_type_secondary"
                                          v-model="form.tax_type_secondary"
                                          placeholder="Tax Type"
                                          id="tax_type_secondary"
                                        >
                                          <option value="" selected disabled
                                            >Tax Type</option
                                          >
                                          <option
                                            :value="tax.name"
                                            v-for="tax in businessTaxSettings"
                                            :key="tax.id"
                                            >{{ tax.name }}</option
                                          >
                                          <option value="Other">Other</option>
                                        </select>
                                      </div>
                                      <!-- Tax 2 : END-->

                                      <div class="col-md-3">
                                        <label>Total Taxes</label>
                                      </div>

                                      <!-- Total Taxes : START-->
                                      <div class="col-md-3 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <input
                                              :readonly="
                                                form.tax_type !== 'Other'
                                              "
                                              type="text"
                                              aria-label="Total Taxes"
                                              :class="{
                                                'is-invalid': errors.hasOwnProperty(
                                                  'tax_amount_total'
                                                ),
                                              }"
                                              v-model="form.tax_amount_total"
                                              class="form-control mr-1"
                                              placeholder="Total Taxes"
                                              @keyup="
                                                delete errors.tax_amount_total
                                              "
                                              :title="errors.tax_amount_total"
                                            />
                                            <i
                                              v-show="price_recalculation"
                                              style="position:absolute;top:10px;right:-1em;"
                                              class="bx bx-loader-alt bx-spin text-primary"
                                            ></i>
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- Total Taxes : END-->
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- section : END -->

                              <!-- section : START -->
                              <div class="row">
                                <div class="col-md-12 col-12">
                                  <div class="m-width-s">
                                    <div class="row">
                                      <!-- additional_information : START -->
                                      <div class="col-md-4">
                                        <label>Additional Information</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <textarea
                                              type="text"
                                              aria-label="Additional Information"
                                              :class="{
                                                'is-invalid': errors.hasOwnProperty(
                                                  'additional_information'
                                                ),
                                              }"
                                              v-model="
                                                form.additional_information
                                              "
                                              class="form-control mr-1"
                                              placeholder="Additional Information"
                                              @keyup="
                                                delete errors.additional_information
                                              "
                                              :title="
                                                errors.additional_information
                                              "
                                            ></textarea>
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- additional_information : END -->

                                      <!-- job_card_information : START -->
                                      <div class="col-md-4">
                                        <label>Job Card Information</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <textarea
                                              type="text"
                                              aria-label="Job Card Information"
                                              :class="{
                                                'is-invalid': errors.hasOwnProperty(
                                                  'job_card_information'
                                                ),
                                              }"
                                              v-model="
                                                form.job_card_information
                                              "
                                              class="form-control mr-1"
                                              placeholder="Job Card Information"
                                              @keyup="
                                                delete errors.job_card_information
                                              "
                                              :title="
                                                errors.job_card_information
                                              "
                                            ></textarea>
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- job_card_information : END -->

                                      <!-- warranty : START -->
                                      <div class="col-md-4">
                                        <label>Warranty</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <textarea
                                              type="text"
                                              aria-label="Warranty"
                                              :class="{
                                                'is-invalid': errors.hasOwnProperty(
                                                  'warranty'
                                                ),
                                              }"
                                              v-model="form.warranty"
                                              class="form-control mr-1"
                                              placeholder="Warranty"
                                              @keyup="delete errors.warranty"
                                              :title="errors.warranty"
                                            ></textarea>
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- warranty : END -->
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- section : END -->

                              <!-- form : END -->
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
            <!-- single tab content goes here : END -->
          </div>
        </div>
      </div>
    </div>
    <!-- END: Content-->
  </div>
</template>
<script>
import Toast from "../../library/Toast";
import { mapGetters, mapActions } from "vuex";
import vSelect from "vue-select";

export default {
  name: "ProductAdd",
  props: ["application", "app_metadata"],
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      title: "Create Product",
      button_title: "Create",
      load: true,
      cost_recalculation: false,
      price_recalculation: false,
      errors: [],
      form: {
        name: "",
        item_code: "",
        description: "",
        category: "",
        type: "",
        vendor_code: "",
        vendor_name: "",
        available_quantity: 0,
        alert_on_threshold_quantity: 0,
        auto_update_quantity: 0,

        price: 0,
        tax_type: "",
        tax_percentage: 0,
        tax_amount: 0,

        tax_type_secondary: "",
        tax_amount_secondary: 0,
        tax_percentage_secondary: 0,
        tax_amount_total: 0,

        cost: 0,
        cost_tax_type: "",
        cost_tax_percentage: 0,
        cost_tax_amount: 0,

        cost_tax_type_secondary: "",
        cost_tax_amount_secondary: 0,
        cost_tax_percentage_secondary: 0,
        cost_tax_amount_total: 0,

        additional_information: "",
        job_card_information: "",
        warranty: "",
      },
    };
  },
  computed: {
    ...mapGetters("configuration", ["businessTaxSettings"]),
    ...mapGetters("productView", [
      "list",
      "getViewId",
      "getViewTitle",
      "getEntities",
      "getPager",
      "getSortColumn",
      "getLoadState",
      "getEntitiesCount",
      "getCurrentPage",
      "getCurrentEntity",
      "getSearchString",
      "getSearch",
    ]),
    ...mapGetters("product", ["getTypes","getCategories"]),
    ...mapGetters("vendor", ["allVendors"]),
    tenantApp() {
      return this.application == "tenant";
    },
    updateView() {
      return this.$route.name == "product-update" && this.$route.params.id;
    },
    mainApp() {
      return this.application == "main";
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
  },
  watch: {
    "form.available_quantity": function(newVal, oldVal) {
      this.form.available_quantity = this.$helpers.coerceToInteger(
        this.form.available_quantity
      );
    },
    "form.alert_on_threshold_quantity": function(newVal, oldVal) {
      this.form.alert_on_threshold_quantity = this.$helpers.coerceToInteger(
        this.form.alert_on_threshold_quantity
      );
    },
    "form.price": function(newVal, oldVal) {
      const vm = this;
      this.form.price = this.$helpers.coerceToNumberString(this.form.price);
      let price = parseFloat(this.form.price);
      if ((this.load === false) & (newVal !== oldVal)) {
        setTimeout(function() {
          vm.calculateLineTotal(
            price,
            vm.form.tax_type,
            vm.form.tax_type_secondary,
            vm.form.tax_amount_total,
            {
              tax_percentage: "tax_percentage",
              tax_amount: "tax_amount",
              tax_percentage_secondary: "tax_percentage_secondary",
              tax_amount_secondary: "tax_amount_secondary",
              tax_amount_total: "tax_amount_total",
            }
          );
        }, 400);
      }
    },
    "form.tax_type": function(newVal, oldVal) {
      const vm = this;
      this.form.tax_percentage = this.$helpers.setTaxRates(
        this.businessTaxSettings,
        newVal
      );

      if ((this.load === false) & (newVal !== oldVal)) {
        vm.calculateLineTotal(
          vm.form.price,
          vm.form.tax_type,
          vm.form.tax_type_secondary,
          vm.form.tax_amount_total,
          {
            tax_percentage: "tax_percentage",
            tax_amount: "tax_amount",
            tax_type_secondary: "tax_type_secondary",
            tax_percentage_secondary: "tax_percentage_secondary",
            tax_amount_secondary: "tax_amount_secondary",
            tax_amount_total: "tax_amount_total",
          }
        );
      }
    },
    "form.tax_type_secondary": function(newVal, oldVal) {
      this.form.tax_percentage_secondary = this.$helpers.setTaxRates(
        this.businessTaxSettings,
        newVal
      );

      const vm = this;

      if ((this.load === false) & (newVal !== oldVal)) {
        vm.calculateLineTotal(
          vm.form.price,
          vm.form.tax_type,
          vm.form.tax_type_secondary,
          vm.form.tax_amount_total,
          {
            tax_percentage: "tax_percentage",
            tax_amount: "tax_amount",
            tax_percentage_secondary: "tax_percentage_secondary",
            tax_amount_secondary: "tax_amount_secondary",
            tax_amount_total: "tax_amount_total",
          }
        );
      }
    },
    "form.cost": function(newVal, oldVal) {
      const vm = this;
      this.form.cost = this.$helpers.coerceToNumberString(this.form.cost);
      let price = parseFloat(this.form.cost);
      if ((this.load === false) & (newVal !== oldVal)) {
        setTimeout(function() {
          vm.calculateLineTotal(
            price,
            vm.form.cost_tax_type,
            vm.form.cost_tax_type_secondary,
            vm.form.cost_tax_amount_total,
            {
              cost_tax_percentage: "tax_percentage",
              cost_tax_amount: "tax_amount",
              cost_tax_percentage_secondary: "tax_percentage_secondary",
              cost_tax_amount_secondary: "tax_amount_secondary",
              cost_tax_amount_total: "tax_amount_total",
            },
            "cost_recalculation"
          );
        }, 400);
      }
    },
    "form.cost_tax_type": function(newVal, oldVal) {
      const vm = this;
      this.form.cost_tax_percentage = this.$helpers.setTaxRates(
        this.businessTaxSettings,
        newVal
      );

      if ((this.load === false) & (newVal !== oldVal)) {
        vm.calculateLineTotal(
          vm.form.cost,
          vm.form.cost_tax_type,
          vm.form.cost_tax_type_secondary,
          vm.form.cost_tax_amount_total,
          {
            cost_tax_percentage: "tax_percentage",
            cost_tax_amount: "tax_amount",
            cost_tax_type_secondary: "tax_type_secondary",
            cost_tax_percentage_secondary: "tax_percentage_secondary",
            cost_tax_amount_secondary: "tax_amount_secondary",
            cost_tax_amount_total: "tax_amount_total",
          },
          "cost_recalculation"
        );
      }
    },
    "form.cost_tax_type_secondary": function(newVal, oldVal) {
      this.form.cost_tax_percentage_secondary = this.$helpers.setTaxRates(
        this.businessTaxSettings,
        newVal
      );

      const vm = this;

      if ((this.load === false) & (newVal !== oldVal)) {
        vm.calculateLineTotal(
          vm.form.cost,
          vm.form.cost_tax_type,
          vm.form.cost_tax_type_secondary,
          vm.form.cost_tax_amount_total,
          {
            cost_tax_percentage: "tax_percentage",
            cost_tax_amount: "tax_amount",
            cost_tax_percentage_secondary: "tax_percentage_secondary",
            cost_tax_amount_secondary: "tax_amount_secondary",
            cost_tax_amount_total: "tax_amount_total",
          },
          "cost_recalculation"
        );
      }
    },
    businessTaxSettings: function(newVal, oldVal) {
      if (this.load == true) {
        let selected_tax = this.businessTaxSettings.filter(
          (tax) => tax.default == "1"
        );

        if (selected_tax.length > 0) {
          this.form.tax_type = this.form.cost_tax_type = selected_tax[0].name;
          if (selected_tax.length > 1) {
            this.form.tax_type_secondary = this.form.cost_tax_type_secondary =
              selected_tax[1].name;
          }
        }
      }

      this.load = false;
    },
  },
  methods: {
    ...mapActions("configuration", ["getBusinessTaxSettings"]),
    ...mapActions("productView", [
      "commitOnlyEntities",
      "commitCurrentEntity",
      "commitLoadState",
    ]),
    ...mapActions("product", [
      "commitCategories",
    ]),
    ...mapActions("vendor", ["getAllVendors"]),
    async calculateLineTotal(
      amount,
      tax_type,
      tax_type_secondary,
      tax_amount_total,
      fields,
      loader_field = "price_recalculation"
    ) {
      const vm = this;
      vm.$data[loader_field] = true;
      if (this.load == false) {
        let formData = new FormData();
        formData.append("amount", amount);
        formData.append("discount", 0);
        formData.append("quantity", 1);
        formData.append("tax_type", tax_type);
        formData.append("tax_type_secondary", tax_type_secondary);
        formData.append("tax_amount_total", tax_amount_total);

        await axios({
          method: "post",
          url: window.location.origin + "/api/business/calculator/line-total",
          data: formData,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
            "Content-Type": "multipart/form-data",
            // 'X-Requested-With' : 'XMLHttpRequest',
          },
        })
          .then(({ data }) => {
            for (const [key, value] of Object.entries(fields)) {
              this.form[key] = data[value];
            }

            vm.$data[loader_field] = false;
          })
          .catch(({ response }) => {
            let error = "There were some errors in calculating the taxes";
            const toast = new Toast(error);
            toast.fire();
            toast.fail(error);
            toast.close();
            vm.$data[loader_field] = false;
          });
      }
    },
    getProduct(id) {
      const vm = this;
      axios({
        method: "get",
        url: window.location.origin + "/api/business/products/show/" + id,
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
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the product";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
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

      let request_url = window.location.origin;
      let update_request =
        this.$route.params.id && this.$route.name == "product-update";

      //success message setting
      let success_message = update_request
        ? `Product Updated Successfully.`
        : `New Product Created.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/products/update/${this.$route.params.id}`
        : `${request_url}/api/business/products/create`;

      //message during request
      let request_message = update_request
        ? `Updating Product ...`
        : `Creating New Product ...`;

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

          if (vm.$route.params.id && vm.$route.name == "product-update") {
            this.commitOnlyEntities();
          } else {
            this.commitOnlyEntities();
          }

          setTimeout(() => {
            vm.$router.back();
          }, 500);
        })
        .catch(function(error) {
          let update_request =
            vm.$route.params.id && vm.$route.name == "product-update";
          let error_message = update_request
            ? "There were errors while updating the product."
            : "There were some errors while creating new product.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
  },
  async created() {
    this.getBusinessTaxSettings();
    this.getAllVendors();
    this.commitCategories();
  },
  mounted() {
    if (this.updateView) {
      this.button_title = "Edit";
      this.title = "Update Product";
      this.getProduct(this.$route.params.id);
      this.commitCurrentEntity(this.$route.params.id);
    }
  },
};
</script>
<style lang="scss">
.small {
  width: 300px;
}
</style>
