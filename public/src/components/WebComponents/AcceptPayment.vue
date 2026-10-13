<template>
  <div>
    <a
      class="btn btn-primary text-white w-100 text-center"
      type="button"
      data-toggle="modal"
      data-target="#paymentModal"
    >
      <i class="bx bx-credit-card mr-1"></i> Payment
    </a>
    <!-- Collecting Payment : START  -->
    <div
      class="modal fade"
      id="paymentModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Accept Payment"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <!-- Modal Header : START -->
          <div class="modal-header">
            <h5 class="modal-title" id="paymentHeader">Payment</h5>
            <button
              type="button"
              id="paymentClose"
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
            <form class="webapp-form">
              <div class="form-row">
                <h6
                  class="form-group col-md-12 font-italic"
                  v-if="form.type === 'Credit' && form.mode === 'Online'"
                >
                  Payment link will open in next tab. This link can be shared
                  with the customer and valid for only 5 minutes.
                </h6>
                <h6 class="form-group col-md-12 font-italic">
                  You can log the payment that has been collected or refunded.
                  To accept payment online, select Type as Credit and Mode as
                  Online. If Online option is not available, please contact our
                  Support.
                </h6>
                <!-- Date : START -->
                <div class="form-group col-md-6" v-show="!showStripeAction">
                  <label for="payment_date">Date : </label>
                  <fieldset
                    class="has-icon-left"
                    style="position: relative"
                    id="payment_date"
                  >
                    <date-picker
                      v-model="form.date"
                      :format="'MM/DD/YYYY'"
                      :value-type="'MM/DD/YYYY'"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('date'),
                      }"
                      @change="delete errors.date"
                      :title="errors.date"
                      :clearable="false"
                    ></date-picker>
                  </fieldset>
                </div>
                <!-- Date : END -->
                <!-- Name : START -->
                <div class="form-group col-md-6">
                  <label for="payment_name">Full Name: </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('name'),
                    }"
                    @keyup="delete errors.name"
                    :title="errors.name"
                    v-model="form.name"
                    id="payment_name"
                    placeholder="Name"
                  />
                </div>
                <!-- Name : END -->
                <!-- Email : START -->
                <div class="form-group col-md-6">
                  <label for="payment_email">Email: </label>
                  <input
                    type="email"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('email'),
                    }"
                    @keyup="delete errors.email"
                    :title="errors.email"
                    v-model="form.email"
                    id="payment_email"
                    placeholder="Email"
                  />
                </div>
                <!-- Email : END -->
                <!-- Phone : START -->
                <div class="form-group col-md-6">
                  <label for="payment_phone">Phone No (10 Digits): </label>
                  <input
                    type="tel"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('phone'),
                    }"
                    @keyup="delete errors.phone"
                    :title="errors.phone"
                    v-model="form.phone"
                    id="payment_phone"
                    placeholder="Phone"
                  />
                </div>
                <!-- Phone : END -->
                <!-- Type : START -->
                <div class="form-group col-md-6">
                  <label for="payment_type">Type: </label>
                  <select
                    class="custom-select form-control"
                    v-model="form.type"
                    id="payment_type"
                    name="payment_type"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('type'),
                    }"
                    @change="delete errors.type"
                  >
                    <option value="" selected="" disabled="">
                      - Payment Types -
                    </option>
                    <option
                      :value="payment_type"
                      v-for="payment_type in getTypes"
                      :key="payment_type"
                    >
                      {{ payment_type }}
                    </option>
                  </select>
                </div>
                <!-- Type : END -->
                <!-- Mode : START -->
                <div class="form-group col-md-6" v-show="form.type !== 'Cash'">
                  <label for="payment_mode">Mode: </label>
                  <select
                    class="custom-select form-control"
                    v-model="form.mode"
                    id="payment_mode"
                    name="payment_mode"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('mode'),
                    }"
                    @change="delete errors.mode"
                  >
                    <option value="" selected="" disabled="">
                      - Payment Modes -
                    </option>
                    <option
                      :value="payment_mode"
                      v-for="payment_mode in getModes"
                      :disabled="
                        payment_mode === `Online` &&
                        !isOnlinePaymentModuleAvailable
                      "
                      :key="payment_mode"
                    >
                      {{ payment_mode }}
                    </option>
                  </select>
                </div>
                <!-- Mode : END -->
                <!-- Transaction ID : START -->
                <div
                  class="form-group col-md-6"
                  v-show="!showStripeAction && form.type !== 'Cash'"
                >
                  <label for="payment_transaction_id">Transaction #: </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('transaction_id'),
                    }"
                    @keyup="delete errors.transaction_id"
                    :title="errors.transaction_id"
                    v-model="form.transaction_id"
                    id="payment_transaction_id"
                    placeholder="Transaction #"
                  />
                </div>
                <!-- Transaction ID : END -->
                <!-- Receipt Number : START -->
                <div class="form-group col-md-6" v-show="!showStripeAction">
                  <label for="payment_receipt_number">Receipt #: </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('receipt_number'),
                    }"
                    @keyup="delete errors.receipt_number"
                    :title="errors.receipt_number"
                    v-model="form.receipt_number"
                    id="payment_receipt_number"
                    placeholder="Receipt #"
                  />
                  <small class="form-text"
                    >Will be autogenerated if left blank.</small
                  >
                </div>
                <!-- Receipt Number : END -->

                <!-- Reason : START -->
                <div class="form-group col-md-6">
                  <label for="reason">Reason</label>
                  <v-select
                    id="payment_reason"
                    placeholder="Reason"
                    taggable
                    v-model="form.reason"
                    clearable
                    :options="getReasons"
                  >
                  </v-select>
                  <small
                    id="reason-error"
                    class="form-text invalid-feedback"
                  ></small>
                </div>
                <!-- Reason : END -->

                <!-- Amount : START -->
                <div class="form-group col-md-6">
                  <label for="payment_amount">Amount: </label>
                  <input
                    type="tel"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('amount'),
                    }"
                    @keyup="delete errors.amount"
                    :title="errors.amount"
                    v-model="form.amount"
                    id="payment_amount"
                    placeholder="Amount"
                  />
                </div>
                <!-- Amount : END -->
                <!-- Status : START -->
                <div class="form-group col-md-6" v-show="!showStripeAction">
                  <label for="payment_status">Status: </label>
                  <select
                    class="custom-select form-control"
                    v-model="form.status"
                    id="payment_status"
                    name="payment_status"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('status'),
                    }"
                    @change="delete errors.status"
                  >
                    <option value="" selected="" disabled="">
                      - Payment Status -
                    </option>
                    <option
                      :value="payment_status"
                      v-for="payment_status in getPaymentStatuses"
                      :key="payment_status"
                    >
                      {{ payment_status }}
                    </option>
                  </select>
                </div>
                <!-- Status : END -->
              </div>
            </form>
          </div>
          <!-- Modal Body : END -->
          <!-- Modal Footer : START -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              v-if="showStripeAction"
              @click.prevent="paymentOnlineForm"
              :disabled="form.name == '' || form.amount < 1"
            >
              Payment Link
            </button>
            <button
              type="button"
              class="btn btn-primary"
              v-else
              @click.prevent="savePayment"
              :disabled="form.name == '' || form.amount < 1"
            >
              Add Log
            </button>
          </div>
          <!-- Modal Footer : END -->
        </div>
      </div>
    </div>
    <!-- Collecting Payment : END  -->
  </div>
