<template>
  <div>
    <div class="app-content content fixed-height-block">
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <router-link
              title="Back To List"
              tag="a"
              :to="{ name: 'dvi' }"
              class="nav-link pr-0 pl-0 d-flex align-items-center"
              exact
            >
              <i class="bx bx-caret-left" style="font-size: 1.4em"></i>
            </router-link>
          </li>
          <li class="nav-item">
            <a
              class="nav-link d-flex align-items-center active"
              id="dvi-create"
              data-toggle="tab"
              href="#dvi-create"
              aria-controls="dvi-create"
              role="tab"
              aria-selected="true"
            >
              <span class="">Update</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="app-content content">
      <div class="content-overlay"></div>

      <!-- Content body : START -->
      <div class="content-body px-2 pt-0 dvi-page">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="row mb-1">
              <div class="col-12">
                <h5 class="col-12 col-sm-12">{{ form.title }}</h5>
                <p class="mb-0 small col-12 col-sm-12 text-muted">
                  {{ form.description }}
                </p>
              </div>

              <!-- Inventory : START -->
              <div class="col-6 border border-right-0 rounded-0">
                <div class="card-body">
                  <h5 class="card-title mb-25">Vehicle Details</h5>
                  <div class="row">
                    <div class="col-12 mb-25 font-weight-bolder">
                      {{ inventory.year }} {{ inventory.make }}
                      {{ inventory.model }} {{ inventory.trim }}
                    </div>
                    <div class="col">
                      <div class="dvi-summary-label">Stock No</div>
                      <div
                        class="dvi-summary-value copy-hover d-inline-block"
                        @click="doCopy(`${form.stock_no}`)"
                      >
                        {{ form.stock_no }}
                      </div>
                      <button
                        @click.prevent="
                          searchInventoryByStockNumber(form.stock_no)
                        "
                        style="cursor: pointer"
                        class="p-0 col-xs-2 btn btn-link  d-inline-block"
                      >
                        <i
                          class="bx bx-link-external"
                          style="font-size: 1.4em"
                        ></i>
                      </button>
                    </div>
                    <div class="col">
                      <div class="dvi-summary-label">VIN</div>
                      <div
                        class="dvi-summary-value copy-hover"
                        @click="doCopy(`${inventory.vin}`)"
                      >
                        {{ inventory.vin }}
                      </div>
                    </div>
                    <div class="col">
                      <div class="">
                        <label class="dvi-summary-label">Odometer</label>
                        <div class="col-12 p-0">
                          <input
                            type="text"
                            name="odometer"
                            class="form-control"
                            v-model="form.odometer"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('odometer'),
                            }"
                            @keyup="
                              delete errors.odometer;
                              triggerPartialUpdateForEntity('odometer');
                            "
                            :title="errors.odometer"
                            :disabled="disableModification"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Inventory : END -->

              <!-- Technician & Status : START -->
              <div class="col-6 border border-right-0">
                <div class="card-body">
                  <h5 class="card-title mb-25">Technician & Status</h5>
                  <div class="row">
                    <div class="col-12 mb-25 font-weight-bolder invisible">
                      {{ inventory.year }} {{ inventory.make }}
                      {{ inventory.model }} {{ inventory.trim }}
                    </div>
                    <div class="col">
                      <div class="">
                        <label class="dvi-summary-label">Inspected By</label>
                        <div class="col-12 p-0">
                          {{ assigneeName }}
                        </div>
                      </div>
                    </div>

                    <div class="col">
                      <div class="dvi-summary-label">Status</div>
                      <div class="dvi-summary-value">
                        <span
                          class="badge badge-success"
                          v-if="form.status === 'COMPLETE'"
                          >{{ form.status }}</span
                        >
                        <span
                          class="badge badge-danger"
                          v-else-if="form.status === 'INCOMPLETE'"
                          >{{ form.status }}</span
                        >
                        <span
                          class="badge badge-warning"
                          v-else-if="form.status === 'ARCHIVE'"
                          >{{ form.status }}</span
                        >
                      </div>
                    </div>

                    <div class="col" v-if="parseInt(form.issues_count, 10) > 0">
                      <div class="">
                        <label class="dvi-summary-label">Issues Count</label>
                        <div class="col-12 text-danger p-0">
                          <span class="badge badge-light-danger">{{
                            form.issues_count
                          }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Technician & Status : END -->
            </div>

            <!-- Item List : START -->
            <div class="dvi-section mb-5 pb-5">
              <template v-for="(item, item_index) in items">
                <div
                  v-if="item.type === 'HEADER'"
                  :key="`item_${item.id}`"
                  class="dvi-section-header d-flex justify-content-between align-items-center"
                >
                  <span>{{ item.title }}</span>
                </div>

                <div
                  class="dvi-row row mx-0"
                  v-else-if="item.type === 'COMMENT'"
                  :key="`item_${item.id}`"
                >
                  <div class="col-auto dvi-row-number">
                    {{ getCurrentItemCount(item_index) }}
                  </div>
                  <div class="col-3 dvi-item-title">
                    {{ item.title }}
                    <span v-if="item.description" class="dvi-item-description">
                      {{ item.description }}</span
                    >
                  </div>
                  <div class="col-2 col-xl-3">
                    <div class="form-group mb-0">
                      <textarea
                        class="form-control"
                        :class="{
                          'is-invalid':
                            items_errors.hasOwnProperty(item_index) &&
                            items_errors[item_index].hasOwnProperty('comment'),
                        }"
                        :title="
                          items_errors.hasOwnProperty(item_index) &&
                          items_errors[item_index].hasOwnProperty('comment')
                            ? items_errors[item_index].comment
                            : ''
                        "
                        v-model="items[item_index].comment"
                        @keyup="onKeyup(item_index, item)"
                        :disabled="disableModification"
                        rows="1"
                      ></textarea>
                    </div>
                  </div>
                  <div class="col-4 text-right"></div>
                  <div class="col-auto text-right">
                    <!-- Upload : START -->
                    <label
                      :style="{
                        visibility:
                          disableModification || item.enable_attachments == '0'
                            ? 'hidden'
                            : '',
                      }"
                      class="btn btn-sm border btn-dark text-center"
                    >
                      <i class="bx bx-xs font-weight-bolder bx-paperclip"></i>

                      <input
                        type="file"
                        hidden
                        multiple
                        accept="image/*"
                        @change="onFileChanged(item_index, item.id, $event)"
                      />
                    </label>
                    <!-- Upload : END -->
                  </div>
                  <div
                    class="col-12 dvi-comment-box my-50"
                    v-if="item.attachments.length > 0"
                  >
                    <!-- Attachment Section : START -->
                    <div class="form-group my-50">
                      <label>Attachments</label>
                      <div class="d-flex align-items-center flex-wrap mb-1">
                        <div
                          :key="`item_${item.id}_attachments_${attachment.id}`"
                          v-for="attachment in item.attachments"
                          class="d-flex flex-column"
                        >
                          <a
                            :href="parseAbsoluteOrRelativeUrl(attachment.url)"
                            target="_blank"
                          >
                            <img
                              :src="parseAbsoluteOrRelativeUrl(attachment.url)"
                              class="dvi-attachment-thumb mx-25"
                              alt=""
                            />
                          </a>
                          <button
                            :disabled="disableModification"
                            class="btn btn-danger btn-sm text-center mx-25"
                            @click.prevent="
                              warningForAttachmentDeletion(
                                item_index,
                                item.id,
                                attachment.id,
                              )
                            "
                          >
                            <i class="bx bx-xs bx-trash font-weight-bolder"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                    <!-- Attachment Section : END -->
                  </div>
                </div>

                <div
                  v-else-if="
                    item.type === 'PASSFAIL' || item.type === 'PASSFAILWITHNA'
                  "
                  :key="`item_${item.id}`"
                  :class="{
                    'is-invalid':
                      items_errors.hasOwnProperty(item_index) &&
                      items_errors[item_index].hasOwnProperty('status'),
                  }"
                  :title="
                    items_errors.hasOwnProperty(item_index) &&
                    items_errors[item_index].hasOwnProperty('status')
                      ? items_errors[item_index].status
                      : ''
                  "
                  class="dvi-row row mx-0"
                >
                  <div class="col-auto dvi-row-number">
                    {{ getCurrentItemCount(item_index) }}
                  </div>
                  <div class="col-3 dvi-item-title">
                    {{ item.title }}
                    <span
                      class="dvi-item-description"
                      v-if="item.description"
                      >{{ item.description }}</span
                    >
                  </div>
                  <div class="col-2 col-xl-3">
                    <div class="form-group mb-0">
                      <textarea
                        v-if="item.enable_comment == '1'"
                        class="form-control"
                        :class="{
                          'is-invalid':
                            items_errors.hasOwnProperty(item_index) &&
                            items_errors[item_index].hasOwnProperty('comment'),
                        }"
                        :title="
                          items_errors.hasOwnProperty(item_index) &&
                          items_errors[item_index].hasOwnProperty('comment')
                            ? items_errors[item_index].comment
                            : ''
                        "
                        v-model="items[item_index].comment"
                        @keyup="onKeyup(item_index, item)"
                        :disabled="disableModification"
                        rows="1"
                      ></textarea>
                    </div>
                  </div>
                  <!-- Buttons : START -->
                  <div class="col-4 text-right">
                    <button
                      :class="
                        item.status === 'PASS'
                          ? 'btn-light-success active'
                          : 'btn-light-secondary'
                      "
                      class="btn btn-sm"
                      @click.prevent="
                        items[item_index].status = 'PASS';
                        updateItem(item_index, item);
                      "
                      :disabled="disableModification"
                    >
                      <i class="bx bx-xs bx-check mr-50 font-weight-bolder"></i>
                      <span class="font-weight-bolder text-uppercase"
                        >PASS</span
                      >
                    </button>
                    <button
                      :class="
                        item.status === 'FAIL'
                          ? 'btn-light-danger active'
                          : 'btn-light-secondary'
                      "
                      :disabled="disableModification"
                      class="btn btn-sm"
                      @click.prevent="
                        items[item_index].status = 'FAIL';
                        updateItem(item_index, item);
                      "
                    >
                      <i
                        class="bx bx-xs bx-x-circle mr-50 font-weight-bolder"
                      ></i>
                      <span class="font-weight-bolder text-uppercase"
                        >FAIL</span
                      >
                    </button>
                    <button
                      :class="
                        item.status === 'NA'
                          ? 'btn-light-secondary active'
                          : 'btn-light-secondary'
                      "
                      class="btn btn-sm"
                      v-if="item.type === 'PASSFAILWITHNA'"
                      :disabled="disableModification"
                      @click.prevent="
                        items[item_index].status = 'NA';
                        updateItem(item_index, item);
                      "
                    >
                      <i class="bx bx-xs bx-block mr-50 font-weight-bolder"></i>
                      <span class="font-weight-bolder text-uppercase">NA</span>
                    </button>
                  </div>
                  <div class="col-auto text-right">
                    <!-- Upload : START -->
                    <label
                      :style="{
                        visibility:
                          disableModification || item.enable_attachments == '0'
                            ? 'hidden'
                            : '',
                      }"
                      class="btn btn-sm border btn-dark text-center"
                    >
                      <i class="bx bx-xs font-weight-bolder bx-paperclip"></i>

                      <input
                        type="file"
                        hidden
                        multiple
                        accept="image/*"
                        @change="onFileChanged(item_index, item.id, $event)"
                      />
                    </label>
                    <!-- Upload : END -->
                  </div>
                  <!-- Buttons : END -->

                  <div
                    class="col-12 dvi-comment-box my-50"
                    v-if="item.status === 'FAIL' || item.attachments.length > 0"
                  >
                    <div class="form-group mb-1" v-if="item.status === 'FAIL'">
                      <label>Fail Reason</label>
                      <textarea
                        class="form-control"
                        rows="1"
                        :class="{
                          'is-invalid':
                            items_errors.hasOwnProperty(item_index) &&
                            items_errors[item_index].hasOwnProperty('reason'),
                        }"
                        :title="
                          items_errors.hasOwnProperty(item_index) &&
                          items_errors[item_index].hasOwnProperty('reason')
                            ? items_errors[item_index].reason
                            : ''
                        "
                        :disabled="disableModification"
                        v-model="items[item_index].reason"
                        @keyup="onKeyup(item_index, item)"
                      ></textarea>
                    </div>

                    <!-- Attachment Section : START -->
                    <div
                      class="form-group my-50"
                      v-if="item.attachments.length > 0"
                    >
                      <label>Attachments</label>
                      <div class="d-flex align-items-center flex-wrap mb-1">
                        <div
                          :key="`item_${item.id}_attachments_${attachment.id}`"
                          v-for="attachment in item.attachments"
                          class="d-flex flex-column"
                        >
                          <a
                            :href="parseAbsoluteOrRelativeUrl(attachment.url)"
                            target="_blank"
                          >
                            <img
                              :src="parseAbsoluteOrRelativeUrl(attachment.url)"
                              class="dvi-attachment-thumb mx-25"
                              alt=""
                            />
                          </a>
                          <button
                            class="btn btn-danger btn-sm text-center mx-25"
                            :disabled="disableModification"
                            @click.prevent="
                              warningForAttachmentDeletion(
                                item_index,
                                item.id,
                                attachment.id,
                              )
                            "
                          >
                            <i class="bx bx-xs bx-trash font-weight-bolder"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                    <!-- Attachment Section : END -->
                  </div>
                </div>
              </template>
            </div>
            <!-- Item List : END -->
          </div>
        </div>
      </div>
      <!-- Content body : END -->
    </div>
    <!-- END: Content-->
    <!-- Action Bar : START -->
    <ul class="nav nav-tabs p-50 custom-footer-fixed-action-bar" role="tablist">
      <!-- print menu : START -->
      <div class="btn-group mr-1" role="group">
        <button
          style="line-height: 1.2em"
          type="button"
          class="btn btn-secondary"
          @click.prevent="downloadPDF"
        >
          <i class="bx bx-printer mr-50"></i> Print
        </button>
      </div>
      <!-- print menu : END -->
      <button
        type="submit"
        @click.prevent="partialUpdateEntity('status', 'COMPLETE')"
        :disabled="form.status == 'COMPLETE'"
        class="btn btn-primary ml-4 mr-1"
      >
        Mark As Complete
      </button>
    </ul>
    <!-- Action Bar : END-->
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import { debounce } from "vue-debounce";
import Toast from "../../library/Toast";

