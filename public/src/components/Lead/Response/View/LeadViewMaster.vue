<template>
  <div v-if="tenantApp">
    <div class="app-content content fixed-height-block">
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <router-link
              title="Back To List"
              tag="a"
              :to="{ name: 'lead-responses' }"
              class="nav-link pr-0 pl-0 d-flex align-items-center"
              exact
            >
              <i class="bx bx-caret-left" style="font-size: 1.4em"></i>
            </router-link>
          </li>
          <li class="nav-item" v-if="!createorUpdateView">
            <a
              class="nav-link d-flex align-items-center active"
              id="lead-view-nav"
              data-toggle="tab"
              href="#lead-view"
              aria-controls="lead-view"
              role="tab"
              aria-selected="true"
            >
              <span class="">View</span>
            </a>
          </li>

          <li class="nav-item" v-if="!createorUpdateView">
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

          <li class="nav-item" v-if="!createorUpdateView">
            <a
              class="nav-link d-flex align-items-center"
              id="lead-view-notes-nav"
              data-toggle="tab"
              href="#lead-view-notes"
              aria-controls="lead-view-notes"
              role="tab"
              aria-selected="true"
            >
              <span class="">Notes</span>
            </a>
          </li>

          <li
            class="nav-item"
            v-if="!createorUpdateView && attachment_count != '0'"
          >
            <a
              class="nav-link d-flex align-items-center"
              id="lead-view-files-nav"
              data-toggle="tab"
              href="#lead-view-files"
              aria-controls="lead-view-files"
              role="tab"
              aria-selected="true"
            >
              <span class="">Attachments</span>
              <span
                v-show="attachment_count != '0'"
                class="badge badge-light-primary badge-pill badge-round ml-50"
                >{{ attachment_count }}</span
              >
            </a>
          </li>

          <li class="nav-item" v-if="!createorUpdateView">
            <a
              class="nav-link d-flex align-items-center"
              id="lead-view-conversation-nav"
              data-toggle="tab"
              href="#lead-view-conversation"
              aria-controls="lead-view-conversation"
              role="tab"
              aria-selected="true"
            >
              <span class="">Email Conversation</span>
              <span
                v-show="email_count != '0'"
                class="badge badge-light-primary badge-pill badge-round ml-50"
                >{{ email_count }}</span
              >
            </a>
          </li>

          <li class="nav-item" v-if="!createorUpdateView">
            <a
              class="nav-link d-flex align-items-center"
              id="lead-view-payment-nav"
              data-toggle="tab"
              href="#generic-view-payment-panel"
              aria-controls="Payment Log"
              role="tab"
              aria-selected="true"
            >
              <span class="">Payment Log</span>
            </a>
          </li>

          <li class="nav-item" v-if="!createorUpdateView">
            <a
              class="nav-link d-flex align-items-center"
              id="lead-view-testdrive-nav"
              data-toggle="tab"
              href="#lead-view-testdrive"
              aria-controls="lead-view-testdrive"
              role="tab"
              aria-selected="true"
            >
              <span class="">Test Drive</span>
              <span
                v-show="testdrive_count != 0"
                class="badge badge-light-primary badge-pill badge-round ml-50"
                >{{ testdrive_count }}</span
              >
            </a>
          </li>

          <li class="nav-item" v-else-if="createorUpdateView">
            <a
              class="nav-link d-flex align-items-center active"
              id="lead-create-nav"
              data-toggle="tab"
              href="#lead-create"
              aria-controls="lead-create"
              role="tab"
              aria-selected="true"
            >
              <span class="">{{ tabTitle }}</span>
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
            <lead-view
              :allUsers="allUsers"
              :id="id"
              v-if="!createorUpdateView"
              :key="viewKey"
              :time_zone="timeZoneName"
            ></lead-view>
            <lead-create
              :allUsers="allUsers"
              :app_metadata="app_metadata"
              :id="id"
              v-else-if="createorUpdateView"
              :key="viewKey + '_create'"
            ></lead-create>
            <module-wise-entity-activity
              :allUsers="allUsers"
              :app_metadata="app_metadata"
              :id="id"
              :time_zone="timeZoneName"
              :module="'Lead'"
            >
            </module-wise-entity-activity>
            <lead-note
              :allUsers="allUsers"
              :app_metadata="app_metadata"
              :id="id"
              :time_zone="timeZoneName"
            ></lead-note>
            <lead-file
              :allUsers="allUsers"
              :app_metadata="app_metadata"
              :id="id"
            ></lead-file>
            <module-wise-entity-payment
              :allUsers="allUsers"
              :app_metadata="app_metadata"
              :time_zone="timeZoneName"
              :module="`Lead`"
              :module_id="id"
              :show="!createorUpdateView"
              :show_notice="true"
              :default_email="email"
              :default_phone="phone"
              :default_name="name"
            >
            </module-wise-entity-payment>
            <lead-test-drive
              :allUsers="allUsers"
              :app_metadata="app_metadata"
              :id="id"
              :module="`Lead`"
              :module_id="id"
              :show="!createorUpdateView"
              :time_zone="timeZoneName"
            ></lead-test-drive>
            <email-conversation
              :allUsers="allUsers"
              :app_metadata="app_metadata"
              :id="id"
              :email="email"
            ></email-conversation>
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
      <div class="btn-group mr-1" role="group" v-if="!createorUpdateView">
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
        </div>
      </div>
      <!-- dropdown menu : END -->

      <button
        style="cursor: pointer"
        v-if="!createorUpdateView"
        class="btn btn-primary mr-50 ml-50 text-bold-700"
        @click="convertWarning"
      >
        <i class="bx bx-transfer"></i> Convert
      </button>

      <router-link
        tag="button"
        style="cursor: pointer"
        v-if="!createorUpdateView"
        :to="{ name: 'lead-response-update', params: { id: id } }"
        class="btn btn-primary mr-50 ml-50 text-bold-700"
        exact
      >
        <i class="bx bxs-edit-alt" style="padding-right: 1em"></i> Edit
      </router-link>

      <button
        class="btn btn-primary ml-1 mr-1"
        :disabled="!submit_button"
        v-if="createorUpdateView"
        @click="createEvent"
      >
        Save
      </button>
    </ul>
    <!-- Action Bar : END-->
  </div>
