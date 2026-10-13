<template>
  <div>
    <div
      class="app-content content fixed-height-block"
      style="overflow: visible"
    >
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <a
              class="nav-link d-flex align-items-center active"
              id="voucher-template-create-tab"
              data-toggle="tab"
              href="#voucher-template-create-panel"
              aria-controls="Voucher Template Create"
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
              id="voucher-template-create-panel"
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
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'title'
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
                                        <label>Description</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <fieldset>
                                          <generic-text-editor
                                            name="description"
                                            :options="editorOption"
                                            :elementId="'voucher_template_description'"
                                            :editor_style="'min-height:10em;'"
                                            v-model="form.description"
                                          >
                                          </generic-text-editor>
                                        </fieldset>
                                      </div>
                                      <!-- Description : END -->

                                      <!-- disclosure : START -->
                                      <div class="col-md-4">
                                        <label>Disclosure</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <textarea
                                              class="form-control"
                                              name=""
                                              id=""
                                              cols="30"
                                              rows="5"
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'disclosure'
                                                  ),
                                              }"
                                              @keyup="delete errors.disclosure"
                                              :title="errors.disclosure"
                                              v-model="form.disclosure"
                                            ></textarea>
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- disclosure : END -->

                                      <!-- value : START -->
                                      <div class="col-md-4">
                                        <label>Coupon Value</label>
                                      </div>
                                      <div class="col-md-3 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <input
                                              type="text"
                                              placeholder="Value"
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'value'
                                                  ),
                                              }"
                                              @keyup="delete errors.value"
                                              :title="errors.value"
                                              v-model="form.value"
                                              class="form-control"
                                            />
                                          </div>
                                        </fieldset>
                                      </div>
                                      <div class="w-100"></div>
                                      <!-- value : END -->

                                      <!-- expiry_date_type : START -->
                                      <div class="col-md-4">
                                        <label>Expiry Date Type</label>
                                      </div>
                                      <div class="col-md-4 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <select
                                              name="expiry_date_type"
                                              id="expiry_date_type"
                                              class="form-control"
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'expiry_date_type'
                                                  ),
                                              }"
                                              @change="
                                                delete errors.expiry_date_type
                                              "
                                              :title="errors.expiry_date_type"
                                              v-model="form.expiry_date_type"
                                            >
                                              <option
                                                selected
                                                disabled
                                                value=""
                                              >
                                                --- Select Expiry Date Type ---
                                              </option>
                                              <option value="STATIC">
                                                ON FIXED DATE
                                              </option>
                                              <option value="RELATIVE">
                                                FROM DATE OF CREATION
                                              </option>
                                            </select>
                                          </div>
                                        </fieldset>
                                      </div>
                                      <div class="w-100"></div>
                                      <!-- expiry_date_type : END -->

                                      <!-- expiry_date : START -->
                                      <div
                                        class="col-md-4"
                                        v-show="
                                          form.expiry_date_type === 'STATIC'
                                        "
                                      >
                                        <label>On Date</label>
                                      </div>
                                      <div
                                        class="col-md-4 form-group"
                                        v-show="
                                          form.expiry_date_type === 'STATIC'
                                        "
                                      >
                                        <fieldset
                                          class="has-icon-left"
                                          style="position: relative"
                                        >
                                          <date-picker
                                            placeholder="Expiry Date"
                                            v-model="form.expiry_date"
                                            :format="'MM/DD/YYYY'"
                                            :value-type="'MM/DD/YYYY'"
                                            id="expiry_date"
                                            :class="{
                                              'is-invalid':
                                                errors.hasOwnProperty(
                                                  'expiry_date'
                                                ),
                                            }"
                                            @change="delete errors.expiry_date"
                                            :title="errors.expiry_date"
                                          >
                                          </date-picker>
                                        </fieldset>
                                        <small
                                          id="expiry_date-error"
                                          class="form-text invalid-feedback"
                                        ></small>
                                      </div>
                                      <div
                                        class="w-100"
                                        v-show="
                                          form.expiry_date_type === 'STATIC'
                                        "
                                      ></div>
                                      <!-- expiry_date : END -->

                                      <!-- relative_date_section : START -->
                                      <div
                                        class="col-md-4"
                                        v-show="
                                          form.expiry_date_type === 'RELATIVE'
                                        "
                                      >
                                        <label>After Period Of</label>
                                      </div>
                                      <div
                                        class="col-md-6 form-group"
                                        v-show="
                                          form.expiry_date_type === 'RELATIVE'
                                        "
                                      >
                                        <fieldset>
                                          <div class="input-group">
                                            <input
                                              type="text"
                                              placeholder="Interval"
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'expiry_date_interval'
                                                  ),
                                              }"
                                              @keyup="
                                                delete errors.expiry_date_interval
                                              "
                                              :title="
                                                errors.expiry_date_interval
                                              "
                                              v-model="
                                                form.expiry_date_interval
                                              "
                                              class="form-control mr-1"
                                            />
                                            <select
                                              class="custom-select form-control"
                                              v-model="
                                                form.expiry_date_interval_unit
                                              "
                                              id="expiry_date_interval_unit"
                                              name="expiry_date_interval_unit"
                                            >
                                              <option
                                                value=""
                                                selected
                                                disabled
                                              >
                                                -- Select Interval Unit --
                                              </option>
                                              <option value="DAYS">DAYS</option>
                                              <option value="WEEKS">
                                                WEEKS
                                              </option>
                                              <option value="MONTHS">
                                                MONTHS
                                              </option>
                                              <option value="YEARS">
                                                YEARS
                                              </option>
                                            </select>
                                          </div>
                                        </fieldset>
                                      </div>

                                      <div
                                        class="w-100"
                                        v-show="
                                          form.expiry_date_type === 'RELATIVE'
                                        "
                                      ></div>
                                      <!-- relative_date_section : END -->

                                      <!-- template : START -->
                                      <div class="col-md-4">
                                        <label>Template</label>
                                      </div>
                                      <div class="col-md-4 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <select
                                              name="template"
                                              id="template"
                                              class="form-control"
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'template'
                                                  ),
                                              }"
                                              @change="delete errors.template"
                                              :title="errors.template"
                                              v-model="form.template"
                                            >
                                              <option
                                                selected
                                                disabled
                                                value=""
                                              >
                                                --- Select Template ---
                                              </option>
                                              <option
                                                :key="
                                                  voucher_template.file_name
                                                "
                                                :value="
                                                  voucher_template.file_name
                                                "
                                                v-for="voucher_template in voucher_templates"
                                              >
                                                {{ voucher_template.label }}
                                              </option>
                                            </select>
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- template : END -->
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
import GenericTextEditor from "../WebComponents/GenericTextEditor.vue";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

