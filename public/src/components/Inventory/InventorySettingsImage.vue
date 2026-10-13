<template>
  <div
    role="tabpanel"
    class="tab-pane"
    id="image-setting"
    aria-labelledby="stacked-pill-1"
    aria-expanded="true"
  >
    <div class="content-wrapper m-0 border-bottom">
      <div class="row">
        <div class="col-md-5 col-12">
          <ul class="pl-0">
            <li class="d-inline-block mr-1">
              <div class="content-list-box">
                <p class="p-0 m-0">
                  <span class="blue-color text-bold-400 title-sizeincrease-font"
                    >Images</span
                  >
                </p>
              </div>
            </li>
          </ul>
        </div>
        <div class="col-md-7 col-12">
          <ul class="p-0 float-right d-flex">
            <li class="d-inline-block">
              <!-- Button trigger modal -->
              <button
                type="button"
                class="btn btn-outline-primary"
                data-toggle="modal"
                data-target="#imageModel"
              >
                + Upload Image
              </button>

              <!-- Modal -->
              <div
                class="modal fade"
                id="imageModel"
                tabindex="-1"
                role="dialog"
                aria-labelledby="imageModelLabel"
                aria-hidden="true"
              >
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">
                        Add New Image
                      </h5>
                      <button
                        type="button"
                        id="imageModalClose"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                      >
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form>
                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <div class="custom-file">
                              <input
                                type="file"
                                class="form-control custom-file-input"
                                @change="changeImage"
                                :class="{
                                  'is-invalid':
                                    errors.hasOwnProperty('image_url'),
                                }"
                                @keyup="delete errors.image_url"
                                :title="errors.image_url"
                                id="customFile"
                              />
                              <label class="custom-file-label" for="customFile"
                                >Choose file</label
                              >
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              v-model="form.type"
                              type="radio"
                              name="inlineRadioOptions"
                              id="inlineRadio1"
                              value="static"
                            />
                            <label class="form-check-label" for="inlineRadio1"
                              >Static Image</label
                            >
                          </div>
                          <div class="form-check form-check-inline">
                            <input
                              class="form-check-input"
                              v-model="form.type"
                              type="radio"
                              name="inlineRadioOptions"
                              id="inlineRadio2"
                              value="placeholder"
                            />
                            <label class="form-check-label" for="inlineRadio2"
                              >Placeholder Image</label
                            >
                          </div>
                          <div class="form-check d-none">
                            <input
                              class="form-check-input"
                              v-model="form.type"
                              type="radio"
                              name="inlineRadioOptions"
                              id="inlineRadio3"
                              value="overlay"
                            />
                            <label class="form-check-label" for="inlineRadio3"
                              >Overlay</label
                            >
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-3">
                            <label for="active">Active</label>
                            <div
                              class="custom-control custom-switch form-control"
                              style="border: 0"
                            >
                              <input
                                type="checkbox"
                                class="custom-control-input"
                                id="customSwitch0"
                                name="Visibility"
                                v-model="form.active"
                                :true-value="'1'"
                                :false-value="'0'"
                                checked
                              />
                              <label
                                class="custom-control-label"
                                for="customSwitch0"
                              >
                                <span class="switch-icon-left"> ON</span>
                                <span class="switch-icon-right">OFF</span>
                              </label>
                            </div>
                          </div>
                          <div
                            class="form-group col-md-3"
                            v-if="form.type == 'static'"
                          >
                            <label for="state">Index</label>
                            <input
                              type="number"
                              :class="{
                                'is-invalid': errors.hasOwnProperty('index'),
                              }"
                              @keyup="delete errors.index"
                              :title="errors.index"
                              v-model="form.index"
                              placeholder="2"
                              class="form-control"
                              id="index"
                            />
                          </div>
                        </div>
                      </form>
                    </div>
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
                        v-show="form.image_url"
                        class="btn btn-primary"
                        @click.prevent="saveImage"
                      >
                        Save
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div
      class="list-right-task-locations list-group ps p-0"
      style="overflow-y: auto !important"
    >
      <!-- Static Image Table Here -->
      <inventory-settings-image-table
        type="overlay"
      ></inventory-settings-image-table>
      <!-- END : Static Image Table Here -->

      <!-- Static Image Table Here -->
      <inventory-settings-image-table
        type="static"
      ></inventory-settings-image-table>
      <!-- END : Static Image Table Here -->

      <!-- Static Image Table Here -->
      <inventory-settings-image-table
        type="placeholder"
      ></inventory-settings-image-table>
      <!-- END : Static Image Table Here -->
    </div>
  </div>
</template>
<script>
import InventorySettingsImageTable from "./InventorySettingsImageTable";
import Toast from "../../library/Toast";

export default {
  name: "InventorySettingsImage",
  components: {
    InventorySettingsImageTable,
  },
  data() {
    return {
      form: {
        image_url: null,
        type: "static",
        index: "",
        active: "0",
      },
      errors: [],
      pager: {},

      page: 1,
    };
  },
  methods: {
    changeImage($event) {
      this.form.image_url = $event.target.files[0];
    },
    saveImage() {
      let message = "Uploading Image ...";
      const toast = new Toast(message);
      toast.fire();

      if (this.form.type === "static") {
        if (isNaN(this.form.index) || this.form.index.trim() == "") {
          toast.fail(`Index should be number.`);
          toast.close();

          return;
        }
      }

      const vm = this;
      let formData = new FormData();
      formData.append("file", this.form.image_url, this.form.image_url.name);
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && key != "image_url") {
          formData.append(key, this.form[key]);
        }
      }

      axios({
        method: "post",
        data: formData,
        url: window.location.origin + "/api/business/image/create",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();

          if (this.form.type == "static") {
            Event.fire("refresh.images.static", null);
          } else if (this.form.type == "overlay") {
            Event.fire("refresh.images.overlay", null);
          } else if (this.form.type == "placeholder") {
            Event.fire("refresh.images.placeholder", null);
          }

          (this.form.image_url = null),
            (this.form.type = "static"),
            (this.form.index = ""),
            (this.form.active = "0"),
            document.getElementById("imageModalClose").click();
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
    },
  },
};
</script>
