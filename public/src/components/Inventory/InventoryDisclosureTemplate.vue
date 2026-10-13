<template>
  <!-- Disclosure Templates Modal : START  -->
  <div
    class="modal fade"
    :id="`disclosureTemplateModal_${elementId}`"
    tabindex="-1"
    role="dialog"
    aria-labelledby="Disclosure Templates"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <!-- Modal Header : START -->
        <div class="modal-header">
          <h5
            v-if="create_view"
            class="modal-title"
            id="disclosureTemplateModalHeader"
          >
            Add Disclosure Template
          </h5>
          <h5 v-else class="modal-title" id="disclosureTemplateModalHeader">
            Disclosure Templates
          </h5>
          <button
            type="button"
            :id="`disclosureTemplateModalClose_${elementId}`"
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
                Add New Disclosure
              </button>
            </div>
          </div>
          <form v-if="create_view">
            <div class="form-row">
              <!-- Description : START -->
              <div class="form-group col-md-12">
                <label for="disclosure_form_description">Description: </label>
                <textarea
                  type="text"
                  class="form-control"
                  :class="{
                    'is-invalid':
                      disclosure_template_errors.hasOwnProperty('description'),
                  }"
                  @keyup="delete disclosure_template_errors.description"
                  :title="disclosure_template_errors.description"
                  v-model="disclosure_template_form.description"
                  id="disclosure_form_description"
                  placeholder=""
                  rows="3"
                ></textarea>
              </div>
              <!-- Description : END -->

              <!-- favourite : START -->

              <div class="col-md-6 form-group">
                <label for="disclosure_template_form_favourite"
                  >Favourite</label
                >
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    id="disclosure_template_form_favourite"
                    v-model="disclosure_template_form.favourite"
                    name="Favourite"
                    :true-value="'1'"
                    :false-value="'0'"
                  />

                  <label
                    class="custom-control-label"
                    for="disclosure_template_form_favourite"
                  >
                    <span class="switch-icon-left"> ON</span>

                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
              <!-- favourite : END -->

              <h6 class="form-group col-md-12 font-italic">
                NOTE: Disclosure marked Favourite will be shown on the top.
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
                  :key="`disclosure_template_${index}`"
                  v-for="(disclosure_template, index) in disclosure_templates"
                >
                  <th scope="row">{{ index + 1 }}</th>
                  <td>
                    {{ disclosure_template.description }}
                  </td>
                  <td class="py-0 px-0">
                    <i
                      class="bx pr-50 bx-md"
                      :class="
                        disclosure_template.favourite == 0
                          ? 'bx-star'
                          : 'bxs-star'
                      "
                      :style="
                        disclosure_template.favourite == 0 ? '' : 'color: gold'
                      "
                      style="vertical-align: bottom"
                    ></i>
                    <button
                      @click.prevent="
                        addPrefilledTemplate(disclosure_template.description)
                      "
                      class="btn btn-secondary"
                    >
                      Select
                    </button>
                    <i
                      @click.prevent="
                        deleteDisclosureTemplate(disclosure_template.id)
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
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Close
          </button>
          <button
            type="button"
            class="btn btn-primary"
            v-show="create_view"
            @click.prevent="saveDisclosureTemplate"
          >
            Save
          </button>
        </div>
        <!-- Modal Footer : END -->
      </div>
    </div>
  </div>
  <!-- Disclosure Templates Modal : END  -->
</template>
<script>
import Toast from "../../library/Toast";
import TextEditor from "../WebComponents/TextEditor";

export default {
  props: {
    elementId: {
      type: String,
      required: false,
      default: "default",
    },
  },
  name: "InventoryDisclosureTemplate",
  components: {
    TextEditor,
  },
  data() {
    return {
      disclosure_templates: [],
      create_view: false,
      disclosure_template_form: {
        description: "",
        favourite: "0",
      },
      disclosure_template_errors: [],
    };
  },
  methods: {
    getDisclosureTemplates() {
      axios({
        method: "get",
        url: `${window.location.origin}/api/business/disclosure-templates?paginate=false&column=favourite&order=DESC`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.disclosure_templates = data.disclosure_template_listing;
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    deleteDisclosureTemplate(id) {
      const vm = this;
      let url = `${window.location.origin}/api/business/disclosure-templates/delete/${id}`;

      let message = "Deleting disclosure...";
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
          let success_message = `Disclosure template has been deleted.`;
          toast.success(success_message);
          toast.close();

          vm.getDisclosureTemplates();
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting Disclosure template.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    saveDisclosureTemplate() {
      const vm = this;
      let formData = new FormData();
      for (const key in this.disclosure_template_form) {
        if (
          this.disclosure_template_form.hasOwnProperty(key) &&
          this.disclosure_template_form[key] != null
        ) {
          formData.append(key, this.disclosure_template_form[key]);
        }
      }

      let request_url = `${window.location.origin}/api/business/disclosure-templates/create`;

      const toast = new Toast(`Creating new disclosure template ...`);
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
          vm.resetDisclosureTemplateForm();
          toast.success(data.success);
          toast.close();
          vm.getDisclosureTemplates();
        })
        .catch(function (error) {
          let error_message =
            "There were some errors while creating new disclosure template.";
          vm.$data.disclosure_template_errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    resetDisclosureTemplateForm() {
      this.disclosure_template_form.description = "";
      this.disclosure_template_form.favourite = "0";
      this.disclosure_template_errors = [];
    },
    addPrefilledTemplate(template) {
      Event.fire(`add_prefilled_template_${this.elementId}`, template);
      this.addPrefilledTemplateNotification();
    },
    addPrefilledTemplateNotification() {
      const toast = new Toast("");
      toast.reset({
        title: "Selected template has been added to complete disclosure.",
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
    this.getDisclosureTemplates();
  },
  mounted() {
    const vm = this;

    jQuery(`#disclosureTemplateModal_${this.elementId}`).on(
      "hidden.bs.modal",
      function () {
        vm.resetDisclosureTemplateForm();
        vm.create_view = false;
      }
    );
  },
  beforeDestroy() {
    Event.vue.$off(`add_prefilled_template_${this.elementId}`, null);
  },
};
</script>