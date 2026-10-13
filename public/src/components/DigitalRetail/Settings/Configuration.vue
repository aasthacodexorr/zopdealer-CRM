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
              id="digital-retailing-settings-configuration"
              data-toggle="tab"
              href="#digital-retailing-settings-configuration"
              aria-controls="Digital Quote Configurations"
              role="tab"
              aria-selected="true"
            >
              <span class="">Configuration Settings</span>
            </a>
          </li>
          <div class="btn-feild-box">
            <button class="btn btn-primary mr-1" @click.prevent="saveConfiguration">
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
              id="digital-retailing-settings-configuration-tab"
              aria-labelledby="digital-retailing-settings-configuration"
              role="tabpanel"
            >
              <!-- form layout section start -->
              <section id="digital-retailing-settings-configuration-section">
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
                                    <!-- Credit Application Template : START -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <label
                                          >Credit Application Template</label
                                        >
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <input
                                          type="text"
                                          name="default_digital_retailing_credit_application_template"
                                          class="form-control"
                                          :elementId="'text-editor-settings-1'"
                                          v-model="
                                            form.default_digital_retailing_credit_application_template
                                          "
                                        />
                                      </div>
                                    </div>
                                    <!-- Credit Application Template : END -->
                                  </div>
                                </div>
                              </div>
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
      form: {
        default_digital_retailing_credit_application_template: "",
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

      let request_url = window.location.origin + "/api/business/configurations";
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
    this.submit = false;
    await this.getModuleConfiguration("digital_retailing");
    this.submit = true;
  },
};
</script>
