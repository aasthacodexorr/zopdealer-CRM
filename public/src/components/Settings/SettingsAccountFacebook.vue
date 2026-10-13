<template>
  <div
    role="tabpanel"
    class="tab-pane"
    id="setting-account-facebook"
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
                      >Facebook Settings
                      <a
                        href="https://help.zopmedia.com/portal/en/kb/articles/updating-facebook-details-for-remote-facebook-marketplace-tool-fbmp-on-portal"
                        target="_blank"
                        class="btn-link"
                        ><i class="bx bxs-help-circle bx-sm"></i></a
                    ></span>
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
          <div class="list-image-content">
            <div class="row">
              <div class="col-md-4">
                <label>Facebook Market Location</label>
              </div>
              <div class="col-md-8 form-group">
                <v-select
                  id="fbmp-location"
                  placeholder="Facebook Location"
                  taggable
                  v-model="form.fbmp_location"
                  clearable
                  :options="getFBMPLocations"
                >
                </v-select>

                <small
                  id="source-error"
                  class="form-text invalid-feedback"
                ></small>
              </div>
            </div>

            <div class="row d-none">
              <div class="col-md-4">
                <label
                  >Enable Inventory Changes Tracking For FBMP &
                  Craigslist</label
                >
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.enable_inventory_changes_tracking_for_poster"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="enable_inventory_changes_tracking_for_poster"
                    id="enable_inventory_changes_tracking_for_poster_switch_for_facebook_settings"
                  />
                  <label
                    class="custom-control-label"
                    for="enable_inventory_changes_tracking_for_poster_switch_for_facebook_settings"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable Posting on Facebook Pages</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.enable_fbmp_group_posting"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Enable Posting on Facebook Page"
                    id="enable_fbmp_group_posting_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="enable_fbmp_group_posting_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div
              v-show="form.enable_fbmp_group_posting == '1'"
              class="mt-1 mb-1 pb-1 pt-2 border-bottom border-top"
            >
              <div class="list-image-content">
                <div class="row">
                  <div class="col-md-4">
                    <label>Facebook Groups List</label>
                  </div>
                </div>
                <div class="row contact-repeater">
                  <div class="col-md-4">
                    <button
                      class="btn btn-icon btn-primary"
                      @click="count_of_list_of_groups += 1"
                      type="button"
                    >
                      <i class="bx bx-plus"></i>Add New Facebook Group
                    </button>
                  </div>
                  <div class="col-md-5 form-group" data-repeater-list="contact">
                    <!-- start Loop for facebook group -->
                    <div
                      class="row"
                      v-for="n in this.count_of_list_of_groups"
                      :key="n"
                    >
                      <div class="col-md-10 col-12 form-group">
                        <input
                          type="text"
                          class="form-control"
                          v-model="form.fbmp_list_of_groups[n - 1]"
                        />
                      </div>
                      <div class="col-md-2 col-12 form-group pl-0">
                        <button
                          class="btn btn-danger text-nowrap px-1"
                          @click="
                            form.fbmp_list_of_groups.splice(n - 1, 1);
                            count_of_list_of_groups -= 1;
                          "
                          type="button"
                        >
                          <i class="bx bx-x"></i>
                        </button>
                      </div>
                    </div>
                    <!-- end Loop for facebook group -->
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Client ID</label>
              </div>
              <div
                class="col-md-8 form-group copy-hover"
                title="Click to Copy"
                @click="doCopy(client_id)"
              >
                {{ client_id }}
                <i
                  @click="doCopy(client_id)"
                  class="bx bxs-copy bx-sm"
                  style="
                    cursor: pointer;
                    position: relative;
                    top: 0.2em;
                    float: right;
                  "
                ></i>
                <small
                  id="source-error"
                  class="form-text invalid-feedback"
                ></small>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Token</label>
              </div>
              <div
                class="col-md-8 form-group copy-hover"
                title="Click to Copy"
                @click="doCopy(rpa_token)"
              >
                {{ rpa_token }}
                <i
                  @click="doCopy(rpa_token)"
                  class="bx bxs-copy bx-sm"
                  style="
                    cursor: pointer;
                    position: relative;
                    top: 0.2em;
                    float: right;
                  "
                ></i>
                <small
                  id="source-error"
                  class="form-text invalid-feedback"
                ></small>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Download Link</label>
              </div>
              <div class="col-md-8 form-group">
                <a
                  href="https://www.zopdealer.com/download-fbmp-tool"
                  target="_blank"
                  rel="noopener noreferrer"
                  >Click Here</a
                >
                <small
                  id="source-error"
                  class="form-text invalid-feedback"
                ></small>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Facebook Email (Remote Account)</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="facebook_email_address"
                  class="form-control"
                  v-model="form.facebook_email_address"
                />
                <small
                  id="facebook_email_address-error"
                  class="form-text invalid-feedback"
                ></small>
              </div>
            </div>

            <div class="row">
              <h6 class="text-danger col-12">
                NOTE: Password is encrypted and not shown once set.
              </h6>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Facebook Password (Remote Password)</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="facebook_password"
                  class="form-control"
                  v-model="form.facebook_password"
                  type="password"
                />
                <small
                  id="facebook_password-error"
                  class="form-text invalid-feedback"
                ></small>
              </div>
            </div>
            <!-- FBMP Enable Template : START -->
            <div class="row">
              <div class="col-md-4">
                <label>Enable FBMP Vehicle Description Template</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.enable_fbmp_vehicle_description_template"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="enable_fbmp_vehicle_description_template"
                    id="enable_fbmp_vehicle_description_template_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="enable_fbmp_vehicle_description_template_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>
            <!-- FBMP Enable Template : END -->

            <!-- FBMP Merge Model With Trim : START -->
            <div class="row">
              <div class="col-md-4">
                <label>FBMP Merge Model With Trim While Posting</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.fbmp_merge_model_and_trim"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="fbmp_merge_model_and_trim"
                    id="fbmp_merge_model_and_trim_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="fbmp_merge_model_and_trim_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>
            <!-- FBMP Merge Model With Trim : END -->

            <!-- FBMP Vehicle Template : START -->
            <div
              class="row"
              v-show="form.enable_fbmp_vehicle_description_template == '1'"
            >
              <div class="col-md-4">
                <label>Vehicle Description Template</label>
              </div>
              <div class="col-md-8 form-group">
                <fieldset class="form-group">
                  <text-editor
                    name="description"
                    :elementId="'text-editor-1'"
                    ref="myTextEditor"
                    :options="editorOption"
                    v-model="form.fbmp_vehicle_description_template"
                    :editor_style="'min-height:15em;'"
                  >
                  </text-editor>
                </fieldset>
              </div>
            </div>
            <!-- FBMP Vehicle Template : END -->
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
import vSelect from "vue-select";
import TextEditor from "../WebComponents/TextEditor.vue";
import Toast from "../../library/Toast";

