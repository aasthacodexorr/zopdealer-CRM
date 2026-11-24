<template>
  <div
    class="tab-pane"
    id="attachments-block-list"
    aria-labelledby="information-tab"
    role="tabpanel"
  >
    <!-- Attachments  form layout section start -->
    <section id="attachments " class="at-page">
      <div class="at-card">
        <div class="at-card-head border-line-bottom-title">
          <h4 class="at-title">Attachments</h4>
        </div>
        <form
          class="form form-horizontal webapp-form"
          id="vehicle-form"
        >
          <div class="form-body">
            <div class="at-list">
                <div class="at-row">
                  <div class="at-row-label">
                    <span class="at-icon at-icon--0"><i class="bx bx-history"></i></span>
                    <label>Vehicle History Report</label>
                  </div>
                  <div class="at-row-controls">
                    <div class="at-check">
                      <div class="form-check">
                        <div class="checkbox">
                          <input
                            type="checkbox"
                            class="checkbox-input"
                            id="vehicleHistoryReport"
                            v-model="vehicleHistoryReportFile"
                            :true-value="'1'"
                            :false-value="'0'"
                          />
                          <label
                            class="form-check-label"
                            for="vehicleHistoryReport"
                            style="cursor: pointer"
                          >
                            Check To Add File.
                          </label>
                        </div>
                      </div>
                    </div>
                    <div
                      class="at-input"
                      v-if="vehicleHistoryReportFile == '1'"
                    >
                      <div class="custom-file at-file">
                        <input
                          type="file"
                          class="custom-file-input"
                          @change="
                            saveFile(
                              $event,
                              'vehicleHistoryReport',
                              'Vehicle History Report',
                              'vehicleHistoryReportUrl'
                            )
                          "
                          id="vehicleHistoryReportFileInput"
                        />
                        <label
                          class="custom-file-label attach-file-label"
                          for="vehicleHistoryReportFileInput"
                          >Upload file</label
                        >
                      </div>
                    </div>
                    <div class="at-input at-url" v-else>
                      <input
                        type="text"
                        v-model="vehicleHistoryReportInput"
                        class="form-control"
                        placeholder="Vehicle History Report Url"
                      />
                      <button
                        @click.prevent="
                          saveUrl(
                            'vehicleHistoryReportInput',
                            'Vehicle History Report',
                            'vehicleHistoryReportUrl'
                          )
                        "
                        class="btn btn-primary at-btn at-btn-primary"
                      >
                        <i class="bx bxs-save"></i> Save
                      </button>
                    </div>
                    <div class="at-actions">
                      <a
                        v-if="vehicleHistoryReportUrl"
                        :href="vehicleHistoryReportUrl"
                        target="_blank"
                        class="btn btn-info at-btn at-btn-ghost"
                        rel="noopener noreferrer"
                        ><i class="bx bx-link-external"></i>View</a
                      >
                      <button
                        v-if="vehicleHistoryReportUrl"
                        @click.prevent="
                          deleteFile(
                            'Vehicle History Report',
                            'vehicleHistoryReportUrl'
                          )
                        "
                        class="btn btn-danger at-btn at-btn-danger"
                      >
                        <i class="bx bx-trash-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="at-row">
                  <div class="at-row-label">
                    <span class="at-icon at-icon--1"><i class="bx bx-wrench"></i></span>
                    <label>Vehicle Repair Report </label>
                  </div>
                  <div class="at-row-controls">
                    <div class="at-check">
                      <div class="form-check">
                        <div class="checkbox">
                          <input
                            type="checkbox"
                            class="checkbox-input"
                            id="vehicleRepairReportFile"
                            v-model="vehicleRepairReportFile"
                            :true-value="'1'"
                            :false-value="'0'"
                          />
                          <label
                            class="form-check-label"
                            for="vehicleRepairReportFile"
                            style="cursor: pointer"
                          >
                            Check To Add File.
                          </label>
                        </div>
                      </div>
                    </div>
                    <div
                      class="at-input"
                      v-if="vehicleRepairReportFile == '1'"
                    >
                      <div class="custom-file at-file">
                        <input
                          type="file"
                          class="custom-file-input"
                          @change="
                            saveFile(
                              $event,
                              'vehicleRepairReport',
                              'Vehicle Repair Report',
                              'vehicleRepairReportUrl'
                            )
                          "
                          id="vehicleRepairReportFileInput"
                        />
                        <label
                          class="custom-file-label attach-file-label"
                          for="vehicleRepairReportFileInput"
                          >Upload file</label
                        >
                      </div>
                    </div>
                    <div class="at-input at-url" v-else>
                      <input
                        type="text"
                        v-model="vehicleRepairReportInput"
                        class="form-control"
                        placeholder="Vehicle Repair Report Url"
                      />
                      <button
                        @click.prevent="
                          saveUrl(
                            'vehicleRepairReportInput',
                            'Vehicle Repair Report',
                            'vehicleRepairReportUrl'
                          )
                        "
                        class="btn btn-primary at-btn at-btn-primary"
                      >
                        <i class="bx bxs-save"></i> Save
                      </button>
                    </div>
                    <div class="at-actions">
                      <a
                        v-if="vehicleRepairReportUrl"
                        :href="vehicleRepairReportUrl"
                        target="_blank"
                        class="btn btn-info at-btn at-btn-ghost"
                        rel="noopener noreferrer"
                        ><i class="bx bx-link-external"></i>View</a
                      >
                      <button
                        v-if="vehicleRepairReportUrl"
                        @click.prevent="
                          deleteFile(
                            'Vehicle Repair Report',
                            'vehicleRepairReportUrl'
                          )
                        "
                        class="btn btn-danger at-btn at-btn-danger"
                      >
                        <i class="bx bx-trash-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="at-row">
                  <div class="at-row-label">
                    <span class="at-icon at-icon--2"><i class="bx bx-receipt"></i></span>
                    <label>Window Sticker</label>
                  </div>
                  <div class="at-row-controls">
                    <div class="at-check">
                      <div class="form-check">
                        <div class="checkbox">
                          <input
                            type="checkbox"
                            class="checkbox-input"
                            id="windowStickerFile"
                            v-model="windowStickerFile"
                            :true-value="'1'"
                            :false-value="'0'"
                          />
                          <label
                            class="form-check-label"
                            for="windowStickerFile"
                            style="cursor: pointer"
                          >
                            Check To Add File.
                          </label>
                        </div>
                      </div>
                    </div>
                    <div
                      class="at-input"
                      v-if="windowStickerFile == '1'"
                    >
                      <div class="custom-file at-file">
                        <input
                          type="file"
                          class="custom-file-input"
                          @change="
                            saveFile(
                              $event,
                              'windowSticker',
                              'Window Sticker',
                              'windowStickerUrl'
                            )
                          "
                          id="windowStickerFileInput"
                        />
                        <label
                          class="custom-file-label attach-file-label"
                          for="windowStickerFileInput"
                          >Upload file</label
                        >
                      </div>
                    </div>
                    <div class="at-input at-url" v-else>
                      <input
                        type="text"
                        v-model="windowStickerInput"
                        class="form-control"
                        placeholder="Window Sticker Url"
                      />
                      <button
                        @click.prevent="
                          saveUrl(
                            'windowStickerInput',
                            'Window Sticker',
                            'windowStickerUrl'
                          )
                        "
                        class="btn btn-primary at-btn at-btn-primary"
                      >
                        <i class="bx bxs-save"></i> Save
                      </button>
                    </div>
                    <div class="at-actions">
                      <a
                        v-if="windowStickerUrl"
                        :href="windowStickerUrl"
                        target="_blank"
                        class="btn btn-info at-btn at-btn-ghost"
                        rel="noopener noreferrer"
                        ><i class="bx bx-link-external"></i>View</a
                      >
                      <button
                        v-if="windowStickerUrl"
                        @click.prevent="
                          deleteFile(
                            'Window Sticker',
                            'windowStickerUrl'
                          )
                        "
                        class="btn btn-danger at-btn at-btn-danger"
                      >
                        <i class="bx bx-trash-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="at-row">
                  <div class="at-row-label">
                    <span class="at-icon at-icon--3"><i class="bx bx-file"></i></span>
                    <label>Other</label>
                  </div>
                  <div class="at-row-controls">
                    <div class="at-check">
                      <div class="form-check">
                        <div class="checkbox">
                          <input
                            type="checkbox"
                            class="checkbox-input"
                            id="otherReport"
                            v-model="otherReportFile"
                            :true-value="'1'"
                            :false-value="'0'"
                          />
                          <label
                            class="form-check-label"
                            for="otherReport"
                            style="cursor: pointer"
                          >
                            Check To Add File.
                          </label>
                        </div>
                      </div>
                    </div>
                    <div
                      class="at-input"
                      v-if="otherReportFile == '1'"
                    >
                      <div class="custom-file at-file">
                        <input
                          type="file"
                          class="custom-file-input"
                          @change="
                            saveFile(
                              $event,
                              'otherReport',
                              'Other Report',
                              'otherReportUrl'
                            )
                          "
                          id="otherReportFileInput"
                        />
                        <label
                          class="custom-file-label attach-file-label"
                          for="otherReportFileInput"
                          >Upload file</label
                        >
                      </div>
                    </div>
                    <div class="at-input at-url" v-else>
                      <input
                        type="text"
                        v-model="otherReportInput"
                        class="form-control"
                        placeholder="Other File Url"
                      />
                      <button
                        @click.prevent="
                          saveUrl(
                            'otherReportInput',
                            'Other Report',
                            'otherReportUrl'
                          )
                        "
                        class="btn btn-primary at-btn at-btn-primary"
                      >
                        <i class="bx bxs-save"></i> Save
                      </button>
                    </div>
                    <div class="at-actions">
                      <a
                        v-if="otherReportUrl"
                        :href="otherReportUrl"
                        target="_blank"
                        class="btn btn-info at-btn at-btn-ghost"
                        rel="noopener noreferrer"
                        ><i class="bx bx-link-external"></i>View</a
                      >
                      <button
                        v-if="otherReportUrl"
                        @click.prevent="
                          deleteFile(
                            'Other Report',
                            'otherReportUrl'
                          )
                        "
                        class="btn btn-danger at-btn at-btn-danger"
                      >
                        <i class="bx bx-trash-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <!-- Video -->
                <div class="at-row">
                  <div class="at-row-label">
                    <span class="at-icon at-icon--4"><i class="bx bx-video"></i></span>
                    <label>Video URL</label>
                  </div>
                  <div class="at-row-controls">
                    <div class="at-check" style="visibility: hidden">
                      <div class="form-check">
                        <div class="checkbox">
                          <input
                            type="checkbox"
                            class="checkbox-input"
                            id="Video"
                            v-model="VideoFile"
                            :true-value="'1'"
                            :false-value="'0'"
                          />
                          <label
                            class="form-check-label"
                            for="Video"
                            style="cursor: pointer"
                          >
                            Check To Add File.
                          </label>
                        </div>
                      </div>
                    </div>
                    <div
                      class="at-input" style="visibility: hidden"
                      v-if="VideoFile == '1'"
                    >
                      <div class="custom-file at-file">
                        <input
                          type="file"
                          class="custom-file-input"
                          @change="
                            saveFile(
                              $event,
                              'Video',
                              'Video Url',
                              'VideoUrl'
                            )
                          "
                          id="VideoFileInput"
                        />
                        <label
                          class="custom-file-label attach-file-label"
                          for="VideoFileInput"
                          >Upload file</label
                        >
                      </div>
                    </div>
                    <div class="at-input at-url" v-else>
                      <input
                        type="text"
                        v-model="VideoInput"
                        class="form-control"
                        placeholder="Video Url"
                      />
                      <button
                        @click.prevent="
                          saveUrl(
                            'VideoInput',
                            'Video Url',
                            'VideoUrl'
                          )
                        "
                        class="btn btn-primary at-btn at-btn-primary"
                      >
                        <i class="bx bxs-save"></i> Save
                      </button>
                    </div>
                    <div class="at-actions">
                      <a
                        v-if="VideoUrl"
                        :href="VideoUrl"
                        target="_blank"
                        class="btn btn-info at-btn at-btn-ghost"
                        rel="noopener noreferrer"
                        ><i class="bx bx-link-external"></i>View</a
                      >
                      <button
                        v-if="VideoUrl"
                        @click.prevent="
                          deleteFile(
                            'Video Url',
                            'VideoUrl'
                          )
                        "
                        class="btn btn-danger at-btn at-btn-danger"
                      >
                        <i class="bx bx-trash-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <!-- Video -->
            </div>
          </div>
        </form>
      </div>
      <div class="at-carfax">
        <carfax-order
          :app_metadata="app_metadata"
          :stock_no="stock_no"
          :vin="vin"
        ></carfax-order>
      </div>
    </section>
    <!-- // Attachments  form layout section end -->
  </div>
