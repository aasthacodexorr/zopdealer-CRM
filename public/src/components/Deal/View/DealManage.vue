<template>
  <div>
    <div
      class="app-content content fixed-height-block"
      style="overflow: visible"
    >
      <review-request
        v-show="updateView"
        :phone="phone"
        :email="primary_customer_email"
        :customer_id="customer_id"
        :show="false"
      ></review-request>

      <deal-attachment-zip
        :id="id"
        :enable_exempt_motor_vehicle="enable_exempt_motor_vehicle"
        :enable_alternate_bos="enable_alternate_bos"
        :retail_or_wholesale_flag="retail_or_wholesale_flag"
        :has_trade_in="has_trade_in"
        :deal_type="deal_type"
      ></deal-attachment-zip>

      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <router-link
              title="Back To List"
              tag="a"
              :to="{ name: 'deals' }"
              class="nav-link pr-0 pl-0 d-flex align-items-center"
              exact
            >
              <i class="bx bx-caret-left" style="font-size: 1.4em"></i>
            </router-link>
          </li>
          <li class="nav-item pr-0 pl-0">
            <a
              class="nav-link d-flex align-items-center active"
              @click="
                show_deal_submit_button = true;
                show_deal_trade_in_submit_button = false;
                show_deal_trade_in_disclosures_submit_button = false;
              "
              id="deal-create-tab"
              data-toggle="tab"
              href="#deal-create-panel"
              aria-controls="Deal Create"
              role="tab"
              aria-selected="true"
            >
              <span class="">{{ tabTitle }}</span>
            </a>
          </li>

          <li
            class="nav-item pr-0 pl-0"
            v-show="updateView && parseInt(this.retail_or_wholesale_flag) == 0"
          >
            <a
              class="nav-link d-flex align-items-center"
              @click="
                show_deal_submit_button = false;
                show_deal_trade_in_submit_button = true;
                show_deal_trade_in_disclosures_submit_button = false;
              "
              id="deal-tradein-tab"
              data-toggle="tab"
              href="#deal-tradein-panel"
              aria-controls="Deal TradeIn"
              role="tab"
              aria-selected="true"
            >
              <span class="">Trade In</span>
            </a>
          </li>

          <li
            class="nav-item pr-0 pl-0"
            v-show="
              updateView &&
                has_trade_in &&
                parseInt(this.retail_or_wholesale_flag) == 0
            "
          >
            <a
              class="nav-link d-flex align-items-center"
              @click="
                show_deal_submit_button = false;
                show_deal_trade_in_submit_button = false;
                show_deal_trade_in_disclosures_submit_button = true;
              "
              id="deal-tradein-disclosure-tab"
              data-toggle="tab"
              href="#deal-tradein-disclosure-panel"
              aria-controls="Trade In Disclosures"
              role="tab"
              aria-selected="true"
            >
              <span class="">Trade In Disclosures</span>
            </a>
          </li>

          <li class="nav-item pr-0 pl-0" v-show="updateView">
            <a
              class="nav-link d-flex align-items-center"
              @click="
                show_deal_submit_button = false;
                show_deal_trade_in_submit_button = false;
                show_deal_trade_in_disclosures_submit_button = false;
              "
              id="deal-worksheet-tab"
              data-toggle="tab"
              href="#deal-worksheet-panel"
              aria-controls="Deal Worksheet"
              role="tab"
              aria-selected="true"
            >
              <span class="">Worksheet</span>
            </a>
          </li>

          <li class="nav-item pr-0 pl-0" v-show="updateView">
            <a
              class="nav-link d-flex align-items-center"
              @click="
                show_deal_submit_button = false;
                show_deal_trade_in_submit_button = false;
                show_deal_trade_in_disclosures_submit_button = false;
              "
              id="deal-note-tab"
              data-toggle="tab"
              href="#deal-note-panel"
              aria-controls="Deal Note"
              role="tab"
              aria-selected="true"
            >
              <span class="">Notes</span>
            </a>
          </li>

          <li class="nav-item pr-0 pl-0" v-show="updateView">
            <a
              class="nav-link d-flex align-items-center"
              @click="
                show_deal_submit_button = false;
                show_deal_trade_in_submit_button = false;
                show_deal_trade_in_disclosures_submit_button = false;
              "
              id="deal-delivery-checklist-tab"
              data-toggle="tab"
              href="#deal-delivery-checklist-panel"
              aria-controls="Deal Delivery Checklist"
              role="tab"
              aria-selected="true"
            >
              <span class="">Delivery Checklist</span>
              <span
                v-show="
                  delivery_checklist_count != null &&
                    delivery_checklist_count != '0'
                "
                class="badge badge-light-danger badge-pill badge-round ml-50"
                >{{ delivery_checklist_count }}</span
              >
            </a>
          </li>

          <li class="nav-item pr-0 pl-0" v-show="updateView && isAuthorized">
            <a
              class="nav-link d-flex align-items-center"
              @click="
                show_deal_submit_button = false;
                show_deal_trade_in_submit_button = false;
                show_deal_trade_in_disclosures_submit_button = false;
              "
              id="deal-sale-commission-tab"
              data-toggle="tab"
              href="#deal-sale-commission-panel"
              aria-controls="Deal Sales Commission"
              role="tab"
              aria-selected="true"
            >
              <span class="">Commission</span>
            </a>
          </li>

          <li class="nav-item pr-0 pl-0" v-show="updateView">
            <a
              class="nav-link d-flex align-items-center"
              @click="
                show_deal_submit_button = false;
                show_deal_trade_in_submit_button = false;
                show_deal_trade_in_disclosures_submit_button = false;
              "
              id="generic-view-payment-tab"
              data-toggle="tab"
              href="#generic-view-payment-panel"
              aria-controls="Payment Log"
              role="tab"
              aria-selected="true"
            >
              <span class="">Payment Log</span>
            </a>
          </li>
          <li class="nav-item pr-0 pl-0" v-show="updateView  && isAuthorized">
            <a
                class="nav-link d-flex align-items-center"
                @click="
                show_deal_submit_button = false;
                show_deal_trade_in_submit_button = false;
                show_deal_trade_in_disclosures_submit_button = false;
              "
                id="deal-jacket-tab"
                data-toggle="tab"
                href="#deal-jacket-panel"
                aria-controls="Deal Jacket"
                role="tab"
                aria-selected="true"
            >
              <span class="">Deal Jacket</span>
            </a>
          </li>
          <li
            class="nav-item pr-0 pl-0"
            v-show="updateView && isDealerTrackEnabled"
          >
            <a
              class="nav-link d-flex align-items-center"
              @click="
                show_deal_submit_button = false;
                show_deal_trade_in_submit_button = false;
                show_deal_trade_in_disclosures_submit_button = false;
              "
              id="deal-dealertrack-tab"
              data-toggle="tab"
              href="#deal-dealertrack-panel"
              aria-controls="DealerTrack Log"
              role="tab"
              aria-selected="true"
            >
              <span class="">DealerTrack Log</span>
              <span
                v-show="getDealertrackCount != null && getDealertrackCount != 0"
                class="badge badge-light-primary badge-pill badge-round ml-50"
              >
                {{ getDealertrackCount }}</span
              >
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="">
        <div class="content-body mb-3" :class="dealIsLocked ? 'freeze' : ''">
          <!-- form layout section start -->
          <div class="tab-content">
            <deal-create
              :id="id"
              :app_metadata="app_metadata"
              :has_trade_in="has_trade_in"
              :allUsers="allUsers"
              :key="viewKey"
              :prev_name="prev_name"
              :preselected_stock_no="preselected_stock_no"
              :preselected_customer_id="preselected_customer_id"
            ></deal-create>
            <deal-worksheet
              :purchase_price="purchase_price"
              :allUsers="allUsers"
              :id="id"
              :trade_value="trade_value"
              :trade_equity="trade_equity"
              :lien_value="lien_value"
              :deal_type="deal_type"
              :app_metadata="app_metadata"
              :retail_or_wholesale_flag="retail_or_wholesale_flag"
            ></deal-worksheet>
            <deal-trade-in
              :id="id"
              :retail_or_wholesale_flag="retail_or_wholesale_flag"
            ></deal-trade-in>
            <deal-note
              :allUsers="allUsers"
              :app_metadata="app_metadata"
              :id="id"
              :time_zone="timeZoneName"
            ></deal-note>
            <deal-delivery-checklist
              :allUsers="allUsers"
              :app_metadata="app_metadata"
              :id="id"
              :time_zone="timeZoneName"
              :primary_customer_name="primary_customer_name"
              :inventory_details="inventory_details"
            ></deal-delivery-checklist>
            <deal-sale-commission
              :allUsers="allUsers"
              :app_metadata="app_metadata"
              :id="id"
              :time_zone="timeZoneName"
            ></deal-sale-commission>
            <s-m-s-conversation
              :app_metadata="app_metadata"
              :user_metadata="user_metadata"
              :application="application"
              :phone_no="phone"
              :time_zone="timeZoneName"
            ></s-m-s-conversation>
            <deal-trade-in-disclosure
              :allUsers="allUsers"
              :app_metadata="app_metadata"
              :time_zone="timeZoneName"
              :primary_customer_name="primary_customer_name"
              :inventory_details="inventory_details"
            ></deal-trade-in-disclosure>
            <deal-jacket
              :allUsers="allUsers"
              :app_metadata="app_metadata"
              :time_zone="timeZoneName"
            ></deal-jacket>
            <module-wise-entity-payment
              :allUsers="allUsers"
              :app_metadata="app_metadata"
              :time_zone="timeZoneName"
              :module="`Deal`"
              :module_id="id"
              :show="updateView"
              :show_notice="true"
              :default_email="primary_customer_email"
              :default_phone="phone"
              :default_name="primary_customer_name"
            >
            </module-wise-entity-payment>
            <deal-dealertrack-log
              :dealertrack_logs="dealertrack_logs"
            ></deal-dealertrack-log>
          </div>
        </div>
      </div>
    </div>

    <!-- model for print : START -->
    <div
      class="modal fade text-left"
      id="deal-print-options"
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
                      v-model="deal_print_pdfs"
                      class="checkbox-input"
                      value="billofsale"
                      :id="'billofsale'"
                    />
                    <label class="pl-1" :for="'billofsale'">Bill Of Sale</label>
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="deal_print_pdfs"
                      class="checkbox-input"
                      value="vehicle-disclosures"
                      :id="'vehicle-disclosures'"
                    />
                    <label class="pl-1" :for="'vehicle-disclosures'"
                      >Vehicle Disclosures</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="deal_print_pdfs"
                      class="checkbox-input"
                      value="test-drive"
                      :id="'test-drive'"
                    />
                    <label class="pl-1" :for="'test-drive'">Test Drive</label>
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="deal_print_pdfs"
                      class="checkbox-input"
                      value="deposit-agreement"
                      :id="'deposit-agreement'"
                    />
                    <label class="pl-1" :for="'deposit-agreement'"
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
                      v-model="deal_print_pdfs"
                      class="checkbox-input"
                      value="credit-application"
                      :id="'credit-application'"
                    />
                    <label class="pl-1" :for="'credit-application'"
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
                      v-model="deal_print_pdfs"
                      class="checkbox-input"
                      value="trade-in"
                      :id="'trade-in'"
                    />
                    <label class="pl-1" :for="'trade-in'">Trade In</label>
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
                      v-model="deal_print_pdfs"
                      class="checkbox-input"
                      value="trade-in-disclosures"
                      :id="'trade-in-disclosures'"
                    />
                    <label class="pl-1" :for="'trade-in-disclosures'"
                      >Trade In Disclosures</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="deal_print_pdfs"
                      class="checkbox-input"
                      value="vehicle-history-report-acknowledgement"
                      :id="'vehicle-history-report-acknowledgement'"
                    />
                    <label
                      class="pl-1"
                      :for="'vehicle-history-report-acknowledgement'"
                      >Vehicle History Report Acknowledgement</label
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
                      v-model="deal_print_pdfs"
                      class="checkbox-input"
                      value="customer-worksheet"
                      :id="'customer-worksheet'"
                    />
                    <label class="pl-1" :for="'customer-worksheet'"
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
                      v-model="deal_print_pdfs"
                      class="checkbox-input"
                      value="open-disclosure-agreement"
                      :id="'open-disclosure-agreement'"
                    />
                    <label class="pl-1" :for="'open-disclosure-agreement'"
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
                      v-model="deal_print_pdfs"
                      class="checkbox-input"
                      value="alternate-billofsale"
                      :id="'alternate-billofsale'"
                    />
                    <label class="pl-1" :for="'alternate-billofsale'"
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
                      v-model="deal_print_pdfs"
                      class="checkbox-input"
                      value="exempt-motor-vehicle"
                      :id="'exempt-motor-vehicle'"
                    />
                    <label class="pl-1" :for="'exempt-motor-vehicle'"
                      >Exempt Motor Vehicle</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="deal_print_pdfs"
                      class="checkbox-input"
                      value="internal-inspection"
                      :id="'internal-inspection'"
                    />
                    <label class="pl-1" :for="'internal-inspection'"
                      >Internal Inspection</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="deal_print_pdfs"
                      class="checkbox-input"
                      value="delivery-checklist"
                      :id="'delivery-checklist'"
                    />
                    <label class="pl-1" :for="'delivery-checklist'"
                      >Delivery Checklist</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item" v-if="isAuthorized">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="deal_print_pdfs"
                      class="checkbox-input"
                      value="deal-recap"
                      :id="'deal-recap'"
                    />
                    <label class="pl-1" :for="'deal-recap'">Deal Recap</label>
                  </div>
                </li>
                <!-- list element : END -->
              </ul>
              <div
                class="col-sm-12 d-flex justify-content-left pb-1"
                v-if="deal_print_pdfs.length > 0"
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

    <!-- model for sharing : START -->
    <div
      class="modal fade text-left"
      id="deal-share-options"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Share Options"
      aria-hidden="true"
    >
      <div
        class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
        role="document"
      >
        <div class="modal-content">
          <div class="modal-header pl-1 pr-1">
            <h3 class="content-header-title" id="myModalLabel19">
              Share Documents
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
              <ul class="col-12 list-group">
                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="deal_pdfs"
                      class="checkbox-input"
                      value="private-billofsale"
                      :id="'private-billofsale'"
                    />
                    <label class="pl-1" :for="'private-billofsale'"
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
                      v-model="deal_pdfs"
                      class="checkbox-input"
                      value="private-test-drive"
                      :id="'private-test-drive'"
                    />
                    <label class="pl-1" :for="'private-test-drive'"
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
                      v-model="deal_pdfs"
                      class="checkbox-input"
                      value="private-deposit-agreement"
                      :id="'private-deposit-agreement'"
                    />
                    <label class="pl-1" :for="'private-deposit-agreement'"
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
                      v-model="deal_pdfs"
                      class="checkbox-input"
                      value="private-credit-application"
                      :id="'private-credit-application'"
                    />
                    <label class="pl-1" :for="'private-credit-application'"
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
                      v-model="deal_pdfs"
                      class="checkbox-input"
                      value="private-trade-in"
                      :id="'private-trade-in'"
                    />
                    <label class="pl-1" :for="'private-trade-in'"
                      >Trade In</label
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
                      v-model="deal_pdfs"
                      class="checkbox-input"
                      value="private-trade-in-disclosures"
                      :id="'private-trade-in-disclosures'"
                    />
                    <label class="pl-1" :for="'private-trade-in-disclosures'"
                      >Trade In Disclosures</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="deal_pdfs"
                      class="checkbox-input"
                      value="private-vehicle-history-report-acknowledgement"
                      :id="'private-vehicle-history-report-acknowledgement'"
                    />
                    <label
                      class="pl-1"
                      :for="'private-vehicle-history-report-acknowledgement'"
                      >Vehicle History Report Acknowledgement</label
                    >
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="deal_pdfs"
                      class="checkbox-input"
                      value="private-customer-worksheet"
                      :id="'private-customer-worksheet'"
                    />
                    <label class="pl-1" :for="'private-customer-worksheet'"
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
                      v-model="deal_pdfs"
                      class="checkbox-input"
                      value="private-open-disclosure-agreement"
                      :id="'private-open-disclosure-agreement'"
                    />
                    <label
                      class="pl-1"
                      :for="'private-open-disclosure-agreement'"
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
                      v-model="deal_pdfs"
                      class="checkbox-input"
                      value="private-alternate-billofsale"
                      :id="'private-alternate-billofsale'"
                    />
                    <label class="pl-1" :for="'private-alternate-billofsale'"
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
                      v-model="deal_pdfs"
                      class="checkbox-input"
                      value="private-exempt-motor-vehicle"
                      :id="'private-exempt-motor-vehicle'"
                    />
                    <label class="pl-1" :for="'private-exempt-motor-vehicle'"
                      >Exempt Motor Vehicle</label
                    >
                  </div>
                </li>
                <!-- list element : END -->
              </ul>
              <div
                class="col-sm-12 d-flex justify-content-left pb-1"
                v-if="deal_pdfs.length > 0"
              >
                <router-link
                  tag="button"
                  type="close"
                  :to="{
                    name: 'email',
                    params: {
                      email_address: primary_customer_email,
                      subject: getEmailSubject,
                      body: bodyMessage(),
                    },
                  }"
                  class="btn btn-primary mr-1 mt-1"
                  data-dismiss="modal"
                  aria-label="Close"
                >
                  <i class="bx bx-mail-send mr-1"></i> Personalized Email
                </router-link>
                <button
                  type="close"
                  class="btn btn-primary mr-1 mt-1"
                  data-dismiss="modal"
                  aria-label="Close"
                  @click="quickSend()"
                >
                  <i class="bx bxs-send mr-1"></i> Quick Share
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- model for sharing : END -->
    <!-- Action Bar : START -->
    <ul class="nav nav-tabs p-50 custom-footer-fixed-action-bar" role="tablist">
      <button
        id="dealTransferButton"
        :data-private-id="private_share_id"
        class="btn btn-primary mr-1 ml-4"
        @click.prevent="doCopy(private_share_id)"
      >
        <i class="bx bxs-cloud-upload"></i>
      </button>

      <!-- dropdown menu : START -->
      <div
        class="btn-group mr-1"
        role="group"
        v-if="updateView && isDealerTrackEnabled"
      >
        <button
          style="line-height: 1.2em; background-color: #015b95"
          type="button"
          class="btn btn-link"
          :disabled="getDealertrackCount > 0"
          @click="dealerTrackPopup()"
        >
          <img
            class="mr-50"
            style="width: 25px"
            src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBwgHBgkIBwgKCgkLDRYPDQwMDRsUFRAWIB0iIiAdHx8kKDQsJCYxJx8fLT0tMTU3Ojo6Iys/RD84QzQ5OjcBCgoKDQwNGg8PGjclHyU3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3N//AABEIADgAOAMBEQACEQEDEQH/xAAcAAEBAAICAwAAAAAAAAAAAAAABgMHAgQBBQj/xAAyEAABAgQEBQEGBwEAAAAAAAABAgMABAURBhITMQcUIVFhkSJBUnGBsSQyQ3OSodIV/8QAGgEBAAIDAQAAAAAAAAAAAAAAAAEEAgMFBv/EACkRAAICAQMDAgYDAAAAAAAAAAACAQMRBBIxEyFhFEEFIlFxgZEVsdH/2gAMAwEAAhEDEQA/AIuPTHlxACAEAIAQAgBACAEAZGGHpheSXacdXa+VtJUfQREzEckwst2gy8hO62jycxrZc2npKzZe9rbRG9cZyZdNs4wYn2HpdeSYacaXa+VxJSfQxMTE8GMrK9pMcSQIA7VOp83U5tEpIS635hf5UI+57DyYxd1SNzT2M0RnnCwVaeF+JCkEpk0k+4v9R/UVfX0+S3/H2+Cg4T0F2QrVYdmshdlLSuZCrpzE3WAfFk+sV9ddDosR79yxoqZR2mfbsUOFD/0sVYjrF7obeTIMHsGx7dvBNjFe75akT8/ssU/NY7/gh6phusYzrdTq9PDHK80phCnXMtwgBNx026feLtd9enRUbn/SlZp7NQ8uvBBrGVak3CrG107H5Rfg58xiTjAg29wZpqWaVO1NxAC33dJCj8CR1t9Sf4xyfiD5eE+h1/h6YSW+pOzvFGuGdf5PlBLaitHMyScl+lzfe1osLoK9sbs5ND6+zdOOC1wk4qi4Beq81Yvvpen3T8alXKfUBMUr46l+yPsXaJ2Ub555PFEWrDfDPnXT+IMqqZUpW6nHOqb+bqSIWR1dRtjjOP0EnpafdP3NZ0zG1YpdGFJkjLolwlaQrTuv2iSTe+/WOk+krd98nMTV2ImyCb2iyVRAH0PhukCTwfKUtZU0pUrZ0oNlJUsEqt5uox5+2zdbL+T0NSQtUJ4PQjhVh4fr1C37qf8AMb/X3eDR6GnycMcVGSmJaRwfS3UrdmnmmHUtKvoNJI3PfoOnYH6tOjRM3N7f2TqHWYilfc6XFCvSkxJsYcpr7S3nHU62VV0tpTskke+9jbx5EbNFU0T1Wjsa9Zcsr0l5NWTkuqUmXGFqSooNsyTcH5R1FbdGTlNG2cGGJMRACw7CBORYdhAZFhAgQAgBACAEAIAQAgBACAP/2Q=="
            alt=""
            srcset=""
          />
          <span class="text-white">Export</span>
        </button>
      </div>

      <!-- dropdown menu : END -->

      <!-- dropdown menu : START -->
      <div class="btn-group mr-1" role="group" v-if="updateView">
        <button
          id="dealSendDropDown"
          style="line-height: 1.2em"
          type="button"
          class="btn btn-primary"
          data-toggle="modal"
          data-target="#deal-share-options"
        >
          <i class="bx bxs-send mr-50"></i> Share
        </button>
      </div>

      <!-- dropdown menu : END -->

      <!-- dropdown menu : START -->
      <div class="btn-group mr-1" role="group" v-if="updateView">
        <button
          id="dealPrintDropDown"
          style="line-height: 1.2em"
          type="button"
          class="btn btn-secondary"
          data-toggle="modal"
          data-target="#deal-print-options"
        >
          <i class="bx bx-printer mr-50"></i> Print
        </button>
      </div>

      <!-- dropdown menu : END -->

      <!-- dropdown menu : START -->
      <div
        class="btn-group mr-1"
        role="group"
        v-if="updateView && isAuthorized"
      >
        <button
          style="line-height: 1.2em"
          type="button"
          class="btn btn-secondary"
          data-toggle="modal"
          data-target="#deal-attachment-zip"
        >
          <i class="bx bx-file"></i>
        </button>
      </div>
      <!-- dropdown menu : END -->

      <review-request-c-t-a
        v-show="updateView"
        :phone="phone"
        :email="primary_customer_email"
        :customer_id="customer_id"
      ></review-request-c-t-a>

      <button
        v-if="showLockButton && !dealIsLocked && isAuthorized"
        class="btn btn-primary mr-1 ml-4"
        @click="lockWarning"
      >
        <i class="bx bxs-lock"></i>
      </button>

      <button
        v-if="dealIsLocked && isAuthorized"
        class="btn btn-success mr-1 ml-4"
        @click="unlockConfirmation"
      >
        <i class="bx bxs-lock-open"></i>
      </button>

      <button
        v-show="show_deal_submit_button && !dealIsLocked"
        type="submit"
        :disabled="!submit_button"
        class="btn btn-primary mr-1 ml-4"
        @click="createEvent"
      >
        Save
      </button>

      <button
        v-show="show_deal_trade_in_submit_button && !dealIsLocked"
        type="submit"
        :disabled="!submit_button"
        class="btn btn-primary mr-1 ml-4"
        @click="createDealTradeInEvent"
      >
        Save
      </button>

      <button
        v-show="show_deal_trade_in_disclosures_submit_button && !dealIsLocked"
        type="submit"
        :disabled="!submit_button"
        class="btn btn-primary mr-1 ml-4"
        @click="createDealTradeInDisclosureEvent"
      >
        Save
      </button>
    </ul>
    <!-- Action Bar : END-->
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import DealCreate from "./DealCreate";
import DealWorksheet from "./DealWorksheet";
import DealTradeIn from "./DealTradeIn";
import DealTradeInDisclosure from "./DealTradeInDisclosure";
import DealNote from "./DealNote";
import DealDeliveryChecklist from "./DealDeliveryChecklist";
import DealSaleCommission from "./DealSaleCommission";
import DealDealertrackLog from "./DealDealertrackLog";
import Toast from "../../../library/Toast";
import SMSConversation from "../../WebComponents/SMSConversation";
import ReviewRequest from "../../WebComponents/ReviewRequest.vue";
import moment from "moment-timezone";
import ReviewRequestCTA from "../../WebComponents/ReviewRequestCTA.vue";
import ModuleWiseEntityPayment from "../../Payment/ModuleWiseEntityPayment.vue";
import DealAttachmentZip from "./DealAttachmentZip.vue";
import DealJacket from "./DealJacket.vue";

