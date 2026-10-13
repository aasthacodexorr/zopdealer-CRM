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
            style="background-color: transparent; z-index: 2"
          >
            <div class="content-wrapper pt-0 pb-1 mt-0">
              <div class="card shadow-none mb-0">
                <div class="row">
                  <div class="d-none d-sm-none d-md-block col-md-3">
                    <ul
                      class="ml-0 mt-1 list-group list-group-horizontal"
                      v-if="getReviewIds.length == 0"
                    >
                      <li class="d-inline-block mr-1">
                        <div
                          class="
                              btn-group
                              mb-0
                              drop-value-set
                              list-size-fillter
                            "
                        >
                          <div class="dropdown">
                            <div class="dropdown">
                              <button
                                class="
                                    btn btn-outline-light
                                    dropdown-toggle
                                    mr-1
                                  "
                                type="button"
                                id="dropdownMenuButton"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                {{ selected_review_view }}
                              </button>
                              <div
                                class="dropdown-menu"
                                aria-labelledby="dropdownMenuButton"
                              >
                                <p class="border-bottom">
                                  <strong>Views</strong>
                                </p>
                                <a
                                  style="cursor: pointer"
                                  class="dropdown-item"
                                  v-show="review_view.default == '1'"
                                  v-for="review_view in review_views"
                                  :key="review_view.id"
                                  @click="changeReviewView(review_view.id)"
                                  >{{ review_view.header }}</a
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
                                    v-show="review_view.default == '0'"
                                    v-for="review_view in review_views"
                                    :key="review_view.id"
                                    @click="changeReviewView(review_view.id)"
                                    >{{ review_view.header }}
                                  </a>
                                </span>
                                <p class="border-top">
                                  <router-link
                                    tag="a"
                                    :to="{ name: 'review-view' }"
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
                      <div class="d-inline-block mr-1">
                        <ul class="bulk-list" style="margin-bottom: 0px">
                          <!-- Bulk Update : START -->
                          <review-bulk-update></review-bulk-update>
                          <!-- Bulk Update : END -->
                          <li class="mr-1 d-none">
                            <select class="form-control btn-outline-primary">
                              <option selected="" disabled="">
                                Bulk Actions
                              </option>
                              <option>Print Window Sticker</option>
                              <option>Print Buyer Guide</option>
                            </select>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <!-- end bulk update buttons -->
                  </div>
                  <div class="col-md-4 col-xs-12 col-xl-5">
                    <!-- Search : START -->
                    <review-search></review-search>
                    <!-- Search : END -->
                  </div>
                  <div class="col-md-5 col-xl-4">
                    <ul class="mt-1 mt-1">
                      <li class="d-inline-block">
                        <div
                          class="btn-feild-box"
                          style="top: 16px; right: 15px"
                        >
                          <ul>
                            <!-- Filter : START -->
                            <review-list-filter></review-list-filter>
                            <!-- Filter : END -->
                            <li class="d-inline-block mr-1">
                              <review-create></review-create>
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
                                  class="
                                      bx bx-dots-vertical-rounded
                                      font-medium-3
                                      dropdown-toggle
                                      nav-hide-arrow
                                      cursor-pointer
                                      list-space
                                      border
                                    "
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
                                    >Import Review</a
                                  >
                                </div>
                              </div>
                            </li>
                            <router-link
                              tag="li"
                              title="Settings"
                              :to="{ name: 'review-settings' }"
                              class="d-inline-block"
                              exact
                            >
                              <span
                                style="cursor: pointer"
                                class="list-space border bx bxs-cog"
                              ></span>
                            </router-link>
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
                  <!-- Draggable List : CLOSE -->
                </div>
              </div>
            </div>
          </div>

          <!-- Bulk Update : START -->
          <!-- Bulk Update : END -->

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
                        <th style="min-width: 100px" class="left-set">
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            v-if="getSortColumn.hasOwnProperty('name')"
                          >
                            <span
                              @click="updateSortColumn('name', 'ASC')"
                              v-if="getSortColumn['name'] == 'DESC'"
                              >Name <i class="bx bx-sort-down"></i>
                            </span>
                            <span
                              @click="updateSortColumn('name', 'DESC')"
                              v-else-if="getSortColumn['name'] == 'ASC'"
                              >Name <i class="bx bx-sort-up"></i>
                            </span>
                          </span>
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            @click="updateSortColumn('name', 'ASC')"
                            v-else
                            >Name <i class="bx bx-menu"></i>
                          </span>
                        </th>
                        <th style="min-width: 100px" class="left-set">
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            v-if="
                              getSortColumn.hasOwnProperty('review_date_time')
                            "
                          >
                            <span
                              @click="
                                updateSortColumn('review_date_time', 'ASC')
                              "
                              v-if="getSortColumn['review_date_time'] == 'DESC'"
                              >Date <i class="bx bx-sort-down"></i>
                            </span>
                            <span
                              @click="
                                updateSortColumn('review_date_time', 'DESC')
                              "
                              v-else-if="
                                getSortColumn['review_date_time'] == 'ASC'
                              "
                              >Date <i class="bx bx-sort-up"></i>
                            </span>
                          </span>
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            @click="updateSortColumn('review_date_time', 'ASC')"
                            v-else
                            >Date <i class="bx bx-menu"></i>
                          </span>
                        </th>
                        <th style="min-width: 100px" class="left-set">
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            v-if="getSortColumn.hasOwnProperty('rating')"
                          >
                            <span
                              @click="updateSortColumn('rating', 'ASC')"
                              v-if="getSortColumn['rating'] == 'DESC'"
                              >Rating <i class="bx bx-sort-down"></i>
                            </span>
                            <span
                              @click="updateSortColumn('rating', 'DESC')"
                              v-else-if="getSortColumn['rating'] == 'ASC'"
                              >Rating <i class="bx bx-sort-up"></i>
                            </span>
                          </span>
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            @click="updateSortColumn('rating', 'ASC')"
                            v-else
                            >Rating <i class="bx bx-menu"></i>
                          </span>
                        </th>
                        <th class="text-center" style="min-width: 400px">
                          COMMENT
                        </th>
                        <template v-for="element in list">
                          <th
                            class="text-center"
                            style="min-width: 150px"
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
                      <review-item
                        v-for="item in getEntities"
                        :item="item"
                        :time_zone="timeZoneName"
                        :childCheck="bulk_select"
                        :app_metadata="app_metadata"
                        v-bind:key="item.id + '_item_listing_view'"
                      ></review-item>
                    </tbody>
                  </table>
                </div>
                <!-- datatable ends -->

                <!-- Navigation Pagination : START -->
                <div class="row content-wrapper mt-1 pt-0">
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
import ReviewItem from "./ReviewItem.vue";
import { mapGetters, mapActions } from "vuex";
import Toast from "../../library/Toast";
import vSelect from "vue-select";
import ReviewListFilter from "./ReviewListFilter";
import ReviewSearch from "./ReviewSearch";
import ReviewBulkUpdate from "./ReviewBulkUpdate";
import ReviewCreate from "./ReviewCreate";

