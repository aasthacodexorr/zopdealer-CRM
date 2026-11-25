<template>
  <div
    class="tab-pane"
    id="disclosure-block-list"
    aria-labelledby="Disclosure"
    role="tabpanel"
  >
    <section id="disclosures" class="dc-page">
      <form class="form form-horizontal webapp-form dc-form">
        <div
          class="dc-card"
          v-for="(section, sIndex) in sections"
          :key="`disclosure_section_${sIndex}`"
        >
          <div class="dc-card-head" v-if="section.title">
            <span class="dc-card-icon" :class="`dc-card-icon--${sIndex % 4}`">
              <i class="bx" :class="sectionIcon(section.title)"></i>
            </span>
            <h5 class="dc-card-title">{{ section.title }}</h5>
          </div>

          <div class="dc-grid">
            <template v-for="{ disclosure, index } in section.items">
              <!-- textbox : START -->
              <div
                class="dc-item dc-item--full"
                :key="`disclosure_div_${index}`"
                v-if="disclosure.type === `Textbox`"
              >
                <div class="dc-statement-head">
                  <label class="dc-label mb-0" :for="`disclosure_${index}`">
                    {{ disclosure.question }}
                  </label>
                  <div
                    class="dc-statement-actions"
                    v-if="disclosure.question === 'Complete Disclosure'"
                  >
                    <div class="dc-template-cta">
                      <inventory-disclosure-template-c-t-a :elementId="'inventory_disclosure'" />
                    </div>
                    <button
                      type="button"
                      class="dc-btn dc-btn-ghost dc-btn-sm"
                      title="Reset Disclosures"
                      @click="confirmResetAction"
                    >
                      <i class="bx bx-reset"></i>
                      <span>Reset</span>
                    </button>
                  </div>
                </div>
                <textarea
                  :id="`disclosure_${index}`"
                  @change="checkForPrefilledTemplate(disclosures[index])"
                  v-model="disclosures[index].answer"
                  class="form-control dc-textarea"
                  rows="4"
                ></textarea>
              </div>
              <!-- textbox : END -->

              <!-- InputField : START -->
              <div
                class="dc-item dc-item--half"
                :key="`disclosure_div_${index}`"
                v-if="disclosure.type === `InputField`"
              >
                <label class="dc-label" :for="`disclosure_${index}`">{{ disclosure.question }}</label>
                <input
                  :id="`disclosure_${index}`"
                  type="text"
                  @change="checkForPrefilledTemplate(disclosures[index])"
                  v-model="disclosures[index].answer"
                  class="form-control dc-input"
                />
              </div>
              <!-- InputField : END -->

              <!-- button group / switch : START -->
              <div
                class="dc-item dc-item--half dc-choice"
                :key="`disclosure_div_${index}`"
                v-if="disclosure.type === `YesNoQuestion` || disclosure.type === `Switch`"
              >
                <div class="dc-choice-row">
                  <div class="dc-choice-text">
                    <span class="dc-question">{{ disclosure.question }}</span>
                  </div>
                  <div
                    class="dc-segmented"
                    role="group"
                    :aria-label="disclosure.question"
                  >
                    <button
                      @click="
                        disclosures[index].answer = option;
                        checkForPrefilledTemplate(disclosures[index]);
                      "
                      type="button"
                      class="dc-seg-btn"
                      :class="segmentClass(option, disclosures[index].answer)"
                      v-for="option in disclosure.options"
                      :key="`disclosure_div_${index}_${option}`"
                    >
                      {{ optionLabel(option) }}
                    </button>
                  </div>
                </div>
                <input
                  v-if="
                    disclosure.has_additional_information === '1' &&
                    disclosures[index].answer === 'Yes'
                  "
                  type="text"
                  v-model="disclosures[index].additional_information"
                  :placeholder="disclosures[index].additional_information_placeholder"
                  class="form-control dc-input dc-additional"
                />
              </div>
              <!-- button group / switch : END -->

              <!-- dropdown : START -->
              <div
                class="dc-item dc-item--half dc-choice"
                :key="`disclosure_div_${index}`"
                v-if="disclosure.type === `Dropdown`"
              >
                <div class="dc-choice-row">
                  <div class="dc-choice-text">
                    <span class="dc-question">{{ disclosure.question }}</span>
                  </div>
                  <select
                    :name="disclosure.question"
                    class="form-control dc-select"
                    @change="checkForPrefilledTemplate(disclosures[index])"
                    v-model="disclosures[index].answer"
                  >
                    <option
                      :value="option"
                      v-for="option in disclosure.options"
                      :key="`disclosure_div_${index}_${option}`"
                    >
                      {{ option == "" ? "Not Applicable" : option }}
                    </option>
                  </select>
                </div>
                <input
                  v-if="
                    disclosure.has_additional_information === '1' &&
                    disclosures[index].answer === 'Yes'
                  "
                  type="text"
                  v-model="disclosures[index].additional_information"
                  :placeholder="disclosures[index].additional_information_placeholder"
                  class="form-control dc-input dc-additional"
                />
              </div>
              <!-- dropdown : END -->

              <!-- DynamicInputField : START -->
              <div
                class="dc-item dc-item--half"
                :key="`disclosure_div_${index}`"
                v-if="disclosure.type === `DynamicInputField`"
              >
                <label class="dc-label" :for="`disclosure_${index}`">{{ disclosure.question }}</label>
                <input
                  :id="`disclosure_${index}`"
                  type="text"
                  @change="checkForPrefilledTemplate(disclosures[index])"
                  v-model="disclosures[index].answer"
                  class="form-control dc-input"
                />
              </div>
              <!-- DynamicInputField : END -->
            </template>
          </div>
        </div>
      </form>
      <inventory-disclosure-template :elementId="'inventory_disclosure'"  />
    </section>
  </div>
