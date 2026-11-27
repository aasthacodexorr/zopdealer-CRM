<template>
  <tr
    :id="'inventory_' + item.inventory_id"
    class="inventory-table-row"
    :class="{ 'row-selected': isSelected || checkValue === '1', 'row-compact': isCompact }"
    @click="onRowClick"
  >
    <!-- 1. Selection Checkbox -->
    <td class="col-checkbox" @click.stop>
      <label class="custom-chk-container">
        <input
          class="custom-chk-input"
          @change="onCheckboxChange"
          type="checkbox"
          v-model="checkValue"
          :true-value="'1'"
          :false-value="'0'"
          :data="item.inventory_id"
        />
        <span class="custom-chk-box"></span>
      </label>
    </td>

    <!-- 2. Vehicle Photo Thumbnail with Eye-Catching Magnifier Quick-View Icon -->
    <td class="col-photo">
      <div
        class="vehicle-thumbnail-wrapper"
        @click.stop="openInventoryDetail(item.inventory_id)"
        title="Click to open vehicle details"
      >
        <img
          :src="thumbnailUrl"
          :alt="vehicleTitle"
          class="vehicle-thumbnail"
          loading="lazy"
          @error="onImageError"
        />
        <span v-if="item.image_count > 1" class="thumbnail-count-badge">
          <i class="bx bxs-camera"></i> {{ item.image_count }}
        </span>
        <div
          class="thumbnail-magnifier-btn"
          @click.stop="openPreview"
          title="Quick Snapshot"
        >
          <i class="bx bx-search-alt"></i>
        </div>
      </div>
    </td>

    <!-- 3. Combined Vehicle Identity: Year Make Model Trim, VIN • Stock # -->
    <td class="col-vehicle">
      <div
        class="vehicle-primary-title"
        :title="'Click to open vehicle details: ' + vehicleFullTitle"
        @click.stop="openInventoryDetail(item.inventory_id)"
      >
        {{ vehicleFullTitle }}
      </div>
      <div class="vehicle-sub-meta">
        <span class="vin-meta-wrap">
          <span
            class="vin-meta copyable-text"
            :title="'Click to copy VIN: ' + item.vin"
            @click.stop="copyVin"
          >
            VIN {{ item.vin || '—' }}
          </span>
          <button
            v-if="item.vin"
            type="button"
            class="zd-inline-copy-btn"
            title="Copy VIN"
            aria-label="Copy VIN"
            @click.stop="copyVin"
          >
            <i class="bx bx-copy"></i>
          </button>
        </span>
        <span class="meta-dot">•</span>
        <span class="stock-meta-wrap">
          <span
            class="stock-meta copyable-text"
            :title="'Click to copy Stock #: ' + item.stock_no"
            @click.stop="copyStockNo"
          >
            {{ item.stock_no || '—' }}
          </span>
          <button
            v-if="item.stock_no"
            type="button"
            class="zd-inline-copy-btn"
            title="Copy Stock #"
            aria-label="Copy Stock #"
            @click.stop="copyStockNo"
          >
            <i class="bx bx-copy"></i>
          </button>
        </span>
      </div>
    </td>

    <!-- Body Type (before DIS) -->
    <td
      v-if="!getCurrentSyndicationViewStatus"
      class="col-body-type text-left"
      :title="item.body_type || ''"
    >
      {{ item.body_type || '—' }}
    </td>

    <!-- 4. DIS (Days In Stock) -->
    <td class="col-dis text-left">
      {{ formattedDis }}
    </td>

    <!-- Regular columns: hidden while Syndication view is on (syndication columns then follow DIS) -->
    <template v-if="!getCurrentSyndicationViewStatus">
    <!-- 5. Odometer -->
    <td class="col-odometer text-left" @click.stop>
      <inventory-item-odometer-component
        :distance="item.odometer"
        :metric="item.km_miles || 'KM'"
        column="odometer"
        :stock_no="item.stock_no"
      ></inventory-item-odometer-component>
    </td>

    <!-- 6. Special Price -->
    <td class="col-special-price text-left font-weight-bold" @click.stop>
      <inventory-item-currency-component
        v-if="item.special_price && Number(item.special_price) > 0"
        :amount="item.special_price"
        column="special_price"
        :stock_no="item.stock_no"
      ></inventory-item-currency-component>
      <span v-else class="text-muted font-weight-normal">—</span>
    </td>

    <!-- 7. Price -->
    <td class="col-price text-left font-weight-bold" @click.stop>
      <inventory-item-currency-component
        :amount="item.price || item.selling_price || 0"
        column="price"
        :stock_no="item.stock_no"
      ></inventory-item-currency-component>
    </td>

    <!-- 8. Status Badge -->
    <td class="col-status text-center">
      <span :class="['status-badge', statusBadgeClass]">
        {{ statusBadgeText }}
      </span>
    </td>

    <!-- 12. Website / VDP Link (Opens dealer website VDP in new tab) -->
    <td class="col-action-btn text-center" @click.stop>
      <a
        v-if="vdpHref"
        :href="vdpHref"
        target="_blank"
        rel="noopener noreferrer"
        class="table-icon-btn btn-vdp"
        title="View on Website"
      >
        <i class="bx bx-link-external"></i>
      </a>
      <button
        v-else
        type="button"
        class="table-icon-btn btn-vdp"
        :disabled="vdp_loading"
        title="View on Website"
        @click="openWebsiteVdp"
      >
        <i :class="vdp_loading ? 'bx bx-loader-alt bx-spin' : 'bx bx-link-external'"></i>
      </button>
    </td>

    <!-- 15. Modified At -->
    <td class="col-modified-at text-muted-sm" :title="item.updated_at || item.modified_at || ''">
      <template v-if="formattedModifiedDate !== '—'">
        <div class="zd-date-line">{{ formattedModifiedDate }}</div>
        <div v-if="formattedModifiedTime" class="zd-time-line">{{ formattedModifiedTime }}</div>
      </template>
      <template v-else>—</template>
    </td>

    <!-- 9. Creation Date -->
    <td class="col-creation-date" :title="item.created_at || ''">
      <template v-if="formattedCreatedDate !== '—'">
        <div class="zd-date-line">{{ formattedCreatedDate }}</div>
        <div v-if="formattedCreatedTime" class="zd-time-line">{{ formattedCreatedTime }}</div>
      </template>
      <template v-else>—</template>
    </td>

    </template>

    <!-- 16. Dynamic Syndication Columns (Preserved if syndication view active) -->
    <template v-if="getCurrentSyndicationViewStatus">
      <td
        class="text-center col-syndication-item"
        :key="`inventory_${active_syndication.slug}_${item.inventory_id}`"
        v-for="active_syndication in getActiveSyndicationList"
        @click.stop
      >
        <span
          class="btn badge badge-light-success font-small-2"
          style="cursor: pointer"
          role="button"
          :title="`${active_syndication.label} is ACTIVE for #${item.stock_no}`"
          v-if="isSyndicationActiveInInventory(active_syndication.slug)"
          @click="updateSyndication([active_syndication.slug], 'REMOVE')"
        >
          ACTIVE
        </span>
        <span
          class="btn badge badge-light-danger font-small-2"
          style="cursor: pointer"
          @click="updateSyndication([active_syndication.slug], 'ADD')"
          :title="`${active_syndication.label} is INACTIVE for #${item.stock_no}`"
          v-else
        >
          INACTIVE
        </span>
      </td>
    </template>
  </tr>
