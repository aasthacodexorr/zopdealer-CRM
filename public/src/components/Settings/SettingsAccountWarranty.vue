<template>
  <div
    role="tabpanel"
    class="tab-pane"
    id="setting-account-warranty"
    aria-labelledby="Warranty Setting"
    aria-expanded="true"
  >
    <div class="content-wrapper m-0 border-bottom">
      <div class="row">
        <settings-cost-variation-matrix
          :title="cost_variation_matrix_title"
          :entity="`WARRANTY_SETTING`"
          :entity_id="entity_id"
        ></settings-cost-variation-matrix>
        <div class="col-md-5 col-12">
          <ul class="pl-0">
            <li class="d-inline-block mr-1">
              <div class="content-list-box">
                <p class="p-0 m-0">
                  <span class="blue-color text-bold-400 title-sizeincrease-font"
                    >Warranty Settings</span
                  >
                </p>
              </div>
            </li>
          </ul>
        </div>
        <div class="col-md-7 col-12">
          <ul class="p-0 float-right d-flex">
            <li class="d-inline-block"></li>
            <li class="d-inline-block">
              <!-- Button trigger modal -->
              <button
                type="button"
                class="btn btn-outline-primary"
                data-toggle="modal"
                data-target="#warrantySettingModal"
              >
                + Add Warranty
              </button>

              <!-- Model Warranty : START -->
              <div
                class="modal fade"
                id="warrantySettingModal"
                tabindex="-1"
                role="dialog"
                aria-labelledby="Warranty Modal"
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
                        id="warrantySettingModalClose"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                      >
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form>
                        <!-- vselect : START -->
                        <!-- vselect : END -->
                        <!-- company_name : START -->
                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="deal_warranties_company_name"
                              >Company Name :
                            </label>
                            <input
                              type="text"
                              class="form-control"
                              :class="{
                                'is-invalid': errors.hasOwnProperty(
                                  'company_name'
                                ),
                              }"
                              @keyup="delete errors.company_name"
                              :title="errors.company_name"
                              v-model="form.company_name"
                              id="deal_warranties_company_name"
                              placeholder="Company Name"
                            />
                          </div>
                        </div>
                        <!-- company_name : END -->
                        <!-- description : START -->
                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="deal_warranties_description"
                              >Description :
                            </label>
                            <textarea
                              type="text"
                              class="form-control"
                              :class="{
                                'is-invalid': errors.hasOwnProperty(
                                  'description'
                                ),
                              }"
                              @keyup="delete errors.description"
                              :title="errors.description"
                              v-model="form.description"
                              id="deal_warranties_description"
                              placeholder="Description"
                            ></textarea>
                          </div>
                        </div>
                        <!-- description : END -->
                        <!-- duration : START -->
                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="deal_warranties_duration"
                              >Duration (Months):
                            </label>
                            <input
                              type="text"
                              class="form-control"
                              :class="{
                                'is-invalid': errors.hasOwnProperty('duration'),
                              }"
                              @keyup="delete errors.duration"
                              :title="errors.duration"
                              v-model="form.duration"
                              id="deal_warranties_duration"
                              placeholder="Duration"
                            />
                          </div>
                        </div>
                        <!-- duration : END -->
                        <!-- distance : START -->
                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="deal_warranties_distance"
                              >Distance :
                            </label>
                            <input
                              type="text"
                              class="form-control"
                              :class="{
                                'is-invalid': errors.hasOwnProperty('distance'),
                              }"
                              @keyup="delete errors.distance"
                              :title="errors.distance"
                              v-model="form.distance"
                              id="deal_warranties_distance"
                              placeholder="Distance"
                            />
                          </div>
                        </div>
                        <!-- distance : END -->
                        <!-- combination : START -->
                        <div class="form-row">
                          <!-- amount : START -->
                          <div class="form-group col-md-4">
                            <label for="deal_warranties_amount">Amount</label>
                            <input
                              type="text"
                              :class="{
                                'is-invalid': errors.hasOwnProperty('amount'),
                              }"
                              @keyup="delete errors.amount"
                              :title="errors.amount"
                              v-model="form.amount"
                              placeholder="Amount"
                              class="form-control"
                              id="deal_warranties_amount"
                            />
                          </div>
                          <!-- amount : END -->

                          <!-- tax_type : START -->
                          <div class="form-group col-md-4">
                            <label for="deal_warranty_tax_type">Tax Type</label>
                            <select
                              class="custom-select form-control mr-1"
                              :class="{
                                'is-invalid': errors.hasOwnProperty('tax_type'),
                              }"
                              @keyup="delete errors.tax_type"
                              :title="errors.tax_type"
                              v-model="form.tax_type"
                              placeholder="Tax Type"
                              id="deal_warranty_tax_type"
                            >
                              <option value="" selected disabled>
                                Tax Type
                              </option>
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
                          <!-- tax_type : END -->
                          <!-- secondary_tax_type : START -->
                          <div class="form-group col-md-4">
                            <label for="deal_warranty_type_secondary"
                              >Tax Type</label
                            >
                            <select
                              class="custom-select form-control mr-1"
                              :class="{
                                'is-invalid': errors.hasOwnProperty(
                                  'tax_type_secondary'
                                ),
                              }"
                              @keyup="delete errors.tax_type_secondary"
                              :title="errors.tax_type_secondary"
                              v-model="form.tax_type_secondary"
                              :disabled="!enableTaxEditing"
                              placeholder="Tax Type"
                              id="deal_warranty_type_secondary"
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
                          <!-- secondary_tax_type : END -->
                          <!-- tax_amount : START -->
                          <div class="form-group col-md-4">
                            <label for="deal_warranties_tax_amount"
                              >Tax Amount</label
                            >
                            <input
                              type="text"
                              :class="{
                                'is-invalid': errors.hasOwnProperty(
                                  'tax_amount_total'
                                ),
                              }"
                              @keyup="delete errors.tax_amount_total"
                              :title="errors.tax_amount_total"
                              v-model="form.tax_amount_total"
                              placeholder="Tax Amount"
                              :readonly="enableTaxEditing"
                              class="form-control"
                              id="deal_warranties_tax_amount"
                            />
                          </div>
                          <!-- tax_amount : END -->
                        </div>
                        <!-- combindation : END -->
                        <hr />
                        <!-- COST : START -->
                        <div class="form-row">
                          <!-- cost : START -->
                          <div class="form-group col-md-4">
                            <label for="fees_cost">Cost</label>
                            <input
                              type="text"
                              :class="{
                                'is-invalid': errors.hasOwnProperty('cost'),
                              }"
                              @keyup="delete errors.cost"
                              :title="errors.cost"
                              v-model="form.cost"
                              placeholder="Cost"
                              class="form-control"
                              id="fees_cost"
                            />
                          </div>
                          <!-- cost : END -->

                          <!-- cost_tax_type : START -->
                          <div class="form-group col-md-4">
                            <label for="fee_cost_tax_type">Tax Type</label>
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
                              id="fee_cost_tax_type"
                            >
                              <option value="" selected disabled>
                                Tax Type
                              </option>
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
                          <!-- cost_tax_type : END -->

                          <!-- cost_tax_type_secondary : START -->
                          <div class="form-group col-md-4">
                            <label for="fee_cost_tax_type_secondary"
                              >Tax Type</label
                            >
                            <select
                              class="custom-select form-control mr-1"
                              :class="{
                                'is-invalid': errors.hasOwnProperty(
                                  'cost_tax_type_secondary'
                                ),
                              }"
                              @keyup="delete errors.cost_tax_type_secondary"
                              :title="errors.cost_tax_type_secondary"
                              v-model="form.cost_tax_type_secondary"
                              :disabled="!enableCostTaxEditing"
                              placeholder="Tax Type"
                              id="fee_cost_tax_type_secondary"
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
                          <!-- cost_tax_type_secondary : END -->

                          <!-- cost_tax_amount_total : START -->
                          <div class="form-group col-md-4">
                            <label for="fees_cost_tax_amount">Tax Amount</label>
                            <input
                              type="text"
                              :class="{
                                'is-invalid': errors.hasOwnProperty(
                                  'cost_tax_amount_total'
                                ),
                              }"
                              @keyup="delete errors.cost_tax_amount_total"
                              :title="errors.cost_tax_amount_total"
                              v-model="form.cost_tax_amount_total"
                              placeholder="Tax Amount"
                              :readonly="enableCostTaxEditing"
                              class="form-control"
                              id="fees_cost_tax_amount"
                            />
                          </div>
                          <!-- cost_tax_amount_total : END -->
                        </div>
                        <!-- COST : END -->
                        <!-- Digital Quote : START -->

                        <hr />

                        <div class="form-row">
                          <h6 class="form-group col-md-12 font-weight-bold">
                            For Digital Quote :
                          </h6>

                          <!-- is_mandatory : START -->

                          <div class="col-md-6 form-group">
                            <label for="warranty_is_mandatory"
                              >Is Mandatory</label
                            >

                            <div class="custom-control custom-switch">
                              <input
                                type="checkbox"
                                class="custom-control-input"
                                id="warranty_is_mandatory"
                                v-model="form.is_mandatory"
                                name="Is Mandatory"
                                :true-value="'1'"
                                :false-value="'0'"
                                checked
                              />

                              <label
                                class="custom-control-label"
                                for="warranty_is_mandatory"
                              >
                                <span class="switch-icon-left"> ON</span>

                                <span class="switch-icon-right">OFF</span>
                              </label>
                            </div>
                          </div>

                          <!-- is_mandatory : END -->

                          <!-- is_variable : START -->

                          <div class="col-md-6 form-group">
                            <label for="warranty_is_variable"
                              >Is Variable</label
                            >

                            <div class="custom-control custom-switch">
                              <input
                                type="checkbox"
                                class="custom-control-input"
                                v-model="form.is_variable"
                                id="warranty_is_variable"
                                name="Is Variable"
                                :true-value="'1'"
                                :false-value="'0'"
                                checked
                              />

                              <label
                                class="custom-control-label"
                                for="warranty_is_variable"
                              >
                                <span class="switch-icon-left"> ON</span>

                                <span class="switch-icon-right">OFF</span>
                              </label>
                            </div>
                          </div>

                          <!-- is_variable : END -->

                          <!-- only_finance : START -->

                          <div class="col-md-6 form-group">
                            <label for="warranty_only_finance"
                              >Only For Finance</label
                            >

                            <div class="custom-control custom-switch">
                              <input
                                type="checkbox"
                                class="custom-control-input"
                                id="warranty_only_finance"
                                v-model="form.only_finance"
                                name="Only For Finance"
                                :true-value="'1'"
                                :false-value="'0'"
                                checked
                              />

                              <label
                                class="custom-control-label"
                                for="warranty_only_finance"
                              >
                                <span class="switch-icon-left"> ON</span>

                                <span class="switch-icon-right">OFF</span>
                              </label>
                            </div>
                          </div>

                          <!-- only_finance : END -->
                        </div>

                        <!-- Digital Quote : END -->
                      </form>
                    </div>
                    <!-- modal footer : START -->
                    <div class="modal-footer">
                      <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                        @click="resetForm"
                      >
                        Close
                      </button>
                      <button
                        type="button"
                        class="btn btn-primary"
                        @click.prevent="saveWarrantySetting"
                      >
                        Save
                      </button>
                    </div>
                    <!-- modal footer : END -->
                  </div>
                </div>
              </div>
              <!-- Model Warranty : END -->
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="list-right-task-locations list-group ps p-0">
      <div class="content-wrapper mt-0 p-0">
        <div class="">
          <div class="">
            <table class="list-page-content-p mt-0 mb-0 w-100">
              <thead>
                <tr class="bg-grey">
                  <th class="text-center">Company Name</th>
                  <th class="text-center">Amount</th>
                  <th class="text-center">Tax Total</th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="cost_setting in cost_setting_listing"
                  :key="`cost_setting` + cost_setting.id"
                >
                  <td class="text-center">
                    <span class="blue-color text-bold-600 title-size-font">
                      {{ cost_setting.company_name }}</span
                    >
                  </td>
                  <td class="text-center">{{ cost_setting.amount }}</td>
                  <td class="text-center">
                    {{ cost_setting.tax_amount_total }}
                  </td>
                  <td class="text-center">
                    <div class="dropdown">
                      <span
                        class="
                          bx bx-dots-vertical-rounded
                          font-medium-3
                          dropdown-toggle
                          nav-hide-arrow
                          cursor-pointer
                        "
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                        role="menu"
                      ></span>
                      <div
                        class="dropdown-menu dropdown-menu-right"
                        x-placement="bottom-end"
                        style="
                          position: absolute;
                          will-change: transform;
                          top: 0px;
                          left: 0px;
                          transform: translate3d(19px, 19px, 0px);
                        "
                      >
                        <a
                          class="dropdown-item"
                          style="cursor: pointer"
                          @click.prevent="getWarrantySetting(cost_setting.id)"
                          >Edit</a
                        >
                        <a
                          class="dropdown-item"
                          style="cursor: pointer"
                          @click.prevent="
                            deleteWarrantySetting(cost_setting.id)
                          "
                          >Delete</a
                        >
                        <a
                          class="dropdown-item"
                          v-show="cost_setting.is_variable == '1'"
                          @click.prevent="
                            triggerCostMatrixModal(
                              cost_setting.company_name,
                              cost_setting.id
                            )
                          "
                          style="cursor: pointer"
                        >
                          e-Quote Variation
                        </a>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="col"></div>
          <div class="col"></div>
          <div class="col p-1">
            <div class="dataTables_paginate paging_simple_numbers text-right">
              <ul class="pagination">
                <li
                  style="cursor: pointer; padding-right: 0.2em"
                  class="paginate_button page-item previous"
                  @click="paginationWithUrl(pager.previous)"
                  :class="{ disabled: pager.previous == null }"
                >
                  <a
                    aria-controls="table-extended-transactions"
                    data-dt-idx="0"
                    tabindex="0"
                    class="page-link"
                    >&lt;</a
                  >
                </li>
                <li
                  style="cursor: pointer; padding-right: 0.2em"
                  class="paginate_button page-item"
                  :class="{ active: n === page }"
                  v-for="n in pager.pageCount"
                  v-show="n >= page - 1 && n <= page + 1"
                  :key="'nav_id' + n"
                >
                  <a
                    aria-controls="table-extended-transactions"
                    @click="pagination(n)"
                    :data-dt-idx="n"
                    tabindex="0"
                    class="page-link"
                    >{{ n }}</a
                  >
                </li>
                <li
                  style="cursor: pointer; padding-right: 0.2em"
                  class="paginate_button page-item next"
                  :class="{ disabled: pager.next == null }"
                >
                  <a
                    aria-controls="table-extended-transactions"
                    @click="paginationWithUrl(pager.next)"
                    data-dt-idx="3"
                    tabindex="0"
                    class="page-link"
                    >&gt;</a
                  >
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import Toast from "../../library/Toast";
import vSelect from "vue-select";
import SettingsCostVariationMatrix from "./SettingsCostVariationMatrix";

