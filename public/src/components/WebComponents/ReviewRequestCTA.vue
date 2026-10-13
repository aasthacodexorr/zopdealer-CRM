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
  </span>
</template>
<script>
export default {
  name: "ReviewRequestCTA",
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
  },
  async created() {
    await this.getGlobalConfiguration();
  },
};
</script>
