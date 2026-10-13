<template>
  <!-- BEGIN: Content-->
  <div class="app-content content" v-if="tenantApp">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-header row">
        <!-- first row : START -->

        <!-- Start of Summary : END -->
        <div class="col-xs-12 col-md-12 row">
          <h2 class="col-xs-12 col-md-8 d-inline-block">
            {{ getSummaryTitle }}
          </h2>
          <div
            class="col-xs-12 col-md-4 form-group d-inline-block text-right"
            style="width: auto"
          >
            <select
              class="custom-select form-control"
              v-model="timeperiod"
              id=""
              name="timeperiod"
              style="width: auto"
              @change="getSummary"
            >
              <option value="" selected disabled>Select Time Period</option>
              <option value="all">All</option>
              <option value="month">This Month</option>
              <option value="year">This Year</option>
            </select>
          </div>
          <div class="col-xl-2 col-md-4 col-sm-6" v-show="isAuthorized">
            <div class="card text-center border shadow-none">
              <div class="card-body">
                <div
                  class="badge-circle badge-circle-lg badge-circle-light-info mx-auto my-1"
                >
                  <i class="bx bxs-car font-medium-5"></i>
                </div>
                <p class="text-muted mb-0 line-ellipsis">Inventories
                  <br> <small> (All) </small>
                </p>
                <h6 class="mb-0">{{ summary.inventory_count }}</h6>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-4 col-sm-6" v-show="isAuthorized">
            <div class="card text-center border shadow-none">
              <div class="card-body">
                <div
                  class="badge-circle badge-circle-lg badge-circle-light-warning mx-auto my-1"
                >
                  <i class="bx bx-target-lock font-medium-5"></i>
                </div>
                <p class="text-muted mb-0 line-ellipsis">Leads
                  <br> <small> (All) </small>
                </p>
                <h6 class="mb-0">{{ summary.lead_responses_count }}</h6>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-4 col-sm-6" v-show="isAuthorized">
            <div class="card text-center border shadow-none">
              <div class="card-body">
                <div
                  class="badge-circle badge-circle-lg badge-circle-light-success mx-auto my-1"
                >
                  <i class="bx bxs-dollar-circle font-medium-5"></i>
                </div>
                <p class="text-muted mb-0 line-ellipsis">Deals
                  <br> <small> (All) </small>
                </p>
                <h6 class="mb-0">{{ summary.deals_count }}</h6>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-4 col-sm-6" v-show="isAuthorized">
            <div class="card text-center border shadow-none">
              <div class="card-body">
                <div
                  class="badge-circle badge-circle-lg badge-circle-light-secondary mx-auto my-1"
                >
                  <i class="bx bxs-car font-medium-5"></i
                  ><i class="bx bx-money font-medium-5"></i>
                </div>
                <p class="text-muted mb-0 line-ellipsis">Total Retail
                  <br> <small> (Visible Instock) </small>
                </p>
                <h6 class="mb-0">{{ summary.retail_total }}</h6>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-4 col-sm-6" v-show="isAuthorized">
            <div class="card text-center border shadow-none">
              <div class="card-body">
                <div
                  class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto my-1"
                >
                  <i class="bx bx-money font-medium-5"></i>
                </div>
                <p class="text-muted mb-0 line-ellipsis">Vehicle Cost  <br> <small> (Visible Instock) </small></p>
                <h6 class="mb-0">{{ summary.vehicle_purchases }}</h6>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-4 col-sm-6" v-show="isAuthorized">
            <div class="card text-center border shadow-none">
              <div class="card-body">
                <div
                  class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto my-1"
                >
                  <i class="bx bx-money font-medium-5"></i>
                </div>
                <p class="text-muted mb-0 line-ellipsis">Additional Expenses
                  <br> <small> (Visible Instock) </small></p>
                <h6 class="mb-0">{{ summary.expenses }}</h6>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-4 col-sm-6 d-none" v-show="isAuthorized">
            <div class="card text-center border shadow-none">
              <div class="card-body">
                <div
                  class="badge-circle badge-circle-lg badge-circle-light-success mx-auto my-1"
                >
                  <i class="bx bx-money font-medium-5"></i>
                </div>
                <p class="text-muted mb-0 line-ellipsis">Vehicle Sold</p>
                <h6 class="mb-0">{{ summary.vehicle_sales }}</h6>
              </div>
            </div>
          </div>
          <div class="col-xl-2 col-md-4 col-sm-6 d-none" v-show="isAuthorized">
            <div class="card text-center border shadow-none">
              <div class="card-body">
                <div
                  class="badge-circle badge-circle-lg badge-circle-light-info mx-auto my-1"
                >
                  <i class="bx bxs-pie-chart-alt font-medium-5"></i>
                </div>
                <p class="text-muted mb-0 line-ellipsis">Profit Loss</p>
                <h6 class="mb-0">{{ summary.vehicle_profit_loss }}</h6>
              </div>
            </div>
          </div>
        </div>
        <!-- End of Summary : END -->

        <h2 class="col-xs-12 col-md-12">Historical Stats</h2>

        <!-- first row : END -->
        <div class="col-xs-12 col-md-4">
          <div class="card border shadow-none">
            <div
              class="card-header d-flex justify-content-between align-items-center pb-50"
            >
              <h5 class="card-title">Inventory Stats</h5>
            </div>
            <div class="card-content">
              <div class="card-body p-0 pb-1 ps ps--active-y">
                <ul class="list-group list-group-flush">
                  <doughnut
                    :chart-data="inventoryData"
                    v-if="inventory_status.length > 0"
                  ></doughnut>

                  <li
                    class="list-group-item border-0 d-flex align-items-center justify-content-between"
                  >
                    <div class="list-left d-flex">
                      <div class="list-icon mr-1">
                        <div class="avatar bg-rgba-info m-0">
                          <div class="avatar-content">
                            <i class="bx bxs-car text-info font-size-base"></i>
                          </div>
                        </div>
                      </div>
                      <div class="list-content">
                        <div style="padding-top: 0.35em;">
                          Total Inventories
                        </div>
                      </div>
                    </div>
                    <span>{{ totalInventories }}</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- <div class="w-100"></div> -->

        <div class="col-xs-12 col-md-4">
          <div class="card border shadow-none">
            <div
              class="card-header d-flex justify-content-between align-items-center pb-50"
            >
              <h5 class="card-title">Lead Stats</h5>
            </div>
            <div class="card-content">
              <div class="card-body p-0 pb-1 ps ps--active-y">
                <ul class="list-group list-group-flush">
                  <bar-chart
                    :chart-data="leadStatusData"
                    :options="barOpitons"
                    v-if="lead_source.length > 0"
                  ></bar-chart>

                  <li
                    class="list-group-item border-0 d-flex align-items-center justify-content-between"
                  >
                    <div class="list-left d-flex">
                      <div class="list-icon mr-1">
                        <div class="avatar bg-rgba-warning m-0">
                          <div class="avatar-content">
                            <i
                              class="bx bx-target-lock text-warning font-size-base"
                            ></i>
                          </div>
                        </div>
                      </div>
                      <div class="list-content">
                        <div style="padding-top: 0.35em;">Total Leads</div>
                      </div>
                    </div>
                    <span>{{ totalLeads }}</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xs-12 col-md-4">
          <div class="card border shadow-none">
            <div
              class="card-header d-flex justify-content-between align-items-center pb-50"
            >
              <h5 class="card-title">Lead Sources</h5>
            </div>
            <div class="card-content">
              <div class="card-body p-0 pb-1 ps ps--active-y">
                <ul class="list-group list-group-flush">
                  <bar-chart
                    :chart-data="leadSourceData"
                    v-if="lead_source.length > 0"
                    :options="barOpitons"
                  ></bar-chart>
                  <li
                    class="list-group-item border-0 d-flex align-items-center justify-content-between"
                  >
                    <div class="list-left d-flex">
                      <div class="list-icon mr-1">
                        <div class="avatar bg-rgba-warning m-0">
                          <div class="avatar-content">
                            <i
                              class="bx bx-target-lock text-warning font-size-base"
                            ></i>
                          </div>
                        </div>
                      </div>
                      <div class="list-content">
                        <div style="padding-top: 0.35em;">Total Leads</div>
                      </div>
                    </div>
                    <span>{{ totalLeads }}</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- leads details : START -->
        <div class="col-xl-4 col-md-6 progress-card">
          <div class="card border shadow-none">
            <div
              class="card-header border-bottom d-flex justify-content-between align-items-center pr-2"
            >
              <h5 class="card-title">Leads Recieved</h5>
            </div>
            <div class="table-responsive">
              <table class="table table-borderless">
                <tbody>
                  <tr>
                    <td class="w-25">Last 7 Days</td>
                    <td class="w-25 text-right">{{ lead_dis_counts["8"] }}</td>
                  </tr>
                  <tr>
                    <td class="w-25">Last 30 Days</td>
                    <td class="w-25 text-right">{{ lead_dis_counts["31"] }}</td>
                  </tr>
                  <tr>
                    <td class="w-25">Last 90 Days</td>
                    <td class="w-25 text-right">{{ lead_dis_counts["91"] }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- leads details : END -->

        <!-- inventories details : START -->
        <div class="col-xl-4 col-md-6 progress-card">
          <div class="card border shadow-none">
            <div
              class="card-header border-bottom d-flex justify-content-between align-items-center pr-2"
            >
              <h5 class="card-title">Inventory Added</h5>
            </div>
            <div class="table-responsive">
              <table class="table table-borderless">
                <tbody>
                  <tr>
                    <td class="w-25">Last 7 Days</td>
                    <td class="w-25 text-right">
                      {{ inventory_dis_counts["8"] }}
                    </td>
                  </tr>
                  <tr>
                    <td class="w-25">Last 30 Days</td>
                    <td class="w-25 text-right">
                      {{ inventory_dis_counts["31"] }}
                    </td>
                  </tr>
                  <tr>
                    <td class="w-25">Last 90 Days</td>
                    <td class="w-25 text-right">
                      {{ inventory_dis_counts["91"] }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- inventories details : END -->

        <!-- activities : START -->
        <div class="col-lg-7">
          <div class="row">
            <div class="col-12">
              <div class="card widget-todo border shadow-none">
                <div
                  class="card-header border-bottom d-flex justify-content-between align-items-center flex-wrap"
                >
                  <h5 class="card-title d-flex mb-25 mb-sm-0">
                    <i class="bx bx-check font-medium-5 pl-25 pr-75"></i>Today's
                    Activities
                  </h5>
                  <ul
                    class="list-inline d-flex p-0 m-0"
                    v-if="bulk_select.length > 0"
                  >
                    <li class="d-flex align-items-center">
                      <div class="dropdown">
                        <button
                          class="btn btn-link p-0"
                          type="button"
                          id="dropDownMenu"
                          data-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          <i
                            class="bx bx-dots-vertical-rounded font-medium-3 cursor-pointer"
                          ></i>
                        </button>
                        <div
                          class="dropdown-menu"
                          aria-labelledby="dropDownMenu"
                        >
                          <a
                            class="dropdown-item"
                            @click="applyBulkOptions('Completed')"
                            >Mark Completed</a
                          >
                          <!-- <a class="dropdown-item" href="#">Another action</a>
                          <a class="dropdown-item" href="#">Something else here</a> -->
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="card-body px-0 py-1">
                  <ul class="widget-todo-list-wrapper">
                    <li
                      class="widget-todo-item"
                      v-for="activity in activities"
                      :key="activity.id"
                    >
                      <div
                        class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50"
                      >
                        <div class=" d-flex align-items-center">
                          <div
                            class="checkbox"
                            :style="[
                              activity.status === 'Completed'
                                ? { visibility: 'hidden' }
                                : {},
                            ]"
                          >
                            <input
                              type="checkbox"
                              :value="activity.id"
                              v-model="bulk_select"
                              :id="'activity_' + activity.id"
                              class="checkbox__input"
                            />
                            <label :for="'activity_' + activity.id"></label>
                          </div>
                          <span class="ml-50">
                            <i
                              class="bx bxs-time-five pr-50"
                              style="position:relative;top:2px;"
                            ></i>
                            <span class="d-inline">
                              {{
                                convertToTimeZone(
                                  activity.start_date,
                                  "hh:mm A"
                                )
                              }}</span
                            >
                            {{ activity.title }}
                          </span>
                        </div>
                        <div
                          class="widget-todo-item-action d-flex align-items-center"
                        >
                          <div
                            class="badge badge-pill mr-1"
                            :class="changeBadgeClass(activity.status)"
                          >
                            {{ activity.status }}
                          </div>
                          <i
                            class="bx bxs-flag-alt pl-50"
                            :class="changePriorityClass(activity.priority)"
                          ></i>
                          <div class="dropdown d-none">
                            <span
                              class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer icon-light"
                              data-toggle="dropdown"
                              aria-haspopup="true"
                              aria-expanded="false"
                              role="menu"
                            ></span>
                            <div
                              class="dropdown-menu dropdown-menu-right"
                              style=""
                            >
                              <a
                                class="dropdown-item"
                                href="javascript:void(0);"
                                >View Details</a
                              >
                              <a
                                class="dropdown-item"
                                href="javascript:void(0);"
                                >Duplicate Task</a
                              >
                              <a
                                class="dropdown-item"
                                href="javascript:void(0);"
                                >Delete Task</a
                              >
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- activities : END -->
        <facebook-campaign-summary></facebook-campaign-summary>
      </div>
    </div>
    <!-- END: Content-->
  </div>
</template>

<script>
import Doughnut from "./Charts/Doughnut";
import BarChart from "./Charts/BarChart";
import Toast from "../library/Toast";
import moment from "moment";
import FacebookCampaignSummary from "./WebComponents/FacebookCampaignSummary";

export default {
  props: {
    application: { required: true },
    app_metadata: { required: true },
    user_metadata: { required: true },
    format: moment().format("YYYY-MM-DD"),
  },
  name: "App",
  components: {
    Doughnut,
    BarChart,
    FacebookCampaignSummary
  },
  data() {
    return {
      barOpitons: {
        indexAxis: "y",
        legend: {
          display: false,
        },
      },
      inventory_status: {},
      summary: {
        inventory_count: 0,
        lead_responses_count: 0,
        deals_count: 0,
        expenses: 0,
        vehicle_purchases: 0,
        vehicle_sales: 0,
        vehicle_profit_loss: 0,
        retail_total: 0,
      },
      timeperiod: "all",
      inventory_dis_counts: {
        "8": 0,
        "31": 0,
        "91": 0,
      },
      lead_dis_counts: {
        "8": 0,
        "31": 0,
        "91": 0,
      },
      lead_status: {},
      lead_source: {},
      activities: [],
      bulk_select: [],
    };
  },
  computed: {
    tenantApp() {
      return this.application == "tenant";
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    getSummaryTitle() {
      switch (this.timeperiod) {
        case "all":
          return "Overall Summary";
          break;
        case "month":
          return "This Month's Summary";
          break;

        case "year":
          return "This Year's Summary";
          break;
        default:
          return "Overall Summary";
          break;
      }
    },
    inventoryData() {
      if (this.inventory_status.length > 0) {
        return {
          datasets: [
            {
              data: this.inventory_status.map((x) => parseInt(x.count, 10)),
              backgroundColor: [
                "#FFDC5E",
                "#FFBF81",
                "#FFA3A5",
                "#FF86C8",
                "#3E78B2",
                "#4A525A",
                "#B0413E",
                "#473335",
                "#735D78",
                "#F7D1CD",
              ],
            },
          ],
          labels: this.inventory_status.map((x) => x.name),
        };
      }
    },
    leadStatusData() {
      if (this.lead_status.length > 0) {
        return {
          datasets: [
            {
              data: this.lead_status.map((x) => parseInt(x.count, 10)),
              backgroundColor: [
                "#FFDC5E",
                "#FFBF81",
                "#FFA3A5",
                "#FF86C8",
                "#3E78B2",
                "#4A525A",
                "#B0413E",
                "#473335",
                "#735D78",
                "#F7D1CD",
              ],
            },
          ],
          labels: this.lead_status.map((x) => x.name),
        };
      }
    },
    leadSourceData() {
      if (this.lead_source.length > 0) {
        return {
          datasets: [
            {
              axis: "y",
              data: this.lead_source.map((x) => parseInt(x.count, 10)),
              backgroundColor: [
                "#FFDC5E",
                "#FFBF81",
                "#FFA3A5",
                "#FF86C8",
                "#3E78B2",
                "#4A525A",
                "#B0413E",
                "#473335",
                "#735D78",
                "#F7D1CD",
              ],
            },
          ],
          labels: this.lead_source.map((x) => x.name),
        };
      }
    },
    totalInventories() {
      let total = 0;
      for (const key in this.inventory_status) {
        if (this.inventory_status.hasOwnProperty(key)) {
          const element = this.inventory_status[key];
          console.log(element);
          total = element.hasOwnProperty("count")
            ? total + parseInt(element["count"])
            : total;
        }
      }

      return total;
    },
    totalLeads() {
      let total = 0;
      for (const key in this.lead_status) {
        if (this.lead_status.hasOwnProperty(key)) {
          const element = this.lead_status[key];
          console.log(element);
          total = element.hasOwnProperty("count")
            ? total + parseInt(element["count"])
            : total;
        }
      }

      return total;
    },
  },
  methods: {
    applyBulkOptions(status = "Completed") {
      const vm = this;
      let formData = new FormData();
      formData.append("activity_ids", this.bulk_select);
      formData.append("status", status);

      let message = `Updating selected activities...`;
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/activities/updates`,
      })
        .then(({ data }) => {
          vm.$data.bulk_select = [];
          toast.success(data.success);
          toast.close();
          vm.getActivitiesToday();
        })
        .catch(function(error) {
          toast.success(error.response.data);
          toast.close();
        });
    },
    convertToTimeZone(date, format) {
      return this.$helpers.changeUTCtoLocalDate(
        date,
        format,
        this.timeZoneName
      );
    },
    changePriorityClass(priority) {
      switch (priority) {
        case "High":
          return "text-danger";
          break;

        case "Moderate":
          return "text-warning";
          break;

        case "Normal":
          return "text-success";
          break;

        case "Low":
          return "text-secondary";
          break;

        default:
          return "text-secondary";
          break;
      }
    },
    changeBadgeClass(status) {
      console.log(status);
      switch (status) {
        case "Pending":
          return "badge-light-success";
          break;

        case "Started":
          return "badge-light-warning";
          break;

        case "Completed":
          return "badge-light-secondary";
          break;

        case "Cancelled":
          return "badge-light-danger";
          break;

        default:
          return "badge-light-primary";
          break;
      }
    },
    getInventoryCounts() {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin + `/api/website/inventory/filters?status=true`,
      })
        .then(({ data }) => {
          this.inventory_status = data.status;
        })
        .catch(() => {});
    },
    getLeadResponses() {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + "/api/business/leads/responses/filters",
      })
        .then(({ data }) => {
          this.lead_status = data.status;
          this.lead_source = data.source;
        })
        .catch(() => {});
    },
    getInventoryWithRecentStockDateCount(count) {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/website/inventory?zm_recent_stock_date=${count}`,
      })
        .then(({ data }) => {
          this.inventory_dis_counts[count] = data.pager.total;
        })
        .catch(() => {});
    },
    getLeadsInPastDays(count) {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          `/api/business/leads/responses?zm_dis=${count}`,
      })
        .then(({ data }) => {
          this.lead_dis_counts[count] = data.pager.total;
        })
        .catch(() => {});
    },
    getSummary() {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          `/api/business/dashboard?timeperiod=${this.timeperiod}`,
      })
        .then(({ data }) => {
          this.summary["inventory_count"] = data.inventory_count;
          this.summary["lead_responses_count"] = data.lead_responses_count;
          this.summary["deals_count"] = data.deals_count;
          this.summary["expenses"] = this.$helpers.moneyFormatter(
            data.expenses
          );
          this.summary["vehicle_purchases"] = this.$helpers.moneyFormatter(
            data.vehicle_purchases
          );
          this.summary["vehicle_sales"] = this.$helpers.moneyFormatter(
            data.vehicle_sales
          );
          this.summary["vehicle_profit_loss"] = this.$helpers.moneyFormatter(
            data.vehicle_profit_loss
          );
          this.summary["retail_total"] = this.$helpers.moneyFormatter(
            data.retail_total
          );
        })
        .catch(() => {});
    },
    async getActivitiesToday() {
      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          `/api/business/activities?only_today=true&paginate=false&column=start_date&order=ASC&zm_assignee=${this.app_metadata.id}`,
      })
        .then(({ data }) => {
          this.activities = data.activity_listing;
        })
        .catch(() => {});
    },
  },
  created() {
    const vm = this;
    moment.tz.setDefault(this.timeZoneName);
  },
  mounted() {
    if (this.tenantApp) {
      this.getInventoryCounts();
      this.getLeadResponses();
      this.getInventoryWithRecentStockDateCount(8);
      this.getInventoryWithRecentStockDateCount(31);
      this.getInventoryWithRecentStockDateCount(91);
      this.getLeadsInPastDays(8);
      this.getLeadsInPastDays(31);
      this.getLeadsInPastDays(91);
      this.getActivitiesToday();
      this.getSummary();
    }
  },
};
</script>

<style>
thead tr:first-child th {
  position: sticky;
  top: 0;
  background: white;
  z-index: 2;
}

.border-line-bottom-title {
  background-color: #f9f7f7;
}
</style>
