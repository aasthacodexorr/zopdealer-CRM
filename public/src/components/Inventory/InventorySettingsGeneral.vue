<template>
  <div
    role="tabpanel"
    class="tab-pane active"
    id="inventory-setting-file"
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
                      >Setting</span
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
        <div class="content-wrapper mt-0 pb-1 pt-2 border-bottom d-none">
          <div class="list-image-content">
            <div class="row">
              <div class="col-md-4">
                <label for="website_display_vin">Hide VIN On Website</label>
              </div>
              <div class="col-md-5 form-group">
                <div class="checkbox">
                  <input
                    type="checkbox"
                    class="checkbox-input"
                    id="website_display_vin"
                    v-model="form.website_display_vin"
                    :true-value="'0'"
                    :false-value="'1'"
                  />
                  <label for="website_display_vin"></label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label for="website_display_price">Hide Price On Website</label>
              </div>
              <div class="col-md-5 form-group">
                <div class="checkbox">
                  <input
                    type="checkbox"
                    class="checkbox-input"
                    id="website_display_price"
                    v-model="form.website_display_price"
                    :true-value="'0'"
                    :false-value="'1'"
                  />
                  <label for="website_display_price"></label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label>Number Of Vehicles To Display</label>
              </div>
              <div class="col-md-5 form-group">
                <fieldset>
                  <div class="input-group">
                    <select
                      class="custom-select form-control mr-1"
                      v-model="form.listing_default_number_of_vehicles"
                    >
                      <option value="10">10 per Page</option>
                      <option value="25">25 per Page</option>
                      <option value="50">50 per Page</option>
                      <option value="100">100 per Page</option>
                      <option value="200">200 per Page</option>
                    </select>
                  </div>
                </fieldset>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label>Number Of Featured Vehicles To Display</label>
              </div>
              <div class="col-md-5 form-group">
                <fieldset>
                  <div class="input-group">
                    <select
                      class="custom-select form-control mr-1"
                      id="listing_default_number_of_featured_vehicles"
                      name="listing_default_number_of_featured_vehicles"
                      v-model="form.listing_default_number_of_featured_vehicles"
                    >
                      <option value="10">10</option>
                      <option value="20">20</option>
                      <option value="30">30</option>
                      <option value="40">40</option>
                      <option value="50">50</option>
                      <option value="100">100</option>
                    </select>
                  </div>
                </fieldset>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label>Default Inventory Sort Order</label>
              </div>
              <div class="col-md-5 form-group">
                <fieldset>
                  <div class="input-group">
                    <select
                      class="custom-select form-control mr-1"
                      id="listing_default_sort_order"
                      name="listing_default_sort_order"
                      v-model="form.listing_default_sort_order"
                    >
                      <option value="Creation Date: High to Low">
                        Creation Date: High to Low
                      </option>
                      <option value="Creation Date: Low to High">
                        Creation Date: Low to High
                      </option>
                      <option value="Year: High to Low">
                        Year: High to Low
                      </option>
                      <option value="Year: Low to High">
                        Year: Low to High
                      </option>
                      <option value="Price: High to Low">
                        Price: High to Low
                      </option>
                      <option value="Price: Low to High">
                        Price: Low to High
                      </option>
                      <option value="Odometer: High to Low">
                        Odometer: High to Low
                      </option>
                      <option value="Odometer: Low to High">
                        Odometer: Low to High
                      </option>
                      <option value="Make: A-Z">Make: A-Z</option>
                      <option value="Make: Z-A">Make: Z-A</option>
                    </select>
                  </div>
                </fieldset>
              </div>
            </div>
          </div>
        </div>
        <div class="content-wrapper mt-0 pb-1 pt-2 border-bottom">
          <div class="list-image-content">
            <h6 class="col-xs-12 text-danger" style="font-size: 0.8em">
              NOTE : These settings do not affect existing inventories and will
              be applied only to new inventories.
            </h6>
            <div class="row">
              <div class="col-md-4">
                <label>Default Title</label>
              </div>
              <div class="col-md-8 form-group">
                <text-editor
                  name="description"
                  :elementId="'text-editor-settings-1'"
                  v-model="form.editor_default_title"
                ></text-editor>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label>Default description</label>
              </div>
              <div class="col-md-8 form-group">
                <text-editor
                  name="description"
                  :elementId="'inventory_settings_default_vehicle_description'"
                  v-model="form.editor_default_vehicle_description"
                ></text-editor>
              </div>
              <div class="col-md-12 text-right">
                <inventory-vehicle-description-re-writer-c-t-a />
                <inventory-vehicle-description-template-c-t-a />
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label>Default VIN Decode Feature Selection</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.default_vin_feature_value"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Default VIN Feature Value"
                    id="default_vin_feature_value"
                  />
                  <label
                    class="custom-control-label"
                    for="default_vin_feature_value"
                  >
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="content-wrapper mt-0 pb-1 pt-2 border-bottom">
          <div class="list-image-content">
            <div class="row">
              <div class="col-md-4">
                <label>Default Vehicle Tags</label>
              </div>
            </div>
            <div class="row contact-repeater">
              <div class="col-md-4">
                <button
                  class="btn btn-icon btn-primary"
                  @click="count_of_vehicle_tags += 1"
                  type="button"
                >
                  <i class="bx bx-plus"></i>Add New Tag
                </button>
              </div>
              <div class="col-md-5 form-group" data-repeater-list="contact">
                <!-- start Loop for vehicle tags -->
                <div
                  class="row"
                  v-for="n in this.count_of_vehicle_tags"
                  :key="n"
                >
                  <div class="col-md-10 col-12 form-group">
                    <input
                      type="text"
                      class="form-control"
                      v-model="form.vehicle_default_vehicle_tags[n - 1]"
                    />
                  </div>
                  <div class="col-md-2 col-12 form-group pl-0">
                    <button
                      class="btn btn-danger text-nowrap px-1"
                      @click="
                        form.vehicle_default_vehicle_tags.splice(n - 1, 1);
                        count_of_vehicle_tags -= 1;
                      "
                      type="button"
                    >
                      <i class="bx bx-x"></i>
                    </button>
                  </div>
                </div>
                <!-- end Loop for vehicle tags -->
              </div>
            </div>
          </div>
        </div>
        <div class="content-wrapper mt-0 pb-1 pt-2 border-bottom">
          <h6 class="col-xs-12 text-danger" style="font-size: 0.8em">
            NOTE : These settings do not affect existing inventories and will be
            applied only to new inventories.
          </h6>
          <div class="list-image-content">
            <div class="row">
              <div class="col-md-4">
                <label>Default Financing</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="custom-control custom-switch">
                  <input
                    type="checkbox"
                    class="custom-control-input"
                    v-model="form.vehicle_default_financing"
                    :true-value="'1'"
                    :false-value="'0'"
                    name="Financing-Special"
                    id="customSwitch11"
                  />
                  <label class="custom-control-label" for="customSwitch11">
                    <span class="switch-icon-left">ON</span>
                    <span class="switch-icon-right">OFF</span>
                  </label>
                </div>
              </div>
              <div class="col-md-4"></div>
              <div
                class="col-md-8 col-12"
                v-show="form.vehicle_default_financing == '1'"
              >
                <div class="row">
                  <div class="col-md-4">
                    <label class="text-muted">Downpayment</label>
                  </div>
                  <div class="col-md-6 form-group">
                    <fieldset>
                      <div class="input-group">
                        <input
                          id="downpayment"
                          min="0"
                          max="100"
                          type="number"
                          step="0.1"
                          @keyup="changeDownpayment()"
                          v-model="form.vehicle_default_downpayment"
                          class="form-control mr-1"
                          name="vehicle_default_downpayment"
                          placeholder
                        />
                        <span class="right-space-slider">%</span>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-4">
                    <label class="text-muted">Interest Rate</label>
                  </div>
                  <div class="col-md-6 form-group">
                    <fieldset>
                      <div class="input-group">
                        <input
                          id="vehicle_default_interest_rate"
                          min="0"
                          max="100"
                          type="number"
                          step="0.1"
                          @keyup="changeInterestRate()"
                          v-model="form.vehicle_default_interest_rate"
                          class="form-control mr-1"
                          name="vehicle_default_interest_rate"
                          placeholder
                        />
                        <span class="right-space-slider">%</span>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-4">
                    <label class="text-muted">Term</label>
                  </div>
                  <div class="col-md-6 form-group">
                    <fieldset>
                      <div class="input-group">
                        <input
                          min="0"
                          max="100"
                          type="number"
                          step="1"
                          @keyup="changeTerm()"
                          v-model="form.vehicle_default_term"
                          id="vehicle_default_term"
                          class="form-control mr-1"
                          name="vehicle_default_term"
                          placeholder
                        />
                        <span class="right-space-slider">Months</span>
                      </div>
                    </fieldset>
                  </div>
                  <div class="col-md-4">
                    <label class="text-muted">Payment Frequency</label>
                  </div>
                  <div class="col-md-5 form-group">
                    <select
                      class="custom-select form-control"
                      v-model="form.vehicle_default_payment_frequency"
                      id="vehicle_default_payment_frequency"
                      name="vehicle_default_payment_frequency"
                    >
                      <option value="Weekly">Weekly</option>
                      <option value="Bi-Weekly">Bi-Weekly</option>
                      <option value="Monthly">Monthly</option>
                    </select>
                  </div>
                  <div class="col-md-4"></div>
                  <div class="col-md-8 form-group">
                    <fieldset>
                      <div class="input-group hidden">
                        <label class="mr-1">$852 per /month</label>
                        <label class="text-muted">$19,250 Down Payment</label>
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="content-wrapper mt-0 pb-1 pt-2 border-bottom">
          <div class="list-image-content">
            <div class="row">
              <div class="col-md-4">
                <label>Stock Number Preferences</label>
              </div>
              <div class="col-md-8 form-group">
                <div class="row">
                  <div class="col-md-4">
                    <label class="text-muted">Stock Number Prefix</label>
                  </div>
                  <div class="col-md-6 form-group">
                    <fieldset>
                      <div class="input-group">
                        <input
                          v-model="form.inventory_stock_number_prefix"
                          class="form-control mr-1"
                          name="inventory_stock_number_prefix"
                          placeholder
                        />
                      </div>
                    </fieldset>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <label class="text-muted">Stock Number Length</label>
                  </div>
                  <div class="col-md-6 form-group">
                    <fieldset>
                      <div class="input-group">
                        <input
                          min="0"
                          max="14"
                          type="number"
                          v-model="form.inventory_stock_number_length"
                          class="form-control mr-1"
                          name="inventory_stock_number_length"
                          placeholder
                        />
                      </div>
                      <small class="text-danger"
                        >Length of stock number should be atleast 4 digits to
                        avoid any issues.</small
                      >
                    </fieldset>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <label class="text-muted">Stock Number Strategy</label>
                  </div>
                  <div class="col-md-6 form-group">
                    <fieldset>
                      <div class="input-group">
                        <select
                          class="custom-select form-control mr-1"
                          v-model="
                            form.inventory_stock_number_generator_strategy
                          "
                        >
                          <option value="">None</option>
                          <option value="1">Random</option>
                          <option value="2">Based on VIN</option>
                          <option value="3">Sequential</option>
                        </select>
                      </div>
                      <small
                        class="text-danger"
                        v-if="
                          form.inventory_stock_number_generator_strategy ==
                            '3' &&
                          form.inventory_stock_number_prefix.trim() === ''
                        "
                        >We Recommend Setting Stock Index Prefix as some
                        processes may not be able to differentiate between 0001
                        and 1</small
                      >
                    </fieldset>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <label class="text-muted">Stock Number Index</label>
                  </div>
                  <div class="col-md-6 form-group">
                    <fieldset>
                      <div class="input-group">
                        <input
                          min="0"
                          type="number"
                          v-model="form.current_inventory_stock_number_index"
                          class="form-control mr-1"
                          name="current_inventory_stock_number_index"
                          placeholder
                        />
                      </div>
                    </fieldset>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="content-wrapper mt-0 pb-1 pt-2 border-bottom d-none">
          <div class="list-image-content">
            <div class="row">
              <div class="col-md-4">
                <label>Default Attributes</label>
              </div>
              <div class="col-md-8">
                <div class="row">
                  <div class="col-md-6 col-12">
                    <p class>
                      <strong>VLP</strong>
                    </p>
                    <ul class="list-unstyled mb-0 mt-0">
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="listing_default_show_vin"
                              v-model="form.listing_default_show_vin"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="listing_default_show_vin">VIN</label>
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="listing_default_show_stock_no"
                              v-model="form.listing_default_show_stock_no"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="listing_default_show_stock_no"
                              >Stock Number</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="listing_default_show_body_type"
                              v-model="form.listing_default_show_body_type"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="listing_default_show_body_type"
                              >Body Style</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="listing_default_show_engine"
                              v-model="form.listing_default_show_engine"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="listing_default_show_engine"
                              >Engine (Displacement, Cylinders)</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="listing_default_show_exterior_color"
                              v-model="form.listing_default_show_exterior_color"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="listing_default_show_exterior_color"
                              >Exterior Color</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="listing_default_show_interior_color"
                              v-model="form.listing_default_show_interior_color"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="listing_default_show_interior_color"
                              >Interior Color</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="listing_default_show_transmission"
                              v-model="form.listing_default_show_transmission"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="listing_default_show_transmission"
                              >Transmission</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="listing_default_show_drivetrain"
                              v-model="form.listing_default_show_drivetrain"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="listing_default_show_drivetrain"
                              >Drivetrain</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="listing_default_show_odometer"
                              v-model="form.listing_default_show_odometer"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="listing_default_show_odometer"
                              >Odometer</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="listing_default_show_price"
                              v-model="form.listing_default_show_price"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="listing_default_show_price"
                              >Price</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="listing_default_show_special_price"
                              v-model="form.listing_default_show_special_price"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="listing_default_show_special_price"
                              >Special price</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="listing_default_show_financing"
                              v-model="form.listing_default_show_financing"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="listing_default_show_financing"
                              >Financing</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="listing_default_show_location"
                              v-model="form.listing_default_show_location"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="listing_default_show_location"
                              >Location</label
                            >
                          </div>
                        </fieldset>
                      </li>
                    </ul>
                  </div>
                  <div class="col-md-6 col-12">
                    <p class>
                      <strong>VDP</strong>
                    </p>
                    <ul class="list-unstyled mb-0 mt-0">
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="vehicle_default_show_vin"
                              v-model="form.vehicle_default_show_vin"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="vehicle_default_show_vin">VIN</label>
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="vehicle_default_show_stock_no"
                              v-model="form.vehicle_default_show_stock_no"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="vehicle_default_show_stock_no"
                              >Stock Number</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="vehicle_default_show_body_type"
                              v-model="form.vehicle_default_show_body_type"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="vehicle_default_show_body_type"
                              >Body Style</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="vehicle_default_show_engine"
                              v-model="form.vehicle_default_show_engine"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="vehicle_default_show_engine"
                              >Engine (Displacement, Cylinders)</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="vehicle_default_show_exterior_color"
                              v-model="form.vehicle_default_show_exterior_color"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="vehicle_default_show_exterior_color"
                              >Exterior Color</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="vehicle_default_show_interior_color"
                              v-model="form.vehicle_default_show_interior_color"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="vehicle_default_show_interior_color"
                              >Interior Color</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="vehicle_default_show_transmission"
                              v-model="form.vehicle_default_show_transmission"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="vehicle_default_show_transmission"
                              >Transmission</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="vehicle_default_show_drivetrain"
                              v-model="form.vehicle_default_show_drivetrain"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="vehicle_default_show_drivetrain"
                              >Drivetrain</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="vehicle_default_show_odometer"
                              v-model="form.vehicle_default_show_odometer"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="vehicle_default_show_odometer"
                              >Odometer</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="vehicle_default_show_price"
                              v-model="form.vehicle_default_show_price"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="vehicle_default_show_price"
                              >Price</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="vehicle_default_show_special_price"
                              v-model="form.vehicle_default_show_special_price"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="vehicle_default_show_special_price"
                              >Special Price</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="vehicle_default_show_financing"
                              v-model="form.vehicle_default_show_financing"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="vehicle_default_show_financing"
                              >Financing</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="vehicle_default_show_door"
                              v-model="form.vehicle_default_show_door"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="vehicle_default_show_door">Doors</label>
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="vehicle_default_show_passengers"
                              v-model="form.vehicle_default_show_passengers"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="vehicle_default_show_passengers"
                              >Passengers</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="vehicle_default_show_fuel_type"
                              v-model="form.vehicle_default_show_fuel_type"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="vehicle_default_show_fuel_type"
                              >Fuel Type</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mr-2 mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="vehicle_default_show_fuel_consumption"
                              v-model="
                                form.vehicle_default_show_fuel_consumption
                              "
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="vehicle_default_show_fuel_consumption"
                              >Fuel consumption (City , HWY)</label
                            >
                          </div>
                        </fieldset>
                      </li>
                      <li class="d-block mb-1">
                        <fieldset>
                          <div class="checkbox">
                            <input
                              type="checkbox"
                              class="checkbox-input"
                              id="vehicle_default_show_location"
                              v-model="form.vehicle_default_show_location"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label for="vehicle_default_show_location"
                              >Location</label
                            >
                          </div>
                        </fieldset>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="content-wrapper mt-0 pb-1 pt-2 d-none">
          <div class="list-image-content">
            <div class="row">
              <div class="col-md-4">
                <label>Default Disclosure</label>
              </div>
              <div class="col-md-8 form-group">
                <fieldset class="form-group">
                  <textarea
                    class="form-control"
                    id="default-disclosure"
                    v-model="form.website_default_disclosure"
                    rows="4"
                    placeholder="Message"
                    spellcheck="false"
                  ></textarea>
                </fieldset>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <inventory-vehicle-description-re-writer
      :vehicle_description="form.editor_default_vehicle_description"
    ></inventory-vehicle-description-re-writer>
    <inventory-vehicle-description-template />
  </div>
