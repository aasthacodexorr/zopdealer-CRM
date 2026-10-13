<template>
  <div
    role="tabpanel"
    class="tab-pane"
    id="setting-account-craigslist"
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
                      >Craigslist Settings</span
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
          <span class="text-danger"
            ><b
              >NOTE: Please double check the settings as invalid settings will
              result in Inventories saved as DRAFT</b
            ></span
          >
          <div class="list-image-content">
            <div class="row">
              <div class="col-md-4">
                <label>Email Privacy Option</label>
              </div>
              <div class="col-md-8 form-group">
                <select
                  class="custom-select form-control mr-1"
                  id="craigslist_email_privacy_option"
                  v-model="form.craigslist_email_privacy_option"
                  name="craigslist_email_privacy_option"
                >
                  <option value="" selected disabled>
                    Select Email Privacy
                  </option>
                  <option value="C">CL Mail Replay</option>
                  <option value="A">No replies to this email</option>
                </select>

                <small
                  id="craigslist_email_privacy_option-error"
                  class="form-text invalid-feedback"
                ></small>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Show Phone Number</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.craigslist_show_phone_ok"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="craigslist_show_phone_ok"
                    id="craigslist_show_phone_ok_switch_for_craigslist_settings"
                  />
                  <label
                    class="custom-control-label"
                    for="craigslist_show_phone_ok_switch_for_craigslist_settings"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row" v-show="form.craigslist_show_phone_ok == '1'">
              <div class="col-md-4">
                <label>Phone Calls OK</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.craigslist_contact_phone_ok"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="craigslist_contact_phone_ok"
                    id="craigslist_contact_phone_ok_switch_for_craigslist_settings"
                  />
                  <label
                    class="custom-control-label"
                    for="craigslist_contact_phone_ok_switch_for_craigslist_settings"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row" v-show="form.craigslist_show_phone_ok == '1'">
              <div class="col-md-4">
                <label>Text/sms OK</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.craigslist_contact_text_ok"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="craigslist_contact_text_ok"
                    id="craigslist_contact_text_ok_switch_for_craigslist_settings"
                  />
                  <label
                    class="custom-control-label"
                    for="craigslist_contact_text_ok_switch_for_craigslist_settings"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row" v-show="form.craigslist_show_phone_ok == '1'">
              <div class="col-md-4">
                <label>Phone Number (10 Digits Valid Number)</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="craigslist_contact_phone"
                  class="form-control"
                  v-model="form.craigslist_contact_phone"
                />
                <small
                  id="craigslist_contact_phone-error"
                  class="form-text invalid-feedback"
                ></small>
              </div>
            </div>

            <div class="row" v-show="form.craigslist_show_phone_ok == '1'">
              <div class="col-md-4">
                <label>Phone Number Extension</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="craigslist_contact_phone_extension"
                  class="form-control"
                  v-model="form.craigslist_contact_phone_extension"
                />
                <small
                  id="craigslist_contact_phone_extension-error"
                  class="form-text invalid-feedback"
                ></small>
              </div>
            </div>

            <div class="row" v-show="form.craigslist_show_phone_ok == '1'">
              <div class="col-md-4">
                <label>Contact Person Name</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="craigslist_contact_name"
                  class="form-control"
                  v-model="form.craigslist_contact_name"
                />
                <small
                  id="craigslist_contact_name-error"
                  class="form-text invalid-feedback"
                ></small>
              </div>
            </div>

            <hr />

            <div class="row">
              <div class="col-md-4">
                <label>Show Address</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.craigslist_show_address_ok"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="craigslist_show_address_ok"
                    id="craigslist_show_address_ok_switch_for_craigslist_settings"
                  />
                  <label
                    class="custom-control-label"
                    for="craigslist_show_address_ok_switch_for_craigslist_settings"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row" v-show="form.craigslist_show_address_ok == '1'">
              <div class="col-md-4">
                <label>Street</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="craigslist_address_street"
                  class="form-control"
                  v-model="form.craigslist_address_street"
                />
                <small
                  id="craigslist_address_street-error"
                  class="form-text invalid-feedback"
                ></small>
              </div>
            </div>

            <div class="row" v-show="form.craigslist_show_address_ok == '1'">
              <div class="col-md-4">
                <label>Cross Street</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="craigslist_address_cross_street"
                  class="form-control"
                  v-model="form.craigslist_address_cross_street"
                />
                <small
                  id="craigslist_address_cross_street-error"
                  class="form-text invalid-feedback"
                ></small>
              </div>
            </div>

            <div class="row" v-show="form.craigslist_show_address_ok == '1'">
              <div class="col-md-4">
                <label>City</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="craigslist_address_city"
                  class="form-control"
                  v-model="form.craigslist_address_city"
                />
                <small
                  id="craigslist_address_city-error"
                  class="form-text invalid-feedback"
                ></small>
              </div>
            </div>

            <hr />

            <div class="row">
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
                    id="enable_inventory_changes_tracking_for_poster_switch_for_craigslist_settings"
                  />
                  <label
                    class="custom-control-label"
                    for="enable_inventory_changes_tracking_for_poster_switch_for_craigslist_settings"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
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
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import Toast from "../../library/Toast";
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";
import { quillEditor } from "vue-quill-editor";
import vSelect from "vue-select";

export default {
  name: "SettingsAccountCraigslist",
  data() {
    return {
      submit: true,
      client_id: "",
      rpa_token: "",
      form: {
        enable_inventory_changes_tracking_for_poster: "1",
        craigslist_email_privacy_option: "C",
        craigslist_show_phone_ok: "0",
        craigslist_contact_phone_ok: "0",
        craigslist_contact_text_ok: "0",
        craigslist_show_address_ok: "0",

        craigslist_contact_phone: "",
        craigslist_contact_phone_extension: "",
        craigslist_contact_name: "",

        craigslist_address_street: "",
        craigslist_address_cross_street: "",
        craigslist_address_city: "",
      },
    };
  },
  components: {
    quillEditor,
    vSelect,
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
            this.hasOwnProperty(element.key) && element.key == "rpa_token"
              ? (this[element.key] = element.value)
              : null;
          }
        }

        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            this.form.hasOwnProperty(element.key)
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
            this.form.hasOwnProperty(element.key)
              ? (this.form[element.key] = element.value)
              : null;
          }
        }
      });
    },
    saveConfiguration() {
      const vm = this;
      this.submit = false;
      let formData = new FormData();
      for (const key in this.form) {
        formData.append(key, this.form[key]);
      }

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
