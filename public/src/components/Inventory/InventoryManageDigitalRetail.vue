<template>
  <div
    class="tab-pane"
    id="inventory-digital-retail-panel"
    aria-labelledby="Inventory Purchase Panel"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="inventory-digital-retail-initial-section">
      <div class="row match-height">
        <div class="col-md-12 col-12">
          <div class="card shadow-none mb-0">
            <div class="card-content">
              <div class="card-body pt-0">
                <form class="form form-horizontal webapp-form">
                  <div class="form-body">
                    <div class="row">
                      <div class="col-md-12 col-12">
                        <div class="m-width-s">
                          <div class="row">
                            <div class="col-md-4">
                              <label>Override Default Finance Terms</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <div class="custom-control custom-switch">
                                <input
                                  type="checkbox"
                                  class="custom-control-input"
                                  id="override_default_finance_terms"
                                  name="Visibility"
                                  v-model="form.override_default_finance_terms"
                                  :true-value="'1'"
                                  :false-value="'0'"
                                  checked
                                />
                                <label
                                  class="custom-control-label"
                                  for="override_default_finance_terms"
                                >
                                  <span class="switch-icon-left"> ON</span>
                                  <span class="switch-icon-right">OFF</span>
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="m-width-s" v-show="showCustomFinanceTerms">
                          <!-- Repeater : START -->
                          <div class="list-image-content">
                            <div class="row contact-repeater">
                              <div class="col-md-3">
                                <button
                                  class="btn btn-icon btn-primary"
                                  type="button"
                                  @click.prevent="addFinanceTerms"
                                >
                                  <i class="bx bx-plus"></i>Add Another Term
                                </button>
                              </div>

                              <div
                                class="col-md-9 form-group"
                                data-repeater-list="contact"
                              >
                                <!-- start Loop for vehicle tags -->
                                <div
                                  class="row"
                                  v-for="n in getFinanceTermsCount"
                                  :key="n"
                                >
                                  <div class="col-md-4 col-12 form-group">
                                    <input
                                      type="text"
                                      class="form-control"
                                      placeholder="Downpayment"
                                      :class="{
                                        'is-invalid': checkIsInvalid(
                                          n - 1,
                                          'downpayment'
                                        ),
                                      }"
                                      @keyup="
                                        deleteErrorAttribute(
                                          n - 1,
                                          'downpayment'
                                        )
                                      "
                                      :title="
                                        titleForErrorAttribute(
                                          n - 1,
                                          'downpayment'
                                        )
                                      "
                                      v-model="
                                        form.finance_terms[n - 1].downpayment
                                      "
                                    />
                                  </div>
                                  <div class="col-md-4 col-12 form-group">
                                    <input
                                      type="number"
                                      class="form-control"
                                      placeholder="Interest Rate %"
                                      :class="{
                                        'is-invalid': checkIsInvalid(
                                          n - 1,
                                          'interest_rate'
                                        ),
                                      }"
                                      @keyup="
                                        deleteErrorAttribute(
                                          n - 1,
                                          'interest_rate'
                                        )
                                      "
                                      :title="
                                        titleForErrorAttribute(
                                          n - 1,
                                          'interest_rate'
                                        )
                                      "
                                      v-model="
                                        form.finance_terms[n - 1].interest_rate
                                      "
                                    />
                                  </div>
                                  <div class="col-md-4 col-12 form-group">
                                    <input
                                      type="number"
                                      class="form-control"
                                      placeholder="Months"
                                      :class="{
                                        'is-invalid': checkIsInvalid(
                                          n - 1,
                                          'term'
                                        ),
                                      }"
                                      @keyup="
                                        deleteErrorAttribute(n - 1, 'term')
                                      "
                                      :title="
                                        titleForErrorAttribute(n - 1, 'term')
                                      "
                                      v-model="form.finance_terms[n - 1].term"
                                    />
                                  </div>
                                  <div class="col-md-3 col-12 form-group">
                                    <select
                                      class="custom-select form-control"
                                      name="Payment Frequency"
                                      v-model="
                                        form.finance_terms[n - 1].frequency
                                      "
                                    >
                                      <option value="" selected disabled>
                                        Select Payment
                                      </option>
                                      <option value="Weekly">Weekly</option>
                                      <option value="Bi-Weekly"
                                        >Bi-Weekly</option
                                      >
                                      <option value="Monthly">Monthly</option>
                                    </select>
                                  </div>
                                  <div class="col-md-3 col-12 form-group">
                                    <select
                                      class="custom-select form-control"
                                      name="Credit Score Band"
                                      v-model="
                                        form.finance_terms[n - 1]
                                          .credit_score_band
                                      "
                                    >
                                      <option value="" selected disabled>
                                        Select Payment
                                      </option>
                                      <option value="Excellent"
                                        >Excellent</option
                                      >
                                      <option value="Great">Great</option>
                                      <option value="Very Good"
                                        >Very Good</option
                                      >
                                      <option value="Good">Good</option>
                                      <option value="Fair">Fair</option>
                                      <option value="Challenged"
                                        >Challenged</option
                                      >
                                    </select>
                                  </div>
                                  <div class="col-md-2 col-12 form-group pl-0">
                                    <button
                                      class="btn btn-danger text-nowrap px-1"
                                      type="button"
                                      @click.prevent="
                                        form.finance_terms.splice(n - 1, 1);
                                        errors = [];
                                      "
                                    >
                                      <i class="bx bx-x"></i>
                                    </button>
                                  </div>
                                </div>
                                <!-- end Loop for vehicle tags -->
                              </div>
                            </div>
                          </div>
                          <!-- Repeater : END-->
                        </div>
                      </div>
                    </div>
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
import Toast from "../../library/Toast";