import Toast from "../../library/Toast";
export default {
  name: "VoucherTemplateCreate",
  data() {
    return {
      title: "Create",
      voucher_templates: [],
      form: {
        title: "",
        disclosure: "",
        description: "",
        expiry_date_type: "STATIC",
        expiry_date: null,
        expiry_date_interval: "0",
        expiry_date_interval_unit: "DAYS",
        template: "",
        value: "",
      },
      errors: [],
      editorOption: {
        modules: {
          //toolbar: '#toolbar',
        },
      },
    };
  },
  components: {
    DatePicker,
    GenericTextEditor,
  },
  computed: {
    updateView() {
      return (
        this.$route.name == "voucher-template-update" && this.$route.params.id
      );
    },
  },
  methods: {
    async getVoucherConfigurations() {
      let request_url =
        window.location.origin + "/api/business/configurations/active/voucher";
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
            if (element.key == "voucher_templates") {
              if (this.$helpers.isValidJsonString(element.value)) {
                this.voucher_templates = JSON.parse(element.value);
              }
            }
          }
        }
      });
    },
    async getVoucherTemplate(id) {
      const vm = this;
      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/voucher-templates/show/${id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          for (const key in this.form) {
            if (!["expiry_date"].includes(key)) {
              if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
                this.form[key] = data[key];
              }
            }
          }

          let expiry_date = moment(data.expiry_date);
          this.form.expiry_date = expiry_date.isValid()
            ? expiry_date.format("MM/DD/YYYY")
            : null;
        })
        .catch(({ response }) => {
          let error =
            "There were some errors in retrieving the voucher template";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
    async save() {
      const vm = this;

      let formData = new FormData();
      for (const key in this.form) {
        if (!["expiry_date"].includes(key)) {
          if (this.form.hasOwnProperty(key) && this.form[key] != null) {
            formData.append(key, this.form[key]);
          }
        }
      }

      let expiry_date = moment(this.form.expiry_date, "MM/DD/YYYY");

      formData.append(
        "expiry_date",
        expiry_date.isValid() ? expiry_date.format("MM/DD/YYYY") : null
      );

      let request_url = window.location.origin;

      request_url = vm.updateView
        ? `${request_url}/api/business/voucher-templates/update/${this.$route.params.id}`
        : `${request_url}/api/business/voucher-templates/create`;

      let request_message = vm.updateView
        ? `Updating Voucher Template ...`
        : `Creating New Voucher Template ...`;

      const toast = new Toast(request_message);
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
          vm.$router.back();
        })
        .catch(function (error) {
          let error_message = vm.updateView
            ? "There were errors while updating the voucher template."
            : "There were some errors while creating new voucher template.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
  },
  async created() {
    await this.getVoucherConfigurations();
    if (this.updateView) {
      this.title = "Update";
      this.getVoucherTemplate(this.$route.params.id);
    }
  },
};
</script>
<style lang="scss">
#voucher-template-create-panel .vdpComponent input {
  padding-left: 2.2em !important;
  line-height: 1.4;
  color: #777;
  background-color: #ffffff !important;
  background-clip: padding-box;
  border: 1px solid #dfe3e7;
  border-radius: 0.267rem;
}
</style>
