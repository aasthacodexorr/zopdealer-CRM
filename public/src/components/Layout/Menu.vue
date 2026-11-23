<template>
  <!-- BEGIN: Main Menu-->
  <div
    class="main-menu menu-fixed menu-light menu-accordion menu-shadow zd-sidebar"
    data-scroll-to-active="true"
    id="main-menu"
  >
    <div class="navbar-header">
      <ul class="nav navbar-nav flex-row">
        <li class="nav-item mr-auto">
          <a class="navbar-brand">
            <img
              src="/dist/assets/images/z-zop-white.png"
              class="img-responsive"
              style="width: 125px; position: relative; left: -5px"
              alt="ZOP DEALER"
              srcset
            />
          </a>
        </li>
        <li class="nav-item nav-toggle">
          <a
            class="nav-link modern-nav-toggle pr-0 active"
            id="main-menu-toggle-btn"
            data-toggle="collapse"
          >
            <i
              class="bx bx-radio-circle-marked bx-md text-white"
              title="Pin Left Menu"
            ></i>
          </a>
        </li>
      </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
      <ul
        class="navigation mt-1"
        id="main-menu-navigation"
        data-menu="menu-navigation"
        data-icon-style="lines"
      >
        <router-link tag="li" to="/" class="nav-item" exact>
          <a>
            <i class="bx bx-show" data-icon="desktop"></i>
            <span class="menu-title" data-i18n="Dashboard">At A Glance</span>
          </a>
        </router-link>

        <router-link tag="li" :to="{ name: 'calendar' }" class="nav-item" exact>
          <a>
            <i class="bx bx-calendar-alt" data-icon="calendar"></i>
            <span class="menu-title" data-i18n="Calendar">Calendar</span>
          </a>
        </router-link>

        <!-- route for clients : BEGIN -->
        <li v-if="mainApp" class="navigation-header">
          <span>Clients</span>
        </li>
        <router-link
          v-if="mainApp"
          tag="li"
          :to="{ name: 'client-add' }"
          class="nav-item"
          exact
        >
          <a>
            <i class="menu-livicon" data-icon="user"></i>
            <span class="menu-title" data-i18n="AddClient">Add Client</span>
          </a>
        </router-link>
        <router-link
          v-if="mainApp"
          tag="li"
          :to="{ name: 'clients' }"
          class="nav-item"
          exact
        >
          <a>
            <i class="menu-livicon" data-icon="users"></i>
            <span class="menu-title" data-i18n="ClientList">List Clients</span>
          </a>
        </router-link>
        <!-- route for add client s: END -->
        <!-- route for lead templates : START -->
        <li v-if="mainApp" class="navigation-header">
          <span>Lead Templates</span>
        </li>
        <router-link
          v-if="mainApp"
          tag="li"
          :to="{ name: 'lead-template-list' }"
          class="nav-item"
          exact
        >
          <a>
            <i class="menu-livicon" data-icon="list"></i>
            <span class="menu-title" data-i18n="LeadTemplateList"
              >Lead Templates List</span
            >
          </a>
        </router-link>
        <!-- route for lead templates : END -->

        <!-- route for syndication global : START -->
        <li v-if="mainApp" class="navigation-header">
          <span>Syndication Companies</span>
        </li>
        <router-link
          v-if="mainApp"
          tag="li"
          :to="{ name: 'syndication-company-list' }"
          class="nav-item"
          exact
        >
          <a>
            <i class="menu-livicon" data-icon="globe"></i>
            <span class="menu-title" data-i18n="LeadTemplateList"
              >Manage Source</span
            >
          </a>
        </router-link>
        <router-link
          v-if="mainApp"
          tag="li"
          :to="{ name: 'syndication-list' }"
          class="nav-item"
          exact
        >
          <a>
            <i class="menu-livicon" data-icon="list"></i>
            <span class="menu-title" data-i18n="SyndicationAction"
              >Syndication List</span
            >
          </a>
        </router-link>
        <router-link
          v-if="mainApp"
          tag="li"
          :to="{ name: 'syndication-action' }"
          class="nav-item"
          exact
        >
          <a>
            <i class="menu-livicon" data-icon="gear"></i>
            <span class="menu-title" data-i18n="SyndicationAction"
              >Syndication Actions</span
            >
          </a>
        </router-link>
        <!-- route for syndication global : END -->

        <!-- route for syndication global : START -->
        <li v-if="mainApp" class="navigation-header">
          <span>Master Actions</span>
        </li>

        <router-link
          v-if="mainApp"
          tag="li"
          :to="{ name: 'super-action' }"
          class="nav-item"
          exact
        >
          <a>
            <i class="menu-livicon" data-icon="gear"></i>
            <span class="menu-title" data-i18n="MasterActions"
              >Master Actions</span
            >
          </a>
        </router-link>

        <li class="navigation-header">
          <span>Apps</span>
        </li>
        <!-- Inventory : START -->
        <li v-if="tenantApp" class="nav-item" style="cursor: pointer;">
          <a
            @click="
              selected = null;
              openInventoryPage();
            "
          >
            <i
              class="bx bxs-car bx-tada bx-flip-horizontal"
              data-icon="inventory"
            ></i>
            <span class="menu-title" data-i18n="Inventory">Inventory</span>
          </a>
        </li>
        <!-- Inventory : END -->
        <!-- Lead : START -->
        <router-link
          v-if="tenantApp"
          tag="li"
          :to="{ name: 'lead-responses' }"
          class="nav-item"
          exact
          style="cursor: pointer;"
        >
          <a @click="selected = null">
            <i
              class="bx bx-target-lock bx-tada bx-flip-horizontal"
              data-icon="leads"
            ></i>
            <span class="menu-title" data-i18n="Leads">Leads</span>
          </a>
        </router-link>
        <!-- Lead : END -->

        <!-- Customer Module : START-->
        <router-link
          v-if="tenantApp"
          tag="li"
          :to="{ name: 'customer-list' }"
          class="nav-item"
          exact
        >
          <a @click="selected = null">
            <i
              class="bx bxs-group bx-tada bx-flip-horizontal"
              data-icon="Customers"
            ></i>
            <span class="menu-title" data-i18n="Customers">Customers</span>
          </a>
        </router-link>
        <!-- Customer Module : END -->

        <!-- Deal Module : START-->
        <router-link
          v-if="tenantApp"
          tag="li"
          :to="{ name: 'deals' }"
          class="nav-item"
          exact
        >
          <a @click="selected = null">
            <i
              class="bx bxs-dollar-circle bx-tada bx-flip-horizontal"
              data-icon="Deals"
            ></i>
            <span class="menu-title" data-i18n="Deals">Deals</span>
          </a>
        </router-link>
        <!-- Deal Module : END -->

        <!-- Vendor Module : START-->
        <router-link
          v-if="tenantApp && isAuthorized"
          tag="li"
          :to="{ name: 'vendor-list' }"
          class="nav-item"
          exact
        >
          <a @click="selected = null">
            <i
              class="bx bxs-buildings bx-tada bx-flip-horizontal"
              data-icon="Vendors"
            ></i>
            <span class="menu-title" data-i18n="Vendors">Vendors</span>
          </a>
        </router-link>
        <!-- Vendor Module : END -->

        <!-- Test Drive Module : START-->
        <router-link
          v-if="tenantApp"
          tag="li"
          :to="{ name: 'test-drive-list' }"
          class="nav-item"
          exact
        >
          <a @click="selected = null">
            <i
              class="bx bxs-car bx-tada bx-flip-horizontal"
              data-icon="TestDrives"
            ></i>
            <span class="menu-title" data-i18n="TestDrives">Test Drives</span>
          </a>
        </router-link>
        <!-- Test Drive Module : END -->

        <!-- DVI Module : START-->
        <router-link
          v-if="tenantApp"
          tag="li"
          :to="{ name: 'dvi' }"
          class="nav-item"
          exact
        >
          <a @click="selected = null">
            <i
              class="bx bx-list-check bx-tada bx-flip-horizontal"
              data-icon="DVI"
            ></i>
            <span class="menu-title" data-i18n="DVI">DVI</span>
          </a>
        </router-link>
        <!-- DVI Module : END -->

        <!-- Facebook Marketplace Module : START-->
        <router-link
          v-if="tenantApp && isAuthorized"
          tag="li"
          :to="{ name: 'facebook-marketplace-list' }"
          :is="isFBMPAvailable === -1 ? 'li' : 'router-link'"
          :style="isFBMPAvailable === -1 ? 'cursor: not-allowed' : ''"
          class="nav-item"
          :disabled="isFBMPAvailable === -1"
          exact
        >
          <a @click="selected = null" :disabled="isFBMPAvailable === -1">
            <i
              class="bx bxl-facebook-square bx-tada bx-flip-horizontal"
              data-icon="Facebook Marketplace"
            ></i>
            <span
              class="menu-title"
              style="width: 100%"
              data-i18n="Facebook Marketplace"
              >FBMP
              <i
                v-if="isFBMPAvailable === -1"
                class="bx bxs-lock float-right"
              ></i
            ></span>
          </a>
        </router-link>
        <!-- Facebook Marketplace Module : END -->

        <!-- Craigslist Module : START-->
        <router-link
          v-if="tenantApp && isAuthorized"
          tag="li"
          :is="isCraigslistAvailable === -1 ? 'li' : 'router-link'"
          :style="isCraigslistAvailable === -1 ? 'cursor: not-allowed' : ''"
          :to="{ name: 'craigslist-list' }"
          class="nav-item"
          :disabled="isCraigslistAvailable === -1"
          exact
        >
          <a @click="selected = null" :disabled="isCraigslistAvailable === -1">
            <i
              class="bx bx-list-ul bx-tada bx-flip-horizontal"
              data-icon="Craigslist"
            ></i>
            <span class="menu-title" style="width: 100%" data-i18n="Craigslist"
              >Craigslist
              <i
                v-if="isCraigslistAvailable === -1"
                class="bx bxs-lock float-right"
              ></i
            ></span>
          </a>
        </router-link>
        <!-- Craigslist Module : END -->

        <!-- SocialConnect Settings : START -->
        <router-link
          v-if="tenantApp && isAuthorized"
          tag="li"
          :is="isSocialConnectAvailable === -1 ? 'li' : 'router-link'"
          :style="isSocialConnectAvailable === -1 ? 'cursor: not-allowed' : ''"
          :to="{ name: 'manage-socialconnect' }"
          class="nav-item"
          :disabled="isSocialConnectAvailable === -1"
          exact
        >
          <a
            @click="selected = null"
            :disabled="isSocialConnectAvailable === -1"
          >
            <i
              class="bx bx-share-alt bx-tada bx-flip-horizontal"
              data-icon="SocialConnect"
            ></i>
            <span
              class="menu-title"
              style="width: 100%"
              data-i18n="SocialConnect"
              >Social Connect
              <i
                v-if="isSocialConnectAvailable === -1"
                class="bx bxs-lock float-right"
              ></i
            ></span>
          </a>
        </router-link>
        <!-- SocialConnect Settings : END -->

        <!-- Report Module : START-->
        <router-link
          v-if="tenantApp && isAuthorized"
          tag="li"
          :to="{ name: 'reports' }"
          class="nav-item"
          exact
        >
          <a @click="selected = null">
            <i
              class="bx bxs-report bx-tada bx-flip-horizontal"
              data-icon="Reports"
            ></i>
            <span class="menu-title" data-i18n="Reports">Reports</span>
          </a>
        </router-link>
        <!-- Report Module : END -->

        <!-- Payment Module : START-->
        <router-link
          v-if="tenantApp && isAuthorized"
          :is="!isPaymentModuleAvailable ? 'li' : 'router-link'"
          :style="!isPaymentModuleAvailable ? 'cursor: not-allowed' : ''"
          :disabled="!isPaymentModuleAvailable"
          tag="li"
          :to="{ name: 'payment-list' }"
          class="nav-item"
          exact
        >
          <a @click="selected = null">
            <i
              class="bx bx-flip-horizontal bx-tada bx-credit-card"
              data-icon="Payments"
            ></i>
            <span class="menu-title" style="width: 100%" data-i18n="Payments"
              >Payments
              <i
                v-if="!isPaymentModuleAvailable"
                class="bx bxs-lock float-right"
              ></i>
            </span>
          </a>
        </router-link>
        <!-- Payment Module : END -->

        <!-- Syndication History : START -->
        <router-link
          v-if="tenantApp"
          tag="li"
          :to="{ name: 'syndication-history' }"
          class="nav-item"
          exact
        >
          <a @click="selected = null">
            <i
              class="bx bx-transfer bx-tada bx-flip-horizontal"
              data-icon="Manage Syndication"
            ></i>
            <span class="menu-title" data-i18n="Manage Syndication"
              >Manage Syndication</span
            >
          </a>
        </router-link>
        <!-- Syndication History : END -->

        <li class="navigation-header">
          <span>Reviews</span>
        </li>

        <!-- Reviews Module : START -->
        <router-link
          v-if="tenantApp && isAuthorized"
          tag="li"
          :is="!isReviewModuleEnabled ? 'li' : 'router-link'"
          :style="!isReviewModuleEnabled ? 'cursor: not-allowed' : ''"
          :disabled="!isReviewModuleEnabled"
          :to="{ name: 'review-list' }"
          class="nav-item"
          exact
        >
          <a @click="selected = null">
            <i
              class="bx bxs-star-half bx-tada bx-flip-horizontal"
              data-icon="google"
            ></i>
            <span class="menu-title" style="width: 100%" data-i18n="Reviews"
              >Reviews
              <i
                v-if="!isReviewModuleEnabled"
                class="bx bxs-lock float-right"
              ></i
            ></span>
          </a>
        </router-link>
        <!-- Reviews Module : END -->

        <!-- Feedback Module : START -->
        <router-link
          v-if="tenantApp && isAuthorized"
          tag="li"
          :is="!isReviewModuleEnabled ? 'li' : 'router-link'"
          :style="!isReviewModuleEnabled ? 'cursor: not-allowed' : ''"
          :disabled="!isReviewModuleEnabled"
          :to="{ name: 'review-feedback-list' }"
          class="nav-item"
          exact
        >
          <a @click="selected = null">
            <i
              class="bx bx-poll bx-tada bx-flip-horizontal"
              data-icon="google"
            ></i>
            <span class="menu-title" style="width: 100%" data-i18n="Feedbacks"
              >Feedbacks
              <i
                v-if="!isReviewModuleEnabled"
                class="bx bxs-lock float-right"
              ></i
            ></span>
          </a>
        </router-link>
        <!-- Feedback Module : END -->

        <li class="navigation-header">
          <span>Services</span>
        </li>

        <!-- Product Module : START-->
        <router-link
          v-if="tenantApp"
          :is="!isServiceModuleEnabled ? 'li' : 'router-link'"
          :style="!isServiceModuleEnabled ? 'cursor: not-allowed' : ''"
          :disabled="!isServiceModuleEnabled"
          tag="li"
          :to="{ name: 'product-list' }"
          class="nav-item"
          exact
        >
          <a @click="selected = null">
            <i
              class="bx bxs-package bx-tada bx-flip-horizontal"
              data-icon="Products"
            ></i>
            <span class="menu-title" style="width: 100%" data-i18n="Products"
              >Products<i
                v-if="!isServiceModuleEnabled"
                class="bx bxs-lock float-right"
              ></i
            ></span>
          </a>
        </router-link>
        <!-- Product Module : END -->

        <!-- Work Order Module : START-->
        <router-link
          v-if="tenantApp"
          :is="!isServiceModuleEnabled ? 'li' : 'router-link'"
          :style="!isServiceModuleEnabled ? 'cursor: not-allowed' : ''"
          :disabled="!isServiceModuleEnabled"
          tag="li"
          :to="{ name: 'work-order-list' }"
          class="nav-item"
          exact
        >
          <a @click="selected = null">
            <i
              class="bx bxs-cuboid bx-tada bx-flip-horizontal"
              data-icon="Work Orders"
            ></i>
            <span class="menu-title" style="width: 100%" data-i18n="Work Orders"
              >Work Orders
              <i
                v-if="!isServiceModuleEnabled"
                class="bx bxs-lock float-right"
              ></i>
            </span>
          </a>
        </router-link>
        <!-- Work Order Module : END -->

        <!-- Tenant Settings -->
        <li v-if="tenantApp" class="navigation-header">
          <span>Settings</span>
        </li>

        <router-link
          v-if="tenantApp && isAuthorized"
          tag="li"
          :to="{ name: 'settings-account' }"
          class="nav-item"
          exact
        >
          <a @click="selected = null">
            <i
              class="bx bx-globe bx-tada bx-flip-horizontal"
              data-icon="accounts-setting"
            ></i>
            <span class="menu-title" data-i18n="AccountSetting"
              >Account Settings</span
            >
          </a>
        </router-link>

        <router-link
          v-if="tenantApp && app_metadata.organization != 'zopdealer'"
          tag="li"
          :to="{ name: 'settings-user-account' }"
          class="nav-item"
          exact
        >
          <a @click="selected = null">
            <i
              class="bx bxs-user-detail bx-tada bx-flip-horizontal"
              data-icon="user-setting"
            ></i>
            <span class="menu-title" data-i18n="UserAccountSetting"
              >User Settings</span
            >
          </a>
        </router-link>

        <router-link
          v-if="tenantApp && app_metadata.organization != 'zopdealer'"
          tag="li"
          :to="{ name: 'settings-user-integration' }"
          class="nav-item"
          exact
        >
          <a @click="selected = null">
            <i
              class="bx bxs-plug bx-tada bx-flip-horizontal"
              data-icon="user-setting"
            ></i>
            <span class="menu-title" data-i18n="UserAccountSetting"
              >User Integrations</span
            >
          </a>
        </router-link>

        <!-- Tenant Settings : END -->

        <!-- Super Settings : START -->
        <li
          class="nav-item"
          :class="{ open: selected == 'SuperSettings' }"
          v-if="tenantApp && app_metadata.organization == 'zopdealer'"
        >
          <a
            href="#"
            @click.prevent="
              selected =
                selected == 'SuperSettings' ? undefined : 'SuperSettings'
            "
          >
            <i
              class="bx bxs-terminal bx-tada bx-flip-horizontal"
              data-icon="grid"
            ></i>
            <span class="menu-title" data-i18n="Content">Super Settings</span>
          </a>
          <transition name="fade">
            <ul v-if="tenantApp && selected == 'SuperSettings'">
              <router-link
                tag="li"
                :to="{ name: 'manage-syndication-companies' }"
                class="nav-item"
                exact
              >
                <a>
                  <i class="bx bx-right-arrow-alt"></i>
                  <span
                    class="menu-item"
                    data-i18n="ManageSyndicationCompanyForClient"
                    >Super Syndication</span
                  >
                </a>
              </router-link>

              <router-link
                tag="li"
                :to="{ name: 'account-action' }"
                class="nav-item"
                exact
              >
                <a>
                  <i class="bx bx-right-arrow-alt"></i>
                  <span class="menu-item" data-i18n="AccountSetting"
                    >Account Actions</span
                  >
                </a>
              </router-link>

              <router-link
                tag="li"
                :to="{ name: 'settings-account-region' }"
                class="nav-item"
                exact
              >
                <a>
                  <i class="bx bx-right-arrow-alt"></i>
                  <span class="menu-item" data-i18n="Regional Seed"
                    >Regional Seeds</span
                  >
                </a>
              </router-link>
            </ul>
          </transition>
        </li>
        <!-- Super Settings : END -->

        <!-- Import Settings : START -->
        <li
          class="nav-item"
          :class="{ open: selected == 'ImportSettings' }"
          v-if="tenantApp && app_metadata.organization == 'zopdealer'"
        >
          <a
            href="#"
            @click.prevent="
              selected =
                selected == 'ImportSettings' ? undefined : 'ImportSettings'
            "
          >
            <i
              class="bx bxs-file bx-tada bx-flip-horizontal"
              data-icon="grid"
            ></i>
            <span class="menu-title" data-i18n="Content">Import Settings</span>
          </a>
          <transition name="fade">
            <ul v-if="tenantApp && selected == 'ImportSettings'">
              <router-link
                tag="li"
                :to="{ name: 'import-inventory' }"
                class="nav-item"
                exact
              >
                <a>
                  <i class="bx bx-right-arrow-alt"></i>
                  <span class="menu-item" data-i18n="ImportInventory"
                    >Import Inventory</span
                  >
                </a>
              </router-link>
            </ul>
          </transition>
        </li>
        <!-- Import Settings : END -->

        <!-- User Management : START -->
        <router-link
          v-if="tenantApp && isAuthorizedForUserManagement"
          tag="li"
          :to="{ name: 'user-management' }"
          class="nav-item"
          exact
        >
          <a @click="selected = null">
            <i
              class="bx bxs-group bx-tada bx-flip-horizontal"
              data-icon="accounts-setting"
            ></i>
            <span class="menu-title" data-i18n="User Management">Users</span>
          </a>
        </router-link>
        <!-- User Management : END -->

        <!-- Support (moved from the top bar; opens the modals defined in Header.vue) : START -->
        <li class="navigation-header">
          <span>Support</span>
        </li>
        <li class="nav-item zd-menu-action">
          <a
            href="#"
            data-toggle="modal"
            data-target="#helpCenter"
            @click.prevent
          >
            <i class="bx bx-support" data-icon="support"></i>
            <span class="menu-title" data-i18n="Customer Support"
              >Customer Support</span
            >
          </a>
        </li>
        <li class="nav-item zd-menu-action">
          <a
            href="#"
            data-toggle="modal"
            data-target="#referAndSave"
            @click.prevent
          >
            <i class="bx bxs-gift" data-icon="refer"></i>
            <span class="menu-title" data-i18n="Refer & Save"
              >Refer &amp; Save</span
            >
          </a>
        </li>
        <!-- Support : END -->
      </ul>
    </div>

    <!-- User Profile Footer (Fixed at bottom of sidebar) -->
    <div class="zd-sidebar-user-footer">
      <div class="zd-sidebar-user-info">
        <div class="zd-sidebar-user-avatar">
          <img v-if="userPicture" :src="userPicture" alt="User Avatar" />
          <span v-else class="zd-sidebar-user-initials">{{ userInitials }}</span>
        </div>
        <div class="zd-sidebar-user-details">
          <span class="zd-sidebar-user-name" :title="userName">{{ userName }}</span>
          <span class="zd-sidebar-user-role" :title="userRole">{{ userRole }}</span>
        </div>
      </div>

      <a class="zd-sidebar-logout" href="api/admin/logout">
        <i class="bx bx-log-out"></i>
        <span>Log out</span>
      </a>

    </div>
  </div>
  <!-- END: Main Menu-->
