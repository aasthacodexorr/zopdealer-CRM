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
              id="pbs_settings"
              data-toggle="tab"
              href="#pbs_settings"
              aria-controls="PBS Settings"
              role="tab"
              aria-selected="true"
            >
              <span class="">PBS Settings</span>
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
              id="pbs_settings-tab"
              aria-labelledby="pbs_settings"
              role="tabpanel"
            >
              <!-- form layout section start -->
              <section id="pbs_settings-section">
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
                                    <div class="row">
                                      <div class="col-md-4">
                                        <label>Serial Number</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <input
                                          name="business_website"
                                          class="form-control"
                                          v-model="form.pbs_serial_number"
                                        />
                                      </div>
                                    </div>

                                    <div class="row">
                                      <div class="col-md-4">
                                        <label>Available Access</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <v-select
                                          v-model="form.pbs_available_access"
                                          append-to-body
                                          placeholder="Available Access"
                                          taggable
                                          pushTags
                                          clearable
                                          multiple
                                          :options="['VehicleGet']"
                                        ></v-select>
                                      </div>
                                    </div>
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
import vSelect from "vue-select";
import ConfigurationMixin from "../../mixins/configuration";
export default {
  name: "PBSSetting",
  props: ["application", "app_metadata"],
  data() {
    return {
      form: {
        pbs_serial_number: "",
        pbs_available_access: "",
      },
      errors: [],
    };
  },
  mixins: [ConfigurationMixin],
  components: {
    "v-select": vSelect,
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
  async mounted() {
    this.submit = false;
    await await this.getModuleConfiguration();
    this.submit = true;
  },
};
</script>
    