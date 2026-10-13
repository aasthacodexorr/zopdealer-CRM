<template>
  <div v-if="isModuleDetailsAvailable">
    <div
      class="app-content content fixed-height-block"
      style="overflow: visible"
    >
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <a
              class="nav-link d-flex align-items-center active"
              id="schedule-template-create-tab"
              data-toggle="tab"
              href="#schedule-template-create-panel"
              aria-controls="Schedule Template Create"
              role="tab"
              aria-selected="true"
            >
              <span class="">{{ title }}</span>
            </a>
          </li>
          <div class="btn-feild-box">
            <button
              type="submit"
              @click.prevent="save"
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
              id="schedule-template-create-panel"
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
                                      <h1 class="col-12">
                                        Schedule {{ page_title }}
                                      </h1>
                                      <!-- Frequency : START -->
                                      <div class="col-md-4">
                                        <label style="color: red"
                                          >Frequency *</label
                                        >
                                      </div>
                                      <div class="col-md-4 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <select
                                              name="frequency"
                                              id="frequency"
                                              class="form-control"
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'frequency'
                                                  ),
                                              }"
                                              @change="delete errors.frequency"
                                              :title="errors.frequency"
                                              v-model="form.frequency"
                                            >
                                              <option
                                                selected
                                                disabled
                                                value=""
                                              >
                                                --- Select Frequency ---
                                              </option>
                                              <option value="Daily">
                                                Daily
                                              </option>
                                              <option value="Weekly">
                                                Weekly
                                              </option>
                                              <option value="Monthly">
                                                Monthly
                                              </option>
                                              <option value="Quarterly">
                                                Quarterly
                                              </option>
                                              <option value="Yearly">
                                                Yearly
                                              </option>
                                            </select>
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- Frequency : END -->

                                      <div class="w-100"></div>

                                      <!-- DateTime : START -->
                                      <div class="col-md-4">
                                        <label style="color: red"
                                          >Schedule Date Time *</label
                                        >
                                      </div>
                                      <div class="col-md-4 form-group">
                                        <fieldset
                                          class="has-icon-left"
                                          style="position: relative"
                                        >
                                          <date-picker
                                            placeholder="Start Date"
                                            v-model="form.datetime"
                                            :format="'MM/DD/YYYY HH:mm'"
                                            :value-type="'MM/DD/YYYY HH:mm'"
                                            :minute-step="5"
                                            type="datetime"
                                            id="datetime"
                                            :class="{
                                              'is-invalid':
                                                errors.hasOwnProperty(
                                                  'datetime'
                                                ),
                                            }"
                                            @change="delete errors.datetime"
                                            :title="errors.datetime"
                                            :clearable="false"
                                          >
                                          </date-picker>
                                        </fieldset>
                                        <small
                                          id="datetime-error"
                                          class="form-text invalid-feedback"
                                        ></small>
                                      </div>
                                      <!-- DateTime : END -->

                                      <div class="w-100"></div>

                                      <!-- Timezone: START -->

                                      <div class="col-md-4">
                                        <label style="color: red"
                                          >Timezone *</label
                                        >
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <v-select
                                          id="timezone"
                                          v-model="form.timezone"
                                          @change="delete errors.timezone"
                                          :title="errors.timezone"
                                          :class="{
                                            'is-invalid':
                                              errors.hasOwnProperty('timezone'),
                                          }"
                                          placeholder="Timezone"
                                          clearable
                                          :options="getTimeZones"
                                        >
                                        </v-select>
                                        <small
                                          id="timezone-error"
                                          class="form-text invalid-feedback"
                                          >{{ errors.timezone }}</small
                                        >
                                      </div>
                                      <!-- Timezone: END -->
                                      <div class="w-100"></div>

                                      <!-- Primary Recipient : START -->

                                      <div class="col-md-4">
                                        <label>Primary Recipients</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <v-select
                                          id="primary_recipients"
                                          v-model="form.primary_recipients"
                                          @change="
                                            delete errors.primary_recipients
                                          "
                                          :title="errors.primary_recipients"
                                          :class="{
                                            'is-invalid':
                                              errors.hasOwnProperty(
                                                'primary_recipients'
                                              ),
                                          }"
                                          placeholder="Primary Recipients"
                                          multiple
                                          taggable
                                          clearable
                                          :options="userMapper"
                                        >
                                        </v-select>
                                        <small
                                          id="primary_recipients-error"
                                          class="form-text invalid-feedback"
                                          >{{
                                            errors.primary_recipients
                                          }}</small
                                        >
                                      </div>
                                      <!-- Primary Recipient : END -->

                                      <div class="w-100"></div>

                                      <!-- Additional Recipient : START -->

                                      <div class="col-md-4">
                                        <label>Additional Recipients</label>
                                      </div>
                                      <div class="col-md-8 form-group">
                                        <v-select
                                          id="additional_recipients"
                                          v-model="form.additional_recipients"
                                          @change="
                                            delete errors.additional_recipients
                                          "
                                          :title="errors.additional_recipients"
                                          :class="{
                                            'is-invalid': errors.hasOwnProperty(
                                              'additional_recipients'
                                            ),
                                          }"
                                          placeholder="Additional Recipients"
                                          multiple
                                          taggable
                                          clearable
                                          :options="[]"
                                        >
                                          <template slot="no-options">
                                            Type the valid email and press enter
                                          </template>
                                        </v-select>
                                        <small
                                          id="additional_recipients-error"
                                          class="form-text invalid-feedback"
                                          >{{
                                            errors.additional_recipients
                                          }}</small
                                        >
                                      </div>
                                      <!-- Additional Recipient : END -->

                                      <div class="w-100"></div>
                                      <!-- Format : START -->
                                      <div class="col-md-4">
                                        <label>Format</label>
                                      </div>
                                      <div class="col-md-4 form-group">
                                        <fieldset>
                                          <div class="input-group">
                                            <select
                                              name="format"
                                              id="format"
                                              class="form-control"
                                              :class="{
                                                'is-invalid':
                                                  errors.hasOwnProperty(
                                                    'format'
                                                  ),
                                              }"
                                              @change="delete errors.format"
                                              :title="errors.format"
                                              v-model="form.format"
                                            >
                                              <option
                                                selected
                                                disabled
                                                value=""
                                              >
                                                --- Select Format ---
                                              </option>
                                              <option value="CSV">CSV</option>
                                              <option value="XLS">XLS</option>
                                            </select>
                                          </div>
                                        </fieldset>
                                      </div>
                                      <!-- Format : END -->

                                      <div class="w-100"></div>

                                      <!-- Enable Schedule : START -->
                                      <div class="col-md-4">
                                        <label>Enable</label>
                                      </div>
                                      <div class="col-md-4 form-group">
                                        <div
                                          class="custom-control custom-switch"
                                        >
                                          <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            id="enableScheduleTemplateSwitch"
                                            name="Visibility"
                                            :true-value="'1'"
                                            :false-value="'0'"
                                            checked
                                            v-model="form.enable"
                                          />
                                          <label
                                            class="custom-control-label"
                                            for="enableScheduleTemplateSwitch"
                                          >
                                            <span class="switch-icon-left">
                                              ON</span
                                            >
                                            <span class="switch-icon-right"
                                              >OFF</span
                                            >
                                          </label>
                                        </div>
                                      </div>
                                      <!-- Enable Schedule : END -->
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
import Toast from "../../../library/Toast";
import { mapGetters, mapActions } from "vuex";
import vSelect from "vue-select";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

