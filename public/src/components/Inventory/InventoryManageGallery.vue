<template>
  <div
    class="tab-pane"
    id="media-block-list"
    aria-labelledby="information-tab"
    role="tabpanel"
  >
    <!-- photo  form layout section start -->
    <section id="dropzone-examples" class="mg-page">
      <vue-easy-lightbox
        escDisabled
        moveDisabled
        :visible="visible"
        :imgs="galleryLinks"
        @hide="handleHide"
      ></vue-easy-lightbox>

      <div role="alert" class="mg-notice">
        <i class="bx bx-info-circle"></i>
        <span>
          NOTE: HEIC/HEIF and WebP Images will be automatically converted to JPG
          to provide broader support for third-party syndication. Maximum Image
          Size acceptable is 30MB.
        </span>
      </div>

      <!-- Toolbar -->
      <div class="mg-toolbar">
        <div class="mg-toolbar-left">
          <input
            ref="fileInput"
            type="file"
            style="display: none"
            multiple
            @change="onFileChanged"
          />
          <button
            type="button"
            class="mg-btn mg-btn-primary"
            @click="$refs.fileInput.click()"
          >
            <i class="bx bxs-cloud-upload"></i>
            <span>Upload</span>
          </button>
          <span v-if="!checkedCount" class="mg-select-count">
            Select Count : <strong>{{ selectedCount }}</strong>
          </span>
        </div>
        <div class="mg-toolbar-right">
          <button type="button" @click="visible = true" class="mg-btn mg-btn-ghost">
            <i class="bx bx-photo-album"></i>
            <span>Show Gallery</span>
          </button>
          <div class="mg-btn-group" role="group" aria-label="Basic example">
            <button
              @click="selected_id = []"
              :disabled="checkedCount"
              type="button"
              class="mg-btn mg-btn-ghost"
            >
              Uncheck All
            </button>
            <button
              @click="checkAll"
              type="button"
              :disabled="allChecked"
              class="mg-btn mg-btn-ghost"
            >
              Check All
            </button>
            <div class="btn-group" role="group">
              <button
                :disabled="checkedCount"
                id="btnGroupDrop1"
                type="button"
                class="mg-btn mg-btn-dark dropdown-toggle"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                Action
              </button>
              <div
                class="dropdown-menu dropdown-menu-right mg-dropdown"
                aria-labelledby="btnGroupDrop1"
              >
                <button @click="activationImages(1)" class="dropdown-item">
                  <i class="bx bx-show"></i> Active
                </button>
                <button @click="activationImages(0)" class="dropdown-item">
                  <i class="bx bx-hide"></i> Inactive
                </button>
                <button @click="deleteImages" class="dropdown-item mg-dropdown-danger">
                  <i class="bx bx-trash"></i> Delete
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- photos gallery -->
      <!-- Draggable List : OPEN -->
      <draggable
        v-show="galleryLoad"
        tag="transition-group"
        :component-data="{
          tag: 'div',
          type: 'transition',
          name: 'flip-list',
          class: 'mg-grid'
        }"
        :list="gallery_list"
        v-bind="dragOptions"
        @start="isDragging = true"
        @end="isDragging = false"
        class="mg-grid"
        @change="saveSortRank"
        handle=".handle"
        item-key="id"
      >
        <template #item="{ element: image }">
          <div
            class="mg-card"
            :class="{ 'is-selected': selected_id.includes(image.id) }"
          >
            <input
              class="mg-checkbox-input"
              type="checkbox"
              v-model="selected_id"
              :id="'checkbox_' + image.id"
              :value="image.id"
            />
            <label :for="'checkbox_' + image.id" class="mg-thumb">
              <img
                :src="parseAbsoluteOrRelativeUrl(image.image_url)"
                class="handle"
              />
              <span class="mg-check"><i class="bx bx-check"></i></span>
              <i v-if="image.active == '0'" class="bx bx-hide mask"></i>
            </label>
          </div>
        </template>
      </draggable>
      <!-- Draggable List : CLOSE -->

      <div class="mg-footer">
        Number of Images : <strong>{{ imagesCount }}</strong>
      </div>
      <!-- end photos gallery -->
    </section>
    <!-- // photo  form layout section end -->
  </div>
