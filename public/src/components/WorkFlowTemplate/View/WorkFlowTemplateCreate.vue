<template>
  <div
    class="tab-pane active"
    id="workflow-create-panel"
    aria-labelledby="information-tab"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="general">
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
                            <!-- Title : START -->
                            <div class="col-md-4">
                              <label>Title</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <input
                                    type="text"
                                    id="title"
                                    class="form-control"
                                    name="title"
                                    placeholder="Title"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('title'),
                                    }"
                                    :disabled="updateView ? true : false"
                                    @keyup="delete errors.title"
                                    :title="errors.title"
                                    v-model="form.title"
                                  />
                                </div>
                              </fieldset>
                            </div>
                            <!-- Title : END -->

                            <!-- Description : START -->
                            <div class="col-md-4">
                              <label>Description</label>
                            </div>
                            <div class="col-md-8 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <textarea
                                    class="form-control"
                                    name=""
                                    id=""
                                    cols="30"
                                    rows="5"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('description'),
                                    }"
                                    @keyup="delete errors.description"
                                    :title="errors.description"
                                    v-model="form.description"
                                    :disabled="updateView ? true : false"
                                  ></textarea>
                                </div>
                              </fieldset>
                            </div>
                            <!-- Description : END -->

                            <!-- Module : START -->
                            <div class="col-md-4">
                              <label>Module</label>
                            </div>
                            <div class="col-md-4 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <select
                                    :disabled="updateView ? true : false"
                                    name="module"
                                    id="module"
                                    class="form-control"
                                    :class="{
                                      'is-invalid':
                                        errors.hasOwnProperty('module'),
                                    }"
                                    @change="delete errors.module"
                                    :title="errors.module"
                                    v-model="form.module"
                                  >
                                    <option selected disabled value="">
                                      --- Select Module ---
                                    </option>
                                    <option
                                      :value="current_module"
                                      v-for="current_module in getAvailableWorkFlowModules"
                                      :key="`option_${current_module}`"
                                    >
                                      {{ current_module }}
                                    </option>
                                  </select>
                                </div>
                              </fieldset>
                            </div>
                            <!-- Module : END -->
                            <div class="w-100"></div>

                            <!-- Enable WorkFlow : START -->
                            <div class="col-md-4">
                              <label>Enable</label>
                            </div>
                            <div class="col-md-4 form-group">
                              <div class="custom-control custom-switch">
                                <input
                                  :disabled="updateView ? true : false"
                                  v-model="form.enable"
                                  type="checkbox"
                                  class="custom-control-input"
                                  id="enableWorkFlowSwitch"
                                  name="Visibility"
                                  :true-value="'1'"
                                  :false-value="'0'"
                                  checked
                                />
                                <label
                                  class="custom-control-label"
                                  for="enableWorkFlowSwitch"
                                >
                                  <span class="switch-icon-left"> ON</span>
                                  <span class="switch-icon-right">OFF</span>
                                </label>
                              </div>
                            </div>
                            <!-- Enable WorkFlow : END -->
                          </div>
                          <div class="card-header border-line-bottom-title">
                            <h5 class="card-title">Trigger</h5>
                          </div>
                          <div class="row">
                            <!-- Trigger : START -->
                            <div class="col-md-4">
                              <label>Trigger</label>
                            </div>
                            <div class="col-md-4 form-group">
                              <v-select
                                :disabled="updateView ? true : false"
                                v-model="form.trigger"
                                append-to-body
                                placeholder="Triggers"
                                taggable
                                pushTags
                                clearable
                                multiple
                                :options="triggers"
                              ></v-select>
                            </div>
                            <!-- Trigger : END -->
                          </div>
                          <div class="card-header border-line-bottom-title">
                            <h5 class="card-title">Condition(s)</h5>
                          </div>
                          <div class="row">
                            <!-- Each Row of Query : OPEN-->
                            <div
                              class="col-12 col-md-12"
                              data-repeater-list="contact"
                              v-for="n in query_rows"
                              :key="form.condition[n - 1].header + n"
                            >
                              <div class="row" data-repeater-item>
                                <div
                                  v-if="n > 1"
                                  class="col-md-2 col-12 form-group d-flex align-items-center pr-0"
                                >
                                  <v-select
                                    placeholder="Join"
                                    :options="['AND', 'OR']"
                                    v-model="form.condition[n - 1].join"
                                    style="width: 100%"
                                    :disabled="updateView ? true : false"
                                  />
                                </div>

                                <div
                                  class="col-md-4 col-12 form-group d-flex align-items-center pr-0"
                                >
                                  <v-select
                                    :disabled="updateView ? true : false"
                                    placeholder="Attribute"
                                    :options="map_columns"
                                    @input="changeQueryDetails(n - 1)"
                                    v-model="form.condition[n - 1].header"
                                    style="width: 100%"
                                    :reduce="
                                      (map_columns) => map_columns.header
                                    "
                                    label="header"
                                  />
                                </div>
                                <div class="col-md-4 col-12 form-group">
                                  <v-select
                                    :disabled="updateView ? true : false"
                                    placeholder="Comparator"
                                    :options="map_comparator[n - 1]"
                                    v-model="form.condition[n - 1].comparator"
                                    style="width: 100%"
                                  />
                                </div>
                              </div>

                              <div class="row" data-repeater-item>
                                <div
                                  class="col-md-4 col-12 form-group pl-0 pr-0 multi-select-component"
                                  v-if="
                                    form.condition[n - 1].type ==
                                      'Multi Select' &&
                                    form.condition[n - 1].comparator != ''
                                  "
                                >
                                  <v-select
                                    :disabled="updateView ? true : false"
                                    multiple
                                    placeholder="Multi Select"
                                    :options="map_value_1[n - 1]"
                                    v-model="form.condition[n - 1].value_1"
                                    style="width: 100%"
                                  />
                                </div>
                                <div
                                  class="col-md-4 col-12 form-group pl-0 pr-0"
                                  v-else-if="
                                    form.condition[n - 1].type ==
                                      'Date Comparision' &&
                                    form.condition[n - 1].comparator != ''
                                  "
                                >
                                  <date-picker
                                    :disabled="updateView ? true : false"
                                    v-model="form.condition[n - 1].value_1"
                                    :format="'MM/DD/YYYY'"
                                    :value-type="'MM/DD/YYYY'"
                                  ></date-picker>
                                </div>
                                <div
                                  class="col-md-4 col-12 form-group pl-0 pr-0"
                                  v-else-if="
                                    form.condition[n - 1].type ==
                                      'Single Select' &&
                                    form.condition[n - 1].comparator != ''
                                  "
                                >
                                  <v-select
                                    :disabled="updateView ? true : false"
                                    placeholder="Single Select"
                                    :options="map_value_1[n - 1]"
                                    :reduce="(each) => each.value"
                                    v-model="form.condition[n - 1].value_1"
                                    style="width: 100%"
                                  />
                                </div>
                                <div
                                  class="col-md-11 col-12 form-group pl-0 pr-0"
                                  v-else-if="
                                    (form.condition[n - 1].type ==
                                      'Numeric Comparision' ||
                                      form.condition[n - 1].type ==
                                        'Relative Time Period Comparision') &&
                                    form.condition[n - 1].comparator != ''
                                  "
                                >
                                  <input
                                    :disabled="updateView ? true : false"
                                    type="number"
                                    style="width: 33.33%; display: inline-block"
                                    min="0"
                                    class="form-control mr-1"
                                    name=""
                                    v-model="form.condition[n - 1].value_1"
                                    placeholder="Value 1"
                                    required
                                  />
                                  <input
                                    :disabled="updateView ? true : false"
                                    type="number"
                                    style="width: 33.33%; display: inline-block"
                                    min="0"
                                    class="form-control mr-1"
                                    v-show="
                                      form.condition[n - 1].comparator ==
                                      'between'
                                    "
                                    name=""
                                    v-model="form.condition[n - 1].value_2"
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
                                      form.condition[n - 1].type ==
                                        'Numeric Comparision' &&
                                      form.condition[n - 1].comparator != ''
                                    )
                                  "
                                ></div>
                                <div class="col-md-1 col-12 form-group">
                                  <button
                                    :disabled="updateView ? true : false"
                                    class="btn btn-icon btn-danger"
                                    type="button"
                                    data-repeater-delete
                                    @click="
                                      form.condition.splice(n - 1, 1);
                                      map_comparator.splice(n - 1, 1);
                                      map_value_1.splice(n - 1, 1);
                                      query_rows -= 1;
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
                                :disabled="updateView ? true : false"
                                data-repeater-create
                              >
                                <i class="bx bx-plus"></i> Add criteria
                              </button>
                            </div>
                          </div>
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
import Toast from "../../../library/Toast";
import { mapGetters, mapActions } from "vuex";
import draggable from "vuedraggable";
import vSelect from "vue-select";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

