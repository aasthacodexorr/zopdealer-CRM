<template>
  <div
    class="tab-pane"
    id="lead-view-payment-panel"
    aria-labelledby="Lead Payments"
    role="tabpanel"
  >
    <section id="lead-payment">
      <div class="row match-height m-1">
        <div class="col-12 col-xs-12">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col" class="text-center">Date</th>
                  <th scope="col" class="text-center">Transaction ID</th>
                  <th scope="col" class="text-center">Amount</th>
                  <th scope="col" class="text-center">Status</th>
                  <th scope="col" class="text-center">Sub Status</th>
                  <th scope="col" class="text-center">Receipt Url</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="payment in payments"
                  :key="`lead_payment_` + payment.id"
                >
                  <td class="text-center">
                    {{ convertUnixToDate(payment.date) }}
                  </td>
                  <td class="text-center">{{ payment.transaction_id }}</td>
                  <td class="text-center">
                    {{ formatAmount(payment.amount) }}
                  </td>
                  <td
                    class="text-center"
                    v-html="switchStatus(payment.status)"
                  ></td>
                  <td
                    class="text-center"
                    v-html="switchStatus(payment.sub_status)"
                  ></td>
                  <td class="text-center">
                    <a
                      v-if="payment.receipt_url"
                      target="_blank"
                      :href="payment.receipt_url"
                      ><i class="bx bx-link-external btn btn-secondary"></i
                    ></a>
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
export default {
  props: {
    id: { required: true },
    allUsers: { required: true },
    app_metadata: { required: true },
  },
  name: "LeadPayment",
  data() {
    return {
      payments: [],
    };
  },
  computed: {
    updateView() {
      return this.$route.params.id && this.$route.name == "lead-response-show";
    },
  },
  methods: {
    convertUnixToDate(unix_date) {
      return moment.unix(unix_date).format("MM/DD/YYYY");
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
          return `<span class='badge badge badge-light-success' title="${status}"><i style="vertical-align:text-top" class='bx bx-credit-card'></i> CREDIT</span>`;
          break;

        default:
          return `<span class='badge badge-light-secondary' title="${status}">NONE</span>`;
          break;
      }
    },
    getAllLeadPayments(lead_id) {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/payments/lead/show/" +
          lead_id,
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
  },
  created() {
    const vm = this;
    if (vm.updateView) {
      vm.getAllLeadPayments(this.$route.params.id);
    }
  },
};
</script>
