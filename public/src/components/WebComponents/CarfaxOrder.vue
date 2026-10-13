<template>
  <div class="row">
    <div class="col-12">
      <div class="card shadow-none mt-1 mb-0">
        <div class="card-header border-line-bottom-title">
          <h4 class="card-title">Carfax History Report Order</h4>
        </div>
        <div
          v-if="show_request_button"
          class="alert alert-light-success alert-dismissible fade show"
          role="alert"
        >
          👋 Good news! Zop has partnered with Carfax Canada, allowing you to
          purchase and showcase Carfax reports directly from Zop in just a few
          clicks.
          <a
            type="button"
            data-toggle="modal"
            data-target="#carfaxRequestModal"
          >
            Click to request the integration.
          </a>
        </div>
        <div class="card-content" v-show="vin && is_available">
          <div class="card-body pt-0 pb-0">
            <form
              class="form form-horizontal webapp-form"
              v-show="current_step === 0"
            >
              <div class="form-body">
                <div class="form-row">
                  <div class="col-xs-12 col-md-12 h4">
                    Do you want to look up for the Vehicle History Report for
                    {{ vin }} ?
                    <button
                      :disabled="is_loading"
                      @click.prevent="lookUp"
                      class="btn btn-primary text-white mx-1"
                    >
                      <i class="bx bx-search mr-50"></i> <span>Lookup</span>
                    </button>
                  </div>
                </div>
              </div>
            </form>
            <form
              class="form form-horizontal webapp-form"
              v-show="current_step === 1"
              v-if="isAuthorized"
            >
              <div class="form-body">
                <h4 class="col-xs-12">Order New Report for {{ vin }}</h4>
                <div class="form-row">
                  <!-- report_type : START -->
                  <div class="form-group col-md-4">
                    <label for="carfax_order_report_type">Report Type</label>
                    <select
                      class="custom-select form-control mr-1"
                      v-model="form.report_type"
                    >
                      <option value="" selected disabled>Report Type</option>
                      <option
                        :key="`report_type_${report_type}`"
                        :value="index"
                        v-for="(report_type, index) in getCarfaxReportTypes"
                      >
                        {{ report_type }}
                      </option>
                    </select>
                  </div>
                  <!-- report_type : END -->
                  <div class="w-100"></div>
                  <!-- lien_express_province : START -->
                  <div
                    class="form-group col-md-4"
                    v-show="form.report_type == '6'"
                  >
                    <label for="carfax_order_lien_express_province"
                      >Lien Express Province</label
                    >
                    <select
                      class="custom-select form-control mr-1"
                      v-model="form.lien_express_province"
                    >
                      <option value="" selected disabled>Province</option>
                      <option value="AB">Alberta</option>
                      <option value="BC">British Columbia</option>
                      <option value="MB">Manitoba</option>
                      <option value="NB">New Brunswick</option>
                      <option value="NL">Newfoundland and Labrador</option>
                      <option value="NS">Nova Scotia</option>
                      <option value="ON">Ontario</option>
                      <option value="PE">Prince Edward Island</option>
                      <option value="QC">Quebec</option>
                      <option value="SK">Saskatchewan</option>
                    </select>
                  </div>
                  <!-- lien_express_province : END -->
                  <div class="w-100"></div>
                  <button
                    :disabled="is_loading"
                    class="btn btn-primary text-white mx-1"
                    @click.prevent="warningForCarfaxOrder"
                  >
                    <i class="bx bxs-basket mr-50"></i> <span>Order</span>
                  </button>
                </div>
              </div>
            </form>
            <h5 class="col-12 text-danger" style="font-size: 0.9em">
              <strong
                >Note: If the report is pending, refresh the page and click
                Search again. Placing another order may result in additional
                charges.</strong
              >
            </h5>
            <div class="row">
              <div class="table-responsive" style="min-height: 300px">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th scope="col">STOCK NO</th>
                      <th scope="col">ACTION</th>
                      <th scope="col">STATUS</th>
                      <th scope="col">USER</th>
                      <th scope="col">DATE</th>
                      <th scope="col"></th>
                      <th class="text-center" scope="col">SHOW ON WEBSITE</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="carfax_order_log in carfax_order_log_listing"
                      :key="`carfax_log_${carfax_order_log.id}`"
                    >
                      <td>
                        {{ carfax_order_log.stock_no.toUpperCase() }}
                      </td>
                      <td>
                        <span
                          class="badge badge-light-primary"
                          v-if="carfax_order_log.action === 'ORDER'"
                          >{{ carfax_order_log.action }}</span
                        >
                        <span class="badge badge-light-secondary" v-else>{{
                          carfax_order_log.action
                        }}</span>
                      </td>
                      <td>
                        <span
                          class="badge badge-light-success"
                          v-if="carfax_order_log.status === 'SUCCESS'"
                          >{{ carfax_order_log.status }}</span
                        >
                        <span class="badge badge-light-danger" v-else>{{
                          carfax_order_log.status
                        }}</span>
                      </td>
                      <td>{{ userName(carfax_order_log.user) }}</td>
                      <td>{{ formatDate(carfax_order_log.created_at) }}</td>
                      <td>
                        <div class="btn-group">
                          <button
                            class="btn btn-secondary btn-sm dropdown-toggle"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            role="menu"
                          >
                            View Reports
                          </button>
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
                              :key="`carfax_level_1_dropdown_${index}${carfax_order_log.id}`"
                              class="dropdown-item"
                              style="cursor: pointer"
                              v-for="(
                                current_report_object, index
                              ) in getReportList(carfax_order_log.response)"
                              :href="current_report_object.url"
                              target="_blank"
                            >
                              {{ current_report_object.label }}
                            </a>
                          </div>
                        </div>
                      </td>
                      <td class="text-center">
                        <div
                          class="dropdown"
                          v-if="
                            getReportListAvailableToShowOnWebsite(
                              carfax_order_log.response
                            ).length > 0
                          "
                        >
                          <button
                            class="btn btn-primary btn-sm dropdown-toggle"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            role="menu"
                          >
                            Choose For Website
                          </button>
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
                              :key="`action_dropdown_${index}${carfax_order_log.id}`"
                              class="dropdown-item"
                              style="cursor: pointer"
                              v-for="(
                                current_report_object, index
                              ) in getReportListAvailableToShowOnWebsite(
                                carfax_order_log.response
                              )"
                              @click.prevent="
                                updateVehicleHistoryReport(
                                  current_report_object.url
                                )
                              "
                            >
                              {{ current_report_object.label }}
                            </a>
                          </div>
                        </div>
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
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import Toast from "../../library/Toast";
export default {
  props: {
    vin: {
      type: String,
      required: true,
      default: "",
    },
    stock_no: {
      type: String,
      required: false,
      default: "",
    },
    app_metadata: { required: true },
  },
  name: "CarfaxOrder",
  data() {
    return {
      is_loading: false,
      is_available: false,
      show_request_button: false,
      carfax_order_log_listing: [],
      current_step: 0,
      form: {
        report_type: "4",
        lien_express_province: "",
      },
      errors: [],
    };
  },
  watch: {
    vin: function (newVal, oldVal) {
      if (newVal !== oldVal) {
        if (newVal !== "") {
          this.getAllLogs();
        }
      }
    },
  },
  computed: {
    ...mapGetters("users", ["allUsers"]),
    getCarfaxReportTypes() {
      return {
        2: "VHR + Lien check",
        3: "VHR BC + Lien check",
        4: "VHR",
        5: "National Lien Check",
        6: "Lien Express (By Province)",
        9: "VHR BC",
      };
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role, [
        "Owner",
        "Manager",
      ]);
    },
  },
  methods: {
    ...mapActions("users", ["getAllUsers"]),
    userName(user) {
      return this.$helpers.assigneeName(this.allUsers, user, "");
    },
    getReportList(response) {
      let report_list = [];

      for (const key in response) {
        if (["VhrReportHideLiensUrl", "VhrReportUrl"].includes(key)) {
          if (response[key]) {
            let report_number = response["ReportNumber"];
            let label = key
              .split(/(?=[A-Z])/)
              .join(" ")
              .replace("Link", "")
              .replace("Report", "")
              .replace("Url", "");
            let report_based_class =
              key === "VhrReportHideLiensUrl"
                ? "btn-outline-primary"
                : "btn-outline-danger";

            if (response[key] != "No report available") {
              report_list.push({
                label: `${label} #${report_number}`,
                url: response[key],
                class: report_based_class,
              });
            }
          }
        }
      }

      if (response.hasOwnProperty("Reports")) {
        for (const report_object_index in response["Reports"]) {
          const current_report_object =
            response["Reports"][report_object_index];
          let report_prefix =
            this.getCarfaxReportTypes[current_report_object["ReportType"]];

          for (const current_report_object_key in current_report_object) {
            if (current_report_object[current_report_object_key]) {
              if (
                ["ReportLinkUrl", "ReportLinkHideLienUrl"].includes(
                  current_report_object_key
                )
              ) {
                let report_number = current_report_object["ReportNumber"];
                let label = current_report_object_key
                  .split(/(?=[A-Z])/)
                  .join(" ")
                  .replace("Link", "")
                  .replace("Report", "")
                  .replace("Url", "");

                let report_based_class =
                  current_report_object_key === "ReportLinkHideLienUrl"
                    ? "btn-outline-primary"
                    : "btn-outline-danger";

                if (
                  current_report_object[current_report_object_key] !=
                  "No report available"
                ) {
                  report_list.push({
                    label: `${report_prefix} ${label} #${report_number}`,
                    url: current_report_object[current_report_object_key],
                    class: report_based_class,
                  });
                }
              }
            }
          }
        }
      }

      return report_list;
    },
    getReportListAvailableToShowOnWebsite(response) {
      let report_list = [];

      for (const key in response) {
        if (["VhrReportHideLiensUrl"].includes(key)) {
          if (response[key]) {
            let report_number = response["ReportNumber"];
            let label = key
              .split(/(?=[A-Z])/)
              .join(" ")
              .replace("Link", "")
              .replace("Report", "")
              .replace("Url", "");
            if (response[key] != "No report available") {
              report_list.push({
                label: `${label} #${report_number}`,
                url: response[key],
              });
            }
          }
        }
      }

      if (response.hasOwnProperty("Reports")) {
        for (const report_object_index in response["Reports"]) {
          const current_report_object =
            response["Reports"][report_object_index];
          let report_prefix =
            this.getCarfaxReportTypes[current_report_object["ReportType"]];

          for (const current_report_object_key in current_report_object) {
            if (current_report_object[current_report_object_key]) {
              if (
                ["ReportLinkHideLienUrl"].includes(current_report_object_key)
              ) {
                let report_number = current_report_object["ReportNumber"];
                let label = current_report_object_key
                  .split(/(?=[A-Z])/)
                  .join(" ")
                  .replace("Link", "")
                  .replace("Report", "")
                  .replace("Url", "");
                if (
                  current_report_object[current_report_object_key] !=
                  "No report available"
                ) {
                  report_list.push({
                    label: `${report_prefix} ${label} #${report_number}`,
                    url: current_report_object[current_report_object_key],
                  });
                }
              }
            }
          }
        }
      }

      return report_list;
    },
    lookUp() {
      const vm = this;
      this.is_loading = true;
      const toast = new Toast(
        `Looking up for history reports of ${this.vin}...`
      );
      toast.fire();
      let url = `${window.location.origin}/api/business/carfax/reports/lookup`;

      let formData = new FormData();
      formData.append("vin", this.vin);
      formData.append("stock_no", this.stock_no);

      axios({
        method: "post",
        url: url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          this.is_loading = false;
          this.current_step = 1;
          this.getAllLogs();
          toast.success(`Search complete.`);
          toast.close();
        })
        .catch((error) => {
          this.is_loading = false;
          let error_message = error.response.data.errors;
          toast.fail(error_message);
          toast.close();
          this.getAllLogs();
        });
    },
    orderReport() {
      const vm = this;
      this.is_loading = true;
      const toast = new Toast(
        `Ordering the history report of ${this.vin} on Carfax...`
      );
      toast.fire();
      let url = `${window.location.origin}/api/business/carfax/reports/order`;

      let formData = new FormData();
      formData.append("vin", this.vin);
      formData.append("stock_no", this.stock_no);
      formData.append("report_type", this.form.report_type);
      formData.append("lien_express_province", this.form.lien_express_province);

      axios({
        method: "post",
        url: url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          this.is_loading = false;
          this.current_step = 0;
          this.getAllLogs();
          toast.success(`Order has been completed.`);
          toast.close();
        })
        .catch((error) => {
          this.is_loading = false;
          let error_message = error.response.data.errors;
          toast.fail(error_message);
          toast.close();
          this.getAllLogs();
        });
    },
    async warningForCarfaxOrder() {
      const vm = this;

      let message = `<h4>You are about to purchase a <b>${
        this.getCarfaxReportTypes[this.form.report_type]
      }</b> report`;

      if (this.form.lien_express_province !== "") {
        message = `${message} from region <b>${this.form.lien_express_province}</b>.`;
      }

      message = `${message} for <b>${this.vin}</b> from Carfax. You will be charged accordingly by Carfax.</h4>`;

      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Back",
        confirmButtonText: "Yes",
        toast: false,
        position: "center",
        html: message,
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.orderReport();
        }
      });
    },
    getAllLogs() {
      let url = `${window.location.origin}/api/business/carfax/reports/logs?zm_vin[]=${this.vin}&paginate=false`;
      axios({
        method: "get",
        url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          if (data.carfax_order_log_listing.length > 0) {
            for (const index in data.carfax_order_log_listing) {
              if (
                this.$helpers.isValidJsonString(
                  data.carfax_order_log_listing[index].response
                )
              ) {
                data.carfax_order_log_listing[index].response = JSON.parse(
                  data.carfax_order_log_listing[index].response
                );
              } else {
                data.carfax_order_log_listing[index].response = {};
              }
            }
          }

          this.carfax_order_log_listing = data.carfax_order_log_listing;
        })
        .catch(() => {
          console.log("Some error in fetching the carfax logs");
        });
    },
    getCarfaxAvailability() {
      let url = `${window.location.origin}/api/business/carfax/reports/available`;
      axios({
        method: "get",
        url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.is_available = data.is_available;
          if (!this.is_available) {
            this.show_request_button = true;
          }
        })
        .catch(() => {
          this.is_available = false;
          this.show_request_button = true;
        });
    },
    updateVehicleHistoryReport(report_url) {
      Event.fire("vehicle_history_report_update", report_url);
    },
    formatDate(date) {
      if (moment(date).isValid()) {
        return moment(date).format("MM/DD/YYYY");
      } else {
        return "";
      }
    },
  },
  created() {
    this.getCarfaxAvailability();
    this.getAllUsers();
  },
  beforeDestroy() {
    Event.vue.$off("vehicle_history_report_update", null);
  },
};
</script>
<style lang="scss">
.alert-light-success {
  background-color: #e6f4ea;
  border: 1px solid #28c76f;
  color: #155724;
}
</style>