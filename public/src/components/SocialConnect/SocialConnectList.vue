<template>
  <!-- BEGIN: Content-->
  <div class="app-content content" v-if="isAuthorized">
    <div class="content-overlay"></div>
    <!-- form layout section start -->
    <div class="content-body">
      <form class="webapp-form">
        <section id="table-transactions">
          <div class="border-bottom mt-3 bg-white header-navbar set-dable-layer" style="background-color: transparent">
            <div class="content-wrapper pt-0 pb-1 mt-0">
              <div class="card shadow-none mb-0">
                <div class="row">
                  <div class="d-none d-sm-none d-md-block col-md-3">
                    <!-- bulk update buttons -->
                    <div class="ml-0 mt-1 list-group list-group-horizontal" role="alert" style="visibility: hidden">
                      <div class="d-inline-block mr-1">
                        <ul class="bulk-list" style="margin-bottom: 0px"></ul>
                      </div>
                    </div>
                    <!-- end bulk update buttons -->
                  </div>
                  <div class="col-md-5 col-xs-12" style="position: relative; top: -0.5em"></div>
                  <div class="col-md-4 col-12">
                    <ul class="ml-1 mt-1">
                      <li class="d-inline-block">
                        <div class="btn-feild-box" style="top: 16px; right: 15px">
                          <ul>
                            <li class="d-none d-sm-none d-md-inline-block">
                              <span href="#" class="list-space border bx bxs-grid" data-toggle="modal"
                                data-target="#grid-list" title="Modify View"></span>
                            </li>
                            <router-link tag="li" title="Settings" :to="{ name: 'settings-account' }"
                              class="d-inline-block" exact>
                              <span style="cursor: pointer" class="list-space border bx bxs-cog"></span>
                            </router-link>
                            <li class="d-inline-block" @click="changeCompact">
                              <span title="Compact View" class="list-space border bx" :class="
                                getCompact ? 'bx-expand' : 'bx-collapse'
                              "></span>
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
          <div class="modal fade text-left" id="grid-list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel19"
            aria-hidden="true">
            <div class="
                modal-dialog
                modal-dialog-centered
                modal-dialog-scrollable
                modal-sm
              " role="document">
              <div class="modal-content">
                <div class="modal-header pl-1 pr-1">
                  <h3 class="content-header-title" id="myModalLabel19">
                    Column List
                  </h3>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                  </button>
                </div>
                <div class="modal-body p-0">
                  <!-- Draggable List : OPEN -->
                  <draggable tag="ul" :list="list" v-bind="dragOptions" @start="isDragging = true"
                    @end="isDragging = false" class="list-group" @change="unsavedView" handle=".handle">
                    <transition-group type="transition" name="flip-list">
                      <li class="list-group-item" v-for="(element, idx) in list" :key="element.header">
                        <span style="cursor: pointer" class="bx bx-grid-vertical pr-1 handle"></span>
                        <div class="checkbox">
                          <input type="checkbox" @change="unsavedView" v-model="element.visibility"
                            class="checkbox-input" value="photodrag" :id="'dragging_' + idx" :true-value="'1'"
                            :false-value="'0'" />
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
                <div class="table-responsive tab-wid check-bulk" :class="getCompact ? 'compact' : ''"
                  style="height: 70vh">
                  <table id=" transactions" class="list-page-content-p mt-0 mb-0">
                    <thead>
                      <tr class="bg-grey">
                        <th style="min-width: 200px"></th>
                        <template v-for="element in list">
                          <th class="text-center" style="min-width: 100px" :style="{
                            'min-width':
                              element.header == 'Facebook Status' ||
                                element.header == 'Instagram Status' ||
                                element.header == 'Google Status'
                                ? '150px'
                                : '200px',
                          }" :key="element.header" v-if="element.visibility == '1'">
                            <span style="cursor: pointer" class="btn btn-link p-0" v-if="
                              getSortColumn.hasOwnProperty(element.column)
                            ">
                              <span @click="updateSortColumn(element.column, 'ASC')"
                                v-if="getSortColumn[element.column] == 'DESC'">{{ element.header }}
                                <i class="bx bx-sort-down"></i>
                              </span>
                              <span @click="
                                updateSortColumn(element.column, 'DESC')
                              " v-else-if="getSortColumn[element.column] == 'ASC'">{{ element.header }}
                                <i class="bx bx-sort-up"></i>
                              </span>
                            </span>
                            <span style="cursor: pointer" class="btn btn-link p-0"
                              @click="updateSortColumn(element.column, 'ASC')" v-else>{{ element.header }} <i
                                class="bx bx-menu"></i>
                            </span>
                          </th>
                        </template>
                      </tr>
                    </thead>
                    <tbody>
                      <social-connect-item v-for="item in getEntities" :item="item" :time_zone="timeZoneName"
                        :childCheck="bulk_select" v-bind:key="item.inventory_id + '_item_listing_view'">
                      </social-connect-item>
                    </tbody>
                  </table>
                </div>
                <!-- datatable ends -->

                <!-- Navigation Pagination : START -->
                <div class="row content-wrapper pt-0 mt-1">
                  <div class="col-sm-4">
                    <div class="dataTables_info" id="table-extended-transactions_info" role="status" aria-live="polite">
                    </div>
                  </div>
                  <div class="col-sm-5 text-right d-list-box">
                    <div class="dataTables_length" id="table-extended-transactions_length">
                      <label>
                        <select v-model="getNumberOfEntities" name="table-extended-transactions_length"
                          aria-controls="table-extended-transactions" class="
                            custom-select custom-select-sm
                            form-control form-control-sm
                          ">
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
                    <div class="dataTables_paginate paging_simple_numbers" id="table-extended-transactions_paginate">
                      <ul class="pagination">
                        <li style="cursor: pointer; padding-right: 0.2em" class="paginate_button page-item previous"
                          @click="paginationWithUrl(getPager.previous)" :class="{ disabled: getPager.previous == null }"
                          id="table-extended-transactions_previous">
                          <a aria-controls="table-extended-transactions" data-dt-idx="0" tabindex="0"
                            class="page-link">&lt;</a>
                        </li>
                        <li style="cursor: pointer; padding-right: 0.2em" class="paginate_button page-item"
                          :class="{ active: n === getCurrentPage }" v-for="n in getPager.pageCount" v-show="
                            n >= getCurrentPage - 2 && n <= getCurrentPage + 2
                          " :key="'nav_id' + n">
                          <a aria-controls="table-extended-transactions" @click="pagination(n)" :data-dt-idx="n"
                            tabindex="0" class="page-link">{{ n }}</a>
                        </li>
                        <li style="cursor: pointer; padding-right: 0.2em" class="paginate_button page-item next"
                          :class="{ disabled: getPager.next == null }" id="table-extended-transactions_next">
                          <a aria-controls="table-extended-transactions" @click="paginationWithUrl(getPager.next)"
                            data-dt-idx="3" tabindex="0" class="page-link">&gt;</a>
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
import SocialConnectItem from "./SocialConnectItem.vue";

