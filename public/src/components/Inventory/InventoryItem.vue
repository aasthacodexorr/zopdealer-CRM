<template>
  <tr :id="'inventory_' + item.inventory_id">
    <td class="left-space-box">
      <input
        class="select_check"
        @change="getCheckValue"
        type="checkbox"
        v-model="checkValue"
        :true-value="'1'"
        :false-value="'0'"
        :data="item.inventory_id"
      />
    </td>
    <td class="text-center">
      <p
        :class="getVisibilityIndicator"
        :title="getVisibilityTitle"
        class="rounded-circle round-box"
      ></p>
      <i
        v-show="app_metadata.organization == 'zopdealer'"
        class="bx bx-window-open pt-1"
        @click="openInventoryNewTab(item.inventory_id)"
      ></i>
      <i
        title="View snapshot of details"
        @click.prevent="showSnapshot()"
        style="cursor: pointer"
        class="bx bx-search-alt mt-25"
      ></i>
      <i
        class="bx bxs-purchase-tag-alt"
        title="Download Window Sticker"
        style="cursor: pointer"
        @click.prevent="windowSticker(item.inventory_id)"
      ></i>
    </td>
    <td>
      <div class="img-list-box">
        <img :src="placeholder" alt="card" width="75" /><span
          v-show="item.image_count > 0"
          class="image-count-list"
        >
          <i class="bx bxs-camera" style="vertical-align: bottom"></i
          >{{ item.image_count }}</span
        >
      </div>
    </td>
    <td class="d-none">{{ item.year }}</td>
    <td class="d-none">{{ item.make }}</td>
    <td class="d-none">{{ item.model }}</td>
    <td class="d-none">{{ item.stock_no }}</td>
    <td class="d-none">{{ item.vin }}</td>
    <td colspan="6">
      <router-link
        tag="span"
        style="cursor: pointer"
        :to="{ name: 'inventory-update', params: { id: item.inventory_id } }"
        class="blue-color text-bold-600 title-size-font"
        exact
      >
        {{ item.year }} {{ item.make }} {{ item.model }} {{ item.trim }}
      </router-link>
      <span class="pl-1 blue-color text-bold-400"></span><br /><span
        class="text-secondary text-bold-400"
        >{{ item.vin }}</span
      ><span class="pl-1 text-secondary text-bold-400">{{
        item.stock_no
      }}</span>
    </td>
    <td class="text-center">
      <span
        style="display: inline-block; width: 70px"
        class="text-white bg-green"
        title="Fresh Inventory"
        v-if="DIS <= 30"
        >{{ DIS }}</span
      >
      <span
        style="
          display: inline-block;
          width: 70px;
          background: #ffe165;
          color: black !important;
          font-weight: 800;
        "
        class="text-white bg-yellow"
        title="Current Inventory"
        v-if="DIS >= 31 && DIS <= 90"
        >{{ DIS }}</span
      >
      <span
        style="display: inline-block; width: 70px"
        class="text-white bg-red"
        title="Stale Inventory"
        v-if="DIS >= 91"
        >{{ DIS }}</span
      >
      <hr class="m-25" />
      <component
        v-bind="{ date: item.stock_date, time_zone: time_zone }"
        v-bind:is="components['date'].component"
      ></component>
    </td>
    <template v-for="element in list">
      <td
        class="text-center"
        :key="element.header"
        v-if="element.visibility == '1' && !getCurrentSyndicationViewStatus"
      >
        <component
          v-if="element.column == 'feature_listing'"
          v-bind="{ text: featured }"
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

        <inventory-item-odometer-component
          :distance="item.odometer"
          :metric="item.km_miles"
          :column="element.column"
          :stock_no="item.stock_no"
          v-else-if="element.column == 'odometer'"
        >
        </inventory-item-odometer-component>

        <inventory-item-currency-component
          v-else-if="
            element.column == 'price' || element.column == 'special_price'
          "
          :amount="item[element.column]"
          :column="element.column"
          :stock_no="item.stock_no"
        ></inventory-item-currency-component>

        <component
          v-else-if="element.component == 'currency'"
          v-bind="{ amount: item[element.column] }"
          v-bind:is="components[element.component].component"
        ></component>

        <component
          v-else-if="element.column == 'status'"
          v-bind="{
            text: item[element.column],
            secondary_text: item.sub_status,
          }"
          v-bind:is="components['doubleBadge'].component"
        ></component>

        <component
          v-else
          v-bind="{ text: item[element.column] }"
          v-bind:is="components[element.component].component"
        ></component>
      </td>
    </template>
    <template v-if="getCurrentSyndicationViewStatus">
      <td
        class="text-center"
        :key="`inventory_${active_syndication.slug}_${item.inventory_id}`"
        v-for="active_syndication in getActiveSyndicationList"
      >
        <span
          class="btn badge badge-light-success"
          style="cursor: pointer"
          role="button"
          :title="`${active_syndication.label} is ACTIVE for #${item.stock_no}`"
          v-if="isSyndicationActiveInInventory(active_syndication.slug)"
          @click="updateSyndication([active_syndication.slug], 'REMOVE')"
          >ACTIVE</span
        >
        <span
          class="btn badge badge-light-danger"
          style="cursor: pointer"
          @click="updateSyndication([active_syndication.slug], 'ADD')"
          :title="
            `${active_syndication.label} is INACTIVE for #${item.stock_no}`
          "
          v-else
        >
          INACTIVE</span
        >
      </td>
    </template>
  </tr>
