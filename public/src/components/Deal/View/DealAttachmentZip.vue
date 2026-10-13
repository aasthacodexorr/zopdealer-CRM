<template>
  <span>
    <!-- model for sharing : START -->
    <div
      class="modal fade text-left"
      id="deal-attachment-zip"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Download Attachments"
      aria-hidden="true"
    >
      <div
        class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"
        role="document"
      >
        <div class="modal-content">
          <div class="modal-header pl-1 pr-1">
            <h3 class="content-header-title" id="myModalLabel19">
              Download Attachments
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
              <h4 class="col-12">
                <input
                  type="checkbox"
                  v-model="select_all_deal_documents"
                  @change="
                    allDealAttachmentSelection(select_all_deal_documents)
                  "
                  class="checkbox-input"
                  :true="true"
                  :false="false"
                  :id="'select_all_deal_documents'"
                />
                <label class="h5" :for="'select_all_deal_documents'"
                  >Deal Documents</label
                >
              </h4>
              <br />
              <ul class="col-6 list-group">
                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="download_attachments"
                      class="checkbox-input"
                      value="deal_bill_of_sale"
                      :id="'deal_bill_of_sale'"
                    />
                    <label class="pl-1" :for="'deal_bill_of_sale'"
                      >Bill Of Sale</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="download_attachments"
                      class="checkbox-input"
                      value="deal_test_drive"
                      :id="'deal_test_drive'"
                    />
                    <label class="pl-1" :for="'deal_test_drive'"
                      >Test Drive</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="download_attachments"
                      class="checkbox-input"
                      value="deal_deposit_agreement"
                      :id="'deal_deposit_agreement'"
                    />
                    <label class="pl-1" :for="'deal_deposit_agreement'"
                      >Deposit Agreement</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item" v-show="deal_type == 'Financed'">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="download_attachments"
                      class="checkbox-input"
                      value="deal_credit_application"
                      :id="'deal_credit_application'"
                    />
                    <label class="pl-1" :for="'deal_credit_application'"
                      >Credit Application</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li
                  class="list-group-item"
                  v-show="retail_or_wholesale_flag == '0' && has_trade_in"
                >
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="download_attachments"
                      class="checkbox-input"
                      value="deal_trade_in"
                      :id="'deal_trade_in'"
                    />
                    <label class="pl-1" :for="'deal_trade_in'">Trade In</label>
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li
                  class="list-group-item"
                  v-show="retail_or_wholesale_flag == '0' && has_trade_in"
                >
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="download_attachments"
                      class="checkbox-input"
                      value="deal_trade_in_disclosures"
                      :id="'deal_trade_in_disclosures'"
                    />
                    <label class="pl-1" :for="'deal_trade_in_disclosures'"
                      >Trade In Disclosures</label
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
                      v-model="download_attachments"
                      class="checkbox-input"
                      value="deal_vehicle_history_report"
                      :id="'deal_vehicle_history_report'"
                    />
                    <label class="pl-1" :for="'deal_vehicle_history_report'"
                      >Vehicle History Report</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="download_attachments"
                      class="checkbox-input"
                      value="deal_customer_worksheet"
                      :id="'deal_customer_worksheet'"
                    />
                    <label class="pl-1" :for="'deal_customer_worksheet'"
                      >Customer Worksheet</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="download_attachments"
                      class="checkbox-input"
                      value="deal_open_disclosure_agreement"
                      :id="'deal_open_disclosure_agreement'"
                    />
                    <label class="pl-1" :for="'deal_open_disclosure_agreement'"
                      >Open Disclosure Agreement</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li
                  class="list-group-item"
                  v-show="enable_alternate_bos == '1'"
                >
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="download_attachments"
                      class="checkbox-input"
                      value="deal_alternate_bill_of_sale"
                      :id="'deal_alternate_bill_of_sale'"
                    />
                    <label class="pl-1" :for="'deal_alternate_bill_of_sale'"
                      >Alternate Bill Of Sale</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li
                  class="list-group-item"
                  v-show="enable_exempt_motor_vehicle == '1'"
                >
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="download_attachments"
                      class="checkbox-input"
                      value="deal_exempt_motor_vehicle"
                      :id="'deal_exempt_motor_vehicle'"
                    />
                    <label class="pl-1" :for="'deal_exempt_motor_vehicle'"
                      >Exempt Motor Vehicle</label
                    >
                  </div>
                </li>
                <!-- list element : END -->
              </ul>
              <hr class="w-100" />
              <h4 class="col-12">
                <input
                  type="checkbox"
                  v-model="select_all_inventory_documents"
                  class="checkbox-input"
                  :true="true"
                  @change="
                    allInventoryAttachmentSelection(
                      select_all_inventory_documents
                    )
                  "
                  :false="false"
                  :id="'select_all_inventory_documents'"
                />
                <label class="h5" :for="'select_all_inventory_documents'"
                  >Inventory Documents</label
                >
              </h4>
              <br />
              <ul class="col-6 list-group">
                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="download_attachments"
                      class="checkbox-input"
                      value="inventory_vehicle_history_report"
                      :id="'inventory_vehicle_history_report'"
                    />
                    <label
                      class="pl-1"
                      :for="'inventory_vehicle_history_report'"
                      >Vehicle History Report</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="download_attachments"
                      class="checkbox-input"
                      value="inventory_vehicle_repair_report"
                      :id="'inventory_vehicle_repair_report'"
                    />
                    <label class="pl-1" :for="'inventory_vehicle_repair_report'"
                      >Vehicle Repair Report</label
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
                      v-model="download_attachments"
                      class="checkbox-input"
                      value="inventory_purchase"
                      :id="'inventory_purchase'"
                    />
                    <label class="pl-1" :for="'inventory_purchase'"
                      >Inventory Purchase</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="download_attachments"
                      class="checkbox-input"
                      value="inventory_costs"
                      :id="'inventory_costs'"
                    />
                    <label class="pl-1" :for="'inventory_costs'"
                      >Inventory Expenses</label
                    >
                  </div>
                </li>
                <!-- list element : END -->
              </ul>
            </div>
          </div>

          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              type="close"
              class="btn btn-primary"
              data-dismiss="modal"
              aria-label="Close"
              @click="downloadDocumentZIP"
            >
              <i class="bx bxs-folder mr-50" style="top: 3px"></i> Download
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- model for sharing : END -->
  </span>
