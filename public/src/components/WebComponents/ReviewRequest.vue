<template>
  <span v-show="reviewEnabled">
    <button
      style="line-height: 1.2em"
      v-show="customer_id && reviewEnabled && show"
      type="button"
      class="btn btn-primary"
      data-toggle="modal"
      data-target="#review-request-options"
    >
      <i class="bx bx-share-alt"></i> Review
    </button>
    <!-- model for review request : START -->
    <div
      class="modal fade text-left"
      id="review-request-options"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Request Review Options"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="content-header-title">
              Request Review By
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
            <div class="row">
              <ul class="col-12 list-group">
                <!-- list element : START -->
                <li
                  class="list-group-item"
                  style="padding:1rem 2rem;"
                  v-show="phoneNumberAvailable"
                >
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
                <li
                  class="list-group-item"
                  style="padding:1rem 2rem;"
                  v-show="emailAvailable"
                >
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
          </div>
          <div class="modal-footer" v-if="request_review_methods.length > 0">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              type="close"
              class="btn btn-primary"
              data-dismiss="modal"
              aria-label="Close"
              @click.prevent="requestReview"
            >
              <i class="bx bxs-send mr-1"></i> Request
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- model for review request : END -->
  </span>
</template>
<script>
import Toast from "../../library/Toast";
import { mapGetters, mapActions } from "vuex";
export default {
  name: "ReviewRequest",
  props: {
    phone: {
      type: String,
      required: false,
      default: "",
    },
    email: {
      type: String,
      required: false,
      default: "",
    },
    customer_id: {
      required: true,
    },
    show: {
      required: false,
      default: true,
    },
  },
  data() {
    return {
      request_review_methods: [],
      form: {
        enable_reviews: "0",
      },
    };
  },
  computed: {
    reviewEnabled() {
      return this.form.enable_reviews == "1";
    },
    phoneNumberAvailable() {
      let phone_number = this.phone === null ? "" : this.phone;
      return phone_number.length === 10;
    },
    emailAvailable() {
      let current_email = this.email === null ? "" : this.email;
      return current_email.length > 0;
    },
  },
  methods: {
    ...mapActions("reviewFeedbackView", ["commitOnlyEntities"]),
    async getGlobalConfiguration() {
      let request_url =
        window.location.origin + "/api/business/configurations/global";

      await axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            this.form.hasOwnProperty(element.key)
              ? (this.form[element.key] = element.value)
              : null;
          }
        }
      });
    },
    async requestReview() {
      const vm = this;
      let formData = new FormData();

      formData.append("request_review_methods", this.request_review_methods);

      //success message setting
      let success_message = "Request sent.";

      let request_url = `${window.location.origin}/api/business/feedbacks/create/${this.customer_id}`;

      let request_message = `Requesting review ...`;

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
          toast.success(success_message);
          toast.close();
          this.request_review_methods = [];
          this.commitOnlyEntities();
        })
        .catch(function(error) {
          toast.fail(error.response.data.errors);
          toast.close();
          this.request_review_methods = [];
        });

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "review",
        "review_request",
        ""
      );
    },
  },
  async created() {
    await this.getGlobalConfiguration();
  },
};
</script>
