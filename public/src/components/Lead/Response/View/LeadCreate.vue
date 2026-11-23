<template>
  <div
    class="tab-pane active"
    id="lead-create"
    aria-labelledby="lead-create"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="lead-create-main" class="lc-page">
      <!-- Page header : START -->
      <div class="lc-header">
        <span class="lc-header-icon"
          ><i class="bx" :class="updateView ? 'bx-edit' : 'bx-user-plus'"></i
        ></span>
        <div>
          <h2 class="lc-title">{{ title }}</h2>
          <p class="lc-subtitle">
            {{
              updateView
                ? "Update the contact and lead details below."
                : "Add a new lead with their contact details and the vehicle they're interested in."
            }}
            Fields marked <span class="lc-required">*</span> are required.
          </p>
        </div>
      </div>
      <!-- Page header : END -->

      <div class="lc-layout">
        <form class="needs-validation lc-form" @submit.prevent>
          <!-- Contact information : START -->
          <div class="lc-card">
            <div class="lc-card-header">
              <span class="lc-card-icon lc-tone-blue"><i class="bx bx-id-card"></i></span>
              <div>
                <h4 class="lc-card-title">Contact Information</h4>
                <p class="lc-card-hint">How your team will reach this lead.</p>
              </div>
            </div>
            <div class="lc-grid lc-grid-4">
              <!-- first name -->
              <div class="lc-field">
                <label class="lc-label" for="first-name"
                  >First Name <span class="lc-required">*</span></label
                >
                <div class="lc-input-icon">
                  <i class="bx bx-user"></i>
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
                </div>
                <small id="first-name-error" class="lc-error" v-if="errors.first_name">{{
                  errors.first_name
                }}</small>
              </div>
              <!-- end : first name -->

              <!-- last name -->
              <div class="lc-field">
                <label class="lc-label" for="last-name">Last Name</label>
                <div class="lc-input-icon">
                  <i class="bx bx-user"></i>
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
                </div>
                <small id="last-name-error" class="lc-error" v-if="errors.last_name">{{
                  errors.last_name
                }}</small>
              </div>
              <!-- end : last name -->

              <!-- email -->
              <div class="lc-field">
                <label class="lc-label" for="email"
                  >Email <span class="lc-required">*</span></label
                >
                <div class="lc-input-icon">
                  <i class="bx bx-envelope"></i>
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
                    placeholder="name@example.com"
                  />
                </div>
                <small id="email-error" class="lc-error" v-if="errors.email">{{
                  errors.email
                }}</small>
              </div>
              <!-- end : email -->

              <!-- phone -->
              <div class="lc-field">
                <label class="lc-label" for="phone"
                  >Mobile <span class="lc-required">*</span></label
                >
                <div class="lc-input-icon">
                  <i class="bx bx-mobile-alt"></i>
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
                </div>
                <small id="phone-error" class="lc-error" v-if="errors.phone">{{
                  errors.phone
                }}</small>
              </div>
              <!-- end : phone -->
            </div>
          </div>
          <!-- Contact information : END -->

          <!-- Lead details : START -->
          <div class="lc-card">
            <div class="lc-card-header">
              <span class="lc-card-icon lc-tone-purple"><i class="bx bx-target-lock"></i></span>
              <div>
                <h4 class="lc-card-title">Lead Details</h4>
                <p class="lc-card-hint">Where the lead came from and who owns it.</p>
              </div>
            </div>
            <div class="lc-grid lc-grid-3">
              <!-- source -->
              <div class="lc-field">
                <label class="lc-label" for="source">Source</label>
                <v-select
                  id="lead-source"
                  placeholder="Source"
                  taggable
                  v-model="form.source"
                  clearable
                  :options="getLeadSources"
                >
                </v-select>
                <small id="source-error" class="form-text invalid-feedback"></small>
              </div>
              <!-- end : source -->

              <!-- status -->
              <div class="lc-field">
                <label class="lc-label" for="status">Status</label>
                <v-select
                  id="lead-status"
                  placeholder="Status"
                  taggable
                  v-model="form.status"
                  clearable
                  :options="getLeadStatuses"
                >
                </v-select>
                <small id="status-error" class="form-text invalid-feedback"></small>
              </div>
              <!-- end : status -->

              <!-- assignee -->
              <div class="lc-field">
                <label class="lc-label" for="assignee">Assignee</label>
                <div class="lc-input-icon">
                  <i class="bx bx-user-check"></i>
                  <select
                    id="assignee"
                    class="custom-select"
                    name="assignee"
                    v-model="form.assignee"
                  >
                    <option disabled value="">-- Select Assignee --</option>
                    <option
                      :value="user.user_id"
                      v-for="user in allUsers"
                      :key="user.user_id + '_assignee'"
                      v-text="user.name"
                    ></option>
                  </select>
                </div>
                <small id="assignee-error" class="form-text invalid-feedback"></small>
              </div>
              <!-- end : assignee -->

              <!-- inventory_id -->
              <div class="lc-field lc-span-full">
                <label class="lc-label" for="inventory_id">Inventory</label>
                <v-select
                  id="inventories"
                  placeholder="Search by year, make, model, stock # or VIN"
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
                <small id="inventory_id-error" class="form-text invalid-feedback"></small>
              </div>
              <!-- end : inventory_id -->
            </div>
          </div>
          <!-- Lead details : END -->

          <!-- Address : START -->
          <div class="lc-card" v-show="!updateView">
            <div class="lc-card-header">
              <span class="lc-card-icon lc-tone-teal"><i class="bx bx-map"></i></span>
              <div>
                <h4 class="lc-card-title">More Details</h4>
                <p class="lc-card-hint">Additional phone numbers and address.</p>
              </div>
            </div>
            <div class="lc-grid lc-grid-3">
              <!-- home phone -->
              <div class="lc-field">
                <label class="lc-label" for="home_phone">Home Phone</label>
                <div class="lc-input-icon">
                  <i class="bx bx-phone"></i>
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
                </div>
                <small id="home-phone-error" class="form-text invalid-feedback"></small>
              </div>
              <!-- end : home phone -->

              <!-- work phone -->
              <div class="lc-field">
                <label class="lc-label" for="work_phone">Work Phone</label>
                <div class="lc-input-icon">
                  <i class="bx bx-briefcase"></i>
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
                </div>
                <small id="work-phone-error" class="form-text invalid-feedback"></small>
              </div>
              <!-- end : work phone -->

              <div class="lc-field lc-location">
                <label class="lc-label" title="Pick a result to fill in the address fields">Find address</label>
                <suggest-location :elementId="`lead_address`" />
              </div>

              <!-- address 1 -->
              <div class="lc-field">
                <label class="lc-label" for="address_1">Address 1</label>
                <textarea
                  type="text"
                  class="form-control lc-textarea"
                  id="address_1"
                  name="address_1"
                  aria-describedby="Address 1"
                  placeholder="Street address"
                  rows="1"
                  v-model="form.address_1"
                ></textarea>
                <small id="address_1-error" class="form-text invalid-feedback"></small>
              </div>
              <!-- end : address 1 -->

              <!-- address 2 -->
              <div class="lc-field">
                <label class="lc-label" for="address_2">Address 2</label>
                <textarea
                  type="text"
                  class="form-control lc-textarea"
                  id="address_2"
                  name="address_2"
                  aria-describedby="Address 2"
                  placeholder="Apt, suite, unit (optional)"
                  rows="1"
                  v-model="form.address_2"
                ></textarea>
                <small id="address_2-error" class="form-text invalid-feedback"></small>
              </div>
              <!-- end : address 2 -->

              <!-- city -->
              <div class="lc-field">
                <label class="lc-label" for="city">City</label>
                <input
                  type="text"
                  class="form-control"
                  id="city"
                  name="city"
                  aria-describedby="City"
                  placeholder="City"
                  v-model="form.city"
                />
                <small id="city-error" class="form-text invalid-feedback"></small>
              </div>
              <!-- end : city -->

              <!-- province -->
              <div class="lc-field">
                <label class="lc-label" for="province">Province / State</label>
                <input
                  type="text"
                  class="form-control"
                  id="province"
                  name="province"
                  aria-describedby="province"
                  placeholder="Province / State"
                  v-model="form.province"
                />
                <small id="province-error" class="form-text invalid-feedback"></small>
              </div>
              <!-- end : province -->

              <!-- postal_code -->
              <div class="lc-field">
                <label class="lc-label" for="postal_code">Postal Code / ZIP</label>
                <input
                  type="text"
                  class="form-control"
                  id="postal_code"
                  name="postal_code"
                  aria-describedby="postal_code"
                  placeholder="Postal Code / ZIP"
                  v-model="form.postal_code"
                />
                <small id="postal_code-error" class="form-text invalid-feedback"></small>
              </div>
              <!-- end : postal_code -->

              <!-- country -->
              <div class="lc-field">
                <label class="lc-label" for="country">Country</label>
                <input
                  type="text"
                  class="form-control"
                  id="country"
                  name="country"
                  aria-describedby="country"
                  placeholder="Country"
                  v-model="form.country"
                />
                <small id="country-error" class="form-text invalid-feedback"></small>
              </div>
              <!-- end : country -->
            </div>
          </div>
          <!-- Address : END -->

          <!-- Personal : START -->
          <div class="lc-card" v-show="!updateView">
            <div class="lc-card-header">
              <span class="lc-card-icon lc-tone-amber"><i class="bx bx-user-pin"></i></span>
              <div>
                <h4 class="lc-card-title">Personal Details</h4>
                <p class="lc-card-hint">Optional details used for paperwork and test drives.</p>
              </div>
            </div>
            <div class="lc-grid lc-grid-4">
              <!-- birthdate -->
              <div class="lc-field">
                <label class="lc-label" for="birthdate">Birth Date</label>
                <input
                  type="date"
                  class="form-control"
                  id="birthdate"
                  name="birthdate"
                  aria-describedby="birthdate"
                  placeholder="Birth Date"
                  v-model="form.birthdate"
                />
                <small id="birthdate-error" class="form-text invalid-feedback"></small>
              </div>
              <!-- end : birthdate -->

              <!-- gender -->
              <div class="lc-field">
                <label class="lc-label" for="gender">Gender</label>
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
                <small id="gender-error" class="form-text invalid-feedback"></small>
              </div>
              <!-- end : gender -->

              <!-- driving_licence -->
              <div class="lc-field">
                <label class="lc-label" for="driving_licence">Driving Licence</label>
                <div class="lc-input-icon">
                  <i class="bx bx-id-card"></i>
                  <input
                    type="text"
                    class="form-control"
                    id="driving_licence"
                    name="driving_licence"
                    aria-describedby="driving_licence"
                    placeholder="Driving Licence"
                    v-model="form.driving_licence"
                  />
                </div>
                <small id="driving_licence-error" class="form-text invalid-feedback"></small>
              </div>
              <!-- end : driving_licence -->

              <!-- driving_licence_expiry_date -->
              <div class="lc-field">
                <label class="lc-label" for="driving_licence_expiry_date"
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
          </div>
          <!-- Personal : END -->

          <!-- Comments : START -->
          <div class="lc-card" v-show="!updateView">
            <div class="lc-card-header">
              <span class="lc-card-icon lc-tone-gray"><i class="bx bx-comment-dots"></i></span>
              <div>
                <h4 class="lc-card-title">Lead Comments</h4>
                <p class="lc-card-hint">Anything the customer mentioned or your team should know.</p>
              </div>
            </div>
            <div class="lc-body">
              <!-- lead comments -->
              <div class="lc-field mb-0">
                <textarea
                  type="text"
                  class="form-control lc-textarea"
                  id="lead_comments"
                  name="lead_comments"
                  rows="2"
                  v-model="form.lead_comments"
                  aria-describedby="Lead Comments"
                  placeholder="Lead Comments"
                ></textarea>
                <small id="lead_comments-error" class="form-text invalid-feedback"></small>
              </div>
              <!-- end : lead comments -->
            </div>
          </div>
          <!-- Comments : END -->
        </form>

        <!-- start of inventory display -->
        <aside class="lc-aside">
          <div class="lc-card lc-vehicle" v-show="website.vin != null && website.vin != ''">
            <div class="lc-card-header">
              <span class="lc-card-icon lc-tone-green"><i class="bx bx-car"></i></span>
              <div>
                <h4 class="lc-card-title">Vehicle of Interest</h4>
                <p class="lc-card-hint">Selected from inventory.</p>
              </div>
            </div>
            <router-link
              class="lc-photo"
              :to="{
                name: 'inventory-update',
                params: { id: form.inventory_id },
              }"
              exact
              title="Open vehicle in inventory"
            >
              <img :src="imageUrl" :alt="website.year + ' ' + website.make + ' ' + website.model" />
            </router-link>
            <div class="lc-vehicle-body">
              <h3 class="lc-vehicle-title">
                {{ website.year }} {{ website.make }} {{ website.model }}
              </h3>
              <p class="lc-vehicle-trim" v-if="website.trim">{{ website.trim }}</p>
              <div class="lc-spec-grid">
                <div class="lc-spec lc-spec--wide">
                  <span class="lc-spec-label">VIN</span>
                  <span class="lc-spec-value lc-mono" v-text="website.vin"></span>
                </div>
                <div class="lc-spec">
                  <span class="lc-spec-label">Stock #</span>
                  <span class="lc-spec-value lc-mono" v-text="website.stock_no"></span>
                </div>
                <div class="lc-spec">
                  <span class="lc-spec-label">Year</span>
                  <span class="lc-spec-value" v-text="website.year"></span>
                </div>
                <div class="lc-spec">
                  <span class="lc-spec-label">Make</span>
                  <span class="lc-spec-value" v-text="website.make"></span>
                </div>
                <div class="lc-spec">
                  <span class="lc-spec-label">Model</span>
                  <span class="lc-spec-value" v-text="website.model"></span>
                </div>
                <div class="lc-spec">
                  <span class="lc-spec-label">Trim</span>
                  <span class="lc-spec-value" v-text="website.trim || '—'"></span>
                </div>
                <div class="lc-spec">
                  <span class="lc-spec-label">Odometer</span>
                  <span class="lc-spec-value"
                    >{{ website.odometer }} {{ website.km_miles }}</span
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="lc-card lc-vehicle-empty" v-show="!(website.vin != null && website.vin != '')">
            <div class="lc-empty">
              <span class="lc-empty-icon"><i class="bx bx-car"></i></span>
              <p class="lc-empty-title">No vehicle selected</p>
              <p class="lc-empty-text">
                Choose a vehicle in the <strong>Inventory</strong> field to link
                it to this lead and preview it here.
              </p>
            </div>
          </div>
        </aside>
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
  beforeUnmount() {
    Event.vue.$off("save_complete", null);
    Event.vue.$off("save_lead", null);
    Event.vue.$off("autopopulate_location_lead_address", null);
  },
};
</script>

