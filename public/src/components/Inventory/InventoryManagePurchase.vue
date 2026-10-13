<template>
  <div
    class="tab-pane"
    id="inventory-purchase-panel"
    aria-labelledby="Inventory Purchase Panel"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="purchase-initial-section">
      <div class="row match-height">
        <div class="col-md-12 col-12">
          <div class="card shadow-none mb-0">
            <div class="card-content">
              <div class="card-body pt-0">
                <form class="form form-horizontal webapp-form">
                  <div class="form-body">
                    <div class="row">
                      <div class="col-md-12 col-12">
                        <div class="m-width-s">
                          <div class="row">
                            <div class="col-md-4">
                              <label>Purchased On</label>
                            </div>
                            <div class="col-md-4 form-group">
                              <fieldset class="has-icon-left">
                                <date-picker
                                  v-model="form.purchase_date"
                                  :format="'MM/DD/YYYY'"
                                  :value-type="'MM/DD/YYYY'"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'purchase_date',
                                    ),
                                  }"
                                  @change="delete errors.purchase_date"
                                  :title="errors.purchase_date"
                                  :clearable="false"
                                ></date-picker>
                              </fieldset>
                            </div>
                            <div
                              class="col-md-4"
                              v-show="inventory_purchase_id"
                            >
                              <a
                                :href="parseAttachmentUrl(form.url)"
                                class="btn btn-secondary p-50 text-uppercase"
                                target="_blank"
                                v-show="showAttachment(form.url)"
                                rel="noopener noreferrer"
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                "
                              >
                                View File
                              </a>
                              <button
                                @click.prevent="
                                  deleteAttachmentConfirmation(form.url)
                                "
                                class="btn btn-danger text-uppercase"
                                target="_blank"
                                v-show="showAttachment(form.url)"
                                rel="noopener noreferrer"
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                "
                              >
                                <i class="bx bx-folder-minus"></i>
                              </button>
                              <label
                                class="btn btn-secondary p-50 text-uppercase"
                                v-show="!showAttachment(form.url)"
                                :for="
                                  `inventory_expense_attachment_${inventory_purchase_id}`
                                "
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                  color: #fff;
                                "
                              >
                                Upload File
                              </label>
                              <input
                                :id="
                                  `inventory_expense_attachment_${inventory_purchase_id}`
                                "
                                class="d-none"
                                type="file"
                                ref="attachment"
                                @change="
                                  savePurchaseAttachment(
                                    $event,
                                    inventory_purchase_id,
                                  )
                                "
                              />
                            </div>
                            <div class="w-100"></div>

                            <div class="col-md-3">
                              <label>Plate No</label>
                            </div>
                            <div class="col-md-3 form-group">
                              <fieldset>
                                <input
                                  type="text"
                                  placeholder="Plate No"
                                  v-model="form.plate_number"
                                  class="form-control"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'plate_number',
                                    ),
                                  }"
                                  @change="delete errors.plate_number"
                                  :title="errors.plate_number"
                                />
                              </fieldset>
                            </div>
                            <div class="col-md-3">
                              <label>No of Keys</label>
                            </div>
                            <div class="col-md-3 form-group">
                              <fieldset>
                                <input
                                  type="text"
                                  placeholder="Key Count"
                                  v-model="form.key_count"
                                  class="form-control"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'key_count',
                                    ),
                                  }"
                                  @change="delete errors.key_count"
                                  :title="errors.key_count"
                                />
                              </fieldset>
                            </div>
                            <div class="col-md-4">
                              <label>Bought from Public or Company?</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <div class="custom-control custom-switch">
                                <input
                                  type="checkbox"
                                  id="switchForPubOrCompanyFlag"
                                  v-model="form.purchase_from_flag"
                                  name="purchase_from_flag"
                                  true-value="1"
                                  false-value="0"
                                  checked="checked"
                                  class="custom-control-input"
                                />
                                <label
                                  for="switchForPubOrCompanyFlag"
                                  class="custom-control-label"
                                >
                                  <span class="switch-icon-left">COMP</span>
                                  <span class="switch-icon-right">PUB</span>
                                </label>
                              </div>
                            </div>
                            <div class="col-md-12 col-12">
                              <p class="alert alert-light mb-0">
                                Vendor information in the section below doesn't
                                affect the Vendor information saved in Vendor
                                module.
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12 col-12">
                        <div class="m-width-s">
                          <div class="row">
                            <div class="col-md-6 form-group">
                              <v-select
                                append-to-body
                                placeholder="Vendors List"
                                label="company_name"
                                :reduce="(company_name) => company_name.id"
                                v-model="selected_company"
                                clearable
                                :options="allVendors"
                              ></v-select>
                            </div>
                            <div class="col-md-3">
                              <router-link
                                tag="button"
                                :to="{ name: 'vendor-create' }"
                                class="btn btn-info"
                                style="vertical-align: text-top"
                                exact
                              >
                                Add New Vendor
                              </router-link>
                            </div>
                            <div class="col-md-3">
                              <button
                                @click="resetFormValues"
                                style="vertical-align: text-top"
                                type="button"
                                class="btn btn-secondary"
                              >
                                Reset Vendor Details
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12 col-12">
                        <div class="m-width-s">
                          <div class="row">
                            <h3 class="col-xs-12">
                              Who did you purchase this vehicle from ?
                            </h3>
                            <h5
                              class="col-xs-12 text-danger"
                              style="font-size: 0.8em"
                            >
                              Note : This information is visible only to the
                              owner and managers. It is not shared with public
                              nor with staff.
                            </h5>
                            <div class="w-100"></div>
                            <div class="col-md-4 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Company Name"
                                  v-model="form.company_name"
                                  class="form-control"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'company_name',
                                    ),
                                  }"
                                  @change="delete errors.company_name"
                                  :title="errors.company_name"
                                />
                                <div class="form-control-position">
                                  <i class="bx bxs-factory font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div
                              class="col-md-4 col-xs-12 form-group"
                              v-if="parseInt(form.purchase_from_flag) == 1"
                            >
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="MVDA #"
                                  v-model="form.mvda"
                                  class="form-control"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty('mvda'),
                                  }"
                                  @change="delete errors.mvda"
                                  :title="errors.mvda"
                                />
                                <div class="form-control-position">
                                  <i
                                    class="bx bxs-spreadsheet font-medium-3"
                                  ></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="w-100"></div>
                            <div class="col-md-4 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="First Name *"
                                  v-model="form.first_name"
                                  class="form-control"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'first_name',
                                    ),
                                  }"
                                  @change="delete errors.first_name"
                                  :title="errors.first_name"
                                />
                                <div class="form-control-position">
                                  <i class="bx bxs-user font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Last Name"
                                  v-model="form.last_name"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'last_name',
                                    ),
                                  }"
                                  @change="delete errors.last_name"
                                  :title="errors.last_name"
                                  class="form-control"
                                />
                                <!-- <div class="form-control-position">
                                  <i class="bx bxs-user font-medium-3"></i>
                                </div> -->
                              </fieldset>
                            </div>
                            <div
                              class="col-md-4 col-xs-12 form-group"
                              v-if="parseInt(form.purchase_from_flag) == 1"
                            >
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Salesperson Reg #"
                                  v-model="form.sales_person_registration"
                                  class="form-control"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'sales_person_registration',
                                    ),
                                  }"
                                  @change="
                                    delete errors.sales_person_registration
                                  "
                                  :title="errors.sales_person_registration"
                                />
                                <div class="form-control-position">
                                  <i
                                    class="bx bxs-spreadsheet font-medium-3"
                                  ></i>
                                </div>
                              </fieldset>
                            </div>
                            <!-- show option if public -->
                            <div
                              class="col-md-2"
                              v-show="parseInt(form.purchase_from_flag) == 0"
                            >
                              <label>DL or RIN</label>
                            </div>
                            <!-- show option if public -->
                            <div
                              class="col-md-2 form-group"
                              v-show="parseInt(form.purchase_from_flag) == 0"
                            >
                              <div class="custom-control custom-switch">
                                <input
                                  type="checkbox"
                                  id="dlOrRlSwitch"
                                  v-model="form.rin_or_dl_flag"
                                  name="rin_or_dl_flag"
                                  true-value="1"
                                  false-value="0"
                                  checked="checked"
                                  class="custom-control-input"
                                />
                                <label
                                  for="dlOrRlSwitch"
                                  class="custom-control-label"
                                >
                                  <span class="switch-icon-left">DL</span>
                                  <span class="switch-icon-right">RIN</span>
                                </label>
                              </div>
                            </div>
                            <div
                              class="col-md-6 col-xs-12 form-group"
                              v-if="parseInt(form.rin_or_dl_flag) == 1"
                            >
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Driving License"
                                  v-model="form.driver_licence"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'driver_licence',
                                    ),
                                  }"
                                  @change="delete errors.driver_licence"
                                  :title="errors.driver_licence"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bx-id-card font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-6 col-xs-12 form-group" v-else>
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="RIN"
                                  v-model="form.rin"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty('rin'),
                                  }"
                                  @change="delete errors.rin"
                                  :title="errors.rin"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bx-id-card font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>

                            <div class="col-md-6 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="TAX"
                                  v-model="form.tax_number"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'tax_number',
                                    ),
                                  }"
                                  @change="delete errors.tax_number"
                                  :title="errors.tax_number"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bx-hash font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-6 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Street Address"
                                  v-model="form.street_address"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'street_address',
                                    ),
                                  }"
                                  @change="delete errors.street_address"
                                  :title="errors.street_address"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bx-directions font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-6 col-xs-12 form-group">
                              <fieldset>
                                <input
                                  type="text"
                                  placeholder="APT No / Suite"
                                  v-model="form.apt"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty('apt'),
                                  }"
                                  @change="delete errors.apt"
                                  :title="errors.apt"
                                  class="form-control"
                                />
                              </fieldset>
                            </div>
                            <div class="col-md-3 col-xs-12 form-group">
                              <fieldset>
                                <input
                                  type="text"
                                  placeholder="City"
                                  v-model="form.city"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty('city'),
                                  }"
                                  @change="delete errors.city"
                                  :title="errors.city"
                                  class="form-control"
                                />
                              </fieldset>
                            </div>
                            <div class="col-md-3 col-xs-12 form-group">
                              <fieldset>
                                <input
                                  type="text"
                                  placeholder="Province"
                                  v-model="form.province"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'province',
                                    ),
                                  }"
                                  @change="delete errors.province"
                                  :title="errors.province"
                                  class="form-control"
                                />
                              </fieldset>
                            </div>
                            <div class="col-md-3 col-xs-12 form-group">
                              <fieldset>
                                <input
                                  type="text"
                                  placeholder="Country"
                                  v-model="form.country"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'country',
                                    ),
                                  }"
                                  @change="delete errors.country"
                                  :title="errors.country"
                                  class="form-control"
                                />
                              </fieldset>
                            </div>
                            <div class="col-md-3 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Postal Code"
                                  v-model="form.postal_code"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'postal_code',
                                    ),
                                  }"
                                  @change="delete errors.postal_code"
                                  :title="errors.postal_code"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bxs-map font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-3 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Phone"
                                  v-model="form.phone"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'phone',
                                    ),
                                  }"
                                  @change="delete errors.phone"
                                  :title="errors.phone"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bx-phone font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-3 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Mobile"
                                  v-model="form.mobile"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'mobile',
                                    ),
                                  }"
                                  @change="delete errors.mobile"
                                  :title="errors.mobile"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bxs-mobile font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-3 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Fax"
                                  v-model="form.fax"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty('fax'),
                                  }"
                                  @change="delete errors.fax"
                                  :title="errors.fax"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bx-printer font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-3 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Email"
                                  v-model="form.email"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'email',
                                    ),
                                  }"
                                  @change="delete errors.email"
                                  :title="errors.email"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bxs-envelope font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- section for taxes : START -->
                      <div class="col-md-12 col-12">
                        <div class="m-width-s">
                          <div class="row">
                            <div class="col-md-3">
                              <div class="col-xs-12">
                                <label for="price">Purchase Price</label>
                              </div>
                              <div
                                class="col-xs-12 form-group"
                                style="position: relative"
                              >
                                <fieldset class="has-icon-left">
                                  <input
                                    type="text"
                                    placeholder="Purchase Price"
                                    class="form-control"
                                    id="price"
                                    v-model="form.price"
                                    :class="{
                                      'is-invalid': errors.hasOwnProperty(
                                        'price',
                                      ),
                                    }"
                                    @change="delete errors.price"
                                    :title="errors.price"
                                  />
                                  <div class="form-control-position">
                                    <i
                                      class="bx bxs-dollar-circle font-medium-3"
                                    ></i>
                                  </div>
                                </fieldset>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="col-xs-12">
                                <label for="discount">Discount</label>
                              </div>
                              <div
                                class="col-xs-12 form-group"
                                style="position: relative"
                              >
                                <fieldset class="has-icon-left">
                                  <input
                                    type="text"
                                    placeholder="Discount Price"
                                    class="form-control"
                                    id="discount"
                                    v-model="form.discount"
                                    :class="{
                                      'is-invalid': errors.hasOwnProperty(
                                        'discount',
                                      ),
                                    }"
                                    @change="delete errors.discount"
                                    :title="errors.discount"
                                  />
                                  <div class="form-control-position">
                                    <i
                                      class="bx bxs-discount font-medium-3"
                                    ></i>
                                  </div>
                                </fieldset>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="col-xs-12">
                                <label for="tax_type">Tax Type</label>
                              </div>
                              <div
                                class="col-xs-12 form-group"
                                style="position: relative"
                              >
                                <select
                                  id="tax_type"
                                  class="custom-select"
                                  v-model="form.tax_type"
                                  name="tax_type"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'tax_type',
                                    ),
                                  }"
                                  @change="delete errors.tax_type"
                                  :title="errors.tax_type"
                                >
                                  <option value="" selected disabled>
                                    Tax Type
                                  </option>
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
                            </div>
                            <!-- secondary tax type : START -->
                            <div class="col-md-3">
                              <div class="col-xs-12">
                                <label for="tax_type_secondary">Tax Type</label>
                              </div>
                              <div
                                class="col-xs-12 form-group"
                                style="position: relative"
                              >
                                <select
                                  id="tax_type_secondary"
                                  class="custom-select"
                                  v-model="form.tax_type_secondary"
                                  :disabled="!enableTaxEditing"
                                  name="tax_type_secondary"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'tax_type_secondary',
                                    ),
                                  }"
                                  @change="delete errors.tax_type_secondary"
                                  :title="errors.tax_type_secondary"
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
                            </div>
                            <!-- secondary tax type : END -->
                            <div class="col-md-3">
                              <div class="col-xs-12">
                                <label for="tax_amount_total">Tax Amount</label>
                              </div>
                              <div
                                class="col-xs-12 form-group"
                                style="position: relative"
                              >
                                <fieldset class="has-icon-left">
                                  <input
                                    type="text"
                                    placeholder="Tax Amount"
                                    class="form-control"
                                    id="tax_amount_total"
                                    v-model="form.tax_amount_total"
                                    :readonly="enableTaxEditing"
                                    :class="{
                                      'is-invalid': errors.hasOwnProperty(
                                        'tax_amount_total',
                                      ),
                                    }"
                                    @change="delete errors.tax_amount_total"
                                    :title="errors.tax_amount_total"
                                  />
                                  <div class="form-control-position">
                                    <i
                                      class="bx bxs-dollar-circle font-medium-3"
                                    ></i>
                                  </div>
                                </fieldset>
                              </div>
                            </div>
                            <!--Total Amount : START -->
                            <div class="col-md-3">
                              <div class="col-xs-12">
                                <label for="total_purchase_amount"
                                  >Total Amount</label
                                >
                              </div>
                              <div
                                class="col-xs-12 form-group"
                                style="position: relative"
                              >
                                <fieldset class="has-icon-left">
                                  <input
                                    type="text"
                                    placeholder="Total Amount"
                                    class="form-control"
                                    id="total_purchase_amount"
                                    v-model="sub_total"
                                    disabled
                                  />
                                  <div class="form-control-position">
                                    <i
                                      class="bx bxs-dollar-circle font-medium-3"
                                    ></i>
                                  </div>
                                </fieldset>
                              </div>
                            </div>
                            <!--Total Amount : END-->
                          </div>
                        </div>
                      </div>
                      <!-- section for taxes : END -->
                      <!-- additional information about transfer : START -->
                      <!-- section for taxes : START -->
                      <div class="col-md-12 col-12">
                        <div class="m-width-s">
                          <div class="row">
                            <!-- Payment : START -->
                            <div class="col-md-4">
                              <label>Has been paid for?</label>
                            </div>
                            <div class="col-md-2 form-group">
                              <div class="custom-control custom-switch">
                                <input
                                  type="checkbox"
                                  id="switchForThePaidFlag"
                                  v-model="form.has_been_paid_for"
                                  name="has_been_paid_for"
                                  true-value="1"
                                  false-value="0"
                                  checked="checked"
                                  class="custom-control-input"
                                />
                                <label
                                  for="switchForThePaidFlag"
                                  class="custom-control-label"
                                >
                                  <span class="switch-icon-left">YES</span>
                                  <span class="switch-icon-right">NO</span>
                                </label>
                              </div>
                            </div>
                            <div
                              v-show="form.has_been_paid_for == '1'"
                              class="col-md-3 form-group"
                            >
                              <fieldset class="has-icon-left">
                                <date-picker
                                  v-model="form.payment_date"
                                  :format="'MM/DD/YYYY'"
                                  :value-type="'MM/DD/YYYY'"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'payment_date',
                                    ),
                                  }"
                                  @change="delete errors.payment_date"
                                  :title="errors.payment_date"
                                ></date-picker>
                              </fieldset>
                            </div>
                            <!-- Has been paid receipt : START -->
                            <div
                              class="col-md-3"
                              v-show="form.has_been_paid_for == '1'"
                            >
                              <a
                                :href="
                                  parseAttachmentUrl(form.purchase_receipt_url)
                                "
                                class="btn btn-secondary p-50 text-uppercase"
                                target="_blank"
                                v-show="
                                  showAttachment(form.purchase_receipt_url)
                                "
                                rel="noopener noreferrer"
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                "
                              >
                                View File
                              </a>
                              <button
                                @click.prevent="
                                  deleteAttachmentConfirmation(
                                    form.purchase_receipt_url,
                                  )
                                "
                                class="btn btn-danger text-uppercase"
                                target="_blank"
                                v-show="
                                  showAttachment(form.purchase_receipt_url)
                                "
                                rel="noopener noreferrer"
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                "
                              >
                                <i class="bx bx-folder-minus"></i>
                              </button>
                              <label
                                class="btn btn-secondary p-50 text-uppercase"
                                v-show="
                                  !showAttachment(form.purchase_receipt_url)
                                "
                                :for="
                                  `inventory_expense_attachment_purchase_receipt_url`
                                "
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                  color: #fff;
                                "
                              >
                                Upload File
                              </label>
                              <input
                                :id="
                                  `inventory_expense_attachment_purchase_receipt_url`
                                "
                                class="d-none"
                                type="file"
                                ref="attachment"
                                @change="
                                  savePurchaseAttachment(
                                    $event,
                                    inventory_purchase_id,
                                    'PURCHASE_RECEIPT',
                                  )
                                "
                              />
                            </div>
                            <!-- Has been paid receipt : END -->
                            <!-- Payment : END -->
                            <div class="w-100"></div>
                            <!-- Title Received : START -->
                            <div class="col-md-4">
                              <label>Title has been received?</label>
                            </div>
                            <div class="col-md-2 form-group">
                              <div class="custom-control custom-switch">
                                <input
                                  type="checkbox"
                                  id="switchForHasTitleBeenReceived"
                                  v-model="form.has_title_been_received"
                                  name="has_title_been_received"
                                  true-value="1"
                                  false-value="0"
                                  checked="checked"
                                  class="custom-control-input"
                                />
                                <label
                                  for="switchForHasTitleBeenReceived"
                                  class="custom-control-label"
                                >
                                  <span class="switch-icon-left">YES</span>
                                  <span class="switch-icon-right">NO</span>
                                </label>
                              </div>
                            </div>
                            <div
                              v-show="form.has_title_been_received == '1'"
                              class="col-md-3 form-group"
                            >
                              <fieldset class="has-icon-left">
                                <date-picker
                                  v-model="form.title_received_date"
                                  :format="'MM/DD/YYYY'"
                                  :value-type="'MM/DD/YYYY'"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'title_received_date',
                                    ),
                                  }"
                                  @change="delete errors.title_received_date"
                                  :title="errors.title_received_date"
                                ></date-picker>
                              </fieldset>
                            </div>
                            <!-- Received Vehicle Title : START -->
                            <div
                              class="col-md-3"
                              v-show="form.has_title_been_received == '1'"
                            >
                              <a
                                :href="
                                  parseAttachmentUrl(form.ownership_title_url)
                                "
                                class="btn btn-secondary p-50 text-uppercase"
                                target="_blank"
                                v-show="
                                  showAttachment(form.ownership_title_url)
                                "
                                rel="noopener noreferrer"
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                "
                              >
                                View File
                              </a>
                              <button
                                @click.prevent="
                                  deleteAttachmentConfirmation(
                                    form.ownership_title_url,
                                  )
                                "
                                class="btn btn-danger text-uppercase"
                                target="_blank"
                                v-show="
                                  showAttachment(form.ownership_title_url)
                                "
                                rel="noopener noreferrer"
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                "
                              >
                                <i class="bx bx-folder-minus"></i>
                              </button>
                              <label
                                class="btn btn-secondary p-50 text-uppercase"
                                v-show="
                                  !showAttachment(form.ownership_title_url)
                                "
                                :for="
                                  `inventory_expense_attachment_ownership_title_url`
                                "
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                  color: #fff;
                                "
                              >
                                Upload File
                              </label>
                              <input
                                :id="
                                  `inventory_expense_attachment_ownership_title_url`
                                "
                                class="d-none"
                                type="file"
                                ref="attachment"
                                @change="
                                  savePurchaseAttachment(
                                    $event,
                                    inventory_purchase_id,
                                    'OWNERSHIP_TITLE',
                                  )
                                "
                              />
                            </div>
                            <!-- Received Vehicle Title : END -->
                            <!-- Title Received : END -->
                            <div class="w-100"></div>
                            <!-- Title Transfer : START -->
                            <div class="col-md-4">
                              <label>Title has been transferred?</label>
                            </div>
                            <div class="col-md-2 form-group">
                              <div class="custom-control custom-switch">
                                <input
                                  type="checkbox"
                                  id="switchForHasTitleBeenTransferred"
                                  v-model="form.has_title_been_transferred"
                                  name="has_title_been_transferred"
                                  true-value="1"
                                  false-value="0"
                                  checked="checked"
                                  class="custom-control-input"
                                />
                                <label
                                  for="switchForHasTitleBeenTransferred"
                                  class="custom-control-label"
                                >
                                  <span class="switch-icon-left">YES</span>
                                  <span class="switch-icon-right">NO</span>
                                </label>
                              </div>
                            </div>
                            <div class="w-100"></div>
                            <!-- Permit Field : START -->
                            <div
                                v-show="form.has_title_been_transferred == '1'"
                                class="col-md-3 form-group"
                            >
                              <fieldset>
                                <input
                                    type="text"
                                    placeholder="Permit Number"
                                    v-model="form.permit_number"
                                    :class="{
                                    'is-invalid': errors.hasOwnProperty('permit_number'),
                                  }"
                                    @change="delete errors.permit_number"
                                    :title="errors.permit_number"
                                    class="form-control"
                                />
                              </fieldset>
                            </div>
                            <!-- Permit Field : END -->
                            <div
                              v-show="form.has_title_been_transferred == '1'"
                              class="col-md-3 form-group"
                            >
                              <fieldset class="has-icon-left">
                                <date-picker
                                  v-model="form.title_transfer_date"
                                  :format="'MM/DD/YYYY'"
                                  :value-type="'MM/DD/YYYY'"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'title_transfer_date',
                                    ),
                                  }"
                                  @change="delete errors.title_transfer_date"
                                  :title="errors.title_transfer_date"
                                ></date-picker>
                              </fieldset>
                            </div>
                            <!-- TITLE TRANSFER FILE : START -->
                            <div
                              class="col-md-3"
                              v-show="form.has_title_been_transferred == '1'"
                            >
                              <a
                                :href="
                                  parseAttachmentUrl(
                                    form.title_transfer_proof_url,
                                  )
                                "
                                class="btn btn-secondary p-50 text-uppercase"
                                target="_blank"
                                v-show="
                                  showAttachment(form.title_transfer_proof_url)
                                "
                                rel="noopener noreferrer"
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                "
                              >
                                View File
                              </a>
                              <button
                                @click.prevent="
                                  deleteAttachmentConfirmation(
                                    form.title_transfer_proof_url,
                                  )
                                "
                                class="btn btn-danger text-uppercase"
                                target="_blank"
                                v-show="
                                  showAttachment(form.title_transfer_proof_url)
                                "
                                rel="noopener noreferrer"
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                "
                              >
                                <i class="bx bx-folder-minus"></i>
                              </button>
                              <label
                                class="btn btn-secondary p-50 text-uppercase"
                                v-show="
                                  !showAttachment(form.title_transfer_proof_url)
                                "
                                :for="
                                  `inventory_expense_attachment_title_transfer_proof_url`
                                "
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                  color: #fff;
                                "
                              >
                                Upload File
                              </label>
                              <input
                                :id="
                                  `inventory_expense_attachment_title_transfer_proof_url`
                                "
                                class="d-none"
                                type="file"
                                ref="attachment"
                                @change="
                                  savePurchaseAttachment(
                                    $event,
                                    inventory_purchase_id,
                                    'TITLE_TRANSFER_PROOF',
                                  )
                                "
                              />
                            </div>
                            <!-- TITLE TRANSFER FILE : END -->
                            <!-- Title Transfer : END -->
                            <div class="w-100"></div>
                            <!-- Has Lien : START -->
                            <div class="col-md-3">
                              <label>Has Lien?</label>
                            </div>
                            <div class="col-md-3 form-group">
                              <select
                                id="has_lien"
                                class="custom-select"
                                v-model="form.has_lien"
                                name="has_lien"
                                :class="{
                                  'is-invalid': errors.hasOwnProperty(
                                    'has_lien',
                                  ),
                                }"
                                @change="delete errors.has_lien"
                                :title="errors.has_lien"
                              >
                                <option value="0">Not Selected</option>
                                <option value="2">NO</option>
                                <option value="1">YES</option>
                              </select>
                            </div>
                            <div
                              class="col-md-3 col-xs-12 form-group"
                              v-show="form.has_lien == '1'"
                            >
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Lien Amount"
                                  v-model="form.lien_amount"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'lien_amount',
                                    ),
                                  }"
                                  @change="delete errors.lien_amount"
                                  :title="errors.lien_amount"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i
                                    class="bx bxs-dollar-circle font-medium-3"
                                  ></i>
                                </div>
                              </fieldset>
                            </div>
                            <div
                              class="col-md-3 col-xs-12 form-group"
                              v-show="form.has_lien == '1'"
                            >
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Lien Holder Name"
                                  v-model="form.lien_holder_name"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'lien_holder_name',
                                    ),
                                  }"
                                  @change="delete errors.lien_holder_name"
                                  :title="errors.lien_holder_name"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bxs-bank font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <!-- Has Lien : END -->
                            <div class="w-100"></div>
                            <!-- Has Lien Been Paid : START -->
                            <div class="col-md-3">
                              <label>Has Lien Been Paid?</label>
                            </div>
                            <div class="col-md-3 form-group">
                              <select
                                id="lien_has_been_paid"
                                class="custom-select"
                                v-model="form.lien_has_been_paid"
                                name="lien_has_been_paid"
                                :class="{
                                  'is-invalid': errors.hasOwnProperty(
                                    'lien_has_been_paid',
                                  ),
                                }"
                                @change="delete errors.lien_has_been_paid"
                                :title="errors.lien_has_been_paid"
                              >
                                <option value="0">Not Selected</option>
                                <option value="2">NO</option>
                                <option value="1">YES</option>
                              </select>
                            </div>
                            <div
                              v-show="form.lien_has_been_paid == '1'"
                              class="col-md-3 form-group"
                            >
                              <fieldset class="has-icon-left">
                                <date-picker
                                  v-model="form.lien_paid_date"
                                  :format="'MM/DD/YYYY'"
                                  :value-type="'MM/DD/YYYY'"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'lien_paid_date',
                                    ),
                                  }"
                                  @change="delete errors.lien_paid_date"
                                  :title="errors.lien_paid_date"
                                ></date-picker>
                              </fieldset>
                            </div>
                            <div
                              class="col-md-3"
                              v-show="form.lien_has_been_paid == '1'"
                            >
                              <a
                                :href="
                                  parseAttachmentUrl(form.lien_paid_proof_url)
                                "
                                class="btn btn-secondary p-50 text-uppercase"
                                target="_blank"
                                v-show="
                                  showAttachment(form.lien_paid_proof_url)
                                "
                                rel="noopener noreferrer"
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                "
                              >
                                View File
                              </a>
                              <button
                                @click.prevent="
                                  deleteAttachmentConfirmation(
                                    form.lien_paid_proof_url,
                                  )
                                "
                                class="btn btn-danger text-uppercase"
                                target="_blank"
                                v-show="
                                  showAttachment(form.lien_paid_proof_url)
                                "
                                rel="noopener noreferrer"
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                "
                              >
                                <i class="bx bx-folder-minus"></i>
                              </button>
                              <label
                                class="btn btn-secondary p-50 text-uppercase"
                                v-show="
                                  !showAttachment(form.lien_paid_proof_url)
                                "
                                :for="
                                  `inventory_expense_attachment_lien_paid_proof_url`
                                "
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                  cursor: pointer;
                                  color: #fff;
                                "
                              >
                                Upload File
                              </label>
                              <input
                                :id="
                                  `inventory_expense_attachment_lien_paid_proof_url`
                                "
                                class="d-none"
                                type="file"
                                ref="attachment"
                                @change="
                                  savePurchaseAttachment(
                                    $event,
                                    inventory_purchase_id,
                                    'LIEN_PAID_PROOF',
                                  )
                                "
                              />
                            </div>
                            <!-- Has Lien Been Paid : END-->
                          </div>
                        </div>
                      </div>
                      <!-- additional information about transfer : END -->
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Initial Section END -->
  </div>
