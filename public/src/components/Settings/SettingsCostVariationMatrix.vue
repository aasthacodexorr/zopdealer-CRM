<template>
  <!-- Model : START -->
  <div
    class="modal fade"
    :id="`costVariationMatrixModal${entity}${entity_id}`"
    tabindex="-1"
    role="dialog"
    aria-labelledby="Cost Variation Matrix Modal"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <!-- Modal header : START -->
        <div class="modal-header">
          <h5 class="modal-title">Cost Variation Matrix for {{ title }}</h5>
          <button
            type="button"
            :id="`costVariationMatrixModal${entity}${entity_id}Close`"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <!-- Modal header : END -->
        <div class="modal-body" v-show="!show_form">
          <button
            class="btn btn-icon btn-primary"
            type="button"
            @click="show_form = true"
          >
            Add Cost Variation
          </button>
          <hr />
          <table class="table">
            <thead>
              <tr>
                <th scope="col">Condition</th>
                <th scope="col">Amount</th>
                <th scope="col">Tax Total</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="cost_variation in cost_variation_matrix_listing"
                :key="`cost_variation_${cost_variation.id}`"
              >
                <td>{{ formatConditions(cost_variation.conditions) }}</td>
                <td>{{ cost_variation.amount }}</td>
                <td>{{ cost_variation.tax_amount_total }}</td>
                <td>
                  <button
                    @click.prevent="deleteCostVariation(cost_variation.id)"
                    class="btn btn-danger text-uppercase"
                    target="_blank"
                    rel="noopener noreferrer"
                    style="
                      padding-right: 0.8em;
                      padding-left: 0.8em;
                      cursor: pointer;
                    "
                  >
                    <i class="bx bx-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="modal-body" v-show="show_form">
          <!-- combination : START -->
          <button
            class="btn btn-icon btn-primary"
            type="button"
            @click="show_form = false"
          >
            Show All
          </button>
          <hr />
          <div class="form-row">
            <!-- amount : START -->
            <div class="form-group col-md-4">
              <label for="fees_amount">Amount</label>
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
                id="fees_amount"
              />
            </div>
            <!-- amount : END -->

            <!-- tax_type : START -->
            <div class="form-group col-md-4">
              <label for="fee_tax_type">Tax Type</label>
              <select
                class="custom-select form-control mr-1"
                :class="{
                  'is-invalid': errors.hasOwnProperty('tax_type'),
                }"
                @keyup="delete errors.tax_type"
                :title="errors.tax_type"
                v-model="form.tax_type"
                placeholder="Tax Type"
                id="fee_tax_type"
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
            <!-- tax_type : END -->

            <!-- secondary_tax_type : START -->
            <div class="form-group col-md-4">
              <label for="fee_tax_type_secondary">Tax Type</label>
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
                id="fee_tax_type_secondary"
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
              <label for="fees_tax_amount">Tax Amount</label>
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
                id="fees_tax_amount"
              />
            </div>
            <!-- tax_amount : END -->
          </div>
          <!-- combination : END -->
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
                  'is-invalid': errors.hasOwnProperty('cost_tax_type'),
                }"
                @keyup="delete errors.cost_tax_type"
                :title="errors.cost_tax_type"
                v-model="form.cost_tax_type"
                placeholder="Tax Type"
                id="fee_cost_tax_type"
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
            <!-- cost_tax_type : END -->

            <!-- cost_tax_type_secondary : START -->
            <div class="form-group col-md-4">
              <label for="fee_cost_tax_type_secondary">Tax Type</label>
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
                  'is-invalid': errors.hasOwnProperty('cost_tax_amount_total'),
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
          <hr />
          <!-- NEW ENTITY : START -->
          <div class="row">
            <!-- Each Row of Query : OPEN-->
            <div
              class="col-12 col-md-12"
              data-repeater-list="contact"
              v-for="n in getFinanceTermsCount"
              :key="n"
            >
              <div class="row" data-repeater-item>
                <div
                  v-if="n > 1"
                  class="col-md-2 col-12 form-group d-flex align-items-center pr-0"
                >
                  <v-select
                    placeholder="Join"
                    :options="['AND', 'OR']"
                    v-model="form.conditions[n - 1].join"
                    style="width: 100%"
                  />
                </div>

                <div
                  class="col-md-4 col-12 form-group d-flex align-items-center pr-0"
                >
                  <v-select
                    placeholder="Attribute"
                    :options="map_columns"
                    @input="changeQueryDetails(n - 1)"
                    v-model="form.conditions[n - 1].header"
                    style="width: 100%"
                    :reduce="(map_columns) => map_columns.header"
                    label="header"
                  />
                </div>
                <div class="col-md-4 col-12 form-group">
                  <v-select
                    placeholder="Comparator"
                    :options="map_comparator[n - 1]"
                    v-model="form.conditions[n - 1].comparator"
                    style="width: 100%"
                  />
                </div>
              </div>

              <div class="row" data-repeater-item>
                <div
                  class="col-md-4 col-12 form-group pl-0 pr-0 multi-select-component"
                  v-if="
                    form.conditions[n - 1].type == 'Multi Select' &&
                    form.conditions[n - 1].comparator != ''
                  "
                >
                  <v-select
                    multiple
                    placeholder="Multi Select"
                    :options="map_value_1[n - 1]"
                    v-model="form.conditions[n - 1].value_1"
                    style="width: 100%"
                  />
                </div>
                <div
                  class="col-md-4 col-12 form-group pl-0 pr-0"
                  v-else-if="
                    form.conditions[n - 1].type == 'Date Comparision' &&
                    form.conditions[n - 1].comparator != ''
                  "
                >
                  <date-picker
                    v-model="form.conditions[n - 1].value_1"
                    :format="'MM/DD/YYYY'"
                    :value-type="'MM/DD/YYYY'"
                  ></date-picker>
                </div>
                <div
                  class="col-md-4 col-12 form-group pl-0 pr-0"
                  v-else-if="
                    form.conditions[n - 1].type == 'Single Select' &&
                    form.conditions[n - 1].comparator != ''
                  "
                >
                  <v-select
                    placeholder="Single Select"
                    :options="map_value_1[n - 1]"
                    :reduce="(each) => each.value"
                    v-model="form.conditions[n - 1].value_1"
                    style="width: 100%"
                  />
                </div>
                <div
                  class="col-md-11 col-12 form-group pl-0 pr-0"
                  v-else-if="
                    (form.conditions[n - 1].type == 'Numeric Comparision' ||
                      form.conditions[n - 1].type ==
                        'Relative Time Period Comparision') &&
                    form.conditions[n - 1].comparator != ''
                  "
                >
                  <input
                    type="number"
                    style="width: 33.33%; display: inline-block"
                    min="0"
                    class="form-control mr-1"
                    name=""
                    v-model="form.conditions[n - 1].value_1"
                    placeholder="Value 1"
                    required
                  />
                  <input
                    type="number"
                    style="width: 33.33%; display: inline-block"
                    min="0"
                    class="form-control mr-1"
                    v-show="form.conditions[n - 1].comparator == 'between'"
                    name=""
                    v-model="form.conditions[n - 1].value_2"
                    placeholder="Value 2"
                    required
                  />
                </div>
                <div class="col-md-4 col-12 form-group pl-0 pr-0" v-else></div>

                <div
                  class="col-md-7 col-12 form-group"
                  v-if="
                    !(
                      (form.conditions[n - 1].type == 'Numeric Comparision' ||
                        form.conditions[n - 1].type ==
                          'Relative Time Period Comparision') &&
                      form.conditions[n - 1].comparator != ''
                    )
                  "
                ></div>
                <div class="col-md-1 col-12 form-group">
                  <button
                    class="btn btn-icon btn-danger"
                    type="button"
                    data-repeater-delete
                    @click="
                      form.conditions.splice(n - 1, 1);
                      getFinanceTermsCount -= 1;
                    "
                  >
                    <i class="bx bx-x"></i>
                  </button>
                </div>
              </div>
            </div>
            <!-- Each Row of Query : CLOSE -->
            <div class="col-12 col-md-12">
              <button
                class="btn btn-icon btn-primary"
                type="button"
                @click="addNewRow"
                data-repeater-create
              >
                <i class="bx bx-plus"></i> Add criteria
              </button>
            </div>
          </div>
          <!-- NEW ENTITY : END -->
        </div>
        <!-- Modal footer : START -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Close
          </button>
          <button
            v-show="show_form"
            @click.prevent="save"
            type="button"
            class="btn btn-primary"
          >
            Save
          </button>
        </div>
        <!-- Modal footer : END -->
      </div>
    </div>
  </div>
  <!-- Model : END -->
