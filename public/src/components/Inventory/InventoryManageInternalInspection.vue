<template>
  <div
    class="tab-pane"
    id="inventory-manage-internal-inspection-panel"
    aria-labelledby="Internal Inspection Checklist"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="inventory-manage-internal-inspection-section">
      <div class="row match-height">
        <div class="col-md-12 col-12">
          <div class="card shadow-none mb-0">
            <div class="card-content">
              <div class="card-body pt-0">
                <form class="form form-horizontal webapp-form">
                  <div class="form-body">
                    <div class="row">
                      <h5 class="col-12 text-danger" style="font-size: 0.9em">
                        <strong
                          >NOTE: This section is not shared on the website.
                          Kindly download the Checklist from the print section
                          to share with customer.</strong
                        >
                      </h5>
                      <span style="position: absolute; right: 1em; z-index: 2">
                        <i
                          class="bx bx-repost pr-1 bx-md"
                          title="Reset Internal Inspection"
                          style="cursor: pointer"
                          @click="confirmResetAction"
                        ></i>
                        <i
                          class="bx bx-save pr-1 bx-md"
                          @click="saveInternalInspectionChecklist()"
                          title="Save Internal Checklist"
                          style="cursor: pointer"
                        ></i>
                      </span>
                      <!-- loop through internal inspection checkpoints : START -->
                      <template
                        v-for="(checkpoint,
                        index) in internal_inspection_checkpoints"
                      >
                        <!-- textbox : START -->
                        <div
                          class="col-md-12 col-12 pt-25 pb-25"
                          :key="`checkpoint_div_${index}`"
                          v-if="checkpoint.type === `Textbox`"
                        >
                          <div class="row">
                            <div class="col-md-3">
                              {{ checkpoint.question }}
                            </div>
                            <div class="col-md-7">
                              <textarea
                                v-model="
                                  internal_inspection_checkpoints[index].answer
                                "
                                class="form-control"
                                rows="2"
                              ></textarea>
                            </div>
                          </div>
                        </div>
                        <!-- textbox : END -->

                        <!-- InputField : START -->
                        <div
                          class="col-md-3 col-3 pt-25 pb-25"
                          :key="`checkpoint_div_${index}`"
                          :class="checkpoint.css_class"
                          v-if="checkpoint.type === `InputField`"
                        >
                          <div class="row">
                            <div class="col-md-12">
                              {{ checkpoint.question }}
                            </div>
                            <div class="col-md-12">
                              <input
                                type="text"
                                v-model="
                                  internal_inspection_checkpoints[index].answer
                                "
                                class="form-control"
                              />
                            </div>
                          </div>
                        </div>
                        <!-- InputField : END -->

                        <!-- button group : START -->
                        <div
                          class="col-md-4 col-4 pt-50 pb-50"
                          :class="checkpoint.css_class"
                          :key="`checkpoint_div_${index}`"
                          v-if="checkpoint.type === `Switch`"
                        >
                          <div class="row">
                            <div class="col-md-12">
                              {{ checkpoint.question }}
                            </div>
                            <div class="col-md-12">
                              <div
                                class="btn-group"
                                role="group"
                                aria-label="Basic example"
                              >
                                <button
                                  @click="
                                    internal_inspection_checkpoints[
                                      index
                                    ].answer = option
                                  "
                                  type="button"
                                  class="btn"
                                  :class="
                                    switchButtonColor(
                                      option,
                                      internal_inspection_checkpoints[index]
                                        .answer
                                    )
                                  "
                                  v-for="option in checkpoint.options"
                                  :key="`checkpoint_div_${index}_${option}`"
                                >
                                  {{
                                    option == "" || option == "Not Applicable"
                                      ? "N/A"
                                      : option
                                  }}
                                </button>
                              </div>
                            </div>
                            <div
                              class="col-md-12 p-25"
                              v-if="
                                checkpoint.has_additional_information === '1' &&
                                  internal_inspection_checkpoints[index]
                                    .answer === 'Yes'
                              "
                            >
                              <input
                                type="text"
                                v-model="
                                  internal_inspection_checkpoints[index]
                                    .additional_information
                                "
                                :placeholder="
                                  internal_inspection_checkpoints[index]
                                    .additional_information_placeholder
                                "
                                class="form-control"
                              />
                            </div>
                          </div>
                        </div>
                        <!-- button group : END -->

                        <!-- dropdown : START -->
                        <div
                          class="col-md-6 col-6 pt-25 pb-25"
                          :key="`checkpoint_div_${index}`"
                          v-if="checkpoint.type === `Dropdown`"
                        >
                          <div class="row">
                            <div class="col-md-6">
                              {{ checkpoint.question }}
                            </div>
                            <div class="col-md-6">
                              <select
                                :name="checkpoint.question"
                                class="form-control"
                                v-model="
                                  internal_inspection_checkpoints[index].answer
                                "
                                id=""
                              >
                                <option
                                  :value="option"
                                  v-for="option in checkpoint.options"
                                  :key="`checkpoint_div_${index}_${option}`"
                                >
                                  {{ option == "" ? "Not Applicable" : option }}
                                </option>
                              </select>
                            </div>
                            <div
                              class="col-md-12 p-25"
                              v-if="
                                checkpoint.has_additional_information === '1' &&
                                  internal_inspection_checkpoints[index]
                                    .answer === 'Yes'
                              "
                            >
                              <input
                                type="text"
                                v-model="
                                  internal_inspection_checkpoints[index]
                                    .additional_information
                                "
                                :placeholder="
                                  internal_inspection_checkpoints[index]
                                    .additional_information_placeholder
                                "
                                class="form-control"
                              />
                            </div>
                          </div>
                        </div>
                        <!-- dropdown : END -->

                        <!-- header : START -->
                        <div
                          class="col-md-12 col-12 pt-25 pb-25"
                          :key="`checkpoint_div_${index}`"
                          v-if="checkpoint.type === `Header`"
                        >
                          <div class="row">
                            <h2 class="col-12">{{ checkpoint.question }}</h2>
                          </div>
                        </div>
                        <!-- header : END -->
                      </template>
                      <!-- loop through internal inspection checkpoints : END -->
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
import vSelect from "vue-select";
import Toast from "../../library/Toast";

