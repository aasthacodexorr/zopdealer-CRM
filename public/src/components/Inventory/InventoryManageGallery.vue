<template>
  <div
    class="tab-pane"
    id="media-block-list"
    aria-labelledby="information-tab"
    role="tabpanel"
  >
    <!-- photo  form layout section start -->
    <section id="dropzone-examples">
      <div class="row">
        <vue-easy-lightbox
          escDisabled
          moveDisabled
          :visible="visible"
          :imgs="galleryLinks"
          @hide="handleHide"
        ></vue-easy-lightbox>
        <div class="col-12">
          <div class="card shadow-none mb-0">
            <div class="card-content">
              <div class="card-body pt-0">
                <!-- image component using dropzone -->
                <div class="row p-1">
                  <div
                    role="alert"
                    class="alert alert-light-warning mb-0 fade show"
                  >
                    NOTE: HEIC/HEIF and WebP Images will be automatically
                    converted to JPG to provide broader support for third-party
                    syndication. Maximum Image Size acceptable is 30MB.
                  </div>
                </div>
                <div class="row p-1">
                  <div class="col-xs-6 col-md-4">
                    <input
                      ref="fileInput"
                      type="file"
                      style="display: none"
                      multiple
                      @change="onFileChanged"
                    />
                    <button
                      style="font-weight: 800"
                      type="button"
                      class="btn btn-primary"
                      @click="$refs.fileInput.click()"
                    >
                      <i
                        style="font-size: 1.4em; padding-right: 0.5em"
                        class="bx bxs-cloud-upload"
                      ></i>
                      Upload
                    </button>
                  </div>
                  <div
                    class="col-xs-6 col-md-2 text-right"
                    style="padding: 0.5em"
                  >
                    <span v-if="!checkedCount" style="vertical-align: baseline"
                      >Select Count : {{ selectedCount }}</span
                    >
                  </div>
                  <div class="col-xs-6 col-md-6">
                    <button @click="visible = true" class="btn btn-secondary">
                      <i class="bx bx-photo-album pr-1"></i>Show Gallery
                    </button>
                    <div
                      class="btn-group"
                      role="group"
                      aria-label="Basic example"
                    >
                      <button
                        @click="selected_id = []"
                        :disabled="checkedCount"
                        type="button"
                        class="btn btn-primary"
                      >
                        Uncheck All
                      </button>
                      <button
                        @click="checkAll"
                        type="button"
                        :disabled="allChecked"
                        class="btn btn-info"
                      >
                        Check All
                      </button>
                      <div class="btn-group" role="group">
                        <button
                          :disabled="checkedCount"
                          id="btnGroupDrop1"
                          type="button"
                          class="btn btn-secondary dropdown-toggle"
                          data-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          Action
                        </button>
                        <div
                          class="dropdown-menu"
                          aria-labelledby="btnGroupDrop1"
                        >
                          <button
                            @click="activationImages(1)"
                            class="dropdown-item"
                          >
                            Active
                          </button>
                          <button
                            @click="activationImages(0)"
                            class="dropdown-item"
                          >
                            Inactive
                          </button>
                          <button @click="deleteImages" class="dropdown-item">
                            Delete
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- image file uploads -->
                <!-- photos gallery -->
                <!-- Draggable List : OPEN -->
                <draggable
                  v-show="galleryLoad"
                  tag="div"
                  :list="gallery_list"
                  v-bind="dragOptions"
                  @start="isDragging = true"
                  @end="isDragging = false"
                  class="row"
                  @change="saveSortRank"
                  handle=".handle"
                >
                  <transition-group
                    tag="div"
                    class="row"
                    type="transition"
                    name="flip-list"
                  >
                    <div
                      v-for="image in gallery_list"
                      :key="image.id"
                      class="col-lg-2 col-md-3 col-xs-6 p-0 m-1"
                      style="min-width: 200px"
                    >
                      <input
                        style="display: none"
                        type="checkbox"
                        v-model="selected_id"
                        :id="'checkbox_' + image.id"
                        :value="image.id"
                      />
                      <label :for="'checkbox_' + image.id">
                        <img
                          style="cursor: pointer; min-width: 200px"
                          :src="parseAbsoluteOrRelativeUrl(image.image_url)"
                          class="handle img-thumbnail"
                        />
                        <i
                          v-if="image.active == '0'"
                          class="bx bx-hide mask"
                        ></i>
                      </label>
                    </div>
                  </transition-group>
                </draggable>
                <!-- Draggable List : CLOSE -->
                <div class="row">
                  <div class="col-12 font-weight-bold">
                    <h5>Number of Images : {{ imagesCount }}</h5>
                  </div>
                </div>
                <!-- end photos gallery -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- remove all thumbnails file upload ends -->
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
#media-block-list input:checked + label {
  opacity: 0.6;
}
#media-block-list input:checked + label img {
  border: 0px;
}

#media-block-list .mask {
  position: absolute;
  z-index: 2;
  font-size: 3em;
  color: white;
  text-shadow: -1px 0px #ccc;
  left: 0;
  background: #0006;
}
</style>
