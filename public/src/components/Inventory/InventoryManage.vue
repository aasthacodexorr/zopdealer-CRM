<template>
  <div class="inventory-manage-root is-manage-mode" :class="{ 'is-update-mode': updateView }">
    <div
      class="app-content content inventory-manage-shell"
      style="overflow: visible"
    >
      <div class="inventory-manage-shell__inner">
        <!-- Top Page Header: Create Mode -->
        <div v-if="!updateView" class="inventory-manage-page-header inventory-manage-page-header--create">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <h2 class="inventory-manage-main-title mb-0">Add Inventory</h2>
            <div class="inventory-header-actions d-flex align-items-center">
              <router-link
                to="/inventory"
                class="btn btn-outline-secondary zd-header-btn"
                exact
              >
                Cancel
              </router-link>
              <button
                v-show="show_general_submit_button"
                type="submit"
                :disabled="!submit_button"
                class="btn btn-primary zd-header-btn"
                @click="createEvent"
              >
                Save
              </button>
            </div>
          </div>
        </div>

        <!-- Top Page Header: Update Mode -->
        <div
          v-else-if="updateView"
          class="inventory-manage-page-header"
        >
          <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex align-items-center">
              <router-link
                title="Back To Inventory"
                tag="a"
                to="/inventory"
                aria-label="Back To Inventory"
                class="btn btn-sm btn-outline-secondary btn-back-nav btn-back-nav--icon mr-1"
                exact
              >
                <i class="bx bx-arrow-back"></i>
              </router-link>
              <div class="inventory-header-thumb mr-1">
                <img
                  :src="headerThumbnailUrl"
                  :alt="`${inventory.year} ${inventory.make} ${inventory.model}`"
                  @error="onHeaderImageError"
                />
              </div>
              <div>
                <template v-if="inventory.year !== ''">
                  <div class="d-flex align-items-center flex-wrap mb-0">
                    <h2 class="inventory-manage-main-title mb-0">
                      {{ inventory.year }} {{ inventory.make }} {{ inventory.model }}
                    </h2>
                    <span v-if="inventory.stock_no" class="badge badge-light-secondary font-weight-bold ml-1 px-1 py-50">#{{ inventory.stock_no }}</span>
                    <span v-if="inventory.vin" class="text-muted font-small-3 ml-1">({{ inventory.vin }})</span>
                  </div>
                  <div v-if="created_at || updated_at" class="text-muted font-small-2 mt-25">
                    <span v-if="created_at">Created: {{ created_at }}</span>
                    <span v-if="created_at && updated_at" class="mx-50">•</span>
                    <span v-if="updated_at">Updated: {{ updated_at }}</span>
                  </div>
                </template>
                <template v-else>
                  <div class="d-flex align-items-center mb-50" style="gap: 8px;">
                    <span class="zd-skeleton zd-skeleton--title"></span>
                    <span class="zd-skeleton zd-skeleton--badge"></span>
                    <span class="zd-skeleton zd-skeleton--vin"></span>
                  </div>
                  <span class="zd-skeleton zd-skeleton--meta"></span>
                </template>
              </div>
            </div>
            <div class="inventory-header-actions d-flex align-items-center flex-wrap mt-50 mt-sm-0">
              <a
                v-show="showVDPUrl"
                target="_blank"
                :href="inventory.page_url"
                class="btn btn-sm btn-outline-primary zd-header-btn"
                title="View on Website"
              >
                <i class="bx bx-link-external mr-50"></i> View on Website
              </a>
              <div
                v-show="getLeadCount > 0"
                class="position-relative cursor-pointer"
                @click="searchLeadResponseByStockNumber"
              >
                <button class="btn btn-sm btn-light-primary zd-header-btn">
                  <i class="bx bxs-magnet mr-50"></i> Leads
                  <span class="badge badge-primary ml-50">{{ getLeadCount }}</span>
                </button>
              </div>
              <button
                id="inventoryPrintDropDown"
                type="button"
                class="btn btn-sm btn-outline-secondary zd-header-btn"
                data-toggle="modal"
                data-target="#inventory-print-options"
              >
                <i class="bx bx-printer mr-50"></i> Print
              </button>
              <router-link
                :to="{
                  name: 'deal-create',
                  params: { preselected_stock_no: inventory.stock_no },
                }"
                class="btn btn-sm btn-outline-secondary zd-header-btn"
                exact
              >
                Create Deal
              </router-link>
            </div>
          </div>
        </div>
        <div class="content-overlay"></div>
        <div class="content-body inventory-manage-content-body">
          <div class="inventory-manage-workspace" :class="{ 'is-create-mode': !updateView }">
            <aside v-if="updateView" class="inventory-side-nav" aria-label="Inventory sections">
              <ul class="nav flex-column inventory-side-tabs" role="tablist">
                <li class="nav-item">
                  <a
                    class="nav-link active"
                    @click="
                      show_general_submit_button = true;
                      show_disclosures_submit_button = false;
                      show_purchase_submit_button = false;
                      show_digital_retailing_button = false;
                    "
                    id="general-tab"
                    data-toggle="tab"
                    href="#general-block-list"
                    aria-controls="information"
                    role="tab"
                    aria-selected="true"
                  >
                    <i class="bx bx-car"></i>
                    <span>General</span>
                  </a>
                </li>
                <li
                  class="nav-item"
                  v-show="updateView"
                  @click="
                    show_general_submit_button = false;
                    show_disclosures_submit_button = true;
                    show_purchase_submit_button = false;
                    show_digital_retailing_button = false;
                  "
                >
                  <a
                    class="nav-link"
                    id="disclosure-tab"
                    data-toggle="tab"
                    href="#disclosure-block-list"
                    aria-controls="Disclosure Tab"
                    role="tab"
                    aria-selected="false"
                  >
                    <i class="bx bx-file-blank"></i>
                    <span>Disclosures</span>
                  </a>
                </li>
                <li
                  class="nav-item"
                  v-show="updateView"
                  @click="
                    show_general_submit_button = false;
                    show_disclosures_submit_button = false;
                    show_purchase_submit_button = false;
                    show_digital_retailing_button = false;
                  "
                >
                  <a
                    class="nav-link"
                    id="information-tab"
                    data-toggle="tab"
                    href="#media-block-list"
                    aria-controls="information"
                    role="tab"
                    aria-selected="false"
                  >
                    <i class="bx bx-images"></i>
                    <span>Media</span>
                  </a>
                </li>
                <li
                  class="nav-item"
                  v-if="updateView"
                  @click="
                    show_general_submit_button = false;
                    show_disclosures_submit_button = false;
                    show_purchase_submit_button = false;
                    show_digital_retailing_button = false;
                  "
                >
                  <a
                    class="nav-link"
                    id="attachment-tab"
                    data-toggle="tab"
                    href="#attachments-block-list"
                    aria-controls="information"
                    role="tab"
                    aria-selected="false"
                  >
                    <i class="bx bx-paperclip"></i>
                    <span>Attachments</span>
                  </a>
                </li>
                <li
                  class="nav-item"
                  v-show="updateView"
                  @click="
                    show_general_submit_button = false;
                    show_disclosures_submit_button = false;
                    show_purchase_submit_button = false;
                    show_digital_retailing_button = false;
                  "
                >
                  <a
                    class="nav-link"
                    id="inventory-note-tab"
                    data-toggle="tab"
                    href="#inventory-notes"
                    aria-controls="Notes"
                    role="tab"
                    aria-selected="false"
                  >
                    <i class="bx bx-note"></i>
                    <span>Notes</span>
                  </a>
                </li>
                <li
                  class="nav-item"
                  v-show="updateView"
                  @click="
                    show_general_submit_button = false;
                    show_disclosures_submit_button = false;
                    show_purchase_submit_button = false;
                    show_digital_retailing_button = false;
                  "
                >
                  <a
                    class="nav-link"
                    id="inventory-inspection-tab"
                    data-toggle="tab"
                    href="#inventory-inspection-panel"
                    aria-controls="Inventory Inspection Tab"
                    role="tab"
                    aria-selected="false"
                  >
                    <i class="bx bx-check-shield"></i>
                    <span>Inspection</span>
                  </a>
                </li>
                <li
                  class="nav-item"
                  v-if="updateView && isAuthorized"
                  @click="
                    show_general_submit_button = false;
                    show_disclosures_submit_button = false;
                    show_purchase_submit_button = true;
                    show_digital_retailing_button = false;
                  "
                >
                  <a
                    class="nav-link"
                    id="inventory-purchase-tab"
                    data-toggle="tab"
                    href="#inventory-purchase-panel"
                    aria-controls="information"
                    role="tab"
                    aria-selected="false"
                  >
                    <i class="bx bx-receipt"></i>
                    <span>Purchase</span>
                  </a>
                </li>
                <li
                  class="nav-item"
                  v-if="updateView && isAuthorized"
                  @click="
                    show_general_submit_button = false;
                    show_disclosures_submit_button = false;
                    show_purchase_submit_button = false;
                    show_digital_retailing_button = false;
                  "
                >
                  <a
                    class="nav-link"
                    id="update-tab"
                    data-toggle="tab"
                    href="#expenses-block-list"
                    aria-controls="information"
                    role="tab"
                    aria-selected="false"
                  >
                    <i class="bx bx-dollar-circle"></i>
                    <span>Expenses</span>
                  </a>
                </li>
                <li
                  class="nav-item"
                  v-if="updateView && isAuthorized"
                  @click="
                    show_general_submit_button = false;
                    show_disclosures_submit_button = false;
                    show_purchase_submit_button = false;
                    show_digital_retailing_button = false;
                  "
                >
                  <a
                    class="nav-link"
                    id="inventory-deals-tab"
                    data-toggle="tab"
                    href="#inventory-deals-panel"
                    aria-controls="Inventory Deals Panel"
                    role="tab"
                    aria-selected="false"
                  >
                    <i class="bx bx-briefcase"></i>
                    <span>Deals</span>
                    <span
                      v-show="deal_count != '0'"
                      class="badge badge-light-primary badge-pill badge-round ml-auto"
                      >{{ deal_count }}</span
                    >
                  </a>
                </li>
                <li
                  class="nav-item"
                  v-if="updateView"
                  @click="
                    show_general_submit_button = false;
                    show_disclosures_submit_button = false;
                    show_purchase_submit_button = false;
                    show_digital_retailing_button = true;
                  "
                >
                  <a
                    class="nav-link"
                    id="inventory-digital-retail-tab"
                    data-toggle="tab"
                    href="#inventory-digital-retail-panel"
                    aria-controls="information"
                    role="tab"
                    aria-selected="false"
                  >
                    <i class="bx bx-calculator"></i>
                    <span>e-Quote</span>
                  </a>
                </li>
              </ul>
            </aside>
            <div class="inventory-manage-main">
              <div
                v-if="
                  updateView &&
                  (show_general_submit_button ||
                    show_disclosures_submit_button ||
                    show_purchase_submit_button ||
                    show_digital_retailing_button)
                "
                class="inventory-tab-toolbar"
              >
                <div class="inventory-tab-toolbar__title">{{ activeSectionTitle }}</div>
                <div class="inventory-tab-toolbar__actions">
                  <button
                    v-show="show_general_submit_button"
                    type="submit"
                    :disabled="!submit_button"
                    class="btn btn-sm btn-primary zd-header-btn"
                    @click="createEvent"
                  >
                    Save
                  </button>
                  <button
                    v-show="show_disclosures_submit_button"
                    type="submit"
                    :disabled="!submit_button"
                    class="btn btn-sm btn-primary zd-header-btn"
                    @click="createDisclosureEvent"
                  >
                    Save
                  </button>
                  <button
                    v-show="show_purchase_submit_button"
                    type="submit"
                    :disabled="!submit_button"
                    class="btn btn-sm btn-primary zd-header-btn"
                    @click="createPurchaseEvent"
                  >
                    Save
                  </button>
                  <button
                    v-show="show_digital_retailing_button"
                    type="submit"
                    :disabled="!submit_button"
                    class="btn btn-sm btn-primary zd-header-btn"
                    @click="createDigitalRetailEvent"
                  >
                    Save
                  </button>
                </div>
              </div>
              <!-- form layout section start -->
              <div class="tab-content">
            <inventory-manage-general
              :key="viewKey"
              :app_metadata="app_metadata"
              :time_zone="timeZoneName"
            ></inventory-manage-general>
            <inventory-manage-disclosure></inventory-manage-disclosure>
            <inventory-manage-gallery></inventory-manage-gallery>
            <inventory-manage-attachment
              :vin="inventory.vin"
              :stock_no="inventory.stock_no"
              :app_metadata="app_metadata"
            ></inventory-manage-attachment>
            <inventory-manage-purchase
              v-if="isAuthorized"
              :app_metadata="app_metadata"
            ></inventory-manage-purchase>
            <inventory-manage-expense
              v-if="isAuthorized"
              :app_metadata="app_metadata"
              :selling_price="selling_price"
              :purchase_detail="purchase"
            ></inventory-manage-expense>
            <inventory-manage-inspection
              :allUsers="allUsers"
              :app_metadata="app_metadata"
              :id="$route.params.id"
              :stock_no="inventory.stock_no"
              :time_zone="timeZoneName"
            ></inventory-manage-inspection>
            <inventory-manage-note
              :allUsers="allUsers"
              :app_metadata="app_metadata"
              :id="$route.params.id"
              :time_zone="timeZoneName"
            ></inventory-manage-note>
            <inventory-manage-deal
              v-if="isAuthorized"
              :allUsers="allUsers"
              :app_metadata="app_metadata"
            ></inventory-manage-deal>
            <inventory-manage-digital-retail
              :app_metadata="app_metadata"
            ></inventory-manage-digital-retail>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END: Content-->

    <!-- model for print : START -->
    <div
      class="modal fade text-left"
      id="inventory-print-options"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Print Options"
      aria-hidden="true"
    >
      <div
        class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"
        role="document"
      >
        <div class="modal-content">
          <div class="modal-header pl-1 pr-1">
            <h3 class="content-header-title" id="myModalLabel19">
              Print Documents
            </h3>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <i class="bx bx-x"></i>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <ul class="col-6 list-group">
                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="inventory_print_pdfs"
                      class="checkbox-input"
                      value="window-sticker"
                      :id="'inventory-window-sticker'"
                    />
                    <label class="pl-1" :for="'inventory-window-sticker'"
                      >Window Sticker</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="inventory_print_pdfs"
                      class="checkbox-input"
                      value="test-drive"
                      :id="'inventory-test-drive'"
                    />
                    <label class="pl-1" :for="'inventory-test-drive'"
                      >Test Drive</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item" v-if="isAuthorized">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="inventory_print_pdfs"
                      class="checkbox-input"
                      value="public-purchase"
                      :id="'inventory-public-purchase'"
                    />
                    <label class="pl-1" :for="'inventory-public-purchase'"
                      >Public Purchase</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item" v-if="isAuthorized">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="inventory_print_pdfs"
                      class="checkbox-input"
                      value="dealer-purchase"
                      :id="'inventory-dealer-purchase'"
                    />
                    <label class="pl-1" :for="'inventory-dealer-purchase'"
                      >Dealer Purchase</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="inventory_print_pdfs"
                      class="checkbox-input"
                      value="vehicle-disclosures"
                      :id="'inventory-vehicle-disclosures'"
                    />
                    <label class="pl-1" :for="'inventory-vehicle-disclosures'"
                      >Vehicle Disclosures</label
                    >
                  </div>
                </li>
                <!-- list element : END -->
              </ul>
              <ul class="col-6 list-group">
                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="inventory_print_pdfs"
                      class="checkbox-input"
                      value="internal-inspection"
                      :id="'inventory-internal-inspection'"
                    />
                    <label class="pl-1" :for="'inventory-internal-inspection'"
                      >Internal Inspection</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item" v-show="enable_buyers_guide == '1'">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="inventory_print_pdfs"
                      class="checkbox-input"
                      value="buyers-guide"
                      :id="'inventory-buyers-guide'"
                    />
                    <label class="pl-1" :for="'inventory-buyers-guide'"
                      >Buyers Guide</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item" v-if="isAuthorized">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="inventory_print_pdfs"
                      class="checkbox-input"
                      value="inventory-recap"
                      :id="'inventory-inventory-recap'"
                    />
                    <label class="pl-1" :for="'inventory-inventory-recap'"
                      >Inventory Recap</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="inventory_print_pdfs"
                      class="checkbox-input"
                      value="download-images"
                      :id="'inventory-download-images'"
                    />
                    <label class="pl-1" :for="'inventory-download-images'"
                      >Download Images</label
                    >
                  </div>
                </li>
                <!-- list element : END -->
              </ul>
              <div
                class="col-sm-12 d-flex justify-content-left pb-1"
                v-if="inventory_print_pdfs.length > 0"
              >
                <button
                  type="close"
                  class="btn btn-primary mr-1 mt-1"
                  data-dismiss="modal"
                  aria-label="Close"
                  @click="unifiedDisclosureStatementWarning()"
                >
                  <i class="bx bxs-printer mr-1"></i> Print
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- model for print : END -->
  </div>
