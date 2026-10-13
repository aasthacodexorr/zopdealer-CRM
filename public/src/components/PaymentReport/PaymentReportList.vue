<template>
  <!-- BEGIN: Content-->
  <div class="app-content content" v-if="isAuthorized">
    <div class="content-overlay"></div>
    <!-- form layout section start -->
    <div class="content-body" id="payment-report-section">
      <form class="webapp-form">
        <section id="table-transactions">
          <div
            class="border-bottom mt-3 bg-white header-navbar set-dable-layer"
            style="background-color: transparent"
          >
            <div class="content-wrapper pt-0 pb-1 mt-0">
              <div class="card shadow-none mb-0">
                <div class="row">
                  <div class="d-none d-sm-none d-md-block col-md-3">
                    <ul class="ml-0 mt-1 list-group list-group-horizontal">
                      <li class="d-inline-block mr-1">
                        <div
                          class="btn-group mb-0 drop-value-set list-size-fillter"
                        >
                          <div class="dropdown">
                            <div class="dropdown">
                              <button
                                class="btn btn-outline-light dropdown-toggle mr-1"
                                type="button"
                                id="dropdownMenuButton"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                {{ selected_payment_view }}
                              </button>
                              <div
                                class="dropdown-menu"
                                aria-labelledby="dropdownMenuButton"
                              >
                                <p class="border-bottom">
                                  <strong>Views</strong>
                                </p>
                                <p class="border-bottom border-top">
                                  <strong>Default Views</strong>
                                </p>
                                <a
                                  style="cursor: pointer"
                                  class="dropdown-item dropdown-item d-flex justify-content-between flex-row"
                                  v-for="payment_view in getDefaultViews"
                                  :key="payment_view.id"
                                  ><span
                                    class="d-inline-block flex-fill"
                                    @click="changePaymentView(payment_view.id)"
                                    >{{
                                      payment_view.header | truncate(25, "...")
                                    }}</span
                                  ><router-link
                                    tag="td"
                                    class="d-inline-block"
                                    :to="{
                                      name: 'payment-report-view-update',
                                      params: { id: payment_view.id },
                                    }"
                                    exact
                                    :key="`payment_report_view_${payment_view.id}`"
                                  >
                                    <i
                                      class="bx bx-edit-alt smart-view-edit-action"
                                    ></i> </router-link
                                ></a>

                                <p class="border-bottom border-top">
                                  <strong>Custom Views</strong>
                                </p>
                                <span
                                  style="
                                    max-height: 200px;
                                    overflow-y: auto;
                                    display: inline-block;
                                    width: 100%;
                                  "
                                >
                                  <a
                                    style="cursor: pointer"
                                    class="dropdown-item dropdown-item d-flex justify-content-between flex-row"
                                    v-for="payment_view in getCustomViews"
                                    :key="payment_view.id"
                                    ><span
                                      class="d-inline-block flex-fill"
                                      @click="
                                        changePaymentView(payment_view.id)
                                      "
                                      >{{
                                        payment_view.header
                                          | truncate(25, "...")
                                      }}</span
                                    ><router-link
                                      tag="td"
                                      class="d-inline-block"
                                      :to="{
                                        name: 'payment-report-view-update',
                                        params: { id: payment_view.id },
                                      }"
                                      exact
                                      :key="`payment_report_default_view_${payment_view.id}`"
                                    >
                                      <i
                                        class="bx bx-edit-alt smart-view-edit-action"
                                      ></i> </router-link
                                  ></a>
                                </span>
                                <p class="border-top">
                                  <router-link
                                    tag="a"
                                    :to="{ name: 'payment-report-view-create' }"
                                    class="btn btn-primary w-100 text-center"
                                    exact
                                  >
                                    + New Custom View</router-link
                                  >
                                </p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
                  <div
                    class="col-md-5 col-xs-12"
                    style="position: relative; top: -0.5em"
                  >
                    <div class="col-12"></div>
                  </div>
                  <div class="col-md-4 col-12">
                    <ul
                      class="ml-0 mt-1 list-group list-group-horizontal text-right"
                    >
                      <li class="d-inline-block">
                        <div
                          class="btn-feild-box"
                          style="top: 16px; right: 15px"
                        >
                          <ul>
                            <li class="d-inline-block">
                              <a
                                class="btn btn-primary text-white"
                                @click="redirectToSchedulePage"
                                style="cursor: pointer"
                                >{{ scheduleButtonLable }}</a
                              >
                            </li>
                            <li class="d-none d-sm-none d-md-inline-block">
                              <span
                                href="#"
                                :class="
                                  getSearch
                                    ? 'bg-secondary bx-tada text-white'
                                    : ''
                                "
                                class="list-space border bx bxs-filter-alt"
                                data-toggle="modal"
                                title="Filters"
                                data-target="#filters"
                              ></span>
                            </li>
                            <li class="d-none d-sm-none d-md-inline-block">
                              <span
                                href="#"
                                class="list-space border bx bxs-grid"
                                data-toggle="modal"
                                title="Modify View"
                                data-target="#grid-list"
                              ></span>
                            </li>
                            <li class="d-inline-block" @click="changeCompact">
                              <span
                                class="list-space border bx"
                                title="Compact View"
                                :class="
                                  getCompact ? 'bx-expand' : 'bx-collapse'
                                "
                              ></span>
                            </li>
                            <li class="d-none d-sm-none d-md-inline-block">
                              <div class="dropdown">
                                <span
                                  class="bx bx-export font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer list-space border"
                                  data-toggle="dropdown"
                                  aria-haspopup="true"
                                  title="Export Files"
                                  aria-expanded="false"
                                  role="menu"
                                ></span>
                                <div class="dropdown-menu dropdown-menu-right">
                                  <a class="dropdown-item" @click="downloadCSV"
                                    >Export List Into CSV</a
                                  >
                                  <a class="dropdown-item" @click="downloadXLS"
                                    >Export List Into XLS</a
                                  >
                                  <a
                                    class="dropdown-item d-none"
                                    @click="downloadPdf"
                                    >Print List Into PDF</a
                                  >
                                </div>
                              </div>
                            </li>
                          </ul>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--grid list modal -->
          <div
            class="modal fade text-left"
            id="grid-list"
            tabindex="-1"
            role="dialog"
            aria-labelledby="myModalLabel19"
            aria-hidden="true"
          >
            <div
              class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md"
              role="document"
            >
              <div class="modal-content">
                <div class="modal-header pl-1 pr-1">
                  <h3 class="content-header-title" id="myModalLabel19">
                    Column List
                  </h3>
                  <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                  >
                    <i class="bx bx-x"></i>
                  </button>
                </div>
                <div class="modal-body p-0">
                  <!-- Draggable List : OPEN -->
                  <draggable
                    tag="ul"
                    :list="list"
                    v-bind="dragOptions"
                    @start="isDragging = true"
                    @end="isDragging = false"
                    class="list-group"
                    @change="unsavedView"
                    handle=".handle"
                  >
                    <transition-group type="transition" name="flip-list">
                      <li
                        class="list-group-item"
                        v-for="(element, idx) in list"
                        :key="element.header"
                      >
                        <span
                          style="cursor: pointer"
                          class="bx bx-grid-vertical pr-1 handle"
                        ></span>
                        <div class="checkbox">
                          <input
                            type="checkbox"
                            @change="unsavedView"
                            v-model="element.visibility"
                            class="checkbox-input"
                            value="photodrag"
                            :id="'dragging_' + idx"
                            :true-value="'1'"
                            :false-value="'0'"
                          />
                          <label :for="'dragging_' + idx">{{
                            element.header
                          }}</label>
                        </div>
                      </li>
                    </transition-group>
                  </draggable>
                  <!-- Draggable List : CLOSE -->
                </div>
              </div>
            </div>
          </div>

          <!-- Filter Model : START -->
          <div
            class="modal fade text-left"
            id="filters"
            tabindex="-1"
            role="dialog"
            aria-labelledby="myModalLabel19"
            aria-hidden="true"
          >
            <div
              class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
              role="document"
            >
              <div class="modal-content" id="filterInventoryModel">
                <div class="modal-header pl-1 pr-1">
                  <h3 class="content-header-title">Filters</h3>
                  <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                  >
                    <i class="bx bx-x"></i>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <!-- type : START -->
                    <div class="col-md-5">
                      <label>Type</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <v-select
                        id="filter-type"
                        placeholder="Type"
                        v-model="typeFilter"
                        clearable
                        :options="getTypes"
                      >
                      </v-select>
                    </div>
                    <!-- type : END -->

                    <!-- status : START -->
                    <div class="col-md-5">
                      <label>Status</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <v-select
                        id="filter-status"
                        placeholder="Status"
                        v-model="statusFilter"
                        clearable
                        :options="getPaymentStatuses"
                      >
                      </v-select>
                    </div>
                    <!-- status : END -->

                    <!-- module : START -->
                    <div class="col-md-5">
                      <label>Module</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <v-select
                        id="filter-module"
                        placeholder="Module"
                        v-model="moduleFilter"
                        clearable
                        :options="getPaymentModules"
                      >
                      </v-select>
                    </div>
                    <!-- module : END -->

                    <!-- reason : START -->
                    <div class="col-md-5">
                      <label>Reason</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <v-select
                        id="fliter-reason"
                        placeholder="Reason"
                        v-model="reasonFilter"
                        clearable
                        :options="getReasons"
                      >
                      </v-select>
                    </div>
                    <!-- reason : END -->

                    <!-- amount filter : START -->
                    <div class="col-md-12">
                      <label>Amount Range</label>
                    </div>
                    <div class="col-md-6 form-group">
                      <input
                        type="number"
                        class="form-control"
                        v-model="amountStartFilter"
                      />
                    </div>
                    <div class="col-md-6 form-group">
                      <input
                        type="number"
                        class="form-control"
                        v-model="amountEndFilter"
                      />
                    </div>
                    <!-- amount filter  : END -->

                    <!-- date filter : START -->
                    <div class="col-md-12">
                      <label>Date Range</label>
                    </div>
                    <div class="col-md-6 form-group">
                      <fieldset
                        class="has-icon-left"
                        style="position: relative"
                      >
                        <date-picker
                          :format="'MM/DD/YYYY'"
                          :value-type="'MM/DD/YYYY'"
                          v-model="dateStartFilter"
                          style="border: 1px solid #eee"
                        >
                        </date-picker>
                      </fieldset>
                    </div>
                    <div class="col-md-6 form-group">
                      <fieldset
                        class="has-icon-left"
                        style="position: relative"
                      >
                        <date-picker
                          style="border: 1px solid #eee"
                          :format="'MM/DD/YYYY'"
                          :value-type="'MM/DD/YYYY'"
                          v-model="dateEndFilter"
                        >
                        </date-picker>
                      </fieldset>
                    </div>
                    <!-- date filter  : END -->

                    <div class="col-xs-12 p-1"></div>

                    <div class="col-sm-12 d-flex justify-content-left pb-1">
                      <button
                        type="close"
                        @click="filterPayment"
                        class="btn btn-primary mr-1 mt-1"
                        data-dismiss="modal"
                        aria-label="Close"
                      >
                        Apply Filters
                      </button>
                      <button
                        type="close"
                        @click="resetFilters"
                        class="btn btn-light-secondary mr-1 mt-1"
                        data-dismiss="modal"
                        aria-label="Reset"
                      >
                        Reset
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Filter Model : END -->

          <!-- TODO: End Here Change Bulk Grid -->
          <div class="pr-0 pl-0">
            <div class="content-body">
              <div class="card shadow-none mb-0">
                <div
                  class="table-responsive tab-wid check-bulk"
                  :class="getCompact ? 'compact' : ''"
                  style="height: 70vh"
                >
                  <table
                    id="table-extended-transactions"
                    class="list-page-content-p mt-0 mb-0"
                  >
                    <thead>
                      <tr class="bg-grey">
                        <template v-for="element in list">
                          <th
                            class="text-center"
                            style="min-width: 150px"
                            :style="{
                              'min-width':
                                element.header == 'EMAIL' ? '250px' : '150px',
                            }"
                            :key="element.header"
                            v-if="element.visibility == '1'"
                          >
                            <span
                              style="cursor: pointer"
                              class="btn btn-link p-0"
                              v-if="
                                getSortColumn.hasOwnProperty(element.column)
                              "
                            >
                              <span
                                @click="updateSortColumn(element.column, 'ASC')"
                                v-if="getSortColumn[element.column] == 'DESC'"
                                >{{ element.header }}
                                <i class="bx bx-sort-down"></i>
                              </span>
                              <span
                                @click="
                                  updateSortColumn(element.column, 'DESC')
                                "
                                v-else-if="
                                  getSortColumn[element.column] == 'ASC'
                                "
                                >{{ element.header }}
                                <i class="bx bx-sort-up"></i>
                              </span>
                            </span>
                            <span
                              style="cursor: pointer"
                              class="btn btn-link p-0"
                              @click="updateSortColumn(element.column, 'ASC')"
                              v-else
                              >{{ element.header }} <i class="bx bx-menu"></i>
                            </span>
                          </th>
                        </template>
                      </tr>
                    </thead>
                    <tbody>
                      <payment-report-item
                        v-for="item in getEntities"
                        :allUsers="allUsers"
                        :item="item"
                        :childCheck="bulk_select"
                        v-bind:key="item.id + '_item_listing_view'"
                      ></payment-report-item>
                    </tbody>
                  </table>
                </div>
                <!-- datatable ends -->

                <!-- Navigation Pagination : START -->
                <div class="row content-wrapper pt-0 mt-1">
                  <div class="col-sm-4">
                    <div
                      class="dataTables_info"
                      id="table-extended-transactions_info"
                      role="status"
                      aria-live="polite"
                    >
                      Total Records {{ getPager.total }}
                    </div>
                  </div>
                  <div class="col-sm-5 text-right d-list-box">
                    <div
                      class="dataTables_length"
                      id="table-extended-transactions_length"
                    >
                      <label>
                        <select
                          v-model="getNumberOfEntities"
                          name="table-extended-transactions_length"
                          aria-controls="table-extended-transactions"
                          class="custom-select custom-select-sm form-control form-control-sm"
                        >
                          <option value="10">10</option>
                          <option value="25">25</option>
                          <option value="50">50</option>
                          <option value="100">100</option>
                        </select>
                        per Page
                      </label>
                    </div>
                  </div>
                  <div class="col-sm-3 text-right">
                    <div
                      class="dataTables_paginate paging_simple_numbers"
                      id="table-extended-transactions_paginate"
                    >
                      <ul class="pagination">
                        <li
                          style="cursor: pointer; padding-right: 0.2em"
                          class="paginate_button page-item previous"
                          @click="paginationWithUrl(getPager.previous)"
                          :class="{ disabled: getPager.previous == null }"
                          id="table-extended-transactions_previous"
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
                          :class="{ active: n === getCurrentPage }"
                          v-show="
                            n >= getCurrentPage - 2 && n <= getCurrentPage + 2
                          "
                          v-for="n in getPager.pageCount"
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
                          :class="{ disabled: getPager.next == null }"
                          id="table-extended-transactions_next"
                        >
                          <a
                            aria-controls="table-extended-transactions"
                            @click="paginationWithUrl(getPager.next)"
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
                <!-- Navigation Pagination : END -->
              </div>
            </div>
          </div>
        </section>
        <!-- form layout section end -->
      </form>
    </div>
  </div>
  <!-- END: Content-->
