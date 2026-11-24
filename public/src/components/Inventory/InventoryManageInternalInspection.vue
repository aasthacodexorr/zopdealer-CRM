<template>
  <div
    class="tab-pane"
    id="inventory-manage-internal-inspection-panel"
    aria-labelledby="Internal Inspection Checklist"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="inventory-manage-internal-inspection-section" class="dc-page">
      <div class="dc-header dc-header--split">
        <h5 class="dc-notice">
          <i class="bx bx-info-circle"></i>
          <strong
            >NOTE: This section is not shared on the website. Kindly download
            the Checklist from the print section to share with customer.</strong
          >
        </h5>
        <div class="dc-header-actions">
          <button
            type="button"
            class="dc-btn dc-btn-ghost"
            title="Reset Internal Inspection"
            @click="confirmResetAction"
          >
            <i class="bx bx-reset"></i>
            <span>Reset</span>
          </button>
          <button
            type="button"
            class="dc-btn dc-btn-primary"
            title="Save Internal Checklist"
            @click="saveInternalInspectionChecklist()"
          >
            <i class="bx bx-save"></i>
            <span>Save</span>
          </button>
        </div>
      </div>

      <form class="form form-horizontal webapp-form dc-form">
        <div
          class="dc-card"
          v-for="(section, sIndex) in sections"
          :key="`checkpoint_section_${sIndex}`"
        >
          <div class="dc-card-head" v-if="section.title">
            <span class="dc-card-icon" :class="`dc-card-icon--${sIndex % 4}`">
              <i class="bx bx-list-check"></i>
            </span>
            <h5 class="dc-card-title">{{ section.title }}</h5>
          </div>

          <div class="dc-grid">
            <!-- loop through internal inspection checkpoints : START -->
            <template v-for="{ checkpoint, index } in section.items">
              <!-- textbox : START -->
              <div
                class="dc-item dc-item--full"
                :key="`checkpoint_div_${index}`"
                v-if="checkpoint.type === `Textbox`"
              >
                <label class="dc-label" :for="`checkpoint_${index}`">{{ checkpoint.question }}</label>
                <textarea
                  :id="`checkpoint_${index}`"
                  v-model="internal_inspection_checkpoints[index].answer"
                  class="form-control dc-textarea"
                  rows="2"
                ></textarea>
              </div>
              <!-- textbox : END -->

              <!-- InputField : START -->
              <div
                class="dc-item dc-item--quarter"
                :key="`checkpoint_div_${index}`"
                :class="checkpoint.css_class"
                v-if="checkpoint.type === `InputField`"
              >
                <label class="dc-label" :for="`checkpoint_${index}`">{{ checkpoint.question }}</label>
                <input
                  :id="`checkpoint_${index}`"
                  type="text"
                  v-model="internal_inspection_checkpoints[index].answer"
                  class="form-control dc-input"
                />
              </div>
              <!-- InputField : END -->

              <!-- button group : START -->
              <div
                class="dc-item dc-item--half dc-choice"
                :class="checkpoint.css_class"
                :key="`checkpoint_div_${index}`"
                v-if="checkpoint.type === `Switch`"
              >
                <div class="dc-choice-row">
                  <div class="dc-choice-text">
                    <span class="dc-question">{{ checkpoint.question }}</span>
                  </div>
                  <div class="dc-segmented" role="group" :aria-label="checkpoint.question">
                    <button
                      @click="internal_inspection_checkpoints[index].answer = option"
                      type="button"
                      class="dc-seg-btn"
                      :class="segmentClass(option, internal_inspection_checkpoints[index].answer)"
                      v-for="option in checkpoint.options"
                      :key="`checkpoint_div_${index}_${option}`"
                    >
                      {{ optionLabel(option) }}
                    </button>
                  </div>
                </div>
                <input
                  v-if="
                    checkpoint.has_additional_information === '1' &&
                    internal_inspection_checkpoints[index].answer === 'Yes'
                  "
                  type="text"
                  v-model="internal_inspection_checkpoints[index].additional_information"
                  :placeholder="internal_inspection_checkpoints[index].additional_information_placeholder"
                  class="form-control dc-input dc-additional"
                />
              </div>
              <!-- button group : END -->

              <!-- dropdown : START -->
              <div
                class="dc-item dc-item--half dc-choice"
                :key="`checkpoint_div_${index}`"
                v-if="checkpoint.type === `Dropdown`"
              >
                <div class="dc-choice-row">
                  <div class="dc-choice-text">
                    <span class="dc-question">{{ checkpoint.question }}</span>
                  </div>
                  <select
                    :name="checkpoint.question"
                    class="form-control dc-select"
                    v-model="internal_inspection_checkpoints[index].answer"
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
                <input
                  v-if="
                    checkpoint.has_additional_information === '1' &&
                    internal_inspection_checkpoints[index].answer === 'Yes'
                  "
                  type="text"
                  v-model="internal_inspection_checkpoints[index].additional_information"
                  :placeholder="internal_inspection_checkpoints[index].additional_information_placeholder"
                  class="form-control dc-input dc-additional"
                />
              </div>
              <!-- dropdown : END -->
            </template>
            <!-- loop through internal inspection checkpoints : END -->
          </div>
        </div>
      </form>
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
    // Display only: group the flat checklist into cards; every "Header" starts a new card.
    sections() {
      const sections = [];
      let current = null;
      this.internal_inspection_checkpoints.forEach((checkpoint, index) => {
        if (checkpoint.type === "Header") {
          current = { title: checkpoint.question, items: [] };
          sections.push(current);
          return;
        }
        if (!current) {
          current = { title: "", items: [] };
          sections.push(current);
        }
        current.items.push({ checkpoint, index });
      });
      return sections;
    },
    updateView() {
      return this.$route.params.id && this.$route.name == "inventory-update";
    },
  },
  methods: {
    optionLabel(option) {
      return option == "" || option == "Not Applicable" ? "N/A" : option;
    },
    segmentClass(option, value) {
      if (option !== value) return "";
      if (option === "Yes") return "is-active is-yes";
      if (option === "No") return "is-active is-no";
      return "is-active is-na";
    },
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
<style lang="scss">
/* ==========================================================================
   Inventory — Internal Inspection. Shares the Disclosures checklist design.
   ========================================================================== */
@import "../../assets/scss/checklist-form";

/* Scoped by the section id: the root element's id is overridden by the
   parent's fall-through `id` attribute. */
#inventory-manage-internal-inspection-section {
  @include checklist-form;
  &.dc-page { padding: 0 0 24px; }
}
</style>

