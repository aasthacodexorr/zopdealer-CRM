<template>
  <div
    class="tab-pane"
    id="inventory-vehicle-inspection-panel"
    aria-labelledby="Inventory Vehicle Inspection"
    role="tabpanel"
  >
    <section id="vehicle-inspection-panel">
      <div class="row match-height">
        <div class="col-md-12 col-12">
          <div class="card shadow-none mb-0">
            <div class="card-content">
              <div class="card-body pt-0">
                <form class="form form-horizontal webapp-form">
                  <div class="form-body">
                    <div class="row">
                      <h5
                        v-if="show_vehicle_inspection_report_notice == '1'"
                        class="col-12 text-danger"
                        style="font-size: 0.9em"
                      >
                        <strong
                          >NOTE: To enable the vehicle inspection report on the
                          website contact ZOP Support.</strong
                        >
                      </h5>
                      <div class="col-md-12 col-12">
                        <div class="m-width-s">
                          <div class="row" style="position: relative">
                            <span
                              style="
                                position: absolute;
                                right: -6em;
                                z-index: 999;
                              "
                            >
                              <i
                                class="bx bxs-edit pr-1 bx-md"
                                v-if="edit.main_index != -1 && edit.index != -1"
                                @click="
                                  edit.main_index = -1;
                                  edit.index = -1;
                                "
                                title="Edit Title & Description"
                                style="cursor: pointer"
                              ></i>
                              <i
                                class="bx bx-repost pr-1 bx-md"
                                @click="confirmResetAction"
                                title="Reset Inspection Report"
                                style="cursor: pointer"
                              ></i>
                              <i
                                class="bx bx-save pr-1 bx-md"
                                @click="
                                  edit.main_index = null;
                                  edit.index = null;
                                  triggerSaveWebInspectionData();
                                "
                                title="Save Details"
                                style="cursor: pointer"
                              ></i>
                            </span>
                            <h5
                              class="col-12"
                              v-if="vehicle_inspection_report !== null"
                            >
                              <input
                                v-if="edit.main_index == -1 && edit.index == -1"
                                v-model="vehicle_inspection_report.title"
                                type="text"
                                class="form-control"
                              />
                              <span v-else>
                                {{ vehicle_inspection_report.title }}
                              </span>
                            </h5>
                            <div
                              class="col-12 p-1"
                              v-if="vehicle_inspection_report !== null"
                            >
                              <textarea
                                v-if="edit.main_index == -1 && edit.index == -1"
                                v-model="vehicle_inspection_report.description"
                                class="form-control"
                              ></textarea>
                              <span v-else>
                                {{ vehicle_inspection_report.description }}
                              </span>
                            </div>
                            <!-- Inspected By : START -->
                            <div
                              class="col-12 p-1"
                              v-if="vehicle_inspection_report !== null"
                            >
                              <label class="pr-1">Inspected By</label>
                              <span style="display: inline-block">
                                <select
                                  id="inspected_by_id"
                                  class="custom-select"
                                  name="inspected_by"
                                  v-model="
                                    vehicle_inspection_report.inspected_by
                                  "
                                >
                                  <option disabled value="">
                                    -- Inspected By --
                                  </option>
                                  <option
                                    :value="user.user_id"
                                    v-for="user in allUsers"
                                    :key="user.user_id + '_inspected_by'"
                                    v-text="user.name"
                                  ></option>
                                </select>
                              </span>
                            </div>
                            <!-- Inspected By : END -->
                            <!-- Inspected DateTime : START -->
                            <div
                              class="col-12 p-1"
                              v-if="vehicle_inspection_report !== null"
                            >
                              <div class="">
                                <label class="pr-1" for="start_date"
                                  >Inspection Date</label
                                >
                                <fieldset
                                  class="has-icon-left"
                                  style="
                                    position: relative;
                                    display: inline-block;
                                  "
                                >
                                  <date-picker
                                    placeholder="Inspection Date"
                                    v-model="
                                      vehicle_inspection_report.inspected_datetime
                                    "
                                    type="datetime"
                                    :format="'MM/DD/YYYY HH:mm'"
                                    :value-type="'MM/DD/YYYY HH:mm'"
                                    :minute-step="5"
                                    id="inspected_datetime"
                                  >
                                  </date-picker>
                                </fieldset>
                              </div>
                            </div>
                            <!-- Inspected DateTime : END -->
                            <div
                              class="col-12 p-1"
                              v-if="vehicle_inspection_report !== null"
                            >
                              <div class="custom-control custom-switch">
                                <label class="pr-1"
                                  >Show Inspection Report On Website</label
                                >
                                <input
                                  type="checkbox"
                                  class="custom-control-input"
                                  id="vehicleInspectionReportVisibility"
                                  name="Visibility"
                                  v-model="vehicle_inspection_report.visibility"
                                  :true-value="'1'"
                                  :false-value="'0'"
                                  @change="triggerSaveWebInspectionData()"
                                  checked
                                />
                                <label
                                  class="custom-control-label"
                                  for="vehicleInspectionReportVisibility"
                                >
                                  <span class="switch-icon-left"> ON</span>
                                  <span class="switch-icon-right">OFF</span>
                                </label>
                              </div>
                            </div>
                            <div
                              class="col-12 text-right"
                              v-if="created_at !== null"
                            >
                              <span class="pl-1" style="font-size: 0.8em"
                                >Created At : {{ created_at }} Last Updated :
                                {{ updated_at }}</span
                              >
                            </div>
                            <table
                              class="table col-12"
                              v-if="vehicle_inspection_report !== null"
                            >
                              <tbody>
                                <template
                                  v-for="(
                                    section, main_index
                                  ) in vehicle_inspection_report.sections"
                                >
                                  <tr :key="section.name" class="thead-dark">
                                    <th scope="col" colspan="3">
                                      {{ section.name }}
                                    </th>
                                    <th scope="col" style="width: 200px">
                                      <i
                                        class="bx bxs-check-circle text-success pr-1 bx-sm"
                                        title="Passed"
                                      ></i>
                                      <i
                                        class="bx bx-error-circle text-warning bx-sm pr-1"
                                        title="Imperfections"
                                      ></i>
                                      <i
                                        class="bx bxs-x-circle bx-sm"
                                        title="Not Applicable"
                                      ></i>
                                    </th>
                                  </tr>
                                  <tr
                                    v-for="(item, index) in section.list"
                                    :key="`section_` + main_index + `_` + index"
                                  >
                                    <td class="p-50">{{ index + 1 }}</td>
                                    <td class="p-50">
                                      <h6 class="col-12">
                                        <input
                                          class="form-control"
                                          placeholder="Title"
                                          v-if="
                                            edit.main_index == main_index &&
                                            edit.index == index
                                          "
                                          v-model="
                                            vehicle_inspection_report[
                                              'sections'
                                            ][main_index]['list'][index][
                                              'title'
                                            ]
                                          "
                                        />
                                        <span v-else>{{ item.title }}</span>
                                      </h6>
                                      <div
                                        class="col-12"
                                        style="
                                          font-size: 0.8em;
                                          font-style: italic;
                                        "
                                      >
                                        <textarea
                                          class="form-control"
                                          placeholder="Additional Details..."
                                          v-if="
                                            edit.main_index == main_index &&
                                            edit.index == index
                                          "
                                          v-model="
                                            vehicle_inspection_report[
                                              'sections'
                                            ][main_index]['list'][index][
                                              'description'
                                            ]
                                          "
                                        ></textarea>
                                        <span v-else>{{
                                          item.description
                                        }}</span>
                                      </div>
                                    </td>
                                    <td
                                      class="p-50 text-center"
                                      style="width: 100px"
                                    >
                                      <i
                                        class="bx bx-save pr-1 bx-sm"
                                        v-if="
                                          edit.main_index == main_index &&
                                          edit.index == index
                                        "
                                        @click="
                                          edit.main_index = null;
                                          edit.index = null;
                                          triggerSaveWebInspectionData();
                                        "
                                        title="Save Details"
                                        style="cursor: pointer"
                                      ></i>
                                      <i
                                        class="bx bxs-pencil pr-1 bx-sm"
                                        v-else
                                        @click="
                                          edit.main_index = main_index;
                                          edit.index = index;
                                        "
                                        title="Add Details"
                                        style="cursor: pointer"
                                      ></i>
                                      <i
                                        style="cursor: pointer"
                                        @click="
                                          setVisibility(main_index, index);
                                          triggerSaveWebInspectionData();
                                        "
                                        :class="[
                                          item.visibility == '1'
                                            ? 'bxs-show'
                                            : 'bxs-hide',
                                        ]"
                                        class="bx pr-1 bx-sm"
                                        :title="[
                                          item.visibility == '1'
                                            ? 'Visible'
                                            : 'Hidden',
                                        ]"
                                      ></i>
                                    </td>
                                    <td class="p-50 text-center">
                                      <i
                                        style="cursor: pointer"
                                        @click="
                                          setInspectionPropertyValue(
                                            main_index,
                                            index,
                                            'Passed'
                                          );
                                          triggerSaveWebInspectionData();
                                        "
                                        :class="[
                                          item.value == 'Passed'
                                            ? 'text-success bx-md'
                                            : 'text-light',
                                        ]"
                                        class="bx bxs-check-circle pr-1 bx-sm"
                                        title="Passed"
                                      ></i>
                                      <i
                                        style="cursor: pointer"
                                        @click="
                                          setInspectionPropertyValue(
                                            main_index,
                                            index,
                                            'Imperfections'
                                          );
                                          triggerSaveWebInspectionData();
                                        "
                                        :class="[
                                          item.value == 'Imperfections'
                                            ? 'text-warning bx-md'
                                            : 'text-light',
                                        ]"
                                        class="bx bx-error-circle bx-sm pr-1"
                                        title="Imperfections"
                                      ></i>
                                      <i
                                        style="cursor: pointer"
                                        @click="
                                          setInspectionPropertyValue(
                                            main_index,
                                            index,
                                            'Not Applicable'
                                          );
                                          triggerSaveWebInspectionData();
                                        "
                                        :class="[
                                          item.value == 'Not Applicable'
                                            ? 'text-secondary bx-md'
                                            : 'text-light',
                                        ]"
                                        class="bx bxs-x-circle bx-sm"
                                        title="Not Applicable"
                                      ></i>
                                    </td>
                                  </tr>
                                </template>
                              </tbody>
                            </table>
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
import Toast from "../../library/Toast";
import vSelect from "vue-select";

