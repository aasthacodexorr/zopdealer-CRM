<template>
  <div
    class="tab-pane"
    id="deal-tradein-disclosure-panel"
    aria-labelledby="Trade In Disclosure"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="disclosures">
      <div class="row match-height">
        <div class="col-md-12 col-12">
          <div class="card shadow-none mb-0">
            <div class="card-content">
              <div class="card-body pt-0">
                <form class="form form-horizontal webapp-form">
                  <div class="form-body">
                    <div class="row">
                      <span style="position: absolute; right: 1em; z-index: 2">
                        <i
                          class="bx bx-repost pr-1 bx-md"
                          title="Reset Disclosures"
                          style="cursor: pointer"
                          @click="confirmResetAction"
                        ></i>
                      </span>
                      <!-- loop through disclosures : START -->
                      <template v-for="(disclosure, index) in disclosures">
                        <!-- textbox : START -->
                        <div
                          class="col-md-12 col-12 pt-25 pb-25"
                          :key="`disclosure_div_${index}`"
                          v-if="disclosure.type === `Textbox`"
                        >
                          <div class="row">
                            <div class="col-md-3">
                              {{ disclosure.question }}
                            </div>
                            <div class="col-md-7">
                              <textarea
                                @change="
                                  checkForPrefilledTemplate(disclosures[index])
                                "
                                v-model="disclosures[index].answer"
                                class="form-control"
                                rows="3"
                              ></textarea>
                            </div>
                          </div>
                          <div
                            class="row"
                            v-if="disclosure.question === 'Complete Disclosure'"
                          >
                            <div class="col-md-10 text-right p-1">
                              <inventory-disclosure-template-c-t-a :elementId="'trade_in_disclosure'" />
                            </div>
                          </div>
                        </div>
                        <!-- textbox : END -->

                        <!-- InputField : START -->
                        <div
                          class="col-md-12 col-12 pt-25 pb-25"
                          :key="`disclosure_div_${index}`"
                          v-if="disclosure.type === `InputField`"
                        >
                          <div class="row">
                            <div class="col-md-3">
                              {{ disclosure.question }}
                            </div>
                            <div class="col-md-7">
                              <input
                                type="text"
                                @change="
                                  checkForPrefilledTemplate(disclosures[index])
                                "
                                v-model="disclosures[index].answer"
                                class="form-control"
                              />
                            </div>
                          </div>
                        </div>
                        <!-- InputField : END -->

                        <!-- button group : START -->
                        <div
                          class="col-md-6 col-6 pt-25 pb-25"
                          :key="`disclosure_div_${index}`"
                          v-if="disclosure.type === `YesNoQuestion`"
                        >
                          <div class="row">
                            <div class="col-md-6">
                              {{ disclosure.question }}
                            </div>
                            <div class="col-md-6">
                              <div
                                class="btn-group"
                                role="group"
                                aria-label="Basic example"
                              >
                                <button
                                  @click="
                                    disclosures[index].answer = option;
                                    checkForPrefilledTemplate(
                                      disclosures[index]
                                    );
                                  "
                                  type="button"
                                  class="btn"
                                  :class="
                                    switchButtonColor(
                                      option,
                                      disclosures[index].answer
                                    )
                                  "
                                  v-for="option in disclosure.options"
                                  :key="`disclosure_div_${index}_${option}`"
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
                                disclosure.has_additional_information === '1' &&
                                disclosures[index].answer === 'Yes'
                              "
                            >
                              <input
                                type="text"
                                v-model="
                                  disclosures[index].additional_information
                                "
                                :placeholder="
                                  disclosures[index]
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
                          :key="`disclosure_div_${index}`"
                          v-if="disclosure.type === `Dropdown`"
                        >
                          <div class="row">
                            <div class="col-md-6">
                              {{ disclosure.question }}
                            </div>
                            <div class="col-md-6">
                              <select
                                :name="disclosure.question"
                                class="form-control"
                                @change="
                                  checkForPrefilledTemplate(disclosures[index])
                                "
                                v-model="disclosures[index].answer"
                                id=""
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
                            <div
                              class="col-md-12 p-25"
                              v-if="
                                disclosure.has_additional_information === '1' &&
                                disclosures[index].answer === 'Yes'
                              "
                            >
                              <input
                                type="text"
                                v-model="
                                  disclosures[index].additional_information
                                "
                                :placeholder="
                                  disclosures[index]
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
                          :key="`disclosure_div_${index}`"
                          v-if="disclosure.type === `Header`"
                        >
                          <div class="row">
                            <h2 class="col-12">{{ disclosure.question }}</h2>
                          </div>
                        </div>
                        <!-- header : END -->

                        <!-- DynamicInputField : START -->
                        <div
                          class="col-md-3 col-3 pt-25 pb-25"
                          :key="`disclosure_div_${index}`"
                          :class="disclosure.css_class"
                          v-if="disclosure.type === `DynamicInputField`"
                        >
                          <div class="row">
                            <div class="col-md-12">
                              {{ disclosure.question }}
                            </div>
                            <div class="col-md-12">
                              <input
                                type="text"
                                @change="
                                  checkForPrefilledTemplate(disclosures[index])
                                "
                                v-model="disclosures[index].answer"
                                class="form-control"
                              />
                            </div>
                          </div>
                        </div>
                        <!-- DynamicInputField : END -->

                        <!-- switch : START -->
                        <div
                          class="col-md-4 col-4 pt-50 pb-50"
                          :class="disclosure.css_class"
                          :key="`disclosure_div_${index}`"
                          v-if="disclosure.type === `Switch`"
                        >
                          <div class="row">
                            <div class="col-md-12">
                              {{ disclosure.question }}
                            </div>
                            <div class="col-md-12">
                              <div
                                class="btn-group"
                                role="group"
                                :aria-label="disclosure.question"
                              >
                                <button
                                  @click="
                                    disclosures[index].answer = option;
                                    checkForPrefilledTemplate(
                                      disclosures[index]
                                    );
                                  "
                                  type="button"
                                  class="btn"
                                  :class="
                                    switchButtonColor(
                                      option,
                                      disclosures[index].answer
                                    )
                                  "
                                  v-for="option in disclosure.options"
                                  :key="`disclosure_div_${index}_${option}`"
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
                                disclosure.has_additional_information === '1' &&
                                disclosures[index].answer === 'Yes'
                              "
                            >
                              <input
                                type="text"
                                v-model="
                                  disclosures[index].additional_information
                                "
                                :placeholder="
                                  disclosures[index]
                                    .additional_information_placeholder
                                "
                                class="form-control"
                              />
                            </div>
                          </div>
                        </div>
                        <!-- switch : END -->
                      </template>
                      <!-- loop through disclosures : END -->
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <inventory-disclosure-template :elementId="'trade_in_disclosure'" />
    </section>
  </div>
