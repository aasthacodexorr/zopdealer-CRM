<template>
  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <!-- form layout section start -->
    <div class="content-body">
      <div class="content-wrapper mt-3 pt-0 pr-0 pl-0">
        <div class="app-content-overlay"></div>
        <form class="grid-list-form">
          <section id="grid-list">
            <div class="sidebar-left border-right">
              <div class="sidebar pt-1">
                <div class="todo-sidebar d-flex">
                  <!-- todo app menu -->
                  <div class="todo-app-menu">
                    <div class="form-group text-center border-bottom mb-0">
                      <!-- new task button -->
                      <div class="row">
                        <div class="col-md-12 col-12">
                          <h3
                            class="content-header-title float-left pl-2 pb-2 mb-0"
                          >
                            Account Settings
                          </h3>
                        </div>
                      </div>
                    </div>
                    <!-- sidebar list start -->
                    <div class="sidebar-menu-list p-0">
                      <div class="list-grid">
                        <ul
                          class="nav flex-column text-center text-md-left team-setting-tab"
                        >
                          <li class="nav-item m-0">
                            <a
                              class="nav-link active"
                              id="team-pill-1"
                              data-toggle="pill"
                              href="#setting-account-email"
                              aria-expanded="true"
                            >
                              Email Settings
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                              class="nav-link"
                              id="team-pill-2"
                              data-toggle="pill"
                              href="#setting-account-tax"
                              aria-expanded="true"
                            >
                              Tax Settings
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                              class="nav-link"
                              id="setting-account-cost-tab"
                              data-toggle="pill"
                              href="#setting-account-cost"
                              aria-expanded="true"
                            >
                              Cost Settings
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                              class="nav-link"
                              id="setting-account-fee-tab"
                              data-toggle="pill"
                              href="#setting-account-fee"
                              aria-expanded="true"
                            >
                              Fee Settings
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                              class="nav-link"
                              id="setting-account-accessory-tab"
                              data-toggle="pill"
                              href="#setting-account-accessory"
                              aria-expanded="true"
                            >
                              Accessory Settings
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                              class="nav-link"
                              id="setting-account-warranty-tab"
                              data-toggle="pill"
                              href="#setting-account-warranty"
                              aria-expanded="true"
                            >
                              Warranty Settings
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                              class="nav-link"
                              id="setting-account-guarantee-tab"
                              data-toggle="pill"
                              href="#setting-account-guarantee"
                              aria-expanded="true"
                            >
                              Guarantee Settings
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                              class="nav-link"
                              id="setting-account-insurance-tab"
                              data-toggle="pill"
                              href="#setting-account-insurance"
                              aria-expanded="true"
                            >
                              Insurance Settings
                            </a>
                          </li>
                          <li
                            class="nav-item m-0"
                            v-if="app_metadata.organization == 'zopdealer'"
                          >
                            <a
                              class="nav-link"
                              id="setting-account-template-tab"
                              data-toggle="pill"
                              href="#setting-account-template"
                              aria-expanded="true"
                            >
                              Template Settings
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                              class="nav-link"
                              id="setting-account-template-tab"
                              data-toggle="pill"
                              href="#setting-account-facebook"
                              aria-expanded="true"
                            >
                              Facebook Settings
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                                class="nav-link"
                                id="setting-account-template-tab"
                                data-toggle="pill"
                                href="#setting-account-testdrive"
                                aria-expanded="true"
                            >
                              Test Drive Settings
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                              class="nav-link"
                              id="setting-account-template-tab"
                              data-toggle="pill"
                              href="#setting-account-craigslist"
                              aria-expanded="true"
                            >
                              Craigslist Settings
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                              class="nav-link"
                              id="setting-account-template-tab"
                              data-toggle="pill"
                              href="#setting-account-socialconnect"
                              aria-expanded="true"
                            >
                              Social Connect Settings
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                                class="nav-link"
                                id="setting-account-template-tab"
                                data-toggle="pill"
                                href="#setting-account-socialconnect-google"
                                aria-expanded="true"
                            >
                              Social Connect (Google) Settings
                            </a>
                          </li>
                          <li
                            class="nav-item m-0"
                            v-if="app_metadata.organization == 'zopdealer'"
                          >
                            <a
                              class="nav-link"
                              id="setting-account-template-tab"
                              data-toggle="pill"
                              href="#setting-account-fbinsights"
                              aria-expanded="true"
                            >
                              FB Insights Settings
                            </a>
                          </li>
                          <li
                            class="nav-item m-0"
                            v-if="app_metadata.organization == 'zopdealer'"
                          >
                            <a
                              class="nav-link"
                              id="setting-account-template-tab"
                              data-toggle="pill"
                              href="#setting-account-integration"
                              aria-expanded="true"
                            >
                              Integration Settings
                            </a>
                          </li>
                          <li
                            class="nav-item m-0"
                            v-if="app_metadata.organization == 'zopdealer'"
                          >
                            <a
                              class="nav-link"
                              id="setting-account-template-tab"
                              data-toggle="pill"
                              href="#setting-account-miscellaneous"
                              aria-expanded="true"
                            >
                              Miscellaneous Settings
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                              class="nav-link"
                              id="setting-account-logo-tab"
                              data-toggle="pill"
                              href="#setting-account-logo"
                              aria-expanded="true"
                            >
                              Logo Settings
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <!-- sidebar list end -->
                  </div>
                </div>
              </div>
            </div>
            <div class="content-right">
              <div class="todo-app-list">
                <div class="webapp-form" id="team-form">
                  <div class="tab-content">
                    <settings-account-email></settings-account-email>
                    <settings-account-tax></settings-account-tax>
                    <settings-account-cost></settings-account-cost>
                    <settings-account-fee></settings-account-fee>
                    <settings-account-accessory></settings-account-accessory>
                    <settings-account-warranty></settings-account-warranty>
                    <settings-account-guarantee></settings-account-guarantee>
                    <settings-account-insurance></settings-account-insurance>
                    <settings-account-template></settings-account-template>
                    <settings-account-facebook></settings-account-facebook>
                    <settings-account-test-drive></settings-account-test-drive>
                    <settings-account-craigslist></settings-account-craigslist>
                    <settings-account-social-connect></settings-account-social-connect>
                    <settings-account-social-connect-google></settings-account-social-connect-google>
                    <settings-account-fbinsights
                      v-if="app_metadata.organization == 'zopdealer'"
                    ></settings-account-fbinsights>
                    <settings-account-integration
                      v-if="app_metadata.organization == 'zopdealer'"
                    ></settings-account-integration>
                    <settings-account-miscellaneous
                      v-if="app_metadata.organization == 'zopdealer'"
                    ></settings-account-miscellaneous>
                    <settings-account-logo></settings-account-logo>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <!-- form layout section end -->
        </form>
      </div>
    </div>
  </div>
  <!-- END: Content-->