export default {
  props: ["application", "app_metadata"],
  name: "SocialConnectList",
  components: {
    "v-select": vSelect,
    SocialConnectItem,
    draggable,
  },
  data() {
    return {
      load: false,
      page: 1,
      number_of_items: 25,
      bulk_select: "0",
      social_connect_heading: "Social Connect",
      social_connect_listing: "",
      pager: "",
      social_connect_view_id: 1,
      dragging: true,
      social_connect_view_flag: 1,
      social_connect_view_default: [],
      sort_column: { created_at: "DESC" },
    };
  },
  computed: {
    ...mapGetters("SocialConnectView", [
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
    ...mapActions("SocialConnectView", [
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
      if (this.social_connect_view_flag == 1) {
        this.social_connect_view_flag = 0;
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
              JSON.parse(JSON.stringify(this.social_connect_view_default))
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
                `/api/business/facebook-social/views/update/${this.getViewId}`,
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
          this.social_connect_view_flag = 1;
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
          `/api/business/facebook-social/views/update/${this.getViewId}`,
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
        vm.socialConnectListing();
      });
    },
    pagination(n = 1) {
      const vm = this;
      let url = window.location.origin;

      if (this.getSearch) {
        url += `/api/business/facebook-social?search=${this.getSearchString}&page=`;
      } else {
        url += `/api/business/user/social-connect?page=`;
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
        this.commitViewEntities(response.data.social_connect_listing);
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
        this.commitViewEntities(response.data.social_connect_listing);
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
    socialConnectListing(refresh = false) {
      let vm = this;

      if (refresh || !this.getLoadState) {
        axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: window.location.origin + `/api/business/user/social-connect`,
        }).then((response) => {
          this.commitViewId(response.data.social_connect_view_id);
          this.commitViewTitle(response.data.header);
          this.commitViewEntities(response.data.social_connect_listing);
          this.commitRequestUrl(
            window.location.origin + `/api/business/user/social-connect`
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
          this.social_connect_view_default = JSON.parse(
            JSON.stringify(Object.values(response.data.view))
          );
        });
        this.commitLoadState(true);
      }
    },
  },
  created() {
    //axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    let vm = this;
    moment.tz.setDefault(this.timeZoneName);
    this.socialConnectListing();
    //this.social_connect_view_default =JSON.parse(JSON.stringify(this.list));
  },
  mounted() {
    if (Number.isInteger(parseInt(this.getCurrentEntity))) {
      let rows = document.querySelector(
        "#facebook_social_" + this.getCurrentEntity
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

<style scoped>
</style>