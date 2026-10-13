<template>
  <div
    role="tabpanel"
    class="tab-pane"
    id="setting-account-review-template"
    aria-labelledby="stacked-pill-1"
    aria-expanded="true"
  >
    <div class="content-wrapper m-0 border-bottom todo-fixed-search">
      <form class="webapp-form">
        <div class="row">
          <div class="col-md-5 col-12">
            <ul class="pl-0">
              <li class="d-inline-block mr-1">
                <div class="content-list-box">
                  <p class="p-0 m-0">
                    <span
                      class="blue-color text-bold-400 title-sizeincrease-font"
                      >Review Channel Templates</span
                    >
                  </p>
                </div>
              </li>
            </ul>
          </div>
          <div class="col-md-7 col-12">
            <ul class="p-0 float-right d-flex">
              <li class="d-inline-block">
                <button
                  type="reset"
                  class="d-none btn btn-light-secondary mr-1"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="!submit"
                  @click.prevent="saveConfiguration"
                  class="btn btn-primary"
                >
                  Save
                </button>
              </li>
            </ul>
          </div>
        </div>
      </form>
    </div>
    <div
      class="list-right-task-list list-group ps p-0"
      style="overflow-y: auto !important"
    >
      <form class="webapp-form">
        <div
          class="content-wrapper mt-0 pb-1 pt-2 border-bottom"
          v-show="submit"
        >
          <div class="list-image-content">
            <div class="row">
              <div class="col-md-4">
                <label>Subject</label>
              </div>
              <div class="col-md-8 form-group">
                <fieldset class="form-group">
                  <generic-text-editor
                    name="subject"
                    :options="editorOption"
                    :elementId="'text-editor-settings-1'"
                    v-model="form.review_email_subject_template"
                  >
                    <template #placeholder>
                      <select class="ql-placeholder">
                        <option value="First Name">First Name</option>
                        <option value="Full Name">Full Name</option>
                        <option value="Business Name">Business Name</option>
                        <option value="Email">Email</option>
                        <option value="Phone">Phone</option>
                        <option value="Address">Address</option>
                        <option value="Website">Website</option>
                        <option value="Review Link">Review Link</option>
                        <option value="Short Review Link">
                          Short Review Link
                        </option>
                      </select>
                    </template>
                  </generic-text-editor>
                </fieldset>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Email Content</label>
              </div>
              <div class="col-md-8 form-group">
                <fieldset class="form-group">
                  <generic-text-editor
                    name="subject"
                    :options="editorOption"
                    :elementId="'review_email_content_template'"
                    v-model="form.review_email_content_template"
                    :editor_style="'min-height:10em;'"
                  >
                    <template #placeholder>
                      <select class="ql-placeholder">
                        <option value="First Name">First Name</option>
                        <option value="Full Name">Full Name</option>
                        <option value="Business Name">Business Name</option>
                        <option value="Email">Email</option>
                        <option value="Phone">Phone</option>
                        <option value="Address">Address</option>
                        <option value="Website">Website</option>
                        <option value="Review Link">Review Link</option>
                        <option value="Short Review Link">
                          Short Review Link
                        </option>
                      </select>
                    </template>
                  </generic-text-editor>
                </fieldset>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>SMS Content</label>
              </div>
              <div class="col-md-8 form-group">
                <fieldset class="form-group">
                  <generic-text-editor
                    name="review_sms_content_template"
                    :options="editorOption"
                    :elementId="'text-editor-settings-2'"
                    v-model="form.review_sms_content_template"
                  >
                    <template #placeholder>
                      <select class="ql-placeholder">
                        <option value="First Name">First Name</option>
                        <option value="Full Name">Full Name</option>
                        <option value="Business Name">Business Name</option>
                        <option value="Email">Email</option>
                        <option value="Phone">Phone</option>
                        <option value="Address">Address</option>
                        <option value="Website">Website</option>
                        <option value="Review Link">Review Link</option>
                        <option value="Short Review Link">
                          Short Review Link
                        </option>
                      </select>
                    </template>
                  </generic-text-editor>
                </fieldset>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import ConfigurationMixin from "../../mixins/configuration";
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";
import GenericTextEditor from "../WebComponents/GenericTextEditor";

export default {
  name: "SettingsAccountReviewTemplate",
  data() {
    return {
      form: {
        review_email_subject_template: "",
        review_email_content_template: "",
        review_sms_content_template: "",
      },
      editorOption: {
        modules: {
          //toolbar: '#toolbar',
          placeholder: {
            placeholders: [
              { id: "First Name", label: "First Name" },
              { id: "Full Name", label: "Full Name" },
              { id: "Business Name", label: "Business Name" },
              { id: "Email", label: "Email" },
              { id: "Phone", label: "Phone" },
              { id: "Address", label: "Address" },
              { id: "Website", label: "Website" },
              { id: "Review Link", label: "Review Link" },
              { id: "Short Review Link", label: "Short Review Link" },
            ],
          },
        },
      },
    };
  },
  mixins: [ConfigurationMixin],
  components: {
    GenericTextEditor,
  },
  async mounted() {
    this.submit = false;
    await this.getModuleConfiguration();
    this.submit = true;
  },
};
</script>
<style>
#text-editor-settings-2 .ql-formats:not(:last-child) {
  display: none;
}
</style>
