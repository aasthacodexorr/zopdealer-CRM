<template>
  <div class="app-content content" v-show="isAuthorized">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="col-xs-12 col-md-4">
          <div class="card">
            <div
              class="card-header d-flex justify-content-between align-items-center pb-50"
            >
              <h4 class="card-title">Bulk Title / Description Change</h4>
            </div>
            <div class="card-content pl-2">
              <!-- Status : START -->
              <div class="row">
                <div class="col-sm-12">
                  <label for="">Filter Inventory Status</label>
                  <select
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('status'),
                    }"
                    @keyup="delete errors.status"
                    :title="errors.status"
                    v-model="form.status"
                  >
                    <option value="" disabled>-- Select Status --</option>
                    <option
                      :value="status"
                      v-for="status in getStatuses"
                      :key="status"
                    >
                      {{ status }}
                    </option>
                  </select>
                </div>
              </div>
              <!-- Status : END -->

              <!-- Year : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <label for="">Filter Inventory Year</label>
                  <v-select
                    id="filter-year"
                    placeholder="Year"
                    v-model="form.year"
                    clearable
                    :options="getYears"
                  >
                  </v-select>
                </div>
              </div>
              <!-- Year : END -->

              <!-- Make : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <label for="">Filter Inventory Make</label>
                  <v-select
                    id="filter-make"
                    placeholder="Make"
                    v-model="form.make"
                    clearable
                    :options="getMakes"
                  >
                  </v-select>
                </div>
              </div>
              <!-- Make : END -->

              <!-- Model : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <label for="">Filter Inventory Model</label>
                  <v-select
                    id="filter-model"
                    placeholder="Model"
                    v-model="form.model"
                    clearable
                    :options="getModels"
                  >
                  </v-select>
                </div>
              </div>
              <!-- Model : END -->

              <div class="row form-group">
                <div class="col-sm-12">
                  <div class="checkbox">
                    <input
                      type="checkbox"
                      class="checkbox-input"
                      id="change_default_veh_description"
                      v-model="form.change_default"
                      :true-value="'1'"
                      :false-value="'0'"
                    />
                    <label for="change_default_veh_description"
                      >Update Vehicle Default Description For New
                      Vehicles</label
                    >
                  </div>
                </div>
              </div>

              <!-- Button : START -->
              <div class="form-group row">
                <div class="col-sm-12">
                  <button
                    @click.prevent="warningForBulkDescriptionChanges"
                    class="btn btn-success"
                  >
                    Submit
                  </button>
                </div>
              </div>
              <!-- Button : END-->
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-md-8" style="min-height: 75vh">
          <div class="row">
            <h4 class="col-xs-12 col-md-12">Title</h4>
            <div class="col-xs-12 col-md-12">
              <text-editor
                name="description"
                :elementId="'text-editor-settings-1'"
                v-model="form.title"
              >
              </text-editor>
            </div>
          </div>
          <div class="row">
            <h4 class="col-xs-12 col-md-12 mt-1">Vehicle Description</h4>
            <div class="col-xs-12 col-md-12">
              <text-editor
                name="description"
                :elementId="'text-editor-1'"
                ref="myTextEditor"
                :options="editorOption"
                v-model="form.vehicle_description"
                :editor_style="'min-height:10em;'"
              >
              </text-editor>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 text-right p-1">
              <inventory-vehicle-description-re-writer-c-t-a />
              <inventory-vehicle-description-template-c-t-a />
            </div>
          </div>
        </div>
      </div>
    </div>
    <inventory-vehicle-description-re-writer
      :vehicle_description="form.vehicle_description"
    ></inventory-vehicle-description-re-writer>
    <inventory-vehicle-description-template></inventory-vehicle-description-template>
  </div>
</template>
<script>
import Toast from "../../library/Toast";
import { mapGetters, mapActions } from "vuex";
import vSelect from "vue-select";
import TextEditor from "../WebComponents/TextEditor";
import InventoryVehicleDescriptionTemplateCTA from "./InventoryVehicleDescriptionTemplateCTA.vue";
import InventoryVehicleDescriptionTemplate from "./InventoryVehicleDescriptionTemplate.vue";
import InventoryVehicleDescriptionReWriter from "./InventoryVehicleDescriptionReWriter.vue";
import InventoryVehicleDescriptionReWriterCTA from "./InventoryVehicleDescriptionReWriterCTA.vue";

