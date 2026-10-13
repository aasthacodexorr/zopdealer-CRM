<template>
  <div
    role="tabpanel"
    class="tab-pane active"
    id="user-settings-email-channel"
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
                      >Email Channel</span
                    >
                  </p>
                </div>
              </li>
            </ul>
          </div>
          <div class="col-md-7 col-12" v-if="!id">
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
                  @click.prevent="saveEmailChannel"
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
          <div class="list-image-content" v-if="id">
            <div class="row">
              <div class="col-xs-12 col-12">
                You have configured {{ form.email }} with our system to recieve
                and send emails. Do you want to delete it?
              </div>
              <div class="col-xs-12 col-12 p-1">
                <button
                  class="btn btn-danger"
                  @click="DeleteEmailConfiguration()"
                >
                  Delete
                </button>
              </div>
            </div>
          </div>
          <div class="list-image-content" v-else>
            <div class="row">
              <h5 class="col-xs-12 text-danger" style="font-size: 0.8em">
                Note: Email only works with providers that have SMTP delivery.
                Gmail and Office 365 will not work
              </h5>
            </div>
            <div class="m-width-s"></div>
            <div class="row">
              <div class="col-md-4">
                <label>Name <span class="text-danger">*</span> </label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="name"
                  type="text"
                  class="form-control"
                  v-model="form.name"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('name'),
                  }"
                  @change="delete errors.name"
                  :title="errors.name"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Email <span class="text-danger">*</span></label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="email"
                  v-model="form.email"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('email'),
                  }"
                  @change="delete errors.email"
                  :title="errors.email"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Password <span class="text-danger">*</span></label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="password"
                  class="form-control"
                  name="password"
                  v-model="form.password"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('password'),
                  }"
                  @change="delete errors.password"
                  :title="errors.password"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label
                  >SMTP (Outgoing) Host Name <span class="text-danger">*</span>
                </label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="name"
                  type="outgoing_hostname"
                  class="form-control"
                  v-model="form.outgoing_hostname"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('outgoing_hostname'),
                  }"
                  @change="delete errors.outgoing_hostname"
                  :title="errors.outgoing_hostname"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label
                  >SMTP (Outgoing) Port & Security
                  <span class="text-danger">*</span></label
                >
              </div>
              <div class="col-md-7 form-group">
                <fieldset>
                  <div class="input-group">
                    <select
                      class="custom-select form-control mr-1"
                      id="outgoing_port"
                      v-model="form.outgoing_port"
                      name="port"
                    >
                      <option value="" selected disabled>Select Port</option>
                      <option value="465">465</option>
                      <option value="587">587</option>
                      <option value="25">25</option>
                    </select>
                    <select
                      class="custom-select form-control mr-1"
                      id="outgoing_secure"
                      v-model="form.outgoing_secure"
                      name="outgoing_secure"
                    >
                      <option value="" selected disabled>
                        Select Security
                      </option>
                      <option value="ssl">SSL</option>
                      <option value="tls">TLS</option>
                    </select>
                  </div>
                </fieldset>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label
                  >IMAP (Incoming) Host Name <span class="text-danger">*</span>
                </label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="name"
                  type="incoming_hostname"
                  class="form-control"
                  v-model="form.incoming_hostname"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('incoming_hostname'),
                  }"
                  @change="delete errors.incoming_hostname"
                  :title="errors.incoming_hostname"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label
                  >IMAP (Incoming) Port & Security
                  <span class="text-danger">*</span></label
                >
              </div>
              <div class="col-md-7 form-group">
                <fieldset>
                  <div class="input-group">
                    <select
                      class="custom-select form-control mr-1"
                      id="incoming_port"
                      v-model="form.incoming_port"
                      name="incoming_port"
                    >
                      <option value="" selected disabled>Select Port</option>
                      <option value="993">993</option>
                      <option value="143">143</option>
                    </select>
                    <select
                      class="custom-select form-control mr-1"
                      id="incoming_secure"
                      v-model="form.incoming_secure"
                      name="incoming_secure"
                    >
                      <option value="" selected disabled>
                        Select Security
                      </option>
                      <option value="ssl">SSL</option>
                      <option value="tls">TLS</option>
                    </select>
                  </div>
                </fieldset>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label
                  >Authentication <span class="text-danger">*</span>
                </label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    id="authenticationReq"
                    name="auth"
                    v-model="form.auth"
                    :true-value="'1'"
                    :false-value="'0'"
                    checked
                  />
                  <label class="custom-control-label" for="authenticationReq">
                    <span class="switch-icon-left"> ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
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
import vSelect from "vue-select";

