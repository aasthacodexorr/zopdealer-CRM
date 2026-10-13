<template>
  <div
    class="tab-pane"
    id="expenses-block-list"
    aria-labelledby="information-tab"
    role="tabpanel"
  >
    <!-- Expenses  form layout section start -->
    <section id="expenses">
      <div class="row">
        <div class="col-12">
          <div class="card shadow-none">
            <div class="card-content">
              <div class="card-body pt-0">
                <div class="row p-1">
                  <h5 class="col-xs-12 text-danger" style="font-size: 0.8em">
                    Note : This information is visible only to the owner and
                    managers. It is not shared with public nor with staff.
                  </h5>
                  <div class="col-xs-12 col-12 mb-1">
                    <button
                      style="font-weight: 800"
                      type="button"
                      class="btn btn-primary"
                      data-toggle="modal"
                      data-target="#inventoryExpenseCost"
                    >
                      <i
                        style="font-size: 1.4em; padding-right: 0.5em"
                        class="bx bx-list-plus"
                      ></i>
                      Add Expense
                    </button>
                  </div>
                  <div class="col-xs-12 col-12">
                    <div class="table-responsive">
                      <table class="table inventory_costs">
                        <thead>
                          <tr>
                            <th class="text-center" scope="col">Date</th>
                            <th class="text-center" scope="col">Details</th>
                            <th class="text-center" scope="col">Vendor</th>
                            <th class="text-center" scope="col">Price</th>
                            <th class="text-center" scope="col">Qty</th>
                            <th class="text-center" scope="col">Discount</th>
                            <th class="text-center" scope="col">Total Tax</th>
                            <th class="text-center" scope="col">Sub Total</th>
                            <th
                              class="text-center"
                              scope="col"
                              style="width: 200px"
                            ></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="cost in inventory_costs" :key="cost.id">
                            <td class="text-center">
                              {{ formatDate(cost.purchase_date) }}
                            </td>
                            <td class="p-1">
                              <h6 class="col-12">{{ cost.name }}</h6>
                              <p class="col-12" style="font-size: 0.8em">
                                {{ cost.description | truncate(50, "...") }}
                              </p>
                            </td>
                            <td class="text-center p-0">
                              {{ cost.vendor_name }}
                            </td>
                            <td class="text-center p-0">
                              <div class="pt-1">
                                {{ `$ ${Number(cost.price).toLocaleString()}` }}
                              </div>
                              <p
                                :title="cost.tax_percentage + ` %`"
                                class="text-primary mb-0"
                                style="font-weight: 600"
                              >
                                {{ cost.tax_type }}
                              </p>
                              <p
                                v-show="cost.tax_type_secondary !== ''"
                                :title="cost.tax_percentage_secondary + ` %`"
                                class="text-primary"
                                style="font-weight: 600"
                              >
                                {{ cost.tax_type_secondary }}
                              </p>
                            </td>
                            <td class="text-center p-0">{{ cost.quantity }}</td>
                            <td class="text-center p-0">
                              {{
                                `$ ${Number(cost.discount).toLocaleString()}`
                              }}
                            </td>
                            <td class="text-center p-0">
                              {{
                                `$ ${Number(
                                  cost.tax_amount_total,
                                ).toLocaleString()}`
                              }}
                            </td>
                            <td class="text-center p-0">
                              {{
                                `$ ${Number(cost.sub_total).toLocaleString()}`
                              }}
                            </td>
                            <td class="p-0">
                              <button
                                class="btn btn-info"
                                @click.prevent="editExpense(cost.id)"
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                "
                              >
                                <i class="bx bx-edit"></i>
                              </button>
                              <button
                                class="btn btn-danger"
                                @click.prevent="deleteExpense(cost.id)"
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                "
                              >
                                <i class="bx bx-trash-alt"></i>
                              </button>
                              <div class="w-100 mt-1"></div>
                              <a
                                :href="parseAttachmentUrl(cost.url)"
                                class="btn btn-secondary p-50 text-uppercase"
                                target="_blank"
                                v-show="showAttachment(cost.url)"
                                rel="noopener noreferrer"
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                "
                              >
                                View File
                              </a>
                              <button
                                @click.prevent="
                                  deleteAttachmentConfirmation(cost.url)
                                "
                                class="btn btn-danger text-uppercase"
                                target="_blank"
                                v-show="showAttachment(cost.url)"
                                rel="noopener noreferrer"
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                "
                              >
                                <i class="bx bx-folder-minus"></i>
                              </button>
                              <label
                                class="btn btn-secondary p-50 text-uppercase"
                                v-show="!showAttachment(cost.url)"
                                :for="`inventory_expense_attachment_${cost.id}`"
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                "
                              >
                                Upload File
                              </label>
                              <input
                                :id="`inventory_expense_attachment_${cost.id}`"
                                class="d-none"
                                type="file"
                                ref="attachment"
                                @change="saveExpenseAttachment($event, cost.id)"
                              />
                            </td>
                          </tr>
                          <tr v-show="showTotal">
                            <td
                              class="p-0 pt-1 text-center"
                              rowspan="6"
                              style="border-bottom: 0px"
                              colspan="5"
                            >
                              <div class="small" style="margin: auto">
                                <doughnut :chart-data="chartData"></doughnut>
                              </div>
                            </td>
                            <td
                              class="p-0 pt-1"
                              style="border-bottom: 0px"
                              colspan="3"
                            >
                              <h5>Vehicle Purchase Price</h5>
                            </td>
                            <td
                              class="p-0 pt-1"
                              style="border-bottom: 0px"
                              colspan="2"
                            >
                              <h5>
                                {{
                                  `$ ${Number(
                                    vehiclePurchasePrice,
                                  ).toLocaleString()}`
                                }}
                              </h5>
                            </td>
                          </tr>
                          <tr v-show="showTotal">
                            <td
                              class="p-0"
                              style="border-bottom: 0px"
                              colspan="3"
                            >
                              <h5>Additional Expenses Total</h5>
                            </td>
                            <td
                              class="p-0"
                              style="border-bottom: 0px"
                              colspan="2"
                            >
                              <h5>
                                {{
                                  `$ ${Number(getTotalPrice).toLocaleString()}`
                                }}
                              </h5>
                            </td>
                          </tr>
                          <tr v-show="showTotal">
                            <td
                              class="p-0"
                              style="border-bottom: 0px"
                              colspan="3"
                            >
                              <h5>Discount</h5>
                            </td>
                            <td
                              class="p-0"
                              style="border-bottom: 0px"
                              colspan="2"
                            >
                              <h5
                                style="position: relative"
                                class="text-success"
                              >
                                <span style="position: absolute; left: -1em"
                                  >-</span
                                >{{
                                  `$ ${Number(
                                    getTotalDiscount,
                                  ).toLocaleString()}`
                                }}
                              </h5>
                            </td>
                          </tr>
                          <tr v-show="showTotal">
                            <td class="p-0" colspan="3"><h5>Sub Total</h5></td>
                            <td class="p-0" colspan="2">
                              <h5 style="position: relative">
                                {{
                                  `$ ${Number(
                                    getTotalBeforTax,
                                  ).toLocaleString()}`
                                }}
                              </h5>
                            </td>
                          </tr>
                          <tr v-show="showTotal">
                            <td class="p-0" colspan="3"><h5>Total Tax</h5></td>
                            <td class="p-0" colspan="2">
                              <h5
                                style="position: relative"
                                class="text-danger"
                              >
                                <span style="position: absolute; left: -1em"
                                  >+</span
                                >{{
                                  `$ ${Number(getTotalTax).toLocaleString()}`
                                }}
                              </h5>
                            </td>
                          </tr>
                          <tr v-show="showTotal">
                            <td
                              class="p-0 pt-1"
                              style="border-bottom: 0px"
                              colspan="3"
                            >
                              <h5>Total Amount</h5>
                            </td>
                            <td
                              class="p-0 pt-1"
                              style="border-bottom: 0px"
                              colspan="2"
                            >
                              <h5>
                                {{ `$ ${Number(getTotal).toLocaleString()}` }}
                              </h5>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Modal -->
    <div
      class="modal fade"
      id="inventoryExpenseCost"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Cost Setting Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ title }}
            </h5>
            <button
              type="button"
              id="inventoryExpenseCostClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <small
                role="alert"
                class="alert alert-light-warning mb-0 fade show d-block"
              >
                You can now enter negative quantities to reflect negative
                amounts.
              </small>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="expense_purchase_date">Date : </label>
                  <fieldset
                    class="has-icon-left"
                    style="position: relative"
                    id="expense_purchase_date"
                  >
                    <date-picker
                      v-model="form.purchase_date"
                      :format="'MM/DD/YYYY'"
                      :value-type="'MM/DD/YYYY'"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('purchase_date'),
                      }"
                      @change="delete errors.purchase_date"
                      :title="errors.purchase_date"
                      :clearable="false"
                    ></date-picker>
                  </fieldset>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="costs">Search Expense : </label>
                  <v-select
                    taggable
                    placeholder="Search Expense Name"
                    label="name"
                    :reduce="(cost) => cost.id"
                    v-model="selected_cost_name"
                    clearable
                    :options="cost_setting_listing"
                  ></v-select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="name">Name : </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('name'),
                    }"
                    @keyup="delete errors.name"
                    :title="errors.name"
                    v-model="form.name"
                    id="name"
                    placeholder="Name"
                  />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="invoice">Invoice Reference : </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('invoice'),
                    }"
                    @keyup="delete errors.invoice"
                    :title="errors.invoice"
                    v-model="form.invoice"
                    id="invoice"
                    placeholder="Invoice Ref"
                  />
                </div>

                <div class="form-group col-md-6">
                  <label for="payment_reference">Payment Reference : </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('payment_reference'),
                    }"
                    @keyup="delete errors.payment_reference"
                    :title="errors.payment_reference"
                    v-model="form.payment_reference"
                    id="payment_reference"
                    placeholder="Payment Ref"
                  />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="description">Description : </label>
                  <textarea
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('description'),
                    }"
                    @keyup="delete errors.description"
                    :title="errors.description"
                    v-model="form.description"
                    id="description"
                    placeholder="Description"
                  ></textarea>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="vendors">Select Vendor : </label>
                  <v-select
                    taggable
                    placeholder="Vendor Name"
                    label="company_name"
                    :reduce="(vendor) => vendor.company_name"
                    v-model="form.vendor_name"
                    clearable
                    :options="allVendors"
                  ></v-select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="price">Price</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('price'),
                    }"
                    @keyup="delete errors.price"
                    :title="errors.price"
                    v-model="form.price"
                    placeholder="Price"
                    class="form-control"
                    id="price"
                  />
                </div>
                <div class="form-group col-md-4">
                  <label for="quantity">Quantity</label>
                  <input
                    :class="{
                      'is-invalid': errors.hasOwnProperty('quantity'),
                    }"
                    @keyup="delete errors.quantity"
                    :title="errors.quantity"
                    type="text"
                    v-model="form.quantity"
                    v-integer
                    placeholder="Quantity"
                    class="form-control"
                    id="quantity"
                    inputmode="numeric"
                  />
                </div>
                <div class="form-group col-md-4">
                  <label for="discount">Discount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('discount'),
                    }"
                    @keyup="delete errors.discount"
                    :title="errors.discount"
                    class="form-control"
                    v-model="form.discount"
                    placeholder="Discount"
                    id="discount"
                  />
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="tax_type">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('tax_type'),
                    }"
                    @keyup="delete errors.tax_type"
                    :title="errors.tax_type"
                    v-model="form.tax_type"
                    placeholder="Tax Type"
                    id="tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.id"
                    >
                      {{ tax.name }}
                    </option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <!-- secondary tax : START -->
                <div class="form-group col-md-4">
                  <label for="tax_type_secondary">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('tax_type_secondary'),
                    }"
                    @keyup="delete errors.tax_type_secondary"
                    :title="errors.tax_type_secondary"
                    v-model="form.tax_type_secondary"
                    :disabled="!enableTaxEditing"
                    placeholder="Tax Type"
                    id="tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                    >
                      {{ tax.name }}
                    </option>
                  </select>
                </div>
                <!-- secondary tax : END -->
                <div class="form-group col-md-4">
                  <label for="tax_amount_total">Tax Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('tax_amount_total'),
                    }"
                    @keyup="delete errors.tax_amount_total"
                    :title="errors.tax_amount_total"
                    v-model="form.tax_amount_total"
                    placeholder="Tax Amount"
                    :readonly="enableTaxEditing"
                    class="form-control"
                    id="tax_amount_total"
                  />
                </div>
                <div class="form-group col-md-4">
                  <label for="sub_total">Sub Total</label>
                  <input
                    readonly
                    type="text"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('sub_total'),
                    }"
                    @keyup="delete errors.sub_total"
                    :title="errors.sub_total"
                    class="form-control"
                    v-model="form.sub_total"
                    placeholder="Sub Total"
                    id="sub_total"
                  />
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click.prevent="saveInventoryExpenseData"
            >
              Save
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal END -->
    <!-- // Expenses  form layout section end -->
  </div>
