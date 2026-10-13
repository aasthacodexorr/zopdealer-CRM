<template>
  <div v-if="tenantApp && isAuthorized">
    <div class="app-content content fixed-height-block">
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <router-link
              title="Back To Settings"
              tag="a"
              :to="{ name: 'settings-account-v2' }"
              class="nav-link pr-0 pl-0 d-flex align-items-center"
              exact
            >
              <i class="bx bx-caret-left" style="font-size: 1.4em"></i>
            </router-link>
          </li>
          <li class="nav-item">
            <a
              class="nav-link d-flex align-items-center active"
              id="digital-retailing-settings-finance"
              data-toggle="tab"
              href="#digital-retailing-settings-finance"
              aria-controls="Digital Quote Settings Finance View"
              role="tab"
              aria-selected="true"
            >
              <span class="">Finance Settings</span>
            </a>
          </li>
          <div class="btn-feild-box">
            <button class="btn btn-primary mr-1" @click.prevent="save()">
              Save
            </button>
          </div>
        </ul>
      </div>
    </div>
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="">
        <div class="content-body">
          <!-- form layout section start -->
          <div class="tab-content">
            <!-- single tab content goes here : START -->
            <div
              class="tab-pane active"
              id="digital-retailing-settings-finance-tab"
              aria-labelledby="digital-retailing-settings-finance"
              role="tabpanel"
            >
              <!-- form layout section start -->
              <section id="digital-retailing-settings-finance-section">
                <div class="row match-height">
                  <div class="col-md-12 col-12">
                    <div class="card shadow-none mb-0">
                      <div class="card-content">
                        <div class="card-body">
                          <form class="form form-horizontal webapp-form">
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
                                    <div class="col-md-2 col-12 form-group">
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
                                          form
                                            .digital_retailing_default_finance_terms[
                                            n - 1
                                          ].downpayment
                                        "
                                      />
                                    </div>
                                    <div class="col-md-2 col-12 form-group">
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
                                          form
                                            .digital_retailing_default_finance_terms[
                                            n - 1
                                          ].interest_rate
                                        "
                                      />
                                    </div>
                                    <div class="col-md-2 col-12 form-group">
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
                                        v-model="
                                          form
                                            .digital_retailing_default_finance_terms[
                                            n - 1
                                          ].term
                                        "
                                      />
                                    </div>
                                    <div class="col-md-2 col-12 form-group">
                                      <select
                                        class="custom-select form-control"
                                        name="Payment Frequency"
                                        v-model="
                                          form
                                            .digital_retailing_default_finance_terms[
                                            n - 1
                                          ].frequency
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
                                    <div class="col-md-2 col-12 form-group">
                                      <select
                                        class="custom-select form-control"
                                        name="Credit Score Band"
                                        v-model="
                                          form
                                            .digital_retailing_default_finance_terms[
                                            n - 1
                                          ].credit_score_band
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
                                    <div
                                      class="col-md-2 col-12 form-group pl-0"
                                    >
                                      <button
                                        class="btn btn-danger text-nowrap px-1"
                                        type="button"
                                        @click.prevent="
                                          form.digital_retailing_default_finance_terms.splice(
                                            n - 1,
                                            1
                                          );
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
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
            <!-- single tab content goes here : END -->
          </div>
        </div>
      </div>
    </div>
    <!-- END: Content-->
  </div>
</template>
<script>
import Toast from "../../../library/Toast";
import { mapGetters, mapActions } from "vuex";

export default {
  name: "Finance",
  props: ["application", "app_metadata"],
  data() {
    return {
      form: {
        digital_retailing_default_finance_terms: [],
      },
      errors: [],
    };
  },
  computed: {
    getFinanceTermsCount() {
      let count = 0;
      let finance_terms = this.form.digital_retailing_default_finance_terms;
      if (Array.isArray(finance_terms)) {
        if (finance_terms.length > 0) {
          count = finance_terms.length;
        }
      }

      return count;
    },
    tenantApp() {
      return this.application == "tenant";
    },
    mainApp() {
      return this.application == "main";
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
  },
  methods: {
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
            this.form.digital_retailing_default_finance_terms = JSON.parse(
              data.value
            );
          }
        })
        .catch(() => {});
    },
    async save() {
      const vm = this;
      let formData = new FormData();

      for (let key = 0; key < this.getFinanceTermsCount; key++) {
        formData.append(
          `${key}[frequency]`,
          this.form.digital_retailing_default_finance_terms[key]["frequency"]
        );

        formData.append(
          `${key}[interest_rate]`,
          this.form.digital_retailing_default_finance_terms[key][
            "interest_rate"
          ]
        );

        formData.append(
          `${key}[downpayment]`,
          this.form.digital_retailing_default_finance_terms[key]["downpayment"]
        );

        formData.append(
          `${key}[term]`,
          this.form.digital_retailing_default_finance_terms[key]["term"]
        );

        formData.append(
          `${key}[credit_score_band]`,
          this.form.digital_retailing_default_finance_terms[key][
            "credit_score_band"
          ]
        );
      }
      let request_url = `${window.location.origin}/api/business/digital-retailing/settings/finance`;
      let message = "Saving finance settings...";
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
        })
        .catch((error) => {
          vm.$data.errors = error.response.data;
          let error_message = "There were some errors in saving settings.";
          toast.fail(error_message);
          toast.close();
        });
    },
    addFinanceTerms() {
      this.form.digital_retailing_default_finance_terms.push({
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
  async created(){
    await this.getFinanceTerms();
  }
};
</script>
