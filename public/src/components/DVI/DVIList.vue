<template>
  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <!-- form layout section start -->
    <div class="content-body" id="dvi-list-section">
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
                    <ul
                      class="ml-0 mt-1 list-group list-group-horizontal"
                      v-if="getIds.length == 0"
                    >
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
                                {{ selectedView }}
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
                                  v-for="digital_vehicle_inspection_view in getDefaultViews"
                                  :key="digital_vehicle_inspection_view.id"
                                  ><span
                                    class="d-inline-block flex-fill"
                                    @click="
                                      changeCurrentEntityView(
                                        digital_vehicle_inspection_view.id
                                      )
                                    "
                                    >{{
                                      digital_vehicle_inspection_view.header
                                        | truncate(25, "...")
                                    }}</span
                                  ><router-link
                                    tag="td"
                                    class="d-inline-block"
                                    :to="{
                                      name: 'dvi-view-update',
                                      params: {
                                        id: digital_vehicle_inspection_view.id,
                                      },
                                    }"
                                    exact
                                    :key="`default_default_view_${digital_vehicle_inspection_view.id}`"
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
                                    v-for="digital_vehicle_inspection_view in getCustomViews"
                                    :key="digital_vehicle_inspection_view.id"
                                    ><span
                                      class="d-inline-block flex-fill"
                                      @click="
                                        changeCurrentEntityView(
                                          digital_vehicle_inspection_view.id
                                        )
                                      "
                                      >{{
                                        digital_vehicle_inspection_view.header
                                          | truncate(25, "...")
                                      }}</span
                                    ><router-link
                                      tag="td"
                                      class="d-inline-block"
                                      :to="{
                                        name: 'dvi-view-update',
                                        params: {
                                          id: digital_vehicle_inspection_view.id,
                                        },
                                      }"
                                      exact
                                      :key="`dvi_custom_view_${digital_vehicle_inspection_view.id}`"
                                    >
                                      <i
                                        class="bx bx-edit-alt smart-view-edit-action"
                                      ></i> </router-link
                                  ></a>
                                </span>
                                <p class="border-top">
                                  <router-link
                                    tag="a"
                                    :to="{ name: 'dvi-view-create' }"
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
                    <!-- bulk update buttons -->
                    <div
                      class="ml-0 mt-1 list-group list-group-horizontal"
                      role="alert"
                      v-else
                    >
                      <div class="d-inline-block mr-1 d-none">
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
                          <li class="d-none mr-1">
                            <a
                              href="#"
                              data-toggle="modal"
                              data-target="#bulk-list"
                              class="btn btn-outline-primary"
                              >Bulk Update</a
                            >
                          </li>
                          <li class="d-none mr-1">
                            <select class="form-control btn-outline-primary">
                              <option selected="" disabled="">
                                Bulk Actions
                              </option>
                              <option
                                style="cursor: pointer"
                                @click="confirmDeleteAction"
                              >
                                Delete
                              </option>
                            </select>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <!-- end bulk update buttons -->
                  </div>
                  <div class="col-md-5 col-xs-12">
                    <div class="col-12">
                      <fieldset
                        class="form-label-group form-group position-relative has-icon-right mt-1"
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
                    <ul
                      class="ml-0 mt-1 list-group list-group-horizontal text-right"
                    >
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
                                title="Modify View"
                                class="list-space border bx bxs-grid"
                                data-toggle="modal"
                                data-target="#grid-list"
                              ></span>
                            </li>
                            <li class="d-none">
                              <div class="dropdown">
                                <span
                                  class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer list-space border"
                                  data-toggle="dropdown"
                                  aria-haspopup="true"
                                  aria-expanded="false"
                                  role="menu"
                                ></span>
                                <div class="dropdown-menu dropdown-menu-right">
                                  <a class="dropdown-item" href="#"
                                    >Export List Into CSV</a
                                  >
                                  <a class="dropdown-item" href="#"
                                    >Print List Into PDF</a
                                  >
                                  <a class="dropdown-item" href="#"
                                    >Import Inventory</a
                                  >
                                </div>
                              </div>
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
              class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm"
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
                        :options="getStatuses"
                      >
                      </v-select>
                    </div>
                    <!-- status : END -->

                    <!-- Assignee: START -->
                    <div class="col-md-5">
                      <label>Assignee</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <v-select
                        id="filter-assignee"
                        placeholder="Assignee"
                        v-model="assigneeFilter"
                        clearable
                        :options="allUsers"
                        :reduce="(user) => user.user_id"
                        label="name"
                      >
                      </v-select>
                    </div>
                    <!-- Assignee : END -->

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
                        @click="filterEntities"
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
                        ></th>
                        <template v-for="element in list">
                          <th
                            class="text-center"
                            style="min-width: 150px"
                            :style="{
                              'min-width':
                                element.header == 'TITLE' ? '400px' : '150px',
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
                      <d-v-i-item
                        v-for="item in getEntities"
                        :allUsers="allUsers"
                        :item="item"
                        :childCheck="bulk_select"
                        v-bind:key="item.id + '_item_listing_view'"
                      ></d-v-i-item>
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
    <d-v-i-snap-shot></d-v-i-snap-shot>
  </div>
  <!-- END: Content-->
