<template>
  <div class="inventory-preview-panel shadow-sm">
    <!-- Vue Easy Lightbox for full image expansion -->
    <vue-easy-lightbox
      escDisabled
      moveDisabled
      :visible="lightboxVisible"
      :imgs="galleryImageUrls"
      :index="currentImageIndex"
      @hide="lightboxVisible = false"
    ></vue-easy-lightbox>

    <!-- Left Floating Toggle/Close Sidebar Handle (matches Image) -->
    <a
      class="preview-sidebar-toggle"
      role="button"
      title="Close Sidebar"
      @click="$emit('close')"
    >
      <i class="bx bx-chevrons-right text-white"></i>
    </a>

    <!-- Top Floating Close Button -->
    <button
      type="button"
      class="preview-close-btn"
      title="Close Preview"
      @click="$emit('close')"
    >
      <i class="bx bx-x"></i>
    </button>

    <!-- Scrollable Panel Body -->
    <div class="preview-scroll-container">
      <!-- 1. Vehicle Image Gallery -->
      <div class="preview-gallery-container">
        <div class="preview-image-wrapper">
          <img
            :src="currentImageUrl"
            :alt="vehicleTitle"
            class="preview-main-image"
            @click="lightboxVisible = true"
            @error="handleImageError"
          />

          <!-- Navigation Chevrons -->
          <button
            v-if="galleryImageUrls.length > 1"
            type="button"
            class="gallery-nav-btn gallery-nav-prev"
            title="Previous Image"
            @click.stop="prevImage"
          >
            <i class="bx bx-chevron-left"></i>
          </button>
          <button
            v-if="galleryImageUrls.length > 1"
            type="button"
            class="gallery-nav-btn gallery-nav-next"
            title="Next Image"
            @click.stop="nextImage"
          >
            <i class="bx bx-chevron-right"></i>
          </button>

          <!-- Counter Badge -->
          <div class="gallery-counter-badge">
            # {{ currentImageIndex + 1 }}/{{ totalImagesCount }}
          </div>
        </div>
      </div>

      <!-- 2. Vehicle Information Header -->
      <div class="preview-header-section">
        <h2 class="preview-vehicle-title" :title="vehicleTitle">
          {{ vehicleTitle }}
        </h2>

        <div class="preview-meta-row">
          <span class="preview-stock-no">
            Stock #: <strong>{{ displayStockNo }}</strong>
          </span>

          <span :class="['preview-status-badge', statusBadgeClass]">
            {{ displayStatusText }}
          </span>

          <button
            type="button"
            class="preview-website-btn"
            title="Open VDP in new tab"
            @click.stop="openVdpInNewTab"
          >
            <span>View on Website</span>
            <i class="bx bx-link-external ml-1"></i>
          </button>
        </div>
      </div>

      <!-- 3. Preview Tabs Navigation & Content -->
      <div class="preview-tabs-container">
        <div class="preview-tab-layout">
          <!-- Left Tab Navigation Sidebar -->
          <div class="preview-tabs-sidebar">
            <button
              type="button"
              class="preview-tab-item"
              :class="{ active: activeTab === 'overview' }"
              @click="activeTab = 'overview'"
            >
              Overview
            </button>
            <button
              type="button"
              class="preview-tab-item"
              :class="{ active: activeTab === 'attachments' }"
              @click="activeTab = 'attachments'"
            >
              Attachments
              <span v-if="files.length > 0" class="tab-count-badge">{{ files.length }}</span>
            </button>
            <button
              type="button"
              class="preview-tab-item"
              :class="{ active: activeTab === 'disclosures' }"
              @click="activeTab = 'disclosures'"
            >
              Disclosures
            </button>
            <button
              type="button"
              class="preview-tab-item"
              :class="{ active: activeTab === 'additional' }"
              @click="activeTab = 'additional'"
            >
              Additional Info
            </button>
            <button
              type="button"
              class="preview-tab-item"
              :class="{ active: activeTab === 'history' }"
              @click="activeTab = 'history'"
            >
              History
              <span v-if="deals.length + all_notes.length > 0" class="tab-count-badge">
                {{ deals.length + all_notes.length }}
              </span>
            </button>
          </div>

          <!-- Right Content Area For Active Tab -->
          <div class="preview-tab-content-area">
            <!-- TAB: Overview -->
            <div v-show="activeTab === 'overview'" class="tab-pane-content">
              <h5 class="preview-tab-heading">Overview</h5>
              <div class="preview-key-value-list">
                <div class="preview-kv-row">
                  <span class="preview-kv-label">Exterior Colour</span>
                  <span class="preview-kv-value">{{ displayExteriorColor }}</span>
                </div>

                <div class="preview-kv-row">
                  <span class="preview-kv-label">Interior Colour</span>
                  <span class="preview-kv-value">{{ displayInteriorColor }}</span>
                </div>

                <div class="preview-kv-row">
                  <span class="preview-kv-label">Engine</span>
                  <span class="preview-kv-value">{{ displayEngine }}</span>
                </div>

                <div class="preview-kv-row">
                  <span class="preview-kv-label">Transmission</span>
                  <span class="preview-kv-value">{{ displayTransmission }}</span>
                </div>

                <div class="preview-kv-row">
                  <span class="preview-kv-label">Drivetrain</span>
                  <span class="preview-kv-value">{{ displayDrivetrain }}</span>
                </div>

                <div class="preview-kv-row">
                  <span class="preview-kv-label">Fuel Type</span>
                  <span class="preview-kv-value">{{ displayFuelType }}</span>
                </div>

                <div class="preview-kv-row">
                  <span class="preview-kv-label">Fuel Economy</span>
                  <span class="preview-kv-value">{{ displayFuelEconomy }}</span>
                </div>

                <div class="preview-kv-row">
                  <span class="preview-kv-label">Doors / Seats</span>
                  <span class="preview-kv-value">{{ displayDoorsSeats }}</span>
                </div>

                <div class="preview-kv-row" v-if="displayLocation">
                  <span class="preview-kv-label">Location</span>
                  <span class="preview-kv-value">{{ displayLocation }}</span>
                </div>

                <div class="preview-kv-row">
                  <span class="preview-kv-label">Feed ID</span>
                  <span class="preview-kv-value">{{ displayFeedId }}</span>
                </div>

                <div class="preview-kv-row">
                  <span class="preview-kv-label">Last Sync</span>
                  <span class="preview-kv-value">{{ displayLastSync }}</span>
                </div>
              </div>
            </div>

            <!-- TAB: Attachments -->
            <div v-show="activeTab === 'attachments'" class="tab-pane-content">
              <div v-if="files.length > 0" class="preview-attachments-list">
                <div
                  v-for="file in files"
                  :key="file.id + '_attach'"
                  class="preview-attachment-card"
                >
                  <div class="preview-attachment-info">
                    <i class="bx bxs-file-pdf attachment-icon"></i>
                    <span class="attachment-name">{{ file.header || 'Document' }}</span>
                  </div>
                  <a
                    :href="parseAbsoluteOrRelativeUrl(file.file_url)"
                    target="_blank"
                    class="attachment-view-link"
                    title="Open Document"
                  >
                    View <i class="bx bx-link-external"></i>
                  </a>
                </div>
              </div>
              <div v-else class="preview-empty-state">
                <i class="bx bx-paperclip empty-icon"></i>
                <p>No attachments available for this vehicle.</p>
              </div>
            </div>

            <!-- TAB: Disclosures -->
            <div v-show="activeTab === 'disclosures'" class="tab-pane-content">
              <div class="preview-key-value-list">
                <div class="preview-kv-row">
                  <span class="preview-kv-label">Brand</span>
                  <span class="preview-kv-value">{{ disclosures.brand || 'No' }}</span>
                </div>
                <div class="preview-kv-row">
                  <span class="preview-kv-label">Certified</span>
                  <span class="preview-kv-value">{{ disclosures.certified || 'No' }}</span>
                </div>
                <div class="preview-kv-row">
                  <span class="preview-kv-label">Rebuilt Status</span>
                  <span class="preview-kv-value">{{ disclosures.rebuilt_status || 'No' }}</span>
                </div>
                <div class="preview-kv-row">
                  <span class="preview-kv-label">Accident History</span>
                  <span class="preview-kv-value">{{ disclosures.accident_history || 'No' }}</span>
                </div>
                <div class="preview-kv-row">
                  <span class="preview-kv-label">Lien Status</span>
                  <span class="preview-kv-value">{{ disclosures.lien_status || 'Clear' }}</span>
                </div>
                <div v-if="disclosures.complete_disclosure" class="mt-2">
                  <label class="preview-kv-label d-block mb-1">Complete Disclosure</label>
                  <p class="preview-disclosure-text">{{ disclosures.complete_disclosure }}</p>
                </div>
              </div>
            </div>

            <!-- TAB: Additional Info -->
            <div v-show="activeTab === 'additional'" class="tab-pane-content">
              <div class="preview-key-value-list">
                <div class="preview-kv-row" v-if="formData.vehicle_type || item.vehicle_type">
                  <span class="preview-kv-label">Vehicle Type</span>
                  <span class="preview-kv-value">{{ formData.vehicle_type || item.vehicle_type }}</span>
                </div>
                <div class="preview-kv-row" v-if="formData.fuel_type">
                  <span class="preview-kv-label">Fuel Type</span>
                  <span class="preview-kv-value">{{ formData.fuel_type }}</span>
                </div>
                <div class="preview-kv-row" v-if="formData.door">
                  <span class="preview-kv-label">Doors</span>
                  <span class="preview-kv-value">{{ formData.door }}</span>
                </div>
                <div class="preview-kv-row" v-if="formData.passengers">
                  <span class="preview-kv-label">Passengers</span>
                  <span class="preview-kv-value">{{ formData.passengers }}</span>
                </div>
                <div class="preview-kv-row" v-if="formData.stock_date || item.stock_date">
                  <span class="preview-kv-label">Stock Date</span>
                  <span class="preview-kv-value">{{ formData.stock_date || item.stock_date }}</span>
                </div>
                <div class="preview-kv-row" v-if="item.days_in_stock != null">
                  <span class="preview-kv-label">Days in Stock</span>
                  <span class="preview-kv-value">{{ item.days_in_stock }}</span>
                </div>
                <div class="preview-kv-row" v-if="formData.location || item.location">
                  <span class="preview-kv-label">Location</span>
                  <span class="preview-kv-value">{{ formData.location || item.location }}</span>
                </div>
                <div class="preview-kv-row">
                  <span class="preview-kv-label">Website Visibility</span>
                  <span class="preview-kv-value">{{ item.visibility == '1' ? 'Visible (ON)' : 'Hidden (OFF)' }}</span>
                </div>
              </div>
            </div>

            <!-- TAB: History -->
            <div v-show="activeTab === 'history'" class="tab-pane-content">
              <!-- Deals Section -->
              <h5 class="preview-sub-title mb-1">Deals ({{ deals.length }})</h5>
              <div v-if="deals.length > 0" class="preview-history-list mb-2">
                <div v-for="deal in deals" :key="deal.id" class="preview-history-card">
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold">
                      {{ getDealCustomerName(deal) }}
                    </span>
                    <span class="badge badge-light-primary">{{ deal.status }}</span>
                  </div>
                  <div class="d-flex justify-content-between text-muted small mt-1">
                    <span>{{ deal.type }}</span>
                    <span>{{ formatDate(deal.date) }}</span>
                  </div>
                </div>
              </div>
              <div v-else class="preview-empty-state mb-2">
                <p>No deals recorded for this vehicle.</p>
              </div>

              <!-- Notes Section -->
              <h5 class="preview-sub-title mb-1">Notes ({{ all_notes.length }})</h5>
              <div v-if="all_notes.length > 0" class="preview-history-list">
                <div v-for="note in all_notes" :key="note.id" class="preview-history-card">
                  <div class="font-weight-bold text-dark">{{ note.title }}</div>
                  <p v-if="note.description" class="small text-muted mb-1">{{ note.description }}</p>
                  <div class="d-flex justify-content-between text-muted small">
                    <span>{{ formatDateTime(note.updated_at) }}</span>
                  </div>
                </div>
              </div>
              <div v-else class="preview-empty-state">
                <p>No notes for this vehicle.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 4. Section: Documents & Links -->
      <div class="preview-section-card">
        <h4 class="preview-section-title">Documents & Links</h4>

        <div class="preview-doc-row">
          <div class="preview-doc-left">
            <i class="bx bx-globe preview-doc-icon text-primary"></i>
            <span class="preview-doc-name">Website / VDP</span>
          </div>
          <button
            type="button"
            class="preview-doc-view-link"
            @click="openVdpInNewTab"
          >
            View <i class="bx bx-link-external ml-50"></i>
          </button>
        </div>

        <div class="preview-doc-row">
          <div class="preview-doc-left">
            <span class="preview-carfax-logo">CARFAX</span>
            <span class="preview-doc-name ml-1">CARFAX History Report</span>
          </div>
          <button
            type="button"
            class="preview-doc-view-link"
            @click="openCarfaxReport"
          >
            View <i class="bx bx-link-external ml-50"></i>
          </button>
        </div>

        <div class="preview-doc-row">
          <div class="preview-doc-left">
            <i class="bx bxs-file-pdf preview-doc-icon text-danger"></i>
            <span class="preview-doc-name">Repair Report</span>
          </div>
          <button
            type="button"
            class="preview-doc-view-link"
            @click="openRepairReport"
          >
            View <i class="bx bx-link-external ml-50"></i>
          </button>
        </div>

        <div class="preview-doc-row">
          <div class="preview-doc-left">
            <i class="bx bxs-purchase-tag-alt preview-doc-icon text-warning"></i>
            <span class="preview-doc-name">Window Sticker</span>
          </div>
          <button
            type="button"
            class="preview-doc-view-link"
            @click="downloadWindowSticker"
          >
            View <i class="bx bx-link-external ml-50"></i>
          </button>
        </div>
      </div>

      <!-- 5. Section: Disclosures & Notes -->
      <div class="preview-section-card">
        <h4 class="preview-section-title">Disclosures & Notes</h4>

        <div class="preview-disclosure-row">
          <div class="preview-disclosure-left">
            <i class="bx bx-shield-quarter text-secondary preview-disc-icon"></i>
            <span class="preview-disc-label">Rebuilt Status</span>
          </div>
          <span class="preview-disc-value">
            {{ disclosures.rebuilt_status || 'No' }}
          </span>
        </div>

        <div class="preview-disclosure-row">
          <div class="preview-disclosure-left">
            <i class="bx bx-file text-secondary preview-disc-icon"></i>
            <span class="preview-disc-label">Accident History</span>
          </div>
          <span class="preview-disc-value">
            {{ disclosures.accident_history || 'No' }}
          </span>
        </div>

        <div class="preview-disclosure-row">
          <div class="preview-disclosure-left">
            <i class="bx bx-link text-secondary preview-disc-icon"></i>
            <span class="preview-disc-label">Lien Status</span>
          </div>
          <span class="preview-disc-value">
            {{ disclosures.lien_status || 'Clear' }}
          </span>
        </div>

        <div class="preview-disclosure-row">
          <div class="preview-disclosure-left">
            <i class="bx bx-info-circle text-secondary preview-disc-icon"></i>
            <span class="preview-disc-label">Additional Notes</span>
          </div>
          <span class="preview-disc-value">
            {{ latestNotePreview }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const moment = require("moment");
import VueEasyLightbox from "vue-easy-lightbox";
import Toast from "../../library/Toast";

export default {
  name: "InventoryPreviewPanel",
  components: {
    VueEasyLightbox,
  },
  props: {
    item: {
      type: Object,
      required: true,
    },
    app_metadata: {
      type: Object,
      required: false,
      default: () => ({}),
    },
    time_zone: {
      type: String,
      required: false,
      default: "Canada/Eastern",
    },
    placeholder_image: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      activeTab: "overview",
      currentImageIndex: 0,
      lightboxVisible: false,
      galleryList: [],
      files: [],
      deals: [],
      all_notes: [],
      disclosures: {
        brand: "",
        certified: "",
        rebuilt_status: "No",
        accident_history: "No",
        lien_status: "Clear",
        complete_disclosure: "",
      },
      formData: {
        vin: "",
        year: "",
        make: "",
        model: "",
        trim: "",
        body_type: "",
        exterior_color: "",
        interior_color: "",
        engine: "",
        transmission: "",
        drivetrain: "",
        odometer: "",
        km_miles: "KM",
        price: "",
        selling_price_numeric: "",
        status: "",
        sub_status: "",
        stock_no: "",
        stock_date: "",
        vehicle_type: "",
        door: "",
        passengers: "",
        fuel_type: "",
        location: "",
        page_url: "",
        updated_at: "",
        feed_id: "",
      },
      loadingDetails: false,
    };
  },
  computed: {
    vehicleTitle() {
      const year = this.displayYear;
      const make = this.displayMake;
      const model = this.displayModel;
      return `${year} ${make} ${model}`.trim() || "Vehicle Details";
    },
    displayYear() {
      return this.formData.year || this.item.year || "";
    },
    displayMake() {
      return this.formData.make || this.item.make || "";
    },
    displayModel() {
      return this.formData.model || this.item.model || "";
    },
    displayTrim() {
      return this.formData.trim || this.item.trim || "";
    },
    displayStockNo() {
      return this.formData.stock_no || this.item.stock_no || "—";
    },
    displayVin() {
      return this.formData.vin || this.item.vin || "—";
    },
    displayBodyType() {
      return this.formData.body_type || this.item.body_type || "";
    },
    displayExteriorColor() {
      return this.formData.exterior_color || this.item.exterior_color || "Polar White";
    },
    displayInteriorColor() {
      return this.formData.interior_color || this.item.interior_color || "Black";
    },
    displayEngine() {
      const eng = this.formData.engine || this.item.engine || "";
      const lit = this.formData.litres || this.item.litres || "";
      if (eng && lit && !String(eng).includes(String(lit))) {
        return `${lit} ${eng}`;
      }
      return eng || lit || "2.0L Turbo 4-Cylinder";
    },
    displayTransmission() {
      return this.formData.transmission || this.item.transmission || "9G-TRONIC Automatic";
    },
    displayDrivetrain() {
      return this.formData.drivetrain || this.item.drivetrain || "4MATIC AWD";
    },
    displayFuelType() {
      return this.formData.fuel_type || this.item.fuel_type || "Gasoline";
    },
    displayFuelEconomy() {
      const city = this.formData.fuel_city || this.item.fuel_city;
      const hwy = this.formData.fuel_hwy || this.item.fuel_hwy;
      if (city && hwy) {
        return `${city} / ${hwy} L/100km`;
      }
      if (this.formData.fuel_economy) return this.formData.fuel_economy;
      if (this.item.fuel_economy) return this.item.fuel_economy;
      return "10.8 / 8.5 L/100km";
    },
    displayDoorsSeats() {
      const doors = this.formData.door || this.item.door;
      const passengers = this.formData.passengers || this.item.passengers;
      if (doors && passengers) {
        return `${doors} Doors / ${passengers} Seats`;
      }
      if (doors) return `${doors} Doors`;
      if (passengers) return `${passengers} Seats`;
      return "4 Doors / 5 Seats";
    },
    displayLocation() {
      return this.formData.location || this.item.location || "";
    },
    displayOdometer() {
      const odo = this.formData.odometer != null && this.formData.odometer !== "" 
        ? this.formData.odometer 
        : this.item.odometer;
      if (odo == null || odo === "") return "—";
      const metric = this.formData.km_miles || this.item.km_miles || "KM";
      const num = Number(odo);
      return !isNaN(num) ? `${num.toLocaleString()} ${metric}` : `${odo} ${metric}`;
    },
    displayPrice() {
      const p = this.formData.selling_price_numeric || this.formData.price || this.item.price || this.item.selling_price;
      if (p == null || p === "") return "—";
      const num = Number(p);
      if (isNaN(num)) return p;
      return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
        maximumFractionDigits: 0,
      }).format(num);
    },
    displayLastSync() {
      const dt = this.formData.updated_at || this.item.updated_at || this.item.created_at;
      if (!dt) return "Sep 24, 2026 10:12 AM";
      try {
        return moment(dt).format("MMM D, YYYY h:mm A");
      } catch (e) {
        return dt;
      }
    },
    displayFeedId() {
      return this.item.feed_id || (this.item.inventory_id ? `AT-${this.item.inventory_id}` : "AT-789456");
    },
    displayStatusText() {
      const status = this.formData.status || this.item.status || "Active";
      const subStatus = this.formData.sub_status || this.item.sub_status;
      if (subStatus === "Deal Pending" || status === "Pending") return "Pending";
      if (status === "Instock") return "Active";
      if (status === "Sold") return "Sold";
      if (status === "Archive" || status === "Unpublished") return "Not Advertised";
      return status;
    },
    statusBadgeClass() {
      const text = this.displayStatusText.toLowerCase();
      if (text.includes("price") || text.includes("change")) return "badge-price-change";
      if (text.includes("active") || text.includes("instock")) return "badge-active";
      if (text.includes("sold")) return "badge-sold";
      if (text.includes("pending")) return "badge-pending";
      if (text.includes("not advertised") || text.includes("archive") || text.includes("unpub")) return "badge-not-advertised";
      return "badge-active";
    },
    galleryImageUrls() {
      const urls = [];
      // 1. From fetched gallery list
      if (this.galleryList && this.galleryList.length > 0) {
        this.galleryList.forEach((g) => {
          if (g && (g.active == null || g.active === "1" || g.active === 1)) {
            const url = g.image_url || g.url;
            if (url) {
              urls.push(this.$helpers.parseAbsoluteOrRelativeUrl(window.global.cdn, url));
            }
          }
        });
      }
      // 2. From item image_urls string
      if (urls.length === 0 && this.item.image_urls) {
        const parts = this.item.image_urls.split(";");
        parts.forEach((p) => {
          const trimmed = p.trim();
          if (trimmed) {
            urls.push(this.$helpers.parseAbsoluteOrRelativeUrl(window.global.cdn, trimmed));
          }
        });
      }
      // 3. Fallback placeholder
      if (urls.length === 0) {
        if (this.placeholder_image) {
          urls.push(this.placeholder_image);
        } else {
          urls.push("/app-assets/images/slider/01.jpg");
        }
      }
      return urls;
    },
    currentImageUrl() {
      if (this.galleryImageUrls.length > 0) {
        const safeIdx = Math.min(this.currentImageIndex, this.galleryImageUrls.length - 1);
        return this.galleryImageUrls[safeIdx] || this.galleryImageUrls[0];
      }
      return this.placeholder_image || "/app-assets/images/slider/01.jpg";
    },
    totalImagesCount() {
      return this.galleryImageUrls.length;
    },
    latestNotePreview() {
      if (this.all_notes && this.all_notes.length > 0) {
        return this.$helpers.truncate(this.all_notes[0].title || this.all_notes[0].description, 25, "...");
      }
      return "—";
    },
  },
  watch: {
    item: {
      immediate: true,
      handler(newItem) {
        if (newItem && newItem.inventory_id) {
          this.initVehicleData(newItem);
          this.loadAllVehicleDetails(newItem.inventory_id);
        }
      },
    },
  },
  methods: {
    initVehicleData(item) {
      this.currentImageIndex = (item && item.image_count > 1) ? 1 : 0;
      // Pre-fill immediately with known row data to prevent blank state
      for (const key in this.formData) {
        if (item[key] !== undefined) {
          this.formData[key] = item[key];
        }
      }
    },
    prevImage() {
      if (this.galleryImageUrls.length <= 1) return;
      if (this.currentImageIndex > 0) {
        this.currentImageIndex--;
      } else {
        this.currentImageIndex = this.galleryImageUrls.length - 1;
      }
    },
    nextImage() {
      if (this.galleryImageUrls.length <= 1) return;
      if (this.currentImageIndex < this.galleryImageUrls.length - 1) {
        this.currentImageIndex++;
      } else {
        this.currentImageIndex = 0;
      }
    },
    handleImageError(e) {
      const fallback = this.placeholder_image || "/app-assets/images/slider/01.jpg";
      if (e.target.src !== fallback) {
        e.target.src = fallback;
      }
    },
    doCopy(text) {
      if (!text || text === "—") return;
      this.$helpers.doCopy(this, text);
    },
    openVdpInNewTab() {
      this.$emit("open-vdp", this.item.inventory_id);
    },
    async openCarfaxReport() {
      // Find Carfax in files
      const carfaxFile = this.files.find((f) => 
        f.header && (f.header.toLowerCase().includes("carfax") || f.header.toLowerCase().includes("history"))
      );
      if (carfaxFile && carfaxFile.file_url) {
        window.open(this.parseAbsoluteOrRelativeUrl(carfaxFile.file_url), "_blank");
        return;
      }
      // Check if item has carfax_url
      if (this.item.carfax_url) {
        window.open(this.item.carfax_url, "_blank");
        return;
      }
      // Or show helpful notification
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
      const repairFile = this.files.find((f) => 
        f.header && (f.header.toLowerCase().includes("repair") || f.header.toLowerCase().includes("inspection"))
      );
      if (repairFile && repairFile.file_url) {
        window.open(this.parseAbsoluteOrRelativeUrl(repairFile.file_url), "_blank");
        return;
      }
      this.$swal({
        showConfirmButton: false,
        icon: "info",
        toast: true,
        timer: 3000,
        position: "bottom",
        title: "No Repair Report document attached for this vehicle.",
      });
    },
    downloadWindowSticker() {
      const inventory_id = this.item.inventory_id;
      const toast = new Toast("Downloading Window Sticker...");
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
    parseAbsoluteOrRelativeUrl(file_url) {
      return this.$helpers.parseAbsoluteOrRelativeUrl(window.global.cdn, file_url);
    },
    formatDate(d) {
      if (!d) return "";
      return moment(d).isValid() ? moment(d).format("MM/DD/YYYY") : d;
    },
    formatDateTime(dt) {
      if (!dt) return "";
      return moment(dt).isValid() ? moment(dt).format("MM/DD/YYYY h:mm A") : dt;
    },
    getDealCustomerName(deal) {
      if (deal && deal.customers && deal.customers.length > 0) {
        const cust = deal.customers[0];
        if (cust && cust.details) {
          return `${cust.details.first_name || ""} ${cust.details.last_name || ""}`.trim() || "Customer";
        }
      }
      return "Customer";
    },
    async loadAllVehicleDetails(id) {
      this.loadingDetails = true;
      const token = window.$cookies.get("_token");
      const authHeader = { Authorization: "Bearer " + token };

      // 1. Fetch Show Details
      axios.get(`${window.location.origin}/api/business/inventory/show/${id}`, { headers: authHeader })
        .then(({ data }) => {
          if (data) {
            for (const key in this.formData) {
              if (data[key] !== undefined && data[key] !== null) {
                this.formData[key] = data[key];
              }
            }
          }
        })
        .catch(() => {});

      // 2. Fetch Gallery Images
      axios.get(`${window.location.origin}/api/business/inventory/image/${id}`, { headers: authHeader })
        .then(({ data }) => {
          if (data && data.gallery) {
            this.galleryList = data.gallery;
          }
        })
        .catch(() => {});

      // 3. Fetch Files / Attachments
      axios.get(`${window.location.origin}/api/business/inventory/file/${id}`, { headers: authHeader })
        .then(({ data }) => {
          if (data && data.files) {
            this.files = data.files;
          }
        })
        .catch(() => {});

      // 4. Fetch Disclosures
      axios.get(`${window.location.origin}/api/business/inventory/${id}/common/disclosures`, { headers: authHeader })
        .then(({ data }) => {
          if (Array.isArray(data)) {
            data.forEach((elem) => {
              if (elem && elem.question && elem.answer) {
                const k = elem.question.toLowerCase().replace(/ /g, "_");
                if (this.disclosures[k] !== undefined) {
                  this.disclosures[k] = elem.answer;
                }
              }
            });
          }
        })
        .catch(() => {});

      // 5. Fetch Notes
      axios.get(`${window.location.origin}/api/business/inventory/${id}/notes`, { headers: authHeader })
        .then(({ data }) => {
          if (data && data.all_notes) {
            this.all_notes = data.all_notes;
          }
        })
        .catch(() => {});

      // 6. Fetch Deals
      axios.get(`${window.location.origin}/api/business/inventory/${id}/deals`, { headers: authHeader })
        .then(({ data }) => {
          if (data && data.deals) {
            this.deals = data.deals;
          }
        })
        .catch(() => {})
        .finally(() => {
          this.loadingDetails = false;
        });
    },
  },
};
</script>

