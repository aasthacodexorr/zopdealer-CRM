<template>
  <div
    class="tab-pane"
    id="deal-sale-commission-panel"
    aria-labelledby="Deal Sale Commission Panel"
    role="tabpanel"
    v-show="isAuthorized"
  >
    <!-- Deal Sale Commission  form layout section start -->
    <!-- Expenses  form layout section start -->
    <section id="deal-sale-commission-section">
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
                      data-target="#dealSaleCommissionModal"
                    >
                      <i
                        style="font-size: 1.4em; padding-right: 0.5em"
                        class="bx bx-list-plus"
                      ></i>
                      Add Commission
                    </button>

                    <button
                      style="font-weight: 800"
                      type="button"
                      class="btn btn-secondary float-right"
                      @click.prevent="recalculateCommissionCalculatedPrice"
                    >
                      <i
                        style="font-size: 1.4em; padding-right: 0.5em"
                        class="bx bx-reset"
                      ></i>
                      Refresh Calculated Amount
                    </button>
                  </div>
                  <div class="col-xs-12 col-12">
                    <div class="table-responsive">
                      <table class="table sales-commissions">
                        <thead>
                          <tr>
                            <th class="text-center" scope="col">Date</th>
                            <th class="text-center" scope="col">
                              Commission Role
                            </th>
                            <th class="text-center" scope="col">User</th>
                            <th class="text-center" scope="col">Rate Type</th>
                            <th class="text-center" scope="col">Percentage</th>
                            <th class="text-center" scope="col">
                              Calculated Amount
                            </th>
                            <th class="text-center" scope="col">Paid Amount</th>
                            <th
                              class="text-center"
                              scope="col"
                              style="min-width: 100px"
                            ></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="deal_sale_commission in deal_sale_commissions"
                            :key="deal_sale_commission.id"
                          >
                            <td class="text-center">
                              {{ formatDate(deal_sale_commission.paid_date) }}
                            </td>
                            <td class="text-center p-0">
                              {{ deal_sale_commission.role }}
                            </td>
                            <td class="text-center p-0">
                              {{ currentUser(deal_sale_commission.user) }}
                            </td>
                            <td class="text-center p-0">
                              {{ deal_sale_commission.type }}
                            </td>
                            <td class="text-center p-0">
                              <span v-if="deal_sale_commission.type !== 'Flat'">
                                {{ deal_sale_commission.percentage }} %</span
                              >
                            </td>
                            <td class="text-center p-0">
                              {{
                                `$ ${Number(
                                  deal_sale_commission.calculated_amount
                                ).toLocaleString()}`
                              }}
                            </td>
                            <td class="text-center p-0">
                              {{
                                `$ ${Number(
                                  deal_sale_commission.paid_amount
                                ).toLocaleString()}`
                              }}
                            </td>
                            <td class="p-0">
                              <button
                                class="btn btn-info"
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                "
                                @click.prevent="
                                  editDealSaleCommission(
                                    deal_sale_commission.id
                                  )
                                "
                              >
                                <i class="bx bx-edit"></i>
                              </button>
                              <button
                                class="btn btn-danger"
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                "
                                @click.prevent="
                                  deletionWarning(deal_sale_commission.id)
                                "
                              >
                                <i class="bx bx-trash-alt"></i>
                              </button>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2" class="text-left font-weight-bold">
                              Calculated Amount :
                              <span v-show="calculateCalculatedAmount > 0">
                                {{
                                  `$ ${Number(
                                    calculateCalculatedAmount
                                  ).toLocaleString()}`
                                }}</span
                              >
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td colspan="2" class="text-right font-weight-bold">
                              Paid Amount :
                              <span v-show="calculatePaidTotal > 0">
                                {{
                                  `$ ${Number(
                                    calculatePaidTotal
                                  ).toLocaleString()}`
                                }}</span
                              >
                            </td>
                            <td></td>
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
    <!-- Deal Sale Commission  form layout section end -->

    <!-- Modal : START -->
    <div
      class="modal fade"
      id="dealSaleCommissionModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Deal Sale Commission Modal"
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
              id="dealSaleCommissionModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_sale_commission_paid_date">Date : </label>
                  <fieldset
                    class="has-icon-left"
                    style="position: relative"
                    id="deal_sale_commission_paid_date"
                  >
                    <date-picker
                      v-model="form.paid_date"
                      :format="'MM/DD/YYYY'"
                      :value-type="'MM/DD/YYYY'"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('paid_date'),
                      }"
                      @change="delete errors.paid_date"
                      :title="errors.paid_date"
                      :clearable="false"
                    ></date-picker>
                  </fieldset>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="commission_role">Commission Role : </label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('role'),
                    }"
                    @keyup="delete errors.role"
                    :title="errors.role"
                    v-model="form.role"
                    placeholder="Role"
                    id="commission_role"
                  >
                    <option value="" selected disabled>Select Role Type</option>
                    <option
                      :value="available_role"
                      v-for="available_role in getCommissionRoles"
                      :key="available_role"
                    >
                      {{ available_role }}
                    </option>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="commission_user">User : </label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('user'),
                    }"
                    @keyup="delete errors.user"
                    :title="errors.user"
                    v-model="form.user"
                    placeholder="User"
                    id="commission_user"
                  >
                    <option value="" selected disabled>Select User</option>
                    <option
                      :value="user.user_id"
                      v-for="user in allUsers"
                      :key="user.user_id + '_user'"
                      v-text="user.name"
                    ></option>
                  </select>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="commission_type">Commission Type : </label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('type'),
                    }"
                    @keyup="delete errors.type"
                    :title="errors.type"
                    v-model="form.type"
                    placeholder="Type"
                    id="commission_type"
                  >
                    <option value="" selected disabled>
                      Select Commission Type
                    </option>
                    <option
                      :value="available_type"
                      v-for="available_type in getCommissionTypes"
                      :key="available_type"
                    >
                      {{ available_type }}
                    </option>
                  </select>
                </div>
              </div>

              <div class="form-row" v-show="form.type !== 'Flat'">
                <div class="form-group col-md-12">
                  <label for="percentage">Percentage</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('percentage'),
                    }"
                    @keyup="delete errors.percentage"
                    :title="errors.percentage"
                    v-model="form.percentage"
                    placeholder="Percentage"
                    class="form-control"
                    id="percentage"
                  />
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="calculated_amount">Calculated Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('calculated_amount'),
                    }"
                    @keyup="delete errors.calculated_amount"
                    :title="errors.calculated_amount"
                    v-model="form.calculated_amount"
                    placeholder="Calculated Amount"
                    class="form-control"
                    id="calculated_amount"
                  />
                </div>
              </div>

              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="paid_amount">Paid Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('paid_amount'),
                    }"
                    @keyup="delete errors.paid_amount"
                    :title="errors.paid_amount"
                    v-model="form.paid_amount"
                    placeholder="Paid Amount"
                    class="form-control"
                    id="paid_amount"
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
              @click.prevent="saveDealSaleCommission"
            >
              Save
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal : END -->
  </div>