</template>
<script>
import draggable from "vuedraggable";
import Toast from "../../library/Toast";
import VueEasyLightbox from "vue-easy-lightbox";

export default {
  name: "InventoryManageGallery",
  components: {
    draggable,
    VueEasyLightbox,
  },
  watch: {
    n: function (newVal, oldVal) {
      if (isNaN(parseInt(newVal))) {
        this.n = 0;
      } else {
        this.n = parseInt(this.n);
      }
    },
  },
  data() {
    return {
      selectedFile: null,
      dragging: true,
      n: 0,
      gallery_list: [],
      selected_id: [],
      galleryLoad: true,
      visible: false,
    };
  },
  computed: {
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost",
      };
    },
    checkedCount() {
      return this.selected_id.length > 0 ? false : true;
    },
    allChecked() {
      return this.selected_id.length == this.gallery_list.length ? true : false;
    },
    selectedCount() {
      return this.selected_id.length;
    },
    imagesCount() {
      return this.gallery_list.length;
    },
    galleryLinks() {
      if (this.selected_id.length > 0) {
        return this.gallery_list
          .filter((image) => this.selected_id.includes(image.id))
          .map((image) =>
            this.$helpers.parseAbsoluteOrRelativeUrl(
              window.global.cdn,
              image.image_url
            )
          );
      } else {
        return this.gallery_list.map((image) =>
          this.$helpers.parseAbsoluteOrRelativeUrl(
            window.global.cdn,
            image.image_url
          )
        );
      }
    },
  },
  methods: {
    handleHide() {
      this.visible = false;
    },
    parseAbsoluteOrRelativeUrl(image_url) {
      return (
        this.$helpers.parseAbsoluteOrRelativeUrl(window.global.cdn, image_url) +
        "?width=200"
      );
    },
    onFileChanged(event) {
      (this.selectedFile = event.target.files), this.onUpload();
    },
    async deleteImages() {
      const vm = this;
      let formData = new FormData();
      formData.append("ids", JSON.stringify(this.selected_id));

      let message = "Deleting Images...";
      const toast = new Toast(message);
      toast.fire();

      if (this.$route.params.id && this.$route.name == "inventory-update") {
        await axios({
          method: "post",
          data: formData,
          url:
            window.location.origin +
            "/api/business/inventory/image/delete/" +
            this.$route.params.id,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
            "Content-Type": "multipart/form-data",
          },
        })
          .then(({ data }) => {
            if (typeof data.gallery !== "undefined") {
              vm.$data.gallery_list = data.gallery;
              vm.$data.n = parseInt(data.last_rank);
              vm.$data.selected_id = [];

              let success_message = "Finished Deleting Images.";
              toast.success(success_message);
              toast.close();
              Event.fire("save_complete", { success: "" });
            }
          })
          .catch((error) => {
            let error_message =
              "There were some errors while deleting images. Refresh And retry again.";
            toast.fail(error_message);
            toast.close();
          });
      }

      await vm.$helpers.gTagClickEvent(
        vm,
        "zd_action",
        "inventory",
        "image",
        "delete"
      );
    },
    async activationImages($activation) {
      const vm = this;
      let formData = new FormData();
      formData.append("ids", JSON.stringify(this.selected_id));
      formData.append("activation", $activation);

      let message = "Changing Active Status...";
      const toast = new Toast(message);
      toast.fire();

      if (this.$route.params.id && this.$route.name == "inventory-update") {
        await axios({
          method: "post",
          data: formData,
          url:
            window.location.origin +
            "/api/business/inventory/image/active/" +
            this.$route.params.id,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
            "Content-Type": "multipart/form-data",
          },
        })
          .then(({ data }) => {
            if (typeof data.gallery !== "undefined") {
              vm.$data.gallery_list = data.gallery;
              vm.$data.n = parseInt(data.last_rank);
              vm.$data.selected_id = [];

              let success_message = "Image active status change.";
              toast.success(success_message);
              toast.close();
              Event.fire("save_complete", { success: "" });
            }
          })
          .catch((error) => {
            let error_message =
              "There were some errors while changing image active status.";

            toast.fail(error_message);
            toast.close();
          });
      }

      await vm.$helpers.gTagClickEvent(
        vm,
        "zd_action",
        "inventory",
        "image",
        "save"
      );
    },
    async saveSortRank() {
      const vm = this;
      let formData = new FormData();
      formData.append("gallery", JSON.stringify(this.gallery_list));

      let message = "Sorting gallery images...";
      const toast = new Toast(message);
      toast.fire();
      if (this.$route.params.id && this.$route.name == "inventory-update") {
        await axios({
          method: "post",
          data: formData,
          url:
            window.location.origin +
            "/api/business/inventory/image/sort/" +
            this.$route.params.id,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
            "Content-Type": "multipart/form-data",
          },
        })
          .then(({ data }) => {
            let success_message = "Gallery images have been sorted.";
            toast.success(success_message);
            Event.fire("save_complete", { success: "" });
            toast.close();
          })
          .catch((error) => {
            let error_message = "There were errors while sorting Image.";
            toast.fail(error_message);
            toast.close();
          });
      }

      await vm.$helpers.gTagClickEvent(
        vm,
        "zd_action",
        "inventory",
        "image",
        "sort"
      );
    },
    async onUpload() {
      const vm = this;
      let promises = [];
      this.galleryLoad = this.gallery_list.length > 0 ? true : false;

      let message = "Images Uploading...";
      const toast = new Toast(message);
      toast.fire();

      if (this.$route.params.id && this.$route.name == "inventory-update") {
        for (let file of this.selectedFile) {
          this.n += 1;
          let formData = new FormData();
          formData.append("file", file, file.name);
          formData.append("sort_rank", this.n);
          let instance = await axios({
            method: "post",
            url:
              window.location.origin +
              "/api/business/inventory/image/upload/" +
              this.$route.params.id,
            data: formData,
            headers: {
              Authorization: "Bearer " + window.$cookies.get("_token"),
              "Content-Type": "multipart/form-data",
            },
            onUploadProgress: (progressEvent) => {
              console.log(progressEvent.loaded / progressEvent.total);
            },
          })
            .then(({ data }) => {
              vm.galleryList();
            })
            .catch((error) => {});
          promises.push(instance);
        }
      }

      axios.all(promises).then((requests) => {
        requests.forEach((request) => {
          // request.then((res) => { console.log(res);}).catch((error) => {console.log(error);});
          // console.log(request);
        });

        let success_message = "Finished Uploading Images.";
        toast.success(success_message);
        toast.close();

        setTimeout(() => {
          vm.$data.galleryLoad = true;
          Event.fire("save_complete", { success: "" });
          vm.$helpers.gTagClickEvent(
            vm,
            "zd_action",
            "inventory",
            "image",
            "save"
          );
        }, 500);
      });
    },
    galleryList(load = false) {
      const vm = this;
      if (this.$route.params.id && this.$route.name == "inventory-update") {
        axios({
          method: "get",
          url:
            window.location.origin +
            "/api/business/inventory/image/" +
            this.$route.params.id,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
            "Content-Type": "multipart/form-data",
          },
        })
          .then(({ data }) => {
            if (typeof data.gallery !== "undefined") {
              vm.$data.gallery_list = data.gallery;
              if (!load) {
                vm.$data.n = parseInt(data.last_rank) + 1;
              } else {
                vm.$data.n = parseInt(data.last_rank);
              }
            }
          })
          .catch((error) => {});
      }
    },
    checkAll() {
      const vm = this;
      this.gallery_list.forEach((image) => {
        this.selected_id.push(image.id);
      });
      this.selected_id = [...new Set(this.selected_id)];
    },
  },
  created() {
    this.galleryList(true);
  },
};
</script>
<style lang="scss">
/* ==========================================================================
   Inventory — Media tab. Scoped to #media-block-list.
   ========================================================================== */