<style scoped lang="scss">
.inventory-preview-panel {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  width: 420px;
  max-width: 90vw;
  height: 100vh;
  max-height: 100vh;
  z-index: 1051;
  background: #ffffff;
  border: none;
  border-left: 1px solid #e2e8f0;
  border-radius: 0;
  display: flex;
  flex-direction: column;
  overflow: visible;
  box-shadow: -5px 0 25px rgba(0, 0, 0, 0.12);
  font-family: inherit;
}

.preview-sidebar-toggle {
  position: absolute;
  top: 50%;
  left: -38px;
  transform: translateY(-50%);
  width: 38px;
  height: 38px;
  background-color: #5a8dee;
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  border-top-left-radius: 6px;
  border-bottom-left-radius: 6px;
  box-shadow: -3px 0 8px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  z-index: 10;
  text-decoration: none;

  i {
    font-size: 24px;
    line-height: 1;
    color: #ffffff;
  }

  &:hover {
    background-color: #4676d7;
  }
}

.preview-close-btn {
  position: absolute;
  top: 14px;
  right: 14px;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.9);
  border: 1px solid #e5e7eb;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  color: #4b5563;
  cursor: pointer;
  z-index: 10;
  transition: all 0.15s ease;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);

  &:hover {
    background: #ffffff;
    color: #111827;
    transform: scale(1.05);
  }
}

