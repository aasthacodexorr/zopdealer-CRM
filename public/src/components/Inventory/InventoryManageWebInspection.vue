<template>
  <div
    class="tab-pane"
    id="inventory-vehicle-inspection-panel"
    aria-labelledby="Inventory Vehicle Inspection"
    role="tabpanel"
  >
    <section id="vehicle-inspection-panel" class="wi-page">
      <form class="form form-horizontal webapp-form">
        <h5
          v-if="show_vehicle_inspection_report_notice == '1'"
          class="wi-notice"
        >
          <i class="bx bx-info-circle"></i>
          <strong
            >NOTE: To enable the vehicle inspection report on the
            website contact ZOP Support.</strong
          >
        </h5>

        <!-- Report header -->
        <div class="wi-card wi-report">
          <div class="wi-report-top">
            <div class="wi-report-text">
              <h5 class="wi-report-title" v-if="vehicle_inspection_report !== null">
                <input
                  v-if="edit.main_index == -1 && edit.index == -1"
                  v-model="vehicle_inspection_report.title"
                  type="text"
                  class="form-control wi-input"
                />
                <span v-else>
                  {{ vehicle_inspection_report.title }}
                </span>
              </h5>
              <div class="wi-report-desc" v-if="vehicle_inspection_report !== null">
                <textarea
                  v-if="edit.main_index == -1 && edit.index == -1"
                  v-model="vehicle_inspection_report.description"
                  class="form-control wi-input wi-textarea"
                ></textarea>
                <span v-else>
                  {{ vehicle_inspection_report.description }}
                </span>
              </div>
            </div>
            <div class="wi-report-actions">
              <button
                type="button"
                class="wi-icon-btn"
                v-if="edit.main_index != -1 && edit.index != -1"
                @click="
                  edit.main_index = -1;
                  edit.index = -1;
                "
                title="Edit Title & Description"
              >
                <i class="bx bxs-edit"></i>
              </button>
              <button
                type="button"
                class="wi-icon-btn"
                @click="confirmResetAction"
                title="Reset Inspection Report"
              >
                <i class="bx bx-repost"></i>
              </button>
              <button
                type="button"
                class="wi-icon-btn wi-icon-btn--primary"
                @click="
                  edit.main_index = null;
                  edit.index = null;
                  triggerSaveWebInspectionData();
                "
                title="Save Details"
              >
                <i class="bx bx-save"></i>
              </button>
            </div>
          </div>

          <div class="wi-meta" v-if="vehicle_inspection_report !== null">
            <!-- Inspected By : START -->
            <div class="wi-meta-item">
              <label class="wi-label" for="inspected_by_id">Inspected By</label>
              <select
                id="inspected_by_id"
                class="custom-select wi-select"
                name="inspected_by"
                v-model="vehicle_inspection_report.inspected_by"
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
            </div>
            <!-- Inspected By : END -->
            <!-- Inspected DateTime : START -->
            <div class="wi-meta-item">
              <label class="wi-label" for="start_date">Inspection Date</label>
              <fieldset class="has-icon-left wi-date">
                <date-picker
                  placeholder="Inspection Date"
                  v-model="vehicle_inspection_report.inspected_datetime"
                  type="datetime"
                  :format="'MM/DD/YYYY HH:mm'"
                  :value-type="'MM/DD/YYYY HH:mm'"
                  :minute-step="5"
                  id="inspected_datetime"
                >
                </date-picker>
              </fieldset>
            </div>
            <!-- Inspected DateTime : END -->
            <div class="wi-meta-item">
              <label class="wi-label">Show Inspection Report On Website</label>
              <div class="custom-control custom-switch wi-switch">
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
          </div>
          <div class="wi-stamp" v-if="created_at !== null">
            Created At : {{ created_at }} Last Updated : {{ updated_at }}
          </div>
        </div>

        <!-- Sections -->
        <template v-if="vehicle_inspection_report !== null">
          <div
            class="wi-card wi-section"
            v-for="(section, main_index) in vehicle_inspection_report.sections"
            :key="section.name"
          >
            <div class="wi-section-head">
              <h6 class="wi-section-title">{{ section.name }}</h6>
              <div class="wi-legend">
                <span title="Passed"><i class="bx bxs-check-circle wi-c-pass"></i></span>
                <span title="Imperfections"><i class="bx bx-error-circle wi-c-warn"></i></span>
                <span title="Not Applicable"><i class="bx bxs-x-circle wi-c-na"></i></span>
              </div>
            </div>
            <div
              class="wi-item"
              :class="{ 'is-hidden': item.visibility != '1' }"
              v-for="(item, index) in section.list"
              :key="`section_` + main_index + `_` + index"
            >
              <span class="wi-num">{{ index + 1 }}</span>
              <div class="wi-item-body">
                <h6 class="wi-item-title">
                  <input
                    class="form-control wi-input"
                    placeholder="Title"
                    v-if="edit.main_index == main_index && edit.index == index"
                    v-model="vehicle_inspection_report['sections'][main_index]['list'][index]['title']"
                  />
                  <span v-else>{{ item.title }}</span>
                </h6>
                <div class="wi-item-desc">
                  <textarea
                    class="form-control wi-input wi-textarea"
                    placeholder="Additional Details..."
                    v-if="edit.main_index == main_index && edit.index == index"
                    v-model="vehicle_inspection_report['sections'][main_index]['list'][index]['description']"
                  ></textarea>
                  <span v-else>{{ item.description }}</span>
                </div>
              </div>
              <div class="wi-item-tools">
                <button
                  type="button"
                  class="wi-icon-btn wi-icon-btn--sm wi-icon-btn--primary"
                  v-if="edit.main_index == main_index && edit.index == index"
                  @click="
                    edit.main_index = null;
                    edit.index = null;
                    triggerSaveWebInspectionData();
                  "
                  title="Save Details"
                >
                  <i class="bx bx-save"></i>
                </button>
                <button
                  type="button"
                  class="wi-icon-btn wi-icon-btn--sm"
                  v-else
                  @click="
                    edit.main_index = main_index;
                    edit.index = index;
                  "
                  title="Add Details"
                >
                  <i class="bx bxs-pencil"></i>
                </button>
                <button
                  type="button"
                  class="wi-icon-btn wi-icon-btn--sm"
                  @click="
                    setVisibility(main_index, index);
                    triggerSaveWebInspectionData();
                  "
                  :title="[item.visibility == '1' ? 'Visible' : 'Hidden']"
                >
                  <i class="bx" :class="[item.visibility == '1' ? 'bxs-show' : 'bxs-hide']"></i>
                </button>
              </div>
              <div class="wi-status">
                <button
                  type="button"
                  class="wi-status-btn wi-status-btn--pass"
                  :class="{ 'is-active': item.value == 'Passed' }"
                  @click="
                    setInspectionPropertyValue(main_index, index, 'Passed');
                    triggerSaveWebInspectionData();
                  "
                  title="Passed"
                >
                  <i class="bx bxs-check-circle"></i>
                </button>
                <button
                  type="button"
                  class="wi-status-btn wi-status-btn--warn"
                  :class="{ 'is-active': item.value == 'Imperfections' }"
                  @click="
                    setInspectionPropertyValue(main_index, index, 'Imperfections');
                    triggerSaveWebInspectionData();
                  "
                  title="Imperfections"
                >
                  <i class="bx bx-error-circle"></i>
                </button>
                <button
                  type="button"
                  class="wi-status-btn wi-status-btn--na"
                  :class="{ 'is-active': item.value == 'Not Applicable' }"
                  @click="
                    setInspectionPropertyValue(main_index, index, 'Not Applicable');
                    triggerSaveWebInspectionData();
                  "
                  title="Not Applicable"
                >
                  <i class="bx bxs-x-circle"></i>
                </button>
              </div>
            </div>
          </div>
        </template>
      </form>
    </section>
  </div>
