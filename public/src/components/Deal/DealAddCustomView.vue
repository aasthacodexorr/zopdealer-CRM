<template>
  <!-- BEGIN: Content-->
  <div id="add_deal_custom_view">
    <div class="app-content content fixed-height-block">
      <div class="top-space-box">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center active">
              <span class="d-none d-sm-block">Deal Custom View</span>
            </a>
          </li>
          <div class="btn-feild-box">
            <button type="reset" class="btn btn-light-secondary mr-1">
              Cancel
            </button>
            <button
              type="submit"
              @click="save()"
              :disabled="!submit_button"
              class="btn btn-primary mr-1"
            >
              Save
            </button>
          </div>
        </ul>
      </div>
    </div>
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="content-wrapper mt-3">
        <div class="content-body" v-show="submit_button">
          <!-- form layout section start -->
          <form class="webapp-form">
            <div class="card shadow-none mb-0">
              <div class="card-header border-line-bottom-title">
                <h4 class="card-title">Name<sup class="text-danger">*</sup></h4>
              </div>
            </div>
            <div class="card shadow-none mb-0">
              <div class="row">
                <div class="col-md-7 form-group">
                  <fieldset>
                    <div class="input-group">
                      <input
                        type="text"
                        id="lead-header-id"
                        class="form-control mr-1 ml-1"
                        name=""
                        v-model="form.header"
                        placeholder="Name"
                        :class="{
                          'is-invalid': errors.hasOwnProperty('header'),
                        }"
                        @keyup="delete errors.header"
                        :title="errors.header"
                        required
                      />
                      <div class="checkbox" style="margin-top: 6px">
                        <input
                          type="checkbox"
                          class="checkbox-input"
                          id="checkbox1"
                          v-model="form.default"
                          :true-value="'1'"
                          :false-value="'0'"
                        />
                        <label for="checkbox1">Mark as Favorite</label>
                      </div>
                    </div>
                  </fieldset>
                </div>
              </div>
            </div>
            <div class="card shadow-none mt-1 mb-0">
              <div class="card-header border-line-bottom-title">
                <h4 class="card-title">Define the criteria ( if any )</h4>
              </div>
              <div class="card-content">
                <div class="card-body pt-0 pb-0">
                  <div class="contact-repeater">
                    <div class="m-width-s mb-0 pb-0 border-0">
                      <div class="row">
                        <!-- Each Row of Query : OPEN-->
                        <div
                          class="col-12 col-md-12"
                          v-for="n in query_rows"
                          :key="form.query[n - 1].header + n"
                        >
                          <div class="row">
                            <div
                              v-if="n > 1"
                              class="col-md-2 col-12 form-group d-flex align-items-center pr-0"
                            >
                              <v-select
                                placeholder="Join"
                                :options="['AND', 'OR']"
                                v-model="form.query[n - 1].join"
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
                                v-model="form.query[n - 1].header"
                                style="width: 100%"
                                :reduce="(map_columns) => map_columns.header"
                                label="header"
                              />
                            </div>
                            <div class="col-md-4 col-12 form-group">
                              <v-select
                                placeholder="Comparator"
                                :options="map_comparator[n - 1]"
                                v-model="form.query[n - 1].comparator"
                                style="width: 100%"
                              />
                            </div>
                          </div>

                          <div class="row">
                            <div
                              class="col-md-4 col-12 form-group pl-0 pr-0 multi-select-component"
                              v-show="
                                showTypeComparatorElement(
                                  form.query[n - 1].type,
                                  ['Multi Select'],
                                  form.query[n - 1].comparator
                                )
                              "
                            >
                              <v-select
                                multiple
                                taggable
                                placeholder="Multi Select"
                                :options="map_value_1[n - 1]"
                                v-model="form.query[n - 1].value_1"
                                style="width: 100%"
                              />
                            </div>
                            <div
                              class="col-md-4 col-12 form-group pl-0 pr-0"
                              v-show="
                                showTypeComparatorElement(
                                  form.query[n - 1].type,
                                  ['Single Select'],
                                  form.query[n - 1].comparator
                                )
                              "
                            >
                              <v-select
                                placeholder="Single Select"
                                :options="map_value_1[n - 1]"
                                :reduce="(each) => each.value"
                                v-model="form.query[n - 1].value_1"
                                style="width: 100%"
                              />
                            </div>
                            <div
                              class="col-md-4 col-12 form-group pl-0 pr-0"
                              v-if="
                                form.query[n - 1].type == 'Date Comparision' &&
                                form.query[n - 1].comparator != ''
                              "
                            >
                              <date-picker
                                v-model="form.query[n - 1].value_1"
                                :format="'MM/DD/YYYY'"
                                :value-type="'MM/DD/YYYY'"
                              ></date-picker>
                            </div>
                            <div
                              class="col-md-11 col-12 form-group pl-0 pr-0"
                              v-else-if="
                                (form.query[n - 1].type ==
                                  'Numeric Comparision' ||
                                  form.query[n - 1].type ==
                                    'Relative Time Period Comparision') &&
                                form.query[n - 1].comparator != ''
                              "
                            >
                              <input
                                type="number"
                                style="width: 33.33%; display: inline-block"
                                min="0"
                                class="form-control mr-1"
                                name=""
                                v-model="form.query[n - 1].value_1"
                                placeholder="Value 1"
                                required
                              />
                              <input
                                type="number"
                                style="width: 33.33%; display: inline-block"
                                min="0"
                                class="form-control mr-1"
                                v-show="
                                  form.query[n - 1].comparator == 'between'
                                "
                                name=""
                                v-model="form.query[n - 1].value_2"
                                placeholder="Value 2"
                                required
                              />
                            </div>
                            <div
                              class="col-md-4 col-12 form-group pl-0 pr-0"
                              v-show="
                                !showTypeComparatorElement(
                                  form.query[n - 1].type,
                                  [
                                    'Multi Select',
                                    'Single Select',
                                    'Date Comparision',
                                    'Numeric Comparision',
                                    'Relative Time Period Comparision',
                                  ],
                                  form.query[n - 1].comparator
                                )
                              "
                            ></div>

                            <div
                              class="col-md-7 col-12 form-group"
                              v-if="
                                !(
                                  (form.query[n - 1].type ==
                                    'Numeric Comparision' ||
                                    form.query[n - 1].type ==
                                      'Relative Time Period Comparision') &&
                                  form.query[n - 1].comparator != ''
                                )
                              "
                            ></div>
                            <div class="col-md-1 col-12 form-group">
                              <button
                                class="btn btn-icon btn-danger"
                                type="button"
                                @click="removeRow(n - 1)"
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
                          >
                            <i class="bx bx-plus"></i> Add criteria
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card shadow-none mt-1 mb-0">
              <div class="card-header border-line-bottom-title">
                <h4 class="card-title">Choose Columns</h4>
              </div>
              <div class="card-content">
                <div class="card-body pt-0 pb-0">
                  <div class="contact-repeater">
                    <div class="m-width-s mb-0 pb-0 border-0">
                      <div class="row">
                        <!-- Draggable List : OPEN -->
                        <draggable
                          tag="ul"
                          v-model="form.view"
                          style="width: 100%"
                          v-bind="dragOptions"
                          @start="isDragging = true"
                          @end="isDragging = false"
                          class="list-group"
                          handle=".handle"
                        >
                          <transition-group type="transition" name="flip-list">
                            <li
                              class="list-group-item"
                              v-for="(element, idx) in form.view"
                              :key="idx + ' ' + element.header"
                            >
                              <span
                                style="cursor: pointer"
                                class="bx bx-grid-vertical pr-1 handle"
                              ></span>
                              <div class="checkbox">
                                <input
                                  type="checkbox"
                                  v-model="element.visibility"
                                  class="checkbox-input"
                                  value="photodrag"
                                  :id="'dragging_' + idx"
                                  :true-value="'1'"
                                  :false-value="'0'"
                                />
                                <label :for="'dragging_' + idx">{{
                                  element.header
                                }}</label>
                              </div>
                            </li>
                          </transition-group>
                        </draggable>
                        <!-- Draggable List : CLOSE -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- form layout section end -->
          </form>
        </div>
      </div>
    </div>
    <!-- END: Content-->
  </div>
