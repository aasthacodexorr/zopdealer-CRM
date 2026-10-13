<template>
  <div>
    <div
      class="app-content content fixed-height-block"
      style="overflow: visible"
    >
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <router-link
              title="Back To List"
              tag="a"
              :to="{ name: 'work-order-list' }"
              class="nav-link pr-0 pl-0 d-flex align-items-center"
              exact
            >
              <i class="bx bx-caret-left" style="font-size: 1.4em"></i>
            </router-link>
          </li>
          <li class="nav-item pr-0 pl-0">
            <a
              class="nav-link d-flex align-items-center active"
              id="work-order-create-tab"
              data-toggle="tab"
              @click="show_work_order_submit_button = true"
              href="#work-order-create-panel"
              aria-controls="Work Order Create"
              role="tab"
              aria-selected="true"
            >
              <span class="">{{ tabTitle }}</span>
            </a>
          </li>
          <li class="nav-item pr-0 pl-0" v-if="updateView">
            <a
              class="nav-link d-flex align-items-center"
              id="work-order-job-tab"
              data-toggle="tab"
              @click="show_work_order_submit_button = false"
              href="#work-order-job-panel"
              aria-controls="Work Order Job"
              role="tab"
              aria-selected="true"
            >
              <span class="">Work Jobs</span>
            </a>
          </li>
          <div class="btn-feild-box">
            <!-- dropdown menu : START -->
            <div
              class="btn-group"
              v-if="updateView"
              role="group"
              style="margin-right: 3em"
            >
              <button
                id="inventoryActionDropDown"
                type="button"
                class="btn btn-secondary dropdown-toggle"
                style="line-height: 1.2em"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-printer mr-50"></i> Print
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <a class="dropdown-item" @click="downloadPDF('work-order-complete')">Work Order Details</a>
                <a class="dropdown-item" @click="downloadPDF('work-order-summary')">Work Order Summary</a>
              </div>
            </div>
            <!-- dropdown menu : END -->

            <button
              type="submit"
              v-show="show_work_order_submit_button"
              :disabled="!submit_button"
              class="btn btn-primary mr-1"
              @click="createEvent"
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
            <work-order-create
              :id="id"
              :app_metadata="app_metadata"
              :allUsers="allUsers"
              :key="viewKey"
              :prev_name="prev_name"
            ></work-order-create>
            <work-order-job
              :id="id"
              :app_metadata="app_metadata"
              :allUsers="allUsers"
              :prev_name="prev_name"
            ></work-order-job>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import Toast from "../../../library/Toast";
import SMSConversation from "../../WebComponents/SMSConversation";
import moment from "moment-timezone";
import WorkOrderCreate from "./WorkOrderCreate";
import WorkOrderJob from "./WorkOrderJob";
export default {
  props: {
    id: { required: false },
    application: { required: true },
    app_metadata: { required: true },
    user_metadata: { required: true },
  },
  name: "WorkOrderManage",
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      vm.prev_name = from.name;
    });
  },
  data() {
    return {
      prev_name: null,
      submit_button: true,
      show_work_order_submit_button: true,
    };
  },
  components: {
    WorkOrderCreate,
    WorkOrderJob,
  },
  computed: {
    ...mapGetters("workOrderView", [
      "list",
      "getViewId",
      "getViewTitle",
      "getEntities",
      "getPager",
      "getSortColumn",
      "getLoadState",
      "getEntitiesCount",
      "getCurrentPage",
    ]),
    ...mapGetters("users", ["allUsers"]),
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    viewKey() {
      return this.$route.params.id + "-" + this.$route.name;
    },
    tabTitle() {
      return this.$route.params.id && this.$route.name == "work-order-update"
        ? "Update"
        : "Create";
    },
    updateView() {
      return this.$route.params.id && this.$route.name == "work-order-update";
    },
    timeZoneName() {
      return this.user_metadata && this.user_metadata.hasOwnProperty("timezone")
        ? this.user_metadata.timezone
        : "Canada/Eastern";
    },
  },
  methods: {
    ...mapActions("workOrderView", [
      "commitOnlyEntities",
      "commitCurrentEntity",
      "commitLoadState",
    ]),
    ...mapActions("users", ["getAllUsers"]),
    createEvent() {
      this.submit_button = false;
      Event.fire("save_work_order", null);
    },
    async downloadPDF(bill_type) {
      if (this.$route.params.id && this.$route.name == "work-order-update") {
        const vm = this;
        await axios({
          method: "get",
          responseType: "blob",
          url:
            window.location.origin +
            `/api/business/work-orders/pdf/${bill_type}/generate/${this.$route.params.id}`,
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
  },
  created() {
    moment.tz.setDefault(this.timeZoneName);
  },
  mounted() {
    const vm = this;
    this.getAllUsers();

    if (this.updateView) {
      this.commitCurrentEntity(this.$route.params.id);
    }

    Event.listen("save_work_order_complete", (data) => {
      vm.$data.submit_button = true;

      console.log(data);

      if (data.hasOwnProperty("success")) {
        if (
          data.success === "New work order added." ||
          data.success === "Work Order has been created."
        ) {
          this.commitOnlyEntities();
        } else {
          this.commitOnlyEntities();
        }

        if (
          data.success === "New work order added." ||
          data.success === "Work Order has been created."
        ) {
          vm.$router.push({
            name: "work-order-update",
            params: { id: data.work_order_id },
          });
        }
      }
    });
  },
  beforeDestroy() {
    Event.vue.$off("save_work_order", null);
    Event.vue.$off("save_work_order_complete", null);
  },
};
</script>