export default {
  name: "SettingsAccountWarranty",
  data() {
    return {
      errors: [],
      cost_variation_matrix_title: "",
      entity_id: null,
      cost_setting_listing: [],
      title: "Add New Warranty",
      pager: {},
      page: 1,
      warranty_setting_id: null,
      action: "create",
      form: {
        company_name: "",
        duration: 0,
        distance: "",
        amount: 0,
        description: "",
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

        is_mandatory: "0",
        is_variable: "0",
        only_finance: "0",
      },
    };
  },
  watch: {
    "form.duration": function(newVal, oldVal) {
      let duration =
        !isNaN(this.form.duration) && this.form.duration != ""
          ? parseFloat(this.form.duration)
          : 0;
      this.form.duration = duration;
    },
    businessTaxSettings: function(newVal, oldVal) {
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.default == "1"
      );

      console.log(selected_tax);
      if (selected_tax.length > 0) {
        this.form.tax_type = selected_tax[0].name;
        this.form.cost_tax_type = selected_tax[0].name;

        if (selected_tax.length > 1) {
          this.form.tax_type_secondary = selected_tax[1].name;
          this.form.cost_tax_type_secondary = selected_tax[1].name;
        }
      }
    },
    "form.tax_type": function(newVal, oldVal) {
      this.form.tax_percentage = this.$helpers.setTaxRates(
        this.businessTaxSettings,
        newVal
      );
    },
    "form.tax_type_secondary": function(newVal, oldVal) {
      this.form.tax_percentage_secondary = this.$helpers.setTaxRates(
        this.businessTaxSettings,
        newVal
      );
    },
    "form.cost_tax_type": function(newVal, oldVal) {
      this.form.cost_tax_percentage = this.$helpers.setTaxRates(
        this.businessTaxSettings,
        newVal
      );
    },
    "form.cost_tax_type_secondary": function(newVal, oldVal) {
      this.form.cost_tax_percentage_secondary = this.$helpers.setTaxRates(
        this.businessTaxSettings,
        newVal
      );
    },
    calculateSubTotal: function(newVal, oldVal) {
      console.log("hello world");
    },
  },
  components: {
    "v-select": vSelect,
    SettingsCostVariationMatrix,
  },
  computed: {
    ...mapGetters("configuration", ["businessTaxSettings"]),
    calculateSubTotal: {
      get: function() {
        let amount =
          !isNaN(this.form.amount) && this.form.amount != ""
            ? this.form.amount
            : 0;
        this.form.amount = amount;

        amount = parseFloat(amount);

        let sub_total = amount;

        let tax =
          !isNaN(this.form.tax_percentage) && this.form.tax_percentage != ""
            ? sub_total * parseFloat(this.form.tax_percentage) * 0.01
            : parseFloat(this.form.tax_amount);

        tax = !isNaN(tax) ? tax : 0;
        this.form.tax_amount = this.$helpers.round(tax, 3);

        let tax_secondary =
          !isNaN(this.form.tax_percentage_secondary) &&
          this.form.tax_percentage_secondary != ""
            ? sub_total * parseFloat(this.form.tax_percentage_secondary) * 0.01
            : parseFloat(0);

        tax_secondary = !isNaN(tax_secondary) ? tax_secondary : 0;
        this.form.tax_amount_secondary = this.$helpers.round(tax_secondary, 3);

        tax += tax_secondary;

        if (this.form.tax_type == "Other") {
          this.form.tax_amount = 0;
          this.form.tax_amount_secondary = 0;
          tax = !isNaN(this.form.tax_amount_total)
            ? parseFloat(this.form.tax_amount_total)
            : 0;
        }

        this.form.tax_amount_total = this.$helpers.round(tax, 3);

        let cost =
          !isNaN(this.form.cost) && this.form.cost != "" ? this.form.cost : 0;
        this.form.cost = cost;

        cost = parseFloat(cost);

        let cost_tax =
          !isNaN(this.form.cost_tax_percentage) &&
          this.form.cost_tax_percentage != ""
            ? cost * parseFloat(this.form.cost_tax_percentage) * 0.01
            : parseFloat(this.form.cost_tax_amount);

        cost_tax = !isNaN(cost_tax) ? cost_tax : 0;
        this.form.cost_tax_amount = this.$helpers.round(cost_tax, 3);

        let cost_tax_secondary =
          !isNaN(this.form.cost_tax_percentage_secondary) &&
          this.form.cost_tax_percentage_secondary != ""
            ? cost * parseFloat(this.form.cost_tax_percentage_secondary) * 0.01
            : parseFloat(0);

        cost_tax_secondary = !isNaN(cost_tax_secondary)
          ? cost_tax_secondary
          : 0;
        this.form.cost_tax_amount_secondary = this.$helpers.round(
          cost_tax_secondary,
          3
        );

        cost_tax += cost_tax_secondary;

        if (this.form.cost_tax_type == "Other") {
          this.form.cost_tax_amount = 0;
          this.form.cost_tax_amount_secondary = 0;
          cost_tax = !isNaN(this.form.cost_tax_amount_total)
            ? parseFloat(this.form.cost_tax_amount_total)
            : 0;
        }

        this.form.cost_tax_amount_total = this.$helpers.round(cost_tax, 3);
      },
    },
    enableTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function() {
        let flag =
          !isNaN(this.form.tax_percentage) && this.form.tax_percentage != ""
            ? true
            : false;
        if (!flag) {
          this.form.tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableCostTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function() {
        let flag =
          !isNaN(this.form.cost_tax_percentage) &&
          this.form.cost_tax_percentage != ""
            ? true
            : false;
        if (!flag) {
          this.form.cost_tax_type_secondary = "";
        }
        return flag;
      },
    },
  },
  methods: {
    ...mapActions("configuration", ["getBusinessTaxSettings"]),
    resetForm() {
      this.form.is_mandatory = this.form.is_variable = this.form.only_finance =
        "0";
      this.form.company_name = "";
      this.form.amount = 0;
      this.form.description = "";
      this.form.tax_type = "";
      this.form.tax_percentage = 0;
      this.form.tax_amount = 0;
      this.form.duration = 0;
      this.form.distance = "";

      this.title = "Add New Warranty";

      this.form.tax_type_secondary = "";
      this.form.tax_amount_secondary = 0;
      this.form.tax_percentage_secondary = 0;
      this.form.tax_amount_secondary = 0;

      this.form.tax_amount_total = 0;

      this.form.cost = 0;

      this.form.cost_tax_type = "";
      this.form.cost_tax_percentage = 0;
      this.form.cost_tax_amount = 0;

      this.form.cost_tax_type_secondary = "";
      this.form.cost_tax_amount_secondary = 0;
      this.form.cost_tax_percentage_secondary = 0;

      this.form.cost_tax_amount_total = 0;

      this.action = "create";
      this.warranty_setting_id = null;
      this.errors = [];

      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.default == "1"
      );

      if (selected_tax.length > 0) {
        this.form.tax_type = this.form.cost_tax_type = selected_tax[0].name;
        this.form.tax_percentage = this.form.cost_tax_percentage = this.$helpers.setTaxRates(
          this.businessTaxSettings,
          selected_tax[0].name
        );

        if (selected_tax.length > 1) {
          this.form.tax_type_secondary = this.form.cost_tax_type_secondary =
            selected_tax[1].name;
          this.form.tax_percentage_secondary = this.form.cost_tax_percentage_secondary = this.$helpers.setTaxRates(
            this.businessTaxSettings,
            selected_tax[1].name
          );
        }
      }
    },
    warrantySettingListing() {
      let request_url =
        window.location.origin + "/api/business/setting/warranty";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.cost_setting_listing = data.cost_setting_listing;
          this.pager = data.pager;
          this.pager.next =
            this.pager.next == null
              ? null
              : "/api" + this.pager.next.split("api")[1];
        })
        .catch(() => {
          console.log("Some error in fetching the cost settings");
        });
    },
    pagination(n) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/setting/warranty?page=`;
      url += n;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.cost_setting_listing = response.data.cost_setting_listing;
        this.pager = response.data.pager;
        this.page = this.pager.currentPage;
        this.pager.next =
          this.pager.next == null
            ? null
            : "/api" + this.pager.next.split("api")[1];
        this.pager.previous =
          this.pager.previous == null
            ? null
            : "/api" + this.pager.previous.split("api")[1];
      });
    },
    paginationWithUrl(url) {
      const vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.cost_setting_listing = response.data.cost_setting_listing;
        this.pager = response.data.pager;
        this.page = this.pager.currentPage;
        this.pager.next =
          this.pager.next == null
            ? null
            : "/api" + this.pager.next.split("api")[1];
        this.pager.previous =
          this.pager.previous == null
            ? null
            : "/api" + this.pager.previous.split("api")[1];
      });
    },
    saveWarrantySetting() {
      const vm = this;
      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key)) {
          formData.append(key, this.form[key]);
        }
      }

      let update_request = this.action == "update" ? true : false;

      let request_url = window.location.origin;
      request_url = update_request
        ? `${request_url}/api/business/setting/warranty/update/${this.warranty_setting_id}`
        : `${request_url}/api/business/setting/warranty/create`;

      let message = update_request
        ? `Updating warranty ...`
        : `Adding new warranty ...`;

      const toast = new Toast(message);
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
          vm.resetForm();

          document.getElementById("warrantySettingModalClose").click();

          toast.success(data.success);
          toast.close();

          if (update_request && vm.pager.hasOwnProperty("currentPage")) {
            vm.pagination(vm.pager.currentPage);
          } else {
            vm.pagination(1);
          }
        })
        .catch((error) => {
          vm.$data.errors = error.response.data;

          let error_message = `There were some errors while submitting form.`;
          toast.fail(error_message);
          toast.close();
        });

      // document.getElementById("locationModalClose").click();
    },
    deleteWarrantySetting(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/setting/warranty/delete/`;
      url += id;

      let message = "Deleting warranty setting...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `Warranty setting has been deleted.`;
          toast.success(success_message);
          toast.close();

          if (this.page == 1) {
            this.pagination(this.page);
          } else if (this.page > 1) {
            if (this.cost_setting_listing.length == 1) {
              this.pagination(this.page - 1);
            } else {
              this.pagination(this.page);
            }
          }
          //this.pagination(this.page);
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting warranty setting.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    getWarrantySetting(id) {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin + "/api/business/setting/warranty/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;

          this.action = "update";
          this.title = "Update Warranty";
          this.warranty_setting_id = data.id;
          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }

          jQuery("#warrantySettingModal").modal("show");
        })
        .catch(({ response }) => {
          let error =
            "There were some errors in retrieving the warranty setting";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
          vm.resetForm();
        });
    },
    async triggerCostMatrixModal(title, id) {
      this.cost_variation_matrix_title = title;
      this.entity_id = id;
      let entity = "WARRANTY_SETTING";
      await this.$helpers.sleep(1000);
      $(`#costVariationMatrixModal${entity}${this.entity_id}`).modal("show");
    },
  },
  mounted() {
    this.getBusinessTaxSettings();
    this.warrantySettingListing();
    const vm = this;
    jQuery("#warrantySettingModal").on("hidden.bs.modal", function() {
      vm.resetForm();
    });
  },
};
</script>
<style lang="scss">
#warrantySettingModal .vs__dropdown-menu {
  z-index: 9999;
  max-height: 250px;
  overflow-y: auto;
}
#warrantySettingModal input:read-only {
  background: #eee !important;
}
</style>
