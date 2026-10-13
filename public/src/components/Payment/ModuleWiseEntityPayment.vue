<template>
  <div
    class="tab-pane"
    id="generic-view-payment-panel"
    aria-labelledby="Payments"
    role="tabpanel"
  >
    <section id="generic-payment">
      <div class="row match-height m-1">
        <div
          class="col-12 col-xs-12 text-danger h6 font-weight-bold"
          style="font-size: 0.8em"
          v-show="show_notice"
        >
          Note: This section wont be shown on any paperwork. It is for Internal
          purpose.
        </div>
        <div class="col-12 col-xs-12">
          <div style="display: inline-block">
            <accept-payment
              :module_id="module_id"
              :module="module"
              :default_name="default_name"
              :default_phone="default_phone"
              :default_email="default_email"
            ></accept-payment>
          </div>
        </div>
        <div class="col-12 col-xs-12">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col" class="text-center">Date</th>
                  <th scope="col" class="text-center">Receipt ID</th>
                  <th scope="col" class="text-center">Amount</th>
                  <th scope="col" class="text-center">Type</th>
                  <th scope="col" class="text-center">Status</th>
                  <th scope="col" class="text-center">Sub Status</th>
                  <th scope="col" class="text-center">Reason</th>
                  <th scope="col" class="text-center">Receipt Url</th>
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
                  <td class="text-center">
                    {{ convertUnixToDate(payment.date) }}
                  </td>
                  <td class="text-center">{{ payment.receipt_number }}</td>
                  <td class="text-center">
                    {{ formatAmount(payment.amount) }}
                  </td>
                  <td
                    class="text-center"
                    v-html="switchStatus(payment.type)"
                  ></td>
                  <td
                    class="text-center"
                    v-html="switchStatus(payment.status)"
                  ></td>
                  <td
                    class="text-center"
                    v-html="switchStatus(payment.sub_status)"
                  ></td>
                  <td class="text-center">{{ payment.reason }}</td>
                  <td class="text-center">
                    <a
                      v-if="payment.receipt_url"
                      target="_blank"
                      :href="payment.receipt_url"
                      ><i class="bx bx-link-external btn btn-secondary"></i
                    ></a>
                    <button
                      class="btn btn-primary"
                      v-else
                      @click.prevent="downloadReceiptPDF(payment.id)"
                    >
                      <i class="bx bx-download"></i>
                    </button>
                  </td>
                  <td class="text-center">
                    <button
                      v-show="isAuthorized && !payment.event_id"
                      class="btn btn-link"
                      @click.prevent="confirmDeleteAction(payment.id)"
                    >
                      <i class="bx bx-trash text-danger"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
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
  },
  methods: {
    ...mapActions("paymentView", ["commitOnlyEntities"]),
    convertUnixToDate(unix_date) {
      return moment(unix_date).format("MM/DD/YYYY");
    },
    formatAmount(amount) {
      return this.$helpers.moneyFormatter(amount);
    },
    switchStatus(status) {
      switch (status) {
        case "succeeded":
          return `<span class='badge badge-light-success' title="${status}">SUCCESS</span>`;
          break;
        case "processing":
          return `<span class='badge badge-light-warning' title="${status}">IN PROCESS</span>`;
          break;
        case "canceled":
          return `<span class='badge badge-light-danger' title="${status}">FAILED</span>`;
          break;
        case "Charge":
          return `<span class='badge badge-light-success' title="${status}">CHARGE</span>`;
          break;
        case "Refund":
          return `<span class='badge badge-light-danger' title="${status}">REFUND</span>`;
          break;
        case "Online":
          return `<span class='badge badge badge-light-success' title="${status}"><i style="vertical-align:text-top" class='bx bx-laptop'></i> ONLINE</span>`;
          break;
        case "Credit":
          return `<span class='badge badge badge-light-primary' title="${status}"><i style="vertical-align:text-top" class='bx bx-credit-card'></i> ${status}</span>`;
          break;
        case "Cash":
          return `<span class='badge badge badge-light-info' title="${status}"><i style="vertical-align:text-top" class='bx bx-money'></i> ${status}</span>`;
          break;
        case "Bank Transfer":
          return `<span class='badge badge badge-light-success' title="${status}"><i style="vertical-align:text-top" class='bx bxs-bank'></i> ${status}</span>`;
          break;

        case "Cheque":
        case "ACH":
        case "Store Credit":
          return `<span class='badge badge badge-light-warning' title="${status}"><i style="vertical-align:text-top" class='bx bxs-bank'></i> ${status}</span>`;
          break;

        default:
          return `<span class='badge badge-light-secondary' title="${status}">${status}</span>`;
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
  beforeDestroy() {
    Event.vue.$off("reload_payments", null);
  },
};
</script>
