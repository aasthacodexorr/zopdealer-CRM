<template>
  <tr :id="'social_connect_' + item.inventory_id">
    <td>
      <i class="bx bxs-edit pt-0 pl-1" @click="openInventoryNewTab(item.inventory_id)" style="font-size: 2em"></i>

      <button class="btn btn-outline-primary mt-0 mb-0" @click.prevent="socialpost(item.inventory_id, 'facebook')"
        v-if="item.facebook_status != ''"><i class='bx bxl-facebook-square'></i></button>

      <button class="btn btn-outline-danger mt-0 mb-0" @click.prevent="socialpost(item.inventory_id, 'instagram')"
        v-if="item.instagram_status != ''"><i class='bx bxl-instagram'></i></button>

        <button class="btn btn-outline-primary mt-0 mb-0" @click.prevent="socialpost(item.inventory_id,'google')"
        v-if="item.google_status != ''"><i class='bx bxl-google' ></i> </button>

    </td>
    <template v-for="element in list">
      <td class="text-center" style="cursor: pointer" v-if="element.visibility == '1'"
        :key="element.column + item.inventory_id">
        <component v-if="
          element.column == 'socialconnect_updated_at' ||
          element.column == 'socialconnect_created_at'
        " v-bind="{ date: item[element.column], time_zone: time_zone }" v-bind:is="components['datetime'].component">
        </component>
        <component v-else-if="element.component == 'date'" v-bind="{ date: item[element.column] }"
          v-bind:is="components[element.component].component"></component>
        <component v-else v-bind="{ text: item[element.column] }" v-bind:is="components[element.component].component">
        </component>
      </td>
    </template>
  </tr>
</template>
<script>
const moment = require("moment");
import { mapGetters, mapActions } from "vuex";
import Toast from "../../library/Toast";

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
        <span v-if="text" class='badge badge-success'>PUBLISHED</span>
        <span v-else class='badge badge-light-secondary'>UNPUBLISHED</span>
        </span>`,
    },
  },
};
export default {
  name: "SocialConnectItem",
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
    ...mapGetters("SocialConnectView", ["list"]),
  },
  methods: {
    ...mapActions("SocialConnectView", [
      'commitOnlyEntities'
    ]),
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
    socialpost(id, source) {
      const toast = new Toast(`Posting to ${source} ...`);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: `${window.location.origin}/api/business/socialconnect/postToSocial/${id}/${source}`,
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
          this.commitOnlyEntities();

        })
        .catch((error) => {
          let error_message = "There were some errors in posting feed.";
          if (error.response.data.hasOwnProperty('error')) {
            if (typeof (error.response.data.error) == 'string') {
              error_message = error.response.data.error;
            }
          }

          toast.fail(error_message);
          toast.close();
        });
    }

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
