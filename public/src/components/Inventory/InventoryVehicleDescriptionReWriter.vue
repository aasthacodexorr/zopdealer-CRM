<template>
  <!-- Vehicle Description ReWriter Modal : START  -->
  <div
    class="modal fade"
    id="vehicleDescriptionReWriterModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="Vehicle Description ReWriter"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <!-- Modal Header : START -->
        <div class="modal-header">
          <h5 class="modal-title" id="vehicleDescriptionReWriterModalHeader">
            Vehicle Description ReWriter
          </h5>
          <button
            type="button"
            id="vehicleDescriptionReWriterModalClose"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <!-- Modal Header : END -->
        <!-- Modal Body : START -->
        <div class="modal-body">
          <form>
            <div class="form-row">
              <!-- Audience : START -->
              <div class="form-group col-md-12">
                <label for="vehicle_description_rewritter_audience"
                  >Audience</label
                >
                <div
                  class="btn-group col-md-12 px-0"
                  role="group"
                  aria-label="Target Audience"
                >
                  <button
                    type="button"
                    @click="
                      changeVehicleDescriptionRewriterAttribute(
                        'audience',
                        'First-Time'
                      )
                    "
                    :class="
                      selectVehicleDescriptionRewriterAttribute(
                        'audience',
                        'First-Time'
                      )
                    "
                    class="btn btn-primary-outline"
                  >
                    First-Time
                  </button>
                  <button
                    type="button"
                    @click="
                      changeVehicleDescriptionRewriterAttribute(
                        'audience',
                        'Family'
                      )
                    "
                    :class="
                      selectVehicleDescriptionRewriterAttribute(
                        'audience',
                        'Family'
                      )
                    "
                    class="btn btn-primary-outline"
                  >
                    Family
                  </button>
                  <button
                    type="button"
                    @click="
                      changeVehicleDescriptionRewriterAttribute(
                        'audience',
                        'Luxury'
                      )
                    "
                    :class="
                      selectVehicleDescriptionRewriterAttribute(
                        'audience',
                        'Luxury'
                      )
                    "
                    class="btn btn-primary-outline"
                  >
                    Luxury
                  </button>
                  <button
                    type="button"
                    @click="
                      changeVehicleDescriptionRewriterAttribute(
                        'audience',
                        'Enthusiast'
                      )
                    "
                    :class="
                      selectVehicleDescriptionRewriterAttribute(
                        'audience',
                        'Enthusiast'
                      )
                    "
                    class="btn btn-primary-outline"
                  >
                    Enthusiast
                  </button>
                  <button
                    type="button"
                    @click="
                      changeVehicleDescriptionRewriterAttribute(
                        'audience',
                        'Eco-Conscious'
                      )
                    "
                    :class="
                      selectVehicleDescriptionRewriterAttribute(
                        'audience',
                        'Eco-Conscious'
                      )
                    "
                    class="btn btn-primary-outline"
                  >
                    Eco-Conscious
                  </button>
                  <button
                    type="button"
                    :class="
                      selectVehicleDescriptionRewriterAttribute(
                        'audience',
                        'Budget'
                      )
                    "
                    @click="
                      changeVehicleDescriptionRewriterAttribute(
                        'audience',
                        'Budget'
                      )
                    "
                    class="btn btn-primary-outline"
                  >
                    Budget
                  </button>
                  <button
                    type="button"
                    :class="
                      selectVehicleDescriptionRewriterAttribute('audience', '')
                    "
                    @click="
                      changeVehicleDescriptionRewriterAttribute('audience', '')
                    "
                    class="btn btn-primary-outline"
                  >
                    All
                  </button>
                </div>
              </div>
              <!-- Audience : END -->

              <!-- Emoji : START -->
              <div class="form-group col-md-12">
                <label for="vehicle_description_rewritter_add_emoji"
                  >Add Emojis</label
                >
                <div
                  class="btn-group col-md-12 px-0"
                  role="group"
                  aria-label="Add Emojis"
                >
                  <button
                    :class="
                      selectVehicleDescriptionRewriterAttribute(
                        'add_emojis',
                        'Yes'
                      )
                    "
                    @click="
                      changeVehicleDescriptionRewriterAttribute(
                        'add_emojis',
                        'Yes'
                      )
                    "
                    type="button"
                    class="btn btn-primary-outline"
                  >
                    Yes
                  </button>
                  <button
                    :class="
                      selectVehicleDescriptionRewriterAttribute(
                        'add_emojis',
                        'No'
                      )
                    "
                    @click="
                      changeVehicleDescriptionRewriterAttribute(
                        'add_emojis',
                        'No'
                      )
                    "
                    type="button"
                    class="btn btn-primary-outline"
                  >
                    No
                  </button>
                  <button
                    :class="
                      selectVehicleDescriptionRewriterAttribute(
                        'add_emojis',
                        ''
                      )
                    "
                    @click="
                      changeVehicleDescriptionRewriterAttribute(
                        'add_emojis',
                        ''
                      )
                    "
                    type="button"
                    class="btn btn-primary-outline"
                  >
                    Skip
                  </button>
                </div>
              </div>
              <!-- Emoji : END -->

              <!-- Platform Optimized : START -->
              <div class="form-group col-md-12">
                <label for="vehicle_description_rewritter_platform"
                  >Platform Optimized</label
                >
                <div
                  class="btn-group col-md-12 px-0"
                  role="group"
                  aria-label="Platform Optimized"
                >
                  <button
                    type="button"
                    :class="
                      selectVehicleDescriptionRewriterAttribute(
                        'platform_optimized',
                        'Social Media'
                      )
                    "
                    @click="
                      changeVehicleDescriptionRewriterAttribute(
                        'platform_optimized',
                        'Social Media'
                      )
                    "
                    class="btn btn-primary-outline"
                  >
                    Social Media
                  </button>
                  <button
                    :class="
                      selectVehicleDescriptionRewriterAttribute(
                        'platform_optimized',
                        'Website'
                      )
                    "
                    @click="
                      changeVehicleDescriptionRewriterAttribute(
                        'platform_optimized',
                        'Website'
                      )
                    "
                    type="button"
                    class="btn btn-primary-outline"
                  >
                    Website
                  </button>
                  <button
                    :class="
                      selectVehicleDescriptionRewriterAttribute(
                        'platform_optimized',
                        'Both'
                      )
                    "
                    @click="
                      changeVehicleDescriptionRewriterAttribute(
                        'platform_optimized',
                        'Both'
                      )
                    "
                    type="button"
                    class="btn btn-primary-outline"
                  >
                    Both
                  </button>
                  <button
                    :class="
                      selectVehicleDescriptionRewriterAttribute(
                        'platform_optimized',
                        ''
                      )
                    "
                    @click="
                      changeVehicleDescriptionRewriterAttribute(
                        'platform_optimized',
                        ''
                      )
                    "
                    type="button"
                    class="btn btn-primary-outline"
                  >
                    Skip
                  </button>
                </div>
              </div>
              <!-- Platform Optimized : END -->

              <!-- Length of Description : START -->
              <div class="form-group col-md-12">
                <label for="vehicle_description_rewritter_platform"
                  >Length of Description</label
                >
                <div
                  class="btn-group col-md-12 px-0"
                  role="group"
                  aria-label="Length of Description"
                >
                  <button
                    :class="
                      selectVehicleDescriptionRewriterAttribute(
                        'length_of_description',
                        'Short'
                      )
                    "
                    @click="
                      changeVehicleDescriptionRewriterAttribute(
                        'length_of_description',
                        'Short'
                      )
                    "
                    type="button"
                    class="btn btn-primary-outline"
                  >
                    Short
                  </button>
                  <button
                    :class="
                      selectVehicleDescriptionRewriterAttribute(
                        'length_of_description',
                        'Long'
                      )
                    "
                    @click="
                      changeVehicleDescriptionRewriterAttribute(
                        'length_of_description',
                        'Long'
                      )
                    "
                    type="button"
                    class="btn btn-primary-outline"
                  >
                    Long
                  </button>
                  <button
                    :class="
                      selectVehicleDescriptionRewriterAttribute(
                        'length_of_description',
                        ''
                      )
                    "
                    @click="
                      changeVehicleDescriptionRewriterAttribute(
                        'length_of_description',
                        ''
                      )
                    "
                    type="button"
                    class="btn btn-primary-outline"
                  >
                    Any
                  </button>
                </div>
              </div>
              <!-- Length of Description : END -->

              <!-- Description : START -->
              <div class="form-group col-md-12">
                <label for="text-editor-vehicle-description-rewriter"
                  >Preview Updated Description:
                </label>
                <text-editor
                  name="description"
                  disabled
                  ref="vehicleDescriptionTemplate"
                  :options="editorOption"
                  v-model="vehicle_description_rewriter_form.description"
                  :elementId="'text-editor-vehicle-description-rewriter'"
                  :editor_style="'min-height:20em;'"
                >
                </text-editor>
                <small class="text-danger">This description is rendered for preview.</small>
              </div>
              <!-- Description : END -->
            </div>
          </form>
        </div>
        <!-- Modal Body : END -->
        <!-- Modal Footer : START -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Close
          </button>
          <button
            type="button"
            class="btn btn-primary"
            @click.prevent="vehicleInformationReWriter"
          >
            Generate
          </button>
          <button
            type="button"
            class="btn btn-primary"
            data-dismiss="modal"
            @click="
              changeTemplateNotification(
                vehicle_description_rewriter_form.description
              )
            "
            v-show="vehicle_description_rewriter_form.description !== ''"
          >
            Apply
          </button>
        </div>
        <!-- Modal Footer : END -->
      </div>
    </div>
  </div>
  <!-- Vehicle Description ReWriter Modal : END  -->
