<template>
  <div>
    <div
      class="app-content content fixed-height-block"
      style="overflow: visible"
    >
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <router-link
              title="Back To List"
              tag="a"
              :to="{ name: 'dvi-template-item-list' }"
              class="nav-link pr-0 pl-0 d-flex align-items-center"
              exact
            >
              <i class="bx bx-caret-left" style="font-size: 1.4em"></i>
            </router-link>
          </li>
          <li class="nav-item pr-0 pl-0">
            <a
              class="nav-link d-flex align-items-center active"
              id="dvi-template-item-create-tab"
              data-toggle="tab"
              href="#dvi-template-item-create-panel"
              aria-controls="DVI Template Create"
              role="tab"
              aria-selected="true"
            >
              <span class="">{{ title }}</span>
            </a>
          </li>
          <div class="btn-feild-box">
            <button
              type="submit"
              @click.prevent="save()"
              class="btn btn-primary mr-1"
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
        <div class="content-body" style="height: 80vh">
          <!-- form layout section start -->
          <div class="tab-content">
            <div
              class="tab-pane active"
              id="dvi-template-item-create-panel"
              aria-labelledby="information-tab"
              role="tabpanel"
            >
              <!-- form layout section start -->
              <section id="general">
                <div class="row match-height">
                  <div class="col-md-12 col-12">
                    <div class="card shadow-none mb-0">
                      <div class="card-content">
                        <div class="card-body pt-0">
                          <form class="form form-horizontal webapp-form">
                            <div class="form-body">
                              <div class="row">
                                <div class="col-md-12 col-12">
                                  <div class="m-width-s">
                                    <div class="row">
                                      <!-- Title : START -->
                                      <div class="col-md-4">
                                        <label>Title*</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <input
                                              type="text"
                                              id="title"
                                              class="form-control"
                                              name="title"
                                              placeholder="Title"
                                              :class="{
                                                'is-invalid': errors.hasOwnProperty(
                                                  'title',
                                                ),
                                              }"
                                              @keyup="delete errors.title"
                                              :title="errors.title"
                                              v-model="form.title"
                                            />
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- Title : END -->

                                      <!-- Description : START -->
                                      <div class="col-md-4">
                                        <label>Short Description</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <fieldset>
                                          <textarea
                                            name="description"
                                            placeholder="Short Description to assist vehicle inspector"
                                            class="form-control"
                                            v-model="form.description"
                                          ></textarea>
                                        </fieldset>
                                      </div>
                                      <!-- Description : END -->

                                      <!-- Type : START -->
                                      <div class="col-md-4">
                                        <label>Type*</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <select
                                              type="text"
                                              id="type"
                                              v-model="form.type"
                                              :class="{
                                                'is-invalid': errors.hasOwnProperty(
                                                  'type',
                                                ),
                                              }"
                                              @change="delete errors.type"
                                              :title="errors.type"
                                              class="custom-select form-control mr-1"
                                              placeholder="Type"
                                              name="type"
                                            >
                                              <option value="" selected>
                                                -- Select A Type --
                                              </option>
                                              <option value="PASSFAIL">
                                                Pass/Fail
                                              </option>
                                              <option value="PASSFAILWITHNA">
                                                Pass/Fail with NA
                                              </option>
                                              <option value="COMMENT">
                                                Comment
                                              </option>
                                              <option value="HEADER">
                                                Section Header
                                              </option>
                                            </select>
                                          </div>
                                          <small v-if="form.type === 'PASSFAIL'"
                                            >Displays PASS or FAIL option</small
                                          >
                                          <small
                                            v-if="
                                              form.type === 'PASSFAILWITHNA'
                                            "
                                            >Displays PASS, FAIL and Not
                                            Applicable option</small
                                          >
                                          <small v-if="form.type === 'COMMENT'"
                                            >Displays a required comment
                                            box</small
                                          >
                                          <small v-if="form.type === 'HEADER'"
                                            >Displays a sectional header for
                                            visual clarity</small
                                          >
                                        </fieldset>
                                      </div>
                                      <!-- Type : END -->

                                      <!-- Enable Comment : START -->
                                      <div
                                        class="col-md-4"
                                        v-if="form.type !== 'HEADER'"
                                      >
                                        <label>Enable Comment?</label>
                                      </div>
                                      <div
                                        class="col-md-8 form-group"
                                        v-if="form.type !== 'HEADER'"
                                      >
                                        <fieldset>
                                          <div class="input-group">
                                            <div
                                              class="custom-control custom-switch"
                                            >
                                              <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="enable_comment"
                                                name="enable_comment"
                                                v-model="form.enable_comment"
                                                :true-value="'1'"
                                                :false-value="'0'"
                                              />
                                              <label
                                                class="custom-control-label"
                                                for="enable_comment"
                                              >
                                                <span class="switch-icon-left">
                                                  YES</span
                                                >
                                                <span class="switch-icon-right"
                                                  >NO</span
                                                >
                                              </label>
                                            </div>
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- Enable Comment : END -->

                                      <!-- Enable Attachments : START -->
                                      <div
                                        class="col-md-4"
                                        v-if="form.type !== 'HEADER'"
                                      >
                                        <label>Enable Attachments?</label>
                                      </div>
                                      <div
                                        class="col-md-8 form-group"
                                        v-if="form.type !== 'HEADER'"
                                      >
                                        <fieldset>
                                          <div class="input-group">
                                            <div
                                              class="custom-control custom-switch"
                                            >
                                              <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="enable_attachments"
                                                name="enable_attachments"
                                                v-model="
                                                  form.enable_attachments
                                                "
                                                :true-value="'1'"
                                                :false-value="'0'"
                                              />
                                              <label
                                                class="custom-control-label"
                                                for="enable_attachments"
                                              >
                                                <span class="switch-icon-left">
                                                  YES</span
                                                >
                                                <span class="switch-icon-right"
                                                  >NO</span
                                                >
                                              </label>
                                            </div>
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- Enable Attachments : END -->
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