</template>
<script>
import Toast from "../../library/Toast";
import InventoryManageAttachment from "./InventoryManageAttachment";
import InventoryManagePurchase from "./InventoryManagePurchase";
import InventoryManageDigitalRetail from "./InventoryManageDigitalRetail";
import InventoryManageExpense from "./InventoryManageExpense";
import InventoryManageGallery from "./InventoryManageGallery";
import InventoryManageGeneral from "./InventoryManageGeneral";
import InventoryManageDisclosure from "./InventoryManageDisclosure";
import InventoryManageWebInspection from "./InventoryManageWebInspection";
import InventoryManageNote from "./InventoryManageNote";
import InventoryManageInspection from "./InventoryManageInspection";
import InventoryManageDeal from "./InventoryManageDeal.vue";

import { mapGetters, mapActions } from "vuex";

export default {
  props: {
    application: { required: true },
    app_metadata: { required: true },
    user_metadata: { required: true },
  },
  name: "InventoryManage",
  components: {
    InventoryManageGeneral,
    InventoryManageGallery,
    InventoryManageAttachment,
    InventoryManageExpense,
    InventoryManagePurchase,
    InventoryManageDisclosure,
    InventoryManageWebInspection,
    InventoryManageNote,
    InventoryManageInspection,
    InventoryManageDigitalRetail,
    InventoryManageDeal,
  },
  data() {
    return {
      deal_count: 0,
      created_at: null,
      updated_at: null,
      show_general_submit_button: true,
      show_purchase_submit_button: false,
      show_disclosures_submit_button: false,
      show_digital_retailing_button: false,
      enable_buyers_guide: "0",
      view_number: 0,
      submit_button: true,
      selling_price: 0,
      purchase: {},
      inventory: {
        year: "",
        make: "",
        model: "",
        stock_no: "",
        vin: "",
        page_url: "",
        status: "",
      },
      lead_count: "",
      inventory_print_pdfs: [],
      header_image_url: "",
      placeholder_image: "",
    };
  },
  watch: {
    "$route.params.id": function(newVal) {
      if (newVal && this.$route.name == "inventory-update") {
        this.prefillHeaderFromStore();
        this.getHeaderImage();
      } else {
        this.header_image_url = "";
      }
    },
    "inventory.stock_no": function(newVal, oldVal) {
      if (newVal.length > 0) {
        if (newVal !== oldVal) {
          this.leadResponseCount(newVal);
        }
      }
    },
  },
  computed: {
    ...mapGetters("inventoryView", [
      "list",
      "getViewId",
      "getViewTitle",
      "getEntities",
      "getPager",
      "getSortColumn",
      "getLoadState",
      "getEntitiesCount",
      "getCurrentPage",
    ]),
    ...mapGetters("users", ["allUsers"]),
    getLeadCount() {
      const parsed = parseInt(this.lead_count, 10);
      return Number.isNaN(parsed) ? 0 : parsed;
    },
    headerThumbnailUrl() {
      if (this.header_image_url) {
        return this.header_image_url;
      }
      const rawUrls = this.inventory.image_urls || this.inventory.image_url || this.inventory.image || "";
      if (rawUrls) {
        const parts = String(rawUrls).includes(";")
          ? String(rawUrls).split(";")
          : String(rawUrls).split(",");
        const cleanParts = parts.map((p) => p.trim()).filter(Boolean);
        const selected = cleanParts[0];
        if (selected) {
          const cdn = window.global && window.global.cdn ? window.global.cdn : "";
          return this.$helpers.parseAbsoluteOrRelativeUrl(cdn, selected);
        }
      }
      return this.placeholder_image || "/media/logo/placeholder.png";
    },
    showVDPUrl() {
      return (
        this.inventory.status === "Instock" && this.inventory.page_url !== ""
      );
    },
    updateView() {
      return this.$route.params.id && this.$route.name == "inventory-update";
    },
    viewKey() {
      return this.$route.params.id && this.$route.name == "inventory-update"
        ? "inventory-update"
        : "inventory-create";
    },
    timeZoneName() {
      return this.user_metadata && this.user_metadata.hasOwnProperty("timezone")
        ? this.user_metadata.timezone
        : "Canada/Eastern";
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    activeSectionTitle() {
      if (this.show_disclosures_submit_button) return "Disclosures";
      if (this.show_purchase_submit_button) return "Purchase Details";
      if (this.show_digital_retailing_button) return "e-Quote";
      return "General Information";
    },
  },
  methods: {
    ...mapActions("inventoryView", [
      "commitOnlyEntities",
      "commitCurrentEntity",
      "commitLoadState",
    ]),
    ...mapActions("users", ["getAllUsers"]),
    ...mapActions("leadResponseView", {
      leadCommitSearchState: "commitSearchState",
      leadCommitSearchString: "commitSearchString",
      leadCommitLoadState: "commitLoadState",
    }),
    onHeaderImageError(e) {
      if (e && e.target && !e.target.dataset.fallbackApplied) {
        e.target.dataset.fallbackApplied = "1";
        e.target.src = this.placeholder_image || "/media/logo/placeholder.png";
      }
    },
    prefillHeaderFromStore() {
      if (!this.updateView || !Array.isArray(this.getEntities)) return;
      const match = this.getEntities.find(
        (item) => String(item.id) === String(this.$route.params.id)
      );
      if (match) {
        this.inventory = {
          year: match.year || "",
          make: match.make || "",
          model: match.model || "",
          stock_no: match.stock_no || "",
          vin: match.vin || "",
          page_url: match.page_url || "",
          status: match.status || "",
          image_urls: match.image_urls || "",
        };
        if (match.created_at) {
          this.created_at = this.$helpers.changeUTCtoLocalDate(
            match.created_at,
            "MM/DD/YYYY hh:mm A",
            this.timeZoneName
          );
        }
        if (match.updated_at) {
          this.updated_at = this.$helpers.changeUTCtoLocalDate(
            match.updated_at,
            "MM/DD/YYYY hh:mm A",
            this.timeZoneName
          );
        }
      }
    },
    async getHeaderImage() {
      if (!this.updateView) return;
      try {
        const { data } = await axios({
          method: "get",
          url: `${window.location.origin}/api/business/inventory/image/${this.$route.params.id}`,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
        });
        const list = Array.isArray(data && data.gallery) ? data.gallery : [];
        const first = list.find((img) => String(img.active) !== "0") || list[0];
        if (first && first.image_url) {
          const cdn = window.global && window.global.cdn ? window.global.cdn : "";
          this.header_image_url =
            this.$helpers.parseAbsoluteOrRelativeUrl(cdn, first.image_url) + "?width=200";
        } else {
          this.header_image_url = "";
        }
      } catch (e) {
        this.header_image_url = "";
      }
    },
    async getPlaceholderImage() {
      try {
        const { data } = await axios({
          method: "get",
          url: `${window.location.origin}/api/business/image/placeholder`,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
        });
        if (data && data.image_url) {
          const cdn = window.global && window.global.cdn ? window.global.cdn : "";
          this.placeholder_image = this.$helpers.parseAbsoluteOrRelativeUrl(cdn, data.image_url);
        }
      } catch (e) {}
    },
    async searchLeadResponseByStockNumber() {
      this.leadCommitSearchState(true);
      this.leadCommitSearchString(this.inventory.stock_no);
      this.leadCommitLoadState(false);
      this.$router.push({ name: "lead-responses" });
    },
    async disclosureStatementWarning(slug) {
      const vm = this;
      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Back",
        confirmButtonText: "I Understand",
        toast: false,
        position: "center",
        text:
          "Before signing the document, please recheck the data. Wrong or missing information may have legal implications.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.downloadPDF(slug);
        }
      });
    },
    async downloadPDF(bill_type) {
      if (this.$route.params.id && this.$route.name == "inventory-update") {
        const vm = this;
        await axios({
          method: "get",
          responseType: "blob",
          url:
            window.location.origin +
            `/api/business/inventory/pdf/${bill_type}/generate/${this.$route.params.id}`,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
        })
          .then((response) => {
            const file = new Blob([response.data], { type: "application/pdf" }); //Build a URL from the file
            const fileURL = URL.createObjectURL(file); //Open the URL on new Window
            window.open(fileURL);
          })
          .catch(({ response }) => {
            console.log(response);
          });
      }
    },
    async downloadInventoryRecap() {
      const vm = this;
      await axios({
        method: "get",
        responseType: "blob",
        url:
          window.location.origin +
          `/api/business/inventory/print/inventory-recap/${this.$route.params.id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then((response) => {
          const file = new Blob([response.data], { type: "application/pdf" }); //Build a URL from the file
          const fileURL = URL.createObjectURL(file); //Open the URL on new Window
          window.open(fileURL);
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    async zipWebsiteImages() {
      if (this.$route.params.id && this.$route.name == "inventory-update") {
        const toast = new Toast(`Downloading images...`);
        toast.fire();

        const vm = this;
        await axios({
          method: "get",
          responseType: "blob",
          url:
            window.location.origin +
            `/api/business/inventory/image/zip/${this.$route.params.id}`,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
        })
          .then((response) => {
            const file = new Blob([response.data]); //Build a URL from the file
            const fileURL = URL.createObjectURL(file); //Open the URL on new Window

            // Create a link to download it
            var pom = document.createElement("a");
            pom.href = fileURL;
            pom.setAttribute(
              "download",
              `${this.inventory.year}-${this.inventory.make}-${this.inventory.model}-${this.$route.params.id}.zip`,
            );
            pom.click();

            toast.success(`Images downloaded successfully.`);
            toast.close();
          })
          .catch((error) => {
            toast.fail(
              `Either there was error in fetching the images or per day limit has been exceeded.`,
            );
            toast.close();
          });
      }
    },
    windowSticker() {
      if (this.$route.params.id && this.$route.name == "inventory-update") {
        const vm = this;
        axios({
          method: "get",
          responseType: "blob",
          url: `${window.location.origin}/api/business/inventory/sticker/v2/${this.$route.params.id}`,
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
            let error =
              "There were some errors in retrieving the window sticker";
            console.log(error);
          });
      }
    },
    async getGlobalConfigurations() {
      let request_url =
        window.location.origin + "/api/business/configurations/active/global";
      await axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          for (const key in data) {
            if (data.hasOwnProperty(key)) {
              const element = data[key];
              if (element.key == "enable_buyers_guide") {
                this.enable_buyers_guide = element.value;
              }
            }
          }
        })
        .catch(() => {});
    },
    async leadResponseCount(stock_no) {
      let request_url = `${window.location.origin}/api/business/leads/responses/filters?zm_stock_no[]=${stock_no}`;
      await axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          console.log(data);
          if (data.hasOwnProperty("stock_no")) {
            let element = data["stock_no"];
            if (element.hasOwnProperty("0")) {
              this.lead_count = element["0"].count;
            }
          }
        })
        .catch(() => {});
    },
    createEvent() {
      this.submit_button = false;
      Event.fire("save_inventory", null);
    },
    createPurchaseEvent() {
      this.submit_button = false;
      Event.fire("save_inventory_purchase", null);
    },
    createDisclosureEvent() {
      this.submit_button = false;
      Event.fire("save_inventory_disclosures", null);
    },
    createDigitalRetailEvent() {
      this.submit_button = false;
      Event.fire("save_inventory_digital_retailing", null);
    },
    async unifiedDisclosureStatementWarning() {
      const vm = this;
      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Back",
        confirmButtonText: "I Understand",
        toast: false,
        position: "center",
        text:
          "Before signing the document, please recheck the data. Wrong or missing information may have legal implications.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          for (let single_pdf of this.inventory_print_pdfs) {
            switch (single_pdf) {
              case "window-sticker":
                vm.windowSticker();
                break;
              case "inventory-recap":
                vm.downloadInventoryRecap();
                break;

              case "download-images":
                vm.zipWebsiteImages();
                break;

              default:
                vm.downloadPDF(single_pdf);
                break;
            }
          }
        }
      });

      vm.inventory_print_pdfs = [];
    },
  },
  async created() {
    moment.tz.setDefault(this.timeZoneName);

    await this.getGlobalConfigurations();
  },
  mounted() {
    const vm = this;
    Event.listen("save_complete", (data) => {
      vm.$data.submit_button = true;
      vm.getHeaderImage();

      if (data.hasOwnProperty("success")) {
        if (data.success == "Inventory has been created.") {
          this.commitOnlyEntities();
          vm.$router.push({
            name: "inventory-update",
            params: { id: data.inventory_id },
          });
        } else {
          this.commitOnlyEntities();
        }
      }
    });

    Event.listen("deal_count", (data) => {
      vm.$data.deal_count = data;
    });

    Event.listen("change_inventory_selling_price", (data) => {
      this.selling_price = data;
    });

    Event.listen("change_inventory", (data) => {
      this.inventory = data;
    });

    Event.listen("get_inventory_datetime", (data) => {
      this.created_at = data.created_at;
      this.updated_at = data.updated_at;
    });

    Event.listen("change_inventory_purchase", (data) => {
      this.purchase = data;
    });

    if (this.$route.params.id && this.$route.name == "inventory-update") {
      this.prefillHeaderFromStore();
      this.commitCurrentEntity(this.$route.params.id);
      this.getHeaderImage();
    }

    this.getPlaceholderImage();
    this.getAllUsers();
  },
  beforeUnmount() {
    Event.vue.$off("save_inventory", null);
    Event.vue.$off("save_inventory_purchase", null);
    Event.vue.$off("save_inventory_disclosures", null);
    Event.vue.$off("save_inventory_digital_retailing", null);
  },
};
</script>
<style lang="scss">
ul.nav.nav-tabs {
  border-bottom: 1px solid #dfe3e7;
}

.inventory-manage-page-header {
  background: #ffffff !important;
  border-bottom: 1px solid #f1f5f9 !important;
  padding: 1rem 1.75rem !important;
  margin-bottom: 0 !important;
  box-shadow: none !important;

  .btn-back-nav.btn-back-nav--icon {
    width: 36px;
    height: 36px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    color: #475569;
    background: #ffffff;
    flex-shrink: 0;

    &:hover {
      background: #f8fafc;
      border-color: #cbd5e1;
      color: #0f172a;
    }
  }

  .inventory-header-thumb {
    width: 68px;
    height: 50px;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    flex-shrink: 0;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
  }

  .zd-skeleton {
    display: inline-block;
    border-radius: 6px;
    background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
    background-size: 200% 100%;
    animation: zd-shimmer 1.4s ease-in-out infinite;
  }
  .zd-skeleton--title { width: 200px; height: 22px; }
  .zd-skeleton--badge { width: 72px; height: 22px; }
  .zd-skeleton--vin { width: 130px; height: 16px; }
  .zd-skeleton--meta { width: 220px; height: 13px; display: block; }

  .inventory-header-actions {
    gap: 8px;

    .zd-header-btn {
      height: 36px;
      padding: 0 14px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      white-space: nowrap;
      margin: 0 !important;
    }
  }
}

.inventory-manage-root .btn,
.inventory-manage-root .dc-btn,
.inventory-manage-root .mg-btn,
.inventory-manage-root .zd-header-btn,
.inventory-manage-root .btn-back-nav.btn-back-nav--icon {
  border-radius: 0.267rem !important;
}

@keyframes zd-shimmer {
  0% { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}

.inventory-manage-workspace {
  display: flex;
  align-items: flex-start;
  gap: 20px;
  padding: 18px 24px 32px;

  &.is-create-mode {
    display: block;
    padding: 16px 24px 32px;
  }

  .inventory-side-nav {
    width: 224px;
    flex: 0 0 224px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 8px;
    position: sticky;
    top: 76px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);

    &__header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 4px 12px 10px;
      margin-bottom: 6px;
      border-bottom: 1px solid #f1f5f9;
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      color: #64748b;

      i {
        font-size: 16px;
        color: #94a3b8;
      }
    }

    .inventory-side-tabs {
      gap: 2px;

      .nav-item {
        width: 100%;
      }

      .nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 500;
        color: #475569;
        transition: background-color 0.15s ease, color 0.15s ease;
        border: none;
        background: transparent;

        i {
          font-size: 17px;
          color: #94a3b8;
          flex-shrink: 0;
          transition: color 0.15s ease;
        }

        &:hover {
          background: #f8fafc;
          color: #0f172a;

          i {
            color: #475569;
          }
        }

        &.active {
          background: #eff6ff;
          color: #2563eb;
          font-weight: 600;

          i {
            color: #2563eb;
          }
        }
      }
    }
  }

  .inventory-manage-main {
    flex: 1 1 auto;
    min-width: 0;
  }

  .inventory-tab-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 8px 18px;
    margin-bottom: 14px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);

    &__title {
      font-size: 14.5px;
      font-weight: 600;
      color: #0f172a;
    }

    &__actions {
      display: inline-flex;
      align-items: center;
      gap: 8px;

      .zd-header-btn {
        height: 32px;
        padding: 0 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
      }
    }
  }
}

@media (min-width: 992px) {
  .inventory-manage-root.is-manage-mode {
    .app-content.content.inventory-manage-shell {
      height: 100vh !important;
      overflow: hidden !important;
      display: flex;
      flex-direction: column;
      padding-top: 4rem !important;
      padding-bottom: 0 !important;
    }

    .inventory-manage-shell__inner {
      display: flex;
      flex-direction: column;
      flex: 1 1 auto;
      min-height: 0;
      overflow: hidden;
    }

    .inventory-manage-page-header {
      flex: 0 0 auto;
    }

    .inventory-manage-content-body {
      flex: 1 1 auto;
      min-height: 0;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      margin-bottom: 0 !important;
    }

    .inventory-manage-workspace {
      flex: 1 1 auto;
      min-height: 0;
      height: 100%;
      overflow: hidden;
      align-items: stretch;
      gap: 0;
      padding: 0;
      background: #f8fafc;

      &.is-create-mode {
        display: flex;
        padding: 16px 24px 0;
      }
    }

    .inventory-side-nav {
      position: static;
      align-self: stretch;
      width: 224px;
      flex: 0 0 224px;
      height: 100%;
      max-height: 100%;
      overflow-y: auto;
      background: #ffffff;
      border: none;
      border-right: 1px solid #e2e8f0;
      border-radius: 0;
      box-shadow: none;
      padding: 16px 10px;
    }

    .inventory-manage-main {
      display: flex;
      flex-direction: column;
      height: 100%;
      min-height: 0;
      overflow: hidden;
      padding: 16px 24px 0;
    }

    .inventory-manage-workspace.is-create-mode .inventory-manage-main {
      padding: 0;
    }

    .inventory-tab-toolbar {
      flex: 0 0 auto;
      margin-bottom: 14px;
    }

    .inventory-manage-main > .tab-content {
      flex: 1 1 auto;
      min-height: 0;
      overflow-y: auto;
      overflow-x: hidden;
      padding-bottom: 48px;
      padding-right: 0;

      #general-block-list {
        max-width: none !important;
        margin: 0 !important;
        padding: 0 0 2rem !important;
      }

      .at-page,
      .dl-page,
      .eq-page,
      .dvi-page,
      .dc-page,
      .ex-page,
      .mg-page,
      .ins-page,
      .nt-page,
      .pu-page,
      .wi-page {
        padding-top: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
      }

      .nt-page {
        background: transparent !important;
        padding-bottom: 1.5rem !important;
        min-height: 0 !important;
      }

      .m-width-s {
        max-width: none !important;
      }
    }
  }
}

@media (max-width: 991.98px) {
  .inventory-manage-workspace {
    flex-direction: column;
    padding: 12px 16px 24px;
    gap: 14px;

    .inventory-side-nav {
      width: 100%;
      flex: none;
      position: static;
      padding: 8px;

      &__header {
        display: none;
      }

      .inventory-side-tabs {
        flex-direction: row !important;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 6px;

        .nav-item {
          width: auto;
          flex: 0 0 auto;
        }

        .nav-link {
          white-space: nowrap;
          padding: 8px 12px;
        }
      }
    }

    .inventory-manage-main {
      width: 100%;
    }
  }
}
</style>