export default {
  name: "InventoryManageInternalInspection",
  components: {
    "v-select": vSelect,
  },
  data() {
    return {
      errors: [],
      default_inventory_internal_inspection_template: [],
      internal_inspection_checkpoints: [],
    };
  },
  computed: {
    updateView() {
      return this.$route.params.id && this.$route.name == "inventory-update";
    },
  },
  methods: {
    switchButtonColor(option, value) {
      if (option !== value) {
        return "";
      }

      return "btn-secondary";
    },
    async confirmResetAction() {
      const vm = this;
      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Confirm",
        toast: false,
        position: "center",
        text:
          "Please confirm if you want to delete the inventory internal inspection checklist.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          //delete the internal inspection checklist
          (async () => {
            await vm.bulkDeleteInventoryInternalInspection();
            //reset to internal inspection checklist
            vm.internal_inspection_checkpoints = JSON.parse(
              JSON.stringify(vm.default_inventory_internal_inspection_template)
            );
            vm.errors = [];
          })();
        }
      });
    },
    async bulkDeleteInventoryInternalInspection() {
      const vm = this;
      let toast = new Toast(`Deleting internal inspection checklist ...`);
      toast.fire();
      await axios({
        method: "get",
        url:
          window.location.origin +
          `/api/business/inventory/${this.$route.params.id}/common/internal-inspection/delete`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          toast.success(`Internal inspection checklist has been deleted.`);
          toast.close();
        })
        .catch(({ response }) => {
          toast.fail(
            `There were some errors deleting internal inspection checklist.`
          );
          toast.close();
        });

      await vm.$helpers.gTagClickEvent(
        vm,
        "zd_action",
        "inventory",
        "internal_inspection",
        "delete"
      );
    },
    async getInventoryInternalInspectionConfiguration() {
      let request_url =
        window.location.origin + "/api/business/configurations/active/global";
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
            if (
              element.key == "default_inventory_internal_inspection_template"
            ) {
              this.default_inventory_internal_inspection_template = JSON.parse(
                element.value
              );
            }
          }
        }
      });
    },
    async saveInternalInspectionChecklist() {
      const vm = this;

      if (Array.isArray(this.internal_inspection_checkpoints)) {
        if (this.internal_inspection_checkpoints.length > 0) {
          let toast = new Toast(
            `Saving inventory internal inspection checklist ...`
          );
          toast.fire();
          let formData = new FormData();
          for (const key in this.internal_inspection_checkpoints) {
            for (const nested_key in this.internal_inspection_checkpoints[key]) {
              if (
                this.internal_inspection_checkpoints[key].hasOwnProperty(
                  nested_key
                ) &&
                this.internal_inspection_checkpoints[key][nested_key] != null
              ) {
                if (nested_key === "options") {
                  formData.append(
                    `${key}[${nested_key}]`,
                    JSON.stringify(
                      this.internal_inspection_checkpoints[key][nested_key]
                    )
                  );
                } else {
                  formData.append(
                    `${key}[${nested_key}]`,
                    this.internal_inspection_checkpoints[key][nested_key]
                  );
                }
              }
            }
          }

          let request_url = `${window.location.origin}/api/business/inventory/${this.$route.params.id}/common/internal-inspection`;
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
              console.log(data);
              toast.success(`Inventory internal inspection checklist saved.`);
              toast.close();
              vm.getInternalInspectionChecklist(this.$route.params.id);
              vm.$data.errors = [];
            })
            .catch((error) => {
              vm.$data.errors = error.response.data;
              let error_message = `There were some errors in saving the internal inspection checklist.`;
              if (error.response.data.hasOwnProperty("errors")) {
                if (typeof error.response.data.errors === `string`) {
                  error_message = error.response.data.errors;
                }
              }
              toast.fail(error_message);
              toast.close();
            });
        }
      }

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "inventory",
        "internal_inspection",
        "save"
      );
    },
    async getInternalInspectionChecklist(inventory_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/inventory/" +
          inventory_id +
          "/common/internal-inspection",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          if (Array.isArray(data)) {
            if (data.length > 0) {
              this.internal_inspection_checkpoints = data;
              for (const key in this.internal_inspection_checkpoints) {
                if (this.internal_inspection_checkpoints.hasOwnProperty(key)) {
                  if (
                    this.internal_inspection_checkpoints[key].hasOwnProperty(
                      "options"
                    )
                  ) {
                    try {
                      this.internal_inspection_checkpoints[key].options = JSON.parse(
                        this.internal_inspection_checkpoints[key].options
                      );
                    } catch (error) {}
                  }

                  if (
                    this.internal_inspection_checkpoints[key].hasOwnProperty(
                      "updated_at"
                    )
                  ) {
                    delete this.internal_inspection_checkpoints[key]["updated_at"];
                  }

                  if (
                    this.internal_inspection_checkpoints[key].hasOwnProperty(
                      "created_at"
                    )
                  ) {
                    delete this.internal_inspection_checkpoints[key]["created_at"];
                  }
                }
              }
            }
          }
        })
        .catch(({ response }) => {
          vm.internal_inspection_checkpoints = JSON.parse(
            JSON.stringify(vm.default_inventory_internal_inspection_template)
          );
        });
    },
  },
  async created() {
    const vm = this;
    if (vm.updateView) {
      await vm.getInventoryInternalInspectionConfiguration();
      await vm.getInternalInspectionChecklist(this.$route.params.id);
    }
  },
};
</script>