</template>

<script>
const moment = require("moment");
import { mapGetters, mapActions } from "vuex";
import InventoryItemCurrencyComponent from "./InventoryItemCurrencyComponent.vue";
import InventoryItemOdometerComponent from "./InventoryItemOdometerComponent.vue";
import Toast from "../../library/Toast";

export default {
  name: "InventoryItem",
  props: {
    item: {
      type: Object,
      required: true,
    },
    childCheck: {
      type: [String, Number],
      default: "0",
    },
    placeholder_image: {
      type: String,
      default: "",
    },
    time_zone: {
      type: String,
      default: "Canada/Eastern",
    },
    app_metadata: {
      type: Object,
      required: true,
    },
    isSelected: {
      type: Boolean,
      default: false,
    },
    isCompact: {
      type: Boolean,
      default: false,
    },
    showCarfaxColumn: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      checkValue: "0",
      vdp_loading: false,
      resolved_page_url: "",
    };
  },
  watch: {
    childCheck: {
      immediate: true,
      handler(val) {
        this.checkValue = String(val);
        if (this.checkValue === "1") {
          this.addInventoryId(this.item.inventory_id);
        } else {
          this.removeInventoryId(this.item.inventory_id);
        }
      },
    },
    getInventoryIds: {
      immediate: true,
      handler(ids) {
        if (ids && ids.includes(this.item.inventory_id)) {
          this.checkValue = "1";
        } else if (!this.childCheck || this.childCheck === "0") {
          this.checkValue = "0";
        }
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
    vehicleTitle() {
      return `${this.item.year || ""} ${this.item.make || ""} ${this.item.model || ""}`.trim();
    },
    cleanModel() {
      return this.item.model ? String(this.item.model).trim() : "—";
    },
    makeModelDisplay() {
      const parts = [];
      if (this.item.make) parts.push(String(this.item.make).trim());
      if (this.item.model) parts.push(String(this.item.model).trim());
      return parts.join(" ") || "—";
    },
    hasCarfaxReport() {
      if (this.item.carfax_url) return true;
      if (this.item.carfax && this.item.carfax !== "0" && this.item.carfax !== 0) return true;
      if (this.item.has_carfax === 1 || this.item.has_carfax === "1") return true;
      if (this.item.files) {
        const lower = String(this.item.files).toLowerCase();
        if (lower.includes("carfax") || lower.includes("vehicle history report") || lower.includes("vhr")) {
          return true;
        }
      }
      return false;
    },
    formattedPrice() {
      const val = this.item.selling_price || this.item.price;
      if (val === null || val === undefined || val === "") return "$0.00";
      const num = Number(val);
      if (isNaN(num)) return String(val).startsWith("$") ? val : `$${val}`;
      return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
      }).format(num);
    },
    formattedOdometer() {
      const val = this.item.odometer;
      if (val === null || val === undefined || val === "") return "—";
      const num = Number(val);
      const metric = this.item.km_miles || "KM";
      if (isNaN(num)) return `${val} ${metric}`.trim();
      return `${new Intl.NumberFormat("en-US").format(num)} ${metric}`.trim();
    },
    thumbnailUrl() {
      const rawUrls = this.item.image_urls || this.item.image_url || this.item.image || "";
      if (rawUrls) {
        const parts = String(rawUrls).includes(";")
          ? String(rawUrls).split(";")
          : String(rawUrls).split(",");
        const cleanParts = parts.map((p) => p.trim()).filter(Boolean);
        // Show index 1 photo as requested by user; fallback to index 0
        const selected = cleanParts.length > 1 ? cleanParts[1] : cleanParts[0];
        if (selected) {
          const cdn = (window.global && window.global.cdn) ? window.global.cdn : "";
          return this.$helpers.parseAbsoluteOrRelativeUrl(cdn, selected);
        }
      }
      return this.placeholder_image || "/app-assets/images/slider/01.jpg";
    },
    statusBadgeText() {
      const status = this.item.status || "Active";
      const subStatus = this.item.sub_status;
      if (subStatus === "Deal Pending" || status === "Pending") return "Pending";
      if (status === "Instock") return "Active";
      if (status === "Sold") return "Sold";
      if (status === "Archive" || status === "Unpublished") return "Not Advertised";
      return status;
    },
    statusBadgeClass() {
      const text = this.statusBadgeText.toLowerCase();
      if (text.includes("price") || text.includes("change")) return "badge-price-change";
      if (text.includes("active") || text.includes("instock")) return "badge-active";
      if (text.includes("sold")) return "badge-sold";
      if (text.includes("pending")) return "badge-pending";
      if (text.includes("not advertised") || text.includes("archive") || text.includes("unpub")) return "badge-not-advertised";
      return "badge-active";
    },
    vehicleFullTitle() {
      const year = this.item.year || "";
      const make = this.item.make || "";
      const model = this.item.model || "";
      const trim = this.item.trim || "";
      return [year, make, model, trim].filter(Boolean).join(" ") || "Vehicle Details";
    },
    formattedOdometerNum() {
      const val = this.item.odometer;
      if (val === null || val === undefined || val === "") return "—";
      const num = Number(val);
      if (isNaN(num)) {
        const s = String(val).trim();
        return s.toUpperCase().endsWith("KM") ? s : `${s} KM`;
      }
      return `${num.toLocaleString("en-US")} KM`;
    },
    formattedDis() {
      if (this.item.days_in_stock !== null && this.item.days_in_stock !== undefined && this.item.days_in_stock !== "") {
        return this.item.days_in_stock;
      }
      const baseDate = this.item.stock_date || this.item.created_at;
      if (baseDate) {
        try {
          const diff = moment().diff(moment(baseDate), "days");
          return !isNaN(diff) && diff >= 0 ? diff : "—";
        } catch (e) {
          return "—";
        }
      }
      return "—";
    },
    vdpHref() {
      return this.resolved_page_url || (this.item && this.item.page_url) || "";
    },
    formattedCreatedDate() {
      const dt = this.item.created_at;
      if (!dt) return "—";
      try {
        const m = moment(dt);
        return m.isValid() ? m.format("MM/DD/YYYY") : dt;
      } catch (e) {
        return dt;
      }
    },
    formattedCreatedTime() {
      const dt = this.item.created_at;
      if (!dt || !String(dt).includes(":")) return "";
      try {
        const m = moment(dt);
        return m.isValid() ? m.format("hh:mm A") : "";
      } catch (e) {
        return "";
      }
    },
    formattedModifiedDate() {
      const dt = this.item.updated_at || this.item.modified_at;
      if (!dt) return "—";
      try {
        const m = moment(dt);
        return m.isValid() ? m.format("MM/DD/YYYY") : dt;
      } catch (e) {
        return dt;
      }
    },
    formattedModifiedTime() {
      const dt = this.item.updated_at || this.item.modified_at;
      if (!dt || !String(dt).includes(":")) return "";
      try {
        const m = moment(dt);
        return m.isValid() ? m.format("hh:mm A") : "";
      } catch (e) {
        return "";
      }
    },
    lastSyncDate() {
      const dt = this.item.updated_at || this.item.created_at;
      if (!dt) return "Sep 24,";
      try {
        return moment(dt).format("MMM D,");
      } catch (e) {
        return dt;
      }
    },
    lastSyncTime() {
      const dt = this.item.updated_at || this.item.created_at;
      if (!dt) return "10:12 AM";
      try {
        return moment(dt).format("h:mm A");
      } catch (e) {
        return "";
      }
    },
    formattedLastSync() {
      const dt = this.item.updated_at || this.item.created_at;
      if (!dt) return "—";
      try {
        return moment(dt).format("MMM D, YYYY");
      } catch (e) {
        return dt;
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
    onCheckboxChange() {
      if (this.checkValue === "1") {
        this.addInventoryId(this.item.inventory_id);
      } else {
        this.removeInventoryId(this.item.inventory_id);
      }
    },
    openPreview(e) {
      if (e) {
        e.stopPropagation();
      }
      this.$emit("open-preview", this.item);
    },
    onRowClick(e) {
      if (
        e &&
        e.target &&
        e.target.closest(
          "button, a, select, input, .custom-chk-container, .inventory-inline-editable, .copyable-text, .thumbnail-magnifier-btn"
        )
      ) {
        return;
      }
      this.openInventoryDetail(this.item.inventory_id);
    },
    copyVin() {
      if (this.item.vin) {
        this.$helpers.doCopy(this, this.item.vin);
      }
    },
    copyStockNo() {
      if (this.item.stock_no) {
        this.$helpers.doCopy(this, this.item.stock_no);
      }
    },
    onImageError(e) {
      const fallback = this.placeholder_image || "/app-assets/images/slider/01.jpg";
      const cdn = (window.global && window.global.cdn) ? window.global.cdn : "";
      const rawUrls = this.item.image_urls || this.item.image_url || this.item.image || "";
      if (rawUrls) {
        const parts = String(rawUrls).includes(";")
          ? String(rawUrls).split(";")
          : String(rawUrls).split(",");
        const cleanParts = parts.map((p) => p.trim()).filter(Boolean);
        // If index 1 failed, try index 0 as fallback before default placeholder
        if (cleanParts[0]) {
          const index0Url = this.$helpers.parseAbsoluteOrRelativeUrl(cdn, cleanParts[0]);
          if (e.target.src !== index0Url && e.target.src !== fallback) {
            e.target.src = index0Url;
            return;
          }
        }
      }
      if (e.target.src !== fallback) {
        e.target.src = fallback;
      }
    },
    getCheckValue() {
      this.onCheckboxChange();
    },
    // Row, photo and title open the vehicle detail page in the same tab.
    openInventoryDetail(id) {
      this.$router.push({ name: "inventory-update", params: { id: id } });
    },
    openInventoryNewTab(id) {
      const routeData = this.$router.resolve({
        name: "inventory-update",
        params: { id: id },
      });
      window.open(routeData.href, "_blank");
    },
    async openWebsiteVdp() {
      const directUrl = this.vdpHref;
      if (directUrl) {
        window.open(directUrl, "_blank", "noopener,noreferrer");
        return;
      }
      if (!this.item || !this.item.inventory_id || this.vdp_loading) return;
      this.vdp_loading = true;
      try {
        const { data } = await axios({
          method: "get",
          url: `${window.location.origin}/api/business/inventory/show/${this.item.inventory_id}`,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
        });
        if (data && data.page_url) {
          this.resolved_page_url = data.page_url;
          window.open(data.page_url, "_blank", "noopener,noreferrer");
          return;
        }
      } catch (e) {}
      finally {
        this.vdp_loading = false;
      }
      const toast = new Toast("Website URL is not available for this vehicle.");
      toast.fail("Website URL is not available for this vehicle.");
      toast.close();
    },
    async openCarfax() {
      if (this.item.carfax_url) {
        window.open(this.item.carfax_url, "_blank");
        return;
      }
      try {
        const { data } = await axios.get(
          `${window.location.origin}/api/business/inventory/file/${this.item.inventory_id}`,
          { headers: { Authorization: "Bearer " + window.$cookies.get("_token") } }
        );
        if (data && data.files) {
          const f = data.files.find((file) =>
            file.header && (file.header.toLowerCase().includes("carfax") || file.header.toLowerCase().includes("history"))
          );
          if (f && f.file_url) {
            window.open(this.$helpers.parseAbsoluteOrRelativeUrl(window.global.cdn, f.file_url), "_blank");
            return;
          }
        }
      } catch (e) {}

      this.$swal({
        showConfirmButton: false,
        icon: "info",
        toast: true,
        timer: 3000,
        position: "bottom",
        title: "No Carfax report document attached for this vehicle.",
      });
    },
    async openRepairReport() {
      try {
        const { data } = await axios.get(
          `${window.location.origin}/api/business/inventory/file/${this.item.inventory_id}`,
          { headers: { Authorization: "Bearer " + window.$cookies.get("_token") } }
        );
        if (data && data.files) {
          const f = data.files.find((file) =>
            file.header && (file.header.toLowerCase().includes("repair") || file.header.toLowerCase().includes("inspection"))
          );
          if (f && f.file_url) {
            window.open(this.$helpers.parseAbsoluteOrRelativeUrl(window.global.cdn, f.file_url), "_blank");
            return;
          }
        }
      } catch (e) {}

      this.$swal({
        showConfirmButton: false,
        icon: "info",
        toast: true,
        timer: 3000,
        position: "bottom",
        title: "No Repair Report document attached for this vehicle.",
      });
    },
    windowSticker(inventory_id) {
      const toast = new Toast("Retrieving Window Sticker...");
      toast.fire();
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
          window.open(fileURL, "_blank");
          toast.close();
        })
        .catch(() => {
          toast.fail("Unable to retrieve window sticker.");
        });
    },
    searchLeadResponseByStockNumber() {
      this.commitSearchState(true);
      this.commitSearchString(this.item.stock_no);
      this.commitLoadState(false);
      this.$router.push({ name: "lead-responses" });
    },
    showSnapshot() {
      Event.fire("change_selected_inventory_id_for_snapshot", this.item.inventory_id);
    },
    isSyndicationActiveInInventory(slug) {
      if (!this.item) return false;
      if (this.$helpers.isValidJsonString(this.item.syndication)) {
        const syndication_list = JSON.parse(this.item.syndication);
        return syndication_list.includes(slug);
      }
      return false;
    },
    updateSyndication(slugs, action) {
      Event.fire("update_bulk_syndication", {
        slugs,
        inventory_ids: [this.item.inventory_id],
        action,
      });
    },
  },
};
</script>

