<template>
  <div
    class="tab-pane"
    id="deal-tradein-panel"
    aria-labelledby="Trade In Tab"
    role="tabpanel"
  >
    <section id="deal-trade-in-section">
      <div class="row match-height">
        <div class="col-12 col-xs-12">
          <div class="card">
            <div class="card-content">
              <div class="card-body pb-1 mx-25">
                <h4 class="card-title col-12">Trade In Details</h4>
                <!-- vehicle information : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Vehicle Information</h5>
                  </div>
                  <div class="row">
                    <h6 class="col-xs-12 text-danger" style="font-size: 0.8em">
                      NOTE: This vehicle can be imported into the inventory when
                      the status of deal is changed to "Delivered"
                    </h6>
                    <div class="w-100"></div>
                    <!-- vin : START -->
                    <div class="col-md-2 col-xs-12">
                      <label class="text-danger">VIN *</label>
                    </div>
                    <div class="col-md-6 col-xs-12">
                      <input
                        id="trade-in-vin"
                        type="text"
                        placeholder="VIN"
                        v-model="form.vin"
                        class="form-control"
                        :class="{
                          'is-invalid': errors.hasOwnProperty('vin'),
                        }"
                        @keyup="delete errors.vin"
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
                    <div class="w-100"></div>
                    <!-- year : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="trade-in-year">Year</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="trade-in-year"
                            type="text"
                            placeholder="Year"
                            v-model="form.year"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('year'),
                            }"
                            @keyup="delete errors.year"
                            :title="errors.year"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-calendar-alt font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="trade-in-year-error"
                          class="form-text invalid-feedback"
                          >{{ errors.year }}</small
                        >
                      </div>
                    </div>
                    <!-- year : END -->

                    <!-- make : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="trade-in-make">Make</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="trade-in-make"
                            type="text"
                            placeholder="Make"
                            v-model="form.make"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('make'),
                            }"
                            @keyup="delete errors.make"
                            :title="errors.make"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-car font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="trade-in-make-error"
                          class="form-text invalid-feedback"
                          >{{ errors.make }}</small
                        >
                      </div>
                    </div>
                    <!-- make : END -->

                    <!-- model : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="trade-in-model">Model</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="trade-in-model"
                            type="text"
                            placeholder="Model"
                            v-model="form.model"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('model'),
                            }"
                            @keyup="delete errors.model"
                            :title="errors.model"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-cog font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="trade-in-model-error"
                          class="form-text invalid-feedback"
                          >{{ errors.model }}</small
                        >
                      </div>
                    </div>
                    <!-- model : END -->

                    <!-- trim : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="trade-in-trim">Trim</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="trade-in-trim"
                            type="text"
                            placeholder="Trim"
                            v-model="form.trim"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('trim'),
                            }"
                            @keyup="delete errors.trim"
                            :title="errors.trim"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-detail font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="trade-in-trim-error"
                          class="form-text invalid-feedback"
                          >{{ errors.trim }}</small
                        >
                      </div>
                    </div>
                    <!-- trim : END -->

                    <!-- odometer : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="trade-in-odometer">Odometer</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="trade-in-odometer"
                            type="text"
                            placeholder="Odometer"
                            v-model="form.odometer"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('odometer'),
                            }"
                            @keyup="delete errors.odometer"
                            :title="errors.odometer"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dashboard font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="trade-in-odometer-error"
                          class="form-text invalid-feedback"
                          >{{ errors.odometer }}</small
                        >
                      </div>
                    </div>
                    <!-- odometer : END -->

                    <!-- km_miles : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="trade-in-km_miles">Distance Type</label>
                        <select
                          id="trade-in-km_miles"
                          class="custom-select"
                          v-model="form.km_miles"
                          name="km_miles"
                          :class="{
                            'is-invalid': errors.hasOwnProperty('km_miles'),
                          }"
                          @keyup="delete errors.km_miles"
                          :title="errors.km_miles"
                        >
                          <option value="" selected disabled>Select</option>
                          <option value="KM">KM</option>
                          <option value="Miles">Miles</option>
                        </select>
                        <small
                          id="trade-in-km_miles-error"
                          class="form-text invalid-feedback"
                          >{{ errors.km_miles }}</small
                        >
                      </div>
                    </div>
                    <!-- km_miles : END -->

                    <!-- body_type : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="trade-in-body_type">Body Type</label>
                        <select
                          id="trade-in-body_type"
                          class="custom-select"
                          v-model="form.body_type"
                          name="body_type"
                          :class="{
                            'is-invalid': errors.hasOwnProperty('body_type'),
                          }"
                          @keyup="delete errors.body_type"
                          :title="errors.body_type"
                        >
                          <option value="" selected disabled>Select</option>
                          <option
                            :value="body_type"
                            v-for="body_type in body_types"
                            :key="body_type"
                            v-text="body_type"
                          ></option>
                        </select>
                        <small
                          id="trade-in-body_type-error"
                          class="form-text invalid-feedback"
                          >{{ errors.body_type }}</small
                        >
                      </div>
                    </div>
                    <!-- body_type : END -->

                    <!-- exterior_color : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="trade-in-exterior_color"
                          >Exterior Color</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="trade-in-exterior_color"
                            type="text"
                            placeholder="Exterior Color"
                            v-model="form.exterior_color"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'exterior_color',
                              ),
                            }"
                            @keyup="delete errors.exterior_color"
                            :title="errors.exterior_color"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-palette font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="trade-in-exterior_color-error"
                          class="form-text invalid-feedback"
                          >{{ errors.exterior_color }}</small
                        >
                      </div>
                    </div>
                    <!-- exterior_color : END -->

                    <!-- interior_color : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="trade-in-interior_color"
                          >Interior Color</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="trade-in-interior_color"
                            type="text"
                            placeholder="Interior Color"
                            v-model="form.interior_color"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'interior_color',
                              ),
                            }"
                            @keyup="delete errors.interior_color"
                            :title="errors.interior_color"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-palette font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="trade-in-interior_color-error"
                          class="form-text invalid-feedback"
                          >{{ errors.interior_color }}</small
                        >
                      </div>
                    </div>
                    <!-- interior_color : END -->
                  </div>
                </div>

                <!-- Vehicle Information Section : END -->
                <!-- trade value : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Trade Value</h5>
                  </div>
                  <div class="row">
                    <!-- trade_value : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="trade-in-trade_value">Trade Value</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="trade-in-trade_value"
                            type="text"
                            placeholder="Trade Value"
                            v-model="form.trade_value"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'trade_value',
                              ),
                            }"
                            @keyup="delete errors.trade_value"
                            :title="errors.trade_value"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="trade-in-trade_value-error"
                          class="form-text invalid-feedback"
                          >{{ errors.trade_value }}</small
                        >
                      </div>
                    </div>
                    <!-- trade_value : END -->

                    <!-- lien_value : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="trade-in-lien_value">Lien Value</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="trade-in-lien_value"
                            type="text"
                            placeholder="Lien Value"
                            v-model="form.lien_value"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('lien_value'),
                            }"
                            @keyup="delete errors.lien_value"
                            :title="errors.lien_value"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="trade-in-lien_value-error"
                          class="form-text invalid-feedback"
                          >{{ errors.lien_value }}</small
                        >
                      </div>
                    </div>
                    <!-- lien_value : END -->

                    <!-- trade_equity : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="trade-in-trade_equity">Trade Equity</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="trade-in-trade_equity"
                            type="text"
                            placeholder="Trade Equity"
                            v-model="form.trade_equity"
                            class="form-control"
                            readonly
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'trade_equity',
                              ),
                            }"
                            @keyup="delete errors.trade_equity"
                            :title="errors.trade_equity"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="trade-in-trade_equity-error"
                          class="form-text invalid-feedback"
                          >{{ errors.trade_equity }}</small
                        >
                      </div>
                    </div>
                    <!-- trade_equity : END -->
                  </div>
                </div>
                <!-- tarde value : END -->

                <!-- Other Section : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Other Details</h5>
                  </div>
                  <div class="row">
                    <!-- lien_holder : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="trade-in-lien_holder">Lien Holder</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="trade-in-lien_holder"
                            type="text"
                            placeholder="Lien Holder"
                            v-model="form.lien_holder"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'lien_holder',
                              ),
                            }"
                            @keyup="delete errors.lien_holder"
                            :title="errors.lien_holder"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-detail font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="trade-in-lien_holder-error"
                          class="form-text invalid-feedback"
                          >{{ errors.lien_holder }}</small
                        >
                      </div>
                    </div>
                    <!-- lien_holder : END -->
                  </div>
                </div>
                <!-- Other Section : END -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <v-i-n-decoder :elementId="'default'"></v-i-n-decoder>
  </div>
