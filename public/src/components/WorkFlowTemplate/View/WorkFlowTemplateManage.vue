<template>
  <div>
    <div
      class="app-content content fixed-height-block"
      style="overflow: visible"
      v-if="isAuthorized"
    >
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <router-link
              title="Back To List"
              tag="a"
              :to="{ name: 'workflow-template-list' }"
              class="nav-link pr-0 pl-0 d-flex align-items-center"
              exact
            >
              <i class="bx bx-caret-left" style="font-size: 1.4em"></i>
            </router-link>
          </li>
          <li class="nav-item pr-0 pl-0">
            <a
              @click="show_workflow_template_submit_button = true"
              class="nav-link d-flex align-items-center active"
              id="workflow-create-tab"
              data-toggle="tab"
              href="#workflow-create-panel"
              aria-controls="Work Flow Create"
              role="tab"
              aria-selected="true"
            >
              <span class="">{{ tabTitle }}</span>
            </a>
          </li>
          <li class="nav-item pr-0 pl-0" v-show="updateView">
            <a
              @click="show_workflow_template_submit_button = false"
              class="nav-link d-flex align-items-center"
              id="workflow-template-step-tab"
              data-toggle="tab"
              href="#workflow-template-steps"
              aria-controls="Work Flow Template Steps"
              role="tab"
              aria-selected="true"
            >
              <span class="">Steps</span>
            </a>
          </li>
          <div
            class="btn-feild-box"
            v-show="show_workflow_template_submit_button && !updateView"
          >
            <button
              type="submit"
              @click="createEvent"
              class="btn btn-primary mr-1"
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
            <work-flow-template-create
              :app_metadata="app_metadata"
              :application="application"
            ></work-flow-template-create>
            <work-flow-template-step
              :app_metadata="app_metadata"
              :application="application"
              :current_module="current_module"
            ></work-flow-template-step>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import moment from "moment-timezone";
import WorkFlowTemplateCreate from "./WorkFlowTemplateCreate";
import WorkFlowTemplateStep from "./WorkFlowTemplateStep.vue";
export default {
  props: {
    id: { required: false },
    application: { required: true },
    app_metadata: { required: true },
    user_metadata: { required: true },
  },
  name: "WorkFlowTemplateManage",
  data() {
    return {
      current_module: "",
      show_workflow_template_submit_button: true,
    };
  },
  components: {
    WorkFlowTemplateCreate,
    WorkFlowTemplateStep,
  },
  computed: {
    ...mapGetters("users", ["allUsers"]),
    tabTitle() {
      return this.$route.params.id &&
        this.$route.name == "workflow-template-update"
        ? "Update"
        : "Create";
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    viewKey() {
      return this.$route.params.id + "-" + this.$route.name;
    },
    updateView() {
      return (
        this.$route.params.id && this.$route.name == "workflow-template-update"
      );
    },
    timeZoneName() {
      return this.user_metadata && this.user_metadata.hasOwnProperty("timezone")
        ? this.user_metadata.timezone
        : "Canada/Eastern";
    },
  },
  methods: {
    ...mapActions("users", ["getAllUsers"]),
    createEvent() {
      Event.fire("save_workflow_template", null);
    },
  },
  created() {
    moment.tz.setDefault(this.timeZoneName);
  },
  mounted() {
    const vm = this;
    this.getAllUsers();

    Event.listen("change_module", (data) => {
      vm.current_module = data;
    });

    Event.listen("save_workflow_template_complete", (data) => {
      vm.$data.submit_button = true;

      if (data.hasOwnProperty("success")) {
        if (data.success == "WorkFlow Template has been created.") {
          vm.$router.push({
            name: "workflow-template-update",
            params: { id: data.workflow_template_id },
          });
        }
      }

      vm.$helpers.gTagClickEvent(
        vm,
        "zd_action",
        "workflow_template",
        "template",
        ""
      );
    });
  },
  beforeDestroy() {
    Event.vue.$off("save_workflow_template", null);
    Event.vue.$off("save_workflow_template_complete", null);
  },
};
</script>
