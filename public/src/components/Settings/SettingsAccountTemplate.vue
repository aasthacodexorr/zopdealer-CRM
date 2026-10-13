<template>
  <div
    role="tabpanel"
    class="tab-pane"
    id="setting-account-template"
    aria-labelledby="stacked-pill-1"
    aria-expanded="true"
  >
    <div class="content-wrapper m-0 border-bottom todo-fixed-search">
      <form class="webapp-form">
        <div class="row">
          <div class="col-md-5 col-12">
            <ul class="pl-0">
              <li class="d-inline-block mr-1">
                <div class="content-list-box">
                  <p class="p-0 m-0">
                    <span
                      class="blue-color text-bold-400 title-sizeincrease-font"
                      >Template Settings</span
                    >
                  </p>
                </div>
              </li>
            </ul>
          </div>
          <div class="col-md-7 col-12">
            <ul class="p-0 float-right d-flex">
              <li class="d-inline-block">
                <button
                  type="reset"
                  class="d-none btn btn-light-secondary mr-1"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="!submit"
                  @click.prevent="saveConfiguration"
                  class="btn btn-primary"
                >
                  Save
                </button>
              </li>
            </ul>
          </div>
        </div>
      </form>
    </div>
    <div
      class="list-right-task-list list-group ps p-0"
      style="overflow-y: auto !important"
    >
      <form class="webapp-form">
        <div class="content-wrapper mt-0 pb-1 pt-2 border-bottom">
          <div class="col-md-12" v-show="!submit">
            Loading Saved Configurations ...
          </div>
          <div class="list-image-content" v-show="submit">
            <div class="row">
              <div class="col-md-4">
                <label>Wholesale Bill Template</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="account_email_name"
                  class="form-control"
                  v-model="form.wholesale_bos"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Retail Bill Template</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="account_email_address"
                  v-model="form.retail_bos"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Window Sticker Template</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="account_email_address"
                  v-model="form.wsticker_template"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Test Drive Template</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="account_email_address"
                  v-model="form.test_drive_template"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Deposit Template</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="account_email_address"
                  v-model="form.deposit_agreement"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Individual Credit Application Template</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="account_email_address"
                  v-model="form.individual_credit_application"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Trade In Template</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="account_email_address"
                  v-model="form.trade_in_template"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Trade In Disclosures Template</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="trade_in_disclosure_template"
                  v-model="form.trade_in_disclosure_template"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Public Purchase Template</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="account_email_address"
                  v-model="form.public_purchase_template"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Dealer Purchase Template</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="account_email_address"
                  v-model="form.dealer_purchase_template"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Vehicle History Report Acknowledgement Template</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="vehicle_history_report_acknowledgement_template"
                  v-model="form.vehicle_history_report_acknowledgement_template"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Customer Worksheet</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="customer_worksheet_template"
                  v-model="form.customer_worksheet_template"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Open Disclosure Agreement</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="open_disclosure_agreement"
                  v-model="form.open_disclosure_agreement"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Vehicle Disclosure Template</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="vehicle_disclosure_template"
                  v-model="form.vehicle_disclosure_template"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Deal Recap Template</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="deal_recap_template"
                  v-model="form.deal_recap_template"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Alternate Retail Bill Template</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="alternate_retail_bos"
                  v-model="form.alternate_retail_bos"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Alternate Wholesale Bill Template</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="alternate_wholesale_bos"
                  v-model="form.alternate_wholesale_bos"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Exempt Motor Vehicle Template</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="exempt_motor_vehicle_template"
                  v-model="form.exempt_motor_vehicle_template"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Vehicle Internal Inspection Template</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="vehicle_internal_inspection_template"
                  v-model="form.vehicle_internal_inspection_template"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable Buyers Guide</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="buyers_guide_template"
                  v-model="form.buyers_guide_template"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Payment Receipt</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  type="text"
                  class="form-control"
                  name="payment_receipt_template"
                  v-model="form.payment_receipt_template"
                />
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import ConfigurationMixin from "../../mixins/configuration";

export default {
  name: "SettingsAccountTemplate",
  data() {
    return {
      form: {
        wholesale_bos: "",
        retail_bos: "",
        wsticker_template: "",
        test_drive_template: "",
        deposit_agreement: "",
        individual_credit_application: "",
        trade_in_template: "",
        trade_in_disclosure_template: "",
        public_purchase_template: "",
        vehicle_history_report_acknowledgement_template: "",
        customer_worksheet_template: "",
        dealer_purchase_template: "",
        open_disclosure_agreement: "",
        vehicle_disclosure_template: "",
        deal_recap_template: "",
        alternate_retail_bos: "",
        alternate_wholesale_bos: "",
        exempt_motor_vehicle_template: "",
        vehicle_internal_inspection_template: "",
        buyers_guide_template: "",
        payment_receipt_template: "",
      },
    };
  },
  mixins: [ConfigurationMixin],
  async mounted() {
    this.submit = false;
    await this.getModuleConfiguration();
    await this.getModuleConfiguration("inventory");
    this.submit = true;
  },
};
</script>
