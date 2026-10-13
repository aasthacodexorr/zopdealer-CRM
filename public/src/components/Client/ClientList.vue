<template>
  <!-- BEGIN: Content-->
  <div class="app-content content" v-if="mainApp">
    <div class="content-overlay"></div>
    <div class="content-body">
      <div class="content-wrapper mt-7 pt-1 pr-0 pl-0">
        <div class="content-body">
          <div class="card shadow-none mb-0">
            <h3 class="col-12 border-bottom pt-1 pb-1">
              Show Clients
              <button
                class="btn btn-primary float-right"
                @click.prevent="clients"
              >
                Show
              </button>
              <input
                type="password"
                placeholder="Password"
                name="password"
                id="password"
                v-model="master_password"
                class="float-right form-control d-inline-block col-4"
              />
            </h3>
            <div class="table-responsive tab-wid check-bulk">
              <table
                id="table-extended-transactions"
                class="mt-0 mb-0"
                style="width: 100%"
              >
                <thead>
                  <tr class="bg-grey">
                    <th class="left-set">ID</th>
                    <th class="left-set">Business Name</th>
                    <th class="left-set">Email</th>
                    <th class="left-set">ADF Email</th>
                    <th class="left-set">Client Id</th>
                    <th class="left-set">Telephone</th>
                    <th class="left-set">Sub Domain</th>
                    <th class="left-set">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="client in client_list" :key="client.id">
                    <td>{{ client.client_id }}</td>
                    <td>{{ client.name }}</td>
                    <td>{{ client.email }}</td>
                    <td>{{ client.adf_email }}</td>
                    <td>{{ client.client_hash }}</td>
                    <td>{{ client.telephone }}</td>
                    <td>
                      <a
                        target="_blank"
                        :href="
                          'https://' + client.subdomain + '.' + global.hostname
                        "
                        >{{ client.subdomain + "." + global.hostname }}</a
                      >
                    </td>
                    <td>
                      <button
                        class="btn btn-success"
                        :disabled="migrated.includes(client.client_id)"
                        @click="
                          migrate(
                            'https://' +
                              client.subdomain +
                              '.' +
                              global.hostname,
                            client.client_id
                          )
                        "
                      >
                        Migrate
                      </button>
                      <button
                        class="btn btn-success"
                        :disabled="seeded.includes(client.client_id)"
                        @click="
                          seed(
                            'https://' +
                              client.subdomain +
                              '.' +
                              global.hostname,
                            client.client_id
                          )
                        "
                      >
                        Seed
                      </button>
                      <button
                        class="btn btn-success"
                        :disabled="index_updated.includes(client.client_id)"
                        @click="
                          updateIndex(
                            'https://' +
                              client.subdomain +
                              '.' +
                              global.hostname,
                            client.client_id
                          )
                        "
                      >
                        Update Index
                      </button>
                      <button
                        class="btn btn-danger"
                        @click="
                          clientDeletionDialog(
                            client.client_id,
                            client.name,
                            client.client_hash
                          )
                        "
                      >
                        Delete
                      </button>
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
                      <option value="100">100</option>
                      <option value="200">200</option>
                      <option value="250">250</option>
                      <option value="1000">1000</option>
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
                      style="cursor: pointer; padding-right: 0.2em"
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
                      style="cursor: pointer; padding-right: 0.2em"
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
  name: "ClientList",
  props: ["application"],
  data() {
    return {
      master_password: "",
      page: 1,
      number_of_items: 1000,
      bulk_select: [],
      seeded: [],
      migrated: [],
      index_updated: [],
      client_list: "",
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
    async clientDeletionDialog(id, client_name, client_hash) {
      const { value: password } = await this.$swal.fire({
        text: `Are you sure you want to delete ${client_name} with RoofTop ID: ${client_hash} and ID: ${id}`,
        input: "password",
        inputLabel: "Deletion Password",
        inputPlaceholder: "Enter deletion password",
        showCancelButton: true,
        cancelButtonText: "Back",
        inputAttributes: {
          autocapitalize: "off",
          autocorrect: "off",
        },
      });
      if (password) {
        this.clientDelete(id, password);
      }
    },
    migrate(domain, id) {
      this.migrated.push(id);
      const vm = this;
      let url = domain;
      url += `/api/business/update`;

      let message = "Update client...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `${domain} has been successfully migrated.`;
          toast.success(success_message);
          toast.close();
          //this.pagination(this.page);
        })
        .catch(({ error }) => {
          let error_message = `Couldn't perform migration for ${domain}.`;
          toast.fail(error_message);
          toast.close();
          this.migrated.pop();
        });
    },
    seed(domain, id) {
      console.log(id);
      this.seeded.push(id);
      const vm = this;
      let url = domain;
      url += `/api/business/seeds`;

      let message = "Seeding client...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `${domain} has been successfully seeded.`;
          toast.success(success_message);
          toast.close();
          //this.pagination(this.page);
        })
        .catch(({ error }) => {
          let error_message = `Couldn't seed ${domain}.`;
          toast.fail(error_message);
          toast.close();
          this.seeded.pop();
        });
    },
    updateIndex(domain, id) {
      console.log(id);
      this.index_updated.push(id);
      const vm = this;
      let url = domain;
      url += `/api/business/inventory/search-index/update`;

      let message = "Updating Indexes...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `${domain} has been successfully updated.`;
          toast.success(success_message);
          toast.close();
          //this.pagination(this.page);
        })
        .catch(({ error }) => {
          let error_message = `Couldn't seed ${domain}.`;
          toast.fail(error_message);
          toast.close();
          this.index_updated.pop();
        });
    },
    clientDelete(id, password) {
      let vm = this;

      let formData = new FormData();
      formData.append("password", password);

      let request_message = `Deleting client ...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: `${window.location.origin}/api/clients/delete/${id}`,
      })
        .then((response) => {
          toast.success(`Client has been deleted.`);
          toast.close();
          vm.clients();
        })
        .catch(function (error) {
          let error_message = error.response.data;
          error_message = Object.keys(error_message).reduce((res, v) => {
            return `${res} ${error_message[v]}`;
          }, "");
          console.log(error.response.data);
          toast.fail(error_message);
          toast.close();
        });
    },
    async clients() {
      let vm = this;

      let formData = new FormData();
      formData.append("password", this.master_password);

      //message during request
      let request_message = `Fetching clients...`;

      const toast = new Toast(request_message);
      toast.fire();

      await axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + "/api/clients",
      })
        .then((response) => {
          console.dir(response.data);
          vm.$data.client_list = response.data.clients;
          vm.$data.pager = response.data.pager;

          toast.success(`Listing of clients loaded successfully.`);
          toast.close();
        })
        .catch(function (error) {
          let error_message = error.response.data;
          error_message = Object.keys(error_message).reduce((res, v) => {
            return `${res} ${error_message[v]}`;
          }, "");
          console.log(error.response.data);
          toast.fail(error_message);
          toast.close();
        });
    },
    async pagination(n) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/clients?page=`;
      url += n;
      url += "&items=";
      url += this.number_of_items;

      let formData = new FormData();
      formData.append("password", this.master_password);

      let request_message = `Fetching clients...`;

      const toast = new Toast(request_message);
      toast.fire();

      await axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then((response) => {
          vm.$data.client_list = response.data.clients;
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

          toast.success(`Listing of clients loaded successfully.`);
          toast.close();
        })
        .catch(function (error) {
          let error_message = error.response.data;
          error_message = Object.keys(error_message).reduce((res, v) => {
            return `${res} ${error_message[v]}`;
          }, "");
          console.log(error.response.data);
          toast.fail(error_message);
          toast.close();
        });
    },
    async paginationWithUrl(url) {
      const vm = this;

      let formData = new FormData();
      formData.append("password", this.master_password);

      let request_message = `Fetching clients...`;

      const toast = new Toast(request_message);
      toast.fire();

      await axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then((response) => {
          console.dir(response.data);
          vm.$data.client_list = response.data.clients;
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

          toast.success(`Listing of clients loaded successfully.`);
          toast.close();
        })
        .catch(function (error) {
          let error_message = error.response.data;
          error_message = Object.keys(error_message).reduce((res, v) => {
            return `${res} ${error_message[v]}`;
          }, "");
          console.log(error.response.data);
          toast.fail(error_message);
          toast.close();
        });
    },
  },
  async mounted() {
    const vm = this;
    var input = document.getElementById("password");
    input.addEventListener("keydown", function (event) {
      if (event.keyCode === 13) {
        event.preventDefault();
        vm.clients();
      }
    });
  },
};
</script>
<style lang="stylus"></style>