</template>
<script>
import TextEditor from "../WebComponents/TextEditor";
import Toast from "../../library/Toast";

export default {
  props: {
    vehicle_description: {
      type: String,
      required: false,
      default: "",
    },
  },
  name: "InventoryVehicleDescriptionReWriter",
  components: {
    TextEditor,
  },
  data() {
    return {
      editorOption: {},
      vehicle_description_rewriter_form: {
        audience: "",
        add_emojis: "",
        platform_optimized: "",
        length_of_description: "",
        description: "",
      },
      vehicle_description_rewriter_errors: [],
    };
  },
  methods: {
    changeVehicleDescriptionRewriterAttribute(attribute, value) {
      this.vehicle_description_rewriter_form[attribute] = value;
    },
    selectVehicleDescriptionRewriterAttribute(attribute, value) {
      return this.vehicle_description_rewriter_form[attribute] === value
        ? "active"
        : "";
    },
    async vehicleInformationReWriter() {
      const vm = this;

      let message = "Rewriting the vehicle description ...";
      const toast = new Toast(message);
      toast.fire();

      let formData = new FormData();
      for (const key in this.vehicle_description_rewriter_form) {
        if (
          this.vehicle_description_rewriter_form.hasOwnProperty(key) &&
          key !== "description"
        ) {
          formData.append(key, this.vehicle_description_rewriter_form[key]);
        }
      }

      formData.append("content", vm.vehicle_description);

      await axios({
        method: "post",
        data: formData,
        url: `${window.location.origin}/api/business/inventory/vehicle-description-rewriter`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          this.vehicle_description_rewriter_form.description = data.description;
          toast.success("Vehicle description has been rewritten.");
          toast.close();
        })
        .catch((error) => {
          let error_message =
            "There were some error in rewriting the vehicle description.";
          toast.fail(error_message);
          toast.close();
        });

      vm.$helpers.gTagClickEvent(
        vm,
        "zd_action",
        "inventory",
        "vehicle_information_rewriter",
        "save"
      );
    },
    changeTemplateNotification(description) {
      Event.fire("update_vehicle_description", description);
      document.getElementById("vehicleDescriptionReWriterModalClose").click();

      this.vehicle_description_rewriter_form.description = "";

      const toast = new Toast("");
      toast.reset({
        title:
          "Vehicle description has been changed. Please submit the form for the changes to take effect.",
        showConfirmButton: false,
        timer: 5000,
        toast: true,
        position: "bottom",
        icon: "success",
      });
      toast.fire();
      toast.close();
    },
  },
  beforeDestroy() {
    Event.vue.$off("update_vehicle_description", null);
  },
};
</script>