#media-block-list {
  .mg-page {
    padding: 8px 20px 96px;
    color: #0f172a;
  }

  .mg-notice {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 14px;
    margin-bottom: 14px;
    border: 1px solid #fde68a;
    border-radius: 10px;
    background: #fffbeb;
    color: #92400e;
    font-size: 13px;
    line-height: 1.5;
    i { font-size: 18px; margin-top: 1px; }
  }

  .mg-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    padding: 10px 12px;
    margin-bottom: 16px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #ffffff;
  }
  .mg-toolbar-left,
  .mg-toolbar-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }
  .mg-select-count {
    font-size: 13px;
    color: #475569;
    padding: 4px 10px;
    border-radius: 999px;
    background: #eff6ff;
    strong { color: #2563eb; }
  }

  .mg-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 36px;
    padding: 0 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    border: 1px solid transparent;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s, color 0.15s;
    i { font-size: 16px; position: static; top: 0; }
    &:disabled { opacity: 0.45; cursor: not-allowed; }
    &:focus { outline: none; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }
  }
  .mg-btn-primary {
    background: #2563eb;
    color: #ffffff;
    &:hover:not(:disabled) { background: #1d4ed8; }
  }
  .mg-btn-ghost {
    background: #ffffff;
    border-color: #e2e8f0;
    color: #475569;
    &:hover:not(:disabled) { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; }
  }
  .mg-btn-dark {
    background: #334155;
    color: #ffffff;
    &:hover:not(:disabled) { background: #1e293b; }
    &::after { margin-left: 6px; }
  }
  .mg-btn-group {
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .mg-dropdown {
    min-width: 160px;
    padding: 6px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
    .dropdown-item {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 8px 10px;
      border-radius: 6px;
      font-size: 13px;
      color: #334155;
      i { font-size: 16px; color: #64748b; position: static; top: 0; }
      &:hover { background: #f1f5f9; color: #0f172a; }
    }
    .mg-dropdown-danger,
    .mg-dropdown-danger i { color: #dc2626; }
    .mg-dropdown-danger:hover { background: #fef2f2; color: #dc2626; }
  }

  .mg-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 14px;
  }

  .mg-card {
    position: relative;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    transition: box-shadow 0.15s, border-color 0.15s;
    &:hover {
      border-color: #cbd5e1;
      box-shadow: 0 6px 18px rgba(15, 23, 42, 0.08);
    }
    &.is-selected {
      border-color: #2563eb;
      box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.35);
    }
  }
  .mg-checkbox-input { display: none; }

  .mg-thumb {
    position: relative;
    display: block;
    margin: 0;
    aspect-ratio: 4 / 3;
    background: #f1f5f9;
    cursor: pointer;
    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      cursor: pointer;
    }
  }

  .mg-check {
    position: absolute;
    top: 8px;
    left: 8px;
    width: 22px;
    height: 22px;
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.92);
    border: 1.5px solid #cbd5e1;
    color: transparent;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    opacity: 0;
    transition: opacity 0.15s;
    z-index: 3;
    pointer-events: none;
  }
  .mg-card:hover .mg-check,
  .mg-card.is-selected .mg-check { opacity: 1; }
  .mg-card.is-selected .mg-check {
    background: #2563eb;
    border-color: #2563eb;
    color: #ffffff;
  }

  .mask {
    position: absolute;
    inset: 0;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.4em;
    color: #ffffff;
    background: rgba(15, 23, 42, 0.55);
    text-shadow: none;
    pointer-events: none;
  }

  .mg-footer {
    margin-top: 14px;
    font-size: 13px;
    color: #64748b;
    strong { color: #0f172a; }
  }

  .flip-list-move { transition: transform 0.3s; }
  .ghost { opacity: 0.4; }
}
</style>
