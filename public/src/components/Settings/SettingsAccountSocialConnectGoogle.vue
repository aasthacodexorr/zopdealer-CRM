<template>
  <div
      role="tabpanel"
      class="tab-pane"
      id="setting-account-socialconnect-google"
      aria-labelledby="setting-account-socialconnect-google"
      aria-expanded="true"
  >
    <div class="content-wrapper m-0 border-bottom todo-fixed-search">
      <div class="row">
        <div class="col-md-5 col-12">
          <ul class="pl-0">
            <li class="d-inline-block mr-1">
              <div class="content-list-box">
                <p class="p-0 m-0">
                  <span class="blue-color text-bold-400 title-sizeincrease-font"
                  >SocialConnect (Google) Settings</span
                  >
                </p>
              </div>
            </li>
          </ul>
        </div>
        <div class="col-md-7 col-12">
          <ul class="p-0 float-right d-flex">
            <li class="d-inline-block">
              <a
                  class="btn btn-outline-success"
                  v-show="!isSocialConnectGoogleAccountAvailable"
                  @click.prevent="getLoginUrl('google')"
              >G CONNECT</a
              >
              <button
                  class="btn btn-light-secondary mr-1"
                  @click.prevent="getModuleConfiguration('socialconnect')"
              >
                <i class="mr-1 bx bx-sync"></i>Clear Status
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div
        class="list-right-task-list list-group ps p-0"
        style="overflow-y:auto !important;"
    >
      <div class="content-wrapper mt-0 pb-1 pt-2">
        <div class="list-image-content">
          <div class="row">
            <div
                class="card-deck"
                v-show="isSocialConnectGoogleAccountAvailable"
            >
              <div class="card" style="width: 50rem;">
                <div class="card-body">
                  <h5 class="card-title">
                    <i class="bx bxl-google"></i>Google Business
                  </h5>
                  <h6 class="card-subtitle mb-2 text-muted">
                    Connected Account:
                  </h6>
                  <table class="table">
                    <thead>
                    <tr>
                      <th scope="col"></th>
                      <th scope="col" style="width:50px;">Location ID</th>
                      <th scope="col">Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="(location, index) in socialconnect_google_account"
                        :key="location.business_location_id"
                    >
                      <td class="left-space-box">
                        <div class="form-check form-check-inline">
                          <div class="checkbox">
                            <input
                                class="checkbox-input d-none"
                                v-model="form.default_socialconnect_google_location"
                                type="radio"
                                :id="index + '_business_location_id'"
                                :value="location.business_location_id"
                            />
                            <label
                                :for="index + '_business_location_id'"
                                class="form-check-label"
                                style="cursor: pointer;"
                            ></label>
                          </div>
                        </div>
                      </td>
                      <td>
                        <small>{{ location.business_location_id }}</small>
                      </td>
                      <td>
                        <h6>{{ location.business_name }}</h6>
                        <small>{{ location.business_address }}</small>
                        <div>
                          <a
                              :href="location.business_website"
                              title="Business Website"
                              target="_blank"
                              rel="noopener noreferrer"
                          ><i class="bx text-secondary bx-world bx-md"></i
                          ></a>

                          <a
                              :href="location.business_map"
                              title="Map"
                              target="_blank"
                              rel="noopener noreferrer"
                          ><i
                              class="bx text-secondary bxs-map-alt bx-md"
                          ></i
                          ></a>
                        </div>
                      </td>
                    </tr>
                    </tbody>
                  </table>

                  <div class="card-footer pl-0">
                    <a
                        href="#"
                        class="btn btn-outline-danger"
                        @click="revoke('google')"
                    >UNLINK</a
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--ELSE START-->

    <!--ELSE END-->
  </div>
  <!-- END-->
</template>
<script>
import { io } from "socket.io-client";
import Toast from "../../library/Toast";

export default {
  name: "SettingsAccountSocialConnectGoogle",
  data() {
    return {
      submit: true,
      socialconnect_google_account: [],
      form: {
        default_socialconnect_google_location: "",
      },
    };
  },
  computed: {
    isSocialConnectGoogleAccountAvailable() {
      if (typeof this.socialconnect_google_account === "object") {
        if (Array.isArray(this.socialconnect_google_account)) {
          if (this.socialconnect_google_account.length > 0) {
            return true;
          }
        }
      }

      return false;
    },
  },
  watch: {
    "form.default_socialconnect_google_location": function(newVal, oldVal) {
      if (this.submit) {
        if (newVal != oldVal) {
          this.saveDefaultGoogleLocation();
        }
      }
    },
  },
  methods: {
    async getModuleConfiguration(module = "global") {
      let request_url =
          window.location.origin + "/api/business/configurations/" + module;

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
            if (element.key === "socialconnect_google_account") {
              if (this.$helpers.isValidJsonString(element.value)) {
                this.socialconnect_google_account = JSON.parse(element.value);
              } else {
                this.socialconnect_google_account = [];
              }
            } else {
              this.form.hasOwnProperty(element.key)
                  ? (this.form[element.key] = element.value)
                  : null;
            }
          }
        }
      });
    },
    async revoke(source) {
      if (confirm("Do you really want to proceed?")) {
        const toast = new Toast("Processing request...");
        toast.fire();
        await axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: `${window.location.origin}/api/business/socialconnect/revoke/${source}`,
        })
            .then(({ data }) => {
              toast.success(data.success);
              toast.close();

              this.getModuleConfiguration("socialconnect");
            })
            .catch(({ response }) => {
              toast.fail(response.data.messages.error);
              toast.close();
            });
      }
    },
    async getLoginUrl(source) {
      const toast = new Toast("Processing request...");
      toast.fire();
      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: `${window.location.origin}/api/business/socialconnect/login/${source}`,
      })
          .then(({ data }) => {
            toast.success("opened in new tab");
            toast.close();
            if (data.hasOwnProperty("url")) {
              window.open(data["url"], "_blank");
            }
          })
          .catch(({ response }) => {
            toast.fail(response.data.messages.error);
            toast.close();
          });
    },
    async saveDefaultGoogleLocation() {
      const vm = this;
      let formData = new FormData();
      for (const key in this.form) {
          formData.append(key, this.form[key]);
      }

      let request_url = window.location.origin + "/api/business/configurations";
      let message = "Saving google default location...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
          .then(({ data }) => {
            let success_message = "Google default location saved.";
            toast.success(success_message);
            toast.close();
          })
          .catch((error) => {
            let error_message =
                "There were some errors in saving default location.";
            toast.fail(error_message);
            toast.close();
          });
    },
    socketIoListener(data) {
      const vm = this;
      console.log(data);
      if (data.user_id === "*" && data.subject === `SOCIALCONNECT:GOOGLE:AUTHORIZE`) {
        vm.getModuleConfiguration("socialconnect");
      }
    },
  },
  async created() {
    if (window.global.app_metadata.organization) {
      this.socket = io(
          `wss://zopnotify.com:3001/` + window.global.app_metadata.organization
      );
     this.socket.on("notification", this.socketIoListener);
    }
  },
  async mounted() {
    this.submit = false;
    await this.getModuleConfiguration();
    await this.getModuleConfiguration("socialconnect");
    this.submit = true;
  },
};
</script>