export default {
  name: "UserEmailChannel",
  data() {
    return {
      id: null,
      submit: true,
      errors: [],
      form: {
        name: "",
        email: "",
        password: "",
        auth: "1",
        outgoing_hostname: "",
        outgoing_port: "465",
        outgoing_secure: "ssl",
        incoming_hostname: "",
        incoming_port: "993",
        incoming_secure: "ssl",
      },
    };
  },
  components: {
    "v-select": vSelect,
  },
  computed: {
    getTimeZones() {
      return moment.tz.names();
    },
  },
  methods: {
    QuickFillConfigurations(company_name) {
      switch (company_name) {
        case "yahoo":
          this.form.outgoing_hostname = "smtp.mail.yahoo.com";
          this.form.outgoing_port = "465";
          this.form.outgoing_secure = "ssl";

          this.form.incoming_hostname = "imap.mail.yahoo.com";
          this.form.incoming_port = "993";
          this.form.incoming_secure = "ssl";

          this.form.auth = "1";
          break;
        case "google":
          this.form.outgoing_hostname = "smtp.gmail.com";
          this.form.outgoing_port = "465";
          this.form.outgoing_secure = "ssl";

          this.form.incoming_hostname = "imap.gmail.com";
          this.form.incoming_port = "993";
          this.form.incoming_secure = "ssl";

          this.form.auth = "1";

          break;
        case "outlook":
          this.form.outgoing_hostname = "smtp.office365.com";
          this.form.outgoing_port = "587";
          this.form.outgoing_secure = "tls";

          this.form.incoming_hostname = "outlook.office365.com";
          this.form.incoming_port = "993";
          this.form.incoming_secure = "ssl";

          this.form.auth = "1";
          break;
      }
    },
    DeleteEmailConfiguration() {
      let request_url =
        window.location.origin +
        `/api/business/user/integrations/email/delete/${this.id}`;

      let message = "Deleting email configuration...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.id = null;
          toast.success(data.success);
          toast.close();
        })
        .catch(({ response }) => {
          let error_message =
            "There were some errors in deleting email configuration.";
          toast.fail(error_message);
          toast.close();
        });
    },
    getEmailDetails() {
      let request_url =
        window.location.origin + "/api/business/user/integrations/email";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.id = data.id;
          for (const key in this.form) {
            console.log(key);
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {});
    },
    saveEmailChannel() {
      const vm = this;
      this.submit = false;
      let formData = new FormData();
      for (const key in this.form) {
        formData.append(key, this.form[key]);
      }

      let request_url =
        window.location.origin + "/api/business/user/integrations/email";
      let message = "Saving email configuration...";
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
          toast.success(data.success);
          toast.close();

          setTimeout(() => {
            vm.$data.submit = true;
          }, 500);

          vm.getEmailDetails();
        })
        .catch((error) => {
          let error_message =
            "There were some errors in saving email configuration.";

          if (error.response.data.hasOwnProperty("errors")) {
            if (typeof error.response.data.errors == "string") {
              error_message = error.response.data.errors;
            }
          }

          toast.fail(error_message);
          toast.close();

          setTimeout(() => {
            vm.$data.submit = true;
          }, 500);

          vm.$data.errors = error.response.data;
        });
    },
  },
  mounted() {
    this.getEmailDetails();
  },
};
</script>
