<template>
  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-body">
      <div class="content-wrapper  pt-1 pr-0 pl-0">
        <div class="content-body">
          <div class="card shadow-none mb-0">
            <div class="row m-1 pt-0">
              <h2 class="col-12">
                Schedule Templates
              </h2>
            </div>
            <div class="col-12 table-responsive tab-wid check-bulk">
              <table
                id="table-extended-transactions"
                class="list-page-content-p mt-0 mb-0"
                style="width: 100%;"
              >
                <thead>
                  <tr class="bg-grey">
                    <th class="left-set">ID</th>
                    <th class="left-set">Next Schedule</th>
                    <th class="left-set">Timezone</th>
                    <th class="left-set">Title</th>
                    <th class="left-set">Module</th>
                    <th class="left-set">Frequency</th>
                    <th class="left-set">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="template in template_list" :key="template.id">
                    <td>{{ template.id }}</td>
                    <td>{{ formatDateTime(template.datetime) }}</td>
                    <td>{{ template.timezone }}</td>
                    <td>{{ template.title }}</td>
                    <td>{{ template.module.replace("_", " ") }}</td>
                    <td>{{ template.frequency }}</td>

                    <td>
                      <router-link
                        tag="button"
                        style="cursor: pointer;"
                        :to="{
                          name: 'schedule-template-update',
                          params: {
                            id: template.id,
                            module: template.module,
                            module_id: template.module_id,
                            page_title: template.title,
                          },
                        }"
                        class="btn btn-primary"
                        exact
                      >
                        <i class="bx bxs-edit-alt"></i>
                      </router-link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- datatable ends -->

            <!-- Navigation Pagination : START -->
            <div class="row content-wrapper pt-0">
              <div class="col-sm-4">
                <div
                  class="dataTables_info"
                  id="table-extended-transactions_info"
                  role="status"
                  aria-live="polite"
                >
                  Total Records {{ pager.total }}
                </div>
              </div>
              <div class="col-sm-5 text-right d-list-box">
                <div
                  class="dataTables_length"
                  id="table-extended-transactions_length"
                >
                  <label>
                    <select
                      @change="pagination"
                      v-model="number_of_items"
                      name="table-extended-transactions_length"
                      aria-controls="table-extended-transactions"
                      class="custom-select custom-select-sm form-control form-control-sm"
                    >
                      <option value="10">10</option>
                      <option value="25">25</option>
                      <option value="50">50</option>
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
                      style="cursor: pointer; padding-right: 0.2em;"
                      class="paginate_button page-item previous"
                      @click="paginationWithUrl(pager.previous)"
                      :class="{ disabled: pager.previous == null }"
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
                      style="cursor: pointer; padding-right: 0.2em;"
                      class="paginate_button page-item"
                      :class="{ active: n === page }"
                      v-show="n >= page - 2 && n <= page + 2"
                      v-for="n in pager.pageCount"
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
                      style="cursor: pointer; padding-right: 0.2em;"
                      class="paginate_button page-item next"
                      :class="{ disabled: pager.next == null }"
                      id="table-extended-transactions_next"
                    >
                      <a
                        aria-controls="table-extended-transactions"
                        @click="paginationWithUrl(pager.next)"
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
    </div>
  </div>
  <!-- END: Content-->
</template>
<script>
import Toast from "../../library/Toast";
export default {
  name: "TemplateList",
  props: ["application", "app_metadata"],
  data() {
    return {
      page: 1,
      number_of_items: 10,
      bulk_select: [],
      template_list: "",
      pager: "",
      global: window.global,
    };
  },
  computed: {
    mainApp() {
      return this.application == "main";
    },
  },
  methods: {
    formatString(emails) {
      return emails.split(",");
    },
    formatDateTime(datetime) {
      return this.$helpers.changeUTCtoLocalDate(
        datetime,
        "MM/DD/YYYY hh:mm A",
        "UTC"
      );
    },
    async schedule_template_listing() {
      let vm = this;
      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + "/api/business/schedule-templates",
      }).then((response) => {
        vm.$data.template_list = response.data.schedule_template_listing;
        vm.$data.pager = response.data.pager;
      });
    },
    async pagination(n) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/schedule-templates?page=`;
      url += n;
      url += "&items=";
      url += this.number_of_items;

      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        vm.$data.template_list = response.data.schedule_template_listing;
        vm.$data.pager = response.data.pager;
        vm.$data.page = vm.$data.pager.currentPage;
        vm.$data.pager.next =
          vm.$data.pager.next == null
            ? null
            : "/api" + vm.$data.pager.next.split("api")[1];
        vm.$data.pager.previous =
          vm.$data.pager.previous == null
            ? null
            : "/api" + vm.$data.pager.previous.split("api")[1];
      });
    },
    async paginationWithUrl(url) {
      const vm = this;

      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        vm.$data.template_list = response.data.schedule_template_listing;
        vm.$data.pager = response.data.pager;
        vm.$data.page = vm.$data.pager.currentPage;
        vm.$data.pager.next =
          vm.$data.pager.next == null
            ? null
            : "/api" + vm.$data.pager.next.split("api")[1];
        vm.$data.pager.previous =
          vm.$data.pager.previous == null
            ? null
            : "/api" + vm.$data.pager.previous.split("api")[1];
      });
    },
  },
  created() {
    this.schedule_template_listing();
  },
};
</script>
