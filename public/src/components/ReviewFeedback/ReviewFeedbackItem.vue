<template>
  <tr
    :id="'review_feedback_' + item.id"
  >
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
      <td
        style="cursor: pointer;"
        class="text-center"
        :key="element.header"
        v-if="element.visibility == '1'"
      >
        <component
          v-if="
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
          v-else-if="element.component == 'currency'"
          v-bind="{ amount: item[element.column] }"
          v-bind:is="components[element.component].component"
        ></component>

        <component
          v-else-if="element.component == 'rating'"
          v-bind="{ rating: item[element.column] }"
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
  uuid: {
    name: "UUID",
    component: {
      props: {
        text: {
          required: false,
        },
      },
      template:
        "<small style='max-width:200px;display:block;'>{{text}}</small>",
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
  badge: {
    name: "Badge",
    component: {
      props: {
        text: {
          required: true,
        },
      },
      template: `<span>
        <span v-if="text == 'Negative'" class='badge badge-light-danger'>{{text}}</span>
        <span v-else-if="text == 'Positive'" class='badge badge-light-success'>{{text}}</span>
        <span v-else-if="text == 'COMPLETED'" class='badge badge-light-success'>{{text}}</span>
        <span v-else-if="text == 'SEEN'" class='badge badge-light-warning'>{{text}}</span>
        <span v-else-if="text == 'SENT'" class='badge badge-light-secondary'>{{text}}</span>
        <span v-else class='badge badge-light-secondary'>{{text}}</span>
        </span>`,
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
};

export default {
  name: "ReviewFeedbackItem",
  props: {
    item: Object,
    childCheck: String,
    time_zone: String,
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
    ...mapGetters("reviewFeedback", ["getReviewFeedbackIds"]),
    ...mapGetters("reviewFeedbackView", ["list"]),
  },
  methods: {
    ...mapActions("reviewFeedback", ["addEntityId", "removeEntityId"]),
    profilePhotoURL(url) {
      return url
        ? url
        : "https://zopsoftware-asset.b-cdn.net/src/assets/images/avatar.jpg";
    },
    convertDate(published_date) {
      let date = moment(published_date);
      return date.isValid()
        ? this.$helpers.changeLocalDateToUTC(
            date,
            "MM/DD/YYYY",
            "Canada/Eastern"
          )
        : null;
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
        ? this.addEntityId(this.item.id)
        : this.removeEntityId(this.item.id);
    },
  },
};
</script>

<style scoped></style>
