<template>
  <tr :id="'craigslist_' + item.inventory_id">
    <td class="text-center" style="cursor: pointer">
      <i
        class="bx bxs-edit pt-1 pl-1"
        @click="openInventoryNewTab(item.inventory_id)"
        style="font-size: 2em"
      ></i>
    </td>
    <template v-for="element in list">
      <td
        class="text-center"
        style="cursor: pointer"
        v-if="element.visibility == '1'"
        :key="item[element.column] + item.inventory_id + element.column"
      >
        <component
          v-if="
            element.column == 'craigslist_updated_at' ||
              element.column == 'craigslist_created_at'
          "
          v-bind="{ date: item[element.column], time_zone: time_zone }"
          v-bind:is="components['datetime'].component"
        ></component>
        <component
          v-else-if="element.column == 'post_id'"
          v-bind="{ id: item[element.column], url: item['craigslist_url'] }"
          v-bind:is="components['craigslist_url'].component"
        ></component>
        <component
          v-else-if="element.component == 'date'"
          v-bind="{ date: item[element.column] }"
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
        parseDates() {
          return this.date == null || this.date == ""
            ? ""
            : this.date
                .split(",")
                .map((current_date) =>
                  this.$helpers.changeUTCtoLocalDate(
                    current_date,
                    "MM/DD/YYYY",
                    this.time_zone
                  )
                );
        },
        parseTimes() {
          return this.date == null || this.date == ""
            ? ""
            : this.date
                .split(",")
                .map((current_date) =>
                  this.$helpers.changeUTCtoLocalDate(
                    current_date,
                    "hh:mm A",
                    this.time_zone
                  )
                );
        }
      },
      template: `<span style="font-size:0.8em;"><span v-for="(current_date,index) in parseDates">{{current_date}}<br>{{parseTimes[index]}}<br></span></span>`,
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
  text: {
    name: "Text",
    component: {
      props: {
        text: {
          required: false,
        },
      },
      computed: {
        allPascalText() {
          return this.text == null || this.text == ""
            ? ""
            : this.text.split(",").map(current_text => current_text[0].toUpperCase() + current_text.substring(1));
        },
      },
      template: `<span><span v-for="pascal_case in allPascalText">{{pascal_case}}<br></span></span>`,
    },
  },
  craigslist_url: {
    name: "CraigslistUrl",
    component: {
      props: {
        id: {
          required: false,
        },
        url: {
          required: false,
        },
      },
      computed: {
        allIDs() {
          return this.id == null || this.id == "" ? "" : this.id.split(",");
        },
        allUrls() {
          return this.url == null || this.url == "" ? "" : this.url.split(",");
        },
      },
      template: `<span><span v-if="allUrls" :key="url" v-for="(url,index) in allUrls" ><a target="_blank" :href="url"><i class='bx bx-link-external'></i></a> {{allIDs[index]}}<br></span></span>`,
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
      computed: {
        badges() {
          return this.text == null || this.text == ""
            ? ""
            : this.text.split(",");
        },
      },
      template: `<span><span v-for="badge in badges">
        <span v-if="badge == 'DELETED'" class='badge badge-light-danger mb-1'>{{badge}}</span>
        <span v-else-if="badge == 'DRAFT'" class='badge badge-light-warning mb-1'>{{badge}}</span>
        <span v-else-if="badge == 'PUBLISHED' || badge == 'UPDATED'" class='badge badge-light-success mb-1'>{{badge}}</span>
        <span v-else class='badge badge-light-secondary mb-1'>{{badge}}</span>
        <br>
        </span></span>`,
    },
  },
};
export default {
  name: "CraigslistItem",
  props: {
    item: Object,
    childCheck: String,
    time_zone: String,
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
      },
    },
  },
  computed: {
    ...mapGetters("craigslistView", ["list"]),
  },
  methods: {
    openInventoryNewTab(id) {
      let routeData = this.$router.resolve({
        name: "inventory-update",
        params: { id: id },
      });
      window.open(routeData.href, "_blank");
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