</template>
<script>
import Toast from "../../library/Toast";
import vSelect from "vue-select";

import DatePicker from "../Common/DatePickerAdapter.vue";
import "vue-datepicker-next/index.css";
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

  /* ---------- Redesign (layout + look only) ---------- */
  .wi-page { padding: 0 0 24px; color: #0f172a; }
  .wi-notice {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    margin: 0 0 14px;
    padding: 10px 14px;
    border: 1px solid #fecaca;
    border-radius: 10px;
    background: #fef2f2;
    color: #b91c1c;
    font-size: 13px;
    i { font-size: 17px; margin-top: 1px; }
    strong { font-weight: 500; }
  }
  .wi-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    margin-bottom: 16px;
  }
  .wi-report { padding: 16px 18px; }
  .wi-report-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
  }
  .wi-report-text { flex: 1 1 auto; min-width: 0; }
  .wi-report-title {
    margin: 0 0 4px;
    font-size: 17px;
    font-weight: 600;
    color: #0f172a;
  }
  .wi-report-desc {
    font-size: 13px;
    line-height: 1.55;
    color: #64748b;
    white-space: pre-line;
  }
  .wi-report-actions {
    display: flex;
    gap: 6px;
    flex-shrink: 0;
  }
  .wi-icon-btn {
    width: 36px;
    height: 36px;
    padding: 0;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #ffffff;
    color: #475569;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    cursor: pointer;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
    &:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; }
    &--primary {
      background: #2563eb;
      border-color: #2563eb;
      color: #ffffff;
      &:hover { background: #1d4ed8; border-color: #1d4ed8; color: #ffffff; }
    }
    &--sm { width: 30px; height: 30px; font-size: 16px; }
  }
  .wi-input {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
    color: #0f172a;
    box-shadow: none;
    &:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }
  }
  .wi-textarea { min-height: 72px; margin-top: 6px; }

  .wi-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    gap: 16px 24px;
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px solid #f1f5f9;
  }
  .wi-meta-item { display: flex; flex-direction: column; gap: 6px; }
  .wi-label {
    margin: 0;
    font-size: 12px;
    font-weight: 500;
    color: #475569;
    text-transform: none;
    letter-spacing: 0;
  }
  .wi-select {
    min-width: 220px;
    height: 38px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 13px;
  }
  .wi-date { position: relative; display: inline-block; margin: 0; }
  .wi-switch { min-height: 38px; display: flex; align-items: center; }
  .wi-stamp {
    margin-top: 12px;
    font-size: 12px;
    color: #94a3b8;
    text-align: right;
  }

  .wi-section { overflow: hidden; }
  .wi-section-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 18px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
  }
  .wi-section-title {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    color: #0f172a;
  }
  .wi-legend {
    display: flex;
    gap: 6px;
    width: 120px;
    justify-content: space-around;
    font-size: 18px;
  }
  .wi-c-pass { color: #16a34a; }
  .wi-c-warn { color: #d97706; }
  .wi-c-na { color: #64748b; }

  .wi-item {
    display: grid;
    grid-template-columns: 28px minmax(0, 1fr) auto 120px;
    align-items: center;
    gap: 14px;
    padding: 12px 18px;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.12s;
    &:last-child { border-bottom: 0; }
    &:hover { background: #fafbfd; }
    &.is-hidden .wi-item-body { opacity: 0.55; }
  }
  .wi-num {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #475569;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }
  .wi-item-title {
    margin: 0;
    font-size: 14px;
    font-weight: 500;
    color: #1e293b;
  }
  .wi-item-desc {
    margin-top: 2px;
    font-size: 12px;
    font-style: italic;
    color: #64748b;
  }
  .wi-item-tools { display: flex; gap: 4px; }
  .wi-status {
    display: flex;
    justify-content: space-around;
    gap: 4px;
  }
  .wi-status-btn {
    width: 34px;
    height: 34px;
    padding: 0;
    border: 0;
    border-radius: 50%;
    background: transparent;
    color: #cbd5e1;
    font-size: 22px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.15s, color 0.15s, transform 0.15s;
    &:hover { background: #f1f5f9; color: #94a3b8; }
    &.is-active { transform: scale(1.1); }
    &--pass.is-active { color: #16a34a; background: #dcfce7; }
    &--warn.is-active { color: #d97706; background: #fef3c7; }
    &--na.is-active { color: #475569; background: #e2e8f0; }
  }

  @media (max-width: 767px) {
    .wi-item { grid-template-columns: 28px minmax(0, 1fr); }
    .wi-item-tools, .wi-status { grid-column: 2; justify-content: flex-start; }
  }
}
</style>
