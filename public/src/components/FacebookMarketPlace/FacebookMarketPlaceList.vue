<template>
  <!-- BEGIN: Content-->
  <div class="app-content content" v-if="isAuthorized">
    <div class="content-overlay"></div>
    <!-- form layout section start -->
    <div class="content-body">
      <form class="webapp-form" id="facebook-marketplace-list">
        <section id="table-transactions">
          <div
            class="border-bottom mt-3 bg-white header-navbar set-dable-layer"
            style="background-color: transparent"
          >
            <div class="content-wrapper pt-0 pb-1 mt-0">
              <div class="card shadow-none mb-0">
                <div class="row">
                  <div class="col-md-6">
                    <!-- bulk update buttons -->
                    <div
                      class="ml-0 mt-1 list-group list-group-horizontal"
                      role="alert"
                      :style="{
                        display: getPostIds.length != 0 ? 'block' : 'none',
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
                    <h3
                      class="ml-1 mt-1 mb-0 font-weight-bold"
                      :style="{
                        display: getPostIds.length === 0 ? 'block' : 'none',
                      }"
                    >
                      Facebook Marketplace
                      <a
                        href="https://help.zopmedia.com/portal/en/kb/articles/understanding-inventories-posted-using-facebook-marketplace-posting-tool-fbmp"
                        target="_blank"
                        class="btn-link"
                        ><i class="bx bxs-help-circle bx-sm"></i
                      ></a>
                    </h3>
                  </div>
                  <div
                    class="col-md-2 col-xs-12"
                    style="position: relative; top: -0.5em"
                  ></div>
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
                                class="list-space border bx bxs-grid"
                                data-toggle="modal"
                                data-target="#grid-list"
                                title="Modify View"
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
                            <router-link
                              tag="li"
                              title="Settings"
                              :to="{ name: 'settings-account' }"
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
                            <li
                              class="d-inline-block"
                              style="cursor: pointer"
                              @click.prevent="purgeDeleteDialog"
                            >
                              <span
                                title="Delete Logs"
                                class="list-space border bx bxs-eraser"
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

          <!-- TODO: Change Bulk Grid -->
          <!-- bulk grid list modal -->

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
                        <th style="width: 100px"></th>
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
                      <facebook-market-place-item
                        v-for="item in getEntities"
                        :item="item"
                        :time_zone="timeZoneName"
                        :childCheck="bulk_select"
                        v-bind:key="item.inventory_id + '_item_listing_view'"
                      ></facebook-market-place-item>
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
                    ></div>
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
  </div>
  <!-- END: Content-->
</template>
<script>
import draggable from "vuedraggable";
import { mapGetters, mapActions } from "vuex";
import vSelect from "vue-select";
import FacebookMarketPlaceItem from "./FacebookMarketPlaceItem.vue";
import Toast from "../../library/Toast";

