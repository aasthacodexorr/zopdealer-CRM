<template>
  <div
    class="tab-pane active"
    id="general-block-list"
    aria-labelledby="information-tab"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="general">
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
                              <label style="color: red">Vin Number *</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <input
                                    type="text"
                                    id="Vin-Number"
                                    :class="{
                                      'is-invalid': errors.hasOwnProperty(
                                        'vin',
                                      ),
                                    }"
                                    @keyup="delete errors.vin"
                                    :title="errors.vin"
                                    class="form-control mr-1"
                                    name=""
                                    v-model="form.vin"
                                    @input="form.vin = form.vin.toUpperCase()"
                                    placeholder="xxxxxxxxxxxxxxxxx"
                                  />
                                  <div
                                    class="input-group-append"
                                    id="button-addon2"
                                  >
                                    <button
                                      class="btn btn-primary"
                                      @click="getAvailableTrimsForVIN()"
                                      type="button"
                                    >
                                      Decode
                                    </button>
                                  </div>
                                </div>
                              </fieldset>
                            </div>
                            <div v-show="!updateView" class="col-md-4"></div>
                            <div
                              v-show="!updateView"
                              class="col-xs-12 col-md-7 mb-50"
                            >
                              <router-link
                                tag="a"
                                title="Third Party Bulk Import using CSV"
                                :to="{ name: 'third-party-inventory' }"
                                exact
                              >
                                <i
                                  class="bx bx-link-external mr-50"
                                  style="vertical-align: middle"
                                ></i>
                                Bulk Import from Third-Party using CSV
                              </router-link>
                              <br />
                              <router-link
                                tag="a"
                                title="Quick Inventory Import"
                                :to="{ name: 'inventory-import' }"
                                exact
                              >
                                <i
                                  class="bx bx-link-external mr-50"
                                  style="vertical-align: middle"
                                ></i>
                                Import multiple inventories manually or from PDF
                              </router-link>
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-7 form-group">
                              <div class="checkbox">
                                <input
                                  type="checkbox"
                                  class="checkbox-input"
                                  id="checkboxvin"
                                  v-model="form.vindisplay"
                                  :true-value="'1'"
                                  :false-value="'0'"
                                />
                                <label for="checkboxvin"
                                  >Display VIN on Website</label
                                >
                              </div>
                            </div>
                            <div class="col-md-4">
                              <label style="color: red">Stock #</label>
                            </div>
                            <div class="col-md-7 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <input
                                    type="text"
                                    aria-label="stock name"
                                    v-model="form.stock_no"
                                    class="form-control mr-1"
                                    placeholder="Stock Number"
                                    :class="{
                                      'is-invalid': errors.hasOwnProperty(
                                        'stock_no',
                                      ),
                                    }"
                                    @keydown="delete errors.stock_no"
                                    :title="
                                      errors.hasOwnProperty('stock_no')
                                        ? errors.stock_no
                                        : 'If not provided, it will be autogenerated.'
                                    "
                                  />
                                  <fieldset class="has-icon-left">
                                    <date-picker
                                      v-model="form.stock_date"
                                      :format="'MM/DD/YYYY'"
                                      :value-type="'MM/DD/YYYY'"
                                      :clearable="false"
                                    ></date-picker>
                                  </fieldset>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4">
                              <label>Status</label>
                            </div>
                            <div class="col-md-7 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <select
                                    class="custom-select form-control mr-1"
                                    id="status"
                                    v-model="form.status"
                                    name="status"
                                  >
                                    <option value="" selected disabled>
                                      Select Status
                                    </option>
                                    <option
                                      :value="status"
                                      v-for="status in getStatuses"
                                      :key="status"
                                    >
                                      {{ status }}
                                    </option>
                                  </select>
                                  <select
                                    class="custom-select form-control mr-1"
                                    id="sub_status"
                                    v-model="form.sub_status"
                                    name="sub_status"
                                  >
                                    <option value="" selected disabled>
                                      Select Sub Status
                                    </option>
                                    <option
                                      :value="sub_status"
                                      v-for="sub_status in getSubStatues"
                                      :key="sub_status"
                                    >
                                      {{ sub_status }}
                                    </option>
                                  </select>
                                  <div class="checkbox" style="margin-top: 6px">
                                    <input
                                      type="checkbox"
                                      class="checkbox-input"
                                      id="checkbox1"
                                      v-model="form.feature_listing"
                                      :true-value="'1'"
                                      :false-value="'0'"
                                    />
                                    <label for="checkbox1"
                                      >Add to Feature List</label
                                    >
                                  </div>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4">
                              <label>Vehicle Type</label>
                            </div>
                            <div class="col-md-5 form-group">
                              <select
                                class="custom-select form-control"
                                v-model="form.vehicle_type"
                                id="vehicle_type"
                                name="vehicle_type"
                              >
                                <option value="" selected disabled>
                                  Select Vehicle Type
                                </option>
                                <option
                                  :value="vehicle_type"
                                  v-for="vehicle_type in getVehicleTypes"
                                  :key="vehicle_type"
                                >
                                  {{ vehicle_type }}
                                </option>
                              </select>
                            </div>
                            <div class="col-md-4">
                              <label>Visibility (Show On Website)</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <div class="custom-control custom-switch">
                                <input
                                  type="checkbox"
                                  class="custom-control-input"
                                  id="customSwitch0"
                                  name="Visibility"
                                  v-model="form.visibility"
                                  :true-value="'1'"
                                  :false-value="'0'"
                                  checked
                                />
                                <label
                                  class="custom-control-label"
                                  for="customSwitch0"
                                >
                                  <span class="switch-icon-left"> ON</span>
                                  <span class="switch-icon-right">OFF</span>
                                </label>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <label>Vehicle Tags</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <v-select
                                v-model="form.vehicle_tags"
                                append-to-body
                                placeholder="Vehicle Tags"
                                taggable
                                pushTags
                                clearable
                                multiple
                                :options="vehicle_tags"
                              ></v-select>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12 col-12">
                        <div class="m-width-s">
                          <div class="row">
                            <div class="col-md-4">
                              <label>Title</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <text-editor
                                name="description"
                                :elementId="'text-editor-settings-1'"
                                v-model="form.title"
                              >
                              </text-editor>
                            </div>
                            <div class="col-md-4">
                              <label style="color: red">Vehicle *</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <select
                                    class="custom-select form-control mr-1"
                                    :class="{
                                      'is-invalid': errors.hasOwnProperty(
                                        'year',
                                      ),
                                    }"
                                    @change="delete errors.year"
                                    :title="errors.year"
                                    v-model="form.year"
                                    id="year"
                                    name="year"
                                  >
                                    <option value="" selected disabled>
                                      Year
                                    </option>
                                    <option
                                      :value="year"
                                      v-for="year in years"
                                      :key="year"
                                      v-text="year"
                                    ></option>
                                  </select>
                                  <select
                                    class="custom-select form-control mr-1"
                                    :class="{
                                      'is-invalid': errors.hasOwnProperty(
                                        'make',
                                      ),
                                    }"
                                    @change="delete errors.make"
                                    :title="errors.make"
                                    v-model="form.make"
                                    id="make"
                                    name="make"
                                    style="width: 17%"
                                  >
                                    <option value="" selected disabled>
                                      Make
                                    </option>
                                    <option
                                      :value="make"
                                      v-for="make in makes"
                                      :key="make"
                                      v-text="make"
                                    ></option>
                                  </select>
                                  <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                      'is-invalid': errors.hasOwnProperty(
                                        'model',
                                      ),
                                    }"
                                    @keyup="delete errors.model"
                                    :title="errors.model"
                                    v-model="form.model"
                                    id="model"
                                    name="model"
                                    placeholder="Model"
                                    style="width: 17%"
                                  />
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4">
                              <label>Trim And Body Type</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <input
                                    list="trims"
                                    type="text"
                                    id="trim"
                                    v-model="form.trim"
                                    class="form-control mr-1"
                                    name="trim"
                                    placeholder="Trim"
                                  />
                                  <datalist id="trims">
                                    <option
                                      v-for="trim in trims"
                                      :value="trim"
                                      :key="trim.key"
                                    ></option>
                                  </datalist>
                                  <select
                                    class="custom-select form-control mr-1"
                                    v-model="form.body_type"
                                    id="body_type"
                                    name="body_type"
                                  >
                                    <option value="" selected disabled>
                                      Body Type
                                    </option>
                                    <option
                                      :value="body_type"
                                      v-for="body_type in body_types"
                                      :key="body_type"
                                      v-text="body_type"
                                    ></option>
                                  </select>
                                  <select
                                    class="custom-select form-control"
                                    v-model="form.door"
                                    id="door"
                                    name="door"
                                  >
                                    <option value="" selected disabled>
                                      Door
                                    </option>
                                    <option
                                      :value="door"
                                      v-for="door in doors"
                                      :key="door"
                                      v-text="door"
                                    ></option>
                                  </select>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4">
                              <label>Engine</label>
                            </div>
                            <div class="col-md-6 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <input
                                    list="litres_list"
                                    type="text"
                                    id="litres"
                                    class="form-control"
                                    v-model="form.litres"
                                    name="litres"
                                    placeholder="Displacement (L)"
                                  />
                                  <div class="input-group-append">
                                    <span
                                      class="input-group-text"
                                      id="basic-addon2"
                                      >L</span
                                    >
                                  </div>
                                  <datalist id="litres_list">
                                    <option
                                      v-for="litre in litres"
                                      :value="litre"
                                      :key="litre.key"
                                    ></option>
                                  </datalist>
                                  <select
                                    class="custom-select ml-2 form-control"
                                    id="engine"
                                    name="engine"
                                    v-model="form.engine"
                                  >
                                    <option value="" disabled>
                                      - Select Cylinders -
                                    </option>
                                    <option
                                      :value="cylinder"
                                      v-for="cylinder in cylinders"
                                      :key="cylinder"
                                      v-text="cylinder"
                                    ></option>
                                  </select>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4">
                              <label>Transmission & Drive </label>
                            </div>
                            <div class="col-md-8 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <select
                                    class="custom-select form-control mr-1"
                                    id="transmission"
                                    v-model="form.transmission"
                                    name="transmission"
                                  >
                                    <option value="" selected disabled>
                                      Transmission
                                    </option>
                                    <option
                                      :value="transmission"
                                      v-for="transmission in transmissions"
                                      :key="transmission"
                                      v-text="transmission"
                                    ></option>
                                  </select>
                                  <!-- <input type="text" id="speed" class="form-control mr-1" name="stock" placeholder="Transmission Type">     -->
                                  <select
                                    class="custom-select form-control"
                                    id="drivetrain"
                                    v-model="form.drivetrain"
                                    name="drivetrain"
                                  >
                                    <option value="" selected disabled>
                                      - Select DriveTrain -
                                    </option>
                                    <option
                                      :value="drivetrain"
                                      v-for="drivetrain in drivetrains"
                                      :key="drivetrain"
                                      v-text="drivetrain"
                                    ></option>
                                  </select>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4">
                              <label>Passengers</label>
                            </div>
                            <div class="col-md-6 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <select
                                    class="custom-select form-control"
                                    v-model="form.passengers"
                                    id="passengers"
                                    name="passengers"
                                  >
                                    <option value="" selected disabled>
                                      Select
                                    </option>
                                    <option
                                      :value="passenger"
                                      v-for="passenger in passengers"
                                      :key="passenger"
                                      v-text="passenger"
                                    ></option>
                                  </select>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4">
                              <label>Odometer</label>
                            </div>
                            <div class="col-md-6 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <input
                                    type="text"
                                    :class="{
                                      'is-invalid': errors.hasOwnProperty(
                                        'odometer',
                                      ),
                                    }"
                                    @keydown="delete errors.odometer"
                                    :title="errors.odometer"
                                    aria-label="Odometer"
                                    id="odometer"
                                    class="form-control mr-1"
                                    v-model="form.odometer"
                                    placeholder=""
                                    style="width: 60%"
                                  />
                                  <select
                                    class="custom-select form-control"
                                    id="km_miles"
                                    name="km_miles"
                                    v-model="form.km_miles"
                                  >
                                    <option value="" selected disabled>
                                      Select
                                    </option>
                                    <option value="KM">KM</option>
                                    <option value="Miles">Miles</option>
                                  </select>
                                </div>
                              </fieldset>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12 col-12">
                        <div class="m-width-s">
                          <div class="row">
                            <div class="col-md-4">
                              <label>Fuel Type</label>
                            </div>
                            <div class="col-md-5 form-group">
                              <select
                                class="custom-select form-control"
                                v-model="form.fuel_type"
                                id="fuel_type"
                                name="fuel_type"
                              >
                                <option value="" selected disabled>
                                  Select Fuel Type
                                </option>
                                <option
                                  :value="fuel_type"
                                  v-for="fuel_type in fuel_types"
                                  :key="fuel_type"
                                  v-text="fuel_type"
                                ></option>
                              </select>
                            </div>
                            <div class="col-md-4">
                              <label>Fuel Consumption</label>
                            </div>
                            <div class="col-md-6 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <input
                                    list="fuel_citys"
                                    type="text"
                                    aria-label="City"
                                    v-model="form.fuel_city"
                                    class="form-control mr-1"
                                    placeholder="City"
                                  />
                                  <datalist id="fuel_citys">
                                    <option
                                      v-for="fuel_city in fuel_citys"
                                      :value="fuel_city"
                                      :key="fuel_city.key"
                                    ></option>
                                  </datalist>
                                  <input
                                    list="fuel_hwys"
                                    type="text"
                                    aria-label="Hwy"
                                    v-model="form.fuel_hwy"
                                    class="form-control"
                                    placeholder="Hwy"
                                  />
                                  <datalist id="fuel_hwys">
                                    <option
                                      v-for="fuel_hwy in fuel_hwys"
                                      :value="fuel_hwy"
                                      :key="fuel_hwy.key"
                                    ></option>
                                  </datalist>
                                </div>
                              </fieldset>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12 col-12">
                        <div class="m-width-s">
                          <div class="row">
                            <div class="col-md-4">
                              <label>Exterior Color</label>
                            </div>
                            <div class="col-md-5 form-group">
                              <div class="none-color active-color">
                                <div
                                  id="Black"
                                  @click="form.exterior_color = 'Black'"
                                  :class="{
                                    'active-color-c':
                                      form.exterior_color == 'Black',
                                  }"
                                  class="r-bg-color-1 bg-active-box"
                                ></div>
                                <div
                                  id="Grey"
                                  @click="form.exterior_color = 'Grey'"
                                  :class="{
                                    'active-color-c':
                                      form.exterior_color == 'Grey',
                                  }"
                                  class="r-bg-color-2 bg-active-box"
                                ></div>
                                <div
                                  id="Silver"
                                  @click="form.exterior_color = 'Silver'"
                                  :class="{
                                    'active-color-c':
                                      form.exterior_color == 'Silver',
                                  }"
                                  class="r-bg-color-3 bg-active-box"
                                ></div>
                                <div
                                  id="White"
                                  @click="form.exterior_color = 'White'"
                                  :class="{
                                    'active-color-c':
                                      form.exterior_color == 'White',
                                  }"
                                  class="r-bg-color-4 bg-active-box"
                                ></div>
                                <div
                                  id="Cream"
                                  @click="form.exterior_color = 'Cream'"
                                  :class="{
                                    'active-color-c':
                                      form.exterior_color == 'Cream',
                                  }"
                                  class="r-bg-color-5 bg-active-box"
                                ></div>
                                <div
                                  id="Tan"
                                  @click="form.exterior_color = 'Tan'"
                                  :class="{
                                    'active-color-c':
                                      form.exterior_color == 'Tan',
                                  }"
                                  class="r-bg-color-6 bg-active-box"
                                ></div>
                                <div
                                  id="Brown"
                                  @click="form.exterior_color = 'Brown'"
                                  :class="{
                                    'active-color-c':
                                      form.exterior_color == 'Brown',
                                  }"
                                  class="r-bg-color-7 bg-active-box"
                                ></div>
                                <div
                                  id="Maroon"
                                  @click="form.exterior_color = 'Maroon'"
                                  :class="{
                                    'active-color-c':
                                      form.exterior_color == 'Maroon',
                                  }"
                                  class="r-bg-color-8 bg-active-box"
                                ></div>
                                <div
                                  id="Red"
                                  @click="form.exterior_color = 'Red'"
                                  :class="{
                                    'active-color-c':
                                      form.exterior_color == 'Red',
                                  }"
                                  class="r-bg-color-9 bg-active-box"
                                ></div>
                                <div
                                  id="Orange"
                                  @click="form.exterior_color = 'Orange'"
                                  :class="{
                                    'active-color-c':
                                      form.exterior_color == 'Orange',
                                  }"
                                  class="r-bg-color-10 bg-active-box"
                                ></div>
                                <div
                                  id="Gold"
                                  @click="form.exterior_color = 'Gold'"
                                  :class="{
                                    'active-color-c':
                                      form.exterior_color == 'Gold',
                                  }"
                                  class="r-bg-color-11 bg-active-box"
                                ></div>
                                <div
                                  id="Yellow"
                                  @click="form.exterior_color = 'Yellow'"
                                  :class="{
                                    'active-color-c':
                                      form.exterior_color == 'Yellow',
                                  }"
                                  class="r-bg-color-12 bg-active-box"
                                ></div>
                                <div
                                  id="Green"
                                  @click="form.exterior_color = 'Green'"
                                  :class="{
                                    'active-color-c':
                                      form.exterior_color == 'Green',
                                  }"
                                  class="r-bg-color-13 bg-active-box"
                                ></div>
                                <div
                                  id="Teal"
                                  @click="form.exterior_color = 'Teal'"
                                  :class="{
                                    'active-color-c':
                                      form.exterior_color == 'Teal',
                                  }"
                                  class="r-bg-color-14 bg-active-box"
                                ></div>
                                <div
                                  id="Blue"
                                  @click="form.exterior_color = 'Blue'"
                                  :class="{
                                    'active-color-c':
                                      form.exterior_color == 'Blue',
                                  }"
                                  class="r-bg-color-15 bg-active-box"
                                ></div>
                                <div
                                  id="Purple"
                                  @click="form.exterior_color = 'Purple'"
                                  :class="{
                                    'active-color-c':
                                      form.exterior_color == 'Purple',
                                  }"
                                  class="r-bg-color-16 bg-active-box"
                                ></div>
                              </div>
                            </div>
                            <div class="col-3">
                              <input
                                type="text"
                                v-model="form.exterior_color"
                                class="form-control"
                              />
                            </div>
                            <div class="col-md-4">
                              <label>Interior Color</label>
                            </div>
                            <div class="col-md-5 form-group">
                              <div class="none-color active-color">
                                <div
                                  id="Black"
                                  @click="form.interior_color = 'Black'"
                                  :class="{
                                    'active-color-s':
                                      form.interior_color == 'Black',
                                  }"
                                  class="i-bg-color-1 bg-active-box1"
                                ></div>
                                <div
                                  id="Grey"
                                  @click="form.interior_color = 'Grey'"
                                  :class="{
                                    'active-color-s':
                                      form.interior_color == 'Grey',
                                  }"
                                  class="i-bg-color-2 bg-active-box1"
                                ></div>
                                <div
                                  id="White"
                                  @click="form.interior_color = 'White'"
                                  :class="{
                                    'active-color-s':
                                      form.interior_color == 'White',
                                  }"
                                  class="i-bg-color-3 bg-active-box1"
                                ></div>
                                <div
                                  id="Cream"
                                  @click="form.interior_color = 'Cream'"
                                  :class="{
                                    'active-color-s':
                                      form.interior_color == 'Cream',
                                  }"
                                  class="i-bg-color-4 bg-active-box1"
                                ></div>
                                <div
                                  id="Tan"
                                  @click="form.interior_color = 'Tan'"
                                  :class="{
                                    'active-color-s':
                                      form.interior_color == 'Tan',
                                  }"
                                  class="i-bg-color-5 bg-active-box1"
                                ></div>
                                <div
                                  id="Beige"
                                  @click="form.interior_color = 'Beige'"
                                  :class="{
                                    'active-color-s':
                                      form.interior_color == 'Beige',
                                  }"
                                  class="i-bg-color-6 bg-active-box1"
                                ></div>
                                <div
                                  id="Brown"
                                  @click="form.interior_color = 'Brown'"
                                  :class="{
                                    'active-color-s':
                                      form.interior_color == 'Brown',
                                  }"
                                  class="i-bg-color-7 bg-active-box1"
                                ></div>
                                <div
                                  id="Red"
                                  @click="form.interior_color = 'Red'"
                                  :class="{
                                    'active-color-s':
                                      form.interior_color == 'Red',
                                  }"
                                  class="i-bg-color-8 bg-active-box1"
                                ></div>
                                <div
                                  id="Orange"
                                  @click="form.interior_color = 'Orange'"
                                  :class="{
                                    'active-color-s':
                                      form.interior_color == 'Orange',
                                  }"
                                  class="i-bg-color-9 bg-active-box1"
                                ></div>
                                <div
                                  id="Yellow"
                                  @click="form.interior_color = 'Yellow'"
                                  :class="{
                                    'active-color-s':
                                      form.interior_color == 'Yellow',
                                  }"
                                  class="i-bg-color-10 bg-active-box1"
                                ></div>
                                <div
                                  id="Blue"
                                  @click="form.interior_color = 'Blue'"
                                  :class="{
                                    'active-color-s':
                                      form.interior_color == 'Blue',
                                  }"
                                  class="i-bg-color-11 bg-active-box1"
                                ></div>
                              </div>
                            </div>
                            <div class="col-3">
                              <input
                                type="text"
                                v-model="form.interior_color"
                                class="form-control"
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12 col-12">
                        <div class="m-width-s">
                          <div class="row">
                            <div class="col-md-4">
                              <label>Price</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <div class="input-group-append">
                                    <span
                                      class="input-group-text s-color-box"
                                      style="color: #000"
                                      >$</span
                                    >
                                  </div>
                                  <input
                                    type="text"
                                    v-model="form.price"
                                    :class="{
                                      'is-invalid': errors.hasOwnProperty(
                                        'price',
                                      ),
                                    }"
                                    @keydown="delete errors.price"
                                    :title="errors.price"
                                    class="form-control"
                                    aria-label="Dollar amount (with dot and two decimal places)"
                                    style="
                                      border-top-left-radius: 0px;
                                      border-bottom-left-radius: 0px;
                                    "
                                  />
                                  <div
                                    class="checkbox pr-1"
                                    style="margin-top: 5px; margin-left: 20px"
                                  >
                                    <input
                                      type="checkbox"
                                      v-model="form.hide_price"
                                      :true-value="'1'"
                                      :false-value="'0'"
                                      class="checkbox-input"
                                      id="checkbox2"
                                    />
                                    <label for="checkbox2">Hide Price</label>
                                  </div>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4">
                              <label>Special Price</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <div class="custom-control custom-switch">
                                <input
                                  type="checkbox"
                                  v-model="form.special_price_on"
                                  :true-value="'1'"
                                  :false-value="'0'"
                                  class="custom-control-input"
                                  id="customSwitch13"
                                  name="Special Price"
                                />
                                <label
                                  class="custom-control-label"
                                  for="customSwitch13"
                                  id="on-price-ss"
                                >
                                  <span class="switch-icon-left">ON</span>
                                  <span class="switch-icon-right">OFF</span>
                                </label>
                              </div>
                            </div>
                            <div
                              class="col-12"
                              v-show="form.special_price_on == '1'"
                            >
                              <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4 form-group">
                                  <fieldset>
                                    <div class="input-group">
                                      <div class="input-group-append">
                                        <span
                                          class="input-group-text s-color-box"
                                          style="color: #000"
                                          >$</span
                                        >
                                      </div>
                                      <input
                                        type="text"
                                        :class="{
                                          'is-invalid': errors.hasOwnProperty(
                                            'special_price',
                                          ),
                                        }"
                                        @keydown="delete errors.special_price"
                                        :title="errors.special_price"
                                        v-model="form.special_price"
                                        aria-label="Special Price"
                                        class="form-control"
                                        placeholder=""
                                      />
                                      <select
                                        class="custom-select form-control d-none"
                                        style="height: 37px"
                                        v-model="form.special_price_type"
                                        id="dateselector"
                                        name=""
                                      >
                                        <option value="Forever">Forever</option>
                                        <option value="Limited-Time">
                                          Limited Time
                                        </option>
                                      </select>
                                      <div
                                        class="limit-time-block"
                                        style="display: block"
                                        v-show="
                                          form.special_price_type ==
                                            'Limited-Time'
                                        "
                                        id="Limited-Time"
                                      >
                                        <div class="input-group d-none">
                                          <div class="fixed-t">
                                            <fieldset class="has-icon-left">
                                              <date-picker
                                                v-model="
                                                  form.special_price_date
                                                "
                                                :format="'MM/DD/YYYY'"
                                                :value-type="'MM/DD/YYYY'"
                                              ></date-picker>
                                              <div
                                                class="form-control-position"
                                              >
                                                <i
                                                  class="bx bx-calendar font-medium-3"
                                                ></i>
                                              </div>
                                            </fieldset>
                                          </div>
                                          <input
                                            type="time"
                                            v-model="form.special_price_time"
                                            aria-label="Speical Price Time"
                                            class="form-control"
                                            value="12:00"
                                            placeholder="Time"
                                          />
                                        </div>
                                      </div>
                                    </div>
                                  </fieldset>
                                </div>
                                <div class="col-md-4"></div>
                                <div class="col-md-8 form-group d-none">
                                  <div class="custom-control-inline">
                                    <!-- <div class="checkbox ">
                                                                        <input type="checkbox" class="checkbox-input" id="checkbox6">
                                                                        <label for="checkbox6"> Active</label>
                                                                      </div> -->
                                    <div
                                      class="checkbox"
                                      style="margin-left: 20px"
                                    >
                                      <input
                                        type="checkbox"
                                        v-model="form.show_stripe"
                                        :true-value="'1'"
                                        :false-value="'0'"
                                        class="checkbox-input"
                                        id="checkbox7"
                                      />
                                      <label for="checkbox7"
                                        >Show Countdown On Web
                                      </label>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="col-12 text-right">
                              <carfax-valuation-c-t-a
                                :app_metadata="app_metadata"
                              ></carfax-valuation-c-t-a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12 col-12">
                        <div class="m-width-s">
                          <div class="row">
                            <div class="col-md-4">
                              <label>Financing</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <div class="custom-control custom-switch">
                                <input
                                  type="checkbox"
                                  v-model="form.financing_on"
                                  :true-value="'1'"
                                  :false-value="'0'"
                                  class="custom-control-input"
                                  id="customSwitch10"
                                  name="Financing"
                                />
                                <label
                                  class="custom-control-label"
                                  for="customSwitch10"
                                >
                                  <span class="switch-icon-left"> ON</span>
                                  <span class="switch-icon-right">OFF</span>
                                </label>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <label>Financing Special</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <div class="custom-control custom-switch">
                                <input
                                  type="checkbox"
                                  v-model="form.financing_special"
                                  :true-value="'1'"
                                  :false-value="'0'"
                                  class="custom-control-input"
                                  name="Financing-Special"
                                  id="customSwitch11"
                                />
                                <label
                                  class="custom-control-label"
                                  for="customSwitch11"
                                >
                                  <span class="switch-icon-left">ON</span>
                                  <span class="switch-icon-right">OFF</span>
                                </label>
                              </div>
                            </div>
                            <div
                              class="col-md-12 col-12"
                              v-show="form.financing_special == '1'"
                            >
                              <div class="row">
                                <div class="col-md-4">
                                  <label
                                    style="position: relative; top: 25%"
                                    class="text-muted"
                                    >Downpayment</label
                                  >
                                </div>
                                <div class="col-md-8 form-group">
                                  <div id="slider-input">
                                    <fieldset>
                                      <div class="input-group">
                                        <!-- <div class="mr-1 slider-space-r" id="slider-with-input"></div> -->
                                        <vue-slider
                                          :height="10"
                                          style="padding-top: 3em"
                                          :interval="0.01"
                                          class="col-8"
                                          :tooltip="'always'"
                                          :tooltip-formatter="'{value} %'"
                                          v-model="form.downpayment"
                                        />
                                        <span
                                          class="col-4"
                                          style="margin-top: 0.5em"
                                          ><input
                                            min="0"
                                            max="100"
                                            type="number"
                                            step="0.01"
                                            @keyup="changeDownpayment()"
                                            style="
                                              width: 60%;
                                              display: inline-block;
                                              margin-right: 1em;
                                            "
                                            v-model="form.downpayment"
                                            class="form-control"
                                          />%</span
                                        >
                                      </div>
                                    </fieldset>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <label
                                    style="position: relative; top: 25%"
                                    class="text-muted"
                                    >Interest Rate</label
                                  >
                                </div>
                                <div class="col-md-8 form-group">
                                  <div id="slider-input">
                                    <fieldset>
                                      <div class="input-group">
                                        <vue-slider
                                          :height="10"
                                          style="padding-top: 3em"
                                          :interval="0.01"
                                          class="col-8"
                                          :tooltip="'always'"
                                          :tooltip-formatter="'{value} %'"
                                          v-model="form.interest_rate"
                                        />
                                        <span
                                          class="col-4"
                                          style="margin-top: 0.5em"
                                          ><input
                                            min="0"
                                            max="100"
                                            type="number"
                                            step="0.01"
                                            @keyup="changeInterestRate()"
                                            style="
                                              width: 60%;
                                              display: inline-block;
                                              margin-right: 1em;
                                            "
                                            v-model="form.interest_rate"
                                            class="form-control"
                                          />%</span
                                        >
                                      </div>
                                    </fieldset>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <label
                                    style="position: relative; top: 25%"
                                    class="text-muted"
                                    >Term</label
                                  >
                                </div>
                                <div class="col-md-8 form-group">
                                  <div id="slider-input">
                                    <fieldset>
                                      <div class="input-group">
                                        <vue-slider
                                          :height="10"
                                          style="padding-top: 3em"
                                          :max="96"
                                          class="col-8"
                                          :tooltip="'always'"
                                          :tooltip-formatter="'{value} Months'"
                                          v-model="form.term"
                                        />
                                        <span
                                          class="col-4"
                                          style="margin-top: 0.5em"
                                          ><input
                                            min="0"
                                            max="100"
                                            type="number"
                                            step="1"
                                            @keyup="changeTerm()"
                                            style="
                                              width: 60%;
                                              display: inline-block;
                                              margin-right: 1em;
                                            "
                                            v-model="form.term"
                                            class="form-control"
                                          />Month</span
                                        >
                                      </div>
                                    </fieldset>
                                  </div>
                                </div>
                                <div class="col-md-4">
                                  <label class="text-muted"
                                    >Payment Frequency</label
                                  >
                                </div>
                                <div class="col-md-5 form-group">
                                  <select
                                    v-model="form.payment_frequency"
                                    class="custom-select form-control"
                                    id="PaymentFrequency"
                                    name="Payment Frequency"
                                  >
                                    <option value="" selected disabled>
                                      Select Payment
                                    </option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Bi-Weekly">Bi-Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                  </select>
                                </div>
                                <div class="col-md-4"></div>
                                <div class="col-md-8 form-group">
                                  <fieldset>
                                    <div class="input-group">
                                      <label class="mr-1">{{ EMI }}</label>
                                      <label class="text-muted">{{
                                        downpayment
                                      }}</label>
                                    </div>
                                  </fieldset>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12 col-12">
                        <div class="m-width-s mb-0 pb-0 border-0">
                          <div class="row">
                            <div class="col-md-4">
                              <label>Location</label>
                            </div>
                            <div class="col-md-5 form-group">
                              <select
                                class="custom-select form-control"
                                v-model="form.location"
                                id="location"
                                name="location"
                              >
                                <option value="" selected disabled>
                                  Select location
                                </option>
                                <option
                                  :value="location"
                                  v-for="location in locations"
                                  :key="location.index"
                                >
                                  {{ location }}
                                </option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- // form layout section end -->
    <!-- form layout section start -->
    <section id="description">
      <div class="row match-height">
        <div class="col-md-12 col-12">
          <div class="card shadow-none mb-0">
            <div class="card-header border-line-bottom-title">
              <h4 class="card-title">Vehicle Description</h4>
            </div>
            <div class="card-content">
              <div class="card-body">
                <form
                  class="form form-horizontal webapp-form"
                  id="vehicle-form-description"
                >
                  <div class="form-body">
                    <div class="row">
                      <div class="col-md-12 col-12 form-group">
                        <text-editor
                          name="description"
                          :elementId="'text-editor-1'"
                          ref="myTextEditor"
                          :options="editorOption"
                          v-model="form.vehicle_description"
                          :editor_style="'min-height:10em;'"
                        >
                        </text-editor>
                      </div>
                      <div class="col-md-12 text-right">
                        <inventory-vehicle-description-re-writer-c-t-a
                          v-show="form.vehicle_description"
                        />
                        <inventory-vehicle-description-template-c-t-a />
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- // form layout section end -->
    <!-- form layout section start -->
    <section id="options">
      <div class="row match-height">
        <div class="col-md-12 col-12">
          <div class="card shadow-none mb-0">
            <div class="card-header border-line-bottom-title">
              <h4 class="card-title">
                Features
                <button
                  class="float-right btn btn-secondary"
                  @click="populateDefaultOptions"
                >
                  Populate Vehicle Options
                </button>
              </h4>
            </div>
            <div class="card-content">
              <div class="card-body">
                <div class="form-body">
                  <input
                    type="hidden"
                    name="features"
                    value=""
                    style="display: none"
                  />
                  <!-- Standard Collapsable Section : OPEN -->
                  <div
                    id="features-data"
                    v-if="form.vin_company == '1' || form.vin_company == '2'"
                    class="collapsible collapse-icon accordion-icon-rotate"
                  >
                    <div class="card collapse-header">
                      <div
                        id="headingCollapse5"
                        data-toggle="collapse"
                        role="button"
                        data-target="#collapse5"
                        aria-expanded="true"
                        aria-controls="collapse5"
                        class="card-header"
                      >
                        <span class="collapse-title"
                          ><i class="bx bx-chalkboard align-middle"></i>
                          <span class="align-middle">Standard</span></span
                        >
                      </div>
                      <div
                        id="collapse5"
                        role="tabpanel"
                        aria-labelledby="headingCollapse5"
                        class="collapse"
                        style=""
                      >
                        <div class="card-content">
                          <div id="v-standard" class="card-body">
                            <ul class="list-unstyled mb-0 feactures-check">
                              <template v-for="(value, key) in this.standard">
                                <li
                                  :key="key"
                                  class="card-header border-line-bottom-title mr-2 mb-1"
                                  style="
                                    width: 100%;
                                    border-right: 0;
                                    border-left: 0;
                                    border-top: 0;
                                  "
                                >
                                  {{ key }}
                                </li>
                                <li
                                  class="d-inline-block mr-2"
                                  v-for="(nested_value, nested_key) in value"
                                  :key="nested_key"
                                >
                                  <div class="checkbox checkbox-success">
                                    <input
                                      type="checkbox"
                                      :id="nested_key"
                                      class="checkbox-input"
                                      v-model="standard[key][nested_key]"
                                      :true-value="'1'"
                                      :false-value="'0'"
                                    />
                                    <label :for="nested_key">{{
                                      nested_key
                                    }}</label>
                                  </div>
                                </li>
                              </template>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Standard Collapsable Section : OPEN -->

                  <!-- Generic Equipments Collapsable Section : OPEN -->
                  <div
                    v-if="form.vin_company == '1'"
                    id="features-generic"
                    class="collapsible collapse-icon accordion-icon-rotate"
                  >
                    <div class="card collapse-header">
                      <div
                        id="headingCollapseGeneric"
                        data-toggle="collapse"
                        role="button"
                        data-target="#genericEquipment"
                        aria-expanded="true"
                        aria-controls="genericEquipment"
                        class="card-header"
                      >
                        <span class="collapse-title"
                          ><i class="bx bx-chalkboard align-middle"></i>
                          <span class="align-middle">Generic Equipments</span>
                        </span>
                      </div>

                      <!-- generic equipment open-->
                      <div
                        id="genericEquipment"
                        role="tabpanel"
                        aria-labelledby="headingCollapseGeneric"
                        class="collapse"
                        style=""
                      >
                        <div class="card-content">
                          <div id="v-standard" class="card-body">
                            <ul class="list-unstyled mb-0">
                              <template
                                v-for="(value, header) in this
                                  .generic_equipment"
                              >
                                <li
                                  :key="header"
                                  class="card-header border-line-bottom-title mr-2 mb-1"
                                  style="
                                    width: 100%;
                                    border-right: 0;
                                    border-left: 0;
                                    border-top: 0;
                                  "
                                >
                                  {{ header }}
                                </li>
                                <li
                                  v-for="(nested_value, nested_key) in value"
                                  :key="nested_key"
                                  class="d-inline-block mr-2"
                                >
                                  <div class="card">
                                    <div
                                      class="card-header"
                                      style="padding: 0.5em"
                                    >
                                      {{ nested_key }}
                                    </div>
                                    <ul class="list-group list-group-flush">
                                      <li
                                        class="list-group-item"
                                        v-for="(attribute_value,
                                        attribute_key) in nested_value"
                                        :key="attribute_key"
                                      >
                                        <div class="checkbox checkbox-success">
                                          <input
                                            type="checkbox"
                                            :id="attribute_key"
                                            class="checkbox-input"
                                            v-model="
                                              generic_equipment[header][
                                                nested_key
                                              ][attribute_key]
                                            "
                                            :true-value="'1'"
                                            :false-value="'0'"
                                          />
                                          <label :for="attribute_key">{{
                                            attribute_key
                                          }}</label>
                                        </div>
                                      </li>
                                    </ul>
                                  </div>
                                </li>
                              </template>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <!-- generic equipment end-->
                    </div>
                  </div>
                  <!-- Generic Equipments Collapsable Section : CLOSE -->

                  <!-- Technical Specifications Collapsable Section : OPEN -->
                  <div
                    v-if="form.vin_company == '1'"
                    id="technical_specifications_1_collapsable"
                    class="collapsible collapse-icon accordion-icon-rotate"
                  >
                    <div class="card collapse-header">
                      <div
                        id="technical_specifications_1_header"
                        data-toggle="collapse"
                        role="button"
                        data-target="#technical_specifications_1_data"
                        aria-expanded="true"
                        aria-controls="technical_specifications_1_data"
                        class="card-header"
                      >
                        <span class="collapse-title"
                          ><i class="bx bx-chalkboard align-middle"></i>
                          <span class="align-middle"
                            >Techincal Specifications</span
                          >
                        </span>
                      </div>

                      <!-- Technical Specifications open-->
                      <div
                        id="technical_specifications_1_data"
                        role="tabpanel"
                        aria-labelledby="technical_specifications_1_data"
                        class="collapse"
                        style=""
                      >
                        <div class="card-content">
                          <div id="v-standard" class="card-body">
                            <ul class="list-unstyled mb-0">
                              <template
                                v-for="(value, header) in this
                                  .technical_specification"
                              >
                                <li
                                  :key="header"
                                  class="card-header border-line-bottom-title mr-2 mb-1"
                                  style="
                                    width: 100%;
                                    border-right: 0;
                                    border-left: 0;
                                    border-top: 0;
                                  "
                                >
                                  {{ header }}
                                </li>
                                <li
                                  v-for="(nested_value, nested_key) in value"
                                  :key="nested_key"
                                  class="d-inline-block mr-2"
                                >
                                  <div class="card">
                                    <div
                                      class="card-header"
                                      style="padding: 0.5em"
                                    >
                                      {{ nested_key }}
                                    </div>
                                    <ul class="list-group list-group-flush">
                                      <template
                                        v-for="(attribute_value,
                                        attribute_key) in nested_value"
                                      >
                                        <li
                                          class="list-group-item"
                                          v-if="
                                            showAttributeInFeatures(
                                              attribute_value[0],
                                            )
                                          "
                                          :key="attribute_key"
                                        >
                                          <div>
                                            <div>
                                              <label :for="attribute_key">{{
                                                attribute_key
                                              }}</label>
                                            </div>
                                            <div>
                                              <input
                                                type="input"
                                                :id="attribute_key"
                                                v-model="
                                                  technical_specification[
                                                    header
                                                  ][nested_key][
                                                    attribute_key
                                                  ][0]
                                                "
                                              />
                                            </div>
                                          </div>
                                        </li>
                                      </template>
                                    </ul>
                                  </div>
                                </li>
                              </template>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <!-- Technical Specifications end-->
                    </div>
                  </div>
                  <!-- Technical Specifications Section : CLOSE -->

                  <!-- Technical Specifications Section : OPEN -->
                  <div
                    v-if="form.vin_company == '2'"
                    id="technical_specifications_2"
                    class="collapsible collapse-icon accordion-icon-rotate"
                  >
                    <div class="card collapse-header">
                      <div
                        id="technical_specifications_2"
                        data-toggle="collapse"
                        role="button"
                        data-target="#tehnical_specifications_2"
                        aria-expanded="true"
                        aria-controls="tehnical_specifications_2"
                        class="card-header"
                      >
                        <span class="collapse-title"
                          ><i class="bx bx-chalkboard align-middle"></i>
                          <span class="align-middle"
                            >Technical Specifications</span
                          ></span
                        >
                      </div>
                      <div
                        id="tehnical_specifications_2"
                        role="tabpanel"
                        aria-labelledby="technical_specifications_2"
                        class="collapse"
                        style=""
                      >
                        <div class="card-content">
                          <div id="v-standard" class="card-body">
                            <ul class="list-unstyled mb-0 feactures-check">
                              <template
                                v-for="(value, key) in this
                                  .technical_specification"
                              >
                                <li
                                  :key="key"
                                  class="card-header border-line-bottom-title mr-2 mb-1"
                                  style="
                                    width: 100%;
                                    border-right: 0;
                                    border-left: 0;
                                    border-top: 0;
                                  "
                                >
                                  {{ key }}
                                </li>
                                <li
                                  class="d-inline-block mr-2"
                                  v-for="(nested_value, nested_key) in value"
                                  :key="nested_key"
                                >
                                  <div>
                                    <label :for="nested_key">{{
                                      nested_key
                                    }}</label>
                                  </div>
                                  <div>
                                    <input
                                      type="text"
                                      :id="nested_key"
                                      v-model="
                                        technical_specification[key][nested_key]
                                      "
                                    />
                                  </div>
                                </li>
                              </template>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Technical Specifications Section : OPEN -->

                  <!-- Standard Collapsable Section : OPEN -->
                  <div
                    v-if="
                      form.vin_company == '2' ||
                        Object.keys(optional).length !== 0
                    "
                    id="optional_data_collapsible"
                    class="collapsible collapse-icon accordion-icon-rotate"
                  >
                    <div class="card collapse-header">
                      <div
                        id="optional_header"
                        data-toggle="collapse"
                        role="button"
                        data-target="#optional_data"
                        aria-expanded="true"
                        aria-controls="optional_data"
                        class="card-header"
                      >
                        <span class="collapse-title"
                          ><i class="bx bx-chalkboard align-middle"></i>
                          <span class="align-middle"
                            >Vehicle Options</span
                          ></span
                        >
                      </div>
                      <div
                        id="optional_data"
                        role="tabpanel"
                        aria-labelledby="optional_data"
                        class="collapse"
                        style=""
                      >
                        <div class="card-content">
                          <div id="v-standard" class="card-body">
                            <ul class="list-unstyled mb-0 feactures-check">
                              <template v-for="(value, key) in this.optional">
                                <li
                                  :key="key"
                                  class="card-header border-line-bottom-title mr-2 mb-1"
                                  style="
                                    width: 100%;
                                    border-right: 0;
                                    border-left: 0;
                                    border-top: 0;
                                  "
                                >
                                  {{ key }}
                                </li>
                                <li
                                  class="d-inline-block mr-2"
                                  v-for="(nested_value, nested_key) in value"
                                  :key="'optional_' + nested_key"
                                >
                                  <div class="checkbox checkbox-success">
                                    <input
                                      type="checkbox"
                                      :id="'optional_' + nested_key"
                                      class="checkbox-input"
                                      v-model="optional[key][nested_key]"
                                      :true-value="'1'"
                                      :false-value="'0'"
                                    />
                                    <label :for="'optional_' + nested_key">{{
                                      nested_key
                                    }}</label>
                                  </div>
                                </li>
                              </template>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Standard Collapsable Section : OPEN -->

                  <!-- Custom Feature Collapsable Section : OPEN -->
                  <div class="row record-repeater">
                    <div class="col-md-4">
                      <button
                        class="btn btn-icon btn-primary"
                        @click="custom_feature_count += 1"
                        type="button"
                        data-repeater-create=""
                      >
                        <i class="bx bx-plus"></i>Add New Custom Feature
                      </button>
                    </div>
                    <div
                      class="col-md-5 form-group"
                      data-repeater-list="custom"
                    >
                      <div
                        class="row"
                        data-repeater-item="features"
                        style=""
                        v-for="n in this.custom_feature_count"
                        :key="n"
                      >
                        <div class="col-md-10 col-12 form-group">
                          <input
                            type="text"
                            class="form-control"
                            v-model="form.custom_features[n - 1]"
                            name="custom[0][features]"
                          />
                        </div>
                        <div
                          class="col-md-2 col-sm-12 form-group d-flex align-items-center"
                        >
                          <button
                            class="btn btn-danger text-nowrap px-1"
                            @click="
                              form.custom_features.splice(n - 1, 1);
                              custom_feature_count -= 1;
                            "
                            data-repeater-delete=""
                            type="button"
                          >
                            <i class="bx bx-x"></i>
                          </button>
                        </div>
                        <!-- New Rows will be here -->
                      </div>
                    </div>
                  </div>
                  <!-- Custom Feature Collapsable Section : CLOSE -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- // form layout section end -->
    <!-- Syndication  form layout section start -->
    <section id="dropzone-examples">
      <div class="row">
        <div class="col-12">
          <div class="card shadow-none mb-0">
            <div class="card-header border-line-bottom-title">
              <h4 class="card-title">Syndication</h4>
            </div>
            <div class="card-content">
              <div class="card-body pb-0 pt-0">
                <form
                  class="form form-horizontal webapp-form"
                  id="vehicle-form"
                >
                  <div class="form-body">
                    <div class="row">
                      <!-- kijiji category -->
                      <div class="col-md-2" v-show="showKijijiCategory">
                        <label>Kijiji Category</label>
                      </div>
                      <div
                        class="col-md-2 form-group"
                        v-show="showKijijiCategory"
                      >
                        <select
                          class="custom-select form-control"
                          v-model="form.kijiji_category"
                          id="kijiji_category"
                          name="Kijiji Category"
                        >
                          <option value="" selected>
                            Select Kijiji Category
                          </option>
                          <option
                            :value="category.value"
                            v-for="category in all_kijiji_categories"
                            :key="category.index"
                          >
                            {{ category.name }}
                          </option>
                        </select>
                      </div>
                      <!-- end : kijiji category -->

                      <!-- kijijinpv category -->
                      <div class="col-md-2" v-show="showKijijiNpvCategory">
                        <label>Kijiji Commercial Category</label>
                      </div>
                      <div
                        class="col-md-2 form-group"
                        v-show="showKijijiNpvCategory"
                      >
                        <select
                          class="custom-select form-control"
                          v-model="form.kijijinpv_category"
                          id="kijijinpv_category"
                          name="Kijiji Commercial Category"
                        >
                          <option value="" selected>
                            Select Kijiji Commercial Category
                          </option>
                          <option
                            :value="category.value"
                            v-for="category in all_kijijinpv_categories"
                            :key="category.index"
                          >
                            {{ category.name }}
                          </option>
                        </select>
                      </div>
                      <!-- end : kijijinpv category -->

                      <!-- autotrader category -->
                      <div class="col-md-2" v-show="showAutoTraderCategory">
                        <label>Autotrader Category</label>
                      </div>
                      <div
                        class="col-md-2 form-group"
                        v-show="showAutoTraderCategory"
                      >
                        <select
                          class="custom-select form-control"
                          v-model="form.autotrader_category"
                          id="autotrader_category"
                          name="Autotrader Category"
                        >
                          <option value="" selected>
                            Select Autotrader Category
                          </option>
                          <option
                            :value="category.value"
                            v-for="category in all_autotrader_categories"
                            :key="category.index"
                          >
                            {{ category.name }}
                          </option>
                        </select>
                      </div>
                      <!-- end : autotrader category -->

                      <!-- facebook category -->
                      <div class="col-md-2" v-show="showFacebookCategory">
                        <label>Facebook Category</label>
                      </div>
                      <div
                        class="col-md-2 form-group"
                        v-show="showFacebookCategory"
                      >
                        <select
                          class="custom-select form-control"
                          v-model="form.facebook_category"
                          id="facebook_category"
                          name="Facebook Category"
                        >
                          <option value="" selected>
                            Select Facebook Category
                          </option>
                          <option
                            :value="category.value"
                            v-for="category in all_facebook_categories"
                            :key="category.index"
                          >
                            {{ category.name }}
                          </option>
                        </select>
                      </div>
                      <!-- end : facebook category -->
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-12 form-group">
                        <ul
                          class="list-unstyled mt-0 mb-0 m-width-s pb-0 border-0"
                        >
                          <li
                            class="d-inline-block mr-2 mb-1 main-syndi-cation"
                            v-for="syndication_company in active_syndication"
                            :key="syndication_company.slug"
                          >
                            <fieldset>
                              <div class="checkbox">
                                <input
                                  type="checkbox"
                                  class="checkbox-input"
                                  :id="
                                    syndication_company.slug + '_syndication'
                                  "
                                  v-model="form.syndication"
                                  :value="syndication_company.slug"
                                />
                                <label
                                  :for="
                                    syndication_company.slug + '_syndication'
                                  "
                                  ><img
                                    :src="
                                      checkForImageUrlOfSyndication(
                                        syndication_company.slug,
                                      )
                                    "
                                    :alt="syndication_company.label"
                                    class="syndi-cation-icon"
                                  />
                                </label>
                              </div>
                            </fieldset>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <inventory-vehicle-description-re-writer
      :vehicle_description="form.vehicle_description"
    />
    <!-- // Syndication  form layout section end -->
    <inventory-vehicle-description-template />
    <v-i-n-decoder :elementId="'default'"></v-i-n-decoder>
    <carfax-valuation-model
      :location_name="form.location"
      :odometer="form.odometer"
      :vin="form.vin"
    ></carfax-valuation-model>
  </div>
