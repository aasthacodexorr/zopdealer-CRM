<template>
  <div
      class="tab-pane"
      id="deal-jacket-panel"
      aria-labelledby="Deal Jacket"
      role="tabpanel"
  >
    <!-- Deal Jacket Main Section : START -->
    <!-- form layout section start -->
    <section id="deal-jacket-section-main">
      <div class="row match-height">
        <div class="col-md-12 col-12">
          <div class="card shadow-none mb-0">
            <div class="card-content">
              <div class="card-body">
                <form class="form form-horizontal webapp-form">
                  <div class="form-body">
                    <small role="alert" class="alert alert-light-danger mb-50 fade show d-block">
                      Deal Jacket is currently in beta. Features and functionality may change in future releases.
                    </small>
                    <small role="alert" class="alert alert-light-warning mb-50 fade show d-block">
                      All documents saved in the Deal Jacket are securely encrypted in storage. Links generated for viewing documents are temporary and will expire after 15 minutes.
                    </small>
                    <!-- Deal Jacket Un Categorized Documents : START -->
                    <div class="table-responsive">
                      <table
                          class="table table-bordered table-hover table-sm deal-jacket-table mb-2"
                      >
                        <thead>
                        <tr class="bg-light">
                          <th colspan="5" class="section-header bg-light">
                            <div
                                class="d-flex justify-content-between align-items-center"
                            >
                                <span class="font-weight-bold section-title">
                                  UNCATEGORIZED DOCUMENTS
                                </span>

                              <div>
                                <input
                                    type="file"
                                    :ref="
                                      `fileInput_UNCATEGORIZED_DOCUMENTS_${getUncategorizedDocumentsCount}`
                                    "
                                    style="display:none"
                                    @change="
                                      addDealJacketDocument(
                                        $event,
                                        'UNCATEGORIZED_DOCUMENTS',
                                        `uncategorized_document_${getUncategorizedDocumentsCount}`,
                                      )
                                    "
                                />
                                <button
                                    :disabled="!is_loaded"
                                    type="button"
                                    class="btn btn-md btn-dark"
                                    title="Upload Document"
                                    @click="
                                      $refs[
                                        `fileInput_UNCATEGORIZED_DOCUMENTS_${getUncategorizedDocumentsCount}`
                                      ].click()
                                    "
                                >
                                  <i class="text-white bx bx-upload"></i>
                                  Upload
                                </button>
                              </div>
                            </div>
                          </th>
                        </tr>

                        <tr>
                          <th style="width: 100px;" class="text-center"></th>

                          <th>
                            File Name
                          </th>
                          <th>
                            Has Tagging
                          </th>
                          <th>
                            Tagged Pages
                          </th>

                          <th class="text-center" style="width: 120px;">
                            Action
                          </th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr
                            v-for="document in getUncategorizedDocuments"
                            :key="`UNCATEGORIZED_DOCUMENTS_${document.id}`"
                        >
                          <td class="text-center align-middle">
                            <img
                                src="https://placehold.co/55x70?text=FILE"
                                alt="Document Preview"
                                class="border"
                                style="
             width: 40px;
  height: 50px;
  object-fit: cover;
            "
                            />
                          </td>

                          <td class="align-middle">
                            {{ document.source }}
                          </td>
                          <td class="align-middle">
                            {{ document.has_tagging == "1" ? "YES" : "NO" }}
                          </td>
                          <td class="align-middle">
                            <template v-if="document.tagging">
                            <div :key="`tag_${tag_index}`" v-for="(tag, tag_index) in document.tagging">{{tag.label}}  <span class="float-right font-weight-bold">{{tag.pages.join(', ')}}</span></div>
                            </template>
                          </td>

                          <td
                              class="text-center align-middle"
                              style="min-width:400px;"
                          >
                            <button
                                :disabled="!is_loaded"
                                @click.prevent="startTagging(document.id)"
                                v-show="document.file_extension === 'pdf'"
                                type="button"
                                class="btn btn-md btn-outline-primary"
                                title="Tag Document"
                            >
                              <i class="bx bx-purchase-tag"></i>
                              Tag
                            </button>
                            <button
                                @click.prevent="
                                  getDocumentWithUnEncryptedURL(document.id)
                                "
                                type="button"
                                class="btn btn-md btn-outline-primary mr-50"
                                title="View Document"
                            >
                              <i class="bx bx-show"></i>
                              View
                            </button>
                            <button
                                :disabled="!is_loaded"
                                type="button"
                                class="btn btn-md btn-outline-danger mr-50"
                                title="View Document"
                                @click.prevent="
                                  confirmDeleteAction(
                                    document.id,
                                    document.source,
                                  )
                                "
                            >
                              <i class="bx bx-trash"></i>
                              Delete
                            </button>
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                    <!-- Deal Jacket Un Categorized Documents : END -->

                    <!-- Deal Jacket Organizer : START -->
                    <div class="table-responsive">
                      <table
                          class="table table-bordered table-hover table-sm deal-jacket-table mb-2"
                          v-for="(section,
                        section_index) in getCurrentDealJacketTemplateBasedDocuments"
                          :key="
                          `deal_jacket_organizer_template_section_${section_index}`
                        "
                      >
                        <thead>
                        <tr class="bg-light">
                          <th colspan="5" class="section-header bg-light">
                            <div
                                class="d-flex justify-content-between align-items-center"
                            >
                                <span class="font-weight-bold section-title">
                                  {{ section.title }}
                                </span>
                            </div>
                          </th>
                        </tr>
                        <tr>
                          <th style="width: 140px;">Document Type</th>
                          <th style="width: 140px;">Requirement</th>
                          <th style="width: 140px;">Document Status</th>
                          <th style="width: 200px;" class="text-center">
                            Action
                          </th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr
                            v-for="(document,
                            document_index) in section.documents"
                            :key="`document_${section_index}_${document_index}`"
                        >

                          <td>{{ document.label }}
                            <i
                                v-show="document.source"
                                :title="document.source"
                                class="float-right bx bx-sm bx-info-circle text-secondary"
                            ></i>
                          </td>
                          <td class="text-left" style="min-width:150px;">
                            <i
                                :class="
                                    switchBadgeBasedOnRequirement(
                                      document.requirement_type,
                                    )
                                  "
                            ></i>
                            <span class="text-uppercase">
                                  {{ document.requirement_type }}
                                </span>
                          </td>
                          <td class="text-center"></td>
                          <td class="text-center">
                            <template v-if="document.url">
                              <button
                                  @click.prevent="
                                      getDocumentWithUnEncryptedURL(
                                        document.deal_jacket_document_id,
                                      )
                                    "
                                  type="button"
                                  class="btn btn-md btn-outline-primary mr-50"
                                  title="View Document"
                              >
                                <i class="bx bx-show"></i>
                                View
                              </button>
                              <button
                                  :disabled="!is_loaded"
                                  @click.prevent="
                                      confirmDeleteAction(
                                        document.deal_jacket_document_id,
                                        document.label,
                                      )
                                    "
                                  type="button"
                                  class="btn btn-md btn-outline-danger mr-50"
                                  title="View Document"
                              >
                                <i class="bx bx-trash"></i>
                                Delete
                              </button>
                            </template>

                            <template v-else>
                              <input
                                  type="file"
                                  :ref="`fileInput_${document.document_type}`"
                                  style="display:none"
                                  @change="
                                      addDealJacketDocument(
                                        $event,
                                        section.section_type,
                                        document.document_type,
                                      )
                                    "
                              />
                              <button
                                  :disabled="!is_loaded"
                                  type="button"
                                  class="btn btn-md btn-primary"
                                  title="Upload Document"
                                  @click="
                                      $refs[
                                        `fileInput_${document.document_type}`
                                      ][0].click()
                                    "
                              >
                                <i class="bx bx-upload"></i>
                                Upload
                              </button>
                            </template>
                          </td>
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
    <!-- Deal Jacket Main Section : END -->
    <document-tagger :available_document_types="getDocumentTypeListBasedOnTemplate"></document-tagger>
  </div>