<style lang="scss">
/* ==========================================================================
   Create / Update Lead form. Scoped to #lead-create.
   ========================================================================== */
#lead-create {
  --lc-border: #e6eaf0;
  --lc-surface: #ffffff;
  --lc-bg: #f6f8fb;
  --lc-text: #1f2937;
  --lc-text-2: #4b5563;
  --lc-muted: #8a94a6;
  --lc-primary: #5a8dee;
  --lc-primary-soft: #eef4ff;

  .lc-page {
    padding: 1.1rem 1.5rem 5.5rem;
    background: var(--lc-bg);
    min-height: 100%;
    color: var(--lc-text);
  }

  /* Header */
  .lc-header {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    margin-bottom: 0.9rem;
  }
  .lc-header-icon {
    width: 42px;
    height: 42px;
    flex-shrink: 0;
    border-radius: 12px;
    background: var(--lc-primary-soft);
    color: var(--lc-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
  }
  .lc-title {
    margin: 0;
    font-size: 1.35rem;
    font-weight: 700;
    letter-spacing: -0.02em;
    color: var(--lc-text);
  }
  .lc-subtitle {
    margin: 0.1rem 0 0;
    font-size: 0.85rem;
    color: var(--lc-muted);
  }
  .lc-required {
    color: #c23434;
  }

  /* Layout */
  .lc-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 1rem;
    align-items: start;
  }
  .lc-form {
    min-width: 0;
  }
  .lc-aside {
    position: sticky;
    top: 1rem;
  }

  /* Cards */
  .lc-card {
    background: var(--lc-surface);
    border: 1px solid var(--lc-border);
    border-radius: 14px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    margin-bottom: 0.9rem;
  }
  /* Single-line card header: icon · title · hint */
  .lc-card-header {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.65rem 1.1rem;
    border-bottom: 1px solid var(--lc-border);
    > div {
      display: flex;
      align-items: baseline;
      gap: 0.6rem;
      min-width: 0;
    }
  }
  .lc-card-icon {
    width: 30px;
    height: 30px;
    flex-shrink: 0;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.05rem;
  }
  .lc-tone-blue { background: #e6efff; color: #2f62d6; }
  .lc-tone-purple { background: #efe8ff; color: #6b46c1; }
  .lc-tone-teal { background: #def6f3; color: #11806f; }
  .lc-tone-amber { background: #fff3d1; color: #a26a00; }
  .lc-tone-green { background: #e3f6e8; color: #1e8a43; }
  .lc-tone-gray { background: #f1f4f8; color: #5b6575; }
  .lc-card-title {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--lc-text);
    white-space: nowrap;
  }
  .lc-card-hint {
    margin: 0;
    font-size: 0.8rem;
    color: var(--lc-muted);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .lc-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0 0.85rem;
    padding: 0.85rem 1.1rem 0.1rem;
  }
  .lc-grid-3 {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }
  .lc-grid-4 {
    grid-template-columns: repeat(4, minmax(0, 1fr));
  }
  .lc-body {
    padding: 0.85rem 1.1rem;
  }
  .lc-span-2 {
    grid-column: span 2;
  }
  .lc-span-full {
    grid-column: 1 / -1;
  }

  /* Fields */
  .lc-field {
    margin-bottom: 0.75rem;
    min-width: 0;
  }
  .lc-label {
    display: block;
    margin-bottom: 0.25rem;
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--lc-text-2);
    text-transform: none;
    letter-spacing: 0;
  }
  .lc-help {
    display: block;
    margin-top: 0.3rem;
    font-size: 0.78rem;
    color: var(--lc-muted);
  }
  .lc-error {
    display: block;
    margin-top: 0.3rem;
    font-size: 0.8rem;
    color: #c23434;
  }
  .form-control,
  .custom-select {
    height: 38px;
    border: 1px solid var(--lc-border);
    border-radius: 9px;
    background-color: var(--lc-surface);
    font-size: 0.9rem;
    color: var(--lc-text);
    box-shadow: none;
    transition: border-color 0.15s, box-shadow 0.15s;
    &::placeholder {
      color: #a3acbb;
    }
    &:hover {
      border-color: #d6dce5;
    }
    &:focus {
      border-color: var(--lc-primary);
      box-shadow: 0 0 0 3px rgba(90, 141, 238, 0.15);
    }
    &.is-invalid {
      border-color: #e05252;
      background-image: none;
      padding-right: 0.9rem;
    }
  }
  .lc-textarea {
    height: auto;
    min-height: 38px;
    padding: 0.45rem 0.8rem;
    line-height: 1.5;
    resize: vertical;
  }
  .lc-input-icon {
    position: relative;
    > i {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1.1rem;
      color: var(--lc-muted);
      pointer-events: none;
      z-index: 1;
    }
    .form-control,
    .custom-select {
      padding-left: 2.4rem;
    }
  }

  /* v-select (source, status, inventory, location search) */
  .v-select {
    .vs__dropdown-toggle {
      min-height: 38px;
      padding: 0 0.35rem;
      border: 1px solid var(--lc-border);
      border-radius: 9px;
      background: var(--lc-surface);
      transition: border-color 0.15s, box-shadow 0.15s;
    }
    &:hover .vs__dropdown-toggle {
      border-color: #d6dce5;
    }
    &.vs--open .vs__dropdown-toggle {
      border-color: var(--lc-primary);
      box-shadow: 0 0 0 3px rgba(90, 141, 238, 0.15);
    }
    .vs__search,
    .vs__selected {
      font-size: 0.9rem;
      color: var(--lc-text);
      margin-top: 2px;
    }
    .vs__search::placeholder {
      color: #a3acbb;
    }
    .vs__dropdown-menu {
      border: 1px solid var(--lc-border);
      border-radius: 10px;
      box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
      margin-top: 4px;
      padding: 0.3rem 0;
    }
    .vs__dropdown-option {
      padding: 0.5rem 0.9rem;
      white-space: normal;
    }
    .vs__dropdown-option--highlight {
      background: var(--lc-primary-soft);
      color: var(--lc-primary);
    }
    .vs__clear,
    .vs__open-indicator {
      fill: var(--lc-muted);
    }
  }
  .lc-location .form-group,
  .lc-location > div {
    margin-bottom: 0;
  }

  /* Vehicle preview */
  .lc-photo {
    display: block;
    aspect-ratio: 16 / 10;
    background: #f1f4f8;
    overflow: hidden;
    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform 0.3s ease;
    }
    &:hover img {
      transform: scale(1.03);
    }
  }
  .lc-vehicle-body {
    padding: 1rem 1.25rem 1.25rem;
  }
  .lc-vehicle-title {
    margin: 0;
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--lc-text);
  }
  .lc-vehicle-trim {
    margin: 0.15rem 0 0;
    font-size: 0.88rem;
    color: var(--lc-muted);
  }
  .lc-spec-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.55rem;
    margin-top: 0.9rem;
  }
  .lc-spec {
    padding: 0.55rem 0.7rem;
    border-radius: 10px;
    background: var(--lc-bg);
    min-width: 0;
  }
  .lc-spec--wide {
    grid-column: 1 / -1;
  }
  .lc-spec-label {
    display: block;
    font-size: 0.74rem;
    font-weight: 500;
    color: var(--lc-muted);
  }
  .lc-spec-value {
    display: block;
    font-size: 0.92rem;
    font-weight: 500;
    color: var(--lc-text);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .lc-mono {
    font-variant-numeric: tabular-nums;
    letter-spacing: 0.02em;
  }
  .lc-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 2.25rem 1.5rem;
  }
  .lc-empty-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: var(--lc-primary-soft);
    color: var(--lc-primary);
    font-size: 1.7rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
  }
  .lc-empty-title {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: var(--lc-text);
  }
  .lc-empty-text {
    margin: 0.3rem 0 0;
    font-size: 0.87rem;
    color: var(--lc-muted);
  }

  @media (max-width: 1399.98px) {
    .lc-grid-4 {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }
  @media (max-width: 1199.98px) {
    .lc-layout {
      grid-template-columns: 1fr;
    }
    .lc-card-hint {
      display: none;
    }
    .lc-aside {
      position: static;
      order: -1;
    }
  }
  @media (max-width: 767.98px) {
    .lc-page {
      padding: 1rem 1rem 6rem;
    }
    .lc-grid,
    .lc-grid-3,
    .lc-grid-4 {
      grid-template-columns: 1fr;
    }
    .lc-span-2 {
      grid-column: auto;
    }
  }
}
</style>
