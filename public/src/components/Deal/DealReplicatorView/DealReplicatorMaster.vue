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
              :to="{ name: 'deal-templates' }"
              class="nav-link pr-0 pl-0 d-flex align-items-center"
              exact
            >
              <i class="bx bx-caret-left" style="font-size: 1.4em"></i>
            </router-link>
          </li>

          <li class="nav-item">
            <a
              class="nav-link d-flex align-items-center active"
              id="deal-template-tab"
              data-toggle="tab"
              href="#deal-template-create"
              aria-controls="Deal Template"
              role="tab"
              aria-selected="true"
              @click="show_deal_submit_button = true;"
            >
              <span class="">{{ tabTitle }}</span>
            </a>
          </li>

          <li class="nav-item" v-show="updateView">
            <a
              class="nav-link d-flex align-items-center"
              id="deal-template-worksheet-tab"
              data-toggle="tab"
              href="#deal-template-worksheet-panel"
              aria-controls="Deal Template Worksheet"
              role="tab"
              aria-selected="true"
               @click="show_deal_submit_button = false;"
            >
              <span class="">Worksheet</span>
            </a>
          </li>

          <div class="btn-feild-box" v-if="show_deal_submit_button">
            <button
              type="submit"
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
            <deal-replicator-create
              :id="id"
              :app_metadata="app_metadata"
              :key="viewKey"
            ></deal-replicator-create>

            <deal-replicator-worksheet
            :retail_or_wholesale_flag="retail_or_wholesale_flag"
            ></deal-replicator-worksheet>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import DealReplicatorCreate from "./DealReplicatorCreate";
import DealReplicatorWorksheet from "./DealReplicatorWorksheet.vue";

export default {
  props: {
    id: { required: false },
    application: { required: true },
    app_metadata: { required: true },
  },
  name: "DealReplicatorMaster",
  data() {
    return {
      submit_button: true,
      show_deal_submit_button: true,
      retail_or_wholesale_flag: 0
    };
  },
  components: {
    DealReplicatorCreate,
    DealReplicatorWorksheet
  },
  methods: {
    createEvent() {
      this.submit_button = false;
      Event.fire("save_deal_template", null);
    },
  },
  computed: {
    tabTitle() {
      return this.$route.params.id && this.$route.name == "deal-template-update"
        ? "Update"
        : "Create";
    },
    viewKey() {
      return this.$route.params.id + "-" + this.$route.name;
    },
    updateView() {
      return this.$route.params.id && this.$route.name == "deal-template-update";
    }
  },
  mounted() {
    const vm = this;

    Event.listen("change_retail_or_wholesale", (data) => {
      vm.$data.retail_or_wholesale_flag = data;
    });

    Event.listen("save_deal_template_complete", (data) => {
      vm.$data.submit_button = true;

      if (data.hasOwnProperty("success")) {
        if (data.success == "Deal Template has been created.") {
          vm.$router.push({
            name: "deal-template-update",
            params: { id: data.deal_replicator_id },
          });
        }
      }
    });
  },
  beforeDestroy() {
    Event.vue.$off("save_deal_template", null);
    Event.vue.$off("save_deal_template_complete", null);
    Event.vue.$off("change_retail_or_wholesale", null);
  },
};
</script>