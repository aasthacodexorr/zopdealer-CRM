<template>
  <div
      id="pdfTagModal"
      aria-hidden="true"
      class="modal fade"
      role="dialog"
      tabindex="-1"
      data-backdrop="static" data-keyboard="false"
  >
    <div
        class="modal-dialog modal-xl"
        role="document"
    >
      <div class="modal-content">
        <!-- Header -->
        <div class="modal-header">
          <div>
            <h5 class="modal-title mb-0">
              Tag Document
            </h5>
            <small
                id="pdfFileName"
                class="text-muted"
            >{{ source }}</small>
          </div>
          <button
              class="close"
              data-dismiss="modal"
              type="button"
          >
            <span>&times;</span>
          </button>

        </div>
        <!-- Body -->
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-7 position-relative">
              <!-- Loader : START -->
              <div
                  v-show="is_pdf_page_loading"
                  class="pdf-canvas-loader"
              >
                <div
                    class="spinner-border text-primary"
                    role="status"
                >
                  <span class="sr-only">
                    Loading...
                  </span>
                </div>
                <div class="mt-1 text-muted">
                  Loading page...
                </div>
              </div>
              <!-- Loader : END -->

              <!-- MAIN PDF Viewer: START -->
              <div v-show="!is_pdf_page_loading" class="pdf-main-viewer">
                <canvas
                    id="mainPdfCanvas"
                    ref="mainPdfCanvas"
                ></canvas>
              </div>
              <!-- MAIN PDF Viewer: END -->
              <!-- Page Navigation : START -->
              <div
                  v-if="!is_pdf_page_loading"
                  class="d-flex justify-content-center align-items-center mt-1"
              >
                <button
                    v-show="current_page > 1"
                    class="btn btn-sm btn-outline-secondary"
                    type="button"
                    @click.prevent="renderMainPage(--current_page)"
                >
                  Previous
                </button>
                <strong
                    class="mx-2"
                >
                  Page {{ current_page }} of {{ pages_count }}
                </strong>
                <button
                    v-show="current_page <= pages_count"
                    class="btn btn-sm btn-outline-secondary"
                    type="button"
                    @click.prevent="renderMainPage(++current_page)"
                >
                  Next
                </button>
              </div>
              <!-- Page Navigation : END -->
            </div>
            <div class="col-lg-5" :style="{
              visibility: is_pdf_page_loading ? 'hidden' : ''
            }">
              <div
                  class="d-flex justify-content-between align-items-center mb-1"
              >
                <strong>
                  Document Pages
                </strong>
                <div>
                  <button
                      class="btn btn-sm btn-outline-primary"
                      type="button"
                      @click.prevent="selectAllPages()"
                  >
                    Select All
                  </button>
                  <button
                      class="btn btn-sm btn-outline-secondary"
                      type="button"
                      @click.prevent="clearSelectedPages()"
                  >
                    Clear
                  </button>
                </div>
              </div>
              <!-- Page Thumbnails : START -->
              <div
                  class="border rounded p-1 p-50 pdf-page-container"
              >
                <div
                    id="pageContainer"
                    class="row no-gutters"
                >
                  <!-- Thumbnail : START -->
                  <div v-for="(thumbnail, index) in thumbnails" :key="`thumbnail_${index + 1}`" class="col-3 p-25"
                       @click="renderMainPage(index + 1)">
                    <div :class="current_page === index + 1 ? 'active' : ''" class="pdf-page-card">
                      <input v-show="!getLabeledPageNumbers.includes(index + 1)" v-model="selected_pages"
                             :value="index + 1" class="pdf-page-checkbox" type="checkbox">
                      <img :alt="`Page ${index + 1}`" :src="thumbnail" class="pdf-thumbnail">
                      <div class="text-center">
                        <small>Page {{ index + 1 }}</small>
                      </div>
                      <span v-if="getLabeledPageNumbers.includes(index + 1)"
                            class="badge badge-primary page-tag">{{ getEachPageTag[index + 1].label }}</span>
                    </div>
                  </div>
                  <!-- Thumbnail : END -->
                </div>
              </div>
              <!-- Page Thumbnails : END -->
              <!-- Tagging Section : START -->
              <div class="border-top mt-2 pt-2">
                <div class="form-group mb-1">
                  <label>
                    Tag Selected Pages As
                  </label>
                  <select
                      v-model="selected_document_type"
                      id="documentType"
                      class="form-control"
                  >
                    <option :value="null">
                      Select Document Type
                    </option>
                    <option :value="document_type" v-for="document_type in getCurrentSelectableDocumentTypes"
                            :key="`document_type_${document_type.document_type}`">
                      {{ document_type.label }}
                    </option>
                  </select>
                </div>
                <div
                    class="d-flex justify-content-between align-items-center"
                >
                  <small
                      id="selectedPageCount"
                      class="text-muted"
                  >
                    {{ selected_pages.length }} pages selected
                  </small>
                  <button
                      id="tagSelectedPages"
                      class="btn btn-primary"
                      @click.prevent="tagSelectedPages()"
                      :disabled="!arePagesSelectedForTagging"
                      type="button"
                  >
                    Tag Selected
                  </button>
                </div>
              </div>
              <!-- Tagging Section : END -->
              <!-- Tagging Information : START -->
              <div class="mt-2">
                <div
                    class="d-flex justify-content-between align-items-center mb-1"
                >
                  <strong>
                    Tagged Documents
                  </strong>
                  <button
                      id="clearTags"
                      class="btn btn-sm btn-outline-danger"
                      type="button"
                      @click.prevent="clearAllTaggedPages()"
                  >
                    Clear Tags
                  </button>
                </div>
                <small
                    role="alert"
                    v-if="getCountOfTaggedDocumentsWithSource"
                    class="alert alert-light-warning mb-1 fade show d-block"
                >
                  There are {{ getCountOfTaggedDocumentsWithSource }} tagged document(s) already mapped or uploaded.
                  Please review the tags before saving.
                </small>
                <div
                    id="tagSummary"
                    class="border rounded px-50 tag-summary"
                >
                  <template v-if="getLabelsForTaggedDocuments.length > 0">
                    <div
                        v-for="(tagged_document, tagged_index) in getLabelsForTaggedDocuments"
                        :key="`tagged_document_list_${tagged_document.document_type}`"
                        class="d-flex justify-content-between border-bottom py-50">
                      <strong class="align-content-center text-left flex-grow-1">{{ tagged_document.label }}</strong>
                      <div
                          class="align-content-center text-muted mx-1">Pages {{ tagged_document.pages.join(", ") }}
                      </div>
                      <i
                          v-show="tagged_document.source"
                          class="bx bx-error-circle text-warning mr-1 align-content-center"
                          :title="`Document has been added from ${tagged_document.source}`"
                      ></i>
                      <button
                          type="button"
                          @click.prevent="tagging.splice(tagged_index, 1)"
                          class="btn btn-outline-danger tagged-delete-btn"
                          title="Remove"
                      >
                        <i class="bx bx-trash"></i>
                      </button>
                    </div>
                  </template>
                  <div class="text-muted p-50" v-else>
                    No pages tagged.
                  </div>
                </div>
              </div>
              <!-- Tagging Information : END -->
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <button
              class="btn btn-outline-secondary"
              data-dismiss="modal"
              type="button"
          >
            Cancel
          </button>
          <button
              :disabled="!tagging"
              id="saveTags"
              class="btn btn-success"
              type="button"
              @click.prevent="triggerSaveTagging"
          >
            Save Tags
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    available_document_types: {
      required: false,
      default: []
    }
  },
  name: "DocumentTagger",
  data() {
    return {
      is_pdf_page_loading: true,
      pdf_pages: [],
      current_page: 1,
      selected_document_type: null,
      source: null,
      pages_count: 1,
      thumbnails: [],
      tagging: [],
      selected_pages: [],
      document_id: null,
    };
  },
  computed: {
    getLabelsForTaggedDocuments() {
      return this.tagging.map(tagged_document => ({
            ...tagged_document,
            label: this.available_document_types.filter(available_document => available_document.document_type === tagged_document.document_type && available_document.section_type === tagged_document.section_type)?.[0]?.label || '',
            source: this.available_document_types.filter(available_document => available_document.document_type === tagged_document.document_type && available_document.section_type === tagged_document.section_type)?.[0]?.source || null
          }
      ));
    },
    getCountOfTaggedDocumentsWithSource() {
      return this.getLabelsForTaggedDocuments.reduce((count, tagged_document) => {
        if (tagged_document?.source) {
          return ++count;
        }

        return count;
      }, 0);
    },
    getLabeledPageNumbers() {
      return this.getLabelsForTaggedDocuments.flatMap(tag => tag.pages);
    },
    arePagesSelectedForTagging() {
      return this.selected_pages.length > 0 && this.selected_document_type;
    },
    getCurrentSelectableDocumentTypes() {
      return this.available_document_types;
    },
    getEachPageTag() {
      return this.getLabelsForTaggedDocuments.reduce((result, tag) => {
        tag.pages.forEach(page_no => {
          result[page_no] = tag;
        });

        return result;
      }, {});
    },
  },
  methods: {
    resetData() {
      this.is_pdf_page_loading = true;
      this.pdf_pages = [];
      this.thumbnails = [];
      this.tagging = [];
      this.selected_pages = [];
      this.current_page = 1;
      this.pages_count = 1;
      this.selected_document_type = null;
      this.source = null;

    },
    clearAllTaggedPages() {
      this.tagging = [];
    },
    clearSelectedPages() {
      this.selected_pages = [];
    },
    selectAllPages() {
      this.selected_pages = [...Array(this.pages_count)].map((_, i) => i + 1).filter(current_page => !this.getLabeledPageNumbers.includes(current_page));
    },
    tagSelectedPages() {
      if (this.arePagesSelectedForTagging) {
        let {document_type, section_type} = this.selected_document_type;

        const tag = this.tagging.find(current_tag => current_tag.document_type === document_type &&  current_tag.section_type === section_type);

        if(tag?.pages) {
          tag.pages.push(...this.selected_pages);
        } else {
          this.tagging.push({
            section_type,
            document_type,
            pages: this.selected_pages
          });
        }

        this.selected_document_type = null;
        this.selected_pages = [];
      }
    },
    async loadPdf(data) {
      this.resetData();
      const {source, url, id, tagging } = data;
      this.document_id = id;
      this.source = source;

      if (this.$helpers.isValidJsonString(tagging)) {
        this.tagging = JSON.parse(tagging);
      } else {
        this.tagging = [];
      }

      this.pdf_pages = await window.pdfjsLib.getDocument({
        url: url,
        isEvalSupported: false,
      }).promise;

      await this.renderMainPage(1);
      this.pages_count = this.pdf_pages.numPages;

      for (const current_page_index in [...Array(this.pages_count)]) {
        let thumbnail = await this.generateThumbnail(parseInt(current_page_index) + 1);
        this.thumbnails.push(thumbnail);
      }

      this.is_pdf_page_loading = false;
    },
    async renderMainPage(current_page = 1) {
      this.current_page = current_page;

      const page =
          await this.pdf_pages.getPage(current_page);

      const viewport =
          page.getViewport({
            scale: 1.3,
          });

      const canvas =
          this.$refs.mainPdfCanvas;

      const context =
          canvas.getContext("2d");

      canvas.width =
          viewport.width;

      canvas.height =
          viewport.height;

      await page.render({
        canvasContext: context,
        viewport: viewport,
      }).promise;
    },
    async generateThumbnail(page_number = 1) {

      const page =
          await this.pdf_pages.getPage(
              page_number
          );

      const viewport =
          page.getViewport({
            scale: 0.35
          });

      const canvas =
          document.createElement(
              "canvas"
          );

      const context =
          canvas.getContext(
              "2d"
          );


      canvas.width =
          viewport.width;

      canvas.height =
          viewport.height;


      await page.render({

        canvasContext:
        context,

        viewport:
        viewport

      }).promise;


      return canvas.toDataURL(
          "image/jpeg",
          0.85
      );

    },
    triggerSaveTagging() {
      const document_id = this.document_id;
      const tagging = this.tagging;
      Event.fire('document_tagging_save', {document_id, tagging});
    }
  },
  mounted() {
    Event.listen(
        "document_tagging_initialize",
        (data) => {
          this.loadPdf(data);
          $('#pdfTagModal').modal('show');
        }
    );

    Event.listen(
        "document_tagging_cleanup",
        (data) => {
          this.resetData();
          $('#pdfTagModal').modal('hide');
        }
    );
  },
  beforeDestroy() {
    Event.vue.$off("document_tagging_initialize", null);
    Event.vue.$off("document_tagging_cleanup", null);
  },
}
</script>
<style lang="scss">
#pdfTagModal {
  background: #f8f8f8;
  padding: 30px;

  .pdf-canvas-loader {
    position: absolute;
    top: 0;
    left: 0;

    width: 100%;
    height: 100%;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    background: rgba(255, 255, 255, 0.85);

    z-index: 10;
  }

  /* Main viewer */
  .pdf-main-viewer {
    height: 650px;
    overflow: auto;
    background: #eee;
    border: 1px solid #ddd;
    text-align: center;
    padding: 15px;
  }

  #mainPdfCanvas {
    max-width: 100%;
    height: auto;
    background: #fff;
  }

  /* Thumbnail area */
  .pdf-page-container {
    max-height: 300px;
    overflow-y: auto;
    overflow-x: hidden;
  }

  .pdf-page-card {
    position: relative;
    cursor: pointer;
    border: 2px solid #ddd;
    border-radius: 3px;
    background: #fff;
    padding: 5px;
  }

  .pdf-page-card.selected {
    border-color: #5a8dee;
  }

  .pdf-page-card.active {
    box-shadow: 0 0 0 2px rgba(90, 141, 238, 0.2);
  }

  .pdf-thumbnail {
    width: 100%;
    height: 75px;
    object-fit: contain;
  }

  .pdf-page-checkbox {
    position: absolute;
    top: 7px;
    right: 7px;
    z-index: 2;
  }

  .page-tag {
    display: block;
    margin-top: 5px;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
  }

  .tag-summary {
    max-height: 200px;
    overflow-y: auto;
  }

  .tagged-delete-btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .tagged-delete-btn i {
    font-size: 16px;
    line-height: 1;
  }
}


</style>