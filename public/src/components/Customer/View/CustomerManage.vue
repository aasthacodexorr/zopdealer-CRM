<template>
  <div>
    <div
      class="app-content content fixed-height-block"
      style="overflow: visible"
    >
      <review-request
        v-show="updateView"
        :phone="phone"
        :email="email"
        :customer_id="customer_id"
        :show="false"
      ></review-request>
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <router-link
              title="Back To List"
              tag="a"
              :to="{ name: 'customer-list' }"
              class="nav-link pr-0 pl-0 d-flex align-items-center"
              exact
            >
              <i class="bx bx-caret-left" style="font-size: 1.4em"></i>
            </router-link>
          </li>
          <li class="nav-item">
            <a
              class="nav-link d-flex align-items-center active"
              @click="
                show_general_submit_button = true;
                show_credit_application_submit_button = false;
                show_insurance_submit_button = false;
              "
              id="customer-create-tab"
              data-toggle="tab"
              href="#customer-create-panel"
              aria-controls="information"
              role="tab"
              aria-selected="true"
            >
              <span class="">{{ tabTitle }}</span>
            </a>
          </li>

          <li class="nav-item" v-if="updateView">
            <a
              class="nav-link d-flex align-items-center"
              id="generic-view-activities-nav"
              data-toggle="tab"
              href="#generic-view-activities"
              aria-controls="generic-view-activities"
              role="tab"
              aria-selected="true"
            >
              <span class="">Activities</span>
              <span
                v-show="activity_count != '0'"
                class="badge badge-light-primary badge-pill badge-round ml-50"
                >{{ activity_count }}</span
              >
            </a>
          </li>

          <li class="nav-item" v-show="updateView">
            <a
              class="nav-link d-flex align-items-center"
              @click="
                show_general_submit_button = false;
                show_credit_application_submit_button = true;
                show_insurance_submit_button = false;
              "
              id="customer-credit-application-tab"
              data-toggle="tab"
              href="#customer-credit-application-panel"
              aria-controls="Credit Application"
              role="tab"
              aria-selected="true"
            >
              <span class="">Credit Application</span>
            </a>
          </li>
          <li class="nav-item" v-show="updateView">
            <a
              class="nav-link d-flex align-items-center"
              @click="
                show_general_submit_button = false;
                show_credit_application_submit_button = false;
                show_insurance_submit_button = false;
              "
              id="customer-note-tab"
              data-toggle="tab"
              href="#customer-notes"
              aria-controls="Notes"
              role="tab"
              aria-selected="true"
            >
              <span class="">Notes</span>
            </a>
          </li>
          <li class="nav-item" v-show="updateView">
            <a
              class="nav-link d-flex align-items-center"
              @click="
                show_general_submit_button = false;
                show_credit_application_submit_button = false;
                show_insurance_submit_button = true;
              "
              id="customer-insurance-tab"
              data-toggle="tab"
              href="#customer-insurance-panel"
              aria-controls="Customer Insurance"
              role="tab"
              aria-selected="true"
            >
              <span class="">Insurance</span>
            </a>
          </li>

          <li class="nav-item" v-show="updateView">
            <a
              class="nav-link d-flex align-items-center"
              @click="
                show_general_submit_button = false;
                show_credit_application_submit_button = false;
                show_insurance_submit_button = false;
              "
              id="customer-deals-tab"
              data-toggle="tab"
              href="#customer-deals-panel"
              aria-controls="Customer Deals"
              role="tab"
              aria-selected="true"
            >
              <span class="">Deals</span>
              <span
                v-show="deal_count != '0'"
                class="badge badge-light-primary badge-pill badge-round ml-50"
                >{{ deal_count }}</span
              >
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="">
        <div class="content-body mb-3">
          <!-- form layout section start -->
          <div class="tab-content">
            <customer-create
              :allUsers="allUsers"
              :app_metadata="app_metadata"
              :key="viewKey"
            ></customer-create>
            <module-wise-entity-activity
            :allUsers="allUsers"
              :app_metadata="app_metadata"
              :id="id"
              :time_zone="timeZoneName"
              :module="'Customer'"
            ></module-wise-entity-activity>
            <credit-application
              :customer_loaded="customer_loaded"
              :customer_type="customer_type"
            ></credit-application>
            <customer-note
              :allUsers="allUsers"
              :app_metadata="app_metadata"
              :id="id"
              :time_zone="timeZoneName"
            ></customer-note>
            <customer-insurance></customer-insurance>
            <customer-deal></customer-deal>
            <s-m-s-conversation
              :app_metadata="app_metadata"
              :user_metadata="user_metadata"
              :application="application"
              :phone_no="phone"
              :time_zone="timeZoneName"
            ></s-m-s-conversation>
          </div>
        </div>
      </div>
    </div>
    <!-- END: Content-->
    <!-- Action Bar : START -->
    <ul class="nav nav-tabs p-50 custom-footer-fixed-action-bar" role="tablist">
      <!-- dropdown menu : START -->
      <div class="btn-group mr-1" role="group" v-if="updateView">
        <button
          id="customerActionDropDown"
          style="line-height: 1.2em"
          type="button"
          class="btn btn-secondary"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <i class="bx bx-printer mr-50"></i> Print
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
          <a class="dropdown-item" @click="downloadPDF('test-drive')"
            >Test Drive</a
          >
          <a class="dropdown-item" @click="downloadPDF('credit-application')"
            >Credit Application</a
          >
        </div>
      </div>
      <!-- dropdown menu : END -->

      <review-request-c-t-a
        v-show="updateView"
        :phone="phone"
        :email="email"
        :customer_id="customer_id"
      ></review-request-c-t-a>

      <router-link
        tag="li"
        :to="{
          name: 'deal-create',
          params: { preselected_customer_id: customer_id },
        }"
        class="d-inline-block ml-1"
        exact
      >
        <a class="btn btn-secondary">Create Deal</a>
      </router-link>

      <button
        v-show="show_general_submit_button"
        type="submit"
        class="btn btn-primary ml-4 mr-1"
        :disabled="!submit_button"
        @click="createEvent"
      >
        Save
      </button>

      <button
        v-show="show_credit_application_submit_button"
        type="submit"
        class="btn btn-primary ml-4 mr-1"
        :disabled="!submit_button"
        @click="createCreditApplicationEvent"
      >
        Save
      </button>

      <button
        v-show="show_insurance_submit_button"
        type="submit"
        class="btn btn-primary ml-4 mr-1"
        :disabled="!submit_button"
        @click="createCustomerInsurance"
      >
        Save
      </button>
    </ul>
    <!-- Action Bar : END-->
  </div>
