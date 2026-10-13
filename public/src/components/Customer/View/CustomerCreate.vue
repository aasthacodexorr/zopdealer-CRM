<template>
  <div
    class="tab-pane active"
    id="customer-create-panel"
    aria-labelledby="information-tab"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="customer-create-section">
      <div class="row match-height">
        <div class="col-md-12 col-12">
          <div class="card shadow-none mb-0">
            <div class="card-content">
              <div class="card-body">
                <form class="form form-horizontal webapp-form">
                  <div class="form-body">
                    <div class="row">
                      <div class="col-md-12 col-12">
                        <div class="m-width-s">
                          <div class="row">
                            <div
                              class="col-xs-12 col-md-12 alert alert-danger"
                              v-show="show_alert"
                            >
                              You have changed the customer type. Please save
                              the changes on this form, to enable any changes on
                              the Credit Application for this customer.
                            </div>
                            <div class="col-md-4 col-xs-12">
                              <label>Customer is Individual or Company</label>
                            </div>
                            <div class="col-md-4 col-xs-12 form-group">
                              <div class="custom-control custom-switch">
                                <input
                                  type="checkbox"
                                  id="switchForIndividualOrCompany"
                                  v-model="form.customer_type"
                                  name="purchase_from_flag"
                                  true-value="1"
                                  false-value="0"
                                  checked="checked"
                                  class="custom-control-input"
                                />
                                <label
                                  for="switchForIndividualOrCompany"
                                  class="custom-control-label"
                                >
                                  <span class="switch-icon-left">CMP</span>
                                  <span class="switch-icon-right">IND</span>
                                </label>
                              </div>
                            </div>
                            <div class="col-md-4 col-xs-12 form-group">
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
                          </div>
                        </div>
                      </div>
                      <!-- Customer Section : START -->
                      <div
                        class="col-md-12 col-12"
                        v-if="parseInt(form.customer_type) == 0"
                      >
                        <div class="m-width-s">
                          <div class="row">
                            <div class="col-md-4 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="First Name *"
                                  v-model="form.first_name"
                                  class="form-control"
                                  :class="{
                                    'is-invalid':
                                      errors.hasOwnProperty('first_name'),
                                  }"
                                  @keyup="delete errors.first_name"
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
                                    'is-invalid':
                                      errors.hasOwnProperty('last_name'),
                                  }"
                                  @keyup="delete errors.last_name"
                                  :title="errors.last_name"
                                  class="form-control"
                                />
                              </fieldset>
                            </div>
                            <div class="col-md-4 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <date-picker
                                  placeholder="Date of birth"
                                  v-model="form.date_of_birth"
                                  :format="'MM/DD/YYYY'"
                                  :value-type="'MM/DD/YYYY'"
                                  id="date_of_birth"
                                  :class="{
                                    'is-invalid':
                                      errors.hasOwnProperty('date_of_birth'),
                                  }"
                                  @keyup="delete errors.date_of_birth"
                                  :title="errors.date_of_birth"
                                >
                                </date-picker>
                              </fieldset>
                            </div>
                            <div class="col-md-2 col-xs-12">
                              <label>DL or RIN ?</label>
                            </div>
                            <div class="col-md-2 col-xs-12 form-group">
                              <div class="custom-control custom-switch">
                                <input
                                  type="checkbox"
                                  id="switchForDLorRIN"
                                  v-model="form.rin_or_dl_flag"
                                  name="purchase_from_flag"
                                  true-value="1"
                                  false-value="0"
                                  class="custom-control-input"
                                />
                                <label
                                  for="switchForDLorRIN"
                                  class="custom-control-label"
                                >
                                  <span class="switch-icon-left">DL</span>
                                  <span class="switch-icon-right">RIN</span>
                                </label>
                              </div>
                            </div>
                            <div
                              class="col-md-4 col-xs-12 form-group"
                              v-if="parseInt(form.rin_or_dl_flag) == 1"
                            >
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Driving License"
                                  v-model="form.driver_licence"
                                  :class="{
                                    'is-invalid':
                                      errors.hasOwnProperty('driver_licence'),
                                  }"
                                  @keyup="delete errors.driver_licence"
                                  :title="errors.driver_licence"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bx-id-card font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div
                              class="col-md-4 col-xs-12 form-group"
                              v-if="parseInt(form.rin_or_dl_flag) == 1"
                            >
                              <fieldset class="has-icon-left">
                                <date-picker
                                  placeholder="DL Expiry Date"
                                  v-model="form.driver_licence_expiry_date"
                                  :format="'MM/DD/YYYY'"
                                  :value-type="'MM/DD/YYYY'"
                                  id="driver_licence_expiry_date"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'driver_licence_expiry_date'
                                    ),
                                  }"
                                  @change="
                                    delete errors.driver_licence_expiry_date
                                  "
                                  :title="errors.driver_licence_expiry_date"
                                >
                                </date-picker>
                              </fieldset>
                            </div>
                            <div
                              class="col-md-4 col-xs-12 form-group"
                              v-if="parseInt(form.rin_or_dl_flag) == 0"
                            >
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="RIN"
                                  v-model="form.rin"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty('rin'),
                                  }"
                                  @keyup="delete errors.rin"
                                  :title="errors.rin"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bx-id-card font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Customer Section : END -->
                      <!-- Company Section : START -->
                      <div
                        class="col-md-12 col-12"
                        v-if="parseInt(form.customer_type) == 1"
                      >
                        <div class="m-width-s">
                          <div class="row">
                            <div class="col-md-4 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Company Name"
                                  v-model="form.company_name"
                                  class="form-control"
                                  :class="{
                                    'is-invalid':
                                      errors.hasOwnProperty('company_name'),
                                  }"
                                  @keyup="delete errors.company_name"
                                  :title="errors.company_name"
                                />
                                <div class="form-control-position">
                                  <i class="bx bxs-factory font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="MVDA #"
                                  v-model="form.mvda"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty('mvda'),
                                  }"
                                  @keyup="delete errors.mvda"
                                  :title="errors.mvda"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i
                                    class="bx bxs-spreadsheet font-medium-3"
                                  ></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Year End"
                                  v-model="form.year_end"
                                  :class="{
                                    'is-invalid':
                                      errors.hasOwnProperty('year_end'),
                                  }"
                                  @keyup="delete errors.year_end"
                                  :title="errors.year_end"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="RIN"
                                  v-model="form.rin"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty('rin'),
                                  }"
                                  @keyup="delete errors.rin"
                                  :title="errors.rin"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bx-id-card font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Tax #"
                                  v-model="form.tax_number"
                                  :class="{
                                    'is-invalid':
                                      errors.hasOwnProperty('tax_number'),
                                  }"
                                  @keyup="delete errors.tax_number"
                                  :title="errors.tax_number"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i
                                    class="bx bxs-spreadsheet font-medium-3"
                                  ></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Salesreport Reg #"
                                  v-model="form.sales_person_registration"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty(
                                      'sales_person_registration'
                                    ),
                                  }"
                                  @change="
                                    delete errors.sales_person_registration
                                  "
                                  :title="errors.sales_person_registration"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bxs-report font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Contact First Name *"
                                  v-model="form.first_name"
                                  class="form-control"
                                  :class="{
                                    'is-invalid':
                                      errors.hasOwnProperty('first_name'),
                                  }"
                                  @keyup="delete errors.first_name"
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
                                  placeholder="Contact Last Name"
                                  v-model="form.last_name"
                                  :class="{
                                    'is-invalid':
                                      errors.hasOwnProperty('last_name'),
                                  }"
                                  @keyup="delete errors.last_name"
                                  :title="errors.last_name"
                                  class="form-control"
                                />
                                <!-- <div class="form-control-position">
                                  <i class="bx bxs-user font-medium-3"></i>
                                </div> -->
                              </fieldset>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Company Section : END -->
                      <!-- Common Section : START -->
                      <div class="col-md-12 col-12">
                        <div class="m-width-s">
                          <div class="row">
                            <div class="col-md-12 col-xs-12 py-1">
                              <suggest-location
                                :elementId="'default'"
                              ></suggest-location>
                            </div>
                            <div class="col-md-6 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Street Address"
                                  v-model="form.street_address"
                                  :class="{
                                    'is-invalid':
                                      errors.hasOwnProperty('street_address'),
                                  }"
                                  @keyup="delete errors.street_address"
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
                                  @keyup="delete errors.apt"
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
                                  @keyup="delete errors.city"
                                  :title="errors.city"
                                  class="form-control"
                                />
                              </fieldset>
                            </div>
                            <div class="col-md-3 col-xs-12 form-group">
                              <fieldset>
                                <input
                                  type="text"
                                  placeholder="Province / State"
                                  v-model="form.province"
                                  :class="{
                                    'is-invalid':
                                      errors.hasOwnProperty('province'),
                                  }"
                                  @keyup="delete errors.province"
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
                                    'is-invalid':
                                      errors.hasOwnProperty('country'),
                                  }"
                                  @keyup="delete errors.country"
                                  :title="errors.country"
                                  class="form-control"
                                />
                              </fieldset>
                            </div>
                            <div class="col-md-3 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Postal Code / ZIP"
                                  v-model="form.postal_code"
                                  :class="{
                                    'is-invalid':
                                      errors.hasOwnProperty('postal_code'),
                                  }"
                                  @keyup="delete errors.postal_code"
                                  :title="errors.postal_code"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bxs-map font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="tel"
                                  placeholder="Phone"
                                  id="phone"
                                  v-model="form.phone"
                                  :class="{
                                    'is-invalid':
                                      errors.hasOwnProperty('phone'),
                                  }"
                                  @keyup="delete errors.phone"
                                  :title="errors.phone"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bx-phone font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Mobile"
                                  id="mobile"
                                  v-model="form.mobile"
                                  :class="{
                                    'is-invalid':
                                      errors.hasOwnProperty('mobile'),
                                  }"
                                  @keyup="delete errors.mobile"
                                  :title="errors.mobile"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bxs-mobile font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="email"
                                  placeholder="Fax"
                                  v-model="form.fax"
                                  :class="{
                                    'is-invalid': errors.hasOwnProperty('fax'),
                                  }"
                                  @keyup="delete errors.fax"
                                  :title="errors.fax"
                                  class="form-control"
                                />
                                <div class="form-control-position">
                                  <i class="bx bx-printer font-medium-3"></i>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-4 col-xs-12 form-group">
                              <fieldset class="has-icon-left">
                                <input
                                  type="text"
                                  placeholder="Email"
                                  v-model="form.email"
                                  :class="{
                                    'is-invalid':
                                      errors.hasOwnProperty('email'),
                                  }"
                                  @keyup="delete errors.email"
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
                      <!-- Common Section : END -->
                      <!-- Additional Information : START -->
                      <div class="col-md-12 col-12">
                        <div class="m-width-s">
                          <div class="row">
                            <!-- additional_information : START -->
                            <div class="col-xs-12 col-md-12">
                              <div class="form-group">
                                <label for="additional_information"
                                  >Additional Information</label
                                >
                                <fieldset class="" style="position: relative">
                                  <textarea
                                    id="additional_information"
                                    type="text"
                                    placeholder="Additional Information"
                                    v-model="form.additional_information"
                                    class="form-control"
                                    :class="{
                                      'is-invalid': errors.hasOwnProperty(
                                        'additional_information'
                                      ),
                                    }"
                                    @keyup="
                                      delete errors.additional_information
                                    "
                                    :title="errors.additional_information"
                                  ></textarea>
                                  <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                                </fieldset>
                                <small
                                  id="additional_information-error"
                                  class="form-text invalid-feedback"
                                  >{{ errors.additional_information }}</small
                                >
                              </div>
                            </div>
                            <!-- additional_information : END -->
                          </div>
                        </div>
                      </div>
                      <!-- Additional Information : END -->
                    </div>

                    <!-- row : end -->
                    <div>
                      <div class="card-header border-line-bottom-title">
                        <h6 class="card-title">More Information</h6>
                      </div>
                    </div>
                    <!-- row : start -->

                    <!-- other Information : START -->
                    <div class="row">
                      <template v-for="(each_data, index) in form.other_data">
                        <div
                          class="col-12 col-md-4"
                          v-if="each_data != null && each_data != ''"
                          :key="'other_data_' + index"
                        >
                          <div
                            class="font-small-3 col-12 text-capitalize text-bold-600 font-small-3 m-1 border-bottom p-0"
                          >
                            {{ formatHeader(index) }}
                          </div>
                          <div class="col-12">{{ each_data }}</div>
                        </div>
                      </template>
                    </div>
                    <!-- Other Information : END -->
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import Toast from "../../../library/Toast";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import Inputmask from "inputmask";
import SuggestLocation from "../../WebComponents/SuggestLocation.vue";