</template>
<script>
const moment = require("moment");
import { mapGetters, mapActions } from "vuex";
import InventoryItemCurrencyComponent from "./InventoryItemCurrencyComponent.vue";
import InventoryItemOdometerComponent from "./InventoryItemOdometerComponent.vue";
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
            : moment(this.date).format("MM/DD/YYYY");
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
                this.time_zone,
              );
        },
        parseTime() {
          return this.date == null || this.date == ""
            ? ""
            : this.$helpers.changeUTCtoLocalDate(
                this.date,
                "hh:mm A",
                this.time_zone,
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
  doubleBadge: {
    name: "DoubleBadge",
    component: {
      props: {
        text: {
          required: true,
        },
        secondary_text: {
          required: false,
          default: "",
        },
      },
      template: `<span>
        <span v-if="text == 'Sold'" class='badge badge-light-danger'>{{text}}</span>
        <span v-else-if="text == 'Archive'" class='badge badge-light-warning'>{{text}}</span>
        <span v-else-if="text == 'Unpublished'" class='badge badge-light-secondary'>{{text}}</span>
        <span v-else-if="text == 'Instock'" class='badge badge-light-success'>{{text}}</span>
        <span v-else class='badge badge-light-secondary'>{{text}}</span>
        <hr class="m-25">
        <span style="font-size:0.7em;" class='badge badge-light-secondary'>{{secondary_text}}</span>
      </span>
        `,
    },
  },
};
export default {
  name: "InventoryItem",
  props: {
    item: Object,
    childCheck: String,
    placeholder_image: String,
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
    InventoryItemCurrencyComponent,
    InventoryItemOdometerComponent,
  },
  computed: {
    ...mapGetters("inventory", ["getInventoryIds"]),
    ...mapGetters("inventoryView", [
      "list",
      "getCurrentSyndicationViewStatus",
      "getActiveSyndicationList",
    ]),
    featured() {
      return this.item.feature_listing == "1" ? "Yes" : "No";
    },
    DIS() {
      return parseInt(this.item.days_in_stock, 10);
    },
    placeholder() {
      if (this.item.image_urls) {
        return this.$helpers.parseAbsoluteOrRelativeUrl(
          window.global.cdn,
          this.item.image_urls.split(";")[0],
        );
      }
      return this.placeholder_image;
    },
    getVisibilityIndicator() {
      let statuses = ["Instock", "Sold"];
      if (!statuses.includes(this.item.status)) {
        return "bg-secondary";
      } else if (this.item.visibility == "1") {
        return "bg-green";
      } else {
        return "bg-red";
      }
    },
    getVisibilityTitle() {
      let statuses = ["Instock", "Sold"];
      if (!statuses.includes(this.item.status)) {
        return "Hidden from website";
      } else if (this.item.visibility == "1") {
        return "Shown on website";
      } else {
        return "Hidden from website";
      }
    },
  },
  methods: {
    ...mapActions("inventory", ["addInventoryId", "removeInventoryId"]),
    ...mapActions("leadResponseView", [
      "commitSearchState",
      "commitSearchString",
      "commitLoadState",
    ]),
    isSyndicationActiveInInventory(slug) {
      if (!this.item) return false;

      if (this.$helpers.isValidJsonString(this.item.syndication)) {
        let syndication_list = JSON.parse(this.item.syndication);
        return syndication_list.includes(slug);
      }

      return false;
    },
    updateSyndication(slugs, action) {
      Event.fire("update_bulk_syndication", {
        slugs,
        inventory_ids: [this.item.inventory_id, this.item.inventory_id],
        action,
      });
    },
    async searchLeadResponseByStockNumber() {
      this.commitSearchState(true);
      this.commitSearchString(this.item.stock_no);
      this.commitLoadState(false);
      this.$router.push({ name: "lead-responses" });
    },
    windowSticker(inventory_id) {
      const vm = this;
      axios({
        method: "get",
        responseType: "blob",
        url: `${window.location.origin}/api/business/inventory/sticker/v2/${inventory_id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then((response) => {
          const file = new Blob([response.data], { type: "application/pdf" });
          const fileURL = URL.createObjectURL(file);
          window.open(fileURL);
        })
        .catch((response) => {
          let error = "There were some errors in retrieving the window sticker";
          console.log(error);
        });
    },
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
        ? this.addInventoryId(this.item.inventory_id)
        : this.removeInventoryId(this.item.inventory_id);
    },
    showSnapshot() {
      this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "snapshot",
        "inventory_snapshot",
        "",
      );

      Event.fire(
        "change_selected_inventory_id_for_snapshot",
        this.item.inventory_id,
      );
    },
  },
};
</script>

<style scoped></style>
