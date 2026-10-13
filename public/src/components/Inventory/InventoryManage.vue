<template>
  <div>
    <div
      class="app-content content fixed-height-block"
      style="overflow: visible"
    >
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <router-link
              title="Back To List"
              tag="a"
              to="/inventory"
              class="nav-link pr-0 pl-0 d-flex align-items-center"
              exact
            >
              <i class="bx bx-caret-left" style="font-size: 1.4em"></i>
            </router-link>
          </li>
          <li class="nav-item">
            <a
              class="nav-link d-flex align-items-center pr-0 pl-0 active"
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
              <span class="d-sm-block">General</span>
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
              class="nav-link d-flex align-items-center pr-0 pl-0"
              id="disclosure-tab"
              data-toggle="tab"
              href="#disclosure-block-list"
              aria-controls="Disclosure Tab"
              role="tab"
              aria-selected="false"
            >
              <span class="d-sm-block">Disclosures</span>
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
              class="nav-link d-flex align-items-center pr-0 pl-0"
              id="information-tab"
              data-toggle="tab"
              href="#media-block-list"
              aria-controls="information"
              role="tab"
              aria-selected="false"
            >
              <span class="d-sm-block">Media</span>
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
              class="nav-link d-flex align-items-center pr-0 pl-0"
              id="attachment-tab"
              data-toggle="tab"
              href="#attachments-block-list"
              aria-controls="information"
              role="tab"
              aria-selected="false"
            >
              <span class="d-sm-block">Attachments </span>
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
              class="nav-link d-flex align-items-center pr-0 pl-0"
              id="inventory-note-tab"
              data-toggle="tab"
              href="#inventory-notes"
              aria-controls="Notes"
              role="tab"
              aria-selected="false"
            >
              <span class="d-sm-block">Notes</span>
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
              class="nav-link d-flex align-items-center pr-0 pl-0"
              id="inventory-inspection-tab"
              data-toggle="tab"
              href="#inventory-inspection-panel"
              aria-controls="Inventory Inspection Tab"
              role="tab"
              aria-selected="false"
            >
              <span class="d-sm-block">Inspection</span>
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
              class="nav-link d-flex align-items-center pr-0 pl-0"
              id="inventory-purchase-tab"
              data-toggle="tab"
              href="#inventory-purchase-panel"
              aria-controls="information"
              role="tab"
              aria-selected="false"
            >
              <span class="d-sm-block">Purchase</span>
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
              class="nav-link d-flex align-items-center pr-0 pl-0"
              id="update-tab"
              data-toggle="tab"
              href="#expenses-block-list"
              aria-controls="information"
              role="tab"
              aria-selected="false"
            >
              <span class="d-sm-block">Expenses</span>
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
              class="nav-link d-flex align-items-center pr-0 pl-0"
              id="inventory-deals-tab"
              data-toggle="tab"
              href="#inventory-deals-panel"
              aria-controls="Inventory Deals Panel"
              role="tab"
              aria-selected="false"
            >
              <span class="d-sm-block">Deals</span>
              <span
                v-show="deal_count != '0'"
                class="badge badge-light-primary badge-pill badge-round ml-50"
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
              class="nav-link d-flex align-items-center pr-0 pl-0"
              id="inventory-digital-retail-tab"
              data-toggle="tab"
              href="#inventory-digital-retail-panel"
              aria-controls="information"
              role="tab"
              aria-selected="false"
            >
              <span class="d-sm-block">e-Quote</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="">
        <h3
          style="vertical-align: super"
          v-if="updateView && inventory.year !== ''"
          class="m-width-s display-8 ml-1"
        >
          <i
            data-v-c6dd3404=""
            data-icon="inventory"
            class="bx bxs-car bx-md mr-1"
          ></i>
          <span style="vertical-align: text-bottom"
            >{{ inventory.year }} {{ inventory.make }} {{ inventory.model }} #{{
              inventory.stock_no
            }}
            ({{ inventory.vin }})
          </span>
          <span v-show="showVDPUrl">
            <a target="_blank" :href="inventory.page_url" class="btn btn-link"
              ><i class="bx bx-link-external"></i
            ></a>
          </span>
          <span v-show="getLeadCount > 0">
            <div
              class="d-inline-block"
              style="position: relative; cursor: pointer"
              @click="searchLeadResponseByStockNumber"
            >
              <i class="bx bxs-magnet bx-sm"></i>

              <span
                :title="`This vehicle has ${getLeadCount} lead(s).`"
                class="badge badge-pill badge-primary badge-right"
                >{{ getLeadCount }}</span
              >
            </div>
          </span>
          <span class="pl-1" style="font-size: 0.4em"
            >Created At : {{ created_at }} Last Updated : {{ updated_at }}</span
          >
        </h3>
        <div class="content-body mb-3">
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
    <!-- Action Bar : START -->
    <ul class="nav nav-tabs p-50 custom-footer-fixed-action-bar" role="tablist">
      <!-- dropdown menu : START -->
      <div class="btn-group mr-1" role="group" v-if="updateView">
        <button
          id="inventoryPrintDropDown"
          style="line-height: 1.2em"
          type="button"
          class="btn btn-secondary"
          data-toggle="modal"
          data-target="#inventory-print-options"
        >
          <i class="bx bx-printer mr-50"></i> Print
        </button>
      </div>

      <!-- dropdown menu : END -->
      <router-link
        v-if="updateView"
        tag="li"
        :to="{
          name: 'deal-create',
          params: { preselected_stock_no: inventory.stock_no },
        }"
        class="d-inline-block"
        exact
      >
        <a class="btn btn-secondary">Create Deal</a>
      </router-link>

      <button
        v-show="show_general_submit_button"
        type="submit"
        :disabled="!submit_button"
        class="btn btn-primary mr-1 ml-4"
        @click="createEvent"
      >
        Save
      </button>
      <button
        v-show="show_disclosures_submit_button"
        type="submit"
        :disabled="!submit_button"
        class="btn btn-primary mr-1 ml-4"
        @click="createDisclosureEvent"
      >
        Save
      </button>
      <button
        v-show="show_purchase_submit_button"
        type="submit"
        :disabled="!submit_button"
        class="btn btn-primary mr-1 ml-4"
        @click="createPurchaseEvent"
      >
        Save
      </button>
      <button
        v-show="show_digital_retailing_button"
        type="submit"
        :disabled="!submit_button"
        class="btn btn-primary mr-1 ml-4"
        @click="createDigitalRetailEvent"
      >
        Save
      </button>
    </ul>
    <!-- Action Bar : END -->
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
    };
  },
  watch: {
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
      return parseInt(this.lead_count, 10);
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
      this.commitCurrentEntity(this.$route.params.id);
    }

    this.getAllUsers();
  },
  beforeDestroy() {
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
</style>
