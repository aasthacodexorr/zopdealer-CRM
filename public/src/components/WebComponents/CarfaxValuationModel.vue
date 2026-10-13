<template>
  <!-- Quick Market Valuation : START  -->
  <div
    class="modal fade"
    id="carfaxValuationModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="Quick Market Valuation"
    aria-hidden="true"
  >
    <div
      class="modal-dialog"
      :class="current_step === 0 ? 'modal-md' : 'modal-lg'"
      role="document"
    >
      <div class="modal-content">
        <!-- Modal Header : START -->
        <div class="modal-header">
          <h5 class="modal-title" id="carfaxValuationModalHeader">
            Quick Market Valuation
          </h5>
          <button
            type="button"
            id="carfaxValuationModalClose"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <!-- Modal Header : END -->
        <!-- Modal Body : START -->
        <div class="modal-body">
          <form v-show="current_step === 0">
            <!-- STEP 1 : ENTER INFORMATION : TRIM SELECTION API : START -->
            <div class="form-row">
              <!-- Logos Row -->
              <div class="text-center w-100 my-50">
                <img
                  src="https://zopsoftware.com/dist/app-assets/images/logo/zop-logo.png"
                  alt="Zop"
                  style="height: 40px"
                />

                <div class="integration-connector mx-1 d-inline">
                  <span class="connector-line"></span>
                  <span class="connector-icon">&#8596;</span>
                  <span class="connector-line"></span>
                </div>

                <img
                  src="https://zopsoftware-asset.b-cdn.net/phaeton/themes/theme-1/images/CFC-Logo_small.png"
                  alt="Carfax Canada"
                  style="height: 40px"
                />
              </div>
              <h6 class="form-group col-md-12 font-italic">
                Get an accurate market valuation for your vehicle based on your
                neighbourhood. (Not intended for trailers, RVs, motorcycles
                etc.)
              </h6>

              <div class="d-flex align-items-center w-100">
                <div class="custom-control custom-radio mr-3">
                  <input
                    type="radio"
                    id="lookupYearMakeModel"
                    name="lookup_type"
                    v-model="lookup_type"
                    class="custom-control-input"
                    value="ymm"
                  />
                  <label
                    class="custom-control-label font-weight-bolder"
                    for="lookupYearMakeModel"
                  >
                    Year/Make/Model
                  </label>
                </div>

                <div class="custom-control custom-radio">
                  <input
                    type="radio"
                    id="lookupVin"
                    name="lookup_type"
                    class="custom-control-input"
                    value="vin"
                    v-model="lookup_type"
                  />
                  <label
                    class="custom-control-label font-weight-bolder"
                    for="lookupVin"
                  >
                    VIN
                  </label>
                </div>
              </div>

              <!-- YEAR : START -->
              <div class="form-group col-md-12" v-show="!isLookUpByVIN">
                <label for="carfax_valuation_year">Year</label>
                <fieldset>
                  <div class="input-group">
                    <select
                      class="custom-select form-control"
                      v-model="form.year"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('year'),
                      }"
                      :title="errors.year"
                      id="carfax_valuation_year"
                      name="year"
                      @change="
                        lookUpByYearMakeModel('makes');
                        delete errors.year;
                      "
                    >
                      <option value="" selected disabled>
                        Select a year
                      </option>
                      <option
                        :value="available_year"
                        v-for="available_year in getAvailableYears"
                        :key="available_year"
                        v-text="available_year"
                      ></option>
                    </select>
                  </div>
                </fieldset>
              </div>
              <!-- YEAR : END -->

              <!-- Make : START -->
              <div class="form-group col-md-12" v-show="!isLookUpByVIN">
                <label for="carfax_valuation_make">Make</label>
                <fieldset>
                  <div class="input-group">
                    <select
                      class="custom-select form-control"
                      v-model="form.make"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('make'),
                      }"
                      :title="errors.make"
                      id="carfax_valuation_make"
                      name="make"
                      @change="
                        lookUpByYearMakeModel('models');
                        delete errors.make;
                      "
                    >
                      <option value="" selected disabled>
                        Select a make
                      </option>
                      <option
                        :value="available_make"
                        v-for="available_make in makes"
                        :key="available_make"
                        v-text="available_make"
                      ></option>
                    </select>
                  </div>
                </fieldset>
              </div>
              <!-- Make : END -->

              <!-- Models : START -->
              <div class="form-group col-md-12" v-show="!isLookUpByVIN">
                <label for="carfax_valuation_model">Model</label>
                <fieldset>
                  <div class="input-group">
                    <select
                      class="custom-select form-control"
                      v-model="form.model"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('model'),
                      }"
                      :title="errors.model"
                      id="carfax_valuation_model"
                      name="model"
                      @change="delete errors.model"
                    >
                      <option value="" selected disabled>
                        Select a model
                      </option>
                      <option
                        :value="available_model"
                        v-for="available_model in models"
                        :key="available_model"
                        v-text="available_model"
                      ></option>
                    </select>
                  </div>
                </fieldset>
              </div>
              <!-- Models : END -->

              <!-- VIN : START -->
              <div class="form-group col-md-12" v-show="isLookUpByVIN">
                <label for="carfax_valuation_vin">VIN</label>
                <input
                  id="carfax_valuation_vin"
                  type="text"
                  class="form-control"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('vin'),
                  }"
                  @keyup="delete errors.vin"
                  :title="errors.vin"
                  v-model="form.vin"
                  placeholder="VIN"
                  v-uppercase
                />
              </div>
              <!-- VIN : END -->
              <!-- POSTAL CODE : START -->
              <div class="form-group col-md-6">
                <label for="carfax_valuation_postal_code">Postal Code</label>
                <input
                  id="carfax_valuation_postal_code"
                  type="text"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('postal_code'),
                  }"
                  @keyup="delete errors.postal_code"
                  :title="errors.postal_code"
                  v-model="form.postal_code"
                  class="form-control"
                  placeholder="Postal Code"
                  v-uppercase
                />
              </div>
              <!-- POSTAL CODE : END -->
              <!-- ODOMETER : START -->
              <div class="form-group col-md-6">
                <label for="carfax_valuation_odometer">Odometer</label>
                <input
                  id="carfax_valuation_odometer"
                  type="text"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('odometer'),
                  }"
                  @keyup="delete errors.odometer"
                  :title="errors.odometer"
                  v-model="form.odometer"
                  class="form-control"
                  placeholder="Odometer"
                />
              </div>
              <!-- ODOMETER : END -->
            </div>
            <!-- STEP 1 : ENTER INFORMATION : TRIM SELECTION API : END -->
          </form>

          <form v-show="current_step === 1">
            <!-- STEP 2 : ENTER INFORMATION : TRIM PROPERTIES : START -->
            <div class="form-row">
              <!-- Vehicle information summary -->
              <div class="form-group col-md-12 mb-1">
                <div class="border rounded bg-white p-1">
                  <div class="row no-gutters text-center text-md-left">
                    <div class="col-md-4 mb-1 mb-md-0">
                      <small class="text-muted d-block">{{
                        getLookUpTypeLabel
                      }}</small>
                      <strong
                        id="carfax_valuation_display_vin"
                        class="text-dark"
                      >
                        {{ getLookUpTypeTitle.toUpperCase() }}
                      </strong>
                    </div>

                    <div class="col-md-4 mb-1 mb-md-0">
                      <small class="text-muted d-block">Odometer</small>
                      <strong
                        id="carfax_valuation_display_odometer"
                        class="text-dark"
                      >
                        {{ renderedOdometer }}
                      </strong>
                    </div>

                    <div class="col-md-4">
                      <small class="text-muted d-block">Postal Code</small>
                      <strong
                        id="carfax_valuation_display_postal_code"
                        class="text-dark"
                      >
                        {{ form.postal_code.toUpperCase() }}
                      </strong>
                    </div>
                  </div>
                </div>
              </div>
              <!-- TRIM : START -->
              <div class="form-group col-md-12">
                <label for="carfax_valuation_trim">Trim</label>
                <fieldset>
                  <div class="input-group">
                    <select
                      class="custom-select form-control"
                      v-model="form.trim"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('trim'),
                      }"
                      :title="errors.trim"
                      id="carfax_valuation_trim"
                      name="trim"
                      @change="
                        lookUpByTrim();
                        delete errors.trim;
                      "
                    >
                      <option value="" selected disabled>
                        Select a trim
                      </option>
                      <option
                        :value="available_trim"
                        v-for="available_trim in trims"
                        :key="available_trim"
                        v-text="available_trim"
                      ></option>
                    </select>
                  </div>
                </fieldset>
              </div>
              <!-- TRIM : END -->

              <!-- ENGINE LABEL : START -->
              <div
                class="form-group col-md-12"
                v-show="getEngineLabels.length > 0"
              >
                <label for="carfax_valuation_engine_option">Engine</label>
                <fieldset>
                  <div class="input-group">
                    <select
                      class="custom-select form-control"
                      v-model="form.engine"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('engine'),
                      }"
                      :title="errors.engine"
                      id="carfax_valuation_engine_option"
                      name="engine"
                      @change="
                        changeDefaultAttributes();
                        delete errors.engine;
                      "
                    >
                      <option value="" selected disabled>
                        Select an engine
                      </option>
                      <option
                        :value="available_engine_option"
                        v-for="available_engine_option in getEngineLabels"
                        :key="`engine_${available_engine_option}`"
                        v-text="available_engine_option"
                      ></option>
                    </select>
                  </div>
                </fieldset>
              </div>
              <!-- ENGINE LABEL : END -->

              <!-- FUEL TYPE : START -->
              <div
                class="form-group col-md-12"
                v-show="getFuelTypeOptions.length > 0"
              >
                <label for="carfax_valuation_fuel_type_option">Fuel Type</label>
                <fieldset>
                  <div class="input-group">
                    <select
                      class="custom-select form-control"
                      v-model="form.fuel_type"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('fuel_type'),
                      }"
                      :title="errors.fuel_type"
                      id="carfax_valuation_fuel_type_option"
                      name="fuel_type"
                      @change="delete errors.fuel_type"
                    >
                      <option value="" selected disabled>
                        Select a fuel type
                      </option>
                      <option
                        :value="available_fuel_type"
                        v-for="available_fuel_type in getFuelTypeOptions"
                        :key="`fuel_type_${available_fuel_type}`"
                        v-text="available_fuel_type"
                      ></option>
                    </select>
                  </div>
                </fieldset>
              </div>
              <!-- FUEL TYPE : END -->

              <!-- DRIVETRAIN : START -->
              <div
                class="form-group col-md-12"
                v-show="getDriveTrainOptions.length > 0"
              >
                <label for="carfax_valuation_drivetrain_option"
                  >Drivetrain</label
                >
                <fieldset>
                  <div class="input-group">
                    <select
                      class="custom-select form-control"
                      v-model="form.drivetrain"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('drivetrain'),
                      }"
                      :title="errors.drivetrain"
                      id="carfax_valuation_drivetrain_option"
                      name="drivetrain"
                      @change="delete errors.drivetrain"
                    >
                      <option value="" selected disabled>
                        Select a drivetrain
                      </option>
                      <option
                        :value="available_drivetrain"
                        v-for="available_drivetrain in getDriveTrainOptions"
                        :key="`drivetrain_${available_drivetrain}`"
                        v-text="available_drivetrain"
                      ></option>
                    </select>
                  </div>
                </fieldset>
              </div>
              <!-- DRIVETRAIN : END -->

              <!-- TRANSMISSION : START -->
              <div
                class="form-group col-md-12"
                v-show="getTransmissionOptions.length > 0"
              >
                <label for="carfax_valuation_transmission_option"
                  >Transmission</label
                >
                <fieldset>
                  <div class="input-group">
                    <select
                      class="custom-select form-control"
                      v-model="form.transmission"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('transmission'),
                      }"
                      :title="errors.transmission"
                      id="carfax_valuation_transmission_option"
                      name="transmission"
                      @change="delete errors.transmission"
                    >
                      <option value="" selected disabled>
                        Select a transmission
                      </option>
                      <option
                        :value="available_transmission"
                        v-for="available_transmission in getTransmissionOptions"
                        :key="`transmission_${available_transmission}`"
                        v-text="available_transmission"
                      ></option>
                    </select>
                  </div>
                </fieldset>
              </div>
              <!-- TRANSMISSION : END -->
            </div>
            <!-- STEP 2 : ENTER INFORMATION : TRIM PROPERTIES : END -->
          </form>

          <!-- VALUATION STEP : START -->
          <div class="valuation-result-step" v-show="current_step === 2">
            <!-- Vehicle header -->
            <div class="valuation-vehicle-header mb-2">
              <div
                class="d-flex align-items-center flex-wrap mb-25 justify-content-between"
              >
                <h5 class="font-weight-bold mb-0 mr-1">
                  {{ vehicle.year }} {{ vehicle.make }} {{ vehicle.model }}
                  {{ form.trim }}
                </h5>

                <button
                  @click.prevent="resetFormToInitialPage"
                  class="font-weight-bold btn btn-light-primary text-right"
                >
                  <i
                    class="bx bxs-pencil pr-1"
                    style="vertical-align: center;"
                  ></i>
                  Edit
                </button>
              </div>
              <div
                class="text-dark text-dark font-weight-bolder my-25"
                v-show="isLookUpByVIN"
              >
                {{ form.vin.toUpperCase() }}
              </div>

              <div class="text-dark">
                {{ form.engine }}, {{ form.fuel_type }}, {{ form.drivetrain }},
                {{ form.transmission }}
                <span class="mx-50">|</span>
                {{ renderedOdometer }}
                <span class="mx-50">|</span>
                {{ form.postal_code }}
              </div>
            </div>

            <!-- Tabs -->
            <ul
              class="nav nav-tabs nav-justified valuation-tabs mb-2"
              role="tablist"
            >
              <li v-show="hasListingRange" class="nav-item">
                <a
                  class="nav-link active"
                  id="listing-tab"
                  data-toggle="tab"
                  href="#listing-content"
                  role="tab"
                  aria-controls="listing-content"
                  aria-selected="false"
                >
                  Listing
                </a>
              </li>
              <li v-show="hasRetailSaleRange" class="nav-item">
                <a
                  class="nav-link"
                  id="retail-tab"
                  data-toggle="tab"
                  href="#retail-content"
                  role="tab"
                  aria-controls="retail-content"
                  aria-selected="false"
                >
                  Retail
                </a>
              </li>
              <li v-show="hasWholeSaleRange" class="nav-item">
                <a
                  class="nav-link"
                  id="wholesale-tab"
                  data-toggle="tab"
                  href="#wholesale-content"
                  role="tab"
                  aria-controls="wholesale-content"
                  aria-selected="false"
                >
                  Wholesale
                </a>
              </li>
              <li v-show="hasTradeInRange" class="nav-item">
                <a
                  class="nav-link"
                  id="trade-in-tab"
                  data-toggle="tab"
                  href="#trade-in-content"
                  role="tab"
                  aria-controls="trade-in-content"
                  aria-selected="true"
                >
                  Trade-In
                </a>
              </li>

              <li v-show="hasPrivateSaleRange" class="nav-item">
                <a
                  class="nav-link"
                  id="private-tab"
                  data-toggle="tab"
                  href="#private-content"
                  role="tab"
                  aria-controls="private-content"
                  aria-selected="false"
                  style="min-width: 150px;"
                >
                  Private Sale
                </a>
              </li>
            </ul>

            <div class="tab-content">
              <!-- Trade-in tab -->
              <div
                class="tab-pane fade"
                id="trade-in-content"
                role="tabpanel"
                aria-labelledby="trade-in-tab"
              >
                <div class="row">
                  <!-- Estimated trade-in range -->
                  <div class="col-md-12 mb-2">
                    <div class="card valuation-info-card h-100 mb-0">
                      <div class="card-body">
                        <h6 class="font-weight-bold mb-1">
                          Estimated Trade-in Range
                        </h6>

                        <p class="text-dark mb-2">
                          Based on current market data, this is the estimated
                          price range you can expect when trading in this
                          vehicle at a dealership.
                        </p>

                        <div class="valuation-amount-box">
                          <span class="valuation-amount">
                            {{ geTradeInRange }}
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Selling tab -->
              <div
                class="tab-pane fade"
                id="private-content"
                role="tabpanel"
                aria-labelledby="private-tab"
              >
                <div class="row">
                  <!-- Private Sale -->
                  <div class="col-md-12 mb-2">
                    <div class="card valuation-info-card h-100 mb-0">
                      <div class="card-body">
                        <h6 class="font-weight-bold mb-1">
                          Private Sale
                        </h6>

                        <p class="text-dark mb-2">
                          This estimated price range reflects private-party
                          transactions between individual buyers and sellers.
                        </p>

                        <div class="valuation-amount-box">
                          <span class="valuation-amount">
                            {{ getPrivateRange }}
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- WHOLESALE TAB : START -->
              <div
                class="tab-pane fade"
                id="wholesale-content"
                role="tabpanel"
                aria-labelledby="wholesale-tab"
              >
                <div class="row">
                  <!-- Wholesale Price -->
                  <div class="col-md-12 mb-2">
                    <div class="card valuation-info-card h-100 mb-0">
                      <div class="card-body">
                        <h6 class="font-weight-bold mb-1">
                          Wholesale Price
                        </h6>

                        <p class="text-dark mb-2">
                          This estimated wholesale price range is based on
                          current dealer-to-dealer market conditions for this
                          vehicle.
                        </p>

                        <div class="valuation-amount-box">
                          <span class="valuation-amount">
                            {{ getWholeSaleRange }}
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- WHOLESALE TAB : END -->

              <!-- RETAIL TAB : START -->
              <div
                class="tab-pane fade"
                id="retail-content"
                role="tabpanel"
                aria-labelledby="retail-tab"
              >
                <div class="row">
                  <!-- Retail Price -->
                  <div class="col-md-12 mb-2">
                    <div class="card valuation-info-card h-100 mb-0">
                      <div class="card-body">
                        <h6 class="font-weight-bold mb-1">
                          Retail Price
                        </h6>

                        <p class="text-dark mb-2">
                          This estimated retail price range is based on current
                          market conditions for this used vehicle.
                        </p>

                        <div class="valuation-amount-box">
                          <span class="valuation-amount">
                            {{ getRetailRange }}
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- RETAIL TAB : END -->

              <!-- LISTING TAB : START -->
              <div
                class="tab-pane fade show active"
                id="listing-content"
                role="tabpanel"
                aria-labelledby="listing-tab"
              >
                <div class="row">
                  <!-- Listing Price -->
                  <div class="col-md-12 mb-2">
                    <div class="card valuation-info-card h-100 mb-0">
                      <div class="card-body">
                        <h6 class="font-weight-bold mb-1">
                          Listing Price
                        </h6>

                        <p class="text-dark mb-2">
                          Based on current market data,this is the suggested
                          listing price range for this vehicle.
                        </p>

                        <div class="valuation-amount-box">
                          <span class="valuation-amount">
                            {{ getListingRange }}
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- LISTING TAB : END -->
            </div>
          </div>
          <!-- VALUATION STEP : END -->
        </div>
        <!-- Modal Body : END -->
        <!-- Modal Footer : START -->
        <div class="modal-footer">
          <button
            type="button"
            data-dismiss="modal"
            v-show="current_step !== 1"
            class="btn btn-secondary"
          >
            Close
          </button>
          <button
            type="button"
            @click="resetFormToInitialPage"
            v-show="current_step === 1"
            class="btn btn-secondary"
          >
            Back
          </button>
          <button
            type="button"
            class="btn btn-primary"
            v-show="current_step === 0"
            @click.prevent="lookUpByVINorYearMakeAndModel"
          >
            <i
              :class="is_loading ? 'bx-spin' : 'd-none'"
              class="bx bx-loader-alt"
              style="vertical-align: text-bottom;"
            ></i>
            <span class="ml-25">Continue</span>
          </button>

          <button
            :disabled="!hasAllRequiredValues"
            type="button"
            class="btn btn-primary"
            v-show="current_step === 1"
            @click.prevent="valuation"
          >
            <i
              :class="is_loading ? 'bx-spin' : 'd-none'"
              class="bx bx-loader-alt"
              style="vertical-align: text-bottom;"
            ></i>
            <span class="ml-25">Valuation</span>
          </button>
        </div>
        <!-- Modal Footer : END -->
      </div>
    </div>
  </div>
  <!-- Quick Market Valuation : END  -->
