<template>
  <!-- Model Deal Modal : START -->
  <div
    class="modal fade"
    :id="current_modal_id"
    tabindex="-1"
    role="dialog"
    :aria-labelledby="`${modal_title}`"
    aria-hidden="true"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ modal_title }}</h5>
          <button
            type="button"
            :id="`${current_modal_id}Close`"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <!-- type : START -->
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="deal_payments_type">Type</label>
                <select
                  class="custom-select form-control mr-1"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('type'),
                  }"
                  @keyup="delete errors.type"
                  :title="errors.type"
                  v-model="form.type"
                  placeholder="Type"
                  id="deal_payments_type"
                >
                  <option value="" selected disabled>Type</option>
                  <option value="Cash">Cash</option>
                  <option value="Debit">Debit</option>
                  <option value="Credit">Credit</option>
                  <option value="Cheque">Cheque</option>
                  <option value="EFT">EFT</option>
                </select>
              </div>
            </div>
            <!-- type : END -->

            <!-- description : START -->
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="deal_payments_description">Description : </label>
                <textarea
                  type="text"
                  class="form-control"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('description'),
                  }"
                  @keyup="delete errors.description"
                  :title="errors.description"
                  v-model="form.description"
                  id="deal_payments_description"
                  placeholder="Description"
                ></textarea>
              </div>
            </div>
            <!-- description : END -->

            <!-- downpayment or deposit : START -->
            <div class="form-row">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <label
                    for=""
                    style="display: inline-block; vertical-align: super"
                    class="mr-1"
                    >Is Downpayment or Deposit ?
                  </label>
                  <div class="form-check form-check-inline">
                    <input
                      class="form-check-input"
                      v-model="form.is_downpayment_or_deposit"
                      type="radio"
                      id="downpayment_payment"
                      value="0"
                    />
                    <label class="form-check-label" for="downpayment_payment"
                      >Downpayment</label
                    >
                  </div>
                  <div class="form-check form-check-inline">
                    <input
                      class="form-check-input"
                      v-model="form.is_downpayment_or_deposit"
                      type="radio"
                      id="deposit_payment"
                      value="1"
                    />
                    <label class="form-check-label" for="deposit_payment"
                      >Deposit</label
                    >
                  </div>
                </div>
              </div>
            </div>
            <!-- downpayment or deposit : END -->

            <!-- combination : START -->
            <div class="form-row">
              <!-- amount : START -->
              <div class="form-group col-md-12">
                <label for="deal_payments_amount">Amount</label>
                <input
                  type="text"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('amount'),
                  }"
                  @keyup="delete errors.amount"
                  :title="errors.amount"
                  v-model="form.amount"
                  placeholder="Amount"
                  class="form-control"
                  id="deal_payments_amount"
                />
              </div>
              <!-- amount : END -->
            </div>
            <!-- combindation : END -->
          </form>
        </div>
        <!-- modal footer : START -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Close
          </button>
          <button
            :disabled="price_recalculation"
            type="button"
            @click="validatePartialData(form)"
            class="btn btn-primary"
          >
            <i
              v-show="is_validating"
              class="bx bx-loader-alt bx-spin px-25 text-white"
              style="vertical-align: sub"
            ></i>
            Save
          </button>
        </div>
        <!-- modal footer : END -->
      </div>
    </div>
  </div>
  <!-- Model Deal Modal : END -->
</template>
<script>
export default {
  name: "DealImportPayment",
  data() {
    return {
      load: true,
      modal_title: "Add Deal Payment",
      form: {
        amount: 0,
        description: "",
        type: "",
        is_downpayment_or_deposit: "0",
      },
      errors: {},
      price_recalculation: false,
      current_action: "add",
      current_module: "payments",
      current_module_index: null,
      current_modal_id: "dealPaymentModal",
      is_validating: false,
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
  },
  methods: {
    async validatePartialData(form) {
      const vm = this;
      let formData = new FormData();
      for (const [key, value] of Object.entries(form)) {
        formData.append(key, value);
      }

      let request_url = `${window.location.origin}/api/business/deals/common/${this.current_module}/validate`;

      this.is_validating = true;

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
          Event.fire("deal_import_worksheet_entity_save", {
            action: vm.current_action,
            module: vm.current_module,
            payload: vm.form,
            module_index: vm.current_module_index,
          });

          vm.resetForm();
          vm.is_validating = false;
          jQuery(`#${vm.current_modal_id}`).modal("hide");
        })
        .catch(function (error) {
          vm.errors = error.response.data;
          vm.is_validating = false;
        });
    },
    resetForm() {
      this.form.amount = 0;
      this.form.description = "";
      (this.form.type = ""), (this.form.is_downpayment_or_deposit = "0");

      this.errors = {};
      this.modal_title = "Add Deal Payment";
      this.current_action = "add";
      this.current_module_index = null;
    },
  },
  mounted() {
    const vm = this;
    jQuery(`#${vm.current_modal_id}`).on("hidden.bs.modal", function () {
      vm.resetForm();
    });

    Event.listen(
      "deal_import_worksheet_entity",
      ({ action, module, payload = null, module_index = null }) => {
        vm.resetForm();

        if (module === vm.current_module) {
          vm.current_action = action;
          jQuery(`#${vm.current_modal_id}`).modal("show");

          if (action === "update") {
            if (payload && module_index !== null) {
              vm.current_module_index = module_index;
              vm.modal_title = "Update Deal Payment";

              for (const [key, value] of Object.entries(vm.form)) {
                vm.form[key] = payload[key];
              }
            }
          }
        }
      }
    );
  },
};
</script>