export default {
  props: {
    allUsers: { required: true },
    app_metadata: { required: true },
  },
  name: "CustomerCreate",
  watch: {
    "form.customer_type": function (newVal, oldVal) {
      if (
        newVal != oldVal &&
        this.$route.params.id &&
        this.$route.name == "customer-update"
      ) {
        Event.fire("change_customer", newVal);
        if (!this.loading) {
          this.show_alert = true;

          if (parseInt(this.form.customer_type) == 1) {
            this.form.driver_licence = "";
            this.form.rin_or_dl_flag = "0";
          } else {
            this.form.rin_or_dl_flag = "1";
            this.form.rin = "";
            Vue.nextTick(() => {
              document.getElementById(
                "driver_licence_expiry_date"
              ).firstElementChild.placeholder = "DL Expiry Date";
              document.getElementById(
                "date_of_birth"
              ).firstElementChild.placeholder = "Date Of Birth";
            });
          }
        }
      }
    },
    "form.rin_or_dl_flag": function (newVal, oldVal) {
      if (parseInt(newVal) == 1) {
        Vue.nextTick(() => {
          document.getElementById(
            "driver_licence_expiry_date"
          ).firstElementChild.placeholder = "DL Expiry Date";
        });
      }
    },
  },
  components: {
    DatePicker,
    Inputmask,
    SuggestLocation,
  },
  data() {
    return {
      show_alert: false,
      loading: true,
      errors: [],
      form: {
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
        rin_or_dl_flag: "1",
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
    };
  },
  methods: {
    formatHeader(text) {
      return text.replace(/_/g, " ");
    },
    async getCustomer(id) {
      const vm = this;
      await axios({
        method: "get",
        url: window.location.origin + "/api/business/customers/show/" + id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }

          if (data.assignee == null) {
            this.form["assignee"] = "";
          }

          let driver_licence_expiry_date = moment(
            data.driver_licence_expiry_date
          );

          this.form.driver_licence_expiry_date =
            driver_licence_expiry_date.isValid()
              ? driver_licence_expiry_date.format("MM/DD/YYYY")
              : "";

          let date_of_birth = moment(data.date_of_birth);

          this.form.date_of_birth = date_of_birth.isValid()
            ? date_of_birth.format("MM/DD/YYYY")
            : "";

          if (this.$helpers.isValidJsonString(data.other_data)) {
            this.form.other_data = JSON.parse(data.other_data);
          } else {
            this.form.other_data = [];
          }

          Event.fire("customer_load", true);
          this.show_alert = false;

          Event.fire("get_customer_phone", data.mobile);
          Event.fire("get_customer_email", data.email);

          setTimeout(() => {
            vm.loading = false;
          }, 10);
        })
        .catch(({ response }) => {
          let error =
            "There were some errors in retrieving the customer details.";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
    async save() {
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && this.form[key] != null) {
          if (key !== "other_data") {
            formData.append(key, this.form[key]);
          }
        }
      }

      console.log(Object.keys(this.form.other_data).length);

      if (typeof this.form.other_data === "object" && this.form.other_data) {
        if (Object.keys(this.form.other_data).length > 0) {
          formData.append("other_data", JSON.stringify(this.form.other_data));
        }
      }

      let request_url = window.location.origin;
      let update_request =
        this.$route.params.id && this.$route.name == "customer-update";

      //success message setting
      let success_message = update_request
        ? `Customer details has been updated.`
        : `Customer details has been created.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/customers/update/${this.$route.params.id}`
        : `${request_url}/api/business/customers/create`;

      //message during request
      let request_message = update_request
        ? `Updating customer details ...`
        : `Creating new customer ...`;

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
          toast.success(data.success);
          toast.close();
          vm.show_alert = false;

          Event.fire("get_customer_phone", vm.form.mobile);
          Event.fire("get_customer_email", vm.form.email);

          setTimeout(function () {
            Event.fire("save_complete", data);
          }, 500);
        })
        .catch(function (error) {
          let update_request =
            vm.$route.params.id && vm.$route.name == "customer-update";
          let error_message = update_request
            ? "There were errors while updating the customer details."
            : "There were some errors while creating new customer.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();
          Event.fire("save_complete", null);
        });
    },
  },
  async created() {
    if (this.$route.params.id && this.$route.name == "customer-update") {
      await this.getCustomer(this.$route.params.id);
    } else {
      this.form.assignee = this.app_metadata.id;
    }
  },
  mounted() {
    const vm = this;
    document.getElementById(
      "driver_licence_expiry_date"
    ).firstElementChild.placeholder = "DL Expiry Date";
    document.getElementById("date_of_birth").firstElementChild.placeholder =
      "Date Of Birth";
    window.addEventListener("load", () => {
      document.getElementById(
        "driver_licence_expiry_date"
      ).firstElementChild.placeholder = "DL Expiry Date";
      document.getElementById("date_of_birth").firstElementChild.placeholder =
        "Date Of Birth";
    });

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
    }).mask(document.getElementById("mobile"));

    Event.listen("save_customer", (data) => {
      vm.save();
    });

    Event.listen("autopopulate_location_default", (data) => {
      if (data.hasOwnProperty("place")) {
        vm.$data.form.country = data.place.country;
        vm.$data.form.postal_code = data.place.postal_code;
        vm.$data.form.province = data.place.province;
        vm.$data.form.city = data.place.city;
        vm.$data.form.apt = "";

        if (data.place.street !== "") {
          vm.$data.form.street_address = `${data.place.street_number} ${data.place.street}`;
        } else {
          vm.$data.form.street_address = data.place.street;
        }
      }
    });
  },
  beforeDestroy() {
    Event.vue.$off("save_complete", null);
    Event.vue.$off("autopopulate_location_default", null);
    Event.vue.$off("change_customer", null);
    Event.vue.$off("get_customer_phone", null);
    Event.vue.$off("get_customer_email", null);
  },
};
</script>
<style lang="scss">
#customer-create-panel .vdpComponent input {
  padding-left: 2.2em !important;
  line-height: 1.4;
  color: #777;
  background-color: #ffffff !important;
  background-clip: padding-box;
  border: 1px solid #dfe3e7;
  border-radius: 0.267rem;
}

input:read-only {
  background: #eee !important;
}
</style>
