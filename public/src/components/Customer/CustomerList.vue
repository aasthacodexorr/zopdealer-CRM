<template>
  <!-- BEGIN: Content-->
  <div class="app-content content">
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
                          getIds.length != 0 ? 'visible' : 'hidden',
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
                          <li class="d-none mr-1">
                            <select class="form-control btn-outline-primary">
                              <option selected="" disabled="">
                                Bulk Actions
                              </option>
                              <option
                                style="cursor: pointer"
                                @click.prevent="confirmDeleteAction"
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
                    <ul class="ml-0 mt-1">
                      <li class="d-inline-block">
                        <div
                          class="btn-feild-box"
                          style="top: 16px; right: 15px"
                        >
                          <ul>
                            <!-- TODO: Change the route for inventory-create -->
                            <router-link
                              tag="li"
                              :to="{ name: 'customer-create' }"
                              class="d-inline-block"
                              exact
                            >
                              <a class="btn btn-primary">Add Customer</a>
                            </router-link>
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
                            <!-- <router-link tag="li"  :to="{name : 'inventory-settings'}" class=" d-inline-block mr-1" exact>
                                                 <span style="cursor:pointer;" class="list-space border bx bxs-cog" ></span>
                                            </router-link> -->
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
                        <th
                          class="left-space-box"
                          id="d-list"
                          style="width: 80px"
                        >
                          &nbsp;&nbsp;&nbsp;&nbsp;
                        </th>
                        <template v-for="element in list">
                          <th
                            class="text-center"
                            style="min-width: 150px"
                            :style="{
                              'min-width':
                                element.header == 'EMAIL' ||
                                element.header == 'COMPANY NAME'
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
                      <customer-item
                        v-for="item in getEntities"
                        :item="item"
                        :allUsers="allUsers"
                        :childCheck="bulk_select"
                        v-bind:key="item.id + '_item_listing_view'"
                      ></customer-item>
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
        <!-- form layout section end -->
      </form>
    </div>
    <customer-snap-shot></customer-snap-shot>
  </div>
  <!-- END: Content-->
</template>
<script>
import draggable from "vuedraggable";
import CustomerItem from "./CustomerItem";
import CustomerSnapShot from "./CustomerSnapShot";
import { mapGetters, mapActions } from "vuex";
import vSelect from "vue-select";
import SmartViewMixin from "../../mixins/smartView";

export default {
  name: "CustomerList",
  components: {
    "v-select": vSelect,
    CustomerItem,
    draggable,
    CustomerSnapShot,
  },
  data() {
    return {
      load: false,
      pager: "",
      dragging: true,
      sort_column: { created_at: "DESC" },
      form: {
        source: "",
        status: "",
      },

      api_entity_listing_attribute: "customer_listing", 
      api_entity_view_id_attribute: "customer_view_id", 
      api_entity_views_attribute: "customer_views", 
      api_entity_bulk_ids_attribute: "customer_ids", 
      entity_view_route_slug: "/api/business/customers/views", 
      entity_index_route_slug: "/api/business/customers", 
      entity_bulk_deletion_route_slug: "/api/business/customers/deletes", 
      entity_bulk_update_route_slug: "", 
      user_entity_route_slug: "/api/business/user/customer", 
      entity_label: "Customer", 
    };
  },
  mixins: [SmartViewMixin],
  computed: {
    ...mapGetters("customer", ["getIds"]),
    ...mapGetters("users", ["allUsers"]),
    ...mapGetters("customerView", [
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
    ]),
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost",
      };
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
  },
  methods: {
    ...mapActions("customer", ["removeOldCustomerId"]),
    ...mapActions("customerView", [
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
    ]),
    ...mapActions("users", ["getAllUsers"]),
  },
  created() {
    this.currentUserSelectedViewEntityListing();
  },
  async mounted() {
    await this.getAllUsers();
    if (Number.isInteger(parseInt(this.getCurrentEntity))) {
      let rows = document.querySelector("#customer_" + this.getCurrentEntity);

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

<style scoped></style>
