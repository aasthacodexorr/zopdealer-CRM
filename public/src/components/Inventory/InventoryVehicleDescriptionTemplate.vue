<template>
  <!-- Vehicle Description Templates Modal : START  -->
  <div
    class="modal fade"
    id="vehicleDescriptionTemplateModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="Vehicle Description Templates"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <!-- Modal Header : START -->
        <div class="modal-header">
          <h5
            v-if="create_view"
            class="modal-title"
            id="vehicleDescriptionTemplateModalHeader"
          >
            Add Vehicle Description Template
          </h5>
          <h5
            v-else
            class="modal-title"
            id="vehicleDescriptionTemplateModalHeader"
          >
            Vehicle Description Templates
          </h5>
          <button
            type="button"
            id="vehicleDescriptionTemplateModalClose"
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
          <div class="row">
            <div class="col-xs-12 col-12 text-right">
              <button
                class="btn btn-primary"
                v-if="create_view"
                @click="create_view = false"
              >
                Show List
              </button>
              <button
                class="btn btn-primary"
                v-else
                @click="create_view = true"
              >
                Add New Template
              </button>
            </div>
          </div>
          <form v-if="create_view">
            <div class="form-row">
              <!-- Title : START -->
              <div class="form-group col-md-12">
                <label for="vehicle_description_form_title">Title: </label>
                <input
                  type="text"
                  class="form-control"
                  :class="{
                    'is-invalid':
                      vehicle_description_template_errors.hasOwnProperty(
                        'title'
                      ),
                  }"
                  @keyup="delete vehicle_description_template_errors.title"
                  :title="vehicle_description_template_errors.title"
                  v-model="vehicle_description_template_form.title"
                  id="vehicle_description_form_title"
                  placeholder=""
                />
              </div>
              <!-- Title : END -->

              <!-- Description : START -->
              <div class="form-group col-md-12">
                <label for="vehicle_description_form_description"
                  >Description:
                </label>
                <text-editor
                  name="description"
                  :class="{
                    'is-invalid':
                      vehicle_description_template_errors.hasOwnProperty(
                        'description'
                      ),
                  }"
                  @keyup="
                    delete vehicle_description_template_errors.description
                  "
                  :title="vehicle_description_template_errors.description"
                  :elementId="'text-editor-2'"
                  ref="vehicleDescriptionTemplate"
                  :options="editorOption"
                  v-model="vehicle_description_template_form.description"
                  :editor_style="'min-height:10em;'"
                >
                </text-editor>
              </div>
              <!-- Description : END -->

              <!-- favourite : START -->

              <div class="col-md-6 form-group">
                <label for="vehicle_description_template_form_favourite"
                  >Favourite</label
                >
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    id="vehicle_description_template_form_favourite"
                    v-model="vehicle_description_template_form.favourite"
                    name="Favourite"
                    :true-value="'1'"
                    :false-value="'0'"
                  />

                  <label
                    class="custom-control-label"
                    for="vehicle_description_template_form_favourite"
                  >
                    <span class="switch-icon-left"> ON</span>

                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
              <!-- favourite : END -->

              <h6 class="form-group col-md-12 font-italic">
                NOTE: Template marked Favourite will be shown on the top.
              </h6>
            </div>
          </form>
          <div style="max-height: 70vh; overflow: auto" v-else>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col" style="width: 70%">Templates</th>
                  <th scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  :key="`vehicle_description_template_${index}`"
                  :id="`vehicle_description_template_${index}`"
                  v-for="(
                    vehicle_description_template, index
                  ) in vehicle_description_templates"
                >
                  <th scope="row">{{ index + 1 }}</th>
                  <td>
                    <h4>{{ vehicle_description_template.title }}</h4>
                    <hr />
                    <div
                      :class="
                        view_complete_description_index === index
                          ? ''
                          : 'vehicle-description-template-description'
                      "
                      v-html="vehicle_description_template.description"
                    ></div>
                    <div>
                      <button
                        :target="`vehicle_description_template_${index}`"
                        v-if="view_complete_description_index !== index"
                        class="btn btn-link px-0"
                        @click="view_complete_description_index = index"
                      >
                        Show Complete Template
                      </button>
                      <button
                        v-else
                        class="btn btn-link px-0"
                        @click="view_complete_description_index = ''"
                      >
                        Hide
                      </button>
                    </div>
                  </td>
                  <td class="py-0 px-0">
                    <i
                      class="bx pr-50 bx-md"
                      :class="
                        vehicle_description_template.favourite == 0
                          ? 'bx-star'
                          : 'bxs-star'
                      "
                      :style="
                        vehicle_description_template.favourite == 0
                          ? ''
                          : 'color: gold'
                      "
                      style="vertical-align: bottom"
                    ></i>
                    <button
                      @click.prevent="
                        changeTemplateNotification(
                          vehicle_description_template.description
                        )
                      "
                      class="btn btn-secondary"
                    >
                      Select
                    </button>
                    <i
                      @click.prevent="
                        deleteVehicleDescriptionTemplate(
                          vehicle_description_template.id
                        )
                      "
                      class="bx pl-1 bx-md bx-trash text-danger"
                      style="vertical-align: bottom; cursor: pointer"
                    >
                    </i>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <!-- Modal Body : END -->
        <!-- Modal Footer : START -->
        <div class="modal-footer">
          <button
            type="button"
            id="vehicleDescriptionTemplateModalClose"
            class="btn btn-secondary"
            data-dismiss="modal"
          >
            Close
          </button>
          <button
            @click.prevent="saveVehicleDescriptionTemplate"
            type="button"
            class="btn btn-primary"
            v-show="create_view"
          >
            Save
          </button>
        </div>
        <!-- Modal Footer : END -->
      </div>
    </div>
  </div>
  <!-- Vehicle Description Templates Modal : END  -->