<style scoped lang="scss">
.inventory-table-row {
  cursor: pointer;
  transition: background-color 0.15s ease;
  background-color: #ffffff;
  border-bottom: 1px solid #f1f5f9;

  &:hover {
    background-color: #f8fafc;
  }

  &.row-selected {
    background-color: #eff6ff !important;
  }

  td {
    padding: 0.25rem 0.5rem;
    vertical-align: middle;
    font-size: 0.8125rem;
    line-height: 1.3;
    white-space: nowrap;
    border-bottom: 1px solid #f1f5f9;
  }
}

/* Custom Checkbox */
.col-checkbox {
  width: 36px;
  min-width: 36px;
  max-width: 36px;
  text-align: center;
}

.custom-chk-container {
  display: inline-flex;
  position: relative;
  cursor: pointer;
  user-select: none;
  margin: 0;

  .custom-chk-input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;

    &:checked ~ .custom-chk-box {
      background-color: #2563eb;
      border-color: #2563eb;

      &:after {
        display: block;
      }
    }
  }

  .custom-chk-box {
    position: relative;
    display: inline-block;
    height: 15px;
    width: 15px;
    background-color: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 3px;
    transition: all 0.15s ease;

    &:after {
      content: "";
      position: absolute;
      display: none;
      left: 4.5px;
      top: 1.5px;
      width: 4px;
      height: 8px;
      border: solid white;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg);
    }
  }

  &:hover .custom-chk-box {
    border-color: #94a3b8;
  }
}