</template>
<script>
import Toast from "../../library/Toast";
export default {
  name: "CarfaxValuationModel",
  props: {
    vin: {
      type: String,
      required: false,
      default: "",
    },
    odometer: {
      type: String,
      required: false,
      default: "",
    },
    location_name: {
      type: String,
      required: false,
      default: "",
    },
  },
  data() {
    return {
      current_step: 0,
      form: {
        year: "",
        make: "",
        model: "",
        vin: "",
        postal_code: "",
        odometer: "",
        trim: "",
        engine: "",
        fuel_type: "",
        drivetrain: "",
        transmission: "",
      },
      is_loading: false,
      errors: [],
      makes: [],
      models: [],
      trims: [],
      engine_options: [],
      vehicle: {
        year: "",
        make: "",
        model: "",
      },
      lookup_type: "vin",
      valuations: [],
    };
  },
  watch: {
    vin(updated_value, previous_value) {
      if (updated_value !== previous_value) {
        if (updated_value) {
          this.form.vin = updated_value;
        }
      }
    },
    odometer(updated_value, previous_value) {
      if (updated_value !== previous_value) {
        if (updated_value) {
          this.form.odometer = updated_value;
        }
      }
    },
    location_name(updated_value, previous_value) {
      if (updated_value !== previous_value) {
        this.getCurrentLocationByName(updated_value);
      }
    },
  },
  computed: {
    getAvailableYears() {
      const d = new Date();
      let current_year = d.getFullYear();
      const sequence = [];
      for (let i = 2000; i <= current_year; i++) {
        sequence.push(i);
      }

      return sequence;
    },
    isLookUpByVIN() {
      return this.lookup_type === "vin";
    },
    getLookUpTypeTitle() {
      return this.lookup_type === "vin"
        ? this.form.vin
        : `${this.form.year} \ ${this.form.make} \ ${this.form.model}`;
    },
    getLookUpTypeLabel() {
      return this.lookup_type === "vin" ? "VIN" : "Year/Make/Model";
    },
    getFieldListByLookUpType() {
      return this.lookup_type === "vin"
        ? [
            "vin",
            "postal_code",
            "odometer",
            "trim",
            "engine",
            "fuel_type",
            "drivetrain",
            "transmission",
          ]
        : [
            "year",
            "make",
            "model",
            "postal_code",
            "odometer",
            "trim",
            "engine",
            "fuel_type",
            "drivetrain",
            "transmission",
          ];
    },
    renderedOdometer() {
      return this.form.odometer == null || this.form.odometer == ""
        ? ""
        : `${new Intl.NumberFormat("en-US", {}).format(this.form.odometer)} KM`;
    },
    getEngineLabels() {
      return this.engine_options.map(
        (engine_option) => engine_option.EngineLabel,
      );
    },
    getSelectedEngineOptionAttributes() {
      return this.engine_options
        .filter(
          (engine_option) => engine_option.EngineLabel === this.form.engine,
        )
        .shift();
    },
    getFuelTypeOptions() {
      return (
        this.engine_options
          .filter(
            (engine_option) => engine_option.EngineLabel === this.form.engine,
          )
          .map((engine_option) => engine_option.FuelType) || []
      );
    },
    getDriveTrainOptions() {
      return (
        this.getSelectedEngineOptionAttributes?.DrivetrainOptions.map(
          (drivetrain_option) => drivetrain_option.DriveTrain,
        ) || []
      );
    },
    getTransmissionOptions() {
      return (
        this.getSelectedEngineOptionAttributes?.DrivetrainOptions.filter(
          (drivetrain_option) =>
            drivetrain_option.DriveTrain === this.form.drivetrain,
        ).shift()?.TransmissionOptions || []
      );
    },
    hasAllRequiredValues() {
      let {
        vin,
        odometer,
        trim,
        engine,
        fuel_type,
        drivetrain,
        transmission,
      } = this.form;

      return (
        vin &&
        odometer &&
        trim &&
        engine &&
        fuel_type &&
        drivetrain &&
        transmission
      );
    },
    hasRetailSaleRange() {
      for (const valuation of this.valuations) {
        if (valuation.valuationType == 1) {
          return true;
        }
      }

      return false;
    },
    getRetailRange() {
      for (const valuation of this.valuations) {
        if (valuation.valuationType == 1 && !valuation.isFloorValue) {
          return `${this.$helpers.moneyFormatter(
            valuation.minValuation,
          )} - ${this.$helpers.moneyFormatter(valuation.maxValuation)}`;
        }
      }

      return "";
    },
    hasWholeSaleRange() {
      for (const valuation of this.valuations) {
        if (valuation.valuationType == 2) {
          return true;
        }
      }

      return false;
    },
    getWholeSaleRange() {
      for (const valuation of this.valuations) {
        if (valuation.valuationType == 2 && !valuation.isFloorValue) {
          return `${this.$helpers.moneyFormatter(
            valuation.minValuation,
          )} - ${this.$helpers.moneyFormatter(valuation.maxValuation)}`;
        }
      }

      return "";
    },
    hasPrivateSaleRange() {
      for (const valuation of this.valuations) {
        if (valuation.valuationType == 3) {
          return true;
        }
      }

      return false;
    },
    getPrivateRange() {
      for (const valuation of this.valuations) {
        if (valuation.valuationType == 3 && !valuation.isFloorValue) {
          return `${this.$helpers.moneyFormatter(
            valuation.minValuation,
          )} - ${this.$helpers.moneyFormatter(valuation.maxValuation)}`;
        }
      }

      return "";
    },
    hasListingRange() {
      for (const valuation of this.valuations) {
        if (valuation.valuationType == 4) {
          return true;
        }
      }

      return false;
    },
    getListingRange() {
      for (const valuation of this.valuations) {
        if (valuation.valuationType == 4 && !valuation.isFloorValue) {
          return `${this.$helpers.moneyFormatter(
            valuation.minValuation,
          )} - ${this.$helpers.moneyFormatter(valuation.maxValuation)}`;
        }
      }

      return "";
    },
    hasTradeInRange() {
      for (const valuation of this.valuations) {
        if (valuation.valuationType == 5) {
          return true;
        }
      }

      return false;
    },
    geTradeInRange() {
      for (const valuation of this.valuations) {
        if (valuation.valuationType == 5 && !valuation.isFloorValue) {
          return `${this.$helpers.moneyFormatter(
            valuation.minValuation,
          )} - ${this.$helpers.moneyFormatter(valuation.maxValuation)}`;
        }
      }

      return "";
    },
  },
  methods: {
    resetFormToInitialPage() {
      this.current_step = 0;

      this.trims = [];
      this.makes = [];
      this.models = [];
      this.engine_options = [];
      this.valuations = [];

      this.form.trim = this.form.engine = this.form.fuel_type = this.form.drivetrain = this.form.transmission =
        "";
      this.form.year = this.form.make = this.form.model = "";
    },
    populateVehicleInformation(responseData) {
      if (responseData.hasOwnProperty("VehicleInformation")) {
        let { Year, Make, Model } = responseData.VehicleInformation;
        this.vehicle.year = Year;
        this.vehicle.make = Make;
        this.vehicle.model = Model;
      } else if (responseData.hasOwnProperty("vehicleInformation")) {
        let { year, make, model } = responseData.vehicleInformation;
        this.vehicle.year = year;
        this.vehicle.make = make;
        this.vehicle.model = model;
      }
    },
    changeDefaultAttributes() {
      if (this.form.engine) {
        this.form.fuel_type = this.getFuelTypeOptions?.[0] ?? "";
        this.form.drivetrain = this.getDriveTrainOptions?.[0] ?? "";
        this.form.transmission = this.getTransmissionOptions?.[0] ?? "";
      }
    },
    getCurrentLocationByName(location_name) {
      let formData = new FormData();
      formData.append("name", location_name);
      axios({
        method: "post",
        url: `${window.location.origin}/api/business/location/find-by-name`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          console.log(data);
          this.form.postal_code = data?.zipcode ?? "";
        })
        .catch((e) => {
          console.log(e);
        });
    },
    valuation() {
      const vm = this;
      this.is_loading = true;
      let formData = new FormData();
      for (const key in this.form) {
        if (!this.getFieldListByLookUpType.includes(key)) {
          continue;
        }

        if (this.form.hasOwnProperty(key) && this.form[key] != null) {
          formData.append(key, this.form[key]);
        }
      }

      axios({
        method: "post",
        url: `${window.location.origin}/api/business/carfax/valuations/lookup/valuation`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          this.populateVehicleInformation(data.responseData);
          this.valuations = data?.responseData?.marketRangeValuations ?? [];

          this.is_loading = false;
          this.errors = [];
          this.current_step = 2;
        })
        .catch(function(error) {
          vm.$data.errors = error.response.data;
          let error_message =
            error?.response?.data?.errors ??
            "There was error while fetching the report";

          error_message = error?.response?.data?.error ?? error_message;
          const toast = new Toast(error_message);
          toast.fire();
          toast.fail(error_message);
          toast.close();
          vm.$data.is_loading = false;
        });
    },
    lookUpByTrim() {
      this.is_loading = true;
      let formData = new FormData();
      for (const key in this.form) {
        if (!this.getFieldListByLookUpType.includes(key)) {
          continue;
        }

        if (this.form.hasOwnProperty(key) && this.form[key] != null) {
          formData.append(key, this.form[key]);
        }
      }

      let slug = this.isLookUpByVIN ? "vin/trim" : "year-make-model/trim";

      axios({
        method: "post",
        url: `${window.location.origin}/api/business/carfax/valuations/lookup/${slug}`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          this.engine_options = data?.ResponseData?.EngineOptions ?? [];
          this.populateVehicleInformation(data.ResponseData);
          this.is_loading = false;
          this.errors = [];
        })
        .catch(function(error) {
          vm.$data.errors = error.response.data;
          let error_message =
            error?.response?.data?.errors ??
            "There was error while performing lookup for engine options.";

          error_message = error?.response?.data?.error ?? error_message;
          const toast = new Toast(error_message);
          toast.fire();
          toast.fail(error_message);
          toast.close();
          vm.$data.is_loading = false;
        });
    },
    lookUpByVINorYearMakeAndModel() {
      const vm = this;
      this.is_loading = true;
      let formData = new FormData();
      for (const key in this.form) {
        if (!this.getFieldListByLookUpType.includes(key)) {
          continue;
        }

        if (this.form.hasOwnProperty(key) && this.form[key] != null) {
          formData.append(key, this.form[key]);
        }
      }

      let slug = this.isLookUpByVIN ? "vin" : "year-make-model";

      axios({
        method: "post",
        url: `${window.location.origin}/api/business/carfax/valuations/lookup/${slug}`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          this.trims = data?.ResponseData?.Trims ?? [];
          this.is_loading = false;
          this.errors = [];
          this.current_step = 1;
        })
        .catch(function(error) {
          vm.$data.errors = error.response.data;
          let error_message =
            error?.response?.data?.errors ??
            "There was error while performing lookup for trims.";

          error_message = error?.response?.data?.error ?? error_message;
          const toast = new Toast(error_message);
          toast.fire();
          toast.fail(error_message);
          toast.close();
          vm.$data.is_loading = false;
        });
    },
    lookUpByYearMakeModel(field) {
      const vm = this;
      this.is_loading = true;
      let formData = new FormData();
      for (const key in this.form) {
        if (["vin"].includes(key)) {
          continue;
        }

        if (this.form.hasOwnProperty(key) && this.form[key] != null) {
          formData.append(key, this.form[key]);
        }
      }

      const toast = new Toast(`Loading available ${field}...`);
      toast.fire();

      axios({
        method: "post",
        url: `${window.location.origin}/api/business/carfax/valuations/lookup/year-make-model`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          if (field === "makes") {
            this.models = [];
            this.makes = data?.ResponseData?.Makes ?? [];
          } else if (field === "models") {
            this.models = data?.ResponseData?.Models ?? [];
          }

          this.is_loading = false;
          this.errors = [];
          this.current_step = 0;

          toast.success(`Available ${field} updated.`);
          toast.close();
        })
        .catch(function(error) {
          vm.$data.errors = error.response.data;
          let error_message =
            error?.response?.data?.errors ??
            `There was error while performing lookup for ${field}.`;

          error_message = error?.response?.data?.error ?? error_message;
          toast.fail(error_message);
          toast.close();
          vm.$data.is_loading = false;
        });
    },
  },
  created() {
    this.getCurrentLocationByName("");
  },
  mounted() {
    const vm = this;

    jQuery("#carfaxValuationModal").on("hidden.bs.modal", function() {
      vm.resetFormToInitialPage();
    });
  },
};
</script>
<style lang="scss">
#carfaxValuationModal .avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
}