</template>
<script>
import Toast from "../../library/Toast";
import { mapGetters, mapActions } from "vuex";
import vSelect from "vue-select";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

export default {
  props: {
    default_name: {
      required: false,
      default: "",
    },
    default_email: {
      required: false,
      default: "",
    },
    default_phone: {
      required: false,
      default: "",
    },
    module: {
      required: false,
      default: null,
    },
    module_id: {
      required: false,
      default: null,
    },
  },
  name: "AcceptPayment",
  data() {
    return {
      form: {
        name: "",
        email: "",
        phone: "",
        type: "Cash",
        mode: "Offline",
        transaction_id: "",
        receipt_number: "",
        status: "Charge",
        amount: 0,
        reason: "",
        date: null,
      },
      global: {
        stripe_account: "",
      },
      errors: [],
    };
  },
  watch: {
    "form.amount": function (newVal, oldVal) {
      let amount =
        !isNaN(this.form.amount) && this.form.amount != ""
          ? this.form.amount
          : 0;
      this.form.amount = amount;
    },
    "form.type": function (newVal, oldVal) {
      if (newVal !== oldVal) {
        if (this.form.type === "Cash") {
          this.form.mode = "Offline";
        }
      }
    },
    default_phone: {
      immediate: true,
      handler(val, oldVal) {
        this.form.phone = val;
      },
    },
    default_email: {
      immediate: true,
      handler(val, oldVal) {
        this.form.email = val;
      },
    },
    default_name: {
      immediate: true,
      handler(val, oldVal) {
        if (val !== "Customer") {
          this.form.name = val;
        }
      },
    },
  },
  components: {
    "v-select": vSelect,
    DatePicker,
  },
  computed: {
    ...mapGetters("payment", [
      "getTypes",
      "getModes",
      "getPaymentStatuses",
      "getReasons",
    ]),
    showStripeAction() {
      return this.form.type === `Credit` && this.form.mode === `Online`;
    },
    isOnlinePaymentModuleAvailable() {
      return this.global.stripe_account.length > 1;
    },
  },
  methods: {
    ...mapActions("payment", ["getPaymentConfigurations"]),
    ...mapActions("paymentView", ["commitOnlyEntities"]),
    async getGlobalConfiguration() {
      let request_url =
        window.location.origin + "/api/business/configurations/global";

      await axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            this.global.hasOwnProperty(element.key)
              ? (this.global[element.key] = element.value)
              : null;
          }
        }
      });
    },
    async resetPaymentData() {
      for (const key in this.form) {
        this.form[key] = "";
      }
      this.form.status = "Charge";
      this.form.type = "Cash";
      this.form.mode = "Offline";
      this.form.amount = 0;

      if (this.default_name !== "Customer") {
        this.form.name = this.default_name;
      }
      this.form.email = this.default_email;
      this.form.phone = this.default_phone;

      this.form.date = moment(new Date()).format("MM/DD/YYYY");
    },
    async paymentOnlineForm() {
      const vm = this;
      let formData = new FormData();
      for (const key in this.form) {
        formData.append(key, this.form[key]);
      }

      if (vm.module && vm.module_id) {
        formData.append("module", vm.module);
        formData.append("module_id", vm.module_id);
      }

      let request_url =
        window.location.origin + "/api/business/payments/online/create";
      let message = "Generating Payment Link...";
      const toast = new Toast(message);
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
          this.errors = [];

          this.resetPaymentData();

          let success_message = "Payment link will open in new tab.";
          toast.success(success_message);
          toast.close();

          document.getElementById("paymentClose").click();
          setTimeout(() => {
            if (data.hasOwnProperty("payment_url")) {
              window.open(
                window.location.origin +
                  "/api/payments/online/show/" +
                  data.payment_url,
                "_blank"
              );
            }
          }, 1000);
        })
        .catch((error) => {
          let error_message = "There were errors in generating payment link.";
          toast.fail(error_message);
          toast.close();
          vm.$data.errors = error.response.data;
        });
    },
    async savePayment() {
      const vm = this;
      let formData = new FormData();
      for (const key in this.form) {
        formData.append(key, this.form[key]);
      }

      if (vm.module && vm.module_id) {
        formData.append("module", vm.module);
        formData.append("module_id", vm.module_id);
      }

      let request_url =
        window.location.origin + "/api/business/payments/create";
      let message = "Saving Payment...";
      const toast = new Toast(message);
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
          this.errors = [];

          this.resetPaymentData();

          let success_message = "Payment details saved.";
          toast.success(success_message);
          toast.close();
          document.getElementById("paymentClose").click();
          this.commitOnlyEntities();
          Event.fire("reload_payments", null);
        })
        .catch((error) => {
          let error_message = "There were errors in saving payments.";
          toast.fail(error_message);
          toast.close();
          vm.$data.errors = error.response.data;
        });
    },
  },
  async created() {
    this.form.date = moment(new Date()).format("MM/DD/YYYY");
    this.resetPaymentData();
    await this.getGlobalConfiguration();
    await this.getPaymentConfigurations();
  },
  mounted() {
    const vm = this;
    jQuery("#paymentModal").on("hidden.bs.modal", function () {
      vm.resetPaymentData();
    });
  },
};
</script>
<style lang="scss" scoped>
label,
input,
select {
  font-family: "Rubik", Helvetica, Arial, serif;
}
</style>
<style lang="scss">
#paymentModal .webapp-form .form-group input {
  height: calc(1.4em + 0.94rem + 3.7px);
  margin-top: 0;
}

#paymentModal .webapp-form .form-group .vs__dropdown-toggle {
  padding: 0px;
}

#paymentModal .vdpComponent {
  width: 100%;
}
#paymentModal .vdpComponent input {
  display: block;
  width: 100%;
  height: calc(1.4em + 0.94rem + 3.7px);
  padding: 0.47rem 0.8rem;
  padding-right: 0.8rem;
  padding-left: 2.2em !important;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.4;
  color: #475f7b;
  background-color: #ffffff;
  background-clip: padding-box;
  border: 1px solid #dfe3e7;
  border-radius: 0.267rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
#paymentModal .vdpComponent.is-invalid input {
  border-color: #ff5b5c;
}
</style>