</template>
<script>
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import vSelect from "vue-select";
import { mapGetters, mapActions } from "vuex";

import Toast from "../../library/Toast";

export default {
  name: "InventoryManagePurchase",
  components: {
    "v-select": vSelect,
    DatePicker,
  },
  watch: {
    businessTaxSettings: function(newVal, oldVal) {
      if (this.load == true && this.inventory_purchase_id == null) {
        let selected_tax = this.businessTaxSettings.filter(
          (tax) => tax.default == "1",
        );

        console.log(selected_tax);
        if (selected_tax.length > 0) {
          this.form.tax_type = selected_tax[0].name;

          if (selected_tax.length > 1) {
            this.form.tax_type_secondary = selected_tax[1].name;
          }
        }
      }

      this.load = false;
    },
    listenToCalculatorChanges: function(newVal, oldVal) {
      Event.fire("change_inventory_purchase", this.form);
    },
    calculateSubTotal: function(newVal, oldVal) {
      console.log("hello world");
    },
    "form.tax_type": function(newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal,
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.form.tax_percentage = "";
        } else {
          this.form.tax_percentage = selected_tax[0].value;
        }
      } else {
        this.form.tax_percentage = "";
      }
    },
    "form.tax_type_secondary": function(newVal, oldVal) {
      //filter the tax name with corresponding tax rate
      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.name == newVal,
      );

      //check for nan if it is a number than set the tax_percentage else set it as empty string
      if (selected_tax.length > 0) {
        if (isNaN(selected_tax[0].value)) {
          this.form.tax_percentage_secondary = "";
        } else {
          this.form.tax_percentage_secondary = selected_tax[0].value;
        }
      } else {
        this.form.tax_percentage_secondary = "";
      }
    },
    "form.purchase_from_flag": function(newVal, oldVal) {
      if (parseInt(newVal) == 1) {
        this.form.rin_or_dl_flag = 0;
        this.form.driver_licence = "";
      } else if (parseInt(newVal) == 0) {
        this.form.rin_or_dl_flag = 1;
        this.form.rin = "";
      }
    },
    selected_company: function(newVal, oldVal) {
      if (newVal != oldVal) {
        if (!isNaN(newVal) && newVal != "null") {
          this.getVendor(newVal);
        }
      }
    },
  },
  data() {
    return {
      errors: [],
      selected_company: "",
      inventory_purchase_data_available: false,
      inventory_purchase_id: null,
      load: true,
      form: {
        purchase_date: "",
        purchase_from_flag: 1,
        company_name: "",
        first_name: "",
        last_name: "",
        rin: "",
        tax_number: "",
        street_address: "",
        apt: "",
        city: "",
        province: "",
        postal_code: "",
        country: "",
        phone: "",
        fax: "",
        mobile: "",
        email: "",
        driver_licence: "",
        plate_number: "",
        key_count: "",
        rin_or_dl_flag: 0,
        tax_override_flag: 0,
        price: 0,
        discount: 0,
        tax_amount: 0,
        tax_percentage: 0,
        tax_type: "",
        tax_type_secondary: "",
        tax_amount_secondary: 0,
        tax_percentage_secondary: 0,
        tax_amount_total: 0,
        mvda: "",
        sales_person_registration: "",
        url: "",
        has_been_paid_for: 0,
        has_title_been_received: 0,
        has_title_been_transferred: 0,
        payment_date: null,
        title_transfer_date: null,
        title_received_date: null,
        has_lien: 0,
        lien_amount: 0,
        lien_holder_name: "",
        lien_has_been_paid: 0,
        lien_paid_date: null,
        purchase_receipt_url: "",
        ownership_title_url: "",
        title_transfer_proof_url: "",
        lien_paid_proof_url: "",
        permit_number: ""
      },
      sub_total: 0,
    };
  },
  computed: {
    ...mapGetters("configuration", ["businessTaxSettings"]),
    ...mapGetters("vendor", ["allVendors"]),
    updateView() {
      return this.$route.params.id && this.$route.name == "inventory-update";
    },
    enableTaxEditing: {
      // This means amount is valid number, enable readonly mode else not.
      get: function() {
        let flag =
          !isNaN(this.form.tax_percentage) && this.form.tax_percentage != ""
            ? true
            : false;
        if (!flag && !this.load) {
          this.form.tax_type_secondary = "";
        }
        return flag;
      },
    },
    listenToCalculatorChanges() {
      return (
        parseFloat(this.form.price) +
        parseFloat(this.form.discount) +
        parseFloat(this.form.tax_percentage) +
        parseFloat(this.form.tax_amount)
      );
    },
    calculateSubTotal: {
      get: function() {
        let price =
          !isNaN(this.form.price) && this.form.price != ""
            ? this.form.price
            : 0;
        this.form.price = price;

        price = parseFloat(price);
        let quantity = 1;
        this.form.quantity = quantity;
        let discount =
          !isNaN(this.form.discount) && this.form.discount != ""
            ? this.form.discount
            : 0;
        this.form.discount = discount;

        discount = parseFloat(discount);

        let sub_total = price * quantity;
        sub_total -= discount;

        let tax =
          !isNaN(this.form.tax_percentage) && this.form.tax_percentage != ""
            ? sub_total * parseFloat(this.form.tax_percentage) * 0.01
            : parseFloat(this.form.tax_amount);

        let tax_secondary =
          !isNaN(this.form.tax_percentage_secondary) &&
          this.form.tax_percentage_secondary != ""
            ? sub_total * parseFloat(this.form.tax_percentage_secondary) * 0.01
            : parseFloat(0);

        tax = !isNaN(tax) ? tax : 0;
        this.form.tax_amount = this.$helpers.round(tax, 3);

        tax_secondary = !isNaN(tax_secondary) ? tax_secondary : 0;
        this.form.tax_amount_secondary = this.$helpers.round(tax_secondary, 3);

        tax += tax_secondary;

        if (this.form.tax_type == "Other") {
          this.form.tax_amount = 0;
          this.form.tax_amount_secondary = 0;

          tax =
            !isNaN(this.form.tax_amount_total) &&
            this.form.tax_amount_total != ""
              ? this.form.tax_amount_total
              : 0;

          this.form.tax_amount_total = tax;
        } else {
          this.form.tax_amount_total = this.$helpers.round(tax, 3);
        }

        tax = parseFloat(tax);
        sub_total += tax;

        this.sub_total = sub_total;
      },
    },
  },
  methods: {
    ...mapActions("configuration", ["getBusinessTaxSettings"]),
    ...mapActions("vendor", ["getAllVendors"]),
    parseAttachmentUrl(url) {
      return this.$helpers.parseAbsoluteOrRelativeUrl(window.global.cdn, url);
    },
    showAttachment(url) {
      try {
        return url.length > 0 ? true : false;
      } catch (error) {
        return false;
      }
    },
    async savePurchaseAttachment(event, id, sub_module = "PURCHASE") {
      const vm = this;
      let attachment = event.target.files[0];

      let formData = new FormData();
      formData.append("module", "INVENTORY");
      formData.append("sub_module", sub_module);
      formData.append("entity_id", id);

      formData.append("attachment", attachment, attachment.name);

      let title = "Saving the attachment..";
      const toast = new Toast(title);
      toast.fire();

      await axios({
        method: "post",
        url: `${window.location.origin}/api/business/attachments/create`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          title = "Attachment has been saved.";
          toast.success(title);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "inventory-update") {
            vm.getInventoryPurchaseData(vm.$route.params.id, true);
          }
        })
        .catch(function({ response }) {
          let errors = response.data;
          let error_string = ``;
          for (const error_key in errors) {
            error_string += ` ${errors[error_key]}`;
          }
          toast.fail(error_string);
          toast.close();
        });
    },
    async deletePurchaseAttachment(url) {
      const vm = this;

      let formData = new FormData();
      formData.append("url", url);

      let title = "Deleting the attachment..";
      const toast = new Toast(title);
      toast.fire();

      await axios({
        method: "post",
        url: `${window.location.origin}/api/business/attachments/delete`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          title = "Attachment has been deleted.";
          toast.success(title);
          toast.close();

          if (vm.$route.params.id && vm.$route.name == "inventory-update") {
            vm.getInventoryPurchaseData(vm.$route.params.id, true);
          }
        })
        .catch(function({ response }) {
          let errors = response.data;
          let error_string = ``;
          for (const error_key in errors) {
            error_string += ` ${errors[error_key]}`;
          }
          toast.fail(error_string);
          toast.close();
        });
    },
    async deleteAttachmentConfirmation(url) {
      const vm = this;
      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Confirm",
        toast: false,
        position: "center",
        text: "Please confirm if you want to delete the attachment.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          (async () => {
            await vm.deletePurchaseAttachment(url);
          })();
        }
      });
    },
    resetFormValues() {
      this.form.company_name = "";
      this.form.first_name = "";
      this.form.last_name = "";
      this.form.rin = "";
      this.form.tax_number = "";
      this.form.street_address = "";
      this.form.apt = "";
      this.form.city = "";
      this.form.province = "";
      this.form.postal_code = "";
      this.form.country = "";
      this.form.phone = "";
      this.form.fax = "";
      this.form.mobile = "";
      this.form.email = "";
      this.form.driver_licence = "";
      this.form.mvda = "";
      this.form.sales_person_registration = "";
      this.selected_company = "";
      this.errors = [];
      this.form.has_been_paid_for = this.form.has_title_been_received = this.form.has_title_been_transferred = 0;
      this.form.payment_date = this.form.title_transfer_date = this.form.title_received_date = null;
      this.form.has_lien = this.form.lien_amount = this.form.lien_has_been_paid = 0;
      this.form.lien_holder_name = "";
      this.form.permit_number = "";
      this.form.lien_paid_date = null;
    },
    getVendor(id) {
      const vm = this;
      axios({
        method: "get",
        url: window.location.origin + "/api/business/vendors/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.errors = [];
          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the vendor";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
          vm.resetFormValues();
        });
    },
    getInventoryPurchaseData(inventory_id, only_file_attributes = false) {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/inventory/" +
          inventory_id +
          "/purchase",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          if (data["id"] != null) {
            this.inventory_purchase_data_available = true;
          }

          for (const key in this.form) {
            if (only_file_attributes) {
              if (
                ![
                  "url",
                  "purchase_receipt_url",
                  "ownership_title_url",
                  "title_transfer_proof_url",
                  "lien_paid_proof_url",
                ].includes(key)
              ) {
                continue;
              }
            }
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }

          if (only_file_attributes) {
            return;
          }

          let purchase_date = moment(data.purchase_date);

          this.form.purchase_date = purchase_date.isValid()
            ? purchase_date.format("MM/DD/YYYY")
            : moment(new Date()).format("MM/DD/YYYY");

          let payment_date = moment(data.payment_date);

          this.form.payment_date = payment_date.isValid()
            ? payment_date.format("MM/DD/YYYY")
            : null;

          let title_transfer_date = moment(data.title_transfer_date);

          this.form.title_transfer_date = title_transfer_date.isValid()
            ? title_transfer_date.format("MM/DD/YYYY")
            : null;

          let title_received_date = moment(data.title_received_date);

          this.form.title_received_date = title_received_date.isValid()
            ? title_received_date.format("MM/DD/YYYY")
            : null;

          let lien_paid_date = moment(data.lien_paid_date);

          this.form.lien_paid_date = lien_paid_date.isValid()
            ? lien_paid_date.format("MM/DD/YYYY")
            : null;

          this.inventory_purchase_id = data["id"];
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    saveInventoryPurchaseData() {
      //save or update the purchase data
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && this.form[key] != null) {
          formData.append(key, this.form[key]);
        }
      }

      let request_url = window.location.origin;
      let update_request =
        !isNaN(this.inventory_purchase_id) &&
        this.inventory_purchase_id != null;

      //success message setting
      let success_message = update_request
        ? `Inventory purchase details updated.`
        : `Inventory purchase details added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/inventory/${this.$route.params.id}/purchase/update/${this.inventory_purchase_id}`
        : `${request_url}/api/business/inventory/${this.$route.params.id}/purchase`;

      //message during request
      let request_message = update_request
        ? `Updating inventory purchase details ...`
        : `Adding new inventory purchase details ...`;

      const toast = new Toast(request_message);
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
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
          vm.getInventoryPurchaseData(vm.$route.params.id);

          setTimeout(function() {
            Event.fire("save_complete", data);
          }, 2000);
        })
        .catch(function(error) {
          let update_request =
            !isNaN(vm.inventory_purchase_id) &&
            vm.inventory_purchase_id != null;

          let error_message = update_request
            ? "There were errors while updating the inventory purchase details."
            : "There were some errors while adding inventory purchase details.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();

          setTimeout(function() {
            Event.fire("save_complete", null);
          }, 2000);
        });
    },
  },
  created() {
    const vm = this;
    this.form.purchase_date = moment(new Date()).format("MM/DD/YYYY");
    this.getBusinessTaxSettings();
    this.getAllVendors();

    if (vm.updateView) {
      vm.getInventoryPurchaseData(this.$route.params.id);
    }
  },
  mounted() {
    const vm = this;

    Event.listen("save_inventory_purchase", (data) => {
      vm.saveInventoryPurchaseData();
      vm.$helpers.gTagClickEvent(
        vm,
        "zd_action",
        "inventory",
        "purchase",
        "save",
      );
    });
  },
  beforeDestroy() {
    Event.vue.$off("save_complete", null);
  },
};
</script>
<style lang="scss">
#purchase-initial-section .vdpComponent input {
  padding-left: 2.2em !important;
  line-height: 1.4;
  color: #475f7b;
  background-color: #ffffff;
  background-clip: padding-box;
  border: 1px solid #dfe3e7;
  border-radius: 0.267rem;
}

input:read-only {
  background: #eee !important;
}
</style>
