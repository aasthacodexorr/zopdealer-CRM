<template>
  <tr :id="'product_' + item.id">
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
        style="cursor: pointer;"
        :to="{ name: 'product-update', params: { id: item.id } }"
        class="text-center"
        exact
        :key="element.header"
        v-if="element.visibility == '1'"
      >
        <component
          v-if="element.column == 'auto_update_quantity'"
          v-bind="{ text: enabledAutoUpdateQuantity }"
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
};
export default {
  name: "ProductItem",
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
    ...mapGetters("product", ["getProductIds"]),
    ...mapGetters("productView", ["list"]),
    enabledAutoUpdateQuantity() {
      return this.item.auto_update_quantity == "1" ? "Yes" : "No";
    },
  },
  methods: {
    ...mapActions("product", ["addEntityId", "removeEntityId"]),
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