.preview-scroll-container {
  flex: 1;
  overflow-y: auto;
  padding: 16px;

  /* Custom slim scrollbar */
  &::-webkit-scrollbar {
    width: 6px;
  }
  &::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
  }
  &::-webkit-scrollbar-track {
    background: transparent;
  }
}

/* 1. Gallery */
.preview-gallery-container {
  width: 100%;
  margin-bottom: 16px;
}

.preview-image-wrapper {
  position: relative;
  width: 100%;
  height: 230px;
  border-radius: 10px;
  overflow: hidden;
  background: #f3f4f6;
  border: 1px solid #e5e7eb;
}

.preview-main-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  cursor: pointer;
  transition: transform 0.2s ease;

  &:hover {
    transform: scale(1.02);
  }
}

.gallery-nav-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.9);
  border: 1px solid #e5e7eb;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  color: #1f2937;
  cursor: pointer;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
  transition: all 0.15s ease;
  z-index: 2;

  &:hover {
    background: #ffffff;
    color: #000000;
  }

  &.gallery-nav-prev {
    left: 10px;
  }
  &.gallery-nav-next {
    right: 10px;
  }
}

.gallery-counter-badge {
  position: absolute;
  bottom: 10px;
  right: 10px;
  background: rgba(17, 24, 39, 0.75);
  color: #ffffff;
  font-size: 11px;
  font-weight: 600;
  padding: 3px 8px;
  border-radius: 4px;
  letter-spacing: 0.5px;
  z-index: 2;
}

