<template>
  <div>
    <div
      class="app-content content fixed-height-block"
      style="overflow: visible"
    >
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <router-link
              title="Back To List"
              tag="a"
              :to="{ name: 'dvi-template-list' }"
              class="nav-link pr-0 pl-0 d-flex align-items-center"
              exact
            >
              <i class="bx bx-caret-left" style="font-size: 1.4em"></i>
            </router-link>
          </li>
          <li class="nav-item pr-0 pl-0">
            <a
              class="nav-link d-flex align-items-center active"
              id="dvi-template-create-tab"
              data-toggle="tab"
              href="#dvi-template-create-panel"
              aria-controls="DVI Template Create"
              role="tab"
              aria-selected="true"
            >
              <span class="">{{ title }}</span>
            </a>
          </li>
          <div class="btn-feild-box">
            <button
              type="submit"
              @click.prevent="save()"
              class="btn btn-primary mr-1"
            >
              Save
            </button>
          </div>
        </ul>
      </div>
    </div>
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="">
        <div class="content-body" style="height: 80vh">
          <!-- form layout section start -->
          <div class="tab-content">
            <div
              class="tab-pane active"
              id="dvi-template-create-panel"
              aria-labelledby="information-tab"
              role="tabpanel"
            >
              <!-- form layout section start -->
              <section id="general">
                <div class="row match-height">
                  <!-- FORM DIV : START -->
                  <div class="col-md-6 col-6">
                    <div class="card shadow-none mb-0">
                      <div class="card-content">
                        <div class="card-body pt-0">
                          <form class="form form-horizontal webapp-form">
                            <div class="form-body">
                              <div class="row">
                                <div class="col-md-12 col-12">
                                  <div>
                                    <div class="row">
                                      <!-- Title : START -->
                                      <div class="col-md-4">
                                        <label>Title*</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <input
                                              type="text"
                                              id="title"
                                              class="form-control"
                                              name="title"
                                              placeholder="Title"
                                              :class="{
                                                'is-invalid': errors.hasOwnProperty(
                                                  'title',
                                                ),
                                              }"
                                              @keyup="delete errors.title"
                                              :title="errors.title"
                                              v-model="form.title"
                                            />
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- Title : END -->

                                      <!-- Description : START -->
                                      <div class="col-md-4">
                                        <label>Description</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <fieldset>
                                          <textarea
                                            name="description"
                                            placeholder="Short Description"
                                            class="form-control"
                                            v-model="form.description"
                                          ></textarea>
                                        </fieldset>
                                      </div>
                                      <!-- Description : END -->

                                      <!-- Odometer Required: START -->
                                      <div class="col-md-4">
                                        <label>Odometer Required?</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <div
                                              class="custom-control custom-switch"
                                            >
                                              <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="require_odometer"
                                                name="require_odometer"
                                                v-model="form.require_odometer"
                                                :true-value="'1'"
                                                :false-value="'0'"
                                              />
                                              <label
                                                class="custom-control-label"
                                                for="require_odometer"
                                              >
                                                <span class="switch-icon-left">
                                                  YES</span
                                                >
                                                <span class="switch-icon-right"
                                                  >NO</span
                                                >
                                              </label>
                                            </div>
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- Odometer Required: END -->
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- FORM DIV : END -->
                  <!-- DRAGGABLE DIV : START -->
                  <div class="col-md-6 col-6" style="height: 75vh">
                    <div class="row">
                      <div class="form-group col-md-12">
                        <label for="item_selection">Search Item : </label>
                        <v-select
                          id="item_selection"
                          class="w-100 m-auto pr-50"
                          placeholder="Search Item To Be Added"
                          label="title"
                          v-model="selected_template_item_name"
                          clearable
                          :options="filteredDVIItemList"
                        >
                          <template
                            #no-options="{ search, searching, loading }"
                          >
                            <span
                              @click.prevent="createDIVItem(search)"
                              v-if="searching"
                            >
                              Click here to add
                              <i
                                ><b> {{ search }} </b></i
                              >
                              ...
                            </span>
                          </template>
                        </v-select>
                      </div>
                      <div class="col-md-12">
                        <!-- Draggable List : OPEN -->
                        <draggable
                          tag="div"
                          :list="form.items"
                          v-bind="dragOptions"
                          @start="isDragging = true"
                          @end="isDragging = false"
                          handle=".handle"
                        >
                          <transition-group
                            tag="ul"
                            class="list-group dvi-template-create-items"
                            style="height: 70vh; overflow-y: auto"
                            type="transition"
                            name="flip-list"
                          >
                            <li
                              v-for="(item, item_index) in form.items"
                              class="list-group-item d-flex justify-content-between align-items-center"
                              :key="
                                `dvi_template_item_${item.id}_${item_index}`
                              "
                            >
                              <span
                                style="cursor: pointer"
                                class="bx bx-grid-vertical pr-1 handle"
                              ></span>
                              <div class="list-text">
                                <b
                                  class="text-uppercase"
                                  v-if="item.type === 'HEADER'"
                                  ><i
                                    class="bx bx-heading pr-50"
                                    style="vertical-align: top;"
                                  ></i
                                  >{{ item.title }}</b
                                >
                                <span v-else> {{ item.title }}</span>
                              </div>
                              <div class="list-icons">
                                <i
                                  class="bx bx-comment text-seconday"
                                  alt="Comment has been enabled"
                                  v-if="item.enable_comment == '1'"
                                ></i>
                                <i
                                  class="bx bx-photo-album text-seconday"
                                  alt="Attachments has been enabled"
                                  v-if="item.enable_attachments == '1'"
                                ></i>
                              </div>
                              <div class="list-icons">
                                <i
                                  @click="form.items.splice(item_index, 1)"
                                  class="bx bxs-trash text-danger"
                                ></i>
                              </div>
                            </li>
                          </transition-group>
                        </draggable>
                        <!-- Draggable List : CLOSE -->
                      </div>
                    </div>
                  </div>
                  <!-- DRAGGABLE DIV : END -->
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Create New DVI Item : START  -->
    <div
      class="modal fade"
      id="createDVIItem"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Create New DVI Item"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <!-- Modal Header : START -->
          <div class="modal-header">
            <h5 class="modal-title" id="createDVIItemHeader">
              Create New DVI Item
            </h5>
            <button
              type="button"
              id="createDVIItemClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- Modal Header : END -->
          <!-- Modal Body : START -->
          <div class="modal-body">
            <form>
              <div class="form-row">
                <!-- Title : START -->
                <div class="form-group col-md-12">
                  <label for="item_form_title">Title: </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': item_errors.hasOwnProperty('title'),
                    }"
                    @keyup="delete item_errors.title"
                    :title="item_errors.title"
                    v-model="item_form.title"
                    id="item_form_title"
                    placeholder="Title"
                  />
                </div>
                <!-- Title : END -->
                <!-- Description : START -->
                <div class="form-group col-md-12">
                  <label for="item_form_description">Description: </label>
                  <textarea
                    class="form-control"
                    :class="{
                      'is-invalid': item_errors.hasOwnProperty('description'),
                    }"
                    @keyup="delete item_errors.description"
                    :title="item_errors.description"
                    v-model="item_form.description"
                    id="item_form_description"
                    placeholder="Description"
                  ></textarea>
                </div>
                <!-- Description : END -->
                <!-- Type : START -->
                <div class="form-group col-md-12">
                  <label for="item_form_type">Type: </label>
                  <fieldset>
                    <div class="input-group">
                      <select
                        type="text"
                        id="item_form_type"
                        v-model="item_form.type"
                        :class="{
                          'is-invalid': item_errors.hasOwnProperty('type'),
                        }"
                        @change="delete item_errors.type"
                        :title="item_errors.type"
                        class="custom-select form-control mr-1"
                        placeholder="Type"
                        name="type"
                      >
                        <option value="" selected>
                          -- Select A Type --
                        </option>
                        <option value="PASSFAIL">
                          Pass/Fail
                        </option>
                        <option value="PASSFAILWITHNA">
                          Pass/Fail with NA
                        </option>
                        <option value="COMMENT">
                          Comment
                        </option>
                        <option value="HEADER">
                          Section Header
                        </option>
                      </select>
                    </div>
                    <small v-if="item_form.type === 'PASSFAIL'"
                      >Displays PASS or FAIL option</small
                    >
                    <small v-if="item_form.type === 'PASSFAILWITHNA'"
                      >Displays PASS, FAIL and Not Applicable option</small
                    >
                    <small v-if="item_form.type === 'COMMENT'"
                      >Displays a required comment box</small
                    >
                    <small v-if="item_form.type === 'HEADER'"
                      >Displays a sectional header for visual clarity</small
                    >
                  </fieldset>
                </div>
                <!-- Type : END -->
                <!-- Enable Comment : START -->
                <div
                  class="form-group col-md-12"
                  v-if="item_form.type !== 'HEADER'"
                >
                  <label for="item_form_enable_comment">Enable Comment: </label>
                  <fieldset>
                    <div class="input-group">
                      <div class="custom-control custom-switch">
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          id="item_form_enable_comment"
                          name="enable_comment"
                          v-model="item_form.enable_comment"
                          :true-value="'1'"
                          :false-value="'0'"
                        />
                        <label
                          class="custom-control-label"
                          for="item_form_enable_comment"
                        >
                          <span class="switch-icon-left"> YES</span>
                          <span class="switch-icon-right">NO</span>
                        </label>
                      </div>
                    </div>
                  </fieldset>
                </div>
                <!-- Enable Comment : END -->
                <!-- Enable Attachment : START -->
                <div
                  class="form-group col-md-12"
                  v-if="item_form.type !== 'HEADER'"
                >
                  <label for="item_form_enable_attachment"
                    >Enable Attachment:
                  </label>
                  <fieldset>
                    <div class="input-group">
                      <div class="custom-control custom-switch">
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          id="item_form_enable_attachment"
                          name="enable_attachments"
                          v-model="item_form.enable_attachments"
                          :true-value="'1'"
                          :false-value="'0'"
                        />
                        <label
                          class="custom-control-label"
                          for="item_form_enable_attachment"
                        >
                          <span class="switch-icon-left"> YES</span>
                          <span class="switch-icon-right">NO</span>
                        </label>
                      </div>
                    </div>
                  </fieldset>
                </div>
                <!-- Enable Attachment : END -->
              </div>
            </form>
          </div>
          <!-- Modal Body : END -->
          <!-- Modal Footer : START -->
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
              @click.prevent="createDVIItem"
            >
              Save
            </button>
          </div>
          <!-- Modal Footer : END -->
        </div>
      </div>
    </div>
    <!-- Create New DVI Item : END  -->
  </div>
