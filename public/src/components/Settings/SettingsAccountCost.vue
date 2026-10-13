<template>
  <div
    role="tabpanel"
    class="tab-pane"
    id="setting-account-cost"
    aria-labelledby="Cost Setting"
    aria-expanded="true"
  >
    <div class="content-wrapper m-0 border-bottom">
      <div class="row">
        <div class="col-md-5 col-12">
          <ul class="pl-0">
            <li class="d-inline-block mr-1">
              <div class="content-list-box">
                <p class="p-0 m-0">
                  <span class="blue-color text-bold-400 title-sizeincrease-font"
                    >Cost Settings</span
                  >
                </p>
              </div>
            </li>
          </ul>
        </div>
        <div class="col-md-7 col-12">
          <ul class="p-0 float-right d-flex">
            <li class="d-inline-block">
              <!-- <button type="reset" class="btn btn-light-secondary mr-1">Cancel</button> -->
              <!--                                                                <button type="submit" class="btn btn-primary">Save</button>-->
            </li>
            <li class="d-inline-block">
              <!-- Button trigger modal -->
              <button
                type="button"
                class="btn btn-outline-primary"
                data-toggle="modal"
                data-target="#costSettingModal"
              >
                + Add Cost
              </button>
              <!-- Modal -->
              <div
                class="modal fade"
                id="costSettingModal"
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
                        id="costSettingModalClose"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                      >
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <small
                        role="alert"
                        class="alert alert-light-warning mb-0 fade show d-block"
                      >
                        Turn ON <b>Include Cost By Default</b> to automatically
                        include the cost when adding new inventory
                      </small>
                      <form>
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
                          <div class="form-group col-md-12">
                            <label for="name">Select Vendor : </label>
                            <v-select
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
                              type="text"
                              :class="{
                                'is-invalid': errors.hasOwnProperty('quantity'),
                              }"
                              @keyup="delete errors.quantity"
                              :title="errors.quantity"
                              v-model="form.quantity"
                              placeholder="Quantity"
                              class="form-control"
                              id="quantity"
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
                          <!-- secondary tax : START -->
                          <div class="form-group col-md-4">
                            <label for="tax_type_secondary">Tax Type</label>
                            <select
                              class="custom-select form-control mr-1"
                              :class="{
                                'is-invalid': errors.hasOwnProperty(
                                  'tax_type_secondary',
                                ),
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
                                >{{ tax.name }}</option
                              >
                            </select>
                          </div>
                          <!-- secondary tax: END -->
                          <div class="form-group col-md-4">
                            <label for="tax_amount_total">Tax Amount</label>
                            <input
                              type="text"
                              :class="{
                                'is-invalid': errors.hasOwnProperty(
                                  'tax_amount_total',
                                ),
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
                                'is-invalid': errors.hasOwnProperty(
                                  'sub_total',
                                ),
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
                        <hr />
                        <!-- include_cost_by_default : START -->
                        <div class="col-md-6 form-group">
                          <label for="include_cost_by_default"
                            >Include Cost By Default</label
                          >
                          <div class="custom-control custom-switch">
                            <input
                              type="checkbox"
                              class="custom-control-input"
                              id="include_cost_by_default"
                              v-model="form.include_cost_by_default"
                              name="Include Cost By Default"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label
                              class="custom-control-label"
                              for="include_cost_by_default"
                            >
                              <span class="switch-icon-left"> ON</span>
                              <span class="switch-icon-right">OFF</span>
                            </label>
                          </div>
                        </div>
                        <!-- include_cost_by_default : END -->
                      </form>
                    </div>
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
                        @click.prevent="saveCostSetting"
                      >
                        Save
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Modal END -->
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
                  <th class="text-center">Name</th>
                  <th class="text-center">Vendor</th>
                  <th class="text-center">Sub Total</th>
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
                      {{ cost_setting.name }}</span
                    >
                  </td>
                  <td class="text-center">{{ cost_setting.vendor_name }}</td>
                  <td class="text-center">{{ cost_setting.sub_total }}</td>
                  <td class="text-center">
                    <div class="dropdown">
                      <span
                        class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
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
                          style="cursor: pointer;"
                          @click.prevent="getCostSetting(cost_setting.id)"
                          >Edit</a
                        >
                        <a
                          class="dropdown-item"
                          style="cursor: pointer;"
                          @click.prevent="deleteCostSetting(cost_setting.id)"
                          >Delete</a
                        >
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
                  style="cursor: pointer; padding-right: 0.2em;"
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
                  style="cursor: pointer; padding-right: 0.2em;"
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
                  style="cursor: pointer; padding-right: 0.2em;"
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

