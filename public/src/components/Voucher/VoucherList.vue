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
                                {{ selected_voucher_view }}
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
                                  class="dropdown-item"
                                  v-show="voucher_view.default == '1'"
                                  v-for="voucher_view in voucher_views"
                                  :key="voucher_view.id"
                                  @click="changeVoucherView(voucher_view.id)"
                                  >{{ voucher_view.header }}</a
                                >

                                <p class="border-bottom border-top">
                                  <strong>Custom Views</strong>
                                </p>
                                <span
                                  style="
                                    max-height: 200px;
                                    overflow-y: auto;
                                    display: inline-block;
                                  "
                                >
                                  <a
                                    style="cursor: pointer"
                                    class="dropdown-item"
                                    v-show="voucher_view.default == '0'"
                                    v-for="voucher_view in voucher_views"
                                    :key="voucher_view.id"
                                    @click="changeVoucherView(voucher_view.id)"
                                    >{{ voucher_view.header }}</a
                                  >
                                </span>
                                <p class="border-top">
                                  <router-link
                                    tag="a"
                                    :to="{ name: 'voucher-view' }"
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
                          <li class="d-inline-block mr-1">
                            <a
                              href="#"
                              data-toggle="modal"
                              data-target="#bulk-list"
                              class="btn btn-outline-primary"
                              >Bulk Update</a
                            >
                          </li>
                        </ul>
                      </div>
                    </div>
                    <!-- end bulk update buttons -->
                  </div>
                  <div
                    class="col-md-5 col-xs-12"
                    style="position: relative; top: -0.5em"
                  >
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
                            <li class="d-none d-sm-none d-md-inline-block text-left">
                              <voucher-create></voucher-create>
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

          <!-- Bulk Grid : START -->
          <div
            class="modal fade text-left"
            id="bulk-list"
            tabindex="-1"
            role="dialog"
            aria-labelledby="myModalLabel19"
            aria-hidden="true"
          >
            <div
              class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
              role="document"
            >
              <div class="modal-content">
                <div class="modal-header pl-1 pr-1">
                  <h3 class="content-header-title" id="myModalLabel19">
                    Inventory Bulk Update
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
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-5">
                      <label>Status</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <fieldset>
                        <div class="input-group">
                          <select
                            class="custom-select form-control"
                            v-model="form.status"
                            id="status"
                            name="status"
                          >
                            <option value="" selected disabled>
                              - Select Status -
                            </option>
                            <option
                              :value="status"
                              v-for="status in getStatuses"
                              :key="status"
                            >
                              {{ status }}
                            </option>
                          </select>
                        </div>
                      </fieldset>
                    </div>

                    <div class="col-sm-12 d-flex justify-content-left pb-1">
                      <button
                        type="close"
                        @click="applyBulkOptions"
                        class="btn btn-primary mr-1 mt-1"
                        data-dismiss="modal"
                        aria-label="Close"
                      >
                        Update Fields
                      </button>
                      <button
                        type="close"
                        @click="resetBulkColumns"
                        class="btn btn-light-secondary mr-1 mt-1"
                        data-dismiss="modal"
                        aria-label="Close"
                      >
                        Cancel
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Bulk Grid : END -->
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
                        <th></th>
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
                      <voucher-item
                        v-for="item in getEntities"
                        :allUsers="allUsers"
                        :item="item"
                        :childCheck="bulk_select"
                        v-bind:key="item.id + '_item_listing_view'"
                      ></voucher-item>
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
import VoucherItem from "./VoucherItem";
import { mapGetters, mapActions } from "vuex";
import vSelect from "vue-select";
import VoucherCreate from "./VoucherCreate.vue";

