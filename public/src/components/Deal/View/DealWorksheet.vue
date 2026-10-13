<template>
  <div
    class="tab-pane"
    id="deal-worksheet-panel"
    aria-labelledby="Worksheet Tab"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="deal-create-section" v-show="!show_page">
      <div class="row match-height">
        <div class="col-12 col-xs-12 col-md-12 row">
          <div class="offset-md-1 col-md-2">Loading the worksheet...</div>
        </div>
      </div>
    </section>
    <section id="deal-create-section" v-show="show_page">
      <div class="row match-height">
        <div class="col-12 col-xs-12 col-md-12 row">
          <div class="offset-md-1 col-md-2">
            <label>Select Template <sub class="fixed-color-set">*</sub></label>
          </div>
          <div class="col-md-4 form-group">
            <fieldset>
              <div class="input-group">
                <select
                  type="text"
                  v-model="selected_deal_template"
                  class="custom-select form-control mr-1"
                  placeholder="Select Deal Template"
                >
                  <option value="" disabled>-- Select Deal Template --</option>
                  <option
                    :value="deal_template.id"
                    v-for="deal_template in getdealTemplates"
                    :key="deal_template.id"
                  >
                    {{ deal_template.title }}
                  </option>
                </select>
              </div>
            </fieldset>
          </div>
          <div class="col-md-2">
            <button
              v-show="selected_deal_template != ''"
              class="btn btn-primary"
              @click="importDealTemplate(selected_deal_template)"
            >
              Apply
            </button>
          </div>
          <div class="col-md-3">
            <router-link
              v-if="isAuthorized"
              tag="button"
              :to="{ name: 'deal-templates' }"
              class="btn btn-link"
              exact
            >
              Create New Template
            </router-link>
          </div>
        </div>
        <!-- deal breakdown : START -->
        <div class="col-12 col-xs-12 col-md-12 row">
          <div class="card col-xs-12 col-md-4">
            <div class="card-content">
              <div class="card-body pb-1 mx-25 row">
                <h4 class="card-title col-6">Structure</h4>
                <div class="col-6" style="text-align: right">
                  <button
                    class="btn btn-primary text-bold-700"
                    @click.prevent="updateWorksheetAndFinance"
                  >
                    <i class="bx bxs-save"></i> Save
                  </button>
                </div>
                <div class="col-12">
                  <!-- row : START -->
                  <div class="row">
                    <!-- deal_dealbreakdown_form purchase_price : START -->
                    <div class="col-xs-12 col-md-12">
                      <div class="form-group">
                        <label for="deal_dealbreakdown_form_purchase_price"
                          >Selling Price</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_purchase_price"
                            type="text"
                            placeholder="Selling Price"
                            v-model="deal_dealbreakdown_form.purchase_price"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_dealbreakdown_errors.hasOwnProperty(
                                  'purchase_price'
                                ),
                            }"
                            @keyup="
                              delete deal_dealbreakdown_errors.purchase_price
                            "
                            :title="deal_dealbreakdown_errors.purchase_price"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="monthly_payment-error"
                          class="form-text invalid-feedback"
                          >{{ deal_dealbreakdown_errors.purchase_price }}</small
                        >
                      </div>
                    </div>
                    <!-- deal_dealbreakdown_form purchase_price : END -->
                    <!-- deal_dealbreakdown_form guarantee : START -->
                    <div
                      class="col-xs-12 col-md-8"
                      v-show="isDealRetailOrWholesale == 0"
                    >
                      <div class="form-group">
                        <label for="deal_dealbreakdown_form_guarantee"
                          >Dealer Guarantee
                        </label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_guarantee"
                            type="text"
                            placeholder="Dealer Guarantee"
                            value="0"
                            class="form-control"
                            readonly
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                      </div>
                    </div>
                    <div
                      class="col-xs-12 col-md-4 p-0 pt-1"
                      v-show="isDealRetailOrWholesale == 0"
                    >
                      <button
                        class="btn btn-link p-0"
                        data-toggle="modal"
                        data-target="#dealGuaranteeModal"
                        v-show="guaranteeCount == 0"
                      >
                        <i class="bx bxs-plus-square text-success bx-md"></i>
                      </button>
                      <button
                        class="btn btn-link p-0"
                        onclick='document.getElementById("deal-guarantees-table").parentElement.parentElement.scrollIntoView({behavior: "smooth"});'
                      >
                        <i class="bx bx-list-ul text-primary bx-md"></i>
                      </button>
                    </div>
                    <!-- deal_dealbreakdown_form guarantee : END -->
                    <div class="w-100"></div>

                    <!-- deal_dealbreakdown_form extended warranty : START -->
                    <div
                      class="col-xs-12 col-md-8"
                      v-show="isDealRetailOrWholesale == 0"
                    >
                      <div class="form-group">
                        <label for="deal_dealbreakdown_form_extended_warranty"
                          >Extended Warranty</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_extended_warranty"
                            type="text"
                            placeholder="Extended Warranty"
                            v-model="calculateDealTotalWarrantyAmount"
                            class="form-control"
                            readonly
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                      </div>
                    </div>
                    <div
                      class="col-xs-12 col-md-4 p-0 pt-1"
                      v-show="isDealRetailOrWholesale == 0"
                    >
                      <button
                        class="btn btn-link p-0"
                        v-show="warrantyCount == 0"
                        data-toggle="modal"
                        data-target="#dealWarrantyModal"
                      >
                        <i class="bx bxs-plus-square text-success bx-md"></i>
                      </button>
                      <button
                        class="btn btn-link p-0"
                        onclick='document.getElementById("deal-warranties-table").parentElement.parentElement.scrollIntoView({behavior: "smooth"});'
                      >
                        <i class="bx bx-list-ul text-primary bx-md"></i>
                      </button>
                    </div>
                    <!-- deal_dealbreakdown_form extended warranty : END -->
                    <div class="w-100"></div>

                    <!-- deal_dealbreakdown_form extended fees : START -->
                    <div class="col-xs-12 col-md-8">
                      <div class="form-group">
                        <label for="deal_dealbreakdown_form_extended_fees"
                          >Fees</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_extended_fees"
                            type="text"
                            placeholder="Fees"
                            v-model="calculateDealTotalFees"
                            class="form-control"
                            readonly
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                      </div>
                    </div>
                    <div class="col-xs-12 col-md-4 p-0 pt-1">
                      <button
                        class="btn btn-link p-0"
                        v-show="feeCount < 5"
                        data-toggle="modal"
                        data-target="#dealFeeModal"
                      >
                        <i class="bx bxs-plus-square text-success bx-md"></i>
                      </button>
                      <button
                        class="btn btn-link p-0"
                        onclick='document.getElementById("deal-fees-table").parentElement.parentElement.scrollIntoView({behavior: "smooth"});'
                      >
                        <i class="bx bx-list-ul text-primary bx-md"></i>
                      </button>
                    </div>
                    <!-- deal_dealbreakdown_form extended fees : END -->

                    <!-- deal_dealbreakdown_form extended accessories : START -->
                    <div
                      class="col-xs-12 col-md-8"
                      v-show="isDealRetailOrWholesale == 0"
                    >
                      <div class="form-group">
                        <label for="deal_dealbreakdown_form_accessories"
                          >Accessories</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_accessories"
                            type="text"
                            placeholder="Accessories"
                            v-model="calculateDealTotalAccessoryAmount"
                            class="form-control"
                            readonly
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                      </div>
                    </div>
                    <div
                      class="col-xs-12 col-md-4 p-0 pt-1"
                      v-show="isDealRetailOrWholesale == 0"
                    >
                      <button
                        class="btn btn-link p-0"
                        data-toggle="modal"
                        data-target="#dealAccessoryModal"
                      >
                        <i class="bx bxs-plus-square text-success bx-md"></i>
                      </button>
                      <button
                        class="btn btn-link p-0"
                        onclick='document.getElementById("deal-accessories-table").parentElement.parentElement.scrollIntoView({behavior: "smooth"});'
                      >
                        <i class="bx bx-list-ul text-primary bx-md"></i>
                      </button>
                    </div>
                    <!-- deal_dealbreakdown_form extended accessories : END -->

                    <!-- deal_dealbreakdown_form insurance : START -->
                    <div
                      class="col-xs-12 col-md-8"
                      v-show="isDealRetailOrWholesale == 0"
                    >
                      <div class="form-group">
                        <label for="deal_dealbreakdown_form_insurance"
                          >Total Insurance</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_insurance"
                            type="text"
                            placeholder="Total Insurance"
                            v-model="calculateDealTotalInsuranceAmount"
                            class="form-control"
                            readonly
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                      </div>
                    </div>
                    <div
                      class="col-xs-12 col-md-4 p-0 pt-1"
                      v-show="isDealRetailOrWholesale == 0"
                    >
                      <button
                        class="btn btn-link p-0"
                        data-toggle="modal"
                        data-target="#dealInsuranceModal"
                      >
                        <i class="bx bxs-plus-square text-success bx-md"></i>
                      </button>
                      <button
                        class="btn btn-link p-0"
                        onclick='document.getElementById("deal-insurances-table").parentElement.parentElement.scrollIntoView({behavior: "smooth"});'
                      >
                        <i class="bx bx-list-ul text-primary bx-md"></i>
                      </button>
                    </div>
                    <!-- deal_dealbreakdown_form insurance : END -->

                    <div class="w-100"></div>
                    <!-- deal_dealbreakdown_form subtotal : START -->
                    <div class="col-xs-12 col-md-12 d-none">
                      <div class="form-group">
                        <label for="deal_dealbreakdown_form_subtotal"
                          >Subtotal</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_subtotal"
                            type="text"
                            readonly
                            placeholder="Subtotal"
                            v-model="deal_dealbreakdown_form.subtotal"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_dealbreakdown_errors.hasOwnProperty(
                                  'subtotal'
                                ),
                            }"
                            @keyup="delete deal_dealbreakdown_errors.subtotal"
                            :title="deal_dealbreakdown_errors.subtotal"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="monthly_payment-error"
                          class="form-text invalid-feedback"
                          >{{ deal_dealbreakdown_errors.subtotal }}</small
                        >
                      </div>
                    </div>
                    <!--  deal_dealbreakdown_purchase subtotal  : END -->
                    <div class="w-100 d-none"></div>
                    <!-- deal_dealbreakdown_form trade_value : START -->

                    <div class="col-xs-12 col-md-12 d-none">
                      <div class="form-group">
                        <label for="deal_dealbreakdown_form_trade_value"
                          >Trade Value</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_trade_value"
                            type="text"
                            readonly
                            placeholder="Trade Value"
                            v-model="deal_dealbreakdown_form.trade_value"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_dealbreakdown_errors.hasOwnProperty(
                                  'trade_value'
                                ),
                            }"
                            @keyup="
                              delete deal_dealbreakdown_errors.trade_value
                            "
                            :title="deal_dealbreakdown_errors.trade_value"
                          />
                          <div class="form-control-position">
                            <i
                              class="bx bxs-minus-circle text-danger font-medium-3"
                            ></i>
                          </div>
                        </fieldset>
                        <small
                          id="monthly_payment-error"
                          class="form-text invalid-feedback"
                          >{{ deal_dealbreakdown_errors.trade_value }}</small
                        >
                      </div>
                    </div>
                    <!--  deal_dealbreakdown_purchase trade_value  : END -->

                    <!-- deal_dealbreakdown_form net_difference : START -->
                    <div class="col-xs-12 col-md-12 d-none">
                      <div class="form-group">
                        <label for="deal_dealbreakdown_form_net_difference"
                          >Net Difference</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_net_difference"
                            type="text"
                            readonly
                            placeholder="Net Difference"
                            v-model="deal_dealbreakdown_form.net_difference"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_dealbreakdown_errors.hasOwnProperty(
                                  'net_difference'
                                ),
                            }"
                            @keyup="
                              delete deal_dealbreakdown_errors.net_difference
                            "
                            :title="deal_dealbreakdown_errors.net_difference"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="monthly_payment-error"
                          class="form-text invalid-feedback"
                          >{{ deal_dealbreakdown_errors.net_difference }}</small
                        >
                      </div>
                    </div>
                    <!--  deal_dealbreakdown_purchase net_difference  : END -->

                    <div class="w-100"></div>
                    <!-- deal_dealbreakdown_form_tax_type : START -->
                    <div class="form-group col-md-12 col-xs-12">
                      <label for="deal_fee_purchase_tax_type">Tax Type</label>
                      <select
                        class="custom-select form-control mr-1"
                        :class="{
                          'is-invalid':
                            deal_dealbreakdown_errors.hasOwnProperty(
                              'purchase_tax_type'
                            ),
                        }"
                        @keyup="
                          delete deal_dealbreakdown_errors.purchase_tax_type
                        "
                        :title="deal_dealbreakdown_errors.purchase_tax_type"
                        v-model="deal_dealbreakdown_form.purchase_tax_type"
                        placeholder="Tax Type"
                        id="deal_fee_purchase_tax_type"
                      >
                        <option value="" selected disabled>Tax Type</option>
                        <option
                          :value="tax.name"
                          v-for="tax in businessTaxSettings"
                          :key="tax.id"
                        >
                          {{ tax.name }}
                        </option>
                        <!-- <option value="Other">Other</option> -->
                      </select>
                    </div>
                    <!--  deal_dealbreakdown_purchase_tax_type : END -->

                    <!-- deal_dealbreakdown_purchase_tax_type_secondary : START -->
                    <div class="form-group col-md-12 col-xs-12">
                      <label for="deal_breakdown_purchase_tax_type_secondary"
                        >Tax Type</label
                      >
                      <select
                        class="custom-select form-control mr-1"
                        :class="{
                          'is-invalid':
                            deal_dealbreakdown_errors.hasOwnProperty(
                              'purchase_tax_type_secondary'
                            ),
                        }"
                        @keyup="
                          delete deal_dealbreakdown_errors.purchase_tax_type_secondary
                        "
                        :title="
                          deal_dealbreakdown_errors.purchase_tax_type_secondary
                        "
                        v-model="
                          deal_dealbreakdown_form.purchase_tax_type_secondary
                        "
                        placeholder="Tax Type"
                        id="deal_breakdown_purchase_tax_type_secondary"
                      >
                        <option value="" selected>Tax Type</option>
                        <option
                          :value="tax.name"
                          v-for="tax in businessTaxSettings"
                          :key="tax.id"
                        >
                          {{ tax.name }}
                        </option>
                        <!-- <option value="Other">Other</option> -->
                      </select>
                    </div>
                    <!-- deal_dealbreakdown_purchase_tax_type_secondary : END -->

                    <!-- <div class="w-100"></div> -->
                    <!-- deal_dealbreakdown_form tax_on_purchase_price : START -->
                    <div class="col-xs-12 col-md-12 d-none">
                      <div class="form-group">
                        <label
                          for="deal_dealbreakdown_form_tax_on_purchase_price"
                          >Tax on Purchase Price
                          <span
                            v-show="
                              deal_dealbreakdown_form.purchase_tax_percentage !=
                              ''
                            "
                          >
                            @
                            {{
                              deal_dealbreakdown_form.purchase_tax_percentage
                            }}
                            %
                          </span>
                        </label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_tax_on_purchase_price"
                            type="text"
                            placeholder="Tax on Purchase Price"
                            v-model="
                              deal_dealbreakdown_form.tax_on_purchase_price
                            "
                            :readonly="enableDealBreakTaxEditing"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_dealbreakdown_errors.hasOwnProperty(
                                  'tax_on_purchase_price'
                                ),
                            }"
                            @keyup="
                              delete deal_dealbreakdown_errors.tax_on_purchase_price
                            "
                            :title="
                              deal_dealbreakdown_errors.tax_on_purchase_price
                            "
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="deal_dealbreakdown_form_tax_on_purchase_price-error"
                          class="form-text invalid-feedback"
                          >{{
                            deal_dealbreakdown_errors.tax_on_purchase_price
                          }}</small
                        >
                      </div>
                    </div>
                    <!-- deal_dealbreakdown_form total_tax_amount : END -->
                    <!-- <div class="w-100"></div> -->

                    <!-- <div class="w-100"></div> -->

                    <div class="col-xs-12 col-md-12">
                      <div class="form-group">
                        <label for="deal_dealbreakdown_form_total_tax_amount"
                          >Total Tax</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_total_tax_amount"
                            type="text"
                            placeholder="Total Tax"
                            v-model="deal_dealbreakdown_form.total_tax_amount"
                            readonly
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_dealbreakdown_errors.hasOwnProperty(
                                  'total_tax_amount'
                                ),
                            }"
                            @keyup="
                              delete deal_dealbreakdown_errors.total_tax_amount
                            "
                            :title="deal_dealbreakdown_errors.total_tax_amount"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="deal_dealbreakdown_total_tax_amount-error"
                          class="form-text invalid-feedback"
                          >{{
                            deal_dealbreakdown_errors.total_tax_amount
                          }}</small
                        >
                      </div>
                    </div>
                    <!-- deal_dealbreakdown_form total_tax_amount : END -->
                    <div class="w-100"></div>
                    <!-- deal_dealbreakdown_form licence_fee : START -->
                    <div class="col-xs-12 col-md-12">
                      <div class="form-group">
                        <label for="deal_dealbreakdown_form_licence_fee"
                          >Licence Fee</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_licence_fee"
                            type="text"
                            placeholder="Licence Fee"
                            v-model="deal_dealbreakdown_form.licence_fee"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_dealbreakdown_errors.hasOwnProperty(
                                  'licence_fee'
                                ),
                            }"
                            @keyup="
                              delete deal_dealbreakdown_errors.licence_fee
                            "
                            :title="deal_dealbreakdown_errors.licence_fee"
                          />
                          <div class="form-control-position">
                            <i
                              class="bx bxs-dollar-circle text-success font-medium-3"
                            ></i>
                          </div>
                        </fieldset>
                        <small
                          id="deal_dealbreakdown_licence_fee-error"
                          class="form-text invalid-feedback"
                          >{{ deal_dealbreakdown_errors.licence_fee }}</small
                        >
                      </div>
                    </div>
                    <!-- deal_dealbreakdown_form licence_fee : END -->

                    <!-- deal_dealbreakdown_form gasoline : START -->
                    <div
                      class="col-xs-12 col-md-12"
                      v-show="isDealRetailOrWholesale == 0"
                    >
                      <div class="form-group">
                        <label for="deal_dealbreakdown_form_gasoline"
                          >Gasoline</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_gasoline"
                            type="text"
                            placeholder="Gasoline"
                            v-model="deal_dealbreakdown_form.gasoline"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_dealbreakdown_errors.hasOwnProperty(
                                  'gasoline'
                                ),
                            }"
                            @keyup="delete deal_dealbreakdown_errors.gasoline"
                            :title="deal_dealbreakdown_errors.gasoline"
                          />
                          <div class="form-control-position">
                            <i
                              class="bx bxs-dollar-circle text-success font-medium-3"
                            ></i>
                          </div>
                        </fieldset>
                        <small
                          id="deal_dealbreakdown_gasoline-error"
                          class="form-text invalid-feedback"
                          >{{ deal_dealbreakdown_errors.gasoline }}</small
                        >
                      </div>
                    </div>
                    <!-- deal_dealbreakdown_form gasoline : END -->

                    <!-- deal_dealbreakdown_form lien_payout : START -->
                    <div class="col-xs-12 col-md-12 d-none">
                      <div class="form-group">
                        <label for="deal_dealbreakdown_form_lien_payout"
                          >Lien Payout</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_lien_payout"
                            type="text"
                            readonly
                            placeholder="Lien Fee"
                            v-model="deal_dealbreakdown_form.lien_payout"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_dealbreakdown_errors.hasOwnProperty(
                                  'lien_payout'
                                ),
                            }"
                            @keyup="
                              delete deal_dealbreakdown_errors.lien_payout
                            "
                            :title="deal_dealbreakdown_errors.lien_payout"
                          />
                          <div class="form-control-position">
                            <i
                              class="bx bxs-plus-circle text-success font-medium-3"
                            ></i>
                          </div>
                        </fieldset>
                        <small
                          id="deal_dealbreakdown_lien_payout-error"
                          class="form-text invalid-feedback"
                          >{{ deal_dealbreakdown_errors.lien_payout }}</small
                        >
                      </div>
                    </div>
                    <!-- deal_dealbreakdown_form lien_payout : END -->

                    <!-- deal_dealbreakdown_form secondary_subtotal : START -->
                    <div class="col-xs-12 col-md-12 d-none">
                      <div class="form-group">
                        <label for="deal_dealbreakdown_secondary_subtotal"
                          >Subtotal</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_secondary_subtotal"
                            type="text"
                            readonly
                            placeholder="Subtotal"
                            v-model="deal_dealbreakdown_form.secondary_subtotal"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_dealbreakdown_errors.hasOwnProperty(
                                  'secondary_subtotal'
                                ),
                            }"
                            @keyup="
                              delete deal_dealbreakdown_errors.secondary_subtotal
                            "
                            :title="
                              deal_dealbreakdown_errors.secondary_subtotal
                            "
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="monthly_payment-error"
                          class="form-text invalid-feedback"
                          >{{
                            deal_dealbreakdown_errors.secondary_subtotal
                          }}</small
                        >
                      </div>
                    </div>
                    <!--  deal_dealbreakdown_purchase secondary_subtotal  : END -->

                    <!-- plate_type : START -->
                    <div class="col-xs-12 col-md-12">
                      <div class="form-group">
                        <div class="">
                          <label style="display: block" class="mb-1">
                            Plate Type
                          </label>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              v-model="deal_dealbreakdown_form.plate_type"
                              type="radio"
                              id="new_plates"
                              value="New Plates"
                            />
                            <label class="form-check-label" for="new_plates"
                              >New Plates</label
                            >
                          </div>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              v-model="deal_dealbreakdown_form.plate_type"
                              type="radio"
                              id="renewal_only"
                              value="Renewal Only"
                            />
                            <label class="form-check-label" for="renewal_only"
                              >Renewal Only</label
                            >
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- plate_type : END -->

                    <!-- deal_dealbreakdown_form payment : START -->
                    <div
                      class="col-xs-12 col-md-8"
                      v-show="
                        isDealRetailOrWholesale == 0 ||
                        isPaymentSectionEnabledForWholeSaleDeal
                      "
                    >
                      <div class="form-group">
                        <label for="deal_dealbreakdown_form_payment"
                          >Total Payments</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_payment"
                            type="text"
                            placeholder="Total Payment"
                            v-model="calculateTotalPayment"
                            class="form-control"
                            readonly
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                      </div>
                    </div>
                    <div
                      class="col-xs-12 col-md-4 p-0 pt-1"
                      v-show="
                        isDealRetailOrWholesale == 0 ||
                        isPaymentSectionEnabledForWholeSaleDeal
                      "
                    >
                      <button
                        class="btn btn-link p-0"
                        data-toggle="modal"
                        data-target="#dealPaymentModal"
                      >
                        <i class="bx bxs-plus-square text-success bx-md"></i>
                      </button>
                      <button
                        class="btn btn-link p-0"
                        onclick='document.getElementById("deal-payments-table").parentElement.parentElement.scrollIntoView({behavior: "smooth"});'
                      >
                        <i class="bx bx-list-ul text-primary bx-md"></i>
                      </button>
                    </div>
                    <!-- deal_dealbreakdown_form payment : END -->

                    <!-- deal_dealbreakdown_form lien_registration_fee : START -->
                    <div
                      class="col-xs-12 col-md-12"
                      v-show="isDealRetailOrWholesale == 0"
                    >
                      <div class="form-group">
                        <label for="lien_registration_fee"
                          >Lien Registration Fee</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="lien_registration_fee"
                            type="text"
                            placeholder="Lien Registration Fee"
                            v-model="
                              deal_dealbreakdown_form.lien_registration_fee
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_dealbreakdown_errors.hasOwnProperty(
                                  'lien_registration_fee'
                                ),
                            }"
                            @keyup="
                              delete deal_dealbreakdown_errors.lien_registration_fee
                            "
                            :title="
                              deal_dealbreakdown_errors.lien_registration_fee
                            "
                          />
                          <div class="form-control-position">
                            <i
                              class="bx bxs-dollar-circle text-success font-medium-3"
                            ></i>
                          </div>
                        </fieldset>
                        <small
                          id="deal_dealbreakdown_lien_registration_fee-error"
                          class="form-text invalid-feedback"
                          >{{
                            deal_dealbreakdown_errors.lien_registration_fee
                          }}</small
                        >
                      </div>
                    </div>
                    <!-- deal_dealbreakdown_form lien_registration_fee : END -->

                    <!-- deal_dealbreakdown_form insurance_tax : START -->
                    <div class="col-xs-12 col-md-12 d-none">
                      <div class="form-group">
                        <label for="deal_dealbreakdown_form_insurance_tax"
                          >Insurance Tax</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_insurance_tax"
                            type="text"
                            placeholder="Insurance Tax"
                            v-model="deal_dealbreakdown_form.insurance_tax"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_dealbreakdown_errors.hasOwnProperty(
                                  'insurance_tax'
                                ),
                            }"
                            @keyup="
                              delete deal_dealbreakdown_errors.insurance_tax
                            "
                            readonly
                            :title="deal_dealbreakdown_errors.insurance_tax"
                          />
                          <div class="form-control-position">
                            <i
                              class="bx bxs-plus-circle text-success font-medium-3"
                            ></i>
                          </div>
                        </fieldset>
                        <small
                          id="deal_dealbreakdown_insurance_tax-error"
                          class="form-text invalid-feedback"
                          >{{ deal_dealbreakdown_errors.insurance_tax }}</small
                        >
                      </div>
                    </div>
                    <!-- deal_dealbreakdown_form insurance_tax : END -->

                    <!-- deal_dealbreakdown_form trade_equity : START -->
                    <div class="col-xs-12 col-md-12 d-none">
                      <div class="form-group">
                        <label for="deal_dealbreakdown_form_trade_equity"
                          >Trade Equity</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_trade_equity"
                            type="text"
                            placeholder="Trade Equity"
                            readonly
                            v-model="deal_dealbreakdown_form.trade_equity"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_dealbreakdown_errors.hasOwnProperty(
                                  'trade_equity'
                                ),
                            }"
                            @keyup="
                              delete deal_dealbreakdown_errors.trade_equity
                            "
                            :title="deal_dealbreakdown_errors.trade_equity"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="deal_dealbreakdown_trade_equity-error"
                          class="form-text invalid-feedback"
                          >{{ deal_dealbreakdown_errors.trade_equity }}</small
                        >
                      </div>
                    </div>
                    <!-- deal_dealbreakdown_form trade_equity : END -->

                    <!-- deal_dealbreakdown_form total_balance_due : START -->
                    <div class="col-xs-12 col-md-12 d-none">
                      <div class="form-group">
                        <label for="deal_dealbreakdown_form_total_balance_due"
                          >Total Balance Due</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_dealbreakdown_form_total_balance_due"
                            type="text"
                            placeholder="Total Balance Due"
                            v-model="deal_dealbreakdown_form.total_balance_due"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_dealbreakdown_errors.hasOwnProperty(
                                  'total_balance_due'
                                ),
                            }"
                            @keyup="
                              delete deal_dealbreakdown_errors.total_balance_due
                            "
                            :title="deal_dealbreakdown_errors.total_balance_due"
                            readonly
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="deal_dealbreakdown_total_balance_due-error"
                          class="form-text invalid-feedback"
                          >{{
                            deal_dealbreakdown_errors.total_balance_due
                          }}</small
                        >
                      </div>
                    </div>
                    <!-- deal_dealbreakdown_form total_balance_due : END -->
                  </div>
                  <!-- row : END -->
                </div>
              </div>
            </div>
          </div>

          <!-- finance : START -->
          <div
            class="card col-xs-12 col-md-4"
            v-show="deal_type == 'Financed' && isDealRetailOrWholesale == 0"
          >
            <div class="card-content">
              <div class="card-body pb-1 mx-25 row">
                <h4 class="card-title col-6">Finance</h4>
                <div class="col-6" style="text-align: right">
                  <button
                    class="btn btn-primary text-bold-700"
                    @click.prevent="updateWorksheetAndFinance"
                  >
                    <i class="bx bxs-save"></i> Save
                  </button>
                </div>
                <div class="col-12">
                  <!-- row : START -->
                  <div class="row">
                    <!-- deal_finance_form total_amount : START -->

                    <!-- override_finance_calculation : START -->
                    <div class="col-xs-12 col-md-12">
                      <div class="form-group">
                        <div class="custom-control custom-switch">
                          <label for="" style="display: block" class="mb-1">
                          </label>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              v-model="
                                deal_finance_form.override_finance_calculation
                              "
                              type="checkbox"
                              id="overrid_finance_calculation"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label
                              style="padding: 0.4em"
                              class="form-check-label"
                              for="overrid_finance_calculation"
                              >Override Finance Calculation</label
                            >
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- override_finance_calculation : END -->

                    <div class="col-xs-12 col-md-12">
                      <div class="form-group">
                        <label for="deal_finance_total_amount"
                          >Total Amount</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_finance_total_amount"
                            type="text"
                            placeholder="Total Amount"
                            v-model="deal_finance_form.total_amount"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_finance_errors.hasOwnProperty(
                                  'total_amount'
                                ),
                            }"
                            readonly
                            @keyup="delete deal_finance_errors.total_amount"
                            :title="deal_finance_errors.total_amount"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="deal_finance_total_amount-error"
                          class="form-text invalid-feedback"
                          >{{ deal_finance_errors.total_amount }}</small
                        >
                      </div>
                    </div>
                    <!-- deal_finance_form total_amount : END -->

                    <!-- deal_finance_form finance_commission : START -->
                    <div class="col-xs-12 col-md-12">
                      <div class="form-group">
                        <label for="deal_finance_total_amount"
                          >Finance Commission</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_finance_total_amount"
                            type="text"
                            placeholder="Finance Commission"
                            v-model="deal_finance_form.finance_commission"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_finance_errors.hasOwnProperty(
                                  'finance_commission'
                                ),
                            }"
                            @keyup="
                              delete deal_finance_errors.finance_commission
                            "
                            :title="deal_finance_errors.finance_commission"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="deal_finance_finance_commission-error"
                          class="form-text invalid-feedback"
                          >{{ deal_finance_errors.finance_commission }}</small
                        >
                      </div>
                    </div>
                    <!-- deal_finance_form finance_commission : END -->
                    <div class="w-100"></div>

                    <!-- deal_finance_form interest_rate : START -->
                    <div class="col-xs-12 col-md-12">
                      <div class="form-group">
                        <label for="deal_finance_total_amount"
                          >Interest Rate</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_finance_total_amount"
                            type="text"
                            placeholder="Interest Rate"
                            v-model="deal_finance_form.interest_rate"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_finance_errors.hasOwnProperty(
                                  'interest_rate'
                                ),
                            }"
                            @keyup="delete deal_finance_errors.interest_rate"
                            :title="deal_finance_errors.interest_rate"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="deal_finance_interest_rate-error"
                          class="form-text invalid-feedback"
                          >{{ deal_finance_errors.interest_rate }}</small
                        >
                      </div>
                    </div>
                    <!-- deal_finance_form interest_rate : END -->

                    <!-- interest_rate_type : START -->
                    <div class="col-xs-12 col-md-12">
                      <div class="form-group">
                        <div class="custom-control custom-switch">
                          <label for="" style="display: block" class="mb-1">
                            Interest Type
                          </label>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              v-model="deal_finance_form.interest_rate_type"
                              type="radio"
                              id="fixed_rate"
                              value="Fixed"
                            />
                            <label class="form-check-label" for="fixed_rate"
                              >Fixed</label
                            >
                          </div>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              v-model="deal_finance_form.interest_rate_type"
                              type="radio"
                              id="variable_rate"
                              value="Variable Rate"
                            />
                            <label class="form-check-label" for="variable_rate"
                              >Variable Rate</label
                            >
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- interest_rate_type : END -->
                    <!-- deal_finance_form term : START -->
                    <div class="col-xs-12 col-md-12">
                      <div class="form-group">
                        <label for="deal_finance_term">Term</label>
                        <fieldset class="" style="position: relative">
                          <input
                            id="deal_finance_term"
                            type="text"
                            placeholder="Term"
                            v-model="deal_finance_form.term"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_finance_errors.hasOwnProperty('term'),
                            }"
                            @keyup="delete deal_finance_errors.term"
                            :title="deal_finance_errors.term"
                          />
                        </fieldset>
                        <small
                          id="deal_finance_term-error"
                          class="form-text invalid-feedback"
                          >{{ deal_finance_errors.term }}</small
                        >
                      </div>
                    </div>
                    <!-- deal_finance_form term : END -->

                    <!-- deal_finance_form_payment_frequency : START -->
                    <div class="form-group col-md-12 col-xs-12">
                      <label for="deal_finance_payment_frequency"
                        >Payment Frequency</label
                      >
                      <select
                        class="custom-select form-control mr-1"
                        :class="{
                          'is-invalid':
                            deal_finance_errors.hasOwnProperty(
                              'payment_frequency'
                            ),
                        }"
                        @keyup="delete deal_finance_errors.payment_frequency"
                        :title="deal_finance_errors.payment_frequency"
                        v-model="deal_finance_form.payment_frequency"
                        placeholder="Payment Frequency"
                        id="deal_finance_payment_frequency"
                      >
                        <option value="" selected disabled>
                          Select Payment Frequency
                        </option>
                        <option value="Weekly">Weekly</option>
                        <option value="Bi-Weekly">Bi-Weekly</option>
                        <option value="Monthly">Monthly</option>
                      </select>
                    </div>
                    <!-- deal_finance_form_payment_frequency : END -->

                    <!-- deal_finance_form interest_amount : START -->
                    <div class="col-xs-12 col-md-12">
                      <div class="form-group">
                        <label for="deal_finance_interest_amount"
                          >Interest Amount</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_finance_interest_amount"
                            type="text"
                            placeholder="Interest Amount"
                            v-model="deal_finance_form.interest_amount"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_finance_errors.hasOwnProperty(
                                  'interest_amount'
                                ),
                            }"
                            @keyup="delete deal_finance_errors.interest_amount"
                            :title="deal_finance_errors.interest_amount"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="deal_finance_interest_amount-error"
                          class="form-text invalid-feedback"
                          >{{ deal_finance_errors.interest_amount }}</small
                        >
                      </div>
                    </div>
                    <!-- deal_finance_form interest_amount : END -->

                    <!-- deal_finance_form emi_amount : START -->
                    <div class="col-xs-12 col-md-12">
                      <div class="form-group">
                        <label for="deal_finance_emi_amount">Payment</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_finance_emi_amount"
                            type="text"
                            placeholder="Payment"
                            v-model="deal_finance_form.emi_amount"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_finance_errors.hasOwnProperty(
                                  'emi_amount'
                                ),
                            }"
                            @keyup="delete deal_finance_errors.emi_amount"
                            :title="deal_finance_errors.emi_amount"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="deal_finance_emi_amount-error"
                          class="form-text invalid-feedback"
                          >{{ deal_finance_errors.emi_amount }}</small
                        >
                      </div>
                    </div>
                    <!-- deal_finance_form emi_amount : END -->

                    <!-- deal_finance_form lien_holder_name : START -->
                    <div class="col-xs-12 col-md-12">
                      <div class="form-group">
                        <label for="deal_finance_lien_holder_name"
                          >Lien Holder Name</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="deal_finance_lien_holder_name"
                            type="text"
                            placeholder="Lien Holder Name"
                            v-model="deal_finance_form.lien_holder_name"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                deal_finance_errors.hasOwnProperty(
                                  'lien_holder_name'
                                ),
                            }"
                            @keyup="delete deal_finance_errors.lien_holder_name"
                            :title="deal_finance_errors.lien_holder_name"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-bank font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="deal_finance_lien_holder_name-error"
                          class="form-text invalid-feedback"
                          >{{ deal_finance_errors.lien_holder_name }}</small
                        >
                      </div>
                    </div>
                    <!-- deal_finance_form lien_holder_name : END -->

                    <!-- first_payment_date : START -->
                    <div class="col-md-12 col-xs-12">
                      <div class="form-group">
                        <label for="first_payment_date"
                          >First Payment Date</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <date-picker
                            placeholder="First Payment Date"
                            v-model="deal_finance_form.first_payment_date"
                            :clearable="false"
                            :format="'MM/DD/YYYY'"
                            :value-type="'MM/DD/YYYY'"
                            style="height: 3.9em"
                            id="first_payment_date"
                            :class="{
                              'is-invalid':
                                deal_finance_errors.hasOwnProperty(
                                  'first_payment_date'
                                ),
                            }"
                            @change="
                              delete deal_finance_errors.first_payment_date
                            "
                            :title="deal_finance_errors.first_payment_date"
                          >
                          </date-picker>
                        </fieldset>
                        <small
                          id="first_payment_date-error"
                          class="form-text invalid-feedback"
                          >{{ deal_finance_errors.first_payment_date }}</small
                        >
                      </div>
                    </div>
                    <!-- first_payment_date : END -->
                  </div>
                  <!-- row : END -->
                </div>
              </div>
            </div>
          </div>
          <!-- finance : END -->
          <!-- deal summary : START -->
          <div class="card col-xs-12 col-md-4">
            <div class="card-content">
              <div class="card-body pb-1 row">
                <h4 class="card-title col-12">Deal Summary</h4>
                <div class="col-12">
                  <!-- row : START -->
                  <div class="row">
                    <ul class="list-group col-12">
                      <li
                        class="list-group-item p-0 row"
                        style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                      >
                        <span class="col-md-7 pl-1 pr-0 d-inline-block"
                          ><i
                            style="vertical-align: middle"
                            class="bx bxs-plus-circle text-success font-medium-3"
                          ></i>
                          Selling Price</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(
                                deal_dealbreakdown_form.purchase_price
                              ).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                      <li
                        class="list-group-item p-0 row"
                        v-show="isDealRetailOrWholesale == 0"
                        style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                      >
                        <span class="col-md-7 pr-0 pl-1 d-inline-block"
                          ><i
                            style="vertical-align: middle"
                            class="bx bxs-plus-circle text-success font-medium-3"
                          ></i>
                          Extended Warranty</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(
                                calculateDealTotalWarrantyAmount
                              ).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                      <li
                        class="list-group-item p-0 row"
                        style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                      >
                        <span class="col-md-7 pr-0 pl-1 d-inline-block"
                          ><i
                            style="vertical-align: middle"
                            class="bx bxs-plus-circle text-success font-medium-3"
                          ></i>
                          Fees</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(
                                calculateDealTotalFees
                              ).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                      <li
                        class="list-group-item p-0 row"
                        v-show="isDealRetailOrWholesale == 0"
                        style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                      >
                        <span class="col-md-7 pr-0 pl-1 d-inline-block"
                          ><i
                            style="vertical-align: middle"
                            class="bx bxs-plus-circle text-success font-medium-3"
                          ></i>
                          Accessories</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(
                                calculateDealTotalAccessoryAmount
                              ).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                      <li class="list-group-item p-0 pb-1 pt-1 row">
                        <span class="col-md-7 pr-0 pl-1 d-inline-block"
                          ><i
                            style="vertical-align: sub"
                            class="bx bxs-calculator text-primary font-medium-3"
                          ></i>
                          Subtotal</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(calculateSubTotal).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                      <li
                        class="list-group-item p-0 row"
                        v-show="isDealRetailOrWholesale == 0"
                        style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                      >
                        <span class="col-md-7 pr-0 pl-1 d-inline-block"
                          ><i
                            style="vertical-align: middle"
                            class="bx bxs-minus-circle text-danger font-medium-3"
                          ></i>
                          Trade Value</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(
                                deal_dealbreakdown_form.trade_value
                              ).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                      <li
                        class="list-group-item p-0 pb-1 pt-1 row"
                        v-show="isDealRetailOrWholesale == 0"
                      >
                        <span class="col-md-7 pr-0 pl-1 d-inline-block"
                          ><i
                            style="vertical-align: sub"
                            class="bx bxs-calculator text-primary font-medium-3"
                          ></i>
                          Net Difference</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(
                                deal_dealbreakdown_form.net_difference
                              ).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                      <li
                        class="list-group-item p-0 row"
                        style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                      >
                        <span class="col-md-7 pr-0 pl-1 d-inline-block"
                          ><i
                            style="vertical-align: middle"
                            class="bx bxs-plus-circle text-success font-medium-3"
                          ></i>
                          Total Tax</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(
                                deal_dealbreakdown_form.total_tax_amount
                              ).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                      <li
                        class="list-group-item p-0 row"
                        style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                      >
                        <span class="col-md-7 pr-0 pl-1 d-inline-block"
                          ><i
                            style="vertical-align: middle"
                            class="bx bxs-plus-circle text-success font-medium-3"
                          ></i>
                          Licence Fee</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(
                                deal_dealbreakdown_form.licence_fee
                              ).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                      <li
                        class="list-group-item p-0 row"
                        v-show="isDealRetailOrWholesale == 0"
                        style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                      >
                        <span class="col-md-7 pr-0 pl-1 d-inline-block"
                          ><i
                            style="vertical-align: middle"
                            class="bx bxs-plus-circle text-success font-medium-3"
                          ></i>
                          Gasoline</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(
                                deal_dealbreakdown_form.gasoline
                              ).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                      <li
                        class="list-group-item p-0 row"
                        v-show="isDealRetailOrWholesale == 0"
                        style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                      >
                        <span class="col-md-7 pr-0 pl-1 d-inline-block"
                          ><i
                            style="vertical-align: middle"
                            class="bx bxs-plus-circle text-success font-medium-3"
                          ></i>
                          Lien Payout</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(
                                deal_dealbreakdown_form.lien_payout
                              ).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                      <li
                        class="list-group-item p-0 pb-1 pt-1 row"
                        v-show="isDealRetailOrWholesale == 0"
                      >
                        <span class="col-md-7 pr-0 pl-1 d-inline-block"
                          ><i
                            style="vertical-align: sub"
                            class="bx bxs-calculator text-primary font-medium-3"
                          ></i>
                          Subtotal</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(
                                deal_dealbreakdown_form.secondary_subtotal
                              ).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                      <li
                        class="list-group-item p-0 row"
                        v-show="
                          isDealRetailOrWholesale == 0 ||
                          isPaymentSectionEnabledForWholeSaleDeal
                        "
                        style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                      >
                        <span class="col-md-7 pr-0 pl-1 d-inline-block"
                          ><i
                            style="vertical-align: middle"
                            class="bx bxs-minus-circle text-danger font-medium-3"
                          ></i>
                          Total Payment</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(
                                calculateTotalPayment
                              ).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                      <li
                        class="list-group-item p-0 row"
                        v-show="isDealRetailOrWholesale == 0"
                        style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                      >
                        <span class="col-md-7 pr-0 pl-1 d-inline-block"
                          ><i
                            style="vertical-align: middle"
                            class="bx bxs-plus-circle text-success font-medium-3"
                          ></i>
                          Total Insurance</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(
                                calculateDealTotalInsuranceAmount
                              ).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                      <li
                        class="list-group-item p-0 row"
                        v-show="isDealRetailOrWholesale == 0"
                        style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                      >
                        <span class="col-md-7 pr-0 pl-1 d-inline-block"
                          ><i
                            style="vertical-align: middle"
                            class="bx bxs-plus-circle text-success font-medium-3"
                          ></i>
                          Insurance Tax</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(
                                calculateInsuranceTaxAmount
                              ).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                      <li
                        class="list-group-item p-0 row"
                        v-show="isDealRetailOrWholesale == 0"
                        style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                      >
                        <span class="col-md-7 pr-0 pl-1 d-inline-block"
                          ><i
                            style="vertical-align: middle"
                            class="bx bxs-plus-circle text-success font-medium-3"
                          ></i>
                          Lien Registration Fee</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(
                                deal_dealbreakdown_form.lien_registration_fee
                              ).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                      <li
                        class="list-group-item p-0 row"
                        v-show="
                          isDealRetailOrWholesale == 0 &&
                          total_balance_cost_includes_interest == 1 &&
                          deal_type == 'Financed'
                        "
                        style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                      >
                        <span class="col-md-7 pr-0 pl-1 d-inline-block"
                          ><i
                            style="vertical-align: middle"
                            class="bx bxs-plus-circle text-success font-medium-3"
                          ></i>
                          Interest Amount</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(
                                deal_finance_form.interest_amount
                              ).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                      <li class="list-group-item p-0 pb-1 pt-1 row">
                        <span class="col-md-7 pr-0 pl-1 d-inline-block"
                          ><i
                            style="vertical-align: sub"
                            class="bx bxs-calculator text-primary font-medium-3"
                          ></i>
                          Total Balance Due</span
                        >
                        <span class="col-md-4 pl-0 pr-0 d-inline-block">
                          <span style="text-align: right" class="d-block">
                            {{
                              `$ ${Number(
                                deal_dealbreakdown_form.total_balance_due
                              ).toLocaleString()}`
                            }}
                          </span>
                        </span>
                      </li>
                    </ul>
                  </div>

                  <!-- Roll A Deal : START -->
                  <div class="row">
                    <div class="card">
                      <div class="card-content">
                        <div class="card-body pb-1 row">
                          <h4 class="card-title col-12">Roll A Deal</h4>

                          <!-- adjust amount : START -->
                          <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                              <label
                                for="deal_dealbreakdown_form_purchase_price"
                                >Adjust
                                {{ requestAdjustmentColumnLabel }} Amount</label
                              >
                              <fieldset
                                class="has-icon-left"
                                style="position: relative"
                              >
                                <input
                                  id="adjust_price"
                                  type="text"
                                  placeholder="Adjust Amount"
                                  v-model="roll_a_deal.adjust_amount"
                                  class="form-control"
                                  title="Adjust Amount"
                                />
                                <div class="form-control-position">
                                  <i
                                    class="bx bxs-dollar-circle font-medium-3"
                                  ></i>
                                </div>
                              </fieldset>
                            </div>
                          </div>
                          <!-- adjust amount : END -->
                          <!-- adjust column : START -->
                          <div class="col-xs-12 col-md-12">
                            <label for="adjust_column">Adjust Column</label>
                            <select
                              class="custom-select form-control mr-1"
                              title="Adjust Column"
                              v-model="roll_a_deal.adjust_column"
                              placeholder="Adjust Column"
                              id="adjust_column"
                            >
                              <option value="" selected disabled>
                                Select Column
                              </option>
                              <option
                                v-for="{
                                  label,
                                  value,
                                } in availableAdjustableColumns"
                                :key="label"
                                :value="value"
                              >
                                {{ label }}
                              </option>
                            </select>
                          </div>
                          <!-- adjust column : END -->
                          <!-- roll a deal : START -->
                          <div
                            class="col-xs-12 col-md-12 pt-1"
                            style="text-align: right"
                          >
                            <button
                              class="btn btn-secondary text-bold-700"
                              @click="rollADeal"
                              :disabled="
                                roll_a_deal.adjust_amount == 0 ||
                                roll_a_deal.adjust_column == ''
                              "
                            >
                              <i
                                class="bx bx-buoy"
                                :class="boll_a_deal_button_class"
                              ></i>
                              Roll
                            </button>
                          </div>
                          <!-- roll a deal : END -->
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Roll A Deal : END -->
                </div>
              </div>
            </div>
          </div>
          <!-- deal summary : END -->
        </div>
        <!-- deal breakdown : END -->
        <!-- deal fees : START -->
        <div class="col-12 col-xs-12 col-md-12">
          <!-- card : START -->
          <div class="card">
            <div class="card-content">
              <div class="card-body pb-1 mx-25 row">
                <h4 class="card-title col-8">
                  <i
                    class="bx bxs-dollar-circle bx-sm"
                    style="padding-right: 1em"
                  ></i>
                  <span style="display: inline-block; vertical-align: top"
                    >Fees</span
                  >
                </h4>
                <div class="col-4 text-right" v-show="feeCount < 5">
                  <button
                    class="btn btn-primary text-bold-700"
                    data-toggle="modal"
                    data-target="#dealFeeModal"
                  >
                    <i class="bx bx-list-plus" style="padding-right: 1em"></i>
                    Add Fee
                  </button>
                </div>
                <!-- row : START -->
                <div class="row col-12">
                  <!-- deal fees : START -->
                  <div
                    id="deal-fees-table"
                    class="content-wrapper mt-0 p-0 col-12"
                  >
                    <table class="list-page-content-p mt-0 mb-0 w-100">
                      <thead>
                        <tr class="bg-grey">
                          <th class="text-center">Name</th>
                          <th class="text-center">Description</th>
                          <th class="text-center" style="min-width: 75px">
                            Amount
                          </th>
                          <th class="text-center" style="min-width: 75px">
                            Tax Type
                          </th>
                          <th class="text-center" style="min-width: 75px">
                            Taxes
                          </th>
                          <th class="text-center">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="deal_fee in deal_fees"
                          :key="`deal_fee` + deal_fee.id"
                        >
                          <td class="text-center">
                            {{ deal_fee.name | truncate(25, "...") }}
                          </td>
                          <td class="text-center">
                            {{ deal_fee.description | truncate(50, "...") }}
                          </td>
                          <td class="text-center">
                            {{
                              `$ ${Number(deal_fee.amount).toLocaleString()}`
                            }}
                          </td>
                          <td class="text-center">
                            <p class="mb-0">{{ deal_fee.tax_type }}</p>
                            <p
                              class="mb-0"
                              v-show="deal_fee.tax_type_secondary !== ''"
                            >
                              {{ deal_fee.tax_type_secondary }}
                            </p>
                          </td>
                          <td class="text-center">
                            {{
                              `$ ${Number(
                                deal_fee.tax_amount_total
                              ).toLocaleString()}`
                            }}
                          </td>
                          <td class="text-center">
                            <div class="dropdown">
                              <span
                                class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                                role="menu"
                              ></span>
                              <div
                                class="dropdown-menu dropdown-menu-right"
                                x-placement="bottom-end"
                                style="
                                  position: absolute;
                                  will-change: transform;
                                  top: 0px;
                                  left: 0px;
                                  transform: translate3d(19px, 19px, 0px);
                                "
                              >
                                <a
                                  class="dropdown-item"
                                  style="cursor: pointer"
                                  @click.prevent="editDealFee(deal_fee.id)"
                                  >Edit</a
                                >
                                <a
                                  class="dropdown-item"
                                  style="cursor: pointer"
                                  @click.prevent="deleteDealFee(deal_fee.id)"
                                  >Delete</a
                                >
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- deal fees : END -->
                </div>
                <!-- row : END -->
              </div>
            </div>
          </div>
          <!-- card : END -->

          <!-- card : START -->
          <div class="card" v-show="isDealRetailOrWholesale == 0">
            <div class="card-content">
              <div class="card-body pb-1 mx-25 row">
                <h4 class="card-title col-8">
                  <i class="bx bxs-widget bx-sm" style="padding-right: 1em"></i>
                  <span style="display: inline-block; vertical-align: top"
                    >Accessories</span
                  >
                </h4>
                <div class="col-4 text-right">
                  <button
                    class="btn btn-primary text-bold-700"
                    data-toggle="modal"
                    data-target="#dealAccessoryModal"
                  >
                    <i class="bx bx-list-plus" style="padding-right: 1em"></i>
                    Add Accessory
                  </button>
                </div>
                <!-- row : START -->
                <div class="row col-12">
                  <!-- deal fees : START -->
                  <div
                    id="deal-accessories-table"
                    class="content-wrapper mt-0 p-0 col-12"
                  >
                    <table class="list-page-content-p mt-0 mb-0 w-100">
                      <thead>
                        <tr class="bg-grey">
                          <th class="text-center">Name</th>
                          <th class="text-center">Description</th>
                          <th class="text-center" style="min-width: 75px">
                            Amount
                          </th>
                          <th class="text-center" style="min-width: 75px">
                            Tax Type
                          </th>
                          <th class="text-center" style="min-width: 75px">
                            Taxes
                          </th>
                          <th class="text-center">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="deal_accessory in deal_accessories"
                          :key="`deal_accessory` + deal_accessory.id"
                        >
                          <td class="text-center">
                            {{ deal_accessory.name | truncate(25, "...") }}
                          </td>
                          <td class="text-center">
                            {{
                              deal_accessory.description | truncate(50, "...")
                            }}
                          </td>
                          <td class="text-center">
                            {{
                              `$ ${Number(
                                deal_accessory.amount
                              ).toLocaleString()}`
                            }}
                          </td>
                          <td class="text-center">
                            <p class="mb-0">{{ deal_accessory.tax_type }}</p>
                            <p
                              class="mb-0"
                              v-show="deal_accessory.tax_type_secondary !== ''"
                            >
                              {{ deal_accessory.tax_type_secondary }}
                            </p>
                          </td>
                          <td class="text-center">
                            {{
                              `$ ${Number(
                                deal_accessory.tax_amount_total
                              ).toLocaleString()}`
                            }}
                          </td>
                          <td class="text-center">
                            <div class="dropdown">
                              <span
                                class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                                role="menu"
                              ></span>
                              <div
                                class="dropdown-menu dropdown-menu-right"
                                x-placement="bottom-end"
                                style="
                                  position: absolute;
                                  will-change: transform;
                                  top: 0px;
                                  left: 0px;
                                  transform: translate3d(19px, 19px, 0px);
                                "
                              >
                                <a
                                  class="dropdown-item"
                                  style="cursor: pointer"
                                  @click.prevent="
                                    editDealAccessory(deal_accessory.id)
                                  "
                                  >Edit</a
                                >
                                <a
                                  class="dropdown-item"
                                  style="cursor: pointer"
                                  @click.prevent="
                                    deleteDealAccessory(deal_accessory.id)
                                  "
                                  >Delete</a
                                >
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- deal fees : END -->
                </div>
                <!-- row : END -->
              </div>
            </div>
          </div>
          <!-- card : END -->

          <!-- card : START -->
          <div class="card" v-show="isDealRetailOrWholesale == 0">
            <div class="card-content">
              <div class="card-body pb-1 mx-25 row">
                <h4 class="card-title col-8">
                  <i
                    class="bx bxs-shield-alt-2 bx-sm"
                    style="padding-right: 1em"
                  ></i>
                  <span style="display: inline-block; vertical-align: top"
                    >Warranties</span
                  >
                </h4>
                <div class="col-4 text-right" v-show="warrantyCount == 0">
                  <button
                    class="btn btn-primary text-bold-700"
                    data-toggle="modal"
                    data-target="#dealWarrantyModal"
                  >
                    <i class="bx bx-list-plus" style="padding-right: 1em"></i>
                    Add Warranty
                  </button>
                </div>
                <!-- row : START -->
                <div class="row col-12">
                  <!-- deal fees : START -->
                  <div
                    id="deal-warranties-table"
                    class="content-wrapper mt-0 p-0 col-12"
                  >
                    <table class="list-page-content-p mt-0 mb-0 w-100">
                      <thead>
                        <tr class="bg-grey">
                          <th class="text-center">Company Name</th>
                          <th class="text-center">Description</th>
                          <th class="text-center" style="min-width: 75px">
                            Amount
                          </th>
                          <th class="text-center" style="min-width: 75px">
                            Tax Type
                          </th>
                          <th class="text-center" style="min-width: 75px">
                            Taxes
                          </th>
                          <th class="text-center">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="deal_warranty in deal_warranties"
                          :key="`deal_warranty` + deal_warranty.id"
                        >
                          <td class="text-center">
                            {{
                              deal_warranty.company_name | truncate(25, "...")
                            }}
                          </td>
                          <td class="text-center">
                            {{
                              deal_warranty.description | truncate(50, "...")
                            }}
                          </td>
                          <td class="text-center">
                            {{
                              `$ ${Number(
                                deal_warranty.amount
                              ).toLocaleString()}`
                            }}
                          </td>
                          <td class="text-center">
                            <p class="mb-0">{{ deal_warranty.tax_type }}</p>
                            <p
                              class="mb-0"
                              v-show="deal_warranty.tax_type_secondary !== ''"
                            >
                              {{ deal_warranty.tax_type_secondary }}
                            </p>
                          </td>
                          <td class="text-center">
                            {{
                              `$ ${Number(
                                deal_warranty.tax_amount_total
                              ).toLocaleString()}`
                            }}
                          </td>
                          <td class="text-center">
                            <div class="dropdown">
                              <span
                                class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                                role="menu"
                              ></span>
                              <div
                                class="dropdown-menu dropdown-menu-right"
                                x-placement="bottom-end"
                                style="
                                  position: absolute;
                                  will-change: transform;
                                  top: 0px;
                                  left: 0px;
                                  transform: translate3d(19px, 19px, 0px);
                                "
                              >
                                <a
                                  class="dropdown-item"
                                  style="cursor: pointer"
                                  @click.prevent="
                                    editDealWarranty(deal_warranty.id)
                                  "
                                  >Edit</a
                                >
                                <a
                                  class="dropdown-item"
                                  style="cursor: pointer"
                                  @click.prevent="
                                    deleteDealWarranty(deal_warranty.id)
                                  "
                                  >Delete</a
                                >
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- deal fees : END -->
                </div>
                <!-- row : END -->
              </div>
            </div>
          </div>
          <!-- card : END -->

          <!-- card : START -->
          <div class="card" v-show="isDealRetailOrWholesale == 0">
            <div class="card-content">
              <div class="card-body pb-1 mx-25 row">
                <h4 class="card-title col-8">
                  <i
                    class="bx bxs-check-shield bx-sm"
                    style="padding-right: 1em"
                  ></i>
                  <span style="display: inline-block; vertical-align: top"
                    >Guarantees</span
                  >
                </h4>
                <div class="col-4 text-right" v-show="guaranteeCount == 0">
                  <button
                    class="btn btn-primary text-bold-700"
                    data-toggle="modal"
                    data-target="#dealGuaranteeModal"
                  >
                    <i class="bx bx-list-plus" style="padding-right: 1em"></i>
                    Add Guarantee
                  </button>
                </div>
                <!-- row : START -->
                <div class="row col-12">
                  <!-- deal fees : START -->
                  <div
                    id="deal-guarantees-table"
                    class="content-wrapper mt-0 p-0 col-12"
                  >
                    <table class="list-page-content-p mt-0 mb-0 w-100">
                      <thead>
                        <tr class="bg-grey">
                          <th class="text-center">Company Name</th>
                          <th class="text-center">Description</th>
                          <th class="text-center" style="min-width: 75px">
                            Amount
                          </th>
                          <th class="text-center" style="min-width: 75px">
                            Tax Type
                          </th>
                          <th class="text-center" style="min-width: 75px">
                            Taxes
                          </th>
                          <th class="text-center">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="deal_guarantee in deal_guarantees"
                          :key="`deal_guarantee` + deal_guarantee.id"
                        >
                          <td class="text-center">
                            {{
                              deal_guarantee.company_name | truncate(25, "...")
                            }}
                          </td>
                          <td class="text-center">
                            {{
                              deal_guarantee.description | truncate(50, "...")
                            }}
                          </td>
                          <td class="text-center">
                            {{
                              `$ ${Number(
                                deal_guarantee.amount
                              ).toLocaleString()}`
                            }}
                          </td>
                          <td class="text-center">
                            <p class="mb-0">{{ deal_guarantee.tax_type }}</p>
                            <p
                              class="mb-0"
                              v-show="deal_guarantee.tax_type_secondary !== ''"
                            >
                              {{ deal_guarantee.tax_type_secondary }}
                            </p>
                          </td>
                          <td class="text-center">
                            {{
                              `$ ${Number(
                                deal_guarantee.tax_amount_total
                              ).toLocaleString()}`
                            }}
                          </td>
                          <td class="text-center">
                            <div class="dropdown">
                              <span
                                class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                                role="menu"
                              ></span>
                              <div
                                class="dropdown-menu dropdown-menu-right"
                                x-placement="bottom-end"
                                style="
                                  position: absolute;
                                  will-change: transform;
                                  top: 0px;
                                  left: 0px;
                                  transform: translate3d(19px, 19px, 0px);
                                "
                              >
                                <a
                                  class="dropdown-item"
                                  style="cursor: pointer"
                                  @click.prevent="
                                    editDealGuarantee(deal_guarantee.id)
                                  "
                                  >Edit</a
                                >
                                <a
                                  class="dropdown-item"
                                  style="cursor: pointer"
                                  @click.prevent="
                                    deleteDealGuarantee(deal_guarantee.id)
                                  "
                                  >Delete</a
                                >
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- deal fees : END -->
                </div>
                <!-- row : END -->
              </div>
            </div>
          </div>
          <!-- card : END -->

          <!-- card : START -->
          <div class="card" v-show="isDealRetailOrWholesale == 0">
            <div class="card-content">
              <div class="card-body pb-1 mx-25 row">
                <h4 class="card-title col-8">
                  <i
                    class="bx bxs-first-aid bx-sm"
                    style="padding-right: 1em"
                  ></i>
                  <span style="display: inline-block; vertical-align: top"
                    >Insurances</span
                  >
                </h4>
                <div class="col-4 text-right">
                  <button
                    class="btn btn-primary text-bold-700"
                    data-toggle="modal"
                    data-target="#dealInsuranceModal"
                  >
                    <i class="bx bx-list-plus" style="padding-right: 1em"></i>
                    Add Insurance
                  </button>
                </div>
                <!-- row : START -->
                <div class="row col-12">
                  <!-- deal fees : START -->
                  <div
                    id="deal-insurances-table"
                    class="content-wrapper mt-0 p-0 col-12"
                  >
                    <table class="list-page-content-p mt-0 mb-0 w-100">
                      <thead>
                        <tr class="bg-grey">
                          <th class="text-center">Type</th>
                          <th class="text-center">Description</th>
                          <th class="text-center" style="min-width: 75px">
                            Amount
                          </th>
                          <th class="text-center" style="min-width: 75px">
                            Tax Type
                          </th>
                          <th class="text-center" style="min-width: 75px">
                            Taxes
                          </th>
                          <th class="text-center">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="deal_insurance in deal_insurances"
                          :key="`deal_insurance` + deal_insurance.id"
                        >
                          <td class="text-center">
                            {{ deal_insurance.type | truncate(25, "...") }}
                          </td>
                          <td class="text-center">
                            {{
                              deal_insurance.description | truncate(50, "...")
                            }}
                          </td>
                          <td class="text-center">
                            {{
                              `$ ${Number(
                                deal_insurance.amount
                              ).toLocaleString()}`
                            }}
                          </td>
                          <td class="text-center">
                            <p class="mb-0">{{ deal_insurance.tax_type }}</p>
                            <p
                              class="mb-0"
                              v-show="deal_insurance.tax_type_secondary !== ''"
                            >
                              {{ deal_insurance.tax_type_secondary }}
                            </p>
                          </td>
                          <td class="text-center">
                            {{
                              `$ ${Number(
                                deal_insurance.tax_amount_total
                              ).toLocaleString()}`
                            }}
                          </td>
                          <td class="text-center">
                            <div class="dropdown">
                              <span
                                class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                                role="menu"
                              ></span>
                              <div
                                class="dropdown-menu dropdown-menu-right"
                                x-placement="bottom-end"
                                style="
                                  position: absolute;
                                  will-change: transform;
                                  top: 0px;
                                  left: 0px;
                                  transform: translate3d(19px, 19px, 0px);
                                "
                              >
                                <a
                                  class="dropdown-item"
                                  style="cursor: pointer"
                                  @click.prevent="
                                    editDealInsurance(deal_insurance.id)
                                  "
                                  >Edit</a
                                >
                                <a
                                  class="dropdown-item"
                                  style="cursor: pointer"
                                  @click.prevent="
                                    deleteDealInsurance(deal_insurance.id)
                                  "
                                  >Delete</a
                                >
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- deal fees : END -->
                </div>
                <!-- row : END -->
              </div>
            </div>
          </div>
          <!-- card : END -->

          <!-- card : START -->
          <div
            class="card"
            v-show="
              isDealRetailOrWholesale == 0 ||
              isPaymentSectionEnabledForWholeSaleDeal
            "
          >
            <div class="card-content">
              <div class="card-body pb-1 mx-25 row">
                <h4 class="card-title col-8">
                  <i
                    class="bx bxs-credit-card-alt bx-sm"
                    style="padding-right: 1em"
                  ></i>
                  <span style="display: inline-block; vertical-align: top"
                    >Payments</span
                  >
                </h4>
                <div class="col-4 text-right">
                  <button
                    class="btn btn-primary text-bold-700"
                    data-toggle="modal"
                    data-target="#dealPaymentModal"
                  >
                    <i class="bx bx-list-plus" style="padding-right: 1em"></i>
                    Add Payment
                  </button>
                </div>
                <!-- row : START -->
                <div class="row col-12">
                  <!-- deal fees : START -->
                  <div
                    id="deal-payments-table"
                    class="content-wrapper mt-0 p-0 col-12"
                  >
                    <table class="list-page-content-p mt-0 mb-0 w-100">
                      <thead>
                        <tr class="bg-grey">
                          <th class="text-center">Type</th>
                          <th class="text-center">Description</th>
                          <th class="text-center" style="min-width: 75px">
                            Amount
                          </th>
                          <th class="text-center">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="deal_payment in deal_payments"
                          :key="`deal_payment` + deal_payment.id"
                        >
                          <td class="text-center">
                            {{ deal_payment.type | truncate(25, "...") }}
                          </td>
                          <td class="text-center">
                            {{ deal_payment.description | truncate(50, "...") }}
                          </td>
                          <td class="text-center">
                            {{
                              `$ ${Number(
                                deal_payment.amount
                              ).toLocaleString()}`
                            }}
                          </td>
                          <td class="text-center">
                            <div class="dropdown">
                              <span
                                class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                                role="menu"
                              ></span>
                              <div
                                class="dropdown-menu dropdown-menu-right"
                                x-placement="bottom-end"
                                style="
                                  position: absolute;
                                  will-change: transform;
                                  top: 0px;
                                  left: 0px;
                                  transform: translate3d(19px, 19px, 0px);
                                "
                              >
                                <a
                                  class="dropdown-item"
                                  style="cursor: pointer"
                                  @click.prevent="
                                    editDealPayment(deal_payment.id)
                                  "
                                  >Edit</a
                                >
                                <a
                                  class="dropdown-item"
                                  style="cursor: pointer"
                                  @click.prevent="
                                    deleteDealPayment(deal_payment.id)
                                  "
                                  >Delete</a
                                >
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <!-- deal fees : END -->
                </div>
                <!-- row : END -->
              </div>
            </div>
          </div>
          <!-- card : END -->
        </div>
        <!-- deal fees : END -->
      </div>
    </section>

    <!-- Model Deal Fees : START -->
    <div
      class="modal fade"
      id="dealFeeModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Deal Fee Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ deal_fees_modal_title }}
            </h5>
            <button
              type="button"
              id="dealFeeModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <small
                    role="alert"
                    class="alert alert-light-warning mb-0 fade show d-block"
                  >
                   You can now enter negative amounts to reflect credits or discounts.
              </small>
              <!-- vselect : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="costs">Search Fee : </label>
                  <v-select
                    taggable
                    placeholder="Search Fee Name"
                    label="name"
                    :reduce="(cost) => cost.id"
                    v-model="selected_cost_name"
                    clearable
                    :options="cost_setting_listing"
                  ></v-select>
                </div>
              </div>
              <!-- vselect : END -->
              <!-- name : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_fees_name">Name : </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': deal_fees_errors.hasOwnProperty('name'),
                    }"
                    @keyup="delete deal_fees_errors.name"
                    :title="deal_fees_errors.name"
                    v-model="deal_fees_form.name"
                    id="deal_fees_name"
                    placeholder="Name"
                  />
                </div>
              </div>
              <!-- name : END -->
              <!-- description : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_fees_description">Description : </label>
                  <textarea
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid':
                        deal_fees_errors.hasOwnProperty('description'),
                    }"
                    @keyup="delete deal_fees_errors.description"
                    :title="deal_fees_errors.description"
                    v-model="deal_fees_form.description"
                    id="deal_fees_description"
                    placeholder="Description"
                  ></textarea>
                </div>
              </div>
              <!-- description : END -->
              <!-- combination : START -->
              <div class="form-row">
                <!-- amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_fees_amount">Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_fees_errors.hasOwnProperty('amount'),
                    }"
                    @keyup="delete deal_fees_errors.amount"
                    :title="deal_fees_errors.amount"
                    v-model="deal_fees_form.amount"
                    placeholder="Amount"
                    class="form-control"
                    id="deal_fees_amount"
                    v-decimal
                    data-default-value=0
                    inputmode="numeric"
                  />
                </div>
                <!-- amount : END -->

                <!-- tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_fee_tax_type">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_fees_errors.hasOwnProperty('tax_type'),
                    }"
                    @keyup="delete deal_fees_errors.tax_type"
                    :title="deal_fees_errors.tax_type"
                    v-model="deal_fees_form.tax_type"
                    placeholder="Tax Type"
                    id="deal_fee_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.id"
                    >
                      {{ tax.name }}
                    </option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <!-- tax_type : END -->

                <!-- secondary_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_fee_tax_type_secondary">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid':
                        deal_fees_errors.hasOwnProperty('tax_type_secondary'),
                    }"
                    @keyup="delete deal_fees_errors.tax_type_secondary"
                    :title="deal_fees_errors.tax_type_secondary"
                    v-model="deal_fees_form.tax_type_secondary"
                    :disabled="!enableDealFeeTaxEditing"
                    placeholder="Tax Type"
                    id="deal_fee_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                    >
                      {{ tax.name }}
                    </option>
                  </select>
                </div>
                <!-- secondary_tax_type : END -->

                <!-- tax_amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_fees_tax_amount">Tax Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid':
                        deal_fees_errors.hasOwnProperty('tax_amount_total'),
                    }"
                    @keyup="delete deal_fees_errors.tax_amount_total"
                    :title="deal_fees_errors.tax_amount_total"
                    v-model="deal_fees_form.tax_amount_total"
                    placeholder="Tax Amount"
                    :readonly="enableDealFeeTaxEditing"
                    class="form-control"
                    id="deal_fees_tax_amount"
                  />
                </div>
                <!-- tax_amount : END -->
              </div>
              <!-- combindation : END -->
              <div
                class="divider divider-dashed"
                type="button"
                data-toggle="collapse"
                data-target="#collapseDealFeeCost"
                aria-expanded="false"
                aria-controls="collapseDealFeeCost"
              >
                <div class="divider-text">Click To Add Cost</div>
              </div>
              <!-- COST : START -->
              <div class="form-row collapse" id="collapseDealFeeCost">
                <!-- cost : START -->
                <div class="form-group col-md-4">
                  <label for="fees_cost">Cost</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_fees_errors.hasOwnProperty('cost'),
                    }"
                    @keyup="delete deal_fees_errors.cost"
                    :title="deal_fees_errors.cost"
                    v-model="deal_fees_form.cost"
                    placeholder="Cost"
                    class="form-control"
                    id="fees_cost"
                  />
                </div>
                <!-- cost : END -->

                <!-- cost_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="fee_cost_tax_type">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid':
                        deal_fees_errors.hasOwnProperty('cost_tax_type'),
                    }"
                    @keyup="delete deal_fees_errors.cost_tax_type"
                    :title="deal_fees_errors.cost_tax_type"
                    v-model="deal_fees_form.cost_tax_type"
                    placeholder="Tax Type"
                    id="fee_cost_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.id"
                    >
                      {{ tax.name }}
                    </option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <!-- cost_tax_type : END -->

                <!-- cost_tax_type_secondary : START -->
                <div class="form-group col-md-4">
                  <label for="fee_cost_tax_type_secondary">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_fees_errors.hasOwnProperty(
                        'cost_tax_type_secondary'
                      ),
                    }"
                    @keyup="delete deal_fees_errors.cost_tax_type_secondary"
                    :title="deal_fees_errors.cost_tax_type_secondary"
                    v-model="deal_fees_form.cost_tax_type_secondary"
                    :disabled="!enableDealFeeCostTaxEditing"
                    placeholder="Tax Type"
                    id="fee_cost_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                    >
                      {{ tax.name }}
                    </option>
                  </select>
                </div>
                <!-- cost_tax_type_secondary : END -->

                <!-- cost_tax_amount_total : START -->
                <div class="form-group col-md-4">
                  <label for="fees_cost_tax_amount">Tax Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_fees_errors.hasOwnProperty(
                        'cost_tax_amount_total'
                      ),
                    }"
                    @keyup="delete deal_fees_errors.cost_tax_amount_total"
                    :title="deal_fees_errors.cost_tax_amount_total"
                    v-model="deal_fees_form.cost_tax_amount_total"
                    placeholder="Tax Amount"
                    :readonly="enableDealFeeCostTaxEditing"
                    class="form-control"
                    id="fees_cost_tax_amount"
                  />
                </div>
                <!-- cost_tax_amount_total : END -->
              </div>
              <!-- COST : END -->
            </form>
          </div>
          <!-- modal footer : START -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click.prevent="saveDealFee"
            >
              Save
            </button>
          </div>
          <!-- modal footer : END -->
        </div>
      </div>
    </div>
    <!-- Model Deal Fees : END -->

    <!-- Model Deal Accessories : START -->
    <div
      class="modal fade"
      id="dealAccessoryModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Deal Accessory Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ deal_accessories_modal_title }}
            </h5>
            <button
              type="button"
              id="dealAccessoryModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <!-- vselect : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="costs">Search Accessory : </label>
                  <v-select
                    taggable
                    placeholder="Search Accessory Name"
                    label="name"
                    :reduce="(cost) => cost.id"
                    v-model="selected_accessory_name"
                    clearable
                    :options="accessory_setting_listing"
                  ></v-select>
                </div>
              </div>
              <!-- vselect : END -->
              <!-- name : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_accessories_name">Name : </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid':
                        deal_accessories_errors.hasOwnProperty('name'),
                    }"
                    @keyup="delete deal_accessories_errors.name"
                    :title="deal_accessories_errors.name"
                    v-model="deal_accessories_form.name"
                    id="deal_accessories_name"
                    placeholder="Name"
                  />
                </div>
              </div>
              <!-- name : END -->
              <!-- description : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_accessories_description"
                    >Description :
                  </label>
                  <textarea
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid':
                        deal_accessories_errors.hasOwnProperty('description'),
                    }"
                    @keyup="delete deal_accessories_errors.description"
                    :title="deal_accessories_errors.description"
                    v-model="deal_accessories_form.description"
                    id="deal_accessories_description"
                    placeholder="Description"
                  ></textarea>
                </div>
              </div>
              <!-- description : END -->
              <!-- combination : START -->
              <div class="form-row">
                <!-- amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_accessories_amount">Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid':
                        deal_accessories_errors.hasOwnProperty('amount'),
                    }"
                    @keyup="delete deal_accessories_errors.amount"
                    :title="deal_accessories_errors.amount"
                    v-model="deal_accessories_form.amount"
                    placeholder="Amount"
                    class="form-control"
                    id="deal_accessories_amount"
                  />
                </div>
                <!-- amount : END -->

                <!-- tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_accessories_tax_type">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid':
                        deal_accessories_errors.hasOwnProperty('tax_type'),
                    }"
                    @keyup="delete deal_accessories_errors.tax_type"
                    :title="deal_accessories_errors.tax_type"
                    v-model="deal_accessories_form.tax_type"
                    placeholder="Tax Type"
                    id="deal_accessories_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.id"
                    >
                      {{ tax.name }}
                    </option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <!-- tax_type : END -->
                <!-- secondary_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_accessories_tax_type_secondary"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid':
                        deal_accessories_errors.hasOwnProperty(
                          'tax_type_secondary'
                        ),
                    }"
                    @keyup="delete deal_accessories_errors.tax_type_secondary"
                    :title="deal_accessories_errors.tax_type_secondary"
                    v-model="deal_accessories_form.tax_type_secondary"
                    :disabled="!enableDealAccessoryTaxEditing"
                    placeholder="Tax Type"
                    id="deal_accessories_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                    >
                      {{ tax.name }}
                    </option>
                  </select>
                </div>
                <!-- secondary_tax_type : END -->
                <!-- tax_amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_accessories_tax_amount">Tax Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid':
                        deal_accessories_errors.hasOwnProperty(
                          'tax_amount_total'
                        ),
                    }"
                    @keyup="delete deal_accessories_errors.tax_amount_total"
                    :title="deal_accessories_errors.tax_amount_total"
                    v-model="deal_accessories_form.tax_amount_total"
                    placeholder="Tax Amount"
                    :readonly="enableDealAccessoryTaxEditing"
                    class="form-control"
                    id="deal_accessories_tax_amount"
                  />
                </div>
                <!-- tax_amount : END -->
              </div>
              <!-- combindation : END -->
              <div
                class="divider divider-dashed"
                type="button"
                data-toggle="collapse"
                data-target="#collapseDealAccessoryCost"
                aria-expanded="false"
                aria-controls="collapseDealAccessoryCost"
              >
                <div class="divider-text">Click To Add Cost</div>
              </div>
              <!-- COST : START -->
              <div class="form-row collapse" id="collapseDealAccessoryCost">
                <!-- cost : START -->
                <div class="form-group col-md-4">
                  <label for="deal_accessories_cost">Cost</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid':
                        deal_accessories_errors.hasOwnProperty('cost'),
                    }"
                    @keyup="delete deal_accessories_errors.cost"
                    :title="deal_accessories_errors.cost"
                    v-model="deal_accessories_form.cost"
                    placeholder="Cost"
                    class="form-control"
                    id="deal_accessories_cost"
                  />
                </div>
                <!-- cost : END -->

                <!-- cost_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_accessories_cost_tax_type">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid':
                        deal_accessories_errors.hasOwnProperty('cost_tax_type'),
                    }"
                    @keyup="delete deal_accessories_errors.cost_tax_type"
                    :title="deal_accessories_errors.cost_tax_type"
                    v-model="deal_accessories_form.cost_tax_type"
                    placeholder="Tax Type"
                    id="deal_accessories_cost_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.id"
                    >
                      {{ tax.name }}
                    </option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <!-- cost_tax_type : END -->

                <!-- cost_tax_type_secondary : START -->
                <div class="form-group col-md-4">
                  <label for="deal_accessories_cost_tax_type_secondary"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_accessories_errors.hasOwnProperty(
                        'cost_tax_type_secondary'
                      ),
                    }"
                    @keyup="
                      delete deal_accessories_errors.cost_tax_type_secondary
                    "
                    :title="deal_accessories_errors.cost_tax_type_secondary"
                    v-model="deal_accessories_form.cost_tax_type_secondary"
                    :disabled="!enableDealAccessoryCostTaxEditing"
                    placeholder="Tax Type"
                    id="deal_accessories_cost_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                    >
                      {{ tax.name }}
                    </option>
                  </select>
                </div>
                <!-- cost_tax_type_secondary : END -->

                <!-- cost_tax_amount_total : START -->
                <div class="form-group col-md-4">
                  <label for="deal_accessories_cost_tax_amount"
                    >Tax Amount</label
                  >
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_accessories_errors.hasOwnProperty(
                        'cost_tax_amount_total'
                      ),
                    }"
                    @keyup="
                      delete deal_accessories_errors.cost_tax_amount_total
                    "
                    :title="deal_accessories_errors.cost_tax_amount_total"
                    v-model="deal_accessories_form.cost_tax_amount_total"
                    placeholder="Tax Amount"
                    :readonly="enableDealAccessoryCostTaxEditing"
                    class="form-control"
                    id="deal_accessories_cost_tax_amount"
                  />
                </div>
                <!-- cost_tax_amount_total : END -->
              </div>
              <!-- COST : END -->
            </form>
          </div>
          <!-- modal footer : START -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click.prevent="saveDealAccessory"
            >
              Save
            </button>
          </div>
          <!-- modal footer : END -->
        </div>
      </div>
    </div>
    <!-- Model Deal Accessories : END -->

    <!-- Model Warranty : START -->
    <div
      class="modal fade"
      id="dealWarrantyModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Deal Warranty Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ deal_warranties_modal_title }}
            </h5>
            <button
              type="button"
              id="dealWarrantyModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <!-- vselect : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="costs">Search Warranty : </label>
                  <v-select
                    taggable
                    placeholder="Search Warranty Name"
                    label="company_name"
                    :reduce="(cost) => cost.id"
                    v-model="selected_warranty_name"
                    clearable
                    :options="warranty_setting_listing"
                  ></v-select>
                </div>
              </div>
              <!-- vselect : END -->
              <!-- company_name : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_warranties_company_name"
                    >Company Name :
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid':
                        deal_warranties_errors.hasOwnProperty('company_name'),
                    }"
                    @keyup="delete deal_warranties_errors.company_name"
                    :title="deal_warranties_errors.company_name"
                    v-model="deal_warranties_form.company_name"
                    id="deal_warranties_company_name"
                    placeholder="Company Name"
                  />
                </div>
              </div>
              <!-- company_name : END -->
              <!-- description : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_warranties_description"
                    >Description :
                  </label>
                  <textarea
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid':
                        deal_warranties_errors.hasOwnProperty('description'),
                    }"
                    @keyup="delete deal_warranties_errors.description"
                    :title="deal_warranties_errors.description"
                    v-model="deal_warranties_form.description"
                    id="deal_warranties_description"
                    placeholder="Description"
                  ></textarea>
                </div>
              </div>
              <!-- description : END -->
              <!-- duration : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_warranties_duration"
                    >Duration (Months):
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid':
                        deal_warranties_errors.hasOwnProperty('duration'),
                    }"
                    @keyup="delete deal_warranties_errors.duration"
                    :title="deal_warranties_errors.duration"
                    v-model="deal_warranties_form.duration"
                    id="deal_warranties_duration"
                    placeholder="Duration"
                  />
                </div>
              </div>
              <!-- duration : END -->
              <!-- distance : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_warranties_distance">Distance : </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid':
                        deal_warranties_errors.hasOwnProperty('distance'),
                    }"
                    @keyup="delete deal_warranties_errors.distance"
                    :title="deal_warranties_errors.distance"
                    v-model="deal_warranties_form.distance"
                    id="deal_warranties_distance"
                    placeholder="Distance"
                  />
                </div>
              </div>
              <!-- distance : END -->
              <!-- combination : START -->
              <div class="form-row">
                <!-- amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_warranties_amount">Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid':
                        deal_warranties_errors.hasOwnProperty('amount'),
                    }"
                    @keyup="delete deal_warranties_errors.amount"
                    :title="deal_warranties_errors.amount"
                    v-model="deal_warranties_form.amount"
                    placeholder="Amount"
                    class="form-control"
                    id="deal_warranties_amount"
                  />
                </div>
                <!-- amount : END -->

                <!-- tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_warranty_tax_type">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid':
                        deal_warranties_errors.hasOwnProperty('tax_type'),
                    }"
                    @keyup="delete deal_warranties_errors.tax_type"
                    :title="deal_warranties_errors.tax_type"
                    v-model="deal_warranties_form.tax_type"
                    placeholder="Tax Type"
                    id="deal_warranty_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.id"
                    >
                      {{ tax.name }}
                    </option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <!-- tax_type : END -->
                <!-- secondary_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_warranty_type_secondary">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid':
                        deal_warranties_errors.hasOwnProperty(
                          'tax_type_secondary'
                        ),
                    }"
                    @keyup="delete deal_warranties_errors.tax_type_secondary"
                    :title="deal_warranties_errors.tax_type_secondary"
                    v-model="deal_warranties_form.tax_type_secondary"
                    :disabled="!enableDealWarrantyTaxEditing"
                    placeholder="Tax Type"
                    id="deal_warranty_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                    >
                      {{ tax.name }}
                    </option>
                  </select>
                </div>
                <!-- secondary_tax_type : END -->
                <!-- tax_amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_warranties_tax_amount">Tax Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid':
                        deal_warranties_errors.hasOwnProperty(
                          'tax_amount_total'
                        ),
                    }"
                    @keyup="delete deal_warranties_errors.tax_amount_total"
                    :title="deal_warranties_errors.tax_amount_total"
                    v-model="deal_warranties_form.tax_amount_total"
                    placeholder="Tax Amount"
                    :readonly="enableDealWarrantyTaxEditing"
                    class="form-control"
                    id="deal_warranties_tax_amount"
                  />
                </div>
                <!-- tax_amount : END -->
              </div>
              <!-- combindation : END -->
              <div
                class="divider divider-dashed"
                type="button"
                data-toggle="collapse"
                data-target="#collapseDealWarrantyCost"
                aria-expanded="false"
                aria-controls="collapseDealWarrantyCost"
              >
                <div class="divider-text">Click To Add Cost</div>
              </div>
              <!-- COST : START -->
              <div class="form-row collapse" id="collapseDealWarrantyCost">
                <!-- cost : START -->
                <div class="form-group col-md-4">
                  <label for="warranty_cost">Cost</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid':
                        deal_warranties_errors.hasOwnProperty('cost'),
                    }"
                    @keyup="delete deal_warranties_errors.cost"
                    :title="deal_warranties_errors.cost"
                    v-model="deal_warranties_form.cost"
                    placeholder="Cost"
                    class="form-control"
                    id="warranty_cost"
                  />
                </div>
                <!-- cost : END -->

                <!-- cost_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="warranty_cost_tax_type">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid':
                        deal_warranties_errors.hasOwnProperty('cost_tax_type'),
                    }"
                    @keyup="delete deal_warranties_errors.cost_tax_type"
                    :title="deal_warranties_errors.cost_tax_type"
                    v-model="deal_warranties_form.cost_tax_type"
                    placeholder="Tax Type"
                    id="warranty_cost_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.id"
                    >
                      {{ tax.name }}
                    </option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <!-- cost_tax_type : END -->

                <!-- cost_tax_type_secondary : START -->
                <div class="form-group col-md-4">
                  <label for="warranty_cost_tax_type_secondary">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_warranties_errors.hasOwnProperty(
                        'cost_tax_type_secondary'
                      ),
                    }"
                    @keyup="
                      delete deal_warranties_errors.cost_tax_type_secondary
                    "
                    :title="deal_warranties_errors.cost_tax_type_secondary"
                    v-model="deal_warranties_form.cost_tax_type_secondary"
                    :disabled="!enableDealWarrantyCostTaxEditing"
                    placeholder="Tax Type"
                    id="warranty_cost_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                    >
                      {{ tax.name }}
                    </option>
                  </select>
                </div>
                <!-- cost_tax_type_secondary : END -->

                <!-- cost_tax_amount_total : START -->
                <div class="form-group col-md-4">
                  <label for="warranty_cost_tax_amount">Tax Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_warranties_errors.hasOwnProperty(
                        'cost_tax_amount_total'
                      ),
                    }"
                    @keyup="delete deal_warranties_errors.cost_tax_amount_total"
                    :title="deal_warranties_errors.cost_tax_amount_total"
                    v-model="deal_warranties_form.cost_tax_amount_total"
                    placeholder="Tax Amount"
                    :readonly="enableDealWarrantyCostTaxEditing"
                    class="form-control"
                    id="warranty_cost_tax_amount"
                  />
                </div>
                <!-- cost_tax_amount_total : END -->
              </div>
              <!-- COST : END -->
            </form>
          </div>
          <!-- modal footer : START -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click.prevent="saveDealWarranty"
            >
              Save
            </button>
          </div>
          <!-- modal footer : END -->
        </div>
      </div>
    </div>
    <!-- Model Warranty : END -->

    <!-- Model Guarantee : START -->
    <div
      class="modal fade"
      id="dealGuaranteeModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Deal Guarantee Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ deal_guarantees_modal_title }}
            </h5>
            <button
              type="button"
              id="dealGuaranteeModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <!-- vselect : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="costs">Search Guarantee : </label>
                  <v-select
                    taggable
                    placeholder="Search Guarantee Name"
                    label="company_name"
                    :reduce="(cost) => cost.id"
                    v-model="selected_guarantee_name"
                    clearable
                    :options="guarantee_setting_listing"
                  ></v-select>
                </div>
              </div>
              <!-- vselect : END -->
              <!-- company_name : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_guarantees_company_name"
                    >Company Name :
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid':
                        deal_guarantees_errors.hasOwnProperty('company_name'),
                    }"
                    @keyup="delete deal_guarantees_errors.company_name"
                    :title="deal_guarantees_errors.company_name"
                    v-model="deal_guarantees_form.company_name"
                    id="deal_guarantees_company_name"
                    placeholder="Company Name"
                  />
                </div>
              </div>
              <!-- company_name : END -->
              <!-- description : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_guarantees_description"
                    >Description :
                  </label>
                  <textarea
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid':
                        deal_guarantees_errors.hasOwnProperty('description'),
                    }"
                    @keyup="delete deal_guarantees_errors.description"
                    :title="deal_guarantees_errors.description"
                    v-model="deal_guarantees_form.description"
                    id="deal_guarantees_description"
                    placeholder="Description"
                  ></textarea>
                </div>
              </div>
              <!-- description : END -->
              <!-- duration : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_guarantees_duration"
                    >Duration (Days):
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid':
                        deal_guarantees_errors.hasOwnProperty('duration'),
                    }"
                    @keyup="delete deal_guarantees_errors.duration"
                    :title="deal_guarantees_errors.duration"
                    v-model="deal_guarantees_form.duration"
                    id="deal_guarantees_duration"
                    placeholder="Duration"
                  />
                </div>
              </div>
              <!-- duration : END -->
              <!-- distance : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_guarantees_distance">Distance : </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid':
                        deal_guarantees_errors.hasOwnProperty('distance'),
                    }"
                    @keyup="delete deal_guarantees_errors.distance"
                    :title="deal_guarantees_errors.distance"
                    v-model="deal_guarantees_form.distance"
                    id="deal_guarantees_distance"
                    placeholder="Distance"
                  />
                </div>
              </div>
              <!-- distance : END -->
              <!-- combination : START -->
              <div class="form-row">
                <!-- amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_guarantees_amount">Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid':
                        deal_guarantees_errors.hasOwnProperty('amount'),
                    }"
                    @keyup="delete deal_guarantees_errors.amount"
                    :title="deal_guarantees_errors.amount"
                    v-model="deal_guarantees_form.amount"
                    placeholder="Amount"
                    readonly
                    class="form-control"
                    id="deal_guarantees_amount"
                  />
                </div>
                <!-- amount : END -->

                <!-- tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_guaranty_tax_type">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid':
                        deal_guarantees_errors.hasOwnProperty('tax_type'),
                    }"
                    @keyup="delete deal_guarantees_errors.tax_type"
                    :title="deal_guarantees_errors.tax_type"
                    v-model="deal_guarantees_form.tax_type"
                    readonly
                    placeholder="Tax Type"
                    id="deal_guaranty_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.id"
                    >
                      {{ tax.name }}
                    </option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <!-- tax_type : END -->
                <!-- secondary_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_guarantee_tax_type_secondary"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid':
                        deal_guarantees_errors.hasOwnProperty(
                          'tax_type_secondary'
                        ),
                    }"
                    @keyup="delete deal_guarantees_errors.tax_type_secondary"
                    :title="deal_guarantees_errors.tax_type_secondary"
                    v-model="deal_guarantees_form.tax_type_secondary"
                    readonly
                    placeholder="Tax Type"
                    id="deal_guarantee_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                    >
                      {{ tax.name }}
                    </option>
                  </select>
                </div>
                <!-- secondary_tax_type : END -->
                <!-- tax_amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_guarantees_tax_amount">Tax Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid':
                        deal_guarantees_errors.hasOwnProperty(
                          'tax_amount_total'
                        ),
                    }"
                    @keyup="delete deal_guarantees_errors.tax_amount_total"
                    :title="deal_guarantees_errors.tax_amount_total"
                    v-model="deal_guarantees_form.tax_amount_total"
                    placeholder="Tax Amount"
                    readonly
                    class="form-control"
                    id="deal_guarantees_tax_amount"
                  />
                </div>
                <!-- tax_amount : END -->
              </div>
              <!-- combindation : END -->
              <div
                class="divider divider-dashed"
                type="button"
                data-toggle="collapse"
                data-target="#collapseDealGuaranteeCost"
                aria-expanded="false"
                aria-controls="collapseDealGuaranteeCost"
              >
                <div class="divider-text">Click To Add Cost</div>
              </div>
              <!-- COST : START -->
              <div class="form-row collapse" id="collapseDealGuaranteeCost">
                <!-- cost : START -->
                <div class="form-group col-md-4">
                  <label for="deal_guarantees_cost">Cost</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid':
                        deal_guarantees_errors.hasOwnProperty('cost'),
                    }"
                    @keyup="delete deal_guarantees_errors.cost"
                    :title="deal_guarantees_errors.cost"
                    v-model="deal_guarantees_form.cost"
                    placeholder="Cost"
                    class="form-control"
                    id="deal_guarantees_cost"
                  />
                </div>
                <!-- cost : END -->

                <!-- cost_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_guarantees_cost_tax_type">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid':
                        deal_guarantees_errors.hasOwnProperty('cost_tax_type'),
                    }"
                    @keyup="delete deal_guarantees_errors.cost_tax_type"
                    :title="deal_guarantees_errors.cost_tax_type"
                    v-model="deal_guarantees_form.cost_tax_type"
                    placeholder="Tax Type"
                    id="deal_guarantees_cost_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.id"
                    >
                      {{ tax.name }}
                    </option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <!-- cost_tax_type : END -->

                <!-- cost_tax_type_secondary : START -->
                <div class="form-group col-md-4">
                  <label for="deal_guarantees_cost_tax_type_secondary"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_guarantees_errors.hasOwnProperty(
                        'cost_tax_type_secondary'
                      ),
                    }"
                    @keyup="
                      delete deal_guarantees_errors.cost_tax_type_secondary
                    "
                    :title="deal_guarantees_errors.cost_tax_type_secondary"
                    v-model="deal_guarantees_form.cost_tax_type_secondary"
                    :disabled="!enableDealGuaranteeCostTaxEditing"
                    placeholder="Tax Type"
                    id="deal_guarantees_cost_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                    >
                      {{ tax.name }}
                    </option>
                  </select>
                </div>
                <!-- cost_tax_type_secondary : END -->

                <!-- cost_tax_amount_total : START -->
                <div class="form-group col-md-4">
                  <label for="deal_guarantees_cost_tax_amount"
                    >Tax Amount</label
                  >
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_guarantees_errors.hasOwnProperty(
                        'cost_tax_amount_total'
                      ),
                    }"
                    @keyup="delete deal_guarantees_errors.cost_tax_amount_total"
                    :title="deal_guarantees_errors.cost_tax_amount_total"
                    v-model="deal_guarantees_form.cost_tax_amount_total"
                    placeholder="Tax Amount"
                    :readonly="enableDealGuaranteeCostTaxEditing"
                    class="form-control"
                    id="deal_guarantees_cost_tax_amount"
                  />
                </div>
                <!-- cost_tax_amount_total : END -->
              </div>
              <!-- COST : END -->
            </form>
          </div>
          <!-- modal footer : START -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click.prevent="saveDealGuarantee"
            >
              Save
            </button>
          </div>
          <!-- modal footer : END -->
        </div>
      </div>
    </div>
    <!-- Model Guarantee : END -->

    <!-- Model Payment : START -->
    <div
      class="modal fade"
      id="dealPaymentModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Deal Payment Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ deal_payments_modal_title }}
            </h5>
            <button
              type="button"
              id="dealPaymentModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <!-- vselect : START -->
              <!-- vselect : END -->
              <!-- type : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_payments_type">Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_payments_errors.hasOwnProperty('type'),
                    }"
                    @keyup="delete deal_payments_errors.type"
                    :title="deal_payments_errors.type"
                    v-model="deal_payments_form.type"
                    placeholder="Type"
                    id="deal_payments_type"
                  >
                    <option value="" selected disabled>Type</option>
                    <option value="Cash">Cash</option>
                    <option value="Debit">Debit</option>
                    <option value="Credit">Credit</option>
                    <option value="Cheque">Cheque</option>
                    <option value="EFT">EFT</option>
                  </select>
                </div>
              </div>
              <!-- type : END -->
              <!-- description : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_payments_description">Description : </label>
                  <textarea
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid':
                        deal_payments_errors.hasOwnProperty('description'),
                    }"
                    @keyup="delete deal_payments_errors.description"
                    :title="deal_payments_errors.description"
                    v-model="deal_payments_form.description"
                    id="deal_payments_description"
                    placeholder="Description"
                  ></textarea>
                </div>
              </div>
              <!-- description : END -->

              <!-- downpayment or deposit : START -->
              <div class="form-row">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <label
                      for=""
                      style="inline-block;vertical-align:super;"
                      class="mr-1"
                      >Is Downpayment or Deposit ?
                    </label>
                    <div class="form-check form-check-inline">
                      <input
                        class="form-check-input"
                        v-model="deal_payments_form.is_downpayment_or_deposit"
                        type="radio"
                        id="downpayment_payment"
                        value="0"
                      />
                      <label class="form-check-label" for="downpayment_payment"
                        >Downpayment</label
                      >
                    </div>
                    <div class="form-check form-check-inline">
                      <input
                        class="form-check-input"
                        v-model="deal_payments_form.is_downpayment_or_deposit"
                        type="radio"
                        id="deposit_payment"
                        value="1"
                      />
                      <label class="form-check-label" for="deposit_payment"
                        >Deposit</label
                      >
                    </div>
                  </div>
                </div>
              </div>
              <!-- downpayment or deposit : END -->
              <!-- combination : START -->
              <div class="form-row">
                <!-- amount : START -->
                <div class="form-group col-md-12">
                  <label for="deal_payments_amount">Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid':
                        deal_payments_errors.hasOwnProperty('amount'),
                    }"
                    @keyup="delete deal_payments_errors.amount"
                    :title="deal_payments_errors.amount"
                    v-model="deal_payments_form.amount"
                    placeholder="Amount"
                    class="form-control"
                    id="deal_payments_amount"
                  />
                </div>
                <!-- amount : END -->
              </div>
              <!-- combindation : END -->
            </form>
          </div>
          <!-- modal footer : START -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click.prevent="saveDealPayment"
            >
              Save
            </button>
          </div>
          <!-- modal footer : END -->
        </div>
      </div>
    </div>
    <!-- Model Payment : END -->

    <!-- Model Insurance : START -->
    <div
      class="modal fade"
      id="dealInsuranceModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Deal Insurance Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ deal_insurances_modal_title }}
            </h5>
            <button
              type="button"
              id="dealInsuranceModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <!-- vselect : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="costs">Search Insurance : </label>
                  <v-select
                    taggable
                    placeholder="Search Insurance"
                    label="label"
                    :reduce="(cost) => cost.id"
                    v-model="selected_insurance_name"
                    clearable
                    :options="insurance_setting_listing"
                  ></v-select>
                </div>
              </div>
              <!-- vselect : END -->
              <!-- type : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_insurances_type">Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid':
                        deal_insurances_errors.hasOwnProperty('type'),
                    }"
                    @keyup="delete deal_insurances_errors.type"
                    :title="deal_insurances_errors.type"
                    v-model="deal_insurances_form.type"
                    placeholder="Type"
                    id="deal_insurances_type"
                  >
                    <option value="" selected disabled>Type</option>
                    <option value="Life">Life</option>
                    <option value="Disability">Disability</option>
                    <option value="GAP">GAP</option>
                    <option value="Income">Income</option>
                    <option value="Walkaway">Walkaway</option>
                    <option value="Sickness">Sickness</option>
                    <option value="Injury">Injury</option>
                    <option value="Other">Other</option>
                  </select>
                </div>
              </div>
              <!-- type : END -->
              <!-- description : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_insurances_description"
                    >Description :
                  </label>
                  <textarea
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid':
                        deal_insurances_errors.hasOwnProperty('description'),
                    }"
                    @keyup="delete deal_insurances_errors.description"
                    :title="deal_insurances_errors.description"
                    v-model="deal_insurances_form.description"
                    id="deal_insurances_description"
                    placeholder="Description"
                  ></textarea>
                </div>
              </div>
              <!-- description : END -->
              <!-- duration : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_insurances_duration">Duration : </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid':
                        deal_insurances_errors.hasOwnProperty('duration'),
                    }"
                    @keyup="delete deal_insurances_errors.duration"
                    :title="deal_insurances_errors.duration"
                    v-model="deal_insurances_form.duration"
                    id="deal_insurances_duration"
                    placeholder="Duration"
                  />
                </div>
              </div>
              <!-- duration : END -->
              <!-- deductible : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="deal_insurances_deductible">Deductible : </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid':
                        deal_insurances_errors.hasOwnProperty('deductible'),
                    }"
                    @keyup="delete deal_insurances_errors.deductible"
                    :title="deal_insurances_errors.deductible"
                    v-model="deal_insurances_form.deductible"
                    id="deal_insurances_deductible"
                    placeholder="Deductible"
                  />
                </div>
              </div>
              <!-- deductible : END -->

              <!-- combination : START -->
              <div class="form-row">
                <!-- amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_insurances_amount">Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid':
                        deal_insurances_errors.hasOwnProperty('amount'),
                    }"
                    @keyup="delete deal_insurances_errors.amount"
                    :title="deal_insurances_errors.amount"
                    v-model="deal_insurances_form.amount"
                    placeholder="Amount"
                    class="form-control"
                    id="deal_insurances_amount"
                  />
                </div>
                <!-- amount : END -->

                <!-- tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_fee_tax_type">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid':
                        deal_insurances_errors.hasOwnProperty('tax_type'),
                    }"
                    @keyup="delete deal_insurances_errors.tax_type"
                    :title="deal_insurances_errors.tax_type"
                    v-model="deal_insurances_form.tax_type"
                    placeholder="Tax Type"
                    id="deal_fee_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.id"
                    >
                      {{ tax.name }}
                    </option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <!-- tax_type : END -->
                <!-- secondary_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_insurance_tax_type_secondary"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid':
                        deal_insurances_errors.hasOwnProperty(
                          'tax_type_secondary'
                        ),
                    }"
                    @keyup="delete deal_insurances_errors.tax_type_secondary"
                    :title="deal_insurances_errors.tax_type_secondary"
                    v-model="deal_insurances_form.tax_type_secondary"
                    :disabled="!enableDealInsuranceTaxEditing"
                    placeholder="Tax Type"
                    id="deal_insurance_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                    >
                      {{ tax.name }}
                    </option>
                  </select>
                </div>
                <!-- secondary_tax_type : END -->
                <!-- tax_amount : START -->
                <div class="form-group col-md-4">
                  <label for="deal_insurances_tax_amount">Tax Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid':
                        deal_insurances_errors.hasOwnProperty(
                          'tax_amount_total'
                        ),
                    }"
                    @keyup="delete deal_insurances_errors.tax_amount_total"
                    :title="deal_insurances_errors.tax_amount_total"
                    v-model="deal_insurances_form.tax_amount_total"
                    placeholder="Tax Amount"
                    :readonly="enableDealInsuranceTaxEditing"
                    class="form-control"
                    id="deal_insurances_tax_amount"
                  />
                </div>
                <!-- tax_amount : END -->
              </div>
              <!-- combindation : END -->
              <div
                class="divider divider-dashed"
                type="button"
                data-toggle="collapse"
                data-target="#collapseDealInsuranceCost"
                aria-expanded="false"
                aria-controls="collapseDealInsuranceCost"
              >
                <div class="divider-text">Click To Add Cost</div>
              </div>
              <!-- COST : START -->
              <div class="form-row collapse" id="collapseDealInsuranceCost">
                <!-- cost : START -->
                <div class="form-group col-md-4">
                  <label for="deal_insurance_cost">Cost</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid':
                        deal_insurances_errors.hasOwnProperty('cost'),
                    }"
                    @keyup="delete deal_insurances_errors.cost"
                    :title="deal_insurances_errors.cost"
                    v-model="deal_insurances_form.cost"
                    placeholder="Cost"
                    class="form-control"
                    id="deal_insurance_cost"
                  />
                </div>
                <!-- cost : END -->

                <!-- cost_tax_type : START -->
                <div class="form-group col-md-4">
                  <label for="deal_insurance_cost_tax_type">Tax Type</label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid':
                        deal_insurances_errors.hasOwnProperty('cost_tax_type'),
                    }"
                    @keyup="delete deal_insurances_errors.cost_tax_type"
                    :title="deal_insurances_errors.cost_tax_type"
                    v-model="deal_insurances_form.cost_tax_type"
                    placeholder="Tax Type"
                    id="deal_insurance_cost_tax_type"
                  >
                    <option value="" selected disabled>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.id"
                    >
                      {{ tax.name }}
                    </option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <!-- cost_tax_type : END -->

                <!-- cost_tax_type_secondary : START -->
                <div class="form-group col-md-4">
                  <label for="deal_insurance_cost_tax_type_secondary"
                    >Tax Type</label
                  >
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': deal_insurances_errors.hasOwnProperty(
                        'cost_tax_type_secondary'
                      ),
                    }"
                    @keyup="
                      delete deal_insurances_errors.cost_tax_type_secondary
                    "
                    :title="deal_insurances_errors.cost_tax_type_secondary"
                    v-model="deal_insurances_form.cost_tax_type_secondary"
                    :disabled="!enableDealInsuranceCostTaxEditing"
                    placeholder="Tax Type"
                    id="deal_insurance_cost_tax_type_secondary"
                  >
                    <option value="" selected>Tax Type</option>
                    <option
                      :value="tax.name"
                      v-for="tax in businessTaxSettings"
                      :key="tax.name"
                    >
                      {{ tax.name }}
                    </option>
                  </select>
                </div>
                <!-- cost_tax_type_secondary : END -->

                <!-- cost_tax_amount_total : START -->
                <div class="form-group col-md-4">
                  <label for="deal_insurance_cost_tax_amount">Tax Amount</label>
                  <input
                    type="text"
                    :class="{
                      'is-invalid': deal_insurances_errors.hasOwnProperty(
                        'cost_tax_amount_total'
                      ),
                    }"
                    @keyup="delete deal_insurances_errors.cost_tax_amount_total"
                    :title="deal_insurances_errors.cost_tax_amount_total"
                    v-model="deal_insurances_form.cost_tax_amount_total"
                    placeholder="Tax Amount"
                    :readonly="enableDealInsuranceCostTaxEditing"
                    class="form-control"
                    id="deal_insurance_cost_tax_amount"
                  />
                </div>
                <!-- cost_tax_amount_total : END -->
              </div>
              <!-- COST : END -->
            </form>
          </div>
          <!-- modal footer : START -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click.prevent="saveDealInsurance"
            >
              Save
            </button>
          </div>
          <!-- modal footer : END -->
        </div>
      </div>
    </div>
    <!-- Model Insurance : END -->
    <button
      class="btn btn-primary p-0 up-action"
      onclick='document.getElementById("deal-worksheet-panel").scrollIntoView({behavior: "smooth"})'
    >
      <i class="bx-md bx bx-chevron-up"></i>
    </button>
  </div>