</template>
<script>
import vSelect from "vue-select";
import Toast from "../../../library/Toast";
import InventoryDisclosureTemplate from "../../Inventory/InventoryDisclosureTemplate.vue";
import InventoryDisclosureTemplateCTA from "../../Inventory/InventoryDisclosureTemplateCTA.vue";

export default {
  name: "DealTradeInDisclosure",
  components: {
    "v-select": vSelect,
    InventoryDisclosureTemplateCTA,
    InventoryDisclosureTemplate,
  },
  data() {
    return {
      errors: [],
      default_trade_in_inventory_disclosure_template: [],
      disclosures: [],
      disclosure_templates: [],
      create_view: false,
      disclosure_template_form: {
        description: "",
        favourite: "0",
      },
      disclosure_template_errors: [],
    };
  },
  computed: {
    updateView() {
      return this.$route.params.id && this.$route.name == "deal-update";
    },
  },
  methods: {
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
        text: "Please confirm if you want to delete the trade-in disclosures.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          (async () => {
            await vm.bulkDeleteDisclosures();
            //reset to defaults
            vm.disclosures = JSON.parse(
              JSON.stringify(vm.default_trade_in_inventory_disclosure_template)
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
          `/api/business/deals/${this.$route.params.id}/tradein/common/disclosures/delete`,
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
            if (
              element.key == "default_trade_in_inventory_disclosure_template"
            ) {
              this.default_trade_in_inventory_disclosure_template = JSON.parse(
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

          let request_url = `${window.location.origin}/api/business/deals/${this.$route.params.id}/tradein/common/disclosures`;
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
            Event.fire("save_deal_trade_in_disclosures_complete", []);
          }, 2000);
        }
      }
    },
    async getDisclosures(deal_id) {
      const vm = this;
      await axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/deals/" +
          deal_id +
          "/tradein/common/disclosures",
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
            JSON.stringify(vm.default_trade_in_inventory_disclosure_template)
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

    Event.listen("save_deal_trade_in_disclosures", (data) => {
      vm.saveDisclosures();
    });

    Event.listen("add_prefilled_template_trade_in_disclosure", (template) => {
      vm.addPrefilledTemplate(template);
    });
  },
  beforeDestroy() {
    Event.vue.$off("save_deal_trade_in_disclosures_complete", null);
  },
};
</script>
