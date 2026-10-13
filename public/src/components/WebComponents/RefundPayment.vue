<template>
  <div>
    <!-- Refund Payment : START  -->
    <div
      class="modal fade"
      id="refundModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Refund Payment"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <!-- Modal Header : START -->
          <div class="modal-header">
            <h5 class="modal-title" id="refundPaymentHeader">
              Refund Payment
            </h5>
            <button
              type="button"
              id="refundModalClose"
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
            <form>
              <div class="form-row">
                <h6 class="form-group col-md-12 font-italic">
                  Review all the refund details carefully. One the request for
                  refund is generated, it cannot be canceled.
                </h6>
                <!-- Name : START -->
                <div class="form-group col-md-12">
                  <label for="payment_name">Full Name: </label>
                  <input
                    readonly
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
                <!-- Transaction ID : START -->
                <div class="form-group col-md-12">
                  <label for="payment_transaction_id">Transaction ID: </label>
                  <input
                    readonly
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('transaction_id'),
                    }"
                    @keyup="delete errors.transaction_id"
                    :title="errors.transaction_id"
                    v-model="form.transaction_id"
                    id="payment_transaction_id"
                    placeholder="Transaction ID"
                  />
                </div>
                <!-- Transaction ID : END -->

                <!-- Amount : START -->
                <div class="form-group col-md-12">
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
              class="btn btn-danger"
              @click.prevent="refundForm"
              :disabled="form.transaction_id == '' || form.amount < 1"
            >
              Proceed
            </button>
          </div>
          <!-- Modal Footer : END -->
        </div>
      </div>
    </div>
    <!-- Refund Payment : END  -->
  </div>
</template>
<script>
import Toast from "../../library/Toast";
export default {
  name: "RefundPayment",
  data() {
    return {
      form: {
        name: "",
        transaction_id: "",
        amount: 0,
      },
      errors: [],
    };
  },
  watch: {
    "form.amount": function(newVal, oldVal) {
      let amount =
        !isNaN(this.form.amount) && this.form.amount != ""
          ? this.form.amount
          : 0;
      this.form.amount = amount;
    },
  },
  methods: {
    async refundForm() {
      const vm = this;
      let formData = new FormData();
      for (const key in this.form) {
        formData.append(key, this.form[key]);
      }

      let request_url =
        window.location.origin + "/api/business/payments/online/refund";
      let message = "Generating Refund Request...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
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
          this.errors = [];
          for (const key in this.form) {
            this.form[key] = "";
          }

          this.form.amount = 0;
          let success_message = data.success;
          toast.success(success_message);
          toast.close();

          document.getElementById("refundModalClose").click();
        })
        .catch((error) => {
          let error_obj = error.response.data;
          let error_message =
            "We encountered an error. Please check the stripe dashboard for more details.";
          if (error_obj.hasOwnProperty("errors")) {
            if (typeof error_obj.errors === "string") {
              error_message = error_obj.errors;
            }
          }
          toast.fail(error_message);
          toast.close();
          vm.$data.errors = error_obj;
        });
    },
  },
  mounted() {
    Event.listen(
      "event_payment_refund",
      ({ transaction_id, name = "", amount = 0 }) => {
        this.form.transaction_id = transaction_id;
        this.form.name = name;
        this.form.amount = amount;
        $("#refundModal").modal("show");
      }
    );
  },
  beforeDestroy() {
    Event.vue.$off("event_payment_refund", null);
  },
};
</script>
