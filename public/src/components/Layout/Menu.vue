<template>
  <!-- BEGIN: Main Menu-->
  <div
    class="main-menu menu-fixed menu-light menu-accordion menu-shadow"
    data-scroll-to-active="true"
    id="main-menu"
    style="overflow: auto;"
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
            >
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
            >
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
      </ul>
    </div>
  </div>
  <!-- END: Main Menu-->
</template>

<script>
import { mapActions } from "vuex";

export default {
  name: "Menu",
  props: ["application", "app_metadata"],
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
  },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 1s ease-in-out;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
