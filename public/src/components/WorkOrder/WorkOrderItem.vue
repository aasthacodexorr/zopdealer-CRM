<template>
  <tr :id="'work_order_' + item.id">
    <td class="left-space-box">
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
    <template v-for="element in list">
      <router-link
        tag="td"
        style="cursor: pointer"
        :to="{ name: 'work-order-update', params: { id: item.id } }"
        class="text-center"
        exact
        :key="element.header"
        v-if="element.visibility == '1'"
      >
        <component
          v-if="element.column == 'auto_sync_expense_flag'"
          v-bind="{ text: autoSyncExpense }"
          v-bind:is="components['text'].component"
        ></component>

        <component
          v-else-if="
            element.column == 'updated_at' || element.column == 'created_at'
          "
          v-bind="{ date: item[element.column], time_zone: time_zone }"
          v-bind:is="components['datetime'].component"
        ></component>

        <component
          v-else-if="element.component == 'date'"
          v-bind="{ date: item[element.column], time_zone: time_zone }"
          v-bind:is="components[element.component].component"
        ></component>

        <component
          v-else-if="
            element.component == 'user' && element.column == 'assigned_advisor'
          "
          v-bind="{ text: advisorName }"
          v-bind:is="components[element.component].component"
        ></component>

        <component
          v-else-if="element.component == 'distance'"
          v-bind="{ distance: item[element.column], metric: '' }"
          v-bind:is="components['distance'].component"
        ></component>

        <component
          v-else-if="element.component == 'currency'"
          v-bind="{ amount: item[element.column] }"
          v-bind:is="components[element.component].component"
        ></component>

        <component
          v-else
          v-bind="{ text: item[element.column] }"
          v-bind:is="components[element.component].component"
        ></component>
      </router-link>
    </template>
  </tr>
</template>
<script>
const moment = require("moment");
import { mapGetters, mapActions } from "vuex";

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
  datetime: {
    name: "DateTime",
    component: {
      props: {
        date: {
          required: false,
        },
        time_zone: {
          required: false,
        },
      },
      computed: {
        parseDate() {
          return this.date == null || this.date == ""
            ? ""
            : this.$helpers.changeUTCtoLocalDate(
                this.date,
                "MM/DD/YYYY",
                this.time_zone
              );
        },
        parseTime() {
          return this.date == null || this.date == ""
            ? ""
            : this.$helpers.changeUTCtoLocalDate(
                this.date,
                "hh:mm A",
                this.time_zone
              );
        },
      },
      template: "<span>{{parseDate}} <br> {{parseTime}}</span>",
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
  distance: {
    name: "Distance",
    component: {
      props: {
        distance: {
          required: true,
        },
        metric: {
          required: false,
        },
      },
      template: "<span>{{distance}} {{metric}}</span>",
    },
  },
  currency: {
    name: "Currency",
    component: {
      props: {
        amount: {
          required: true,
        },
      },
      computed: {
        currency() {
          return this.amount == null || this.amount == ""
            ? ""
            : new Intl.NumberFormat("en-US", {
                style: "currency",
                currency: "USD",
              }).format(this.amount);
        },
      },
      template: "<span>{{currency}}</span>",
    },
  },
  phone: {
    name: "Phone",
    component: {
      props: {
        text: {
          required: false,
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
      template: `<span>
          <span v-if="text == 'Open'" class='badge badge-light-secondary'>{{text}}</span>
          <span v-else-if="text == 'In Progress'" class='badge badge-light-warning'>{{text}}</span>
          <span v-else-if="text == 'Completed'" class='badge badge-light-success'>{{text}}</span>
          <span  v-else-if="text == 'Lost'" class='badge badge-light-danger'>{{text}}</span>
          </span>`,
    },
  },
};
export default {
  name: "WorkOrderItem",
  props: {
    item: Object,
    childCheck: String,
    time_zone: String,
    allUsers: Array,
    app_metadata: { required: true },
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
    ...mapGetters("workOrder", ["getWorkOrderIds"]),
    ...mapGetters("workOrderView", ["list"]),
    advisorName() {
      return this.$helpers.assigneeName(
        this.allUsers,
        this.item.assigned_advisor
      );
    },
    autoSyncExpense() {
      return this.item.auto_sync_expense_flag == "1" ? "Yes" : "No";
    },
  },
  methods: {
    ...mapActions("workOrder", ["addEntityId", "removeEntityId"]),
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
        ? this.addEntityId(this.item.id)
        : this.removeEntityId(this.item.id);
    },
  },
};
</script>

<style scoped></style>