</template>
<script>
import vSelect from "vue-select";
import VueSlider from "vue-slider-component";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import Toast from "../../library/Toast";

import TextEditor from "../WebComponents/TextEditor";
import VINDecoder from "../WebComponents/VINDecoder";
import { mapGetters, mapActions } from "vuex";
import InventoryVehicleDescriptionTemplateCTA from "./InventoryVehicleDescriptionTemplateCTA.vue";
import InventoryVehicleDescriptionTemplate from "./InventoryVehicleDescriptionTemplate.vue";
import InventoryVehicleDescriptionReWriterCTA from "./InventoryVehicleDescriptionReWriterCTA.vue";
import InventoryVehicleDescriptionReWriter from "./InventoryVehicleDescriptionReWriter.vue";
import CarfaxValuationCTA from "../WebComponents/CarfaxValuationCTA.vue";
import CarfaxValuationModel from "../WebComponents/CarfaxValuationModel.vue";

export default {
  name: "InventoryManageGeneral",
  props: {
    time_zone: String,
    app_metadata: { required: true },
  },
  watch: {
    sellingPrice: function(newVal, oldVal) {
      if (newVal != oldVal) {
        Event.fire("change_inventory_selling_price", newVal);
      }
    },
  },
  components: {
    "v-select": vSelect,
    VueSlider,
    DatePicker,
    TextEditor,
    InventoryVehicleDescriptionTemplateCTA,
    InventoryVehicleDescriptionTemplate,
    InventoryVehicleDescriptionReWriterCTA,
    InventoryVehicleDescriptionReWriter,
    VINDecoder,
    CarfaxValuationCTA,
    CarfaxValuationModel,
  },
  data() {
    return {
      global: window.global,
      created_at: null,
      updated_at: null,
      editorOption: {},
      main_domain: "",
      vehicle_tags: [],
      action: "new",
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
      all_facebook_categories: [],
      all_autotrader_categories: [],
      all_kijiji_categories: [],
      all_kijijinpv_categories: [],
      technical_specification: {},
      generic_equipment: {},
      standard: {},
      optional: {},
      default_optional: {},
      locations: [],
      syndication_companies_list: [],
      active_syndication: [],
      custom_feature_count: 0,
      form: {
        vin_company: "",
        vin: "",
        vindisplay: "1",
        stock_no: "",
        stock_date: "",
        status: "Instock",
        sub_status: "Available For Sale",
        feature_listing: "0",
        vehicle_type: "",
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
        km_miles: "",
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
        kijiji_category: "",
        autotrader_category: "",
        facebook_category: "",
        kijijinpv_category: "",
        page_url: "",
      },
      errors: [],
    };
  },
  computed: {
    ...mapGetters("inventoryView", [
      "getStatuses",
      "getSubStatues",
      "getVehicleTypes",
    ]),
    sellingPrice() {
      return this.form.special_price_on == "1"
        ? this.form.special_price
        : this.form.price;
    },
    setSpecialTimeData() {
      return moment(
        this.form.special_price_date + " " + this.form.special_price_time,
        "MM/DD/YYYY HH:mm",
      ).isValid();
    },
    showKijijiCategory() {
      return (
        this.all_kijiji_categories.length > 0 &&
        this.form.syndication.includes("kijiji")
      );
    },
    showKijijiNpvCategory() {
      return (
        this.all_kijijinpv_categories.length > 0 &&
        this.form.syndication.includes("kijijinpv")
      );
    },
    showFacebookCategory() {
      return (
        this.all_facebook_categories.length > 0 &&
        this.form.syndication.includes("facebook")
      );
    },
    showAutoTraderCategory() {
      return (
        this.all_autotrader_categories.length > 0 &&
        (this.form.syndication.includes("autotrader") ||
          this.form.syndication.includes("autotraderglobal") ||
          this.form.syndication.includes("autotraderglobalnpv"))
      );
    },
    downpayment() {
      let special_price = parseFloat(this.form.special_price);
      let price = parseFloat(this.form.price);
      let amount = isNaN(special_price) ? price : special_price;
      if (this.form.special_price_on != "1") {
        amount = price;
      }

      let down = parseFloat(this.form.downpayment);
      down = (down * amount) / 100;
      return isFinite(down) ? "$" + down + " Down Payment" : "Not Available";
    },
    EMI() {
      let special_price = parseFloat(this.form.special_price);
      let price = parseFloat(this.form.price);
      let term_placeholder = "Month";

      let amount = isNaN(special_price) ? price : special_price;
      if (this.form.special_price_on != "1") {
        amount = price;
      }

      let payment_frequency = this.form.payment_frequency;

      let months = parseFloat(this.form.term);
      let annInterest = parseFloat(this.form.interest_rate);
      let monInt = annInterest / 1200;

      if (payment_frequency == "Weekly") {
        term_placeholder = "Week";
        months = (parseFloat(this.form.term) / 12) * 52;
        monInt = annInterest / 5200;
      } else if (payment_frequency == "Bi-Weekly") {
        term_placeholder = "Bi-Week";
        months = (parseFloat(this.form.term) / 12) * 26;
        monInt = annInterest / 2600;
      }

      let down = parseFloat(this.form.downpayment);
      down = (down * amount) / 100;

      let cal = (
        (monInt + monInt / (Math.pow(1 + monInt, months) - 1)) *
        (amount - (down || 0))
      ).toFixed(2);
      return isFinite(cal)
        ? "$" + cal + " Per " + term_placeholder
        : "Not Available";
    },
    updateView() {
      return this.$route.params.id && this.$route.name == "inventory-update";
    },
  },
  methods: {
    populateDefaultOptions() {
      this.optional = this.default_optional;
    },
    showAttributeInFeatures(value) {
      if (typeof value === "undefined") {
        return false;
      }

      if (value.search("TBD") == -1 && value != "") {
        return true;
      }

      return false;
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
    checkForImageUrlOfSyndication(slug) {
      const vm = this;
      let response = "";
      if (this.active_syndication.length > 0) {
        this.syndication_companies_list.some((x) => {
          if (x["slug"] == slug) {
            response = x["image_url"];
          }
        });
      }
      return this.$helpers.parseAbsoluteOrRelativeUrl(
        window.global.cdn,
        response,
      );
    },
    activeSyndication() {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          "/api/business/configurations/active/syndication",
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (element["key"] == "syndication") {
              this.active_syndication = JSON.parse(element["value"]);
              this.active_syndication.forEach((x) => {
                if (
                  x["status"] == "1" &&
                  !(this.$route.name == "inventory-update")
                ) {
                  vm.form.syndication.push(x["slug"]);
                }
              });
            }
          }
        }
      });
    },
    syndicationCompanyList() {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: this.main_domain + "/api/syndication/companies/all?paginate=false",
      }).then((response) => {
        vm.$data.syndication_companies_list =
          response.data.syndication_companies_list;
        vm.$data.pager = response.data.pager;
      });
    },
    getLocations() {
      let request_url =
        window.location.origin + "/api/business/location/active";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      }).then(({ data }) => {
        this.locations = data.locations.map((location) => location.name);
        let default_location = data.locations.filter(
          (location) => location.default == "1",
        );
        let update_request =
          this.$route.params.id && this.$route.name == "inventory-update";
        if (default_location.length > 0 && !update_request) {
          for (const location of default_location) {
            this.form.location = location.name;
          }
        }
      });
    },
    changeDownpayment() {
      this.form.downpayment =
        this.form.downpayment <= 100 && this.form.downpayment >= 0
          ? this.form.downpayment
          : 0;
    },
    changeInterestRate() {
      this.form.interest_rate =
        this.form.interest_rate <= 100 && this.form.interest_rate >= 0
          ? this.form.interest_rate
          : 0;
    },
    changeTerm() {
      this.form.term =
        this.form.term <= 96 && this.form.term >= 0 ? this.form.term : 0;
    },
    setArrayAndDefaultValues(property_in_array, property_form, value) {
      //check the array, if present than do nothing else push the item.
      this[property_in_array].includes(value)
        ? ""
        : this[property_in_array].push(value);
      //set the value in the form.
      this.form[property_form] = value;
    },
    async getAvailableTrimsForVIN() {
      Event.fire("load_available_trims_for_a_vin_default", this.form.vin);
      return;
    },
    async getVehicleInformation(data) {
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
        let update_request =
          this.$route.params.id && this.$route.name == "inventory-update";

        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (element.key == "all_kijiji_categories") {
              if (this.$helpers.isValidJsonString(element.value)) {
                this.all_kijiji_categories = JSON.parse(element.value);
              }
            } else if (element.key == "all_autotrader_categories") {
              if (this.$helpers.isValidJsonString(element.value)) {
                this.all_autotrader_categories = JSON.parse(element.value);
              }
            } else if (element.key == "all_facebook_categories") {
              if (this.$helpers.isValidJsonString(element.value)) {
                this.all_facebook_categories = JSON.parse(element.value);
              }
            } else if (element.key == "inventory_default_feature_options") {
              if (this.$helpers.isValidJsonString(element.value)) {
                this.default_optional = JSON.parse(element.value);
              }
            } else if (element.key == "all_kijijinpv_categories") {
              if (this.$helpers.isValidJsonString(element.value)) {
                this.all_kijijinpv_categories = JSON.parse(element.value);
              }
            }
          }
        }

        if (!update_request) {
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
  },
  created() {
    const vm = this;
    this.form.stock_date = moment(new Date()).format("MM/DD/YYYY");

    this.getYears();
    this.getBodyTypes();
    this.getDoors();
    this.getCylinders();
    this.getTransmissions();
    this.getDriveTrains();
    this.getPassengers();
    this.getFuelTypes();
    this.getLocations();
    this.getInventoryConfigurations();

    //Update
    if (this.$route.params.id && this.$route.name == "inventory-update") {
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/inventory/show/" +
          this.$route.params.id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      }).then(({ data }) => {
        this.setArrayAndDefaultValues("makes", "make", data.make);
        this.setArrayAndDefaultValues("trim", "trim", data.trim);
        this.setArrayAndDefaultValues("doors", "door", data.door);
        this.setArrayAndDefaultValues(
          "drivetrains",
          "drivetrain",
          data.drivetrain,
        );
        this.setArrayAndDefaultValues(
          "body_types",
          "body_type",
          data.body_type,
        );
        this.setArrayAndDefaultValues("cylinders", "engine", data.engine);
        this.setArrayAndDefaultValues(
          "fuel_types",
          "fuel_type",
          data.fuel_type,
        );

        let properties_of_form = [
          "vin_company",
          "vin",
          "vindisplay",
          "stock_no",
          "status",
          "sub_status",
          "feature_listing",
          "vehicle_type",
          "visibility",
          "title",
          "year",
          "model",
          "litres",
          "transmission",
          "passengers",
          "odometer",
          "km_miles",
          "fuel_city",
          "fuel_hwy",
          "exterior_color",
          "interior_color",
          "price",
          "hide_price",
          "special_price_on",
          "special_price",
          "special_price_type",
          "show_stripe",
          "financing_on",
          "financing_special",
          "downpayment",
          "interest_rate",
          "term",
          "payment_frequency",
          "location",
          "vehicle_description",
          "kijiji_category",
          "kijijinpv_category",
          "autotrader_category",
          "facebook_category",
          "page_url",
        ];

        for (var property of properties_of_form) {
          this.form[property] = data[property];
        }

        if (this.$helpers.isValidJsonString(data.vehicle_tags)) {
          this.form.vehicle_tags = JSON.parse(data.vehicle_tags);
        }

        if (this.$helpers.isValidJsonString(data.custom_features)) {
          this.form.custom_features = JSON.parse(data.custom_features);
          this.custom_feature_count = this.form.custom_features.length;
        }

        if (this.$helpers.isValidJsonString(data.technical_specification)) {
          this.technical_specification = JSON.parse(
            data.technical_specification,
          );
        }

        if (this.$helpers.isValidJsonString(data.generic_equipment)) {
          this.generic_equipment = JSON.parse(data.generic_equipment);
        }

        if (this.$helpers.isValidJsonString(data.standard)) {
          this.standard = JSON.parse(data.standard);
        }

        if (this.$helpers.isValidJsonString(data.syndication)) {
          this.form.syndication = [];
          this.form.syndication = JSON.parse(data.syndication);
        }

        if (this.$helpers.isValidJsonString(data.optional)) {
          this.optional = JSON.parse(data.optional);
        }

        let stock_date = moment(data.stock_date);

        this.form.stock_date = stock_date.isValid()
          ? stock_date.format("MM/DD/YYYY")
          : moment(new Date()).format("MM/DD/YYYY");

        let special_price_date = moment(data.special_price_date);
        this.form.special_price_date = special_price_date.isValid()
          ? special_price_date.format("MM/DD/YYYY")
          : null;
        this.form.special_price_time = special_price_date.isValid()
          ? special_price_date.format("HH:mm")
          : null;

        vm.created_at = this.$helpers.changeUTCtoLocalDate(
          data.created_at,
          "MM/DD/YYYY hh:mm A",
          vm.time_zone,
        );
        vm.updated_at = this.$helpers.changeUTCtoLocalDate(
          data.updated_at,
          "MM/DD/YYYY hh:mm A",
          vm.time_zone,
        );

        Event.fire(
          "change_inventory",
          (({ year, make, model, stock_no, vin, page_url, status }) => ({
            year,
            make,
            model,
            stock_no,
            vin,
            page_url,
            status,
          }))(this.form),
        );

        Event.fire("get_inventory_datetime", {
          created_at: vm.created_at,
          updated_at: vm.updated_at,
        });
      });
    }
  },
  mounted() {
    const vm = this;

    const main_domain = psl.parse(window.location.host);
    this.main_domain = "https://" + main_domain.domain;
    this.syndicationCompanyList();
    this.activeSyndication();

    Event.listen("save_inventory", (data) => {
      let date_time = moment(
        vm.$data.form.special_price_date +
          " " +
          vm.$data.form.special_price_time,
        "MM/DD/YYYY HH:mm",
      );
      vm.$data.form.special_price_date = date_time.isValid() ? date_time : null;
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

      let request_url = window.location.origin;
      let update_request =
        this.$route.params.id && this.$route.name == "inventory-update";
      let success_message = update_request
        ? `Inventory Item Updated Successfully.`
        : `New Inventory's Item Created Successfully.`;

      request_url = update_request
        ? `${request_url}/api/business/inventory/update/${this.$route.params.id}`
        : `${request_url}/api/business/inventory/create`;

      let message = update_request
        ? "Updating inventory ..."
        : "Creating new inventory ...";
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
          toast.success(success_message);
          toast.close();

          setTimeout(function() {
            Event.fire("save_complete", data);
          }, 500);

          Event.fire(
            "change_inventory",
            (({ year, make, model, stock_no, vin }) => ({
              year,
              make,
              model,
              stock_no,
              vin,
            }))(vm.form),
          );
          vm.updated_at = this.$helpers.changeUTCtoLocalDate(
            moment(),
            "MM/DD/YYYY HH:mm A",
            vm.time_zone,
          );
          Event.fire("get_inventory_datetime", {
            created_at: vm.created_at,
            updated_at: vm.updated_at,
          });
        })
        .catch(function(error) {
          vm.$data.errors = error.response.data;

          let error_message = "There were some errors while submitting form.";
          toast.fail(error_message);
          toast.close();

          setTimeout(function() {
            Event.fire("save_complete", null);
          }, 2000);
        });

      vm.$helpers.gTagClickEvent(
        vm,
        "zd_action",
        "inventory",
        "general",
        "save",
      );
    });

    Event.listen("update_vehicle_description", (vehicle_description) => {
      vm.$data.form.vehicle_description = vehicle_description;
    });

    Event.listen("load_available_data_for_a_vin_default", (data) => {
      vm.getVehicleInformation(data);
    });
  },
  beforeDestroy() {
    Event.vue.$off("save_complete", null);
    Event.vue.$off("change_inventory_selling_price", null);
    Event.vue.$off("change_inventory", null);
    Event.vue.$off("get_inventory_datetime", null);
    Event.vue.$off("load_available_data_for_a_vin_default", null);
  },
};
</script>
<style lang="scss">
@import "vue-select/src/scss/vue-select.scss";
@import "~vue-slider-component/lib/theme/default.scss";
#general-block-list .vs__selected {
  background-color: #5a8dee !important;
  border-color: transparent !important;
  color: #ffffff !important;
  padding: 0.2rem 0.6rem 0.2rem 0.6rem !important;
  height: 2em;
}
#general-block-list .vs__deselect {
  fill: white;
}
#general-block-list .vs__dropdown-option--selected {
  color: #727e8c;
  background: #ddd;
}
#general-block-list .vs__dropdown-menu {
  height: 200px;
}

