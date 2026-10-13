<template>
  <div>
    <a
      class="btn btn-primary text-white w-100 text-center"
      type="button"
      data-toggle="modal"
      data-target="#reviewModal"
    >
      <i class="bx bxs-add-to-queue mr-1"></i> Add Review
    </a>
    <!-- Add A Review : START  -->
    <div
      class="modal fade"
      id="reviewModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Add Another Review"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <!-- Modal Header : START -->
          <div class="modal-header">
            <h5 class="modal-title" id="reviewHeader">Add New Review</h5>
            <button
              type="button"
              id="reviewClose"
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
                <!-- Name : START -->
                <div class="form-group col-md-12">
                  <label for="review_name">Name: </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('name'),
                    }"
                    @keyup="delete errors.name"
                    :title="errors.name"
                    v-model="form.name"
                    id="review_name"
                    placeholder="Display Name"
                  />
                </div>
              </div>
              <!-- Name : END -->

              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="profile_photo_url">Profile Picture: </label>
                  <div class="custom-file">
                    <input
                      :class="{
                        'is-invalid':
                          errors.hasOwnProperty('profile_photo_url'),
                      }"
                      :title="errors.attachment"
                      type="file"
                      ref="profile_photo_url"
                      @change="saveFile($event)"
                      class="custom-file-input"
                      id="profile_photo_url"
                    />
                    <label
                      class="custom-file-label"
                      id="profile_photo_url_label"
                      for="profile_photo_url"
                      >Choose Profile Picture</label
                    >
                  </div>
                </div>
              </div>

              <!-- Comment : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="review_comment">Comment: </label>
                  <textarea
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('comment'),
                    }"
                    @keyup="delete errors.comment"
                    :title="errors.comment"
                    v-model="form.comment"
                    id="review_comment"
                    placeholder="Comment of the Reviewer"
                  ></textarea>
                </div>
              </div>
              <!-- Comment : END -->

              <!-- Rating : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="review_rating" class="mr-1">Rating: </label>
                  <star-rating
                    :inline="true"
                    :star-size="14"
                    :read-only="false"
                    :show-rating="true"
                    :increment="0.5"
                    v-model="form.rating"
                  ></star-rating>
                </div>
              </div>
              <!-- Rating : END -->

              <!-- Review Date : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="review_date_time">Date : </label>
                  <fieldset
                    class="has-icon-left"
                    style="position: relative"
                    id="review_date_time"
                  >
                    <date-picker
                      v-model="form.review_date_time"
                      :format="'MM/DD/YYYY'"
                      :value-type="'MM/DD/YYYY'"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('review_date_time'),
                      }"
                      @change="delete errors.review_date_time"
                      :title="errors.review_date_time"
                      :clearable="false"
                    ></date-picker>
                  </fieldset>
                </div>
              </div>
              <!-- Review Date : END -->

              <!-- Visibility : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="review_rating" class="mr-1">Visibility: </label>
                  <div class="custom-control custom-switch d-inline-block">
                    <input
                      type="checkbox"
                      id="switchForReviewVisibility"
                      v-model="form.visibility"
                      name="review_visibility"
                      true-value="1"
                      false-value="0"
                      checked="checked"
                      class="custom-control-input"
                    />
                    <label
                      for="switchForReviewVisibility"
                      class="custom-control-label"
                    >
                      <span class="switch-icon-left">ON</span>
                      <span class="switch-icon-right">OFF</span>
                    </label>
                  </div>
                </div>
              </div>
              <!-- Visibility : END -->

              <!-- Featured : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="review_rating" class="mr-1">Featured: </label>
                  <div class="custom-control custom-switch d-inline-block">
                    <input
                      type="checkbox"
                      id="switchForReviewFeatured"
                      v-model="form.featured"
                      name="review_featured"
                      true-value="1"
                      false-value="0"
                      checked="checked"
                      class="custom-control-input"
                    />
                    <label
                      for="switchForReviewFeatured"
                      class="custom-control-label"
                    >
                      <span class="switch-icon-left">ON</span>
                      <span class="switch-icon-right">OFF</span>
                    </label>
                  </div>
                </div>
              </div>
              <!-- Featured : END -->
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
              @click.prevent="saveReview"
              class="btn btn-primary"
            >
              Save
            </button>
          </div>
          <!-- Modal Footer : END -->
        </div>
      </div>
    </div>
    <!-- Add A Review : END  -->
  </div>
</template>
<script>
import StarRating from "vue-star-rating";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import { mapGetters, mapActions } from "vuex";

import Toast from "../../library/Toast";
export default {
  name: "ReviewCreate",
  components: {
    StarRating,
    DatePicker,
  },
  data() {
    return {
      form: {
        name: "",
        comment: "",
        source: "ZOP",
        rating: 5,
        review_date_time: null,
        visibility: "1",
        featured: "0",
        profile_photo_url: null,
      },
      errors: [],
    };
  },
  methods: {
    ...mapActions("reviewView", ["commitOnlyEntities"]),
    saveFile(event) {
      this.errors = [];
      this.form.profile_photo_url = event.target.files[0];
    },
    resetReview() {
      this.form.name = this.form.comment = "";
      this.form.source = "ZOP";
      this.form.rating = 5;
      this.form.review_date_time = moment(new Date()).format("MM/DD/YYYY");
      this.form.visibility = "1";
      this.form.featured = "0";
      this.form.profile_photo_url = null;
      this.$refs.profile_photo_url.value = null;
      document.getElementById("profile_photo_url_label").innerHTML =
        "Choose Profile Picture";
    },
    async saveReview() {
      const vm = this;
      let formData = new FormData();
      for (const key in this.form) {
        if (
          this.form.hasOwnProperty(key) &&
          key !== "profile_photo_url" &&
          this.form[key] != null
        ) {
          formData.append(key, this.form[key]);
        }
      }

      let review_date_time = moment(this.form.review_date_time);

      this.form.review_date_time = review_date_time.isValid()
        ? review_date_time.format("MM/DD/YYYY")
        : moment(new Date()).format("MM/DD/YYYY");

      if (this.form.profile_photo_url != null) {
        formData.append(
          "profile_photo_url",
          this.form.profile_photo_url,
          this.form.profile_photo_url.name
        );
      }

      let request_url = window.location.origin + "/api/business/reviews/create";
      let message = "Saving Review...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.errors = [];

          this.resetReview();

          toast.success(data.success);
          toast.close();
          document.getElementById("reviewClose").click();
          this.commitOnlyEntities();
        })
        .catch((error) => {
          let error_message = "There were errors in saving review.";
          toast.fail(error_message);
          toast.close();
          vm.$data.errors = error.response.data;
        });
    },
  },
  created() {
    this.form.review_date_time = moment(new Date()).format("MM/DD/YYYY");
  },
  mounted() {
    const vm = this;
    jQuery("#reviewModal").on("hidden.bs.modal", function () {
      vm.resetReview();
    });
  },
};
</script>
<style lang="scss">
.vue-star-rating-rating-text {
  position: relative;
  top: 2px;
}
#review_date_time .vdpComponent {
  width: 100%;
}
#review_date_time .vdpComponent input {
  display: block;
  width: 100%;
  height: calc(1.4em + 0.94rem + 3.7px);
  padding: 0.47rem 0.8rem;
  padding-right: 0.8rem;
  padding-left: 2.2em !important;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.4;
  color: #475f7b;
  background-color: #ffffff;
  background-clip: padding-box;
  border: 1px solid #dfe3e7;
  border-radius: 0.267rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.small {
  width: 300px;
}
</style>
