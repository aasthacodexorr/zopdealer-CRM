<template>
  <div
    class="tab-pane"
    id="inventory-digital-retail-panel"
    aria-labelledby="Inventory Purchase Panel"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="inventory-digital-retail-initial-section" class="eq-page">
      <form class="form form-horizontal webapp-form">
        <div class="m-width-s eq-card eq-toggle-card">
          <div class="eq-toggle-row">
            <span class="eq-icon"><i class="bx bx-calculator"></i></span>
            <label class="eq-toggle-label">Override Default Finance Terms</label>
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

        <div class="m-width-s eq-card" v-show="showCustomFinanceTerms">
          <!-- Repeater : START -->
          <div class="list-image-content">
            <div class="contact-repeater">
              <div class="eq-terms-head">
                <button
                  class="btn btn-icon btn-primary eq-add-btn"
                  type="button"
                  @click.prevent="addFinanceTerms"
                >
                  <i class="bx bx-plus"></i>Add Another Term
                </button>
              </div>

              <div class="eq-terms" data-repeater-list="contact">
                <!-- start Loop for vehicle tags -->
                <div
                  class="eq-term"
                  v-for="n in getFinanceTermsCount"
                  :key="n"
                >
                  <div class="eq-field eq-field--money">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Downpayment"
                      :class="{
                        'is-invalid': checkIsInvalid(n - 1, 'downpayment'),
                      }"
                      @keyup="deleteErrorAttribute(n - 1, 'downpayment')"
                      :title="titleForErrorAttribute(n - 1, 'downpayment')"
                      v-model="form.finance_terms[n - 1].downpayment"
                    />
                  </div>
                  <div class="eq-field eq-field--pct">
                    <input
                      type="number"
                      class="form-control"
                      placeholder="Interest Rate %"
                      :class="{
                        'is-invalid': checkIsInvalid(n - 1, 'interest_rate'),
                      }"
                      @keyup="deleteErrorAttribute(n - 1, 'interest_rate')"
                      :title="titleForErrorAttribute(n - 1, 'interest_rate')"
                      v-model="form.finance_terms[n - 1].interest_rate"
                    />
                  </div>
                  <div class="eq-field eq-field--months">
                    <input
                      type="number"
                      class="form-control"
                      placeholder="Months"
                      :class="{
                        'is-invalid': checkIsInvalid(n - 1, 'term'),
                      }"
                      @keyup="deleteErrorAttribute(n - 1, 'term')"
                      :title="titleForErrorAttribute(n - 1, 'term')"
                      v-model="form.finance_terms[n - 1].term"
                    />
                  </div>
                  <div class="eq-field">
                    <select
                      class="custom-select form-control"
                      name="Payment Frequency"
                      v-model="form.finance_terms[n - 1].frequency"
                    >
                      <option value="" selected disabled>
                        Select Payment
                      </option>
                      <option value="Weekly">Weekly</option>
                      <option value="Bi-Weekly">Bi-Weekly</option>
                      <option value="Monthly">Monthly</option>
                    </select>
                  </div>
                  <div class="eq-field">
                    <select
                      class="custom-select form-control"
                      name="Credit Score Band"
                      v-model="form.finance_terms[n - 1].credit_score_band"
                    >
                      <option value="" selected disabled>
                        Select Payment
                      </option>
                      <option value="Excellent">Excellent</option>
                      <option value="Great">Great</option>
                      <option value="Very Good">Very Good</option>
                      <option value="Good">Good</option>
                      <option value="Fair">Fair</option>
                      <option value="Challenged">Challenged</option>
                    </select>
                  </div>
                  <div class="eq-remove">
                    <button
                      class="btn btn-danger text-nowrap eq-remove-btn"
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
      </form>
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
  beforeUnmount() {
    Event.vue.$off("save_complete", null);
  },
};
</script>
<style lang="scss">
/* Inventory — E-Quote (digital retailing) tab (look only). */
#inventory-digital-retail-panel .eq-page {
  padding: 8px 20px 96px;
  color: #0f172a;

  .eq-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 18px;
    margin-bottom: 16px;
    max-width: none;
  }
  .eq-toggle-row {
    display: flex;
    align-items: center;
    gap: 12px;
    .custom-switch { margin-left: auto; min-height: 30px; display: flex; align-items: center; }
  }
  .eq-icon {
    width: 36px;
    height: 36px;
    border-radius: 9px;
    background: #eff6ff;
    color: #2563eb;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
  }
  .eq-toggle-label {
    margin: 0;
    font-size: 14px;
    font-weight: 500;
    color: #1e293b;
    text-transform: none;
    letter-spacing: 0;
  }

  .eq-terms-head {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 12px;
  }
  .eq-add-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 36px;
    padding: 0 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    background: #2563eb !important;
    border-color: #2563eb !important;
    box-shadow: none !important;
    i { position: static; top: 0; font-size: 16px; }
  }
  .eq-terms {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .eq-term {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr)) repeat(2, minmax(0, 1.1fr)) 38px;
    gap: 10px;
    align-items: center;
    padding: 10px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
  }
  .eq-field { position: relative; min-width: 0; }
  .form-control,
  .custom-select {
    height: 38px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    color: #0f172a;
    background-color: #ffffff;
    box-shadow: none;
    &:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }
    &.is-invalid { border-color: #ef4444; }
  }
  .eq-remove-btn {
    width: 38px;
    height: 38px;
    padding: 0;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    background: #ffffff !important;
    border: 1px solid #fecaca !important;
    color: #dc2626 !important;
    box-shadow: none !important;
    i { position: static; top: 0; }
    &:hover { background: #fef2f2 !important; }
  }

  @media (max-width: 991px) {
    .eq-term { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .eq-remove { grid-column: 2; justify-self: end; }
  }
}
</style>

