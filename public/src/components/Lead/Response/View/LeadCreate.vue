<template>
  <div
    class="tab-pane active"
    id="lead-create"
    aria-labelledby="lead-create"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="lead-create-main">
      <div class="row match-height">
        <div class="col-12 col-xs-12 col-md-8">
          <div class="card">
            <div class="card-content">
              <div class="card-body pb-1 mx-25">
                <h4 class="card-title col-12">
                  {{ title }}
                </h4>
                <form class="needs-validation">
                  <div class="row">
                    <!-- Personal Details -->
                    <div class="col-xs-12 col-md-6">
                      <!-- first name -->
                      <div class="form-group">
                        <label class="text-danger" for="first-name"
                          >First Name <span>*</span></label
                        >
                        <input
                          type="text"
                          class="form-control"
                          id="first-name"
                          name="first_name"
                          v-model="form.first_name"
                          :class="{
                            'is-invalid': errors.hasOwnProperty('first_name'),
                          }"
                          @keyup="delete errors.first_name"
                          :title="errors.first_name"
                          aria-describedby="FirstName"
                          placeholder="First Name"
                        />
                        <small
                          id="first-name-error"
                          class="form-text invalid-feedback"
                          >{{ errors.first_name }}</small
                        >
                      </div>
                      <!-- end : first name -->

                      <!-- last name -->
                      <div class="form-group">
                        <label for="last-name">Last Name</label>
                        <input
                          type="text"
                          class="form-control"
                          id="last-name"
                          name="last_name"
                          v-model="form.last_name"
                          :class="{
                            'is-invalid': errors.hasOwnProperty('last_name'),
                          }"
                          @keyup="delete errors.last_name"
                          :title="errors.last_name"
                          aria-describedby="Last Name"
                          placeholder="Last Name"
                        />
                        <small
                          id="last-name-error"
                          class="form-text invalid-feedback"
                          >{{ errors.last_name }}</small
                        >
                      </div>
                      <!-- end : last name -->

                      <!-- email -->
                      <div class="form-group">
                        <label class="text-danger" for="email"
                          >Email <span>*</span></label
                        >
                        <input
                          type="email"
                          class="form-control"
                          id="email"
                          name="email"
                          v-model="form.email"
                          :class="{
                            'is-invalid': errors.hasOwnProperty('email'),
                          }"
                          @keyup="delete errors.email"
                          :title="errors.email"
                          aria-describedby="Email"
                          placeholder="Email"
                        />
                        <small
                          id="email-error"
                          class="form-text invalid-feedback"
                          >{{ errors.email }}</small
                        >
                      </div>
                      <!-- end : email -->

                      <!-- phone -->
                      <div class="form-group">
                        <label class="text-danger" for="phone"
                          >Mobile <span>*</span></label
                        >
                        <input
                          data-inputmask="'mask': '(999) 999-9999'"
                          data-inputmask-clearmaskonlostfocus="true"
                          type="tel"
                          class="form-control"
                          id="phone"
                          name="phone"
                          v-model="form.phone"
                          :class="{
                            'is-invalid': errors.hasOwnProperty('phone'),
                          }"
                          @keyup="delete errors.phone"
                          :title="errors.phone"
                          aria-describedby="Phone"
                          placeholder="Mobile"
                        />
                        <small
                          id="phone-error"
                          class="form-text invalid-feedback"
                          >{{ errors.phone }}</small
                        >
                      </div>
                      <!-- end : phone -->
                    </div>
                    <!-- END Personal Details -->
                    <!-- Lead Response Details such as source and assignee -->
                    <div class="col-xs-12 col-md-6">
                      <!-- source -->
                      <div class="form-group">
                        <label for="source">Source </label>
                        <v-select
                          id="lead-source"
                          placeholder="Source"
                          taggable
                          v-model="form.source"
                          clearable
                          :options="getLeadSources"
                        >
                        </v-select>

                        <small
                          id="source-error"
                          class="form-text invalid-feedback"
                        ></small>
                      </div>
                      <!-- end : source -->

                      <!-- status -->
                      <div class="form-group">
                        <label for="status">Status</label>
                        <v-select
                          id="lead-status"
                          placeholder="Status"
                          taggable
                          v-model="form.status"
                          clearable
                          :options="getLeadStatuses"
                        >
                        </v-select>
                        <small
                          id="status-error"
                          class="form-text invalid-feedback"
                        ></small>
                      </div>
                      <!-- end : status -->

                      <!-- status -->
                      <div class="form-group">
                        <label for="assignee">Assignee </label>
                        <select
                          id="assignee"
                          class="custom-select"
                          name="assignee"
                          v-model="form.assignee"
                        >
                          <option disabled value="">
                            -- Select Assignee --
                          </option>
                          <option
                            :value="user.user_id"
                            v-for="user in allUsers"
                            :key="user.user_id + '_assignee'"
                            v-text="user.name"
                          ></option>
                        </select>

                        <small
                          id="assignee-error"
                          class="form-text invalid-feedback"
                        ></small>
                      </div>
                      <!-- end : status -->

                      <!-- inventory_id -->
                      <div class="form-group">
                        <label for="inventory_id">Inventory</label>
                        <v-select
                          id="inventories"
                          placeholder="Select Inventory"
                          label="label"
                          @change="changeInventory"
                          v-model="form.inventory_id"
                          :reduce="
                            (createIndexForInventories) =>
                              createIndexForInventories.inventory_id
                          "
                          clearable
                          :options="createIndexForInventories"
                        >
                        </v-select>

                        <small
                          id="inventory_id-error"
                          class="form-text invalid-feedback"
                        ></small>
                      </div>
                      <!-- end : inventory_id -->
                    </div>
                    <!-- EMD :Lead Response Details such as source and assignee  -->
                  </div>
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">More Details</h5>
                  </div>
                  <div class="row" v-show="!updateView">
                    <div class="col-xs-12 col-md-6">
                      <!-- home phone -->
                      <div class="form-group">
                        <label for="home_phone">Home Phone</label>
                        <input
                          data-inputmask="'mask': '(999) 999-9999'"
                          data-inputmask-clearmaskonlostfocus="true"
                          type="tel"
                          class="form-control"
                          id="home_phone"
                          name="home_phone"
                          aria-describedby="Home Phone"
                          placeholder="Home Phone"
                          v-model="form.home_phone"
                        />
                        <small
                          id="home-phone-error"
                          class="form-text invalid-feedback"
                        ></small>
                      </div>
                      <!-- end : home phone -->

                      <div class="col-xs-12 col-md-12 p-0">
                        <suggest-location :elementId="`lead_address`" />
                      </div>

                      <!-- address 1 -->
                      <div class="form-group">
                        <label for="address_1">Address 1</label>
                        <textarea
                          type="text"
                          class="form-control"
                          id="address_1"
                          name="address_1"
                          aria-describedby="Address 1"
                          placeholder="Address 1"
                          v-model="form.address_1"
                        ></textarea>
                        <small
                          id="address_1-error"
                          class="form-text invalid-feedback"
                        ></small>
                      </div>
                      <!-- end : address 1 -->

                      <!-- city -->
                      <div class="form-group">
                        <label for="city">City</label>
                        <input
                          type="text"
                          class="form-control"
                          id="city"
                          name="city"
                          aria-describedby="City"
                          placeholder="City"
                          v-model="form.city"
                        />
                        <small
                          id="city-error"
                          class="form-text invalid-feedback"
                        ></small>
                      </div>
                      <!-- end : city -->

                      <!-- postal_code -->
                      <div class="form-group">
                        <label for="postal_code">Postal Code / ZIP</label>
                        <input
                          type="text"
                          class="form-control"
                          id="postal_code"
                          name="postal_code"
                          aria-describedby="postal_code"
                          placeholder="Postal Code / ZIP"
                          v-model="form.postal_code"
                        />
                        <small
                          id="postal_code-error"
                          class="form-text invalid-feedback"
                        ></small>
                      </div>
                      <!-- end : postal_code -->

                      <!-- birthdate -->
                      <div class="form-group">
                        <label for="birthdate">Birth Date</label>
                        <input
                          type="date"
                          class="form-control"
                          id="birthdate"
                          name="birthdate"
                          aria-describedby="birthdate"
                          placeholder="Birth Date"
                          v-model="form.birthdate"
                        />
                        <small
                          id="birthdate-error"
                          class="form-text invalid-feedback"
                        ></small>
                      </div>
                      <!-- end : birthdate -->

                      <!-- driving_licence_expiry_date -->
                      <div class="form-group">
                        <label for="driving_licence_expiry_date"
                          >Driving Licence Expiry Date</label
                        >
                        <input
                          type="date"
                          class="form-control"
                          id="driving_licence_expiry_date"
                          name="driving_licence_expiry_date"
                          aria-describedby="driving_licence_expiry_date"
                          placeholder="DL Expiry Date"
                          v-model="form.driving_licence_expiry_date"
                        />
                        <small
                          id="driving_licence_expiry_date-error"
                          class="form-text invalid-feedback"
                        ></small>
                      </div>
                      <!-- end : driving_licence_expiry_date -->
                    </div>
                    <div class="col-xs-12 col-md-6">
                      <!-- work phone -->
                      <div class="form-group">
                        <label for="work_phone">Work Phone</label>
                        <input
                          data-inputmask="'mask': '(999) 999-9999'"
                          data-inputmask-clearmaskonlostfocus="true"
                          type="tel"
                          class="form-control"
                          id="work_phone"
                          name="work_phone"
                          aria-describedby="Work Phone"
                          placeholder="Work Phone"
                          v-model="form.work_phone"
                        />
                        <small
                          id="work-phone-error"
                          class="form-text invalid-feedback"
                        ></small>
                      </div>
                      <!-- end : work phone -->

                      <!-- address 2 -->
                      <div class="form-group">
                        <label for="address_2">Address 2</label>
                        <textarea
                          type="text"
                          class="form-control"
                          id="address_2"
                          name="address_2"
                          aria-describedby="Address 2"
                          placeholder="Address 2"
                          v-model="form.address_2"
                        ></textarea>
                        <small
                          id="address_2-error"
                          class="form-text invalid-feedback"
                        ></small>
                      </div>
                      <!-- end : address 2 -->

                      <!-- province -->
                      <div class="form-group">
                        <label for="province">Province / State</label>
                        <input
                          type="text"
                          class="form-control"
                          id="province"
                          name="province"
                          aria-describedby="province"
                          placeholder="Province / State"
                          v-model="form.province"
                        />
                        <small
                          id="province-error"
                          class="form-text invalid-feedback"
                        ></small>
                      </div>
                      <!-- end : province -->

                      <!-- country -->
                      <div class="form-group">
                        <label for="country">Country</label>
                        <input
                          type="text"
                          class="form-control"
                          id="country"
                          name="country"
                          aria-describedby="country"
                          placeholder="Country"
                          v-model="form.country"
                        />
                        <small
                          id="country-error"
                          class="form-text invalid-feedback"
                        ></small>
                      </div>
                      <!-- end : country -->

                      <!-- driving_licence -->
                      <div class="form-group">
                        <label for="driving_licence">Driving Licence</label>
                        <input
                          type="text"
                          class="form-control"
                          id="driving_licence"
                          name="driving_licence"
                          aria-describedby="driving_licence"
                          placeholder="Driving Licence"
                          v-model="form.driving_licence"
                        />
                        <small
                          id="driving_licence-error"
                          class="form-text invalid-feedback"
                        ></small>
                      </div>
                      <!-- end : driving_licence -->

                      <!-- gender -->
                      <div class="form-group">
                        <label for="gender">Gender</label>
                        <select
                          id="gender"
                          class="custom-select"
                          v-model="form.gender"
                          name="gender"
                        >
                          <option selected value="Prefer Not To Say / Other">
                            Prefer Not To Say / Other
                          </option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                        </select>

                        <small
                          id="gender-error"
                          class="form-text invalid-feedback"
                        ></small>
                      </div>
                      <!-- end : gender -->
                    </div>
                  </div>
                  <div class="divider divider-dashed" v-show="!updateView">
                    <div class="divider-text">Lead Comments</div>
                  </div>
                  <div class="row" v-show="!updateView">
                    <div class="col-xs-12 col-md-12">
                      <!-- lead comments -->
                      <div class="form-group">
                        <textarea
                          type="text"
                          class="form-control"
                          id="lead_comments"
                          name="lead_comments"
                          v-model="form.lead_comments"
                          aria-describedby="Lead Comments"
                          placeholder="Lead Comments"
                        ></textarea>
                        <small
                          id="lead_comments-error"
                          class="form-text invalid-feedback"
                        ></small>
                      </div>
                      <!-- end : lead comments -->
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- end of form -->
        <!-- start of inventory dispaly -->
        <div class="col-12 col-xs-12 col-md-4 p-1">
          <div class="card-group">
            <div class="card" v-show="website.vin != null && website.vin != ''">
              <div class="card-content">
                <router-link
                  tag="img"
                  style="cursor: pointer"
                  :src="imageUrl"
                  :to="{
                    name: 'inventory-update',
                    params: { id: form.inventory_id },
                  }"
                  class="card-img-top img-fluid"
                  exact
                >
                </router-link>
                <div class="card-body">
                  <h4 class="card-title">
                    {{ website.year }} {{ website.make }} {{ website.model }}
                    {{ website.trim }}
                  </h4>
                  <div class="row">
                    <div class="col-8 mb-1">
                      <span class="col-12 font-small-2 d-block text-center"
                        >VIN</span
                      >
                      <h6
                        class="col-12 text-capitalize text-bold-600 font-medium-1 m-0 border-bottom p-0 text-center"
                        v-text="website.vin"
                      ></h6>
                    </div>
                    <div class="col-4 mb-1">
                      <span class="col-12 font-small-2 d-block text-center"
                        >Stock No</span
                      >
                      <h6
                        class="col-12 text-capitalize text-bold-600 font-medium-1 m-0 border-bottom p-0 text-center"
                        v-text="website.stock_no"
                      ></h6>
                    </div>
                    <div class="col-3 mb-1">
                      <span class="col-12 font-small-2 d-block text-center"
                        >Year</span
                      >
                      <h6
                        class="col-12 text-capitalize text-bold-600 font-medium-1 m-0 border-bottom p-0 text-center"
                        v-text="website.year"
                      ></h6>
                    </div>
                    <div class="col-4 mb-1">
                      <span class="col-12 font-small-2 d-block text-center"
                        >Make</span
                      >
                      <h6
                        class="col-12 text-capitalize text-bold-600 font-medium-1 m-0 border-bottom p-0 text-center"
                        v-text="website.make"
                      ></h6>
                    </div>
                    <div class="col-4 mb-1">
                      <span class="col-12 font-small-2 d-block text-center"
                        >Model</span
                      >
                      <h6
                        class="col-12 text-capitalize text-bold-600 font-medium-1 m-0 border-bottom p-0 text-center"
                        v-text="website.model"
                      ></h6>
                    </div>
                    <div class="col-6 mb-1">
                      <span class="col-12 font-small-2 d-block text-center"
                        >Trim</span
                      >
                      <h6
                        class="col-12 text-capitalize text-bold-600 font-medium-1 m-0 border-bottom p-0 text-center"
                        v-text="website.trim"
                      ></h6>
                    </div>
                    <div class="col-6 mb-1">
                      <span class="col-12 font-small-2 d-block text-center"
                        >Odometer</span
                      >
                      <h6
                        class="col-12 text-capitalize text-bold-600 font-medium-1 m-0 border-bottom p-0 text-center"
                      >
                        {{ website.odometer }} {{ website.km_miles }}
                      </h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- end of inventory display -->
      </div>
    </section>
  </div>