.vin-meta-wrap,
.stock-meta-wrap {
  display: inline-flex;
  align-items: center;
  gap: 3px;
}

.zd-inline-copy-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 18px;
  height: 18px;
  padding: 0;
  border: none;
  border-radius: 4px;
  background: transparent;
  color: #94a3b8;
  cursor: pointer;
  line-height: 1;
  transition: color 0.15s ease, background-color 0.15s ease;

  i {
    font-size: 12.5px;
  }

  &:hover {
    color: #2563eb;
    background: #eff6ff;
  }
}

/* Vehicle Thumbnail */
.col-photo {
  width: 80px;
  min-width: 80px;
}

.vehicle-thumbnail-wrapper {
  position: relative;
  width: 68px;
  height: 46px;
  border-radius: 8px;
  overflow: hidden;
  background: #f1f5f9;
  border: 1px solid #e2e8f0;
  box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;

  &::after {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.04);
    transition: background 0.2s ease;
    pointer-events: none;
    z-index: 1;
  }

  &:hover {
    border-color: #2563eb;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);

    &::after {
      background: rgba(15, 23, 42, 0.36);
    }

    .vehicle-thumbnail {
      transform: scale(1.06);
    }

    .thumbnail-magnifier-btn {
      opacity: 1;
      pointer-events: auto;
      transform: translate(-50%, -50%) scale(1);
    }
  }
}

