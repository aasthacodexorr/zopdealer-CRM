<template>
  <tr :id="'dvi_' + item.id">
    <td
      class="left-space-box"
      id="d-list"
      title="View snapshot of details"
      @click.prevent="showSnapshot()"
      style="cursor: pointer"
    >
      <i class="bx bx-search-alt"></i>
    </td>
    <template v-for="element in list">
      <router-link
        tag="td"
        :to="{
          name: 'dvi-update',
          params: { id: item.id },
        }"
        exact
        style="cursor: pointer"
        class="text-center"
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
            element.component == 'user' && element.column == 'assignee'
          "
          v-bind="{ text: assigneeName }"
          v-bind:is="components[element.component].component"
        ></component>
        <component
          v-else-if="element.column == 'issues_count'"
          v-bind="{ text: item[element.column] }"
          v-bind:is="components['numericBadge'].component"
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
        <span v-if='this.text == "INCOMPLETE"' class='badge badge-light-danger'>{{text}}</span>
        <span v-else-if='this.text == "ARCHIVE"' class='badge badge-light-warning'>{{text}}</span>
        <span v-else-if='this.text == "COMPLETE"' class='badge badge-light-success'>{{text}}</span>
        <span v-else class='badge badge-light-secondary'>{{text}}</span>
        </span>`,
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
  numericBadge: {
    name: "NumericBadge",
    component: {
      props: {
        text: {
          required: true,
        },
      },
      template: `<span>
        <span v-if="text > 0" class="badge badge-pill badge-danger text-bold">{{text}}</span>
        </span>`,
    },
  },
};
export default {
  name: "DVIItem",
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
    ...mapGetters("dVI", ["getIds"]),
    ...mapGetters("dVIView", ["list"]),
    assigneeName() {
      return this.$helpers.assigneeName(this.allUsers, this.item.assignee, "");
    },
  },
  methods: {
    ...mapActions("dVI", ["addId", "removeId"]),
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
    showSnapshot() {
      this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "snapshot",
        "dvi_snapshot",
        ""
      );

      Event.fire("change_selected_dvi_id_for_snapshot", this.item.id);
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