export default {
  props: ["application", "app_metadata"],
  name: "InventoryBulkDescription",
  components: {
    "v-select": vSelect,
    TextEditor,
    InventoryVehicleDescriptionTemplateCTA,
    InventoryVehicleDescriptionTemplate,
    InventoryVehicleDescriptionReWriter,
    InventoryVehicleDescriptionReWriterCTA,
  },
  data() {
    return {
      editorOption: {},
      form: {
        status: "Instock",
        year: "",
        make: "",
        model: "",
        vehicle_description: "",
        title: "",
        change_default: "1",
      },
      errors: [],
      submit: false,
    };
  },
  computed: {
    ...mapGetters("inventoryView", ["getStatuses", "getAvailableFilters"]),
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    getYears() {
      if (this.getAvailableFilters.hasOwnProperty("year")) {
        if (Array.isArray(this.getAvailableFilters["year"])) {
          return this.getAvailableFilters["year"]
            .filter((x) => x.name != "")
            .map((x) => x.name);
        }
      }

      return [];
    },
    getMakes() {
      if (this.getAvailableFilters.hasOwnProperty("make")) {
        if (Array.isArray(this.getAvailableFilters["make"])) {
          return this.getAvailableFilters["make"]
            .filter((x) => x.name != "")
            .map((x) => x.name);
        }
      }

      return [];
    },
    getModels() {
      if (this.getAvailableFilters.hasOwnProperty("model")) {
        if (Array.isArray(this.getAvailableFilters["model"])) {
          return this.getAvailableFilters["model"]
            .filter((x) => x.name != "")
            .map((x) => x.name);
        }
      }

      return [];
    },
  },
  methods: {
    ...mapActions("inventoryView", [
      "commitOnlyEntities",
      "commitAvailableFilters",
    ]),
    async warningForBulkDescriptionChanges() {
      const vm = this;
      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Back",
        confirmButtonText: "Yes",
        toast: false,
        position: "center",
        text: `This action will change the descriptions & title for the selected criteria. Are you sure about this action?`,
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.bulkUpdate();
        }
      });
    },
    bulkUpdate() {
      const vm = this;
      this.submit = true;
      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && this.form[key] != null) {
          formData.append(key, this.form[key]);
        }
      }

      const toast = new Toast(
        `Bulk changes in progress. This might take some time ...`
      );
      toast.fire();

      axios({
        method: "post",
        url: `${window.location.origin}/api/business/inventory/description/updates`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(
            `Bulk changes done. This may take some time to reflect.`
          );
          toast.close();
          this.commitOnlyEntities();
          this.submit = false;
          this.form.year = "";
          this.form.make = "";
          this.form.model = "";
          this.form.status = "Instock";
        })
        .catch(function (error) {
          let error_message = "There were errors in bulk changes.";
          vm.$data.errors = error.response.data;
          toast.fail(error_message);
          toast.close();
          this.submit = false;
        });
    },
    getInventoryConfigurations() {
      let request_url =
        window.location.origin +
        "/api/business/configurations/active/inventory";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (element.key == "editor_default_title") {
              this.form.title = element.value;
            } else if (element.key == "editor_default_vehicle_description") {
              this.form.vehicle_description = element.value;
            }
          }
        }
      });
    },
  },
  created() {
    this.getInventoryConfigurations();
    if (Object.keys(this.getAvailableFilters).length === 0) {
      this.commitAvailableFilters();
    }
  },
  mounted() {
    const vm = this;
    Event.listen("update_vehicle_description", (vehicle_description) => {
      vm.$data.form.vehicle_description = vehicle_description;
    });
  },
};
</script>
<style lang="scss">
#text-editor-settings-1 {
  #ql-picker-options-4 {
    max-height: 300px;
  }
}
</style>
  