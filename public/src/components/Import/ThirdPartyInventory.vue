<template>
  <div>
    <div
      class="app-content content fixed-height-block"
      style="overflow: visible"
    >
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <a
              class="nav-link d-flex align-items-center active"
              id="third-party-inventory-import-tab"
              data-toggle="tab"
              href="#third-party-inventory-import-panel"
              aria-controls="Inventory Import"
              role="tab"
              aria-selected="true"
            >
              <span class="">Import</span>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="">
        <div class="content-body mb-3">
          <div class="tab-content">
            <div
              class="tab-pane active"
              id="third-party-inventory-import-panel"
              aria-labelledby="Inventory Import Tab"
              role="tabpanel"
            >
              <!-- Section : START -->
              <section id="third-partyinventory-import-in-section">
                <div class="row match-height">
                  <div class="col-12 col-xs-12">
                    <div class="card">
                      <div class="card-content">
                        <div class="card-body pb-1 mx-25">
                          <div class="m-width-s">
                            <div class="card-header border-line-bottom-title">
                              <h5 class="card-title">
                                Third Party Bulk CSV Import Section
                              </h5>
                            </div>
                            <div class="row">
                              <!-- third party source : START -->
                              <div class="col-xs-12 col-md-4">
                                <div class="form-group">
                                  <select
                                    id="third_party_source"
                                    class="custom-select"
                                    v-model="third_party_source"
                                    name="third_party_source"
                                  >
                                    <option value="" selected>
                                      Select CSV Source
                                    </option>
                                    <option value="openlane">Openlane</option>
                                    <option value="otolane">Otolane</option>
                                  </select>
                                </div>
                              </div>
                              <!-- third party source : END -->
                              <!-- file : START -->
                              <div
                                class="col-xs-12 col-md-4"
                                v-show="third_party_source !== ``"
                              >
                                <div class="form-group">
                                  <!-- <label class="" for="file"
                                    >File</label
                                  > -->
                                  <div class="">
                                    <input
                                      ref="fileInput"
                                      type="file"
                                      @change="onFileChanged($event)"
                                      class="form-control"
                                      id="file"
                                    />
                                  </div>
                                </div>
                              </div>
                              <!-- file : END-->
                              <div
                                class="col-md-4 col-xs-12"
                                v-show="
                                  third_party_source !== `` &&
                                  selectedFile !== null
                                "
                              >
                                <button
                                  :disabled="submit"
                                  class="btn btn-primary"
                                  @click="onUpload"
                                  type="button"
                                >
                                  Upload
                                </button>
                              </div>

                              <div class="col-md-12 col-xs-12 text-danger">
                                NOTE: Please contact customer support to add new
                                CSV sources.
                              </div>
                            </div>
                          </div>

                          <!-- RESULTS : START -->
                          <div class="row">
                            <div class="col-12">
                              <table class="table">
                                <thead>
                                  <tr>
                                    <th>VIN</th>
                                    <th>Messages</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr
                                    :class="classcss"
                                    v-for="(message, index) in messages"
                                    :key="index"
                                  >
                                    <td>{{ index }}</td>
                                    <td>
                                      <span
                                        v-for="single_message in message"
                                        :key="single_message + index"
                                      >
                                        {{ single_message }}
                                      </span>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <!-- RESULTS : END -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>

              <!-- Section : END -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Toast from "../../library/Toast";

import { mapGetters, mapActions } from "vuex";
export default {
  props: ["application", "app_metadata"],
  name: "ThirdPartyInventory",
  data() {
    return {
      selectedFile: null,
      messages: [],
      classcss: "",
      third_party_source: "",
      submit: false,
    };
  },
  methods: {
    ...mapActions("inventoryView", [
      "commitOnlyEntities",
    ]),
    onFileChanged(event) {
      this.selectedFile = event.target.files[0];
    },
    onUpload() {
      const vm = this;
      this.submit = true;
      let formData = new FormData();
      formData.append("file", this.selectedFile, this.selectedFile.name);

      let message = "Uploading File ...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "post",
        data: formData,
        url:
          window.location.origin +
          "/api/business/import/inventory/third-party/" +
          this.third_party_source,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          let success_message = "Finished Uploading File.";
          this.messages = data;
          this.classcss = "table-success";
          toast.success(success_message);
          toast.close();
          this.submit = false;

          this.commitOnlyEntities();
        })
        .catch((error) => {
          let data = error.response.data;
          this.messages = data;
          this.classcss = "table-danger";
          let error_message = `There were errors in uploading the file.`;
          toast.fail(error_message);
          toast.close();

          this.submit = false;
        });
    },
  },
};
</script>
