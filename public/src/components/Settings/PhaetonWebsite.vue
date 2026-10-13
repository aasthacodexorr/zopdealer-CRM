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
              id="phaeton-website"
              data-toggle="tab"
              href="#phaeton-website"
              aria-controls="Phaeton Website Settings"
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
              id="phaeton-website-tab"
              aria-labelledby="phaeton-website"
              role="tabpanel"
            >
              <!-- form layout section start -->
              <section id="phaeton-website-section">
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
                                    <!-- Facebook Social Media Link : START -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <label>Facebook Link</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <input
                                          type="text"
                                          name="social_media_facebook"
                                          class="form-control"
                                          :elementId="'text-editor-settings-1'"
                                          v-model="form.social_media_facebook"
                                        />
                                      </div>
                                    </div>
                                    <!-- Facebook Social Media Link : END -->

                                    <!-- Instagram Social Media Link : START -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <label>Instagram Link</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <input
                                          type="text"
                                          name="social_media_instagram"
                                          class="form-control"
                                          :elementId="'text-editor-settings-1'"
                                          v-model="form.social_media_instagram"
                                        />
                                      </div>
                                    </div>
                                    <!-- Instagram Social Media Link : END -->

                                    <!-- Twitter Social Media Link : START -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <label>Twitter Link</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <input
                                          type="text"
                                          name="social_media_twitter"
                                          class="form-control"
                                          :elementId="'text-editor-settings-1'"
                                          v-model="form.social_media_twitter"
                                        />
                                      </div>
                                    </div>
                                    <!-- Twitter Social Media Link : END -->

                                    <!-- Youtube Social Media Link : START -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <label>Youtube Link</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <input
                                          type="text"
                                          name="social_media_youtube"
                                          class="form-control"
                                          :elementId="'text-editor-settings-1'"
                                          v-model="form.social_media_youtube"
                                        />
                                      </div>
                                    </div>
                                    <!-- Youtube Social Media Link : END -->

                                    <!-- Google Review Social Media Link : START -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <label>Google Review Link</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <input
                                          type="text"
                                          name="social_media_google_review"
                                          class="form-control"
                                          :elementId="'text-editor-settings-1'"
                                          v-model="
                                            form.social_media_google_review
                                          "
                                        />
                                      </div>
                                    </div>
                                    <!-- Google Review Social Media Link : END -->

                                    <!-- Tiktok Social Media Link : START -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <label>Tiktok Link</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <input
                                          type="text"
                                          name="social_media_tiktok"
                                          class="form-control"
                                          :elementId="'text-editor-settings-1'"
                                          v-model="form.social_media_tiktok"
                                        />
                                      </div>
                                    </div>
                                    <!-- Tiktok Social Media Link : END -->

                                    <!-- Dealership Logo : START -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <label>Dealership Logo</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <input
                                          type="text"
                                          name="dealership_logo"
                                          class="form-control"
                                          :elementId="'text-editor-settings-1'"
                                          v-model="form.dealership_logo"
                                        />
                                      </div>
                                    </div>
                                    <!-- Dealership Logo : END -->

                                    <!-- Working Hours : START -->
                                    <div class="row">
                                      <div class="col-md-4">
                                        <label>Working Hours</label>
                                      </div>
                                    </div>
                                    <div class="row contact-repeater">
                                      <div class="col-md-4">
                                        <button
                                          class="btn btn-icon btn-primary"
                                          @click="
                                            addANewColumnForWorkingHours()
                                          "
                                          type="button"
                                        >
                                          <i class="bx bx-plus"></i>Add New Row
                                        </button>
                                      </div>
                                      <div
                                        class="col-md-8 form-group"
                                        data-repeater-list="contact"
                                      >
                                        <!-- start Loop for working hours -->
                                        <div
                                          class="row"
                                          v-for="n in this
                                            .count_of_working_hours_entities"
                                          :key="n"
                                        >
                                          <div
                                            class="col-md-5 col-12 form-group"
                                          >
                                            <input
                                              type="text"
                                              placeholder="Day"
                                              class="form-control"
                                              v-model="
                                                form.working_hours[n - 1]['day']
                                              "
                                            />
                                          </div>
                                          <div
                                            class="col-md-5 col-12 form-group"
                                          >
                                            <input
                                              type="text"
                                              placeholder="Hours"
                                              class="form-control"
                                              v-model="
                                                form.working_hours[n - 1][
                                                  'hours'
                                                ]
                                              "
                                            />
                                          </div>
                                          <div
                                            class="col-md-2 col-12 form-group pl-0"
                                          >
                                            <button
                                              class="btn btn-danger text-nowrap px-1"
                                              @click="
                                                form.working_hours.splice(
                                                  n - 1,
                                                  1
                                                );
                                                count_of_working_hours_entities -= 1;
                                              "
                                              type="button"
                                            >
                                              <i class="bx bx-x"></i>
                                            </button>
                                          </div>
                                        </div>
                                        <!-- end Loop for working hours -->
                                      </div>
                                    </div>
                                    <!-- Working Hours : END -->
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
import Toast from "../../library/Toast";

export default {
  name: "PhaetonWebsite",
  props: ["application", "app_metadata"],
  data() {
    return {
      count_of_working_hours_entities: 0,
      submit: true,
      form: {
        dealership_logo: "",
        social_media_tiktok: "",
        social_media_google_review: "",
        social_media_youtube: "",
        social_media_twitter: "",
        social_media_instagram: "",
        social_media_facebook: "",
        working_hours: [],
      },
      errors: [],
    };
  },
  computed: {
    tenantApp() {
      return this.application == "tenant";
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
  },
  methods: {
    addANewColumnForWorkingHours() {
      this.form.working_hours.push({ day: "", hours: "" });
      this.count_of_working_hours_entities += 1;
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
            if (element.key == "working_hours" && element.value != "") {
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
        if (this.form.working_hours == "") {
          this.form.working_hours = [];
        } else {
          this.count_of_working_hours_entities = this.form.working_hours.length;
        }
      });
    },
    async saveConfiguration() {
      const vm = this;
      this.submit = false;
      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && key != "working_hours") {
          formData.append(key, this.form[key]);
        }
      }
      formData.append(
        "working_hours",
        JSON.stringify(this.form["working_hours"])
      );

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
    await this.getModuleConfiguration("website");
    this.submit = true;
  },
};
</script>
    