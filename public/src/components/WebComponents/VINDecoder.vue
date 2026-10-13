<template>
  <div
    class="modal fade text-left"
    :id="`trim-selection-modal_${elementId}`"
    tabindex="-1"
    role="dialog"
    aria-labelledby="Select Trim Option"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover mb-0">
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>Year</th>
                  <th>Make</th>
                  <th>Model</th>
                  <th>Trim</th>
                  <th></th>
                </tr>
              </thead>

              <tbody>
                <tr
                  :key="`trim_by_selection_${index}`"
                  v-for="(selection, index) in form.selections"
                  @click="selected_trim = selection.style_id"
                >
                  <td>
                    <div class="text-center">
                      <input
                        type="checkbox"
                        class="select_check"
                        :true-value="selection.style_id"
                        v-model="selected_trim"
                      />
                    </div>
                  </td>
                  <td>{{ form.year }}</td>
                  <td>{{ form.make }}</td>
                  <td>{{ form.model }}</td>
                  <td>{{ selection.name }}</td>
                  <td>{{ selection.style }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer py-25">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cancel
          </button>
          <button
            type="close"
            @click.prevent="getVehicleInformationFromTrimID"
            class="btn btn-primary"
            :disabled="!selected_trim"
          >
            Apply
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Toast from "../../library/Toast";
export default {
  props: {
    elementId: {
      type: String,
      required: true,
      default: "",
    },
  },
  name: "VINDecoder",
  data() {
    return {
      form: {
        year: "",
        make: "",
        model: "",
        selections: [],
      },
      selected_trim: "",
    };
  },
  methods: {
    async getVehicleInformationForVIN(vin) {
      if (vin) {
        let message = "Loading VIN attributes...";
        const toast = new Toast(message);
        toast.fire();
        try {
          const { data } = await axios({
            method: "get",
            url: `${window.location.origin}/api/business/vehicles/lookup/vin/${vin}`,
            headers: {
              Authorization: "Bearer " + window.$cookies.get("_token"),
              Accept: "application/json",
            },
          });

          Event.fire(`load_available_data_for_a_vin_${this.elementId}`, data);

          toast.success(
            "Available attributes have been loaded from VIN successfully.",
          );
          toast.close();
        } catch (error) {
          let message = `There were some errors decoding VIN ${vin}`;
          if (error?.response?.data?.error) {
            message = error.response.data.error;
          }
          toast.fail(message);
          toast.close();
        }
      }
    },
    async getVehicleInformationFromTrimID() {
      if (this.selected_trim) {
        let message = "Loading VIN attributes...";
        const toast = new Toast(message);
        toast.fire();
        try {
          const { data } = await axios({
            method: "get",
            url: `${window.location.origin}/api/business/vehicles/lookup/trim_id/${this.selected_trim}`,
            headers: {
              Authorization: "Bearer " + window.$cookies.get("_token"),
              Accept: "application/json",
            },
          });

          Event.fire(`load_available_data_for_a_vin_${this.elementId}`, data);

          toast.success(
            "Available attributes have been loaded from VIN successfully.",
          );
          toast.close();
        } catch (error) {
          let message = `There were some errors decoding VIN ${vin}`;
          if (error?.response?.data?.error) {
            message = error.response.data.error;
          }
          toast.fail(message);
          toast.close();
        }

        this.selected_trim = "";

        $(`#trim-selection-modal_${this.elementId}`).modal("hide");
      }
    },
    async getAvailableTrimsForVIN(vin) {
      let message = "Loading available Trims from VIN ...";
      const toast = new Toast(message);
      toast.fire();

      try {
        const { data } = await axios({
          method: "get",
          url: `${window.location.origin}/api/business/vehicles/lookup/vin/${vin}`,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
            Accept: "application/json",
          },
        });

        for (const key in data) {
          if (this.form.hasOwnProperty(key)) {
            this.form[key] = data[key];
          }

          for (const selection of data.selections) {
            if (selection?.selected) {
              this.selected_trim = selection.style_id;
            }
          }

          $(`#trim-selection-modal_${this.elementId}`).modal("show");

          toast.success(
            "Available trims have been loaded from VIN successfully.",
          );
          toast.close();
        }
      } catch (error) {
        let message = `There were some errors decoding VIN ${vin}`;
        if (error?.response?.data?.error) {
          message = error.response.data.error;
        }
        toast.fail(message);
        toast.close();
      }
    },
  },
  mounted() {
    const vm = this;
    Event.listen(`load_available_trims_for_a_vin_${this.elementId}`, (vin) => {
      vm.getVehicleInformationForVIN(vin);
    });
  },
  beforeDestroy() {
    Event.vue.$off(`load_available_trims_for_a_vin_${this.elementId}`, null);
  },
};
</script>