export default {
  name: "SettingsAccountCost",
  data() {
    return {
      errors: [],
      cost_setting_listing: [],
      title: "Add New Cost",
      pager: {},
      page: 1,
      cost_setting_id: null,
      action: "create",
      form: {
        name: "",
        vendor_name: "",
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

        include_cost_by_default: "0",
      },
    };
  },
  watch: {
    businessTaxSettings: function(newVal, oldVal) {
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
    calculateSubTotal: function(newVal, oldVal) {
      console.log("hello world");
    },
  },
  components: {
    "v-select": vSelect,
  },
  computed: {
    ...mapGetters("configuration", ["businessTaxSettings"]),
    ...mapGetters("vendor", ["allVendors"]),
    calculateSubTotal: {
      get: function() {
        let price =
          !isNaN(this.form.price) && this.form.price != ""
            ? this.form.price
            : 0;
        this.form.price = price;
        let quantity =
          !isNaN(this.form.quantity) && this.form.quantity != ""
            ? parseFloat(this.form.quantity)
            : 0;
        this.form.quantity = quantity;
        let discount =
          !isNaN(this.form.discount) && this.form.discount != ""
            ? this.form.discount
            : 0;
        this.form.discount = discount;

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
          tax = !isNaN(this.form.tax_amount_total)
            ? parseFloat(this.form.tax_amount_total)
            : 0;
        }

        this.form.tax_amount_total = this.$helpers.round(tax, 3);

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
        if (!flag) {
          this.form.tax_type_secondary = "";
        }
        return flag;
      },
    },
  },
  methods: {
    ...mapActions("configuration", ["getBusinessTaxSettings"]),
    ...mapActions("vendor", ["getAllVendors"]),
    resetForm() {
      this.form.name = "";
      this.form.vendor_name = "";
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

      this.form.include_cost_by_default = "0";

      this.action = "create";
      this.cost_setting_id = null;
      this.title = "Add New Cost";
      this.errors = [];

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
    costSettingListing() {
      let request_url = window.location.origin + "/api/business/setting/costs";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
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
      url += `/api/business/setting/costs?page=`;
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
    saveCostSetting() {
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
        ? `${request_url}/api/business/setting/costs/update/${this.cost_setting_id}`
        : `${request_url}/api/business/setting/costs/create`;

      let message = update_request
        ? `Updating cost ...`
        : `Adding new cost ...`;

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

          document.getElementById("costSettingModalClose").click();

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
    deleteCostSetting(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/setting/costs/delete/`;
      url += id;

      let message = "Deleting cost setting...";
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
          let success_message = `Cost setting has been deleted.`;
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
          let error_message = `Error in deleting cost setting.`;
          toast.fail(error_message);
          toast.close();
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
          //this.form = data;

          this.action = "update";
          this.title = "Update Cost";
          this.cost_setting_id = data.id;
          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }

          jQuery("#costSettingModal").modal("show");
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the cost setting";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
          vm.resetForm();
        });
    },
  },
  mounted() {
    this.getBusinessTaxSettings();
    this.costSettingListing();
    this.getAllVendors();
    const vm = this;
    jQuery("#costSettingModal").on("hidden.bs.modal", function() {
      vm.resetForm();
    });
  },
};
</script>
<style lang="scss">
#costSettingModal .vs__dropdown-menu {
  z-index: 9999;
  max-height: 250px;
  overflow-y: auto;
}
#costSettingModal input:read-only {
  background: #eee !important;
}
</style>
