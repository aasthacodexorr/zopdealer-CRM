<template>
  <!-- BEGIN: Content-->
  <div
    class="app-content content"
    v-if="app_metadata.organization == 'zopdealer'"
  >
    <div class="content-overlay"></div>
    <div class="content-body">
      <div class="content-wrapper mt-7 pt-1 pr-0 pl-0">
        <div class="content-body">
          <div class="card shadow-none mb-0">
            <div class="row m-1 pt-0">
              <div class="col-12">
                <button
                  type="button"
                  class="btn btn-primary float-right"
                  data-toggle="modal"
                  data-target="#syndicationCompanyModel"
                >
                  <i class="bx bx-customize" style="padding-right: 1em;"></i>
                  Add New Syndication Company
                </button>
              </div>
            </div>
            <div class="table-responsive tab-wid check-bulk">
              <table
                id="table-extended-transactions"
                class="list-page-content-p mt-0 mb-0"
                style="width: 100%;"
              >
                <thead>
                  <tr class="bg-grey">
                    <th class="left-set">ID</th>
                    <th class="left-set" style="width:200px;"></th>
                    <th class="left-set">Label</th>
                    <th class="left-set">Slug</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="syndication_company in syndication_companies_list"
                    :key="syndication_company.id"
                  >
                    <td>{{ syndication_company.id }}</td>
                    <td>
                      <span
                        ><img
                          style="width:100px;"
                          :src="parseAbsoluteOrRelativeUrl(syndication_company.image_url)"
                          :alt="syndication_company.label"
                          class="img-responsive"
                          srcset=""
                      /></span>
                    </td>
                    <td>{{ syndication_company.label }}</td>
                    <td>{{ syndication_company.slug }}</td>
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
                      @change="pagination(1)"
                      v-model="number_of_items"
                      name="table-extended-transactions_length"
                      aria-controls="table-extended-transactions"
                      class="custom-select custom-select-sm form-control form-control-sm"
                    >
                      <option value="10">10</option>
                      <option value="25">25</option>
                      <option value="50">50</option>
                      <option value="100">100</option>
                      <option value="200">200</option>
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
    <!-- Modal -->
    <div
      class="modal fade"
      id="syndicationCompanyModel"
      tabindex="-1"
      role="dialog"
      aria-labelledby="syndicationCompanyModelLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              Add New Syndication Company
            </h5>
            <button
              type="button"
              id="syndicationCompanyModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <div class="custom-file">
                    <input
                      type="file"
                      class="form-control custom-file-input"
                      @change="changeImage"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('image_url'),
                      }"
                      @keyup="delete errors.image_url"
                      :title="errors.image_url"
                      id="customFile"
                    />
                    <label class="custom-file-label" for="customFile"
                      >Choose file</label
                    >
                  </div>
                </div>
                <div class="form-group col-md-12">
                  <div class="input-group">
                    <input
                      type="text"
                      id="label"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('label'),
                      }"
                      @keydown="delete errors.label"
                      :title="errors.label"
                      class="form-control"
                      v-model="form.label"
                      name=""
                      placeholder="Label"
                    />
                  </div>
                </div>
                <div class="form-group col-md-12">
                  <div class="input-group">
                    <input
                      type="text"
                      id="slug"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('slug'),
                      }"
                      @keydown="delete errors.slug"
                      :title="errors.slug"
                      class="form-control"
                      v-model="form.slug"
                      name=""
                      placeholder="Slug"
                    />
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click.prevent="saveSyndicationCompany"
            >
              Save
            </button>
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
  name: "SyndicationCompaniesList",
  props: ["application", "app_metadata"],
  data() {
    return {
      errors: [],
      page: 1,
      number_of_items: 25,
      syndication_companies_list: "",
      pager: "",
      global: window.global,
      form: {
        file: null,
        label: "",
        slug: "",
      },
    };
  },
  computed: {
    mainApp() {
      return this.application == "main";
    },
  },
  methods: {
    parseAbsoluteOrRelativeUrl(image_url){
      return this.$helpers.parseAbsoluteOrRelativeUrl(window.global.cdn, image_url) + '?width=200';
    },
    saveSyndicationCompany() {
      let message = "Saving new syndication company ...";
      const toast = new Toast(message);
      toast.fire();

      const vm = this;
      let formData = new FormData();

      if (this.form.file == null) {
        toast.fail("No file selected.");
        toast.close();
        return;
      }

      formData.append("file", this.form.file, this.form.file.name);
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && key != "file") {
          formData.append(key, this.form[key]);
        }
      }

      axios({
        method: "post",
        data: formData,
        url: window.location.origin + "/api/syndication/companies/create",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();

          vm.syndicationCompanyList();
          this.form.file = null;
          this.form.label = "";
          this.form.slug = "";

          document.getElementById("syndicationCompanyModel").click();
        })
        .catch((error) => {
          let returnObj = error.response.data;
          vm.$data.errors = error.response.data;

          for (const key in returnObj) {
            if (returnObj.hasOwnProperty(key)) {
              const element = returnObj[key];
              toast.fail(element);
            }
          }

          toast.close();
        });
    },
    changeImage($event) {
      this.form.file = $event.target.files[0];
    },
    syndicationCompanyList() {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + "/api/syndication/companies",
      }).then((response) => {
        vm.$data.syndication_companies_list =
          response.data.syndication_companies_list;
        vm.$data.pager = response.data.pager;
      });
    },
    pagination(n = 1) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/syndication/companies?page=`;
      url += n;
      url += "&items=";
      url += this.number_of_items;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        vm.$data.syndication_companies_list =
          response.data.syndication_companies_list;
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
    paginationWithUrl(url) {
      const vm = this;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        vm.$data.syndication_companies_list =
          response.data.syndication_companies_list;
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
  mounted() {
    this.syndicationCompanyList();
  },
};
</script>

<style lang="scss" scoped></style>