</template>
<script>
import vSelect from "vue-select";
import Toast from "../../library/Toast";
import InventoryDisclosureTemplate from "./InventoryDisclosureTemplate.vue";
import InventoryDisclosureTemplateCTA from "./InventoryDisclosureTemplateCTA.vue";

export default {
  name: "InventoryManageDisclosure",
  components: {
    "v-select": vSelect,
    InventoryDisclosureTemplateCTA,
    InventoryDisclosureTemplate,
  },
  data() {
    return {
      errors: [],
      default_inventory_disclosure_template: [],
      disclosures: [],
    };
  },
  computed: {
    // Group the flat template into cards: every "Header" starts a new card.
    sections() {
      const sections = [];
      let current = null;
      this.disclosures.forEach((disclosure, index) => {
        if (disclosure.type === "Header") {
          current = { title: disclosure.question, items: [] };
          sections.push(current);
          return;
        }
        if (!current) {
          current = { title: "", items: [] };
          sections.push(current);
        }
        current.items.push({ disclosure, index });
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
    sectionIcon(title) {
      const t = String(title || "").toLowerCase();
      if (t.includes("history")) return "bx-history";
      if (t.includes("title") || t.includes("status")) return "bx-certification";
      if (t.includes("damage") || t.includes("repair")) return "bx-wrench";
      return "bx-list-check";
    },
    switchButtonColor(option, value) {
      if (option !== value) {
        return "";
      }

      switch (option) {
        default:
          return "btn-secondary";
          break;
      }
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
        text: "Please confirm if you want to delete the inventory disclosures.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          (async () => {
            await vm.bulkDeleteDisclosures();
            vm.disclosures = JSON.parse(
              JSON.stringify(vm.default_inventory_disclosure_template)
            );
            vm.errors = [];
          })();
        }
      });
    },
    async bulkDeleteDisclosures() {
      const vm = this;
      let toast = new Toast(`Deleting disclosures ...`);
      toast.fire();
      await axios({
        method: "get",
        url:
          window.location.origin +
          `/api/business/inventory/${this.$route.params.id}/common/disclosures/delete`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          toast.success(`Disclosures have been deleted.`);
          toast.close();
        })
        .catch(({ response }) => {
          toast.fail(`There were some errors deleting disclosures.`);
          toast.close();
        });

      await vm.$helpers.gTagClickEvent(
        vm,
        "zd_action",
        "inventory",
        "disclosure",
        "delete"
      );
    },
    async getDisclosureConfiguration() {
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
            if (element.key == "default_inventory_disclosure_template") {
              this.default_inventory_disclosure_template = JSON.parse(
                element.value
              );
            }
          }
        }
      });
    },
    async saveDisclosures() {
      const vm = this;

      if (Array.isArray(this.disclosures)) {
        if (this.disclosures.length > 0) {
          let toast = new Toast(`Saving inventory disclosures ...`);
          toast.fire();
          let formData = new FormData();
          for (const key in this.disclosures) {
            for (const nested_key in this.disclosures[key]) {
              if (
                this.disclosures[key].hasOwnProperty(nested_key) &&
                this.disclosures[key][nested_key] != null
              ) {
                if (
                  nested_key === "options" ||
                  nested_key === "prefilled_templates"
                ) {
                  formData.append(
                    `${key}[${nested_key}]`,
                    JSON.stringify(this.disclosures[key][nested_key])
                  );
                } else {
                  formData.append(
                    `${key}[${nested_key}]`,
                    this.disclosures[key][nested_key]
                  );
                }
              }
            }
          }

          let request_url = `${window.location.origin}/api/business/inventory/${this.$route.params.id}/common/disclosures`;
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
              toast.success(`Inventory disclosures saved.`);
              toast.close();
              vm.getDisclosures(this.$route.params.id);
              vm.$data.errors = [];
            })
            .catch((error) => {
              vm.$data.errors = error.response.data;
              let error_message = `There were some errors in saving the disclosures.`;
              if (error.response.data.hasOwnProperty("errors")) {
                if (typeof error.response.data.errors === `string`) {
                  error_message = error.response.data.errors;
                }
              }
              toast.fail(error_message);
              toast.close();
            });

          setTimeout(function () {
            Event.fire("save_complete", []);
          }, 2000);
        }
      }
    },
    async getDisclosures(inventory_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/inventory/" +
          inventory_id +
          "/common/disclosures",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          if (Array.isArray(data)) {
            if (data.length > 0) {
              this.disclosures = data;
              for (const key in this.disclosures) {
                if (this.disclosures.hasOwnProperty(key)) {
                  if (this.disclosures[key].hasOwnProperty("options")) {
                    try {
                      this.disclosures[key].options = JSON.parse(
                        this.disclosures[key].options
                      );
                    } catch (error) {}
                  }

                  if (
                    this.disclosures[key].hasOwnProperty("prefilled_templates")
                  ) {
                    try {
                      if (
                        this.$helpers.isValidJsonString(
                          this.disclosures[key].prefilled_templates
                        )
                      ) {
                        this.disclosures[key].prefilled_templates = JSON.parse(
                          this.disclosures[key].prefilled_templates
                        );
                      }
                    } catch (error) {}
                  }

                  if (this.disclosures[key].hasOwnProperty("updated_at")) {
                    delete this.disclosures[key]["updated_at"];
                  }

                  if (this.disclosures[key].hasOwnProperty("created_at")) {
                    delete this.disclosures[key]["created_at"];
                  }
                }
              }
            }
          }
        })
        .catch(({ response }) => {
          vm.disclosures = JSON.parse(
            JSON.stringify(vm.default_inventory_disclosure_template)
          );
        });
    },
    checkForPrefilledTemplate(disclosure) {
      if (disclosure.hasOwnProperty("prefilled_templates")) {
        if (
          typeof disclosure["prefilled_templates"] === "object" &&
          ["YesNoQuestion", "Switch", "Dropdown"].includes(disclosure["type"])
        ) {
          for (const [key, template] of Object.entries(
            disclosure["prefilled_templates"]
          )) {
            this.removePrefilledTemplate(template);
          }

          if (
            disclosure["prefilled_templates"].hasOwnProperty(disclosure.answer)
          ) {
            let new_template =
              disclosure["prefilled_templates"][disclosure.answer];
            this.addPrefilledTemplate(new_template);
          }
        }
      }
    },
    removePrefilledTemplate(template) {
      this.disclosures[0].answer = this.disclosures[0].answer.replaceAll(
        template,
        ""
      );
    },
    addPrefilledTemplate(template, additional_information = "") {
      this.disclosures[0].answer = `${this.disclosures[0].answer} ${template}`;
      if (additional_information.trim() !== "") {
        this.disclosures[0].answer = `${this.disclosures[0].answer} ${additional_information}`;
      }
    },
  },
  async created() {
    const vm = this;
    if (vm.updateView) {
      await vm.getDisclosureConfiguration();
      await vm.getDisclosures(this.$route.params.id);
    }
  },
  mounted() {
    const vm = this;

    Event.listen("save_inventory_disclosures", (data) => {
      vm.saveDisclosures();
      vm.$helpers.gTagClickEvent(
        vm,
        "zd_action",
        "inventory",
        "disclosure",
        "save"
      );
    });

    Event.listen("add_prefilled_template_inventory_disclosure", (template) => {
      vm.addPrefilledTemplate(template);
    });
  },
  beforeUnmount() {
    Event.vue.$off("save_complete", null);
  },
};
</script>
<style lang="scss">
/* ==========================================================================
   Inventory — Disclosures tab. Scoped to #disclosure-block-list.
   Shared checklist design lives in assets/scss/_checklist-form.scss.
   ========================================================================== */
@import "../../assets/scss/checklist-form";

#disclosure-block-list {
  @include checklist-form;
}
</style>