export default {
  props: {
    application: { required: true },
    app_metadata: { required: true },
    user_metadata: { required: true },
  },
  name: "ReviewList",
  components: {
    "v-select": vSelect,
    ReviewItem,
    draggable,
    ReviewListFilter,
    ReviewSearch,
    ReviewBulkUpdate,
    ReviewCreate,
  },
  data() {
    return {
      load: false,
      page: 1,
      bulk_select: "0",
      review_listing: "",
      pager: "",
      review_views: [],
      review_view_id: 1,
      dragging: true,
      review_view_flag: 1,
      review_view_default: [],
      sort_column: { created_at: "DESC" },
      search: false,
      search_string: "",
      entity_base_views_api: "/api/business/reviews/views",
      entity_base_user_view_api: "/api/business/user/review",
    };
  },
  computed: {
    ...mapGetters("review", ["getReviewIds", "getTypes", "getCategories"]),
    ...mapGetters("reviewView", [
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
    timeZoneName() {
      return this.user_metadata && this.user_metadata.hasOwnProperty("timezone")
        ? this.user_metadata.timezone
        : "Canada/Eastern";
    },
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost",
      };
    },
    selected_review_view() {
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
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
  },
  methods: {
    ...mapActions("review", ["removeEveryEntityId", "addEveryEntityId"]),
    ...mapActions("reviewView", [
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
    changeCompact() {
      this.commitCompactFlag(!this.getCompact);
    },
    unsavedView() {
      const vm = this;
      //check for the flag if flag is 0 that means the pop up is there and dont do any thing.
      // If flag is 1 that means reset the value to 0, show the popup
      if (this.review_view_flag == 1) {
        this.review_view_flag = 0;
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
              JSON.parse(JSON.stringify(this.review_view_default))
            );
          } else if (result.hasOwnProperty("value") && result.value) {
            //Save the review view.
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
                `${this.entity_base_views_api}/update/${this.getViewId}`,
            }).then((response) => {
              this.$swal({
                showConfirmButton: false,
                icon: "success",
                toast: true,
                timer: 2000,
                position: "bottom",
                title: "Review View Changed.",
              });
            });
          }
          this.review_view_flag = 1;
        });
      }
    },
    updateSortColumn(column, order) {
      if (this.getSearch || this.getFilterState) {
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
          `${this.entity_base_views_api}/update/${this.getViewId}`,
      }).then((response) => {
        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: "Review View Changed.",
        });
        this.commitLoadState(false);
        vm.entityListing();
      });
    },
    changeReviewView(id) {
      this.commitSearchString("");
      this.commitSearchState(false);
      this.commitFilterState(false);
      this.commitFilters({ name: "type", value: "" });
      this.commitFilters({ name: "category", value: "" });

      //this.getViewId = id;
      let vm = this;
      this.clearBulkSelection();
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin + `${this.entity_base_views_api}/result/${id}`,
      }).then((response) => {
        this.commitViewId(response.data.review_view_id);
        this.commitViewTitle(response.data.header);
        this.commitViewEntities(response.data.review_listing);
        this.commitRequestUrl(
          window.location.origin + `${this.entity_base_views_api}/result/${id}`
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
        this.review_view_default = JSON.parse(
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
            `${this.entity_base_user_view_api}/update?id=${this.getViewId}`,
        }).then((response) => {
          this.$swal({
            showConfirmButton: false,
            icon: "success",
            toast: true,
            timer: 2000,
            position: "bottom",
            title: "Review View Changed.",
          });
        });
      });
    },

    pagination(n = 1) {
      const vm = this;
      let url = window.location.origin;
      if (this.getSearch || this.getFilterState) {
        url += `/api/business/reviews?all=true`;

        if (this.getSearch) {
          url += `&search=${this.getSearchString}`;
        }

        if (this.getFilterState) {
          for (const key in this.getFilters) {
            if (this.getFilters.hasOwnProperty(key)) {
              if (this.getFilters[key]) {
                url += `&zm_${key}[]=${this.getFilters[key]}`;
              }
            }
          }
        }

        url += `&page=`;
      } else {
        url += `${this.entity_base_views_api}/result/${this.getViewId}?page=`;
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
        //this.commitViewId(response.data.review_view_id);
        //this.commitViewTitle(response.data.header);
        this.commitViewEntities(response.data.review_listing);
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
        //this.commitSortColumn(JSON.parse(JSON.stringify(response.data.sort)));
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
        //this.commitViewId(response.data.review_view_id);
        //this.commitViewTitle(response.data.header);
        this.commitViewEntities(response.data.review_listing);
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
        //this.commitSortColumn(JSON.parse(JSON.stringify(response.data.sort)));
      });
    },
    reviewViews() {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `${this.entity_base_views_api}`,
      }).then((response) => {
        vm.$data.review_views = response.data.review_views;
      });
    },
    async entityListing(refresh = false) {
      let vm = this;

      if (refresh || !this.getLoadState) {
        await axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: window.location.origin + this.entity_base_user_view_api,
        }).then((response) => {
          this.commitViewId(response.data.review_view_id);
          this.commitViewTitle(response.data.header);
          this.commitViewEntities(response.data.review_listing);
          this.commitRequestUrl(
            window.location.origin + this.entity_base_user_view_api
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
          this.review_view_default = JSON.parse(
            JSON.stringify(Object.values(response.data.view))
          );
        });

        this.commitLoadState(true);
      }
    },
    clearBulkSelection() {
      this.bulk_select = "0";
      this.removeEveryEntityId();
    },
  },
  created() {
    //axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    let vm = this;
    moment.tz.setDefault(this.timeZoneName);
    this.reviewViews();
    this.entityListing();
    //this.review_view_default =JSON.parse(JSON.stringify(this.list));
  },
  mounted() {
    const vm = this;
    Event.listen("reset_listing_page", (data) => {
      vm.entityListing(true);
    });

    Event.listen("reset_selected_rows", (data) => {
      /** Purposefully set to 0 (integer) to fail the prop check so subcomponents become reactive to change */
      vm.$data.bulk_select = 0;
    });

    Event.listen("reset_current_pagination", (data) => {
      vm.pagination(vm.getCurrentPage);
    });

    if (Number.isInteger(parseInt(this.getCurrentEntity))) {
      let rows = document.querySelector("#review_" + this.getCurrentEntity);

      // line is zero-based
      // line is the row number that you want to see into view after scroll
      rows.style.background = "#e7e7e7";
      rows.scrollIntoView({
        behavior: "smooth",
        block: "center",
      });
    }
  },
  beforeDestroy() {
    Event.vue.$off("reset_listing_page", null);
    Event.vue.$off("reset_selected_rows", null);
    Event.vue.$off("reset_current_pagination", null);
  },
};
</script>

<style lang="scss">
.compact > table.list-page-content-p th,
.compact > table.list-page-content-p th {
  padding: 2px !important;
  margin: 0px !important;
  min-width: auto !important;
  width: auto !important;
  font-size: 0.9em;
  span,
  a {
    font-size: 0.9em;
  }
}

.compact > table.list-page-content-p th,
.compact > table.list-page-content-p td {
  padding: 2px !important;
  margin: 0px !important;
  min-width: auto !important;
  width: auto !important;
  font-size: 0.9em;
  span,
  a {
    font-size: 0.9em;
  }
}
</style>
