<template>
  <tr :id="'lead_response_' + item.id">
    <td class="left-space-box" id="d-list">
      <input
        class="select_check"
        @change="getCheckValue"
        type="checkbox"
        v-model="checkValue"
        :true-value="'1'"
        :false-value="'0'"
        :data="item.id"
      />
    </td>
    <td
      class="text-center"
      title="View snapshot of details"
      @click.prevent="showSnapshot()"
      style="cursor: pointer"
    >
      <i class="bx bx-search-alt"></i>
    </td>
    <template v-for="element in list">
      <td
        style="cursor: pointer"
        class="text-center"
        exact
        :key="element.header"
        @click.prevent="openLeadResponse(element.column)"
        v-if="element.visibility == '1'"
      >
        <component
          v-if="element.component == 'date'"
          v-bind="{ date: item[element.column] }"
          v-bind:is="components[element.component].component"
        ></component>
        <lead-response-item-select-component
          v-else-if="element.column == 'status'"
          :column="element.column"
          :lead_id="item.id"
          :current_value="item[element.column]"
          :options="getLeadStatusesMapper"
        />
        <lead-response-item-select-component
          v-else-if="element.column == 'assignee'"
          :column="element.column"
          :lead_id="item.id"
          :current_value="item[element.column]"
          :options="getAllUsersMapper"
        />
        <component
          v-else
          v-bind="{ text: item[element.column] }"
          v-bind:is="components[element.component].component"
        ></component>
      </td>
    </template>
  </tr>
</template>
<script>
const moment = require("moment");
import { mapGetters, mapActions } from "vuex";
import LeadResponseItemSelectComponent from "./LeadResponseItemSelectComponent.vue";

var components = {
  date: {
    name: "Date",
    component: {
      props: {
        date: {
          required: false,
        },
      },
      computed: {
        parseDate() {
          return this.date == null || this.date == ""
            ? ""
            : moment(this.date).format("MMMM Do YYYY");
        },
      },
      template: "<span>{{parseDate}}</span>",
    },
  },
  text: {
    name: "Text",
    component: {
      props: {
        text: {
          required: false,
        },
      },
      computed: {
        pascalCase() {
          return this.text == null || this.text == ""
            ? ""
            : this.text[0].toUpperCase() + this.text.substring(1);
        },
      },
      template: "<span>{{pascalCase}}</span>",
    },
  },
  phone: {
    name: "Phone",
    component: {
      props: {
        text: {
          required: false,
          default: "",
        },
      },
      computed: {
        phoneFormat() {
          x = this.text;
          x = x == null ? "" : x;
          var x = x.replace(/\D/g, "").match(/(\d{0,3})(\d{0,3})(\d{0,4})/);

          return !x[2]
            ? x[1]
            : "(" + x[1] + ") " + x[2] + (x[3] ? "-" + x[3] : "");
        },
      },
      template:
        "<span><i class='bx bx-phone-call phone-icon'></i>{{phoneFormat}}</span>",
    },
  },
  email: {
    name: "Email",
    component: {
      props: {
        text: {
          required: false,
        },
      },
      computed: {
        pascalCase() {
          return this.text == null || this.text == ""
            ? ""
            : this.text[0].toUpperCase() + this.text.substring(1);
        },
        showTemplate() {
          return this.text == null || this.text == "" ? false : true;
        },
      },
      template:
        "<span v-if='!showTemplate' ></span><span v-else><i class='bx bxs-inbox mail-icon'></i>{{pascalCase}}</span>",
    },
  },
  user: {
    name: "User",
    component: {
      props: {
        text: {
          required: false,
        },
      },
      computed: {
        pascalCase() {
          return this.text == null || this.text == ""
            ? ""
            : this.text[0].toUpperCase() + this.text.substring(1);
        },
      },
      template: "<span>{{pascalCase}}</span>",
    },
  },
  badge: {
    name: "Badge",
    component: {
      props: {
        text: {
          required: true,
        },
      },
      template:
        "<span><span class='badge badge-light-success'>{{text}}</span></span>",
    },
  },
};
export default {
  components: { LeadResponseItemSelectComponent },
  name: "LeadResponseItem",
  props: {
    item: Object,
    childCheck: String,
    allUsers: Array,
  },
  data() {
    return {
      checkValue: "0",
      components: components,
    };
  },
  watch: {
    childCheck: {
      // the callback will be called immediately after the start of the observation
      immediate: true,
      handler(val, oldVal) {
        this.checkValue = val;
        this.getCheckValue();
      },
    },
  },
  computed: {
    ...mapGetters("leadResponse", ["getLeadResponseIds", "getLeadStatuses"]),
    ...mapGetters("leadResponseView", ["list"]),
    assigneeName() {
      return this.$helpers.assigneeName(this.allUsers, this.item.assignee, "");
    },
    getAllUsersMapper() {
     return this.allUsers.map((user) => {
        return {
          label: user.name,
          value: user.user_id,
        };
      });
    },
    getLeadStatusesMapper() {
      return this.getLeadStatuses.map((status) => {
        return {
          label: status,
          value: status,
        };
      });
    },
  },
  methods: {
    ...mapActions("leadResponse", [
      "addLeadResponseId",
      "removeLeadResponseId",
    ]),
    pascalCase(string) {
      return string == null || string == ""
        ? ""
        : string[0].toUpperCase() + string.substring(1);
    },
    parseDate(date) {
      return date == null || date == ""
        ? ""
        : moment(date).format("MMMM Do YYYY");
    },
    getCheckValue() {
      this.checkValue == "1"
        ? this.addLeadResponseId(this.item.id)
        : this.removeLeadResponseId(this.item.id);
    },
    showSnapshot() {
      this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "snapshot",
        "lead_snapshot",
        ""
      );

      Event.fire("change_selected_lead_response_id_for_snapshot", this.item.id);
    },
    openLeadResponse(column) {
      if (column === "status" || column === "assignee") {
        return;
      }

      this.$router.push({
        name: "lead-response-show",
        params: { id: this.item.id },
      });
    },
  },
};
</script>

<style>
.phone-icon {
  color: #00c3ff;
  float: left;
  /* position: relative; */
  /* top: 0.2em; */
  /* padding-right: 1em; */
}

.mail-icon {
  color: #1cd216;
  float: left;
}
</style>
