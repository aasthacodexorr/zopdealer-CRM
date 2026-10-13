<template>
  <tr :id="'facebook_marketplace_' + item.inventory_id">
    <td class="left-space-box" style="cursor: pointer">
      <input
        class="select_check"
        @change="getCheckValue"
        type="checkbox"
        v-model="checkValue"
        :true-value="'1'"
        :false-value="'0'"
        :data="item.post_id"
        v-show="item.post_id"
      />
    </td>
    <td class="text-center">
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
            element.column == 'facebook_updated_at' ||
            element.column == 'facebook_created_at'
          "
          v-bind="{ date: item[element.column], time_zone: time_zone }"
          v-bind:is="components['datetime'].component"
        ></component>
        <component
          v-else-if="element.column == 'post_id'"
          v-bind="{ id: item[element.column] }"
          v-bind:is="components['facebook_url'].component"
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
  facebook_url: {
    name: "FacebookUrl",
    component: {
      props: {
        id: {
          required: false,
        },
      },
      computed: {
        completeUrl() {
          return this.id == null || this.id == ""
            ? ""
            : `https://www.facebook.com/marketplace/item/${this.id}`;
        },
      },
      template: `<span v-if="completeUrl" ><a target="_blank" :href="completeUrl"><i class='bx bx-link-external'></i></a> {{this.id}}</span>`,
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
      template: `<span>
        <span v-if="text == 'DELETED'" class='badge badge-light-danger'>{{text}}</span>
        <span v-else-if="text == 'DRAFT'" class='badge badge-light-warning'>{{text}}</span>
        <span v-else-if="text == 'PUBLISHED' || text == 'UPDATED'" class='badge badge-light-success'>{{text}}</span>
        <span v-else class='badge badge-light-secondary'>{{text}}</span>
        </span>`,
    },
  },
};
export default {
  name: "FacebookMarketPlaceItem",
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
        this.getCheckValue();
      },
    },
  },
  computed: {
    ...mapGetters("facebookMarketPlaceView", ["list"]),
  },
  methods: {
    ...mapActions("facebookMarketPlace", ["addPostId", "removePostId"]),
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
    getCheckValue() {
      this.checkValue == "1"
        ? this.addPostId(this.item.post_id)
        : this.removePostId(this.item.post_id);
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