export default {
  name: "DVICreate",
  props: ["application", "app_metadata"],
  data() {
    return {
      created_at: null,
      updated_at: null,
      form: {
        title: "",
        description: "",
        odometer: "",
        require_odometer: "1",
        stock_no: "",
        status: "INCOMPLETE",
        assignee: "",
        issues_count: 0,
        issue_index: 0,
      },
      errors: {},
      inventory: {
        year: "",
        make: "",
        model: "",
        trim: "",
        vin: "",
      },
      items: [],
      items_errors: [],
      debouncedItemUpdates: {},
      disable_update: false,
    };
  },
  computed: {
    ...mapGetters("users", ["allUsers"]),
    tenantApp() {
      return this.application == "tenant";
    },
    updateView() {
      return this.$route.name == "dvi-update" && this.$route.params.id;
    },
    assigneeName() {
      return this.$helpers.assigneeName(this.allUsers, this.form.assignee, "");
    },
    disableModification() {
      return this.form.status === "COMPLETE" || this.disable_update;
    },
  },
  methods: {
    ...mapActions("users", ["getAllUsers"]),
    ...mapActions("inventoryView", {
      inventoryCommitSearchState: "commitSearchState",
      inventoryCommitSearchString: "commitSearchString",
      inventoryCommitLoadState: "commitLoadState",
    }),
    async searchInventoryByStockNumber(stock_no) {
      this.inventoryCommitSearchState(true);
      this.inventoryCommitSearchString(stock_no);
      this.inventoryCommitLoadState(false);
      this.$router.push({ name: "inventory-list" });
    },
    getCurrentItemCount(index) {
      let header_type_items_for_section = this.items.filter(
        (x, current_index) => x.type === "HEADER" && current_index <= index,
      );
      return index - header_type_items_for_section.length + 1;
    },
    onKeyup(item_index, item) {
      if (this.disableModification) {
        return;
      }

      if (!this.debouncedItemUpdates[item_index]) {
        this.debouncedItemUpdates[item_index] = debounce((latestItem) => {
          this.updateItem(item_index, latestItem);
        }, 700);
      }

      this.debouncedItemUpdates[item_index](item);
    },
    parseAbsoluteOrRelativeUrl(file_url) {
      return this.$helpers.parseAbsoluteOrRelativeUrl(
        window.global.cdn,
        file_url,
      );
    },
    doCopy(current_string) {
      this.$helpers.doCopy(this, current_string);
    },
    getEntity(id) {
      const vm = this;
      axios({
        method: "get",
        url: `${window.location.origin}/api/business/dvi/render/${id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          for (const key in this.form) {
            if (
              this.form.hasOwnProperty(key) &&
              data.hasOwnProperty(key) &&
              !["items", "inventory"].includes(key)
            ) {
              this.form[key] = data[key];
            }
          }

          if (data.hasOwnProperty("inventory")) {
            for (const key in this.inventory) {
              if (
                this.inventory.hasOwnProperty(key) &&
                data["inventory"].hasOwnProperty(key)
              ) {
                this.inventory[key] = data["inventory"][key];
              }
            }
          }

          if (data.hasOwnProperty("items")) {
            this.items = data.items;
          }
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the DVI";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
    triggerFetchUpdatedEntity: debounce(function(id) {
      this.fetchUpdatedEntity(id);
    }, "700ms"),
    fetchUpdatedEntity(id) {
      const vm = this;
      const toast = new Toast(`Updating DVI ...`);
      toast.fire();

      axios({
        method: "get",
        url: `${window.location.origin}/api/business/dvi/render/${id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.form.status = data.status;
          this.form.issues_count = data.issues_count;
          toast.success(`DVI updated.`);
          toast.close();
        })
        .catch(({ response }) => {
          toast.fail(`Error in updating DVI.`);
          toast.close();
        });
    },
    triggerPartialUpdateForEntity: debounce(function(column) {
      if (this.disableModification) {
        return;
      }

      this.partialUpdateEntity(column);
    }, "700ms"),
    partialUpdateEntity(column, value = null) {
      const vm = this;
      let formData = new FormData();
      formData.append("digital_vehicle_inspection_id", this.$route.params.id);
      formData.append("column", column);
      if (value) {
        formData.append("value", value);
      } else {
        formData.append("value", this.form[column]);
      }

      const toast = new Toast(`Updating DVI ...`);
      toast.fire();

      axios({
        method: "post",
        url: `${window.location.origin}/api/business/dvi/partial/update`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();

          if (value) {
            this.getEntity(vm.$route.params.id);
          }
        })
        .catch(({ response }) => {
          let error_message = "There were errors in saving DVI.";
          let { items: items_errors, ...errors } = response.data;

          vm.$data.errors = errors;

          if (items_errors) {
            vm.$data.items_errors = items_errors;
          }

          if (errors.hasOwnProperty("status")) {
            error_message = errors.status;
          }

          toast.fail(error_message);
          toast.close();
        });
    },
    fetchUpdatedItem(item_index, item_id) {
      const vm = this;
      this.disable_update = true;

      axios({
        method: "get",
        url: `${window.location.origin}/api/business/dvi/${this.$route.params.id}/item/show/${item_id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          if (this.items.hasOwnProperty(item_index)) {
            for (const key in this.items[item_index]) {
              if (
                this.items[item_index].hasOwnProperty(key) &&
                data.hasOwnProperty(key)
              ) {
                this.items[item_index][key] = data[key];
              }
            }
          }

          this.disable_update = false;
        })
        .catch(({ response }) => {
          vm.disable_update = false;
        });
    },
    updateItem(item_index, item) {
      if (this.disableModification) {
        return;
      }

      const vm = this;
      let formData = new FormData();
      for (const [key, value] of Object.entries(item)) {
        if (["comment", "status", "reason"].includes(key)) {
          formData.append(key, value);
        }
      }

      let request_url = `${window.location.origin}/api/business/dvi/${this.$route.params.id}/item/update/${item.id}`;

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
          vm.$set(vm.items_errors, item_index, {});
          vm.triggerFetchUpdatedEntity(vm.$route.params.id);
        })
        .catch(function(error) {
          let current_error = error.response.data;
          vm.$set(vm.items_errors, item_index, current_error);
          vm.triggerFetchUpdatedEntity(vm.$route.params.id);
        });
    },
    onFileChanged(item_index, item_id, event) {
      let files = event.target.files;
      this.onUpload(item_index, item_id, files);
    },
    async onUpload(item_index, item_id, files) {
      if (this.disableModification) {
        return;
      }
      const vm = this;
      this.disable_update = true;
      let promises = [];

      let message = "Uploading files ...";
      const toast = new Toast(message);
      toast.fire();

      for (let file of files) {
        let formData = new FormData();
        formData.append("file", file, file.name);
        let instance = axios({
          method: "post",
          url: `${window.location.origin}/api/business/dvi/${this.$route.params.id}/item/${item_id}/attachments/create`,
          data: formData,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
            "Content-Type": "multipart/form-data",
          },
          onUploadProgress: (progressEvent) => {
            console.log(progressEvent.loaded / progressEvent.total);
          },
        })
          .then(({ data }) => {
            vm.fetchUpdatedItem(item_index, item_id);
            vm.disable_update = false;
          })
          .catch((error) => {
            vm.disable_update = false;
          });

        promises.push(instance);
      }

      axios.all(promises).then((requests) => {
        requests.forEach((request) => {
          console.log(request);
        });

        let success_message = "Files have been uploaded.";
        toast.success(success_message);
        toast.close();
      });
    },
    async warningForAttachmentDeletion(item_index, item_id, attachment_id) {
      const vm = this;
      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Confirm",
        toast: false,
        position: "center",
        text: `Please confirm if you would like to delete the attachment.`,
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.deleteAnAttachment(item_index, item_id, attachment_id);
        }
      });
    },
    deleteAnAttachment(item_index, item_id, attachment_id) {
      const vm = this;
      this.disable_update = true;
      const toast = new Toast(`Deleting an attachment ...`);
      toast.fire();

      axios({
        method: "get",
        url: `${window.location.origin}/api/business/dvi/${this.$route.params.id}/item/${item_id}/attachments/delete/${attachment_id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          toast.success(`Attachment has been deleted.`);
          toast.close();

          vm.fetchUpdatedItem(item_index, item_id);
        })
        .catch(({ response }) => {
          toast.fail(`There were errors in deletion of the attachment`);
          toast.close();

          this.disable_update = false;
        });
    },
    downloadPDF() {
      axios({
        method: "get",
        url: `${window.location.origin}/api/business/dvi/pdf/dvi-complete/generate/${this.$route.params.id}`,
        responseType: "blob",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then((response) => {
          const disposition = response.headers["content-disposition"];

          let fileName = "DVI.pdf";

          if (disposition) {
            const match = disposition.match(/filename="?([^"]+)"?/);
            if (match && match[1]) {
              fileName = match[1];
            }
          }

          const blob = new Blob([response.data], {
            type: response.headers["content-type"] || "application/pdf",
          });

          const url = window.URL.createObjectURL(blob);
          window.open(url);
        })
        .catch(() => {
          console.log("There were some errors in retrieving the DVI");
        });
    },
  },
  async mounted() {
    const vm = this;
    if (this.updateView) {
      this.getEntity(this.$route.params.id);
      this.getAllUsers();
    }
  },
};
</script>
<style lang="scss">
.dvi-page {
  color: #40566f;

  .dvi-vehicle-title {
    font-size: 1.35rem;
    font-weight: 500;
    color: #40566f;
  }
  .dvi-meta-text {
    font-size: 10px;
    color: #6b7f95;
  }
  .dvi-tab-actions .btn {
    min-width: 130px;
  }
  .dvi-toolbar-icon {
    font-size: 24px;
    color: #40566f;
    margin-left: 22px;
    cursor: pointer;
  }
  .dvi-section-header {
    background: #405b79;
    color: #fff;
    font-weight: 600;
    padding: 14px 24px;
  }
  .dvi-section-header i {
    margin-left: 24px;
    font-size: 17px;
  }
  .dvi-row {
    border-bottom: 1px solid #dce4ec;
    min-height: 43px;
    padding: 10px 6px;
    align-items: center;
  }
  .dvi-row:hover {
    background: #f8fafc;
  }
  .dvi-row-number {
    width: 34px;
    color: #5a6f86;
  }
  .dvi-item-title {
    color: #405f82;
  }
  .dvi-item-description {
    display: block;
    color: #8b9bad;
    font-size: 11px;
    margin-top: 2px;
    white-space: pre-line;
  }
  .dvi-icon-btn {
    border: 0;
    background: transparent;
    color: #7a8794;
    padding: 4px 8px;
    font-size: 16px;
  }
  .dvi-status-btn {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 0;
    color: #fff;
    line-height: 24px;
    padding: 0;
    font-size: 12px;
    text-align: center;
    margin-left: 12px;
    opacity: 0.45;
  }
  .dvi-status-btn.active {
    opacity: 1;
    box-shadow: 0 0 0 3px rgba(64, 91, 121, 0.12);
  }
  .dvi-status-pass {
    background: #28c76f;
  }
  .dvi-status-na {
    background: #f5a623;
  }
  .dvi-status-fail {
    background: #405b79;
  }
  .dvi-comment-box {
    background: #f7f9fb;
    border-top: 1px dashed #d5dee8;
    padding: 12px 15px 15px 49px;
  }
  .dvi-attachment-thumb {
    width: 72px;
    height: 54px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #dce4ec;
    margin-right: 8px;
  }
  .dvi-summary-label {
    color: #7b8fa5;
    font-size: 11px;
    text-transform: uppercase;
  }
  .dvi-summary-value {
    color: #40566f;
    font-weight: 500;
    font-size: 13px;
  }

  .dvi-row.is-invalid {
    border: 1px solid #ff7575 !important;
  }
}
</style>