</template>
<script>
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import vSelect from "vue-select";
import Toast from "../../library/Toast";
import { mapGetters, mapActions } from "vuex";
import Doughnut from "../Charts/Doughnut";

export default {
  props: {
    purchase_detail: {
      required: false,
    },
    selling_price: {
      required: false,
    },
  },
  name: "InventoryManageExpense",
  components: {
    "v-select": vSelect,
    DatePicker,
    Doughnut,
  },
  data() {
    return {
      cdata: {
        datasets: [
          {
            data: [10, 20, 30],
            backgroundColor: [
              "rgba(255, 0, 0, 0.1)",
              "rgba(0, 255, 0, 0.1)",
              "rgba(0, 0, 255, 0.1)",
            ],
          },
        ],

        // These labels appear in the legend and in the tooltips when hovering different arcs
        labels: ["Red", "Yellow", "Blue"],
      },
      errors: [],
      title: "Add New Expense",
      action: "create",
      selected_cost_name: "",
      inventory_expense_id: null,
      load: true,
      cost_setting_listing: [],
      inventory_costs: [],
      form: {
        purchase_date: "",
        name: "",
        vendor_name: "",
        description: "",
        invoice: "",
        payment_reference: "",
        price: 0,
        quantity: 1,
        discount: 0,
        tax_type: "",
        tax_percentage: "",
        tax_amount: 0,
        sub_total: 0,
        tax_type_secondary: "",
        tax_percentage_secondary: 0,
        tax_amount_secondary: 0,
        tax_amount_total: 0,
      },
    };
  },
  watch: {
    selected_cost_name: function(newVal, oldVal) {
      if (newVal != oldVal) {
        if (!isNaN(newVal) && newVal != "null") {
          this.getCostSetting(newVal);
        }
      }
    },
    businessTaxSettings: function(newVal, oldVal) {
      if (this.load == true) {
        let selected_tax = this.businessTaxSettings.filter(
          (tax) => tax.default == "1",
        );

        console.log(selected_tax);
        if (selected_tax.length > 0) {
          this.form.tax_type = selected_tax[0].name;
          if (selected_tax.length > 1) {
            this.form.tax_type_secondary = selected_tax[1].name;
          }
        }
      }

      this.load = false;
    },
    calculateSubTotal: function(newVal, oldVal) {
      console.log("hello world");
    },
    "form.tax_type": function(newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal,
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.form.tax_percentage = "";
        } else {
          this.form.tax_percentage = selected_tax[0].value;
        }
      } else {
        this.form.tax_percentage = "";
      }
    },
    "form.tax_type_secondary": function(newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal,
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.form.tax_percentage_secondary = "";
        } else {
          this.form.tax_percentage_secondary = selected_tax[0].value;
        }
      } else {
        this.form.tax_percentage_secondary = "";
      }
    },
  },
  computed: {
    ...mapGetters("configuration", ["businessTaxSettings"]),
    ...mapGetters("vendor", ["allVendors"]),
    getSellingPrice() {
      return !isNaN(this.selling_price) && this.selling_price != ""
        ? parseFloat(this.selling_price)
        : 0;
    },
    getSubTotal() {
      let price = this.inventory_costs.reduce(
        (price, cost) =>
          price + parseFloat(cost.price) * parseInt(cost.quantity, 10),
        0,
      );
      let discount = this.inventory_costs.reduce(
        (discount, cost) => discount + parseFloat(cost.discount),
        0,
      );
      return price - discount;
    },
    getTotalPrice() {
      return this.inventory_costs
        .reduce(
          (price, cost) =>
            price + parseFloat(cost.price) * parseInt(cost.quantity),
          0,
        )
        .toFixed(2);
    },
    getVehiclePurchasePriceAfterDiscount() {
      let vehicle_price = this.purchase_detail.hasOwnProperty("price")
        ? parseFloat(this.purchase_detail.price)
        : 0;
      let discount = this.purchase_detail.hasOwnProperty("discount")
        ? parseFloat(this.purchase_detail.discount)
        : 0;
      return vehicle_price - discount;
    },
    getTotalTax() {
      let total_tax = this.inventory_costs.reduce(
        (tax, cost) => tax + parseFloat(cost.tax_amount_total),
        0,
      );
      let vehicle_tax = 0;
      if (this.purchase_detail.hasOwnProperty("tax_amount")) {
        vehicle_tax =
          !isNaN(this.purchase_detail.tax_amount_total) &&
          this.purchase_detail.tax_amount_total != ""
            ? parseFloat(this.purchase_detail.tax_amount_total)
            : 0;
      }

      total_tax += vehicle_tax;

      return total_tax.toFixed(2);
    },
    getTotalDiscount() {
      let total_discount = this.inventory_costs.reduce(
        (discount, cost) => discount + parseFloat(cost.discount),
        0,
      );
      let vehicle_discount = 0;
      if (this.purchase_detail.hasOwnProperty("discount")) {
        vehicle_discount =
          !isNaN(this.purchase_detail.discount) &&
          this.purchase_detail.discount != ""
            ? parseFloat(this.purchase_detail.discount)
            : 0;
      }

      total_discount += vehicle_discount;

      return total_discount.toFixed(2);
    },
    vehiclePurchasePrice() {
      return this.purchase_detail.hasOwnProperty("price")
        ? parseFloat(this.purchase_detail.price).toFixed(2)
        : 0;
    },
    getTotalBeforTax() {
      let total_price = parseFloat(this.getTotalPrice);
      total_price += parseFloat(this.vehiclePurchasePrice);
      total_price -= parseFloat(this.getTotalDiscount);
      return total_price.toFixed(2);
    },
    getTotal() {
      let total_price = parseFloat(this.getTotalPrice);
      total_price += parseFloat(this.vehiclePurchasePrice);
      total_price += parseFloat(this.getTotalTax);
      total_price -= parseFloat(this.getTotalDiscount);
      return total_price.toFixed(2);
    },
    showTotal() {
      return this.inventory_costs.length > 0 ? true : false;
    },
    calculateSubTotal: {
      get: function() {
        let price =
          !isNaN(this.form.price) && this.form.price != ""
            ? this.form.price
            : 0;
        this.form.price = price;
        price = parseFloat(price);

        let quantity =
          !isNaN(this.form.quantity) && this.form.quantity != ""
            ? parseInt(this.form.quantity)
            : 0;

        let discount =
          !isNaN(this.form.discount) && this.form.discount != ""
            ? this.form.discount
            : 0;
        this.form.discount = discount;
        discount = parseFloat(discount);

        let sub_total = price * quantity;
        sub_total -= discount;

        let tax =
          !isNaN(this.form.tax_percentage) && this.form.tax_percentage != ""
            ? sub_total * parseFloat(this.form.tax_percentage) * 0.01
            : parseFloat(this.form.tax_amount);

        let tax_secondary =
          !isNaN(this.form.tax_percentage_secondary) &&
          this.form.tax_percentage_secondary != ""
            ? sub_total * parseFloat(this.form.tax_percentage_secondary) * 0.01
            : parseFloat(0);

        tax = !isNaN(tax) ? tax : 0;
        this.form.tax_amount = this.$helpers.round(tax, 3);

        tax_secondary = !isNaN(tax_secondary) ? tax_secondary : 0;
        this.form.tax_amount_secondary = this.$helpers.round(tax_secondary, 3);

        tax += tax_secondary;

        if (this.form.tax_type == "Other") {
          this.form.tax_amount = 0;
          this.form.tax_amount_secondary = 0;

          tax =
            !isNaN(this.form.tax_amount_total) &&
            this.form.tax_amount_total != ""
              ? this.form.tax_amount_total
              : 0;

          this.form.tax_amount_total = tax;
        } else {
          this.form.tax_amount_total = this.$helpers.round(tax, 3);
        }

        tax = parseFloat(tax);
        sub_total += tax;

        this.form.sub_total = sub_total;
      },
    },
    enableTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function() {
        let flag =
          !isNaN(this.form.tax_percentage) && this.form.tax_percentage != ""
            ? true
            : false;
        if (!flag && !this.load) {
          this.form.tax_type_secondary = "";
        }
        return flag;
      },
    },
    getProfitOrLoss() {
      //Selling Price - Total Subtotal + Purchase Price Discount
      return (
        this.getSellingPrice -
        (this.getSubTotal + this.getVehiclePurchasePriceAfterDiscount)
      );
    },
    chartData() {
      let profit_loss_label = this.getProfitOrLoss > 0 ? "Profit" : "Loss";
      let color_for_profit_loss =
        this.getProfitOrLoss > 0
          ? "rgba(129, 178, 154, 1)"
          : "rgba(224, 122, 95,1)";
      return {
        datasets: [
          {
            data: [
              this.getVehiclePurchasePriceAfterDiscount,
              this.getSubTotal,
              parseFloat(this.getTotalTax),
              this.getProfitOrLoss,
            ],
            backgroundColor: [
              "rgba(61, 64, 91, 1)",
              "rgba(162, 210, 255, 1)",
              "rgba(242, 204, 143, 1)",
              color_for_profit_loss,
            ],
          },
        ],

        // These labels appear in the legend and in the tooltips when hovering different arcs
        labels: ["Purchase", "Expenses", "Tax", profit_loss_label],
      };
    },
  },
  methods: {
    ...mapActions("configuration", ["getBusinessTaxSettings"]),
    ...mapActions("vendor", ["getAllVendors"]),
    parseAttachmentUrl(url) {
      return this.$helpers.parseAbsoluteOrRelativeUrl(window.global.cdn, url);
    },
    showAttachment(url) {
      try {
        return url.length > 0 ? true : false;
      } catch (error) {
        return false;
      }
    },
    formatDate(date) {
      if (moment(date).isValid()) {
        return moment(date).format("MM/DD/YYYY");
      } else {
        return "";
      }
    },
    resetForm() {
      this.form.name = "";
      this.form.vendor_name = "";
      this.form.invoice = "";
      this.form.payment_reference = "";
      this.form.description = "";
      this.inventory_expense_id = null;
      this.form.purchase_date = moment(new Date()).format("MM/DD/YYYY");
      this.form.price = 0;
      this.form.quantity = 1;
      this.form.discount = 0;
      this.form.tax_type = "";
      this.form.tax_percentage = "";

      this.form.tax_type_secondary = "";
      this.form.tax_amount_secondary = 0;
      this.form.tax_percentage_secondary = 0;
      this.form.tax_amount_secondary = 0;

      this.form.tax_amount = 0;
      this.form.sub_total = 0;
      this.action = "create";
      this.title = "Add New Expense";
      this.errors = [];
      this.selected_cost_name = "";

      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.default == "1",
      );

      console.log(selected_tax);
      if (selected_tax.length > 0) {
        this.form.tax_type = selected_tax[0].name;
        if (selected_tax.length > 1) {
          this.form.tax_type_secondary = selected_tax[1].name;
        }
      }

      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.form.tax_percentage = "";
        } else {
          this.form.tax_percentage = selected_tax[0].value;
        }

        if (selected_tax.length > 1) {
          if (isNaN(selected_tax[1].value)) {
            this.form.tax_percentage_secondary = 0;
          } else {
            this.form.tax_percentage_secondary = selected_tax[1].value;
          }
        } else {
          this.form.tax_percentage_secondary = 0;
        }
      } else {
        this.form.tax_percentage = "";
      }
    },
    async saveInventoryExpenseData() {
      //save or update the purchase data
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
        !isNaN(this.inventory_expense_id) && this.inventory_expense_id != null;

      //success message setting
      let success_message = update_request
        ? `Inventory expense updated.`
        : `Inventory expense added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/inventory/${this.$route.params.id}/costs/update/${this.inventory_expense_id}`
        : `${request_url}/api/business/inventory/${this.$route.params.id}/costs`;

      //message during request
      let request_message = update_request
        ? `Updating inventory expense details ...`
        : `Adding new inventory expense details ...`;

      const toast = new Toast(request_message);
      toast.fire();

      await axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          vm.resetForm();
          document.getElementById("inventoryExpenseCostClose").click();
          toast.success(data.success);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "inventory-update") {
            vm.getInventoryExpensesData(vm.$route.params.id);
          }
        })
        .catch(function(error) {
          let update_request =
            !isNaN(vm.inventory_expense_id) && vm.inventory_expense_id != null;

          let error_message = update_request
            ? "There were errors while updating the inventory purchase details."
            : "There were some errors while adding inventory purchase details.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "inventory",
        "expense",
        "save",
      );
    },
    async getInventoryExpensesData(inventory_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/inventory/" +
          inventory_id +
          "/costs",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.inventory_costs = data;
        })
        .catch(({ response }) => {
          this.inventory_costs = [];
          console.log(response);
        });
    },
    async deleteExpense(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/inventory/${vm.$route.params.id}/costs/delete/`;
      url += id;

      let message = "Deleting expense...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `Inventory expense has been deleted.`;
          toast.success(success_message);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "inventory-update") {
            vm.getInventoryExpensesData(vm.$route.params.id);
          }
          //this.pagination(this.page);
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting inventory expense.`;
          toast.fail(error_message);
          toast.close();
        });

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "inventory",
        "expense",
        "delete",
      );
    },
    editExpense(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/inventory/${vm.$route.params.id}/costs/show/`;
      url += id;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          this.errors = [];
          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }

          this.inventory_expense_id = data.id;
          this.action = "update";
          this.title = "Update Expense";

          let purchase_date = moment(data.purchase_date);

          this.form.purchase_date = purchase_date.isValid()
            ? purchase_date.format("MM/DD/YYYY")
            : moment(new Date()).format("MM/DD/YYYY");

          jQuery("#inventoryExpenseCost").modal("show");
        })
        .catch(({ error }) => {
          let error_message = `Error in fetching inventory expense.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    costSettingListing() {
      let request_url =
        window.location.origin +
        "/api/business/setting/costs?column=name&order=ASC&paginate=false";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.cost_setting_listing = data.cost_setting_listing;
        })
        .catch(() => {
          console.log("Some error in fetching the cost settings");
        });
    },
    getCostSetting(id) {
      const vm = this;
      axios({
        method: "get",
        url: window.location.origin + "/api/business/setting/costs/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.errors = [];
          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the cost setting";
          console.log(error);
        });
    },
    async saveExpenseAttachment(event, id) {
      const vm = this;
      let attachment = event.target.files[0];

      let formData = new FormData();
      formData.append("module", "INVENTORY");
      formData.append("sub_module", "EXPENSE");
      formData.append("entity_id", id);

      formData.append("attachment", attachment, attachment.name);

      let title = "Saving the attachment..";
      const toast = new Toast(title);
      toast.fire();

      await axios({
        method: "post",
        url: `${window.location.origin}/api/business/attachments/create`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          title = "Attachment has been saved.";
          toast.success(title);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "inventory-update") {
            vm.getInventoryExpensesData(vm.$route.params.id);
          }
        })
        .catch(function({ response }) {
          // handle error
          let errors = response.data;

          let error_string = ``;

          for (const error_key in errors) {
            error_string += ` ${errors[error_key]}`;
          }
          toast.fail(error_string);
          toast.close();
        });
    },
    async deleteExpenseAttachment(url) {
      const vm = this;

      let formData = new FormData();
      formData.append("url", url);

      let title = "Deleting the attachment..";
      const toast = new Toast(title);
      toast.fire();

      await axios({
        method: "post",
        url: `${window.location.origin}/api/business/attachments/delete`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          title = "Attachment has been deleted.";
          toast.success(title);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "inventory-update") {
            vm.getInventoryExpensesData(vm.$route.params.id);
          }
        })
        .catch(function({ response }) {
          let errors = response.data;
          let error_string = ``;
          for (const error_key in errors) {
            error_string += ` ${errors[error_key]}`;
          }
          toast.fail(error_string);
          toast.close();
        });
    },
    async deleteAttachmentConfirmation(url) {
      const vm = this;
      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Confirm",
        toast: false,
        position: "center",
        text: "Please confirm if you want to delete the attachment.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          //delete the disclosures
          (async () => {
            await vm.deleteExpenseAttachment(url);
          })();
        }
      });
    },
  },
  created() {
    this.form.purchase_date = moment(new Date()).format("MM/DD/YYYY");
    this.getBusinessTaxSettings();
    this.getAllVendors();
    this.costSettingListing();
    if (this.$route.params.id && this.$route.name == "inventory-update") {
      this.getInventoryExpensesData(this.$route.params.id);
    }
  },
  mounted() {
    const vm = this;
    jQuery("#inventoryExpenseCost").on("hidden.bs.modal", function() {
      vm.resetForm();
    });
  },
};
</script>
<style lang="scss">
#expense_purchase_date .vdpComponent {
  width: 100%;
}
#expense_purchase_date .vdpComponent input {
  display: block;
  width: 100%;
  height: calc(1.4em + 0.94rem + 3.7px);
  padding: 0.47rem 0.8rem;
  padding-right: 0.8rem;
  padding-left: 2.2em !important;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.4;
  color: #475f7b;
  background-color: #ffffff;
  background-clip: padding-box;
  border: 1px solid #dfe3e7;
  border-radius: 0.267rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.small {
  width: 300px;
}
</style>
