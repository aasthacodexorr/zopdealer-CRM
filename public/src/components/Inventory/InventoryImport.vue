<template>
  <div>
    <div
      class="app-content content fixed-height-block"
      style="overflow: visible"
    >
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <a
              class="nav-link d-flex align-items-center active"
              id="inventory-import-tab"
              data-toggle="tab"
              href="#inventory-import-panel"
              aria-controls="Inventory Import"
              role="tab"
              aria-selected="true"
            >
              <span class="">Import</span>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="">
        <div class="content-body mb-3">
          <div class="tab-content">
            <div
              class="tab-pane active"
              id="inventory-import-panel"
              aria-labelledby="Inventory Import Tab"
              role="tabpanel"
            >
              <div id="zd-toast-stack">
                <div
                  class="alert alert-secondary m-1"
                  role="alert"
                  :key="`saved_inventory_${saved_inventory.vin}`"
                  v-for="(saved_inventory,
                  current_inventory_index) in inventories"
                >
                  <div>
                    <b>
                      {{ saved_inventory.title }} - <br />
                      {{ saved_inventory.vin }}
                    </b>
                  </div>
                  <div class="text-center">
                    <i
                      class="bx bx-link-external pr-1"
                      style="cursor: pointer"
                      @click.prevent="
                        openInventoryNewTab(saved_inventory.inventory_id)
                      "
                    ></i>
                    <i
                      style="cursor: pointer"
                      class="bx bx-x-circle"
                      @click.prevent="
                        inventories.splice(current_inventory_index, 1)
                      "
                    ></i>
                  </div>
                </div>
              </div>
              <!-- Inventory Section : START -->
              <section id="inventory-import-in-section">
                <div class="row match-height">
                  <div class="col-12 col-xs-12">
                    <div class="card">
                      <div class="card-content">
                        <div class="card-body pb-1 mx-25">
                          <!-- Import Section : START -->
                          <div class="m-width-s">
                            <div class="card-header border-line-bottom-title">
                              <h5 class="card-title">PDF Import Section</h5>
                            </div>
                            <div class="row">
                              <!-- template source : START -->
                              <div class="col-xs-12 col-md-3">
                                <div class="form-group">
                                  <select
                                    id="template_source"
                                    class="custom-select"
                                    v-model="pdf_parser_form.source"
                                    name="source"
                                  >
                                    <option value="" selected>
                                      Select PDF Source
                                    </option>
                                    <option value="eblock">
                                      EBlock Invoices
                                    </option>
                                    <option value="openlane">
                                      Openlane Bill of Sale
                                    </option>
                                    <option value="adesa">
                                      Adesa Bill of Sale
                                    </option>
                                    <option value="traderev">TradeRev</option>
                                    <option value="iaa">
                                      Impact Auto Auctions
                                    </option>
                                    <option value="otolane">Otolane</option>
                                  </select>
                                </div>
                              </div>
                              <!-- template source : END -->
                              <!-- Auction As Seller : START -->
                              <div class="col-xs-12 col-md-3 form-group">
                                <div
                                  class="checkbox"
                                  style="vertical-align: sub"
                                >
                                  <input
                                    type="checkbox"
                                    class="checkbox-input"
                                    id="auction_as_seller"
                                    v-model="pdf_parser_form.auction_as_seller"
                                    :true-value="'1'"
                                    :false-value="'0'"
                                    @change="changeAuctionAsSeller"
                                  />
                                  <label for="auction_as_seller"
                                    >Auction As Seller</label
                                  >
                                </div>
                              </div>
                              <!-- Auction As Seller : END -->
                              <!-- file : START -->
                              <div
                                class="col-xs-12 col-md-3"
                                v-show="pdf_parser_form.source !== ``"
                              >
                                <div class="form-group">
                                  <div class="">
                                    <input
                                      type="file"
                                      ref="file"
                                      @change="saveFile($event)"
                                      class="form-control"
                                      id="file"
                                    />
                                  </div>
                                </div>
                              </div>
                              <!-- file : END-->
                              <div
                                class="col-xs-12 col-md-3"
                                v-show="
                                  pdf_parser_form.source !== `` &&
                                    pdf_parser_form.file !== null
                                "
                              >
                                <button
                                  class="btn btn-primary"
                                  @click="parsePDF()"
                                  type="button"
                                >
                                  Process
                                </button>
                              </div>
                              <div class="col-xs-12 col-md-12">
                                <a
                                  :href="
                                    parseAttachmentUrl(inventory_purchase.url)
                                  "
                                  v-show="
                                    inventory_purchase.url !== '' &&
                                      inventory_purchase.url !== null
                                  "
                                  class="btn btn-secondary p-50 text-uppercase"
                                  target="_blank"
                                  rel="noopener noreferrer"
                                  style="
                                    padding-right: 0.8em;
                                    padding-left: 0.8em;
                                    cursor: pointer;
                                  "
                                >
                                  View Uploaded File
                                </a>
                              </div>
                            </div>
                            <h6 class="text-danger">
                              Please check the parsed information for accuracy.
                              If Vehicle is bought from different province,
                              check for the Taxes.
                            </h6>
                          </div>
                          <!-- Import Section : END -->

                          <!-- vehicle information : START -->
                          <div class="m-width-s">
                            <div class="card-header border-line-bottom-title">
                              <h5 class="card-title">Vehicle Information</h5>
                            </div>
                            <div class="row">
                              <!-- vin : START -->
                              <div class="col-md-2 col-xs-12">
                                <label class="text-danger">VIN *</label>
                              </div>
                              <div class="col-md-6 col-xs-12">
                                <input
                                  id="inventory-vin"
                                  type="text"
                                  placeholder="VIN"
                                  v-model="form.vin"
                                  @input="form.vin = form.vin.toUpperCase()"
                                  class="form-control"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty('vin'),
                                  }"
                                  @keydown="delete errors.vin"
                                  :title="errors.vin"
                                />
                              </div>
                              <div class="col-md-4 col-xs-12">
                                <button
                                  class="btn btn-primary"
                                  @click="getAvailableTrimsForVIN()"
                                  type="button"
                                >
                                  Decode
                                </button>
                              </div>
                              <!-- vin : END -->

                              <!-- vehicle information : END -->
                              <div class="w-100 m-50"></div>

                              <!-- Visibility : START -->
                              <div class="col-md-4 col-xs-12">
                                <label for="form-visibility"
                                  >Visibility (Show On Website)</label
                                >
                              </div>
                              <div class="col-md-8 form-group col-xs-12">
                                <div class="custom-control custom-switch">
                                  <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    id="form-visibility"
                                    name="Visibility"
                                    v-model="form.visibility"
                                    :true-value="'1'"
                                    :false-value="'0'"
                                    checked
                                  />
                                  <label
                                    class="custom-control-label"
                                    for="form-visibility"
                                  >
                                    <span class="switch-icon-left"> ON</span>
                                    <span class="switch-icon-right">OFF</span>
                                  </label>
                                </div>
                              </div>

                              <!-- Visibility : END -->
                              <!-- year : START -->
                              <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                  <label for="inventory-year">Year</label>
                                  <fieldset
                                    class="has-icon-left"
                                    style="position: relative"
                                  >
                                    <input
                                      id="inventory-year"
                                      type="text"
                                      placeholder="Year"
                                      v-model="form.year"
                                      class="form-control"
                                      :class="{
                                        'is-invalid': errors.hasOwnProperty(
                                          'year',
                                        ),
                                      }"
                                      @keydown="delete errors.year"
                                      :title="errors.year"
                                    />
                                    <div class="form-control-position">
                                      <i
                                        class="bx bxs-calendar-alt font-medium-3"
                                      ></i>
                                    </div>
                                  </fieldset>
                                  <small
                                    id="inventory-year-error"
                                    class="form-text invalid-feedback"
                                    >{{ errors.year }}</small
                                  >
                                </div>
                              </div>
                              <!-- year : END -->

                              <!-- make : START -->
                              <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                  <label for="inventory-make">Make</label>
                                  <fieldset
                                    class="has-icon-left"
                                    style="position: relative"
                                  >
                                    <input
                                      id="inventory-make"
                                      type="text"
                                      placeholder="Make"
                                      v-model="form.make"
                                      class="form-control"
                                      :class="{
                                        'is-invalid': errors.hasOwnProperty(
                                          'make',
                                        ),
                                      }"
                                      @keydown="delete errors.make"
                                      :title="errors.make"
                                    />
                                    <div class="form-control-position">
                                      <i class="bx bx-car font-medium-3"></i>
                                    </div>
                                  </fieldset>
                                  <small
                                    id="inventory-make-error"
                                    class="form-text invalid-feedback"
                                    >{{ errors.make }}</small
                                  >
                                </div>
                              </div>
                              <!-- make : END -->

                              <!-- model : START -->
                              <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                  <label for="inventory-model">Model</label>
                                  <fieldset
                                    class="has-icon-left"
                                    style="position: relative"
                                  >
                                    <input
                                      id="inventory-model"
                                      type="text"
                                      placeholder="Model"
                                      v-model="form.model"
                                      class="form-control"
                                      :class="{
                                        'is-invalid': errors.hasOwnProperty(
                                          'model',
                                        ),
                                      }"
                                      @keydown="delete errors.model"
                                      :title="errors.model"
                                    />
                                    <div class="form-control-position">
                                      <i class="bx bxs-cog font-medium-3"></i>
                                    </div>
                                  </fieldset>
                                  <small
                                    id="inventory-model-error"
                                    class="form-text invalid-feedback"
                                    >{{ errors.model }}</small
                                  >
                                </div>
                              </div>
                              <!-- model : END -->

                              <!-- trim : START -->
                              <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                  <label for="inventory-trim">Trim</label>
                                  <fieldset
                                    class="has-icon-left"
                                    style="position: relative"
                                  >
                                    <input
                                      id="inventory-trim"
                                      type="text"
                                      placeholder="Trim"
                                      v-model="form.trim"
                                      class="form-control"
                                      :class="{
                                        'is-invalid': errors.hasOwnProperty(
                                          'trim',
                                        ),
                                      }"
                                      @keydown="delete errors.trim"
                                      :title="errors.trim"
                                    />
                                    <div class="form-control-position">
                                      <i
                                        class="bx bxs-detail font-medium-3"
                                      ></i>
                                    </div>
                                  </fieldset>
                                  <small
                                    id="inventory-trim-error"
                                    class="form-text invalid-feedback"
                                    >{{ errors.trim }}</small
                                  >
                                </div>
                              </div>
                              <!-- trim : END -->

                              <!-- odometer : START -->
                              <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                  <label for="inventory-odometer"
                                    >Odometer</label
                                  >
                                  <fieldset
                                    class="has-icon-left"
                                    style="position: relative"
                                  >
                                    <input
                                      id="inventory-odometer"
                                      type="text"
                                      placeholder="Odometer"
                                      v-model="form.odometer"
                                      class="form-control"
                                      :class="{
                                        'is-invalid': errors.hasOwnProperty(
                                          'odometer',
                                        ),
                                      }"
                                      @keydown="delete errors.odometer"
                                      :title="errors.odometer"
                                    />
                                    <div class="form-control-position">
                                      <i
                                        class="bx bxs-dashboard font-medium-3"
                                      ></i>
                                    </div>
                                  </fieldset>
                                  <small
                                    id="inventory-odometer-error"
                                    class="form-text invalid-feedback"
                                    >{{ errors.odometer }}</small
                                  >
                                </div>
                              </div>
                              <!-- odometer : END -->

                              <!-- km_miles : START -->
                              <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                  <label for="inventory-km_miles"
                                    >Distance Type</label
                                  >
                                  <select
                                    id="inventory-km_miles"
                                    class="custom-select"
                                    v-model="form.km_miles"
                                    name="km_miles"
                                    :class="{
                                      'is-invalid': errors.hasOwnProperty(
                                        'km_miles',
                                      ),
                                    }"
                                    @keydown="delete errors.km_miles"
                                    :title="errors.km_miles"
                                  >
                                    <option value="" selected disabled>
                                      Select
                                    </option>
                                    <option value="KM">KM</option>
                                    <option value="Miles">Miles</option>
                                  </select>
                                  <small
                                    id="inventory-km_miles-error"
                                    class="form-text invalid-feedback"
                                    >{{ errors.km_miles }}</small
                                  >
                                </div>
                              </div>
                              <!-- km_miles : END -->

                              <!-- body_type : START -->
                              <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                  <label for="inventory-body_type"
                                    >Body Type</label
                                  >
                                  <select
                                    id="inventory-body_type"
                                    class="custom-select"
                                    v-model="form.body_type"
                                    name="body_type"
                                    :class="{
                                      'is-invalid': errors.hasOwnProperty(
                                        'body_type',
                                      ),
                                    }"
                                    @keydown="delete errors.body_type"
                                    :title="errors.body_type"
                                  >
                                    <option value="" selected disabled>
                                      Select
                                    </option>
                                    <option
                                      :value="body_type"
                                      v-for="body_type in body_types"
                                      :key="body_type"
                                      v-text="body_type"
                                    ></option>
                                  </select>
                                  <small
                                    id="inventory-body_type-error"
                                    class="form-text invalid-feedback"
                                    >{{ errors.body_type }}</small
                                  >
                                </div>
                              </div>
                              <!-- body_type : END -->

                              <!-- exterior_color : START -->
                              <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                  <label for="inventory-exterior_color"
                                    >Exterior Color</label
                                  >
                                  <fieldset
                                    class="has-icon-left"
                                    style="position: relative"
                                  >
                                    <input
                                      id="inventory-exterior_color"
                                      type="text"
                                      placeholder="Exterior Color"
                                      v-model="form.exterior_color"
                                      class="form-control"
                                      :class="{
                                        'is-invalid': errors.hasOwnProperty(
                                          'exterior_color',
                                        ),
                                      }"
                                      @keydown="delete errors.exterior_color"
                                      :title="errors.exterior_color"
                                    />
                                    <div class="form-control-position">
                                      <i
                                        class="bx bx-palette font-medium-3"
                                      ></i>
                                    </div>
                                  </fieldset>
                                  <small
                                    id="inventory-exterior_color-error"
                                    class="form-text invalid-feedback"
                                    >{{ errors.exterior_color }}</small
                                  >
                                </div>
                              </div>
                              <!-- exterior_color : END -->

                              <!-- interior_color : START -->
                              <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                  <label for="inventory-interior_color"
                                    >Interior Color</label
                                  >
                                  <fieldset
                                    class="has-icon-left"
                                    style="position: relative"
                                  >
                                    <input
                                      id="inventory-interior_color"
                                      type="text"
                                      placeholder="Interior Color"
                                      v-model="form.interior_color"
                                      class="form-control"
                                      :class="{
                                        'is-invalid': errors.hasOwnProperty(
                                          'interior_color',
                                        ),
                                      }"
                                      @keydown="delete errors.interior_color"
                                      :title="errors.interior_color"
                                    />
                                    <div class="form-control-position">
                                      <i
                                        class="bx bxs-palette font-medium-3"
                                      ></i>
                                    </div>
                                  </fieldset>
                                  <small
                                    id="inventory-interior_color-error"
                                    class="form-text invalid-feedback"
                                    >{{ errors.interior_color }}</small
                                  >
                                </div>
                              </div>
                              <!-- interior_color : END -->

                              <!-- price : START -->
                              <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                  <label for="inventory-price">Price</label>
                                  <fieldset
                                    class="has-icon-left"
                                    style="position: relative"
                                  >
                                    <input
                                      id="inventory-price"
                                      type="text"
                                      placeholder="Price"
                                      v-model="form.price"
                                      class="form-control"
                                      :class="{
                                        'is-invalid': errors.hasOwnProperty(
                                          'price',
                                        ),
                                      }"
                                      @keydown="delete errors.price"
                                      :title="errors.price"
                                    />
                                    <div class="form-control-position">
                                      <i
                                        class="bx bxs-dollar-circle font-medium-3"
                                      ></i>
                                    </div>
                                  </fieldset>
                                  <small
                                    id="inventory-price-error"
                                    class="form-text invalid-feedback"
                                    >{{ errors.price }}</small
                                  >
                                </div>
                              </div>
                              <!-- price : END -->
                            </div>
                          </div>
                          <!-- vehicle information : END -->

                          <!-- vehicle attachment : START -->
                          <div class="m-width-s">
                            <div class="card-header border-line-bottom-title">
                              <h5 class="card-title">Vehicle Attachments</h5>
                            </div>
                            <div class="row">
                              <!-- vehicle history report : START -->
                              <div class="col-md-4 col-xs-12">
                                <label>Vehicle History Report</label>
                              </div>
                              <div class="col-md-8 col-xs-12">
                                <input
                                  id="vehicle_history_report"
                                  type="text"
                                  placeholder="Vehicle History Report"
                                  v-model="form.vehicle_history_report"
                                  class="form-control"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'vehicle_history_report',
                                    ),
                                  }"
                                  @keydown="
                                    delete errors.vehicle_history_report
                                  "
                                  :title="errors.vehicle_history_report"
                                />
                              </div>
                              <!-- vehicle history report : END -->

                              <div class="w-100 p-25"></div>

                              <!-- Vehicle Video URL : START -->
                              <div class="col-md-4 col-xs-12">
                                <label>Vehicle Video URL</label>
                              </div>
                              <div class="col-md-8 col-xs-12">
                                <input
                                  id="video_url"
                                  type="text"
                                  placeholder="Vehicle Video URL"
                                  v-model="form.video_url"
                                  class="form-control"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'video_url',
                                    ),
                                  }"
                                  @keydown="delete errors.video_url"
                                  :title="errors.video_url"
                                />
                              </div>
                              <!-- Vehicle Video URL : END -->
                            </div>
                          </div>

                          <!-- vehicle attachment : END -->
                          <!-- has_inventory_purchase : START -->
                          <div class="col-12 form-group">
                            <div class="checkbox">
                              <input
                                type="checkbox"
                                class="checkbox-input"
                                id="has_inventory_purchase"
                                v-model="has_inventory_purchase"
                                :true-value="true"
                                :false-value="false"
                              />
                              <label for="has_inventory_purchase"
                                >Has Vehicle Purchase Information</label
                              >
                            </div>
                          </div>
                          <hr />
                          <!-- has_inventory_purchase : END -->

                          <!-- vehicle purchase : START -->
                          <div
                            class="m-width-s"
                            v-show="has_inventory_purchase"
                            id="import-inventory-purchase-section"
                          >
                            <div class="card-header border-line-bottom-title">
                              <h5 class="card-title">Vehicle Purchase</h5>
                            </div>
                            <div class="row">
                              <div class="form-group col-md-6 offset-md-2">
                                <label for="costs">Search Vendor : </label>
                                <v-select
                                  taggable
                                  placeholder="Vendors List"
                                  label="company_name"
                                  :reduce="(company_name) => company_name.id"
                                  v-model="selected_company"
                                  clearable
                                  :options="allVendors"
                                ></v-select>
                              </div>
                            </div>
                            <hr class="w-100" />
                            <div class="row">
                              <div class="col-md-4 form-group">
                                <label>Purchased On</label>
                                <fieldset class="has-icon-left">
                                  <date-picker
                                    v-model="inventory_purchase.purchase_date"
                                    :format="'MM/DD/YYYY'"
                                    :value-type="'MM/DD/YYYY'"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty(
                                          'purchase_date',
                                        ),
                                    }"
                                    @keydown="
                                      delete errors.purchase.purchase_date
                                    "
                                    :title="errors.purchase.purchase_date"
                                    :clearable="false"
                                  ></date-picker>
                                </fieldset>
                              </div>
                              <!-- Plate No : START -->
                              <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                  <label for="inventory-purchase-plate-number"
                                    >Plate No</label
                                  >
                                  <fieldset
                                    class="has-icon-left"
                                    style="position: relative"
                                  >
                                    <input
                                      id="inventory-purchase-plate-number"
                                      type="text"
                                      placeholder="Plate Number"
                                      v-model="inventory_purchase.plate_number"
                                      class="form-control"
                                      :class="{
                                        'is-invalid':
                                          errors.hasOwnProperty('purchase') &&
                                          errors.purchase.hasOwnProperty(
                                            'plate_number',
                                          ),
                                      }"
                                      @keydown="
                                        delete errors.purchase.plate_number
                                      "
                                      :title="errors.purchase.plate_number"
                                    />
                                  </fieldset>
                                  <small
                                    id="inventory-purchase-plate-number-error"
                                    class="form-text invalid-feedback"
                                    >{{ errors.purchase.plate_number }}</small
                                  >
                                </div>
                              </div>
                              <!-- Plate No : END -->

                              <!-- Keys : START -->
                              <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                  <label for="inventory-purchase-keys"
                                    >Keys</label
                                  >
                                  <fieldset
                                    class="has-icon-left"
                                    style="position: relative"
                                  >
                                    <input
                                      id="inventory-purchase-keys"
                                      type="text"
                                      placeholder="Keys"
                                      v-model="inventory_purchase.key_count"
                                      class="form-control"
                                      :class="{
                                        'is-invalid':
                                          errors.hasOwnProperty('purchase') &&
                                          errors.purchase.hasOwnProperty(
                                            'key_count',
                                          ),
                                      }"
                                      @keydown="
                                        delete errors.purchase.key_count
                                      "
                                      :title="errors.purchase.key_count"
                                    />
                                  </fieldset>
                                  <small
                                    id="inventory-purchase-keys-error"
                                    class="form-text invalid-feedback"
                                    >{{ errors.purchase.key_count }}</small
                                  >
                                </div>
                              </div>
                              <!-- Keys : END -->

                              <div class="w-100"></div>

                              <div class="col-md-4 col-xs-12 form-group">
                                <fieldset class="has-icon-left">
                                  <input
                                    type="text"
                                    placeholder="Company Name"
                                    v-model="inventory_purchase.company_name"
                                    class="form-control"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty(
                                          'company_name',
                                        ),
                                    }"
                                    @keydown="
                                      delete errors.purchase.company_name
                                    "
                                    :title="errors.purchase.company_name"
                                  />
                                  <div class="form-control-position">
                                    <i class="bx bxs-factory font-medium-3"></i>
                                  </div>
                                </fieldset>
                              </div>
                              <div
                                class="col-md-4 col-xs-12 form-group"
                                v-if="
                                  parseInt(
                                    inventory_purchase.purchase_from_flag,
                                  ) == 1
                                "
                              >
                                <fieldset class="has-icon-left">
                                  <input
                                    type="text"
                                    placeholder="MVDA #"
                                    v-model="inventory_purchase.mvda"
                                    class="form-control"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty('mvda'),
                                    }"
                                    @keydown="delete errors.purchase.mvda"
                                    :title="errors.purchase.mvda"
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
                                    v-model="inventory_purchase.first_name"
                                    class="form-control"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty(
                                          'first_name',
                                        ),
                                    }"
                                    @keydown="delete errors.purchase.first_name"
                                    :title="errors.purchase.first_name"
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
                                    v-model="inventory_purchase.last_name"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty(
                                          'last_name',
                                        ),
                                    }"
                                    @keydown="delete errors.purchase.last_name"
                                    :title="errors.purchase.last_name"
                                    class="form-control"
                                  />
                                  <!-- <div class="form-control-position">
                                  <i class="bx bxs-user font-medium-3"></i>
                                </div> -->
                                </fieldset>
                              </div>
                              <div
                                class="col-md-4 col-xs-12 form-group"
                                v-if="
                                  parseInt(
                                    inventory_purchase.purchase_from_flag,
                                  ) == 1
                                "
                              >
                                <fieldset class="has-icon-left">
                                  <input
                                    type="text"
                                    placeholder="Salesperson Reg #"
                                    v-model="
                                      inventory_purchase.sales_person_registration
                                    "
                                    class="form-control"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty(
                                          'sales_person_registration',
                                        ),
                                    }"
                                    @keydown="
                                      delete errors.purchase
                                        .sales_person_registration
                                    "
                                    :title="
                                      errors.purchase.sales_person_registration
                                    "
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
                                v-show="
                                  parseInt(
                                    inventory_purchase.purchase_from_flag,
                                  ) == 0
                                "
                              >
                                <label>DL or RIN</label>
                              </div>
                              <!-- show option if public -->
                              <div
                                class="col-md-2 form-group"
                                v-show="
                                  parseInt(
                                    inventory_purchase.purchase_from_flag,
                                  ) == 0
                                "
                              >
                                <div class="custom-control custom-switch">
                                  <input
                                    type="checkbox"
                                    id="dlOrRlSwitch"
                                    v-model="inventory_purchase.rin_or_dl_flag"
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
                                v-if="
                                  parseInt(inventory_purchase.rin_or_dl_flag) ==
                                    1
                                "
                              >
                                <fieldset class="has-icon-left">
                                  <input
                                    type="text"
                                    placeholder="Driving License"
                                    v-model="inventory_purchase.driver_licence"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty(
                                          'driver_licence',
                                        ),
                                    }"
                                    @keydown="
                                      delete errors.purchase.driver_licence
                                    "
                                    :title="errors.purchase.driver_licence"
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
                                    v-model="inventory_purchase.rin"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty('rin'),
                                    }"
                                    @keydown="delete errors.purchase.rin"
                                    :title="errors.purchase.rin"
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
                                    v-model="inventory_purchase.tax_number"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty(
                                          'tax_number',
                                        ),
                                    }"
                                    @keydown="delete errors.purchase.tax_number"
                                    :title="errors.purchase.tax_number"
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
                                    v-model="inventory_purchase.street_address"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty(
                                          'street_address',
                                        ),
                                    }"
                                    @keydown="
                                      delete errors.purchase.street_address
                                    "
                                    :title="errors.purchase.street_address"
                                    class="form-control"
                                  />
                                  <div class="form-control-position">
                                    <i
                                      class="bx bx-directions font-medium-3"
                                    ></i>
                                  </div>
                                </fieldset>
                              </div>
                              <div class="col-md-6 col-xs-12 form-group">
                                <fieldset>
                                  <input
                                    type="text"
                                    placeholder="APT No / Suite"
                                    v-model="inventory_purchase.apt"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty('apt'),
                                    }"
                                    @keydown="delete errors.purchase.apt"
                                    :title="errors.purchase.apt"
                                    class="form-control"
                                  />
                                </fieldset>
                              </div>
                              <div class="col-md-3 col-xs-12 form-group">
                                <fieldset>
                                  <input
                                    type="text"
                                    placeholder="City"
                                    v-model="inventory_purchase.city"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty('city'),
                                    }"
                                    @keydown="delete errors.purchase.city"
                                    :title="errors.purchase.city"
                                    class="form-control"
                                  />
                                </fieldset>
                              </div>
                              <div class="col-md-3 col-xs-12 form-group">
                                <fieldset>
                                  <input
                                    type="text"
                                    placeholder="Province"
                                    v-model="inventory_purchase.province"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty(
                                          'province',
                                        ),
                                    }"
                                    @keydown="delete errors.purchase.province"
                                    :title="errors.purchase.province"
                                    class="form-control"
                                  />
                                </fieldset>
                              </div>
                              <div class="col-md-3 col-xs-12 form-group">
                                <fieldset>
                                  <input
                                    type="text"
                                    placeholder="Country"
                                    v-model="inventory_purchase.country"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty(
                                          'country',
                                        ),
                                    }"
                                    @keydown="delete errors.purchase.country"
                                    :title="errors.purchase.country"
                                    class="form-control"
                                  />
                                </fieldset>
                              </div>
                              <div class="col-md-3 col-xs-12 form-group">
                                <fieldset class="has-icon-left">
                                  <input
                                    type="text"
                                    placeholder="Postal Code"
                                    v-model="inventory_purchase.postal_code"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty(
                                          'postal_code',
                                        ),
                                    }"
                                    @keydown="
                                      delete errors.purchase.postal_code
                                    "
                                    :title="errors.purchase.postal_code"
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
                                    v-model="inventory_purchase.phone"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty('phone'),
                                    }"
                                    @keydown="delete errors.purchase.phone"
                                    :title="errors.purchase.phone"
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
                                    v-model="inventory_purchase.mobile"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty(
                                          'mobile',
                                        ),
                                    }"
                                    @keydown="delete errors.purchase.mobile"
                                    :title="errors.purchase.mobile"
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
                                    v-model="inventory_purchase.fax"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty('fax'),
                                    }"
                                    @keydown="delete errors.purchase.fax"
                                    :title="errors.purchase.fax"
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
                                    v-model="inventory_purchase.email"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('purchase') &&
                                        errors.purchase.hasOwnProperty('email'),
                                    }"
                                    @keydown="delete errors.purchase.email"
                                    :title="errors.purchase.email"
                                    class="form-control"
                                  />
                                  <div class="form-control-position">
                                    <i
                                      class="bx bxs-envelope font-medium-3"
                                    ></i>
                                  </div>
                                </fieldset>
                              </div>
                            </div>
                          </div>

                          <!-- vehicle purchase : END -->

                          <!-- section for taxes : START -->
                          <div
                            class="col-md-12 col-12"
                            v-show="has_inventory_purchase"
                          >
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
                                        v-model="inventory_purchase.price"
                                        :class="{
                                          'is-invalid':
                                            errors.hasOwnProperty('purchase') &&
                                            errors.purchase.hasOwnProperty(
                                              'price',
                                            ),
                                        }"
                                        @keyup="
                                          calculateInventoryPurchase('price');
                                          delete errors.purchase.price;
                                        "
                                        :title="errors.purchase.price"
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
                                        v-model="inventory_purchase.discount"
                                        :class="{
                                          'is-invalid':
                                            errors.hasOwnProperty('purchase') &&
                                            errors.purchase.hasOwnProperty(
                                              'discount',
                                            ),
                                        }"
                                        @keyup="
                                          calculateInventoryPurchase(
                                            'discount',
                                          );
                                          delete errors.purchase.discount;
                                        "
                                        :title="errors.purchase.discount"
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
                                      v-model="inventory_purchase.tax_type"
                                      name="tax_type"
                                      :class="{
                                        'is-invalid':
                                          errors.hasOwnProperty('purchase') &&
                                          errors.purchase.hasOwnProperty(
                                            'tax_type',
                                          ),
                                      }"
                                      @change="
                                        calculateInventoryPurchase('tax_type');
                                        delete errors.purchase.tax_type;
                                      "
                                      :title="errors.purchase.tax_type"
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
                                    <label for="tax_type_secondary"
                                      >Tax Type</label
                                    >
                                  </div>
                                  <div
                                    class="col-xs-12 form-group"
                                    style="position: relative"
                                  >
                                    <select
                                      id="tax_type_secondary"
                                      class="custom-select"
                                      v-model="
                                        inventory_purchase.tax_type_secondary
                                      "
                                      name="tax_type_secondary"
                                      :class="{
                                        'is-invalid':
                                          errors.hasOwnProperty('purchase') &&
                                          errors.purchase.hasOwnProperty(
                                            'tax_type_secondary',
                                          ),
                                      }"
                                      :disabled="
                                        inventory_purchase.tax_type === 'Other'
                                      "
                                      @change="
                                        calculateInventoryPurchase(
                                          'tax_type_secondary',
                                        );
                                        delete errors.purchase
                                          .tax_type_secondary;
                                      "
                                      :title="
                                        errors.purchase.tax_type_secondary
                                      "
                                    >
                                      <option value="" selected>
                                        Tax Type
                                      </option>
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
                                    <label for="tax_amount_total"
                                      >Tax Amount</label
                                    >
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
                                        :disabled="
                                          inventory_purchase.tax_type !==
                                            'Other'
                                        "
                                        v-model="
                                          inventory_purchase.tax_amount_total
                                        "
                                        :class="{
                                          'is-invalid':
                                            errors.hasOwnProperty('purchase') &&
                                            errors.purchase.hasOwnProperty(
                                              'tax_amount_total',
                                            ),
                                        }"
                                        @keyup="
                                          calculateInventoryPurchase(
                                            'tax_amount_total',
                                          );
                                          delete errors.purchase
                                            .tax_amount_total;
                                        "
                                        :title="
                                          errors.purchase.tax_amount_total
                                        "
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
                                        v-model="inventory_purchase.sub_total"
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
                                <div class="col-md-1 p-50">
                                  <i
                                    v-show="price_recalculation"
                                    class="bx bx-loader-alt bx-spin bx-md p-75 text-primary"
                                  ></i>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- section for taxes : END -->

                          <!-- vehicle expenses : START -->
                          <div
                            class="m-width-s"
                            id="import-inventory-expense-section"
                          >
                            <div class="card-header border-line-bottom-title">
                              <h5 class="card-title">Vehicle Expenses</h5>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-3 col-offset-md-3">
                                <label for="costs">Search Expense : </label>
                                <v-select
                                  taggable
                                  placeholder="Search Expense Name"
                                  label="name"
                                  :reduce="(cost) => cost.id"
                                  v-model="selected_expense_name"
                                  clearable
                                  :options="cost_setting_listing"
                                ></v-select>
                              </div>
                            </div>
                            <div
                              class="row contact-repeater"
                              v-for="n in this.count_of_expenses"
                              :key="`inventory_expense_${n}`"
                            >
                              <h6 class="col-md-12">Expense #{{ n }}</h6>

                              <!-- purchase date : START -->
                              <div class="col-md-3 form-group">
                                <label>Purchased On</label>
                                <fieldset class="has-icon-left">
                                  <date-picker
                                    v-model="
                                      inventory_expenses[n - 1]['purchase_date']
                                    "
                                    :format="'MM/DD/YYYY'"
                                    :value-type="'MM/DD/YYYY'"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('expenses') &&
                                        errors.expenses.hasOwnProperty(
                                          `expense_${n - 1}`,
                                        ) &&
                                        errors.expenses[
                                          `expense_${n - 1}`
                                        ].hasOwnProperty('purchase_date'),
                                    }"
                                    @change="
                                      deleteExpenseError(n - 1, 'purchase_date')
                                    "
                                    :title="showTitle(n - 1, 'purchase_date')"
                                    :clearable="false"
                                  ></date-picker>
                                </fieldset>
                              </div>
                              <!-- purchase date : END -->

                              <div class="w-100"></div>

                              <!-- expense name : START -->
                              <div class="col-md-6 col-xs-12 form-group">
                                <label>Name</label>
                                <fieldset>
                                  <input
                                    type="text"
                                    placeholder=""
                                    v-model="inventory_expenses[n - 1]['name']"
                                    class="form-control"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('expenses') &&
                                        errors.expenses.hasOwnProperty(
                                          `expense_${n - 1}`,
                                        ) &&
                                        errors.expenses[
                                          `expense_${n - 1}`
                                        ].hasOwnProperty('name'),
                                    }"
                                    @keydown="deleteExpenseError(n - 1, 'name')"
                                    :title="showTitle(n - 1, 'name')"
                                  />
                                </fieldset>
                              </div>
                              <!-- expense name : END -->

                              <!-- expense invoice reference : START -->
                              <div class="col-md-3 col-xs-12 form-group">
                                <label>Invoice Reference</label>
                                <fieldset>
                                  <input
                                    type="text"
                                    placeholder=""
                                    v-model="
                                      inventory_expenses[n - 1]['invoice']
                                    "
                                    class="form-control"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('expenses') &&
                                        errors.expenses.hasOwnProperty(
                                          `expense_${n - 1}`,
                                        ) &&
                                        errors.expenses[
                                          `expense_${n - 1}`
                                        ].hasOwnProperty('invoice'),
                                    }"
                                    @keydown="
                                      deleteExpenseError(n - 1, 'invoice')
                                    "
                                    :title="showTitle(n - 1, 'invoice')"
                                  />
                                </fieldset>
                              </div>
                              <!-- expense invoice reference : END -->

                              <!-- expense payment reference : START -->
                              <div class="col-md-3 col-xs-12 form-group">
                                <label>Payment Reference</label>
                                <fieldset>
                                  <input
                                    type="text"
                                    placeholder=""
                                    v-model="
                                      inventory_expenses[n - 1][
                                        'payment_reference'
                                      ]
                                    "
                                    class="form-control"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('expenses') &&
                                        errors.expenses.hasOwnProperty(
                                          `expense_${n - 1}`,
                                        ) &&
                                        errors.expenses[
                                          `expense_${n - 1}`
                                        ].hasOwnProperty('payment_reference'),
                                    }"
                                    @keydown="
                                      deleteExpenseError(
                                        n - 1,
                                        'payment_reference',
                                      )
                                    "
                                    :title="
                                      showTitle(n - 1, 'payment_reference')
                                    "
                                  />
                                </fieldset>
                              </div>
                              <!-- expense payment reference : END -->

                              <!-- expense description : START -->
                              <div class="col-md-9 col-xs-12 form-group">
                                <label>Description</label>
                                <fieldset>
                                  <textarea
                                    type="text"
                                    placeholder=""
                                    v-model="
                                      inventory_expenses[n - 1]['description']
                                    "
                                    class="form-control"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('expenses') &&
                                        errors.expenses.hasOwnProperty(
                                          `expense_${n - 1}`,
                                        ) &&
                                        errors.expenses[
                                          `expense_${n - 1}`
                                        ].hasOwnProperty('description'),
                                    }"
                                    @keydown="
                                      deleteExpenseError(n - 1, 'description')
                                    "
                                    :title="showTitle(n - 1, 'description')"
                                  ></textarea>
                                </fieldset>
                              </div>
                              <!-- expense description : END -->

                              <!-- expense vendor name : START -->
                              <div class="col-md-3 col-xs-12 form-group">
                                <label>Vendor</label>
                                <fieldset>
                                  <input
                                    type="text"
                                    placeholder=""
                                    v-model="
                                      inventory_expenses[n - 1]['vendor_name']
                                    "
                                    class="form-control"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('expenses') &&
                                        errors.expenses.hasOwnProperty(
                                          `expense_${n - 1}`,
                                        ) &&
                                        errors.expenses[
                                          `expense_${n - 1}`
                                        ].hasOwnProperty('vendor_name'),
                                    }"
                                    @keydown="
                                      deleteExpenseError(n - 1, 'vendor_name')
                                    "
                                    :title="showTitle(n - 1, 'vendor_name')"
                                  />
                                </fieldset>
                              </div>
                              <!-- expense vendor name : END -->

                              <!-- expense price : START -->
                              <div class="col-md-2">
                                <div class="col-xs-12">
                                  <label for="expense_price">Price</label>
                                </div>
                                <div
                                  class="col-xs-12 form-group"
                                  style="position: relative"
                                >
                                  <fieldset class="has-icon-left">
                                    <input
                                      type="text"
                                      placeholder="Price"
                                      class="form-control"
                                      id="expense_price"
                                      v-model="
                                        inventory_expenses[n - 1]['price']
                                      "
                                      :class="{
                                        'is-invalid':
                                          errors.hasOwnProperty('expenses') &&
                                          errors.expenses.hasOwnProperty(
                                            `expense_${n - 1}`,
                                          ) &&
                                          errors.expenses[
                                            `expense_${n - 1}`
                                          ].hasOwnProperty('price'),
                                      }"
                                      @keyup="
                                        deleteExpenseError(n - 1, 'price');
                                        calculateAnExpense(n - 1, 'price');
                                      "
                                      :title="showTitle(n - 1, 'price')"
                                    />
                                    <div class="form-control-position">
                                      <i
                                        class="bx bxs-dollar-circle font-medium-3"
                                      ></i>
                                    </div>
                                  </fieldset>
                                </div>
                              </div>
                              <!-- expense price : END -->

                              <!-- expense qty : START -->
                              <div class="col-md-2">
                                <div class="col-xs-12">
                                  <label for="expense_qty">QTY</label>
                                </div>
                                <div
                                  class="col-xs-12 form-group"
                                  style="position: relative"
                                >
                                  <fieldset>
                                    <input
                                      type="text"
                                      placeholder="QTY"
                                      class="form-control"
                                      id="expense_qty"
                                      v-integer
                                      v-model="
                                        inventory_expenses[n - 1]['quantity']
                                      "
                                      :class="{
                                        'is-invalid':
                                          errors.hasOwnProperty('expenses') &&
                                          errors.expenses.hasOwnProperty(
                                            `expense_${n - 1}`,
                                          ) &&
                                          errors.expenses[
                                            `expense_${n - 1}`
                                          ].hasOwnProperty('quantity'),
                                      }"
                                      @keyup="
                                        deleteExpenseError(n - 1, 'quantity');
                                        calculateAnExpense(n - 1, 'quantity');
                                      "
                                      :title="showTitle(n - 1, 'quantity')"
                                    />
                                  </fieldset>
                                </div>
                              </div>
                              <!-- expense qty : END -->

                              <!-- expense discount : START -->
                              <div class="col-md-2">
                                <div class="col-xs-12">
                                  <label for="expense_discount">DISCOUNT</label>
                                </div>
                                <div
                                  class="col-xs-12 form-group"
                                  style="position: relative"
                                >
                                  <fieldset class="has-icon-left">
                                    <input
                                      type="text"
                                      placeholder="Discount"
                                      class="form-control"
                                      id="expense_discount"
                                      v-model="
                                        inventory_expenses[n - 1]['discount']
                                      "
                                      :class="{
                                        'is-invalid':
                                          errors.hasOwnProperty('expenses') &&
                                          errors.expenses.hasOwnProperty(
                                            `expense_${n - 1}`,
                                          ) &&
                                          errors.expenses[
                                            `expense_${n - 1}`
                                          ].hasOwnProperty('discount'),
                                      }"
                                      @keyup="
                                        deleteExpenseError(n - 1, 'discount');
                                        calculateAnExpense(n - 1, 'discount');
                                      "
                                      :title="showTitle(n - 1, 'discount')"
                                    />
                                    <div class="form-control-position">
                                      <i
                                        class="bx bxs-discount font-medium-3"
                                      ></i>
                                    </div>
                                  </fieldset>
                                </div>
                              </div>
                              <!-- expense discount : END -->

                              <!-- expense tax type : START -->
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
                                    v-model="
                                      inventory_expenses[n - 1]['tax_type']
                                    "
                                    name="tax_type"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('expenses') &&
                                        errors.expenses.hasOwnProperty(
                                          `expense_${n - 1}`,
                                        ) &&
                                        errors.expenses[
                                          `expense_${n - 1}`
                                        ].hasOwnProperty('tax_type'),
                                    }"
                                    @change="
                                      deleteExpenseError(n - 1, 'tax_type');
                                      calculateAnExpense(n - 1, 'tax_type');
                                    "
                                    :title="showTitle(n - 1, 'tax_type')"
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
                              <!-- expense tax type : END -->

                              <!-- expense tax type seconday: START -->
                              <div class="col-md-3">
                                <div class="col-xs-12">
                                  <label for="tax_type">Tax Type</label>
                                </div>
                                <div
                                  class="col-xs-12 form-group"
                                  style="position: relative"
                                >
                                  <select
                                    id="inventory_expense_tax_type_secondary"
                                    class="custom-select"
                                    v-model="
                                      inventory_expenses[n - 1][
                                        'tax_type_secondary'
                                      ]
                                    "
                                    name="tax_type_secondary"
                                    :disabled="
                                      inventory_expenses[n - 1]['tax_type'] ===
                                        'Other'
                                    "
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('expenses') &&
                                        errors.expenses.hasOwnProperty(
                                          `expense_${n - 1}`,
                                        ) &&
                                        errors.expenses[
                                          `expense_${n - 1}`
                                        ].hasOwnProperty('tax_type_secondary'),
                                    }"
                                    @change="
                                      deleteExpenseError(
                                        0,
                                        'tax_type_secondary',
                                      );
                                      calculateAnExpense(
                                        n - 1,
                                        'tax_type_secondary',
                                      );
                                    "
                                    :title="
                                      showTitle(n - 1, 'tax_type_secondary')
                                    "
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
                              <!-- expense tax type seconday: END -->

                              <!-- Tax Amount : START -->
                              <div class="col-md-3">
                                <div class="col-xs-12">
                                  <label for="expense_tax_amount_total"
                                    >Tax Amount</label
                                  >
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
                                      id="expense_tax_amount_total"
                                      v-model="
                                        inventory_expenses[n - 1][
                                          'tax_amount_total'
                                        ]
                                      "
                                      :class="{
                                        'is-invalid':
                                          errors.hasOwnProperty('expenses') &&
                                          errors.expenses.hasOwnProperty(
                                            `expense_${n - 1}`,
                                          ) &&
                                          errors.expenses[
                                            `expense_${n - 1}`
                                          ].hasOwnProperty('tax_amount_total'),
                                      }"
                                      @keyup="
                                        deleteExpenseError(
                                          0,
                                          'tax_amount_total',
                                        );
                                        calculateAnExpense(
                                          n - 1,
                                          'tax_amount_total',
                                        );
                                      "
                                      :disabled="
                                        inventory_expenses[n - 1][
                                          'tax_type'
                                        ] !== 'Other'
                                      "
                                      :title="
                                        showTitle(n - 1, 'tax_amount_total')
                                      "
                                    />
                                    <div class="form-control-position">
                                      <i
                                        class="bx bxs-dollar-circle font-medium-3"
                                      ></i>
                                    </div>
                                  </fieldset>
                                </div>
                              </div>
                              <!-- Tax Amount : END -->

                              <!--Sub Total : START -->
                              <div class="col-md-3">
                                <div class="col-xs-12">
                                  <label for="expense_sub_total"
                                    >Sub Total</label
                                  >
                                </div>
                                <div
                                  class="col-xs-12 form-group"
                                  style="position: relative"
                                >
                                  <fieldset class="has-icon-left">
                                    <input
                                      type="text"
                                      placeholder="Sub Total"
                                      class="form-control"
                                      id="expense_sub_total"
                                      v-model="
                                        inventory_expenses[n - 1]['sub_total']
                                      "
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
                              <!--Sub Total : END-->
                              <div class="col-md-1 py-75">
                                <i
                                  v-show="price_recalculation"
                                  class="bx bx-loader-alt bx-spin bx-md p-75 text-primary"
                                ></i>
                              </div>

                              <div class="col-md-2 col-12 form-group py-1">
                                <button
                                  class="btn btn-danger text-nowrap px-1"
                                  @click="
                                    inventory_expenses.splice(n - 1, 1);
                                    count_of_expenses -= 1;
                                  "
                                  type="button"
                                >
                                  <i class="bx bx-x"></i>
                                </button>
                              </div>

                              <hr class="w-100" />
                            </div>
                            <div class="col-md-12">
                              <button
                                class="btn btn-icon btn-primary"
                                @click.prevent="addANewExpense()"
                                type="button"
                              >
                                <i class="bx bx-plus"></i>Add New Expense
                              </button>
                            </div>
                          </div>

                          <!-- vehicle expenses : END -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
              <!-- Inventory Section : END -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <v-i-n-decoder :elementId="'default'"></v-i-n-decoder>

    <!-- action bar : START -->
    <ul class="nav nav-tabs p-50 custom-footer-fixed-action-bar" role="tablist">
      <button
        @click.prevent="save"
        type="submit"
        class="btn btn-primary mr-1 ml-4"
      >
        Save
      </button>
    </ul>
    <!-- action bar : END -->
  </div>