import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import { debounce } from "vue-debounce";

export default {
  props: {
    id: { required: true },
    allUsers: { required: true },
    app_metadata: { required: true },
    time_zone: String,
  },
  name: "InventoryManageWebInspection",
  data() {
    return {
      vehicle_inspection_report: null,
      inventory_vehicle_inspection_id: null,
      default_vehicle_inspection_report: null,
      show_vehicle_inspection_report_notice: null,
      created_at: null,
      updated_at: null,
      edit: {
        main_index: null,
        index: null,
      },
    };
  },
  components: {
    "v-select": vSelect,
    DatePicker,
  },
  methods: {
    triggerSaveWebInspectionData: debounce(function () {
      this.saveInventoryWebInspectionData();
    }, "700ms"),
    confirmResetAction() {
      const vm = this;
      this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Confirm",
        toast: false,
        position: "center",
        text: "Please confirm if you want to reset the vehicle inspection sheet.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.vehicle_inspection_report = JSON.parse(
            JSON.stringify(vm.default_vehicle_inspection_report)
          );
          vm.saveInventoryWebInspectionData();
        }
      });
    },
    async getInventoryWebInspectionData(inventory_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/inventory/" +
          inventory_id +
          "/vehicle-inspection",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.vehicle_inspection_report = JSON.parse(data.detail);
          this.inventory_vehicle_inspection_id = data.id;
          this.created_at = this.$helpers.changeUTCtoLocalDate(
            data.created_at,
            "MM/DD/YYYY hh:mm A",
            this.time_zone
          );
          this.updated_at = this.$helpers.changeUTCtoLocalDate(
            data.updated_at,
            "MM/DD/YYYY hh:mm A",
            this.time_zone
          );
        })
        .catch(({ response }) => {
          this.vehicle_inspection_report = JSON.parse(
            JSON.stringify(this.default_vehicle_inspection_report)
          );
        });
    },
    async getInventoryWebInspectionID(inventory_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/inventory/" +
          inventory_id +
          "/vehicle-inspection",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.inventory_vehicle_inspection_id = data.id;
        })
        .catch(({ response }) => {});
    },
    async saveInventoryWebInspectionData() {
      const vm = this;
      let formData = new FormData();
      formData.append("detail", JSON.stringify(this.vehicle_inspection_report));

      let request_url = window.location.origin;
      let update_request =
        !isNaN(this.inventory_vehicle_inspection_id) &&
        this.inventory_vehicle_inspection_id != null;

      let success_message = update_request
        ? `Inventory vehicle inspection report updated.`
        : `Inventory vehicle inspection report added.`;

      request_url = update_request
        ? `${request_url}/api/business/inventory/${this.$route.params.id}/vehicle-inspection/update/${this.inventory_vehicle_inspection_id}`
        : `${request_url}/api/business/inventory/${this.$route.params.id}/vehicle-inspection`;

      //message during request
      let request_message = update_request
        ? `Updating inventory vehicle inspection report ...`
        : `Adding new inventory vehicle inspection report ...`;

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

          if (vm.$route.params.id && vm.$route.name == "inventory-update") {
            vm.getInventoryWebInspectionID(vm.$route.params.id);
          }
        })
        .catch(function (error) {
          let update_request =
            !isNaN(vm.inventory_vehicle_inspection_id) &&
            vm.inventory_vehicle_inspection_id != null;
          let error_message = update_request
            ? "There were errors while updating the vehicle inspection report."
            : "There were some errors while adding vehicle inspection report.";
          toast.fail(error_message);
          toast.close();
        });

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "inventory",
        "vehicle_inspection",
        "save"
      );
    },
    setInspectionPropertyValue(main_index, index, value) {
      this.vehicle_inspection_report["sections"][main_index]["list"][index][
        "value"
      ] = value;
    },
    setVisibility(main_index, index) {
      let value =
        this.vehicle_inspection_report["sections"][main_index]["list"][index][
          "visibility"
        ];
      this.vehicle_inspection_report["sections"][main_index]["list"][index][
        "visibility"
      ] = value == "1" ? "0" : "1";
    },
    async getInventoryWebInspectionConfigurations() {
      let request_url =
        window.location.origin +
        "/api/business/configurations/active/inventory";
      await axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (element.key == "default_vehicle_inspection_report") {
              this.default_vehicle_inspection_report = JSON.parse(
                element.value
              );
            } else if (element.key == "show_vehicle_inspection_report_notice") {
              this.show_vehicle_inspection_report_notice = JSON.parse(
                element.value
              );
            }
          }
        }
      });
    },
  },
  async created() {
    await this.getInventoryWebInspectionConfigurations();
    await this.getInventoryWebInspectionData(this.$route.params.id);
  },
};
</script>
<style lang="scss">
#inventory-vehicle-inspection-panel {
  .vdpComponent {
    height: 3.9em !important;
  }
  .vdpComponent > input {
    padding-left: 2.2em !important;
    line-height: 1.4;
    color: #777;
    background-color: #ffffff !important;
    background-clip: padding-box;
    border: 1px solid #dfe3e7;
    border-radius: 0.267rem;
    font-size: 1.4em;
  }

  .vdpComponent input {
    background: white;
    border: 0px;
  }
  .vdpComponent input:read-only {
    background: white !important;
    padding-right: 0em;
    font-size: 1.4em;
    border: 0px;
    padding-left: 0px !important;
  }

  .vdpComponent .vdpTimeUnit {
    min-width: 50px;
  }

  .notification-icon-small {
    font-size: 0.5em;
    height: 50px;
    width: 50px;
  }

  .vdpComponent .vdpClearInput {
    display: none !important;
  }
}
</style>
