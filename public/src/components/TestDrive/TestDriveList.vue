<template>
  <!-- BEGIN: Content-->
  <div class="app-content content" v-if="isAuthorized">
    <div class="content-overlay"></div>
    <!-- form layout section start -->
    <div class="content-body">
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
                    <!-- bulk update buttons -->
                    <div
                        class="ml-0 mt-1 list-group list-group-horizontal"
                        role="alert"
                        :style="{
                        visibility:
                          getTestDriveIds.length != 0 ? 'visible' : 'hidden',
                      }"
                    >
                      <div class="d-inline-block mr-1">
                        <ul class="bulk-list" style="margin-bottom: 0px">
                          <li class="d-inline-block mr-1">
                            <span
                                class="form-control btn-outline-danger"
                                style="cursor: pointer"
                                @click="confirmDeleteAction"
                            >
                              Delete
                            </span>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <!-- end bulk update buttons -->
                  </div>
                  <div class="col-md-5 col-xs-12">
                    <div class="col-12">
                      <fieldset
                          class="
                          form-label-group form-group
                          position-relative
                          has-icon-right
                          mt-1
                        "
                      >
                        <input
                            type="text"
                            id="searchText"
                            v-debounce:1000ms="searchAction"
                            v-model="searchString"
                            class="form-control round"
                            style="height: 2.6em"
                            placeholder="Search Anything ..."
                        />

                        <div class="form-control-position">
                          <i class="bx bx-search"></i>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="col-md-4 col-12">
                    <ul class="ml-1 mt-1">
                      <li class="d-inline-block">
                        <div
                            class="btn-feild-box"
                            style="top: 16px; right: 15px"
                        >
                          <ul>
                            <li class="d-none d-sm-none d-md-inline-block">
                              <span
                                  href="#"
                                  :class="
                                  getFilterState
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
                                  data-target="#grid-list"
                                  title="Modify View"
                              ></span>
                            </li>
                            <li class="d-inline-block" @click="changeCompact">
                              <span
                                  title="Compact View"
                                  class="list-space border bx"
                                  :class="
                                  getCompact ? 'bx-expand' : 'bx-collapse'
                                "
                              ></span>
                            </li>
                            <li class="d-inline-block">
                              <div class="dropdown">
                                <span
                                    class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer list-space border"
                                    data-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    role="menu"
                                ></span>
                                <div class="dropdown-menu dropdown-menu-right">
                                  <router-link
                                      v-if="isAuthorized"
                                      tag="a"
                                      title="TestDrive Customers"
                                      :to="{ name: 'test-drive-customer-list' }"
                                      class="dropdown-item"
                                      exact
                                  >
                                    <span
                                        style="cursor: pointer"
                                        class="bx bxs-dollar-circle mr-1"
                                    ></span>
                                    TestDrive Customers
                                  </router-link>
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
                class="
                modal-dialog
                modal-dialog-centered
                modal-dialog-scrollable
                modal-sm
              "
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
              <div class="modal-content" id="filterLeadModel">
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
                <div class="modal-body pt-4">
                  <div class="row">
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
                          :options="getTestDriveStatuses"
                      >
                      </v-select>
                    </div>
                    <!-- status : END -->

                    <!-- assignee: START -->
                    <div class="col-md-5">
                      <label>Sales Person</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <v-select
                          id="filter-assignee"
                          placeholder="Assignee"
                          v-model="assigneeFilter"
                          clearable
                          :options="getTestDriveSalesReps"
                          :reduce="(user) => user.user_id"
                          label="name"
                      >
                      </v-select>
                    </div>
                    <!-- assignee : END -->

                    <!-- dates: START -->
                    <div class="col-md-12 mt-5">
                      <label>TestDrive Date Range</label>
                    </div>
                    <div class="col-md-5 form-group">
                      <fieldset
                          class="has-icon-left"
                          style="position: relative"
                      >
                        <date-picker
                            :inputAttributes="{ readonly: true }"
                            :format="'MM/DD/YYYY'"
                            :value-type="'MM/DD/YYYY'"
                            v-model="dateStartFilter"
                            style="border: 1px solid #eee"
                        >
                        </date-picker>
                      </fieldset>
                    </div>
                    <div class="col-md-5 form-group">
                      <fieldset
                          class="has-icon-left"
                          style="position: relative"
                      >
                        <date-picker
                            :inputAttributes="{ readonly: true }"
                            style="border: 1px solid #eee"
                            :format="'MM/DD/YYYY'"
                            :value-type="'MM/DD/YYYY'"
                            v-model="endStartFilter"
                        >
                        </date-picker>
                      </fieldset>
                    </div>
                    <!-- dates : END -->

                    <div class="col-xs-12 p-1"></div>

                    <div class="col-sm-12 d-flex justify-content-left pb-1">
                      <button
                          type="close"
                          @click="filterTestDrive"
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
                      <th
                          class="left-space-box"
                          id="d-list"
                          style="width: 40px"
                      >
                        <input
                            class="select_check"
                            type="checkbox"
                            v-model="bulk_select"
                            :true-value="'1'"
                            :false-value="'0'"
                        />
                      </th>
                      <template v-for="element in list">
                        <th
                            class="text-center"
                            style="min-width: 150px"
                            :style="{
                              'min-width':
                                element.header == 'EMAIL'
                                  ? '250px'
                                  : '150px',
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
                    <test-drive-item
                        v-for="item in getEntities"
                        :item="item"
                        :allUsers="allUsers"
                        :childCheck="bulk_select"
                        v-bind:key="item.id + '_item_listing_view'"
                    ></test-drive-item>
                    </tbody>
                  </table>
                </div>

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
                            class="
                            custom-select custom-select-sm
                            form-control form-control-sm
                          "
                        >
                          <option value="10">10</option>
                          <option value="25">25</option>
                          <option value="50">50</option>
                          <option value="100">100</option>
                          <option value="200">200</option>
                          <option value="250">250</option>
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
                            v-for="n in getPager.pageCount"
                            v-show="
                            n >= getCurrentPage - 2 && n <= getCurrentPage + 2
                          "
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
      </form>
    </div>
  </div>
  <!-- END: Content-->
