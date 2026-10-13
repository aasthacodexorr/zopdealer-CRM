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
                            Review settings
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
                              id="setting-account-review-tab"
                              data-toggle="pill"
                              href="#setting-account-review"
                              aria-expanded="true"
                            >
                              Website Review Settings
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                              class="nav-link"
                              id="setting-account-template-tab"
                              data-toggle="pill"
                              href="#setting-account-googleconnect"
                              aria-expanded="true"
                            >
                              Google Reviews Settings
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                              class="nav-link"
                              id="setting-account-template-tab"
                              data-toggle="pill"
                              href="#setting-account-facebookconnect"
                              aria-expanded="true"
                            >
                              Facebook Reviews Settings
                            </a>
                          </li>                          
                          <li class="nav-item m-0">
                            <a
                              class="nav-link"
                              id="setting-account-review-template-tab"
                              data-toggle="pill"
                              href="#setting-account-review-template"
                              aria-expanded="true"
                            >
                              Review Channel Template
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
                    <settings-account-google-connect></settings-account-google-connect>
                    <settings-account-facebook-connect></settings-account-facebook-connect>
                    <settings-account-review></settings-account-review>
                    <settings-account-review-template></settings-account-review-template>                    
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
import SettingsAccountGoogleConnect from "../Settings/SettingsAccountGoogleConnect.vue";
import SettingsAccountFacebookConnect from "../Settings/SettingsAccountFacebookConnect.vue";
import SettingsAccountReview from "../Settings/SettingsAccountReview.vue";
import SettingsAccountReviewTemplate from "../Settings/SettingsAccountReviewTemplate.vue";

export default {
  name: "ReviewSettings",
  components: {
    SettingsAccountGoogleConnect,
    SettingsAccountFacebookConnect,
    SettingsAccountReview,
    SettingsAccountReviewTemplate
  },
};
</script>
