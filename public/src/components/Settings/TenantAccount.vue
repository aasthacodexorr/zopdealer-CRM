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
              id="tenant_account_settings"
              data-toggle="tab"
              href="#tenant_account_settings"
              aria-controls="Tenant Account Settings"
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
              id="tenant_account_settings-tab"
              aria-labelledby="tenant_account_settings"
              role="tabpanel"
            >
              <!-- form layout section start -->
              <section id="tenant_account_settings-section">
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
                                        <label>Is Account Active</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <div
                                          class="custom-control custom-switch"
                                        >
                                          <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            v-model="form.is_account_active"
                                            :true-value="'1'"
                                            :false-value="'0'"
                                            name="Is Account Active"
                                            id="is_account_active_switch"
                                          />
                                          <label
                                            class="custom-control-label"
                                            for="is_account_active_switch"
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
import ConfigurationMixin from "../../mixins/configuration";
export default {
  name: "TenantAccount",
  props: ["application", "app_metadata"],
  data() {
    return {
      form: {
        is_account_active: "",
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
  },
  async mounted() {
    this.submit = false;
    await await this.getModuleConfiguration();
    this.submit = true;
  },
};
</script>
    