</template>
<script>
import Toast from "../../library/Toast";
import VINDecoder from "../WebComponents/VINDecoder";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import vSelect from "vue-select";
import { mapGetters, mapActions } from "vuex";

export default {
  name: "InventoryImport",
  components: {
    "v-select": vSelect,
    DatePicker,
    VINDecoder,
  },
  data() {
    return {
      errors: {
        purchase: {},
        expenses: {},
      },
      load: true,
      has_inventory_purchase: false,
      count_of_expenses: 0,
      form: {
        video_url: "",
        vehicle_history_report: "",
        vin_company: "",
        vin: "",
        vindisplay: "1",
        stock_no: "",
        stock_date: "",
        status: "Instock",
        sub_status: "Available For Sale",
        feature_listing: "0",
        vehicle_type: "Used",
        visibility: "1",
        vehicle_tags: [],
        title: "",
        year: "",
        make: "",
        model: "",
        trim: "",
        body_type: "",
        door: "",
        engine: "",
        litres: "",
        transmission: "",
        drivetrain: "",
        passengers: "",
        odometer: "",
        km_miles: "KM",
        fuel_type: "",
        fuel_city: "",
        fuel_hwy: "",
        exterior_color: "",
        interior_color: "",
        price: "",
        hide_price: "",
        special_price_on: "",
        special_price: "",
        special_price_type: "Forever",
        special_price_date: null,
        special_price_time: null,
        show_stripe: "0",
        financing_on: "0",
        financing_special: "0",
        downpayment: "0",
        interest_rate: "0",
        term: "0",
        payment_frequency: "",
        location: "",
        vehicle_description: "",
        custom_features: [],
        syndication: [],
      },
      inventory_purchase: {
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
        has_lien: 0,
        lien_amount: 0,
        lien_has_been_paid: 0,
        sub_total: 0,
      },
      inventory_expenses: [],
      exterior_color: [],
      interior_color: [],
      trim: [],
      trims: [],
      doors: [],
      stock_images: [],
      years: [],
      makes: [],
      body_types: [],
      cylinders: [],
      litres: [],
      transmissions: [],
      drivetrains: [],
      passengers: [],
      fuel_types: [],
      fuel_citys: [],
      fuel_hwys: [],
      technical_specification: {},
      generic_equipment: {},
      standard: {},
      optional: {},
      vehicle_tags: [],
      price_recalculation: false,
      default_tax_type: "",
      default_tax_type_secondary: "",
      inventories: [],
      pdf_parser_form: {
        source: "",
        file: null,
        auction_as_seller: "1",
      },
      cost_setting_listing: [],
      selected_expense_name: "",
      selected_company: "",
    };
  },
  computed: {
    ...mapGetters("configuration", ["businessTaxSettings"]),
    ...mapGetters("vendor", ["allVendors"]),
  },
  watch: {
    selected_expense_name: function(newVal, oldVal) {
      if (newVal != oldVal && newVal !== "") {
        if (!isNaN(newVal) && newVal != "null") {
          this.getCostSetting(newVal);
        }
      }
    },
    selected_company: function(newVal, oldVal) {
      if (newVal != oldVal && newVal !== "") {
        if (!isNaN(newVal) && newVal != "null") {
          this.getVendor(newVal);
        }
      }
    },
    businessTaxSettings: function(newVal, oldVal) {
      if (this.load == true) {
        let selected_tax = this.businessTaxSettings.filter(
          (tax) => tax.default == "1",
        );

        if (selected_tax.length > 0) {
          this.inventory_purchase.tax_type = selected_tax[0].name;
          this.default_tax_type = selected_tax[0].name;
          if (selected_tax.length > 1) {
            this.inventory_purchase.tax_type_secondary = selected_tax[1].name;
            this.default_tax_type_secondary = selected_tax[1].name;
          }
        }
      }

      this.load = false;
    },
  },
  methods: {
    ...mapActions("configuration", ["getBusinessTaxSettings"]),
    ...mapActions("vendor", ["getAllVendors"]),
    getInventoryConfigurations() {
      let request_url = `${window.location.origin}/api/business/configurations/active/inventory`;
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      }).then(({ data }) => {
        if (true) {
          for (const key in data) {
            if (data.hasOwnProperty(key)) {
              const element = data[key];
              if (element.key == "editor_default_title") {
                this.form.title = element.value;
              } else if (element.key == "editor_default_vehicle_description") {
                this.form.vehicle_description = element.value;
              } else if (element.key == "vehicle_default_financing") {
                this.form.financing_on = element.value;
              } else if (element.key == "vehicle_default_downpayment") {
                this.form.downpayment = element.value;
              } else if (element.key == "vehicle_default_interest_rate") {
                this.form.interest_rate = element.value;
              } else if (element.key == "vehicle_default_term") {
                this.form.term = element.value;
              } else if (element.key == "vehicle_default_payment_frequency") {
                this.form.payment_frequency = element.value;
              } else if (element.key == "inventory_import_auction_as_seller") {
                this.pdf_parser_form.auction_as_seller = element.value;
              }
            }
          }
        }

        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (element.key == "vehicle_default_vehicle_tags") {
              this.vehicle_tags = JSON.parse(element.value);
            } else if (element.key == "inventory_makes") {
              this.makes = JSON.parse(element.value);
            } else if (element.key == "inventory_body_types") {
              this.body_types = JSON.parse(element.value);
            }
          }
        }
      });
    },
    getLocations() {
      let request_url = `${window.location.origin}/api/business/location/active`;
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      }).then(({ data }) => {
        let locations = data.locations.map((location) => location.name);

        this.form.location = locations[0].name;

        let default_location = data.locations.filter(
          (location) => location.default == "1",
        );

        if (default_location.length > 0) {
          for (const location of default_location) {
            this.form.location = location.name;
          }
        }
      });
    },
    activeSyndication() {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: `${window.location.origin}/api/business/configurations/active/syndication`,
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (element["key"] == "syndication") {
              let active_syndication = JSON.parse(element["value"]);
              active_syndication.forEach((x) => {
                if (x["status"] == "1") {
                  vm.form.syndication.push(x["slug"]);
                }
              });
            }
          }
        }
      });
    },
    getYears() {
      axios.get("/json/year.json").then(({ data }) => (this.years = data));
    },
    getBodyTypes() {
      axios
        .get("/json/body_type.json")
        .then(({ data }) => (this.body_types = data));
    },
    getDoors() {
      axios.get("/json/door.json").then(({ data }) => (this.doors = data));
    },
    getCylinders() {
      axios
        .get("/json/cylinder.json")
        .then(({ data }) => (this.cylinders = data));
    },
    getTransmissions() {
      axios
        .get("/json/transmission.json")
        .then(({ data }) => (this.transmissions = data));
    },
    getDriveTrains() {
      axios
        .get("/json/drivetrain.json")
        .then(({ data }) => (this.drivetrains = data));
    },
    getPassengers() {
      axios
        .get("/json/passenger.json")
        .then(({ data }) => (this.passengers = data));
    },
    getFuelTypes() {
      axios
        .get("/json/fuel_type.json")
        .then(({ data }) => (this.fuel_types = data));
    },
    setArrayAndDefaultValues(property_in_array, property_form, value) {
      this[property_in_array].includes(value)
        ? ""
        : this[property_in_array].push(value);
      this.form[property_form] = value;
    },
    async parsePDF() {
      const vm = this;

      if (
        this.pdf_parser_form.source !== "" &&
        this.pdf_parser_form.file !== null
      ) {
        let formData = new FormData();
        formData.append(
          "file",
          this.pdf_parser_form.file,
          this.pdf_parser_form.file.name,
        );
        formData.append(
          "auction_as_seller",
          this.pdf_parser_form.auction_as_seller,
        );
        let message = "Parsing the PDF. This may take upto few mins ...";
        const toast = new Toast(message);
        toast.fire();

        await axios({
          method: "post",
          url: `${window.location.origin}/api/business/inventory/parser/pdf/${this.pdf_parser_form.source}`,
          data: formData,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
            "Content-Type": "multipart/form-data",
          },
        })
          .then(({ data }) => {
            this.pdf_parser_form.source = "";
            this.pdf_parser_form.file = null;
            this.$refs.file.value = null;

            this.resetErrors();
            this.resetForm();

            let inventory_attributes = [
              "vin",
              "year",
              "make",
              "model",
              "trim",
              "odometer",
              "interior_color",
              "exterior_color",
            ];

            for (let inventory_attribute of inventory_attributes) {
              if (
                this.form.hasOwnProperty(inventory_attribute) &&
                data.hasOwnProperty(inventory_attribute)
              ) {
                this.form[inventory_attribute] = data[inventory_attribute];
              }
            }

            let inventory_purchase_attributes = [
              "street_address",
              "city",
              "province",
              "postal_code",
              "company_name",
              "first_name",
              "price",
            ];

            if (data.hasOwnProperty("has_inventory_purchase")) {
              if (data.has_inventory_purchase) {
                this.has_inventory_purchase = true;

                let inventory_purchase_date = moment(new Date()).format(
                  "MM/DD/YYYY",
                );

                if (
                  data["inventory_purchase"].hasOwnProperty("purchase_date")
                ) {
                  inventory_purchase_date = moment(
                    data["inventory_purchase"]["purchase_date"],
                  );
                }

                inventory_purchase_date = inventory_purchase_date.isValid()
                  ? inventory_purchase_date.format("MM/DD/YYYY")
                  : moment(new Date()).format("MM/DD/YYYY");

                this.inventory_purchase.purchase_date = inventory_purchase_date;

                for (let inventory_purchase_attribute of inventory_purchase_attributes) {
                  if (
                    this.inventory_purchase.hasOwnProperty(
                      inventory_purchase_attribute,
                    ) &&
                    data["inventory_purchase"].hasOwnProperty(
                      inventory_purchase_attribute,
                    )
                  ) {
                    this.inventory_purchase[inventory_purchase_attribute] =
                      data["inventory_purchase"][inventory_purchase_attribute];

                    this.calculateInventoryPurchase();
                  }
                }

                if (data.hasOwnProperty("inventory_purchase_url")) {
                  this.inventory_purchase["url"] =
                    data["inventory_purchase_url"];
                }
              }
            }

            if (data.hasOwnProperty("has_inventory_expenses")) {
              for (let inventory_expense of data["inventory_expenses"]) {
                let expense_date = moment(new Date()).format("MM/DD/YYYY");

                if (inventory_expense.hasOwnProperty("purchase_date")) {
                  expense_date = moment(inventory_expense.purchase_date);
                }

                expense_date = expense_date.isValid()
                  ? expense_date.format("MM/DD/YYYY")
                  : moment(new Date()).format("MM/DD/YYYY");

                this.inventory_expenses.push({
                  purchase_date: expense_date,
                  name: inventory_expense["name"],
                  vendor_name: inventory_expense["vendor_name"],
                  description: "",
                  invoice: inventory_expense["invoice"],
                  payment_reference: "",
                  price: inventory_expense["price"],
                  quantity: inventory_expense["quantity"],
                  discount: 0,
                  tax_type: this.default_tax_type,
                  tax_percentage: "",
                  tax_amount: 0,
                  sub_total: 0,
                  tax_type_secondary: this.default_tax_type_secondary,
                  tax_percentage_secondary: 0,
                  tax_amount_secondary: 0,
                  tax_amount_total: 0,
                });

                this.calculateAnExpense(this.count_of_expenses);

                this.count_of_expenses += 1;
              }
            }

            toast.success("PDF Parsed successfully.");
            toast.close();
          })
          .catch((error) => {
            console.log(error);
            let error_message = "There were some errors in parsing the PDF.";
            toast.fail(error_message);
            toast.close();
          });
      }

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "inventory",
        "inventory_import",
        "pdf_parse",
      );
    },
    async getAvailableTrimsForVIN() {
      Event.fire("load_available_trims_for_a_vin_default", this.form.vin);
      return;
    },
    async getVehicleInformation(data) {
      this.resetErrors();

      if (data.hasOwnProperty("vin_company")) {
        this.form.vin_company = data.vin_company;
      }
      if (data.hasOwnProperty("year")) {
        this.form.year = data.year;
      }
      if (data.hasOwnProperty("make")) {
        this.setArrayAndDefaultValues("makes", "make", data.make);
      }
      if (data.hasOwnProperty("model")) {
        this.form.model = data.model;
      }
      if (data.hasOwnProperty("trim")) {
        this.trims = data.trim;
        this.form.trim = data.trim[0];
      }
      if (data.hasOwnProperty("body_type")) {
        this.setArrayAndDefaultValues(
          "body_types",
          "body_type",
          data.body_type,
        );
      }
      if (data.hasOwnProperty("door")) {
        data.door.forEach((door) => {
          this.setArrayAndDefaultValues("doors", "door", door);
        });
      }
      if (data.hasOwnProperty("drivetrain")) {
        data.drivetrain.forEach((drivetrain) => {
          this.setArrayAndDefaultValues(
            "drivetrains",
            "drivetrain",
            drivetrain,
          );
        });
      }
      if (data.hasOwnProperty("engine")) {
        data.engine.forEach((engine) => {
          this.setArrayAndDefaultValues("cylinders", "engine", engine);
        });
      }
      if (data.hasOwnProperty("litres")) {
        this.litres = data.litres;
        this.form.litres = data.litres[0];
      }
      if (data.hasOwnProperty("transmission")) {
        this.form.transmission = data.transmission[0];
      }

      if (data.hasOwnProperty("passengers")) {
        this.form.passengers = data.passengers[0];
      }

      if (data.hasOwnProperty("fuel_type")) {
        data.fuel_type.forEach((fuel_type) => {
          this.setArrayAndDefaultValues("fuel_types", "fuel_type", fuel_type);
        });
      }
      if (data.hasOwnProperty("fuel_city")) {
        this.fuel_citys = data.fuel_city;
        this.form.fuel_city = data.fuel_city[0];
      }
      if (data.hasOwnProperty("fuel_hwy")) {
        this.fuel_hwys = data.fuel_hwy;
        this.form.fuel_hwy = data.fuel_hwy[0];
      }
      if (data.hasOwnProperty("standard")) {
        this.standard = data.standard;
      }
      if (data.hasOwnProperty("generic_equipment")) {
        this.generic_equipment = data.generic_equipment;
      }
      if (data.hasOwnProperty("technical_specification")) {
        this.technical_specification = data.technical_specification;
      }
      if (data.hasOwnProperty("optional")) {
        this.optional = data.optional;
      }

      this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "inventory",
        "decode",
        "save",
      );
    },
    save() {
      const vm = this;
      let formData = new FormData();
      for (const key in this.form) {
        if (
          this.form.hasOwnProperty(key) &&
          key != "custom_features" &&
          key != "syndication" &&
          key != "vehicle_tags"
        ) {
          formData.append(key, this.form[key]);
        }
      }

      if (Array.isArray(this.form["vehicle_tags"])) {
        formData.append(
          "vehicle_tags",
          JSON.stringify(this.form["vehicle_tags"]),
        );
      } else {
        formData.append("vehicle_tags", "[]");
      }

      if (Array.isArray(this.form["custom_features"])) {
        formData.append(
          "custom_features",
          JSON.stringify(this.form["custom_features"]),
        );
      } else {
        formData.append("custom_features", "[]");
      }

      if (Array.isArray(this.form["syndication"])) {
        formData.append(
          "syndication",
          JSON.stringify(this.form["syndication"]),
        );
      } else {
        formData.append("syndication", "[]");
      }

      if (
        typeof this.technical_specification == "object" &&
        this.technical_specification
      ) {
        if (Object.keys(this.technical_specification).length > 0) {
          formData.append(
            "technical_specification",
            JSON.stringify(this.technical_specification),
          );
        }
      }

      if (typeof this.generic_equipment == "object" && this.generic_equipment) {
        if (Object.keys(this.generic_equipment).length > 0) {
          formData.append(
            "generic_equipment",
            JSON.stringify(this.generic_equipment),
          );
        }
      }

      if (typeof this.standard == "object" && this.standard) {
        if (Object.keys(this.standard).length > 0) {
          formData.append("standard", JSON.stringify(this.standard));
        }
      }

      if (typeof this.optional == "object" && this.optional) {
        if (Object.keys(this.optional).length > 0) {
          formData.append("optional", JSON.stringify(this.optional));
        }
      }

      if (this.has_inventory_purchase) {
        //vehicle purchase form data
        for (const inventory_purchase_key in this.inventory_purchase) {
          if (this.inventory_purchase.hasOwnProperty(inventory_purchase_key)) {
            formData.append(
              `purchase[${inventory_purchase_key}]`,
              this.inventory_purchase[inventory_purchase_key],
            );
          }
        }
      }

      for (const inventory_expense_key in this.inventory_expenses) {
        for (const form_key in this.inventory_expenses[inventory_expense_key]) {
          formData.append(
            `expenses[${inventory_expense_key}][${form_key}]`,
            this.inventory_expenses[inventory_expense_key][form_key],
          );
        }
      }

      let request_url = `${window.location.origin}/api/business/inventory/import`;
      let message = `Importing Inventory ...`;

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
        .then(({ data }) => {
          if (this.inventories.length > 5) {
            this.inventories.splice(0, 1);
          }

          this.inventories.push({
            title: `${this.form.year} ${this.form.make} ${this.form.model}`,
            vin: this.form.vin,
            inventory_id: data.inventory_id,
          });
          this.resetErrors();

          toast.success(data.success);
          toast.close();

          this.resetForm();
        })
        .catch(function(error) {
          vm.$data.errors = error.response.data;

          if (!vm.$data.errors.hasOwnProperty("purchase")) {
            vm.$data.errors.purchase = {};
          }

          if (!vm.$data.errors.hasOwnProperty("expenses")) {
            vm.$data.errors.expenses = {};
          }

          let error_message = "There were some errors while submitting form.";
          toast.fail(error_message);
          toast.close();
        });

      vm.$helpers.gTagClickEvent(
        vm,
        "zd_action",
        "inventory",
        "import",
        "save",
      );
    },
    async calculateLineTotal(
      amount,
      discount = 0,
      quantity = 1,
      tax_type,
      tax_type_secondary,
      tax_amount_total,
      fields,
      base_form,
      base_form_sub_key = null,
      loader_field = "price_recalculation",
    ) {
      const vm = this;
      vm.$data[loader_field] = true;
      if (this.load == false) {
        let formData = new FormData();
        formData.append("amount", amount);
        formData.append("discount", discount);
        formData.append("quantity", quantity);
        formData.append("tax_type", tax_type);
        formData.append("tax_type_secondary", tax_type_secondary);
        formData.append("tax_amount_total", tax_amount_total);

        await axios({
          method: "post",
          url: window.location.origin + "/api/business/calculator/line-total",
          data: formData,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
            "Content-Type": "multipart/form-data",
          },
        })
          .then(({ data }) => {
            for (const [key, value] of Object.entries(fields)) {
              if (base_form_sub_key !== null) {
                vm.$data[base_form][base_form_sub_key][key] = data[value];
              } else {
                vm.$data[base_form][key] = data[value];
              }
            }

            vm.$data[loader_field] = false;
          })
          .catch(({ response }) => {
            let error = "There were some errors in calculating the taxes";
            const toast = new Toast(error);
            toast.fire();
            toast.fail(error);
            toast.close();
            vm.$data[loader_field] = false;
          });
      }
    },
    deleteExpenseError(expense_index, attribute) {
      if (this.errors.hasOwnProperty("expenses")) {
        if (this.errors.expenses.hasOwnProperty(`expense_${expense_index}`)) {
          if (
            this.errors.expenses[`expense_${expense_index}`].hasOwnProperty(
              attribute,
            )
          ) {
            delete this.errors.expenses[`expense_${expense_index}`][attribute];
          }
        }
      }
    },
    showTitle(expense_index, attribute) {
      if (this.errors.hasOwnProperty("expenses")) {
        if (this.errors.expenses.hasOwnProperty(`expense_${expense_index}`)) {
          if (
            this.errors.expenses[`expense_${expense_index}`].hasOwnProperty(
              attribute,
            )
          ) {
            return this.errors.expenses[`expense_${expense_index}`][attribute];
          }
        }
      }

      return "";
    },
    addANewExpense() {
      this.inventory_expenses.push({
        purchase_date: moment(new Date()).format("MM/DD/YYYY"),
        name: "",
        vendor_name: "",
        description: "",
        invoice: "",
        payment_reference: "",
        price: 0,
        quantity: 1,
        discount: 0,
        tax_type: this.default_tax_type,
        tax_percentage: "",
        tax_amount: 0,
        sub_total: 0,
        tax_type_secondary: this.default_tax_type_secondary,
        tax_percentage_secondary: 0,
        tax_amount_secondary: 0,
        tax_amount_total: 0,
      });
      this.count_of_expenses += 1;
    },
    calculateInventoryPurchase(attribute = null) {
      const vm = this;

      if (attribute === "tax_type" || attribute === "tax_type_secondary") {
        if (vm.inventory_purchase.tax_type === "Other") {
          vm.inventory_purchase.tax_type_secondary = "";
        }
      } else if (attribute !== null) {
        vm.inventory_purchase[attribute] = this.$helpers.coerceToNumberString(
          vm.inventory_purchase[attribute],
        );
      }

      if (this.load === false) {
        setTimeout(function() {
          vm.calculateLineTotal(
            vm.inventory_purchase.price,
            vm.inventory_purchase.discount,
            1,
            vm.inventory_purchase.tax_type,
            vm.inventory_purchase.tax_type_secondary,
            vm.inventory_purchase.tax_amount_total,
            {
              tax_percentage: "tax_percentage",
              tax_amount: "tax_amount",
              tax_percentage_secondary: "tax_percentage_secondary",
              tax_amount_secondary: "tax_amount_secondary",
              tax_amount_total: "tax_amount_total",
              sub_total: "sub_total",
            },
            "inventory_purchase",
          );
        }, 400);
      }
    },
    calculateAnExpense(n, attribute = null) {
      const vm = this;

      if (attribute === "tax_type" || attribute === "tax_type_secondary") {
        if (vm.inventory_expenses[n]["tax_type"] === "Other") {
          vm.inventory_expenses[n]["tax_type_secondary"] = "";
        }
      } else if (attribute !== null) {
        vm.inventory_expenses[n][
          attribute
        ] = this.$helpers.coerceToNumberString(
          vm.inventory_expenses[n][attribute],
        );
      }

      setTimeout(function() {
        vm.calculateLineTotal(
          vm.inventory_expenses[n].price,
          vm.inventory_expenses[n].discount,
          vm.inventory_expenses[n].quantity,
          vm.inventory_expenses[n].tax_type,
          vm.inventory_expenses[n].tax_type_secondary,
          vm.inventory_expenses[n].tax_amount_total,
          {
            tax_percentage: "tax_percentage",
            tax_amount: "tax_amount",
            tax_percentage_secondary: "tax_percentage_secondary",
            tax_amount_secondary: "tax_amount_secondary",
            tax_amount_total: "tax_amount_total",
            sub_total: "sub_total",
          },
          `inventory_expenses`,
          n,
        );
      }, 400);
    },
    resetErrors() {
      this.errors = {};
      this.errors.purchase = {};
      this.errors.expenses = {};
    },
    resetForm() {
      this.inventory_expenses = [];
      this.count_of_expenses = 0;

      let form_string_attributes = [
        "video_url",
        "vehicle_history_report",
        "vin_company",
        "vin",
        "stock_no",
        "year",
        "make",
        "model",
        "trim",
        "body_type",
        "door",
        "engine",
        "litres",
        "transmission",
        "drivetrain",
        "passengers",
        "odometer",
        "fuel_type",
        "fuel_city",
        "fuel_hwy",
        "exterior_color",
        "interior_color",
        "price",
        "hide_price",
        "special_price_on",
        "special_price",
      ];

      for (let attribute of form_string_attributes) {
        this.form[attribute] = "";
      }

      this.form.km_miles = "KM";

      this.standard = {};
      this.generic_equipment = {};
      this.technical_specification = {};
      this.optional = {};

      let purchase_string_attributes = [
        "company_name",
        "first_name",
        "last_name",
        "rin",
        "tax_number",
        "street_address",
        "apt",
        "city",
        "province",
        "postal_code",
        "country",
        "phone",
        "fax",
        "mobile",
        "email",
        "driver_licence",
        "plate_number",
        "key_count",
        "mvda",
        "sales_person_registration",
        "url",
      ];

      for (let attribute of purchase_string_attributes) {
        this.inventory_purchase[attribute] = "";
      }

      this.inventory_purchase.purchase_date = moment(new Date()).format(
        "MM/DD/YYYY",
      );

      let purchase_default_zero_attributes = [
        "rin_or_dl_flag",
        "tax_override_flag",
        "price",
        "discount",
        "tax_amount",
        "tax_percentage",
        "tax_amount_secondary",
        "tax_percentage_secondary",
        "tax_amount_total",
        "has_been_paid_for",
        "has_title_been_received",
        "has_title_been_transferred",
        "has_lien",
        "lien_amount",
        "lien_has_been_paid",
        "sub_total",
      ];

      for (let attribute of purchase_default_zero_attributes) {
        this.inventory_purchase[attribute] = 0;
      }

      this.inventory_purchase.purchase_from_flag = 1;
      this.inventory_purchase.tax_type = this.default_tax_type;
      this.inventory_purchase.tax_type_secondary = this.default_tax_type_secondary;
    },
    costSettingListing() {
      let request_url =
        window.location.origin +
        "/api/business/setting/costs?column=name&order=ASC&paginate=false";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
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
        url: window.location.origin + "/api/business/setting/costs/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          let inventory_expense = {
            purchase_date: moment(new Date()).format("MM/DD/YYYY"),
            name: "",
            vendor_name: "",
            description: "",
            invoice: "",
            payment_reference: "",
            price: 0,
            quantity: 1,
            discount: 0,
            tax_type: this.default_tax_type,
            tax_percentage: "",
            tax_amount: 0,
            sub_total: 0,
            tax_type_secondary: this.default_tax_type_secondary,
            tax_percentage_secondary: 0,
            tax_amount_secondary: 0,
            tax_amount_total: 0,
          };

          for (const key in inventory_expense) {
            if (
              inventory_expense.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              inventory_expense[key] = data[key];
            }
          }

          this.inventory_expenses.push(inventory_expense);

          this.calculateAnExpense(this.count_of_expenses);

          this.count_of_expenses += 1;

          this.selected_expense_name = "";
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the cost setting";
          console.log(error);
        });
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
          if (this.errors.hasOwnProperty("purchase")) {
            this.errors.purchase = {};
          }

          for (const key in this.inventory_purchase) {
            if (
              this.inventory_purchase.hasOwnProperty(key) &&
              data.hasOwnProperty(key)
            ) {
              this.inventory_purchase[key] = data[key];
            }
          }

          this.selected_company = "";
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the vendor";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
    openInventoryNewTab(id) {
      let routeData = this.$router.resolve({
        name: "inventory-update",
        params: { id: id },
      });
      window.open(routeData.href, "_blank");
    },
    saveFile(event) {
      this.pdf_parser_form.file = event.target.files[0];
    },
    parseAttachmentUrl(url) {
      return this.$helpers.parseAbsoluteOrRelativeUrl(window.global.cdn, url);
    },
    changeAuctionAsSeller() {
      const vm = this;
      let formData = new FormData();
      formData.append(
        "inventory_import_auction_as_seller",
        this.pdf_parser_form.auction_as_seller,
      );

      let request_url = window.location.origin + "/api/business/configurations";

      let message = "Saving Auction As Seller Settings ...";
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
        .then(({ data }) => {
          let success_message = "Finished Saving.";
          toast.success(success_message);
          toast.close();
        })
        .catch((error) => {
          let error_message = "There were some errors in saving settings.";
          toast.fail(error_message);
          toast.close();
        });
    },
  },
  created() {
    this.getYears();
    this.getBodyTypes();
    this.getDoors();
    this.getCylinders();
    this.getTransmissions();
    this.getDriveTrains();
    this.getPassengers();
    this.getFuelTypes();
    this.getInventoryConfigurations();
    this.getLocations();
    this.activeSyndication();
    this.getBusinessTaxSettings();
    this.getAllVendors();
    this.costSettingListing();

    const vm = this;
    this.inventory_purchase.purchase_date = moment(new Date()).format(
      "MM/DD/YYYY",
    );
  },
  mounted() {
    const vm = this;
    Event.listen("load_available_data_for_a_vin_default", (data) => {
      vm.getVehicleInformation(data);
    });
  },
  beforeDestroy() {
    Event.vue.$off("load_available_data_for_a_vin_default", null);
  },
};
</script>
<style lang="scss">
#import-inventory-purchase-section .vdpComponent input {
  padding-left: 2.2em !important;
  line-height: 1.4;
  color: #475f7b;
  background-color: #ffffff;
  background-clip: padding-box;
  border: 1px solid #dfe3e7;
  border-radius: 0.267rem;

  /** Replicating the css for form-control */
  display: block;
  width: 100%;
  height: calc(1.4em + 0.94rem + 3.7px);
  padding: 0.47rem 0.8rem;
  padding-right: 0.8rem;
  padding-left: 0.8rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.4;
  color: #475f7b;
  background-color: #ffffff;
  background-clip: padding-box;
  border: 1px solid #dfe3e7;
  border-top-color: rgb(223, 227, 231);
  border-right-color: rgb(223, 227, 231);
  border-bottom-color: rgb(223, 227, 231);
  border-left-color: rgb(223, 227, 231);
  border-radius: 0.267rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

input:read-only {
  background: #eee !important;
}

#import-inventory-expense-section .vdpComponent input {
  padding-left: 2.2em !important;
  line-height: 1.4;
  color: #475f7b;
  background-color: #ffffff;
  background-clip: padding-box;
  border: 1px solid #dfe3e7;
  border-radius: 0.267rem;

  /** Replicating the css for form-control */
  display: block;
  width: 100%;
  height: calc(1.4em + 0.94rem + 3.7px);
  padding: 0.47rem 0.8rem;
  padding-right: 0.8rem;
  padding-left: 0.8rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.4;
  color: #475f7b;
  background-color: #ffffff;
  background-clip: padding-box;
  border: 1px solid #dfe3e7;
  border-top-color: rgb(223, 227, 231);
  border-right-color: rgb(223, 227, 231);
  border-bottom-color: rgb(223, 227, 231);
  border-left-color: rgb(223, 227, 231);
  border-radius: 0.267rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

input:read-only {
  background: #eee !important;
}

#zd-toast-stack {
  position: fixed;
  right: 0px;
  z-index: 999;
}
</style>
