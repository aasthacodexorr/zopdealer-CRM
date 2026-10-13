<template>
  <div class="app-content content" v-show="isAuthorized">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="col-xs-12 col-md-4">
          <div class="card">
            <div
              class="card-header d-flex justify-content-between align-items-center pb-50"
            >
              <h4 class="card-title">Bulk Pricing Change</h4>
            </div>
            <div class="card-content pl-2">
              <!-- Status : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <label for="">Filter Inventory Status</label>
                  <select
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('status'),
                    }"
                    @keyup="delete errors.status"
                    :title="errors.status"
                    v-model="form.status"
                  >
                    <option value="" disabled>-- Select Status --</option>
                    <option
                      :value="status"
                      v-for="status in getStatuses"
                      :key="status"
                    >
                      {{ status }}
                    </option>
                  </select>
                </div>
              </div>
              <!-- Status : END -->

              <!-- Year : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <label for="">Filter Inventory Year</label>
                  <v-select
                    id="filter-year"
                    placeholder="Year"
                    v-model="form.year"
                    clearable
                    :options="getYears"
                  >
                  </v-select>
                </div>
              </div>
              <!-- Year : END -->

              <!-- Make : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <label for="">Filter Inventory Make</label>
                  <v-select
                    id="filter-make"
                    placeholder="Make"
                    v-model="form.make"
                    clearable
                    :options="getMakes"
                  >
                  </v-select>
                </div>
              </div>
              <!-- Make : END -->

              <!-- Model : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <label for="">Filter Inventory Model</label>
                  <v-select
                    id="filter-model"
                    placeholder="Model"
                    v-model="form.model"
                    clearable
                    :options="getModels"
                  >
                  </v-select>
                </div>
              </div>
              <!-- Model : END -->

              <!-- Column : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <label for="">Inventory Column To Adjust</label>
                  <select
                    :class="{
                      'is-invalid': errors.hasOwnProperty('column'),
                    }"
                    @keyup="delete errors.column"
                    :title="errors.column"
                    class="form-control"
                    v-model="form.column"
                  >
                    <option value="" disabled>-- Select Column --</option>
                    <option
                      :value="current_column.value"
                      v-for="current_column in getSelectableColumns"
                      :key="current_column.value"
                    >
                      {{ current_column.key }}
                    </option>
                  </select>
                </div>
              </div>
              <!-- Column : END -->

              <!-- Operator : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <label for="">Operator</label>
                  <select
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('operator'),
                    }"
                    @keyup="delete errors.operator"
                    :title="errors.operator"
                    v-model="form.operator"
                  >
                    <option value="" disabled>-- Select Operator --</option>
                    <option
                      :value="current_operator.value"
                      v-for="current_operator in getOperators"
                      :key="current_operator.value"
                    >
                      {{ current_operator.key }}
                    </option>
                  </select>
                </div>
              </div>
              <!-- Operator : END -->

              <!-- Operator : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <label for="">Amount</label>
                  <input
                    :class="{
                      'is-invalid': errors.hasOwnProperty('amount'),
                    }"
                    @keyup="delete errors.amount"
                    :title="errors.amount"
                    type="text"
                    class="form-control"
                    v-model="form.amount"
                  />
                </div>
              </div>
              <!-- Operator : END -->

              <span v-show="isValid" class="text-uppercase">
                <b>
                  {{ getMessage }}
                </b>
              </span>

              <!-- Button : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <button
                    @click.prevent="warningForBulkPricingChanges"
                    v-show="isValid"
                    class="btn btn-success"
                  >
                    Submit
                  </button>
                </div>
              </div>
              <!-- Button : END-->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
  <script>
import Toast from "../../library/Toast";
import { mapGetters, mapActions } from "vuex";
import vSelect from "vue-select";

