<template>
  <div
    class="tab-pane"
    id="generic-view-payment-panel"
    aria-labelledby="Payments"
    role="tabpanel"
  >
    <section id="generic-payment" class="pl-page">
      <div class="pl-card">
        <!-- Header : START -->
        <div class="pl-header">
          <div>
            <h4 class="pl-title">Payment Log</h4>
            <p class="pl-subtitle">
              {{ payments.length }}
              {{ payments.length == 1 ? "transaction" : "transactions" }}
            </p>
          </div>
          <div class="pl-accept">
            <accept-payment
              :module_id="module_id"
              :module="module"
              :default_name="default_name"
              :default_phone="default_phone"
              :default_email="default_email"
            ></accept-payment>
          </div>
        </div>
        <div class="pl-notice" v-show="show_notice">
          <i class="bx bx-info-circle"></i>
          Note: This section wont be shown on any paperwork. It is for Internal
          purpose.
        </div>
        <!-- Header : END -->

        <!-- Summary : START -->
        <div class="pl-stats" v-if="payments.length > 0">
          <div class="pl-stat">
            <span class="pl-stat-icon pl-tone-green"
              ><i class="bx bx-wallet"></i
            ></span>
            <div>
              <span class="pl-stat-label">Total received</span>
              <span class="pl-stat-value">{{
                formatAmount(totalReceived)
              }}</span>
            </div>
          </div>
          <div class="pl-stat">
            <span class="pl-stat-icon pl-tone-red"
              ><i class="bx bx-undo"></i
            ></span>
            <div>
              <span class="pl-stat-label">Refunded</span>
              <span class="pl-stat-value">{{
                formatAmount(totalRefunded)
              }}</span>
            </div>
          </div>
          <div class="pl-stat">
            <span class="pl-stat-icon pl-tone-blue"
              ><i class="bx bx-receipt"></i
            ></span>
            <div>
              <span class="pl-stat-label">Transactions</span>
              <span class="pl-stat-value">{{ payments.length }}</span>
            </div>
          </div>
        </div>
        <!-- Summary : END -->

        <!-- Table : START -->
        <div class="table-responsive pl-table-wrap" v-if="payments.length > 0">
          <table class="table pl-table">
            <thead>
              <tr>
                <th scope="col">Date</th>
                <th scope="col">Receipt ID</th>
                <th scope="col" class="text-right">Amount</th>
                <th scope="col">Type</th>
                <th scope="col">Status</th>
                <th scope="col">Sub Status</th>
                <th scope="col">Reason</th>
                <th scope="col" class="text-center">Receipt</th>
                <th v-show="isAuthorized" scope="col" class="text-center">
                  Delete
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="payment in payments"
                :key="`entity_payment_` + payment.id"
              >
                <td>
                  <span class="pl-date"
                    ><i class="bx bx-calendar"></i>
                    {{ convertUnixToDate(payment.date) }}</span
                  >
                </td>
                <td>
                  <span
                    v-if="payment.receipt_number"
                    class="pl-mono"
                    :title="payment.receipt_number"
                    >{{ payment.receipt_number }}</span
                  >
                  <span v-else class="pl-muted">—</span>
                </td>
                <td class="text-right">
                  <span
                    class="pl-amount"
                    :class="{ 'is-refund': payment.sub_status === 'Refund' }"
                    >{{ formatAmount(payment.amount) }}</span
                  >
                </td>
                <td v-html="switchStatus(payment.type)"></td>
                <td v-html="switchStatus(payment.status)"></td>
                <td v-html="switchStatus(payment.sub_status)"></td>
                <td>
                  <span
                    v-if="payment.reason"
                    class="pl-reason"
                    :title="payment.reason"
                    >{{ payment.reason }}</span
                  >
                  <span v-else class="pl-muted">—</span>
                </td>
                <td class="text-center">
                  <a
                    v-if="payment.receipt_url"
                    class="pl-receipt-btn"
                    target="_blank"
                    rel="noopener"
                    :href="payment.receipt_url"
                    title="Open receipt"
                    ><i class="bx bx-link-external"></i> Open</a
                  >
                  <button
                    class="pl-receipt-btn"
                    v-else
                    title="Download receipt PDF"
                    @click.prevent="downloadReceiptPDF(payment.id)"
                  >
                    <i class="bx bx-download"></i> PDF
                  </button>
                </td>
                <td class="text-center" v-show="isAuthorized">
                  <button
                    v-show="isAuthorized && !payment.event_id"
                    class="pl-icon-btn pl-icon-btn--danger"
                    title="Delete payment"
                    @click.prevent="confirmDeleteAction(payment.id)"
                  >
                    <i class="bx bx-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Table : END -->

        <div class="pl-empty" v-else>
          <span class="pl-empty-icon"><i class="bx bx-credit-card"></i></span>
          <p class="pl-empty-title">No payments yet</p>
          <p class="pl-empty-text">
            Use the Payment button to record or collect a payment.
          </p>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
