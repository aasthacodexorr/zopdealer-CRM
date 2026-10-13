<template>
  <div
    class="tab-pane"
    id="inventory-notes"
    aria-labelledby="inventory-notes"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="inventory-notes-main">
      <div class="row match-height">
        <div class="col-12 col-xs-12 col-md-8">
          <div class="card">
            <div class="card-content">
              <div class="card-body pb-0 mx-25">
                <h4 class="card-title col-12">Notes</h4>
                <!-- Form to be submitted -->
                <div class="row">
                  <h5 class="col-12 text-danger" style="font-size: 0.8em">
                    Internal notes, not visible on paperwork.
                  </h5>
                  <div class="col-12 mb-2">
                    <div class="form-group">
                      <input
                        type="text"
                        v-model="form.title"
                        id="title"
                        class="form-control"
                        name="title"
                        placeholder="Title"
                      />
                    </div>
                    <div class="form-group">
                      <textarea
                        type="text"
                        id="text"
                        v-model="form.description"
                        class="form-control"
                        name="text"
                        placeholder="Type note..."
                      ></textarea>
                    </div>
                    <div class="form-row">
                      <div class="col">
                        <div class="custom-file">
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
                            class="custom-file-label"
                            id="attachment_label"
                            for="attachment"
                            >Choose Attachment</label
                          >
                        </div>
                      </div>
                      <div class="col">
                        <button
                          type="button"
                          @click.prevent="saveNote()"
                          :disabled="disableButton"
                          class="float-right btn btn-primary shadow mr-1 mb-1"
                        >
                          Submit
                        </button>

                        <button
                          type="button"
                          @click.prevent="resetForm()"
                          class="float-right btn btn-secondary shadow mr-1 mb-1"
                        >
                          Reset
                        </button>
                      </div>
                    </div>
                  </div>
                  <form action></form>
                </div>
                <!-- end of form to be submitted -->
                <div class="row">
                  <!-- each note -->
                  <div
                    class="mb-0 col-md-12 col-sm-12"
                    v-for="each_note in all_notes"
                    :key="each_note.id + `_note`"
                  >
                    <div class="card" style="background: #f5f5f5">
                      <div class="card-content mb-0">
                        <div class="card-body">
                          <h6 class="card-title mb-0">
                            <span class="pr-3">{{ each_note.title }}</span>
                            <span
                              v-if="showDeleteUpdateButtons(each_note.user_id)"
                              class="lead-note-buttons"
                              style="position: absolute; right: 1em; top: 1em"
                            >
                              <i
                                @click.prevent="openEditNote(each_note.id)"
                                style="font-size: 1.4em; background: whitesmoke"
                                class="bx text-info bx bx-edit"
                              ></i>
                              <i
                                @click.prevent="deleteNote(each_note.id)"
                                style="font-size: 1.4em; background: whitesmoke"
                                class="bx text-danger bx bx-trash"
                              ></i>
                            </span>
                          </h6>
                          <p
                            class="card-text"
                            v-if="
                              each_note.description != null &&
                              each_note.description != ''
                            "
                          >
                            {{ each_note.description }}
                          </p>
                          <medium
                            class="d-block"
                            v-if="each_note.attachment != null"
                          >
                            <a
                              :href="
                                parseAbsoluteOrRelativeUrl(each_note.attachment)
                              "
                              target="_blank"
                            >
                              <i class="bx-link-alt bx"></i>
                              {{ attachment_link(each_note.attachment) }}
                            </a>
                          </medium>
                          <small class="d-block text-muted">
                            <span class="font-weight-bold font-italic">
                              {{ note_author(each_note.user_id) }}
                            </span>
                            <span style="float: right">
                              <i
                                style="
                                  vertical-align: middle;
                                  padding-right: 1em;
                                "
                                class="bxs-time bx"
                              ></i>
                              {{ modified_data(each_note.updated_at) }}
                            </span>
                          </small>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- each note -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Model -->
    <div
      class="modal fade"
      id="editNote"
      tabindex="-1"
      role="dialog"
      aria-labelledby="editNoteLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Update Note</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!--form model -->
          <form action="#">
            <div class="modal-body">
              <div class="form-group">
                <input
                  type="text"
                  v-model="edit_form.title"
                  id="title"
                  class="form-control"
                  name="title"
                  placeholder="Title"
                />
              </div>
              <div class="form-group">
                <textarea
                  type="text"
                  id="text"
                  v-model="edit_form.description"
                  class="form-control"
                  name="text"
                  placeholder="Type note..."
                ></textarea>
              </div>
            </div>
          </form>
          <!-- end of form model -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              @click.prevent="updateNote(edit_form.id)"
              class="btn btn-primary"
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
export default {
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