export default {
  props: ["application", "app_metadata", "module", "module_id", "page_title"],
  name: "ScheduleTemplateCreate",
  data() {
    return {
      title: "Create",
      form: {
        datetime: null,
        frequency: "Monthly",
        format: "CSV",
        enable: "1",
        timezone: "Canada/Eastern",
        primary_recipients: [],
        additional_recipients: [],
      },
      errors: [],
    };
  },
  components: {
    DatePicker,
    "v-select": vSelect,
  },
  computed: {
    ...mapGetters("users", ["allUsers"]),
    getTimeZones() {
      return moment.tz.names();
    },
    isModuleDetailsAvailable() {
      return (
        typeof this.module !== "undefined" &&
        typeof this.module_id !== "undefined"
      );
    },
    userMapper() {
      return this.allUsers.map((user) => `${user.name}`);
    },
    updateView() {
      return (
        this.$route.name == "schedule-template-update" && this.$route.params.id
      );
    },
  },
  methods: {
    ...mapActions("users", ["getAllUsers"]),
    async save() {
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.form) {
        if (
          !["primary_recipients", "additional_recipients", "datetime"].includes(
            key
          )
        ) {
          if (this.form.hasOwnProperty(key) && this.form[key] != null) {
            formData.append(key, this.form[key]);
          }
        }
      }

      const attributes = ["primary_recipients", "additional_recipients"];

      let datetime = moment(this.form.datetime, "MM/DD/YYYY HH:mm");

      formData.append(
        "datetime",
        datetime.isValid() ? datetime.format("MM/DD/YYYY HH:mm") : null
      );

      for (const attribute of attributes) {
        if (Array.isArray(this.form[attribute])) {
          formData.append(attribute, JSON.stringify(this.form[attribute]));
        } else {
          formData.append(attribute, "[]");
        }
      }

      formData.append("module", this.module);
      formData.append("module_id", this.module_id);

      if (typeof this.page_title != "undefined") {
        formData.append("title", this.page_title);
      }

      let request_url = window.location.origin;

      //success message setting
      let success_message = this.updateView
        ? `Schedule Template Updated Successfully.`
        : `New Schedule Template Created.`;

      //api setting
      request_url = this.updateView
        ? `${request_url}/api/business/schedule-templates/update/${this.$route.params.id}`
        : `${request_url}/api/business/schedule-templates/create`;

      //message during request
      let request_message = this.updateView
        ? `Updating Schedule Template ...`
        : `Creating New Schedule Template ...`;

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

          vm.$router.back();
        })
        .catch(function (error) {
          let error_message = vm.updateView
            ? "There were errors while updating the schedule template."
            : "There were some errors while creating new schedule template.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    async getScheduleTemplate(id) {
      const vm = this;
      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/schedule-templates/show/${id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          for (const key in this.form) {
            if (
              ![
                "primary_recipients",
                "additional_recipients",
                "datetime",
              ].includes(key)
            ) {
              if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
                this.form[key] = data[key];
              }
            }
          }

          const attributes = ["primary_recipients", "additional_recipients"];

          for (const attribute of attributes) {
            if (this.$helpers.isValidJsonString(data[attribute])) {
              this.form[attribute] = JSON.parse(data[attribute]);
            } else {
              this.form[attribute] = [];
            }
          }

          let datetime = moment(data.datetime);
          this.form.datetime = datetime.isValid()
            ? datetime.format("MM/DD/YYYY HH:mm")
            : null;
        })
        .catch(({ response }) => {
          let error =
            "There were some errors in retrieving the schedule template";
          const toast = new Toast(error);
          toast.fire();
          toast.fail(error);
          toast.close();
        });
    },
  },
  async created() {
    this.getAllUsers();
    if (this.updateView) {
      this.title = "Update";
      this.getScheduleTemplate(this.$route.params.id);
    }
  },
};
</script>
<style lang="scss">
#schedule-template-create-panel {
  .vdpComponent {
    height: 3.9em !important;
  }
  .vdpComponent > input {
    padding-left: 2.2em !important;
    line-height: 1.4;
    color: #777;
    background-color: #ffffff !important;
    background-clip: padding-box;
    border: 1px solid #dfe3e7;
    border-radius: 0.267rem;
    font-size: 1.4em;
  }

  .vdpComponent input {
    background: white;
    border: 0px;
  }
  .vdpComponent input:read-only {
    background: white !important;
    padding-right: 0em;
    font-size: 1.4em;
    border: 0px;
    padding-left: 0px !important;
  }

  .webapp-form .form-group input {
    height: auto;
  }

  .vdpComponent .vdpTimeUnit {
    min-width: 50px;
  }

  .notification-icon-small {
    font-size: 0.5em;
    height: 50px;
    width: 50px;
  }

  .vdpComponent .vdpClearInput {
    display: none !important;
  }
}
</style>
