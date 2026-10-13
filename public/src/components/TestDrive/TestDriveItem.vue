<template>
  <tr :id="'test_drive_'+ item.id">
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
          style="cursor: pointer;"
          :to="{ name: 'test-drive-detail', params: { id: item.id } }"
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
          v-else-if="element.component == 'user' && element.column == 'sales_rep_id' "
          v-bind="{ text: salespersonName }"
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
import {mapGetters, mapActions} from "vuex";

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
          var x = x
              .replace(/\D/g, "")
              .match(/(\d{0,3})(\d{0,3})(\d{0,4})/);

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
  name: "TestDriveItem",
  props: {
    item: Object,
    childCheck: [String], // @todo verify reason for character
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
      immediate: true,
      handler(val, oldVal) {
        this.checkValue = val;
        this.getCheckValue();
      },
    },
  },
  computed: {
    ...mapGetters("testDrive", ["getTestDriveIds"]),
    ...mapGetters("testDriveView", ["list"]),
    salespersonName() {
      return this.$helpers.assigneeName(this.allUsers, this.item.sales_rep_id, "");
    },    
  },
  methods: {
    ...mapActions("testDrive", [
      "addTestDriveId",
      "removeTestDriveId",
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
          ? this.addTestDriveId(this.item.id)
          : this.removeTestDriveId(this.item.id);
    },
  },
};
</script>

<style>
.phone-icon {
  color: #00c3ff;
  float: left;
}

.mail-icon {
  color: #1cd216;
  float: left;
}
</style>

