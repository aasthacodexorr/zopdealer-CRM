<template>
  <div
    role="tabpanel"
    class="tab-pane"
    id="setting-account-miscellaneous"
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
                      >Miscellaneous Settings</span
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
                <label>Include Interest In Total</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.total_balance_cost_includes_interest"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Financing-Special"
                    id="total_balance_cost_includes_interest_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="total_balance_cost_includes_interest_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label
                  >Override Salesperson as approver of wholesale deal</label
                >
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="
                      form.override_salesperson_as_approver_of_wholesale_deal
                    "
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Financing-Special"
                    id="override_salesperson_as_approver_of_wholesale_deal_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="override_salesperson_as_approver_of_wholesale_deal_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable Payment Section For Wholesale Deals</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.enable_payment_section_for_wholesale_deal"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Enable Payment Section For Wholesale Deal"
                    id="enable_payment_section_for_wholesale_deal_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="enable_payment_section_for_wholesale_deal_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable Inventory Import</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.enable_import"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Financing-Special"
                    id="enable_import_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="enable_import_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable Mark Inventory Sold Button</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.enable_inventories_sold_button"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Show inventories sold button"
                    id="enable_inventories_sold_button_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="enable_inventories_sold_button_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable Mark Inventory Unpublished Button</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.enable_inventories_unpublished_button"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Show inventories sold button"
                    id="enable_inventories_unpublished_button_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="enable_inventories_unpublished_button_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable Inventory Purge</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.enable_purge"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Financing-Special"
                    id="enable_purge_switch"
                  />
                  <label class="custom-control-label" for="enable_purge_switch">
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable Inventory Images And Files Deletion</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.enable_inventory_purge_assets"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Financing-Special"
                    id="enable_inventory_purge_assets_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="enable_inventory_purge_assets_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable Image Overlay Queue</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.image_overlay_queuing"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Financing-Special"
                    id="image_overlay_queuing_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="image_overlay_queuing_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable Service Module</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.enable_service_module"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Enable Service Module"
                    id="enable_service_module_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="enable_service_module_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Show Inspection Report Notice</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.show_vehicle_inspection_report_notice"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="inspection_report_notice"
                    id="inspection_report_notice_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="inspection_report_notice_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Download Third Party Images (For Inbound)</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.download_inventory_third_party_image"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="download_inventory_third_party_image"
                    id="download_inventory_third_party_image_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="download_inventory_third_party_image_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable Alternate Bill Of Sale</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.enable_alternate_bos"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Financing-Special"
                    id="enable_alternate_bos_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="enable_alternate_bos_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable Buyers Guide</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.enable_buyers_guide"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Enable Buyers Guide"
                    id="enable_buyers_guide_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="enable_buyers_guide_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable Exempt Motor Vehicle</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.enable_exempt_motor_vehicle"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Financing-Special"
                    id="enable_exempt_motor_vehicle_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="enable_exempt_motor_vehicle_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Business Website</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="business_website"
                  class="form-control"
                  v-model="form.business_website"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Website Page Slug</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="inventory_type"
                  class="form-control"
                  v-model="form.inventory_type"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Wordpress Type</label>
              </div>
              <div class="col-md-8 form-group">
                <select
                  name="select_wordpress_type"
                  class="form-control"
                  v-model="form.wordpress_type"
                >
                  <option value="0">Current Wordpress (Default)</option>
                  <option value="1">Ankur (Legacy)</option>
                  <option value="2">Phaeton</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable Website Auto Purge on Update</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.website_auto_purge_on_update"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="website_auto_purge_on_update"
                    id="website_auto_purge_on_update_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="website_auto_purge_on_update_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Business Logo</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="business_logo"
                  class="form-control"
                  v-model="form.business_logo"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Subscription Client Id</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="subscription_client_id"
                  class="form-control"
                  v-model="form.subscription_client_id"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Show Billing Menu</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.show_billing"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="show_billing"
                    id="show_billing_switch"
                  />
                  <label class="custom-control-label" for="show_billing_switch">
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Past Due Notification</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.show_pastduenotification"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="show_pastduenotification"
                    id="show_pastdue_notification_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="show_pastdue_notification_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Enable Landscape Window Sticker</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.show_landscapewindowsticker"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="show_landscapewindowsticker"
                    id="show_landscapewindowsticker_switch"
                  />
                  <label
                    class="custom-control-label"
                    for="show_landscapewindowsticker_switch"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Faceboook Ads Account Id</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="fbads_account_id"
                  class="form-control"
                  v-model="form.fbads_account_id"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Faceboook Ads Value+ (%)</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="fbads_value"
                  class="form-control"
                  v-model="form.fbads_value"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Faceboook Ads Budget</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="fbads_budget"
                  class="form-control"
                  v-model="form.fbads_budget"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Email Destination ( Facebook Ads)</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="facebook_ads_email"
                  class="form-control"
                  v-model="form.facebook_ads_email"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>ADF Email Destination ( Facebook Ads)</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="facebook_ads_email_adf"
                  class="form-control"
                  v-model="form.facebook_ads_email_adf"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Lead Assignment Strategy</label>
              </div>
              <div class="col-md-8 form-group">
                <select
                  name="lead_auto_assignment_strategy"
                  class="form-control"
                  v-model="form.lead_auto_assignment_strategy"
                >
                  <option value="">None</option>
                  <option value="1">Random</option>
                  <option value="2">Round Robin</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>ADF Default Provider Name</label>
              </div>
              <div class="col-md-8 form-group">
                <input
                  name="adf_default_provider_name"
                  class="form-control"
                  v-model="form.adf_default_provider_name"
                />
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>Override Comment With Extended Data In ADF</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.override_comment_with_extended_data"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="override_comment_with_extended_data"
                    id="show_override_comment_with_extended_data"
                  />
                  <label
                    class="custom-control-label"
                    for="show_override_comment_with_extended_data"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>GPS Status for Test Dive </label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                      type="checkbox"
                      class="custom-control-input"
                      v-model="form.testdrive_gps_enabled"
                      :true-value="'1'"
                      :false-value="'0'"
                      name="testdrive_gps_enabled"
                      id="show_testdrive_gps_enabled"
                  />
                  <label
                      class="custom-control-label"
                      for="show_testdrive_gps_enabled"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label>TestDrive Client package</label>
              </div>
              <div class="col-md-8 form-group">
                <select
                    name="testdrive_package"
                    class="form-control"
                    v-model="form.testdrive_package"
                >
                  <option value="automatepro">Automatepro</option>
                  <option value="advanced">Advanced</option>
                  <option value="hyperboost">Hyperboost</option>
                </select>
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
  name: "SettingsAccountMiscellaneous",
  data() {
    return {
      form: {
        business_logo: "",
        inventory_type: "",
        total_balance_cost_includes_interest: "0",
        enable_payment_section_for_wholesale_deal: "0",
        override_salesperson_as_approver_of_wholesale_deal: "0",
        enable_purge: "0",
        enable_inventory_purge_assets: "0",
        enable_import: "0",
        enable_alternate_bos: "0",
        enable_exempt_motor_vehicle: "0",
        enable_inventories_sold_button: "0",
        enable_inventories_unpublished_button: "0",
        image_overlay_queuing: "0",
        business_website: "",
        wordpress_type: "",
        show_vehicle_inspection_report_notice: "1",
        download_inventory_third_party_image: "0",
        subscription_client_id: "",
        show_billing: "0",
        show_pastduenotification: "0",
        show_landscapewindowsticker: "0",
        fbads_account_id: "",
        fbads_value: "",
        facebook_ads_email: "",
        facebook_ads_email_adf: "",
        enable_service_module: "0",
        enable_buyers_guide: "0",
        lead_auto_assignment_strategy: "",
        fbads_budget: "0",
        adf_default_provider_name: "Zop Media",
        override_comment_with_extended_data: "0",
        website_auto_purge_on_update: "0",
        testdrive_gps_enabled:"1",
        testdrive_package:"",
      },
    };
  },
  mixins: [ConfigurationMixin],
  async mounted() {
    this.submit = false;
    await this.getModuleConfiguration();
    await this.getModuleConfiguration("inventory");
    await this.getModuleConfiguration("deal");
    await this.getModuleConfiguration("lead");
    await this.getModuleConfiguration("testdrive");
    this.submit = true;
  },
};
</script>
