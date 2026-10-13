<template>
  <tr :style="visibilityText === 'OFF' ? 'background:#e7e7e74f' : ''" :id="'review_' + item.id">
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
    <td>
      <div class="img-list-box">
        <img :src="profilePhotoURL(item.profile_photo_url)" alt="card" width="50" />
      </div>
    </td>
    <td colspan="3">
      <span class="blue-color text-bold-600">{{ item.name }}</span
      ><br />
      <span
        ><star-rating
          :inline="true"
          :star-size="14"
          :read-only="true"
          :show-rating="false"
          :rating="item.rating"
        ></star-rating>
        {{ formatedRating }}/5</span
      ><br>
      <small class="mt-50 font-weight-bold"><b><i class="bxs-time bx pr-1"></i> {{ convertDate(item.review_date_time) }}</b></small>
    </td>
    <td>
      <small class="">{{ item.comment }}</small>
    </td>
    <template v-for="element in list">
      <td
        style="cursor: pointer;"
        class="text-center"
        :key="element.header"
        v-if="element.visibility == '1'"
      >
        <component
          v-if="element.column == 'featured'"
          v-bind="{ text: featuredText }"
          v-bind:is="components['text'].component"
        ></component>

        <component
          v-else-if="element.column == 'visibility'"
          v-bind="{ text: visibilityText }"
          v-bind:is="components['text'].component"
        ></component>

        <component
          v-else-if="element.column == 'uuid'"
          v-bind="{ text: item[element.column] }"
          v-bind:is="components['uuid'].component"
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
import StarRating from "vue-star-rating";

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
  rating: {
    name: "Rating",
    component: {
      props: {
        rating: {
          required: false,
        },
      },
      computed: {
        formatedRating() {
          return Math.round(this.rating * 10) / 10;
        },
      },
      template: `<span>{{formatedRating}}</span>`,
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
  source: {
    name: "Source",
    component: {
      props: {
        text: {
          required: false,
        },
      },
      template: `<div class='avatar avatar-md'>
        <img v-if="text == 'GOOGLE'" :title="text" class="img-thumbnail" style="width:30px;height:30px;border:0px;" src="https://zopsoftware-asset.b-cdn.net/dist/app-assets/images/icon/google_cropped.png">
        <img  v-if="text == 'ZOP'" :title="text" src="https://zopsoftware-asset.b-cdn.net/dist/app-assets/images/logo/zop-logo.png" class="img-thumbnail" style="width:30px;height:30px;border:0px;">
        <img  v-if="text == 'FACEBOOK'" :title="text" src="https://zopsoftware-asset.b-cdn.net/dist/app-assets/images/icon/facebook.png" class="img-thumbnail" style="width:30px;height:30px;border:0px;">
              </div>`,
    },
  },
};

export default {
  name: "ReviewItem",
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
  components: {
    StarRating,
  },
  computed: {
    ...mapGetters("review", ["getReviewIds"]),
    ...mapGetters("reviewView", ["list"]),
    featuredText() {
      return this.item.featured == "1" ? "YES" : "NO";
    },
    visibilityText() {
      return this.item.visibility == "1" ? "ON" : "OFF";
    },
    formatedRating() {
      return Math.round(this.item.rating * 10) / 10;
    },
  },
  methods: {
    ...mapActions("review", ["addEntityId", "removeEntityId"]),
    profilePhotoURL(url){
      return url ? url : 'https://zopsoftware-asset.b-cdn.net/src/assets/images/avatar.jpg';
    },
    convertDate(published_date){
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