export default {
  name: "WorkFlowTemplateCreate",
  props: ["application", "app_metadata"],
  data() {
    return {
      loading: true,
      errors: [],
      triggers: ["Create", "Update"],
      locations: [],
      query_rows: 0,
      map_columns: [],
      map_comparator: [],
      map_value_1: [],
      available_mapper: [],
      form: {
        title: "",
        description: "",
        module: "",
        trigger: "",
        condition: [],
        enable: "1",
      },
    };
  },
  watch: {
    "form.module": function (newVal, oldVal) {
      if (!this.loading) {
        this.form.condition = [];
        this.query_rows = 0;
        this.map_comparator = [];
        this.map_value_1 = [];
      }

      if (newVal !== oldVal) {
        Event.fire("change_module", newVal);
        this.map_columns = this.available_mapper = [];

        if (newVal === "Inventory") {
          this.map_columns = this.inventoryGetMapColumns;
          this.available_mapper = [
            {
              header: "Location",
              values: this.locations,
            },
            ...this.inventoryGetAvailableValueMapper,
          ];
        } else if (newVal === "Lead") {
          this.map_columns = this.leadGetMapColumns;
          this.available_mapper = [
            {
              header: "Assignee",
              values: this.users,
            },
            ...this.leadGetAvailableValueMapper,
          ];
        } else if (newVal === "Deal") {
          this.map_columns = this.dealGetMapColumns;
          this.available_mapper = [
            {
              header: "Approved By",
              values: this.users,
            },
            {
              header: "Salesperson",
              values: this.users,
            },
            ...this.dealGetAvailableValueMapper,
          ];
        } else {
          this.map_columns = this.available_mapper = [];
        }
      }
    },
  },
  components: {
    "v-select": vSelect,
    draggable,
    DatePicker,
  },
  computed: {
    ...mapGetters("users", ["allUsers"]),
    ...mapGetters("workFlow", ["getAvailableWorkFlowModules"]),
    ...mapGetters("baseView", ["getDefaultComparatorMapping"]),
    ...mapGetters("inventoryView", {
      inventoryGetAvailableValueMapper: "getAvailableValueMapper",
      inventoryGetMapColumns: "getMapColumns",
    }),
    ...mapGetters("leadResponseView", {
      leadGetAvailableValueMapper: "getAvailableValueMapper",
      leadGetMapColumns: "getMapColumns",
    }),
    ...mapGetters("dealView", {
      dealGetAvailableValueMapper: "getAvailableValueMapper",
      dealGetMapColumns: "getMapColumns",
    }),
    users() {
      return this.allUsers.map((user) => {
        return { label: user.name, value: user.user_id };
      });
    },
    updateView() {
      return (
        this.$route.params.id && this.$route.name == "workflow-template-update"
      );
    },
  },
  methods: {
    ...mapActions("users", ["getAllUsers"]),
    async getLocations() {
      let request_url =
        window.location.origin + "/api/business/location/active";
      await axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      }).then(({ data }) => {
        this.locations = data.locations.map((location) => location.name);
      });
    },
    addNewRow() {
      this.map_comparator.push([]);
      this.map_value_1.push([]);
      this.form.condition.push({
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
    changeQueryDetails(i, reset = true) {
      //fetch the row for which the input has changed.
      //once fetched changed the column_name, comparator in the corresponding object.
      let query_row = this.form.condition[i];
      query_row.column = this.map_columns.filter(
        (x) => x.header == query_row.header
      )[0].column;
      query_row.type = this.map_columns.filter(
        (x) => x.header == query_row.header
      )[0].type;

      if (reset) {
        query_row.value_1 = "";
        query_row.comparator = "";
        query_row.value_2 = "";
      }

      const default_mapper = this.getDefaultComparatorMapping.filter(
        (map) => map.type === query_row.type
      );

      if (default_mapper.length > 0) {
        this.map_comparator[i] = default_mapper[0]["comparator"];
      }

      const available_value_mapper = [
        {
          header: "Location",
          values: this.locations,
        },
        ...this.available_mapper,
      ].filter((map) => map.header === query_row.header);

      if (available_value_mapper.length > 0) {
        this.map_value_1[i] = available_value_mapper[0]["values"];
      }
    },
    async save() {
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.form) {
        if (
          this.form.hasOwnProperty(key) &&
          this.form[key] != null &&
          !["condition", "trigger"].includes(key)
        ) {
          formData.append(key, this.form[key]);
        }
      }

      if (Array.isArray(this.form["trigger"])) {
        formData.append("trigger", JSON.stringify(this.form["trigger"]));
      } else {
        formData.append("trigger", "[]");
      }

      if (Array.isArray(this.form["condition"])) {
        formData.append("condition", JSON.stringify(this.form["condition"]));
      } else {
        formData.append("condition", "[]");
      }

      let request_url = window.location.origin;
      let update_request =
        this.$route.params.id && this.$route.name == "workflow-template-update";

      //success message setting
      let success_message = update_request
        ? `Workflow Template Updated Successfully.`
        : `New Workflow Template Created.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/workflow-templates/update/${this.$route.params.id}`
        : `${request_url}/api/business/workflow-templates/create`;

      //message during request
      let request_message = update_request
        ? `Updating Workflow Template ...`
        : `Creating New Workflow Template ...`;

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
          toast.success(data.success);
          toast.close();

          setTimeout(function () {
            Event.fire("save_workflow_template_complete", data);
          }, 500);
        })
        .catch(function (error) {
          let update_request =
            vm.$route.params.id && vm.$route.name == "workflow-template-update";
          let error_message = update_request
            ? "There were errors while updating the workflow template."
            : "There were some errors while creating new workflow template.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();

          setTimeout(function () {
            Event.fire("save_workflow_template_complete", null);
          }, 500);
        });
    },
    async getWorkflowTemplate(id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/workflow-templates/show/" +
          id,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          for (const key in this.form) {
            if (
              this.form.hasOwnProperty(key) &&
              data.hasOwnProperty(key) &&
              !["condition", "trigger"].includes(key)
            ) {
              this.form[key] = data[key];
            }
          }

          if (this.$helpers.isValidJsonString(data["trigger"])) {
            this.form["trigger"] = JSON.parse(data["trigger"]);
          } else {
            this.form["trigger"] = [];
          }

          if (this.$helpers.isValidJsonString(data["condition"])) {
            this.form["condition"] = JSON.parse(data["condition"]);
            this.query_rows = this.form["condition"].length;

            for (let row_index = 0; row_index < this.query_rows; row_index++) {
              this.map_comparator.push([]);
              this.map_value_1.push([]);
            }
          } else {
            this.form["condition"] = [];
            this.query_rows = 0;
          }
        })
        .catch(({ response }) => {
          let error =
            "There were some errors in retrieving the workflow template.";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
  },
  async created() {
    await this.getAllUsers();
    await this.getLocations();

    if (
      this.$route.params.id &&
      this.$route.name == "workflow-template-update"
    ) {
      await this.getWorkflowTemplate(this.$route.params.id);
    } else {
      this.loading = false;
    }
  },
  async mounted() {
    const vm = this;
    Event.listen("save_workflow_template", (data) => {
      vm.save();
    });
  },
  async updated() {
    const vm = this;

    if (this.loading) {
      this.loading = false;

      if (
        this.$route.params.id &&
        this.$route.name == "workflow-template-update"
      ) {
        setTimeout(() => {
          for (let row_index = 0; row_index < vm.query_rows; row_index++) {
            vm.changeQueryDetails(row_index, false);
          }
          vm.$forceUpdate();
        }, 500);
      }
    }
  },
  beforeDestroy() {
    Event.vue.$off("save_workflow_template", null);
  },
};
</script>
