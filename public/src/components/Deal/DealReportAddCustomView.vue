<template>
  <!-- BEGIN: Content-->
  <div id="add_deal_custom_view">
    <div class="app-content content fixed-height-block">
      <div class="top-space-box">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center active">
              <span class="d-none d-sm-block">Deal Report Custom View</span>
            </a>
          </li>
          <div class="btn-feild-box">
            <button type="reset" class="btn btn-light-secondary mr-1">
              Cancel
            </button>
            <button
              type="submit"
              @click="save()"
              :disabled="!submit_button"
              class="btn btn-primary mr-1"
            >
              Save
            </button>
          </div>
        </ul>
      </div>
    </div>
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="content-wrapper mt-3">
        <div class="content-body"  v-show="submit_button">
          <!-- form layout section start -->
          <form class="webapp-form">
            <div class="card shadow-none mb-0">
              <div class="card-header border-line-bottom-title">
                <h4 class="card-title">Name<sup class="text-danger">*</sup></h4>
              </div>
            </div>
            <div class="card shadow-none mb-0">
              <div class="row">
                <div class="col-md-7 form-group">
                  <fieldset>
                    <div class="input-group">
                      <input
                        type="text"
                        id="Vin-Number"
                        class="form-control mr-1"
                        name=""
                        v-model="form.header"
                        placeholder="Name"
                        :class="{
                          'is-invalid': errors.hasOwnProperty('header'),
                        }"
                        @keyup="delete errors.header"
                        :title="errors.header"
                        required
                      />
                      <div class="checkbox" style="margin-top: 6px">
                        <input
                          type="checkbox"
                          class="checkbox-input"
                          id="checkbox1"
                          v-model="form.default"
                          :true-value="'1'"
                          :false-value="'0'"
                        />
                        <label for="checkbox1">Mark as Favorite</label>
                      </div>
                    </div>
                  </fieldset>
                </div>
              </div>
            </div>
            <div class="card shadow-none mt-1 mb-0">
              <div class="card-header border-line-bottom-title">
                <h4 class="card-title">Define the criteria ( if any )</h4>
              </div>
              <div class="card-content">
                <div class="card-body pt-0 pb-0">
                  <div class="contact-repeater">
                    <div class="m-width-s mb-0 pb-0 border-0">
                      <div class="row">
                        <!-- Each Row of Query : OPEN-->
                        <div
                          class="col-12 col-md-12"
                          v-for="n in query_rows"
                          :key="n"
                        >
                          <div class="row">
                            <div
                              v-if="n > 1"
                              class="col-md-2 col-12 form-group d-flex align-items-center pr-0"
                            >
                              <v-select
                                placeholder="Join"
                                :options="['AND', 'OR']"
                                v-model="form.query[n - 1].join"
                                style="width: 100%"
                              />
                            </div>

                            <div
                              class="col-md-4 col-12 form-group d-flex align-items-center pr-0"
                            >
                              <v-select
                                placeholder="Attribute"
                                :options="map_columns"
                                @input="changeQueryDetails(n - 1)"
                                v-model="form.query[n - 1].header"
                                style="width: 100%"
                                :reduce="(map_columns) => map_columns.header"
                                label="header"
                              />
                            </div>
                            <div class="col-md-4 col-12 form-group">
                              <v-select
                                placeholder="Comparator"
                                :options="map_comparator[n - 1]"
                                v-model="form.query[n - 1].comparator"
                                style="width: 100%"
                              />
                            </div>
                          </div>

                          <div class="row">
                            <div
                              class="col-md-4 col-12 form-group pl-0 pr-0 multi-select-component"
                              v-show="
                                showTypeComparatorElement(
                                  form.query[n - 1].type,
                                  ['Multi Select'],
                                  form.query[n - 1].comparator
                                )
                              "
                            >
                              <v-select
                                multiple
                                placeholder="Multi Select"
                                :options="map_value_1[n - 1]"
                                v-model="form.query[n - 1].value_1"
                                style="width: 100%"
                              />
                            </div>

                            <div
                              class="col-md-4 col-12 form-group pl-0 pr-0"
                              v-show="
                                showTypeComparatorElement(
                                  form.query[n - 1].type,
                                  ['Single Select'],
                                  form.query[n - 1].comparator
                                )
                              "
                            >
                              <v-select
                                placeholder="Single Select"
                                :options="map_value_1[n - 1]"
                                :reduce="(each) => each.value"
                                v-model="form.query[n - 1].value_1"
                                style="width: 100%"
                              />
                            </div>
                            <div
                              class="col-md-4 col-12 form-group pl-0 pr-0"
                              v-if="
                                form.query[n - 1].type == 'Date Comparision' &&
                                form.query[n - 1].comparator != ''
                              "
                            >
                              <date-picker
                                v-model="form.query[n - 1].value_1"
                                :format="'MM/DD/YYYY'"
                                :value-type="'MM/DD/YYYY'"
                              ></date-picker>
                            </div>
                            <div
                              class="col-md-11 col-12 form-group pl-0 pr-0"
                              v-else-if="
                                (form.query[n - 1].type ==
                                  'Numeric Comparision' ||
                                  form.query[n - 1].type ==
                                    'Relative Time Period Comparision') &&
                                form.query[n - 1].comparator != ''
                              "
                            >
                              <input
                                type="number"
                                style="width: 33.33%; display: inline-block"
                                min="0"
                                class="form-control mr-1"
                                name=""
                                v-model="form.query[n - 1].value_1"
                                placeholder="Value 1"
                                required
                              />
                              <input
                                type="number"
                                style="width: 33.33%; display: inline-block"
                                min="0"
                                class="form-control mr-1"
                                v-show="
                                  form.query[n - 1].comparator == 'between'
                                "
                                name=""
                                v-model="form.query[n - 1].value_2"
                                placeholder="Value 2"
                                required
                              />
                            </div>
                            <div
                              class="col-md-4 col-12 form-group pl-0 pr-0"
                              v-show="
                                !showTypeComparatorElement(
                                  form.query[n - 1].type,
                                  [
                                    'Multi Select',
                                    'Single Select',
                                    'Date Comparision',
                                    'Numeric Comparision',
                                    'Relative Time Period Comparision',
                                  ],
                                  form.query[n - 1].comparator
                                )
                              "
                            ></div>

                            <div
                              class="col-md-7 col-12 form-group"
                              v-if="
                                !(
                                  (form.query[n - 1].type ==
                                    'Numeric Comparision' ||
                                    form.query[n - 1].type ==
                                      'Relative Time Period Comparision') &&
                                  form.query[n - 1].comparator != ''
                                )
                              "
                            ></div>
                            <div class="col-md-1 col-12 form-group">
                              <button
                                class="btn btn-icon btn-danger"
                                type="button"
                                @click="removeRow(n - 1)"
                              >
                                <i class="bx bx-x"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                        <!-- Each Row of Query : CLOSE -->
                        <div class="col-12 col-md-12">
                          <button
                            class="btn btn-icon btn-primary"
                            type="button"
                            @click="addNewRow"
                          >
                            <i class="bx bx-plus"></i> Add criteria
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card shadow-none mt-1 mb-0">
              <div class="card-header border-line-bottom-title">
                <h4 class="card-title">Choose Columns</h4>
              </div>
              <div class="card-content">
                <div class="card-body pt-0 pb-0">
                  <div class="contact-repeater">
                    <div class="m-width-s mb-0 pb-0 border-0">
                      <div class="row">
                        <!-- Draggable List : OPEN -->
                        <draggable
                          tag="ul"
                          v-model="form.view"
                          style="width: 100%"
                          v-bind="dragOptions"
                          @start="isDragging = true"
                          @end="isDragging = false"
                          class="list-group"
                          handle=".handle"
                        >
                          <transition-group type="transition" name="flip-list">
                            <li
                              class="list-group-item"
                              v-for="(element, idx) in form.view"
                              :key="element.header"
                            >
                              <span
                                style="cursor: pointer"
                                class="bx bx-grid-vertical pr-1 handle"
                              ></span>
                              <div class="checkbox">
                                <input
                                  type="checkbox"
                                  v-model="element.visibility"
                                  class="checkbox-input"
                                  value="photodrag"
                                  :id="'dragging_' + idx"
                                  :true-value="'1'"
                                  :false-value="'0'"
                                />
                                <label :for="'dragging_' + idx">{{
                                  element.header
                                }}</label>
                              </div>
                            </li>
                          </transition-group>
                        </draggable>
                        <!-- Draggable List : CLOSE -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- form layout section end -->
          </form>
        </div>
      </div>
    </div>
    <!-- END: Content-->
  </div>