export default {
  props: ["application", "app_metadata"],
  name: "InventoryBulkPricing",
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      form: {
        status: "Instock",
        year: "",
        make: "",
        model: "",
        column: "",
        operator: "EQUAL",
        amount: "",
      },
      errors: [],
      submit: false,
    };
  },
  computed: {
    ...mapGetters("inventoryView", ["getStatuses", "getAvailableFilters"]),
    getSelectableColumns: () => {
      return [
        { key: "Special Price", value: "special_price" },
        { key: "Price", value: "price" },
        { key: "Interest Rate", value: "interest_rate" },
        { key: "Term", value: "term" },
        { key: "Downpayment", value: "downpayment" },
      ];
    },
    getOperators: () => {
      return [
        { key: "EQUAL TO", value: "EQUAL" },
        { key: "INCREASE BY", value: "INCREMENT" },
        { key: "DECREASE BY", value: "DECREMENT" },
      ];
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    isValid() {
      if (this.submit) {
        return false;
      }

      if (
        !isNaN(this.form.amount) &&
        this.form.column !== "" &&
        this.form.amount !== ""
      ) {
        if (["interest_rate", "downpayment"].includes(this.form.column)) {
          if (parseInt(this.form.amount) > 100) {
            return false;
          }
        }
        return true;
      }

      return false;
    },
    getYears() {
      if (this.getAvailableFilters.hasOwnProperty("year")) {
        if (Array.isArray(this.getAvailableFilters["year"])) {
          return this.getAvailableFilters["year"]
            .filter((x) => x.name != "")
            .map((x) => x.name);
        }
      }

      return [];
    },
    getMakes() {
      if (this.getAvailableFilters.hasOwnProperty("make")) {
        if (Array.isArray(this.getAvailableFilters["make"])) {
          return this.getAvailableFilters["make"]
            .filter((x) => x.name != "")
            .map((x) => x.name);
        }
      }

      return [];
    },
    getModels() {
      if (this.getAvailableFilters.hasOwnProperty("model")) {
        if (Array.isArray(this.getAvailableFilters["model"])) {
          return this.getAvailableFilters["model"]
            .filter((x) => x.name != "")
            .map((x) => x.name);
        }
      }

      return [];
    },
    getMessage() {
      let column =
        this.getSelectableColumns.filter(
          (column) => column.value === this.form.column
        ).length > 0
          ? this.getSelectableColumns.filter(
              (column) => column.value === this.form.column
            )[0]["key"]
          : "";

      let operator =
        this.getOperators.filter(
          (operator) => operator.value === this.form.operator
        ).length > 0
          ? this.getOperators.filter(
              (operator) => operator.value === this.form.operator
            )[0]["key"]
          : "";

      let status =
        this.getStatuses.filter((status) => status === this.form.status)
          .length > 0
          ? this.getStatuses.filter((status) => status === this.form.status)
          : "";

      let message = `SET ${column} ${operator} ${this.form.amount} for ${status} Inventories`;

      if (this.form.year) {
        message = `${message} \n AND WHERE YEAR is ${this.form.year}`;
      }

      if (this.form.make) {
        message = `${message} \n AND WHERE MAKE is ${this.form.make}`;
      }

      if (this.form.model) {
        message = `${message} \n AND WHERE MODEL is ${this.form.model}`;
      }

      return message;
    },
  },
  methods: {
    ...mapActions("inventoryView", [
      "commitOnlyEntities",
      "commitAvailableFilters",
    ]),
    async warningForBulkPricingChanges() {
      const vm = this;
      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Back",
        confirmButtonText: "Yes",
        toast: false,
        position: "center",
        text: `This action will ${this.getMessage}. Are you sure about this action?`,
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.bulkUpdate();
        }
      });
    },
    bulkUpdate() {
      const vm = this;
      this.submit = true;
      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && this.form[key] != null) {
          formData.append(key, this.form[key]);
        }
      }

      const toast = new Toast(
        `Bulk changes in progress. This might take some time ...`
      );
      toast.fire();

      axios({
        method: "post",
        url: `${window.location.origin}/api/business/inventory/pricing/updates`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(
            `Bulk changes done. This may take some time to reflect.`
          );
          toast.close();
          this.commitOnlyEntities();
          this.submit = false;
          this.form.amount = "";
          this.form.year = "";
          this.form.make = "";
          this.form.model = "";
          this.form.status = "Instock";
          this.form.column = "";
          this.form.operator = "EQUAL";
        })
        .catch(function (error) {
          let error_message = "There were errors in bulk changes.";
          vm.$data.errors = error.response.data;
          toast.fail(error_message);
          toast.close();
          this.submit = false;
        });
    },
  },
  created() {
    if (Object.keys(this.getAvailableFilters).length === 0) {
      this.commitAvailableFilters();
    }
  },
};
</script>
  