</template>
<script>
import DatePicker from "vue2-datepicker";
import draggable from "vuedraggable";
import "vue2-datepicker/index.css";
import vSelect from "vue-select";

import Toast from "../../library/Toast";
export default {
  name: "DVITemplateCreate",
  data() {
    return {
      title: "Create",
      dragging: true,
      selected_template_item_name: "",
      item_form: {
        title: "",
        description: "",
        type: "",
        enable_comment: "0",
        enable_attachments: "1",
      },
      item_errors: [],
      form: {
        title: "",
        description: "",
        require_odometer: "0",
        items: [],
      },
      digital_vehicle_inspection_template_item_listing: [],
      errors: [],
    };
  },
  components: {
    "v-select": vSelect,
    DatePicker,
    draggable,
  },
  watch: {
    selected_template_item_name: function(newVal, oldVal) {
      if (newVal != oldVal) {
        if (newVal && newVal != "null") {
          this.form.items.push(newVal);
          setTimeout(() => {
            this.selected_template_item_name = null;
          }, 200);
        }
      }
    },
  },
  computed: {
    getTemplateItemId() {
      return this.form.items.map((item) => item.id);
    },
    filteredDVIItemList() {
      return this.digital_vehicle_inspection_template_item_listing.filter(
        (item) => {
          return !this.getTemplateItemId.includes(item.id);
        },
      );
    },
    updateView() {
      return this.$route.name == "dvi-template-update" && this.$route.params.id;
    },
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost",
      };
    },
  },
  methods: {
    resetDVIItemForm() {
      this.item_form.title = "";
      this.item_form.description = "";
      this.item_form.enable_comment = "0";
      this.item_form.enable_attachments = "1";
      this.item_errors = [];
    },
    async createDIVItem(search) {
      this.item_form.title = search;
      jQuery("#createDVIItem").modal("show");
    },
    dviTemplateItemListing() {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          "/api/business/dvi-template-items?paginate=false&order=DESC",
      })
        .then((response) => {
          vm.$data.digital_vehicle_inspection_template_item_listing =
            response.data.digital_vehicle_inspection_template_item_listing;
        })
        .catch((e) => {
          console.log(e);
        });
    },
    getDVITemplate(id) {
      const vm = this;
      axios({
        method: "get",
        url: `${window.location.origin}/api/business/dvi-templates/show/${id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the DVI template";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
    save() {
      const vm = this;

      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && this.form[key] != null) {
          if (key !== "items") {
            formData.append(key, this.form[key]);
          }
        }
      }

      if (Array.isArray(this.form.items)) {
        formData.append(
          "items",
          JSON.stringify(this.form.items.map((item) => item.id)),
        );
      }

      let request_url = window.location.origin;

      request_url = vm.updateView
        ? `${request_url}/api/business/dvi-templates/update/${this.$route.params.id}`
        : `${request_url}/api/business/dvi-templates/create`;

      let request_message = vm.updateView
        ? `Updating DVI Template ...`
        : `Creating New DVI Template ...`;

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

          if (!vm.updateView) {
            vm.$router.push({
              name: "dvi-template-update",
              params: { id: data.digital_vehicle_inspection_template_id },
            });
          }
        })
        .catch(function(error) {
          let error_message = vm.updateView
            ? "There were errors while updating the DVI template."
            : "There were some errors while creating new DVI template.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    createDVIItem() {
      const vm = this;

      let formData = new FormData();
      for (const key in this.item_form) {
        if (this.item_form.hasOwnProperty(key) && this.item_form[key] != null) {
          formData.append(key, this.item_form[key]);
        }
      }

      let request_url = window.location.origin;
      request_url = `${request_url}/api/business/dvi-template-items/create`;
      let request_message = `Creating New DVI Item template ...`;

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
          toast.success(`${data.success}. Click the main Save button to save your changes to the DVI template.`);
          toast.close();
          if (
            data.hasOwnProperty("digital_vehicle_inspection_template_item_id")
          ) {
            let instance = JSON.parse(JSON.stringify(this.item_form));
            instance.id = data.digital_vehicle_inspection_template_item_id;
            this.digital_vehicle_inspection_template_item_listing.push(
              instance,
            );
            this.form.items.push(instance);

            jQuery(`#createDVIItem`).modal("hide");
          }
        })
        .catch(function(error) {
          let error_message =
            "There were some errors while creating new DVI Item template.";
          vm.$data.item_errors = error.response.data;
          toast.fail(error_message);
          toast.close();
        });
    },
  },
  async created() {
    if (this.updateView) {
      this.title = "Update";
      this.getDVITemplate(this.$route.params.id);
    }

    this.dviTemplateItemListing();
  },
  mounted() {
    const vm = this;
    jQuery("#createDVIItem").on("hidden.bs.modal", function() {
      vm.resetDVIItemForm();
    });
  },
};
</script>
<style lang="scss">
.dvi-template-create-items .list-group-item {
  padding: 12px 16px;
}

.dvi-template-create-items .list-text {
  flex: 1;
  font-size: 15px;
}

.dvi-template-create-items .list-icons {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-left: 15px;
}

.dvi-template-create-items .list-icons i {
  font-size: 20px;
  cursor: pointer;
}
</style>
