<template>
  <span>
    <button
      style="line-height: 1.2em"
      type="button"
      class="btn btn-primary"
      data-toggle="modal"
      data-target="#review-feedback-request"
    >
      <i class="bx bx-send"></i> Request
    </button>
    <!-- model for review request : START -->
    <div
      class="modal fade text-left"
      id="review-feedback-request"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Request Review"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="content-header-title">
              Request Feedback
            </h3>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <i class="bx bx-x"></i>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-row" v-show="!new_customer">
              <div class="form-group col-md-12">
                <label for="costs">Search Customer : </label>
                <v-select
                  placeholder="Select Customer"
                  label="label"
                  :reduce="(customer) => customer.id"
                  v-model="customer_id"
                  clearable
                  :options="createIndexForCustomers"
                ></v-select>
              </div>
            </div>

            <div class="form-row" v-show="new_customer">
              <div class="form-group col-md-12">
                <label for="first_name">First Name : </label>
                <input
                  type="text"
                  class="form-control"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('first_name'),
                  }"
                  @keyup="delete errors.first_name"
                  :title="errors.first_name"
                  v-model="form.first_name"
                  id="first_name"
                  placeholder="First Name"
                />
              </div>
            </div>

            <div class="form-row" v-show="new_customer">
              <div class="form-group col-md-12">
                <label for="last_name">Last Name : </label>
                <input
                  type="text"
                  class="form-control"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('last_name'),
                  }"
                  @keyup="delete errors.last_name"
                  :title="errors.last_name"
                  v-model="form.last_name"
                  id="last_name"
                  placeholder="Last Name"
                />
              </div>
            </div>

            <div class="form-row" v-show="new_customer">
              <div class="form-group col-md-12">
                <label for="mobile">Mobile : </label>
                <input
                  type="text"
                  class="form-control"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('mobile'),
                  }"
                  @keyup="delete errors.mobile"
                  :title="errors.mobile"
                  v-model="form.mobile"
                  id="mobile"
                  placeholder="Mobile"
                />
              </div>
            </div>

            <div class="form-row" v-show="new_customer">
              <div class="form-group col-md-12">
                <label for="email">Email : </label>
                <input
                  type="text"
                  class="form-control"
                  :class="{
                    'is-invalid': errors.hasOwnProperty('email'),
                  }"
                  @keyup="delete errors.email"
                  :title="errors.email"
                  v-model="form.email"
                  id="email"
                  placeholder="Email"
                />
              </div>
            </div>

            <!-- Send Options : START -->
            <div class="form-row" v-show="customer_id || new_customer">
              <ul class="col-12 list-group">
                <!-- list element : START -->
                <li class="list-group-item" style="padding:1rem 2rem;">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="request_review_methods"
                      class="checkbox-input"
                      value="sms"
                      :id="'request_by_sms'"
                    />
                    <label class="pl-1" :for="'request_by_sms'">SMS</label>
                  </div>
                </li>
                <!-- list element : END -->

                <!-- list element : START -->
                <li class="list-group-item" style="padding:1rem 2rem;">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      v-model="request_review_methods"
                      class="checkbox-input"
                      value="email"
                      :id="'request_by_email'"
                    />
                    <label class="pl-1" :for="'request_by_email'">Email</label>
                  </div>
                </li>
                <!-- list element : END -->
              </ul>
            </div>

            <div
              class="divider divider-dashed"
              type="button"
              @click="new_customer = !new_customer"
            >
              <div class="divider-text" v-show="!new_customer">
                Click here to create new
              </div>
              <div class="divider-text" v-show="new_customer">
                Show Customer Dropdown
              </div>
            </div>

            <!-- Send Options : END -->
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              @click.prevent="requestReview"
              v-show="
                customer_id &&
                  !new_customer &&
                  request_review_methods.length > 0
              "
              type="button"
              class="btn btn-primary"
            >
              Send
            </button>
            <button
              @click.prevent="createCustomer"
              v-show="new_customer && request_review_methods.length > 0"
              type="button"
              class="btn btn-primary"
            >
              Save & Request
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- model for review request : END -->
  </span>
