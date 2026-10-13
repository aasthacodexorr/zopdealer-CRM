<template>
  <!-- Model Deal Modal : START -->
  <div
    class="modal fade"
    :id="current_modal_id"
    tabindex="-1"
    role="dialog"
    :aria-labelledby="`${modal_title}`"
    aria-hidden="true"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ modal_title }}</h5>
          <button
            type="button"
            :id="`${current_modal_id}Close`"
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
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="costs">Search Warranty : </label>
                <v-select
                  taggable
                  placeholder="Search Warranty Name"
                  label="company_name"
                  :reduce="(entity) => entity.id"
                  v-model="selected_presaved_entity"
                  clearable
                  :options="presaved_entities_list"
                ></v-select>
              </div>
            </div>
            <!-- vselect : END -->
            <!-- company_name : START -->
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="deal_warranties_company_name"
                  >Company Name :
                </label>
                <input
                  type="text"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('company_name'),
                  }"
                  @keyup="delete errors.company_name"
                  :title="errors.company_name"
                  v-model="form.company_name"
                  class="form-control"
                  id="deal_warranties_company_name"
                  placeholder="Company Name"
                />
              </div>
            </div>
            <!-- company_name : END -->
            <!-- description : START -->
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="deal_warranties_description">Description : </label>
                <textarea
                  type="text"
                  class="form-control"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('description'),
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
                <label for="deal_warranties_distance">Distance : </label>
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
                  placeholder="Amount"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('amount'),
                  }"
                  @keyup="
                    delete errors.amount;
                    triggerRecalculationOfFee('amount');
                  "
                  :title="errors.amount"
                  v-model="form.amount"
                  class="form-control"
                  id="deal_warranties_amount"
                />
              </div>
              <!-- amount : END -->

              <!-- tax_type : START -->
              <div class="form-group col-md-4">
                <label for="deal_warranties_tax_type">Tax Type</label>
                <select
                  class="custom-select form-control mr-1"
                  placeholder="Tax Type"
                  id="deal_warranties_tax_type"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('tax_type'),
                  }"
                  @keyup="delete errors.tax_type"
                  :title="errors.tax_type"
                  v-model="form.tax_type"
                  @change="triggerRecalculationOfFee('tax_type')"
                >
                  <option value="" selected disabled>Tax Type</option>
                  <option
                    :value="tax.name"
                    v-for="tax in businessTaxSettings"
                    :key="`${tax.name}_tax_type`"
                  >
                    {{ tax.name }}
                  </option>
                  <option value="Other">Other</option>
                </select>
              </div>
              <!-- tax_type : END -->

              <!-- secondary_tax_type : START -->
              <div class="form-group col-md-4">
                <label for="deal_warranties_tax_type_secondary">Tax Type</label>
                <select
                  class="custom-select form-control mr-1"
                  placeholder="Tax Type"
                  id="deal_warranties_tax_type_secondary"
                  :disabled="form['tax_type'] === 'Other'"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('tax_type_secondary'),
                  }"
                  @keyup="delete errors.tax_type_secondary"
                  @change="triggerRecalculationOfFee('tax_type_secondary')"
                  :title="errors.tax_type_secondary"
                  v-model="form.tax_type_secondary"
                >
                  <option value="" selected>Tax Type</option>
                  <option
                    :value="tax.name"
                    v-for="tax in businessTaxSettings"
                    :key="`${tax.name}_tax_type_secondary`"
                  >
                    {{ tax.name }}
                  </option>
                </select>
              </div>
              <!-- secondary_tax_type : END -->

              <!-- tax_amount : START -->
              <div class="form-group col-md-4">
                <label for="deal_warranties_tax_amount">Tax Amount</label>
                <input
                  type="text"
                  placeholder="Tax Amount"
                  class="form-control"
                  id="deal_warranties_tax_amount"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('tax_amount_total'),
                  }"
                  :disabled="form['tax_type'] !== 'Other'"
                  @keyup="
                    delete errors.tax_amount_total;
                    triggerRecalculationOfFee('tax_amount_total');
                  "
                  :title="errors.tax_amount_total"
                  v-model="form.tax_amount_total"
                />
              </div>
              <!-- tax_amount : END -->
              <div class="col-md-1 py-75">
                <i
                  v-show="price_recalculation"
                  class="bx bx-loader-alt bx-spin bx-md p-75 text-primary"
                ></i>
              </div>
            </div>
            <!-- combindation : END -->
            <div
              class="divider divider-dashed"
              type="button"
              data-toggle="collapse"
              :data-target="`#collapse${current_modal_id}Cost`"
              aria-expanded="false"
              :aria-controls="`#collapse${current_modal_id}Cost`"
            >
              <div class="divider-text">Click To Add Cost</div>
            </div>
            <!-- COST : START -->
            <div
              class="form-row collapse"
              :id="`collapse${current_modal_id}Cost`"
            >
              <!-- cost : START -->
              <div class="form-group col-md-4">
                <label for="deal_warranties_cost">Cost</label>
                <input
                  type="text"
                  placeholder="Cost"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('cost'),
                  }"
                  @keyup="
                    delete errors.cost;
                    triggerRecalculationOfCost('cost');
                  "
                  :title="errors.cost"
                  v-model="form.cost"
                  class="form-control"
                  id="deal_warranties_cost"
                />
              </div>
              <!-- cost : END -->

              <!-- cost_tax_type : START -->
              <div class="form-group col-md-4">
                <label for="deal_warranties_cost_tax_type">Tax Type</label>
                <select
                  class="custom-select form-control mr-1"
                  placeholder="Tax Type"
                  id="deal_warranties_cost_tax_type"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('cost_tax_type'),
                  }"
                  @change="
                    delete errors.cost_tax_type;
                    triggerRecalculationOfCost('cost_tax_type');
                  "
                  :title="errors.cost_tax_type"
                  v-model="form.cost_tax_type"
                >
                  <option value="" selected disabled>Tax Type</option>
                  <option
                    :value="tax.name"
                    v-for="tax in businessTaxSettings"
                    :key="`${tax.name}_cost_tax_type`"
                  >
                    {{ tax.name }}
                  </option>
                  <option value="Other">Other</option>
                </select>
              </div>
              <!-- cost_tax_type : END -->

              <!-- cost_tax_type_secondary : START -->
              <div class="form-group col-md-4">
                <label for="deal_warranties_cost_tax_type_secondary"
                  >Tax Type</label
                >
                <select
                  :class="{
                    'is-invalid': errors.hasOwnProperty(
                      'cost_tax_type_secondary'
                    ),
                  }"
                  @change="
                    delete errors.cost_tax_type_secondary;
                    triggerRecalculationOfCost('cost_tax_type_secondary');
                  "
                  :title="errors.cost_tax_type_secondary"
                  v-model="form.cost_tax_type_secondary"
                  class="custom-select form-control mr-1"
                >
                  <option value="" selected>Tax Type</option>
                  <option
                    :value="tax.name"
                    v-for="tax in businessTaxSettings"
                    :key="`${tax.name}_cost_tax_type_secondary`"
                  >
                    {{ tax.name }}
                  </option>
                </select>
              </div>
              <!-- cost_tax_type_secondary : END -->

              <!-- cost_tax_amount_total : START -->
              <div class="form-group col-md-4">
                <label for="deal_warranties_cost_tax_amount">Tax Amount</label>
                <input
                  type="text"
                  class="form-control"
                  :class="{
                    'is-invalid': errors.hasOwnProperty(
                      'cost_tax_amount_total'
                    ),
                  }"
                  @keyup="
                    delete errors.cost_tax_amount_total;
                    triggerRecalculationOfCost('cost_tax_amount_total');
                  "
                  :disabled="form['cost_tax_type'] !== 'Other'"
                  :title="errors.cost_tax_amount_total"
                  v-model="form.cost_tax_amount_total"
                  id="deal_warranties_cost_tax_amount"
                />
              </div>
              <!-- cost_tax_amount_total : END -->
              <div class="col-md-1 py-75">
                <i
                  v-show="price_recalculation"
                  class="bx bx-loader-alt bx-spin bx-md p-75 text-primary"
                ></i>
              </div>
            </div>
            <!-- COST : END -->
          </form>
        </div>
        <!-- modal footer : START -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Close
          </button>
          <button
            :disabled="price_recalculation"
            type="button"
            @click="validatePartialData(form)"
            class="btn btn-primary"
          >
            <i
              v-show="is_validating"
              class="bx bx-loader-alt bx-spin px-25 text-white"
              style="vertical-align: sub"
            ></i>
            Save
          </button>
        </div>
        <!-- modal footer : END -->
      </div>
    </div>
  </div>
  <!-- Model Deal Modal : END -->
