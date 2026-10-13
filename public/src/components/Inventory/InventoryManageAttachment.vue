<template>
  <div
    class="tab-pane"
    id="attachments-block-list"
    aria-labelledby="information-tab"
    role="tabpanel"
  >
    <!-- Attachments  form layout section start -->
    <section id="attachments ">
      <div class="row">
        <div class="col-12">
          <div class="card shadow-none mt-1 mb-0">
            <div class="card-header border-line-bottom-title">
              <h4 class="card-title">Attachments</h4>
            </div>
            <div class="card-content">
              <div class="card-body pt-0 pb-0">
                <form
                  class="form form-horizontal webapp-form"
                  id="vehicle-form"
                >
                  <div class="form-body">
                    <div class="mb-0 pb-0 border-0">
                      <div class="row">
                        <div class="col-md-3">
                          <label>Vehicle History Report</label>
                        </div>
                        <div class="col-md-9 row pb-1">
                          <div class="col-3">
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
                            class="col-5"
                            v-if="vehicleHistoryReportFile == '1'"
                          >
                            <div class="custom-file">
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
                          <div class="col-5" v-else>
                            <input
                              type="text"
                              style="width: 70%; display: inline-block"
                              v-model="vehicleHistoryReportInput"
                              class="form-control"
                              placeholder="Vehicle History Report Url"
                            />
                            <button
                              style="padding-top: 0.3em"
                              @click.prevent="
                                saveUrl(
                                  'vehicleHistoryReportInput',
                                  'Vehicle History Report',
                                  'vehicleHistoryReportUrl'
                                )
                              "
                              class="btn btn-primary"
                            >
                              <i class="bx bxs-save"></i> Save
                            </button>
                          </div>
                          <div class="col-2 p-0" v-if="vehicleHistoryReportUrl">
                            <a
                              :href="vehicleHistoryReportUrl"
                              target="_blank"
                              class="btn btn-info"
                              rel="noopener noreferrer"
                              ><i class="bx bx-link-external pr-50"></i>View</a
                            >
                          </div>
                          <div class="col-2 p-0" v-if="vehicleHistoryReportUrl">
                            <button
                              @click.prevent="
                                deleteFile(
                                  'Vehicle History Report',
                                  'vehicleHistoryReportUrl'
                                )
                              "
                              class="btn btn-danger"
                            >
                              <i class="bx bx-trash-alt"></i>
                            </button>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <label>Vehicle Repair Report </label>
                        </div>
                        <div class="col-md-9 row pb-1">
                          <div class="col-3">
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
                            class="col-5"
                            v-if="vehicleRepairReportFile == '1'"
                          >
                            <div class="custom-file">
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
                          <div class="col-5" v-else>
                            <input
                              type="text"
                              style="width: 70%; display: inline-block"
                              v-model="vehicleRepairReportInput"
                              class="form-control"
                              placeholder="Vehicle Repair Report Url"
                            />
                            <button
                              style="padding-top: 0.3em"
                              @click.prevent="
                                saveUrl(
                                  'vehicleRepairReportInput',
                                  'Vehicle Repair Report',
                                  'vehicleRepairReportUrl'
                                )
                              "
                              class="btn btn-primary"
                            >
                              <i class="bx bxs-save"></i> Save
                            </button>
                          </div>
                          <div class="col-2 p-0" v-if="vehicleRepairReportUrl">
                            <a
                              :href="vehicleRepairReportUrl"
                              target="_blank"
                              class="btn btn-info"
                              rel="noopener noreferrer"
                              ><i class="bx bx-link-external pr-50"></i>View</a
                            >
                          </div>
                          <div class="col-2 p-0" v-if="vehicleRepairReportUrl">
                            <button
                              @click.prevent="
                                deleteFile(
                                  'Vehicle Repair Report',
                                  'vehicleRepairReportUrl'
                                )
                              "
                              class="btn btn-danger"
                            >
                              <i class="bx bx-trash-alt"></i>
                            </button>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <label>Window Sticker</label>
                        </div>
                        <div class="col-md-9 row pb-1">
                          <div class="col-3">
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
                          <div class="col-5" v-if="windowStickerFile == '1'">
                            <div class="custom-file">
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
                          <div class="col-5" v-else>
                            <input
                              type="text"
                              style="width: 70%; display: inline-block"
                              v-model="windowStickerInput"
                              class="form-control"
                              placeholder="Window Sticker Url"
                            />
                            <button
                              style="padding-top: 0.3em"
                              @click.prevent="
                                saveUrl(
                                  'windowStickerInput',
                                  'Window Sticker',
                                  'windowStickerUrl'
                                )
                              "
                              class="btn btn-primary"
                            >
                              <i class="bx bxs-save"></i> Save
                            </button>
                          </div>
                          <div class="col-2 p-0" v-if="windowStickerUrl">
                            <a
                              :href="windowStickerUrl"
                              target="_blank"
                              class="btn btn-info"
                              rel="noopener noreferrer"
                              ><i class="bx bx-link-external pr-50"></i>View</a
                            >
                          </div>
                          <div class="col-2 p-0" v-if="windowStickerUrl">
                            <button
                              @click.prevent="
                                deleteFile('Window Sticker', 'windowStickerUrl')
                              "
                              class="btn btn-danger"
                            >
                              <i class="bx bx-trash-alt"></i>
                            </button>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <label>Other</label>
                        </div>
                        <div class="col-md-9 row pb-1">
                          <div class="col-3">
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
                          <div class="col-5" v-if="otherReportFile == '1'">
                            <div class="custom-file">
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
                          <div class="col-5" v-else>
                            <input
                              type="text"
                              style="width: 70%; display: inline-block"
                              v-model="otherReportInput"
                              class="form-control"
                              placeholder="Other File Url"
                            />
                            <button
                              style="padding-top: 0.3em"
                              @click.prevent="
                                saveUrl(
                                  'otherReportInput',
                                  'Other Report',
                                  'otherReportUrl'
                                )
                              "
                              class="btn btn-primary"
                            >
                              <i class="bx bxs-save"></i> Save
                            </button>
                          </div>
                          <div class="col-2 p-0" v-if="otherReportUrl">
                            <a
                              :href="otherReportUrl"
                              target="_blank"
                              class="btn btn-info"
                              rel="noopener noreferrer"
                              ><i class="bx bx-link-external pr-50"></i>View</a
                            >
                          </div>
                          <div class="col-2 p-0" v-if="otherReportUrl">
                            <button
                              class="btn btn-danger"
                              @click.prevent="
                                deleteFile('Other Report', 'otherReportUrl')
                              "
                            >
                              <i class="bx bx-trash-alt"></i>
                            </button>
                          </div>
                        </div>
                        <!-- Video -->
                        <div class="col-md-3">
                          <label>Video URL</label>
                        </div>
                        <div class="col-md-9 row pb-1">
                          <div class="col-3" style="visibility: hidden">
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
                            class="col-5"
                            style="visibility: hidden"
                            v-if="VideoFile == '1'"
                          >
                            <div class="custom-file">
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
                          <div class="col-5" v-else>
                            <input
                              type="text"
                              style="width: 70%; display: inline-block"
                              v-model="VideoInput"
                              class="form-control"
                              placeholder="Video Url"
                            />
                            <button
                              style="padding-top: 0.3em"
                              @click.prevent="
                                saveUrl('VideoInput', 'Video Url', 'VideoUrl')
                              "
                              class="btn btn-primary"
                            >
                              <i class="bx bxs-save"></i> Save
                            </button>
                          </div>
                          <div class="col-2 p-0" v-if="VideoUrl">
                            <a
                              :href="VideoUrl"
                              target="_blank"
                              class="btn btn-info"
                              rel="noopener noreferrer"
                              ><i class="bx bx-link-external pr-50"></i>View</a
                            >
                          </div>
                          <div class="col-2 p-0" v-if="VideoUrl">
                            <button
                              class="btn btn-danger"
                              @click.prevent="
                                deleteFile('Video Url', 'VideoUrl')
                              "
                            >
                              <i class="bx bx-trash-alt"></i>
                            </button>
                          </div>
                        </div>
                        <!-- Video -->
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <carfax-order
            :app_metadata="app_metadata"
            :stock_no="stock_no"
            :vin="vin"
          ></carfax-order>
        </div>
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