</template>
<script>
import Toast from "../../../library/Toast";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import { mapGetters, mapActions } from "vuex";
import vSelect from "vue-select";

export default {
  name: "DealWorksheet",
  components: {
    DatePicker,
    "v-select": vSelect,
  },
  props: {
    id: { required: true },
    allUsers: { required: true },
    deal_type: { required: true },
    retail_or_wholesale_flag: { required: true },
    lien_value: { required: true },
    trade_equity: { required: true },
    trade_value: { required: true },
    purchase_price: { required: true },
    app_metadata: { required: true },
  },
  watch: {
    retail_or_wholesale_flag: function (newVal, oldVal) {
      this.roll_a_deal.adjust_column = "";
    },
    deal_type: function (newVal, oldVal) {
      this.roll_a_deal.adjust_column = "";
    },
    selected_cost_name: function (newVal, oldVal) {
      if (newVal != oldVal) {
        if (!isNaN(newVal) && newVal != "null") {
          this.getCostSetting(newVal);
        }
      }
    },
    selected_accessory_name: function (newVal, oldVal) {
      if (newVal != oldVal) {
        if (!isNaN(newVal) && newVal != "null") {
          this.getAccessorySetting(newVal);
        }
      }
    },
    selected_warranty_name: function (newVal, oldVal) {
      if (newVal != oldVal) {
        if (!isNaN(newVal) && newVal != "null") {
          this.getWarrantySetting(newVal);
        }
      }
    },
    selected_guarantee_name: function (newVal, oldVal) {
      if (newVal != oldVal) {
        if (!isNaN(newVal) && newVal != "null") {
          this.getGuaranteeSetting(newVal);
        }
      }
    },
    selected_insurance_name: function (newVal, oldVal) {
      if (newVal != oldVal) {
        if (!isNaN(newVal) && newVal != "null") {
          this.getInsuranceSetting(newVal);
        }
      }
    },
    purchase_price: function (newVal, oldVal) {
      if (parseInt(newVal) != parseInt(oldVal)) {
        this.deal_dealbreakdown_form.purchase_price = newVal;
      }
    },
    lien_value: function (newVal, oldVal) {
      if (parseFloat(newVal) != parseFloat(oldVal)) {
        this.deal_dealbreakdown_form.lien_payout = newVal;
      }
    },
    trade_equity: function (newVal, oldVal) {
      if (parseFloat(newVal) != parseFloat(oldVal)) {
        this.deal_dealbreakdown_form.trade_equity = newVal;
      }
    },
    gasoline: function (newVal, oldVal) {
      if (parseInt(newVal) != parseInt(oldVal)) {
        this.deal_dealbreakdown_form.gasoline = newVal;
      }
    },
    lien_registration_fee: function (newVal, oldVal) {
      if (parseInt(newVal) != parseInt(oldVal)) {
        this.deal_dealbreakdown_form.lien_registration_fee = newVal;
      }
    },
    trade_value: function (newVal, oldVal) {
      if (parseFloat(newVal) != parseFloat(oldVal)) {
        this.deal_dealbreakdown_form.trade_value = newVal;
      }
    },
    businessTaxSettings: function (newVal, oldVal) {
      if (!this.show_page) {
        let selected_tax = this.businessTaxSettings.filter(
          (tax) => tax.default == "1"
        );

        console.log(selected_tax);
        if (selected_tax.length > 0) {
          this.deal_fees_form.tax_type = selected_tax[0].name;
          this.deal_fees_form.cost_tax_type = selected_tax[0].name;

          this.deal_accessories_form.tax_type = selected_tax[0].name;
          this.deal_accessories_form.cost_tax_type = selected_tax[0].name;

          this.deal_warranties_form.tax_type = selected_tax[0].name;
          this.deal_warranties_form.cost_tax_type = selected_tax[0].name;

          this.deal_guarantees_form.tax_type = selected_tax[0].name;
          this.deal_guarantees_form.cost_tax_type = selected_tax[0].name;

          this.deal_insurances_form.tax_type = selected_tax[0].name;
          this.deal_insurances_form.cost_tax_type = selected_tax[0].name;

          if (!this.is_deal_breakdown_loaded) {
            this.deal_dealbreakdown_form.purchase_tax_type =
              selected_tax[0].name;
          }

          if (selected_tax.length > 1) {
            this.deal_fees_form.tax_type_secondary = selected_tax[1].name;
            this.deal_fees_form.cost_tax_type = selected_tax[1].name;

            this.deal_accessories_form.tax_type_secondary =
              selected_tax[1].name;
            this.deal_accessories_form.cost_tax_type = selected_tax[1].name;

            this.deal_warranties_form.tax_type_secondary = selected_tax[1].name;
            this.deal_warranties_form.cost_tax_type = selected_tax[1].name;

            this.deal_guarantees_form.tax_type_secondary = selected_tax[1].name;
            this.deal_guarantees_form.cost_tax_type = selected_tax[1].name;

            this.deal_insurances_form.tax_type_secondary = selected_tax[1].name;
            this.deal_insurances_form.cost_tax_type = selected_tax[1].name;

            if (!this.is_deal_breakdown_loaded) {
              this.deal_dealbreakdown_form.purchase_tax_type_secondary =
                selected_tax[1].name;
            }
          }
        }
      }
    },
    calculateDealFeeTax: function (newVal, oldVal) {
      console.log("");
    },
    calculateDealAccessoryTax: function (newVal, oldVal) {
      console.log("");
    },
    calculateDealWarrantyTax: function (newVal, oldVal) {
      console.log("");
    },
    calculateDealGuaranteeTax: function () {
      console.log("");
    },
    calculateDealInsuranceTax: function (newVal, oldVal) {
      console.log("");
    },
    calculateDealBreakDown: function (newVal, oldVal) {
      console.log("");
    },
    "deal_fees_form.tax_type": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_fees_form.tax_percentage = "";
        } else {
          this.deal_fees_form.tax_percentage = selected_tax[0].value;
        }
      } else {
        this.deal_fees_form.tax_percentage = "";
      }
    },
    "deal_fees_form.tax_type_secondary": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_fees_form.tax_percentage_secondary = 0;
        } else {
          this.deal_fees_form.tax_percentage_secondary = selected_tax[0].value;
        }
      } else {
        this.deal_fees_form.tax_percentage_secondary = 0;
      }
    },
    "deal_fees_form.cost_tax_type": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_fees_form.cost_tax_percentage = "";
        } else {
          this.deal_fees_form.cost_tax_percentage = selected_tax[0].value;
        }
      } else {
        this.deal_fees_form.cost_tax_percentage = "";
      }
    },
    "deal_fees_form.cost_tax_type_secondary": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_fees_form.cost_tax_percentage_secondary = "";
        } else {
          this.deal_fees_form.cost_tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_fees_form.cost_tax_percentage_secondary = "";
      }
    },
    "deal_accessories_form.tax_type": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_accessories_form.tax_percentage = "";
        } else {
          this.deal_accessories_form.tax_percentage = selected_tax[0].value;
        }
      } else {
        this.deal_accessories_form.tax_percentage = "";
      }
    },
    "deal_accessories_form.tax_type_secondary": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_accessories_form.tax_percentage_secondary = 0;
        } else {
          this.deal_accessories_form.tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_accessories_form.tax_percentage_secondary = 0;
      }
    },
    "deal_accessories_form.cost_tax_type": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_accessories_form.cost_tax_percentage = "";
        } else {
          this.deal_accessories_form.cost_tax_percentage =
            selected_tax[0].value;
        }
      } else {
        this.deal_accessories_form.cost_tax_percentage = "";
      }
    },
    "deal_accessories_form.cost_tax_type_secondary": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_accessories_form.cost_tax_percentage_secondary = "";
        } else {
          this.deal_accessories_form.cost_tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_accessories_form.cost_tax_percentage_secondary = "";
      }
    },
    "deal_insurances_form.tax_type": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_insurances_form.tax_percentage = "";
        } else {
          this.deal_insurances_form.tax_percentage = selected_tax[0].value;
        }
      } else {
        this.deal_insurances_form.tax_percentage = "";
      }
    },
    "deal_insurances_form.tax_type_secondary": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_insurances_form.tax_percentage_secondary = 0;
        } else {
          this.deal_insurances_form.tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_insurances_form.tax_percentage_secondary = 0;
      }
    },
    "deal_insurances_form.cost_tax_type": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_insurances_form.cost_tax_percentage = "";
        } else {
          this.deal_insurances_form.cost_tax_percentage = selected_tax[0].value;
        }
      } else {
        this.deal_insurances_form.cost_tax_percentage = "";
      }
    },
    "deal_insurances_form.cost_tax_type_secondary": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_insurances_form.cost_tax_percentage_secondary = "";
        } else {
          this.deal_insurances_form.cost_tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_insurances_form.cost_tax_percentage_secondary = "";
      }
    },
    "deal_warranties_form.duration": function (newVal, oldVal) {
      let duration =
        !isNaN(this.deal_warranties_form.duration) &&
        this.deal_warranties_form.duration != ""
          ? parseFloat(this.deal_warranties_form.duration)
          : 0;
      this.deal_warranties_form.duration = duration;
    },
    "deal_insurances_form.duration": function (newVal, oldVal) {
      let duration =
        !isNaN(this.deal_insurances_form.duration) &&
        this.deal_insurances_form.duration != ""
          ? parseFloat(this.deal_insurances_form.duration)
          : 0;
      this.deal_insurances_form.duration = duration;
    },
    "deal_insurances_form.deductible": function (newVal, oldVal) {
      let deductible =
        !isNaN(this.deal_insurances_form.deductible) &&
        this.deal_insurances_form.deductible != ""
          ? parseFloat(this.deal_insurances_form.deductible)
          : 0;
      this.deal_insurances_form.deductible = deductible;
    },
    "deal_warranties_form.tax_type": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_warranties_form.tax_percentage = "";
        } else {
          this.deal_warranties_form.tax_percentage = selected_tax[0].value;
        }
      } else {
        this.deal_warranties_form.tax_percentage = "";
      }
    },
    "deal_warranties_form.tax_type_secondary": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_warranties_form.tax_percentage_secondary = 0;
        } else {
          this.deal_warranties_form.tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_warranties_form.tax_percentage_secondary = 0;
      }
    },
    "deal_warranties_form.cost_tax_type": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_warranties_form.cost_tax_percentage = "";
        } else {
          this.deal_warranties_form.cost_tax_percentage = selected_tax[0].value;
        }
      } else {
        this.deal_warranties_form.cost_tax_percentage = "";
      }
    },
    "deal_warranties_form.cost_tax_type_secondary": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_warranties_form.cost_tax_percentage_secondary = "";
        } else {
          this.deal_warranties_form.cost_tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_warranties_form.cost_tax_percentage_secondary = "";
      }
    },
    "deal_guarantees_form.tax_type": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_guarantees_form.tax_percentage = "";
        } else {
          this.deal_guarantees_form.tax_percentage = selected_tax[0].value;
        }
      } else {
        this.deal_guarantees_form.tax_percentage = "";
      }
    },
    "deal_guarantees_form.tax_type_secondary": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_guarantees_form.tax_percentage_secondary = 0;
        } else {
          this.deal_guarantees_form.tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_guarantees_form.tax_percentage_secondary = 0;
      }
    },
    "deal_guarantees_form.cost_tax_type": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_guarantees_form.cost_tax_percentage = "";
        } else {
          this.deal_guarantees_form.cost_tax_percentage = selected_tax[0].value;
        }
      } else {
        this.deal_guarantees_form.cost_tax_percentage = "";
      }
    },
    "deal_guarantees_form.cost_tax_type_secondary": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_guarantees_form.cost_tax_percentage_secondary = "";
        } else {
          this.deal_guarantees_form.cost_tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else {
        this.deal_guarantees_form.cost_tax_percentage_secondary = "";
      }
    },
    "deal_payments_form.amount": function (newVal, oldVal) {
      let amount =
        !isNaN(this.deal_payments_form.amount) &&
        this.deal_payments_form.amount != ""
          ? this.deal_payments_form.amount
          : 0;
      this.deal_payments_form.amount = amount;
    },
    "deal_dealbreakdown_form.purchase_tax_type": function (newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_dealbreakdown_form.purchase_tax_percentage = "";
        } else {
          this.deal_dealbreakdown_form.purchase_tax_percentage =
            selected_tax[0].value;
        }
      } else if (isNaN(this.deal_dealbreakdown_form.purchase_tax_percentage)) {
        this.deal_dealbreakdown_form.purchase_tax_percentage = "";
      }
    },
    "deal_dealbreakdown_form.purchase_tax_type_secondary": function (
      newVal,
      oldVal
    ) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_dealbreakdown_form.purchase_tax_percentage_secondary = 0;
        } else {
          this.deal_dealbreakdown_form.purchase_tax_percentage_secondary =
            selected_tax[0].value;
        }
      } else if (
        isNaN(this.deal_dealbreakdown_form.purchase_tax_percentage_secondary)
      ) {
        this.deal_dealbreakdown_form.purchase_tax_percentage_secondary = 0;
      }
    },
    "deal_dealbreakdown_form.licence_fee": function (newVal, oldVal) {
      let licence_fee =
        !isNaN(this.deal_dealbreakdown_form.licence_fee) &&
        this.deal_dealbreakdown_form.licence_fee != ""
          ? this.deal_dealbreakdown_form.licence_fee
          : 0;
      this.deal_dealbreakdown_form.licence_fee = licence_fee;
    },
    "deal_dealbreakdown_form.lien_payout": function (newVal, oldVal) {
      let lien_payout =
        !isNaN(this.deal_dealbreakdown_form.lien_payout) &&
        this.deal_dealbreakdown_form.lien_payout != ""
          ? this.deal_dealbreakdown_form.lien_payout
          : 0;
      this.deal_dealbreakdown_form.lien_payout = lien_payout;
    },
    "deal_dealbreakdown_form.trade_value": function (newVal, oldVal) {
      let trade_value =
        !isNaN(this.deal_dealbreakdown_form.trade_value) &&
        this.deal_dealbreakdown_form.trade_value != ""
          ? this.deal_dealbreakdown_form.trade_value
          : 0;
      this.deal_dealbreakdown_form.trade_value = trade_value;
    },
    "deal_dealbreakdown_form.gasoline": function (newVal, oldVal) {
      let gasoline =
        !isNaN(this.deal_dealbreakdown_form.gasoline) &&
        this.deal_dealbreakdown_form.gasoline != ""
          ? this.deal_dealbreakdown_form.gasoline
          : 0;
      this.deal_dealbreakdown_form.gasoline = gasoline;
    },
    "deal_dealbreakdown_form.lien_registration_fee": function (newVal, oldVal) {
      let lien_registration_fee =
        !isNaN(this.deal_dealbreakdown_form.lien_registration_fee) &&
        this.deal_dealbreakdown_form.lien_registration_fee != ""
          ? this.deal_dealbreakdown_form.lien_registration_fee
          : 0;
      this.deal_dealbreakdown_form.lien_registration_fee =
        lien_registration_fee;
    },
    "deal_finance_form.finance_commission": function (newVal, oldVal) {
      let finance_commission =
        !isNaN(this.deal_finance_form.finance_commission) &&
        this.deal_finance_form.finance_commission != ""
          ? this.deal_finance_form.finance_commission
          : 0;
      this.deal_finance_form.finance_commission = finance_commission;
    },
    "deal_finance_form.interest_rate": function (newVal, oldVal) {
      let interest_rate =
        !isNaN(this.deal_finance_form.interest_rate) &&
        this.deal_finance_form.interest_rate != ""
          ? this.deal_finance_form.interest_rate
          : 0;
      this.deal_finance_form.interest_rate = interest_rate;
    },
    "deal_finance_form.term": function (newVal, oldVal) {
      let term =
        !isNaN(this.deal_finance_form.term) && this.deal_finance_form.term != ""
          ? parseInt(this.deal_finance_form.term)
          : 0;
      this.deal_finance_form.term = term;
    },
    "deal_finance_form.interest_amount": function (newVal, oldVal) {
      let interest_amount =
        !isNaN(this.deal_finance_form.interest_amount) &&
        this.deal_finance_form.interest_amount != ""
          ? this.deal_finance_form.interest_amount
          : 0;
      this.deal_finance_form.interest_amount = interest_amount;
    },
    "deal_finance_form.emi_amount": function (newVal, oldVal) {
      let emi_amount =
        !isNaN(this.deal_finance_form.emi_amount) &&
        this.deal_finance_form.emi_amount != ""
          ? this.deal_finance_form.emi_amount
          : 0;
      this.deal_finance_form.emi_amount = emi_amount;
    },
    "roll_a_deal.adjust_amount": function (newVal, oldVal) {
      let adjust_amount =
        !isNaN(this.roll_a_deal.adjust_amount) &&
        this.roll_a_deal.adjust_amount != ""
          ? parseFloat(this.roll_a_deal.adjust_amount)
          : 0;
      this.roll_a_deal.adjust_amount = adjust_amount;
    },
    calculateSubTotal: function (newVal, oldVal) {
      this.deal_dealbreakdown_form.subtotal = newVal;
    },
    calculateSecondarySubTotal: function (newVal, oldVal) {
      this.deal_dealbreakdown_form.secondary_subtotal = newVal;
    },
    calculateNetDifference: function (newVal, oldVal) {
      this.deal_dealbreakdown_form.net_difference = newVal;
    },
    calculateInsuranceTaxAmount: function (newVal, oldVal) {
      this.deal_dealbreakdown_form.insurance_tax = newVal;
    },
    calculateTotalTax: function (newVal, oldVal) {
      this.deal_dealbreakdown_form.total_tax_amount = newVal;
    },
    calculateTotalBalance: function (newVal, oldVal) {
      this.deal_dealbreakdown_form.total_balance_due = newVal;
    },
    calculateTotalFinanceAmount: function (newVal, oldVal) {
      this.deal_finance_form.total_amount = newVal;
    },
    emiPayment: function (newVal, oldVal) {
      let override = this.deal_finance_form.override_finance_calculation;
      if (override == "1") {
        return;
      }

      this.deal_finance_form.emi_amount = newVal;
      let emi = parseFloat(this.deal_finance_form.emi_amount);

      let term = this.deal_finance_form.term;

      let payment_frequency = this.deal_finance_form.payment_frequency;

      if (payment_frequency == "Weekly") {
        term = (parseFloat(term) / 12) * 52;
      } else if (payment_frequency == "Bi-Weekly") {
        term = (parseFloat(term) / 12) * 26;
      }

      let amount = parseFloat(this.deal_finance_form.total_amount);

      let interest_amount = emi * term - amount;

      interest_amount = interest_amount < 0 ? 0 : interest_amount;
      this.deal_finance_form.interest_amount =
        Number.parseFloat(interest_amount).toFixed(2);
    },
  },
  data() {
    return {
      show_page: false,
      deal_fees_errors: [],
      deal_fees: [],
      deal_fees_modal_title: "Add Deal Fee",
      deal_fee_id: null,
      boll_a_deal_button_class: "",
      roll_a_deal: {
        adjust_amount: 0,
        adjust_column: "",
      },
      deal_fees_form: {
        name: "",
        amount: 0,
        description: "",
        tax_type: "",
        tax_percentage: 0,
        tax_amount: 0,

        tax_type_secondary: "",
        tax_amount_secondary: 0,
        tax_percentage_secondary: 0,
        tax_amount_total: 0,

        cost: 0,
        cost_tax_type: "",
        cost_tax_percentage: 0,
        cost_tax_amount: 0,

        cost_tax_type_secondary: "",
        cost_tax_amount_secondary: 0,
        cost_tax_percentage_secondary: 0,
        cost_tax_amount_total: 0,
      },

      deal_accessories_errors: [],
      deal_accessories: [],
      deal_accessories_modal_title: "Add New Accessory",
      deal_accessories_id: null,
      deal_accessories_form: {
        name: "",
        amount: 0,
        description: "",
        tax_type: "",
        tax_percentage: 0,
        tax_amount: 0,

        tax_type_secondary: "",
        tax_amount_secondary: 0,
        tax_percentage_secondary: 0,
        tax_amount_total: 0,

        cost: 0,
        cost_tax_type: "",
        cost_tax_percentage: 0,
        cost_tax_amount: 0,

        cost_tax_type_secondary: "",
        cost_tax_amount_secondary: 0,
        cost_tax_percentage_secondary: 0,
        cost_tax_amount_total: 0,
      },

      deal_warranties_errors: [],
      deal_warranties: [],
      deal_warranties_modal_title: "Add New Warranty",
      deal_warranties_id: null,
      deal_warranties_form: {
        company_name: "",
        duration: 0,
        distance: "",
        amount: 0,
        description: "",
        tax_type: "",
        tax_percentage: 0,
        tax_amount: 0,

        tax_type_secondary: "",
        tax_amount_secondary: 0,
        tax_percentage_secondary: 0,
        tax_amount_total: 0,

        cost: 0,
        cost_tax_type: "",
        cost_tax_percentage: 0,
        cost_tax_amount: 0,

        cost_tax_type_secondary: "",
        cost_tax_amount_secondary: 0,
        cost_tax_percentage_secondary: 0,
        cost_tax_amount_total: 0,
      },

      deal_guarantees_errors: [],
      deal_guarantees: [],
      deal_guarantees_modal_title: "Add New Guarantee",
      deal_guarantees_id: null,
      deal_guarantees_form: {
        company_name: "",
        duration: 0,
        distance: "",
        amount: 0,
        description: "",
        tax_type: "",
        tax_percentage: 0,
        tax_amount: 0,

        tax_type_secondary: "",
        tax_amount_secondary: 0,
        tax_percentage_secondary: 0,
        tax_amount_total: 0,

        cost: 0,
        cost_tax_type: "",
        cost_tax_percentage: 0,
        cost_tax_amount: 0,

        cost_tax_type_secondary: "",
        cost_tax_amount_secondary: 0,
        cost_tax_percentage_secondary: 0,
        cost_tax_amount_total: 0,
      },

      deal_insurances_errors: [],
      deal_insurances: [],
      deal_insurances_modal_title: "Add New Insurance",
      deal_insurances_id: null,
      deal_insurances_form: {
        type: "",
        amount: 0,
        deductible: 0,
        duration: 0,
        description: "",
        tax_type: "",
        tax_percentage: 0,
        tax_amount: 0,

        tax_type_secondary: "",
        tax_amount_secondary: 0,
        tax_percentage_secondary: 0,
        tax_amount_total: 0,

        cost: 0,
        cost_tax_type: "",
        cost_tax_percentage: 0,
        cost_tax_amount: 0,

        cost_tax_type_secondary: "",
        cost_tax_amount_secondary: 0,
        cost_tax_percentage_secondary: 0,
        cost_tax_amount_total: 0,
      },

      deal_payments_errors: [],
      deal_payments: [],
      deal_payments_modal_title: "Add New Payment",
      deal_payments_id: null,
      deal_payments_form: {
        amount: 0,
        description: "",
        type: "",
        is_downpayment_or_deposit: "0",
      },

      deal_dealbreakdown_errors: [],
      deal_dealbreakdown_id: null,
      is_deal_breakdown_loaded: false,
      deal_dealbreakdown_form: {
        purchase_price: 0,
        discount: 0,
        subtotal: 0,
        trade_value: 0,
        net_difference: 0,

        purchase_tax_type: "",
        purchase_tax_percentage: 0,
        tax_on_purchase_price: 0,

        purchase_tax_type_secondary: "",
        tax_on_purchase_price_secondary: 0,
        purchase_tax_percentage_secondary: 0,
        tax_on_purchase_total: 0,

        total_tax_amount: 0,
        insurance_tax: 0,

        licence_fee: 0,
        lien_payout: 0,
        trade_equity: 0,
        gasoline: 0,
        lien_registration_fee: 0,
        total_balance_due: 0,
        plate_type: "",
        secondary_subtotal: 0,
      },

      deal_finance_errors: [],
      deal_finance_id: null,
      deal_finance_form: {
        total_amount: 0,
        finance_commission: 0,
        override_finance_calculation: "0",
        interest_rate: 0,
        interest_rate_type: "Fixed",
        term: 0,
        payment_frequency: "Monthly",
        interest_amount: 0,
        emi_amount: 0,
        first_payment_date: null,
        lien_holder_name: "",
      },

      selected_cost_name: "",
      selected_accessory_name: "",
      selected_warranty_name: "",
      selected_guarantee_name: "",
      selected_insurance_name: "",
      cost_setting_listing: [],
      accessory_setting_listing: [],
      warranty_setting_listing: [],
      guarantee_setting_listing: [],
      insurance_setting_listing: [],

      total_balance_cost_includes_interest: 0,
      enable_payment_section_for_wholesale_deal: 0,

      deal_template_listing: [],
      selected_deal_template: "",
    };
  },
  computed: {
    ...mapGetters("configuration", ["businessTaxSettings"]),
    isPaymentSectionEnabledForWholeSaleDeal() {
      return parseInt(this.enable_payment_section_for_wholesale_deal, 10) === 1;
    },
    availableAdjustableColumns() {
      if (
        this.deal_type === `Financed` &&
        parseInt(this.retail_or_wholesale_flag, 10) === 0
      ) {
        return [
          { label: "Selling Price", value: "purchase_price" },
          { label: "Term", value: "term" },
        ];
      } else {
        return [{ label: "Selling Price", value: "purchase_price" }];
      }
    },
    requestAdjustmentColumnLabel() {
      if (
        this.deal_type === `Financed` &&
        parseInt(this.retail_or_wholesale_flag, 10) === 0
      ) {
        return `${this.deal_finance_form.payment_frequency}`;
      } else {
        return `Total Balance Due`;
      }
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    getdealTemplates() {
      const vm = this;
      return this.deal_template_listing.filter((template) => {
        return template.retail_or_wholesale_flag == vm.retail_or_wholesale_flag;
      });
    },
    isDealRetailOrWholesale() {
      return parseInt(this.retail_or_wholesale_flag);
    },
    calculateDealTotalFees() {
      let total = this.deal_fees.reduce(
        (amount, fee) => amount + parseFloat(fee.amount),
        0
      );

      return this.$helpers.round(total, 3);
    },
    calculateDealTotalAccessoryAmount() {
      let total = this.deal_accessories.reduce(
        (amount, accessory) => amount + parseFloat(accessory.amount),
        0
      );

      return this.$helpers.round(total, 3);
    },
    calculateDealTotalWarrantyAmount() {
      let total = this.deal_warranties.reduce(
        (amount, deal_warranty) => amount + parseFloat(deal_warranty.amount),
        0
      );

      return this.$helpers.round(total, 3);
    },
    calculateDealTotalInsuranceAmount() {
      let total = this.deal_insurances.reduce(
        (amount, deal_warranty) => amount + parseFloat(deal_warranty.amount),
        0
      );

      return this.$helpers.round(total, 3);
    },
    calculateTotalPayment() {
      let total = this.deal_payments.reduce(
        (amount, payment) => amount + parseFloat(payment.amount),
        0
      );

      return this.$helpers.round(total, 3);
    },
    warrantyCount() {
      return this.deal_warranties.length;
    },
    guaranteeCount() {
      return this.deal_guarantees.length;
    },
    feeCount() {
      return this.deal_fees.length;
    },
    calculateSubTotal() {
      //purchase_price, total fees, accessory total, warranty total (All without taxes)
      // minus discount
      let calSubTotal =
        parseFloat(this.deal_dealbreakdown_form.purchase_price) -
        parseFloat(this.deal_dealbreakdown_form.discount) +
        parseFloat(this.calculateDealTotalFees) +
        parseFloat(this.calculateDealTotalAccessoryAmount) +
        parseFloat(this.calculateDealTotalWarrantyAmount);

      return this.$helpers.round(calSubTotal, 3);
    },
    calculateNetDifference() {
      let diff =
        parseFloat(this.calculateSubTotal) -
        parseFloat(this.deal_dealbreakdown_form.trade_value);
      return this.$helpers.round(diff, 3);
    },
    calculateInsuranceTaxAmount() {
      let total = this.deal_insurances.reduce(
        (tax_amount, insurance) =>
          tax_amount + parseFloat(insurance.tax_amount_total),
        0
      );

      return this.$helpers.round(total, 3);
    },
    calculateTotalTax() {
      // adddition of taxes on fees, accessories, warranties and insurances
      let deal_fees_taxes = this.deal_fees.reduce(
        (tax_amount_total, fee) =>
          tax_amount_total + parseFloat(fee.tax_amount_total),
        0
      );
      let deal_accessories_taxes = this.deal_accessories.reduce(
        (tax_amount_total, accessory) =>
          tax_amount_total + parseFloat(accessory.tax_amount_total),
        0
      );
      let deal_warranties_taxes = this.deal_warranties.reduce(
        (tax_amount_total, warranty) =>
          tax_amount_total + parseFloat(warranty.tax_amount_total),
        0
      );
      // let deal_insurances_taxes = parseFloat(this.deal_dealbreakdown_form.insurance_tax);

      //addition of tax on purchase price.
      let tax_on_purchase_total = parseFloat(
        this.deal_dealbreakdown_form.tax_on_purchase_total
      );

      let total_taxes =
        deal_fees_taxes +
        deal_accessories_taxes +
        deal_warranties_taxes +
        0 +
        tax_on_purchase_total;

      return this.$helpers.round(total_taxes, 3);
    },
    calculateSecondarySubTotal() {
      let net_difference = parseFloat(
        this.deal_dealbreakdown_form.net_difference
      );
      let hst_on_net_difference = parseFloat(
        this.deal_dealbreakdown_form.total_tax_amount
      );
      let licence_fee = parseFloat(this.deal_dealbreakdown_form.licence_fee);
      let gasoline = parseFloat(this.deal_dealbreakdown_form.gasoline);
      let lien_payout = parseFloat(this.deal_dealbreakdown_form.lien_payout);

      let secondarySubTotal =
        net_difference +
        hst_on_net_difference +
        licence_fee +
        gasoline +
        lien_payout;

      return this.$helpers.round(secondarySubTotal, 3);
    },
    calculateTotalBalance() {
      // subtotal + total tax + insurance tax + licence fee
      // minus total payments
      //minus trade_equity
      // add insurance total amount
      //add gasoline
      //add lien_registration_fee

      //add interest if interest in enabled.

      let subtotal = parseFloat(this.deal_dealbreakdown_form.subtotal);
      let trade_equity = parseFloat(this.deal_dealbreakdown_form.trade_equity);
      let total_tax_amount = parseFloat(
        this.deal_dealbreakdown_form.total_tax_amount
      );
      let insurance_tax = parseFloat(
        this.deal_dealbreakdown_form.insurance_tax
      );
      let licence_fee = parseFloat(this.deal_dealbreakdown_form.licence_fee);
      let lien_payout = parseFloat(this.deal_dealbreakdown_form.lien_payout);
      let gasoline = parseFloat(this.deal_dealbreakdown_form.gasoline);
      let lien_registration_fee = parseFloat(
        this.deal_dealbreakdown_form.lien_registration_fee
      );
      let interest_amount = parseFloat(this.deal_finance_form.interest_amount);

      lien_payout = 0;

      let balance =
        subtotal -
        trade_equity +
        total_tax_amount +
        insurance_tax +
        licence_fee +
        gasoline +
        lien_registration_fee +
        lien_payout -
        this.calculateTotalPayment +
        this.calculateDealTotalInsuranceAmount;

      if (parseInt(this.total_balance_cost_includes_interest) === 1) {
        balance += interest_amount;
      }

      return this.$helpers.round(balance, 3);
    },
    calculateTotalFinanceAmount() {
      let interest_amount = parseFloat(this.deal_finance_form.interest_amount);
      let finance_amount = parseFloat(this.calculateTotalBalance);

      if (parseInt(this.total_balance_cost_includes_interest) === 1) {
        finance_amount -= interest_amount;
      }

      return this.$helpers.round(finance_amount, 3);
    },
    emiPayment() {
      let term_placeholder = "Month";

      let amount = parseFloat(this.deal_finance_form.total_amount);

      let payment_frequency = this.deal_finance_form.payment_frequency;

      let months = parseInt(this.deal_finance_form.term);
      let annInterest = parseFloat(this.deal_finance_form.interest_rate);
      let monInt = annInterest / 1200;

      if (payment_frequency == "Weekly") {
        term_placeholder = "Week";
        months = (parseInt(this.deal_finance_form.term) / 12) * 52;
        monInt = annInterest / 5200;
      } else if (payment_frequency == "Bi-Weekly") {
        term_placeholder = "Bi-Week";
        months = (parseInt(this.deal_finance_form.term) / 12) * 26;
        monInt = annInterest / 2600;
      }

      let down = 0;

      let cal = (
        (monInt + monInt / (Math.pow(1 + monInt, months) - 1)) *
        (amount - (down || 0))
      ).toFixed(2);
      return isFinite(cal) ? cal : 0;
    },
    enableDealFeeTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function () {
        let flag =
          !isNaN(this.deal_fees_form.tax_percentage) &&
          this.deal_fees_form.tax_percentage != ""
            ? true
            : false;
        if (!flag && this.show_page) {
          this.deal_fees_form.tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableDealFeeCostTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function () {
        let flag =
          !isNaN(this.deal_fees_form.cost_tax_percentage) &&
          this.deal_fees_form.cost_tax_percentage != ""
            ? true
            : false;
        if (!flag) {
          this.deal_fees_form.cost_tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableDealAccessoryCostTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function () {
        let flag =
          !isNaN(this.deal_accessories_form.cost_tax_percentage) &&
          this.deal_accessories_form.cost_tax_percentage != ""
            ? true
            : false;
        if (!flag) {
          this.deal_accessories_form.cost_tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableDealAccessoryTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function () {
        let flag =
          !isNaN(this.deal_accessories_form.tax_percentage) &&
          this.deal_accessories_form.tax_percentage != ""
            ? true
            : false;
        if (!flag && this.show_page) {
          this.deal_accessories_form.tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableDealWarrantyTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function () {
        let flag =
          !isNaN(this.deal_warranties_form.tax_percentage) &&
          this.deal_warranties_form.tax_percentage != ""
            ? true
            : false;
        if (!flag && this.show_page) {
          this.deal_warranties_form.tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableDealWarrantyCostTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function () {
        let flag =
          !isNaN(this.deal_warranties_form.cost_tax_percentage) &&
          this.deal_warranties_form.cost_tax_percentage != ""
            ? true
            : false;
        if (!flag) {
          this.deal_warranties_form.cost_tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableDealInsuranceTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function () {
        let flag =
          !isNaN(this.deal_insurances_form.tax_percentage) &&
          this.deal_insurances_form.tax_percentage != ""
            ? true
            : false;
        if (!flag && this.show_page) {
          this.deal_insurances_form.tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableDealInsuranceCostTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function () {
        let flag =
          !isNaN(this.deal_insurances_form.cost_tax_percentage) &&
          this.deal_insurances_form.cost_tax_percentage != ""
            ? true
            : false;
        if (!flag) {
          this.deal_insurances_form.cost_tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableDealGuaranteeCostTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function () {
        let flag =
          !isNaN(this.deal_guarantees_form.cost_tax_percentage) &&
          this.deal_guarantees_form.cost_tax_percentage != ""
            ? true
            : false;
        if (!flag) {
          this.deal_guarantees_form.cost_tax_type_secondary = "";
        }
        return flag;
      },
    },
    enableDealBreakTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function () {
        let flag =
          !isNaN(this.deal_dealbreakdown_form.purchase_tax_percentage) &&
          this.deal_dealbreakdown_form.purchase_tax_percentage != ""
            ? true
            : false;
        if (!flag && this.show_page) {
          this.deal_dealbreakdown_form.purchase_tax_percentage_secondary = "";
        }
        return flag;
      },
    },
    calculateDealFeeTax: {
      get: function () {
        let amount =
          !isNaN(this.deal_fees_form.amount) && this.deal_fees_form.amount != ""
            ? this.deal_fees_form.amount
            : 0;

        amount = parseFloat(amount);
        let sub_total = amount;

        let tax =
          !isNaN(this.deal_fees_form.tax_percentage) &&
          this.deal_fees_form.tax_percentage != ""
            ? sub_total * parseFloat(this.deal_fees_form.tax_percentage) * 0.01
            : parseFloat(this.deal_fees_form.tax_amount);

        tax = !isNaN(tax) ? tax : 0;
        this.deal_fees_form.tax_amount = this.$helpers.round(tax, 3);

        let tax_secondary =
          !isNaN(this.deal_fees_form.tax_percentage_secondary) &&
          this.deal_fees_form.tax_percentage_secondary != ""
            ? sub_total *
              parseFloat(this.deal_fees_form.tax_percentage_secondary) *
              0.01
            : parseFloat(0);

        tax_secondary = !isNaN(tax_secondary) ? tax_secondary : 0;
        this.deal_fees_form.tax_amount_secondary = this.$helpers.round(
          tax_secondary,
          3
        );

        tax += tax_secondary;

        if (this.deal_fees_form.tax_type == "Other") {
          this.deal_fees_form.tax_amount = 0;
          this.deal_fees_form.tax_amount_secondary = 0;

          tax =
            !isNaN(this.deal_fees_form.tax_amount_total) &&
            this.deal_fees_form.tax_amount_total != ""
              ? this.deal_fees_form.tax_amount_total
              : 0;

          this.deal_fees_form.tax_amount_total = tax;
        } else {
          this.deal_fees_form.tax_amount_total = this.$helpers.round(tax, 3);
        }

        let cost =
          !isNaN(this.deal_fees_form.cost) && this.deal_fees_form.cost != ""
            ? this.deal_fees_form.cost
            : 0;
        this.deal_fees_form.cost = cost;

        cost = parseFloat(cost);

        let cost_tax =
          !isNaN(this.deal_fees_form.cost_tax_percentage) &&
          this.deal_fees_form.cost_tax_percentage != ""
            ? cost * parseFloat(this.deal_fees_form.cost_tax_percentage) * 0.01
            : parseFloat(this.deal_fees_form.cost_tax_amount);

        cost_tax = !isNaN(cost_tax) ? cost_tax : 0;
        this.deal_fees_form.cost_tax_amount = this.$helpers.round(cost_tax, 3);

        let cost_tax_secondary =
          !isNaN(this.deal_fees_form.cost_tax_percentage_secondary) &&
          this.deal_fees_form.cost_tax_percentage_secondary != ""
            ? cost *
              parseFloat(this.deal_fees_form.cost_tax_percentage_secondary) *
              0.01
            : parseFloat(0);

        cost_tax_secondary = !isNaN(cost_tax_secondary)
          ? cost_tax_secondary
          : 0;
        this.deal_fees_form.cost_tax_amount_secondary = this.$helpers.round(
          cost_tax_secondary,
          3
        );

        cost_tax += cost_tax_secondary;

        if (this.deal_fees_form.cost_tax_type == "Other") {
          this.deal_fees_form.cost_tax_amount = 0;
          this.deal_fees_form.cost_tax_amount_secondary = 0;

          cost_tax =
            !isNaN(this.deal_fees_form.cost_tax_amount_total) &&
            this.deal_fees_form.cost_tax_amount_total != ""
              ? this.deal_fees_form.cost_tax_amount_total
              : 0;

          this.deal_fees_form.cost_tax_amount_total = cost_tax;
        } else {
          this.deal_fees_form.cost_tax_amount_total = this.$helpers.round(
            cost_tax,
            3
          );
        }
      },
    },
    calculateDealAccessoryTax: {
      get: function () {
        let amount =
          !isNaN(this.deal_accessories_form.amount) &&
          this.deal_accessories_form.amount != ""
            ? this.deal_accessories_form.amount
            : 0;
        this.deal_accessories_form.amount = amount;

        amount = parseFloat(amount);

        let sub_total = amount;

        let tax =
          !isNaN(this.deal_accessories_form.tax_percentage) &&
          this.deal_accessories_form.tax_percentage != ""
            ? sub_total *
              parseFloat(this.deal_accessories_form.tax_percentage) *
              0.01
            : parseFloat(this.deal_accessories_form.tax_amount);

        tax = !isNaN(tax) ? tax : 0;
        this.deal_accessories_form.tax_amount = this.$helpers.round(tax, 3);

        let tax_secondary =
          !isNaN(this.deal_accessories_form.tax_percentage_secondary) &&
          this.deal_accessories_form.tax_percentage_secondary != ""
            ? sub_total *
              parseFloat(this.deal_accessories_form.tax_percentage_secondary) *
              0.01
            : parseFloat(0);

        tax_secondary = !isNaN(tax_secondary) ? tax_secondary : 0;
        this.deal_accessories_form.tax_amount_secondary = this.$helpers.round(
          tax_secondary,
          3
        );

        tax += tax_secondary;

        if (this.deal_accessories_form.tax_type == "Other") {
          this.deal_accessories_form.tax_amount = 0;
          this.deal_accessories_form.tax_amount_secondary = 0;

          tax =
            !isNaN(this.deal_accessories_form.tax_amount_total) &&
            this.deal_accessories_form.tax_amount_total != ""
              ? this.deal_accessories_form.tax_amount_total
              : 0;

          this.deal_accessories_form.tax_amount_total = tax;
        } else {
          this.deal_accessories_form.tax_amount_total = this.$helpers.round(
            tax,
            3
          );
        }

        let cost =
          !isNaN(this.deal_accessories_form.cost) &&
          this.deal_accessories_form.cost != ""
            ? this.deal_accessories_form.cost
            : 0;
        this.deal_accessories_form.cost = cost;

        cost = parseFloat(cost);

        let cost_tax =
          !isNaN(this.deal_accessories_form.cost_tax_percentage) &&
          this.deal_accessories_form.cost_tax_percentage != ""
            ? cost *
              parseFloat(this.deal_accessories_form.cost_tax_percentage) *
              0.01
            : parseFloat(this.deal_accessories_form.cost_tax_amount);

        cost_tax = !isNaN(cost_tax) ? cost_tax : 0;
        this.deal_accessories_form.cost_tax_amount = this.$helpers.round(
          cost_tax,
          3
        );

        let cost_tax_secondary =
          !isNaN(this.deal_accessories_form.cost_tax_percentage_secondary) &&
          this.deal_accessories_form.cost_tax_percentage_secondary != ""
            ? cost *
              parseFloat(
                this.deal_accessories_form.cost_tax_percentage_secondary
              ) *
              0.01
            : parseFloat(0);

        cost_tax_secondary = !isNaN(cost_tax_secondary)
          ? cost_tax_secondary
          : 0;
        this.deal_accessories_form.cost_tax_amount_secondary =
          this.$helpers.round(cost_tax_secondary, 3);

        cost_tax += cost_tax_secondary;

        if (this.deal_accessories_form.cost_tax_type == "Other") {
          this.deal_accessories_form.cost_tax_amount = 0;
          this.deal_accessories_form.cost_tax_amount_secondary = 0;

          cost_tax =
            !isNaN(this.deal_accessories_form.cost_tax_amount_total) &&
            this.deal_accessories_form.cost_tax_amount_total != ""
              ? this.deal_accessories_form.cost_tax_amount_total
              : 0;

          this.deal_accessories_form.cost_tax_amount_total = cost_tax;
        } else {
          this.deal_accessories_form.cost_tax_amount_total =
            this.$helpers.round(cost_tax, 3);
        }
      },
    },
    calculateDealWarrantyTax: {
      get: function () {
        let amount =
          !isNaN(this.deal_warranties_form.amount) &&
          this.deal_warranties_form.amount != ""
            ? this.deal_warranties_form.amount
            : 0;
        this.deal_warranties_form.amount = amount;

        amount = parseFloat(amount);

        let sub_total = amount;

        let tax =
          !isNaN(this.deal_warranties_form.tax_percentage) &&
          this.deal_warranties_form.tax_percentage != ""
            ? sub_total *
              parseFloat(this.deal_warranties_form.tax_percentage) *
              0.01
            : parseFloat(this.deal_warranties_form.tax_amount);

        tax = !isNaN(tax) ? tax : 0;
        this.deal_warranties_form.tax_amount = this.$helpers.round(tax, 3);

        let tax_secondary =
          !isNaN(this.deal_warranties_form.tax_percentage_secondary) &&
          this.deal_warranties_form.tax_percentage_secondary != ""
            ? sub_total *
              parseFloat(this.deal_warranties_form.tax_percentage_secondary) *
              0.01
            : parseFloat(0);

        tax_secondary = !isNaN(tax_secondary) ? tax_secondary : 0;
        this.deal_warranties_form.tax_amount_secondary = this.$helpers.round(
          tax_secondary,
          3
        );

        tax += tax_secondary;

        if (this.deal_warranties_form.tax_type == "Other") {
          this.deal_warranties_form.tax_amount = 0;
          this.deal_warranties_form.tax_amount_secondary = 0;
          tax =
            !isNaN(this.deal_warranties_form.tax_amount_total) &&
            this.deal_warranties_form.tax_amount_total != ""
              ? this.deal_warranties_form.tax_amount_total
              : 0;

          this.deal_warranties_form.tax_amount_total = tax;
        } else {
          this.deal_warranties_form.tax_amount_total = this.$helpers.round(
            tax,
            3
          );
        }

        let cost =
          !isNaN(this.deal_warranties_form.cost) &&
          this.deal_warranties_form.cost != ""
            ? this.deal_warranties_form.cost
            : 0;
        this.deal_warranties_form.cost = cost;

        cost = parseFloat(cost);

        let cost_tax =
          !isNaN(this.deal_warranties_form.cost_tax_percentage) &&
          this.deal_warranties_form.cost_tax_percentage != ""
            ? cost *
              parseFloat(this.deal_warranties_form.cost_tax_percentage) *
              0.01
            : parseFloat(this.deal_warranties_form.cost_tax_amount);

        cost_tax = !isNaN(cost_tax) ? cost_tax : 0;
        this.deal_warranties_form.cost_tax_amount = this.$helpers.round(
          cost_tax,
          3
        );

        let cost_tax_secondary =
          !isNaN(this.deal_warranties_form.cost_tax_percentage_secondary) &&
          this.deal_warranties_form.cost_tax_percentage_secondary != ""
            ? cost *
              parseFloat(
                this.deal_warranties_form.cost_tax_percentage_secondary
              ) *
              0.01
            : parseFloat(0);

        cost_tax_secondary = !isNaN(cost_tax_secondary)
          ? cost_tax_secondary
          : 0;
        this.deal_warranties_form.cost_tax_amount_secondary =
          this.$helpers.round(cost_tax_secondary, 3);

        cost_tax += cost_tax_secondary;

        if (this.deal_warranties_form.cost_tax_type == "Other") {
          this.deal_warranties_form.cost_tax_amount = 0;
          this.deal_warranties_form.cost_tax_amount_secondary = 0;

          cost_tax =
            !isNaN(this.deal_warranties_form.cost_tax_amount_total) &&
            this.deal_warranties_form.cost_tax_amount_total != ""
              ? this.deal_warranties_form.cost_tax_amount_total
              : 0;

          this.deal_warranties_form.cost_tax_amount_total = cost_tax;
        } else {
          this.deal_warranties_form.cost_tax_amount_total = this.$helpers.round(
            cost_tax,
            3
          );
        }
      },
    },
    calculateDealGuaranteeTax: {
      get: function () {
        let cost =
          !isNaN(this.deal_guarantees_form.cost) &&
          this.deal_guarantees_form.cost != ""
            ? this.deal_guarantees_form.cost
            : 0;
        this.deal_guarantees_form.cost = cost;

        cost = parseFloat(cost);

        let cost_tax =
          !isNaN(this.deal_guarantees_form.cost_tax_percentage) &&
          this.deal_guarantees_form.cost_tax_percentage != ""
            ? cost *
              parseFloat(this.deal_guarantees_form.cost_tax_percentage) *
              0.01
            : parseFloat(this.deal_guarantees_form.cost_tax_amount);

        cost_tax = !isNaN(cost_tax) ? cost_tax : 0;
        this.deal_guarantees_form.cost_tax_amount = this.$helpers.round(
          cost_tax,
          3
        );

        let cost_tax_secondary =
          !isNaN(this.deal_guarantees_form.cost_tax_percentage_secondary) &&
          this.deal_guarantees_form.cost_tax_percentage_secondary != ""
            ? cost *
              parseFloat(
                this.deal_guarantees_form.cost_tax_percentage_secondary
              ) *
              0.01
            : parseFloat(0);

        cost_tax_secondary = !isNaN(cost_tax_secondary)
          ? cost_tax_secondary
          : 0;
        this.deal_guarantees_form.cost_tax_amount_secondary =
          this.$helpers.round(cost_tax_secondary, 3);

        cost_tax += cost_tax_secondary;

        if (this.deal_guarantees_form.cost_tax_type == "Other") {
          this.deal_guarantees_form.cost_tax_amount = 0;
          this.deal_guarantees_form.cost_tax_amount_secondary = 0;

          cost_tax =
            !isNaN(this.deal_guarantees_form.cost_tax_amount_total) &&
            this.deal_guarantees_form.cost_tax_amount_total != ""
              ? this.deal_guarantees_form.cost_tax_amount_total
              : 0;

          this.deal_guarantees_form.cost_tax_amount_total = cost_tax;
        } else {
          this.deal_guarantees_form.cost_tax_amount_total = this.$helpers.round(
            cost_tax,
            3
          );
        }
      },
    },
    calculateDealInsuranceTax: {
      get: function () {
        let amount =
          !isNaN(this.deal_insurances_form.amount) &&
          this.deal_insurances_form.amount != ""
            ? this.deal_insurances_form.amount
            : 0;
        this.deal_insurances_form.amount = amount;

        amount = parseFloat(amount);

        let sub_total = amount;

        let tax =
          !isNaN(this.deal_insurances_form.tax_percentage) &&
          this.deal_insurances_form.tax_percentage != ""
            ? sub_total *
              parseFloat(this.deal_insurances_form.tax_percentage) *
              0.01
            : parseFloat(this.deal_insurances_form.tax_amount);

        tax = !isNaN(tax) ? tax : 0;
        this.deal_insurances_form.tax_amount = this.$helpers.round(tax, 3);

        let tax_secondary =
          !isNaN(this.deal_insurances_form.tax_percentage_secondary) &&
          this.deal_insurances_form.tax_percentage_secondary != ""
            ? sub_total *
              parseFloat(this.deal_insurances_form.tax_percentage_secondary) *
              0.01
            : parseFloat(0);

        tax_secondary = !isNaN(tax_secondary) ? tax_secondary : 0;
        this.deal_insurances_form.tax_amount_secondary = this.$helpers.round(
          tax_secondary,
          3
        );

        tax += tax_secondary;

        if (this.deal_insurances_form.tax_type == "Other") {
          this.deal_insurances_form.tax_amount = 0;
          this.deal_insurances_form.tax_amount_secondary = 0;
          tax =
            !isNaN(this.deal_insurances_form.tax_amount_total) &&
            this.deal_insurances_form.tax_amount_total != ""
              ? this.deal_insurances_form.tax_amount_total
              : 0;

          this.deal_insurances_form.tax_amount_total = tax;
        } else {
          this.deal_insurances_form.tax_amount_total = this.$helpers.round(
            tax,
            3
          );
        }

        let cost =
          !isNaN(this.deal_insurances_form.cost) &&
          this.deal_insurances_form.cost != ""
            ? this.deal_insurances_form.cost
            : 0;
        this.deal_insurances_form.cost = cost;

        cost = parseFloat(cost);

        let cost_tax =
          !isNaN(this.deal_insurances_form.cost_tax_percentage) &&
          this.deal_insurances_form.cost_tax_percentage != ""
            ? cost *
              parseFloat(this.deal_insurances_form.cost_tax_percentage) *
              0.01
            : parseFloat(this.deal_insurances_form.cost_tax_amount);

        cost_tax = !isNaN(cost_tax) ? cost_tax : 0;
        this.deal_insurances_form.cost_tax_amount = this.$helpers.round(
          cost_tax,
          3
        );

        let cost_tax_secondary =
          !isNaN(this.deal_insurances_form.cost_tax_percentage_secondary) &&
          this.deal_insurances_form.cost_tax_percentage_secondary != ""
            ? cost *
              parseFloat(
                this.deal_insurances_form.cost_tax_percentage_secondary
              ) *
              0.01
            : parseFloat(0);

        cost_tax_secondary = !isNaN(cost_tax_secondary)
          ? cost_tax_secondary
          : 0;
        this.deal_insurances_form.cost_tax_amount_secondary =
          this.$helpers.round(cost_tax_secondary, 3);

        cost_tax += cost_tax_secondary;

        if (this.deal_insurances_form.cost_tax_type == "Other") {
          this.deal_insurances_form.cost_tax_amount = 0;
          this.deal_insurances_form.cost_tax_amount_secondary = 0;

          cost_tax =
            !isNaN(this.deal_insurances_form.cost_tax_amount_total) &&
            this.deal_insurances_form.cost_tax_amount_total != ""
              ? this.deal_insurances_form.cost_tax_amount_total
              : 0;

          this.deal_insurances_form.cost_tax_amount_total = cost_tax;
        } else {
          this.deal_insurances_form.cost_tax_amount_total = this.$helpers.round(
            cost_tax,
            3
          );
        }
      },
    },
    calculateDealBreakDown: {
      get: function () {
        let purchase_price =
          !isNaN(this.deal_dealbreakdown_form.purchase_price) &&
          this.deal_dealbreakdown_form.purchase_price != ""
            ? this.deal_dealbreakdown_form.purchase_price
            : 0;
        this.deal_dealbreakdown_form.purchase_price = purchase_price;

        purchase_price = parseFloat(purchase_price);

        let discount =
          !isNaN(this.deal_dealbreakdown_form.discount) &&
          this.deal_dealbreakdown_form.discount != ""
            ? this.deal_dealbreakdown_form.discount
            : 0;
        this.deal_dealbreakdown_form.discount = discount;

        discount = parseFloat(discount);

        let trade_value =
          !isNaN(this.deal_dealbreakdown_form.trade_value) &&
          this.deal_dealbreakdown_form.trade_value != ""
            ? this.deal_dealbreakdown_form.trade_value
            : 0;
        this.deal_dealbreakdown_form.trade_value = trade_value;

        trade_value = parseFloat(trade_value);

        let sub_total = purchase_price;
        sub_total -= discount;
        sub_total -= trade_value;

        let tax =
          !isNaN(this.deal_dealbreakdown_form.purchase_tax_percentage) &&
          this.deal_dealbreakdown_form.purchase_tax_percentage != ""
            ? sub_total *
              parseFloat(this.deal_dealbreakdown_form.purchase_tax_percentage) *
              0.01
            : parseFloat(this.deal_dealbreakdown_form.tax_on_purchase_price);

        tax = !isNaN(tax) ? tax : 0;
        this.deal_dealbreakdown_form.tax_on_purchase_price =
          this.$helpers.round(tax, 3);
        //sub_total += tax;

        let tax_secondary =
          !isNaN(
            this.deal_dealbreakdown_form.purchase_tax_percentage_secondary
          ) &&
          this.deal_dealbreakdown_form.purchase_tax_percentage_secondary != ""
            ? sub_total *
              parseFloat(
                this.deal_dealbreakdown_form.purchase_tax_percentage_secondary
              ) *
              0.01
            : parseFloat(0);

        tax_secondary = !isNaN(tax_secondary) ? tax_secondary : 0;

        this.deal_dealbreakdown_form.tax_on_purchase_price_secondary =
          this.$helpers.round(tax_secondary, 3);

        tax += tax_secondary;

        if (this.deal_dealbreakdown_form.purchase_tax_type == "Other") {
          this.deal_dealbreakdown_form.tax_on_purchase_price = 0;
          this.deal_dealbreakdown_form.tax_on_purchase_price_secondary = 0;
          tax = !isNaN(this.deal_dealbreakdown_form.tax_on_purchase_total)
            ? parseFloat(this.deal_dealbreakdown_form.tax_on_purchase_total)
            : 0;
        }

        this.deal_dealbreakdown_form.tax_on_purchase_total =
          this.$helpers.round(tax, 3);

        // this.deal_dealbreakdown_form.sub_total = sub_total;
      },
    },
  },
  methods: {
    ...mapActions("configuration", ["getBusinessTaxSettings"]),
    async rollADeal() {
      this.boll_a_deal_button_class = "bx-spin";

      let formData = new FormData();
      let request_column =
        this.deal_type === `Financed` &&
        parseInt(this.retail_or_wholesale_flag, 10) === 0
          ? `emi_amount`
          : `total_balance_due`;
      formData.append("adjust_amount", this.roll_a_deal.adjust_amount);
      formData.append("adjust_column", this.roll_a_deal.adjust_column);
      formData.append("request_column", request_column);

      formData.append("deal_type", this.deal_type);
      formData.append(
        "retail_or_wholesale_flag",
        this.retail_or_wholesale_flag
      );
      formData.append(
        "total_balance_cost_includes_interest",
        this.total_balance_cost_includes_interest
      );

      let finance_attributes = [
        "emi_amount",
        "term",
        "interest_rate",
        "override_finance_calculation",
        "payment_frequency",
      ];

      for (let attribute of finance_attributes) {
        formData.append(attribute, this.deal_finance_form[attribute]);
      }

      formData.append("finance_amount", this.deal_finance_form.total_amount);

      let dealbreakdown_attributes = [
        "total_balance_due",
        "trade_value",
        "purchase_tax_percentage",
        "purchase_tax_percentage_secondary",
        "purchase_price",
      ];

      for (let attribute of dealbreakdown_attributes) {
        formData.append(attribute, this.deal_dealbreakdown_form[attribute]);
      }

      let message = "Rolling a new deal...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "post",
        url: `${window.location.origin}/api/business/deals/roll-a-deal`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          this.boll_a_deal_button_class = "";
          toast.success(`Amount adjusted successfully for the new deal.`);
          toast.close();

          if (
            data.hasOwnProperty("request_amount") &&
            data.hasOwnProperty("adjust_column")
          ) {
            if (
              !isNaN(data.request_amount) &&
              data.adjust_column === `purchase_price`
            ) {
              this.deal_dealbreakdown_form.purchase_price = this.$helpers.round(
                data.request_amount,
                3
              );
            } else if (
              !isNaN(data.request_amount) &&
              data.adjust_column === `term`
            ) {
              this.deal_finance_form.term = data.request_amount;
            }
          }
        })
        .catch(() => {
          this.boll_a_deal_button_class = "";
          toast.fail(`Failed to roll a new deal.`);
          toast.close();
        });

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "deal",
        "roll_a_deal",
        ""
      );
    },
    getDealConfigurations() {
      let request_url =
        window.location.origin + "/api/business/configurations/active/deal";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          for (const key in data) {
            if (data.hasOwnProperty(key)) {
              const element = data[key];
              if (element.key == "total_balance_cost_includes_interest") {
                this.total_balance_cost_includes_interest = element.value;
              } else if (
                element.key == "enable_payment_section_for_wholesale_deal"
              ) {
                this.enable_payment_section_for_wholesale_deal = element.value;
              }
            }
          }
        })
        .catch((e) => {
          console.log(e);
        });
    },
    editDealFee(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/deals/${vm.$route.params.id}/common/fees/show/`;
      url += id;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          this.deal_fees_errors = [];
          for (const key in this.deal_fees_form) {
            if (
              this.deal_fees_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_fees_form[key] = data[key];
            }
          }

          this.deal_fee_id = data.id;
          this.deal_fees_modal_title = "Update Deal Fee";

          jQuery("#dealFeeModal").modal("show");
        })
        .catch(({ error }) => {
          let error_message = `Error in fetching deal fee.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    editDealAccessory(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/deals/${vm.$route.params.id}/common/accessories/show/`;
      url += id;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          this.deal_accessories_errors = [];
          for (const key in this.deal_accessories_form) {
            if (
              this.deal_accessories_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_accessories_form[key] = data[key];
            }
          }

          this.deal_accessories_id = data.id;
          this.deal_accessories_modal_title = "Update Accessory";

          jQuery("#dealAccessoryModal").modal("show");
        })
        .catch(({ error }) => {
          let error_message = `Error in fetching accessory.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    editDealWarranty(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/deals/${vm.$route.params.id}/common/warranties/show/`;
      url += id;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          this.deal_warranties_errors = [];
          for (const key in this.deal_warranties_form) {
            if (
              this.deal_warranties_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_warranties_form[key] = data[key];
            }
          }

          this.deal_warranties_id = data.id;
          this.deal_warranties_modal_title = "Update Warranty";

          jQuery("#dealWarrantyModal").modal("show");
        })
        .catch(({ error }) => {
          let error_message = `Error in fetching warranty.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    editDealGuarantee(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/deals/${vm.$route.params.id}/common/guarantee/show/`;
      url += id;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          this.deal_guarantees_errors = [];
          for (const key in this.deal_guarantees_form) {
            if (
              this.deal_guarantees_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_guarantees_form[key] = data[key];
            }
          }

          this.deal_guarantees_id = data.id;
          this.deal_guarantees_modal_title = "Update Guarantee";

          jQuery("#dealGuaranteeModal").modal("show");
        })
        .catch(({ error }) => {
          let error_message = `Error in fetching guarantee.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    editDealPayment(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/deals/${vm.$route.params.id}/common/payments/show/`;
      url += id;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          this.deal_payments_errors = [];
          for (const key in this.deal_payments_form) {
            if (
              this.deal_payments_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_payments_form[key] = data[key];
            }
          }

          this.deal_payments_id = data.id;
          this.deal_payments_modal_title = "Update Payment";

          jQuery("#dealPaymentModal").modal("show");
        })
        .catch(({ error }) => {
          let error_message = `Error in fetching payment.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    editDealInsurance(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/deals/${vm.$route.params.id}/common/insurances/show/`;
      url += id;

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          this.deal_insurances_errors = [];
          for (const key in this.deal_insurances_form) {
            if (
              this.deal_insurances_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_insurances_form[key] = data[key];
            }
          }

          this.deal_insurances_id = data.id;
          this.deal_insurances_modal_title = "Update Insurance";

          jQuery("#dealInsuranceModal").modal("show");
        })
        .catch(({ error }) => {
          let error_message = `Error in fetching insurance.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    deleteDealFee(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/deals/${vm.$route.params.id}/common/fees/delete/`;
      url += id;

      let message = "Deleting deal fee...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `Deal fee has been deleted.`;
          toast.success(success_message);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            (async () => {
              await vm.getDealFees(vm.$route.params.id);
              await vm.updateWorksheetAndFinance();
            })();
          }
          //this.pagination(this.page);
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting deal fee.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    deleteDealAccessory(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/deals/${vm.$route.params.id}/common/accessories/delete/`;
      url += id;

      let message = "Deleting accessory...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `Accessory has been deleted.`;
          toast.success(success_message);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            (async () => {
              await vm.getAccessories(vm.$route.params.id);
              await vm.updateWorksheetAndFinance();
            })();
          }
          //this.pagination(this.page);
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting accessory.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    deleteDealWarranty(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/deals/${vm.$route.params.id}/common/warranties/delete/`;
      url += id;

      let message = "Deleting warranty...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `Warranty has been deleted.`;
          toast.success(success_message);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            (async () => {
              await vm.getWarranties(vm.$route.params.id);
              await vm.updateWorksheetAndFinance();
            })();
          }
          //this.pagination(this.page);
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting warranty.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    deleteDealGuarantee(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/deals/${vm.$route.params.id}/common/guarantee/delete/`;
      url += id;

      let message = "Deleting guarantee...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `Guarantee has been deleted.`;
          toast.success(success_message);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            (async () => {
              await vm.getGuarantees(vm.$route.params.id);
              await vm.updateWorksheetAndFinance();
            })();
          }
          //this.pagination(this.page);
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting guarantee.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    deleteDealPayment(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/deals/${vm.$route.params.id}/common/payments/delete/`;
      url += id;

      let message = "Deleting payment...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `Payment has been deleted.`;
          toast.success(success_message);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            (async () => {
              await vm.getPayments(vm.$route.params.id);
              await vm.updateWorksheetAndFinance();
            })();
          }
          //this.pagination(this.page);
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting payment.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    deleteDealInsurance(id) {
      const vm = this;
      let url = window.location.origin;
      url += `/api/business/deals/${vm.$route.params.id}/common/insurances/delete/`;
      url += id;

      let message = "Deleting insurance...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `Insurance has been deleted.`;
          toast.success(success_message);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            (async () => {
              await vm.getInsurances(vm.$route.params.id);
              await vm.updateWorksheetAndFinance();
            })();
          }
          //this.pagination(this.page);
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting insurance.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    async saveDealFee() {
      //save or update the deal fee
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.deal_fees_form) {
        if (
          this.deal_fees_form.hasOwnProperty(key) &&
          this.deal_fees_form[key] != null
        ) {
          formData.append(key, this.deal_fees_form[key]);
        }
      }

      let request_url = window.location.origin;
      let update_request = !isNaN(this.deal_fee_id) && this.deal_fee_id != null;

      //success message setting
      let success_message = update_request
        ? `Deal fee updated.`
        : `Deal fee added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/deals/${this.$route.params.id}/common/fees/update/${this.deal_fee_id}`
        : `${request_url}/api/business/deals/${this.$route.params.id}/common/fees/create`;

      //message during request
      let request_message = update_request
        ? `Updating deal fee ...`
        : `Adding new deal fee ...`;

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
          vm.resetDealFeeForm();
          document.getElementById("dealFeeModalClose").click();
          toast.success(data.success);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            (async () => {
              await vm.getDealFees(vm.$route.params.id);
              await vm.updateWorksheetAndFinance();
            })();
          }
        })
        .catch(function (error) {
          let update_request = !isNaN(vm.deal_fee_id) && vm.deal_fee_id != null;

          let error_message = update_request
            ? "There were errors while updating the deal fee."
            : "There were some errors while adding deal fee.";

          vm.$data.deal_fees_errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    saveDealAccessory() {
      //save or update the deal fee
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.deal_accessories_form) {
        if (
          this.deal_accessories_form.hasOwnProperty(key) &&
          this.deal_accessories_form[key] != null
        ) {
          formData.append(key, this.deal_accessories_form[key]);
        }
      }

      let request_url = window.location.origin;
      let update_request =
        !isNaN(this.deal_accessories_id) && this.deal_accessories_id != null;

      //success message setting
      let success_message = update_request
        ? `Accessory updated.`
        : `Accessory added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/deals/${this.$route.params.id}/common/accessories/update/${this.deal_accessories_id}`
        : `${request_url}/api/business/deals/${this.$route.params.id}/common/accessories/create`;

      //message during request
      let request_message = update_request
        ? `Updating accessory ...`
        : `Adding new accessory ...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          vm.resetDealAccessoryForm();
          document.getElementById("dealAccessoryModalClose").click();
          toast.success(data.success);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            (async () => {
              await vm.getAccessories(vm.$route.params.id);
              // await document.getElementById("deal-accessories-table").parentElement.parentElement.scrollIntoView({behavior: "smooth"});
              await vm.updateWorksheetAndFinance();
            })();
          }
        })
        .catch(function (error) {
          let update_request = !isNaN(vm.deal_fee_id) && vm.deal_fee_id != null;

          let error_message = update_request
            ? "There were errors while updating the accessory."
            : "There were some errors while adding accessory.";

          vm.$data.deal_accessories_errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    saveDealWarranty() {
      //save or update the warranty
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.deal_warranties_form) {
        if (
          this.deal_warranties_form.hasOwnProperty(key) &&
          this.deal_warranties_form[key] != null
        ) {
          formData.append(key, this.deal_warranties_form[key]);
        }
      }

      let request_url = window.location.origin;
      let update_request =
        !isNaN(this.deal_warranties_id) && this.deal_warranties_id != null;

      //success message setting
      let success_message = update_request
        ? `Warranty updated.`
        : `Warranty added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/deals/${this.$route.params.id}/common/warranties/update/${this.deal_warranties_id}`
        : `${request_url}/api/business/deals/${this.$route.params.id}/common/warranties/create`;

      //message during request
      let request_message = update_request
        ? `Updating warranty ...`
        : `Adding new warranty ...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          vm.resetDealWarrantyForm();
          document.getElementById("dealWarrantyModalClose").click();
          toast.success(data.success);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            (async () => {
              await vm.getWarranties(vm.$route.params.id);
              //await document.getElementById("deal-warranties-table").parentElement.parentElement.scrollIntoView({behavior: "smooth"});
              await vm.updateWorksheetAndFinance();
            })();
          }
        })
        .catch(function (error) {
          let update_request =
            !isNaN(vm.deal_warranties_id) && vm.deal_warranties_id != null;

          let error_message = update_request
            ? "There were errors while updating the warranty."
            : "There were some errors while adding warranty.";

          vm.$data.deal_warranties_errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    saveDealGuarantee() {
      //save or update the warranty
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.deal_guarantees_form) {
        if (
          this.deal_guarantees_form.hasOwnProperty(key) &&
          this.deal_guarantees_form[key] != null
        ) {
          formData.append(key, this.deal_guarantees_form[key]);
        }
      }

      let request_url = window.location.origin;
      let update_request =
        !isNaN(this.deal_guarantees_id) && this.deal_guarantees_id != null;

      //success message setting
      let success_message = update_request
        ? `Guarantee updated.`
        : `Guarantee added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/deals/${this.$route.params.id}/common/guarantee/update/${this.deal_guarantees_id}`
        : `${request_url}/api/business/deals/${this.$route.params.id}/common/guarantee/create`;

      //message during request
      let request_message = update_request
        ? `Updating guarantee ...`
        : `Adding new guarantee ...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          vm.resetDealGuaranteeForm();
          document.getElementById("dealGuaranteeModalClose").click();
          toast.success(data.success);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            (async () => {
              await vm.getGuarantees(vm.$route.params.id);
              // await document.getElementById("deal-guarantees-table").parentElement.parentElement.scrollIntoView({behavior: "smooth"});
              await vm.updateWorksheetAndFinance();
            })();
          }
        })
        .catch(function (error) {
          let update_request =
            !isNaN(vm.deal_guarantees_id) && vm.deal_guarantees_id != null;

          let error_message = update_request
            ? "There were errors while updating the guarantee."
            : "There were some errors while adding guarantee.";

          vm.$data.deal_guarantees_errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    saveDealPayment() {
      //save or update the payment
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.deal_payments_form) {
        if (
          this.deal_payments_form.hasOwnProperty(key) &&
          this.deal_payments_form[key] != null
        ) {
          formData.append(key, this.deal_payments_form[key]);
        }
      }

      let request_url = window.location.origin;
      let update_request =
        !isNaN(this.deal_payments_id) && this.deal_payments_id != null;

      //success message setting
      let success_message = update_request
        ? `Payment updated.`
        : `Payment added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/deals/${this.$route.params.id}/common/payments/update/${this.deal_payments_id}`
        : `${request_url}/api/business/deals/${this.$route.params.id}/common/payments/create`;

      //message during request
      let request_message = update_request
        ? `Updating payment ...`
        : `Adding new payment ...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          vm.resetDealPaymentForm();
          document.getElementById("dealPaymentModalClose").click();
          toast.success(data.success);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            (async () => {
              await vm.getPayments(vm.$route.params.id);
              //await document.getElementById("deal-payments-table").parentElement.parentElement.scrollIntoView({behavior: "smooth"});
              await vm.updateWorksheetAndFinance();
            })();
          }
        })
        .catch(function (error) {
          let update_request =
            !isNaN(vm.deal_payments_id) && vm.deal_payments_id != null;

          let error_message = update_request
            ? "There were errors while updating the payment."
            : "There were some errors while adding payment.";

          vm.$data.deal_payments_errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    saveDealInsurance() {
      //save or update the insurance
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.deal_insurances_form) {
        if (
          this.deal_insurances_form.hasOwnProperty(key) &&
          this.deal_insurances_form[key] != null
        ) {
          formData.append(key, this.deal_insurances_form[key]);
        }
      }

      let request_url = window.location.origin;
      let update_request =
        !isNaN(this.deal_insurances_id) && this.deal_insurances_id != null;

      //success message setting
      let success_message = update_request
        ? `Insurance updated.`
        : `Insurance added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/deals/${this.$route.params.id}/common/insurances/update/${this.deal_insurances_id}`
        : `${request_url}/api/business/deals/${this.$route.params.id}/common/insurances/create`;

      //message during request
      let request_message = update_request
        ? `Updating insurance ...`
        : `Adding new insurance ...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          vm.resetDealInsuranceForm();
          document.getElementById("dealInsuranceModalClose").click();
          toast.success(data.success);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            (async () => {
              await vm.getInsurances(vm.$route.params.id);
              // await document.getElementById("deal-insurances-table").parentElement.parentElement.scrollIntoView({behavior: "smooth"});
              await vm.updateWorksheetAndFinance();
            })();
          }
        })
        .catch(function (error) {
          let update_request =
            !isNaN(vm.deal_insurances_id) && vm.deal_insurances_id != null;

          let error_message = update_request
            ? "There were errors while updating the insurance."
            : "There were some errors while adding insurance.";

          vm.$data.deal_insurances_errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    async saveDealBreakDown(show_toast = true) {
      //save or update the insurance
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.deal_dealbreakdown_form) {
        if (
          this.deal_dealbreakdown_form.hasOwnProperty(key) &&
          this.deal_dealbreakdown_form[key] != null
        ) {
          formData.append(key, this.deal_dealbreakdown_form[key]);
        }
      }

      let request_url = window.location.origin;
      let update_request =
        !isNaN(this.deal_dealbreakdown_id) &&
        this.deal_dealbreakdown_id != null;

      //success message setting
      let success_message = update_request
        ? `Deal Breakdown updated.`
        : `Deal Breakdown added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/deals/${this.$route.params.id}/common/dealbreakdown/update/${this.deal_dealbreakdown_id}`
        : `${request_url}/api/business/deals/${this.$route.params.id}/common/dealbreakdown/create`;

      //message during request
      let request_message = update_request
        ? `Updating deal breakdown ...`
        : `Adding new deal breakdown ...`;

      const toast = new Toast(request_message);
      if (show_toast) {
        toast.fire();
      }

      await axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          if (show_toast) {
            toast.success(data.success);
            toast.close();
          }

          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            vm.getDealBreakDown(vm.$route.params.id);
          }

          Event.fire("refresh_complete_break_down", null);
        })
        .catch(function (error) {
          let update_request =
            !isNaN(vm.deal_dealbreakdown_id) &&
            vm.deal_dealbreakdown_id != null;

          let error_message = update_request
            ? "There were errors while updating the deal breakdown."
            : "There were some errors while adding deal breakdown.";

          vm.$data.deal_dealbreakdown_errors = error.response.data;

          if (show_toast) {
            toast.fail(error_message);
            toast.close();
          }
        });
    },
    async saveDealFinance(show_toast = true) {
      //save or update the insurance
      const vm = this;
      //create data
      let first_payment_date = moment(
        this.deal_finance_form.first_payment_date
      );

      this.deal_finance_form.first_payment_date = first_payment_date.isValid()
        ? first_payment_date.format("MM/DD/YYYY")
        : null;

      let formData = new FormData();
      for (const key in this.deal_finance_form) {
        if (
          this.deal_finance_form.hasOwnProperty(key) &&
          this.deal_finance_form[key] != null
        ) {
          formData.append(key, this.deal_finance_form[key]);
        }
      }

      let request_url = window.location.origin;
      let update_request =
        !isNaN(this.deal_finance_id) && this.deal_finance_id != null;

      //success message setting
      let success_message = update_request
        ? `Deal Finance updated.`
        : `Deal Finance added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/deals/${this.$route.params.id}/common/finance/update/${this.deal_finance_id}`
        : `${request_url}/api/business/deals/${this.$route.params.id}/common/finance/create`;

      //message during request
      let request_message = update_request
        ? `Updating deal finance ...`
        : `Adding new deal finance ...`;

      const toast = new Toast(request_message);

      if (show_toast) {
        toast.fire();
      }

      await axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          console.log(data);
          if (show_toast) {
            toast.success(data.success);
            toast.close();
          }

          if (vm.$route.params.id && vm.$route.name == "deal-update") {
            vm.getDealFinance(vm.$route.params.id);
          }
        })
        .catch(function (error) {
          let update_request =
            !isNaN(vm.deal_finance_id) && vm.deal_finance_id != null;

          let error_message = update_request
            ? "There were errors while updating the deal finance."
            : "There were some errors while adding deal finance.";

          vm.$data.deal_finance_errors = error.response.data;

          if (show_toast) {
            toast.fail(error_message);
            toast.close();
          }
        });
    },
    async getDealBreakDown(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/deals/" +
          deal_id +
          "/common/dealbreakdown",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.deal_dealbreakdown_errors = [];

          if (data.length > 0) {
            for (const key in this.deal_dealbreakdown_form) {
              if (
                this.deal_dealbreakdown_form.hasOwnProperty(key) &&
                data[0].hasOwnProperty(key)
              ) {
                this.deal_dealbreakdown_form[key] = data[0][key];
              }
            }
            if (data[0].hasOwnProperty("id")) {
              this.deal_dealbreakdown_id = data[0].id;
            }
          }

          this.is_deal_breakdown_loaded = true;
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    async getDealFinance(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/deals/" +
          deal_id +
          "/common/finance",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.deal_finance_errors = [];

          if (data.length > 0) {
            for (const key in this.deal_finance_form) {
              if (
                this.deal_finance_form.hasOwnProperty(key) &&
                data[0].hasOwnProperty(key)
              ) {
                this.deal_finance_form[key] = data[0][key];
              }
            }
            if (data[0].hasOwnProperty("id")) {
              this.deal_finance_id = data[0].id;
              let date = moment(data[0].first_payment_date);
              this.deal_finance_form.first_payment_date = date.isValid()
                ? date.format("MM/DD/YYYY")
                : null;
            }
          }
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    async getDealFees(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/deals/" +
          deal_id +
          "/common/fees",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.deal_fees = data;
        })
        .catch(({ response }) => {
          this.deal_fees = [];
          console.log(response);
        });
    },
    async getAccessories(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/deals/" +
          deal_id +
          "/common/accessories",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.deal_accessories = data;
        })
        .catch(({ response }) => {
          this.deal_accessories = [];
          console.log(response);
        });
    },

    async getWarranties(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/deals/" +
          deal_id +
          "/common/warranties",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.deal_warranties = data;
        })
        .catch(({ response }) => {
          this.deal_warranties = [];
          console.log(response);
        });
    },
    async getGuarantees(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/deals/" +
          deal_id +
          "/common/guarantee",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.deal_guarantees = data;
        })
        .catch(({ response }) => {
          this.deal_guarantees = [];
          console.log(response);
        });
    },
    async getInsurances(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/deals/" +
          deal_id +
          "/common/insurances",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.deal_insurances = data;
        })
        .catch(({ response }) => {
          this.deal_insurances = [];
          console.log(response);
        });
    },
    async getPayments(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/deals/" +
          deal_id +
          "/common/payments",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.deal_payments = data;
        })
        .catch(({ response }) => {
          this.deal_payments = [];
          console.log(response);
        });
    },
    resetDealFeeForm() {
      this.deal_fees_form.name = "";
      this.deal_fees_form.amount = 0;
      this.deal_fees_form.description = "";
      this.deal_fees_form.tax_type = "";
      this.deal_fees_form.tax_percentage = 0;
      this.deal_fees_form.tax_amount = 0;

      this.deal_fees_modal_title = "Add New Expense";

      this.deal_fees_form.tax_type_secondary = "";
      this.deal_fees_form.tax_amount_secondary = 0;
      this.deal_fees_form.tax_percentage_secondary = 0;
      this.deal_fees_form.tax_amount_secondary = 0;

      this.deal_fees_form.cost = 0;

      this.deal_fees_form.cost_tax_type = "";
      this.deal_fees_form.cost_tax_percentage = 0;
      this.deal_fees_form.cost_tax_amount = 0;

      this.deal_fees_form.cost_tax_type_secondary = "";
      this.deal_fees_form.cost_tax_amount_secondary = 0;
      this.deal_fees_form.cost_tax_percentage_secondary = 0;

      this.deal_fees_form.cost_tax_amount_total = 0;

      this.deal_fees_errors = [];
      this.deal_fee_id = null;

      this.selected_cost_name = "";

      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.default == "1"
      );

      console.log(selected_tax);
      if (selected_tax.length > 0) {
        this.deal_fees_form.tax_type = selected_tax[0].name;
        this.deal_fees_form.cost_tax_type = selected_tax[0].name;

        if (selected_tax.length > 1) {
          this.deal_fees_form.tax_type_secondary = selected_tax[1].name;
          this.deal_fees_form.cost_tax_type_secondary = selected_tax[1].name;
        }
      }

      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_fees_form.tax_percentage = "";
          this.deal_fees_form.cost_tax_percentage = "";
        } else {
          this.deal_fees_form.tax_percentage = selected_tax[0].value;
          this.deal_fees_form.cost_tax_percentage = selected_tax[0].value;
        }

        if (selected_tax.length > 1) {
          if (isNaN(selected_tax[1].value)) {
            this.deal_fees_form.tax_percentage_secondary = 0;
            this.deal_fees_form.cost_tax_percentage_secondary = 0;
          } else {
            this.deal_fees_form.tax_percentage_secondary =
              selected_tax[1].value;
            this.deal_fees_form.cost_tax_percentage_secondary =
              selected_tax[1].value;
          }
        } else {
          this.deal_fees_form.tax_percentage_secondary = 0;
          this.deal_fees_form.cost_tax_percentage_secondary = 0;
        }
      } else {
        this.deal_fees_form.tax_percentage = "";
        this.deal_fees_form.cost_tax_percentage = "";
      }
    },
    resetDealAccessoryForm() {
      this.deal_accessories_form.name = "";
      this.deal_accessories_form.amount = 0;
      this.deal_accessories_form.description = "";
      this.deal_accessories_form.tax_type = "";
      this.deal_accessories_form.tax_percentage = 0;
      this.deal_accessories_form.tax_amount = 0;

      this.deal_accessories_modal_title = "Add New Accessory";

      this.deal_accessories_form.tax_type_secondary = "";
      this.deal_accessories_form.tax_amount_secondary = 0;
      this.deal_accessories_form.tax_percentage_secondary = 0;
      this.deal_accessories_form.tax_amount_secondary = 0;

      this.deal_accessories_form.cost = 0;

      this.deal_accessories_form.cost_tax_type = "";
      this.deal_accessories_form.cost_tax_percentage = 0;
      this.deal_accessories_form.cost_tax_amount = 0;

      this.deal_accessories_form.cost_tax_type_secondary = "";
      this.deal_accessories_form.cost_tax_amount_secondary = 0;
      this.deal_accessories_form.cost_tax_percentage_secondary = 0;

      this.deal_accessories_form.cost_tax_amount_total = 0;

      this.deal_accessories_errors = [];
      this.deal_accessories_id = null;

      this.selected_accessory_name = "";

      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.default == "1"
      );

      console.log(selected_tax);
      if (selected_tax.length > 0) {
        this.deal_accessories_form.tax_type = selected_tax[0].name;
        this.deal_accessories_form.cost_tax_type = selected_tax[0].name;
        if (selected_tax.length > 1) {
          this.deal_accessories_form.tax_type_secondary = selected_tax[1].name;
          this.deal_accessories_form.cost_tax_type_secondary =
            selected_tax[1].name;
        }
      }

      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_accessories_form.tax_percentage = "";
          this.deal_accessories_form.cost_tax_percentage = "";
        } else {
          this.deal_accessories_form.tax_percentage = selected_tax[0].value;
          this.deal_accessories_form.cost_tax_percentage =
            selected_tax[0].value;
        }

        if (selected_tax.length > 1) {
          if (isNaN(selected_tax[1].value)) {
            this.deal_accessories_form.tax_percentage_secondary = 0;
            this.deal_accessories_form.cost_tax_percentage_secondary = 0;
          } else {
            this.deal_accessories_form.tax_percentage_secondary =
              selected_tax[1].value;
            this.deal_accessories_form.cost_tax_percentage_secondary =
              selected_tax[1].value;
          }
        } else {
          this.deal_accessories_form.tax_percentage_secondary = 0;
          this.deal_accessories_form.cost_tax_percentage_secondary = 0;
        }
      } else {
        this.deal_accessories_form.tax_percentage = "";
        this.deal_accessories_form.cost_tax_percentage = "";
      }
    },
    resetDealGuaranteeForm() {
      this.deal_guarantees_form.company_name = "";
      this.deal_guarantees_form.amount = 0;
      this.deal_guarantees_form.description = "";
      this.deal_guarantees_form.tax_type = "";
      this.deal_guarantees_form.tax_percentage = 0;
      this.deal_guarantees_form.tax_amount = 0;
      this.deal_guarantees_form.duration = 0;
      this.deal_guarantees_form.distance = "";

      this.deal_guarantees_form.tax_type_secondary = "";
      this.deal_guarantees_form.tax_amount_secondary = 0;
      this.deal_guarantees_form.tax_percentage_secondary = 0;
      this.deal_guarantees_form.tax_amount_secondary = 0;

      this.deal_guarantees_form.cost = 0;

      this.deal_guarantees_form.cost_tax_type = "";
      this.deal_guarantees_form.cost_tax_percentage = 0;
      this.deal_guarantees_form.cost_tax_amount = 0;

      this.deal_guarantees_form.cost_tax_type_secondary = "";
      this.deal_guarantees_form.cost_tax_amount_secondary = 0;
      this.deal_guarantees_form.cost_tax_percentage_secondary = 0;

      this.deal_guarantees_form.cost_tax_amount_total = 0;

      this.deal_guarantees_modal_title = "Add New Guarantee";

      this.deal_guarantees_errors = [];
      this.deal_guarantees_id = null;
      this.selected_guarantee_name = "";

      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.default == "1"
      );

      console.log(selected_tax);
      if (selected_tax.length > 0) {
        this.deal_guarantees_form.tax_type = selected_tax[0].name;
        this.deal_guarantees_form.cost_tax_type = selected_tax[0].name;
        if (selected_tax.length > 1) {
          this.deal_guarantees_form.tax_type_secondary = selected_tax[1].name;
          this.deal_guarantees_form.cost_tax_type_secondary =
            selected_tax[1].name;
        }
      }

      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_guarantees_form.tax_percentage = "";
          this.deal_guarantees_form.cost_tax_percentage = "";
        } else {
          this.deal_guarantees_form.tax_percentage = selected_tax[0].value;
          this.deal_guarantees_form.cost_tax_percentage = selected_tax[0].value;
        }

        if (selected_tax.length > 1) {
          if (isNaN(selected_tax[1].value)) {
            this.deal_guarantees_form.tax_percentage_secondary = 0;
            this.deal_guarantees_form.cost_tax_percentage_secondary = 0;
          } else {
            this.deal_guarantees_form.tax_percentage_secondary =
              selected_tax[1].value;
            this.deal_guarantees_form.cost_tax_percentage_secondary =
              selected_tax[1].value;
          }
        } else {
          this.deal_guarantees_form.tax_percentage_secondary = 0;
          this.deal_guarantees_form.cost_tax_percentage_secondary = 0;
        }
      } else {
        this.deal_guarantees_form.tax_percentage = "";
        this.deal_guarantees_form.cost_tax_percentage = "";
      }
    },
    resetDealFinanceForm() {
      let deal_finance_form = {
        total_amount: 0,
        finance_commission: 0,
        override_finance_calculation: "0",
        interest_rate: 0,
        interest_rate_type: "Fixed",
        term: 0,
        payment_frequency: "Monthly",
        interest_amount: 0,
        emi_amount: 0,
        first_payment_date: null,
        lien_holder_name: "",
      };

      this.deal_finance_form = {
        ...deal_finance_form,
      };
    },
    resetDealWarrantyForm() {
      this.deal_warranties_form.company_name = "";
      this.deal_warranties_form.amount = 0;
      this.deal_warranties_form.description = "";
      this.deal_warranties_form.tax_type = "";
      this.deal_warranties_form.tax_percentage = 0;
      this.deal_warranties_form.tax_amount = 0;
      this.deal_warranties_form.duration = 0;
      this.deal_warranties_form.distance = "";

      this.deal_warranties_modal_title = "Add New Warranty";

      this.deal_warranties_form.tax_type_secondary = "";
      this.deal_warranties_form.tax_amount_secondary = 0;
      this.deal_warranties_form.tax_percentage_secondary = 0;
      this.deal_warranties_form.tax_amount_secondary = 0;

      this.deal_warranties_form.cost = 0;

      this.deal_warranties_form.cost_tax_type = "";
      this.deal_warranties_form.cost_tax_percentage = 0;
      this.deal_warranties_form.cost_tax_amount = 0;

      this.deal_warranties_form.cost_tax_type_secondary = "";
      this.deal_warranties_form.cost_tax_amount_secondary = 0;
      this.deal_warranties_form.cost_tax_percentage_secondary = 0;

      this.deal_warranties_form.cost_tax_amount_total = 0;

      this.deal_warranties_errors = [];
      this.deal_warranties_id = null;

      this.selected_warranty_name = "";

      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.default == "1"
      );

      console.log(selected_tax);
      if (selected_tax.length > 0) {
        this.deal_warranties_form.tax_type = selected_tax[0].name;
        this.deal_warranties_form.cost_tax_type = selected_tax[0].name;
        if (selected_tax.length > 1) {
          this.deal_warranties_form.tax_type_secondary = selected_tax[1].name;
          this.deal_warranties_form.cost_tax_type_secondary =
            selected_tax[1].name;
        }
      }

      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_warranties_form.tax_percentage = "";
          this.deal_warranties_form.cost_tax_percentage = "";
        } else {
          this.deal_warranties_form.tax_percentage = selected_tax[0].value;
          this.deal_warranties_form.cost_tax_percentage = selected_tax[0].value;
        }

        if (selected_tax.length > 1) {
          if (isNaN(selected_tax[1].value)) {
            this.deal_warranties_form.tax_percentage_secondary = 0;
            this.deal_warranties_form.cost_tax_percentage_secondary = 0;
          } else {
            this.deal_warranties_form.tax_percentage_secondary =
              selected_tax[1].value;
            this.deal_warranties_form.cost_tax_percentage_secondary =
              selected_tax[1].value;
          }
        } else {
          this.deal_warranties_form.tax_percentage_secondary = 0;
          this.deal_warranties_form.cost_tax_percentage_secondary = 0;
        }
      } else {
        this.deal_warranties_form.tax_percentage = "";
        this.deal_warranties_form.cost_tax_percentage = "";
      }
    },
    resetDealPaymentForm() {
      this.deal_payments_form.amount = 0;
      this.deal_payments_form.description = "";
      this.deal_payments_form.type = "";
      this.deal_payments_form.is_downpayment_or_deposit = "0";

      this.deal_payments_modal_title = "Add New Payment";

      this.deal_payments_errors = [];
      this.deal_payments_id = null;
    },
    resetDealInsuranceForm() {
      this.deal_insurances_form.type = "";
      this.deal_insurances_form.amount = 0;
      this.deal_insurances_form.deductible = 0;
      this.deal_insurances_form.duration = 0;
      this.deal_insurances_form.description = "";
      this.deal_insurances_form.tax_type = "";
      this.deal_insurances_form.tax_percentage = 0;
      this.deal_insurances_form.tax_amount = 0;

      this.deal_insurances_form.tax_type_secondary = "";
      this.deal_insurances_form.tax_amount_secondary = 0;
      this.deal_insurances_form.tax_percentage_secondary = 0;
      this.deal_insurances_form.tax_amount_secondary = 0;

      this.deal_insurances_form.cost = 0;

      this.deal_insurances_form.cost_tax_type = "";
      this.deal_insurances_form.cost_tax_percentage = 0;
      this.deal_insurances_form.cost_tax_amount = 0;

      this.deal_insurances_form.cost_tax_type_secondary = "";
      this.deal_insurances_form.cost_tax_amount_secondary = 0;
      this.deal_insurances_form.cost_tax_percentage_secondary = 0;

      this.deal_insurances_form.cost_tax_amount_total = 0;

      this.selected_insurance_name = "";

      this.deal_insurances_modal_title = "Add New Insurance";

      this.deal_insurances_errors = [];
      this.deal_insurances_id = null;

      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.default == "1"
      );

      console.log(selected_tax);
      if (selected_tax.length > 0) {
        this.deal_insurances_form.tax_type = selected_tax[0].name;
        this.deal_insurances_form.cost_tax_type = selected_tax[0].name;

        if (selected_tax.length > 1) {
          this.deal_insurances_form.tax_type_secondary = selected_tax[1].name;
          this.deal_insurances_form.cost_tax_type_secondary =
            selected_tax[1].name;
        }
      }

      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.deal_insurances_form.tax_percentage = "";
          this.deal_insurances_form.cost_tax_percentage = "";
        } else {
          this.deal_insurances_form.tax_percentage = selected_tax[0].value;
          this.deal_insurances_form.cost_tax_percentage = selected_tax[0].value;
        }

        if (selected_tax.length > 1) {
          if (isNaN(selected_tax[1].value)) {
            this.deal_insurances_form.tax_percentage_secondary = 0;
            this.deal_insurances_form.cost_tax_percentage_secondary = 0;
          } else {
            this.deal_insurances_form.tax_percentage_secondary =
              selected_tax[1].value;
            this.deal_insurances_form.cost_tax_percentage_secondary =
              selected_tax[1].value;
          }
        } else {
          this.deal_insurances_form.tax_percentage_secondary = 0;
          this.deal_insurances_form.cost_tax_percentage_secondary = 0;
        }
      } else {
        this.deal_insurances_form.tax_percentage = "";
        this.deal_insurances_form.cost_tax_percentage = "";
      }
    },
    async updateWorksheetAndFinance() {
      const toast = new Toast(`Updating the Deal Break Down & Finance ...`);
      toast.fire();

      // if (!isNaN(parseInt(this.deal_dealbreakdown_id))) {
      //   await this.saveDealBreakDown(false);
      // }

      await this.saveDealBreakDown(false);

      // if (!isNaN(parseInt(this.deal_finance_id))) {
      //   await this.saveDealFinance(false);
      // }

      await this.saveDealFinance(false);

      toast.success(`Deal Break Down & Finance Updated.`);
      toast.close();

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "note",
        "deal_worksheet",
        ""
      );
    },
    async refreshAndUpdate() {
      const vm = this;
      // fetch all the data again

      const toast = new Toast(`Updating the Deal Break Down & Finance ...`);

      console.log(parseInt(this.retail_or_wholesale_flag));

      await this.getDealBreakDown(this.$route.params.id);
      if (
        parseInt(this.retail_or_wholesale_flag) == 1 ||
        this.deal_type != "Financed"
      ) {
        this.resetDealFinanceForm();
      }

      if (parseInt(this.retail_or_wholesale_flag) == 1) {
        this.deal_dealbreakdown_form.trade_value = 0;
        this.deal_dealbreakdown_form.lien_payout = 0;
        this.deal_dealbreakdown_form.trade_equity = 0;
        this.deal_dealbreakdown_form.gasoline = 0;
        this.deal_dealbreakdown_form.lien_registration_fee = 0;
      }
      toast.fire();
      await this.getDealFees(this.$route.params.id);
      await this.getAccessories(this.$route.params.id);
      await this.getWarranties(this.$route.params.id);
      await this.getGuarantees(this.$route.params.id);
      await this.getInsurances(this.$route.params.id);
      await this.getPayments(this.$route.params.id);
      await this.getDealFinance(this.$route.params.id);

      //if (!isNaN(parseInt(this.deal_dealbreakdown_id))) {
      await this.saveDealBreakDown(false);
      //}

      //if (!isNaN(parseInt(this.deal_finance_id))) {
      await this.saveDealFinance(false);
      // }

      toast.success(`Deal Break Down & Finance Updated.`);
      toast.close();
    },
    costSettingListing() {
      let request_url =
        window.location.origin + "/api/business/setting/fees?paginate=false";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          this.cost_setting_listing = data.cost_setting_listing;
        })
        .catch(() => {
          console.log("Some error in fetching the cost settings");
        });
    },
    getCostSetting(id) {
      const vm = this;
      axios({
        method: "get",
        url: window.location.origin + "/api/business/setting/fees/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.errors = [];
          for (const key in this.deal_fees_form) {
            if (
              this.deal_fees_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_fees_form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the fee setting";
          console.log(error);
        });
    },
    getAccessorySetting(id) {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/setting/accessories/show/" +
          id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.errors = [];
          for (const key in this.deal_accessories_form) {
            if (
              this.deal_accessories_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_accessories_form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          let error =
            "There were some errors in retrieving the accessory setting";
          console.log(error);
        });
    },
    accessorySettingListing() {
      let request_url =
        window.location.origin +
        "/api/business/setting/accessories?paginate=false";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          this.accessory_setting_listing = data.cost_setting_listing;
        })
        .catch(() => {
          console.log("Some error in fetching the accessory settings");
        });
    },
    getWarrantySetting(id) {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin + "/api/business/setting/warranty/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.errors = [];
          for (const key in this.deal_warranties_form) {
            if (
              this.deal_warranties_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_warranties_form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          let error =
            "There were some errors in retrieving the warranty setting";
          console.log(error);
        });
    },
    getGuaranteeSetting(id) {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin + "/api/business/setting/guarantee/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.errors = [];
          for (const key in this.deal_guarantees_form) {
            if (
              this.deal_guarantees_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_guarantees_form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          let error =
            "There were some errors in retrieving the guarantee setting";
          console.log(error);
        });
    },
    warrantySettingListing() {
      let request_url =
        window.location.origin +
        "/api/business/setting/warranty?paginate=false";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          this.warranty_setting_listing = data.cost_setting_listing;
        })
        .catch(() => {
          console.log("Some error in fetching the warranty settings");
        });
    },
    guaranteeSettingListing() {
      let request_url =
        window.location.origin +
        "/api/business/setting/guarantee?paginate=false";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          this.guarantee_setting_listing = data.cost_setting_listing;
        })
        .catch(() => {
          console.log("Some error in fetching the guarantee settings");
        });
    },
    getInsuranceSetting(id) {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/setting/insurances/show/" +
          id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          this.errors = [];
          for (const key in this.deal_insurances_form) {
            if (
              this.deal_insurances_form.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.deal_insurances_form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          let error =
            "There were some errors in retrieving the insurance setting";
          console.log(error);
        });
    },
    insuranceSettingListing() {
      let request_url =
        window.location.origin +
        "/api/business/setting/insurances?paginate=false";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          this.insurance_setting_listing = data.cost_setting_listing;
        })
        .catch(() => {
          console.log("Some error in fetching the insurance settings");
        });
    },
    async importDealTemplate(deal_template_id) {
      const vm = this;
      if (
        this.deal_fees.length === 0 &&
        this.deal_accessories.length === 0 &&
        this.deal_warranties.length === 0 &&
        this.deal_guarantees.length === 0 &&
        this.deal_insurances.length === 0 &&
        this.deal_payments.length === 0
      ) {
        await axios({
          method: "get",
          url:
            window.location.origin +
            "/api/business/model-deals/" +
            deal_template_id +
            "/common/fees",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
        })
          .then(({ data }) => {
            if (data.length > 0) {
              let fees = data;
              for (const fee of fees) {
                if (typeof fee === "object") {
                  for (const key in this.deal_fees_form) {
                    if (
                      vm.$data.deal_fees_form.hasOwnProperty(key) &&
                      fee[key] != null
                    ) {
                      vm.$data.deal_fees_form[key] = fee[key];
                    }
                  }
                  vm.$data.deal_fee_id = null;
                  (async () => {
                    await vm.saveDealFee();
                  })();
                }
              }
            }
          })
          .catch(({ response }) => {});

        if (vm.retail_or_wholesale_flag == "0") {
          await axios({
            method: "get",
            url:
              window.location.origin +
              "/api/business/model-deals/" +
              deal_template_id +
              "/common/accessories",
            headers: {
              Authorization: "Bearer " + window.$cookies.get("_token"),
            },
          })
            .then(({ data }) => {
              if (data.length > 0) {
                let accessories = data;
                for (const accessory of accessories) {
                  if (typeof accessory === "object") {
                    for (const key in this.deal_accessories_form) {
                      if (
                        vm.$data.deal_accessories_form.hasOwnProperty(key) &&
                        accessory[key] != null
                      ) {
                        vm.$data.deal_accessories_form[key] = accessory[key];
                      }
                    }
                    vm.$data.deal_accessories_id = null;
                    (async () => {
                      await vm.saveDealAccessory();
                    })();
                  }
                }
              }
            })
            .catch(({ response }) => {});

          await axios({
            method: "get",
            url:
              window.location.origin +
              "/api/business/model-deals/" +
              deal_template_id +
              "/common/warranties",
            headers: {
              Authorization: "Bearer " + window.$cookies.get("_token"),
            },
          })
            .then(({ data }) => {
              if (data.length > 0) {
                let warranties = data;
                for (const warranty of warranties) {
                  if (typeof warranty === "object") {
                    for (const key in this.deal_warranties_form) {
                      if (
                        vm.$data.deal_warranties_form.hasOwnProperty(key) &&
                        warranty[key] != null
                      ) {
                        vm.$data.deal_warranties_form[key] = warranty[key];
                      }
                    }
                    vm.$data.deal_warranties_id = null;
                    (async () => {
                      await vm.saveDealWarranty();
                    })();
                  }
                }
              }
            })
            .catch(({ response }) => {});

          await axios({
            method: "get",
            url:
              window.location.origin +
              "/api/business/model-deals/" +
              deal_template_id +
              "/common/guarantee",
            headers: {
              Authorization: "Bearer " + window.$cookies.get("_token"),
            },
          })
            .then(({ data }) => {
              if (data.length > 0) {
                let guarantees = data;
                for (const guarantee of guarantees) {
                  if (typeof guarantee === "object") {
                    for (const key in this.deal_guarantees_form) {
                      if (
                        vm.$data.deal_guarantees_form.hasOwnProperty(key) &&
                        guarantee[key] != null
                      ) {
                        vm.$data.deal_guarantees_form[key] = guarantee[key];
                      }
                    }
                    vm.$data.deal_guarantees_id = null;
                    (async () => {
                      await vm.saveDealGuarantee();
                    })();
                  }
                }
              }
            })
            .catch(({ response }) => {});

          await axios({
            method: "get",
            url:
              window.location.origin +
              "/api/business/model-deals/" +
              deal_template_id +
              "/common/insurances",
            headers: {
              Authorization: "Bearer " + window.$cookies.get("_token"),
            },
          })
            .then(({ data }) => {
              if (data.length > 0) {
                let insurances = data;
                for (const insurance of insurances) {
                  if (typeof insurance === "object") {
                    for (const key in this.deal_insurances_form) {
                      if (
                        vm.$data.deal_insurances_form.hasOwnProperty(key) &&
                        insurance[key] != null
                      ) {
                        vm.$data.deal_insurances_form[key] = insurance[key];
                      }
                    }
                    vm.$data.deal_insurances_id = null;
                    (async () => {
                      await vm.saveDealInsurance();
                    })();
                  }
                }
              }
            })
            .catch(({ response }) => {});

          await axios({
            method: "get",
            url:
              window.location.origin +
              "/api/business/model-deals/" +
              deal_template_id +
              "/common/payments",
            headers: {
              Authorization: "Bearer " + window.$cookies.get("_token"),
            },
          })
            .then(({ data }) => {
              if (data.length > 0) {
                let payments = data;
                for (const payment of payments) {
                  if (typeof payment === "object") {
                    for (const key in this.deal_payments_form) {
                      if (
                        vm.$data.deal_payments_form.hasOwnProperty(key) &&
                        payment[key] != null
                      ) {
                        vm.$data.deal_payments_form[key] = payment[key];
                      }
                    }
                    vm.$data.deal_payments_id = null;
                    (async () => {
                      await vm.saveDealPayment();
                    })();
                  }
                }
              }
            })
            .catch(({ response }) => {});
        }
      } else {
        let error_message = `Template can only be imported for deals without Fees, Accessories, Warranty, Guarantee, Insurance And Payment Sections.`;
        const toast = new Toast("");
        toast.fire();
        toast.fail(error_message);
        toast.close();
      }
    },
    dealTemplateList() {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin + "/api/business/model-deals?paginate=false",
      }).then((response) => {
        vm.$data.deal_template_listing = response.data.deal_replicator_listing;
      });
    },
  },
  async created() {
    await this.getDealConfigurations();
    await this.getBusinessTaxSettings();
    if (this.$route.params.id && this.$route.name == "deal-update") {
      await this.getDealBreakDown(this.$route.params.id);
      await this.getDealFees(this.$route.params.id);
      await this.getAccessories(this.$route.params.id);
      await this.getWarranties(this.$route.params.id);
      await this.getGuarantees(this.$route.params.id);
      await this.getInsurances(this.$route.params.id);
      await this.getPayments(this.$route.params.id);
      await this.getDealFinance(this.$route.params.id);
      await this.costSettingListing();
      await this.accessorySettingListing();
      await this.warrantySettingListing();
      await this.guaranteeSettingListing();
      await this.insuranceSettingListing();
      await this.dealTemplateList();
    }
    this.show_page = true;
  },
  mounted() {
    const vm = this;
    jQuery("#dealFeeModal").on("hidden.bs.modal", function () {
      vm.resetDealFeeForm();
    });

    jQuery("#dealAccessoryModal").on("hidden.bs.modal", function () {
      vm.resetDealAccessoryForm();
    });

    jQuery("#dealWarrantyModal").on("hidden.bs.modal", function () {
      vm.resetDealWarrantyForm();
    });

    jQuery("#dealGuaranteeModal").on("hidden.bs.modal", function () {
      vm.resetDealGuaranteeForm();
    });

    jQuery("#dealPaymentModal").on("hidden.bs.modal", function () {
      vm.resetDealPaymentForm();
    });

    jQuery("#dealInsuranceModal").on("hidden.bs.modal", function () {
      vm.resetDealInsuranceForm();
    });

    Event.listen("saved_trade_in", (data) => {
      vm.updateWorksheetAndFinance();
    });

    Event.listen("update_worksheet_data", (data) => {
      vm.refreshAndUpdate();
    });
  },
};
</script>
<style lang="scss">
#deal-worksheet-panel {
  .vdpComponent {
    height: 3.9em !important;
  }
  .vdpComponent input {
    padding-left: 2.2em !important;
    line-height: 1.4;
    color: #777;
    background-color: #ffffff !important;
    background-clip: padding-box;
    border: 1px solid #dfe3e7;
    border-radius: 0.267rem;
    font-size: 1.4em;
  }

  .vdpComponent input {
    background: white;
    border: 0px;
  }
  .vdpComponent input:read-only {
    background: white !important;
    padding-right: 0em;
    font-size: 1.4em;
    border: 0px;
    padding-left: 0px !important;
  }
  .up-action {
    position: fixed;
    bottom: 5em;
    right: 1em;
    z-index: 3;
  }
}
</style>