</template>
<script>
import draggable from "vuedraggable";
import vSelect from "vue-select";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import { mapGetters, mapActions } from "vuex";
import Toast from "../../library/Toast";

export default {
  name: "DealAddCustomView",
  components: {
    "v-select": vSelect,
    draggable,
    DatePicker,
  },
  data() {
    return {
      errors: [],
      submit_button: false,
      query_rows: 0,
      map_columns: [],
      map_comparator: [],
      map_value_1: [],
      form: {
        default: "0",
        header: "",
        query: [],
        view: [
          {
            header: "FIRST NAME",
            column: "first_name",
            component: "text",
            visibility: "1",
          },
          {
            header: "LAST NAME",
            column: "last_name",
            component: "text",
            visibility: "1",
          },
          {
            header: "COMPANY NAME",
            column: "company_name",
            component: "text",
            visibility: "0",
          },
          {
            header: "PHONE",
            column: "phone",
            component: "phone",
            visibility: "1",
          },
          {
            header: "MOBILE",
            column: "mobile",
            component: "phone",
            visibility: "1",
          },
          {
            header: "EMAIL",
            column: "email",
            component: "email",
            visibility: "1",
          },
          {
            header: "YEAR",
            column: "year",
            component: "text",
            visibility: "1",
          },
          {
            header: "MAKE",
            column: "make",
            component: "text",
            visibility: "1",
          },
          {
            header: "MODEL",
            column: "model",
            component: "text",
            visibility: "1",
          },
          {
            header: "VIN",
            column: "vin",
            component: "text",
            visibility: "1",
          },
          {
            header: "STOCK NO",
            column: "stock_no",
            component: "text",
            visibility: "1",
          },
          {
            header: "ODOMETER",
            column: "odometer",
            component: "distance",
            visibility: "1",
          },
          {
            header: "EXTERIOR COLOR",
            column: "exterior_color",
            component: "text",
            visibility: "0",
          },
          {
            header: "INTERIOR COLOR",
            column: "interior_color",
            component: "text",
            visibility: "0",
          },
          {
            header: "TYPE",
            column: "type",
            component: "text",
            visibility: "1",
          },
          {
            header: "DATE",
            column: "date",
            component: "date",
            visibility: "1",
          },
          {
            header: "RL or WHL",
            column: "retail_or_wholesale_flag",
            component: "rtl_wh_flag",
            visibility: "1",
          },
          {
            header: "STATUS",
            column: "status",
            component: "badge",
            visibility: "1",
          },
          {
            header: "Approved By",
            column: "approved_by",
            component: "user",
            visibility: "1",
          },
          {
            header: "Salesperson",
            column: "salesperson",
            component: "user",
            visibility: "1",
          },
          {
            header: "CREATION DATE",
            column: "created_at",
            component: "date",
            visibility: "1",
          },
          {
            header: "MODIFIED DATE",
            column: "updated_at",
            component: "date",
            visibility: "1",
          },
        ],
      },
    };
  },
  computed: {
    ...mapGetters("baseView", ["getDefaultComparatorMapping"]),
    ...mapGetters("deal", ["getDealStatuses"]),
    ...mapGetters("users", ["allUsers"]),
    ...mapGetters("dealView", [
      "getAvailableValueMapper",
      "getMapColumns",
      "getViewId",
    ]),
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost",
      };
    },
    users() {
      return this.allUsers.map((user) => {
        return { label: user.name, value: user.user_id };
      });
    },
    updateView() {
      return this.$route.params.id && this.$route.name == "deal-view-update";
    },
  },
  methods: {
    ...mapActions("users", ["getAllUsers"]),
    ...mapActions("dealView", ["commitOnlyEntities", "commitLoadState"]),
    showTypeComparatorElement(
      current_element_type = "",
      all_element_types = [],
      comparator = ""
    ) {
      return (
        all_element_types.includes(current_element_type) && comparator != ""
      );
    },
    async getSmartView(id) {
      let request_url = `${window.location.origin}/api/business/deals/views/show/${id}`;

      await axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.form["header"] = data["header"];
          this.form["default"] = data["default"];
          this.form["view"] = JSON.parse(data["view"]);
          this.form["query"] = JSON.parse(data["query"]);

          this.form.query =
            this.form.query.length < 1
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
              : this.form.query;

          this.query_rows = this.form.query.length;

          for (const key in this.form.query) {
            this.changeQueryAvailableValues(key);
          }
        })
        .catch((response) => {
          let error = "There were some errors in retrieving the view";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
    save() {
      const vm = this;
      this.submit_button = false;

      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && key != "query" && key != "view") {
          formData.append(key, this.form[key]);
        }
      }

      formData.append("view", JSON.stringify(this.form["view"]));
      formData.append("query", JSON.stringify(this.form["query"]));

      let request_url = this.updateView
        ? `${window.location.origin}/api/business/deals/views/update/${this.$route.params.id}`
        : `${window.location.origin}/api/business/deals/views/create`;

      let message = this.updateView
        ? "Updating deal view ..."
        : "Creating new deal view ...";

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
        .then((response) => {
          let success_message = this.updateView
            ? "View has been updated."
            : "New view created successfully.";

          vm.$data.submit_button = true;

          toast.success(success_message);
          toast.close();

          if (vm.updateView) {
            if (vm.$route.params.id == vm.getViewId) {
              vm.commitLoadState(false);
            }
          }

          setTimeout(() => {
            vm.$router.push({ name: "deals" });
          }, 500);
        })
        .catch(function (error) {
          // handle error
          let error_message = "There were some errors while submitting form.";
          vm.$data.errors = error.response.data;
          toast.fail(error_message);
          toast.close();

          vm.$data.submit_button = true;
        });
    },
    removeRow(current_row) {
      this.form.query.splice(current_row, 1);
      this.map_comparator.splice(current_row, 1);
      this.map_value_1.splice(current_row, 1);
      this.query_rows -= 1;
    },
    addNewRow() {
      this.map_comparator.push([]);
      this.map_value_1.push([]);
      this.form.query.push({
        header: "",
        column: "",
        join: "AND",
        comparator: "",
        type: "",
        value_1: "",
        value_2: "",
      });
      this.query_rows += 1;
    },
    changeQueryDetails(i, reset_query = true) {
      //fetch the row for which the input has changed.
      //once fetched changed the column_name, comparator in the corresponding object.
      let query_row = this.form.query[i];
      query_row.column = this.map_columns.filter(
        (x) => x.header == query_row.header
      )[0].column;
      query_row.type = this.map_columns.filter(
        (x) => x.header == query_row.header
      )[0].type;

      if (reset_query) {
        query_row.value_1 = "";
        query_row.comparator = "";
        query_row.value_2 = "";
      }

      this.map_value_1[i] = [];

      const default_mapper = this.getDefaultComparatorMapping.filter(
        (map) => map.type === query_row.type
      );

      if (default_mapper.length > 0) {
        this.map_comparator[i] = default_mapper[0]["comparator"];
      }

      const available_value_mapper = [
        {
          header: "Approved By",
          values: this.users,
        },
        {
          header: "Salesperson",
          values: this.users,
        },
        ...this.getAvailableValueMapper,
      ].filter((map) => map.header === query_row.header);

      if (available_value_mapper.length > 0) {
        this.map_value_1[i] = available_value_mapper[0]["values"];
      }
    },
    changeQueryAvailableValues(i) {
      this.changeQueryDetails(i, false);
    },
  },
  async created() {
    await this.getAllUsers();
    this.map_columns = this.getMapColumns;
    this.submit_button = true;
  },
  async mounted() {
    if (this.updateView) {
      await this.getSmartView(this.$route.params.id);
    }
  },
};
</script>
<style lang="scss">
@import "vue-select/src/scss/vue-select.scss";
#add_deal_custom_view .v-select > div {
  height: 2.2em;
}