.vehicle-thumbnail {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transition: transform 0.25s ease;
}

.thumbnail-count-badge {
  position: absolute;
  bottom: 3px;
  right: 3px;
  background: rgba(15, 23, 42, 0.82);
  color: #ffffff;
  font-size: 9.5px;
  font-weight: 600;
  padding: 2px 5px;
  border-radius: 4px;
  line-height: 1;
  display: inline-flex;
  align-items: center;
  gap: 2px;
  z-index: 3;

  i {
    font-size: 8.5px;
    vertical-align: middle;
  }
}

.thumbnail-magnifier-btn {
  position: absolute;
  top: 50%;
  left: 50%;
  right: auto;
  transform: translate(-50%, -50%) scale(0.75);
  transform-origin: center center;
  width: 26px;
  height: 26px;
  border-radius: 50%;
  background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
  border: 1.5px solid #ffffff;
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 3px 10px rgba(37, 99, 235, 0.55), 0 1px 3px rgba(0, 0, 0, 0.25);
  cursor: pointer;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.18s ease, transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), background 0.18s ease, box-shadow 0.18s ease;
  z-index: 4;

  i {
    font-size: 13px;
    line-height: 1;
    color: #ffffff !important;
    position: static !important;
    top: 0 !important;
  }

  &:hover {
    transform: translate(-50%, -50%) scale(1.1);
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.75), 0 1px 3px rgba(0, 0, 0, 0.3);
  }
}

