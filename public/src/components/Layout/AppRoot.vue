<template>
  <div class="page-loader" v-if="!application">
    <div class="lds-grid">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
  </div>
  <div v-if="application">
    <!-- BEGIN: Header-->
    <div class="header-navbar-shadow"></div>
    <main-header :application="application" :app_metadata="app_metadata" :user_metadata="user_metadata"></main-header>
    <!-- END: Header-->
    <side-bar :application="application" :app_metadata="app_metadata" :user_metadata="user_metadata"></side-bar>

    <router-view :application="application" :app_metadata="app_metadata" :user_metadata="user_metadata" :key="$route.fullPath"></router-view>

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <main-footer></main-footer>
  </div>
</template>

<script>
import MainFooter from "./Footer.vue";
import MainHeader from "./Header.vue";
import SideBar from "./Menu.vue";

export default {
  name: "AppRoot",
  components: {
    SideBar,
    MainHeader,
    MainFooter,
  },
  data() {
    return {
      global: window.global,
    };
  },
  computed: {
    application() {
      let application_name = "";
      let g = window.global || this.global;
      if (g && g.hostname) {
        let hostname = g.hostname.split(".");
        let org =
          g.app_metadata && g.app_metadata.organization
            ? g.app_metadata.organization
            : "";
        if (hostname.length > 2 && hostname[0] != "www") {
          application_name =
            hostname[0] == org || org == "zopdealer" ? "tenant" : "";
        } else {
          application_name = org == "zopdealer" ? "main" : "";
        }
        return application_name;
      }
      return "";
    },
    app_metadata() {
      let g = window.global || this.global;
      return g && g.app_metadata ? g.app_metadata : {};
    },
    user_metadata() {
      let g = window.global || this.global;
      return g && g.user_metadata ? g.user_metadata : {};
    },
  },
};
</script>