import Toast from "../../library/Toast";
export default {
  name: "DVITemplateItemCreate",
  data() {
    return {
      title: "Create",
      form: {
        title: "",
        description: "",
        type: "",
        enable_comment: "0",
        enable_attachments: "1",
      },
      errors: [],
    };
  },
  components: {
    DatePicker,
  },
  computed: {
    updateView() {
      return (
        this.$route.name == "dvi-template-item-update" && this.$route.params.id
      );
    },
  },
  methods: {
    getDVITemplateItem(id) {
      const vm = this;
      axios({
        method: "get",
        url: `${window.location.origin}/api/business/dvi-template-items/show/${id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }
        })
        .catch(({ response }) => {
          let error = "There were some errors in retrieving the DVI template";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
    resetForm() {
      this.form.title = "";
      this.form.description = "";
      this.form.type = "";
      this.form.enable_comment = "0";
      this.form.enable_attachments = "1";
      this.errors = [];
    },
    save() {
      const vm = this;

      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && this.form[key] != null) {
          formData.append(key, this.form[key]);
        }
      }

      let request_url = window.location.origin;

      request_url = vm.updateView
        ? `${request_url}/api/business/dvi-template-items/update/${this.$route.params.id}`
        : `${request_url}/api/business/dvi-template-items/create`;

      let request_message = vm.updateView
        ? `Updating DVI Item template ...`
        : `Creating New DVI Item template ...`;

      const toast = new Toast(request_message);
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
          toast.success(data.success);
          toast.close();
          if (vm.updateView) {
            vm.$router.back();
          } else {
            vm.resetForm();
          }
        })
        .catch(function(error) {
          let error_message = vm.updateView
            ? "There were errors while updating the DVI Item template."
            : "There were some errors while creating new DVI Item template.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
  },
  async created() {
    if (this.updateView) {
      this.title = "Update";
      this.getDVITemplateItem(this.$route.params.id);
    }
  },
};
</script>