</template>
<script>
import Toast from "../../library/Toast";
import CarfaxOrder from "../WebComponents/CarfaxOrder.vue";
export default {
  props: {
    vin: {
      type: String,
      required: false,
      default: "",
    },
    stock_no: {
      type: String,
      required: false,
      default: "",
    },
    app_metadata: { required: true },
  },
  components: { CarfaxOrder },
  name: "InventoryManageAttachment",
  data() {
    return {
      vehicleHistoryReport: null,
      vehicleHistoryReportInput: null,
      vehicleHistoryReportFile: "0",
      vehicleHistoryReportUrl: null,

      vehicleRepairReport: null,
      vehicleRepairReportInput: null,
      vehicleRepairReportFile: "0",
      vehicleRepairReportUrl: null,

      windowSticker: null,
      windowStickerFile: "0",
      windowStickerInput: null,
      windowStickerUrl: null,

      otherReport: null,
      otherReportFile: "0",
      otherReportInput: null,
      otherReportUrl: null,

      Video: null,
      VideoFile: "0",
      VideoInput: null,
      VideoUrl: null,
    };
  },
  methods: {
    async saveFile(event, data, header, file_url) {
      const vm = this;
      let formData = new FormData();
      this.$data[data] = event.target.files[0];
      formData.append("file", this.$data[data], this.$data[data].name);
      formData.append("header", header);

      let message = "Uploading File ...";
      const toast = new Toast(message);
      toast.fire();

      if (this.$route.params.id && this.$route.name == "inventory-update") {
        await axios({
          method: "post",
          data: formData,
          url:
            window.location.origin +
            "/api/business/inventory/file/upload/" +
            this.$route.params.id,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
            "Content-Type": "multipart/form-data",
          },
        })
          .then(({ data }) => {
            vm.$data[file_url] = this.$helpers.parseAbsoluteOrRelativeUrl(
              window.global.cdn,
              data.file_url
            );
            let success_message = "Finished Uploading File.";

            toast.success(success_message);
            toast.close();
          })
          .catch((error) => {
            let returnObj = error.response.data;
            for (const key in returnObj) {
              if (returnObj.hasOwnProperty(key)) {
                const element = returnObj[key];

                let error_message = element + ` Only PDF file can be uploaded.`;
                toast.fail(error_message);
              }
            }

            toast.close();
          });
      }

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "inventory",
        "attachment",
        "save"
      );
    },
    async saveUrl(input, header, file_url) {
      let formData = new FormData();
      formData.append("file_url", this.$data[input]);
      formData.append("header", header);
      const vm = this;

      let message = "Saving File Url";
      const toast = new Toast(message);
      toast.fire();
      if (this.$route.params.id && this.$route.name == "inventory-update") {
        await axios({
          method: "post",
          data: formData,
          url:
            window.location.origin +
            "/api/business/inventory/file/upload/" +
            this.$route.params.id,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
            "Content-Type": "multipart/form-data",
          },
        })
          .then(({ data }) => {
            vm.$data[file_url] = data.file_url;
            let success_message = "Finished Saving Url.";

            toast.success(success_message);
            toast.close();
          })
          .catch((error) => {
            let returnObj = error.response.data;
            for (const key in returnObj) {
              if (returnObj.hasOwnProperty(key)) {
                const element = returnObj[key];
                toast.fail(element);
              }
            }

            toast.close();
          });
      }

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "inventory",
        "attachment",
        "save"
      );
    },
    async deleteFile(header, input) {
      let vm = this;
      let formData = new FormData();
      formData.append("header", header);

      let message = "Deleting File";
      const toast = new Toast(message);
      toast.fire();

      if (this.$route.params.id && this.$route.name == "inventory-update") {
        await axios({
          method: "post",
          url:
            window.location.origin +
            "/api/business/inventory/file/delete/" +
            this.$route.params.id,
          data: formData,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
        })
          .then(({ data }) => {
            let success_message = "Attachment deleted successfully.";
            toast.success(success_message);
            toast.close();

            this[input] = null;
            this.retriveAllFiles();
          })
          .catch((error) => {
            let error_message =
              "There were some errors in deleting the attachment.";

            toast.fail(error_message);
            toast.close();
          });
      }

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "inventory",
        "attachment",
        "delete"
      );
    },
    retriveAllFiles() {
      const vm = this;
      if (this.$route.params.id && this.$route.name == "inventory-update") {
        axios({
          method: "get",
          url:
            window.location.origin +
            "/api/business/inventory/file/" +
            this.$route.params.id,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
        }).then(({ data }) => {
          data.files.forEach((element) => {
            if (element.header == "Vehicle History Report") {
              vm.$data.vehicleHistoryReportUrl =
                this.$helpers.parseAbsoluteOrRelativeUrl(
                  window.global.cdn,
                  element.file_url
                );
            } else if (element.header == "Vehicle Repair Report") {
              vm.$data.vehicleRepairReportUrl =
                this.$helpers.parseAbsoluteOrRelativeUrl(
                  window.global.cdn,
                  element.file_url
                );
            } else if (element.header == "Window Sticker") {
              vm.$data.windowStickerUrl =
                this.$helpers.parseAbsoluteOrRelativeUrl(
                  window.global.cdn,
                  element.file_url
                );
            } else if (element.header == "Other Report") {
              vm.$data.otherReportUrl =
                this.$helpers.parseAbsoluteOrRelativeUrl(
                  window.global.cdn,
                  element.file_url
                );
            } else if (element.header == "Video Url") {
              vm.$data.VideoUrl = this.$helpers.parseAbsoluteOrRelativeUrl(
                window.global.cdn,
                element.file_url
              );
            }
          });
        });
      }
    },
  },
  created() {
    this.retriveAllFiles();
  },
  mounted() {
    const vm = this;
    Event.listen("vehicle_history_report_update", (data) => {
      vm.vehicleHistoryReportInput = data;
      vm.saveUrl(
        "vehicleHistoryReportInput",
        "Vehicle History Report",
        "vehicleHistoryReportUrl"
      );
    });
  },
};
</script>
<style lang="scss">
/* ==========================================================================
   Inventory — Attachments tab. Scoped to #attachments-block-list.
   ========================================================================== */