</template>
<script>
import LeadView from "./LeadView";
import LeadCreate from "./LeadCreate";
import LeadNote from "./LeadNote";
import LeadFile from "./LeadFile";
import ModuleWiseEntityPayment from "../../../Payment/ModuleWiseEntityPayment.vue";
import EmailConversation from "./EmailConversation";
import { mapGetters, mapActions } from "vuex";
import moment from "moment-timezone";
import SMSConversation from "../../../WebComponents/SMSConversation";
import Toast from "../../../../library/Toast";
import ModuleWiseEntityActivity from "../../../Activity/ModuleWiseEntityActivity.vue";
import LeadTestDrive from "./LeadTestDrive.vue";

export default {
  props: {
    id: { required: false },
    application: { required: true },
    app_metadata: { required: true },
    user_metadata: { required: true },
  },
  name: "LeadViewMaster",
  components: {
    LeadView,
    LeadCreate,
    LeadNote,
    EmailConversation,
    LeadFile,
    SMSConversation,
    ModuleWiseEntityPayment,
    ModuleWiseEntityActivity,
    LeadTestDrive,
  },
  data() {
    return {
      view_number: 0,
      submit_button: true,
      activity_count: 0,
      testdrive_count: 0,
      email_count: 0,
      attachment_count: 0,
      name: null,
      email: null,
      phone: null,
      is_converted: 0,
    };
  },
  computed: {
    ...mapGetters("users", ["allUsers"]),
    ...mapGetters("leadResponseView", [
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
    tenantApp() {
      return this.application == "tenant";
    },
    viewKey() {
      return this.$route.params.id + "-" + this.$route.name;
    },
    createorUpdateView() {
      return (
        this.$route.name == "lead-response-create" ||
        this.$route.name == "lead-response-update"
      );
    },
    tabTitle() {
      return this.$route.name == "lead-response-create" ? "Create" : "Update";
    },
    timeZoneName() {
      return this.user_metadata && this.user_metadata.hasOwnProperty("timezone")
        ? this.user_metadata.timezone
        : "Canada/Eastern";
    },
  },
  methods: {
    ...mapActions("users", ["getAllUsers"]),
    ...mapActions("leadResponseView", [
      "commitOnlyEntities",
      "commitCurrentEntity",
      "commitLoadState",
    ]),
    async downloadPDF(slug) {
      if (this.$route.params.id) {
        const vm = this;
        await axios({
          method: "get",
          responseType: "blob",
          url:
            window.location.origin +
            `/api/business/leads/responses/pdf/test-drive/generate/` +
            this.$route.params.id,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
        })
          .then((response) => {
            const file = new Blob([response.data], { type: "application/pdf" });
            const fileURL = URL.createObjectURL(file);
            window.open(fileURL);
          })
          .catch(({ response }) => {
            console.log(response);
          });
      }
    },
    createEvent() {
      this.submit_button = false;
      Event.fire("save_lead", null);
    },
    async convertWarning() {
      const vm = this;
      if (parseInt(this.is_converted) === 0) {
        this.convertLeadToCustomer();
      } else {
        await this.$swal({
          showConfirmButton: true,
          showCancelButton: true,
          cancelButtonText: "Back",
          confirmButtonText: "Yes, convert",
          toast: false,
          position: "center",
          text:
            "This lead has already been converted as a customer. Do you want to proceed?",
        }).then((result) => {
          if (result.isDismissed) {
          } else if (result.isConfirmed) {
            vm.convertLeadToCustomer();
          }
        });
      }
    },
    convertLeadToCustomer() {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/leads/responses/convert/" +
          this.$route.params.id +
          "/customers",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          vm.$router.push({
            name: "customer-update",
            params: { id: data.customer_id },
          });
        })
        .catch(({ response }) => {
          let error =
            "There were some errors in converting the lead to customer.";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
  },
  created() {
    moment.tz.setDefault(this.timeZoneName);
  },
  mounted() {
    const vm = this;
    Event.listen("save_complete", (data) => {
      vm.$data.submit_button = true;
      if (data.hasOwnProperty("success")) {
        if (data.success == `Lead has been created successfully.`) {
          this.commitOnlyEntities();
        } else {
          this.commitOnlyEntities();
        }
        //vm.commitLoadState(false);
        vm.$router.push({
          name: "lead-response-show",
          params: { id: data.lead_id },
        });
      }
    });

    if (this.$route.params.id) {
      this.commitCurrentEntity(this.$route.params.id);
    }

    this.getAllUsers();

    Event.listen("activity_count", (data) => {
      vm.$data.activity_count = data;
    });

    Event.listen("email_count", (data) => {
      vm.$data.email_count = data;
    });

    Event.listen("attachment_count", (data) => {
      vm.$data.attachment_count = data;
    });

    Event.listen("testdrive_count", (data) => {
      vm.$data.testdrive_count = data;
    });

    Event.listen("get_lead_email", (data) => {
      vm.$data.email = data;
    });

    Event.listen("get_lead_phone", (data) => {
      vm.$data.phone = data;
    });

    Event.listen("get_lead_converted_flag", (data) => {
      vm.$data.is_converted = data;
    });

    Event.listen("get_lead_name", (data) => {
      vm.$data.name = data;
    });
  },
};
</script>