</template>
<script>
import vSelect from "vue-select";
import Toast from "../../../library/Toast";
import DocumentTagger from "../../WebComponents/DocumentTagger.vue";

export default {
  name: "DealJacket",
  props: {
    allUsers: {required: true},
    app_metadata: {required: true},
    time_zone: String,
  },
  data() {
    return {
      is_loaded: true,
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
      deal_jacket_documents: [],
      errors: [],
    };
  },
  components: {
    DocumentTagger,
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
    getUncategorizedDocumentsCount() {
      return (
          this.deal_jacket_documents.filter(
              (uploaded_document) =>
                  uploaded_document.section_type === "UNCATEGORIZED_DOCUMENTS",
          )?.length ?? 0
      );
    },
    getUncategorizedDocuments() {
      return this.deal_jacket_documents
          .filter(
              (uploaded_document) =>
                  uploaded_document.section_type === "UNCATEGORIZED_DOCUMENTS",
          )
          .map((uploaded_document) => ({
            ...uploaded_document,
            tagging: this.$helpers.isValidJsonString(uploaded_document.tagging) ? JSON.parse(uploaded_document.tagging).map((document) => (
                {
                  ...document,
                  label: this.getDocumentTypeListBasedOnTemplate.filter(available_document => available_document.document_type === document.document_type && available_document.section_type === document.section_type)?.[0]?.label || '',
                }
            )) : null,
            file_extension: uploaded_document.url
                .split(".")
                .pop()
                .toLowerCase(),
          }));
    },
    getDocumentTypeListBasedOnTemplate() {
      return this.getCurrentDealJacketTemplateBasedDocuments.flatMap(section => section.documents || []);
    },
    getCurrentDealJacketTemplateBasedDocuments() {
      let current_deal_jacket_organizer_template = this.form
          .deal_jacket_organizer_template;

      current_deal_jacket_organizer_template = current_deal_jacket_organizer_template.map(
          (section) => ({
            ...section,
            documents: section.documents.map((document) => ({
              ...document,
              source:
                  this.deal_jacket_documents.filter(
                      (uploaded_document) =>
                          uploaded_document.section_type === section.section_type &&
                          uploaded_document.type === document.document_type,
                  )?.[0]?.source ?? null,
              deal_jacket_document_id:
                  this.deal_jacket_documents.filter(
                      (uploaded_document) =>
                          uploaded_document.section_type === section.section_type &&
                          uploaded_document.type === document.document_type,
                  )?.[0]?.id ?? null,
              url:
                  this.deal_jacket_documents.filter(
                      (uploaded_document) =>
                          uploaded_document.section_type === section.section_type &&
                          uploaded_document.type === document.document_type,
                  )?.[0]?.url ?? null,
            })),
          }),
      );

      return current_deal_jacket_organizer_template;
    },
  },
  methods: {
    async getDealJacketTemplate() {
      try {
        let { data } = await axios({
          method: "get",
          url: `${window.location.origin}/api/business/deals/${this.$route.params.id}/deal-jacket/template`,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
        });

        this.form["deal_jacket_organizer_template"] = data;
      } catch (error) {
        console.log(error);
      }
    },
    startTagging(document_id) {
      this.getDocumentWithUnEncryptedURL(document_id, true);
    },
    getDocumentWithUnEncryptedURL(document_id, tagging = false) {
      const toast = new Toast(`Generating Encrypted URL of Document...`);
      toast.fire();

      const vm = this;
      axios({
        method: "get",
        url: `${window.location.origin}/api/business/deals/${this.$route.params.id}/deal-jacket/documents/show/${document_id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
          .then(({data}) => {
            if (tagging) {
              Event.fire("document_tagging_initialize", data);
            } else {
              if (data?.url) {
                window.open(data.url, "_blank");
              }
            }

            toast.success(`Encrypted URL has been generated successfully.`);
            toast.close();
          })
          .catch((response) => {
            let error = "We couldn't generate the Encrypted URL of Document";
            toast.fail(error);
            toast.close();
          });
    },
    addDealJacketDocument(
        event,
        section_type,
        type,
        has_tagging = false,
        tagging = null,
    ) {
      let request_url = `${window.location.origin}/api/business/deals/${this.$route.params.id}/deal-jacket/documents/create`;

      const file = event.target.files[0];
      if (!file) {
        return;
      }

      this.is_loaded = false;

      let message = `Uploading a new document ...`;
      const toast = new Toast(message);
      toast.fire();

      const formData = new FormData();
      formData.append("file", file);
      formData.append("type", type);
      formData.append("section_type", section_type);

      if (has_tagging && tagging) {
        formData.append("has_tagging", 1);

        if (Array.isArray(tagging)) {
          formData.append("tagging", JSON.stringify(tagging));
        }
      }

      axios({
        url: request_url,
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
          .then(({data}) => {
            let success_message =
                data?.success ?? "Document uploaded successfully";
            toast.success(success_message);
            toast.close();
            this.is_loaded = true;
            this.getDealJacketDocuments();
          })
          .catch((error) => {
            console.log(error);
            let error_message =
                error?.response?.data?.file ??
                "There were some errors while uploading the file.";
            toast.fail(error_message);
            toast.close();
            this.is_loaded = true;
          });
    },
    getDealJacketDocuments() {
      let request_url = `${window.location.origin}/api/business/deals/${this.$route.params.id}/deal-jacket`;
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
          .then(({data}) => {
            this.deal_jacket_documents = data;
          })
          .catch((e) => {
            this.deal_jacket_documents = [];
            console.log(e);
          });
    },
    updateDocumentTagging(
        document_id,
        tagging
    ) {
      let request_url = `${window.location.origin}/api/business/deals/${this.$route.params.id}/deal-jacket/documents/update/tagging/${document_id}`;

      let message = `Updating the tagging information. This might take few minutes for larger files ...`;
      const toast = new Toast(message);
      toast.fire();

      const formData = new FormData();
      if (Array.isArray(tagging)) {
        formData.append("tagging", JSON.stringify(tagging));
      }

      axios({
        url: request_url,
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
          .then(({data}) => {
            let success_message =
                data?.success ?? "Document has been updated successfully";
            toast.success(success_message);
            toast.close();

            Event.fire('document_tagging_cleanup', null);
            this.is_loaded = false;
            return this.getDealJacketDocuments();
          })
          .then(() => {
            this.is_loaded = true;
          })
          .catch((error) => {
            console.log(error);
            let error_message =
                error?.response?.data ??
                "There were some errors while updating the tagging information.";
            toast.fail(error_message);
            toast.close();
            this.is_loaded = true;
          });
    },
    confirmDeleteAction(id, document_label) {
      const vm = this;
      this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Confirm",
        toast: false,
        position: "center",
        text: `Please confirm if you want to delete the ${document_label}. Document will be permanently deleted.`,
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.dealJacketDocumentDelete(id, document_label);
        }
      });
    },
    dealJacketDocumentDelete(id, document_label) {
      let url = `${window.location.origin}/api/business/deals/${this.$route.params.id}/deal-jacket/documents/delete/${id}`;

      let message = `Deleting ${document_label} document ...`;
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url,
      })
          .then(({data}) => {
            let success_message = `${document_label} has been deleted.`;
            toast.success(success_message);
            toast.close();

            this.getDealJacketDocuments();
          })
          .catch(({error}) => {
            let error_message = `There was some error while deleting ${document_label}`;
            toast.fail(error_message);
            toast.close();
          });
    },
    switchBadgeBasedOnRequirement(label) {
      switch (label.toUpperCase()) {
        case "REQUIRED":
          return `bx bx-check-circle text-success mr-25`;

        case "OPTIONAL":
          return `bx bx-minus-circle text-muted mr-25`;

        default:
          return `bx bx-help-circle text-warning mr-25`;
      }
    },
  },
  async mounted() {
    this.is_loaded = false;
    await this.getDealJacketTemplate();
    await this.getDealJacketDocuments();
    this.is_loaded = true;

    Event.listen(
        "document_tagging_save",
        ({document_id, tagging}) => {
          this.updateDocumentTagging(document_id, tagging);
        }
    );
  },
  beforeDestroy() {
    Event.vue.$off("document_tagging_save", null);
  }
};
</script>
<style lang="scss">
.deal-jacket-section-main th,
.deal-jacket-section-main td {
  padding: 0.45rem 0.6rem;
  vertical-align: middle;
}

.deal-jacket-section-main .section-header {
  padding: 0.4rem 0.6rem;
}

.deal-jacket-section-main .section-title {
  font-size: 0.95rem;
}

.deal-jacket-section-main .badge {
  font-size: 0.72rem;
  padding: 0.3rem 0.45rem;
}

.deal-jacket-section-main .v-select {
  background: white;
}
</style>
