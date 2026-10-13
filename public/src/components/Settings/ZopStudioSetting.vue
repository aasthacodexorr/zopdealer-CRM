<template>
  <div v-if="tenantApp">
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
              id="zop_studio_account_settings"
              data-toggle="tab"
              href="#zop_studio_account_settings"
              aria-controls="AI Account Settings"
              role="tab"
              aria-selected="true"
            >
              <span class="">AI Studio Settings</span>
            </a>
          </li>
          <div class="btn-feild-box">
            <button
              v-if="isAuthorized"
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
              id="zop_studio_account_settings-tab"
              aria-labelledby="zop_studio_account_settings"
              role="tabpanel"
            >
              <!-- form layout section start -->
              <section id="zop_studio_account_settings-section">
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
                                <small class="col-md-12 col-xs-12 text-danger">NOTE: Changes to AI Studio settings will consume credits.</small>
                                <div class="col-md-12 col-12">
                                  <div class="">
                                    <!-- Enable AI Studio : START -->
                                    <div class="row">
                                      <div class="col-md-2">
                                        <label>Enable AI Studio </label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <div
                                          class="custom-control custom-switch"
                                        >
                                          <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            v-model="form.enable_zop_studio"
                                            :true-value="'1'"
                                            :false-value="'0'"
                                            name="enable_zop_studio"
                                            id="enable_zop_studio_switch"
                                            :disabled="!isAuthorized"
                                          />
                                          <label
                                            class="custom-control-label"
                                            for="enable_zop_studio_switch"
                                          >
                                            <span class="switch-icon-left"
                                              >ON</span
                                            >
                                            <span class="switch-icon-right"
                                              >OFF</span
                                            >
                                          </label>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- Enable AI Studio : END -->

                                    <!-- Enable AI Studio Queue : START -->
                                    <div class="row">
                                      <div class="col-md-2">
                                        <label>Enable Automatted Queue </label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <div
                                          class="custom-control custom-switch"
                                        >
                                          <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            v-model="
                                              form.enable_zop_studio_queue
                                            "
                                            :true-value="'1'"
                                            :false-value="'0'"
                                            name="enable_zop_studio_queue"
                                            id="enable_zop_studio_queue_switch"
                                            :disabled="!isAuthorized"
                                          />
                                          <label
                                            class="custom-control-label"
                                            for="enable_zop_studio_queue_switch"
                                          >
                                            <span class="switch-icon-left"
                                              >ON</span
                                            >
                                            <span class="switch-icon-right"
                                              >OFF</span
                                            >
                                          </label>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- Enable AI Studio Queue : END -->

                                    <!-- Mask Numberplate : START -->
                                    <div class="row">
                                      <div class="col-md-2">
                                        <label>Mask Number Plate </label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <div
                                          class="custom-control custom-switch"
                                        >
                                          <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            v-model="
                                              form.zop_studio_mask_number_plate
                                            "
                                            :true-value="'1'"
                                            :false-value="'0'"
                                            name="zop_studio_mask_number_plate"
                                            id="zop_studio_mask_number_plate_switch"
                                            :disabled="!isAuthorized"
                                          />
                                          <label
                                            class="custom-control-label"
                                            for="zop_studio_mask_number_plate_switch"
                                          >
                                            <span class="switch-icon-left"
                                              >ON</span
                                            >
                                            <span class="switch-icon-right"
                                              >OFF</span
                                            >
                                          </label>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- Mask Numberplate : END -->

                                    <!-- Show Shadow : START -->
                                    <div class="row">
                                      <div class="col-md-2">
                                        <label>Show Shadow</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <div
                                          class="custom-control custom-switch"
                                        >
                                          <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            v-model="
                                              form.zop_studio_show_shadow
                                            "
                                            :true-value="'1'"
                                            :false-value="'0'"
                                            name="zop_studio_show_shadow"
                                            id="zop_studio_show_shadow_switch"
                                            :disabled="!isAuthorized"
                                          />
                                          <label
                                            class="custom-control-label"
                                            for="zop_studio_show_shadow_switch"
                                          >
                                            <span class="switch-icon-left"
                                              >ON</span
                                            >
                                            <span class="switch-icon-right"
                                              >OFF</span
                                            >
                                          </label>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- Show Shadow : END -->

                                    <!-- Available Credits : START -->
                                    <div class="row">
                                      <div
                                        class="col-md-2"
                                        style="vertical-align: text-top"
                                      >
                                        <label>Available Credits</label>
                                      </div>
                                      <div
                                        class="col-md-8 form-group text-bold"
                                        style="
                                          font-weight: 900;
                                          letter-spacing: 1px;
                                          font-size: 1.4em;
                                        "
                                      >
                                        {{ form.zop_studio_available_credit }}
                                      </div>
                                    </div>
                                    <!-- Available Credits : END -->
                                    <!-- Selection of the Background Image : START -->
                                    <div class="row">
                                      <div
                                        class="col-md-2"
                                        style="vertical-align: text-top"
                                      >
                                        <label>Select A Background </label>
                                      </div>
                                      <div
                                        class="col-md-8 form-group text-bold"
                                        style="
                                          font-weight: 900;
                                          letter-spacing: 1px;
                                          font-size: 1.4em;
                                        "
                                      >
                                        <div class="row">
                                          <div
                                            class="col-xs-12 col-md-2 p-0 m-25 ai-bg-container"
                                            :class="
                                              form.zop_studio_background_image ==
                                              image
                                                ? 'active'
                                                : ''
                                            "
                                            :key="`image${image_index}`"
                                            v-for="(
                                              image, image_index
                                            ) in getAvailableBackgroundImages"
                                          >
                                            <img
                                              :src="image"
                                              class="img-fluid cursor-pointer ai-bg-hover img-thumbnail"
                                              alt=""
                                              srcset=""
                                              @click="
                                                form.zop_studio_background_image =
                                                  image
                                              "
                                            />
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- Selection of the Background Image : END -->
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
import ConfigurationMixin from "../../mixins/configuration";
export default {
  name: "ZopStudioSetting",
  props: ["application", "app_metadata"],
  data() {
    return {
      form: {
        enable_zop_studio: "0",
        enable_zop_studio_queue: "0",
        zop_studio_available_credit: "0",
        zop_studio_background_image: "",
        zop_studio_mask_number_plate: "0",
        zop_studio_show_shadow: "0",
        zop_studio_available_background_images: "",
      },
      errors: [],
    };
  },
  mixins: [ConfigurationMixin],
  computed: {
    tenantApp() {
      return this.application == "tenant";
    },
    mainApp() {
      return this.application == "main";
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role, [
        "Super Owner",
      ]);
    },
    getAvailableBackgroundImages() {
      return this.$helpers.isValidJsonString(
        this.form.zop_studio_available_background_images
      )
        ? JSON.parse(this.form.zop_studio_available_background_images)
        : [];
    },
  },
  async mounted() {
    this.submit = false;
    await await this.getModuleConfiguration("zop_studio");
    this.submit = true;
  },
};
</script>
<style lang="scss">
img.img-thumbnail {
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4); /* shadow with opacity */
  border-radius: 5px;
}

.ai-bg-hover:hover {
  opacity: 0.7;
}

.ai-bg-container {
  position: relative;
  display: inline-block;
}

.ai-bg-container img {
  display: block;
  width: 100%;
}

.ai-bg-container.active::after {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(rgba(35, 189, 112, 0.2), rgba(35, 189, 112, 0.6));
  border-radius: 5px;
}
</style>
    