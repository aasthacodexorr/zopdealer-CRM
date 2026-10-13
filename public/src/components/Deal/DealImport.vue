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
              id="deal-import-tab"
              data-toggle="tab"
              href="#deal-import-panel"
              aria-controls="Deal Import"
              role="tab"
              aria-selected="true"
            >
              <span class="">Import Retail Deal</span>
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
              id="deal-import-panel"
              aria-labelledby="Deal Import Tab"
              role="tabpanel"
            >
              <div id="zd-toast-stack">
                <div
                  class="alert alert-secondary m-1"
                  role="alert"
                  :key="`saved_deal_${saved_deal.vin}`"
                  v-for="(saved_deal, current_deal_index) in deals"
                >
                  <div>
                    <b>
                      {{ saved_deal.line_1 }} - <br />
                      {{ saved_deal.line_2 }}
                    </b>
                  </div>
                  <div class="text-center">
                    <i
                      class="bx bx-link-external pr-1"
                      style="cursor: pointer"
                      @click.prevent="openDealNewTab(saved_deal.deal_id)"
                    ></i>
                    <i
                      style="cursor: pointer"
                      class="bx bx-x-circle"
                      @click.prevent="deals.splice(current_deal_index, 1)"
                    ></i>
                  </div>
                </div>
              </div>
              <section id="deal-import-in-section">
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
                              <div class="col-xs-12 col-md-4">
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
                                    <option value="dealertrack">
                                      Dealertrack
                                    </option>
                                  </select>
                                </div>
                              </div>
                              <!-- template source : END -->
                              <!-- file : START -->
                              <div
                                class="col-xs-12 col-md-4"
                                v-show="pdf_parser_form.source !== ``"
                              >
                                <div class="form-group">
                                  <!-- <label class="" for="file"
                                    >File</label
                                  > -->
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
                                class="col-md-4 col-xs-12"
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
                                  class="btn btn-secondary p-50 text-uppercase text-white"
                                  target="_blank"
                                  v-show="pdf_parser_form.file"
                                  rel="noopener noreferrer"
                                  @click.prevent="openFileBlobAsPDF"
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
                              Please check all the fees and tax rates as they
                              might be inaccurate.
                            </h6>
                          </div>
                          <!-- Import Section : END -->

                          <!-- Details Section : START -->
                          <div>
                            <div class="card-header border-line-bottom-title">
                              <h5 class="card-title">Details</h5>
                            </div>
                          </div>
                          <div class="row">
                            <!-- date : START -->
                            <div class="col-md-2 col-xs-12">
                              <div class="form-group">
                                <label for="date">Date</label>
                                <fieldset
                                  class="has-icon-left"
                                  style="position: relative"
                                >
                                  <date-picker
                                    placeholder="Date"
                                    :clearable="false"
                                    :format="'MM/DD/YYYY'"
                                    :value-type="'MM/DD/YYYY'"
                                    id="date"
                                    v-model="form.date"
                                    :class="{
                                      'is-invalid': errors.hasOwnProperty(
                                        'date',
                                      ),
                                    }"
                                    @change="delete errors.date"
                                    :title="errors.date"
                                  >
                                  </date-picker>
                                </fieldset>
                                <small
                                  id="date-error"
                                  class="form-text invalid-feedback"
                                  >{{ errors.date }}</small
                                >
                              </div>
                            </div>
                            <!-- date : END -->

                            <!-- type : START -->
                            <div class="col-xs-12 col-md-2">
                              <div class="form-group">
                                <label for="type">Deal Type</label>
                                <select
                                  id="type"
                                  class="custom-select"
                                  name="type"
                                  v-model="form.type"
                                >
                                  <option value="Cash">Cash</option>
                                  <option value="Financed">Financed</option>
                                </select>
                                <small
                                  id="type-error"
                                  class="form-text invalid-feedback"
                                  >{{ errors.type }}</small
                                >
                              </div>
                            </div>
                            <!-- type : END -->
                            <!-- status : START -->
                            <div class="col-xs-12 col-md-2">
                              <div class="form-group">
                                <label for="status">Deal Status</label>
                                <select
                                  id="status"
                                  class="custom-select"
                                  v-model="form.status"
                                  name="status"
                                >
                                  <option
                                    :value="current_status"
                                    v-for="current_status in getDealStatuses"
                                    v-show="
                                      current_status === 'Open' ||
                                        current_status === 'Quote'
                                    "
                                    :key="`status_${current_status}`"
                                  >
                                    {{ current_status }}
                                  </option>
                                </select>
                                <small
                                  id="status-error"
                                  class="form-text invalid-feedback"
                                  >{{ errors.status }}</small
                                >
                              </div>
                            </div>
                            <!-- status : END -->

                            <!-- approved : start -->
                            <div class="col-md-2 col-xs-12 form-group">
                              <label for="approved_by">Approved By </label>
                              <select
                                id="approved_by"
                                class="custom-select"
                                name="approved_by"
                                v-model="form.approved_by"
                              >
                                <option disabled value="">
                                  -- Select Approver --
                                </option>
                                <option
                                  :value="user.user_id"
                                  v-for="user in managersAndOwners"
                                  :key="user.user_id + '_approved_by'"
                                  v-text="user.name"
                                ></option>
                              </select>

                              <small
                                id="approved_by-error"
                                class="form-text invalid-feedback"
                                >{{ errors.approved_by }}</small
                              >
                            </div>
                            <!-- approved : end -->

                            <!-- salesperson : start -->
                            <div class="col-md-2 col-xs-12 form-group">
                              <label for="salesperson">Salesperson </label>
                              <select
                                id="salesperson"
                                class="custom-select"
                                name="salesperson"
                                v-model="form.salesperson"
                              >
                                <option disabled value="">
                                  -- Select Salesperson --
                                </option>
                                <option
                                  :value="user.user_id"
                                  v-for="user in salesPersons"
                                  :key="user.user_id + '_salesperson'"
                                  v-text="user.name"
                                ></option>
                              </select>

                              <small
                                id="salesperson-error"
                                class="form-text invalid-feedback"
                                >{{ errors.salesperson }}</small
                              >
                            </div>
                            <!-- salesperson : end -->
                          </div>
                          <!-- Details Section : END -->

                          <!-- Summary Section : START -->
                          <div>
                            <div class="card-header border-line-bottom-title">
                              <h5 class="card-title">Summary</h5>
                            </div>
                          </div>

                          <!-- Options : START -->

                          <div>
                            <!-- skip_inventory : START -->
                            <div
                              class="card-header border-line-bottom-title m-50"
                            >
                              <h5 class="checkbox card-title">
                                <input
                                  type="checkbox"
                                  class="checkbox-input"
                                  id="skip_inventory"
                                  v-model="skip_inventory"
                                  :true-value="true"
                                  :false-value="false"
                                />
                                <label for="skip_inventory"
                                  >I already have the vehicle saved in the
                                  system</label
                                >
                              </h5>
                            </div>
                            <!-- skip_inventory : END -->
                            <!-- skip_primary_customer : START -->
                            <div
                              class="card-header border-line-bottom-title m-50"
                            >
                              <h5 class="checkbox card-title">
                                <input
                                  type="checkbox"
                                  class="checkbox-input"
                                  id="skip_primary_customer"
                                  v-model="skip_primary_customer"
                                  :true-value="true"
                                  :false-value="false"
                                />
                                <label for="skip_primary_customer"
                                  >I already have the customer saved in the
                                  system</label
                                >
                              </h5>
                            </div>
                            <!-- skip_primary_customer : END -->
                          </div>
                          <!-- Options : END -->
                          <div class="row">
                            <!-- CARD 1 : START -->
                            <div
                              class="card col-xs-12 col-md-3"
                              v-if="!skip_inventory"
                            >
                              <div class="card-content">
                                <div class="card-body pb-1 mx-25 row px-0">
                                  <h4 class="card-title col-6">Inventory</h4>
                                  <div class="col-12">
                                    <!-- row : START -->
                                    <div class="row">
                                      <!-- inventory vin : START -->
                                      <div class="col-xs-12 col-md-8 px-25">
                                        <div class="form-group">
                                          <label for="inventory_vin">VIN</label>
                                          <input
                                            id="inventory_vin"
                                            type="text"
                                            placeholder="VIN"
                                            class="form-control"
                                            v-model="deal_inventory_form.vin"
                                            :class="{
                                              'is-invalid':
                                                errors.hasOwnProperty(
                                                  'inventory',
                                                ) &&
                                                errors.inventory.hasOwnProperty(
                                                  'vin',
                                                ),
                                            }"
                                            @keyup="delete errors.inventory.vin"
                                            :title="errors.inventory.vin"
                                          />
                                          <!-- <small
                                            id="inventory_vin-error"
                                            class="form-text invalid-feedback"
                                            >{{ errors.inventory.vin }}</small
                                          > -->
                                        </div>
                                      </div>
                                      <div class="col-xs-12 col-md-4 px-25">
                                        <button
                                          class="btn btn-primary"
                                          style="
                                            position: absolute;
                                            bottom: 1em;
                                          "
                                          @click="
                                            getAvailableTrimsForVIN(
                                              'deal_inventory_form',
                                            )
                                          "
                                          type="button"
                                        >
                                          Decode
                                        </button>
                                      </div>
                                      <!-- inventory vin : END -->

                                      <!-- inventory year : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label for="inventory_year"
                                            >Year</label
                                          >
                                          <fieldset
                                            class="has-icon-left"
                                            style="position: relative"
                                          >
                                            <input
                                              id="inventory_year"
                                              type="text"
                                              placeholder="Year"
                                              class="form-control"
                                              v-model="deal_inventory_form.year"
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'inventory',
                                                  ) &&
                                                  errors.inventory.hasOwnProperty(
                                                    'year',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.inventory.year
                                              "
                                              :title="errors.inventory.year"
                                            />
                                            <div class="form-control-position">
                                              <i
                                                class="bx bxs-calendar-alt font-medium-3"
                                              ></i>
                                            </div>
                                          </fieldset>
                                          <small
                                            id="inventory_year-error"
                                            class="form-text invalid-feedback"
                                            >{{ errors.inventory.year }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- inventory year : END -->

                                      <!-- inventory make : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label for="inventory_make"
                                            >Make</label
                                          >
                                          <fieldset
                                            class="has-icon-left"
                                            style="position: relative"
                                          >
                                            <input
                                              id="inventory_make"
                                              type="text"
                                              placeholder="Make"
                                              v-model="deal_inventory_form.make"
                                              class="form-control"
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'inventory',
                                                  ) &&
                                                  errors.inventory.hasOwnProperty(
                                                    'make',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.inventory.make
                                              "
                                              :title="errors.inventory.make"
                                            />
                                            <div class="form-control-position">
                                              <i
                                                class="bx bx-car font-medium-3"
                                              ></i>
                                            </div>
                                          </fieldset>
                                          <small
                                            id="inventory_make-error"
                                            class="form-text invalid-feedback"
                                            >{{ errors.inventory.make }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- inventory make : END -->

                                      <!-- inventory model : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label for="inventory_model"
                                            >Model</label
                                          >
                                          <fieldset
                                            class="has-icon-left"
                                            style="position: relative"
                                          >
                                            <input
                                              id="inventory_model"
                                              type="text"
                                              placeholder="Model"
                                              v-model="
                                                deal_inventory_form.model
                                              "
                                              class="form-control"
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'inventory',
                                                  ) &&
                                                  errors.inventory.hasOwnProperty(
                                                    'model',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.inventory.model
                                              "
                                              :title="errors.inventory.model"
                                            />
                                            <div class="form-control-position">
                                              <i
                                                class="bx bxs-cog font-medium-3"
                                              ></i>
                                            </div>
                                          </fieldset>
                                          <small
                                            id="inventory_model-error"
                                            class="form-text invalid-feedback"
                                            >{{ errors.inventory.model }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- inventory model : END -->

                                      <!-- inventory trim : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label for="inventory_trim"
                                            >Trim</label
                                          >
                                          <fieldset
                                            class="has-icon-left"
                                            style="position: relative"
                                          >
                                            <input
                                              id="inventory_trim"
                                              type="text"
                                              placeholder="Trim"
                                              class="form-control"
                                              v-model="deal_inventory_form.trim"
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'inventory',
                                                  ) &&
                                                  errors.inventory.hasOwnProperty(
                                                    'trim',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.inventory.trim
                                              "
                                              :title="errors.inventory.trim"
                                            />
                                            <div class="form-control-position">
                                              <i
                                                class="bx bxs-detail font-medium-3"
                                              ></i>
                                            </div>
                                          </fieldset>
                                          <small
                                            id="inventory_trim-error"
                                            class="form-text invalid-feedback"
                                            >{{ errors.inventory.trim }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- inventory trim : END -->

                                      <!-- inventory odometer : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label for="odometer">Odometer</label>
                                          <fieldset
                                            class="has-icon-left"
                                            style="position: relative"
                                          >
                                            <input
                                              id="odometer"
                                              type="text"
                                              placeholder="Odometer"
                                              v-model="
                                                deal_inventory_form.odometer
                                              "
                                              class="form-control"
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'inventory',
                                                  ) &&
                                                  errors.inventory.hasOwnProperty(
                                                    'odometer',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.inventory.odometer
                                              "
                                              :title="errors.inventory.odometer"
                                            />
                                            <div class="form-control-position">
                                              <i
                                                class="bx bxs-dashboard font-medium-3"
                                              ></i>
                                            </div>
                                          </fieldset>
                                          <small
                                            id="odometer-error"
                                            class="form-text invalid-feedback"
                                            >{{
                                              errors.inventory.odometer
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- inventory odometer : END -->

                                      <!-- km_miles : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label for="inventory-km_miles"
                                            >Distance Type</label
                                          >
                                          <select
                                            id="inventory-km_miles"
                                            class="custom-select"
                                            name="km_miles"
                                            v-model="
                                              deal_inventory_form.km_miles
                                            "
                                            :class="{
                                              'is-invalid':
                                                errors.hasOwnProperty(
                                                  'inventory',
                                                ) &&
                                                errors.inventory.hasOwnProperty(
                                                  'km_miles',
                                                ),
                                            }"
                                            @keyup="
                                              delete errors.inventory.km_miles
                                            "
                                            :title="errors.inventory.km_miles"
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
                                            >{{
                                              errors.inventory.km_miles
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- km_miles : END -->

                                      <!-- body_type : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label for="inventory-body_type"
                                            >Body Type</label
                                          >
                                          <select
                                            id="inventory-body_type"
                                            class="custom-select"
                                            v-model="
                                              deal_inventory_form.body_type
                                            "
                                            name="body_type"
                                            :class="{
                                              'is-invalid':
                                                errors.hasOwnProperty(
                                                  'inventory',
                                                ) &&
                                                errors.inventory.hasOwnProperty(
                                                  'body_type',
                                                ),
                                            }"
                                            @keyup="
                                              delete errors.inventory.body_type
                                            "
                                            :title="errors.inventory.body_type"
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
                                            >{{
                                              errors.inventory.body_type
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- body_type : END -->

                                      <!-- exterior_color : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
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
                                              class="form-control"
                                              v-model="
                                                deal_inventory_form.exterior_color
                                              "
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'inventory',
                                                  ) &&
                                                  errors.inventory.hasOwnProperty(
                                                    'exterior_color',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.inventory
                                                  .exterior_color
                                              "
                                              :title="
                                                errors.inventory.exterior_color
                                              "
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
                                            >{{
                                              errors.inventory.exterior_color
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- exterior_color : END -->

                                      <!-- interior_color : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
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
                                              class="form-control"
                                              v-model="
                                                deal_inventory_form.interior_color
                                              "
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'inventory',
                                                  ) &&
                                                  errors.inventory.hasOwnProperty(
                                                    'interior_color',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.inventory
                                                  .interior_color
                                              "
                                              :title="
                                                errors.inventory.interior_color
                                              "
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
                                            >{{
                                              errors.inventory.interior_color
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- interior_color : END -->
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- CARD 1 : END -->

                            <!-- CARD 2 : START -->
                            <div
                              class="card col-xs-12 col-md-3"
                              v-if="!skip_primary_customer"
                            >
                              <div class="card-content">
                                <div class="card-body pb-1 mx-25 row px-0">
                                  <h4 class="card-title col-6">Customer</h4>
                                  <div class="col-12">
                                    <!-- row : START -->
                                    <div class="row">
                                      <!-- customer first_name : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label for="customer_first_name"
                                            >First Name</label
                                          >
                                          <fieldset
                                            class="has-icon-left"
                                            style="position: relative"
                                          >
                                            <input
                                              id="customer_first_name"
                                              type="text"
                                              placeholder="First Name*"
                                              class="form-control"
                                              v-model="
                                                deal_customer_form.first_name
                                              "
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'primary_customer',
                                                  ) &&
                                                  errors.primary_customer.hasOwnProperty(
                                                    'first_name',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.primary_customer
                                                  .first_name
                                              "
                                              :title="
                                                errors.primary_customer
                                                  .first_name
                                              "
                                            />
                                            <div class="form-control-position">
                                              <i
                                                class="bx bxs-user font-medium-3"
                                              ></i>
                                            </div>
                                          </fieldset>
                                          <small
                                            id="customer_first_name-error"
                                            class="form-text invalid-feedback"
                                            >{{
                                              errors.primary_customer.first_name
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- customer first_name : END -->

                                      <!-- customer last_name : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label for="customer_last_name"
                                            >Last Name</label
                                          >
                                          <fieldset
                                            class="has-icon-left"
                                            style="position: relative"
                                          >
                                            <input
                                              id="customer_last_name"
                                              type="text"
                                              placeholder="Last Name"
                                              class="form-control"
                                              v-model="
                                                deal_customer_form.last_name
                                              "
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'primary_customer',
                                                  ) &&
                                                  errors.primary_customer.hasOwnProperty(
                                                    'last_name',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.primary_customer
                                                  .last_name
                                              "
                                              :title="
                                                errors.primary_customer
                                                  .last_name
                                              "
                                            />
                                          </fieldset>
                                          <small
                                            id="customer_last_name-error"
                                            class="form-text invalid-feedback"
                                            >{{
                                              errors.primary_customer.last_name
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- customer last_name : END -->

                                      <!-- customer date_of_birth : START -->
                                      <div class="col-xs-12 col-md-12 px-25">
                                        <div class="form-group">
                                          <label for="customer_date_of_birth"
                                            >Date Of Birth</label
                                          >
                                          <fieldset
                                            class="has-icon-left"
                                            style="position: relative"
                                          >
                                            <date-picker
                                              placeholder="Date of birth"
                                              :format="'MM/DD/YYYY'"
                                              :value-type="'MM/DD/YYYY'"
                                              id="date_of_birth"
                                              v-model="
                                                deal_customer_form.date_of_birth
                                              "
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'primary_customer',
                                                  ) &&
                                                  errors.primary_customer.hasOwnProperty(
                                                    'date_of_birth',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.primary_customer
                                                  .date_of_birth
                                              "
                                              :title="
                                                errors.primary_customer
                                                  .date_of_birth
                                              "
                                            >
                                            </date-picker>
                                          </fieldset>
                                          <small
                                            id="customer_date_of_birth-error"
                                            class="form-text invalid-feedback"
                                          >
                                            {{
                                              errors.primary_customer
                                                .date_of_birth
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- customer date_of_birth : END -->

                                      <div class="w-100"></div>

                                      <!-- customer driver_licence : START -->
                                      <div class="col-xs-12 col-md-12 px-25">
                                        <div class="form-group">
                                          <label for="customer_driver_licence"
                                            >Driving License</label
                                          >
                                          <fieldset
                                            class="has-icon-left"
                                            style="position: relative"
                                          >
                                            <input
                                              id="customer_driver_licence"
                                              type="text"
                                              placeholder="Driving License"
                                              class="form-control"
                                              v-model="
                                                deal_customer_form.driver_licence
                                              "
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'primary_customer',
                                                  ) &&
                                                  errors.primary_customer.hasOwnProperty(
                                                    'driver_licence',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.primary_customer
                                                  .driver_licence
                                              "
                                              :title="
                                                errors.primary_customer
                                                  .driver_licence
                                              "
                                            />
                                            <div class="form-control-position">
                                              <i
                                                class="bx bx-id-card font-medium-3"
                                              ></i>
                                            </div>
                                          </fieldset>
                                          <small
                                            id="customer_driver_licence-error"
                                            class="form-text invalid-feedback"
                                            >{{
                                              errors.primary_customer
                                                .driver_licence
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- customer driver_licence : END -->

                                      <!-- customer driver_licence_expiry_date : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label
                                            for="customer_driver_licence_expiry_date"
                                            >DL Expiry Date</label
                                          >
                                          <fieldset
                                            class="has-icon-left"
                                            style="position: relative"
                                          >
                                            <date-picker
                                              placeholder="DL Expiry Date"
                                              v-model="
                                                deal_customer_form.driver_licence_expiry_date
                                              "
                                              :format="'MM/DD/YYYY'"
                                              :value-type="'MM/DD/YYYY'"
                                              id="driver_licence_expiry_date"
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'primary_customer',
                                                  ) &&
                                                  errors.primary_customer.hasOwnProperty(
                                                    'driver_licence_expiry_date',
                                                  ),
                                              }"
                                              @change="
                                                delete errors.primary_customer
                                                  .driver_licence_expiry_date
                                              "
                                              :title="
                                                errors.primary_customer
                                                  .driver_licence_expiry_date
                                              "
                                            >
                                            </date-picker>
                                          </fieldset>
                                          <small
                                            id="customer_driver_licence_expiry_date-error"
                                            class="form-text invalid-feedback"
                                            >{{
                                              errors.primary_customer
                                                .driver_licence_expiry_date
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- customer driver_licence_expiry_date : END -->

                                      <div
                                        class="col-md-12 col-xs-12 px-0 py-1"
                                      >
                                        <suggest-location
                                          :elementId="'default'"
                                        ></suggest-location>
                                      </div>

                                      <!-- customer street_address : START -->
                                      <div class="col-xs-12 col-md-12 px-25">
                                        <div class="form-group">
                                          <label for="customer_street_address"
                                            >Street Address</label
                                          >
                                          <fieldset
                                            class="has-icon-left"
                                            style="position: relative"
                                          >
                                            <input
                                              id="customer_street_address"
                                              type="text"
                                              placeholder="Street Address"
                                              class="form-control"
                                              v-model="
                                                deal_customer_form.street_address
                                              "
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'primary_customer',
                                                  ) &&
                                                  errors.primary_customer.hasOwnProperty(
                                                    'street_address',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.primary_customer
                                                  .street_address
                                              "
                                              :title="
                                                errors.primary_customer
                                                  .street_address
                                              "
                                            />
                                            <div class="form-control-position">
                                              <i
                                                class="bx bx-directions font-medium-3"
                                              ></i>
                                            </div>
                                          </fieldset>
                                          <small
                                            id="customer_street_address-error"
                                            class="form-text invalid-feedback"
                                            >{{
                                              errors.primary_customer
                                                .street_address
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- customer street_address : END -->

                                      <!-- customer city : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label for="customer_city"
                                            >City</label
                                          >
                                          <fieldset
                                            class=""
                                            style="position: relative"
                                          >
                                            <input
                                              id="customer_city"
                                              type="text"
                                              placeholder="City"
                                              class="form-control"
                                              v-model="deal_customer_form.city"
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'primary_customer',
                                                  ) &&
                                                  errors.primary_customer.hasOwnProperty(
                                                    'city',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.primary_customer
                                                  .city
                                              "
                                              :title="
                                                errors.primary_customer.city
                                              "
                                            />
                                          </fieldset>
                                          <small
                                            id="customer_city-error"
                                            class="form-text invalid-feedback"
                                            >{{
                                              errors.primary_customer.city
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- customer city : END -->

                                      <!-- customer province : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label for="customer_province"
                                            >Province / State</label
                                          >
                                          <fieldset
                                            class=""
                                            style="position: relative"
                                          >
                                            <input
                                              id="customer_province"
                                              type="text"
                                              placeholder="Province / State"
                                              class="form-control"
                                              v-model="
                                                deal_customer_form.province
                                              "
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'primary_customer',
                                                  ) &&
                                                  errors.primary_customer.hasOwnProperty(
                                                    'province',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.primary_customer
                                                  .province
                                              "
                                              :title="
                                                errors.primary_customer.province
                                              "
                                            />
                                          </fieldset>
                                          <small
                                            id="customer_province-error"
                                            class="form-text invalid-feedback"
                                            >{{
                                              errors.primary_customer.province
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- customer province : END -->

                                      <!-- customer country : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label for="customer_country"
                                            >Country</label
                                          >
                                          <fieldset
                                            class=""
                                            style="position: relative"
                                          >
                                            <input
                                              id="customer_country"
                                              type="text"
                                              placeholder="Country"
                                              class="form-control"
                                              v-model="
                                                deal_customer_form.country
                                              "
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'primary_customer',
                                                  ) &&
                                                  errors.primary_customer.hasOwnProperty(
                                                    'country',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.primary_customer
                                                  .country
                                              "
                                              :title="
                                                errors.primary_customer.country
                                              "
                                            />
                                          </fieldset>
                                          <small
                                            id="customer_country-error"
                                            class="form-text invalid-feedback"
                                            >{{
                                              errors.primary_customer.country
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- customer country : END -->

                                      <!-- customer postal code : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label for="customer_postal_code"
                                            >Postal Code / ZIP</label
                                          >
                                          <fieldset
                                            class=""
                                            style="position: relative"
                                          >
                                            <input
                                              id="customer_postal_code"
                                              type="text"
                                              placeholder="Postal Code / ZIP"
                                              class="form-control"
                                              v-model="
                                                deal_customer_form.postal_code
                                              "
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'primary_customer',
                                                  ) &&
                                                  errors.primary_customer.hasOwnProperty(
                                                    'postal_code',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.primary_customer
                                                  .postal_code
                                              "
                                              :title="
                                                errors.primary_customer
                                                  .postal_code
                                              "
                                            />
                                          </fieldset>
                                          <small
                                            id="customer_postal_code-error"
                                            class="form-text invalid-feedback"
                                            >{{
                                              errors.primary_customer
                                                .postal_code
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- customer postal code : END -->

                                      <!-- customer phone : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label for="customer_phone"
                                            >Phone</label
                                          >
                                          <fieldset
                                            class="has-icon-left"
                                            style="position: relative"
                                          >
                                            <input
                                              id="customer_phone"
                                              type="tel"
                                              placeholder="Phone"
                                              class="form-control"
                                              v-model="deal_customer_form.phone"
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'primary_customer',
                                                  ) &&
                                                  errors.primary_customer.hasOwnProperty(
                                                    'phone',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.primary_customer
                                                  .phone
                                              "
                                              :title="
                                                errors.primary_customer.phone
                                              "
                                            />
                                            <div class="form-control-position">
                                              <i
                                                class="bx bx-phone font-medium-3"
                                              ></i>
                                            </div>
                                          </fieldset>
                                          <small
                                            id="customer_phone-error"
                                            class="form-text invalid-feedback"
                                            >{{
                                              errors.primary_customer.phone
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- customer phone : END -->

                                      <!-- customer mobile : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label for="customer_mobile"
                                            >Mobile</label
                                          >
                                          <fieldset
                                            class="has-icon-left"
                                            style="position: relative"
                                          >
                                            <input
                                              id="customer_mobile"
                                              type="tel"
                                              placeholder="Mobile"
                                              class="form-control"
                                              v-model="
                                                deal_customer_form.mobile
                                              "
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'primary_customer',
                                                  ) &&
                                                  errors.primary_customer.hasOwnProperty(
                                                    'mobile',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.primary_customer
                                                  .mobile
                                              "
                                              :title="
                                                errors.primary_customer.mobile
                                              "
                                            />
                                            <div class="form-control-position">
                                              <i
                                                class="bx bxs-mobile font-medium-3"
                                              ></i>
                                            </div>
                                          </fieldset>
                                          <small
                                            id="customer_mobile-error"
                                            class="form-text invalid-feedback"
                                            >{{
                                              errors.primary_customer.mobile
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- customer mobile : END -->

                                      <!-- customer email : START -->
                                      <div class="col-xs-12 col-md-12 px-25">
                                        <div class="form-group">
                                          <label for="customer_email"
                                            >Email</label
                                          >
                                          <fieldset
                                            class="has-icon-left"
                                            style="position: relative"
                                          >
                                            <input
                                              id="customer_email"
                                              type="text"
                                              placeholder="Email"
                                              class="form-control"
                                              v-model="deal_customer_form.email"
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'primary_customer',
                                                  ) &&
                                                  errors.primary_customer.hasOwnProperty(
                                                    'email',
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.primary_customer
                                                  .email
                                              "
                                              :title="
                                                errors.primary_customer.email
                                              "
                                            />
                                            <div class="form-control-position">
                                              <i
                                                class="bx bxs-envelope font-medium-3"
                                              ></i>
                                            </div>
                                          </fieldset>
                                          <small
                                            id="customer_email-error"
                                            class="form-text invalid-feedback"
                                            >{{
                                              errors.primary_customer.email
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- customer email : END -->
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- CARD 2 : END -->

                            <!-- CARD 4 : START -->
                            <div class="card col-xs-12 col-md-3">
                              <div class="card-content">
                                <div class="card-body pb-1 mx-25 row px-0">
                                  <h4 class="card-title col-6">
                                    Terms of settlement
                                  </h4>
                                  <div class="col-12">
                                    <div class="row">
                                      <!-- price : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label for="inventory-price"
                                            >Selling Price</label
                                          >
                                          <fieldset
                                            class="has-icon-left"
                                            style="position: relative"
                                          >
                                            <input
                                              id="inventory-price"
                                              type="text"
                                              placeholder="Selling Price"
                                              v-model="
                                                deal_base_breakdown_form.purchase_price
                                              "
                                              class="form-control"
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'base_breakdown',
                                                  ) &&
                                                  errors.base_breakdown.hasOwnProperty(
                                                    'purchase_price',
                                                  ),
                                              }"
                                              @keyup="
                                                triggerWorksheetCalculation();
                                                delete errors.base_breakdown
                                                  .purchase_price;
                                              "
                                              :title="
                                                errors.base_breakdown
                                                  .purchase_price
                                              "
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
                                            >{{
                                              errors.base_breakdown
                                                .purchase_price
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- price : END -->

                                      <!-- tax_type_1 : START -->
                                      <div
                                        class="col-xs-12 col-md-6 px-25"
                                        v-show="false"
                                      >
                                        <div class="form-group">
                                          <label for="tax_type_1"
                                            >Tax Type</label
                                          >
                                          <select
                                            id="tax_type_1"
                                            class="custom-select"
                                            name="purchase_tax_type"
                                            :class="{
                                              'is-invalid':
                                                errors.hasOwnProperty(
                                                  'base_breakdown',
                                                ) &&
                                                errors.base_breakdown.hasOwnProperty(
                                                  'purchase_tax_type',
                                                ),
                                            }"
                                            @keyup="
                                              delete errors.base_breakdown
                                                .purchase_tax_type
                                            "
                                            :title="
                                              errors.base_breakdown
                                                .purchase_tax_type
                                            "
                                            v-model="
                                              deal_base_breakdown_form.purchase_tax_type
                                            "
                                            placeholder="Tax Type"
                                          >
                                            <option value="" selected disabled>
                                              Tax Type
                                            </option>
                                            <option
                                              :value="tax.name"
                                              v-for="tax in businessTaxSettings"
                                              :key="
                                                `deal_base_breakdown_form_primary_purchase_${tax.name}`
                                              "
                                            >
                                              {{ tax.name }}
                                            </option>
                                          </select>
                                          <small
                                            id="tax_type_1-error"
                                            class="form-text invalid-feedback"
                                          ></small>
                                        </div>
                                      </div>
                                      <!-- tax_type_1 : END -->

                                      <!-- tax_type_2 : START -->
                                      <div
                                        class="col-xs-12 col-md-6 px-25"
                                        v-show="false"
                                      >
                                        <div class="form-group">
                                          <label for="tax_type_2"
                                            >Tax Type</label
                                          >
                                          <select
                                            id="tax_type_2"
                                            class="custom-select"
                                            name="purchase_tax_type_secondary"
                                            :class="{
                                              'is-invalid':
                                                errors.hasOwnProperty(
                                                  'base_breakdown',
                                                ) &&
                                                errors.base_breakdown.hasOwnProperty(
                                                  'purchase_tax_type_secondary',
                                                ),
                                            }"
                                            @keyup="
                                              delete errors.base_breakdown
                                                .purchase_tax_type_secondary
                                            "
                                            :title="
                                              errors.base_breakdown
                                                .purchase_tax_type_secondary
                                            "
                                            v-model="
                                              deal_base_breakdown_form.purchase_tax_type_secondary
                                            "
                                            placeholder="Tax Type"
                                          >
                                            <option value="" selected disabled>
                                              Tax Type
                                            </option>
                                            <option
                                              :value="tax.name"
                                              v-for="tax in businessTaxSettings"
                                              :key="
                                                `deal_base_breakdown_form_secondary_purchase_${tax.name}`
                                              "
                                            >
                                              {{ tax.name }}
                                            </option>
                                          </select>
                                          <small
                                            id="tax_type_2-error"
                                            class="form-text invalid-feedback"
                                          ></small>
                                        </div>
                                      </div>
                                      <!-- tax_type_2 : END -->

                                      <!-- licence_fee : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label
                                            for="deal_base_breakdown_form_licence_fee"
                                            >Licence Fee</label
                                          >
                                          <fieldset
                                            class="has-icon-left"
                                            style="position: relative"
                                          >
                                            <input
                                              id="deal_base_breakdown_form_licence_fee"
                                              type="text"
                                              placeholder="Licence Fee"
                                              class="form-control"
                                              v-model="
                                                deal_base_breakdown_form.licence_fee
                                              "
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'base_breakdown',
                                                  ) &&
                                                  errors.base_breakdown.hasOwnProperty(
                                                    'licence_fee',
                                                  ),
                                              }"
                                              @keyup="
                                                triggerWorksheetCalculation();
                                                delete errors.base_breakdown
                                                  .licence_fee;
                                              "
                                              :title="
                                                errors.base_breakdown
                                                  .licence_fee
                                              "
                                            />
                                            <div class="form-control-position">
                                              <i
                                                class="bx bxs-dollar-circle text-success font-medium-3"
                                              ></i>
                                            </div>
                                          </fieldset>
                                          <small
                                            id="deal_base_breakdown_licence_fee-error"
                                            class="form-text invalid-feedback"
                                            >{{
                                              errors.base_breakdown.licence_fee
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- licence_fee : END -->

                                      <!-- deal_base_breakdown_form gasoline : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
                                        <div class="form-group">
                                          <label
                                            for="deal_base_breakdown_form_gasoline"
                                            >Gasoline</label
                                          >
                                          <fieldset
                                            class="has-icon-left"
                                            style="position: relative"
                                          >
                                            <input
                                              id="deal_base_breakdown_form_gasoline"
                                              type="text"
                                              placeholder="Gasoline"
                                              class="form-control"
                                              v-model="
                                                deal_base_breakdown_form.gasoline
                                              "
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'base_breakdown',
                                                  ) &&
                                                  errors.base_breakdown.hasOwnProperty(
                                                    'gasoline',
                                                  ),
                                              }"
                                              @keyup="
                                                triggerWorksheetCalculation();
                                                delete errors.base_breakdown
                                                  .gasoline;
                                              "
                                              :title="
                                                errors.base_breakdown.gasoline
                                              "
                                            />
                                            <div class="form-control-position">
                                              <i
                                                class="bx bxs-dollar-circle text-success font-medium-3"
                                              ></i>
                                            </div>
                                          </fieldset>
                                          <small
                                            id="deal_base_breakdown_gasoline-error"
                                            class="form-text invalid-feedback"
                                            >{{
                                              errors.base_breakdown.gasoline
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- deal_base_breakdown_form gasoline : END -->

                                      <!-- deal_base_breakdown_form lien_registration_fee : START -->
                                      <div class="col-xs-12 col-md-6 px-25">
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
                                              class="form-control"
                                              v-model="
                                                deal_base_breakdown_form.lien_registration_fee
                                              "
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'base_breakdown',
                                                  ) &&
                                                  errors.base_breakdown.hasOwnProperty(
                                                    'lien_registration_fee',
                                                  ),
                                              }"
                                              @keyup="
                                                triggerWorksheetCalculation();
                                                delete errors.base_breakdown
                                                  .lien_registration_fee;
                                              "
                                              :title="
                                                errors.base_breakdown
                                                  .lien_registration_fee
                                              "
                                            />
                                            <div class="form-control-position">
                                              <i
                                                class="bx bxs-dollar-circle text-success font-medium-3"
                                              ></i>
                                            </div>
                                          </fieldset>
                                          <small
                                            id="deal_base_breakdown_lien_registration_fee-error"
                                            class="form-text invalid-feedback"
                                            >{{
                                              errors.base_breakdown
                                                .lien_registration_fee
                                            }}</small
                                          >
                                        </div>
                                      </div>
                                      <!-- deal_base_breakdown_form lien_registration_fee : END -->

                                      <template v-if="isDealFinanced">
                                        <!-- override_finance_calculation : START -->
                                        <div class="col-xs-12 col-md-12">
                                          <div class="form-group">
                                            <div
                                              class="custom-control custom-switch"
                                            >
                                              <label
                                                for=""
                                                style="display: block"
                                                class="mb-1"
                                              >
                                              </label>
                                              <div
                                                class="form-check form-check-inline"
                                              >
                                                <input
                                                  class="form-check-input"
                                                  v-model="
                                                    deal_finance_form.override_finance_calculation
                                                  "
                                                  type="checkbox"
                                                  id="override_finance_calculation"
                                                  :true-value="'1'"
                                                  :false-value="'0'"
                                                />
                                                <label
                                                  style="padding: 0.4em"
                                                  class="form-check-label"
                                                  for="override_finance_calculation"
                                                  >Override Finance
                                                  Calculation</label
                                                >
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- override_finance_calculation : END -->
                                        <!-- finance_total_amount : START -->
                                        <div class="col-xs-12 col-md-6 px-25">
                                          <div class="form-group">
                                            <label
                                              for="deal_finance_total_amount"
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
                                                v-model="
                                                  deal_finance_form.total_amount
                                                "
                                                class="form-control"
                                                :class="{
                                                  'is-invalid':
                                                    errors.hasOwnProperty(
                                                      'finance',
                                                    ) &&
                                                    errors.finance.hasOwnProperty(
                                                      'total_amount',
                                                    ),
                                                }"
                                                readonly
                                                @keyup="
                                                  delete errors.finance
                                                    .purchase_price
                                                "
                                                :title="
                                                  errors.finance.purchase_price
                                                "
                                              />
                                              <div
                                                class="form-control-position"
                                              >
                                                <i
                                                  class="bx bxs-dollar-circle font-medium-3"
                                                ></i>
                                              </div>
                                            </fieldset>
                                            <small
                                              id="deal_finance_total_amount-error"
                                              class="form-text invalid-feedback"
                                              >{{
                                                errors.finance.purchase_price
                                              }}</small
                                            >
                                          </div>
                                        </div>
                                        <!-- finance_total_amount : END -->

                                        <!-- finance_commission : START -->
                                        <div class="col-xs-12 col-md-6 px-25">
                                          <div class="form-group">
                                            <label for="deal_finance_commission"
                                              >Commission</label
                                            >
                                            <fieldset
                                              class="has-icon-left"
                                              style="position: relative"
                                            >
                                              <input
                                                id="deal_finance_commission"
                                                type="text"
                                                placeholder="Finance Commission"
                                                v-model="
                                                  deal_finance_form.finance_commission
                                                "
                                                class="form-control"
                                                :class="{
                                                  'is-invalid':
                                                    errors.hasOwnProperty(
                                                      'finance',
                                                    ) &&
                                                    errors.finance.hasOwnProperty(
                                                      'finance_commission',
                                                    ),
                                                }"
                                                @keyup="
                                                  delete errors.finance
                                                    .finance_commission
                                                "
                                                :title="
                                                  errors.finance
                                                    .finance_commission
                                                "
                                              />
                                              <div
                                                class="form-control-position"
                                              >
                                                <i
                                                  class="bx bxs-dollar-circle font-medium-3"
                                                ></i>
                                              </div>
                                            </fieldset>
                                            <small
                                              id="deal_finance_finance_commission-error"
                                              class="form-text invalid-feedback"
                                              >{{
                                                errors.finance
                                                  .finance_commission
                                              }}</small
                                            >
                                          </div>
                                        </div>
                                        <!-- finance_commission : END -->

                                        <!-- interest_rate : START -->
                                        <div class="col-xs-12 col-md-5 px-25">
                                          <div class="form-group">
                                            <label
                                              for="deal_finance_interest_rate"
                                              >Interest Rate</label
                                            >
                                            <fieldset
                                              class="has-icon-left"
                                              style="position: relative"
                                            >
                                              <input
                                                id="deal_finance_interest_rate"
                                                type="text"
                                                placeholder="Interest Rate"
                                                v-model="
                                                  deal_finance_form.interest_rate
                                                "
                                                class="form-control"
                                                :class="{
                                                  'is-invalid':
                                                    errors.hasOwnProperty(
                                                      'finance',
                                                    ) &&
                                                    errors.finance.hasOwnProperty(
                                                      'interest_rate',
                                                    ),
                                                }"
                                                @keyup="
                                                  triggerWorksheetCalculation();
                                                  delete errors.finance
                                                    .interest_rate;
                                                "
                                                :title="
                                                  errors.finance.interest_rate
                                                "
                                              />
                                              <div
                                                class="form-control-position"
                                              >
                                                <i
                                                  class="bx bxs-dollar-circle font-medium-3"
                                                ></i>
                                              </div>
                                            </fieldset>
                                            <small
                                              id="deal_finance_interest_rate-error"
                                              class="form-text invalid-feedback"
                                              >{{
                                                errors.finance.interest_rate
                                              }}</small
                                            >
                                          </div>
                                        </div>
                                        <!-- interest_rate : END -->

                                        <!-- interest_rate_type : START -->
                                        <div class="col-xs-12 col-md-7 px-25">
                                          <div class="form-group">
                                            <div
                                              class="custom-control custom-switch"
                                            >
                                              <label
                                                for=""
                                                style="display: block"
                                                class="mb-1"
                                              >
                                                Interest Type
                                              </label>
                                              <div
                                                class="form-check form-check-inline"
                                              >
                                                <input
                                                  class="form-check-input"
                                                  v-model="
                                                    deal_finance_form.interest_rate_type
                                                  "
                                                  type="radio"
                                                  id="fixed_rate"
                                                  value="Fixed"
                                                />
                                                <label
                                                  class="form-check-label"
                                                  for="fixed_rate"
                                                  >Fixed</label
                                                >
                                              </div>
                                              <div
                                                class="form-check form-check-inline"
                                              >
                                                <input
                                                  class="form-check-input"
                                                  v-model="
                                                    deal_finance_form.interest_rate_type
                                                  "
                                                  type="radio"
                                                  id="variable_rate"
                                                  value="Variable Rate"
                                                />
                                                <label
                                                  class="form-check-label"
                                                  for="variable_rate"
                                                  >Variable Rate</label
                                                >
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- interest_rate_type : END -->

                                        <!-- term : START -->
                                        <div class="col-xs-12 col-md-4 px-25">
                                          <div class="form-group">
                                            <label for="deal_finance_term"
                                              >Term</label
                                            >
                                            <fieldset
                                              class=""
                                              style="position: relative"
                                            >
                                              <input
                                                id="deal_finance_term"
                                                type="text"
                                                placeholder="Term"
                                                v-model="deal_finance_form.term"
                                                class="form-control"
                                                :class="{
                                                  'is-invalid':
                                                    errors.hasOwnProperty(
                                                      'finance',
                                                    ) &&
                                                    errors.finance.hasOwnProperty(
                                                      'term',
                                                    ),
                                                }"
                                                @keyup="
                                                  triggerWorksheetCalculation();
                                                  delete errors.finance.term;
                                                "
                                                :title="errors.finance.term"
                                              />
                                            </fieldset>
                                            <small
                                              id="deal_finance_term-error"
                                              class="form-text invalid-feedback"
                                              >{{ errors.finance.term }}</small
                                            >
                                          </div>
                                        </div>
                                        <!-- term : END -->

                                        <!-- payment_frequency : START -->
                                        <div
                                          class="form-group col-md-8 col-xs-12 px-25"
                                        >
                                          <label
                                            for="deal_finance_payment_frequency"
                                            >Frequency</label
                                          >
                                          <select
                                            class="custom-select form-control mr-1"
                                            :class="{
                                              'is-invalid':
                                                errors.hasOwnProperty(
                                                  'finance',
                                                ) &&
                                                errors.finance.hasOwnProperty(
                                                  'payment_frequency',
                                                ),
                                            }"
                                            @keyup="
                                              delete errors.finance
                                                .payment_frequency
                                            "
                                            :title="
                                              errors.finance.payment_frequency
                                            "
                                            v-model="
                                              deal_finance_form.payment_frequency
                                            "
                                            placeholder="Payment Frequency"
                                            @change="
                                              triggerWorksheetCalculation()
                                            "
                                            id="deal_finance_payment_frequency"
                                          >
                                            <option value="" selected disabled>
                                              Select Payment Frequency
                                            </option>
                                            <option value="Weekly">
                                              Weekly
                                            </option>
                                            <option value="Bi-Weekly">
                                              Bi-Weekly
                                            </option>
                                            <option value="Monthly">
                                              Monthly
                                            </option>
                                          </select>
                                        </div>
                                        <!-- payment_frequency : END -->

                                        <!-- interest_amount : START -->
                                        <div class="col-xs-12 col-md-6 px-25">
                                          <div class="form-group">
                                            <label
                                              for="deal_finance_interest_amount"
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
                                                v-model="
                                                  deal_finance_form.interest_amount
                                                "
                                                class="form-control"
                                                @keyup="
                                                  triggerWorksheetCalculation();
                                                  delete errors.finance
                                                    .interest_amount;
                                                "
                                                :readonly="
                                                  !isFinanceOverriden
                                                    ? true
                                                    : false
                                                "
                                                :class="{
                                                  'is-invalid':
                                                    errors.hasOwnProperty(
                                                      'finance',
                                                    ) &&
                                                    errors.finance.hasOwnProperty(
                                                      'interest_amount',
                                                    ),
                                                }"
                                                :title="
                                                  errors.finance.interest_amount
                                                "
                                              />
                                              <div
                                                class="form-control-position"
                                              >
                                                <i
                                                  class="bx bxs-dollar-circle font-medium-3"
                                                ></i>
                                              </div>
                                            </fieldset>
                                            <small
                                              id="deal_finance_interest_amount-error"
                                              class="form-text invalid-feedback"
                                              >{{
                                                errors.finance.interest_amount
                                              }}</small
                                            >
                                          </div>
                                        </div>
                                        <!-- interest_amount : END -->

                                        <!-- emi_amount : START -->
                                        <div class="col-xs-12 col-md-6 px-25">
                                          <div class="form-group">
                                            <label for="deal_finance_emi_amount"
                                              >Payment</label
                                            >
                                            <fieldset
                                              class="has-icon-left"
                                              style="position: relative"
                                            >
                                              <input
                                                id="deal_finance_emi_amount"
                                                type="text"
                                                placeholder="Payment"
                                                v-model="
                                                  deal_finance_form.emi_amount
                                                "
                                                class="form-control"
                                                :readonly="
                                                  !isFinanceOverriden
                                                    ? true
                                                    : false
                                                "
                                                :class="{
                                                  'is-invalid':
                                                    errors.hasOwnProperty(
                                                      'finance',
                                                    ) &&
                                                    errors.finance.hasOwnProperty(
                                                      'emi_amount',
                                                    ),
                                                }"
                                                @keyup="
                                                  delete errors.finance
                                                    .emi_amount
                                                "
                                                :title="
                                                  errors.finance.emi_amount
                                                "
                                              />
                                              <div
                                                class="form-control-position"
                                              >
                                                <i
                                                  class="bx bxs-dollar-circle font-medium-3"
                                                ></i>
                                              </div>
                                            </fieldset>
                                            <small
                                              id="deal_finance_emi_amount-error"
                                              class="form-text invalid-feedback"
                                              >{{
                                                errors.finance.emi_amount
                                              }}</small
                                            >
                                          </div>
                                        </div>
                                        <!-- emi_amount : END -->

                                        <!-- lien_holder_name : START -->
                                        <div class="col-xs-12 col-md-6 px-25">
                                          <div class="form-group">
                                            <label
                                              for="deal_finance_lien_holder_name"
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
                                                v-model="
                                                  deal_finance_form.lien_holder_name
                                                "
                                                class="form-control"
                                                :class="{
                                                  'is-invalid':
                                                    errors.hasOwnProperty(
                                                      'finance',
                                                    ) &&
                                                    errors.finance.hasOwnProperty(
                                                      'lien_holder_name',
                                                    ),
                                                }"
                                                @keyup="
                                                  delete errors.finance
                                                    .lien_holder_name
                                                "
                                                :title="
                                                  errors.finance
                                                    .lien_holder_name
                                                "
                                              />
                                              <div
                                                class="form-control-position"
                                              >
                                                <i
                                                  class="bx bxs-bank font-medium-3"
                                                ></i>
                                              </div>
                                            </fieldset>
                                            <small
                                              id="deal_finance_lien_holder_name-error"
                                              class="form-text invalid-feedback"
                                              >{{
                                                errors.finance.lien_holder_name
                                              }}</small
                                            >
                                          </div>
                                        </div>
                                        <!-- lien_holder_name : END -->

                                        <!-- first_payment_date : START -->
                                        <div class="col-md-6 col-xs-12 px-25">
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
                                                v-model="
                                                  deal_finance_form.first_payment_date
                                                "
                                                :format="'MM/DD/YYYY'"
                                                :value-type="'MM/DD/YYYY'"
                                                id="first_payment_date"
                                                :class="{
                                                  'is-invalid':
                                                    errors.hasOwnProperty(
                                                      'finance',
                                                    ) &&
                                                    errors.finance.hasOwnProperty(
                                                      'first_payment_date',
                                                    ),
                                                }"
                                                @change="
                                                  delete errors.finance
                                                    .first_payment_date
                                                "
                                                :title="
                                                  errors.finance
                                                    .first_payment_date
                                                "
                                              >
                                              </date-picker>
                                            </fieldset>
                                            <small
                                              id="first_payment_date-error"
                                              class="form-text invalid-feedback"
                                              >{{
                                                errors.finance
                                                  .first_payment_date
                                              }}</small
                                            >
                                          </div>
                                        </div>
                                        <!-- first_payment_date : END -->
                                      </template>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- CARD 4 : END -->

                            <!-- CARD 3 : START -->
                            <div class="card col-xs-12 col-md-3">
                              <div class="card-content">
                                <div class="card-body pb-1 mx-25 row px-0">
                                  <h4 class="card-title col-6">Summary</h4>
                                  <!-- LIST : START -->
                                  <div class="col-12">
                                    <!-- row : START -->
                                    <div class="row">
                                      <ul class="list-group col-12">
                                        <li
                                          class="list-group-item p-0 row"
                                          style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                                        >
                                          <span
                                            class="col-md-7 pl-1 pr-0 d-inline-block"
                                            ><i
                                              style="vertical-align: middle"
                                              class="bx bxs-plus-circle text-success font-medium-3"
                                            ></i>
                                            Selling Price</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{
                                                `$ ${Number(
                                                  deal_base_breakdown_form.purchase_price,
                                                ).toLocaleString()}`
                                              }}
                                            </span>
                                          </span>
                                        </li>
                                        <li
                                          class="list-group-item p-0 row"
                                          style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                                        >
                                          <span
                                            class="col-md-7 pr-0 pl-1 d-inline-block"
                                            ><i
                                              style="vertical-align: middle"
                                              class="bx bxs-plus-circle text-success font-medium-3"
                                            ></i>
                                            Extended Warranty</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{
                                                `$ ${Number(
                                                  calculateDealTotalWarrantiesAmount,
                                                ).toLocaleString()}`
                                              }}
                                            </span>
                                          </span>
                                        </li>
                                        <li
                                          class="list-group-item p-0 row"
                                          style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                                        >
                                          <span
                                            class="col-md-7 pr-0 pl-1 d-inline-block"
                                            ><i
                                              style="vertical-align: middle"
                                              class="bx bxs-plus-circle text-success font-medium-3"
                                            ></i>
                                            Fees</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{ calculateDealTotalFeesAmount }}
                                            </span>
                                          </span>
                                        </li>
                                        <li
                                          class="list-group-item p-0 row"
                                          style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                                        >
                                          <span
                                            class="col-md-7 pr-0 pl-1 d-inline-block"
                                            ><i
                                              style="vertical-align: middle"
                                              class="bx bxs-plus-circle text-success font-medium-3"
                                            ></i>
                                            Accessories</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{
                                                `$ ${Number(
                                                  calculateDealTotalAccessoriesAmount,
                                                ).toLocaleString()}`
                                              }}
                                            </span>
                                          </span>
                                        </li>
                                        <li
                                          class="list-group-item p-0 pb-1 pt-1 row"
                                        >
                                          <span
                                            class="col-md-7 pr-0 pl-1 d-inline-block"
                                            ><i
                                              style="vertical-align: sub"
                                              class="bx bxs-calculator text-primary font-medium-3"
                                            ></i>
                                            Subtotal</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{
                                                `$ ${Number(
                                                  deal_base_breakdown_form.subtotal,
                                                ).toLocaleString()}`
                                              }}
                                            </span>
                                          </span>
                                        </li>
                                        <li
                                          class="list-group-item p-0 row"
                                          style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                                        >
                                          <span
                                            class="col-md-7 pr-0 pl-1 d-inline-block"
                                            ><i
                                              style="vertical-align: middle"
                                              class="bx bxs-minus-circle text-danger font-medium-3"
                                            ></i>
                                            Trade Value</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{
                                                `$ ${Number(
                                                  deal_base_breakdown_form.trade_value,
                                                ).toLocaleString()}`
                                              }}
                                            </span>
                                          </span>
                                        </li>
                                        <li
                                          class="list-group-item p-0 pb-1 pt-1 row"
                                        >
                                          <span
                                            class="col-md-7 pr-0 pl-1 d-inline-block"
                                            ><i
                                              style="vertical-align: sub"
                                              class="bx bxs-calculator text-primary font-medium-3"
                                            ></i>
                                            Net Difference</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{
                                                `$ ${Number(
                                                  deal_base_breakdown_form.net_difference,
                                                ).toLocaleString()}`
                                              }}
                                            </span>
                                          </span>
                                        </li>
                                        <li
                                          class="list-group-item p-0 row"
                                          style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                                        >
                                          <span
                                            class="col-md-7 pr-0 pl-1 d-inline-block"
                                            ><i
                                              style="vertical-align: middle"
                                              class="bx bxs-plus-circle text-success font-medium-3"
                                            ></i>
                                            Total Tax</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{
                                                `$ ${Number(
                                                  deal_base_breakdown_form.total_tax_amount,
                                                ).toLocaleString()}`
                                              }}
                                            </span>
                                          </span>
                                        </li>
                                        <li
                                          class="list-group-item p-0 row"
                                          style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                                        >
                                          <span
                                            class="col-md-7 pr-0 pl-1 d-inline-block"
                                            ><i
                                              style="vertical-align: middle"
                                              class="bx bxs-plus-circle text-success font-medium-3"
                                            ></i>
                                            Licence Fee</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{
                                                `$ ${Number(
                                                  deal_base_breakdown_form.licence_fee,
                                                ).toLocaleString()}`
                                              }}
                                            </span>
                                          </span>
                                        </li>
                                        <li
                                          class="list-group-item p-0 row"
                                          style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                                        >
                                          <span
                                            class="col-md-7 pr-0 pl-1 d-inline-block"
                                            ><i
                                              style="vertical-align: middle"
                                              class="bx bxs-plus-circle text-success font-medium-3"
                                            ></i>
                                            Gasoline</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{
                                                `$ ${Number(
                                                  deal_base_breakdown_form.gasoline,
                                                ).toLocaleString()}`
                                              }}
                                            </span>
                                          </span>
                                        </li>
                                        <li
                                          class="list-group-item p-0 row"
                                          style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                                        >
                                          <span
                                            class="col-md-7 pr-0 pl-1 d-inline-block"
                                            ><i
                                              style="vertical-align: middle"
                                              class="bx bxs-plus-circle text-success font-medium-3"
                                            ></i>
                                            Lien Payout</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{
                                                `$ ${Number(
                                                  deal_base_breakdown_form.lien_payout,
                                                ).toLocaleString()}`
                                              }}
                                            </span>
                                          </span>
                                        </li>
                                        <li
                                          class="list-group-item p-0 pb-1 pt-1 row"
                                        >
                                          <span
                                            class="col-md-7 pr-0 pl-1 d-inline-block"
                                            ><i
                                              style="vertical-align: sub"
                                              class="bx bxs-calculator text-primary font-medium-3"
                                            ></i>
                                            Subtotal</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{
                                                `$ ${Number(
                                                  deal_base_breakdown_form.secondary_subtotal,
                                                ).toLocaleString()}`
                                              }}
                                            </span>
                                          </span>
                                        </li>
                                        <li
                                          class="list-group-item p-0 row"
                                          style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                                        >
                                          <span
                                            class="col-md-7 pr-0 pl-1 d-inline-block"
                                            ><i
                                              style="vertical-align: middle"
                                              class="bx bxs-minus-circle text-danger font-medium-3"
                                            ></i>
                                            Total Payment</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{
                                                `$ ${Number(
                                                  calculateTotalPayment,
                                                ).toLocaleString()}`
                                              }}
                                            </span>
                                          </span>
                                        </li>
                                        <li
                                          class="list-group-item p-0 row"
                                          style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                                        >
                                          <span
                                            class="col-md-7 pr-0 pl-1 d-inline-block"
                                            ><i
                                              style="vertical-align: middle"
                                              class="bx bxs-plus-circle text-success font-medium-3"
                                            ></i>
                                            Total Insurance</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{
                                                `$ ${Number(
                                                  calculateDealTotalInsurancesAmount,
                                                ).toLocaleString()}`
                                              }}
                                            </span>
                                          </span>
                                        </li>
                                        <li
                                          class="list-group-item p-0 row"
                                          style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                                        >
                                          <span
                                            class="col-md-7 pr-0 pl-1 d-inline-block"
                                            ><i
                                              style="vertical-align: middle"
                                              class="bx bxs-plus-circle text-success font-medium-3"
                                            ></i>
                                            Insurance Tax</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{
                                                `$ ${Number(
                                                  deal_base_breakdown_form.insurance_tax,
                                                ).toLocaleString()}`
                                              }}
                                            </span>
                                          </span>
                                        </li>
                                        <li
                                          class="list-group-item p-0 row"
                                          style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                                        >
                                          <span
                                            class="col-md-7 pr-0 pl-1 d-inline-block"
                                            ><i
                                              style="vertical-align: middle"
                                              class="bx bxs-plus-circle text-success font-medium-3"
                                            ></i>
                                            Lien Registration Fee</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{
                                                `$ ${Number(
                                                  deal_base_breakdown_form.lien_registration_fee,
                                                ).toLocaleString()}`
                                              }}
                                            </span>
                                          </span>
                                        </li>
                                        <li
                                          class="list-group-item p-0 row"
                                          v-show="isDealFinanced"
                                          style="padding-top:0.25em !important;padding-bottom::0.25em !important;"
                                        >
                                          <span
                                            class="col-md-7 pr-0 pl-1 d-inline-block"
                                            ><i
                                              style="vertical-align: middle"
                                              class="bx bxs-plus-circle text-success font-medium-3"
                                            ></i>
                                            Interest Amount</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{
                                                `$ ${Number(
                                                  deal_finance_form.interest_amount,
                                                ).toLocaleString()}`
                                              }}
                                            </span>
                                          </span>
                                        </li>
                                        <li
                                          class="list-group-item p-0 pb-1 pt-1 row"
                                        >
                                          <span
                                            class="col-md-7 pr-0 pl-1 d-inline-block"
                                            ><i
                                              style="vertical-align: sub"
                                              class="bx bxs-calculator text-primary font-medium-3"
                                            ></i>
                                            Total Balance Due</span
                                          >
                                          <span
                                            class="col-md-4 pl-0 pr-0 d-inline-block"
                                          >
                                            <span
                                              style="text-align: right"
                                              class="d-block"
                                            >
                                              {{
                                                `$ ${Number(
                                                  deal_base_breakdown_form.total_balance_due,
                                                ).toLocaleString()}`
                                              }}
                                            </span>
                                          </span>
                                        </li>
                                      </ul>
                                    </div>
                                  </div>

                                  <!-- LIST : END -->
                                </div>
                              </div>
                            </div>
                            <!-- CARD 3 : END -->
                          </div>
                          <!-- Summary Section : END -->

                          <!-- has_trade_in : START -->
                          <div class="card-header border-line-bottom-title">
                            <h5 class="checkbox card-title">
                              <input
                                type="checkbox"
                                class="checkbox-input"
                                id="has_trade_in"
                                v-model="has_trade_in"
                                :true-value="true"
                                :false-value="false"
                              />
                              <label for="has_trade_in"
                                >Deal Has Trade In</label
                              >
                            </h5>
                          </div>
                          <hr />
                          <!-- has_trade_in : END -->

                          <!-- Trade In : START -->
                          <div v-show="has_trade_in">
                            <div class="card-header border-line-bottom-title">
                              <h5 class="card-title">Trade-In</h5>
                            </div>
                          </div>
                          <div class="row" v-show="has_trade_in">
                            <!-- inventory vin : START -->
                            <div class="col-xs-12 col-md-2">
                              <div class="form-group">
                                <label for="trade_in_inventory_vin">VIN</label>
                                <input
                                  id="trade_in_inventory_vin"
                                  type="text"
                                  placeholder="VIN"
                                  class="form-control"
                                  v-model="deal_trade_in_form.vin"
                                  :class="{
                                    'is-invalid':
                                      errors.hasOwnProperty('trade_in') &&
                                      errors.trade_in.hasOwnProperty('vin'),
                                  }"
                                  @keyup="delete errors.trade_in.vin"
                                  :title="errors.trade_in.vin"
                                />
                                <!-- <small
                                  id="trade_in_inventory_vin-error"
                                  class="form-text invalid-feedback"
                                  >{{ errors.trade_in.vin }}</small
                                > -->
                              </div>
                            </div>
                            <!-- inventory vin : END -->
                            <div class="col-xs-12 col-md-1 px-25">
                              <button
                                class="btn btn-primary"
                                style="position: absolute; bottom: 1em"
                                @click="
                                  getAvailableTrimsForVIN('deal_trade_in_form')
                                "
                                type="button"
                              >
                                Decode
                              </button>
                            </div>

                            <div class="w-100"></div>
                            <!-- trade-in inventory year : START -->
                            <div class="col-xs-12 col-md-2">
                              <div class="form-group">
                                <label for="trade_in_inventory_year"
                                  >Year</label
                                >
                                <fieldset
                                  class="has-icon-left"
                                  style="position: relative"
                                >
                                  <input
                                    id="trade_in_inventory_year"
                                    type="text"
                                    placeholder="Year"
                                    class="form-control"
                                    v-model="deal_trade_in_form.year"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('trade_in') &&
                                        errors.trade_in.hasOwnProperty('year'),
                                    }"
                                    @keyup="delete errors.trade_in.year"
                                    :title="errors.trade_in.year"
                                  />
                                  <div class="form-control-position">
                                    <i
                                      class="bx bxs-calendar-alt font-medium-3"
                                    ></i>
                                  </div>
                                </fieldset>
                                <small
                                  id="trade_in_inventory_year-error"
                                  class="form-text invalid-feedback"
                                  >{{ errors.trade_in.year }}</small
                                >
                              </div>
                            </div>
                            <!-- trade-in inventory year : END -->

                            <!-- inventory make : START -->
                            <div class="col-xs-12 col-md-2">
                              <div class="form-group">
                                <label for="trade_in_make">Make</label>
                                <fieldset
                                  class="has-icon-left"
                                  style="position: relative"
                                >
                                  <input
                                    id="trade_in_make"
                                    type="text"
                                    placeholder="Make"
                                    class="form-control"
                                    v-model="deal_trade_in_form.make"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('trade_in') &&
                                        errors.trade_in.hasOwnProperty('make'),
                                    }"
                                    @keyup="delete errors.trade_in.make"
                                    :title="errors.trade_in.make"
                                  />
                                  <div class="form-control-position">
                                    <i class="bx bx-car font-medium-3"></i>
                                  </div>
                                </fieldset>
                                <small
                                  id="trade_in_make-error"
                                  class="form-text invalid-feedback"
                                  >{{ errors.trade_in.make }}</small
                                >
                              </div>
                            </div>
                            <!-- inventory make : END -->

                            <!-- inventory model : START -->
                            <div class="col-xs-12 col-md-2">
                              <div class="form-group">
                                <label for="trade_in_model">Model</label>
                                <fieldset
                                  class="has-icon-left"
                                  style="position: relative"
                                >
                                  <input
                                    id="trade_in_model"
                                    type="text"
                                    placeholder="Model"
                                    class="form-control"
                                    v-model="deal_trade_in_form.model"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('trade_in') &&
                                        errors.trade_in.hasOwnProperty('model'),
                                    }"
                                    @keyup="delete errors.trade_in.model"
                                    :title="errors.trade_in.model"
                                  />
                                  <div class="form-control-position">
                                    <i class="bx bxs-cog font-medium-3"></i>
                                  </div>
                                </fieldset>
                                <small
                                  id="trade_in_model-error"
                                  class="form-text invalid-feedback"
                                  >{{ errors.trade_in.model }}</small
                                >
                              </div>
                            </div>
                            <!-- inventory model : END -->

                            <!-- inventory trim : START -->
                            <div class="col-xs-12 col-md-2">
                              <div class="form-group">
                                <label for="trade_in_trim">Trim</label>
                                <fieldset
                                  class="has-icon-left"
                                  style="position: relative"
                                >
                                  <input
                                    id="trade_in_trim"
                                    type="text"
                                    placeholder="Trim"
                                    class="form-control"
                                    v-model="deal_trade_in_form.trim"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('trade_in') &&
                                        errors.trade_in.hasOwnProperty('trim'),
                                    }"
                                    @keyup="delete errors.trade_in.trim"
                                    :title="errors.trade_in.trim"
                                  />
                                  <div class="form-control-position">
                                    <i class="bx bxs-detail font-medium-3"></i>
                                  </div>
                                </fieldset>
                                <small
                                  id="trade_in_trim-error"
                                  class="form-text invalid-feedback"
                                  >{{ errors.trade_in.trim }}</small
                                >
                              </div>
                            </div>
                            <!-- inventory trim : END -->

                            <!-- inventory odometer : START -->
                            <div class="col-xs-12 col-md-2">
                              <div class="form-group">
                                <label for="trade_in_odometer">Odometer</label>
                                <fieldset
                                  class="has-icon-left"
                                  style="position: relative"
                                >
                                  <input
                                    id="trade_in_odometer"
                                    type="text"
                                    placeholder="Odometer"
                                    class="form-control"
                                    v-model="deal_trade_in_form.odometer"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('trade_in') &&
                                        errors.trade_in.hasOwnProperty(
                                          'odometer',
                                        ),
                                    }"
                                    @keyup="delete errors.trade_in.odometer"
                                    :title="errors.trade_in.odometer"
                                  />
                                  <div class="form-control-position">
                                    <i
                                      class="bx bxs-dashboard font-medium-3"
                                    ></i>
                                  </div>
                                </fieldset>
                                <small
                                  id="odometer-error"
                                  class="form-text invalid-feedback"
                                  >{{ errors.trade_in.odometer }}</small
                                >
                              </div>
                            </div>
                            <!-- inventory odometer : END -->

                            <!-- km_miles : START -->
                            <div class="col-xs-12 col-md-2">
                              <div class="form-group">
                                <label for="trade_in_inventory-km_miles"
                                  >Distance Type</label
                                >
                                <select
                                  id="trade_in_inventory-km_miles"
                                  class="custom-select"
                                  name="km_miles"
                                  v-model="deal_trade_in_form.km_miles"
                                  :class="{
                                    'is-invalid':
                                      errors.hasOwnProperty('trade_in') &&
                                      errors.trade_in.hasOwnProperty(
                                        'km_miles',
                                      ),
                                  }"
                                  @keyup="delete errors.trade_in.km_miles"
                                  :title="errors.trade_in.km_miles"
                                >
                                  <option value="" selected disabled>
                                    Select
                                  </option>
                                  <option value="KM">KM</option>
                                  <option value="Miles">Miles</option>
                                </select>
                                <small
                                  id="trade_in_inventory-km_miles-error"
                                  class="form-text invalid-feedback"
                                  >{{ errors.trade_in.km_miles }}</small
                                >
                              </div>
                            </div>
                            <!-- km_miles : END -->

                            <!-- exterior_color : START -->
                            <div class="col-xs-12 col-md-2">
                              <div class="form-group">
                                <label for="trade_in_inventory-exterior_color"
                                  >Exterior Color</label
                                >
                                <fieldset
                                  class="has-icon-left"
                                  style="position: relative"
                                >
                                  <input
                                    id="trade_in_inventory-exterior_color"
                                    type="text"
                                    placeholder="Exterior Color"
                                    class="form-control"
                                    v-model="deal_trade_in_form.exterior_color"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('trade_in') &&
                                        errors.trade_in.hasOwnProperty(
                                          'exterior_color',
                                        ),
                                    }"
                                    @keyup="
                                      delete errors.trade_in.exterior_color
                                    "
                                    :title="errors.trade_in.exterior_color"
                                  />
                                  <div class="form-control-position">
                                    <i class="bx bx-palette font-medium-3"></i>
                                  </div>
                                </fieldset>
                                <small
                                  id="trade_in_inventory-exterior_color-error"
                                  class="form-text invalid-feedback"
                                  >{{ errors.trade_in.exterior_color }}</small
                                >
                              </div>
                            </div>
                            <!-- exterior_color : END -->

                            <!-- trade_value : START -->
                            <div class="col-xs-12 col-md-2">
                              <div class="form-group">
                                <label for="trade-in-trade_value"
                                  >Trade Value</label
                                >
                                <fieldset
                                  class="has-icon-left"
                                  style="position: relative"
                                >
                                  <input
                                    id="trade-in-trade_value"
                                    type="text"
                                    placeholder="Trade Value"
                                    class="form-control"
                                    v-model="deal_trade_in_form.trade_value"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('trade_in') &&
                                        errors.trade_in.hasOwnProperty(
                                          'trade_value',
                                        ),
                                    }"
                                    @keyup="
                                      triggerWorksheetCalculation();
                                      delete errors.trade_in.trade_value;
                                    "
                                    :title="errors.trade_in.trade_value"
                                  />
                                  <div class="form-control-position">
                                    <i
                                      class="bx bxs-dollar-circle font-medium-3"
                                    ></i>
                                  </div>
                                </fieldset>
                                <small
                                  id="trade-in-trade_value-error"
                                  class="form-text invalid-feedback"
                                  >{{ errors.trade_in.trade_value }}</small
                                >
                              </div>
                            </div>
                            <!-- trade_value : END -->

                            <!-- lien_value : START -->
                            <div class="col-xs-12 col-md-2">
                              <div class="form-group">
                                <label for="trade-in-lien_value"
                                  >Lien Value</label
                                >
                                <fieldset
                                  class="has-icon-left"
                                  style="position: relative"
                                >
                                  <input
                                    id="trade-in-lien_value"
                                    type="text"
                                    placeholder="Lien Value"
                                    class="form-control"
                                    v-model="deal_trade_in_form.lien_value"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('trade_in') &&
                                        errors.trade_in.hasOwnProperty(
                                          'lien_value',
                                        ),
                                    }"
                                    @keyup="
                                      triggerWorksheetCalculation();
                                      delete errors.trade_in.lien_value;
                                    "
                                    :title="errors.trade_in.lien_value"
                                  />
                                  <div class="form-control-position">
                                    <i
                                      class="bx bxs-dollar-circle font-medium-3"
                                    ></i>
                                  </div>
                                </fieldset>
                                <small
                                  id="trade-in-lien_value-error"
                                  class="form-text invalid-feedback"
                                  >{{ errors.trade_in.lien_value }}</small
                                >
                              </div>
                            </div>
                            <!-- lien_value : END -->

                            <!-- trade_equity : START -->
                            <div class="col-xs-12 col-md-2">
                              <div class="form-group">
                                <label for="trade-in-trade_equity"
                                  >Trade Equity</label
                                >
                                <fieldset
                                  class="has-icon-left"
                                  style="position: relative"
                                >
                                  <input
                                    id="trade-in-trade_equity"
                                    type="text"
                                    placeholder="Trade Equity"
                                    class="form-control"
                                    v-model="deal_trade_in_form.trade_equity"
                                    readonly
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('trade_in') &&
                                        errors.trade_in.hasOwnProperty(
                                          'trade_equity',
                                        ),
                                    }"
                                    @keyup="delete errors.trade_in.trade_equity"
                                    :title="errors.trade_in.trade_equity"
                                  />
                                  <div class="form-control-position">
                                    <i
                                      class="bx bxs-dollar-circle font-medium-3"
                                    ></i>
                                  </div>
                                </fieldset>
                                <small
                                  id="trade-in-trade_equity-error"
                                  class="form-text invalid-feedback"
                                  >{{ errors.trade_in.trade_equity }}</small
                                >
                              </div>
                            </div>
                            <!-- trade_equity : END -->

                            <!-- lien_holder : START -->
                            <div class="col-xs-12 col-md-2">
                              <div class="form-group">
                                <label for="trade-in-lien_holder"
                                  >Lien Holder</label
                                >
                                <fieldset
                                  class="has-icon-left"
                                  style="position: relative"
                                >
                                  <input
                                    id="trade-in-lien_holder"
                                    type="text"
                                    placeholder="Lien Holder"
                                    class="form-control"
                                    v-model="deal_trade_in_form.lien_holder"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('trade_in') &&
                                        errors.trade_in.hasOwnProperty(
                                          'lien_holder',
                                        ),
                                    }"
                                    @keyup="delete errors.trade_in.lien_holder"
                                    :title="errors.trade_in.lien_holder"
                                  />
                                  <div class="form-control-position">
                                    <i class="bx bxs-detail font-medium-3"></i>
                                  </div>
                                </fieldset>
                                <small
                                  id="trade-in-lien_holder-error"
                                  class="form-text invalid-feedback"
                                  >{{ errors.trade_in.lien_holder }}</small
                                >
                              </div>
                            </div>
                            <!-- lien_holder : END -->
                          </div>
                          <!-- Trade In : END-->
                          <!-- Deal Breakdown : START -->
                          <div>
                            <div class="card-header border-line-bottom-title">
                              <h5 class="card-title">Deal Breakdown</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-12 col-md-6">
                              <!-- card : START -->
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body pb-1 mx-25 row">
                                    <h4 class="card-title col-8">
                                      <i
                                        class="bx bxs-dollar-circle bx-sm"
                                        style="padding-right: 1em"
                                      ></i>
                                      <span
                                        style="
                                          display: inline-block;
                                          vertical-align: top;
                                        "
                                        >Fees</span
                                      >
                                    </h4>
                                    <div class="col-4 text-right">
                                      <button
                                        class="btn btn-primary text-bold-700"
                                        @click.prevent="addAction('fees')"
                                        v-show="deal_fees.length < 5"
                                      >
                                        <i
                                          class="bx bx-list-plus"
                                          style="padding-right: 1em"
                                        ></i>
                                        Add Fee
                                      </button>
                                    </div>
                                    <!-- row : START -->
                                    <div class="row col-12">
                                      <div
                                        colspan="5"
                                        class="text-danger col-12"
                                        v-show="
                                          deal_finance_form.lien_holder_name !==
                                            ''
                                        "
                                      >
                                        HINT : Non-Taxable Fees might have Taxes
                                        for Lender from Dealertrack.
                                      </div>
                                      <div
                                        colspan="5"
                                        class="text-danger col-12"
                                        v-show="
                                          deal_finance_form.lien_holder_name ===
                                            'Scotia Dealer Advantage'
                                        "
                                      >
                                        This Lender Includes Lien Registration
                                        Fee Within the Administration Fee.
                                        Adjust the Lien Registration Fee Amount
                                        to match the Total.
                                      </div>
                                      <div
                                        colspan="5"
                                        class="text-danger col-12"
                                        v-show="
                                          deal_finance_form.lien_holder_name ===
                                            'TD Auto Finance'
                                        "
                                      >
                                        Remove Tax from the Admin Fee to match
                                        the Taxes.
                                      </div>
                                      <!-- deal fees : START -->
                                      <div
                                        id="deal-fees-table"
                                        class="content-wrapper mt-0 p-0 col-12"
                                      >
                                        <table
                                          class="list-page-content-p mt-0 mb-0 w-100"
                                        >
                                          <thead>
                                            <tr class="bg-grey">
                                              <th class="text-center">Name</th>
                                              <th
                                                class="text-center"
                                                style="min-width: 75px"
                                              >
                                                Amount
                                              </th>
                                              <th
                                                class="text-center"
                                                style="min-width: 75px"
                                              >
                                                Tax Type
                                              </th>
                                              <th
                                                class="text-center"
                                                style="min-width: 75px"
                                              >
                                                Taxes
                                              </th>
                                              <th class="text-center">
                                                Actions
                                              </th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr
                                              v-for="(deal_fee,
                                              index) in deal_fees"
                                              :key="`deal_fee` + index"
                                            >
                                              <td class="text-center">
                                                {{
                                                  deal_fee.name
                                                    | truncate(25, "...")
                                                }}
                                              </td>
                                              <td class="text-center">
                                                {{
                                                  `$ ${Number(
                                                    deal_fee.amount,
                                                  ).toLocaleString()}`
                                                }}
                                              </td>
                                              <td class="text-center">
                                                <p class="mb-0">
                                                  {{ deal_fee.tax_type }}
                                                </p>
                                                <p
                                                  class="mb-0"
                                                  v-show="
                                                    deal_fee.tax_type_secondary !==
                                                      ''
                                                  "
                                                >
                                                  {{
                                                    deal_fee.tax_type_secondary
                                                  }}
                                                </p>
                                              </td>
                                              <td class="text-center">
                                                {{
                                                  `$ ${Number(
                                                    deal_fee.tax_amount_total,
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
                                                      transform: translate3d(
                                                        19px,
                                                        19px,
                                                        0px
                                                      );
                                                    "
                                                  >
                                                    <a
                                                      class="dropdown-item"
                                                      style="cursor: pointer"
                                                      @click.prevent="
                                                        editFeeAction(
                                                          'fees',
                                                          index,
                                                        )
                                                      "
                                                      >Edit</a
                                                    >
                                                    <a
                                                      class="dropdown-item"
                                                      style="cursor: pointer"
                                                      @click.prevent="
                                                        deleteFee('fees', index)
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

                            <div class="col-xs-12 col-md-6">
                              <!-- card : START -->
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body pb-1 mx-25 row">
                                    <h4 class="card-title col-8">
                                      <i
                                        class="bx bxs-widget bx-sm"
                                        style="padding-right: 1em"
                                      ></i>
                                      <span
                                        style="
                                          display: inline-block;
                                          vertical-align: top;
                                        "
                                        >Accessories</span
                                      >
                                    </h4>
                                    <div class="col-4 text-right">
                                      <button
                                        class="btn btn-primary text-bold-700"
                                        @click.prevent="
                                          addAction('accessories')
                                        "
                                      >
                                        <i
                                          class="bx bx-list-plus"
                                          style="padding-right: 1em"
                                        ></i>
                                        Add Accessory
                                      </button>
                                    </div>
                                    <!-- row : START -->
                                    <div class="row col-12">
                                      <!-- deal accessories : START -->
                                      <div
                                        id="deal-accessories-table"
                                        class="content-wrapper mt-0 p-0 col-12"
                                      >
                                        <table
                                          class="list-page-content-p mt-0 mb-0 w-100"
                                        >
                                          <thead>
                                            <tr class="bg-grey">
                                              <th class="text-center">Name</th>
                                              <th
                                                class="text-center"
                                                style="min-width: 75px"
                                              >
                                                Amount
                                              </th>
                                              <th
                                                class="text-center"
                                                style="min-width: 75px"
                                              >
                                                Tax Type
                                              </th>
                                              <th
                                                class="text-center"
                                                style="min-width: 75px"
                                              >
                                                Taxes
                                              </th>
                                              <th class="text-center">
                                                Actions
                                              </th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr
                                              v-for="(deal_accessory,
                                              index) in deal_accessories"
                                              :key="`deal_accessory` + index"
                                            >
                                              <td class="text-center">
                                                {{
                                                  deal_accessory.name
                                                    | truncate(25, "...")
                                                }}
                                              </td>
                                              <td class="text-center">
                                                {{
                                                  `$ ${Number(
                                                    deal_accessory.amount,
                                                  ).toLocaleString()}`
                                                }}
                                              </td>
                                              <td class="text-center">
                                                <p class="mb-0">
                                                  {{ deal_accessory.tax_type }}
                                                </p>
                                                <p
                                                  class="mb-0"
                                                  v-show="
                                                    deal_accessory.tax_type_secondary !==
                                                      ''
                                                  "
                                                >
                                                  {{
                                                    deal_accessory.tax_type_secondary
                                                  }}
                                                </p>
                                              </td>
                                              <td class="text-center">
                                                {{
                                                  `$ ${Number(
                                                    deal_accessory.tax_amount_total,
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
                                                      transform: translate3d(
                                                        19px,
                                                        19px,
                                                        0px
                                                      );
                                                    "
                                                  >
                                                    <a
                                                      class="dropdown-item"
                                                      style="cursor: pointer"
                                                      @click.prevent="
                                                        editFeeAction(
                                                          'accessories',
                                                          index,
                                                        )
                                                      "
                                                      >Edit</a
                                                    >
                                                    <a
                                                      class="dropdown-item"
                                                      style="cursor: pointer"
                                                      @click.prevent="
                                                        deleteFee(
                                                          'accessories',
                                                          index,
                                                        )
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
                                      <!-- deal accessories : END -->
                                    </div>
                                    <!-- row : END -->
                                  </div>
                                </div>
                              </div>
                              <!-- card : END -->
                            </div>

                            <div class="col-xs-12 col-md-6">
                              <!-- card : START -->
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body pb-1 mx-25 row">
                                    <h4 class="card-title col-8">
                                      <i
                                        class="bx bxs-shield-alt-2 bx-sm"
                                        style="padding-right: 1em"
                                      ></i>
                                      <span
                                        style="
                                          display: inline-block;
                                          vertical-align: top;
                                        "
                                        >Warranties</span
                                      >
                                    </h4>
                                    <div class="col-4 text-right">
                                      <button
                                        class="btn btn-primary text-bold-700"
                                        @click.prevent="addAction('warranties')"
                                        v-show="deal_warranties.length == 0"
                                      >
                                        <i
                                          class="bx bx-list-plus"
                                          style="padding-right: 1em"
                                        ></i>
                                        Add Warranty
                                      </button>
                                    </div>
                                    <div class="col-12 text-danger">
                                      HINT: For British Columbia Region, you may
                                      need to change PST to Tax Exempt for
                                      "Extended Service Contract" warranty to
                                      match the Taxes.
                                    </div>
                                    <!-- row : START -->
                                    <div class="row col-12">
                                      <!-- deal warranties : START -->
                                      <div
                                        id="deal-warranties-table"
                                        class="content-wrapper mt-0 p-0 col-12"
                                      >
                                        <table
                                          class="list-page-content-p mt-0 mb-0 w-100"
                                        >
                                          <thead>
                                            <tr class="bg-grey">
                                              <th class="text-center">
                                                Company Name / Description
                                              </th>
                                              <th
                                                class="text-center"
                                                style="min-width: 75px"
                                              >
                                                Amount
                                              </th>
                                              <th
                                                class="text-center"
                                                style="min-width: 75px"
                                              >
                                                Tax Type
                                              </th>
                                              <th
                                                class="text-center"
                                                style="min-width: 75px"
                                              >
                                                Taxes
                                              </th>
                                              <th class="text-center">
                                                Actions
                                              </th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr
                                              v-for="(deal_warranty,
                                              index) in deal_warranties"
                                              :key="`deal_warranty` + index"
                                            >
                                              <td class="text-center">
                                                {{
                                                  deal_warranty.company_name
                                                    | truncate(25, "...")
                                                }}
                                                <hr />
                                                <small>
                                                  {{
                                                    deal_warranty.description
                                                      | truncate(25, "...")
                                                  }}
                                                </small>
                                              </td>
                                              <td class="text-center">
                                                {{
                                                  `$ ${Number(
                                                    deal_warranty.amount,
                                                  ).toLocaleString()}`
                                                }}
                                              </td>
                                              <td class="text-center">
                                                <p class="mb-0">
                                                  {{ deal_warranty.tax_type }}
                                                </p>
                                                <p
                                                  class="mb-0"
                                                  v-show="
                                                    deal_warranty.tax_type_secondary !==
                                                      ''
                                                  "
                                                >
                                                  {{
                                                    deal_warranty.tax_type_secondary
                                                  }}
                                                </p>
                                              </td>
                                              <td class="text-center">
                                                {{
                                                  `$ ${Number(
                                                    deal_warranty.tax_amount_total,
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
                                                      transform: translate3d(
                                                        19px,
                                                        19px,
                                                        0px
                                                      );
                                                    "
                                                  >
                                                    <a
                                                      class="dropdown-item"
                                                      style="cursor: pointer"
                                                      @click.prevent="
                                                        editFeeAction(
                                                          'warranties',
                                                          index,
                                                        )
                                                      "
                                                      >Edit</a
                                                    >
                                                    <a
                                                      class="dropdown-item"
                                                      style="cursor: pointer"
                                                      @click.prevent="
                                                        deleteFee(
                                                          'warranties',
                                                          index,
                                                        )
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
                                      <!-- deal warranties : END -->
                                    </div>
                                    <!-- row : END -->
                                  </div>
                                </div>
                              </div>
                              <!-- card : END -->
                            </div>

                            <div class="col-xs-12 col-md-6">
                              <!-- card : START -->
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body pb-1 mx-25 row">
                                    <h4 class="card-title col-8">
                                      <i
                                        class="bx bxs-check-shield bx-sm"
                                        style="padding-right: 1em"
                                      ></i>
                                      <span
                                        style="
                                          display: inline-block;
                                          vertical-align: top;
                                        "
                                        >Guarantees</span
                                      >
                                    </h4>
                                    <div class="col-4 text-right">
                                      <button
                                        class="btn btn-primary text-bold-700"
                                        @click.prevent="addAction('guarantee')"
                                        v-show="deal_guarantees.length == 0"
                                      >
                                        <i
                                          class="bx bx-list-plus"
                                          style="padding-right: 1em"
                                        ></i>
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
                                        <table
                                          class="list-page-content-p mt-0 mb-0 w-100"
                                        >
                                          <thead>
                                            <tr class="bg-grey">
                                              <th class="text-center">
                                                Company Name / Description
                                              </th>
                                              <th
                                                class="text-center"
                                                style="min-width: 75px"
                                              >
                                                Amount
                                              </th>
                                              <th
                                                class="text-center"
                                                style="min-width: 75px"
                                              >
                                                Tax Type
                                              </th>
                                              <th
                                                class="text-center"
                                                style="min-width: 75px"
                                              >
                                                Taxes
                                              </th>
                                              <th class="text-center">
                                                Actions
                                              </th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr
                                              v-for="(deal_guarantee,
                                              index) in deal_guarantees"
                                              :key="`deal_guarantee` + index"
                                            >
                                              <td class="text-center">
                                                {{
                                                  deal_guarantee.company_name
                                                    | truncate(25, "...")
                                                }}
                                                <hr />
                                                <small>
                                                  {{
                                                    deal_guarantee.description
                                                      | truncate(25, "...")
                                                  }}
                                                </small>
                                              </td>
                                              <td class="text-center">
                                                {{
                                                  `$ ${Number(
                                                    deal_guarantee.amount,
                                                  ).toLocaleString()}`
                                                }}
                                              </td>
                                              <td class="text-center">
                                                <p class="mb-0">
                                                  {{ deal_guarantee.tax_type }}
                                                </p>
                                                <p
                                                  class="mb-0"
                                                  v-show="
                                                    deal_guarantee.tax_type_secondary !==
                                                      ''
                                                  "
                                                >
                                                  {{
                                                    deal_guarantee.tax_type_secondary
                                                  }}
                                                </p>
                                              </td>
                                              <td class="text-center">
                                                {{
                                                  `$ ${Number(
                                                    deal_guarantee.tax_amount_total,
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
                                                      transform: translate3d(
                                                        19px,
                                                        19px,
                                                        0px
                                                      );
                                                    "
                                                  >
                                                    <a
                                                      class="dropdown-item"
                                                      style="cursor: pointer"
                                                      @click.prevent="
                                                        editFeeAction(
                                                          'guarantee',
                                                          index,
                                                        )
                                                      "
                                                      >Edit</a
                                                    >
                                                    <a
                                                      class="dropdown-item"
                                                      style="cursor: pointer"
                                                      @click.prevent="
                                                        deleteFee(
                                                          'guarantee',
                                                          index,
                                                        )
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

                            <div class="col-xs-12 col-md-6">
                              <!-- card : START -->
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body pb-1 mx-25 row">
                                    <h4 class="card-title col-8">
                                      <i
                                        class="bx bxs-first-aid bx-sm"
                                        style="padding-right: 1em"
                                      ></i>
                                      <span
                                        style="
                                          display: inline-block;
                                          vertical-align: top;
                                        "
                                        >Insurances</span
                                      >
                                    </h4>
                                    <div class="col-4 text-right">
                                      <button
                                        class="btn btn-primary text-bold-700"
                                        @click.prevent="addAction('insurances')"
                                      >
                                        <i
                                          class="bx bx-list-plus"
                                          style="padding-right: 1em"
                                        ></i>
                                        Add Insurance
                                      </button>
                                    </div>
                                    <!-- row : START -->
                                    <div class="row col-12">
                                      <!-- deal insurances : START -->
                                      <div
                                        id="deal-insurances-table"
                                        class="content-wrapper mt-0 p-0 col-12"
                                      >
                                        <table
                                          class="list-page-content-p mt-0 mb-0 w-100"
                                        >
                                          <thead>
                                            <tr class="bg-grey">
                                              <th class="text-center">
                                                Type / Description
                                              </th>
                                              <th
                                                class="text-center"
                                                style="min-width: 75px"
                                              >
                                                Amount
                                              </th>
                                              <th
                                                class="text-center"
                                                style="min-width: 75px"
                                              >
                                                Tax Type
                                              </th>
                                              <th
                                                class="text-center"
                                                style="min-width: 75px"
                                              >
                                                Taxes
                                              </th>
                                              <th class="text-center">
                                                Actions
                                              </th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr
                                              v-for="(deal_insurance,
                                              index) in deal_insurances"
                                              :key="`deal_insurance` + index"
                                            >
                                              <td class="text-center">
                                                {{
                                                  deal_insurance.type
                                                    | truncate(25, "...")
                                                }}
                                                <hr />
                                                <small>
                                                  {{
                                                    deal_insurance.description
                                                      | truncate(25, "...")
                                                  }}
                                                </small>
                                              </td>
                                              <td class="text-center">
                                                {{
                                                  `$ ${Number(
                                                    deal_insurance.amount,
                                                  ).toLocaleString()}`
                                                }}
                                              </td>
                                              <td class="text-center">
                                                <p class="mb-0">
                                                  {{ deal_insurance.tax_type }}
                                                </p>
                                                <p
                                                  class="mb-0"
                                                  v-show="
                                                    deal_insurance.tax_type_secondary !==
                                                      ''
                                                  "
                                                >
                                                  {{
                                                    deal_insurance.tax_type_secondary
                                                  }}
                                                </p>
                                              </td>
                                              <td class="text-center">
                                                {{
                                                  `$ ${Number(
                                                    deal_insurance.tax_amount_total,
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
                                                      transform: translate3d(
                                                        19px,
                                                        19px,
                                                        0px
                                                      );
                                                    "
                                                  >
                                                    <a
                                                      class="dropdown-item"
                                                      style="cursor: pointer"
                                                      @click.prevent="
                                                        editFeeAction(
                                                          'insurances',
                                                          index,
                                                        )
                                                      "
                                                      >Edit</a
                                                    >
                                                    <a
                                                      class="dropdown-item"
                                                      style="cursor: pointer"
                                                      @click.prevent="
                                                        deleteFee(
                                                          'insurances',
                                                          index,
                                                        )
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
                                      <!-- deal insurances : END -->
                                    </div>
                                    <!-- row : END -->
                                  </div>
                                </div>
                              </div>
                              <!-- card : END -->
                            </div>

                            <div class="col-xs-12 col-md-6">
                              <!-- card : START -->
                              <div class="card">
                                <div class="card-content">
                                  <div class="card-body pb-1 mx-25 row">
                                    <h4 class="card-title col-8">
                                      <i
                                        class="bx bxs-credit-card-alt bx-sm"
                                        style="padding-right: 1em"
                                      ></i>
                                      <span
                                        style="
                                          display: inline-block;
                                          vertical-align: top;
                                        "
                                        >Payments</span
                                      >
                                    </h4>
                                    <div class="col-4 text-right">
                                      <button
                                        class="btn btn-primary text-bold-700"
                                        @click.prevent="addAction('payments')"
                                      >
                                        <i
                                          class="bx bx-list-plus"
                                          style="padding-right: 1em"
                                        ></i>
                                        Add Payment
                                      </button>
                                    </div>
                                    <!-- row : START -->
                                    <div class="row col-12">
                                      <!-- deal payments : START -->
                                      <div
                                        id="deal-payments-table"
                                        class="content-wrapper mt-0 p-0 col-12"
                                      >
                                        <table
                                          class="list-page-content-p mt-0 mb-0 w-100"
                                        >
                                          <thead>
                                            <tr class="bg-grey">
                                              <th class="text-center">Type</th>
                                              <th class="text-center">
                                                Description
                                              </th>
                                              <th
                                                class="text-center"
                                                style="min-width: 75px"
                                              >
                                                Amount
                                              </th>
                                              <th class="text-center">
                                                Actions
                                              </th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr
                                              v-for="(deal_payment,
                                              index) in deal_payments"
                                              :key="`deal_payment` + index"
                                            >
                                              <td class="text-center">
                                                {{
                                                  deal_payment.type
                                                    | truncate(25, "...")
                                                }}
                                              </td>
                                              <td class="text-center">
                                                {{
                                                  deal_payment.description
                                                    | truncate(25, "...")
                                                }}
                                              </td>
                                              <td class="text-center">
                                                {{
                                                  `$ ${Number(
                                                    deal_payment.amount,
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
                                                      transform: translate3d(
                                                        19px,
                                                        19px,
                                                        0px
                                                      );
                                                    "
                                                  >
                                                    <a
                                                      class="dropdown-item"
                                                      style="cursor: pointer"
                                                      @click.prevent="
                                                        editFeeAction(
                                                          'payments',
                                                          index,
                                                        )
                                                      "
                                                      >Edit</a
                                                    >
                                                    <a
                                                      class="dropdown-item"
                                                      style="cursor: pointer"
                                                      @click.prevent="
                                                        deleteFee(
                                                          'payments',
                                                          index,
                                                        )
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
                                      <!-- deal payments : END -->
                                    </div>
                                    <!-- row : END -->
                                  </div>
                                </div>
                              </div>
                              <!-- card : END -->
                            </div>
                          </div>
                          <!-- Deal Breakdown : END -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
    </div>
    <deal-import-fee></deal-import-fee>
    <deal-import-accessory></deal-import-accessory>
    <deal-import-warranty></deal-import-warranty>
    <deal-import-guarantee></deal-import-guarantee>
    <deal-import-insurance></deal-import-insurance>
    <deal-import-payment></deal-import-payment>
    <v-i-n-decoder :elementId="'deal_inventory_form'"></v-i-n-decoder>
    <v-i-n-decoder :elementId="'deal_trade_in_form'"></v-i-n-decoder>
    <!-- action bar : START -->
    <ul class="nav nav-tabs p-50 custom-footer-fixed-action-bar" role="tablist">
      <button
        :disabled="is_loading"
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
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import { mapGetters, mapActions } from "vuex";
import { debounce } from "vue-debounce";
import Inputmask from "inputmask";

import Toast from "../../library/Toast";
import SuggestLocation from "../WebComponents/SuggestLocation.vue";
import VINDecoder from "../WebComponents/VINDecoder";

import DealImportFee from "./DealImportComponent/DealImportFee.vue";
import DealImportAccessory from "./DealImportComponent/DealImportAccessory.vue";
import DealImportWarranty from "./DealImportComponent/DealImportWarranty.vue";
import DealImportGuarantee from "./DealImportComponent/DealImportGuarantee.vue";
import DealImportInsurance from "./DealImportComponent/DealImportInsurance.vue";
import DealImportPayment from "./DealImportComponent/DealImportPayment.vue";

export default {
  name: "DealImport",
  props: {
    app_metadata: { required: true },
    user_metadata: { required: true },
  },
  components: {
    DatePicker,
    DealImportFee,
    DealImportAccessory,
    DealImportWarranty,
    DealImportGuarantee,
    DealImportInsurance,
    DealImportPayment,
    Inputmask,
    SuggestLocation,
    VINDecoder,
  },
  watch: {
    skip_primary_customer: function(newVal, oldVal) {
      if (newVal !== oldVal) {
        if (newVal) {
          this.resetPrimaryCustomerForm();
        }
      }
    },
    skip_inventory: function(newVal, oldVal) {
      if (newVal !== oldVal) {
        if (newVal) {
          this.resetDealInventoryForm();
        }
      }
    },
    has_trade_in: function(newVal, oldVal) {
      if (newVal === false) {
        this.resetDealTradeInForm();
        this.calculateWorkSheet();
      }
    },
    "form.type": function(newVal, oldVal) {
      if (newVal != oldVal && !this.type_dismissed) {
        if (
          !this.is_loading &&
          parseInt(this.form.retail_or_wholesale_flag) != 1
        ) {
          this.$swal({
            showConfirmButton: true,
            showCancelButton: true,
            cancelButtonText: "No",
            confirmButtonText: "Yes",
            text:
              "Changes to type of deal will affect the Deal Worksheet and Trade In Details. Do you want to continue?",
          }).then((result) => {
            if (result.isConfirmed) {
              this.form.type = newVal;
              this.resetDealFinanceForm();
            } else if (result.isDismissed) {
              this.form.type = oldVal;
              this.type_dismissed = true;
            }
          });
        }
      } else {
        this.type_dismissed = !this.type_dismissed;
      }
    },
    "deal_base_breakdown_form.purchase_price": function(newVal) {
      this.deal_base_breakdown_form.purchase_price = this.$helpers.coerceToNumberString(
        this.deal_base_breakdown_form.purchase_price,
      );
    },
    "deal_base_breakdown_form.licence_fee": function(newVal) {
      this.deal_base_breakdown_form.licence_fee = this.$helpers.coerceToNumberString(
        this.deal_base_breakdown_form.licence_fee,
      );
    },
    "deal_base_breakdown_form.gasoline": function(newVal) {
      this.deal_base_breakdown_form.gasoline = this.$helpers.coerceToNumberString(
        this.deal_base_breakdown_form.gasoline,
      );
    },
    "deal_base_breakdown_form.lien_registration_fee": function(newVal) {
      this.deal_base_breakdown_form.lien_registration_fee = this.$helpers.coerceToNumberString(
        this.deal_base_breakdown_form.lien_registration_fee,
      );
    },
    "deal_finance_form.finance_commission": function(newVal) {
      this.deal_finance_form.finance_commission = this.$helpers.coerceToNumberString(
        this.deal_finance_form.finance_commission,
      );
    },
    "deal_finance_form.interest_rate": function(newVal) {
      this.deal_finance_form.interest_rate = this.$helpers.coerceToNumberString(
        this.deal_finance_form.interest_rate,
      );
    },
    "deal_finance_form.term": function(newVal) {
      this.deal_finance_form.term = this.$helpers.coerceToInteger(
        this.deal_finance_form.term,
      );
    },
    "deal_finance_form.interest_amount": function(newVal) {
      this.deal_finance_form.interest_amount = this.$helpers.coerceToNumberString(
        this.deal_finance_form.interest_amount,
      );
    },
    "deal_finance_form.emi_amount": function(newVal) {
      this.deal_finance_form.emi_amount = this.$helpers.coerceToNumberString(
        this.deal_finance_form.emi_amount,
      );
    },
    businessTaxSettings: function(newVal, oldVal) {
      if (this.is_loading == true) {
        let selected_tax = this.businessTaxSettings.filter(
          (tax) => tax.default == "1",
        );

        if (selected_tax.length > 0) {
          this.deal_base_breakdown_form.purchase_tax_type =
            selected_tax[0].name;
          if (selected_tax.length > 1) {
            this.deal_base_breakdown_form.purchase_tax_type_secondary =
              selected_tax[1].name;
          }
        }
      }

      this.is_loading = false;
    },
  },
  data() {
    return {
      is_loading: true,
      deal_fees: [],
      deal_accessories: [],
      deal_warranties: [],
      deal_guarantees: [],
      deal_insurances: [],
      deal_payments: [],
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
      deal_base_breakdown_form: {
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
      deal_inventory_form: {
        vin_company: "",
        vin: "",
        vindisplay: "1",
        stock_no: "",
        stock_date: "",
        status: "Unpublished",
        sub_status: "Reconditioning",
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
      },
      deal_trade_in_form: {
        vin_company: "",
        vin: "",
        vindisplay: "1",
        stock_no: "",
        stock_date: "",
        status: "Unpublished",
        sub_status: "Reconditioning",
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

        trade_value: "0",
        lien_value: "0",
        trade_equity: "0",

        lien_holder: "",
      },
      form: {
        type: "Financed",
        import_inventory: false,
        inventory_sub_status: "Available For Sale",
        mark_inventory_as_sold: false,
        date: "",
        retail_or_wholesale_flag: 0,
        status: "Open",
        delivery_date: null,
        delivery_time: null,
        note: "",
        disclosure: "",
        condition: "",
        approved_by: "",
        salesperson: "",
        cancellation_date: "",
        close_date: "",
        brand: "",
        certified: "",
      },
      errors: {
        base_breakdown: {},
        finance: {},
        trade_in: {},
        inventory: {},
        primary_customer: {},
      },
      deal_customer_form: {
        first_name: "",
        last_name: "",
        customer_type: 0,
        company_name: "",
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
        rin_or_dl_flag: 1,
        driver_licence_expiry_date: "",
        date_of_birth: "",
        mvda: "",
        year_end: "",
        sales_person_registration: "",
        additional_information: "",
        other_data: [],
        is_encrypted: 0,
        assignee: "",
      },
      has_trade_in: false,
      skip_inventory: false,
      skip_primary_customer: false,
      body_types: [],
      current_index: null,
      type_dismissed: false,
      has_trade_in_dismissed: false,
      deals: [],
      pdf_parser_form: {
        source: "",
        file: null,
      },
    };
  },
  computed: {
    ...mapGetters("deal", ["getDealStatuses"]),
    ...mapGetters("users", ["allUsers"]),
    ...mapGetters("configuration", ["businessTaxSettings"]),
    managersAndOwners() {
      return this.allUsers.filter(
        (user) => user.role == "Owner" || user.role == "Manager",
      );
    },
    salesPersons() {
      return this.allUsers.filter(
        (user) =>
          user.role == "Staff" ||
          user.role == "Owner" ||
          user.role == "Manager",
      );
    },
    isDealFinanced() {
      return this.form.type === "Financed";
    },
    isFinanceOverriden() {
      let override = parseInt(
        this.deal_finance_form.override_finance_calculation,
        10,
      );

      return override === 1;
    },
    calculateDealTotalFeesAmount() {
      let total = this.deal_fees.reduce(
        (amount, fee) => amount + parseFloat(fee.amount),
        0,
      );

      return this.$helpers.round(total, 3);
    },
    calculateDealTotalFeesTotalTax() {
      let total = this.deal_fees.reduce(
        (tax_amount_total, fee) =>
          tax_amount_total + parseFloat(fee.tax_amount_total),
        0,
      );

      return this.$helpers.round(total, 3);
    },
    calculateDealTotalAccessoriesAmount() {
      let total = this.deal_accessories.reduce(
        (amount, accessory) => amount + parseFloat(accessory.amount),
        0,
      );

      return this.$helpers.round(total, 3);
    },
    calculateDealTotalAccessoriesTotalTax() {
      let total = this.deal_accessories.reduce(
        (tax_amount_total, accessory) =>
          tax_amount_total + parseFloat(accessory.tax_amount_total),
        0,
      );

      return this.$helpers.round(total, 3);
    },
    calculateDealTotalWarrantiesAmount() {
      let total = this.deal_warranties.reduce(
        (amount, deal_warranty) => amount + parseFloat(deal_warranty.amount),
        0,
      );

      return this.$helpers.round(total, 3);
    },
    calculateDealTotalWarrantiesTotalTax() {
      let total = this.deal_warranties.reduce(
        (tax_amount_total, deal_warranty) =>
          tax_amount_total + parseFloat(deal_warranty.tax_amount_total),
        0,
      );

      return this.$helpers.round(total, 3);
    },
    calculateDealTotalInsurancesAmount() {
      let total = this.deal_insurances.reduce(
        (amount, deal_insurance) => amount + parseFloat(deal_insurance.amount),
        0,
      );

      return this.$helpers.round(total, 3);
    },
    calculateDealTotalInsurancesTotalTax() {
      let total = this.deal_insurances.reduce(
        (tax_amount_total, deal_insurance) =>
          tax_amount_total + parseFloat(deal_insurance.tax_amount_total),
        0,
      );

      return this.$helpers.round(total, 3);
    },
    calculateTotalPayment() {
      let total = this.deal_payments.reduce(
        (amount, payment) => amount + parseFloat(payment.amount),
        0,
      );

      return this.$helpers.round(total, 3);
    },
  },
  methods: {
    ...mapActions("users", ["getAllUsers"]),
    ...mapActions("configuration", ["getBusinessTaxSettings"]),
    save() {
      const vm = this;
      this.is_loading = true;
      let formData = new FormData();
      for (const key in this.form) {
        formData.append(key, this.form[key]);
      }

      if (!this.skip_inventory) {
        for (const deal_inventory_key in this.deal_inventory_form) {
          if (this.deal_inventory_form.hasOwnProperty(deal_inventory_key)) {
            formData.append(
              `inventory[${deal_inventory_key}]`,
              this.deal_inventory_form[deal_inventory_key],
            );
          }
        }
      }

      if (!this.skip_primary_customer) {
        for (const deal_primary_customer_key in this.deal_customer_form) {
          if (
            this.deal_customer_form.hasOwnProperty(deal_primary_customer_key)
          ) {
            formData.append(
              `primary_customer[${deal_primary_customer_key}]`,
              this.deal_customer_form[deal_primary_customer_key],
            );
          }
        }
      }

      for (const deal_base_breakdown_key in this.deal_base_breakdown_form) {
        if (
          this.deal_base_breakdown_form.hasOwnProperty(deal_base_breakdown_key)
        ) {
          formData.append(
            `base_breakdown[${deal_base_breakdown_key}]`,
            this.deal_base_breakdown_form[deal_base_breakdown_key],
          );
        }
      }

      if (this.isDealFinanced) {
        for (const deal_finance_key in this.deal_finance_form) {
          if (this.deal_finance_form.hasOwnProperty(deal_finance_key)) {
            formData.append(
              `finance[${deal_finance_key}]`,
              this.deal_finance_form[deal_finance_key],
            );
          }
        }
      }

      for (const deal_fee_key in this.deal_fees) {
        for (const form_key in this.deal_fees[deal_fee_key]) {
          formData.append(
            `fees[${deal_fee_key}][${form_key}]`,
            this.deal_fees[deal_fee_key][form_key],
          );
        }
      }

      for (const deal_accessory_key in this.deal_accessories) {
        for (const form_key in this.deal_accessories[deal_accessory_key]) {
          formData.append(
            `accessories[${deal_accessory_key}][${form_key}]`,
            this.deal_accessories[deal_accessory_key][form_key],
          );
        }
      }

      for (const deal_warranty_key in this.deal_warranties) {
        for (const form_key in this.deal_warranties[deal_warranty_key]) {
          formData.append(
            `warranties[${deal_warranty_key}][${form_key}]`,
            this.deal_warranties[deal_warranty_key][form_key],
          );
        }
      }

      for (const deal_guarantee_key in this.deal_guarantees) {
        for (const form_key in this.deal_guarantees[deal_guarantee_key]) {
          formData.append(
            `guarantee[${deal_guarantee_key}][${form_key}]`,
            this.deal_guarantees[deal_guarantee_key][form_key],
          );
        }
      }

      for (const deal_insurance_key in this.deal_insurances) {
        for (const form_key in this.deal_insurances[deal_insurance_key]) {
          formData.append(
            `insurances[${deal_insurance_key}][${form_key}]`,
            this.deal_insurances[deal_insurance_key][form_key],
          );
        }
      }

      for (const deal_payment_key in this.deal_payments) {
        for (const form_key in this.deal_payments[deal_payment_key]) {
          formData.append(
            `payments[${deal_payment_key}][${form_key}]`,
            this.deal_payments[deal_payment_key][form_key],
          );
        }
      }

      if (this.has_trade_in) {
        for (const deal_trade_in_key in this.deal_trade_in_form) {
          if (this.deal_trade_in_form.hasOwnProperty(deal_trade_in_key)) {
            formData.append(
              `trade_in[${deal_trade_in_key}]`,
              this.deal_trade_in_form[deal_trade_in_key],
            );
          }
        }
      }

      let request_url = `${window.location.origin}/api/business/deals/import`;
      let message = `Importing Deal ...`;

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
          this.is_loading = false;

          if (this.deals.length > 5) {
            this.deals.splice(0, 1);
          }

          let line_1 = `DEAL #${data.deal_id} FOR`;

          let line_2 = "";
          if (!this.skip_primary_customer) {
            line_2 = `${this.deal_customer_form.first_name} ${this.deal_customer_form.last_name}`;
          } else if (!this.skip_inventory) {
            line_2 = `${this.deal_inventory_form.year} ${this.deal_inventory_form.make} ${this.deal_inventory_form.model}`;
          }

          this.deals.push({
            line_1,
            line_2,
            deal_id: data.deal_id,
          });

          toast.success(data.success);
          toast.close();

          this.resetAllForms();
          this.resetAllErrors();
        })
        .catch(function(error) {
          vm.$data.errors = error.response.data;

          if (!vm.$data.errors.hasOwnProperty("base_breakdown")) {
            vm.$data.errors.base_breakdown = {};
          }

          if (!vm.$data.errors.hasOwnProperty("finance")) {
            vm.$data.errors.finance = {};
          }

          if (!vm.$data.errors.hasOwnProperty("inventory")) {
            vm.$data.errors.inventory = {};
          }

          if (!vm.$data.errors.hasOwnProperty("trade_in")) {
            vm.$data.errors.trade_in = {};
          }

          if (!vm.$data.errors.hasOwnProperty("primary_customer")) {
            vm.$data.errors.primary_customer = {};
          }

          let error_message = "There were some errors while submitting form.";
          toast.fail(error_message);
          toast.close();

          vm.$data.is_loading = false;
        });
    },
    addAction(current_module) {
      Event.fire("deal_import_worksheet_entity", {
        action: "add",
        module: current_module,
        payload: null,
      });
    },
    calculateWorkSheet() {
      let formData = new FormData();

      formData.append(
        "purchase_price",
        this.deal_base_breakdown_form.purchase_price,
      );
      formData.append("fees_total", this.calculateDealTotalFeesAmount);
      formData.append(
        "accessories_total",
        this.calculateDealTotalAccessoriesAmount,
      );
      formData.append(
        "warranty_total_amount",
        this.calculateDealTotalWarrantiesAmount,
      );
      formData.append(
        "insurance_total_amount",
        this.calculateDealTotalInsurancesAmount,
      );
      formData.append("downpayment", this.calculateTotalPayment);
      formData.append("fees_tax_total", this.calculateDealTotalFeesTotalTax);
      formData.append(
        "accessories_tax_total",
        this.calculateDealTotalAccessoriesTotalTax,
      );
      formData.append(
        "warranty_tax_amount_total",
        this.calculateDealTotalWarrantiesTotalTax,
      );
      formData.append(
        "insurance_tax_amount_total",
        this.calculateDealTotalInsurancesTotalTax,
      );

      formData.append("licence_fee", this.deal_base_breakdown_form.licence_fee);

      formData.append(
        "lien_registration_fee",
        this.deal_base_breakdown_form.lien_registration_fee,
      );

      formData.append("fuel_fee", this.deal_base_breakdown_form.gasoline);

      if (this.has_trade_in) {
        formData.append("trade_value", this.deal_trade_in_form.trade_value);
        formData.append("lien_payout", this.deal_trade_in_form.lien_value);
      }

      if (this.isDealFinanced) {
        formData.append("is_financed", this.isDealFinanced);
        formData.append("term", this.deal_finance_form.term);
        formData.append("interest_rate", this.deal_finance_form.interest_rate);
        formData.append(
          "payment_frequency",
          this.deal_finance_form.payment_frequency,
        );

        formData.append(
          "override_finance_calculation",
          this.deal_finance_form.override_finance_calculation,
        );

        formData.append(
          "interest_amount",
          this.deal_finance_form.interest_amount,
        );
      }

      let message = "Updating the deal worksheet ...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "post",
        url: `${window.location.origin}/api/services/calculator/build-worksheet`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          let deal_breakdown_attributes = [
            "subtotal",
            "trade_value",
            "net_difference",
            "purchase_tax_type",
            "purchase_tax_percentage",
            "tax_on_purchase_price",
            "purchase_tax_type_secondary",
            "tax_on_purchase_price_secondary",
            "purchase_tax_percentage_secondary",
            "tax_on_purchase_total",
            "lien_payout",
            "trade_equity",
            "total_balance_due",
            "secondary_subtotal",
          ];

          for (const attribute of deal_breakdown_attributes) {
            this.deal_base_breakdown_form[attribute] = data[attribute];
          }

          this.deal_base_breakdown_form["total_tax_amount"] =
            data["total_taxes"];
          this.deal_base_breakdown_form["insurance_tax"] =
            data["insurance_tax_amount_total"];

          if (this.isDealFinanced) {
            this.deal_finance_form["total_amount"] = data["finance_amount"];
            if (!this.isFinanceOverriden) {
              this.deal_finance_form["emi_amount"] = data["emi_amount"];
              this.deal_finance_form["interest_amount"] =
                data["total_interest"];
            }
          }

          if (this.has_trade_in) {
            this.deal_trade_in_form["trade_equity"] = data["trade_equity"];
          }

          toast.success(`Deal Worksheet updated.`);
          toast.close();
        })
        .catch(() => {
          toast.fail(`Failed to update the deal worksheet.`);
          toast.close();
        });
    },
    getInventoryConfigurations() {
      let request_url =
        window.location.origin +
        "/api/business/configurations/active/inventory";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (element.key == "editor_default_title") {
              this.deal_inventory_form.title = element.value;
              this.deal_trade_in_form.title = element.value;
            } else if (element.key == "editor_default_vehicle_description") {
              this.deal_inventory_form.vehicle_description = element.value;
              this.deal_trade_in_form.vehicle_description = element.value;
            } else if (element.key == "vehicle_default_financing") {
              this.deal_inventory_form.financing_on = element.value;
              this.deal_trade_in_form.financing_on = element.value;
            } else if (element.key == "vehicle_default_downpayment") {
              this.deal_inventory_form.downpayment = element.value;
              this.deal_trade_in_form.downpayment = element.value;
            } else if (element.key == "vehicle_default_interest_rate") {
              this.deal_inventory_form.interest_rate = element.value;
              this.deal_trade_in_form.interest_rate = element.value;
            } else if (element.key == "vehicle_default_term") {
              this.deal_inventory_form.term = element.value;
              this.deal_trade_in_form.term = element.value;
            } else if (element.key == "vehicle_default_payment_frequency") {
              this.deal_inventory_form.payment_frequency = element.value;
              this.deal_trade_in_form.payment_frequency = element.value;
            }
          }
        }
      });
    },
    async getAvailableTrimsForVIN(form_name) {
      Event.fire(
        `load_available_trims_for_a_vin_${form_name}`,
        this[form_name].vin,
      );
      return;
    },
    getVehicleInformation(form, data) {
      if (form === "deal_inventory_form") {
        this.errors.inventory = {};
      } else {
        this.errors.trade_in = {};
      }

      if (data.hasOwnProperty("vin_company")) {
        this[form].vin_company = data.vin_company;
      }
      if (data.hasOwnProperty("year")) {
        this[form].year = data.year;
      }
      if (data.hasOwnProperty("make")) {
        this[form].make = data.make;
      }
      if (data.hasOwnProperty("model")) {
        this[form].model = data.model;
      }
      if (data.hasOwnProperty("trim")) {
        this[form].trim = data.trim[0];
      }
      if (data.hasOwnProperty("body_type")) {
        this[form].body_type = data.body_type;
      }
      if (data.hasOwnProperty("door")) {
        this[form].door = data.door[0];
      }
      if (data.hasOwnProperty("drivetrain")) {
        this[form].drivetrain = data.drivetrain[0];
      }
      if (data.hasOwnProperty("engine")) {
        this[form].engine = data.engine[0];
      }
      if (data.hasOwnProperty("litres")) {
        this[form].litres = data.litres[0];
      }
      if (data.hasOwnProperty("transmission")) {
        this[form].transmission = data.transmission[0];
      }
      if (data.hasOwnProperty("passengers")) {
        this[form].passengers = data.passengers[0];
      }

      if (data.hasOwnProperty("fuel_type")) {
        this[form].fuel_type = data.fuel_type[0];
      }
      if (data.hasOwnProperty("fuel_city")) {
        this[form].fuel_city = data.fuel_city[0];
      }
      if (data.hasOwnProperty("fuel_hwy")) {
        this[form].fuel_hwy = data.fuel_hwy[0];
      }
      if (data.hasOwnProperty("standard")) {
        this[form].standard = JSON.stringify(data.standard);
      }
      if (data.hasOwnProperty("generic_equipment")) {
        this[form].generic_equipment = JSON.stringify(data.generic_equipment);
      }
      if (data.hasOwnProperty("technical_specification")) {
        this[form].technical_specification = JSON.stringify(
          data.technical_specification,
        );
      }
      if (data.hasOwnProperty("optional")) {
        this[form].optional = JSON.stringify(data.optional);
      }
    },
    addAFee(attribute, form) {
      this[attribute].push({ ...form });
    },
    updateAFee(attribute, form, index) {
      if (index !== null) {
        for (const [key, value] of Object.entries(form)) {
          this[attribute][index][key] = form[key];
        }
      }
    },
    editFeeAction(current_module, index) {
      let current_module_attribute = this.getModuleAttribute(current_module);

      Event.fire("deal_import_worksheet_entity", {
        action: "update",
        module: current_module,
        payload: this[current_module_attribute][index],
        module_index: index,
      });
    },
    deleteFee(current_module, current_index) {
      let current_module_attribute = this.getModuleAttribute(current_module);
      this[current_module_attribute].splice(current_index, 1);
    },
    getModuleAttribute(current_module) {
      let current_module_attribute = null;

      switch (current_module) {
        case "fees":
          current_module_attribute = "deal_fees";
          break;

        case "accessories":
          current_module_attribute = "deal_accessories";
          break;

        case "warranties":
          current_module_attribute = "deal_warranties";
          break;

        case "guarantee":
          current_module_attribute = "deal_guarantees";
          break;

        case "insurances":
          current_module_attribute = "deal_insurances";
          break;

        case "payments":
          current_module_attribute = "deal_payments";
          break;

        default:
          break;
      }

      return current_module_attribute;
    },
    getBodyTypes() {
      axios
        .get("/json/body_type.json")
        .then(({ data }) => (this.body_types = data));
    },
    triggerWorksheetCalculation: debounce(function(attribute = null) {
      this.calculateWorkSheet();
    }, "1000ms"),
    resetAllForms() {
      this.deal_fees = [];
      this.deal_accessories = [];
      this.deal_warranties = [];
      this.deal_guarantees = [];
      this.deal_insurances = [];
      this.deal_payments = [];

      this.resetDealFinanceForm();
      this.resetDealBreakDownForm();
      this.resetDealInventoryForm();
      this.resetDealTradeInForm();
      this.resetPrimaryCustomerForm();
      this.resetDealForm();
    },
    resetAllErrors() {
      this.errors.base_breakdown = {};
      this.errors.finance = {};
      this.errors.trade_in = {};
      this.errors.inventory = {};
      this.errors.primary_customer = {};
    },
    resetDealForm() {
      this.form.disclosure = "";
      this.form.certified = "";
      this.form.brand = "";
      this.form.note = "";
    },
    resetDealBreakDownForm() {
      this.errors.base_breakdown = {};

      let form = {
        purchase_price: 0,
        discount: 0,
        subtotal: 0,
        trade_value: 0,
        net_difference: 0,

        purchase_tax_type: this.deal_base_breakdown_form.purchase_tax_type,
        purchase_tax_percentage: this.deal_base_breakdown_form
          .purchase_tax_percentage,
        tax_on_purchase_price: 0,

        purchase_tax_type_secondary: this.deal_base_breakdown_form
          .purchase_tax_type_secondary,
        tax_on_purchase_price_secondary: 0,
        purchase_tax_percentage_secondary: this.deal_base_breakdown_form
          .purchase_tax_percentage_secondary,
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
      };

      this.deal_base_breakdown_form = {
        ...form,
      };
    },
    resetDealFinanceForm() {
      this.deal_finance_form.total_amount = 0;
      this.deal_finance_form.finance_commission = 0;
      this.deal_finance_form.override_finance_calculation = "0";
      this.deal_finance_form.interest_rate = 0;
      this.deal_finance_form.interest_rate_type = "Fixed";
      this.deal_finance_form.term = 0;
      this.deal_finance_form.payment_frequency = "Monthly";
      this.deal_finance_form.interest_amount = 0;
      this.deal_finance_form.emi_amount = 0;
      this.deal_finance_form.first_payment_date = null;
      this.deal_finance_form.lien_holder_name = "";

      this.errors.finance = {};
    },
    resetDealTradeInForm() {
      this.errors.trade_in = {};
      let form = {
        vin_company: "",
        vin: "",
        vindisplay: "1",
        stock_no: "",
        stock_date: "",
        status: "Unpublished",
        sub_status: "Reconditioning",
        feature_listing: "0",
        vehicle_type: "Used",
        visibility: "1",
        vehicle_tags: [],
        title: this.deal_trade_in_form.title,
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
        financing_on: this.deal_trade_in_form.financing_on,
        financing_special: "0",
        downpayment: this.deal_trade_in_form.downpayment,
        interest_rate: this.deal_trade_in_form.interest_rate,
        term: this.deal_trade_in_form.term,
        payment_frequency: this.deal_trade_in_form.payment_frequency,
        location: "",
        vehicle_description: this.deal_trade_in_form.vehicle_description,
        custom_features: [],
        syndication: [],

        trade_value: "0",
        lien_value: "0",
        trade_equity: "0",

        lien_holder: "",
      };

      this.deal_trade_in_form = {
        ...form,
      };
    },
    resetDealInventoryForm() {
      this.errors.inventory = {};

      let form = {
        vin_company: "",
        vin: "",
        vindisplay: "1",
        stock_no: "",
        stock_date: "",
        status: "Unpublished",
        sub_status: "Reconditioning",
        feature_listing: "0",
        vehicle_type: "Used",
        visibility: "1",
        vehicle_tags: [],
        title: this.deal_inventory_form.title,
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
        financing_on: this.deal_inventory_form.financing_on,
        financing_special: "0",
        downpayment: this.deal_inventory_form.downpayment,
        interest_rate: this.deal_inventory_form.interest_rate,
        term: this.deal_inventory_form.term,
        payment_frequency: this.deal_inventory_form.payment_frequency,
        location: "",
        vehicle_description: this.deal_inventory_form.vehicle_description,
        custom_features: [],
      };

      this.deal_inventory_form = {
        ...form,
      };
    },
    resetPrimaryCustomerForm() {
      this.errors.primary_customer = {};

      let form = {
        first_name: "",
        last_name: "",
        customer_type: 0,
        company_name: "",
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
        rin_or_dl_flag: 1,
        driver_licence_expiry_date: "",
        date_of_birth: "",
        mvda: "",
        year_end: "",
        sales_person_registration: "",
        additional_information: "",
        other_data: [],
        is_encrypted: 0,
        assignee: "",
      };

      this.deal_customer_form = {
        ...form,
      };
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
        let message = "Parsing the PDF. This may take upto few mins ...";
        const toast = new Toast(message);
        toast.fire();

        await axios({
          method: "post",
          url: `${window.location.origin}/api/business/deals/import/parser/pdf/${this.pdf_parser_form.source}`,
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

            this.resetAllForms();
            this.resetAllErrors();

            console.log(data);

            if (data.hasOwnProperty("customer")) {
              for (const customer_key in data.customer) {
                if (
                  this.deal_customer_form.hasOwnProperty(customer_key) &&
                  data.customer.hasOwnProperty(customer_key)
                ) {
                  this.deal_customer_form[customer_key] =
                    data.customer[customer_key];
                }
              }
            }

            if (data.hasOwnProperty("inventory")) {
              for (const inventory_key in data.inventory) {
                if (
                  this.deal_inventory_form.hasOwnProperty(inventory_key) &&
                  data.inventory.hasOwnProperty(inventory_key)
                ) {
                  this.deal_inventory_form[inventory_key] =
                    data.inventory[inventory_key];
                }
              }
            }

            if (data.hasOwnProperty("payments")) {
              if (data.payments.length > 0) {
                this.deal_payments = data["payments"];
              }
            }

            if (data.hasOwnProperty("base_breakdown")) {
              for (const base_break_down_key in data.base_breakdown) {
                if (
                  this.deal_base_breakdown_form.hasOwnProperty(
                    base_break_down_key,
                  )
                ) {
                  this.deal_base_breakdown_form[base_break_down_key] =
                    data.base_breakdown[base_break_down_key];
                }
              }
            }

            if (data.hasOwnProperty("trade_in") && data.has_trade_in) {
              this.has_trade_in = true;
              for (const trade_in_key in data.trade_in) {
                if (
                  this.deal_trade_in_form.hasOwnProperty(trade_in_key) &&
                  data.trade_in.hasOwnProperty(trade_in_key)
                ) {
                  this.deal_trade_in_form[trade_in_key] =
                    data.trade_in[trade_in_key];
                }
              }
            }

            if (data.hasOwnProperty("fees")) {
              this.deal_fees = data["fees"];
            }

            if (data.hasOwnProperty("insurances")) {
              this.deal_insurances = data["insurances"];
            }

            if (data.hasOwnProperty("warranties")) {
              this.deal_warranties = data["warranties"];
            }

            if (data.hasOwnProperty("finance") && this.isDealFinanced) {
              for (const finance_key in data.finance) {
                if (
                  this.deal_finance_form.hasOwnProperty(finance_key) &&
                  data.finance.hasOwnProperty(finance_key)
                ) {
                  this.deal_finance_form[finance_key] =
                    data.finance[finance_key];
                }
              }
            }

            toast.success("PDF Parsed successfully.");
            toast.close();

            this.calculateWorkSheet();
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
        "deal",
        "deal_import",
        "pdf_parse",
      );
    },
    saveFile(event) {
      this.pdf_parser_form.file = event.target.files[0];
    },
    openFileBlobAsPDF() {
      const file = this.pdf_parser_form.file;
      if (file) {
        const objectURL = URL.createObjectURL(file);
        window.open(objectURL, "_blank"); // Opens the file in a new tab

        // Optionally, revoke the Object URL when no longer needed to free up memory
        // URL.revokeObjectURL(objectURL);
      }
    },
    openDealNewTab(id) {
      let routeData = this.$router.resolve({
        name: "deal-update",
        params: { id: id },
      });
      window.open(routeData.href, "_blank");
    },
  },
  created() {
    this.form.date = moment(new Date()).format("MM/DD/YYYY");
    this.getBusinessTaxSettings();
    this.getBodyTypes();
    this.getAllUsers();
    this.getInventoryConfigurations();

    this.form.salesperson = this.app_metadata.id;
    if (
      this.app_metadata.role == "Owner" ||
      this.app_metadata.role == "Manager"
    ) {
      this.form.approved_by = this.app_metadata.id;
    }
  },
  mounted() {
    Inputmask({
      mask: "(999) 999-9999",
      autoUnmask: true,
      onBeforePaste: function(pastedValue, opts) {
        pastedValue = pastedValue.toLowerCase();
        return pastedValue.replace(/\D/g, "");
      },
    }).mask(document.getElementById("customer_phone"));

    Inputmask({
      mask: "(999) 999-9999",
      autoUnmask: true,
      onBeforePaste: function(pastedValue, opts) {
        pastedValue = pastedValue.toLowerCase();
        return pastedValue.replace(/\D/g, "");
      },
    }).mask(document.getElementById("customer_mobile"));

    const vm = this;
    Event.listen(
      "deal_import_worksheet_entity_save",
      ({ action, module, payload = null, module_index = null }) => {
        let current_module_attribute = vm.getModuleAttribute(module);

        if (action === "add") {
          vm.addAFee(current_module_attribute, payload);
        } else if (action === "update") {
          vm.updateAFee(current_module_attribute, payload, module_index);
        }

        vm.calculateWorkSheet();
      },
    );

    Event.listen(
      "load_available_data_for_a_vin_deal_inventory_form",
      (data) => {
        vm.getVehicleInformation("deal_inventory_form", data);
      },
    );

    Event.listen("load_available_data_for_a_vin_deal_trade_in_form", (data) => {
      vm.getVehicleInformation("deal_trade_in_form", data);
    });

    Event.listen("autopopulate_location_default", (data) => {
      if (data.hasOwnProperty("place")) {
        vm.$data.deal_customer_form.country = data.place.country;
        vm.$data.deal_customer_form.postal_code = data.place.postal_code;
        vm.$data.deal_customer_form.province = data.place.province;
        vm.$data.deal_customer_form.city = data.place.city;
        vm.$data.deal_customer_form.apt = "";

        if (data.place.street !== "") {
          vm.$data.deal_customer_form.street_address = `${data.place.street_number} ${data.place.street}`;
        } else {
          vm.$data.deal_customer_form.street_address = data.place.street;
        }
      }
    });
  },
  beforeDestroy() {
    Event.vue.$off("add_deal_import_worksheet_entity", null);
    Event.vue.$off("deal_import_worksheet_entity_save", null);
    Event.vue.$off("autopopulate_location_default", null);
    Event.vue.$off("load_available_data_for_a_vin_deal_inventory_form", null);
    Event.vue.$off("load_available_data_for_a_vin_deal_trade_in_form", null);
  },
};
</script>
<style lang="scss" scoped>
.card {
  .card {
    box-shadow: -8px 12px 18px 0 rgba(25, 42, 70, 0.13) !important;
  }
}
</style>
<style lang="scss">
#deal-import-panel {
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
    max-width: 100%;
  }
}
#deal-import-panel {
  .swal2-radio {
    flex-direction: column;
    align-items: start;

    label {
      text-align: left;
      position: relative;

      input[type="radio"] {
        position: absolute;
        left: -1.5em;
        top: 4px;
      }
    }
  }
}
#zd-toast-stack {
  position: fixed;
  right: 0px;
  z-index: 999;
}
</style>