</template>
<script>
import SettingsAccountEmail from "./SettingsAccountEmail";
import SettingsAccountTax from "./SettingsAccountTax";
import SettingsAccountCost from "./SettingsAccountCost.vue";
import SettingsAccountTemplate from "./SettingsAccountTemplate";
import SettingsAccountFee from "./SettingsAccountFee.vue";
import SettingsAccountWarranty from "./SettingsAccountWarranty.vue";
import SettingsAccountGuarantee from "./SettingsAccountGuarantee.vue";
import SettingsAccountFacebook from "./SettingsAccountFacebook";
import SettingsAccountTestDrive from "./SettingsAccountTestDrive.vue";
import SettingsAccountMiscellaneous from "./SettingsAccountMiscellaneous";
import SettingsAccountIntegration from "./SettingsAccountIntegration";
import SettingsAccountCraigslist from "./SettingsAccountCraigslist.vue";
import SettingsAccountAccessory from "./SettingsAccountAccessory.vue";
import SettingsAccountInsurance from "./SettingsAccountInsurance.vue";
import SettingsAccountSocialConnect from "./SettingsAccountSocialConnect.vue";
import SettingsAccountSocialConnectGoogle from "./SettingsAccountSocialConnectGoogle.vue";
import SettingsAccountFbinsights from "./SettingsAccountFbinsights.vue";
import SettingsAccountLogo from "./SettingsAccountLogo.vue";

export default {
  props: ["application", "app_metadata"],
  name: "SettingsAccount",
  components: {
    SettingsAccountEmail,
    SettingsAccountTax,
    SettingsAccountCost,
    SettingsAccountFee,
    SettingsAccountTemplate,
    SettingsAccountWarranty,
    SettingsAccountGuarantee,
    SettingsAccountFacebook,
    SettingsAccountTestDrive,
    SettingsAccountMiscellaneous,
    SettingsAccountIntegration,
    SettingsAccountCraigslist,
    SettingsAccountAccessory,
    SettingsAccountInsurance,
    SettingsAccountSocialConnect,
    SettingsAccountSocialConnectGoogle,
    SettingsAccountFbinsights,
    SettingsAccountLogo,
  },
  beforeDestroy() {
    Event.vue.$off("change_rpa_settings", null);
  },
};
</script>