#attachments-block-list {
  .at-page {
    padding: 8px 20px 96px;
    color: #0f172a;
  }

  .at-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    margin-bottom: 16px;
    overflow: visible;
  }
  .at-card-head {
    padding: 14px 18px;
    border-bottom: 1px solid #e2e8f0;
  }
  .at-title {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #0f172a;
  }

  .at-list {
    display: flex;
    flex-direction: column;
  }
  .at-row {
    display: grid;
    grid-template-columns: minmax(200px, 260px) 1fr;
    align-items: center;
    gap: 16px;
    padding: 14px 18px;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s;
    &:last-child { border-bottom: 0; }
    &:hover { background: #f8fafc; }
  }
  .at-row-label {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
    label {
      margin: 0;
      font-size: 14px;
      font-weight: 500;
      color: #1e293b;
      text-transform: none;
      letter-spacing: 0;
    }
  }
  .at-icon {
    width: 36px;
    height: 36px;
    flex-shrink: 0;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    &--0 { background: #eff6ff; color: #2563eb; }
    &--1 { background: #fff7ed; color: #ea580c; }
    &--2 { background: #ecfdf5; color: #059669; }
    &--3 { background: #f1f5f9; color: #475569; }
    &--4 { background: #f5f3ff; color: #7c3aed; }
  }

  .at-row-controls {
    display: grid;
    grid-template-columns: 170px minmax(0, 1fr) 132px;
    align-items: center;
    gap: 12px;
  }
  .at-check {
    .form-check { padding-left: 0; }
    .checkbox label {
      font-size: 13px;
      color: #475569;
      text-transform: none;
      letter-spacing: 0;
    }
  }

  .at-input { min-width: 0; }
  .at-url {
    display: flex;
    gap: 8px;
    .form-control {
      flex: 1 1 auto;
      min-width: 0;
      height: 36px;
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
  }
  .at-file {
    height: 36px;
    .custom-file-input { height: 36px; cursor: pointer; }
    .custom-file-label {
      height: 36px;
      line-height: 34px;
      padding: 0 12px;
      border: 1px dashed #cbd5e1;
      border-radius: 8px;
      background: #f8fafc;
      color: #475569;
      font-size: 13px;
      text-transform: none;
      letter-spacing: 0;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      box-shadow: none;
      &::after {
        height: 34px;
        line-height: 34px;
        padding: 0 12px;
        border-left: 1px solid #e2e8f0;
        border-radius: 0 8px 8px 0;
        background: #ffffff;
        color: #2563eb;
        font-weight: 500;
      }
    }
    &:hover .custom-file-label { border-color: #2563eb; background: #eff6ff; }
  }

  .at-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
    min-width: 110px;
  }
  .at-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    height: 36px;
    padding: 0 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    line-height: 1;
    white-space: nowrap;
    box-shadow: none !important;
    i { font-size: 16px; position: static; top: 0; line-height: 1; }
  }
  .at-btn-primary {
    background: #2563eb !important;
    border: 1px solid #2563eb !important;
    color: #ffffff !important;
    &:hover { background: #1d4ed8 !important; }
  }
  .at-btn-ghost {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    color: #2563eb !important;
    &:hover { background: #eff6ff !important; border-color: #bfdbfe !important; }
  }
  .at-btn-danger {
    width: 36px;
    padding: 0;
    background: #ffffff !important;
    border: 1px solid #fecaca !important;
    color: #dc2626 !important;
    &:hover { background: #fef2f2 !important; }
  }

  .at-carfax { margin-top: 4px; }

  @media (max-width: 1199px) {
    .at-row { grid-template-columns: 1fr; gap: 10px; }
  }
  @media (max-width: 767px) {
    .at-row-controls { grid-template-columns: 1fr; }
    .at-actions { justify-content: flex-start; }
  }
}
</style>

