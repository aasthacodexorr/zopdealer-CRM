<template>
  <tr
    :id="'lead_response_' + item.id"
    :class="{ 'is-selected': checkValue == '1' }"
  >
    <td class="lr-col-check" id="d-list">
      <input
        class="select_check lr-checkbox"
        @change="getCheckValue"
        type="checkbox"
        v-model="checkValue"
        :true-value="'1'"
        :false-value="'0'"
        :data="item.id"
        :aria-label="'Select lead ' + fullName"
      />
    </td>
    <td class="lr-col-avatar">
      <span class="lr-lead-cell">
        <span
          class="lr-avatar"
          :style="avatarStyle(fullName)"
          :title="fullName"
          @click.prevent="openLeadResponse('avatar')"
          >{{ initials(item.first_name, item.last_name) }}</span
        >
        <button
          type="button"
          class="lr-snapshot-btn"
          title="View snapshot of details"
          @click.prevent="showSnapshot()"
        >
          <i class="bx bx-search-alt"></i>
        </button>
      </span>
    </td>
    <template v-for="element in list">
      <td
        style="cursor: pointer"
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
          display="pill"
        />
        <lead-response-item-select-component
          v-else-if="element.column == 'assignee'"
          :column="element.column"
          :lead_id="item.id"
          :current_value="item[element.column]"
          :options="getAllUsersMapper"
          display="user"
        />
        <component
          v-else
          v-bind="{ text: item[element.column], column: element.column }"
          v-bind:is="components[element.component].component"
        ></component>
      </td>
    </template>
    <td class="lr-col-actions">
      <div class="dropdown">
        <button
          type="button"
          class="lr-row-menu-btn"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
          title="More actions"
        >
          <i class="bx bx-dots-horizontal-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right lr-row-menu">
          <a class="dropdown-item" @click.prevent="openLeadResponse('menu')"
            ><i class="bx bx-show"></i> View lead</a
          >
          <a class="dropdown-item" @click.prevent="showSnapshot()"
            ><i class="bx bx-search-alt"></i> Quick snapshot</a
          >
        </div>
      </div>
    </td>
  </tr>
</template>
<script>
const moment = require("moment");
import { mapGetters, mapActions } from "vuex";
import LeadResponseItemSelectComponent from "./LeadResponseItemSelectComponent.vue";

const AVATAR_COLORS = [
  ["#e6efff", "#2f62d6"],
  ["#e3f6e8", "#1e8a43"],
  ["#fff3d1", "#a26a00"],
  ["#efe8ff", "#6b46c1"],
  ["#def6f3", "#11806f"],
  ["#fde6ef", "#b83268"],
];

const pascal = (text) =>
  text == null || text === "" ? "" : String(text)[0].toUpperCase() + String(text).substring(1);

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
        isEmpty() {
          return this.date == null || this.date == "";
        },
        parseDate() {
          return this.isEmpty ? "" : moment(this.date).format("MMM D, YYYY");
        },
        parseTime() {
          return this.isEmpty ? "" : moment(this.date).format("h:mm A");
        },
        fullDate() {
          return this.isEmpty ? "" : moment(this.date).format("MMMM Do YYYY, h:mm A");
        },
      },
      template:
        "<span v-if='isEmpty' class='lr-cell-muted'>—</span><span v-else class='lr-date' :title='fullDate'><span>{{parseDate}}</span><small>{{parseTime}}</small></span>",
    },
  },
  text: {
    name: "Text",
    component: {
      props: {
        text: {
          required: false,
        },
        column: {
          required: false,
          default: "",
        },
      },
      computed: {
        pascalCase() {
          return pascal(this.text);
        },
        cellClass() {
          if (["first_name", "last_name"].includes(this.column)) return "lr-cell-strong";
          if (["vin", "stock_no", "year"].includes(this.column)) return "lr-mono";
          return "";
        },
      },
      template:
        "<span v-if='pascalCase === \"\"' class='lr-cell-muted'>—</span><span v-else :class='cellClass'>{{pascalCase}}</span>",
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
        column: {
          required: false,
          default: "",
        },
      },
      computed: {
        phoneFormat() {
          var x = this.text == null ? "" : String(this.text);
          x = x.replace(/\D/g, "").match(/(\d{0,3})(\d{0,3})(\d{0,4})/);

          return !x[2]
            ? x[1]
            : "(" + x[1] + ") " + x[2] + (x[3] ? "-" + x[3] : "");
        },
      },
      template:
        "<span v-if='phoneFormat === \"\"' class='lr-cell-muted'>—</span><span v-else class='lr-cell-icon'><i class='bx bx-phone-call lr-icon-phone'></i>{{phoneFormat}}</span>",
    },
  },
  email: {
    name: "Email",
    component: {
      props: {
        text: {
          required: false,
        },
        column: {
          required: false,
          default: "",
        },
      },
      computed: {
        pascalCase() {
          return pascal(this.text);
        },
        showTemplate() {
          return this.text == null || this.text == "" ? false : true;
        },
      },
      template:
        "<span v-if='!showTemplate' class='lr-cell-muted'>—</span><span v-else class='lr-cell-icon'><i class='bx bx-envelope lr-icon-mail'></i>{{pascalCase}}</span>",
    },
  },
  user: {
    name: "User",
    component: {
      props: {
        text: {
          required: false,
        },
        column: {
          required: false,
          default: "",
        },
      },
      computed: {
        pascalCase() {
          return pascal(this.text);
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
        column: {
          required: false,
          default: "",
        },
      },
      template:
        "<span><span class='lr-pill lr-pill--green'>{{text}}</span></span>",
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
    fullName() {
      return [this.item.first_name, this.item.last_name]
        .filter((part) => part)
        .join(" ");
    },
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
    initials(first, last) {
      const letters =
        (first ? String(first).trim()[0] || "" : "") +
        (last ? String(last).trim()[0] || "" : "");
      return letters ? letters.toUpperCase() : "?";
    },
    avatarStyle(name) {
      const value = String(name || "");
      let hash = 0;
      for (let i = 0; i < value.length; i++) {
        hash = (hash * 31 + value.charCodeAt(i)) >>> 0;
      }
      const [background, color] = AVATAR_COLORS[hash % AVATAR_COLORS.length];
      return { background, color };
    },
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
