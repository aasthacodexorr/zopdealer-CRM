<template>
  <tr :id="'inventory_' + item.id">
    <template v-for="element in list">
      <td
        style="cursor: pointer;"
        class="text-center"
        exact
        :key="element.header"
        v-if="element.visibility == '1'"
      >
        <component
          v-if="element.column == 'feature_listing'"
          v-bind="{ text: featured }"
          v-bind:is="components['text'].component"
        ></component>
        <component
          v-else-if="element.column == 'visibility'"
          v-bind="{ text: visibility }"
          v-bind:is="components['text'].component"
        ></component>
        <component
          v-else-if="element.component == 'date'"
          v-bind="{ date: item[element.column] }"
          v-bind:is="components[element.component].component"
        ></component>
        <component
          v-else-if="element.column == 'odometer'"
          v-bind="{ distance: item.odometer, metric: item.km_miles }"
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
      </td>
    </template>
  </tr>
</template>
<script>
const moment = require("moment");
import { mapGetters, mapActions } from "vuex";

var components = {
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
  yes_or_no: {
    name: "YesNo",
    component: {
      props: {
        text: {
          required: false,
        },
      },
      computed: {
        booleanYesOrNo() {
          return this.text == null || this.text == "" || this.text == "0"
            ? "No"
            : "Yes";
        },
      },
      template: "<span>{{booleanYesOrNo}}</span>",
    },
  },
  yes_no_unselected: {
    name: "YesNo",
    component: {
      props: {
        text: {
          required: false,
        },
      },
      computed: {
        selectedValue() {
          switch (this.text) {
            case "1":
              return "Yes";

            case "2":
              return "No";

            default:
              return "";
              break;
          }
        },
      },
      template: "<span>{{selectedValue}}</span>",
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
        <span v-if="text == 'Sold'" class='badge badge-light-danger'>{{text}}</span>
        <span v-else-if="text == 'Archive'" class='badge badge-light-warning'>{{text}}</span>
        <span v-else-if="text == 'Unpublished'" class='badge badge-light-secondary'>{{text}}</span>
        <span v-else class='badge badge-light-success'>{{text}}</span>
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
  name: "InventoryReportItem",
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
  computed: {
    ...mapGetters("inventoryReportView", ["list"]),
    featured() {
      return this.item.feature_listing == "1" ? "Yes" : "No";
    },
    visibility() {
      return this.item.visibility == "0" ? "OFF" : "ON";
    },
  },
  methods: {
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