</template>
<script>
import draggable from "vuedraggable";
import DVIItem from "./DVIItem";
import { mapGetters, mapActions } from "vuex";
import vSelect from "vue-select";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import DVISnapShot from "./DVISnapShot.vue";
import SmartViewMixin from "../../mixins/smartView";

export default {
  name: "DVIList",
  components: {
    DVIItem,
    draggable,
    "v-select": vSelect,
    DatePicker,
    DVISnapShot,
  },
  data() {
    return {
      load: false,
      digital_vehicle_inspection_listing: "",
      pager: "",
      digital_vehicle_inspection_view_id: 1,
      dragging: true,
      sort_column: { created_at: "DESC" },
      form: {
        source: "",
        status: "",
      },

      api_entity_listing_attribute: "digital_vehicle_inspection_listing",
      api_entity_view_id_attribute: "digital_vehicle_inspection_view_id",
      api_entity_views_attribute: "digital_vehicle_inspection_views",
      api_entity_bulk_ids_attribute: "digital_vehicle_inspection_ids",
      entity_view_route_slug: "/api/business/dvi/views",
      entity_index_route_slug: "/api/business/dvi",
      entity_bulk_deletion_route_slug: "/api/business/dvi/deletes",
      entity_bulk_update_route_slug: "",
      user_entity_route_slug: "/api/business/user/dvi",
      entity_label: "DVI",
    };
  },
  mixins: [SmartViewMixin],
  computed: {
    ...mapGetters("dVI", ["getIds", "getStatuses"]),
    ...mapGetters("dVIView", [
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
    ...mapGetters("users", ["allUsers"]),
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost",
      };
    },
    assigneeFilter: {
      get() {
        return this.getFilters.assignee;
      },
      set(value) {
        this.commitFilters({ name: "assignee", value: value });
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
  },
  methods: {
    ...mapActions("dVI", ["removeOldId", "addAllId"]),
    ...mapActions("dVIView", [
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
    ...mapActions("users", ["getAllUsers"]),
    resetFilterProperties() {
      this.commitFilterState(false);
      this.commitFilters({ name: "status", value: "" });
      this.commitFilters({ name: "assignee", value: "" });
      this.commitFilters({ name: "date_start", value: null });
      this.commitFilters({ name: "date_end", value: null });
    },
  },
  created() {
    this.entityViews();
    this.currentUserSelectedViewEntityListing();
  },
  mounted() {
    this.getAllUsers();
    if (Number.isInteger(parseInt(this.getCurrentEntity))) {
      let rows = document.querySelector("#dvi_" + this.getCurrentEntity);

      // line is zero-based
      // line is the row number that you want to see into view after scroll
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
  },
};
</script>

<style lang="scss">
#dvi-list-section {
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
