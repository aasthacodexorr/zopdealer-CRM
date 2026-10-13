<template>
  <div
    role="tabpanel"
    class="tab-pane"
    id="setting-account-testdrive"
    aria-labelledby="stacked-pill-1"
    aria-expanded="true"
  >
    <div class="content-wrapper m-0 border-bottom todo-fixed-search">
      <form class="webapp-form">
        <div class="row">
          <div class="col-md-5 col-12">
            <ul class="pl-0">
              <li class="d-inline-block mr-1">
                <div class="content-list-box">
                  <p class="p-0 m-0">
                    <span
                      class="blue-color text-bold-400 title-sizeincrease-font"
                      >TestDrive Settings
                   </span>
                  </p>
                </div>
              </li>
            </ul>
          </div>
          <div class="col-md-7 col-12">
            <ul class="p-0 float-right d-flex">
              <li class="d-inline-block">
                <button
                  type="reset"
                  class="d-none btn btn-light-secondary mr-1"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="!submit"
                  @click.prevent="saveConfiguration"
                  class="btn btn-primary"
                >
                  Save
                </button>
              </li>
            </ul>
          </div>
        </div>
      </form>
    </div>
    <div
      class="list-right-task-list list-group ps p-0"
      style="overflow-y: auto !important"
    >
      <form class="webapp-form">
        <div class="content-wrapper mt-0 pb-1 pt-2 border-bottom">
          <div class="list-image-content">

            <div
              class="mt-1 mb-1 pb-1 pt-2"
            >
              <div class="list-image-content">
                <div class="row">
                  <div class="col-md-4">
                    <label>Number Plate List</label>
                  </div>
                </div>
                <div class="row contact-repeater">
                  <div class="col-md-4">
                    <button
                      class="btn btn-icon btn-primary"
                      @click="count_of_number_plates+= 1"
                      type="button"
                    >
                      <i class="bx bx-plus"></i>Add New Number Plate
                    </button>
                  </div>
                  <div class="col-md-5 form-group" data-repeater-list="contact">
                    <!-- start Loop for test drive group -->
                    <div
                      class="row"
                      v-for="n in this.count_of_number_plates"
                      :key="n"
                    >
                      <div class="col-md-10 col-12 form-group">
                        <input
                          type="text"
                          class="form-control"
                          v-model="form.testdrive_number_plates[n - 1]"
                        />
                      </div>
                      <div class="col-md-2 col-12 form-group pl-0">
                        <button
                          class="btn btn-danger text-nowrap px-1"
                          @click="
                            form.testdrive_number_plates.splice(n - 1, 1);
                            count_of_number_plates -= 1;
                          "
                          type="button"
                        >
                          <i class="bx bx-x"></i>
                        </button>
                      </div>
                    </div>
                    <!-- end Loop for test drive group -->
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import Toast from "../../library/Toast";
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";
import { quillEditor } from "vue-quill-editor";

export default {
  name: "SettingsAccountTestDrive",
  data() {
    return {
      submit: true,
      count_of_number_plates: 0,
      form: {
        testdrive_number_plates: [],
      },
    };
  },
  components: {
    quillEditor,
  },
  computed: {

  },
  methods: {
    async getTestDriveConfiguration() {
      let request_url =
        window.location.origin + "/api/business/configurations/testdrive";

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
            if (element.key == "testdrive_number_plates" && element.value != "") {
              this.form.hasOwnProperty(element.key)
                ? (this.form[element.key] = JSON.parse(element.value))
                : null;
            } else {
              this.form.hasOwnProperty(element.key)
                ? (this.form[element.key] = element.value)
                : null;
            }
          }
        }

        if (this.form.testdrive_number_plates == "") {
          this.form.testdrive_number_plates = [];
        } else {
          this.count_of_number_plates = this.form.testdrive_number_plates.length;
        }
      });
    },
    saveConfiguration() {
      const vm = this;
      this.submit = false;
      let formData = new FormData();
      for (const key in this.form) {
        let element = this.form[key];
        if (key != "testdrive_number_plates") {
          if (element.trim() !== "") {
            formData.append(key, this.form[key]);
          }
        }
      }

      formData.append(
        "testdrive_number_plates",
        JSON.stringify(this.form["testdrive_number_plates"])
      );

      let request_url = window.location.origin + "/api/business/configurations";
      let message = "Saving account settings...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          let success_message = "Finished saving configurations.";
          toast.success(success_message);
          toast.close();


          setTimeout(() => {
            vm.$data.submit = true;
          }, 500);
        })
        .catch((error) => {
          let error_message = "There were some errors in saving settings.";
          toast.fail(error_message);
          toast.close();

          setTimeout(() => {
            vm.$data.submit = true;
          }, 500);
        });
    },
  },
  mounted() {
    const vm = this;
    this.getTestDriveConfiguration();
  },
};
</script>