</template>
<script>
import Toast from "../../../library/Toast";
export default {
  props: {
    id: { required: true },
    enable_exempt_motor_vehicle: { required: true, default: "0" },
    enable_alternate_bos: { required: true, default: "0" },
    retail_or_wholesale_flag: { required: true, default: "0" },
    has_trade_in: { required: true, default: false },
    deal_type: { require: true, default: "Cash" },
  },
  name: "DealAttachmentZip",
  data() {
    return {
      download_attachments: [],
      select_all_inventory_documents: false,
      select_all_deal_documents: false,
      inventory_attachments: [
        "inventory_vehicle_history_report",
        "inventory_vehicle_repair_report",
        "inventory_purchase",
        "inventory_costs",
      ],
    };
  },
  watch: {
    getDealAttachments: function (newVal) {
      console.log("");
    },
    download_attachments: function (newVal) {
      const currentInventorySelectedAttachments = newVal.filter((value) =>
        this.inventory_attachments.includes(value)
      );

      if (currentInventorySelectedAttachments.length < this.inventory_attachments.length) {
        this.select_all_inventory_documents = false;
      } else if (currentInventorySelectedAttachments.length >= this.inventory_attachments.length) {
        this.select_all_inventory_documents = true;
      }

      const currentDocumentSelectedAttachments = newVal.filter((value) =>
        this.getDealAttachments.includes(value)
      );

      if (currentDocumentSelectedAttachments.length < this.getDealAttachments.length) {
        this.select_all_deal_documents = false;
      } else if (currentDocumentSelectedAttachments.length >= this.getDealAttachments.length) {
        this.select_all_deal_documents = true;
      }
    },
  },
  computed: {
    getDealAttachments() {
      let deal_attachments = [
        "deal_bill_of_sale",
        "deal_test_drive",
        "deal_deposit_agreement",
        "deal_vehicle_history_report",
        "deal_customer_worksheet",
        "deal_open_disclosure_agreement",
      ];

      if (this.deal_type === "Financed") {
        deal_attachments.push("deal_credit_application");
      }

      if (this.retail_or_wholesale_flag == "0" && this.has_trade_in) {
        deal_attachments.push("deal_trade_in");
        deal_attachments.push("deal_trade_in_disclosures");
      }

      if (this.enable_alternate_bos == "1") {
        deal_attachments.push("deal_alternate_bill_of_sale");
      }

      if (this.enable_exempt_motor_vehicle == "1") {
        deal_attachments.push("deal_exempt_motor_vehicle");
      }

      return deal_attachments;
    },
  },
  methods: {
    async allInventoryAttachmentSelection(current_selection) {
      if (current_selection) {
        this.download_attachments.push(...this.inventory_attachments);
      } else {
        for (const inventory_attachment of this.inventory_attachments) {
          this.download_attachments = this.download_attachments.filter(
            function (current_attachment) {
              return current_attachment === inventory_attachment;
            }
          );
        }
      }
    },
    async allDealAttachmentSelection(current_selection) {
      if (current_selection) {
        this.download_attachments.push(...this.getDealAttachments);
      } else {
        for (const deal_attachment of this.getDealAttachments) {
          this.download_attachments = this.download_attachments.filter(
            function (current_attachment) {
              return current_attachment === deal_attachment;
            }
          );
        }
      }
    },
    async downloadDocumentZIP() {
      const toast = new Toast(`Downloading the zip...`);
      toast.fire();

      let formData = new FormData();
      formData.append("current_module_id", this.id);

      for (const attachment of this.download_attachments) {
        formData.append("selected_attachments[]", attachment);
      }

      const vm = this;
      await axios({
        method: "post",
        responseType: "blob",
        data: formData,
        url:
          window.location.origin +
          `/api/business/deals/attachments/zip/${this.id}`,
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
          pom.setAttribute("download", `DEAL_${this.id}.zip`);
          pom.click();

          toast.success(`ZIP downloaded successfully.`);
          toast.close();
        })
        .catch((error) => {
          toast.fail(
            `Either there was error in downloading the zip or per day limit has been exceeded.`
          );
          toast.close();
        });
    },
  },
};
</script>