export default {
  name: "SettingsAccountFacebook",
  data() {
    return {
      submit: true,
      client_id: "",
      rpa_token: "",
      count_of_list_of_groups: 0,
      form: {
        fbmp_location: "",
        facebook_email_address: "",
        facebook_password: "",
        enable_fbmp_group_posting: "0",
        enable_inventory_changes_tracking_for_poster: "1",
        fbmp_list_of_groups: [],
        enable_fbmp_vehicle_description_template: "0",
        fbmp_vehicle_description_template: "",
        fbmp_merge_model_and_trim: "0",
      },
    };
  },
  components: {
    quillEditor,
    vSelect,
    TextEditor,
  },
  computed: {
    getFBMPLocations() {
      return [
        "Brampton, Ontario",
        "London, Ontario",
        "Waterloo, Ontario",
        "Mississauga, Ontario",
        "Edmonton, Alberta",
        "Sherwood Park, Alberta",
        "Red Deer, Alberta",
        "Leduc, Alberta",
        "Vancouver, British Columbia",
        "Victoria, British Columbia",
        "Kelowna, British Columbia",
        "Surrey, British Columbia",
        "Burnaby, British Columbia",
      ];
    },
  },
  methods: {
    doCopy(current_string) {
      this.$helpers.doCopy(this, current_string);
    },
    async getGlobalConfiguration() {
      let request_url =
        window.location.origin + "/api/business/configurations/global";

      await axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            this.hasOwnProperty(element.key) && element.key === "rpa_token"
              ? (this[element.key] = element.value)
              : null;
          }
        }

        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            this.form.hasOwnProperty(element.key) &&
            element.key !== "facebook_password"
              ? (this.form[element.key] = element.value)
              : null;
          }
        }
      });
    },
    async getInventoryConfiguration() {
      let request_url =
        window.location.origin + "/api/business/configurations/inventory";

      await axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (element.key == "fbmp_list_of_groups" && element.value != "") {
              this.form.hasOwnProperty(element.key)
                ? (this.form[element.key] = JSON.parse(element.value))
                : null;
            } else {
              this.form.hasOwnProperty(element.key)
                ? (this.form[element.key] = element.value)
                : null;
            }
          }
        }

        if (this.form.fbmp_list_of_groups == "") {
          this.form.fbmp_list_of_groups = [];
        } else {
          this.count_of_list_of_groups = this.form.fbmp_list_of_groups.length;
        }
      });
    },
    saveConfiguration() {
      const vm = this;
      this.submit = false;
      let formData = new FormData();
      for (const key in this.form) {
        let element = this.form[key];
        if (key != "fbmp_list_of_groups") {
          if (element.trim() !== "") {
            formData.append(key, this.form[key]);
          }
        }
      }

      formData.append(
        "fbmp_list_of_groups",
        JSON.stringify(this.form["fbmp_list_of_groups"]),
      );

      let request_url = window.location.origin + "/api/business/configurations";
      let message = "Saving account settings...";
      const toast = new Toast(message);
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
          let success_message = "Finished saving configurations.";
          toast.success(success_message);
          toast.close();

          Event.fire("change_rpa_settings", null);

          setTimeout(() => {
            vm.$data.submit = true;
          }, 500);
        })
        .catch((error) => {
          let error_message = "There were some errors in saving settings.";
          toast.fail(error_message);
          toast.close();

          setTimeout(() => {
            vm.$data.submit = true;
          }, 500);
        });
    },
  },
  mounted() {
    const vm = this;

    const main_domain = psl.parse(window.location.host);
    this.client_id = main_domain.subdomain;
    this.getGlobalConfiguration();
    this.getInventoryConfiguration();

    Event.listen("change_rpa_settings", (data) => {
      vm.getGlobalConfiguration();
      vm.getInventoryConfiguration();
    });
  },
};
</script>
<style lang="scss">
#text-editor-settings-1 {
  #ql-picker-options-4 {
    max-height: 300px;
  }
}
</style>
