<template>
  <div
    class="tab-pane"
    id="inventory-notes"
    aria-labelledby="inventory-notes"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="inventory-notes-main" class="nt-page">
      <div class="nt-layout">
        <!-- Composer : START -->
        <div class="nt-card nt-composer">
          <div class="nt-composer-top">
            <h4 class="nt-feed-title">Notes</h4>
            <h5 class="nt-internal">
              <i class="bx bx-lock-alt"></i>
              Internal notes, not visible on paperwork.
            </h5>
          </div>
          <div class="nt-composer-head">
            <span class="nt-avatar" :style="noteAvatarStyle(noteComposerName)">{{
              noteInitials(noteComposerName)
            }}</span>
            <div class="nt-composer-fields">
              <input
                type="text"
                v-model="form.title"
                id="title"
                class="nt-title-input"
                name="title"
                placeholder="Title"
              />
              <textarea
                type="text"
                id="text"
                v-model="form.description"
                class="nt-textarea"
                name="text"
                rows="3"
                placeholder="Type note..."
              ></textarea>
            </div>
          </div>

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
              <label
                class="custom-file-label nt-file-label"
                id="attachment_label"
                for="attachment"
                >Choose Attachment</label
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
                Submit
              </button>
            </div>
          </div>
          <form action></form>
        </div>
        <!-- Composer : END -->

        <!-- Notes feed : START -->
        <div class="nt-card" v-if="all_notes.length">
          <ul class="nt-feed">
            <!-- each note -->
            <li
              class="nt-note"
              v-for="each_note in all_notes"
              :key="each_note.id + `_note`"
            >
              <span
                class="nt-avatar"
                :style="noteAvatarStyle(note_author(each_note.user_id))"
                >{{ noteInitials(note_author(each_note.user_id)) }}</span
              >
              <div class="nt-note-body">
                <div class="nt-note-head">
                  <div class="nt-note-meta">
                    <span class="nt-author">{{
                      note_author(each_note.user_id)
                    }}</span>
                    <span class="nt-time"
                      ><i class="bx bx-time-five"></i>
                      {{ modified_data(each_note.updated_at) }}</span
                    >
                  </div>
                  <div
                    v-if="showDeleteUpdateButtons(each_note.user_id)"
                    class="lead-note-buttons nt-note-actions"
                  >
                    <button
                      type="button"
                      class="nt-icon-btn"
                      title="Edit"
                      @click.prevent="openEditNote(each_note.id)"
                    >
                      <i class="bx bx-edit-alt"></i>
                    </button>
                    <button
                      type="button"
                      class="nt-icon-btn nt-icon-btn--danger"
                      title="Delete"
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
                >
                  <span class="nt-attachment-icon"
                    ><i class="bx" :class="noteFileIcon(each_note.attachment)"></i
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
            <h5 class="nt-modal-title" id="exampleModalLabel">Update Note</h5>
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
          <form action="#">
            <div class="modal-body nt-modal-body">
              <div class="nt-field">
                <input
                  type="text"
                  v-model="edit_form.title"
                  id="title"
                  class="form-control nt-input"
                  name="title"
                  placeholder="Title"
                />
              </div>
              <div class="nt-field mb-0">
                <textarea
                  type="text"
                  id="text"
                  v-model="edit_form.description"
                  class="form-control nt-input nt-input-area"
                  name="text"
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
              Close
            </button>
            <button
              type="button"
              @click.prevent="updateNote(edit_form.id)"
              class="btn btn-primary nt-primary-btn"
            >
              Update Note
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Model End -->
  </div>
</template>
<script>
import Toast from "../../library/Toast";
import noteDisplay from "../../mixins/noteDisplay";
export default {
  mixins: [noteDisplay],
  props: {
    id: { required: true },
    allUsers: { required: true },
    app_metadata: { required: true },
    time_zone: String,
  },
  name: "InventoryManageNote",
  data() {
    return {
      errors: [],
      form: {
        title: null,
        description: null,
        attachment: null,
        inventory_id: this.id,
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
      return this.$helpers.assigneeName(this.allUsers, author_id);
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
          `/api/business/inventory/${this.id}/notes/show/${note_id}`,
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
          `/api/business/inventory/${this.id}/notes/update/${note_id}`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          title = "Note updated successfully.";
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
            : "There were some errors in updating notes.";
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
          `/api/business/inventory/${this.id}/notes/delete/${note_id}`,
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
            : "There were some errors in deleting note.";
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
      document.getElementById("attachment_label").innerHTML =
        "Choose Attachment";
    },
    allNotes() {
      axios({
        method: "get",
        url:
          window.location.origin + `/api/business/inventory/${this.id}/notes`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
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
          `/api/business/inventory/${this.id}/notes/create`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          title = "Note has been saved successfully.";
          toast.success(title);

          toast.close();

          this.form.title = null;
          this.form.description = null;
          this.form.attachment = null;
          this.$refs.attachment.value = null;
          document.getElementById("attachment_label").innerHTML =
            "Choose Attachment";
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
@import "../../assets/scss/notes-tab";

/* Shared Notes tab design (same as Leads). */
#inventory-notes {
  @include notes-tab;
  .lead-note-buttons i:hover {
    font-size: inherit !important;
  }
}

.lead-note-buttons {
  i {
    transition-timing-function: linear;
    vertical-align: top;
    &:hover {
      font-size: 1.8em !important;
      cursor: pointer;
    }
  }
}
</style>