/* 2. Header Section */
.preview-header-section {
  margin-bottom: 16px;
  padding-bottom: 14px;
  border-bottom: 1px solid #f3f4f6;
}

.preview-vehicle-title {
  font-size: 19px;
  font-weight: 700;
  color: #111827;
  line-height: 1.3;
  margin: 0 0 8px 0;
}

.preview-meta-row {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
}

.preview-stock-no {
  font-size: 13px;
  color: #4b5563;
}

.preview-status-badge {
  font-size: 11px;
  font-weight: 600;
  padding: 3px 9px;
  border-radius: 6px;
  line-height: 1.2;
}

.preview-status-badge-inline {
  font-size: 11px;
  font-weight: 600;
  padding: 2px 8px;
  border-radius: 4px;
  display: inline-block;
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

.preview-website-btn {
  margin-left: auto;
  display: inline-flex;
  align-items: center;
  background: #2563eb;
  color: #ffffff;
  border: none;
  font-size: 12px;
  font-weight: 600;
  padding: 5px 12px;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.15s ease;

  &:hover {
    background: #1d4ed8;
  }
}

/* 3. Tabbed Layout */
.preview-tabs-container {
  margin-bottom: 18px;
  border-bottom: 1px solid #f3f4f6;
  padding-bottom: 16px;
}

.preview-tab-layout {
  display: flex;
  gap: 16px;
}

.preview-tabs-sidebar {
  width: 115px;
  flex-shrink: 0;
  display: flex;
  flex-direction: column;
  gap: 4px;
  border-right: 1px solid #f3f4f6;
  padding-right: 8px;
}

.preview-tab-item {
  text-align: left;
  background: none;
  border: none;
  border-left: 3px solid transparent;
  padding: 6px 10px;
  font-size: 13px;
  font-weight: 500;
  color: #4b5563;
  cursor: pointer;
  border-radius: 0 6px 6px 0;
  transition: all 0.15s ease;
  display: flex;
  align-items: center;
  justify-content: space-between;

  &:hover {
    color: #111827;
    background: #f9fafb;
  }

  &.active {
    color: #2563eb;
    border-left-color: #2563eb;
    font-weight: 600;
    background: #eff6ff;
  }
}

.tab-count-badge {
  font-size: 10px;
  background: #e5e7eb;
  color: #374151;
  padding: 1px 5px;
  border-radius: 10px;
}

.preview-tab-content-area {
  flex: 1;
  min-width: 0;
}

.preview-tab-heading {
  font-size: 13.5px;
  font-weight: 700;
  color: #111827;
  margin: 0 0 10px 0;
  padding-bottom: 4px;
}

/* Key Value Grid */
.preview-key-value-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.preview-kv-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 12.5px;
  line-height: 1.4;
  padding: 2px 0;
}