</template>
<script>
import draggable from "vuedraggable";
import vSelect from "vue-select";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import { mapGetters, mapActions } from "vuex";
import Toast from "../../library/Toast";

export default {
  name: "DealReportAddCustomView",
  components: {
    "v-select": vSelect,
    draggable,
    DatePicker,
  },
  data() {
    return {
      errors: [],
      submit_button: false,
      query_rows: 1,
      map_columns: [
        { header: "Status", column: "status", type: "Multi Select" },

        { header: "Type", column: "type", type: "Single Select" },

        {
          header: "Retail Or Wholesale",
          column: "retail_or_wholesale_flag",
          type: "Single Select",
        },

        { header: "Approved By", column: "approved_by", type: "Single Select" },
        { header: "Salesperson", column: "salesperson", type: "Single Select" },

        {
          header: "Deal Time Period",
          column: "date",
          type: "Relative Time Period Comparision",
        },
        {
          header: "Delivery Time Period",
          column: "delivery_date_time",
          type: "Relative Time Period Comparision",
        },
        {
          header: "Creation Date",
          column: "created_at",
          type: "Date Comparision",
        },
        {
          header: "Modified Date",
          column: "updated_at",
          type: "Date Comparision",
        },
      ],
      map_comparator: [[]],
      map_value_1: [[]],
      form: {
        default: "0",
        header: "",
        query: [
          {
            header: "",
            column: "",
            join: "AND",
            comparator: "",
            type: "",
            value_1: "",
            value_2: "",
          },
        ],
        view: [
          {
            header: "DEAL ID",
            column: "deal_id",
            component: "text",
            visibility: "1",
          },
          {
            header: "DEAL STATUS",
            column: "status",
            component: "badge",
            visibility: "1",
          },
          {
            header: "DEAL TYPE",
            column: "type",
            component: "badge",
            visibility: "1",
          },
          {
            header: "DEAL DATE",
            column: "date",
            component: "date",
            visibility: "1",
          },
          {
            header: "RT-WHL",
            column: "retail_or_wholesale_flag",
            component: "rtl_wh_flag",
            visibility: "1",
          },
          {
            header: "DELIVERY DATE",
            column: "delivery_date_time",
            component: "date",
            visibility: "1",
          },
          {
            header: "APPROVED BY",
            column: "approved_by",
            component: "user",
            visibility: "1",
          },
          {
            header: "SALESPERSON",
            column: "salesperson",
            component: "user",
            visibility: "1",
          },
          {
            header: "YEAR",
            column: "year",
            component: "text",
            visibility: "1",
          },
          {
            header: "MAKE",
            column: "make",
            component: "text",
            visibility: "1",
          },
          {
            header: "MODEL",
            column: "model",
            component: "text",
            visibility: "1",
          },
          {
            header: "VIN",
            column: "vin",
            component: "text",
            visibility: "1",
          },
          {
            header: "STOCK NO",
            column: "stock_no",
            component: "text",
            visibility: "1",
          },
          {
            header: "ODOMETER",
            column: "odometer",
            component: "distance",
            visibility: "0",
          },
          {
            header: "EXTERIOR COLOR",
            column: "exterior_color",
            component: "text",
            visibility: "1",
          },
          {
            header: "INTERIOR COLOR",
            column: "interior_color",
            component: "text",
            visibility: "1",
          },
          {
            header: "CUSTOMER NAME",
            column: "customer_name",
            component: "text",
            visibility: "1",
          },
          {
            header: "CUSTOMER PHONE",
            column: "customer_phone",
            component: "phone",
            visibility: "0",
          },
          {
            header: "CUSTOMER MOBILE",
            column: "customer_mobile",
            component: "phone",
            visibility: "0",
          },
          {
            header: "CUSTOMER EMAIL",
            column: "customer_email",
            component: "email",
            visibility: "0",
          },
          {
            header: "CUSTOMER COMPANY NAME",
            column: "customer_company_name",
            component: "text",
            visibility: "1",
          },
          {
            header: "CUSTOMER APT",
            column: "customer_apt",
            component: "text",
            visibility: "0",
          },
          {
            header: "CUSTOMER STREET",
            column: "customer_street_address",
            component: "text",
            visibility: "0",
          },
          {
            header: "CUSTOMER CITY",
            column: "customer_city",
            component: "text",
            visibility: "0",
          },
          {
            header: "CUSTOMER PROVINCE",
            column: "customer_province",
            component: "text",
            visibility: "0",
          },
          {
            header: "CUSTOMER POSTAL CODE",
            column: "customer_postal_code",
            component: "text",
            visibility: "0",
          },
          {
            header: "CUSTOMER COUNTRY",
            column: "customer_country",
            component: "text",
            visibility: "0",
          },
          {
            header: "VENDOR NAME",
            column: "vendor_name",
            component: "text",
            visibility: "1",
          },
          {
            header: "VENDOR PHONE",
            column: "vendor_phone",
            component: "phone",
            visibility: "0",
          },
          {
            header: "VENDOR MOBILE",
            column: "vendor_mobile",
            component: "phone",
            visibility: "0",
          },
          {
            header: "VENDOR EMAIL",
            column: "vendor_email",
            component: "email",
            visibility: "0",
          },
          {
            header: "VENDOR COMPANY NAME",
            column: "vendor_company_name",
            component: "text",
            visibility: "1",
          },
          {
            header: "VENDOR APT",
            column: "vendor_apt",
            component: "text",
            visibility: "0",
          },
          {
            header: "VENDOR STREET",
            column: "vendor_street_address",
            component: "text",
            visibility: "0",
          },
          {
            header: "VENDOR CITY",
            column: "vendor_city",
            component: "text",
            visibility: "0",
          },
          {
            header: "VENDOR PROVINCE",
            column: "vendor_province",
            component: "text",
            visibility: "0",
          },
          {
            header: "VENDOR POSTAL CODE",
            column: "vendor_postal_code",
            component: "text",
            visibility: "0",
          },
          {
            header: "VENDOR COUNTRY",
            column: "vendor_country",
            component: "text",
            visibility: "0",
          },
          {
            header: "VENDOR PURCHASE DATE",
            column: "vendor_purchase_date",
            component: "date",
            visibility: "1",
          },
          {
            header: "VENDOR PURCHASE PRICE",
            column: "vendor_purchase_price",
            component: "currency",
            visibility: "1",
          },
          {
            header: "VENDOR PURCHASE DISCOUNT",
            column: "vendor_purchase_discount",
            component: "currency",
            visibility: "1",
          },
          {
            header: "VENDOR PURCHASE PRIMARY TAX",
            column: "vendor_purchase_primary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "VENDOR PURCHASE SECONDARY TAX",
            column: "vendor_purchase_secondary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "VENDOR PURCHASE TOTAL TAX",
            column: "vendor_purchase_total_tax",
            component: "currency",
            visibility: "1",
          },
          {
            header: "ADDITIONAL COST",
            column: "additional_cost",
            component: "currency",
            visibility: "1",
          },
          {
            header: "ADDITIONAL COST DISCOUNT",
            column: "additional_cost_discount",
            component: "currency",
            visibility: "1",
          },
          {
            header: "ADDITIONAL COST PRIMARY TAX",
            column: "additional_cost_primary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "ADDITIONAL COST SECONDARY TAX",
            column: "additional_cost_secondary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "ADDITIONAL COST TOTAL TAX",
            column: "additional_cost_total_tax",
            component: "currency",
            visibility: "1",
          },
          {
            header: "ADDITIONAL COST TOTAL",
            column: "additional_cost_total",
            component: "currency",
            visibility: "1",
          },
          {
            header: "SELLING PRICE",
            column: "selling_price",
            component: "currency",
            visibility: "1",
          },
          {
            header: "SELLING PRICE PRIMARY TAX",
            column: "selling_price_primary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "SELLING PRICE SECONDARY TAX",
            column: "selling_price_secondary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "SELLING PRICE TOTAL TAX",
            column: "selling_price_total_tax",
            component: "currency",
            visibility: "1",
          },
          {
            header: "EXTENDED WARRANTY",
            column: "extended_warranty",
            component: "currency",
            visibility: "1",
          },
          {
            header: "EXTENDED WARRANTY PRIMARY TAX",
            column: "extended_warranty_primary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "EXTENDED WARRANTY SECONDARY TAX",
            column: "extended_warranty_secondary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "EXTENDED WARRANTY TOTAL TAX",
            column: "extended_warranty_total_tax",
            component: "currency",
            visibility: "1",
          },
          {
            header: "EXTENDED WARRANTY COST",
            column: "extended_warranty_cost",
            component: "currency",
            visibility: "1",
          },
          {
            header: "EXTENDED WARRANTY COST PRIMARY TAX",
            column: "extended_warranty_cost_primary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "EXTENDED WARRANTY COST SECONDARY TAX",
            column: "extended_warranty_cost_secondary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "EXTENDED WARRANTY COST TOTAL TAX",
            column: "extended_warranty_cost_total_tax",
            component: "currency",
            visibility: "1",
          },
          {
            header: "GUARANTEE COST",
            column: "guarantee_cost",
            component: "currency",
            visibility: "1",
          },
          {
            header: "GUARANTEE COST PRIMARY TAX",
            column: "guarantee_cost_primary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "GUARANTEE COST SECONDARY TAX",
            column: "guarantee_cost_secondary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "GUARANTEE COST TOTAL TAX",
            column: "guarantee_cost_total_tax",
            component: "currency",
            visibility: "1",
          },
          {
            header: "FEES",
            column: "fees",
            component: "currency",
            visibility: "1",
          },
          {
            header: "FEES PRIMARY TAX",
            column: "fees_primary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "FEES SECONDARY TAX",
            column: "fees_secondary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "FEES TOTAL TAX",
            column: "fees_total_tax",
            component: "currency",
            visibility: "1",
          },
          {
            header: "FEES COST",
            column: "fees_cost",
            component: "currency",
            visibility: "1",
          },
          {
            header: "FEES COST PRIMARY TAX",
            column: "fees_cost_primary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "FEES COST SECONDARY TAX",
            column: "fees_cost_secondary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "FEES COST TOTAL TAX",
            column: "fees_cost_total_tax",
            component: "currency",
            visibility: "1",
          },
          {
            header: "ACCESSORIES",
            column: "accessories",
            component: "currency",
            visibility: "1",
          },
          {
            header: "ACCESSORIES PRIMARY TAX",
            column: "accessories_primary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "ACCESSORIES SECONDARY TAX",
            column: "accessories_secondary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "ACCESSORIES TOTAL TAX",
            column: "accessories_total_tax",
            component: "currency",
            visibility: "1",
          },
          {
            header: "ACCESSORIES COST",
            column: "accessories_cost",
            component: "currency",
            visibility: "1",
          },
          {
            header: "ACCESSORIES COST PRIMARY TAX",
            column: "accessories_cost_primary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "ACCESSORIES COST SECONDARY TAX",
            column: "accessories_cost_secondary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "ACCESSORIES COST TOTAL TAX",
            column: "accessories_cost_total_tax",
            component: "currency",
            visibility: "1",
          },
          {
            header: "TRADE VALUE",
            column: "trade_value",
            component: "currency",
            visibility: "1",
          },
          {
            header: "NET DIFFERENCE",
            column: "net_difference",
            component: "currency",
            visibility: "1",
          },
          {
            header: "TOTAL TAX",
            column: "total_tax",
            component: "currency",
            visibility: "1",
          },
          {
            header: "LICENCE FEE",
            column: "licence_fee",
            component: "currency",
            visibility: "1",
          },
          {
            header: "GASOLINE",
            column: "gasoline",
            component: "currency",
            visibility: "1",
          },
          {
            header: "LIEN PAYOUT",
            column: "lien_payout",
            component: "currency",
            visibility: "1",
          },
          {
            header: "SUBTOTAL",
            column: "subtotal",
            component: "currency",
            visibility: "1",
          },
          {
            header: "PAYMENT TOTAL",
            column: "payment_total",
            component: "currency",
            visibility: "1",
          },
          {
            header: "PAYMENT DETAILS",
            column: "payment_details",
            component: "text",
            visibility: "1",
          },
          {
            header: "INSURANCE",
            column: "insurance_total",
            component: "currency",
            visibility: "1",
          },
          {
            header: "INSURANCE PRIMARY TAX",
            column: "insurance_primary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "INSURANCE SECONDARY TAX",
            column: "insurance_secondary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "INSURANCE TOTAL TAX",
            column: "insurance_total_tax",
            component: "currency",
            visibility: "1",
          },
          {
            header: "INSURANCE COST",
            column: "insurance_total_cost",
            component: "currency",
            visibility: "1",
          },
          {
            header: "INSURANCE COST PRIMARY TAX",
            column: "insurance_cost_primary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "INSURANCE COST SECONDARY TAX",
            column: "insurance_cost_secondary_tax",
            component: "currency",
            visibility: "0",
          },
          {
            header: "INSURANCE COST TOTAL TAX",
            column: "insurance_cost_total_tax",
            component: "currency",
            visibility: "1",
          },
          {
            header: "LIEN REGISTRATION FEE",
            column: "lien_registration_fee",
            component: "currency",
            visibility: "1",
          },
          {
            header: "BALANCE DUE",
            column: "total_balance_due",
            component: "currency",
            visibility: "1",
          },
          {
            header: "FINANCE COMMISSION",
            column: "finance_commission_total",
            component: "currency",
            visibility: "1",
          },
          {
            header: "SALES COMMISSIONS",
            column: "sale_commissions_total",
            component: "currency",
            visibility: "1",
          },
          {
            header: "VEHICLE PROFIT LOSS",
            column: "vehicle_profit_loss",
            component: "currency",
            visibility: "1",
          },
          {
            header: "TOTAL PROFIT LOSS",
            column: "total_profit_loss",
            component: "currency",
            visibility: "1",
          },
          {
            header: "TAXES COLLECTED",
            column: "total_taxes_collected",
            component: "currency",
            visibility: "1",
          },
          {
            header: "TAXES PAID",
            column: "total_taxes_paid",
            component: "currency",
            visibility: "1",
          },
          {
            header: "TAXES DIFFERENCE",
            column: "total_taxes_difference",
            component: "currency",
            visibility: "1",
          },
        ],
      },
    };
  },
  computed: {
    ...mapGetters("deal", ["getDealStatuses"]),
    ...mapGetters("users", ["allUsers"]),
    ...mapGetters("dealReportView", ["getViewId"]),
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost",
      };
    },
    users() {
      return this.allUsers.map((user) => {
        return { label: user.name, value: user.user_id };
      });
    },
    updateView() {
      return (
        this.$route.params.id && this.$route.name == "deal-report-view-update"
      );
    },
  },
  methods: {
    ...mapActions("users", ["getAllUsers"]),
    ...mapActions("dealReportView", ["commitLoadState"]),
    showTypeComparatorElement(
      current_element_type = "",
      all_element_types = [],
      comparator = ""
    ) {
      return (
        all_element_types.includes(current_element_type) && comparator != ""
      );
    },
    async getSmartView(id) {
      let request_url = `${window.location.origin}/api/business/deal-reports/views/show/${id}`;

      await axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.form["header"] = data["header"];
          this.form["default"] = data["default"];
          this.form["view"] = JSON.parse(data["view"]);
          this.form["query"] = JSON.parse(data["query"]);

          this.form.query =
            this.form.query.length < 1
              ? [
                  {
                    header: "",
                    column: "",
                    join: "AND",
                    comparator: "",
                    type: "",
                    value_1: "",
                    value_2: "",
                  },
                ]
              : this.form.query;

          this.query_rows = this.form.query.length;

          for (const key in this.form.query) {
            this.changeQueryAvailableValues(key);
          }
        })
        .catch((response) => {
          console.log(response);
          let error = "There were some errors in retrieving the view";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
    save() {
      const vm = this;
      this.submit_button = false;

      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && key != "query" && key != "view") {
          formData.append(key, this.form[key]);
        }
      }

      formData.append("view", JSON.stringify(this.form["view"]));
      formData.append("query", JSON.stringify(this.form["query"]));

      let request_url = this.updateView
        ? `${window.location.origin}/api/business/deal-reports/views/update/${this.$route.params.id}`
        : `${window.location.origin}/api/business/deal-reports/views/create`;

      let message = this.updateView
        ? "Updating deal report view ..."
        : "Creating new deal report view ...";

      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          let success_message = this.updateView
            ? "View has been updated."
            : "New view created successfully.";

          vm.$data.submit_button = true;

          toast.success(success_message);
          toast.close();

          if (vm.updateView) {
            if (vm.$route.params.id == vm.getViewId) {
              vm.commitLoadState(false);
            }
          }

          setTimeout(() => {
            vm.$router.push({ name: "deal-reports" });
          }, 500);
        })
        .catch(function (error) {
          // handle error
          let error_message = "There were some errors while submitting form.";
          vm.$data.errors = error.response.data;
          toast.fail(error_message);
          toast.close();

          vm.$data.submit_button = true;
        });
    },
    removeRow(current_row) {
      this.form.query.splice(current_row, 1);
      this.map_comparator.splice(current_row, 1);
      this.map_value_1.splice(current_row, 1);
      this.query_rows -= 1;
    },
    addNewRow() {
      this.map_comparator.push([]);
      this.map_value_1.push([]);
      this.form.query.push({
        header: "",
        column: "",
        join: "AND",
        comparator: "",
        type: "",
        value_1: "",
        value_2: "",
      });
      this.query_rows += 1;
    },
    changeQueryDetails(i, reset_query = true) {
      //fetch the row for which the input has changed.
      //once fetched changed the column_name, comparator in the corresponding object.
      let query_row = this.form.query[i];
      query_row.column = this.map_columns.filter(
        (x) => x.header == query_row.header
      )[0].column;
      query_row.type = this.map_columns.filter(
        (x) => x.header == query_row.header
      )[0].type;

      if (reset_query) {
        query_row.value_1 = "";
        query_row.comparator = "";
        query_row.value_2 = "";
      }

      this.map_value_1[i] = [];

      if (query_row.type == "Null Comparision") {
        this.map_comparator[i] = ["is empty", "is not empty"];
      } else if (query_row.type == "Date Comparision") {
        this.map_comparator[i] = ["is", "after", "before"];
      } else if (query_row.type == "Multi Select") {
        this.map_comparator[i] = ["is", "is not"];
      } else if (query_row.type == "Single Select") {
        this.map_comparator[i] = ["is"];
      } else if (
        query_row.type == "Numeric Comparision" ||
        query_row.type == "Relative Time Period Comparision"
      ) {
        this.map_comparator[i] = ["greater than", "less than", "between"];
      }

      if (query_row.header == "Status") {
        this.map_value_1[i] = this.getDealStatuses;
      } else if (
        query_row.header == "Approved By" ||
        query_row.header == "Salesperson"
      ) {
        this.map_value_1[i] = this.users;
      } else if (query_row.header == "Retail Or Wholesale") {
        this.map_value_1[i] = [
          { label: "Retail", value: "0" },
          { label: "Wholesale", value: "1" },
        ];
      } else if (query_row.header == "Type") {
        this.map_value_1[i] = [
          { label: "Financed", value: "Financed" },
          { label: "Cash", value: "Cash" },
        ];
      }
    },
    changeQueryAvailableValues(i) {
      this.changeQueryDetails(i, false);
    },
  },
  async mounted() {
    await this.getAllUsers();
    if (this.updateView) {
      await this.getSmartView(this.$route.params.id);
    }
    this.submit_button = true;
  },
};
</script>
<style lang="scss">
@import "vue-select/src/scss/vue-select.scss";
#add_deal_custom_view .v-select > div {
  height: 2.2em;
}