</template>
<script>
import vSelect from "vue-select";
import Toast from "../../library/Toast";
import Inputmask from "inputmask";
import { mapGetters, mapActions } from "vuex";
export default {
  name: "ReviewFeedbackRequest",
  data() {
    return {
      customers: [],
      customer_id: null,
      request_review_methods: [],
      form: {
        first_name: "",
        last_name: "",
        mobile: "",
        email: "",
      },
      new_customer: false,
      errors: [],
    };
  },
  components: {
    "v-select": vSelect,
    Inputmask,
  },
  computed: {
    createIndexForCustomers() {
      if (this.customers.length > 0) {
        for (var index in this.customers) {
          // this.customers[index].label = "Hello World";
          let label =
            this.customers[index].first_name != "" &&
            this.customers[index].first_name != null
              ? `${this.customers[index].first_name} `
              : "";

          label +=
            this.customers[index].last_name != "" &&
            this.customers[index].last_name != null
              ? `${this.customers[index].last_name}`
              : "";

          label +=
            this.customers[index].company_name != "" &&
            this.customers[index].company_name != null
              ? ` < ${this.customers[index].company_name} >`
              : "";

          this.customers[index].label = label;
        }

        this.$forceUpdate();
      }
      return this.customers;
    },
  },
  methods: {
    ...mapActions("reviewFeedbackView", ["commitOnlyEntities"]),
    async resetCustomer() {
      this.form.first_name = this.form.last_name = this.form.mobile = this.form.email =
        "";
    },
    async createCustomer() {
      const vm = this;
      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && this.form[key] != null) {
          formData.append(key, this.form[key]);
        }
      }

      let success_message = "Creating a customer and sending request ...";

      let request_url = `${window.location.origin}/api/business/customers/create`;

      let request_message = `Request Sent`;

      const toast = new Toast(request_message);
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
          this.customer_id = data.customer_id;
          this.requestReview(false);
          toast.success(success_message);
          toast.close();
          this.resetCustomer();
          this.getCustomers();
        })
        .catch(function(error) {
          vm.$data.errors = error.response.data;

          toast.fail(`There were some errors.`);
          toast.close();
        });
    },
    async requestReview(show_toast = true) {
      const vm = this;
      let formData = new FormData();

      formData.append("request_review_methods", this.request_review_methods);

      //success message setting
      let success_message = "Request sent.";

      let request_url = `${window.location.origin}/api/business/feedbacks/create/${this.customer_id}`;

      let request_message = `Requesting review ...`;

      const toast = new Toast(request_message);

      if (show_toast) {
        toast.fire();
      }

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
          if (show_toast) {
            toast.success(success_message);
            toast.close();
          }
          this.customer_id = null;
          this.resetCustomer();
          this.commitOnlyEntities();
        })
        .catch(function(error) {
          if (show_toast) {
            toast.fail(
              Object.values(error.response.data)
                .flat()
                .join(" ")
            );
            toast.close();
          }
        });

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "deal",
        "deal_quick_send_document",
        ""
      );
    },
    async getCustomers() {
      const vm = this;
      await axios({
        method: "get",
        url: window.location.origin + "/api/business/customers?paginate=false",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          vm.customers = data.customer_listing;
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
  },
  async created() {
    await this.getCustomers();
  },
  async mounted() {
    const vm = this;
    Inputmask({ mask: "(999) 999-9999", autoUnmask: true }).mask(
      document.getElementById("mobile")
    );

    jQuery("#review-feedback-request").on("hidden.bs.modal", function() {
      vm.resetCustomer();
      vm.new_customer = false;
      vm.customer_id = null;
    });
  },
};
</script>
