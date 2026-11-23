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
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
          <!-- Modal Header : START -->
          <div class="ap-header">
            <div class="ap-heading">
              <span class="ap-header-icon"><i class="bx bx-credit-card"></i></span>
              <div>
                <h5 class="ap-title" id="paymentHeader">Payment</h5>
                <p class="ap-subtitle">
                  You can log the payment that has been collected or refunded.
                  To accept payment online, select Type as Credit and Mode as
                  Online. If Online option is not available, please contact our
                  Support.
                </p>
              </div>
            </div>
            <button
              type="button"
              id="paymentClose"
              class="ap-close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <i class="bx bx-x"></i>
            </button>
          </div>
          <!-- Modal Header : END -->
          <!-- Modal Body : START -->
          <div class="modal-body ap-body">
            <form class="webapp-form" @submit.prevent>
              <div
                class="ap-banner"
                v-if="form.type === 'Credit' && form.mode === 'Online'"
              >
                <i class="bx bx-link"></i>
                <span
                  >Payment link will open in next tab. This link can be shared
                  with the customer and valid for only 5 minutes.</span
                >
              </div>

              <!-- Section: Payment : START -->
              <div class="ap-section">
                <p class="ap-section-title">Payment</p>

                <!-- Type : START -->
                <div class="ap-field">
                  <label class="ap-label" for="payment_type">Type</label>
                  <div class="ap-chips" role="radiogroup" aria-label="Payment type">
                    <button
                      type="button"
                      class="ap-chip"
                      v-for="payment_type in getTypes"
                      :key="'chip_' + payment_type"
                      :class="{ active: form.type === payment_type }"
                      role="radio"
                      :aria-checked="form.type === payment_type"
                      @click="
                        form.type = payment_type;
                        delete errors.type;
                      "
                    >
                      <i class="bx" :class="typeIcon(payment_type)"></i>
                      {{ payment_type }}
                    </button>
                  </div>
                  <select
                    class="d-none"
                    v-model="form.type"
                    id="payment_type"
                    name="payment_type"
                    tabindex="-1"
                    aria-hidden="true"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('type'),
                    }"
                    @change="delete errors.type"
                  >
                    <option value="" disabled="">- Payment Types -</option>
                    <option
                      :value="payment_type"
                      v-for="payment_type in getTypes"
                      :key="payment_type"
                    >
                      {{ payment_type }}
                    </option>
                  </select>
                  <small class="ap-error" v-if="errors.type">{{ errors.type }}</small>
                </div>
                <!-- Type : END -->

                <div class="ap-grid">
                  <!-- Mode : START -->
                  <div class="ap-field" v-show="form.type !== 'Cash'">
                    <label class="ap-label" for="payment_mode">Mode</label>
                    <div class="ap-segmented" role="radiogroup" aria-label="Payment mode">
                      <button
                        type="button"
                        class="ap-segment"
                        v-for="payment_mode in getModes"
                        :key="'mode_' + payment_mode"
                        :class="{ active: form.mode === payment_mode }"
                        :disabled="
                          payment_mode === `Online` &&
                          !isOnlinePaymentModuleAvailable
                        "
                        :title="
                          payment_mode === `Online` &&
                          !isOnlinePaymentModuleAvailable
                            ? 'Online payments are not enabled for this account'
                            : ''
                        "
                        role="radio"
                        :aria-checked="form.mode === payment_mode"
                        @click="
                          form.mode = payment_mode;
                          delete errors.mode;
                        "
                      >
                        <i
                          class="bx"
                          :class="payment_mode === 'Online' ? 'bx-globe' : 'bx-store'"
                        ></i>
                        {{ payment_mode }}
                      </button>
                    </div>
                    <select
                      class="d-none"
                      v-model="form.mode"
                      id="payment_mode"
                      name="payment_mode"
                      tabindex="-1"
                      aria-hidden="true"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('mode'),
                      }"
                      @change="delete errors.mode"
                    >
                      <option value="" disabled="">- Payment Modes -</option>
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
                    <small class="ap-error" v-if="errors.mode">{{ errors.mode }}</small>
                  </div>
                  <!-- Mode : END -->

                  <!-- Status : START -->
                  <div class="ap-field" v-show="!showStripeAction">
                    <label class="ap-label" for="payment_status">Status</label>
                    <div class="ap-segmented" role="radiogroup" aria-label="Payment status">
                      <button
                        type="button"
                        class="ap-segment"
                        v-for="payment_status in getPaymentStatuses"
                        :key="'status_' + payment_status"
                        :class="[
                          { active: form.status === payment_status },
                          payment_status === 'Refund' ? 'is-refund' : '',
                        ]"
                        role="radio"
                        :aria-checked="form.status === payment_status"
                        @click="
                          form.status = payment_status;
                          delete errors.status;
                        "
                      >
                        <i
                          class="bx"
                          :class="payment_status === 'Refund' ? 'bx-undo' : 'bx-down-arrow-alt'"
                        ></i>
                        {{ payment_status }}
                      </button>
                    </div>
                    <select
                      class="d-none"
                      v-model="form.status"
                      id="payment_status"
                      name="payment_status"
                      tabindex="-1"
                      aria-hidden="true"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('status'),
                      }"
                      @change="delete errors.status"
                    >
                      <option value="" disabled="">- Payment Status -</option>
                      <option
                        :value="payment_status"
                        v-for="payment_status in getPaymentStatuses"
                        :key="payment_status"
                      >
                        {{ payment_status }}
                      </option>
                    </select>
                    <small class="ap-error" v-if="errors.status">{{ errors.status }}</small>
                  </div>
                  <!-- Status : END -->
                </div>

                <!-- Amount : START -->
                <div class="ap-field mb-0">
                  <label class="ap-label" for="payment_amount">Amount</label>
                  <div
                    class="ap-amount"
                    :class="{ 'is-invalid': errors.hasOwnProperty('amount') }"
                  >
                    <span class="ap-amount-prefix">$</span>
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
                      placeholder="0.00"
                      inputmode="decimal"
                    />
                  </div>
                  <small class="ap-error" v-if="errors.amount">{{ errors.amount }}</small>
                </div>
                <!-- Amount : END -->
              </div>
              <!-- Section: Payment : END -->

              <!-- Section: Customer : START -->
              <div class="ap-section">
                <p class="ap-section-title">Customer</p>
                <div class="ap-grid">
                  <!-- Name : START -->
                  <div class="ap-field ap-span-2">
                    <label class="ap-label" for="payment_name"
                      >Full Name <span class="ap-required">*</span></label
                    >
                    <div class="ap-input-icon">
                      <i class="bx bx-user"></i>
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
                    <small class="ap-error" v-if="errors.name">{{ errors.name }}</small>
                  </div>
                  <!-- Name : END -->
                  <!-- Email : START -->
                  <div class="ap-field">
                    <label class="ap-label" for="payment_email">Email</label>
                    <div class="ap-input-icon">
                      <i class="bx bx-envelope"></i>
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
                    <small class="ap-error" v-if="errors.email">{{ errors.email }}</small>
                  </div>
                  <!-- Email : END -->
                  <!-- Phone : START -->
                  <div class="ap-field">
                    <label class="ap-label" for="payment_phone"
                      >Phone <span class="ap-hint">(10 digits)</span></label
                    >
                    <div class="ap-input-icon">
                      <i class="bx bx-phone"></i>
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
                    <small class="ap-error" v-if="errors.phone">{{ errors.phone }}</small>
                  </div>
                  <!-- Phone : END -->
                </div>
              </div>
              <!-- Section: Customer : END -->

              <!-- Section: Details : START -->
              <div class="ap-section">
                <p class="ap-section-title">Details</p>
                <div class="ap-grid">
                  <!-- Date : START -->
                  <div class="ap-field" v-show="!showStripeAction">
                    <label class="ap-label" for="payment_date">Date</label>
                    <fieldset
                      class="ap-date"
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
                    <small class="ap-error" v-if="errors.date">{{ errors.date }}</small>
                  </div>
                  <!-- Date : END -->

                  <!-- Reason : START -->
                  <div class="ap-field">
                    <label class="ap-label" for="reason">Reason</label>
                    <v-select
                      id="payment_reason"
                      placeholder="Select or type a reason"
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

                  <!-- Transaction ID : START -->
                  <div
                    class="ap-field"
                    v-show="!showStripeAction && form.type !== 'Cash'"
                  >
                    <label class="ap-label" for="payment_transaction_id"
                      >Transaction #</label
                    >
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
                    <small class="ap-error" v-if="errors.transaction_id">{{
                      errors.transaction_id
                    }}</small>
                  </div>
                  <!-- Transaction ID : END -->

                  <!-- Receipt Number : START -->
                  <div class="ap-field" v-show="!showStripeAction">
                    <label class="ap-label" for="payment_receipt_number"
                      >Receipt #</label
                    >
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
                    <small class="ap-help">Will be autogenerated if left blank.</small>
                    <small class="ap-error" v-if="errors.receipt_number">{{
                      errors.receipt_number
                    }}</small>
                  </div>
                  <!-- Receipt Number : END -->
                </div>
              </div>
              <!-- Section: Details : END -->
            </form>
          </div>
          <!-- Modal Body : END -->
          <!-- Modal Footer : START -->
          <div class="ap-footer">
            <div class="ap-summary">
              <span class="ap-summary-label">{{
                showStripeAction ? "Payment link for" : form.status === "Refund" ? "Refund" : "Charge"
              }}</span>
              <span
                class="ap-summary-amount"
                :class="{ 'is-refund': form.status === 'Refund' && !showStripeAction }"
                >{{ formattedAmount }}</span
              >
              <span class="ap-summary-meta"
                >{{ form.type }}<template v-if="form.type !== 'Cash'"> · {{ form.mode }}</template></span
              >
            </div>
            <div class="ap-footer-actions">
              <button
                type="button"
                class="btn ap-ghost-btn"
                data-dismiss="modal"
              >
                Close
              </button>
              <button
                type="button"
                class="btn btn-primary ap-primary-btn"
                v-if="showStripeAction"
                @click.prevent="paymentOnlineForm"
                :disabled="form.name == '' || form.amount < 1"
              >
                <i class="bx bx-link-external"></i> Payment Link
              </button>
              <button
                type="button"
                class="btn btn-primary ap-primary-btn"
                v-else
                @click.prevent="savePayment"
                :disabled="form.name == '' || form.amount < 1"
              >
                <i class="bx bx-check"></i> Add Log
              </button>
            </div>
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
import DatePicker from "../Common/DatePickerAdapter.vue";
import "vue-datepicker-next/index.css";

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
    formattedAmount() {
      const value = parseFloat(this.form.amount);
      return this.$helpers.moneyFormatter(isNaN(value) ? 0 : value);
    },
  },
  methods: {
    ...mapActions("payment", ["getPaymentConfigurations"]),
    ...mapActions("paymentView", ["commitOnlyEntities"]),
    typeIcon(type) {
      switch (type) {
        case "Cash":
          return "bx-money";
        case "Credit":
          return "bx-credit-card";
        case "Bank Transfer":
        case "ACH":
        case "Draft":
          return "bxs-bank";
        case "Cheque":
          return "bx-receipt";
        case "Email Transfer":
          return "bx-envelope";
        case "Store Credit":
          return "bx-store";
        default:
          return "bx-wallet";
      }
    },
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
/* ==========================================================================
   Payment modal (AcceptPayment). Scoped to #paymentModal.
   ========================================================================== */
#paymentModal {
  --ap-border: #e6eaf0;
  --ap-surface: #ffffff;
  --ap-bg: #f6f8fb;
  --ap-text: #1f2937;
  --ap-text-2: #4b5563;
  --ap-muted: #8a94a6;
  --ap-primary: #5a8dee;
  --ap-primary-soft: #eef4ff;

  .modal-dialog {
    max-width: 760px;
  }
  .modal-content {
    border: 1px solid var(--ap-border);
    border-radius: 16px;
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
    overflow: hidden;
  }

  /* Header */
  .ap-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.2rem 1.5rem 1rem;
    border-bottom: 1px solid var(--ap-border);
  }
  .ap-heading {
    display: flex;
    gap: 0.85rem;
  }
  .ap-header-icon {
    width: 42px;
    height: 42px;
    flex-shrink: 0;
    border-radius: 12px;
    background: #e3f6e8;
    color: #1e8a43;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
  }
  .ap-title {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--ap-text);
  }
  .ap-subtitle {
    margin: 0.2rem 0 0;
    max-width: 560px;
    font-size: 0.83rem;
    line-height: 1.45;
    color: var(--ap-muted);
  }
  .ap-close {
    width: 34px;
    height: 34px;
    flex-shrink: 0;
    border: 0;
    border-radius: 8px;
    background: transparent;
    color: var(--ap-muted);
    font-size: 1.4rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    &:hover {
      background: #f1f4f8;
      color: var(--ap-text);
    }
  }

  /* Body */
  .ap-body {
    padding: 0;
  }
  .ap-banner {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    margin: 1rem 1.5rem 0;
    padding: 0.65rem 0.85rem;
    border-radius: 10px;
    background: var(--ap-primary-soft);
    color: #2f62d6;
    font-size: 0.85rem;
    i {
      font-size: 1.15rem;
      margin-top: 0.05rem;
    }
  }
  .ap-section {
    padding: 1.1rem 1.5rem 1.2rem;
    border-bottom: 1px solid var(--ap-border);
    &:last-child {
      border-bottom: 0;
    }
  }
  .ap-section-title {
    margin: 0 0 0.85rem;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--ap-muted);
  }
  .ap-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0 1rem;
  }
  .ap-span-2 {
    grid-column: 1 / -1;
  }
  .ap-field {
    margin-bottom: 1rem;
  }
  .ap-label {
    display: block;
    margin-bottom: 0.4rem;
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--ap-text-2);
    text-transform: none;
    letter-spacing: 0;
  }
  .ap-hint {
    color: var(--ap-muted);
    font-weight: 400;
  }
  .ap-required {
    color: #c23434;
  }
  .ap-help {
    display: block;
    margin-top: 0.3rem;
    font-size: 0.78rem;
    color: var(--ap-muted);
  }
  .ap-error {
    display: block;
    margin-top: 0.3rem;
    font-size: 0.8rem;
    color: #c23434;
  }

  /* Inputs */
  .webapp-form .form-control,
  .webapp-form input.form-control {
    height: 44px;
    margin-top: 0;
    border: 1px solid var(--ap-border);
    border-radius: 10px;
    font-size: 0.95rem;
    color: var(--ap-text);
    box-shadow: none;
    transition: border-color 0.15s, box-shadow 0.15s;
    &:hover {
      border-color: #d6dce5;
    }
    &:focus {
      border-color: var(--ap-primary);
      box-shadow: 0 0 0 3px rgba(90, 141, 238, 0.15);
    }
    &.is-invalid {
      border-color: #e05252;
      background-image: none;
    }
  }
  .ap-input-icon {
    position: relative;
    > i {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--ap-muted);
      font-size: 1.1rem;
      pointer-events: none;
      z-index: 1;
    }
    .form-control {
      padding-left: 2.4rem;
    }
  }

  /* Type chips */
  .ap-chips {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 0.5rem;
  }
  .ap-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.35rem;
    height: 40px;
    padding: 0 0.5rem;
    border: 1px solid var(--ap-border);
    border-radius: 10px;
    background: var(--ap-surface);
    color: var(--ap-text-2);
    font-size: 0.85rem;
    font-weight: 500;
    white-space: nowrap;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s, color 0.15s;
    i {
      font-size: 1.05rem;
      color: var(--ap-muted);
    }
    &:hover {
      border-color: #d6dce5;
      color: var(--ap-text);
    }
    &.active {
      border-color: var(--ap-primary);
      background: var(--ap-primary-soft);
      color: var(--ap-primary);
      box-shadow: 0 0 0 1px var(--ap-primary) inset;
      i {
        color: var(--ap-primary);
      }
    }
  }

  /* Segmented (mode / status) */
  .ap-segmented {
    display: flex;
    gap: 0.25rem;
    padding: 0.25rem;
    border-radius: 12px;
    background: #f1f4f8;
  }
  .ap-segment {
    flex: 1 1 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.35rem;
    height: 36px;
    border: 0;
    border-radius: 9px;
    background: transparent;
    color: var(--ap-text-2);
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.15s, color 0.15s, box-shadow 0.15s;
    i {
      font-size: 1.05rem;
    }
    &:hover:not(:disabled) {
      color: var(--ap-text);
    }
    &.active {
      background: var(--ap-surface);
      color: var(--ap-primary);
      box-shadow: 0 1px 3px rgba(15, 23, 42, 0.12);
    }
    &.active.is-refund {
      color: #c23434;
    }
    &:disabled {
      opacity: 0.45;
      cursor: not-allowed;
    }
  }

  /* Amount */
  .ap-amount {
    position: relative;
    max-width: 320px;
    .ap-amount-prefix {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1.35rem;
      font-weight: 600;
      color: var(--ap-muted);
      pointer-events: none;
      z-index: 1;
    }
    input.form-control {
      height: 52px;
      padding-left: 2.1rem;
      font-size: 1.4rem;
      font-weight: 700;
      font-variant-numeric: tabular-nums;
    }
  }

  /* Date picker */
  .ap-date {
    border: 0;
    padding: 0;
    margin: 0;
    .mx-datepicker {
      width: 100%;
    }
    .mx-input {
      height: 44px;
      padding: 0 2.2rem 0 0.9rem;
      border: 1px solid var(--ap-border);
      border-radius: 10px;
      box-shadow: none;
      font-size: 0.95rem;
      color: var(--ap-text);
      &:hover {
        border-color: #d6dce5;
      }
      &:focus {
        border-color: var(--ap-primary);
        box-shadow: 0 0 0 3px rgba(90, 141, 238, 0.15);
      }
    }
    .is-invalid .mx-input {
      border-color: #e05252;
    }
  }

  /* Reason (v-select) */
  .v-select {
    .vs__dropdown-toggle {
      min-height: 44px;
      padding: 0 0.4rem;
      border: 1px solid var(--ap-border);
      border-radius: 10px;
      background: var(--ap-surface);
    }
    &.vs--open .vs__dropdown-toggle {
      border-color: var(--ap-primary);
      box-shadow: 0 0 0 3px rgba(90, 141, 238, 0.15);
    }
    .vs__search,
    .vs__selected {
      font-size: 0.95rem;
      color: var(--ap-text);
    }
    .vs__search::placeholder {
      color: var(--ap-muted);
    }
    .vs__dropdown-menu {
      border: 1px solid var(--ap-border);
      border-radius: 10px;
      box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
      margin-top: 4px;
    }
    .vs__dropdown-option--highlight {
      background: var(--ap-primary-soft);
      color: var(--ap-primary);
    }
  }

  /* Footer */
  .ap-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    padding: 0.9rem 1.5rem;
    border-top: 1px solid var(--ap-border);
    background: var(--ap-bg);
    border-radius: 0 0 16px 16px;
  }
  .ap-summary {
    display: flex;
    align-items: baseline;
    gap: 0.5rem;
    min-width: 0;
  }
  .ap-summary-label {
    font-size: 0.85rem;
    color: var(--ap-muted);
  }
  .ap-summary-amount {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--ap-text);
    font-variant-numeric: tabular-nums;
    &.is-refund {
      color: #c23434;
    }
  }
  .ap-summary-meta {
    font-size: 0.85rem;
    color: var(--ap-text-2);
  }
  .ap-footer-actions {
    display: flex;
    gap: 0.5rem;
    margin-left: auto;
  }
  .ap-primary-btn {
    height: 40px;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0 1.15rem;
    border-radius: 10px;
    font-weight: 500;
    line-height: 1;
    i {
      position: static;
      top: 0;
      font-size: 1.1rem;
      line-height: 1;
    }
    &:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }
  }
  .ap-ghost-btn {
    height: 40px;
    padding: 0 1rem;
    border-radius: 10px;
    border: 1px solid var(--ap-border);
    background: var(--ap-surface);
    color: var(--ap-text-2);
    font-weight: 500;
    &:hover {
      background: #fff;
      color: var(--ap-text);
    }
  }

  @media (max-width: 767.98px) {
    .ap-chips {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    .ap-grid {
      grid-template-columns: 1fr;
    }
    .ap-amount {
      max-width: none;
    }
  }
}
</style>