</template>
<script>
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import Toast from "../../../library/Toast";
import { mapGetters, mapActions } from "vuex";

export default {
  props: {
    id: { required: true },
    allUsers: { required: true },
    app_metadata: { required: true },
    time_zone: String,
  },
  name: "DealSaleCommission",
  data() {
    return {
      title: "Add Sale Commission",
      deal_sale_commission_id: null,
      errors: [],
      deal_sale_commissions: [],
      complete_deal_breakdown: null,
      form: {
        role: "Rep",
        user: "",
        type: "Flat",
        percentage: 0,
        calculated_amount: 0,
        paid_amount: 0,
        paid_date: "",
      },
    };
  },
  components: {
    DatePicker,
  },
  watch: {
    calculatedAmount: function (newVal, oldVal) {
      console.log("");
    },
    "form.percentage": function (newVal, oldVal) {
      let percentage =
        !isNaN(this.form.percentage) && this.form.percentage != ""
          ? this.form.percentage
          : 0;

      percentage =
        parseFloat(percentage, 10) < 0 || parseFloat(percentage, 10) > 100
          ? 0
          : percentage;
      this.form.percentage = percentage;
    },
    "form.paid_amount": function (newVal, oldVal) {
      let paid_amount =
        !isNaN(this.form.paid_amount) && this.form.paid_amount != ""
          ? this.form.paid_amount
          : 0;

      this.form.paid_amount = paid_amount;
    },
  },
  computed: {
    ...mapGetters("deal", ["getCommissionRoles", "getCommissionTypes"]),
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    calculateCalculatedAmount() {
      return this.deal_sale_commissions.reduce(
        (total, commission) => total + parseFloat(commission.calculated_amount),
        0
      );
    },
    calculatePaidTotal() {
      return this.deal_sale_commissions.reduce(
        (total, commission) => total + parseFloat(commission.paid_amount),
        0
      );
    },
    calculatedAmount: {
      get: function () {
        if (
          this.form.type !== "Flat" &&
          this.complete_deal_breakdown !== null
        ) {
          let percentage =
            !isNaN(this.form.percentage) && this.form.percentage != ""
              ? parseFloat(this.form.percentage)
              : 0;

          let amount = 0;

          switch (this.form.type) {
            case "Selling Price Percentage":
              amount = parseFloat(
                this.complete_deal_breakdown.deal_selling_price
              );
              break;

            case "Vehicle Profit Percentage":
              amount = parseFloat(this.complete_deal_breakdown.vehicle_profit);
              break;

            case "Deal Profit Percentage":
              amount = parseFloat(
                this.complete_deal_breakdown
                  .deal_profit_cost_exclude_sale_commission
              );
              break;

            case "Additional Income Percentage":
              amount = parseFloat(
                this.complete_deal_breakdown.additional_income_difference
              );
              break;

            case "Backend Income Percentage":
              amount = parseFloat(
                this.complete_deal_breakdown.backend_income_difference
              );
              break;

            default:
              break;
          }

          this.form.calculated_amount = amount * percentage * 0.01;
        }
      },
    },
  },
  methods: {
    resetForm() {
      this.form.role = "Rep";
      this.form.user = "";
      this.form.paid_date = moment(new Date()).format("MM/DD/YYYY");
      this.form.type = "Flat";
      this.form.percentage =
        this.form.calculated_amount =
        this.form.paid_amount =
          0;
      this.form.title = "Add Sale Commission";
      this.form.deal_sale_commission_id = null;
      this.errors = [];
    },
    currentUser(user_id) {
      return this.$helpers.assigneeName(this.allUsers, user_id);
    },
    formatDate(date) {
      if (moment(date).isValid()) {
        return moment(date).format("MM/DD/YYYY");
      } else {
        return "";
      }
    },
    async getDealCompleteBreakDown(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin + "/api/business/deals/breakdown/" + deal_id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.complete_deal_breakdown = data;
        })
        .catch(({ response }) => {
          this.complete_deal_breakdown = [];
        });
    },
    async recalculateCommissionCalculatedPrice() {
      const vm = this;

      const toast = new Toast(
        `Recalculating the Sales Commission's calculated amount ...`
      );
      toast.fire();

      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/deals/sale-commissions/recalculate/" +
          vm.$route.params.id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            vm.getDealSaleCommissions(vm.$route.params.id);
          }

          toast.success(data.success);
          toast.close();
        })
        .catch(({ response }) => {
          toast.fail(
            `Error in recalculating the Sales Commission's calculated amount.`
          );
          toast.close();
        });
    },
    async getDealSaleCommissions(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/deals/" +
          deal_id +
          "/common/sale-commissions",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.deal_sale_commissions = data;
        })
        .catch(({ response }) => {
          this.deal_sale_commissions = [];
        });
    },
    async editDealSaleCommission(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/deals/${vm.$route.params.id}/common/sale-commissions/show/`;
      url += id;

      await axios({
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

          this.deal_sale_commission_id = data.id;
          this.action = "update";
          this.title = "Update Commission";

          let paid_date = moment(data.paid_date);

          this.form.paid_date = paid_date.isValid()
            ? paid_date.format("MM/DD/YYYY")
            : moment(new Date()).format("MM/DD/YYYY");

          jQuery("#dealSaleCommissionModal").modal("show");
        })
        .catch(({ error }) => {
          let error_message = `Error in fetching sale commission.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    async saveDealSaleCommission() {
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
        !isNaN(this.deal_sale_commission_id) &&
        this.deal_sale_commission_id != null;

      //success message setting
      let success_message = update_request
        ? `Sale commission updated.`
        : `Sale commission added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/deals/${this.$route.params.id}/common/sale-commissions/update/${this.deal_sale_commission_id}`
        : `${request_url}/api/business/deals/${this.$route.params.id}/common/sale-commissions/create`;

      //message during request
      let request_message = update_request
        ? `Updating sale commission details ...`
        : `Adding new sale commission details ...`;

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
          document.getElementById("dealSaleCommissionModalClose").click();
          toast.success(data.success);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            vm.getDealSaleCommissions(vm.$route.params.id);
          }
        })
        .catch(function (error) {
          let update_request =
            !isNaN(vm.deal_sale_commission_id) &&
            vm.deal_sale_commission_id != null;

          let error_message = update_request
            ? "There were errors while updating the sale commission details."
            : "There were some errors while adding sale commission details.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "deal",
        "sale_commission",
        "save"
      );
    },
    async deletionWarning(id) {
      const vm = this;
      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "No",
        confirmButtonText: "Yes",
        toast: false,
        position: "center",
        text: "Please confirm if you would like to delete the sale commission.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.deleteSaleCommission(id);
        }
      });
    },
    async deleteSaleCommission(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/deals/${vm.$route.params.id}/common/sale-commissions/delete/`;
      url += id;

      let message = "Deleting sale commission...";
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
          let success_message = `Sale commission has been deleted.`;
          toast.success(success_message);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            vm.getDealSaleCommissions(vm.$route.params.id);
          }
          //this.pagination(this.page);
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting sale commission.`;
          toast.fail(error_message);
          toast.close();
        });

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "deal",
        "sale_commission",
        "delete"
      );
    },
  },
  async created() {
    this.form.paid_date = moment(new Date()).format("MM/DD/YYYY");

    if (this.$route.params.id && this.$route.name == "deal-update") {
      await this.getDealSaleCommissions(this.$route.params.id);
      await this.getDealCompleteBreakDown(this.$route.params.id);
    }
  },
  async mounted() {
    const vm = this;
    jQuery("#dealSaleCommissionModal").on("hidden.bs.modal", function () {
      vm.resetForm();
    });

    if (this.$route.params.id && this.$route.name == "deal-update") {
      Event.listen("refresh_complete_break_down", (data) => {
        this.getDealCompleteBreakDown(this.$route.params.id);
      });
    }
  },
};
</script>
<style lang="scss">
#deal_sale_commission_paid_date .vdpComponent {
  width: 100%;
}
#deal_sale_commission_paid_date .vdpComponent input {
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