</template>

<script>
import { mapActions } from "vuex";

export default {
  name: "Menu",
  props: ["application", "app_metadata", "user_metadata"],
  data() {
    return {
      selected: undefined,
      active_syndication: [],
      global: {
        stripe_account: "",
        enable_service_module: "",
        enable_reviews: "",
      },
    };
  },
  computed: {
    isFBMPAvailable() {
      return this.active_syndication.findIndex((element) => {
        return element["slug"] === "facebook";
      });
    },
    isCraigslistAvailable() {
      return this.active_syndication.findIndex((element) => {
        return element["slug"] === "craigslist";
      });
    },
    isSocialConnectAvailable() {
      return this.active_syndication.findIndex((element) => {
        return element["slug"] === "socialconnect";
      });
    },
    isReviewModuleEnabled() {
      return this.global.enable_reviews == "1";
    },
    isPaymentModuleAvailable() {
      return true;
    },
    isServiceModuleEnabled() {
      return this.global.enable_service_module == "1";
    },
    tenantApp() {
      return this.application == "tenant";
    },
    mainApp() {
      return this.application == "main";
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    isAuthorizedForUserManagement() {
      return this.$helpers.isAuthorized(this.app_metadata.role, [
        "Super Owner",
        "Owner",
      ]);
    },
    isSuperAdmin() {
      if (this.app_metadata.role == "Super Owner") {
        return true;
      } else false;
    },
    userName() {
      if (this.user_metadata && this.user_metadata.name) {
        return this.user_metadata.name;
      }
      let g = window.global || this.global;
      if (g && g.user_metadata && g.user_metadata.name) {
        return g.user_metadata.name;
      }
      return "Daniel Carter";
    },
    userRole() {
      if (this.user_metadata && this.user_metadata.title) {
        return this.user_metadata.title;
      }
      if (this.app_metadata && this.app_metadata.role) {
        return this.app_metadata.role;
      }
      return "Sales Manager";
    },
    userPicture() {
      if (this.user_metadata && (this.user_metadata.picture || this.user_metadata.avatar || this.user_metadata.image)) {
        return this.user_metadata.picture || this.user_metadata.avatar || this.user_metadata.image;
      }
      let g = window.global || this.global;
      if (g && g.user_metadata && (g.user_metadata.picture || g.user_metadata.avatar)) {
        return g.user_metadata.picture || g.user_metadata.avatar;
      }
      return null;
    },
    userInitials() {
      const name = this.userName || "";
      const parts = name.trim().split(/\s+/).filter(Boolean);
      const letters = (parts[0] || "").charAt(0) + (parts[1] || "").charAt(0);
      return letters ? letters.toUpperCase() : "DC";
    },
  },
  methods: {
    ...mapActions("inventoryView", ["commitCurrentSyndicationViewStatus"]),
    openInventoryPage() {
      this.commitCurrentSyndicationViewStatus(false);
      this.$router.push({
        name: "inventory-list",
      });
    },
    activeSyndication() {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          "/api/business/configurations/active/syndication",
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (element["key"] == "syndication") {
              this.active_syndication = JSON.parse(element["value"]);
            }
          }
        }
      });
    },
    async getGlobalConfiguration() {
      let request_url =
        window.location.origin + "/api/business/configurations/global";

      await axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            this.global.hasOwnProperty(element.key)
              ? (this.global[element.key] = element.value)
              : null;
          }
        }
      });
    },
  },
  created() {
    this.activeSyndication();
    this.getGlobalConfiguration();
  },
  mounted() {
    $("#main-menu-toggle-btn").on("click", function(e) {
      e.preventDefault();
      $("#body").removeClass("menu-open");
      if ($("#main-menu-toggle-btn").hasClass("active")) {
        $("#main-menu-toggle-btn").removeClass("active");
        $("#body").addClass("menu-collapsed");
        $(".nav-item.open")
          .addClass("menu-collapsed-open")
          .removeClass("open");
      } else {
        $("#main-menu-toggle-btn").addClass("active");
        $("#body").removeClass("menu-collapsed");
        $(".nav-item.menu-collapsed-open")
          .addClass("open")
          .removeClass("menu-collapsed-open");
      }
    });

    $("#hidden-main-menu-button").on("click", function(e) {
      e.preventDefault();
      $("#body")
        .removeClass()
        .addClass(
          "vertical-layout 2-columns navbar-sticky footer-static pace-done vertical-overlay-menu menu-open",
        );
      //  $("#main-menu-toggle-btn").trigger("click");
    });

    $("#main-menu").hover(
      function() {
        if (!$("#main-menu-toggle-btn").hasClass("active")) {
          $("#body").removeClass("menu-collapsed");
          $(".nav-item.menu-collapsed-open")
            .addClass("open")
            .removeClass("menu-collapsed-open");
        }
      },
      function() {
        if (!$("#main-menu-toggle-btn").hasClass("active")) {
          $("#body").addClass("menu-collapsed");
          $(".nav-item.open")
            .addClass("menu-collapsed-open")
            .removeClass("open");
        }
      },
    );

    // Disable duplicate PerfectScrollbar on menu content so only 1 styled scrollbar is rendered
    if (window.$ && window.$.app && window.$.app.menu && window.$.app.menu.manualScroller) {
      window.$.app.menu.manualScroller.disable();
    }
  },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 1s ease-in-out;
}
.fade-enter-from,
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>