#add_deal_custom_view .vs__selected {
  font-size: 1rem;
  padding-left: 1em;
  align-items: normal;
}

#add_deal_custom_view .webapp-form .form-group .vdpComponent input {
  padding-left: 2.2em !important;
  line-height: 1.4;
  color: #475f7b;
  background-color: #ffffff;
  background-clip: padding-box;
  border: 1px solid #dfe3e7;
  border-radius: 0.267rem;
}

#add_deal_custom_view .vdpArrowPrev:after {
  border-right-color: green;
}

#add_deal_custom_view .vdpArrowNext:after {
  border-left-color: green;
}

#add_deal_custom_view .vdpCell.selectable:hover .vdpCellContent,
#add_deal_custom_view .vdpCell.selected .vdpCellContent {
  background: green;
}

#add_deal_custom_view .vdpCell.today {
  color: green;
}

#add_deal_custom_view .vdpTimeUnit > input:hover,
#add_deal_custom_view .vdpTimeUnit > input:focus {
  border-bottom-color: green;
}

#add_deal_custom_view .multi-select-component .v-select > div {
  height: auto;
}
.multi-select-component .vs__selected {
  background-color: #5a8dee !important;
  border-color: transparent !important;
  color: #ffffff !important;
  padding: 0.2rem 0.6rem 0.2rem 0.6rem !important;
  height: 2em;
}
.multi-select-component .vs__deselect {
  fill: white;
}
.multi-select-component .vs__dropdown-option--selected {
  color: #727e8c;
  background: #ddd;
}
.multi-select-component .vs__dropdown-menu {
  height: 200px;
}
</style>