#carfaxValuationModal .avatar-content {
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
}

#carfaxValuationModal .card {
  border-color: #dfe3e7;
  border-radius: 0.5rem;
}

#carfaxValuationModal strong {
  font-size: 0.9rem;
  word-break: break-word;
}

.valuation-vehicle-header {
  width: 100%;
  padding-bottom: 0.75rem;
}

.valuation-vehicle-header h5 {
  color: #2c3e50;
}

.valuation-result-step {
  color: #1f2d3d;
}

.valuation-tabs {
  border-bottom: 1px solid #dfe3e7;
}

.valuation-tabs .nav-link {
  border: 0;
  color: #475f7b;
  font-weight: 500;
  padding: 0.85rem 1rem;
}

.valuation-tabs .nav-link.active {
  color: #1689f2;
  border-bottom: 2px solid #1689f2;
  background-color: transparent;
}

.valuation-info-card {
  border: 1px solid #dfe3e7;
  border-radius: 0.45rem;
  box-shadow: none;
}

.valuation-info-card .card-body {
  padding: 1.6rem 1.5rem;
}

.valuation-amount-box {
  background-color: #f1f3f8;
  border-radius: 0.45rem;
  padding: 1rem 1.25rem;
  display: inline-flex;
  align-items: center;
  max-width: 100%;
}

.valuation-amount {
  font-size: 1.7rem;
  font-weight: 700;
  color: #2f4054;
  letter-spacing: 0.3px;
}

.tax-saving-box {
  background-color: #e5f3ff;
  border-radius: 0.45rem;
  padding: 1rem 1.25rem;
  display: inline-flex;
  align-items: center;
  min-width: 100%;
  color: #1689f2;
}

.tax-saving-box i {
  font-size: 1.5rem;
}

.tax-saving-amount {
  font-size: 1.8rem;
  font-weight: 700;
}

.valuation-circle {
  position: absolute;
  width: 45px;
  height: 45px;
  background-color: rgba(255, 255, 255, 0.22);
  color: #fff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.6rem;
}

.valuation-circle-left {
  right: 205px;
  bottom: 25px;
}

.valuation-circle-right {
  right: 40px;
  top: 10px;
}

@media (max-width: 767.98px) {
  .valuation-result-step .btn-outline-primary {
    margin-top: 0.75rem;
  }

  .valuation-result-step > .d-flex {
    display: block !important;
  }

  .valuation-amount,
  .tax-saving-amount {
    font-size: 1.45rem;
  }

  .valuation-offer-banner {
    padding: 1.25rem;
  }
}
</style>