</template>
<script>
import axios from "axios";
import draggable from "vuedraggable";
import TestDriveItem from "./TestDriveItem";
import {mapGetters, mapActions} from "vuex";
import vSelect from "vue-select";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

export default {
  props: ["application", "app_metadata"],
  name: "TestDriveList",
  components: {
    "v-select": vSelect,
    TestDriveItem,
    draggable,
    DatePicker,
  },
  data() {
    return {
      load: false,
      page: 1,
      number_of_items: 25,
      bulk_select: "0",
      test_drives: [],
      test_drive_heading: "Test Drives",
      test_drive_listing: "",
      pager: "",
      test_drive_view_id: 1,
      dragging: true,
      test_drive_view_flag: 1,
      test_drive_view_default: [],
      sort_column: {created_at: "DESC"},
    };
  },
  computed: {
    ...mapGetters("testDrive", [
      "getTestDriveIds",
      "getTestDriveStatuses",
      "getTestDriveSalesReps",
      
    ]),
    ...mapGetters("users", ["allUsers"]),
    ...mapGetters("testDriveView", [
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
      "getFilterState",
    ]),
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
    getNumberOfEntities: {
      get() {
        return this.getEntitiesCount;
      },
      set(value) {
        this.commitEntitiesCount(value);
        this.pagination();
      },
    },
    searchString: {
      get() {
        return this.getSearchString;
      },
      set(value) {
        this.commitSearchString(value);
      },
    },
    statusFilter: {
      get() {
        return this.getFilters.status;
      },
      set(value) {
        this.commitFilters({name: "status", value: value});
      },
    },
    assigneeFilter: {
      get() {
        return this.getFilters.sales_rep_id;
      },
      set(value) {
        this.commitFilters({name: "sales_rep_id", value: value});
      },
    },
    dateStartFilter: {
      get() {
        return this.getFilters.date_start;
      },
      set(value) {
        this.commitFilters({name: "date_start", value: value});
      },
    },
    endStartFilter: {
      get() {
        return this.getFilters.date_end;
      },
      set(value) {
        this.commitFilters({name: "date_end", value: value});
      },
    },
  },
  methods: {
    ...mapActions("testDrive", [
      "removeEveryTestDriveId",
      "addAllTestDriveId",
      "fetchTestDriveFilters",
    ]),
    ...mapActions("users", ["getAllUsers"]),
    ...mapActions("testDriveView", [
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
      "commitFilterState",
    ]),
    filterTestDrive() {
      let url = window.location.origin;
      url += `/api/business/test-drives/?all=true`;

      if (this.getSearch) {
        url += `&search=${this.getSearchString}`;
      }

      for (const key in this.getFilters) {
        if (Object.prototype.hasOwnProperty.call(this.getFilters, key)) {
          if (this.getFilters[key]) {
            url += `&zm_${key}[]=${this.getFilters[key]}`;
          }
        }
      }

      axios({
        method: "get",
        headers: {Authorization: "Bearer " + window.$cookies.get("_token")},
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data.test_drive_listing);
        this.commitRequestUrl(url);

        let pager = response.data.pager;
        pager.next = pager.next == null ? null : "/api" + pager.next.split("api")[1];
        pager.previous = pager.previous == null ? null : "/api" + pager.previous.split("api")[1];

        this.commitViewPager(pager);
        this.commitCurrentPage(parseInt(pager.currentPage));
        this.commitEntitiesCount(parseInt(pager.perPage));
        this.commitSortColumn(JSON.parse(JSON.stringify(response.data.sort)));
        this.commitFilterState(true);

        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: `Test Drives have been filtered.`,
        });
      });
    },
    resetFilters() {
      this.commitFilters({name: "status", value: ""});
      this.commitFilters({name: "sales_rep_id", value: ""});
      this.commitFilters({name: "date_start", value: ""});
      this.commitFilters({name: "date_end", value: ""});

      if (this.getSearch) {
        this.searchTestDriveResponses(this.getSearchString);
      } else {
        this.testDriveListing(true);
      }
      this.commitFilterState(false);
    },
    searchAction(value, e) {
      if (value.length >= 3) {
        this.commitSearchState(true);
        this.searchTestDrives(this.getSearchString);
      } else {
        if (this.getSearch) {
          this.testDriveListing(true);
          this.commitSearchState(false);
        }
      }
    },
    searchTestDrives(search) {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/test-drives?search=${search}`,
      }).then((response) => {
        this.commitViewEntities(response.data.test_drive_listing);
        this.commitRequestUrl(
            window.location.origin + `/api/business/test-drives?search=${search}`
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

        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: `Search for ${search} has been completed.`,
        });
      });
    },
    changeCompact() {
      this.commitCompactFlag(!this.getCompact);
    },
    unsavedView() {
      const vm = this;
      if (this.test_drive_view_flag == 1) {
        this.test_drive_view_flag = 0;
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
                JSON.parse(JSON.stringify(this.test_drive_view_default))
            );
          } else if (result.hasOwnProperty("value") && result.value) {
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
                  `/api/business/test-drives/views/update/${this.getViewId}`,
            }).then((response) => {
              this.$swal({
                showConfirmButton: false,
                icon: "success",
                toast: true,
                timer: 2000,
                position: "bottom",
                title: "Test Drive View Updated.",
              });
            });
          }
          this.test_drive_view_flag = 1;
        });
      }
    },
    async searchTestDriveResponses(search) {
      let url = window.location.origin;
      url += `/api/business/test-drives/?all=true`;

      url += `&search=${this.getSearchString}`;

      if (this.getFilterState) {
        for (const key in this.getFilters) {
          if (this.getFilters.hasOwnProperty(key)) {
            if (this.getFilters[key]) {
              url += `&zm_${key}[]=${this.getFilters[key]}`;
            }
          }
        }
      }

      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data.test_drive_listing);
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

        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: `Search for ${search} has been completed.`,
        });
      });
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
            `/api/business/test-drives/views/update/${this.getViewId}`,
      }).then((response) => {
        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: "Test Drive View Changed.",
        });

        this.commitLoadState(false);
        vm.testDriveListing();
      });
    },
    pagination(n = 1) {
      const vm = this;
      let url = window.location.origin;

      if (this.getSearch) {
        url += `/api/business/test-drives?search=${this.getSearchString}&page=`;
      } else {
        url += `/api/business/user/test-drive?page=`;
      }
      url += n;
      url += "&items=";
      url += this.getEntitiesCount;
      this.clearBulkSelection();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data.test_drive_listing);
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
      this.clearBulkSelection();
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data.test_drive_listing);
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
    testDriveListing(refresh = false) {
      let vm = this;

      if (refresh || !this.getLoadState) {
        axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: window.location.origin + `/api/business/user/test-drive`,
        }).then((response) => {
          this.commitViewId(response.data.test_drive_view_id);
          this.commitViewTitle(response.data.header);
          this.commitViewEntities(response.data.test_drive_listing);
          this.commitRequestUrl(
              window.location.origin + `/api/business/user/test-drive`
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
          this.test_drive_view_default = JSON.parse(
              JSON.stringify(Object.values(response.data.view))
          );
        });
        this.commitLoadState(true);
      }
    },
    clearBulkSelection() {
      this.bulk_select = "0";
      this.removeEveryTestDriveId();
    },
    resetBulkColumns() {
    },
    applyBulkDelete() {
      const vm = this;
      let formData = new FormData();
      formData.append("test_drive_ids", this.getTestDriveIds);
      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/test-drives/deletes`,
      })
          .then((response) => {
            this.resetBulkColumns();
            vm.$data.bulk_select = 0;

            setTimeout(() => {
              this.pagination(this.getCurrentPage);
              this.$swal({
                showConfirmButton: false,
                icon: "success",
                toast: true,
                timer: 2000,
                position: "bottom",
                title: "Selected data has been deleted.",
              });
            }, 1000);
          })
          .catch(function (error) {
            vm.resetBulkColumns();
            vm.$data.bulk_select = 0;
            setTimeout(() => {
              vm.$swal({
                showConfirmButton: false,
                icon: "error",
                toast: true,
                timer: 2000,
                position: "bottom",
                title: "There were some errors in deleting data.",
              });
            }, 1000);
          });
    },
    confirmDeleteAction() {
      const vm = this;
      this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Confirm",
        toast: false,
        position: "center",
        text: "Please confirm if you want to delete the test drives.",
      }).then((result) => {
        if (result.isDismissed) {
          //@TODO Reset the selection
        } else if (result.isConfirmed) {
          vm.applyBulkDelete();
        }
      });
    },
  },
  created() {
    this.testDriveListing();
    this.fetchTestDriveFilters();
  },
  mounted() {
    if (Number.isInteger(parseInt(this.getCurrentEntity))) {
      let rows = document.querySelector("#test_drive_" + this.getCurrentEntity);

      rows.style.background = "#e7e7e7";
      rows.scrollIntoView({
        behavior: "smooth",
        block: "center",
      });
    }

    var input = document.getElementById("searchText");

    input.addEventListener("keydown", function (event) {
      if (event.keyCode === 13) {
        event.preventDefault();

        this.searchAction();
      }
    });
    this.getAllUsers();

  },
};
</script>

<style scoped></style>