<style lang="scss">
/* ==========================================================================
   Sidebar — modern dark navigation. Scoped to #main-menu.zd-sidebar.
   ========================================================================== */
#main-menu.zd-sidebar {
  --sb-bg: #161b26;
  --sb-bg-2: #1c2230;
  --sb-text: #c3cad6;
  --sb-text-strong: #ffffff;
  --sb-muted: #6b7486;
  --sb-hover: rgba(255, 255, 255, 0.06);
  --sb-active-bg: rgba(90, 141, 238, 0.16);
  --sb-active: #8fb2ff;
  --sb-accent: #5a8dee;

  display: flex !important;
  flex-direction: column !important;
  height: 100vh !important;
  overflow: hidden !important;
  overflow-y: hidden !important;
  overflow-x: hidden !important;
  background: var(--sb-bg) !important;
  border-right: 1px solid rgba(255, 255, 255, 0.04);
  box-shadow: none;

  /* Brand row */
  /* Vertically centre the logo + pin toggle inside the brand row
     (the theme's margins pushed the logo past the bottom border). */
  .navbar-header {
    flex-shrink: 0;
    display: flex;
    align-items: center;
    height: 68px !important;
    padding: 0 1rem 0 1.25rem !important;
    background: var(--sb-bg);
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    > ul.nav {
      flex: 1 1 auto;
      display: flex;
      align-items: center;
      height: 100%;
      margin: 0;
      > li.nav-item {
        display: flex;
        align-items: center;
        height: 100%;
        margin: 0 !important;
      }
      > li.nav-toggle {
        margin-left: auto !important;
      }
    }
    .navbar-brand {
      display: flex;
      align-items: center;
      margin: 0 !important;
      padding: 0 !important;
      img {
        position: static !important;
        left: 0 !important;
        top: 0 !important;
        width: 112px !important;
        height: auto;
        display: block;
      }
    }
    .modern-nav-toggle {
      display: flex;
      align-items: center;
      padding: 0 !important;
      margin: 0 !important;
    }
  }
  .shadow-bottom {
    display: none !important;
  }
  .modern-nav-toggle i {
    color: var(--sb-muted) !important;
    transition: color 0.15s;
  }
  .modern-nav-toggle:hover i,
  .modern-nav-toggle.active i {
    color: var(--sb-text-strong) !important;
  }

  .main-menu-content {
    flex: 1 1 auto;
    height: auto !important;
    min-height: 0 !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    padding-bottom: 1rem;

    /* Hide PerfectScrollbar so there is never a duplicate scrollbar */
    .ps__rail-y,
    .ps__rail-x {
      display: none !important;
      opacity: 0 !important;
      visibility: hidden !important;
      pointer-events: none !important;
    }

    /* Single clean modern scrollbar */
    &::-webkit-scrollbar {
      width: 5px !important;
    }
    &::-webkit-scrollbar-track {
      background: transparent !important;
    }
    &::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.16) !important;
      border-radius: 5px !important;
    }
    &::-webkit-scrollbar-thumb:hover {
      background: rgba(255, 255, 255, 0.3) !important;
    }
  }
  .navigation {
    background: transparent !important;
    padding-top: 0.25rem;
  }

  /* Section headings */
  .navigation > .navigation-header {
    margin: 1.4rem 0 0.4rem 1.6rem !important;
    padding: 0 !important;
    background: transparent !important;
    span {
      color: var(--sb-muted) !important;
      font-size: 0.68rem !important;
      font-weight: 600 !important;
      letter-spacing: 0.09em !important;
      text-transform: uppercase;
    }
  }

  /* Items */
  .navigation > li.nav-item {
    margin: 1px 0.75rem !important;
    border-radius: 10px;
    background: transparent !important;
    > a {
      position: relative;
      display: flex !important;
      align-items: center;
      gap: 0.15rem;
      min-height: 40px;
      padding: 0.55rem 0.8rem !important;
      border-radius: 10px !important;
      color: var(--sb-text) !important;
      background: transparent !important;
      font-size: 0.92rem;
      font-weight: 500;
      line-height: 1.2;
      transition: background 0.15s, color 0.15s;
      > i:first-child {
        flex-shrink: 0;
        width: 22px;
        margin-right: 0.75rem !important;
        font-size: 1.25rem !important;
        line-height: 1;
        color: var(--sb-muted) !important;
        transition: color 0.15s;
        /* keep icons still; the theme's bx-tada animation is distracting in a list */
        animation: none !important;
        transform: none !important;
      }
      .menu-title {
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        flex: 1 1 auto;
        min-width: 0;
        color: inherit !important;
      }
      .bxs-lock {
        font-size: 0.95rem !important;
        color: var(--sb-muted) !important;
        opacity: 0.8;
      }
    }
    &:hover > a {
      background: var(--sb-hover) !important;
      color: var(--sb-text-strong) !important;
      > i:first-child {
        color: var(--sb-text-strong) !important;
      }
    }
    /* Active route */
    &.active > a,
    &.router-link-exact-active > a {
      background: var(--sb-active-bg) !important;
      color: var(--sb-text-strong) !important;
      box-shadow: none !important;
      > i:first-child {
        color: var(--sb-active) !important;
      }
      &::before {
        content: "";
        position: absolute;
        left: -0.75rem;
        top: 8px;
        bottom: 8px;
        width: 3px;
        border-radius: 0 3px 3px 0;
        background: var(--sb-accent);
      }
    }
    /* Locked / unavailable modules */
    &[disabled="true"] > a,
    &[style*="not-allowed"] > a {
      color: var(--sb-muted) !important;
      &:hover {
        background: transparent !important;
      }
    }
    /* Sub menus (Super / Import settings) */
    > ul {
      margin: 0.15rem 0 0.25rem;
      padding: 0;
      background: transparent !important;
      li > a {
        display: flex !important;
        align-items: center;
        padding: 0.45rem 0.8rem 0.45rem 2.4rem !important;
        border-radius: 8px;
        color: var(--sb-text) !important;
        font-size: 0.87rem;
        background: transparent !important;
        i {
          margin-right: 0.5rem;
          color: var(--sb-muted) !important;
        }
        &:hover {
          background: var(--sb-hover) !important;
          color: var(--sb-text-strong) !important;
        }
      }
      li.active > a,
      li.router-link-exact-active > a {
        color: var(--sb-active) !important;
        background: transparent !important;
      }
    }
  }

  /* Support actions */
  .zd-menu-action > a {
    cursor: pointer;
  }
  .zd-menu-badge {
    margin-left: auto;
    padding: 0.1rem 0.45rem;
    border-radius: 999px;
    background: rgba(40, 199, 111, 0.16);
    color: #5fd99a;
    font-size: 0.68rem;
    font-weight: 600;
    letter-spacing: 0.02em;
  }

  /* User profile footer (fixed at bottom of sidebar) */
  .zd-sidebar-user-footer {
    flex-shrink: 0;
    margin-top: auto;
    padding: 1rem 1.25rem 1.25rem;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    background: var(--sb-bg);
    display: flex;
    flex-direction: column;
    gap: 0.85rem;

    .zd-sidebar-user-info {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .zd-sidebar-user-avatar {
      width: 40px;
      height: 40px;
      min-width: 40px;
      border-radius: 50%;
      overflow: hidden;
      background: #5a8dee;
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      font-size: 0.95rem;
      flex-shrink: 0;

      img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
    }

    .zd-sidebar-user-details {
      display: flex;
      flex-direction: column;
      min-width: 0;
      overflow: hidden;

      .zd-sidebar-user-name {
        color: var(--sb-text-strong);
        font-weight: 600;
        font-size: 0.92rem;
        line-height: 1.25;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }

      .zd-sidebar-user-role {
        color: var(--sb-muted);
        font-size: 0.78rem;
        line-height: 1.2;
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
    }

    .zd-sidebar-logout {
      display: inline-flex;
      align-items: center;
      gap: 0.65rem;
      color: var(--sb-text);
      font-size: 0.88rem;
      font-weight: 500;
      text-decoration: none;
      transition: color 0.15s;
      width: fit-content;

      i {
        font-size: 1.2rem;
        color: var(--sb-text);
        transition: color 0.15s;
      }

      &:hover {
        color: var(--sb-text-strong);
        i {
          color: var(--sb-text-strong);
        }
      }
    }
  }
}

/* Collapsed (icon-only) mode keeps the theme behaviour; just centre the icons */
body.menu-collapsed #main-menu.zd-sidebar:not(:hover) {
  .zd-sidebar-user-footer {
    padding: 0.75rem 0.5rem;
    align-items: center;

    .zd-sidebar-user-details,
    .zd-sidebar-logout span {
      display: none !important;
    }

    .zd-sidebar-logout {
      width: auto;
      justify-content: center;
    }
  }
  /* show only the logo mark, centred */
  .navbar-header {
    width: 100% !important;
    padding: 0 !important;
    justify-content: center;
    > ul.nav {
      justify-content: center;
      flex: 0 0 auto;
      > li.nav-toggle {
        display: none !important;
      }
    }
    .navbar-brand {
      width: 46px;
      overflow: hidden;
    }
  }
  .navigation > .navigation-header {
    margin-left: 0 !important;
    text-align: center;
  }
  .navigation > li.nav-item {
    margin: 1px 0.5rem !important;
    > a {
      justify-content: center;
      > i:first-child {
        margin-right: 0 !important;
      }
      .menu-title,
      .zd-menu-badge {
        display: none !important;
      }
      &::before {
        left: -0.5rem;
      }
    }
  }
}
</style>
