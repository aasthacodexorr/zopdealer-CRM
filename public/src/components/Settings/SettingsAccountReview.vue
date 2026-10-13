<template>
  <div
    role="tabpanel"
    class="tab-pane active"
    id="setting-account-review"
    aria-labelledby="Reviews View Setting"
    aria-expanded="true"
  >
    <div class="content-wrapper m-0 border-bottom todo-fixed-search">
      <form class="webapp-form">
        <div class="row">
          <div class="col-md-5 col-12">
            <ul class="pl-0">
              <li class="d-inline-block mr-1">
                <div class="content-list-box">
                  <p class="p-0 m-0">
                    <span
                      class="blue-color text-bold-400 title-sizeincrease-font"
                      >Website Review Settings</span
                    >
                  </p>
                </div>
              </li>
            </ul>
          </div>
          <div class="col-md-7 col-12">
            <ul class="p-0 float-right d-flex">
              <li class="d-inline-block">
                <button
                  type="reset"
                  class="d-none btn btn-light-secondary mr-1"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="!submit"
                  @click.prevent="saveConfiguration"
                  class="btn btn-primary"
                >
                  Save
                </button>
              </li>
            </ul>
          </div>
        </div>
      </form>
    </div>
    <div
      class="list-right-task-list list-group ps p-0"
      style="overflow-y: auto !important"
    >
      <form class="webapp-form">
        <div class="content-wrapper mt-0 pb-1 pt-2 border-bottom">
          <div class="list-image-content">
            <div class="row contact-repeater">
              <small class="col-12 text-danger">
                Please Note: the review widget will only appear on your website
                if the snippet has been added.
              </small>
              <!-- Each Row of Query : OPEN-->
              <div
                class="col-12 col-md-12"
                data-repeater-list="contact"
                v-for="n in review_view_rows"
                :key="n"
              >
                <div class="row" data-repeater-item>
                  <div
                    v-if="n > 1"
                    class="col-md-2 col-12 form-group d-flex align-items-center pr-0"
                  >
                    <v-select
                      placeholder="Join"
                      :options="['AND', 'OR']"
                      v-model="form.website_review_view[n - 1].join"
                      style="width: 100%"
                    />
                  </div>

                  <div
                    class="col-md-4 col-12 form-group d-flex align-items-center pr-0"
                  >
                    <v-select
                      placeholder="Attribute"
                      :options="map_columns"
                      @input="changeQueryDetails(n - 1)"
                      v-model="form.website_review_view[n - 1].header"
                      style="width: 100%"
                      :reduce="(map_columns) => map_columns.header"
                      label="header"
                    />
                  </div>
                  <div class="col-md-4 col-12 form-group">
                    <v-select
                      placeholder="Comparator"
                      :options="map_comparator[n - 1]"
                      v-model="form.website_review_view[n - 1].comparator"
                      style="width: 100%"
                    />
                  </div>
                </div>

                <div class="row" data-repeater-item>
                  <div
                    class="col-md-4 col-12 form-group pl-0 pr-0 multi-select-component"
                    v-if="
                      form.website_review_view[n - 1].type == 'Multi Select' &&
                      form.website_review_view[n - 1].comparator != ''
                    "
                  >
                    <v-select
                      multiple
                      placeholder="Multi Select"
                      :options="map_value_1[n - 1]"
                      v-model="form.website_review_view[n - 1].value_1"
                      style="width: 100%"
                    />
                  </div>
                  <div
                    class="col-md-4 col-12 form-group pl-0 pr-0"
                    v-else-if="
                      form.website_review_view[n - 1].type ==
                        'Date Comparision' &&
                      form.website_review_view[n - 1].comparator != ''
                    "
                  >
                    <date-picker
                      v-model="form.website_review_view[n - 1].value_1"
                      :format="'MM/DD/YYYY'"
                      :value-type="'MM/DD/YYYY'"
                    ></date-picker>
                  </div>
                  <div
                    class="col-md-4 col-12 form-group pl-0 pr-0"
                    v-else-if="
                      form.website_review_view[n - 1].type == 'Single Select' &&
                      form.website_review_view[n - 1].comparator != ''
                    "
                  >
                    <v-select
                      placeholder="Single Select"
                      :options="map_value_1[n - 1]"
                      :reduce="(each) => each.value"
                      v-model="form.website_review_view[n - 1].value_1"
                      style="width: 100%"
                    />
                  </div>
                  <div
                    class="col-md-11 col-12 form-group pl-0 pr-0"
                    v-else-if="
                      (form.website_review_view[n - 1].type ==
                        'Numeric Comparision' ||
                        form.website_review_view[n - 1].type ==
                          'Relative Time Period Comparision') &&
                      form.website_review_view[n - 1].comparator != ''
                    "
                  >
                    <input
                      type="number"
                      style="width: 33.33%; display: inline-block"
                      min="0"
                      class="form-control mr-1"
                      name=""
                      v-model="form.website_review_view[n - 1].value_1"
                      placeholder="Value 1"
                      required
                    />
                    <input
                      type="number"
                      style="width: 33.33%; display: inline-block"
                      min="0"
                      class="form-control mr-1"
                      v-show="
                        form.website_review_view[n - 1].comparator == 'between'
                      "
                      name=""
                      v-model="form.website_review_view[n - 1].value_2"
                      placeholder="Value 2"
                      required
                    />
                  </div>
                  <div
                    class="col-md-4 col-12 form-group pl-0 pr-0"
                    v-else
                  ></div>

                  <div
                    class="col-md-7 col-12 form-group"
                    v-if="
                      !(
                        (form.website_review_view[n - 1].type ==
                          'Numeric Comparision' ||
                          form.website_review_view[n - 1].type ==
                            'Relative Time Period Comparision') &&
                        form.website_review_view[n - 1].comparator != ''
                      )
                    "
                  ></div>
                  <div class="col-md-1 col-12 form-group">
                    <button
                      class="btn btn-icon btn-danger"
                      type="button"
                      data-repeater-delete
                      @click="
                        form.website_review_view.splice(n - 1, 1);
                        review_view_rows -= 1;
                      "
                    >
                      <i class="bx bx-x"></i>
                    </button>
                  </div>
                </div>
              </div>
              <!-- Each Row of Query : CLOSE -->
              <div class="col-12 col-md-12">
                <button
                  class="btn btn-icon btn-primary"
                  type="button"
                  @click="addNewRow"
                  data-repeater-create
                >
                  <i class="bx bx-plus"></i> Add criteria
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import draggable from "vuedraggable";
import vSelect from "vue-select";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

