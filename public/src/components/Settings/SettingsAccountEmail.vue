<template>
  <div
    role="tabpanel"
    class="tab-pane active"
    id="setting-account-email"
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
                      >Email Settings</span
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
        <div class="content-wrapper mt-0 pb-1 pt-2 border-bottom">
          <div class="col-md-12" v-show="!submit">
            Loading Saved Configurations ...
          </div>
          <div class="list-image-content" v-show="submit">
            <div class="row">
              <div class="col-md-4">
                <label>Name</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="account_email_name"
                  class="form-control"
                  :elementId="'text-editor-settings-1'"
                  v-model="form.account_email_name"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Email</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="email"
                  class="form-control"
                  name="account_email_address"
                  :elementId="'text-editor-settings-1'"
                  v-model="form.account_email_address"
                />
              </div>
            </div>
          </div>
        </div>

        <div
          class="content-wrapper mt-0 pb-1 pt-2 border-bottom"
          v-show="submit"
        >
          <div class="list-image-content">
            <div class="row">
              <div class="col-md-4">
                <label>Header</label>
              </div>
              <div class="col-md-8 form-group">
                <fieldset class="form-group">
                  <quill-editor
                    id="default-disclosure"
                    v-model="form.account_email_template_header"
                    :options="editorOption"
                  ></quill-editor>
                </fieldset>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Content</label>
              </div>
              <div class="col-md-8 form-group">
                <fieldset class="form-group">
                  <quill-editor
                    id="default-disclosure"
                    v-model="form.account_email_template_content"
                    :options="editorOption"
                  ></quill-editor>
                </fieldset>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Footer</label>
              </div>
              <div class="col-md-8 form-group">
                <fieldset class="form-group">
                  <quill-editor
                    v-model="form.account_email_template_footer"
                    :options="editorOption"
                  ></quill-editor>
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
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";
import { quillEditor } from "vue-quill-editor";

import ConfigurationMixin from "../../mixins/configuration";

export default {
  name: "SettingsAccountEmail",
  data() {
    return {
      form: {
        account_email_name: "",
        account_email_address: "",
        account_email_template_header: "",
        account_email_template_content: "",
        account_email_template_footer: "",
      },
      editorOption: {
        modules: {
          toolbar: [
            ["bold", "italic", "underline", "strike"],
            ["blockquote", "code-block"],
            [{ list: "ordered" }, { list: "bullet" }],
            [{ script: "sub" }, { script: "super" }],
            [{ indent: "-1" }, { indent: "+1" }],
            [{ direction: "rtl" }],
            ["link", "image"],
            [{ size: ["small", false, "large", "huge"] }],
            [{ header: [1, 2, 3, 4, 5, 6, false] }],
            [{ color: [] }, { background: [] }],
            [{ align: [] }],
            ["clean"],
          ],
        },
      },
    };
  },
  components: {
    quillEditor,
  },
  mixins: [ConfigurationMixin],
  async mounted() {
    this.submit = false;
    await this.getModuleConfiguration();
    this.submit = true;
  },
};
</script>
