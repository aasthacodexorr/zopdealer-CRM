<template>
  <div
    class="tab-pane"
    id="customer-insurance-panel"
    aria-labelledby="Customer Insurance"
    role="tabpanel"
  >
    <section id="customer-insurance">
      <div class="row match-height">
        <div class="col-12 col-xs-12">
          <div class="card" style="min-height:85vh;">
            <div class="card-content">
              <div class="card-body pb-1 mx-25">
                <h4 class="card-title col-12">
                  Customer Insurance
                </h4>
                <!-- insurance_company_name : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Details</h5>
                  </div>
                  <div class="row">
                    <h5 class="col-12 text-danger" style="font-size: 0.8em;">
                      Note : This is displayed on paperwork.
                    </h5>
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="insurance_company_name"
                          >Insurance Company</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position:relative;"
                        >
                          <input
                            id="insurance_company_name"
                            type="text"
                            placeholder="Insurance Company"
                            v-model="form.company_name"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'company_name'
                              ),
                            }"
                            @keyup="delete errors.company_name"
                            :title="errors.company_name"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-factory font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="insurance_company_name-error"
                          class="form-text invalid-feedback"
                          >{{ errors.company_name }}</small
                        >
                      </div>
                    </div>
                    <!-- insurance_company_name : END -->

                    <!-- insurance_agent_name : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="insurance_agent_name"
                          >Insurance Agent</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position:relative;"
                        >
                          <input
                            id="insurance_agent_name"
                            type="text"
                            placeholder="Insurance Agent"
                            v-model="form.agent_name"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('agent_name'),
                            }"
                            @keyup="delete errors.agent_name"
                            :title="errors.agent_name"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-factory font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="insurance_agent_name-error"
                          class="form-text invalid-feedback"
                          >{{ errors.agent_name }}</small
                        >
                      </div>
                    </div>
                    <!-- insurance_agent_name : END -->
                    <div class="w-100"></div>

                    <!-- insurance_policy_number : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="insurance_policy_number"
                          >Policy Number</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position:relative;"
                        >
                          <input
                            id="insurance_policy_number"
                            type="text"
                            placeholder="Policy Number"
                            v-model="form.policy_number"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'policy_number'
                              ),
                            }"
                            @keyup="delete errors.policy_number"
                            :title="errors.policy_number"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-factory font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="insurance_policy_number-error"
                          class="form-text invalid-feedback"
                          >{{ errors.policy_number }}</small
                        >
                      </div>
                    </div>
                    <!-- insurance_policy_number : END -->

                    <!-- policy expiry date : START -->
                    <div class="col-md-4 col-xs-12">
                      <div class="form-group">
                        <label for="policy_expiry_date"
                          >Policy Expiry Date</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position:relative;"
                        >
                          <date-picker
                            placeholder="Policy Expiry Date"
                            v-model="form.policy_expiry_date"
                            :format="'MM/DD/YYYY'"
                            :value-type="'MM/DD/YYYY'"
                            id="policy_expiry_date"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'policy_expiry_date'
                              ),
                            }"
                            @change="delete errors.policy_expiry_date"
                            :title="errors.policy_expiry_date"
                          >
                          </date-picker>
                        </fieldset>
                        <small
                          id="policy_expiry_date-error"
                          class="form-text invalid-feedback"
                          >{{ errors.policy_expiry_date }}</small
                        >
                      </div>
                    </div>
                    <!-- policy expiry date : END -->
                    <div class="w-100"></div>

                    <!-- policy_phone : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="policy_phone">Phone</label>
                        <fieldset class="" style="position:relative;">
                          <input
                            id="policy_phone"
                            type="tel"
                            placeholder="Phone"
                            v-model="form.phone"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('phone'),
                            }"
                            @keyup="delete errors.phone"
                            :title="errors.phone"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="policy_phone-error"
                          class="form-text invalid-feedback"
                          >{{ errors.phone }}</small
                        >
                      </div>
                    </div>
                    <!-- policy_phone : END -->

                    <!-- policy_phone_ext : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="policy_phone_ext">Phone Ext</label>
                        <fieldset class="" style="position:relative;">
                          <input
                            id="policy_phone_ext"
                            type="tel"
                            placeholder="Phone Ext"
                            v-model="form.phone_ext"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('phone_ext'),
                            }"
                            @keyup="delete errors.phone_ext"
                            :title="errors.phone_ext"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="policy_phone_ext-error"
                          class="form-text invalid-feedback"
                          >{{ errors.phone_ext }}</small
                        >
                      </div>
                    </div>
                    <!-- policy_phone_ext : END -->
                  </div>
                </div>
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

export default {
  name: "CustomerInsurance",
  components: {
    DatePicker,
    Inputmask,
  },
  data() {
    return {
      errors: [],
      customer_insurance_id: null,
      form: {
        company_name: "",
        agent_name: "",
        policy_number: "",
        policy_expiry_date: "",
        phone: "",
        phone_ext: "",
      },
    };
  },
  computed: {
    updateView() {
      return this.$route.params.id && this.$route.name == "customer-update";
    },
  },
  methods: {
    getCustomerInsuranceDetails(customer_id) {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/customers/" +
          customer_id +
          "/insurance",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          //this.form = data;

          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }

          let policy_expiry_date = moment(data.policy_expiry_date);

          this.form.policy_expiry_date = policy_expiry_date.isValid()
            ? policy_expiry_date.format("MM/DD/YYYY")
            : "";

          this.customer_insurance_id = data["id"];
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    save() {
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
        !isNaN(this.customer_insurance_id) &&
        this.customer_insurance_id != null;

      //success message setting
      let success_message = update_request
        ? `Customer insurance details updated.`
        : `Customer insurance details added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/customers/${this.$route.params.id}/insurance/update/${this.customer_insurance_id}`
        : `${request_url}/api/business/customers/${this.$route.params.id}/insurance`;

      //message during request
      let request_message = update_request
        ? `Updating customer insurance details ...`
        : `Adding new customer insurance details ...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
          vm.getCustomerInsuranceDetails(vm.$route.params.id);

          setTimeout(function() {
            Event.fire("save_customer_insurance_complete", data);
          }, 2000);
        })
        .catch(function(error) {
          let update_request =
            !isNaN(vm.customer_insurance_id) &&
            vm.customer_insurance_id != null;

          let error_message = update_request
            ? "There were errors while updating the customer insurance details."
            : "There were some errors while adding customer insurance details.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();

          setTimeout(function() {
            Event.fire("save_customer_insurance_complete", null);
          }, 2000);
        });
    },
  },
  created() {
    const vm = this;
    if (vm.updateView) {
      vm.getCustomerInsuranceDetails(this.$route.params.id);
    }
  },
  mounted() {
    const vm = this;

    Event.listen("save_customer_insurance", (data) => {
      vm.save();
    });

    window.addEventListener("load", () => {
      document.getElementById(
        "policy_expiry_date"
      ).firstElementChild.placeholder = "Policy Expiry Date";
    });

    Inputmask({
      mask: "(999) 999-9999",
      autoUnmask: true,
      onBeforePaste: function(pastedValue, opts) {
        pastedValue = pastedValue.toLowerCase();
        return pastedValue.replace(/\D/g, "");
      },
    }).mask(document.getElementById("policy_phone"));
  },
  beforeDestroy() {
    Event.vue.$off("save_customer_insurance_complete", null);
  },
};
</script>