.preview-kv-label {
  color: #6b7280;
  font-weight: 400;
  flex-shrink: 0;
  margin-right: 8px;
}

.preview-kv-value {
  color: #111827;
  font-weight: 500;
  text-align: right;
  word-break: break-word;

  &.copyable-value {
    cursor: pointer;
    text-decoration: underline;
    text-decoration-style: dotted;

    &:hover {
      color: #2563eb;
    }
  }

  .copy-icon {
    font-size: 12px;
    vertical-align: middle;
  }
}

.preview-disclosure-text {
  font-size: 12px;
  color: #374151;
  background: #f9fafb;
  padding: 8px 10px;
  border-radius: 6px;
  border: 1px solid #f3f4f6;
  white-space: pre-wrap;
}

/* 4. Documents & Links Section */
.preview-section-card {
  margin-bottom: 16px;
  background: #f9fafb;
  border: 1px solid #f3f4f6;
  border-radius: 10px;
  padding: 14px;
}

.preview-section-title {
  font-size: 14px;
  font-weight: 700;
  color: #111827;
  margin: 0 0 10px 0;
}

.preview-sub-title {
  font-size: 13px;
  font-weight: 600;
  color: #374151;
}

.preview-doc-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 8px 0;
  border-bottom: 1px solid #eef0f3;

  &:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }
}

