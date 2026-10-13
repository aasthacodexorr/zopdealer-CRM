<template>
  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-body">
      <div class="content-wrapper pt-1 pr-0 pl-0">
        <div class="content-body">
          <div class="card shadow-none mb-0">
            <div class="row m-1 pt-0">
              <h2 class="col-12">
                DVI Template Items
              </h2>
              <div class="col-12">
                <button
                  v-show="
                    digital_vehicle_inspection_template_item_ids.length > 0
                  "
                  class="btn btn-outline-danger text-left"
                  style="cursor: pointer"
                  @click.prevent="confirmBulkDeleteAction"
                >
                  Delete
                </button>
                <router-link
                  tag="button"
                  style="cursor: pointer;"
                  :to="{ name: 'dvi-template-item-create' }"
                  class="btn btn-primary float-right"
                  exact
                >
                  <i class="bx bxs-notepad" style="padding-right: 1em"></i>
                  Create New Item
                </router-link>
              </div>
            </div>
            <div class="col-12 table-responsive tab-wid check-bulk">
              <table
                id="table-extended-transactions"
                class="list-page-content-p mt-0 mb-0"
                style="width: 100%"
              >
                <thead>
                  <tr class="bg-grey">
                    <th class="text-center" id="d-list" style="width: 40px">
                      <input
                        class="select_check"
                        type="checkbox"
                        v-model="bulk_select"
                        :true-value="'1'"
                        :false-value="'0'"
                      />
                    </th>
                    <th class="left-set">ID</th>
                    <th class="left-set">Title</th>
                    <th class="left-set" width="200px">Options</th>
                    <th class="left-set">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="template in template_list" :key="template.id">
                    <td class="text-center" id="d-list">
                      <input
                        class="select_check"
                        type="checkbox"
                        v-model="digital_vehicle_inspection_template_item_ids"
                        :value="template.id"
                      />
                    </td>
                    <td>{{ template.id }}</td>
                    <td>
                      <div class="h6">{{ template.title }}</div>
                      <div class="small">
                        {{ template.description | truncate(75) }}
                      </div>
                    </td>
                    <td>
                      <i
                        class="bx bx-comment bx-sm text-seconday m-auto"
                        alt="Comment has been enabled"
                        v-if="template.enable_comment == '1'"
                      ></i>
                      <i
                        class="bx bx-photo-album bx-sm text-seconday m-auto"
                        alt="Attachments has been enabled"
                        v-if="template.enable_attachments == '1'"
                      ></i>
                    </td>
                    <td>
                      <router-link
                        tag="button"
                        style="cursor: pointer"
                        :to="{
                          name: 'dvi-template-item-update',
                          params: {
                            id: template.id,
                          },
                        }"
                        class="btn btn-primary"
                        exact
                      >
                        <i class="bx bxs-edit-alt"></i>
                      </router-link>
                      <button
                        @click="
                          confirmDeleteAction(template.title, template.id)
                        "
                        class="btn btn-danger"
                      >
                        <i class="bx bxs-trash-alt"></i>
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
                      <option value="100">100</option>
                      <option value="200">200</option>
                      <option value="300">300</option>
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
  name: "DVITemplateItemList",
  props: ["application", "app_metadata"],
  data() {
    return {
      page: 1,
      number_of_items: 100,
      bulk_select: "0",
      template_list: [],
      pager: "",
      global: window.global,
      digital_vehicle_inspection_template_item_ids: [],
    };
  },
  watch: {
    bulk_select: function(newVal, oldVal) {
      if (newVal !== oldVal) {
        console.log(newVal);
        if (newVal == "1") {
          this.digital_vehicle_inspection_template_item_ids = this.template_list.map(
            (item) => item.id,
          );
        } else {
          this.digital_vehicle_inspection_template_item_ids = [];
        }
      }
    },
  },
  computed: {
    mainApp() {
      return this.application == "main";
    },
  },
  methods: {
    resetBulkColumns() {
      this.digital_vehicle_inspection_template_item_ids = [];
      this.bulk_select = "0";
    },
    applyBulkDelete() {
      const vm = this;
      let formData = new FormData();
      formData.append(
        "digital_vehicle_inspection_template_item_ids",
        this.digital_vehicle_inspection_template_item_ids,
      );
      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin + "/api/business/dvi-template-items/deletes",
      })
        .then((response) => {
          this.resetBulkColumns();

          setTimeout(() => {
            this.pagination(this.page);
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
    confirmBulkDeleteAction() {
      const vm = this;
      this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Confirm",
        toast: false,
        position: "center",
        text: "Please confirm if you want to delete the DVI Template Item.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.applyBulkDelete();
        }
      });
    },
    dviTemplateItemListing() {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + "/api/business/dvi-template-items",
      })
        .then((response) => {
          vm.$data.template_list =
            response.data.digital_vehicle_inspection_template_item_listing;
          vm.$data.pager = response.data.pager;

          this.resetBulkColumns();
        })
        .catch((e) => {
          console.log(e);
        });
    },
    pagination(n) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/dvi-template-items?page=`;
      url += n;
      url += "&items=";
      url += this.number_of_items;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then((response) => {
          vm.$data.template_list =
            response.data.digital_vehicle_inspection_template_item_listing;
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

          this.resetBulkColumns();
        })
        .catch((e) => {
          console.log(e);
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
      })
        .then((response) => {
          vm.$data.template_list =
            response.data.digital_vehicle_inspection_template_item_listing;
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

          this.resetBulkColumns();
        })
        .catch((e) => {
          console.log(e);
        });
    },
    confirmDeleteAction(title, id) {
      const vm = this;
      this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Confirm",
        toast: false,
        position: "center",
        text: `Please confirm if you want to delete the ${title} item.`,
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.deleteADVITemplateItem(id);
        }
      });
    },
    deleteADVITemplateItem(id) {
      let vm = this;
      let message = "Deleting DVI template item...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          `/api/business/dvi-template-items/delete/${id}`,
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
          vm.dviTemplateItemListing();
        })
        .catch(({ error }) => {
          let error_message = `Couldn't delete DVI template item.`;
          toast.fail(error_message);
          toast.close();
        });
    },
  },
  created() {
    this.dviTemplateItemListing();
  },
};
</script>
