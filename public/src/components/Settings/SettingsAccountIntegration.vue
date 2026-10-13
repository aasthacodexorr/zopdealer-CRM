<template>
  <div
    role="tabpanel"
    class="tab-pane"
    id="setting-account-integration"
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
                      >Integration Settings</span
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
                <label>Carfax Dealer Id</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="carfax_vehicle_history_api"
                  class="form-control"
                  :elementId="'text-editor-settings-1'"
                  v-model="form.carfax_vehicle_history_api"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Carfax Account Token (If Available)</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="carfax_account_token"
                  class="form-control"
                  :elementId="'text-editor-settings-1'"
                  v-model="form.carfax_account_token"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Hide VIN on Carfax Report</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.carfax_report_hide_vin"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Hide VIN on Carfax Report"
                    id="carfax_report_hide_vin_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="carfax_report_hide_vin_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable SMS Package (Twilio)</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.enable_sms_package"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Enable SMS Package"
                    id="enable_sms_package_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="enable_sms_package_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Monthly Recurring SMS Credits</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="monthly_recurring_sms_credit"
                  class="form-control"
                  type="number"
                  v-model="form.monthly_recurring_sms_credit"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Available SMS Credits</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="number"
                  name="monthly_recurring_sms_credit"
                  class="form-control"
                  v-model="form.available_sms_credit"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable Kelly Blue Book</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.enable_kelly_blue_book"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Enable Kelly Blue Book"
                    id="enable_kelly_blue_book_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="enable_kelly_blue_book_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Stripe Account</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  name="stripe_account"
                  class="form-control"
                  v-model="form.stripe_account"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Default Deposit Amount</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="number"
                  name="default_deposit_amount"
                  class="form-control"
                  v-model="form.default_deposit_amount"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable Reviews</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.enable_reviews"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="enable_reviews"
                    id="enable_reviews_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="enable_reviews_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Glo3D User Name</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="glo3d_username"
                  class="form-control"
                  :elementId="'text-editor-settings-1'"
                  v-model="form.glo3d_username"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Glo3D Password</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="glo3d_password"
                  class="form-control"
                  :elementId="'text-editor-settings-1'"
                  v-model="form.glo3d_password"
                />
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

export default {
  name: "SettingsAccountIntegration",
  data() {
    return {
      submit: true,
      form: {
        carfax_vehicle_history_api: "",
        carfax_report_hide_vin: "0",
        enable_sms_package: "0",
        monthly_recurring_sms_credit: "1000",
        available_sms_credit: "1000",
        enable_kelly_blue_book: "0",
        stripe_account: "",
        default_deposit_amount: "0",
        enable_reviews: "0",
        glo3d_username: "",
        glo3d_password: "",
        carfax_account_token: "",
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
  watch: {
    "form.monthly_recurring_sms_credit": function (newVal, oldVal) {
      let monthly_recurring_sms_credit =
        !isNaN(this.form.monthly_recurring_sms_credit) &&
        this.form.monthly_recurring_sms_credit != ""
          ? this.form.monthly_recurring_sms_credit
          : 0;

      this.form.monthly_recurring_sms_credit = monthly_recurring_sms_credit;
    },
    "form.available_sms_credit": function (newVal, oldVal) {
      let available_sms_credit =
        !isNaN(this.form.available_sms_credit) &&
        this.form.available_sms_credit != ""
          ? this.form.available_sms_credit
          : 0;

      this.form.available_sms_credit = available_sms_credit;
    },
    "form.default_deposit_amount": function (newVal, oldVal) {
      let default_deposit_amount =
        !isNaN(this.form.default_deposit_amount) &&
        this.form.default_deposit_amount != ""
          ? this.form.default_deposit_amount
          : 0;

      this.form.default_deposit_amount = default_deposit_amount;
    },
  },
  components: {
    quillEditor,
  },
  methods: {
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
  async mounted() {
    this.submit = false;
    await this.getGlobalConfiguration();
    await this.getInventoryConfiguration();
    this.submit = true;
  },
};
</script>
