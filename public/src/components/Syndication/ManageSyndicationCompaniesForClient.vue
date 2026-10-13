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
            <div class="table-responsive tab-wid check-bulk">
              <table
                id="table-extended-transactions"
                class="list-page-content-p mt-0 mb-0"
                style="width: 100%;"
              >
                <thead>
                  <tr class="bg-grey">
                    <th class="left-set">ID</th>
                    <th class="left-set" style="width: 200px;"></th>
                    <th class="left-set">Label</th>
                    <th class="left-set">Slug</th>
                    <th class="left-set">Inventory Actions</th>
                    <th class="left-set" style="width: 50px;">Global Actions</th>
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
                          style="width: 100px;"
                          :src="parseAbsoluteOrRelativeUrl(syndication_company.image_url)"
                          :alt="syndication_company.label"
                          class="img-responsive"
                          srcset=""
                      /></span>
                    </td>
                    <td>{{ syndication_company.label }}</td>
                    <td>{{ syndication_company.slug }}</td>
                    <td>
                      <button class="btn btn-success" @click="addSyndicationToAllInventory(syndication_company.slug)">Bulk Add</button>
                      <button class="btn btn-danger" @click="removeSyndicationFromAllInventory(syndication_company.slug)">Bulk Remove</button>
                    </td>
                    <td>
                      <span
                        v-if="
                          checkIfSydnicationIsActive(syndication_company.slug)
                        "
                      >
                        <button
                          class="btn btn-danger"
                          @click.prevent="
                            removeSyndication(syndication_company.slug)
                          "
                        >
                          Remove
                        </button>
                      </span>
                      <span v-else>
                        <button
                          class="btn btn-success"
                          @click.prevent="addSyndication(syndication_company)"
                        >
                          Add
                        </button>
                      </span>
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
  </div>
  <!-- END: Content-->
</template>

<script>
import Toast from "../../library/Toast";
export default {
  name: "ManageSyndicationCompaniesForClient",
  props: ["application", "app_metadata"],
  data() {
    return {
      errors: [],
      page: 1,
      number_of_items: 25,
      main_domain: "",
      syndication_companies_list: "",
      active_syndication: "",
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
    parseAbsoluteOrRelativeUrl(image_url){
      return this.$helpers.parseAbsoluteOrRelativeUrl(window.global.cdn, image_url) + '?width=200';
    },
    checkIfSydnicationIsActive(slug) {
      const vm = this;
      let response = false;
      if (this.active_syndication.length > 0) {
        this.active_syndication.some((x) => {
          if (x["slug"] == slug) {
            response = true;
            console.log(x["slug"]);
            console.log(slug);
            return true;
          }
        });
      }
      return response;
    },
    removeSyndication(slug) {
      let vm = this;
      let message = "Removing syndication company for client...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin + `/api/syndication/companies/remove/${slug}`,
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
          this.activeSyndication();
        })
        .catch((error) => {
          toast.fail(
            "Could not remove the syndication company for the client."
          );
          toast.close();
        });
    },
    removeSyndicationFromAllInventory(slug) {
      let vm = this;
      let message = "Removing syndication company from the inventories of the client...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin + `/api/syndication/companies/inventory/remove/${slug}`,
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
          this.activeSyndication();
        })
        .catch(({response}) => {
          toast.fail(
            response.data.errors
          );
          toast.close();
        });
    },
    addSyndicationToAllInventory(slug) {
      let vm = this;
      let message = "Adding syndication company to the inventories of the client...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin + `/api/syndication/companies/inventory/add/${slug}`,
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
          this.activeSyndication();
        })
        .catch(({response}) => {
          toast.fail(
           response.data.errors
          );
          toast.close();
        });
    },
    addSyndication(syndication_object) {
      let formData = new FormData();
      for (const key in syndication_object) {
        if (syndication_object.hasOwnProperty(key)) {
          formData.append(key, syndication_object[key]);
        }
      }

      let vm = this;
      let message = "Updating syndication companies for client...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "post",
        url: window.location.origin + `/api/syndication/companies/update`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
          this.activeSyndication();
        })
        .catch(({ response }) => {
          for (const key in response.data) {
            if (response.data.hasOwnProperty(key)) {
              const element = response.data[key];
              if (typeof element == "string") {
                toast.fail(element);
              }
            }
          }

          toast.close();
        });
    },
    activeSyndication() {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          "/api/business/configurations/active/syndication",
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (element["key"] == "syndication") {
              this.active_syndication = JSON.parse(element["value"]);
            }
          }
        }
      });
    },
    syndicationCompanyList() {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: this.main_domain + "/api/syndication/companies/all",
      }).then((response) => {
        vm.$data.syndication_companies_list =
          response.data.syndication_companies_list;
        vm.$data.pager = response.data.pager;
      });
    },
    pagination(n = 1) {
      const vm = this;
      let url = this.main_domain;
      url += `/api/syndication/companies/all?page=`;
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
    const main_domain = psl.parse(window.location.host);
    this.main_domain = "https://" + main_domain.domain;
    this.syndicationCompanyList();
    this.activeSyndication();
  },
};
</script>

<style lang="scss" scoped></style>
