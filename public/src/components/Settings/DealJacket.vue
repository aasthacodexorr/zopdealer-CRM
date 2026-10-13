<template>
  <div v-if="tenantApp && isAuthorized">
    <div class="app-content content fixed-height-block">
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <router-link
              title="Back To Settings"
              tag="a"
              :to="{ name: 'settings-account-v2' }"
              class="nav-link pr-0 pl-0 d-flex align-items-center"
              exact
            >
              <i class="bx bx-caret-left" style="font-size: 1.4em"></i>
            </router-link>
          </li>
          <li class="nav-item">
            <a
              class="nav-link d-flex align-items-center active"
              id="deal-jacket-template"
              data-toggle="tab"
              href="#deal-jacket-template"
              aria-controls="Deal Jacket Template"
              role="tab"
              aria-selected="true"
            >
              <span class="">Deal Jacket Template</span>
            </a>
          </li>
          <div class="d-none btn-feild-box">
            <button
              class="btn btn-primary mr-1"
              @click.prevent="saveConfiguration"
            >
              Save
            </button>
          </div>
        </ul>
      </div>
    </div>
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="">
        <div class="content-body">
          <!-- form layout section start -->
          <div class="tab-content">
            <!-- single tab content goes here : START -->
            <div
              class="tab-pane active"
              id="deal-jacket-template-tab"
              aria-labelledby="deal-jacket-template"
              role="tabpanel"
            >
              <!-- form layout section start -->
              <section id="deal-jacket-template-section">
                <div class="row match-height">
                  <div class="col-md-12 col-12">
                    <div class="card shadow-none mb-0">
                      <div class="card-content">
                        <div class="card-body">
                          <form class="form form-horizontal webapp-form">
                            <div class="form-body" v-show="!submit">
                              Loading Saved Configurations ...
                            </div>
                            <div class="form-body" v-show="submit">
                              <!-- Deal Jacket Organizer : START -->
                              <div class="table-responsive">
                                <table
                                  class="table table-bordered table-hover table-sm deal-jacket-table mb-2"
                                  v-for="(section,
                                  section_index) in form.deal_jacket_organizer_template"
                                  :key="
                                    `deal_jacket_organizer_template_section_${section_index}`
                                  "
                                >
                                  <thead>
                                    <tr class="bg-light">
                                      <th colspan="4" class="section-header">
                                        <div
                                          class="d-flex justify-content-between align-items-center"
                                        >
                                          <span
                                            class="font-weight-bold section-title"
                                          >
                                            {{ section.title }}
                                          </span>

                                          <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            :disabled="editMode"
                                            v-show="
                                              section.documents.length <
                                                available_documents.filter(
                                                  (mapper) =>
                                                    mapper.section_type ===
                                                    section.section_type,
                                                ).length
                                            "
                                            @click.prevent="
                                              addDocumentInSection(
                                                section_index,
                                                section.section_type,
                                              )
                                            "
                                          >
                                            <i class="bx bx-plus"></i>
                                            Add
                                          </button>
                                        </div>
                                      </th>
                                    </tr>

                                    <tr>
                                      <th>Document Type</th>
                                      <th style="width: 140px;">Requirement</th>
                                      <th style="width: 220px;">Mapper</th>
                                      <th
                                        style="width: 70px;"
                                        class="text-center"
                                      >
                                        Action
                                      </th>
                                    </tr>
                                  </thead>

                                  <tbody>
                                    <tr
                                      v-for="(document,
                                      document_index) in section.documents"
                                      :key="
                                        `document_${section_index}_${document_index}`
                                      "
                                    >
                                      <template
                                        v-if="
                                          edit_section.section_index ===
                                            section_index &&
                                            edit_section.document_index ===
                                              document_index
                                        "
                                      >
                                        <!-- Add Document Form Row -->
                                        <td>
                                          <v-select
                                            placeholder="Document Type"
                                            label="label"
                                            :class="{
                                              'is-invalid':
                                                errors[section_index] &&
                                                errors[section_index][
                                                  document_index
                                                ] &&
                                                errors[section_index][
                                                  document_index
                                                ].document_type !== undefined,
                                            }"
                                            :reduce="
                                              (document_mapper) =>
                                                document_mapper.type
                                            "
                                            v-model="
                                              form
                                                .deal_jacket_organizer_template[
                                                section_index
                                              ].documents[document_index]
                                                .document_type
                                            "
                                            clearable
                                            :options="
                                              available_documents
                                                .filter(
                                                  (mapper) =>
                                                    mapper.section_type ===
                                                    section.section_type,
                                                )
                                                .filter(
                                                  (mapper) =>
                                                    !section.documents
                                                      .filter(
                                                        (
                                                          document,
                                                          current_document_index,
                                                        ) =>
                                                          current_document_index !=
                                                          edit_section.document_index,
                                                      )
                                                      .map(
                                                        (document) =>
                                                          document.document_type,
                                                      )
                                                      .includes(mapper.type),
                                                )
                                            "
                                            @input="
                                              changedDocument(
                                                form
                                                  .deal_jacket_organizer_template[
                                                  section_index
                                                ].documents[document_index],
                                                $event,
                                              );
                                              deleteErrorMessage(
                                                section_index,
                                                document_index,
                                                'document_type',
                                              );
                                              deleteErrorMessage(
                                                section_index,
                                                document_index,
                                                'mapper_id',
                                              );
                                            "
                                            :title="
                                              getErrorTitle(
                                                section_index,
                                                document_index,
                                                'document_type',
                                              )
                                            "
                                          ></v-select>
                                        </td>

                                        <td>
                                          <select
                                            class="form-control form-control-sm"
                                            name="requirement_type"
                                            v-model="
                                              form
                                                .deal_jacket_organizer_template[
                                                section_index
                                              ].documents[document_index]
                                                .requirement_type
                                            "
                                            :class="{
                                              'is-invalid':
                                                errors[section_index] &&
                                                errors[section_index][
                                                  document_index
                                                ] &&
                                                errors[section_index][
                                                  document_index
                                                ].requirement_type !==
                                                  undefined,
                                            }"
                                            @change="
                                              deleteErrorMessage(
                                                section_index,
                                                document_index,
                                                'requirement_type',
                                              )
                                            "
                                            :title="
                                              getErrorTitle(
                                                section_index,
                                                document_index,
                                                'requirement_type',
                                              )
                                            "
                                          >
                                            <option value="" disabled
                                              >Select</option
                                            >
                                            <option value="REQUIRED"
                                              >Required</option
                                            >
                                            <option value="OPTIONAL"
                                              >Optional</option
                                            >
                                            <option value="CONDITIONAL"
                                              >Conditional</option
                                            >
                                          </select>
                                        </td>

                                        <td>
                                          <select
                                            class="form-control form-control-sm"
                                            name="mapper_id"
                                            v-model="
                                              form
                                                .deal_jacket_organizer_template[
                                                section_index
                                              ].documents[document_index]
                                                .mapper_id
                                            "
                                            :class="{
                                              'is-invalid':
                                                errors[section_index] &&
                                                errors[section_index][
                                                  document_index
                                                ] &&
                                                errors[section_index][
                                                  document_index
                                                ].mapper_id !== undefined,
                                            }"
                                            @input="
                                              deleteErrorMessage(
                                                section_index,
                                                document_index,
                                                'mapper_id',
                                              )
                                            "
                                            :title="
                                              getErrorTitle(
                                                section_index,
                                                document_index,
                                                'mapper_id',
                                              )
                                            "
                                          >
                                            <option disabled value=""
                                              >Select Mapper</option
                                            >
                                            <option
                                              :value="mapper.id"
                                              v-for="mapper in available_mappers.filter(
                                                (mapper) =>
                                                  mapper.section_type ===
                                                  section.section_type,
                                              )"
                                              :key="`mapper_${mapper.id}`"
                                              >{{ mapper.label }}</option
                                            >
                                          </select>
                                        </td>

                                        <td
                                          class="text-center"
                                          style="min-width:200px;"
                                        >
                                          <button
                                            type="button"
                                            class="btn btn-sm btn-primary mx-50 p-50"
                                            title="Save"
                                            @click.prevent="validateAndSave"
                                          >
                                            <i class="bx bx-check"></i>
                                          </button>

                                          <button
                                            type="button"
                                            class="btn btn-sm btn-outline-secondary"
                                            title="Cancel"
                                            @click.prevent="
                                              removeDocumentInSectionAndSave(
                                                section_index,
                                                document_index,
                                              )
                                            "
                                          >
                                            <i class="bx bx-x"></i>
                                          </button>
                                        </td>
                                      </template>
                                      <template v-else>
                                        <td>{{ document.label }}</td>
                                        <td>
                                          <span class="text-uppercase">
                                            {{ document.requirement_type }}
                                          </span>
                                        </td>
                                        <td>
                                          <select
                                            class="form-control form-control-sm"
                                            name="mapper_id"
                                            disabled
                                            v-model="
                                              form
                                                .deal_jacket_organizer_template[
                                                section_index
                                              ].documents[document_index]
                                                .mapper_id
                                            "
                                          >
                                            <option disabled value=""
                                              >Select Mapper</option
                                            >
                                            <option
                                              :value="mapper.id"
                                              v-for="mapper in available_mappers.filter(
                                                (mapper) =>
                                                  mapper.section_type ===
                                                  section.section_type,
                                              )"
                                              :key="
                                                `mapper_${mapper.id}_${document.requirement_type}`
                                              "
                                              >{{ mapper.label }}</option
                                            >
                                          </select>
                                        </td>

                                        <td
                                          class="text-center"
                                          style="min-width:200px;"
                                        >
                                          <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary mx-50"
                                            title="Edit"
                                            :disabled="editMode"
                                            @click.prevent="
                                              editDocument(
                                                section_index,
                                                document_index,
                                              )
                                            "
                                          >
                                            <i class="bx bx-edit"></i>
                                          </button>
                                          <button
                                            type="button"
                                            class="btn btn-sm btn-outline-secondary"
                                            title="Cancel"
                                            @click.prevent="
                                              removeDocumentInSectionAndSave(
                                                section_index,
                                                document_index,
                                              )
                                            "
                                          >
                                            <i class="bx bx-x"></i>
                                          </button>
                                        </td>
                                      </template>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                              <!-- Deal Jacket Organizer : END -->
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
            <!-- single tab content goes here : END -->
          </div>
        </div>
      </div>
    </div>
    <!-- END: Content-->
  </div>