export default {
  name: "VoucherList",
  components: {
    VoucherItem,
    draggable,
    "v-select": vSelect,
    VoucherCreate,
  },
  data() {
    return {
      load: false,
      single_search_voucher: null,
      page: 1,
      number_of_items: 25,
      bulk_select: "0",
      voucher_heading: "All Vochers",
      voucher_listing: "",
      vouchers: [],
      pager: "",
      voucher_views: [],
      voucher_view_id: 1,
      dragging: true,
      voucher_view_flag: 1,
      voucher_view_default: [],
      sort_column: { created_at: "DESC" },
      form: {
        status: "",
      },
    };
  },
  watch: {
    single_search_voucher: function(newVal, oldVal) {
      if (newVal.hasOwnProperty("id")) {
        if (!isNaN(newVal.id)) {
          this.$router.push({ name: "deal-update", params: { id: newVal.id } });
        }
      }
    },
  },
  computed: {
    ...mapGetters("voucher", ["getIds", "getStatuses"]),
    ...mapGetters("voucherView", [
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
    ...mapGetters("users", ["allUsers"]),
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost",
      };
    },
    selected_voucher_view() {
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
    ...mapActions("voucher", ["removeOldId", "addAllId"]),
    ...mapActions("voucherView", [
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
    searchAction(value, e) {
      if (value.length >= 3) {
        this.commitSearchState(true);
        // this.commitSearchString(value);
        this.searchVoucher(this.getSearchString);
      } else {
        if (this.getSearch) {
          this.voucherListing(true);
          this.commitSearchState(false);
        }
      }
    },
    searchVoucher(search) {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/vouchers?search=${search}`,
      }).then((response) => {
        this.commitViewEntities(response.data.voucher_listing);
        this.commitRequestUrl(
          window.location.origin + `/api/business/vouchers?search=${search}`
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
      //check for the flag if flag is 0 that means the pop up is there and dont do any thing.
      // If flag is 1 that means reset the value to 0, show the popup
      if (this.voucher_view_flag == 1) {
        this.voucher_view_flag = 0;
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
              JSON.parse(JSON.stringify(this.voucher_view_default))
            );
          } else if (result.hasOwnProperty("value") && result.value) {
            //Save the inventory view.
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
                `/api/business/vouchers/views/update/${this.getViewId}`,
            }).then((response) => {
              this.$swal({
                showConfirmButton: false,
                icon: "success",
                toast: true,
                timer: 2000,
                position: "bottom",
                title: "Voucher View Updated.",
              });
            });
          }
          this.voucher_view_flag = 1;
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
          `/api/business/vouchers/views/update/${this.getViewId}`,
      }).then((response) => {
        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: "Voucher View Changed.",
        });

        this.commitLoadState(false);
        vm.voucherListing();
      });
    },
    changeVoucherView(id) {
      this.commitSearchString("");
      this.commitSearchState(false);

      this.voucher_view_id = id;
      let vm = this;
      this.clearBulkSelection();
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin + `/api/business/vouchers/views/result/${id}`,
      }).then((response) => {
        this.commitViewId(response.data.voucher_view_id);
        this.commitViewTitle(response.data.header);
        this.commitViewEntities(response.data.voucher_listing);
        this.commitRequestUrl(
          window.location.origin + `/api/business/vouchers/views/result/${id}`
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
        this.voucher_view_default = JSON.parse(
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
            `/api/business/user/voucher/update?id=${this.getViewId}`,
        }).then((response) => {
          this.$swal({
            showConfirmButton: false,
            icon: "success",
            toast: true,
            timer: 2000,
            position: "bottom",
            title: "Voucher View Changed.",
          });
        });
      });
    },

    pagination(n = 1) {
      const vm = this;
      let url = window.location.origin;

      if (this.getSearch) {
        url += `/api/business/vouchers?search=${this.getSearchString}&page=`;
      } else {
        url += `/api/business/vouchers/views/result/${this.getViewId}?page=`;
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
        this.commitViewEntities(response.data.voucher_listing);
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
        this.commitViewEntities(response.data.voucher_listing);
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
    voucherViews(refresh = false) {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + "/api/business/vouchers/views",
      }).then((response) => {
        vm.$data.voucher_views = response.data.voucher_views;
      });
    },
    async voucherListing(refresh = false) {
      let vm = this;

      if (refresh || !this.getLoadState) {
        await axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: window.location.origin + `/api/business/user/voucher`,
        }).then((response) => {
          this.commitViewId(response.data.voucher_view_id);
          this.commitViewTitle(response.data.header);
          this.commitViewEntities(response.data.voucher_listing);
          this.commitRequestUrl(
            window.location.origin + `/api/business/user/voucher`
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
          this.voucher_view_default = JSON.parse(
            JSON.stringify(Object.values(response.data.view))
          );
        });

        this.commitLoadState(true);
      }
    },
    clearBulkSelection() {
      this.bulk_select = "0";
      this.removeOldId();
    },
    resetBulkColumns() {
      Object.keys(this.form).forEach((key) => {
        this.form[key] = "";
      });
    },
    async applyBulkOptions() {
      const vm = this;
      let formData = new FormData();
      formData.append("voucher_ids", this.getIds);
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key)) {
          formData.append(key, this.form[key]);
        }
      }

      await axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/vouchers/updates`,
      })
        .then((response) => {
          this.resetBulkColumns();
          /** Purposefully set to 0 (integer) to fail the prop check so subcomponents become reactive to change */
          vm.$data.bulk_select = 0;

          setTimeout(() => {
            this.pagination(this.getCurrentPage);
            this.$swal({
              showConfirmButton: false,
              icon: "success",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "Columns Are Updated Successfully.",
            });
          }, 1000);
        })
        .catch(function(error) {
          vm.resetBulkColumns();
          vm.$data.bulk_select = 0;
          setTimeout(() => {
            vm.$swal({
              showConfirmButton: false,
              icon: "error",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "No Columns Or Rows Were Selected.",
            });
          }, 1000);
        });
    },
    applyBulkDelete() {
      const vm = this;
      let formData = new FormData();
      formData.append("voucher_ids", this.getIds);
      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/vouchers/deletes`,
      })
        .then((response) => {
          this.resetBulkColumns();
          /** Purposefully set to 0 (integer) to fail the prop check so subcomponents become reactive to change */
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
        .catch(function(error) {
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
        text: "Please confirm if you want to delete the vouchers.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.applyBulkDelete();
        }
      });
    },
  },
  created() {
    let vm = this;

    this.voucherViews();
    this.voucherListing();
  },
  mounted() {
    this.getAllUsers();
    if (Number.isInteger(parseInt(this.getCurrentEntity))) {
      let rows = document.querySelector("#deal_" + this.getCurrentEntity);

      // line is zero-based
      // line is the row number that you want to see into view after scroll
      rows.style.background = "#e7e7e7";
      rows.scrollIntoView({
        behavior: "smooth",
        block: "center",
      });
    }

    var input = document.getElementById("searchText");

    input.addEventListener("keydown", function(event) {
      if (event.keyCode === 13) {
        event.preventDefault();

        this.searchAction();
      }
    });
  },
};
</script>

<style scoped></style>