</template>
<script>
import { mapGetters } from "vuex";
import Toast from "../../../library/Toast";
import { debounce } from "vue-debounce";
import vSelect from "vue-select";

export default {
  name: "DealImportWarranty",
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      load: true,
      modal_title: "Add Deal Warranty",
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
      },
      errors: {},
      default_tax_type: "",
      default_tax_type_secondary: "",
      price_recalculation: false,
      current_action: "add",
      current_module: "warranties",
      current_module_index: null,
      current_modal_id: "dealWarrantyModal",
      presaved_entities_list: [],
      selected_presaved_entity: "",
      is_validating: false,
    };
  },
  watch: {
    selected_presaved_entity: function (newVal, oldVal) {
      if (newVal != oldVal) {
        if (!isNaN(newVal) && newVal != "null") {
          this.getAPreSavedPreset(newVal);
        }
      }
    },
    businessTaxSettings: function (newVal, oldVal) {
      if (this.load == true) {
        let selected_tax = this.businessTaxSettings.filter(
          (tax) => tax.default == "1"
        );

        if (selected_tax.length > 0) {
          this.form.tax_type =
            this.form.cost_tax_type =
            this.default_tax_type =
              selected_tax[0].name;
          if (selected_tax.length > 1) {
            this.form.tax_type_secondary =
              this.form.cost_tax_type_secondary =
              this.default_tax_type_secondary =
                selected_tax[1].name;
          }
        }
      }

      this.load = false;
    },
    "form.duration": function (newVal, oldVal) {
      let duration =
        !isNaN(this.form.duration) && this.form.duration != ""
          ? parseFloat(this.form.duration)
          : 0;
      this.form.duration = duration;
    },
  },
  computed: {
    ...mapGetters("configuration", ["businessTaxSettings"]),
  },
  methods: {
    triggerRecalculationOfFee: debounce(function (attribute = null) {
      this.calculateAFee(attribute);
    }, "700ms"),
    triggerRecalculationOfCost: debounce(function (attribute = null) {
      this.calculateACost(attribute);
    }, "700ms"),
    calculateAFee(attribute = null) {
      const vm = this;
      const mapper = {
        tax_percentage: "tax_percentage",
        tax_amount: "tax_amount",
        tax_percentage_secondary: "tax_percentage_secondary",
        tax_amount_secondary: "tax_amount_secondary",
      };

      if (attribute !== "tax_amount_total") {
        mapper.tax_amount_total = "tax_amount_total";
      }

      if (attribute === "tax_type" || attribute === "tax_type_secondary") {
        if (vm.form["tax_type"] === "Other") {
          vm.form["tax_type_secondary"] = "";
        }
      } else if (attribute !== null) {
        vm.form[attribute] = this.$helpers.coerceToNumberString(
          vm.form[attribute]
        );
      }

      vm.calculateLineTotal(
        vm.form.amount,
        0,
        1,
        vm.form.tax_type,
        vm.form.tax_type_secondary,
        vm.form.tax_amount_total,
        mapper,
        `form`
      );
    },
    calculateACost(attribute = null) {
      const vm = this;
      const mapper = {
        cost_tax_percentage: "tax_percentage",
        cost_tax_amount: "tax_amount",
        cost_tax_percentage_secondary: "tax_percentage_secondary",
        cost_tax_amount_secondary: "tax_amount_secondary",
      };

      if (attribute !== "cost_tax_amount_total") {
        mapper.cost_tax_amount_total = "tax_amount_total";
      }

      if (
        attribute === "cost_tax_type" ||
        attribute === "cost_tax_type_secondary"
      ) {
        if (vm.form["cost_tax_type"] === "Other") {
          vm.form["cost_tax_type_secondary"] = "";
        }
      } else if (attribute !== null) {
        vm.form[attribute] = this.$helpers.coerceToNumberString(
          vm.form[attribute]
        );
      }

      vm.calculateLineTotal(
        vm.form.cost,
        0,
        1,
        vm.form.cost_tax_type,
        vm.form.cost_tax_type_secondary,
        vm.form.cost_tax_amount_total,
        mapper,
        `form`
      );
    },
    async calculateLineTotal(
      amount,
      discount = 0,
      quantity = 1,
      tax_type,
      tax_type_secondary,
      tax_amount_total,
      fields,
      base_form,
      base_form_sub_key = null,
      loader_field = "price_recalculation"
    ) {
      const vm = this;
      vm.$data[loader_field] = true;
      if (this.load == false) {
        let formData = new FormData();
        formData.append("amount", amount);
        formData.append("discount", discount);
        formData.append("quantity", quantity);
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
          },
        })
          .then(({ data }) => {
            for (const [key, value] of Object.entries(fields)) {
              if (base_form_sub_key !== null) {
                vm.$data[base_form][base_form_sub_key][key] = data[value];
              } else {
                vm.$data[base_form][key] = data[value];
              }
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
    async validatePartialData(form) {
      const vm = this;
      let formData = new FormData();
      for (const [key, value] of Object.entries(form)) {
        formData.append(key, value);
      }

      let request_url = `${window.location.origin}/api/business/deals/common/${this.current_module}/validate`;

      this.is_validating = true;

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
          Event.fire("deal_import_worksheet_entity_save", {
            action: vm.current_action,
            module: vm.current_module,
            payload: vm.form,
            module_index: vm.current_module_index,
          });

          vm.resetForm();
          vm.is_validating = false;
          jQuery(`#${vm.current_modal_id}`).modal("hide");
        })
        .catch(function (error) {
          vm.errors = error.response.data;
          vm.is_validating = false;
        });
    },
    getAPreSavedPreset(id) {
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
          this.errors = [];
          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the saved preset";
          console.log(error);
        });
    },
    getAllPresavedPresets() {
      let request_url =
        window.location.origin +
        "/api/business/setting/warranty?paginate=false";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.presaved_entities_list = data.cost_setting_listing;
        })
        .catch(() => {
          console.log("Some error in fetching the saved presets");
        });
    },
    resetForm() {
      this.form.company_name = "";
      this.form.amount = 0;
      this.form.duration = 0;
      this.form.distance = "";
      this.form.description = "";
      this.form.tax_type = this.default_tax_type;
      this.form.tax_percentage = 0;
      this.form.tax_amount = 0;

      this.form.tax_type_secondary = this.default_tax_type_secondary;
      this.form.tax_amount_secondary = 0;
      this.form.tax_percentage_secondary = 0;
      this.form.tax_amount_total = 0;

      this.form.cost = 0;
      this.form.cost_tax_type = this.default_tax_type;
      this.form.cost_tax_percentage = 0;
      this.form.cost_tax_amount = 0;

      this.form.cost_tax_type_secondary = this.default_tax_type_secondary;
      this.form.cost_tax_amount_secondary = 0;
      this.form.cost_tax_percentage_secondary = 0;
      this.form.cost_tax_amount_total = 0;

      this.errors = {};
      this.modal_title = "Add Deal Warranty";
      this.current_action = "add";
      this.current_module_index = null;

      this.selected_presaved_entity = "";
    },
  },
  created() {
    this.getAllPresavedPresets();
  },
  mounted() {
    const vm = this;
    jQuery(`#${vm.current_modal_id}`).on("hidden.bs.modal", function () {
      vm.resetForm();
    });

    Event.listen(
      "deal_import_worksheet_entity",
      ({ action, module, payload = null, module_index = null }) => {
        vm.resetForm();

        if (module === vm.current_module) {
          vm.current_action = action;
          jQuery(`#${vm.current_modal_id}`).modal("show");

          if (action === "update") {
            if (payload && module_index !== null) {
              vm.current_module_index = module_index;
              vm.modal_title = "Update Deal Warranty";

              for (const [key, value] of Object.entries(vm.form)) {
                vm.form[key] = payload[key];
              }
            }
          }
        }
      }
    );
  },
};
</script>