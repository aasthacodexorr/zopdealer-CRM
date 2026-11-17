<template>
  <div
    class="tab-pane"
    id="lead-view-notes"
    aria-labelledby="lead-view-notes"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="lead-view-main" class="nt-page">
      <div class="nt-layout">
        <!-- Composer : START -->
        <div class="nt-card nt-composer">
          <div class="nt-composer-head">
            <span class="nt-avatar" :style="avatarStyle(currentUserName)">{{
              initials(currentUserName)
            }}</span>
            <div class="nt-composer-fields">
              <input
                type="text"
                v-model="form.title"
                id="title"
                class="nt-title-input"
                name="title"
                placeholder="Note title (optional)"
                autocomplete="off"
              />
              <textarea
                type="text"
                id="text"
                v-model="form.description"
                class="nt-textarea"
                name="text"
                rows="3"
                placeholder="Write a note about this lead..."
              ></textarea>
            </div>
          </div>

          <div class="nt-file-chip" v-if="form.attachment">
            <i class="bx" :class="fileIcon(form.attachment.name)"></i>
            <span class="nt-file-name" :title="form.attachment.name">{{
              form.attachment.name
            }}</span>
            <span class="nt-file-size">{{ fileSize(form.attachment.size) }}</span>
            <button
              type="button"
              class="nt-file-remove"
              title="Remove attachment"
              @click="removeAttachment"
            >
              <i class="bx bx-x"></i>
            </button>
          </div>
          <small class="nt-error" v-if="errors.attachment">{{
            errors.attachment
          }}</small>

          <div class="nt-composer-foot">
            <div class="custom-file nt-attach">
              <input
                :class="{
                  'is-invalid': errors.hasOwnProperty('attachment'),
                }"
                :title="errors.attachment"
                type="file"
                ref="attachment"
                @change="saveFile($event)"
                class="custom-file-input"
                id="attachment"
              />
              <label class="nt-attach-btn" id="attachment_label" for="attachment"
                ><i class="bx bx-paperclip"></i>
                {{ form.attachment ? "Change file" : "Attach file" }}</label
              >
            </div>
            <div class="nt-composer-actions">
              <button
                type="button"
                @click.prevent="resetForm()"
                class="btn nt-ghost-btn"
              >
                Reset
              </button>
              <button
                type="button"
                @click.prevent="saveNote()"
                :disabled="disableButton"
                class="btn btn-primary nt-primary-btn"
              >
                <i class="bx bx-send"></i> Add Note
              </button>
            </div>
          </div>
          <form action></form>
        </div>
        <!-- Composer : END -->

        <!-- Notes feed : START -->
        <div class="nt-card">
          <div class="nt-feed-head">
            <h4 class="nt-feed-title">Notes</h4>
            <span class="nt-count" v-if="all_notes.length">{{
              all_notes.length
            }}</span>
          </div>

          <ul class="nt-feed" v-if="all_notes.length">
            <!-- each note -->
            <li
              class="nt-note"
              v-for="each_note in all_notes"
              :key="each_note.id + `_note`"
            >
              <span
                class="nt-avatar"
                :style="avatarStyle(note_author(each_note.user_id))"
                >{{ initials(note_author(each_note.user_id)) }}</span
              >
              <div class="nt-note-body">
                <div class="nt-note-head">
                  <div class="nt-note-meta">
                    <span class="nt-author">{{
                      note_author(each_note.user_id) || "Unknown user"
                    }}</span>
                    <span
                      class="nt-time"
                      :title="modified_data(each_note.updated_at)"
                      ><i class="bx bx-time-five"></i>
                      {{ relativeTime(each_note.updated_at) }}</span
                    >
                    <span
                      class="nt-edited"
                      v-if="
                        each_note.created_at &&
                        each_note.updated_at &&
                        each_note.created_at != each_note.updated_at
                      "
                      >· edited</span
                    >
                  </div>
                  <div
                    v-if="showDeleteUpdateButtons(each_note.user_id)"
                    class="lead-note-buttons nt-note-actions"
                  >
                    <button
                      type="button"
                      class="nt-icon-btn"
                      title="Edit note"
                      @click.prevent="openEditNote(each_note.id)"
                    >
                      <i class="bx bx-edit-alt"></i>
                    </button>
                    <button
                      type="button"
                      class="nt-icon-btn nt-icon-btn--danger"
                      title="Delete note"
                      @click.prevent="deleteNote(each_note.id)"
                    >
                      <i class="bx bx-trash"></i>
                    </button>
                  </div>
                </div>
                <h6 class="nt-note-title" v-if="each_note.title">
                  {{ each_note.title }}
                </h6>
                <p
                  class="nt-note-text"
                  v-if="
                    each_note.description != null &&
                    each_note.description != ''
                  "
                >
                  {{ each_note.description }}
                </p>
                <a
                  v-if="each_note.attachment != null"
                  class="nt-attachment"
                  :href="parseAbsoluteOrRelativeUrl(each_note.attachment)"
                  target="_blank"
                  rel="noopener"
                >
                  <span class="nt-attachment-icon"
                    ><i class="bx" :class="fileIcon(each_note.attachment)"></i
                  ></span>
                  <span class="nt-attachment-name">{{
                    attachment_link(each_note.attachment)
                  }}</span>
                  <i class="bx bx-link-external nt-attachment-open"></i>
                </a>
              </div>
            </li>
            <!-- each note -->
          </ul>

          <div class="nt-empty" v-else>
            <span class="nt-empty-icon"><i class="bx bx-note"></i></span>
            <p class="nt-empty-title">No notes yet</p>
            <p class="nt-empty-text">
              Use the box above to add the first note about this lead.
            </p>
          </div>
        </div>
        <!-- Notes feed : END -->
      </div>
    </section>

    <!-- Model -->
    <div
      class="modal fade nt-modal"
      id="editNote"
      tabindex="-1"
      role="dialog"
      aria-labelledby="editNoteLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="nt-modal-header">
            <div>
              <h5 class="nt-modal-title" id="editNoteLabel">Edit Note</h5>
              <p class="nt-modal-subtitle">Update the title or text of this note.</p>
            </div>
            <button
              type="button"
              class="nt-close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <i class="bx bx-x"></i>
            </button>
          </div>
          <!--form model -->
          <form action="#" @submit.prevent>
            <div class="modal-body nt-modal-body">
              <div class="nt-field">
                <label class="nt-label" for="edit_note_title">Title</label>
                <input
                  type="text"
                  v-model="edit_form.title"
                  id="edit_note_title"
                  class="form-control nt-input"
                  name="title"
                  placeholder="Title"
                />
              </div>
              <div class="nt-field mb-0">
                <label class="nt-label" for="edit_note_text">Note</label>
                <textarea
                  type="text"
                  id="edit_note_text"
                  v-model="edit_form.description"
                  class="form-control nt-input nt-input-area"
                  name="text"
                  rows="5"
                  placeholder="Type note..."
                ></textarea>
              </div>
            </div>
          </form>
          <!-- end of form model -->
          <div class="nt-modal-footer">
            <button
              type="button"
              class="btn nt-ghost-btn"
              data-dismiss="modal"
            >
              Cancel
            </button>
            <button
              type="button"
              @click.prevent="updateNote(edit_form.id)"
              class="btn btn-primary nt-primary-btn"
            >
              <i class="bx bx-check"></i> Update Note
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Model End -->
  </div>