</template>
<script>
import TextEditor from "../WebComponents/TextEditor";
import Toast from "../../library/Toast";
import InventoryVehicleDescriptionTemplateCTA from "./InventoryVehicleDescriptionTemplateCTA.vue";
import InventoryVehicleDescriptionTemplate from "./InventoryVehicleDescriptionTemplate.vue";
import InventoryVehicleDescriptionReWriter from "./InventoryVehicleDescriptionReWriter.vue";
import InventoryVehicleDescriptionReWriterCTA from "./InventoryVehicleDescriptionReWriterCTA.vue";

export default {
  name: "InventorySettingsGeneral",
  data() {
    return {
      count_of_vehicle_tags: 0,
      submit: true,
      form: {
        website_display_vin: "1",
        website_display_price: "1",

        listing_default_number_of_vehicles: 10,
        listing_default_number_of_featured_vehicles: 10,
        listing_default_sort_order: "Creation Date: High to Low",

        editor_default_title: "",
        editor_default_vehicle_description: "",

        vehicle_default_financing: 0,
        vehicle_default_downpayment: 0,
        vehicle_default_interest_rate: 0,
        vehicle_default_term: 0,
        vehicle_default_payment_frequency: "Monthly",
        vehicle_default_vehicle_tags: [],

        listing_default_show_vin: "1",
        listing_default_show_stock_no: "1",
        listing_default_show_body_type: "1",
        listing_default_show_engine: "1",
        listing_default_show_exterior_color: "1",
        listing_default_show_interior_color: "1",
        listing_default_show_transmission: "1",
        listing_default_show_drivetrain: "1",
        listing_default_show_odometer: "1",
        listing_default_show_price: "1",
        listing_default_show_special_price: "1",
        listing_default_show_financing: "1",
        listing_default_show_location: "1",

        vehicle_default_show_vin: "1",
        vehicle_default_show_stock_no: "1",
        vehicle_default_show_body_type: "1",
        vehicle_default_show_engine: "1",
        vehicle_default_show_exterior_color: "1",
        vehicle_default_show_interior_color: "1",
        vehicle_default_show_transmission: "1",
        vehicle_default_show_drivetrain: "1",
        vehicle_default_show_odometer: "1",
        vehicle_default_show_price: "1",
        vehicle_default_show_special_price: "1",
        vehicle_default_show_financing: "1",
        vehicle_default_show_door: "1",
        vehicle_default_show_passengers: "1",
        vehicle_default_show_fuel_type: "1",
        vehicle_default_show_fuel_consumption: "1",
        vehicle_default_show_location: "1",
        website_default_disclosure: "",
        default_vin_feature_value: "1",

        current_inventory_stock_number_index: "1",
        inventory_stock_number_generator_strategy: "",
        inventory_stock_number_length: "6",
        inventory_stock_number_prefix: "",
      },
    };
  },
  components: {
    TextEditor,
    InventoryVehicleDescriptionTemplateCTA,
    InventoryVehicleDescriptionTemplate,
    InventoryVehicleDescriptionReWriter,
    InventoryVehicleDescriptionReWriterCTA,
  },
  methods: {
    changeDownpayment() {
      this.form.vehicle_default_downpayment =
        this.form.vehicle_default_downpayment <= 100 &&
        this.form.vehicle_default_downpayment >= 0
          ? this.form.vehicle_default_downpayment
          : 0;
    },
    changeInterestRate() {
      this.form.vehicle_default_interest_rate =
        this.form.vehicle_default_interest_rate <= 100 &&
        this.form.vehicle_default_interest_rate >= 0
          ? this.form.vehicle_default_interest_rate
          : 0;
    },
    changeTerm() {
      this.form.term =
        this.form.vehicle_default_term <= 96 &&
        this.form.vehicle_default_term >= 0
          ? this.form.vehicle_default_term
          : 0;
    },

    getInventoryConfigurations() {
      let request_url =
        window.location.origin + "/api/business/configurations/inventory";
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
            if (
              element.key == "vehicle_default_vehicle_tags" &&
              element.value != ""
            ) {
              this.form.hasOwnProperty(element.key)
                ? (this.form[element.key] = JSON.parse(element.value))
                : null;
            } else {
              this.form.hasOwnProperty(element.key)
                ? (this.form[element.key] = element.value)
                : null;
            }
          }
        }
        if (this.form.vehicle_default_vehicle_tags == "") {
          this.form.vehicle_default_vehicle_tags = [];
        } else {
          this.count_of_vehicle_tags =
            this.form.vehicle_default_vehicle_tags.length;
        }
      });
    },
    saveConfiguration() {
      const vm = this;
      this.submit = false;
      let formData = new FormData();
      for (const key in this.form) {
        if (
          this.form.hasOwnProperty(key) &&
          key != "vehicle_default_vehicle_tags"
        ) {
          formData.append(key, this.form[key]);
        }
      }
      formData.append(
        "vehicle_default_vehicle_tags",
        JSON.stringify(this.form["vehicle_default_vehicle_tags"])
      );
      let request_url = window.location.origin + "/api/business/configurations";

      let message = "Saving Inventory Settings ...";
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
          let success_message = "Finished Saving Configurations";
          toast.success(success_message);
          toast.close();

          setTimeout(() => {
            vm.$data.submit = true;
          }, 500);
        })
        .catch((error) => {
          let error_message = "There were some errors in saving settings.";
          toast.fail(error_message);
          toast.close();

          setTimeout(() => {
            vm.$data.submit = true;
          }, 500);
        });
    },
  },
  created() {
    this.getInventoryConfigurations();
  },
  mounted() {
    const vm = this;
    Event.listen("update_vehicle_description", (vehicle_description) => {
      vm.$data.form.editor_default_vehicle_description = vehicle_description;
    });
  },
};
</script>
<style lang="scss">
#text-editor-settings-1 .ql-formats:not(:last-child) {
  display: none;
}

#text-editor-settings-1 .ql-picker-options,
#inventory_settings_default_vehicle_description .ql-picker-options {
  max-height: 350px;
}
</style>