</template>
<script>
import Toast from "../../library/Toast";
import TextEditor from "../WebComponents/TextEditor";

export default {
  name: "InventoryVehicleDescriptionTemplate",
  components: {
    TextEditor,
  },
  data() {
    return {
      vehicle_description_templates: [],
      create_view: false,
      vehicle_description_template_form: {
        title: "",
        description: "",
        favourite: "0",
      },
      vehicle_description_template_errors: [],
      view_complete_description_index: "",
    };
  },
  methods: {
    deleteVehicleDescriptionTemplate(id) {
      const vm = this;
      let url = `${window.location.origin}/api/business/vehicle-description-templates/delete/${id}`;

      let message = "Deleting template...";
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
          let success_message = `Template has been deleted.`;
          toast.success(success_message);
          toast.close();
          vm.getVehicleDescriptionTemplates();
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting template.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    saveVehicleDescriptionTemplate() {
      const vm = this;
      let formData = new FormData();
      for (const key in this.vehicle_description_template_form) {
        if (
          this.vehicle_description_template_form.hasOwnProperty(key) &&
          this.vehicle_description_template_form[key] != null
        ) {
          formData.append(key, this.vehicle_description_template_form[key]);
        }
      }

      let request_url = `${window.location.origin}/api/business/vehicle-description-templates/create`;

      const toast = new Toast(`Creating new template ...`);
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
          vm.resetVehicleDescriptionTemplateForm();
          toast.success(data.success);
          toast.close();
          vm.getVehicleDescriptionTemplates();
        })
        .catch(function (error) {
          let error_message =
            "There were some errors while creating new template.";
          vm.$data.vehicle_description_template_errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    resetVehicleDescriptionTemplateForm() {
      this.vehicle_description_template_form.title = "";
      this.vehicle_description_template_form.description = "";
      this.vehicle_description_template_form.favourite = "0";
      this.vehicle_description_template_errors = [];
    },
    getVehicleDescriptionTemplates() {
      axios({
        method: "get",
        url: `${window.location.origin}/api/business/vehicle-description-templates?paginate=false&column=favourite&order=DESC`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.vehicle_description_templates =
            data.vehicle_description_template_listing;
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    changeTemplateNotification(description) {
      Event.fire("update_vehicle_description", description);

      document.getElementById("vehicleDescriptionTemplateModalClose").click();

      const toast = new Toast("");
      toast.reset({
        title:
          "This template has been selected. Please submit the form for the changes to take effect.",
        showConfirmButton: false,
        timer: 5000,
        toast: true,
        position: "bottom",
        icon: "success",
      });
      toast.fire();
      toast.close();
    },
  },
  created() {
    this.getVehicleDescriptionTemplates();
  },
  mounted() {
    const vm = this;
    jQuery("#vehicleDescriptionTemplateModal").on(
      "hidden.bs.modal",
      function () {
        vm.resetVehicleDescriptionTemplateForm();
        vm.create_view = false;
        vm.view_complete_description_index = "";
      }
    );
  },
  beforeDestroy() {
    Event.vue.$off("update_vehicle_description", null);
  }
};
</script>
<style lang="scss">
.vehicle-description-template-description {
  max-height: 200px;
  overflow: hidden;
}
</style>