</template>
<script>
import draggable from "vuedraggable";
import vSelect from "vue-select";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import { mapGetters, mapActions } from "vuex";
import Toast from "../../library/Toast";
export default {
  props: {
    title: {
      required: false,
      default: "",
    },
    entity: {
      required: true,
    },
    entity_id: {
      required: true,
    },
  },
  name: "SettingsCostVariationMatrix",
  data() {
    return {
      errors: [],
      show_form: false,
      cost_variation_matrix_listing: [],
      map_comparator: [[]],
      map_value_1: [[]],
      map_columns: [
        {
          header: "Odometer",
          column: "odometer_numeric",
          type: "Numeric Comparision",
        },
        { header: "Year", column: "year", type: "Numeric Comparision" },
        {
          header: "Selling Price",
          column: "selling_price_numeric",
          type: "Numeric Comparision",
        },
      ],
      form: {
        conditions: [
          {
            header: "",
            column: "",
            join: "AND",
            comparator: "",
            type: "",
            value_1: "",
            value_2: "",
          },
        ],
        amount: 0,
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
      },
    };
  },
  components: {
    "v-select": vSelect,
    draggable,
    DatePicker,
  },
  watch: {
    entity_id: function (newVal, oldVal) {
      this.getCostVariationList(this.entity, this.entity_id);
    },
    businessTaxSettings: function (newVal, oldVal) {
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
    "form.tax_type": function (newVal, oldVal) {
      this.form.tax_percentage = this.$helpers.setTaxRates(
        this.businessTaxSettings,
        newVal
      );
    },
    "form.tax_type_secondary": function (newVal, oldVal) {
      this.form.tax_percentage_secondary = this.$helpers.setTaxRates(
        this.businessTaxSettings,
        newVal
      );
    },
    "form.cost_tax_type": function (newVal, oldVal) {
      this.form.cost_tax_percentage = this.$helpers.setTaxRates(
        this.businessTaxSettings,
        newVal
      );
    },
    "form.cost_tax_type_secondary": function (newVal, oldVal) {
      this.form.cost_tax_percentage_secondary = this.$helpers.setTaxRates(
        this.businessTaxSettings,
        newVal
      );
    },
    calculateSubTotal: function (newVal, oldVal) {
      console.log("hello world");
    },
  },
  computed: {
    ...mapGetters("configuration", ["businessTaxSettings"]),
    calculateSubTotal: {
      get: function () {
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
      get: function () {
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
      get: function () {
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
    getFinanceTermsCount() {
      let count = 0;
      let conditions = this.form.conditions;
      if (Array.isArray(conditions)) {
        if (conditions.length > 0) {
          count = conditions.length;
        }
      }

      return count;
    },
  },
  methods: {
    ...mapActions("configuration", ["getBusinessTaxSettings"]),
    resetForm() {
      this.form.conditions = [
        {
          header: "",
          column: "",
          join: "AND",
          comparator: "",
          type: "",
          value_1: "",
          value_2: "",
        },
      ];

      this.form.amount = 0;
      this.form.tax_type = "";
      this.form.tax_percentage = 0;
      this.form.tax_amount = 0;

      this.form.tax_type_secondary = "";
      this.form.tax_amount_secondary = 0;
      this.form.tax_percentage_secondary = 0;

      this.form.tax_amount_total = 0;

      this.form.cost = 0;

      this.form.cost_tax_type = "";
      this.form.cost_tax_percentage = 0;
      this.form.cost_tax_amount = 0;

      this.form.cost_tax_type_secondary = "";
      this.form.cost_tax_amount_secondary = 0;
      this.form.cost_tax_percentage_secondary = 0;

      this.form.cost_tax_amount_total = 0;

      this.errors = [];

      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.default == "1"
      );

      if (selected_tax.length > 0) {
        this.form.tax_type = this.form.cost_tax_type = selected_tax[0].name;
        this.form.tax_percentage = this.form.cost_tax_percentage =
          this.$helpers.setTaxRates(
            this.businessTaxSettings,
            selected_tax[0].name
          );

        if (selected_tax.length > 1) {
          this.form.tax_type_secondary = this.form.cost_tax_type_secondary =
            selected_tax[1].name;
          this.form.tax_percentage_secondary =
            this.form.cost_tax_percentage_secondary = this.$helpers.setTaxRates(
              this.businessTaxSettings,
              selected_tax[1].name
            );
        }
      }
    },
    formatConditions(conditions) {
      try {
        let conditions_object = JSON.parse(conditions);
        let formatted_conditions = ``;
        for (const key in conditions_object) {
          const element = conditions_object[key];
          formatted_conditions += `${element["header"]} ${element["comparator"]} ${element["value_1"]}`;

          if (element["value_2"] !== "") {
            formatted_conditions += ` and ${element["value_2"]} `;
          }
        }
        return formatted_conditions;
      } catch (error) {
        return "";
      }
    },
    async getCostVariationList(entity, entity_id) {
      let request_url = `${window.location.origin}/api/business/cost-variable-matrix?zm_entity[]=${entity}&zm_entity_id[]=${entity_id}&paginate=false`;
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.cost_variation_matrix_listing =
            data.cost_variation_matrix_listing;
        })
        .catch(() => {
          console.log("Some error in fetching the cost variation matrix.");
        });
    },
    async save() {
      const vm = this;

      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && key != "conditions") {
          formData.append(key, this.form[key]);
        }
      }

      formData.append("entity", this.entity);
      formData.append("entity_id", this.entity_id);

      formData.append("conditions", JSON.stringify(this.form["conditions"]));

      let message = "Adding new Cost Variation...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "post",
        url:
          window.location.origin + "/api/business/cost-variable-matrix/create",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          let success_message = "New cost variation added.";
          toast.success(success_message);
          toast.close();
          this.getCostVariationList(this.entity, this.entity_id);
          this.show_form = false;

          this.resetForm();
        })
        .catch(function (error) {
          // handle error
          let error_message = "There were some errors while submitting form.";
          vm.$data.errors = error.response.data;
          toast.fail(error_message);
          toast.close();
        });
    },
    async deleteCostVariation(id) {
      const vm = this;
      let url = `${window.location.origin}/api/business/cost-variable-matrix/delete/${id}`;

      let message = "Deleting cost variation...";
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
          let success_message = `Cost variation has been deleted.`;
          toast.success(success_message);
          toast.close();

          vm.getCostVariationList(this.entity, this.entity_id);
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting Cost variation.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    addNewRow() {
      this.map_comparator.push([]);
      this.map_value_1.push([]);
      this.form.conditions.push({
        header: "",
        column: "",
        join: "AND",
        comparator: "",
        type: "",
        value_1: "",
        value_2: "",
      });
    },
    changeQueryDetails(i) {
      //fetch the row for which the input has changed.
      //once fetched changed the column_name, comparator in the corresponding object.
      let condition = this.form.conditions[i];
      condition.column = this.map_columns.filter(
        (x) => x.header == condition.header
      )[0].column;
      condition.type = this.map_columns.filter(
        (x) => x.header == condition.header
      )[0].type;
      condition.value_1 = "";
      condition.comparator = "";
      condition.value_2 = "";

      if (condition.type == "Null Comparision") {
        this.map_comparator[i] = ["is empty", "is not empty"];
      } else if (condition.type == "Date Comparision") {
        this.map_comparator[i] = ["is", "after", "before"];
      } else if (condition.type == "Multi Select") {
        this.map_comparator[i] = ["is", "is not"];
      } else if (condition.type == "Single Select") {
        this.map_comparator[i] = ["is"];
      } else if (
        condition.type == "Numeric Comparision" ||
        condition.type == "Relative Time Period Comparision"
      ) {
        this.map_comparator[i] = ["greater than", "less than", "between"];
      }
    },
    changeQueryAvailableValues(i) {
      //fetch the row for which the input has changed.
      //once fetched changed the column_name, comparator in the corresponding object.
      let condition = this.form.conditions[i];
      condition.column = this.map_columns.filter(
        (x) => x.header == condition.header
      )[0].column;
      condition.type = this.map_columns.filter(
        (x) => x.header == condition.header
      )[0].type;

      if (condition.type == "Null Comparision") {
        this.map_comparator[i] = ["is empty", "is not empty"];
      } else if (condition.type == "Date Comparision") {
        this.map_comparator[i] = ["is", "after", "before"];
      } else if (condition.type == "Multi Select") {
        this.map_comparator[i] = ["is", "is not"];
      } else if (condition.type == "Single Select") {
        this.map_comparator[i] = ["is"];
      } else if (
        condition.type == "Numeric Comparision" ||
        condition.type == "Relative Time Period Comparision"
      ) {
        this.map_comparator[i] = ["greater than", "less than", "between"];
      }
    },
  },
  async mounted() {
    const vm = this;
    await this.getBusinessTaxSettings();
    jQuery(`#costVariationMatrixModal${this.entity}${this.entity_id}`).on(
      "hidden.bs.modal",
      function () {
        vm.resetForm();
      }
    );
  },
  async unmounted() {
    this.cost_variation_matrix_listing = [];
  },
};
</script>
