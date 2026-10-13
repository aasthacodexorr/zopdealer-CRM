<template>
  <li class="d-inline-block mr-1">
    <a
      href="#"
      data-toggle="modal"
      data-target="#bulk-list"
      class="btn btn-outline-primary"
      >Bulk Update</a
    >
    <div
      class="modal fade text-left"
      id="bulk-list"
      tabindex="-1"
      role="dialog"
      aria-labelledby="reviewFeedbackBulkModal"
      aria-hidden="true"
    >
      <div
        class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
        role="document"
      >
        <div class="modal-content">
          <div class="modal-header pl-1 pr-1">
            <h3 class="content-header-title" id="reviewFeedbackBulkModal">
              Feedback Bulk Update
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
              <!-- Status : START -->
              <div class="col-md-5">
                <label>Status</label>
              </div>
              <div class="col-md-7 form-group">
                <fieldset>
                  <div class="input-group">
                    <select
                      class="custom-select form-control"
                      v-model="form.status"
                      id="status"
                      name="status"
                    >
                      <option value="" selected disabled>
                        - Select Status -
                      </option>
                      <option
                        :value="status"
                        v-for="status in getStatuses"
                        :key="status"
                      >
                        {{ status }}
                      </option>
                    </select>
                  </div>
                </fieldset>
              </div>
              <!-- Status : END -->

              <div class="col-sm-12 d-flex justify-content-left pb-1">
                <button
                  type="close"
                  @click="applyBulkOptions"
                  class="btn btn-primary mr-1 mt-1"
                  data-dismiss="modal"
                  aria-label="Close"
                >
                  Update Fields
                </button>
                <button
                  type="close"
                  @click="resetBulkColumns"
                  class="btn btn-light-secondary mr-1 mt-1"
                  data-dismiss="modal"
                  aria-label="Close"
                >
                  Cancel
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </li>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import vSelect from "vue-select";
import Toast from "../../library/Toast";
export default {
  name: "ReviewFeedbackBulkUpdate",
  data() {
    return {
      entity_plural_label: "Reviews",
      entity_base_bulk_update_api: `/api/business/feedbacks/updates`,
      entity_listing_attribute: "review_feedback_listing",
      form: {
        status: "",
      },
    };
  },
  components: {
    "v-select": vSelect,
  },
  computed: {
    ...mapGetters("reviewFeedback", ["getReviewFeedbackIds","getStatuses"]),
  },
  methods: {
    async applyBulkOptions() {
      const vm = this;
      let formData = new FormData();
      formData.append("review_feedback_ids", this.getReviewFeedbackIds);
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key)) {
          formData.append(key, this.form[key]);
        }
      }

      let message = `Updating selected ${this.entity_plural_label}...`;
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + this.entity_base_bulk_update_api,
      })
        .then((response) => {
          this.resetBulkColumns();
          Event.fire("reset_selected_rows");

          setTimeout(() => {
            Event.fire("reset_current_pagination");
            toast.success(
              `${this.entity_plural_label} are updated successfully.`
            );
            toast.close();
          }, 1000);
        })
        .catch(function(error) {
          vm.resetBulkColumns();
          Event.fire("reset_selected_rows");
          setTimeout(() => {
            vm.$swal({
              showConfirmButton: false,
              icon: "error",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "No columns or rows were selected.",
            });
          }, 1000);
        });
    },
    resetBulkColumns() {
      Object.keys(this.form).forEach((key) => {
        this.form[key] = "";
      });
    },
  },
  async mounted() {
    const vm = this;
    Event.listen("reset_selected_columns", (data) => {
      vm.resetBulkColumns();
    });
  },
  beforeDestroy() {
    Event.vue.$off("reset_selected_columns", null);
  },
};
</script>