export default {
  props: {
    id: { required: false },
    application: { required: true },
    app_metadata: { required: true },
    user_metadata: { required: true },
    preselected_stock_no: { required: false, default: "", type: String },
    preselected_customer_id: { required: false, default: "" },
  },
  name: "DealManage",
  components: {
    DealCreate,
    DealWorksheet,
    DealTradeIn,
    DealNote,
    SMSConversation,
    DealDeliveryChecklist,
    DealTradeInDisclosure,
    DealSaleCommission,
    ReviewRequest,
    ReviewRequestCTA,
    ModuleWiseEntityPayment,
    DealDealertrackLog,
    DealAttachmentZip,
    DealJacket,
  },
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      vm.prev_name = from.name;
    });
  },
  data() {
    return {
      prev_name: null,
      show_deal_submit_button: true,
      show_deal_trade_in_submit_button: false,
      show_deal_trade_in_disclosures_submit_button: false,
      submit_button: true,
      deal_type: "Cash",
      private_share_id: null,
      primary_customer_email: "",
      primary_customer_name: "Customer",
      retail_or_wholesale_flag: 0,
      lien_value: "0",
      trade_equity: "0",
      trade_value: "0",
      has_trade_in: false,
      purchase_price: 0,
      deal_pdfs: [],
      deal_print_pdfs: [],
      phone: null,
      delivery_checklist_count: null,
      inventory_details: null,
      enable_alternate_bos: "0",
      dealertrack_dealer_id: "",
      dealertrack_dealer_confirm_id: "",
      enable_exempt_motor_vehicle: "0",
      customer_id: null,
      is_frozen: false,
      deal_status: "Open",
      deal_lock: "1",
      dealertrack_logs: [],
    };
  },
  computed: {
    ...mapGetters("dealView", [
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
    getEmailSubject() {
      let subject = `Bill Of Sale #${this.$route.params.id}`;
      if (this.inventory_details) {
        subject += ` for ${this.inventory_details}.`;
      }
      return subject;
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    viewKey() {
      return this.$route.params.id + "-" + this.$route.name;
    },
    tabTitle() {
      return this.$route.params.id && this.$route.name == "deal-update"
        ? "Update"
        : "Create";
    },
    updateView() {
      return this.$route.params.id && this.$route.name == "deal-update";
    },
    timeZoneName() {
      return this.user_metadata && this.user_metadata.hasOwnProperty("timezone")
        ? this.user_metadata.timezone
        : "Canada/Eastern";
    },
    showLockButton() {
      return this.deal_status === "Delivered" ? true : false;
    },
    dealIsLocked() {
      return this.deal_lock == "1" ? true : false;
    },
    isDealerTrackEnabled() {
      return (
        this.dealertrack_dealer_confirm_id.length > 1 &&
        this.dealertrack_dealer_id.length > 1
      );
    },
    getDealertrackCount() {
      return this.dealertrack_logs.length;
    },
  },
  methods: {
    ...mapActions("dealView", [
      "commitOnlyEntities",
      "commitCurrentEntity",
      "commitLoadState",
    ]),
    ...mapActions("users", ["getAllUsers"]),
    doCopy(current_string) {
      this.$helpers.doCopy(this, current_string);
    },
    async lockADeal() {
      const toast = new Toast(`Locking a deal ...`);
      toast.fire();

      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/deals/lock/${this.$route.params.id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
          this.deal_lock = "1";
        })
        .catch(() => {
          toast.fail(`There were errors in locking the deal.`);
          toast.close();
        });

      this.$helpers.gTagClickEvent(this, "zd_action", "deal", "deal_lock", "");
    },
    async unlockConfirmation() {
      const { value: password } = await this.$swal.fire({
        text: "Please enter your password to unlock the deal.",
        input: "password",
        inputLabel: "Password",
        inputPlaceholder: "Enter your password",
        showCancelButton: true,
        cancelButtonText: "Back",
        inputAttributes: {
          autocapitalize: "off",
          autocorrect: "off",
        },
      });
      if (password) {
        this.unLockADeal(password);
      }
    },
    async unLockADeal(password) {
      const toast = new Toast(`Unlocking a deal ...`);
      toast.fire();

      let formData = new FormData();
      formData.append("password", password);

      await axios({
        method: "post",
        data: formData,
        url: `${window.location.origin}/api/business/deals/unlock/${this.$route.params.id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
          this.deal_lock = "0";
        })
        .catch(() => {
          toast.fail(`There were errors in unlocking the deal.`);
          toast.close();
        });

      this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "deal",
        "deal_unlock",
        "",
      );
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
          vm.downloadDealPDF(slug);
        }
      });
    },
    async lockWarning() {
      const vm = this;
      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Back",
        confirmButtonText: "I Understand",
        toast: false,
        position: "center",
        text:
          "Once the deal is locked, you cannot change any details for the deal.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.lockADeal();
        }
      });
    },
    createEvent() {
      this.submit_button = false;
      Event.fire("save_deal", null);
    },
    createDealTradeInEvent() {
      this.submit_button = false;
      Event.fire("save_deal_trade_in", null);
    },
    createDealTradeInDisclosureEvent() {
      this.submit_button = false;
      Event.fire("save_deal_trade_in_disclosures", null);
    },
    async downloadDealPDF(type) {
      const vm = this;
      await axios({
        method: "get",
        responseType: "blob",
        url:
          window.location.origin +
          `/api/business/deals/pdf/` +
          type +
          `/generate/` +
          this.id,
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

      await vm.$helpers.gTagClickEvent(
        vm,
        "zd_action",
        "deal",
        `deal_document_${type}`,
        "",
      );
    },
    async downloadDeliveryChecklistPDF() {
      const vm = this;
      await axios({
        method: "get",
        responseType: "blob",
        url:
          window.location.origin +
          `/api/business/deals/print/delivery-checklist/` +
          this.id,
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
    bodyMessage() {
      let body = `Hello, ${this.primary_customer_name}. <br>`;
      body += `Following are the links for the PDF of Deal`;

      let pdf_title_mapping = {
        "private-billofsale": "Bill Of Sale",
        "private-test-drive": "Test Drive",
        "private-deposit-agreement": "Deposit Agreement",
        "private-credit-application": "Credit Application",
        "private-trade-in": "Trade In",
        "private-trade-in-disclosures": "Trade In Disclosures",
        "private-vehicle-history-report-acknowledgement":
          "Vehicle History Report Acknowledgement",
        "private-customer-worksheet": "Customer Worksheet",
        "private-open-disclosure-agreement": "Open Disclosure Agreement",
        "private-alternate-billofsale": "Bill Of Sale",
        "private-exempt-motor-vehicle": "Exempt Motor Vehicle",
      };

      for (let single_pdf of this.deal_pdfs) {
        body += `Following is the link to the ${pdf_title_mapping[single_pdf]}.
        <a href="${window.location.origin}/api/website/private/pdf/${single_pdf}/view/${this.private_share_id}">Click To View</a><br>
        If button doesn't work, copy and paste the following link in the browser:
        <br>${window.location.origin}/api/website/private/pdf/${single_pdf}/view/${this.private_share_id}<br>`;
        body +=
          "<br> ---------------------------------------------------------- <br>";
      }

      return body;
    },
    async quickSend() {
      const vm = this;
      let formData = new FormData();

      formData.append("recipient_email", this.primary_customer_email);
      formData.append("recipient_name", this.primary_customer_name);
      formData.append("subject", this.getEmailSubject);
      formData.append("body", this.bodyMessage());

      //success message setting
      let success_message = "Documents shared successfully by email.";

      let request_url = `${window.location.origin}/api/business/real-time/notification/emails`;

      let request_message = `Sharing documents...`;

      const toast = new Toast(request_message);
      toast.fire();

      await axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(success_message);
          toast.close();
          this.deal_pdfs = [];
        })
        .catch(function(error) {
          toast.fail(error.response.data.errors);
          toast.close();
          this.deal_pdfs = [];
        });

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "deal",
        "deal_quick_send_document",
        "",
      );
    },
    async dealerTrackPopup() {
      const vm = this;
      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "No",
        confirmButtonText: "Yes",
        toast: false,
        position: "center",
        text:
          "Once the deal is sent to dealertrack, it cannot be sent again. Do you want to proceed?",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.sendToDealerTrack();
        }
      });
    },
    async sendToDealerTrack() {
      const vm = this;
      const toast = new Toast(`Sending deal to DealerTrack ...`);
      toast.fire();

      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/dealer-track/send/${this.$route.params.id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          vm.getDealertrackLogs(this.$route.params.id);
          toast.success(`Deal has been sent.`);
          toast.close();
        })
        .catch((error) => {
          let error_message =
            typeof error_message === "string"
              ? error.response.data.errors
              : error.response.data.errors.toString();
          toast.fail(error_message);
          toast.close();
        });
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
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (element.key == "enable_alternate_bos") {
              this.enable_alternate_bos = element.value;
            } else if (element.key == "enable_exempt_motor_vehicle") {
              this.enable_exempt_motor_vehicle = element.value;
            } else if (element.key == "dealertrack_dealer_id") {
              this.dealertrack_dealer_id = element.value;
            } else if (element.key == "dealertrack_dealer_confirm_id") {
              this.dealertrack_dealer_confirm_id = element.value;
            }
          }
        }
      });
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
          for (let single_pdf of this.deal_print_pdfs) {
            if (single_pdf === "delivery-checklist") {
              vm.downloadDeliveryChecklistPDF();
            } else {
              vm.downloadDealPDF(single_pdf);
            }
          }
        }
      });

      vm.deal_print_pdfs = [];
    },
    async getDealertrackLogs(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/deals/" +
          deal_id +
          "/common/dealertrack",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.dealertrack_logs = data;
        })
        .catch(({ response }) => {
          this.dealertrack_logs = [];
          console.log(response);
        });
    },
  },
  async created() {
    moment.tz.setDefault(this.timeZoneName);

    Event.listen("delivery_checklist_count", (data) => {
      this.$data.delivery_checklist_count = data;
    });

    await this.getGlobalConfigurations();
  },
  async mounted() {
    const vm = this;
    this.getAllUsers();

    if (this.updateView) {
      this.getDealertrackLogs(this.$route.params.id);
      this.commitCurrentEntity(this.$route.params.id);
    } else {
      this.deal_lock = "0";
    }

    Event.listen("change_purchase_price", (data) => {
      vm.$data.purchase_price = data;
    });

    Event.listen("change_deal_lock", (data) => {
      vm.$data.deal_lock = data;
    });

    Event.listen("change_deal_type", (data) => {
      vm.$data.deal_type = data;
    });

    Event.listen("get_private_share_id", (data) => {
      vm.$data.private_share_id = data;
    });

    Event.listen("get_primary_customer_email", (data) => {
      vm.$data.primary_customer_email = data;
    });

    Event.listen("get_primary_customer_name", (data) => {
      vm.$data.primary_customer_name = data;
    });

    Event.listen("get_deal_status", (data) => {
      vm.$data.deal_status = data;
    });

    Event.listen("get_inventory_details", (data) => {
      vm.$data.inventory_details = data;
    });

    Event.listen("get_primary_customer_phone", (data) => {
      vm.$data.phone = data;
    });

    Event.listen("get_primary_customer_id", (data) => {
      vm.$data.customer_id = data;
    });

    Event.listen("change_retail_or_wholesale", (data) => {
      vm.$data.retail_or_wholesale_flag = data;
    });

    Event.listen("change_lien_value", (data) => {
      vm.$data.lien_value = data;
    });

    Event.listen("change_trade_equity", (data) => {
      vm.$data.trade_equity = data;
    });

    Event.listen("change_trade_value", (data) => {
      vm.$data.trade_value = data;
    });

    Event.listen("has_trade_in_flag", (data) => {
      vm.$data.has_trade_in = data;
    });

    Event.listen("save_deal_trade_in_complete", (data) => {
      vm.$data.submit_button = true;

      vm.$helpers.gTagClickEvent(vm, "zd_action", "deal", "deal_trade_in", "");
    });

    Event.listen("save_deal_trade_in_disclosures_complete", (data) => {
      vm.$data.submit_button = true;

      vm.$helpers.gTagClickEvent(
        vm,
        "zd_action",
        "deal",
        "deal_trade_in_disclosure",
        "",
      );
    });

    Event.listen("save_deal_complete", (data) => {
      vm.$data.submit_button = true;

      if (data.hasOwnProperty("success")) {
        if (data.success == "Deal has been added.") {
          this.commitOnlyEntities();
        } else {
          this.commitOnlyEntities();
        }

        if (data.success == "Deal has been added.") {
          vm.$router.push({
            name: "deal-update",
            params: { id: data.deal_id },
          });
        }
      }

      vm.$helpers.gTagClickEvent(vm, "zd_action", "deal", "deal_general", "");
    });
  },
  beforeDestroy() {
    Event.vue.$off("save_deal", null);
    Event.vue.$off("save_deal_complete", null);

    Event.vue.$off("change_deal_type", null);
    Event.vue.$off("get_private_share_id", null);
    Event.vue.$off("get_primary_customer_email", null);
    Event.vue.$off("get_primary_customer_name", null);
    Event.vue.$off("get_inventory_details", null);
    Event.vue.$off("get_primary_customer_phone", null);
    Event.vue.$off("get_primary_customer_id", null);
    Event.vue.$off("change_retail_or_wholesale", null);
    Event.vue.$off("change_purchase_price", null);

    Event.vue.$off("change_lien_value", null);
    Event.vue.$off("change_trade_equity", null);
    Event.vue.$off("has_trade_in_flag", null);
    Event.vue.$off("change_trade_value", null);

    Event.vue.$off("save_deal_trade_in", null);
    Event.vue.$off("save_deal_trade_in_disclosures_complete", null);
    Event.vue.$off("save_deal_trade_in_disclosures", null);
    Event.vue.$off("save_deal_trade_in_complete", null);
    Event.vue.$off("delivery_checklist_count", null);
    Event.vue.$off("refresh_complete_break_down", null);

    Event.vue.$off("get_deal_status", null);
    Event.vue.$off("change_deal_lock", null);
  },
};
</script>
<style lang="scss">
.freeze {
  input,
  select,
  textarea,
  .v-select {
    pointer-events: none;
    background: #e7e7e7;
  }

  button {
    pointer-events: none;
  }

  .custom-control-label {
    display: none;
  }
}

#generic-view-payment-panel {
  button {
    pointer-events: initial !important;
  }
}
</style>