const moment = require("moment");
import AcceptPayment from "../WebComponents/AcceptPayment.vue";
import { mapActions } from "vuex";

export default {
  props: {
    allUsers: { required: true },
    app_metadata: { required: true },
    module: { required: true },
    show: { required: false, default: false },
    module_id: { required: true },
    show_notice: { required: false, default: false },
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
  },
  name: "ModuleWiseEntityPayment",
  data() {
    return {
      payments: [],
    };
  },
  components: {
    AcceptPayment,
  },
  computed: {
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    /** Successful payments that are not refunds */
    totalReceived() {
      return this.payments
        .filter(
          (payment) =>
            payment.status === "succeeded" && payment.sub_status !== "Refund"
        )
        .reduce((sum, payment) => sum + (parseFloat(payment.amount) || 0), 0);
    },
    totalRefunded() {
      return this.payments
        .filter(
          (payment) =>
            payment.status === "succeeded" && payment.sub_status === "Refund"
        )
        .reduce(
          (sum, payment) => sum + Math.abs(parseFloat(payment.amount) || 0),
          0
        );
    },
  },
  methods: {
    ...mapActions("paymentView", ["commitOnlyEntities"]),
    convertUnixToDate(unix_date) {
      return moment(unix_date).format("MMM D, YYYY");
    },
    formatAmount(amount) {
      return this.$helpers.moneyFormatter(amount);
    },
    switchStatus(status) {
      switch (status) {
        case "succeeded":
          return `<span class='pl-pill pl-pill--green' title="${status}"><i class='bx bx-check-circle'></i> Success</span>`;
          break;
        case "processing":
          return `<span class='pl-pill pl-pill--amber' title="${status}"><i class='bx bx-time-five'></i> In Process</span>`;
          break;
        case "canceled":
          return `<span class='pl-pill pl-pill--red' title="${status}"><i class='bx bx-x-circle'></i> Failed</span>`;
          break;
        case "Charge":
          return `<span class='pl-pill pl-pill--green' title="${status}"><i class='bx bx-down-arrow-alt'></i> Charge</span>`;
          break;
        case "Refund":
          return `<span class='pl-pill pl-pill--red' title="${status}"><i class='bx bx-undo'></i> Refund</span>`;
          break;
        case "Online":
          return `<span class='pl-pill pl-pill--green' title="${status}"><i class='bx bx-laptop'></i> Online</span>`;
          break;
        case "Credit":
          return `<span class='pl-pill pl-pill--blue' title="${status}"><i class='bx bx-credit-card'></i> ${status}</span>`;
          break;
        case "Cash":
          return `<span class='pl-pill pl-pill--teal' title="${status}"><i class='bx bx-money'></i> ${status}</span>`;
          break;
        case "Bank Transfer":
          return `<span class='pl-pill pl-pill--green' title="${status}"><i class='bx bxs-bank'></i> ${status}</span>`;
          break;

        case "Cheque":
        case "ACH":
        case "Store Credit":
          return `<span class='pl-pill pl-pill--amber' title="${status}"><i class='bx bxs-bank'></i> ${status}</span>`;
          break;

        default:
          return `<span class='pl-pill pl-pill--gray' title="${status}">${status}</span>`;
          break;
      }
    },
    async getAllEntityPayments(module, module_id) {
      const vm = this;
      await axios({
        method: "get",
        url: `${
          window.location.origin
        }/api/business/payments/${module.toLowerCase()}/show/${module_id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.payments = data;
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    async downloadReceiptPDF(id) {
      const vm = this;
      await axios({
        method: "get",
        responseType: "blob",
        url: `${window.location.origin}/api/business/payments/pdf/receipt/generate/${id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then((response) => {
          const file = new Blob([response.data], { type: "application/pdf" });
          const fileURL = URL.createObjectURL(file);
          window.open(fileURL);
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    confirmDeleteAction(id) {
      const vm = this;
      this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Confirm",
        toast: false,
        position: "center",
        text: "Please confirm if you want to delete the payment.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.deleteAPayment(id);
        }
      });
    },
    deleteAPayment(id) {
      const vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: `${window.location.origin}/api/business/payments/delete/${id}`,
      })
        .then((response) => {
          this.commitOnlyEntities();
          this.getAllEntityPayments(vm.module, vm.module_id);
          setTimeout(() => {
            this.$swal({
              showConfirmButton: false,
              icon: "success",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "Payment has been deleted.",
            });
          }, 1000);
        })
        .catch(function(error) {
          setTimeout(() => {
            vm.$swal({
              showConfirmButton: false,
              icon: "error",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "There were some errors in deleting data.",
            });
          }, 1000);
        });
    },
  },
  async created() {
    const vm = this;
    if (vm.show) {
      await vm.getAllEntityPayments(vm.module, vm.module_id);
    }
  },
  async mounted() {
    const vm = this;
    Event.listen("reload_payments", (data) => {
      if (vm.show) {
        vm.getAllEntityPayments(vm.module, vm.module_id);
      }
    });
  },
  beforeUnmount() {
    Event.vue.$off("reload_payments", null);
  },
};
</script>
<style lang="scss">
/* ==========================================================================
   Payment Log tab (shared by Lead and Deal detail pages).
   Scoped to #generic-view-payment-panel.
   ========================================================================== */
#generic-view-payment-panel {
  --pl-border: #e6eaf0;
  --pl-surface: #ffffff;
  --pl-bg: #f6f8fb;
  --pl-text: #1f2937;
  --pl-text-2: #4b5563;
  --pl-muted: #8a94a6;
  --pl-primary: #5a8dee;
  --pl-primary-soft: #eef4ff;

  .pl-page {
    padding: 1.5rem 1.75rem 6rem;
    background: var(--pl-bg);
    min-height: 100%;
  }
  .pl-card {
    background: var(--pl-surface);
    border: 1px solid var(--pl-border);
    border-radius: 14px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    overflow: hidden;
  }

  /* Header */
  .pl-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.2rem 1.4rem 1rem;
  }
  .pl-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    letter-spacing: -0.01em;
    color: var(--pl-text);
  }
  .pl-subtitle {
    margin: 0.15rem 0 0;
    font-size: 0.875rem;
    color: var(--pl-muted);
  }
  /* Style the AcceptPayment trigger without touching that component */
  .pl-accept > div > a.btn {
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.35rem;
    width: auto !important;
    padding: 0 1.1rem;
    border-radius: 10px;
    font-weight: 500;
    line-height: 1;
    i {
      position: static;
      top: 0;
      margin: 0 !important;
      font-size: 1.15rem;
      line-height: 1;
    }
  }
  .pl-notice {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    margin: 0 1.4rem 1rem;
    padding: 0.55rem 0.85rem;
    border-radius: 10px;
    background: #fff3d1;
    color: #8a5a00;
    font-size: 0.85rem;
    i {
      font-size: 1.1rem;
    }
  }

  /* Summary tiles */
  .pl-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.75rem;
    padding: 1rem 1.4rem;
    border-top: 1px solid var(--pl-border);
    border-bottom: 1px solid var(--pl-border);
  }
  .pl-stat {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.8rem 0.95rem;
    border: 1px solid var(--pl-border);
    border-radius: 12px;
    background: var(--pl-surface);
  }
  .pl-stat-icon {
    width: 40px;
    height: 40px;
    flex-shrink: 0;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
  }
  .pl-tone-green { background: #e3f6e8; color: #1e8a43; }
  .pl-tone-red { background: #fde6e6; color: #c23434; }
  .pl-tone-blue { background: #e6efff; color: #2f62d6; }
  .pl-stat-label {
    display: block;
    font-size: 0.78rem;
    font-weight: 500;
    color: var(--pl-muted);
  }
  .pl-stat-value {
    display: block;
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--pl-text);
    font-variant-numeric: tabular-nums;
  }

  /* Table */
  .pl-table-wrap {
    margin: 0;
  }
  .pl-table {
    margin: 0;
    width: 100%;
    color: var(--pl-text);
    font-size: 0.93rem;
    thead th {
      padding: 0.85rem 1rem;
      background: #f8fafc;
      border-top: 0;
      border-bottom: 1px solid var(--pl-border);
      color: var(--pl-text-2);
      font-size: 0.85rem;
      font-weight: 500;
      text-transform: none;
      letter-spacing: 0;
      white-space: nowrap;
      &:first-child {
        padding-left: 1.4rem;
      }
      &:last-child {
        padding-right: 1.4rem;
      }
    }
    tbody td {
      padding: 0.8rem 1rem;
      border-top: 0;
      border-bottom: 1px solid #eef1f5;
      vertical-align: middle;
      white-space: nowrap;
      &:first-child {
        padding-left: 1.4rem;
      }
      &:last-child {
        padding-right: 1.4rem;
      }
    }
    tbody tr:last-child td {
      border-bottom: 0;
    }
    tbody tr:hover {
      background: #f9fbff;
    }
  }
  .pl-date {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    i {
      color: var(--pl-muted);
      font-size: 1.05rem;
    }
  }
  .pl-mono {
    display: inline-block;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    vertical-align: middle;
    font-family: SFMono-Regular, Menlo, Consolas, monospace;
    font-size: 0.82rem;
    color: var(--pl-text-2);
    background: #f1f4f8;
    padding: 0.18rem 0.45rem;
    border-radius: 6px;
  }
  .pl-amount {
    font-weight: 600;
    font-variant-numeric: tabular-nums;
    color: var(--pl-text);
    &.is-refund {
      color: #c23434;
    }
  }
  .pl-reason {
    display: inline-block;
    max-width: 220px;
    overflow: hidden;
    text-overflow: ellipsis;
    vertical-align: middle;
    color: var(--pl-text-2);
  }
  .pl-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.22rem 0.6rem;
    border-radius: 7px;
    font-size: 0.8rem;
    font-weight: 500;
    line-height: 1.2;
    white-space: nowrap;
    i {
      font-size: 0.95rem;
    }
  }
  .pl-pill--green { background: #e3f6e8; color: #1e8a43; }
  .pl-pill--amber { background: #fff3d1; color: #a26a00; }
  .pl-pill--red { background: #fde6e6; color: #c23434; }
  .pl-pill--blue { background: #e6efff; color: #2f62d6; }
  .pl-pill--teal { background: #def6f3; color: #11806f; }
  .pl-pill--gray { background: #eef1f5; color: #5b6575; }
  .pl-receipt-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    height: 32px;
    padding: 0 0.7rem;
    border: 1px solid var(--pl-border);
    border-radius: 8px;
    background: var(--pl-surface);
    color: var(--pl-text-2);
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s, color 0.15s;
    i {
      font-size: 1rem;
    }
    &:hover {
      border-color: var(--pl-primary);
      background: var(--pl-primary-soft);
      color: var(--pl-primary);
    }
  }
  .pl-icon-btn {
    width: 32px;
    height: 32px;
    border: 0;
    border-radius: 8px;
    background: transparent;
    color: var(--pl-text-2);
    font-size: 1.1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }
  .pl-icon-btn--danger {
    color: #c23434;
    &:hover {
      background: #fde6e6;
    }
  }
  .pl-muted {
    color: var(--pl-muted);
  }

  /* Empty state */
  .pl-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 3rem 1.5rem 3.25rem;
    border-top: 1px solid var(--pl-border);
  }
  .pl-empty-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: var(--pl-primary-soft);
    color: var(--pl-primary);
    font-size: 1.7rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
  }
  .pl-empty-title {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 600;
    color: var(--pl-text);
  }
  .pl-empty-text {
    margin: 0.3rem 0 0;
    max-width: 360px;
    color: var(--pl-muted);
    font-size: 0.9rem;
  }

  @media (max-width: 767.98px) {
    .pl-page {
      padding: 1rem 1rem 6rem;
    }
    .pl-header {
      flex-wrap: wrap;
    }
  }
}
</style>