</template>
<script>
import Toast from "../../../library/Toast";
import VINDecoder from "../../WebComponents/VINDecoder";

export default {
  name: "DealTradeIn",
  props: {
    id: { required: true },
    retail_or_wholesale_flag: { required: true },
  },
  watch: {
    trade_in_id: function(newVal, oldVal) {
      let has_trade_in = this.$route.params.id && !isNaN(parseInt(newVal));
      if (has_trade_in) {
        Event.fire("has_trade_in_flag", has_trade_in);
      }
    },
    calculateTradeIn(n, o) {},
  },
  components: {
    VINDecoder,
  },
  data() {
    return {
      errors: [],
      form: {
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
      trade_in_id: null,
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
    };
  },
  computed: {
    isDealRetailOrWholesale() {
      return parseInt(this.retail_or_wholesale_flag);
    },
    calculateTradeIn: {
      get: function() {
        let trade_value =
          !isNaN(this.form.trade_value) && this.form.trade_value != ""
            ? this.form.trade_value
            : 0;
        this.form.trade_value = trade_value;

        trade_value = parseFloat(trade_value);

        let lien_value =
          !isNaN(this.form.lien_value) && this.form.lien_value != ""
            ? this.form.lien_value
            : 0;
        this.form.lien_value = lien_value;

        lien_value = parseFloat(lien_value);

        let trade_equity = trade_value - lien_value;
        this.form.trade_equity = trade_equity;

        Event.fire("change_lien_value", lien_value);
        Event.fire("change_trade_equity", trade_equity);
        Event.fire("change_trade_value", trade_value);
      },
    },
  },
  methods: {
    resetForm() {
      this.trade_in_id = null;

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
      };

      this.form = {
        ...form,
      };
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
    getDealTradeIn(event = false) {
      axios({
        method: "get",
        url: `${window.location.origin}/api/business/deals/${this.$route.params.id}/tradein`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      }).then(({ data }) => {
        this.trade_in_id = data.id;
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

          "trade_value",
          "lien_value",
          "trade_equity",
          "lien_holder",
        ];

        for (var property of properties_of_form) {
          this.form[property] = data[property];
        }

        if (data.vehicle_tags != null) {
          this.form.vehicle_tags = JSON.parse(data.vehicle_tags);
        }

        if (data.custom_features != null) {
          this.form.custom_features = JSON.parse(data.custom_features);
          this.custom_feature_count = this.form.custom_features.length;
        }

        if (data.technical_specification != null) {
          this.technical_specification = JSON.parse(
            data.technical_specification,
          );
        }

        if (data.generic_equipment != null) {
          this.generic_equipment = JSON.parse(data.generic_equipment);
        }

        if (data.standard != null) {
          this.standard = JSON.parse(data.standard);
        }

        if (data.syndication != null) {
          if (data.syndication != "") {
            this.form.syndication = [];
            this.form.syndication = JSON.parse(data.syndication);
          }
        }

        if (data.optional != null) {
          this.optional = JSON.parse(data.optional);
        }

        this.form.stock_date = null;

        let special_price_date = null;
        this.form.special_price_time = null;

        if (event) {
          Event.fire("saved_trade_in", null);
        }
      });
    },
    saveDealTradeIn() {
      const vm = this;
      //create data
      vm.$data.form.special_price_date = null;

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

      formData.append(
        "vehicle_tags",
        JSON.stringify(this.form["vehicle_tags"]),
      );
      formData.append(
        "custom_features",
        JSON.stringify(this.form["custom_features"]),
      );

      formData.append("syndication", JSON.stringify(this.form["syndication"]));

      if (Object.keys(this.technical_specification).length > 0) {
        formData.append(
          "technical_specification",
          JSON.stringify(this.technical_specification),
        );
      }

      if (Object.keys(this.generic_equipment).length > 0) {
        formData.append(
          "generic_equipment",
          JSON.stringify(this.generic_equipment),
        );
      }

      if (Object.keys(this.standard).length > 0) {
        formData.append("standard", JSON.stringify(this.standard));
      }

      if (Object.keys(this.optional).length > 0) {
        formData.append("optional", JSON.stringify(this.optional));
      }

      let request_url = window.location.origin;
      let update_request =
        this.$route.params.id && !isNaN(parseInt(this.trade_in_id));

      //success message setting
      let success_message = update_request
        ? `Trade In details have been updated.`
        : `Trade In details have been created.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/deals/${this.$route.params.id}/tradein/update/${this.trade_in_id}`
        : `${request_url}/api/business/deals/${this.$route.params.id}/tradein/create`;

      //message during request
      let request_message = update_request
        ? `Updating trade in details ...`
        : `Creating new trade in details ...`;

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
          vm.show_alert = false;
          vm.getDealTradeIn(true);

          setTimeout(function() {
            Event.fire("save_deal_trade_in_complete", data);
          }, 500);
        })
        .catch(function(error) {
          let update_request = vm.$route.params.id && !isNaN(vm.trade_in_id);

          let error_message = update_request
            ? "There were errors while updating the trade in details."
            : "There were some errors while creating new trade in details.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();
          Event.fire("save_deal_trade_in_complete", null);
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
        url:
          window.location.origin +
          "/api/business/configurations/active/syndication",
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
    // this.getLocations();
    this.getDealTradeIn(false);
    this.getInventoryConfigurations();
    this.getLocations();
    this.activeSyndication();
  },
  mounted() {
    const vm = this;
    Event.listen("save_deal_trade_in", (data) => {
      this.saveDealTradeIn();
    });

    Event.listen("update_worksheet_data", (data) => {
      if (parseInt(vm.retail_or_wholesale_flag) == 1) {
        vm.resetForm();
      }
    });

    Event.listen("load_available_data_for_a_vin_default", (data) => {
      vm.getVehicleInformation(data);
    });
  },
  beforeDestroy() {
    Event.vue.$off("save_deal_trade_in_complete", null);
    Event.vue.$off("saved_trade_in", null);
    Event.vue.$off("update_worksheet_data", null);
    Event.vue.$off("load_available_data_for_a_vin_default", null);
  },
};
</script>