</template>
<script>
import CustomerCreate from "./CustomerCreate";
import CreditApplication from "./CreditApplication";
import CustomerNote from "./CustomerNote";
import CustomerInsurance from "./CustomerInsurance";
import { mapGetters, mapActions } from "vuex";
import CustomerDeal from "./CustomerDeal";
import SMSConversation from "../../WebComponents/SMSConversation";
import moment from "moment-timezone";
import ReviewRequest from "../../WebComponents/ReviewRequest.vue";
import ReviewRequestCTA from "../../WebComponents/ReviewRequestCTA.vue";
import ModuleWiseEntityActivity from '../../Activity/ModuleWiseEntityActivity.vue';

export default {
  props: {
    id: { required: false },
    application: { required: true },
    app_metadata: { required: true },
    user_metadata: { required: true },
  },
  name: "CustomerManage",
  components: {
    CustomerCreate,
    CreditApplication,
    CustomerNote,
    CustomerInsurance,
    CustomerDeal,
    SMSConversation,
    ReviewRequest,
    ReviewRequestCTA,
    ModuleWiseEntityActivity,
  },
  data() {
    return {
      show_general_submit_button: true,
      show_credit_application_submit_button: false,
      show_insurance_submit_button: false,
      view_number: 0,
      submit_button: true,
      customer_type: 0,
      customer_loaded: false,
      prev_name: null,
      deal_count: 0,
      phone: null,
      email: null,
      customer_id: null,
      activity_count: 0,
    };
  },
  computed: {
    ...mapGetters("customerView", [
      "list",
      "getViewId",
      "getViewTitle",
      "getEntities",
      "getPager",
      "getSortColumn",
      "getLoadState",
      "getEntitiesCount",
      "getCurrentPage",
      "getCurrentEntity",
      "getSearchString",
      "getSearch",
    ]),
    ...mapGetters("users", ["allUsers"]),
    updateView() {
      return this.$route.params.id && this.$route.name == "customer-update";
    },
    viewKey() {
      return this.$route.params.id && this.$route.name == "customer-update"
        ? "customer-update"
        : "customer-create";
    },
    tabTitle() {
      return this.$route.params.id && this.$route.name == "customer-update"
        ? "Update"
        : "Create";
    },
    timeZoneName() {
      return this.user_metadata && this.user_metadata.hasOwnProperty("timezone")
        ? this.user_metadata.timezone
        : "Canada/Eastern";
    },
  },
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      vm.prev_name = from.name;
    });
  },
  methods: {
    ...mapActions("customerView", [
      "commitOnlyEntities",
      "commitCurrentEntity",
      "commitLoadState",
    ]),
    ...mapActions("users", ["getAllUsers"]),
    async downloadPDF(slug) {
      if (this.$route.params.id) {
        const vm = this;
        await axios({
          method: "get",
          responseType: "blob",
          url:
            window.location.origin +
            `/api/business/customers/pdf/${slug}/generate/` +
            this.$route.params.id,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
        })
          .then((response) => {
            const file = new Blob([response.data], { type: "application/pdf" }); //Build a URL from the file
            const fileURL = URL.createObjectURL(file); //Open the URL on new Window
            window.open(fileURL);
          })
          .catch(({ response }) => {
            console.log(response);
          });
      }
    },
    createEvent() {
      this.submit_button = false;
      Event.fire("save_customer", null);
    },
    createCustomerInsurance() {
      this.submit_button = false;
      Event.fire("save_customer_insurance", null);
    },
    createCreditApplicationEvent() {
      this.submit_button = false;
      Event.fire("save_credit_application", null);
    },
  },
  created() {
    moment.tz.setDefault(this.timeZoneName);
  },
  mounted() {
    const vm = this;

    if (this.updateView) {
      this.commitCurrentEntity(this.$route.params.id);
      this.customer_id = this.$route.params.id;
    }

    Event.listen("deal_count", (data) => {
      vm.$data.deal_count = data;
    });

    Event.listen("save_credit_application_complete", (data) => {
      vm.$data.submit_button = true;
    });
    Event.listen("save_customer_insurance_complete", (data) => {
      vm.$data.submit_button = true;
    });
    Event.listen("save_complete", (data) => {
      vm.$data.submit_button = true;

      if (data.hasOwnProperty("success")) {
        if (data.success == "Customer details has been created.") {
          this.commitOnlyEntities();
        } else {
          this.commitOnlyEntities();
        }

        if (data.success == "Customer details has been created.") {
          //If user came from deal than push it to deal route again. Else normal routing
          if (
            vm.prev_name == "deal-create" ||
            vm.prev_name == "deal-update" ||
            vm.prev_name == "work-order-create" ||
            vm.prev_name == "work-order-update"
          ) {
            vm.$router.back();
          } else {
            vm.$router.push({
              name: "customer-update",
              params: { id: data.customer_id },
            });
          }
        } else if (
          vm.prev_name == "deal-create" ||
          vm.prev_name == "deal-update"
        ) {
          vm.$router.back();
        }
      }
    });

    Event.listen("change_customer", (data) => {
      this.customer_type = data;
    });

    Event.listen("customer_load", (data) => {
      this.customer_loaded = data;
    });

    Event.listen("get_customer_phone", (data) => {
      vm.$data.phone = data;
    });

    Event.listen("get_customer_email", (data) => {
      vm.$data.email = data;
    });

    Event.listen("activity_count", (data) => {
      vm.$data.activity_count = data;
    });

    this.getAllUsers();
  },
  beforeDestroy() {
    Event.vue.$off("save_customer", null);
    Event.vue.$off("save_customer_insurance", null);
    Event.vue.$off("change_customer", null);
    Event.vue.$off("customer_load", null);
    Event.vue.$off("save_credit_application", null);
  },
};
</script>