</template>
<script>
import vSelect from "vue-select";
import ConfigurationMixin from "../../mixins/configuration";
import Toast from "../../library/Toast";
export default {
  name: "DealJacket",
  props: ["application", "app_metadata"],
  data() {
    return {
      edit_section: {
        section_index: null,
        document_index: null,
      },
      form: {
        deal_jacket_organizer_template: [
          {
            section_type: "GENERAL_DOCUMENTS",
            title: "GENERAL DOCUMENTS",
            documents: [],
          },
          {
            section_type: "RETAIL_CASH",
            title: "RETAIL DEAL - CASH",
            documents: [],
          },
          {
            section_type: "RETAIL_FINANCE",
            title: "RETAIL DEAL - FINANCE",
            documents: [],
          },
          {
            section_type: "WHOLESALE",
            title: "WHOLESALE DOCUMENTS",
            documents: [],
          },
          {
            section_type: "CO_APPLICANT",
            title: "CO-APPLICANT DOCUMENTS",
            documents: [],
          },
          {
            section_type: "TRADE_IN",
            title: "TRADE-IN DOCUMENTS",
            documents: [],
          },
          {
            section_type: "FEES",
            title: "FEE DOCUMENTS",
            documents: [],
          },
          {
            section_type: "WARRANTY",
            title: "WARRANTY DOCUMENTS",
            documents: [],
          },
          {
            section_type: "INSURANCE",
            title: "INSURANCE DOCUMENTS",
            documents: [],
          },
          {
            section_type: "PAYMENT",
            title: "PAYMENT DOCUMENTS",
            documents: [],
          },
          {
            section_type: "MISCELLANEOUS",
            title: "MISCELLANEOUS DOCUMENTS",
            documents: [],
          },
        ],
      },
      errors: [],
      available_documents: [
        {
          type: "DRIVER_LICENCE",
          label: "Driver Licence",
          section_type: "GENERAL_DOCUMENTS",
          default_mapper_id: "default_driver_licence",
        },
        {
          type: "SAFETY_INSPECTION",
          label: "Safety Standards Certificate",
          section_type: "GENERAL_DOCUMENTS",
          default_mapper_id: "default_safety_inspection",
        },
        {
          type: "BILL_OF_SALE_MAIN_APPLICANT_RETAIL_CASH",
          label: "Retail Bill Of Sale (Applicant)",
          section_type: "RETAIL_CASH",
          default_mapper_id: "default_bill_of_sale_main_applicant_retail_cash",
        },
        {
          type: "BILL_OF_SALE_MAIN_APPLICANT_RETAIL_FINANCE",
          label: "Retail Bill Of Sale (Applicant)",
          section_type: "RETAIL_FINANCE",
          default_mapper_id:
            "default_bill_of_sale_main_applicant_retail_finance",
        },
        {
          type: "SALES_CONTRACT",
          label: "Conditional Sales Contract",
          section_type: "RETAIL_FINANCE",
          default_mapper_id: "default_sales_contract",
        },
        {
          type: "CO_APPLICANT_IDENTIFICATION_DOCUMENTS",
          label: "Co-Applicant IDs",
          section_type: "CO_APPLICANT",
          default_mapper_id: "default_co_applicant_identification_documents",
        },
        {
          type: "DT_PRIVACY_CONSENT",
          label: "DT Privacy Consent",
          section_type: "RETAIL_FINANCE",
          default_mapper_id: "default_dealertrack_privacy_consent",
        },
        {
          type: "BANK_CREDIT_APPLICATION",
          label: "Bank Credit Application",
          section_type: "RETAIL_FINANCE",
          default_mapper_id: "default_bank_credit_application",
        },
        {
          type: "ANTITHEFT_FEE",
          label: "Antitheft",
          section_type: "FEES",
          default_mapper_id: "default_antitheft_fee",
        },
        {
          type: "ADDITIONAL_PACKAGE_FEE",
          label: "Additional Packages",
          section_type: "FEES",
          default_mapper_id: "default_additional_package_fee",
        },
        {
          type: "TIRE_AND_RIM_WARRANTY_FEE",
          label: "Tire and Rim Warranty",
          section_type: "FEES",
          default_mapper_id: "default_tire_and_rim_warranty_fee",
        },
        {
          type: "GAP",
          label: "GAP Insurance",
          section_type: "INSURANCE",
          default_mapper_id: "default_gap_insurance",
        },
        {
          type: "WARRANTY",
          label: "Extended Warranty",
          section_type: "WARRANTY",
          default_mapper_id: "default_warranty",
        },
        {
          type: "VOID_CHEQUE",
          label: "Void Cheque",
          section_type: "PAYMENT",
          default_mapper_id: "default_void_cheque",
        },
        {
          type: "PROOF_OF_PAYMENT",
          label: "Proof of Payment (Deposit/Balance)",
          section_type: "PAYMENT",
          default_mapper_id: "default_proof_of_payment",
        },
        {
          type: "FUNDING_SHEET",
          label: "Funding Sheet",
          section_type: "PAYMENT",
          default_mapper_id: "default_funding_sheet",
        },
        {
          type: "OWNERSHIP_DOCUMENTS",
          label: "Ownership (Signed) Documents",
          section_type: "MISCELLANEOUS",
          default_mapper_id: "default_ownership_documents",
        },
        {
          type: "OWNERSHIP_HISTORY_DOCUMENTS",
          label: "Ownership History (Flips)",
          section_type: "MISCELLANEOUS",
          default_mapper_id: "default_ownership_history_documents",
        },
        {
          type: "PROOF_OF_INSURANCE",
          label: "Insurance Slip (POI)",
          section_type: "INSURANCE",
          default_mapper_id: "default_proof_of_insurance",
        },
        {
          type: "THIRD_PARTY_APPLICATION",
          label: "Third Party Application",
          section_type: "GENERAL_DOCUMENTS",
          default_mapper_id: "default_third_party_application",
        },
        {
          type: "CARFAX_REPORT",
          label: "Carfax Report",
          section_type: "GENERAL_DOCUMENTS",
          default_mapper_id: "default_carfax_report",
        },
        {
          type: "CARFAX_ACKNOWLEDGMENT",
          label: "Carfax Acknowledgment",
          section_type: "GENERAL_DOCUMENTS",
          default_mapper_id: "default_carfax_acknowledgment",
        },
        {
          type: "UCDA_AUTOCHECK_TITLE_SEARCH",
          label: "UCDA Autocheck",
          section_type: "GENERAL_DOCUMENTS",
          default_mapper_id: "default_ucda_autocheck_title_search",
        },
        {
          type: "GET_READY_DOCUMENT",
          label: "Get Ready Sheet",
          section_type: "MISCELLANEOUS",
          default_mapper_id: "default_get_ready_documents",
        },
        {
          type: "ID_VERIFICATION_DOCUMENTS",
          label: "ID Verification Documents",
          section_type: "GENERAL_DOCUMENTS",
          default_mapper_id: "default_id_verification_documents",
        },
        {
          type: "ADDITIONAL_IDENTIFICATION_DOCUMENTS",
          label: "Additional IDs",
          section_type: "GENERAL_DOCUMENTS",
          default_mapper_id: "default_additional_identification_documents",
        },
        {
          type: "TRADE_OWNERSHIP_DOCUMENTS",
          label: "Trade Ownership (Signed) Documents",
          section_type: "TRADE_IN",
          default_mapper_id: "default_trade_ownership_documents",
        },
        {
          type: "TRADE_APPRAISAL_FORM",
          label: "Trade Appraisal Form",
          section_type: "TRADE_IN",
          default_mapper_id: "default_trade_appraisal_form",
        },
        {
          type: "TRADE_LIEN_PAYOUT_DOCUMENTS",
          label: "Trade Lien Payout Documents",
          section_type: "TRADE_IN",
          default_mapper_id: "default_trade_lien_payout_documents",
        },
        {
          type: "TRADE_IN_CARFAX_REPORT",
          label: "Trade-In Carfax History Report",
          section_type: "TRADE_IN",
          default_mapper_id: "default_trade_in_carfax_report",
        },
        {
          type: "TRADE_IN_CARFAX_LIEN_REPORT",
          label: "Trade-In Carfax Lien Report",
          section_type: "TRADE_IN",
          default_mapper_id: "default_trade_in_carfax_lien_report",
        },
        {
          type: "TRADE_IN_TITLE_SEARCH",
          label: "Trade-In Title Search",
          section_type: "TRADE_IN",
          default_mapper_id: "default_trade_in_title_search",
        },
        {
          type: "TRADE_IN_UCDA_AUTOCHECK_TITLE_SEARCH",
          label: "Trade UCDA Autocheck",
          section_type: "TRADE_IN",
          default_mapper_id: "default_trade_in_ucda_autocheck_title_search",
        },
        {
          type: "BILL_OF_SALE_WHOLESALE",
          label: "Wholesale Bill Of Sale",
          section_type: "WHOLESALE",
          default_mapper_id: "default_wholesale_bill_of_sale",
        },
      ],
      available_mappers: [
        {
          id: "default_driver_licence",
          label: "Default Driver Licence",
          section_type: "GENERAL_DOCUMENTS",
        },
        {
          id: "default_safety_inspection",
          label: "Default Safety Inspection",
          section_type: "GENERAL_DOCUMENTS",
        },
        {
          id: "default_bill_of_sale_main_applicant_retail_cash",
          label: "Default Bill Of Sale",
          section_type: "RETAIL_CASH",
        },
        {
          id: "default_bill_of_sale_main_applicant_retail_finance",
          label: "Default Bill Of Sale",
          section_type: "RETAIL_FINANCE",
        },
        {
          id: "default_sales_contract",
          label: "Default Sales Contract",
          section_type: "RETAIL_FINANCE",
        },
        {
          id: "default_co_applicant_identification_documents",
          label: "Default Co-Applicant IDs",
          section_type: "CO_APPLICANT",
        },
        {
          id: "default_dealertrack_privacy_consent",
          label: "Default DT Privacy Consent",
          section_type: "RETAIL_FINANCE",
        },
        {
          id: "default_bank_credit_application",
          label: "Default Bank Credit Application",
          section_type: "RETAIL_FINANCE",
        },
        {
          id: "default_gap_insurance",
          label: "Default GAP",
          section_type: "INSURANCE",
        },
        {
          id: "default_warranty",
          label: "Default Extended Warranty",
          section_type: "WARRANTY",
        },
        {
          id: "default_void_cheque",
          label: "Default Void Cheque",
          section_type: "PAYMENT",
        },
        {
          id: "default_ownership_documents",
          label: "Default Ownership Documents",
          section_type: "MISCELLANEOUS",
        },
        {
          id: "default_ownership_history_documents",
          label: "Default Ownership History (Flips)",
          section_type: "MISCELLANEOUS",
        },
        {
          id: "default_proof_of_insurance",
          label: "Default Insurance Slip (POI)",
          section_type: "INSURANCE",
        },
        {
          id: "default_third_party_application",
          label: "Default Third Party Application",
          section_type: "GENERAL_DOCUMENTS",
        },
        {
          id: "default_carfax_report",
          label: "Default Carfax Report",
          section_type: "GENERAL_DOCUMENTS",
        },
        {
          id: "default_carfax_acknowledgment",
          label: "Default Carfax Acknowledgment",
          section_type: "GENERAL_DOCUMENTS",
        },
        {
          id: "default_ucda_autocheck_title_search",
          label: "Default UCDA Autocheck",
          section_type: "GENERAL_DOCUMENTS",
        },
        {
          id: "default_get_ready_documents",
          label: "Default Get Ready Document",
          section_type: "MISCELLANEOUS",
        },
        {
          id: "default_trade_ownership_documents",
          label: "Default Trade Ownership Documents",
          section_type: "TRADE_IN",
        },
        {
          id: "default_trade_appraisal_form",
          label: "Default Trade Appraisal Form",
          section_type: "TRADE_IN",
        },
        {
          id: "default_trade_lien_payout_documents",
          label: "Default Trade Lien Payout Documents",
          section_type: "TRADE_IN",
        },
        {
          id: "default_trade_in_carfax_report",
          label: "Default Trade-In Carfax Report",
          section_type: "TRADE_IN",
        },
        {
          id: "default_trade_in_carfax_lien_report",
          label: "Default Trade-In Carfax Lien Report",
          section_type: "TRADE_IN",
        },
        {
          id: "default_trade_in_title_search",
          label: "Default Trade-In Title Search",
          section_type: "TRADE_IN",
        },
        {
          id: "default_trade_in_ucda_autocheck_title_search",
          label: "Default Trade UCDA Autocheck",
          section_type: "TRADE_IN",
        },
        {
          id: "default_wholesale_bill_of_sale",
          label: "Default Wholesale Bill Of Sale",
          section_type: "WHOLESALE",
        },
        {
          id: "default_id_verification_documents",
          label: "Default ID Verification Documents",
          section_type: "GENERAL_DOCUMENTS",
        },
        {
          id: "default_additional_identification_documents",
          label: "Default Additional IDs",
          section_type: "GENERAL_DOCUMENTS",
        },
        {
          id: "default_proof_of_payment",
          label: "Default Proof of Payment",
          section_type: "PAYMENT",
        },
        {
          id: "default_funding_sheet",
          label: "Default Funding Sheet",
          section_type: "PAYMENT",
        },
        {
          id: "default_antitheft_fee",
          label: "Default Antitheft",
          section_type: "FEES",
        },
        {
          id: "default_additional_package_fee",
          label: "Default Additional Packages",
          section_type: "FEES",
        },
        {
          id: "default_tire_and_rim_warranty_fee",
          label: "Default Tire and Rim Warranty",
          section_type: "FEES",
        }

      ],
    };
  },
  mixins: [ConfigurationMixin],
  components: {
    "v-select": vSelect,
  },
  computed: {
    tenantApp() {
      return this.application == "tenant";
    },
    mainApp() {
      return this.application == "main";
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    editMode() {
      return Object.values(this.edit_section).some((value) => value !== null);
    },
  },
  methods: {
    resetEditSection() {
      this.edit_section.section_index = null;
      this.edit_section.document_index = null;
    },
    deleteErrorMessage(section_index, document_index, attribute) {
      if (
        this.errors?.[section_index]?.[document_index]?.[attribute] !==
        undefined
      ) {
        delete this.errors[section_index][document_index][attribute];
      }
    },
    getErrorTitle(section_index, document_index, attribute) {
      if (
        this.errors?.[section_index]?.[document_index]?.[attribute] !==
        undefined
      ) {
        return this.errors[section_index][document_index][attribute];
      }

      return "";
    },
    editDocument(section_index, document_index) {
      this.edit_section.section_index = section_index;
      this.edit_section.document_index = document_index;
    },
    changedDocument(current_document, selected_option) {
      current_document.label =
        this.available_documents.filter(
          (mapper) => mapper.type === selected_option,
        )?.[0]?.label ?? "";

      current_document.mapper_id =
        this.available_documents.filter(
          (mapper) => mapper.type === selected_option,
        )?.[0]?.default_mapper_id ?? "";
    },
    removeDocumentInSectionAndSave(section_index, document_index) {
      this.form.deal_jacket_organizer_template[section_index][
        "documents"
      ].splice(document_index, 1);

      if (this.edit_section.document_index) {
        this.edit_section.document_index =
          this.form.deal_jacket_organizer_template[section_index]["documents"]
            .length - 1;
      }

      this.validateAndSave();
    },
    addDocumentInSection(section_index, section_type) {
      this.form.deal_jacket_organizer_template[section_index]["documents"].push(
        {
          document_type: "",
          label: "",
          requirement_type: "REQUIRED",
          requirement_condition: {},
          mapper_id: "",
          section_type,
        },
      );

      this.edit_section.section_index = section_index;
      this.edit_section.document_index =
        this.form.deal_jacket_organizer_template[section_index]["documents"]
          .length - 1;
    },
    async validateAndSave() {
      const vm = this;
      let formData = new FormData();
      formData.append(
        "deal_jacket_organizer_template",
        JSON.stringify(this.form.deal_jacket_organizer_template),
      );
      let request_url = `${window.location.origin}/api/business/deals/deal-jacket/template/validate`;

      const toast = new Toast(`Validating the template`);
      toast.fire();

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
          toast.success(data.success);
          toast.close();
          this.saveConfiguration();
          this.resetEditSection();
        })
        .catch(function(error) {
          vm.$data.errors = error.response.data;

          toast.fail(`There were errors in validation.`);
          toast.close();
        });
    },
  },
  async mounted() {
    this.submit = false;
    await this.getModuleConfiguration("deal");
    this.submit = true;
  },
};
</script>
<style lang="scss">
.deal-jacket-table th,
.deal-jacket-table td {
  padding: 0.45rem 0.6rem;
  vertical-align: middle;
}

.deal-jacket-table .section-header {
  padding: 0.4rem 0.6rem;
}

.deal-jacket-table .section-title {
  font-size: 0.95rem;
}

.deal-jacket-table .btn-sm {
  padding: 0.2rem 0.5rem;
  font-size: 0.75rem;
}

.deal-jacket-table .badge {
  font-size: 0.72rem;
  padding: 0.3rem 0.45rem;
}

.deal-jacket-table .v-select {
  background: white;
}
</style>