</template>
<script>
import Toast from "../../../../library/Toast";
import moment from "moment";
export default {
  props: {
    id: { required: true },
    allUsers: { required: true },
    app_metadata: { required: true },
    time_zone: String,
  },
  name: "LeadNote",
  data() {
    return {
      errors: [],
      form: {
        title: null,
        description: null,
        attachment: null,
        lead_response_id: this.id,
        user_id: this.app_metadata.id,
      },
      edit_form: {
        title: null,
        description: null,
        id: null,
      },
      all_notes: [],
    };
  },
  computed: {
    disableButton() {
      return (
        (this.form.title == null || this.form.title.trim() == "") &&
        (this.form.description == null || this.form.description.trim() == "") &&
        this.form.attachment == null
      );
    },
    timeZoneName() {
      return this.time_zone ? this.time_zone : "Canada/Eastern";
    },
    currentUserName() {
      return this.note_author(this.app_metadata.id) ||
        (window.global && window.global.user_metadata
          ? window.global.user_metadata.name
          : "");
    },
  },
  methods: {
    parseAbsoluteOrRelativeUrl(file_url) {
      return this.$helpers.parseAbsoluteOrRelativeUrl(
        window.global.cdn,
        file_url
      );
    },
    showDeleteUpdateButtons(author_id) {
      return (
        author_id == this.app_metadata.id || this.app_metadata.role == "Owner"
      );
    },
    note_author(author_id) {
      if (!author_id) return "";
      return this.$helpers.assigneeName(this.allUsers, author_id) || "";
    },
    initials(name) {
      const parts = String(name || "")
        .trim()
        .split(/\s+/)
        .filter((part) => part);
      const letters = (parts[0] || "").charAt(0) + (parts[1] || "").charAt(0);
      return letters ? letters.toUpperCase() : "?";
    },
    avatarStyle(name) {
      const palette = [
        ["#e6efff", "#2f62d6"],
        ["#e3f6e8", "#1e8a43"],
        ["#fff3d1", "#a26a00"],
        ["#efe8ff", "#6b46c1"],
        ["#def6f3", "#11806f"],
        ["#fde6ef", "#b83268"],
      ];
      const value = String(name || "");
      let hash = 0;
      for (let i = 0; i < value.length; i++) {
        hash = (hash * 31 + value.charCodeAt(i)) >>> 0;
      }
      const [background, color] = palette[hash % palette.length];
      return { background, color };
    },
    relativeTime(date) {
      if (!date) return "";
      const value = moment.utc(date);
      return value.isValid() ? value.fromNow() : "";
    },
    fileIcon(name) {
      const ext = String(name || "").split("?")[0].split(".").pop().toLowerCase();
      if (["png", "jpg", "jpeg", "gif", "webp", "svg", "heic"].includes(ext)) return "bx-image";
      if (ext === "pdf") return "bxs-file-pdf";
      if (["doc", "docx", "txt", "rtf"].includes(ext)) return "bx-file";
      if (["xls", "xlsx", "csv"].includes(ext)) return "bx-spreadsheet";
      if (["zip", "rar", "7z"].includes(ext)) return "bx-archive";
      return "bx-paperclip";
    },
    fileSize(bytes) {
      if (!bytes && bytes !== 0) return "";
      if (bytes < 1024) return bytes + " B";
      if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + " KB";
      return (bytes / (1024 * 1024)).toFixed(1) + " MB";
    },
    removeAttachment() {
      this.form.attachment = null;
      if (this.$refs.attachment) this.$refs.attachment.value = null;
      delete this.errors.attachment;
    },
    attachment_link(attachment) {
      if (attachment != null) {
        let attachment_array = attachment.split("/");
        return attachment_array[attachment_array.length - 1];
      } else {
        return "";
      }
    },
    modified_data(updated_at) {
      return this.$helpers.changeUTCtoLocalDate(
        updated_at,
        "MM/DD/YYYY hh:mm:ss A",
        this.timeZoneName
      );
    },
    saveFile(event) {
      this.errors = [];
      this.form.attachment = event.target.files[0];
    },
    openEditNote(note_id) {
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin +
          `/api/business/leads/responses/${this.id}/notes/show/${note_id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.edit_form.title = data.title;
          this.edit_form.description = data.description;
          this.edit_form.id = data.id;
          $("#editNote").modal("show");
        })
        .catch((error) => {});
    },
    updateNote(note_id) {
      const vm = this;
      let formData = new FormData();
      for (const key in this.edit_form) {
        if (
          this.edit_form.hasOwnProperty(key) &&
          key != "id" &&
          this.edit_form[key] != null
        ) {
          formData.append(key, this.edit_form[key]);
        }
      }

      let title = "Updating note ...";
      const toast = new Toast(title);
      toast.fire();

      axios({
        method: "post",
        url:
          window.location.origin +
          `/api/business/leads/responses/${this.id}/notes/update/${note_id}`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then((response) => {
          title = "Lead updated successfully.";
          toast.success(title);
          toast.close();
          this.edit_form.title = null;
          this.edit_form.description = null;
          this.edit_form.id = null;

          vm.allNotes();
          $("#editNote").modal("hide");
        })
        .catch(({ response }) => {
          let error = response.data.hasOwnProperty("errors")
            ? response.data.errors
            : "There were some errors in updating lead notes.";
          toast.fail(error);
          toast.close();
        });
    },
    deleteNote(note_id) {
      const vm = this;

      let title = "Deleting note ...";
      const toast = new Toast(title);
      toast.fire();

      axios({
        method: "get",
        url:
          window.location.origin +
          `/api/business/leads/responses/${this.id}/notes/delete/${note_id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
          vm.allNotes();
        })
        .catch(({ response }) => {
          let error = response.data.hasOwnProperty("errors")
            ? response.data.errors
            : "There were some errors in deleting lead note.";
          toast.fail(error);
          toast.close();
          vm.allNotes();
        });
    },
    resetForm() {
      this.form.title = null;
      this.form.description = null;
      this.form.attachment = null;
      this.$refs.attachment.value = null;
    },
    allNotes() {
      axios({
        method: "get",
        url:
          window.location.origin +
          `/api/business/leads/responses/${this.id}/notes`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          this.all_notes = data.all_notes;
        })
        .catch((response) => {});
    },
    saveNote() {
      const vm = this;
      this.errors = [];
      let formData = new FormData();
      for (const key in this.form) {
        if (
          this.form.hasOwnProperty(key) &&
          key != "attachment" &&
          this.form[key] != null
        ) {
          formData.append(key, this.form[key]);
        }
      }

      if (this.form.attachment != null) {
        formData.append(
          "attachment",
          this.form.attachment,
          this.form.attachment.name
        );
      }

      let title = "Saving a new note..";
      const toast = new Toast(title);
      toast.fire();

      axios({
        method: "post",
        url:
          window.location.origin +
          `/api/business/leads/responses/${this.id}/notes/create`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then((response) => {
          //vm.$data.SubmitDisable = true;
          title = "Note has been saved successfully.";
          toast.success(title);

          toast.close();

          this.form.title = null;
          this.form.description = null;
          this.form.attachment = null;
          this.$refs.attachment.value = null;
          this.allNotes();
        })
        .catch(function ({ response }) {
          // handle error
          vm.$data.errors = response.data;
          title = "There were some errors in saving note.";
          toast.fail(title);
          toast.close();
        });
    },
  },
  mounted() {
    this.allNotes();
  },
};
</script>
<style lang="scss">
/* ==========================================================================
   Lead detail — Notes tab. Scoped to #lead-view-notes.
   ========================================================================== */
#lead-view-notes {
  --nt-border: #e6eaf0;
  --nt-surface: #ffffff;
  --nt-bg: #f6f8fb;
  --nt-text: #1f2937;
  --nt-text-2: #4b5563;
  --nt-muted: #8a94a6;
  --nt-primary: #5a8dee;
  --nt-primary-soft: #eef4ff;

  .nt-page {
    padding: 1.5rem 1.75rem 6rem;
    background: var(--nt-bg);
    min-height: 100%;
  }
  .nt-layout {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
  }
  .nt-card {
    background: var(--nt-surface);
    border: 1px solid var(--nt-border);
    border-radius: 14px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
  }

  .nt-avatar {
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.02em;
  }

  /* ---------- Composer ---------- */
  .nt-composer {
    padding: 1.1rem 1.25rem 0.9rem;
    transition: border-color 0.15s, box-shadow 0.15s;
    &:focus-within {
      border-color: var(--nt-primary);
      box-shadow: 0 0 0 3px rgba(90, 141, 238, 0.12);
    }
  }
  .nt-composer-head {
    display: flex;
    gap: 0.85rem;
  }
  .nt-composer-fields {
    flex: 1 1 auto;
    min-width: 0;
  }
  .nt-title-input {
    width: 100%;
    border: 0;
    outline: none;
    padding: 0.4rem 0 0.25rem;
    font-size: 1rem;
    font-weight: 600;
    color: var(--nt-text);
    background: transparent;
    &::placeholder {
      color: var(--nt-muted);
      font-weight: 500;
    }
  }
  .nt-textarea {
    width: 100%;
    min-height: 72px;
    border: 0;
    outline: none;
    resize: vertical;
    padding: 0.25rem 0;
    font-size: 0.95rem;
    line-height: 1.55;
    color: var(--nt-text);
    background: transparent;
    &::placeholder {
      color: var(--nt-muted);
    }
  }
  .nt-file-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    max-width: 100%;
    margin: 0.4rem 0 0 calc(38px + 0.85rem);
    padding: 0.35rem 0.4rem 0.35rem 0.65rem;
    border: 1px solid var(--nt-border);
    border-radius: 10px;
    background: var(--nt-bg);
    font-size: 0.85rem;
    color: var(--nt-text);
    > i {
      font-size: 1.15rem;
      color: var(--nt-primary);
    }
  }
  .nt-file-name {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 260px;
    font-weight: 500;
  }
  .nt-file-size {
    color: var(--nt-muted);
    font-size: 0.78rem;
  }
  .nt-file-remove {
    width: 24px;
    height: 24px;
    border: 0;
    border-radius: 6px;
    background: transparent;
    color: var(--nt-muted);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1.1rem;
    &:hover {
      background: #fde6e6;
      color: #c23434;
    }
  }
  .nt-error {
    display: block;
    margin: 0.3rem 0 0 calc(38px + 0.85rem);
    font-size: 0.8rem;
    color: #c23434;
  }
  .nt-composer-foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    flex-wrap: wrap;
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid var(--nt-border);
  }
  .nt-attach {
    position: relative;
    width: auto;
    height: auto;
    display: inline-block;
    .custom-file-input {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      cursor: pointer;
      z-index: 2;
    }
  }
  .nt-attach-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    height: 36px;
    margin: 0;
    padding: 0 0.8rem;
    border-radius: 9px;
    border: 1px dashed #c9d1dc;
    color: var(--nt-text-2);
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: none;
    letter-spacing: 0;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s, color 0.15s;
    i {
      font-size: 1.1rem;
    }
  }
  .nt-attach:hover .nt-attach-btn {
    border-color: var(--nt-primary);
    color: var(--nt-primary);
    background: var(--nt-primary-soft);
  }
  .nt-composer-actions {
    display: flex;
    gap: 0.5rem;
    margin-left: auto;
  }
  .nt-primary-btn {
    height: 38px;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0 1.1rem;
    border-radius: 10px;
    font-weight: 500;
    line-height: 1;
    i {
      position: static;
      top: 0;
      font-size: 1.1rem;
      line-height: 1;
    }
    &:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }
  }
  .nt-ghost-btn {
    height: 38px;
    padding: 0 1rem;
    border-radius: 10px;
    border: 1px solid var(--nt-border);
    background: var(--nt-surface);
    color: var(--nt-text-2);
    font-weight: 500;
    &:hover {
      background: var(--nt-bg);
      color: var(--nt-text);
    }
  }

  /* ---------- Feed ---------- */
  .nt-feed-head {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--nt-border);
  }
  .nt-feed-title {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 600;
    color: var(--nt-text);
  }
  .nt-count {
    min-width: 22px;
    height: 22px;
    padding: 0 7px;
    border-radius: 999px;
    background: var(--nt-primary-soft);
    color: var(--nt-primary);
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }
  .nt-feed {
    list-style: none;
    margin: 0;
    padding: 0;
  }
  .nt-note {
    display: flex;
    gap: 0.85rem;
    padding: 1.1rem 1.25rem;
    border-bottom: 1px solid #eef1f5;
    transition: background 0.12s;
    &:last-child {
      border-bottom: 0;
      border-radius: 0 0 14px 14px;
    }
    &:hover {
      background: #fafbfd;
      .nt-note-actions {
        opacity: 1;
      }
    }
  }
  .nt-note-body {
    flex: 1 1 auto;
    min-width: 0;
  }
  .nt-note-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
  }
  .nt-note-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.25rem 0.6rem;
    min-height: 32px;
  }
  .nt-author {
    font-weight: 600;
    color: var(--nt-text);
    font-size: 0.92rem;
  }
  .nt-time,
  .nt-edited {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.82rem;
    color: var(--nt-muted);
    i {
      font-size: 0.95rem;
    }
  }
  .nt-note-actions {
    display: flex;
    gap: 0.2rem;
    flex-shrink: 0;
    opacity: 0.6;
    transition: opacity 0.15s;
  }
  .nt-icon-btn {
    width: 32px;
    height: 32px;
    border: 0;
    border-radius: 8px;
    background: transparent;
    color: var(--nt-text-2);
    font-size: 1.1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    &:hover {
      background: var(--nt-primary-soft);
      color: var(--nt-primary);
    }
  }
  .nt-icon-btn--danger:hover {
    background: #fde6e6;
    color: #c23434;
  }
  .nt-note-title {
    margin: 0.15rem 0 0.25rem;
    font-size: 0.98rem;
    font-weight: 600;
    color: var(--nt-text);
  }
  .nt-note-text {
    margin: 0;
    font-size: 0.93rem;
    line-height: 1.6;
    color: var(--nt-text-2);
    white-space: pre-line;
    word-break: break-word;
  }
  .nt-attachment {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    max-width: 100%;
    margin-top: 0.7rem;
    padding: 0.45rem 0.75rem 0.45rem 0.45rem;
    border: 1px solid var(--nt-border);
    border-radius: 10px;
    background: var(--nt-surface);
    color: var(--nt-text);
    font-size: 0.85rem;
    font-weight: 500;
    transition: border-color 0.15s, background 0.15s;
    &:hover {
      border-color: var(--nt-primary);
      background: var(--nt-primary-soft);
      color: var(--nt-text);
      .nt-attachment-open {
        color: var(--nt-primary);
      }
    }
  }
  .nt-attachment-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: var(--nt-primary-soft);
    color: var(--nt-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
  }
  .nt-attachment-name {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .nt-attachment-open {
    color: var(--nt-muted);
    font-size: 1rem;
  }

  /* Empty state */
  .nt-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 2.75rem 1.5rem 3rem;
  }
  .nt-empty-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: var(--nt-primary-soft);
    color: var(--nt-primary);
    font-size: 1.7rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
  }
  .nt-empty-title {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 600;
    color: var(--nt-text);
  }
  .nt-empty-text {
    margin: 0.3rem 0 0;
    max-width: 360px;
    color: var(--nt-muted);
    font-size: 0.9rem;
  }

  /* ---------- Edit modal ---------- */
  .nt-modal {
    .modal-dialog {
      max-width: 540px;
    }
    .modal-content {
      border: 1px solid var(--nt-border);
      border-radius: 16px;
      box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
      overflow: hidden;
    }
  }
  .nt-modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.2rem 1.4rem 1rem;
    border-bottom: 1px solid var(--nt-border);
  }
  .nt-modal-title {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--nt-text);
  }
  .nt-modal-subtitle {
    margin: 0.15rem 0 0;
    font-size: 0.85rem;
    color: var(--nt-muted);
  }
  .nt-close {
    width: 34px;
    height: 34px;
    border: 0;
    border-radius: 8px;
    background: transparent;
    color: var(--nt-muted);
    font-size: 1.4rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    &:hover {
      background: #f1f4f8;
      color: var(--nt-text);
    }
  }
  .nt-modal-body {
    padding: 1.2rem 1.4rem;
  }
  .nt-field {
    margin-bottom: 1rem;
  }
  .nt-label {
    display: block;
    margin-bottom: 0.4rem;
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--nt-text-2);
    text-transform: none;
    letter-spacing: 0;
  }
  .nt-input {
    height: 44px;
    border: 1px solid var(--nt-border);
    border-radius: 10px;
    font-size: 0.95rem;
    color: var(--nt-text);
    box-shadow: none;
    &:focus {
      border-color: var(--nt-primary);
      box-shadow: 0 0 0 3px rgba(90, 141, 238, 0.15);
    }
  }
  .nt-input-area {
    height: auto;
    min-height: 130px;
    padding: 0.7rem 0.9rem;
    line-height: 1.5;
    resize: vertical;
  }
  .nt-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    padding: 0.9rem 1.4rem;
    border-top: 1px solid var(--nt-border);
  }

  @media (max-width: 767.98px) {
    .nt-page {
      padding: 1rem 1rem 6rem;
    }
    .nt-file-chip,
    .nt-error {
      margin-left: 0;
    }
    .nt-note-actions {
      opacity: 1;
    }
  }
}
</style>