/* Combined Vehicle Identity Column */
.col-vehicle {
  width: 300px;
  min-width: 300px;
  max-width: 300px;
  padding: 8px 12px;

  .vehicle-primary-title {
    font-size: 13.5px;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.3;
    white-space: normal;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    cursor: pointer;
    transition: color 0.15s ease;

    &:hover {
      color: #2563eb;
    }
  }

  .vehicle-sub-meta {
    font-size: 11.5px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 2px;
  }

  .meta-dot {
    color: #94a3b8;
    font-weight: bold;
    margin: 0 2px;
  }

  .vin-meta {
    cursor: pointer;

    &:hover {
      color: #2563eb;
      text-decoration: underline;
    }
  }

  .stock-meta {
    color: #64748b;
  }
}

.col-year {
  width: 60px;
  min-width: 60px;
  font-size: 0.8125rem;
}

.col-body-type {
  width: 95px;
  min-width: 95px;
  max-width: 120px;
  font-size: 12.5px;
  color: #334155;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.col-dis {
  width: 55px;
  min-width: 55px;
  font-size: 13px;
  font-weight: 500;
  color: #1e293b;
  text-align: left;
}

.col-special-price {
  width: 105px;
  min-width: 105px;
  font-size: 13px;
  font-weight: 600;
  color: #1e293b;
  text-align: left;
}