#add_deal_custom_view .vs__selected {
  font-size: 1rem;
  padding-left: 1em;
  align-items: normal;
}

#add_deal_custom_view .webapp-form .form-group .vdpComponent input {
  padding-left: 2.2em !important;
  line-height: 1.4;
  color: #475f7b;
  background-color: #ffffff;
  background-clip: padding-box;
  border: 1px solid #dfe3e7;
  border-radius: 0.267rem;
}

#add_deal_custom_view .vdpArrowPrev:after {
  border-right-color: green;
}

#add_deal_custom_view .vdpArrowNext:after {
  border-left-color: green;
}

#add_deal_custom_view .vdpCell.selectable:hover .vdpCellContent,
#add_deal_custom_view .vdpCell.selected .vdpCellContent {
  background: green;
}

#add_deal_custom_view .vdpCell.today {
  color: green;
}

#add_deal_custom_view .vdpTimeUnit > input:hover,
#add_deal_custom_view .vdpTimeUnit > input:focus {
  border-bottom-color: green;
}

#add_deal_custom_view .multi-select-component .v-select > div {
  height: auto;
}
.multi-select-component .vs__selected {
  background-color: #5a8dee !important;
  border-color: transparent !important;
  color: #ffffff !important;
  padding: 0.2rem 0.6rem 0.2rem 0.6rem !important;
  height: 2em;
}
.multi-select-component .vs__deselect {
  fill: white;
}
.multi-select-component .vs__dropdown-option--selected {
  color: #727e8c;
  background: #ddd;
}
.multi-select-component .vs__dropdown-menu {
  height: 200px;
}
</style>