export default {
  name: "InventoryManageDigitalRetail",
  data() {
    return {
      errors: [],
      digital_retailing_default_finance_terms: [],
      form: {
        override_default_finance_terms: "0",
        finance_terms: [],
      },
    };
  },
  computed: {
    showCustomFinanceTerms() {
      return parseInt(this.form.override_default_finance_terms, 10) === 1;
    },
    getFinanceTermsCount() {
      let count = 0;
      let finance_terms = this.form.finance_terms;
      if (Array.isArray(finance_terms)) {
        if (finance_terms.length > 0) {
          count = finance_terms.length;
        }
      }

      return count;
    },
  },
  methods: {
    async save() {
      const vm = this;
      let formData = new FormData();
      formData.append(
        "override_default_finance_terms",
        this.form.override_default_finance_terms
      );

      for (let key = 0; key < this.getFinanceTermsCount; key++) {
        formData.append(
          `finance_terms[${key}][frequency]`,
          this.form.finance_terms[key]["frequency"]
        );

        formData.append(
          `finance_terms[${key}][interest_rate]`,
          this.form.finance_terms[key]["interest_rate"]
        );

        formData.append(
          `finance_terms[${key}][downpayment]`,
          this.form.finance_terms[key]["downpayment"]
        );

        formData.append(
          `finance_terms[${key}][term]`,
          this.form.finance_terms[key]["term"]
        );

        formData.append(
          `finance_terms[${key}][credit_score_band]`,
          this.form.finance_terms[key]["credit_score_band"]
        );
      }
      let request_url = `${window.location.origin}/api/business/inventory/${this.$route.params.id}/common/digital-retailing`;
      let message = "Saving e-Quote details for an inventory...";
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
          toast.success(data.success);
          toast.close();

          setTimeout(function() {
            Event.fire("save_complete", []);
          }, 2000);
        })
        .catch((error) => {
          vm.$data.errors = error.response.data;
          let error_message = "There were some errors in saving the details.";
          toast.fail(error_message);
          toast.close();

          setTimeout(function() {
            Event.fire("save_complete", []);
          }, 2000);
        });
    },
    async getInventoryDigitalRetailData(inventory_id) {
      const vm = this;
      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/inventory/${inventory_id}/common/digital-retailing`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          if (data.hasOwnProperty("0")) {
            let entity = data[0];
            this.form.override_default_finance_terms =
              entity["override_default_finance_terms"];
            this.form.finance_terms = JSON.parse(entity["finance_terms"]);
          }
        })
        .catch(({ response }) => {
          this.form.finance_terms = this.digital_retailing_default_finance_terms;
        });
    },
    async getFinanceTerms() {
      let request_url = `${window.location.origin}/api/business/digital-retailing/settings/finance`;

      await axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          if (data.hasOwnProperty("value")) {
            this.digital_retailing_default_finance_terms = JSON.parse(
              data.value
            );
          }
        })
        .catch(() => {});
    },
    addFinanceTerms() {
      this.form.finance_terms.push({
        frequency: "Bi-Weekly",
        interest_rate: "",
        downpayment: "",
        term: "",
        credit_score_band: "Excellent",
      });
    },
    checkIsInvalid(key, attribute) {
      if (this.errors.hasOwnProperty(key)) {
        if (this.errors[key].hasOwnProperty(attribute)) {
          return true;
        }
      }

      return false;
    },
    deleteErrorAttribute(key, attribute) {
      if (this.errors.hasOwnProperty(key)) {
        if (this.errors[key].hasOwnProperty(attribute)) {
          delete this.errors[key][attribute];
        }
      }
    },
    titleForErrorAttribute(key, attribute) {
      if (this.errors.hasOwnProperty(key)) {
        if (this.errors[key].hasOwnProperty(attribute)) {
          return this.errors[key][attribute];
        }
      }
    },
  },
  async created() {
    await this.getFinanceTerms();
    await this.getInventoryDigitalRetailData(this.$route.params.id);
  },
  async mounted() {
    const vm = this;

    Event.listen("save_inventory_digital_retailing", (data) => {
      vm.save();
      vm.$helpers.gTagClickEvent(
        vm,
        "zd_action",
        "inventory",
        "digital_retailing",
        "save"
      );
    });
  },
  beforeDestroy() {
    Event.vue.$off("save_complete", null);
  },
};
</script>