import Toast from "../../library/Toast";

export default {
  name: "SettingsAccountReview",
  data() {
    return {
      map_comparator: [[]],
      map_columns: [
        { header: "Source", column: "source", type: "Multi Select" },
        { header: "Rating", column: "rating", type: "Numeric Comparision" },
        {
          header: "Visibility",
          column: "visibility",
          type: "Single Select",
        },
        {
          header: "Featured",
          column: "featured",
          type: "Single Select",
        },
        {
          header: "Creation Date",
          column: "created_at",
          type: "Date Comparision",
        },
        {
          header: "Modified Date",
          column: "updated_at",
          type: "Date Comparision",
        },
      ],
      errors: [],
      map_value_1: [[]],
      submit: true,
      review_view_rows: 1,
      form: {
        website_review_view: [
          {
            header: "",
            column: "",
            join: "AND",
            comparator: "",
            type: "",
            value_1: "",
            value_2: "",
          },
        ],
      },
    };
  },
  components: {
    "v-select": vSelect,
    draggable,
    DatePicker,
  },
  computed: {
    ...mapGetters("review", ["getSources"]),
  },
  methods: {
    addNewRow() {
      this.map_comparator.push([]);
      this.map_value_1.push([]);
      this.form.website_review_view.push({
        header: "",
        column: "",
        join: "AND",
        comparator: "",
        type: "",
        value_1: "",
        value_2: "",
      });
      this.review_view_rows += 1;
    },
    changeQueryDetails(i) {
      //fetch the row for which the input has changed.
      //once fetched changed the column_name, comparator in the corresponding object.
      let review_view = this.form.website_review_view[i];
      review_view.column = this.map_columns.filter(
        (x) => x.header == review_view.header
      )[0].column;
      review_view.type = this.map_columns.filter(
        (x) => x.header == review_view.header
      )[0].type;
      review_view.value_1 = "";
      review_view.comparator = "";
      review_view.value_2 = "";

      if (review_view.type == "Null Comparision") {
        this.map_comparator[i] = ["is empty", "is not empty"];
      } else if (review_view.type == "Date Comparision") {
        this.map_comparator[i] = ["is", "after", "before"];
      } else if (review_view.type == "Multi Select") {
        this.map_comparator[i] = ["is", "is not"];
      } else if (review_view.type == "Single Select") {
        this.map_comparator[i] = ["is"];
      } else if (
        review_view.type == "Numeric Comparision" ||
        review_view.type == "Relative Time Period Comparision"
      ) {
        this.map_comparator[i] = ["greater than", "less than", "between"];
      }

      if (review_view.header == "Source") {
        this.map_value_1[i] = this.getSources;
      } else if (review_view.header == "Visibility") {
        this.map_value_1[i] = [
          { label: "ON", value: "1" },
          { label: "OFF", value: "0" },
        ];
      } else if (review_view.header == "Featured") {
        this.map_value_1[i] = [
          { label: "Featured", value: "1" },
          { label: "Not Featured", value: "0" },
        ];
      }
    },
    changeQueryAvailableValues(i) {
      //fetch the row for which the input has changed.
      //once fetched changed the column_name, comparator in the corresponding object.
      let review_view = this.form.website_review_view[i];
      review_view.column = this.map_columns.filter(
        (x) => x.header == review_view.header
      )[0].column;
      review_view.type = this.map_columns.filter(
        (x) => x.header == review_view.header
      )[0].type;

      if (review_view.type == "Null Comparision") {
        this.map_comparator[i] = ["is empty", "is not empty"];
      } else if (review_view.type == "Date Comparision") {
        this.map_comparator[i] = ["is", "after", "before"];
      } else if (review_view.type == "Multi Select") {
        this.map_comparator[i] = ["is", "is not"];
      } else if (review_view.type == "Single Select") {
        this.map_comparator[i] = ["is"];
      } else if (
        review_view.type == "Numeric Comparision" ||
        review_view.type == "Relative Time Period Comparision"
      ) {
        this.map_comparator[i] = ["greater than", "less than", "between"];
      }

      if (review_view.header == "Source") {
        this.map_value_1[i] = this.getSources;
      } else if (review_view.header == "Visibility") {
        this.map_value_1[i] = [
          { label: "ON", value: "1" },
          { label: "OFF", value: "0" },
        ];
      } else if (review_view.header == "Featured") {
        this.map_value_1[i] = [
          { label: "Featured", value: "1" },
          { label: "Not Featured", value: "0" },
        ];
      }
    },
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

        this.form.website_review_view = JSON.parse(
          this.form.website_review_view
        );

        console.log(this.form.website_review_view.length);

        this.form.website_review_view =
          this.form.website_review_view.length < 1
            ? [
                {
                  header: "",
                  column: "",
                  join: "AND",
                  comparator: "",
                  type: "",
                  value_1: "",
                  value_2: "",
                },
              ]
            : this.form.website_review_view;

        this.review_view_rows = this.form.website_review_view.length;

        for (const key in this.form.website_review_view) {
          this.changeQueryAvailableValues(key);
        }
      });
    },
    saveConfiguration() {
      const vm = this;
      this.submit = false;
      let formData = new FormData();
      for (const key in this.form) {
        formData.append(key, JSON.stringify(this.form[key]));
      }

      let request_url = window.location.origin + "/api/business/configurations";
      let message = "Saving account settings...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          let success_message = "Finished saving configurations.";
          toast.success(success_message);
          toast.close();

          setTimeout(() => {
            vm.$data.submit = true;
          }, 500);
        })
        .catch((error) => {
          let error_message = "There were some errors in saving settings.";
          toast.fail(error_message);
          toast.close();

          setTimeout(() => {
            vm.$data.submit = true;
          }, 500);
        });
    },
  },
  async mounted() {
    this.submit = false;
    await this.getGlobalConfiguration();
    this.submit = true;
  },
};
</script>