.col-price {
  width: 105px;
  min-width: 105px;
  font-size: 13px;
  font-weight: 600;
  color: #1e293b;
  text-align: left;
}

.col-odometer,
.col-kms {
  width: 110px;
  min-width: 110px;
  font-size: 13px;
  color: #1e293b;
  text-align: left;
}

.col-status {
  width: 110px;
  min-width: 110px;
}

.col-created-at,
.col-creation-date {
  width: 105px;
  min-width: 105px;
  font-size: 12.5px;
  color: #334155;
}

.col-modified-at {
  width: 105px;
  min-width: 105px;
  font-size: 12px;
  color: #64748b;
}

.col-creation-date,
.col-modified-at {
  line-height: 1.35;
  .zd-date-line {
    font-size: 12.5px;
    font-weight: 500;
    color: #334155;
    white-space: nowrap;
  }
  .zd-time-line {
    font-size: 11px;
    color: #64748b;
    white-space: nowrap;
  }
}

.col-last-sync {
  width: 100px;
  min-width: 100px;

  .sync-date-line {
    font-size: 12px;
    color: #334155;
    line-height: 1.25;
  }

  .sync-time-line {
    font-size: 11px;
    color: #64748b;
    line-height: 1.25;
  }
}

.col-action-btn {
  width: 52px;
  min-width: 52px;
  text-align: center;
}

