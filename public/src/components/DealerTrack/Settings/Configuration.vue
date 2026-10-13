<template>
  <div v-if="tenantApp && isAuthorized">
    <div class="app-content content fixed-height-block">
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <router-link
              title="Back To Settings"
              tag="a"
              :to="{ name: 'settings-account-v2' }"
              class="nav-link pr-0 pl-0 d-flex align-items-center"
              exact
            >
              <i class="bx bx-caret-left" style="font-size: 1.4em"></i>
            </router-link>
          </li>
          <li class="nav-item">
            <a
              class="nav-link d-flex align-items-center active"
              id="dealer-track-settings-configuration"
              data-toggle="tab"
              href="#dealer-track-settings-configuration"
              aria-controls="Digital Quote Configurations"
              role="tab"
              aria-selected="true"
            >
              <span class="">Configuration Settings</span>
            </a>
          </li>
          <div class="btn-feild-box">
            <button
              class="btn btn-primary mr-1"
              @click.prevent="saveConfiguration"
            >
              Save
            </button>
          </div>
        </ul>
      </div>
    </div>
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="">
        <div class="content-body">
          <!-- form layout section start -->
          <div class="tab-content">
            <!-- single tab content goes here : START -->
            <div
              class="tab-pane active"
              id="dealer-track-settings-configuration-tab"
              aria-labelledby="dealer-track-settings-configuration"
              role="tabpanel"
            >
              <!-- form layout section start -->
              <section id="dealer-track-settings-configuration-section">
                <div class="row match-height">
                  <div class="col-md-12 col-12">
                    <div class="card shadow-none mb-0">
                      <div class="card-content">
                        <div class="card-body">
                          <form class="form form-horizontal webapp-form">
                            <div class="form-body" v-show="!submit">
                              Loading Saved Configurations ...
                            </div>
                            <div class="form-body" v-show="submit">
                              <div class="row">
                                <div class="col-md-12 col-12">
                                  <div class="m-width-s">
                                    <!-- Dealer ID : START -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <label>Dealer ID</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <input
                                          type="text"
                                          name="dealertrack_dealer_id"
                                          class="form-control"
                                          :elementId="'text-editor-settings-1'"
                                          v-model="form.dealertrack_dealer_id"
                                        />
                                      </div>
                                    </div>
                                    <!-- Dealer ID : END -->

                                    <!-- Dealer Confirmation ID : START -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <label>Dealer Confirmation ID</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <input
                                          type="text"
                                          name="dealertrack_dealer_confirm_id"
                                          class="form-control"
                                          :elementId="'text-editor-settings-1'"
                                          v-model="
                                            form.dealertrack_dealer_confirm_id
                                          "
                                        />
                                      </div>
                                    </div>
                                    <!-- Dealer Confirmation ID : END -->
                                  </div>
                                </div>
                              </div>
                              <!-- ROW : START -->
                              <div class="row">
                                <div class="col-md-12 col-12">
                                  <div class="m-width-s">
                                    <h4>Web Extension Credentials</h4>

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
                                        @click="doCopy(extension_crawler_token)"
                                      >
                                        {{ extension_crawler_token }}
                                        <i
                                          @click="
                                            doCopy(extension_crawler_token)
                                          "
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
                              </div>
                              <!-- ROW : END-->
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
            <!-- single tab content goes here : END -->
          </div>
        </div>
      </div>
    </div>
    <!-- END: Content-->
  </div>
</template>
  <script>
import Toast from "../../../library/Toast";
import { mapGetters, mapActions } from "vuex";

export default {
  name: "Configuration",
  props: ["application", "app_metadata"],
  data() {
    return {
      submit: true,
      client_id: "",
      extension_crawler_token: "",
      form: {
        dealertrack_dealer_id: "",
        dealertrack_dealer_confirm_id: "",
      },
      errors: [],
    };
  },
  computed: {
    tenantApp() {
      return this.application == "tenant";
    },
    mainApp() {
      return this.application == "main";
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
  },
  methods: {
    doCopy(current_string) {
      this.$helpers.doCopy(this, current_string);
    },
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
            this.hasOwnProperty(element.key) &&
            element.key === "extension_crawler_token"
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
    async saveConfiguration() {
      const vm = this;
      this.submit = false;
      let formData = new FormData();
      for (const key in this.form) {
        formData.append(key, this.form[key]);
      }

      let request_url =
        window.location.origin +
        "/api/business/dealer-track/configurations/save";
      let message = "Saving account settings...";
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
  async created() {},
  async mounted() {
    const main_domain = psl.parse(window.location.host);
    this.client_id = main_domain.subdomain;
    this.submit = false;
    await this.getModuleConfiguration("global");
    this.submit = true;
  },
};
</script>
  