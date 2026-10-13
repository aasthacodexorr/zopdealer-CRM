<template>
  <!-- BEGIN: Content-->
  <div class="app-content content" v-if="isAuthorized">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="col-12">
          <div class="card-group">
            <!-- Tenant Settings Card : START -->
            <div style="cursor: pointer" class="col-xl-4 col-md-4 col-sm-12">
              <div class="card border shadow-none">
                <div class="card-header text-center p-50 border-bottom">
                  <h3 class="mb-0" style="font-weight: 600">
                    Account Settings
                  </h3>
                </div>
                <div class="card-body pt-50">
                  <p>This section includes ...</p>
                  <ul class="list-style-icons">
                    <router-link
                      tag="li"
                      :to="{ name: 'tenant-account-settings' }"
                      class="p-25"
                      v-show="isSuperAdmin"
                      exact
                    >
                      <i
                        style="margin-top: -2px"
                        class="bx bx-chevron-right text-primary bx-sm"
                      ></i>
                      Tenant Account
                    </router-link>

                    <router-link
                      tag="li"
                      :to="{ name: 'tenant-account-website-settings' }"
                      class="p-25"
                      v-show="isSuperAdmin"
                      exact
                    >
                      <i
                        style="margin-top: -2px"
                        class="bx bx-chevron-right text-primary bx-sm"
                      ></i>
                      Website Settings
                    </router-link>

                    <router-link
                      tag="li"
                      :to="{ name: 'tenant-account-phaeton-settings' }"
                      class="p-25"
                      v-show="isAuthorized"
                      exact
                    >
                      <i
                        style="margin-top: -2px"
                        class="bx bx-chevron-right text-primary bx-sm"
                      ></i>
                      Phaeton Website Settings
                    </router-link>

                    <router-link
                      tag="li"
                      :to="{ name: 'tenant-account-ai-studio-settings' }"
                      class="p-25"
                      v-show="isSuperAdmin"
                      exact
                    >
                      <i
                        style="margin-top: -2px"
                        class="bx bx-chevron-right text-primary bx-sm"
                      ></i>
                      AI Studio Settings
                    </router-link>

                    <router-link
                      tag="li"
                      :to="{ name: 'tenant-account-extension-settings' }"
                      class="p-25"
                      v-show="isAuthorized"
                      exact
                    >
                      <i
                        style="margin-top: -2px"
                        class="bx bx-chevron-right text-primary bx-sm"
                      ></i>
                      Chrome Extension Settings
                    </router-link>

                    <br />
                  </ul>
                </div>
              </div>
            </div>
            <!-- Tenant Settings Card : START -->

            <!-- Templates Card : START -->
            <div style="cursor: pointer" class="col-xl-4 col-md-4 col-sm-12">
              <div class="card border shadow-none">
                <div class="card-header text-center p-50 border-bottom">
                  <h3 class="mb-0" style="font-weight: 600">Templates</h3>
                </div>
                <div class="card-body pt-50">
                  <p>This section includes ...</p>
                  <ul class="list-style-icons">
                    <router-link
                      tag="li"
                      :to="{ name: 'schedule-template-list' }"
                      class="p-25"
                      exact
                    >
                      <i
                        style="margin-top: -2px"
                        class="bx bx-chevron-right text-primary bx-sm"
                      ></i>
                      Schedule Templates
                    </router-link>

                    <router-link
                      tag="li"
                      :to="{ name: 'workflow-template-list' }"
                      class="p-25"
                      exact
                    >
                      <i
                        style="margin-top: -2px"
                        class="bx bx-chevron-right text-primary bx-sm"
                      ></i>
                      Workflow Templates
                    </router-link>

                    <router-link
                      tag="li"
                      :to="{ name: 'deal-templates' }"
                      class="p-25"
                      exact
                    >
                      <i
                        style="margin-top: -2px"
                        class="bx bx-chevron-right text-primary bx-sm"
                      ></i>
                      Deal Templates
                    </router-link>

                    <router-link
                      tag="li"
                      :to="{ name: 'lead-template-list' }"
                      class="p-25"
                      v-show="isSuperAdmin"
                      exact
                    >
                      <i
                        style="margin-top: -2px"
                        class="bx bx-chevron-right text-primary bx-sm"
                      ></i>
                      Lead Templates
                    </router-link>

                    <router-link
                      tag="li"
                      :to="{ name: 'voucher-template-list' }"
                      class="p-25"
                      exact
                    >
                      <i
                        style="margin-top: -2px"
                        class="bx bx-chevron-right text-primary bx-sm"
                      ></i>
                      Voucher Templates
                    </router-link>

                    <router-link
                      tag="li"
                      :to="{ name: 'dvi-template-list' }"
                      class="p-25"
                      exact
                    >
                      <i
                        style="margin-top: -2px"
                        class="bx bx-chevron-right text-primary bx-sm"
                      ></i>
                      DVI Templates
                    </router-link>

                    <router-link
                      tag="li"
                      :to="{ name: 'dvi-template-item-list' }"
                      class="p-25"
                      exact
                    >
                      <i
                        style="margin-top: -2px"
                        class="bx bx-chevron-right text-primary bx-sm"
                      ></i>
                      DVI Item Templates
                    </router-link>

                    <router-link
                      tag="li"
                      :to="{ name: 'tenant-account-deal-jacket-template' }"
                      class="p-25"
                      exact
                    >
                      <i
                        style="margin-top: -2px"
                        class="bx bx-chevron-right text-primary bx-sm"
                      ></i>
                      Deal Jacket Organizer Template
                    </router-link>
                  </ul>
                </div>
              </div>
            </div>
            <!-- Templates Card : START -->

            <!-- Digital Quote Card : START -->
            <div style="cursor: pointer" class="col-xl-4 col-md-4 col-sm-12">
              <div class="card border shadow-none">
                <div class="card-header text-center p-50 border-bottom">
                  <h3 class="mb-0" style="font-weight: 600">e-Quote</h3>
                </div>
                <div class="card-body pt-50">
                  <p>This section includes ...</p>
                  <ul class="list-style-icons">
                    <router-link
                      tag="li"
                      :to="{ name: 'digital-retailing-settings-finance' }"
                      class="p-25"
                      exact
                    >
                      <i
                        style="margin-top: -2px"
                        class="bx bx-chevron-right text-primary bx-sm"
                      ></i>
                      Default Finance Terms
                    </router-link>

                    <router-link
                      tag="li"
                      :to="{ name: 'digital-retailing-settings-configuration' }"
                      class="p-25"
                      exact
                    >
                      <i
                        style="margin-top: -2px"
                        class="bx bx-chevron-right text-primary bx-sm"
                      ></i>
                      e-Quote Settings
                    </router-link>
                    <br /><br /><br /><br />
                  </ul>
                </div>
              </div>
            </div>
            <!-- Digital Quote Card : END -->

            <!-- ThirdParty Settings Card : START -->
            <div style="cursor: pointer" class="col-xl-4 col-md-4 col-sm-12">
              <div class="card border shadow-none">
                <div class="card-header text-center p-50 border-bottom">
                  <h3 class="mb-0" style="font-weight: 600">
                    Third Party Settings
                  </h3>
                </div>
                <div class="card-body pt-50">
                  <p>This section includes ...</p>
                  <ul class="list-style-icons">
                    <router-link
                      tag="li"
                      :to="{ name: 'dealer-track-settings-configuration' }"
                      class="p-25"
                      exact
                    >
                      <i
                        style="margin-top: -2px"
                        class="bx bx-chevron-right text-primary bx-sm"
                      ></i>
                      DealerTrack Configuration Settings
                    </router-link>

                    <router-link
                      tag="li"
                      :to="{ name: 'tenant-account-pbs-settings' }"
                      class="p-25"
                      exact
                    >
                      <i
                        style="margin-top: -2px"
                        class="bx bx-chevron-right text-primary bx-sm"
                      ></i>
                      PBS Configuration Settings
                    </router-link>
                  </ul>
                </div>
              </div>
            </div>
            <!-- DealerTrack Settings Card : END -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END: Content-->
</template>
<script>
export default {
  props: ["application", "app_metadata"],
  name: "AccountLanding",
  computed: {
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    isSuperAdmin() {
      return this.$helpers.isAuthorized(this.app_metadata.role, [
        "Super Owner",
      ]);
    },
    tenantApp() {
      return this.application == "tenant";
    },
    mainApp() {
      return this.application == "main";
    },
  },
};
</script>