#general-block-list .webapp-form .form-group .vdpComponent input {
  padding-left: 2.2em !important;
  line-height: 1.4;
  color: #475f7b;
  background-color: #ffffff;
  background-clip: padding-box;
  border: 1px solid #dfe3e7;
  border-radius: 0.267rem;
}

#general-block-list .vdpArrowPrev:after {
  border-right-color: green;
}

#general-block-list .vdpArrowNext:after {
  border-left-color: green;
}

#general-block-list .vdpCell.selectable:hover .vdpCellContent,
#general-block-list .vdpCell.selected .vdpCellContent {
  background: green;
}

#general-block-list .vdpCell.today {
  color: green;
}

#general-block-list .vdpTimeUnit > input:hover,
#general-block-list .vdpTimeUnit > input:focus {
  border-bottom-color: green;
}
#general-block-list .checkbox {
  margin-bottom: 0.2em;
}
#general-block-list .checkbox label {
  color: #212529;
  font-size: 14px;
  text-transform: capitalize;
  line-height: 1.5;
}

#general-block-list .collapsible .card.open,
.accordion .card.open {
  box-shadow: 0 3px 12px 0 rgba(0, 0, 0, 0.1) !important;
  border-radius: 0.267rem !important;
}

#text-editor-settings-1 .ql-picker-options,
#text-editor-1 .ql-picker-options {
  max-height: 350px;
}
</style>