export default {
  props: ["application", "app_metadata"],
  name: "FacebookMarketPlaceList",
  components: {
    "v-select": vSelect,
    FacebookMarketPlaceItem,
    draggable,
  },
  data() {
    return {
      load: false,
      page: 1,
      number_of_items: 25,
      bulk_select: "0",
      facebook_marketplace_heading: "Facebook Marketplace",
      facebook_marketplace_listing: "",
      pager: "",
      facebook_marketplace_view_id: 1,
      dragging: true,
      facebook_marketplace_view_flag: 1,
      facebook_marketplace_view_default: [],
      sort_column: { created_at: "DESC" },
    };
  },
  computed: {
    ...mapGetters("facebookMarketPlace", ["getPostIds"]),
    ...mapGetters("facebookMarketPlaceView", [
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
  },
  methods: {
    ...mapActions("facebookMarketPlaceView", [
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
    changeCompact() {
      this.commitCompactFlag(!this.getCompact);
    },
    unsavedView() {
      const vm = this;
      //check for the flag if flag is 0 that means the pop up is there and dont do any thing.
      // If flag is 1 that means reset the value to 0, show the popup
      if (this.facebook_marketplace_view_flag == 1) {
        this.facebook_marketplace_view_flag = 0;
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
              JSON.parse(JSON.stringify(this.facebook_marketplace_view_default))
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
                `/api/business/facebook-marketplace/views/update/${this.getViewId}`,
            }).then((response) => {
              this.$swal({
                showConfirmButton: false,
                icon: "success",
                toast: true,
                timer: 2000,
                position: "bottom",
                title: "FBMP View Updated.",
              });
            });
          }
          this.facebook_marketplace_view_flag = 1;
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
          `/api/business/facebook-marketplace/views/update/${this.getViewId}`,
      }).then((response) => {
        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: "FBMP View Changed.",
        });

        this.commitLoadState(false);
        vm.facebookMarketPlaceListing();
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
        text: "Please confirm if you want to delete the facebook marketplace entries.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
          //@TODO   Reset the selection
        } else if (result.isConfirmed) {
          vm.applyBulkDelete();
        }
      });
    },
    applyBulkDelete() {
      const vm = this;
      let formData = new FormData();
      formData.append("post_ids", this.getPostIds);
      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin + `/api/business/facebook-marketplace/deletes`,
      })
        .then((response) => {
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
        .catch(function (error) {
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
    pagination(n = 1) {
      const vm = this;
      let url = window.location.origin;

      if (this.getSearch) {
        url += `/api/business/facebook-marketplace?search=${this.getSearchString}&page=`;
      } else {
        url += `/api/business/user/facebook-marketplace?page=`;
      }
      url += n;
      url += "&items=";
      url += this.getEntitiesCount;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data.facebook_marketplace_listing);
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
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data.facebook_marketplace_listing);
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
    facebookMarketPlaceListing(refresh = false) {
      let vm = this;

      if (refresh || !this.getLoadState) {
        axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url:
            window.location.origin + `/api/business/user/facebook-marketplace`,
        }).then((response) => {
          this.commitViewId(response.data.facebook_marketplace_view_id);
          this.commitViewTitle(response.data.header);
          this.commitViewEntities(response.data.facebook_marketplace_listing);
          this.commitRequestUrl(
            window.location.origin + `/api/business/user/facebook-marketplace`
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
          this.facebook_marketplace_view_default = JSON.parse(
            JSON.stringify(Object.values(response.data.view))
          );
        });
        this.commitLoadState(true);
      }
    },
    async purgeDeleteDialog() {
      const { value: option } = await this.$swal.fire({
        target: "#facebook-marketplace-list",
        title: "Bulk Deletion Options:",
        text: "Please be cautious while performing this action. This is irreversible process.",
        input: "radio",
        inputOptions: {
          1: "Delete All Logs",
          2: "Delete All Logs Without Post ID",
          3: "Delete All Logs With status DRAFT",
          4: "Delete All Logs With status PUBLISHED",
          5: "Delete All Logs With status UPDATED",
          6: "Delete All Logs With status TO BE UPDATED",
        },
        inputValidator: (value) => {
          if (!value) {
            return "Please select an option.";
          }
        },
      });
      if (option) {
        switch (option) {
          case "1":
            this.filterDataAndPurgeDelete();
            break;
          case "2":
            this.filterDataAndPurgeDelete("post_id", "");
            break;
          case "3":
            this.filterDataAndPurgeDelete("status", "DRAFT");
            break;
          case "4":
            this.filterDataAndPurgeDelete("status", "PUBLISHED");
            break;
          case "5":
            this.filterDataAndPurgeDelete("status", "UPDATED");
            break;
          case "6":
            this.filterDataAndPurgeDelete("status", "TO BE UPDATED");
            break;

          default:
            break;
        }
      }
    },
    filterDataAndPurgeDelete(column = "", value = "") {
      const vm = this;
      //create data
      let formData = new FormData();
      formData.append("column", column);
      formData.append("value", value);

      let request_url = `${window.location.origin}/api/business/facebook-marketplace/purge`;

      let request_message = "Deleting FBMP Logs...";

      const toast = new Toast(request_message);
      toast.fire();

      axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();

          this.pagination();
        })
        .catch(function (error) {
          let error_message = error.response.data;
          toast.fail(error_message);
          toast.close();
        });
    },
  },
  created() {
    let vm = this;
    moment.tz.setDefault(this.timeZoneName);
    this.facebookMarketPlaceListing();
  },
  mounted() {
    if (Number.isInteger(parseInt(this.getCurrentEntity))) {
      let rows = document.querySelector(
        "#facebook_marketplace_" + this.getCurrentEntity
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
#facebook-marketplace-list {
  .swal2-content {
    color: red;
    font-weight: 600;
  }
  .swal2-label {
    font-size: 0.9em;
  }
  .swal2-radio {
    flex-direction: column;
    align-items: start;

    label {
      text-align: left;
      position: relative;

      input[type="radio"] {
        position: absolute;
        left: -1.5em;
        top: 4px;
      }
    }
  }
}
</style>