</template>
<script>
import draggable from "vuedraggable";
import PaymentReportItem from "./PaymentReportItem";
import { mapGetters, mapActions } from "vuex";
import vSelect from "vue-select";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

import Toast from "../../library/Toast";

export default {
  props: ["application", "app_metadata"],
  name: "PaymentReportList",
  components: {
    PaymentReportItem,
    draggable,
    "v-select": vSelect,
    DatePicker,
  },
  data() {
    return {
      load: false,
      single_search_payment: null,
      page: 1,
      number_of_items: 25,
      bulk_select: "0",
      payment_heading: "Payment Reports",
      payment_listing: "",
      payments: [],
      pager: "",
      payment_views: [],
      payment_view_id: 1,
      dragging: true,
      payment_view_flag: 1,
      payment_view_default: [],
      sort_column: { created_at: "DESC" },
      form: {
        source: "",
        status: "",
      },
      current_schedules: [],
    };
  },
  watch: {
    single_search_payment: function (newVal, oldVal) {
      if (newVal.hasOwnProperty("id")) {
        if (!isNaN(newVal.id)) {
        }
      }
    },
    getViewId: function (newVal, oldVal) {
      if (newVal !== oldVal) {
        if (newVal) {
          this.currentSchedules(newVal);
        }
      }
    },
  },
  computed: {
    ...mapGetters("payment", [
      "getTypes",
      "getModes",
      "getPaymentStatuses",
      "getReasons",
      "getPaymentModules",
    ]),
    ...mapGetters("paymentReportView", [
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
      "getCompact",
      "getFilters",
    ]),
    ...mapGetters("users", ["allUsers"]),
    getDefaultViews() {
      return this.payment_views.filter((view) => view.default == "1");
    },
    getCustomViews() {
      return this.payment_views.filter((view) => view.default == "0");
    },
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost",
      };
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    selected_payment_view() {
      return this.getViewTitle.length > 12
        ? this.getViewTitle.substring(0, 12 - 3) + "..."
        : this.getViewTitle;
    },
    getNumberOfEntities: {
      get() {
        return this.getEntitiesCount;
      },
      set(value) {
        this.commitEntitiesCount(value);
        this.pagination();
      },
    },
    typeFilter: {
      get() {
        return this.getFilters.type;
      },
      set(value) {
        this.commitFilters({ name: "type", value: value });
      },
    },
    reasonFilter: {
      get() {
        return this.getFilters.reason;
      },
      set(value) {
        this.commitFilters({ name: "reason", value: value });
      },
    },
    moduleFilter: {
      get() {
        return this.getFilters.module;
      },
      set(value) {
        this.commitFilters({ name: "module", value: value });
      },
    },
    statusFilter: {
      get() {
        return this.getFilters.status;
      },
      set(value) {
        this.commitFilters({ name: "status", value: value });
      },
    },
    dateStartFilter: {
      get() {
        return this.getFilters.date_start;
      },
      set(value) {
        this.commitFilters({ name: "date_start", value: value });
      },
    },
    dateEndFilter: {
      get() {
        return this.getFilters.date_end;
      },
      set(value) {
        this.commitFilters({ name: "date_end", value: value });
      },
    },
    amountStartFilter: {
      get() {
        return this.getFilters.amount_start;
      },
      set(value) {
        this.commitFilters({ name: "amount_start", value: value });
      },
    },
    amountEndFilter: {
      get() {
        return this.getFilters.amount_end;
      },
      set(value) {
        this.commitFilters({ name: "amount_end", value: value });
      },
    },
    downloadFileName() {
      const date = new Date();

      const day = date.getDate();
      const month = date.getMonth() + 1;
      const year = date.getFullYear();

      let file_name = this.getViewTitle;
      file_name = `${file_name} ${year} ${month} ${day}`;
      file_name = file_name.toLowerCase().replace(/[^A-Z0-9]/gi, "_");

      return file_name;
    },
    scheduleButtonLable() {
      return this.current_schedules.length > 0
        ? "Update Schedule"
        : "Set Schedule";
    },
  },
  methods: {
    ...mapActions("payment", ["getPaymentConfigurations"]),
    ...mapActions("paymentReportView", [
      "updateView",
      "commitViewId",
      "commitViewTitle",
      "commitViewEntities",
      "commitViewPager",
      "commitSortColumn",
      "commitLoadState",
      "commitEntitiesCount",
      "commitCurrentPage",
      "commitRequestUrl",
      "commitSearchState",
      "commitSearchString",
      "commitCompactFlag",
      "commitFilters",
    ]),
    ...mapActions("users", ["getAllUsers"]),
    changeCompact() {
      this.commitCompactFlag(!this.getCompact);
    },
    redirectToSchedulePage() {
      if (this.current_schedules.length > 0) {
        this.$router.push({
          name: "schedule-template-update",
          params: {
            module: "PAYMENT_REPORT",
            page_title: this.getViewTitle,
            module_id: this.getViewId,
            id: this.current_schedules[0].id,
          },
        });
      } else {
        this.$router.push({
          name: "schedule-template-create",
          params: {
            module: "PAYMENT_REPORT",
            page_title: this.getViewTitle,
            module_id: this.getViewId,
          },
        });
      }
    },
    async currentSchedules(module_id) {
      const vm = this;
      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/schedule-templates?zm_module[]=PAYMENT_REPORT&zm_module_id[]=${module_id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          if (data.hasOwnProperty("schedule_template_listing")) {
            this.current_schedules = data.schedule_template_listing;
          }
        })
        .catch(({ response }) => {});
    },
    unsavedView() {
      const vm = this;
      //check for the flag if flag is 0 that means the pop up is there and dont do any thing.
      // If flag is 1 that means reset the value to 0, show the popup
      if (this.payment_view_flag == 1) {
        this.payment_view_flag = 0;
        this.$swal({
          showConfirmButton: true,
          showCancelButton: true,
          cancelButtonText: "Revert",
          confirmButtonText: "Save",
          toast: true,
          position: "bottom",
          text: "Do you like the view ? Save It.",
        }).then((result) => {
          if (result.hasOwnProperty("dismiss")) {
            this.updateView(
              JSON.parse(JSON.stringify(this.payment_view_default))
            );
          } else if (result.hasOwnProperty("value") && result.value) {
            //Save the payment view.
            let formData = new FormData();
            formData.append("view", JSON.stringify(vm.list));
            axios({
              method: "post",
              data: formData,
              headers: {
                Authorization: "Bearer " + window.$cookies.get("_token"),
              },
              url:
                window.location.origin +
                `/api/business/payment-reports/views/update/${this.getViewId}`,
            }).then((response) => {
              this.$swal({
                showConfirmButton: false,
                icon: "success",
                toast: true,
                timer: 2000,
                position: "bottom",
                title: "Payment Report View Updated.",
              });
            });
          }
          this.payment_view_flag = 1;
        });
      }
    },
    updateSortColumn(column, order) {
      if (this.getSearch) {
        this.$swal({
          showConfirmButton: false,
          icon: "error",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: "Sort order for view cannot be updated while searching.",
        });
        return;
      }

      const vm = this;
      let formData = new FormData();
      vm.sort_column = {};
      vm.sort_column[column] = order;
      formData.append("sort", JSON.stringify(vm.sort_column));
      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          `/api/business/payment-reports/views/update/${this.getViewId}`,
      }).then((response) => {
        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: "Payment Report View Changed.",
        });

        this.commitLoadState(false);
        vm.paymentListing();
      });
    },
    filterPayment() {
      let vm = this;
      let url = window.location.origin;
      url += `/api/business/payments/reports?items=${this.getEntitiesCount}`;
      for (const key in this.getFilters) {
        if (this.getFilters.hasOwnProperty(key)) {
          if (this.getFilters[key]) {
            url += `&zm_${key}[]=${this.getFilters[key]}`;
          }
        }
      }
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data.payment_listing);
        this.commitRequestUrl(url);

        let pager = response.data.pager;
        pager.next =
          pager.next == null ? null : "/api" + pager.next.split("api")[1];
        pager.previous =
          pager.previous == null
            ? null
            : "/api" + pager.previous.split("api")[1];

        this.commitViewPager(pager);
        this.commitCurrentPage(parseInt(pager.currentPage));
        this.commitEntitiesCount(parseInt(pager.perPage));
        this.commitSortColumn(JSON.parse(JSON.stringify(response.data.sort)));
        this.commitSearchState(true);

        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: `Payment has been filtered.`,
        });
      });
    },
    resetFilters() {
      this.commitFilters({ name: "type", value: "" });
      this.commitFilters({ name: "reason", value: "" });
      this.commitFilters({ name: "status", value: "" });
      this.commitFilters({ name: "module", value: "" });
      this.commitFilters({ name: "amount_start", value: "" });
      this.commitFilters({ name: "amount_end", value: "" });
      this.commitFilters({ name: "date_start", value: null });
      this.commitFilters({ name: "date_end", value: null });

      this.paymentListing(true);
      this.commitSearchState(false);
    },
    changePaymentView(id) {
      this.commitSearchString("");
      this.commitSearchState(false);

      this.commitFilters({ name: "type", value: "" });
      this.commitFilters({ name: "reason", value: "" });
      this.commitFilters({ name: "status", value: "" });
      this.commitFilters({ name: "module", value: "" });
      this.commitFilters({ name: "amount_start", value: "" });
      this.commitFilters({ name: "amount_end", value: "" });
      this.commitFilters({ name: "date_start", value: null });
      this.commitFilters({ name: "date_end", value: null });
      this.payment_view_id = id;
      let vm = this;
      // this.clearBulkSelection();
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          `/api/business/payment-reports/views/result/${id}`,
      }).then((response) => {
        this.commitViewId(response.data.payment_view_id);
        this.commitViewTitle(response.data.header);
        this.commitViewEntities(response.data.payment_listing);
        this.commitRequestUrl(
          window.location.origin +
            `/api/business/payment-reports/views/result/${id}`
        );

        let pager = response.data.pager;
        pager.next =
          pager.next == null ? null : "/api" + pager.next.split("api")[1];
        pager.previous =
          pager.previous == null
            ? null
            : "/api" + pager.previous.split("api")[1];

        this.commitViewPager(pager);
        this.commitCurrentPage(parseInt(pager.currentPage));
        this.commitEntitiesCount(parseInt(pager.perPage));

        this.updateView(Object.values(response.data.view));
        this.payment_view_default = JSON.parse(
          JSON.stringify(Object.values(response.data.view))
        );

        this.commitSortColumn(JSON.parse(JSON.stringify(response.data.sort)));

        axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url:
            window.location.origin +
            `/api/business/user/payment-report/update?id=${this.getViewId}`,
        }).then((response) => {
          this.$swal({
            showConfirmButton: false,
            icon: "success",
            toast: true,
            timer: 2000,
            position: "bottom",
            title: "Payment View Changed.",
          });
        });
      });
    },

    pagination(n = 1) {
      const vm = this;
      let url = window.location.origin;
      if (this.getSearch) {
        url += `/api/business/payments/reports?`;
        let param_set = false;
        for (const key in this.getFilters) {
          if (this.getFilters.hasOwnProperty(key)) {
            if (this.getFilters[key]) {
              if (param_set) {
                url += `&zm_${key}[]=${this.getFilters[key]}`;
              } else {
                url += `zm_${key}[]=${this.getFilters[key]}`;
              }
              param_set = true;
            }
          }
        }
        url += `&page=`;
      } else {
        url += `/api/business/payment-reports/views/result/${this.getViewId}?page=`;
      }

      url += n;
      url += "&items=";
      url += this.getEntitiesCount;
      //  this.clearBulkSelection();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data.payment_listing);
        this.commitRequestUrl(url);

        let pager = response.data.pager;
        pager.next =
          pager.next == null ? null : "/api" + pager.next.split("api")[1];
        pager.previous =
          pager.previous == null
            ? null
            : "/api" + pager.previous.split("api")[1];

        this.commitViewPager(pager);
        this.commitCurrentPage(parseInt(pager.currentPage));
        this.commitEntitiesCount(parseInt(pager.perPage));
      });
    },
    paginationWithUrl(url) {
      const vm = this;
      // this.clearBulkSelection();
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data.payment_listing);
        this.commitRequestUrl(url);

        let pager = response.data.pager;
        pager.next =
          pager.next == null ? null : "/api" + pager.next.split("api")[1];
        pager.previous =
          pager.previous == null
            ? null
            : "/api" + pager.previous.split("api")[1];

        this.commitViewPager(pager);
        this.commitCurrentPage(parseInt(pager.currentPage));
        this.commitEntitiesCount(parseInt(pager.perPage));
      });
    },
    paymentViews(refresh = false) {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + "/api/business/payment-reports/views",
      }).then((response) => {
        vm.$data.payment_views = response.data.payment_views;
      });
    },
    async paymentListing(refresh = false) {
      let vm = this;

      if (refresh || !this.getLoadState) {
        await axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: window.location.origin + `/api/business/user/payment-report`,
        }).then((response) => {
          this.commitViewId(response.data.payment_view_id);
          this.commitViewTitle(response.data.header);
          this.commitViewEntities(response.data.payment_listing);
          this.commitRequestUrl(
            window.location.origin + `/api/business/user/payment-report`
          );

          let pager = response.data.pager;
          pager.next =
            pager.next == null ? null : "/api" + pager.next.split("api")[1];
          pager.previous =
            pager.previous == null
              ? null
              : "/api" + pager.previous.split("api")[1];

          this.commitViewPager(pager);
          this.commitCurrentPage(parseInt(pager.currentPage));
          this.commitEntitiesCount(parseInt(pager.perPage));
          this.commitSortColumn(JSON.parse(JSON.stringify(response.data.sort)));

          this.updateView(Object.values(response.data.view));
          this.payment_view_default = JSON.parse(
            JSON.stringify(Object.values(response.data.view))
          );
        });

        this.commitLoadState(true);
      }
    },
    async downloadCSV() {
      const vm = this;

      let method = "get";

      let formData = new FormData();
      formData.append("view", JSON.stringify(vm.list));

      const toast = new Toast(`Downloading ...`);
      toast.fire();

      let url = window.location.origin;
      if (this.getSearch) {
        method = "post";
        url += `/api/business/payments/reports/csv/report?`;
        let param_set = false;
        for (const key in this.getFilters) {
          if (this.getFilters.hasOwnProperty(key)) {
            if (this.getFilters[key]) {
              if (param_set) {
                url += `&zm_${key}[]=${this.getFilters[key]}`;
              } else {
                url += `zm_${key}[]=${this.getFilters[key]}`;
              }
              param_set = true;
            }
          }
        }
        url += `&page=`;
      } else {
        url += `/api/business/payment-reports/views/csv/report/${this.getViewId}`;
      }

      await axios({
        method: method,
        responseType: "blob",
        url: url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then((response) => {
          const file = new Blob([response.data], { type: "text/csv" }); //Build a URL from the file
          const fileURL = URL.createObjectURL(file); //Open the URL on new Window

          // Create a link to download it
          var pom = document.createElement("a");
          pom.href = fileURL;
          pom.setAttribute(
            "download",
            `${this.$helpers.downloadFileName(this.getViewTitle)}.csv`
          );
          pom.click();

          toast.close();
        })
        .catch(({ response }) => {
          console.log(response);
          toast.fail(`There were errors in downloading.`);
          toast.close();
        });
    },
    async downloadXLS() {
      const vm = this;

      let method = "get";

      let formData = new FormData();
      formData.append("view", JSON.stringify(vm.list));

      const toast = new Toast(`Downloading ...`);
      toast.fire();

      let url = window.location.origin;
      if (this.getSearch) {
        method = "post";
        url += `/api/business/payments/reports/xls/report?`;
        let param_set = false;
        for (const key in this.getFilters) {
          if (this.getFilters.hasOwnProperty(key)) {
            if (this.getFilters[key]) {
              if (param_set) {
                url += `&zm_${key}[]=${this.getFilters[key]}`;
              } else {
                url += `zm_${key}[]=${this.getFilters[key]}`;
              }
              param_set = true;
            }
          }
        }
        url += `&page=`;
      } else {
        url += `/api/business/payment-reports/views/xls/report/${this.getViewId}`;
      }

      await axios({
        method: method,
        responseType: "blob",
        url: url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then((response) => {
          const file = new Blob([response.data], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
          }); //Build a URL from the file
          const fileURL = URL.createObjectURL(file); //Open the URL on new Window

          // Create a link to download it
          var pom = document.createElement("a");
          pom.href = fileURL;
          pom.setAttribute(
            "download",
            `${this.$helpers.downloadFileName(this.getViewTitle)}.xlsx`
          );
          pom.click();

          toast.close();
        })
        .catch(({ response }) => {
          console.log(response);
          toast.fail(`There were errors in downloading.`);
          toast.close();
        });
    },
    async downloadPdf() {
      const vm = this;

      let method = "get";

      let formData = new FormData();
      formData.append("view", JSON.stringify(vm.list));

      const toast = new Toast(`Downloading ...`);
      toast.fire();

      let url = window.location.origin;
      if (this.getSearch) {
        method = "post";
        url += `/api/business/payments/reports/pdf/report?`;
        let param_set = false;
        for (const key in this.getFilters) {
          if (this.getFilters.hasOwnProperty(key)) {
            if (this.getFilters[key]) {
              if (param_set) {
                url += `&zm_${key}[]=${this.getFilters[key]}`;
              } else {
                url += `zm_${key}[]=${this.getFilters[key]}`;
              }
              param_set = true;
            }
          }
        }
        url += `&page=`;
      } else {
        url += `/api/business/payment-reports/views/pdf/report/${this.getViewId}`;
      }

      await axios({
        method: method,
        responseType: "blob",
        url: url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then((response) => {
          const file = new Blob([response.data], { type: "application/pdf" });
          const fileURL = URL.createObjectURL(file);

          var pom = document.createElement("a");
          pom.href = fileURL;
          pom.setAttribute("download", "payments.pdf");
          pom.click();

          toast.close();
        })
        .catch(({ response }) => {
          console.log(response);
          toast.fail(`There were errors in downloading.`);
          toast.close();
        });
    },
  },
  async created() {
    let vm = this;
    const today = new Date();
    const last_week = new Date(today);
    last_week.setDate(last_week.getDate() - 7);

    await this.getPaymentConfigurations();

    this.commitFilters({
      name: "date_start",
      value: null,
    });
    this.commitFilters({
      name: "date_end",
      value: null,
    });

    this.commitFilters({
      name: "amount_start",
      value: "",
    });
    this.commitFilters({
      name: "amount_end",
      value: "",
    });

    await this.paymentViews();
    await this.resetFilters();
  },
  async mounted() {
    await this.currentSchedules(this.getViewId);
    await this.getAllUsers();

    if (Number.isInteger(parseInt(this.getCurrentEntity))) {
      let rows = document.querySelector(
        "#payment_report_" + this.getCurrentEntity
      );

      // line is zero-based
      // line is the row number that you want to see into view after scroll
      rows.style.background = "#e7e7e7";
      rows.scrollIntoView({
        behavior: "smooth",
        block: "center",
      });
    }
  },
};
</script>

<style lang="scss">
#payment-report-section {
  .vdpComponent {
    height: 3.9em !important;
  }
  .vdpComponent > input {
    padding-left: 2.2em !important;
    line-height: 1.4;
    color: #777;
    background-color: #ffffff !important;
    background-clip: padding-box;
    border: 1px solid #dfe3e7;
    border-radius: 0.267rem;
    font-size: 1.4em;
  }

  .vdpComponent input {
    background: white;
    border: 0px;
  }
  .vdpComponent input:read-only {
    display: inline-block;
    width: 80%;
    background: white !important;
    padding-right: 0em;
    font-size: 1.4em;
    border: 0px;
    padding-left: 0px !important;
    margin-left: 3em !important;
  }

  .vdpComponent .vdpTimeUnit {
    min-width: 50px;
  }

  .notification-icon-small {
    font-size: 0.5em;
    height: 50px;
    width: 50px;
  }

  .vdpComponent .vdpClearInput {
    display: none !important;
  }

  .smart-view-edit-action {
    &:hover {
      font-weight: 800;
    }
  }
}
</style>