.col-syndication-item {
  min-width: 100px;
}

/* Status Badges */
.status-badge {
  font-size: 11px;
  font-weight: 600;
  padding: 3px 10px;
  border-radius: 9999px;
  display: inline-block;
  white-space: nowrap;
  line-height: 1.25;
}

.badge-price-change {
  background: #fef3c7;
  color: #92400e;
  border: 1px solid #fde68a;
}

.badge-active {
  background: #dcfce7;
  color: #15803d;
  border: 1px solid #bbf7d0;
}

.badge-sold {
  background: #fee2e2;
  color: #991b1b;
  border: 1px solid #fecaca;
}

.badge-pending {
  background: #fef9c3;
  color: #854d0e;
  border: 1px solid #fef08a;
}

.badge-not-advertised {
  background: #f3e8ff;
  color: #6b21a8;
  border: 1px solid #e9d5ff;
}

/* Action Buttons */
.table-icon-btn {
  border-radius: 4px;
  width: 28px;
  height: 28px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.15s ease;
  padding: 0;

  &.btn-vdp {
    color: #0284c7;
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    font-size: 15px;

    &:hover {
      background: #e0f2fe;
      border-color: #7dd3fc;
      color: #0369a1;
    }
  }

  &.btn-repair-report {
    color: #dc2626;
    background: #fef2f2;
    border: 1px solid #fecaca;
    font-size: 15px;

    &:hover {
      background: #fee2e2;
      border-color: #fca5a5;
      color: #b91c1c;
    }
  }
}

.carfax-pill-badge {
  background: #ffffff;
  color: #0f172a;
  border: 1.5px solid #0f172a;
  border-radius: 4px;
  font-size: 10px;
  font-weight: 800;
  letter-spacing: 0.5px;
  padding: 2px 6px;
  cursor: pointer;
  transition: all 0.15s ease;
  display: inline-block;

  &:hover {
    background: #0f172a;
    color: #ffffff;
  }
}

.dropdown-item {
  font-size: 12.5px;
  padding: 6px 12px;
  color: #334155;

  &:hover {
    background-color: #f8fafc;
    color: #0f172a;
  }
}

/* Compact Row Density */
tr.row-compact {
  td {
    padding: 0.15rem 0.4rem !important;
    font-size: 0.775rem !important;
    line-height: 1.2 !important;
  }

  .vehicle-thumbnail-wrapper {
    width: 48px !important;
    height: 32px !important;
    border-radius: 6px !important;
  }

  .thumbnail-magnifier-btn {
    width: 20px !important;
    height: 20px !important;

    i {
      font-size: 11px !important;
    }
  }

  .thumbnail-count-badge {
    font-size: 7px !important;
    padding: 0 2px !important;
  }

  .col-make-model {
    font-size: 0.775rem !important;

    .vehicle-trim-subtext {
      font-size: 0.675rem !important;
    }
  }

  .col-vin .vin-display-text {
    font-size: 0.775rem !important;
  }

  .status-badge {
    font-size: 9.5px !important;
    padding: 1px 5px !important;
  }

  .col-price {
    font-size: 0.775rem !important;
  }

  .col-odometer,
  .col-kms {
    font-size: 0.775rem !important;
  }

  .col-created-at,
  .col-modified-at,
  .col-last-sync {
    font-size: 0.725rem !important;
  }

  .table-icon-btn {
    width: 24px !important;
    height: 24px !important;
    font-size: 13px !important;
  }

  .carfax-pill-badge {
    font-size: 8px !important;
    padding: 1px 4px !important;
  }
}
</style>
