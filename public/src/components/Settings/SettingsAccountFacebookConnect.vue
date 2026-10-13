<template>
  <div
    role="tabpanel"
    class="tab-pane"
    id="setting-account-facebookconnect"
    aria-labelledby="Facebook Review Setting"
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
                    >Reviews Settings</span
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
                v-show="!isFacebookReviewAccountDetailsAvailable"
                @click.prevent="getLoginUrl('facebook')"
                >FB CONNECT</a
              >
              <button
                class="btn btn-light-secondary mr-1"
                @click.prevent="getModuleConfiguration('reviews')"
              >
                <i class="mr-1 bx bx-sync"></i>Refresh Status
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div
      v-if="isFacebookConnectAvailable"
      class="list-right-task-list list-group ps p-0"
      style="overflow-y:auto !important;"
    >
      <div class="content-wrapper mt-0 pb-1 pt-2">
        <div class="list-image-content">
          <div class="row">
            <div
              class="card-deck"
              v-show="isFacebookReviewAccountDetailsAvailable"
            >
              <div class="card" style="width: 50rem;">
                <div class="card-body">
                  <h5 class="card-title">
                    <i class="bx bxl-facebook"></i>Facebook
                  </h5>
                  <h6 class="card-subtitle mb-2 text-muted">
                    Connected Account:
                  </h6>
                  <table class="table">
                    <thead>
                      <tr>
                        <th scope="col"></th>
                        <th scope="col" style="width:50px;">ID</th>
                        <th scope="col">Details</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(location, index) in facebook_reviews_account"
                        :key="location.business_location_id"
                      >
                        <td class="left-space-box">
                          <div class="form-check form-check-inline">
                            <div class="checkbox">
                              <input
                                class="checkbox-input d-none"
                                v-model="form.default_facebook_review_location"
                                type="radio"
                                :id="index + '_business_location_id'"
                                :value="location.business_id"
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
                          <small>{{ location.business_id }}</small>
                        </td>
                        <td>
                          <h6>{{ location.business_name }}</h6>
                          <small>{{ location.business_address }}</small>
                          <div>
                            <a
                              :href="location.business_review_url"
                              title="Review Link"
                              target="_blank"
                              rel="noopener noreferrer"
                              ><i
                                class="bx text-secondary bxs-star-half bx-md"
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
                      @click="revoke('facebook')"
                      >UNLINK</a
                    >
                    <a
                      href="#"
                      class="btn btn-outline-success"
                      @click="fetchReviews('facebook')"
                      >UPDATE REVIEWS</a
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
    <div class="content-wrapper mt-0 pb-1 pt-2" v-else>
      <div class="list-image-content">
        <div class="row">
          <h2>
            You are not authorized to acess this page. Please contact admin.
          </h2>
        </div>
      </div>
    </div>
    <!--ELSE END-->
  </div>
  <!-- END-->
</template>
<script>
import { io } from "socket.io-client";
import Toast from "../../library/Toast";

export default {
  name: "SettingsAccountFacebookConnect",
  data() {
    return {
      submit: true,
      facebook_reviews_account: [],
      form: {
        enable_reviews: "",
        default_facebook_review_location: "",
      },
    };
  },
  computed: {
    isFacebookConnectAvailable() {
      return this.form.enable_reviews == "1";
    },
    isFacebookReviewAccountDetailsAvailable() {
      if (typeof this.facebook_reviews_account === "object") {
        if (Array.isArray(this.facebook_reviews_account)) {
          if (this.facebook_reviews_account.length > 0) {
            return true;
          }
        }
      }

      return false;
    },
  },
  watch: {
    "form.default_facebook_review_location": function(newVal, oldVal) {
      if (this.submit) {
        if (newVal != oldVal) {
          this.saveDefaultFacebookLocation();
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
            if (element.key === "facebook_reviews_account") {
              if (this.$helpers.isValidJsonString(element.value)) {
                this.facebook_reviews_account = JSON.parse(element.value);
              } else {
                this.facebook_reviews_account = [];
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
          url: `${window.location.origin}/api/business/external-reviews/revoke/${source}`,
        })
          .then(({ data }) => {
            toast.success(data.success);
            toast.close();

            this.getModuleConfiguration("reviews");
          })
          .catch(({ response }) => {
            toast.fail(response.data.messages.error);
            toast.close();
          });
      }
    },
    async fetchReviews(source, ask_confirmation = true) {
      const fetch = async () => {
        const toast = new Toast("Processing request...");
        toast.fire();
        await axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: `${window.location.origin}/api/business/external-reviews/fetch/${source}`,
        })
          .then(({ data }) => {
            toast.success(data.success);
            toast.close();
          })
          .catch(({ response }) => {
            toast.fail(response.data.messages.error);
            toast.close();
          });
      };

      if (!ask_confirmation) {
        fetch();
      } else if (confirm("Do you really want to proceed?")) {
        fetch();
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
        url: `${window.location.origin}/api/business/external-reviews/login/${source}`,
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
    async saveDefaultFacebookLocation() {
      const vm = this;
      let formData = new FormData();
      for (const key in this.form) {
        if (key != "enable_reviews") {
          formData.append(key, this.form[key]);
        }
      }

      let request_url = window.location.origin + "/api/business/configurations";
      let message = "Saving facebook default location...";
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
          let success_message = "Facebook default location saved.";
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
      if (data.user_id === "*" && data.subject === `REVIEW:FACEBOOK:AUTHORIZE`) {
        vm.getModuleConfiguration("reviews");
        vm.fetchReviews("facebook", false);
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
    await this.getModuleConfiguration("reviews");
    this.submit = true;
  },
};
</script>