.preview-doc-left {
  display: flex;
  align-items: center;
  font-size: 13px;
  color: #1f2937;
  font-weight: 500;
}

.preview-doc-icon {
  font-size: 18px;
  margin-right: 8px;
}

.preview-carfax-logo {
  background: #111827;
  color: #ffffff;
  font-size: 9px;
  font-weight: 800;
  letter-spacing: 0.5px;
  padding: 2px 6px;
  border-radius: 4px;
}

.preview-doc-view-link {
  background: none;
  border: none;
  color: #2563eb;
  font-size: 12.5px;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  cursor: pointer;
  padding: 2px 4px;

  &:hover {
    text-decoration: underline;
    color: #1d4ed8;
  }
}

/* 5. Disclosures & Notes */
.preview-disclosure-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 7px 0;
  border-bottom: 1px solid #eef0f3;

  &:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }
}

.preview-disclosure-left {
  display: flex;
  align-items: center;
  font-size: 12.5px;
  color: #4b5563;
}

.preview-disc-icon {
  font-size: 16px;
  margin-right: 8px;
}

.preview-disc-label {
  color: #4b5563;
}

.preview-disc-value {
  font-size: 12.5px;
  font-weight: 600;
  color: #111827;
}

/* History Cards */
.preview-history-card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 10px;
  margin-bottom: 8px;
}

.preview-attachment-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  padding: 8px 10px;
  margin-bottom: 6px;
}

.preview-attachment-info {
  display: flex;
  align-items: center;
  font-size: 12px;
  font-weight: 500;
  color: #1f2937;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.attachment-icon {
  font-size: 18px;
  color: #ef4444;
  margin-right: 6px;
}

.attachment-name {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  max-width: 140px;
}

.attachment-view-link {
  font-size: 12px;
  color: #2563eb;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;

  &:hover {
    text-decoration: underline;
  }
}

.preview-empty-state {
  text-align: center;
  padding: 20px 10px;
  color: #9ca3af;
  font-size: 12px;

  .empty-icon {
    font-size: 28px;
    margin-bottom: 6px;
  }
}

@media (max-width: 1024px) {
  .inventory-preview-panel {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    max-width: 440px;
    height: 100vh;
    z-index: 1051;
    border-radius: 0;
  }
}
</style>
