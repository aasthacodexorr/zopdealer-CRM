<template>
  <tr :id="'deal_' + item.id">
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
    <template v-for="element in list">
      <router-link
        tag="td"
        style="cursor: pointer"
        :to="{ name: 'deal-update', params: { id: item.id } }"
        class="text-center"
        exact
        :key="element.header"
        v-if="element.visibility == '1'"
      >
        <component
          v-if="element.component == 'date'"
          v-bind="{ date: item[element.column] }"
          v-bind:is="components[element.component].component"
        ></component>
        <component
          v-else-if="
            element.component == 'user' && element.column == 'approved_by'
          "
          v-bind="{ text: approverName }"
          v-bind:is="components[element.component].component"
        ></component>
        <component
          v-else-if="
            element.component == 'user' && element.column == 'salesperson'
          "
          v-bind="{ text: salespersonName }"
          v-bind:is="components[element.component].component"
        ></component>
        <component
          v-else-if="element.component == 'currency'"
          v-bind="{ amount: item[element.column] }"
          v-bind:is="components[element.component].component"
        ></component>
        <component
          v-else-if="element.column == 'odometer'"
          v-bind="{ distance: item.odometer, metric: item.km_miles }"
          v-bind:is="components['distance'].component"
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
        <span v-if='this.text == "Lost"' class='badge badge-light-danger'>{{text}}</span>
        <span v-else-if='this.text == "Cancelled"' class='badge badge-light-danger'>{{text}}</span>
        <span v-else-if='this.text == "Open"' class='badge badge-light-primary'>{{text}}</span>
        <span v-else-if='this.text == "Delivered"' class='badge badge-light-success'>{{text}}</span>
        <span v-else class='badge badge-light-secondary'>{{text}}</span>
        </span>`,
    },
  },
  rtl_wh_flag: {
    name: "RTL_WH_Flag",
    component: {
      props: {
        text: {
          required: false,
        },
      },
      computed: {
        RtlWh() {
          return this.text == "0" ? "Retail" : "Wholesale";
        },
      },
      template: "<span>{{RtlWh}}</span>",
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
};
export default {
  name: "DealItem",
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
    ...mapGetters("deal", ["getIds"]),
    ...mapGetters("dealView", ["list"]),
    approverName() {
      return this.$helpers.assigneeName(this.allUsers, this.item.approved_by, "");
    },
    salespersonName() {
      return this.$helpers.assigneeName(this.allUsers, this.item.salesperson, "");
    },
  },
  methods: {
    ...mapActions("deal", ["addId", "removeId"]),
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
        ? this.addId(this.item.id)
        : this.removeId(this.item.id);
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
