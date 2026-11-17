<template>
  <tr
    :id="'inventory_' + item.inventory_id"
    class="inventory-table-row"
    :class="{ 'row-selected': isSelected, 'row-compact': isCompact }"
    @click="onRowClick"
  >
    <!-- 1. Selection Checkbox -->
    <td class="col-checkbox" @click.stop>
      <label class="custom-chk-container">
        <input
          class="custom-chk-input"
          @change="getCheckValue"
          type="checkbox"
          v-model="checkValue"
          :true-value="'1'"
          :false-value="'0'"
          :data="item.inventory_id"
        />
        <span class="custom-chk-box"></span>
      </label>
    </td>

    <!-- 2. Vehicle Photo Thumbnail -->
    <td class="col-photo">
      <div class="vehicle-thumbnail-wrapper">
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
      </div>
    </td>

    <!-- 3. Year -->
    <td class="col-year text-muted-dark">
      {{ item.year || '—' }}
    </td>

    <!-- 4. Make / Model (Show ONLY Make and Model in same line, normal font) -->
    <td class="col-make-model text-muted-dark">
      {{ makeModelDisplay }}
    </td>

    <!-- 5. VIN (Same font as stock) -->
    <td class="col-vin text-muted-dark" @click.stop="copyVin">
      <span class="vin-display-text" :title="'Click to copy: ' + item.vin">
        {{ item.vin || '—' }}
      </span>
    </td>

    <!-- 6. Stock # -->
    <td class="col-stock text-muted-dark">
      {{ item.stock_no || '—' }}
    </td>

    <!-- 7. Status Badge -->
    <td class="col-status">
      <span :class="['status-badge', statusBadgeClass]">
        {{ statusBadgeText }}
      </span>
    </td>

    <!-- 8. Price -->
    <td class="col-price font-weight-bold" @click.stop>
      <span>{{ formattedPrice }}</span>
    </td>

    <!-- 9. KMs (Odometer) -->
    <td class="col-kms text-muted-dark" @click.stop>
      <span>{{ formattedOdometer }}</span>
    </td>

    <!-- 10. Last Sync -->
    <td class="col-last-sync text-muted-sm">
      {{ formattedLastSync }}
    </td>

    <!-- 11. Website / VDP Link (Opens in new tab) -->
    <td class="col-action-btn text-center" @click.stop>
      <button
        type="button"
        class="table-icon-btn btn-vdp"
        title="Open VDP in new tab"
        @click="openInventoryNewTab(item.inventory_id)"
      >
        <i class="bx bx-link-external"></i>
      </button>
    </td>

    <!-- 12. CARFAX Button: Only visible if CARFAX data exists in backend and not in syndication view -->
    <td v-if="showCarfaxColumn" class="col-action-btn text-center" @click.stop>
      <button
        v-if="hasCarfaxReport"
        type="button"
        class="carfax-pill-badge"
        title="View CARFAX Report"
        @click="openCarfax"
      >
        CARFAX
      </button>
      <span v-else class="text-muted font-small-2">—</span>
    </td>

    <!-- 13. Repair Report Button -->
    <td class="col-action-btn text-center" @click.stop>
      <button
        type="button"
        class="table-icon-btn btn-repair-report"
        title="View Repair Report"
        @click="openRepairReport"
      >
        <i class="bx bxs-file-pdf"></i>
      </button>
    </td>

    <!-- Dynamic Syndication Columns if Syndication View is Active -->
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
    };
  },
  watch: {
    childCheck: {
      immediate: true,
      handler(val) {
        this.checkValue = String(val);
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
    formattedLastSync() {
      const dt = this.item.updated_at || this.item.created_at;
      if (!dt) return "—";
      try {
        return moment(dt).format("MMM D, h:mm A");
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
    onRowClick() {
      this.$emit("select-row", this.item);
    },
    copyVin() {
      if (this.item.vin) {
        this.$helpers.doCopy(this, this.item.vin);
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
      this.checkValue === "1"
        ? this.addInventoryId(this.item.inventory_id)
        : this.removeInventoryId(this.item.inventory_id);
    },
    openInventoryNewTab(id) {
      const routeData = this.$router.resolve({
        name: "inventory-update",
        params: { id: id },
      });
      window.open(routeData.href, "_blank");
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
  border-bottom: 1px solid #f3f4f6;

  &:hover {
    background-color: #f9fafb;
  }

  &.row-selected {
    background-color: #eff6ff !important;
  }

  td {
    padding: 0.45rem 0.75rem;
    vertical-align: middle;
    font-size: 0.9rem;
    line-height: 1.3;
    white-space: nowrap;
    border-bottom: 1px solid #eef1f5;
  }
}

/* Custom Checkbox */
.col-checkbox {
  width: 44px;
  min-width: 44px;
  max-width: 44px;
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
    height: 19px;
    width: 19px;
    background-color: #ffffff;
    border: 1.5px solid #d1d5db;
    border-radius: 4px;
    transition: all 0.15s ease;

    &:after {
      content: "";
      position: absolute;
      display: none;
      left: 6px;
      top: 2px;
      width: 5px;
      height: 10px;
      border: solid white;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg);
    }
  }

  &:hover .custom-chk-box {
    border-color: #9ca3af;
  }
}

/* Vehicle Thumbnail */
.col-photo {
  width: 58px;
  min-width: 58px;
}

.vehicle-thumbnail-wrapper {
  position: relative;
  width: 48px;
  height: 34px;
  border-radius: 4px;
  overflow: hidden;
  background: #f3f4f6;
  border: 1px solid #e5e7eb;
}

.vehicle-thumbnail {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.thumbnail-count-badge {
  position: absolute;
  bottom: 2px;
  right: 2px;
  background: rgba(17, 24, 39, 0.75);
  color: #ffffff;
  font-size: 9.5px;
  padding: 1px 4px;
  border-radius: 3px;
  line-height: 1;

  i {
    font-size: 8.5px;
    vertical-align: middle;
  }
}

.col-year {
  width: 70px;
  min-width: 70px;
  font-size: 0.95rem;
}

.col-make-model {
  min-width: 160px;
  font-size: 0.95rem;
  font-weight: 400;
  color: #1f2937;
  white-space: nowrap;
}

.col-vin {
  width: 150px;
  min-width: 150px;
  font-size: 0.95rem;
  cursor: pointer;

  .vin-display-text {
    font-size: 0.95rem;
    font-weight: 400;
    color: #4b5563;

    &:hover {
      color: #2563eb;
      text-decoration: underline;
    }
  }
}

.col-stock {
  width: 95px;
  min-width: 95px;
  font-size: 0.95rem;
  font-weight: 400;
  color: #4b5563;
}

.col-status {
  width: 110px;
  min-width: 110px;
}

.col-price {
  width: 105px;
  min-width: 105px;
  font-size: 0.95rem;
}

.col-kms {
  width: 110px;
  min-width: 110px;
  font-size: 0.95rem;
}

.col-last-sync {
  width: 125px;
  min-width: 125px;
  font-size: 0.85rem;
}

.col-action-btn {
  width: 50px;
  min-width: 50px;
  text-align: center;
}

.col-syndication-item {
  min-width: 110px;
}

/* Text styles */
.text-muted-dark {
  color: #374151;
  font-weight: 500;
}

.text-muted-sm {
  color: #6b7280;
}

/* Status Badges */
.status-badge {
  font-size: 11.5px;
  font-weight: 600;
  padding: 3px 9px;
  border-radius: 6px;
  display: inline-block;
  white-space: nowrap;
}

.badge-price-change {
  background: #fef3c7;
  color: #92400e;
  border: 1px solid #fde68a;
}

.badge-active {
  background: #dcfce7;
  color: #166534;
  border: 1px solid #bbf7d0;
}

.badge-sold {
  background: #fee2e2;
  color: #991b1b;
  border: 1px solid #fecaca;
}

.badge-pending {
  background: #e0f2fe;
  color: #0369a1;
  border: 1px solid #bae6fd;
}

.badge-not-advertised {
  background: #f1f5f9;
  color: #475569;
  border: 1px solid #e2e8f0;
}

/* Action Buttons */
.table-icon-btn {
  background: transparent;
  border: none;
  width: 32px;
  height: 32px;
  border-radius: 6px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.15s ease;
  padding: 0;

  &.btn-vdp {
    color: #2563eb;
    font-size: 17px;

    &:hover {
      background: #eff6ff;
      color: #1d4ed8;
    }
  }

  &.btn-repair-report {
    color: #ef4444;
    font-size: 17px;

    &:hover {
      background: #fee2e2;
      color: #dc2626;
    }
  }

  &.btn-more {
    color: #6b7280;
    font-size: 19px;

    &:hover {
      background: #f3f4f6;
      color: #111827;
    }
  }
}

.carfax-pill-badge {
  background: #111827;
  color: #ffffff;
  border: none;
  font-size: 10px;
  font-weight: 800;
  letter-spacing: 0.5px;
  padding: 3px 8px;
  border-radius: 4px;
  cursor: pointer;
  transition: opacity 0.15s ease;

  &:hover {
    opacity: 0.85;
  }
}

.dropdown-item {
  font-size: 13px;
  padding: 8px 14px;
  color: #374151;

  &:hover {
    background-color: #f9fafb;
    color: #111827;
  }
}

/* Compact Row Density */
tr.row-compact {
  td {
    padding: 4px 8px !important;
    font-size: 12.5px !important;
    line-height: 1.25 !important;
  }

  .vehicle-thumbnail-wrapper {
    width: 38px !important;
    height: 26px !important;
    border-radius: 4px !important;
  }

  .thumbnail-count-badge {
    font-size: 7.5px !important;
    padding: 0 2px !important;
  }

  .vehicle-make-title {
    font-size: 12.5px !important;
    line-height: 1.15 !important;
  }

  .vehicle-model-sub {
    font-size: 11.5px !important;
    line-height: 1.15 !important;
  }

  .col-vin .vin-display-text {
    font-size: 11.5px !important;
  }

  .status-badge {
    font-size: 10px !important;
    padding: 1px 6px !important;
  }

  .col-price {
    font-size: 12.5px !important;
  }

  .col-kms {
    font-size: 12px !important;
  }

  .col-last-sync {
    font-size: 11.5px !important;
  }

  .table-icon-btn {
    width: 25px !important;
    height: 25px !important;
    font-size: 14px !important;
  }

  .carfax-pill-badge {
    font-size: 8.5px !important;
    padding: 1px 5px !important;
  }
}
</style>