</template>

<script>
import Inputmask from "inputmask";
import { mapGetters, mapActions } from "vuex";
import vSelect from "vue-select";
import Toast from "../../../../library/Toast";
import SuggestLocation from "../../../WebComponents/SuggestLocation.vue";

export default {
  props: {
    id: { required: false },
    allUsers: { required: true },
    app_metadata: { required: true },
  },
  name: "LeadCreate",
  data() {
    return {
      errors: [],
      inventories: [],
      website: {
        placeholder: "",
        image_url: "",
        vin: "",
        year: "",
        make: "",
        model: "",
        trim: "",
        stock_no: "",
        odometer: "",
      },
      form: {
        first_name: "",
        last_name: "",
        phone: "",
        email: "",
        inventory_id: "",
        source: "Walk-in",
        status: "New",
        stock_no: "",
        assignee: "",
        home_phone: null,
        work_phone: null,
        address_1: null,
        address_2: null,
        city: null,
        province: null,
        postal_code: null,
        country: null,
        birthdate: null,
        driving_licence: null,
        driving_licence_expiry_date: null,
        gender: "Prefer Not To Say / Other",
        lead_comments: null,
        form_id: 1,
      },
    };
  },
  components: {
    "v-select": vSelect,
    Inputmask,
    SuggestLocation,
  },
  watch: {
    imageUrl: function (newVal, oldVal) {
      console.log("");
    },
  },
  methods: {
    ...mapActions("leadResponse", ["getLeadConfigurations"]),
    getPlaceholder() {
      axios({
        method: "get",
        url: window.location.origin + "/api/website/image/placeholder",
      }).then(({ data }) => {
        this.website.placeholder = data.image_url;
      });
    },
    getInventory(inventory_id) {
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/website/inventory/show/" +
          inventory_id,
      }).then(({ data }) => {
        this.website.vin = data.vin;
        this.website.year = data.year;
        this.website.make = data.make;
        this.website.model = data.model;
        this.website.trim = data.trim;
        this.website.stock_no = data.stock_no;
        this.website.odometer = `${data.odometer} ${data.km_miles}`;
        this.form.stock_no = data.stock_no;
      });
    },
    getImage(inventory_id) {
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/website/inventory/images/" +
          inventory_id,
      }).then(({ data }) => {
        if (data.hasOwnProperty("gallery")) {
          if (data["gallery"].length > 0) {
            this.website.image_url = data["gallery"][0]["image_url"];
          } else {
            this.website.image_url = "";
          }
        }
      });
    },
    getAllInventoriesInStock() {
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/website/inventory?zm_status[]=instock&paginate=false&column=year&order=DESC",
      }).then(({ data }) => {
        this.inventories = data.inventory;
      });
    },
    findByStockNumber(stock_no) {
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/website/inventory?zm_stock_no[]=" +
          stock_no,
      })
        .then(({ data }) => {
          if (data.hasOwnProperty("inventory")) {
            if (data["inventory"].hasOwnProperty("0")) {
              this.website.vin = data["inventory"]["0"].vin;
              this.website.year = data["inventory"]["0"].year;
              this.website.make = data["inventory"]["0"].make;
              this.website.model = data["inventory"]["0"].model;
              this.website.trim = data["inventory"]["0"].trim;
              this.website.stock_no = data["inventory"]["0"].stock_no;
              this.form.stock_no = data["inventory"]["0"].stock_no;
              this.form.inventory_id = data["inventory"]["0"].inventory_id;
              if (data["inventory"]["0"].image_urls != null) {
                this.website.image_url =
                  data["inventory"]["0"].image_urls.split(", ")[0];
              }
            }
          }
        })
        .catch(() => {});
    },
    getLeadResponse(id) {
      const vm = this;
      axios({
        method: "get",
        url: window.location.origin + "/api/leads/responses/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;
          for (const key in this.form) {
            if (
              this.form.hasOwnProperty(key) &&
              data.hasOwnProperty(key) &&
              key != "inventory_id"
            ) {
              this.form[key] = data[key];
            }
          }
          if (data.assignee == null) {
            this.form["assignee"] = "";
          }
          if (data.stock_no != "" && data.stock_no != null) {
            this.findByStockNumber(data.stock_no);
          }
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the lead";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
  },
  computed: {
    ...mapGetters("leadResponse", ["getLeadSources", "getLeadStatuses"]),
    createIndexForInventories() {
      if (this.inventories.length > 0) {
        for (var index in this.inventories) {
          // this.inventories[index].label = "Hello World";
          let label =
            this.inventories[index].year != "" &&
            this.inventories[index].year != null
              ? `${this.inventories[index].year} `
              : "";

          label +=
            this.inventories[index].make != "" &&
            this.inventories[index].make != null
              ? `${this.inventories[index].make} `
              : "";

          label +=
            this.inventories[index].model != "" &&
            this.inventories[index].model != null
              ? `${this.inventories[index].model} `
              : "";

          label +=
            this.inventories[index].stock_no != "" &&
            this.inventories[index].stock_no != null
              ? `< ${this.inventories[index].stock_no} >`
              : "";

          label +=
            this.inventories[index].vin != "" &&
            this.inventories[index].vin != null
              ? `< ${this.inventories[index].vin} >`
              : "";

          this.inventories[index].label = label;
        }

        this.$forceUpdate();
      }
      return this.inventories;
    },
    imageUrl() {
      let image_url =
        this.website.image_url == ""
          ? this.website.placeholder
          : this.website.image_url;

      return this.$helpers.parseAbsoluteOrRelativeUrl(
        window.global.cdn,
        image_url
      );
    },
    changeInventory() {
      if (this.form.inventory_id != "") {
        this.getInventory(this.form.inventory_id);
        this.getImage(this.form.inventory_id);
      }
    },
    updateView() {
      return (
        this.$route.name == "lead-response-update" && this.$route.params.id
      );
    },
    title() {
      return this.$route.name == "lead-response-update"
        ? "Update The Lead"
        : "Create New Lead";
    },
  },
  created() {
    this.getLeadConfigurations();
  },
  mounted() {
    Inputmask({
      mask: "(999) 999-9999",
      autoUnmask: true,
      onBeforePaste: function (pastedValue, opts) {
        pastedValue = pastedValue.toLowerCase();
        return pastedValue.replace(/\D/g, "");
      },
    }).mask(document.getElementById("phone"));
    Inputmask({
      mask: "(999) 999-9999",
      autoUnmask: true,
      onBeforePaste: function (pastedValue, opts) {
        pastedValue = pastedValue.toLowerCase();
        return pastedValue.replace(/\D/g, "");
      },
    }).mask(document.getElementById("home_phone"));
    Inputmask({
      mask: "(999) 999-9999",
      autoUnmask: true,
      onBeforePaste: function (pastedValue, opts) {
        pastedValue = pastedValue.toLowerCase();
        return pastedValue.replace(/\D/g, "");
      },
    }).mask(document.getElementById("work_phone"));
    this.getPlaceholder();
    this.getAllInventoriesInStock();

    const vm = this;
    Event.listen("save_lead", (data) => {
      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && this.form[key] != null) {
          formData.append(key, this.form[key]);
        }
      }

      let request_url = window.location.origin;
      request_url = !this.updateView
        ? `${request_url}/api/business/leads/responses/create`
        : `${request_url}/api/business/leads/responses/update/${this.id}`;
      let success_message = !this.updateView
        ? `Lead created successfully`
        : `Lead updated successfully.`;

      let message = !this.updateView
        ? `Creating lead ...`
        : `Updating lead ...`;

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

          setTimeout(function () {
            Event.fire("save_complete", data);
          }, 500);
        })
        .catch(function (error) {
          vm.$data.errors = error.response.data.errors;
          let error_message = "There were some errors while submitting form.";

          toast.fail(error_message);
          toast.close();

          setTimeout(function () {
            Event.fire("save_complete", null);
          }, 2000);
        });
    });

    if (this.updateView) {
      this.getLeadResponse(this.id);
    } else {
      this.form.assignee = this.app_metadata.id;
    }

    Event.listen("autopopulate_location_lead_address", (data) => {
      if (data.hasOwnProperty("place")) {
        vm.$data.form.country = data.place.country;
        vm.$data.form.postal_code = data.place.postal_code;
        vm.$data.form.province = data.place.province;
        vm.$data.form.city = data.place.city;

        if (data.place.street !== "") {
          vm.$data.form.address_1 = `${data.place.street_number} ${data.place.street}`;
        } else {
          vm.$data.form.address_1 = data.place.street;
        }
      }
    });
  },
  beforeDestroy() {
    Event.vue.$off("save_complete", null);
    Event.vue.$off("save_lead", null);
    Event.vue.$off("autopopulate_location_lead_address", null);
  },
};
</script>

<style lang="scss" scoped></style>
