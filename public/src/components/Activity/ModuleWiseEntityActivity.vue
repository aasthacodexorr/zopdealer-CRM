<template>
  <div
    class="tab-pane"
    id="generic-view-activities"
    aria-labelledby="generic-view-activities"
    role="tabpanel"
  >
    <section id="generic-view-activities-section">
      <div class="row match-height">
        <div class="col-12 col-xs-12 col-md-8">
          <div class="card">
            <div class="card-content">
              <div class="card-body pb-0 mx-25">
                <h4 class="card-title col-12">
                  Activities
                  <button
                    data-toggle="modal"
                    data-target="#genericActivityModel"
                    class="float-right btn btn-primary"
                  >
                    <i class="bx bx-plus pr-1"></i> Add Activity
                  </button>
                </h4>
                <div class="row pt-1">
                  <div
                    class="col-12 card"
                    :key="activity.id"
                    v-for="activity in this.activities"
                  >
                    <div
                      style="
                        background: rgb(245, 245, 245) none repeat scroll 0% 0%;
                      "
                      class="card-body row"
                    >
                      <div class="col-xs-12 col-md-2">
                        <i
                          class="bx bx-lg text-info"
                          :class="changeIconClass(activity.activity_type)"
                        ></i>
                      </div>
                      <div class="col-xs-12 col-md-10 row">
                        <h6
                          :title="activity.title"
                          class="col-12 card-title mb-50"
                        >
                          {{ activity.title }}
                          <span
                            v-if="showDeleteUpdateButtons(activity.assignee)"
                            class="generic-note-buttons"
                            style="position: absolute; right: 1em"
                          >
                            <i
                              @click.prevent="openActivity(activity.id)"
                              style="font-size: 1.4em; background: whitesmoke"
                              class="bx text-info bx bx-edit"
                            ></i>
                            <i
                              @click.prevent="deleteActivity(activity.id)"
                              style="font-size: 1.4em; background: whitesmoke"
                              class="bx text-danger bx bx-trash"
                            ></i>
                          </span>
                        </h6>
                        <div :title="activity.description" class="col-12 mb-50">
                          {{ activity.description | truncate(250) }}
                        </div>
                        <small class="col-12 d-block text-muted">
                          <span
                            style="display: inline-block; min-width: 100px"
                            class="font-weight-bold font-italic"
                          >
                            Assignee - {{ activityAuthor(activity.assignee) }}
                          </span>
                          <span
                            class="badge badge-pill mr-1 ml-1"
                            :title="'Status - ' + activity.status"
                            :class="changeBadgeClass(activity.status)"
                            >{{ activity.status }}</span
                          >
                          <i
                            class="bx bxs-flag-alt mr-1 ml-1"
                            :title="'Priority - ' + activity.priority"
                            :class="changePriorityClass(activity.priority)"
                          ></i>
                          <span
                            class="font-weight-bold"
                            :title="'Priority - ' + activity.priority"
                            style="
                              font-weight: bold !important;
                              display: inline-block;
                              top: -0.3em;
                              position: relative;
                            "
                            :class="changePriorityClass(activity.priority)"
                            >{{ activity.priority }}</span
                          >
                          <span title="Scheduled TIme" style="float: right"
                            ><i
                              class="bxs-time bx"
                              style="vertical-align: middle; padding-right: 1em"
                            ></i>
                            {{ activityData(activity.start_date) }}
                          </span>
                        </small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Model Activity : START -->
    <div
      class="modal fade"
      id="genericActivityModel"
      tabindex="-1"
      role="dialog"
      :aria-labelledby="`${module} Activity Modal`"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ modalTitle }}</h5>
            <button
              type="button"
              id="genericActivityModelClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="row">
                <div class="col-md-3">
                  <!-- type : START -->
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label for="activity_type">Type</label>
                      <select
                        class="custom-select form-control mr-1"
                        :class="{
                          'is-invalid': errors.hasOwnProperty('activity_type'),
                        }"
                        @keyup="delete errors.activity_type"
                        :title="errors.activity_type"
                        v-model="form.activity_type"
                        placeholder="Type"
                        id="activity_type"
                      >
                        <option value="" selected disabled>Type</option>
                        <option
                          :value="activity"
                          v-for="activity in getActivityTypes"
                          :key="activity"
                        >
                          {{ activity }}
                        </option>
                      </select>
                    </div>
                  </div>

                  <!-- type : END -->

                  <!-- is_private : start -->
                  <div class="form-group col-md-12">
                    <div class="custom-control custom-switch">
                      <label for="" style="vertical-align: super" class="mr-1">
                        Visible to All Staff ?
                      </label>
                      <div class="custom-control custom-switch">
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          id="isPrivate"
                          name="is_private"
                          v-model="form.is_private"
                          :true-value="'0'"
                          :false-value="'1'"
                          @change="changeActivityIsPrivateDefault"
                        />
                        <label class="custom-control-label" for="isPrivate">
                          <span class="switch-icon-left"> YES</span>
                          <span class="switch-icon-right">NO</span>
                        </label>
                      </div>
                    </div>
                  </div>
                  <!-- is_private : end -->

                  <!-- notify : START -->
                  <div
                    class="form-group col-md-12"
                    v-show="form.activity_type !== 'Log'"
                  >
                    <div class="custom-control custom-switch">
                      <label for="" style="vertical-align: super" class="mr-1">
                        Reminder ?
                      </label>
                      <div class="custom-control custom-switch">
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          id="notifySwitch"
                          name="Visibility"
                          v-model="form.notify"
                          :true-value="'1'"
                          :false-value="'0'"
                          checked
                        />
                        <label class="custom-control-label" for="notifySwitch">
                          <span class="switch-icon-left"> YES</span>
                          <span class="switch-icon-right">NO</span>
                        </label>
                      </div>
                    </div>
                  </div>
                  <!-- notify : END -->

                  <!-- allday : start -->
                  <div class="form-group col-md-12">
                    <div class="custom-control custom-switch">
                      <label for="" style="vertical-align: super" class="mr-1">
                        All Day?
                      </label>
                      <div class="custom-control custom-switch">
                        <input
                          type="checkbox"
                          class="custom-control-input"
                          id="allDay"
                          name="Visibility"
                          v-model="form.all_day"
                          :true-value="'1'"
                          :false-value="'0'"
                          checked
                        />
                        <label class="custom-control-label" for="allDay">
                          <span class="switch-icon-left"> YES</span>
                          <span class="switch-icon-right">NO</span>
                        </label>
                      </div>
                    </div>
                  </div>
                  <!-- adllday : end -->

                  <!-- status : start -->
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label for="status">Status</label>
                      <select
                        id="status"
                        class="custom-select"
                        name="status"
                        v-model="form.status"
                      >
                        <option disabled value="">-- Statuses --</option>
                        <option
                          :value="status"
                          v-for="status in getStatuses"
                          :key="status"
                        >
                          {{ status }}
                        </option>
                      </select>

                      <small
                        id="status-error"
                        class="form-text invalid-feedback"
                        >{{ errors.status }}</small
                      >
                    </div>
                  </div>
                  <!-- status : end -->

                  <!-- priority : start -->
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label for="priority">Priority</label>
                      <select
                        id="priority"
                        class="custom-select"
                        name="priority"
                        v-model="form.priority"
                      >
                        <option disabled value="">-- Select --</option>
                        <option
                          :value="priority"
                          v-for="priority in getPriorities"
                          :key="priority"
                        >
                          {{ priority }}
                        </option>
                      </select>

                      <small
                        id="priority-error"
                        class="form-text invalid-feedback"
                        >{{ errors.priority }}</small
                      >
                    </div>
                  </div>
                  <!-- priority : end -->

                  <!-- assignee : start -->
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label for="assignee">Assignee</label>
                      <select
                        id="assignee"
                        class="custom-select"
                        name="assignee"
                        v-model="form.assignee"
                      >
                        <option disabled value="">-- Assignee --</option>
                        <option
                          :value="user.user_id"
                          v-for="user in allUsers"
                          :key="user.user_id + '_assignee'"
                          v-text="user.name"
                        ></option>
                      </select>

                      <small
                        id="assignee-error"
                        class="form-text invalid-feedback"
                        >{{ errors.assignee }}</small
                      >
                    </div>
                  </div>
                  <!-- assignee : end -->
                </div>
                <div class="col-md-9">
                  <!-- title : START -->
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label for="activity_title">Title</label>
                      <input
                        type="text"
                        :class="{
                          'is-invalid': errors.hasOwnProperty('title'),
                        }"
                        @keyup="delete errors.title"
                        :title="errors.title"
                        v-model="form.title"
                        placeholder="Title"
                        class="form-control"
                        id="activity_title"
                      />
                    </div>
                  </div>
                  <!-- title : END -->

                  <small v-if="form.is_private === '0'" class="text-danger"
                    >This task can be viewed and modified by other team
                    members.</small
                  >

                  <!-- activity_method : START -->
                  <div class="form-row" v-show="!isTask">
                    <div class="form-group col-md-12">
                      <label for="activity_method">{{
                        activityMethodLabel
                      }}</label>
                      <select
                        class="custom-select form-control mr-1"
                        :class="{
                          'is-invalid':
                            errors.hasOwnProperty('activity_method'),
                        }"
                        @keyup="delete errors.activity_method"
                        :title="errors.activity_method"
                        v-model="form.activity_method"
                        placeholder="Type"
                        id="activity_method"
                      >
                        <option value="" selected disabled>
                          {{ activityMethodLabel }}
                        </option>
                        <option
                          :value="type"
                          v-for="type in activityMethodTypes"
                          :key="type"
                        >
                          {{ type }}
                        </option>
                      </select>
                    </div>
                  </div>
                  <!-- activity_method : END -->

                  <div class="w-100"></div>
                  <!-- dates : START -->
                  <div class="form-row">
                    <!-- start_date : START -->
                    <div class="col-md-6 col-xs-12">
                      <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <date-picker
                            :format="'MM/DD/YYYY HH:mm'"
                            :value-type="'MM/DD/YYYY HH:mm'"
                            :minute-step="5"
                            type="datetime"
                            :clearable="false"
                            placeholder="Start Date"
                            v-model="form.start_date"
                            id="start_date"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('start_date'),
                            }"
                            @change="delete errors.start_date"
                            :title="errors.start_date"
                          ></date-picker>
                        </fieldset>
                        <small
                          id="start_date-error"
                          class="form-text invalid-feedback"
                          >{{ errors.start_date }}</small
                        >
                      </div>
                    </div>
                    <!-- start_date : END -->
                    <!-- end_date : START -->
                    <div class="col-md-6 col-xs-12" v-show="!isTask">
                      <div class="form-group">
                        <label for="end_date">End Date</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <date-picker
                            :format="'MM/DD/YYYY HH:mm'"
                            :value-type="'MM/DD/YYYY HH:mm'"
                            :minute-step="5"
                            type="datetime"
                            :clearable="false"
                            placeholder="End Date"
                            v-model="form.end_date"
                            id="end_date"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('end_date'),
                            }"
                            @change="delete errors.end_date"
                            :title="errors.end_date"
                          ></date-picker>
                        </fieldset>
                        <small
                          id="end_date-error"
                          class="form-text invalid-feedback"
                          >{{ errors.end_date }}</small
                        >
                      </div>
                    </div>
                    <!-- end_date : END -->
                  </div>
                  <!-- dates : END -->

                  <!-- notify : START -->
                  <div class="form-row" v-show="form.activity_type !== 'Log'">
                    <div
                      class="col-md-6 col-xs-12"
                      v-show="showNotificationSection"
                    >
                      <div class="form-group">
                        <label for="notification_at">Notification At</label>
                        <select
                          class="custom-select form-control mr-1"
                          :class="{
                            'is-invalid':
                              errors.hasOwnProperty('notification_at'),
                          }"
                          @keyup="delete errors.notification_at"
                          :title="errors.notification_at"
                          v-model="form.notification_at"
                          placeholder=""
                          id="notification_at"
                          style="vertical-align: text-top"
                          @change="notificationChange"
                        >
                          <option value="" selected disabled>Prior</option>
                          <option value="10">Before 10 Mins</option>
                          <option value="15">Before 15 Mins</option>
                          <option value="30">Before 30 Mins</option>
                          <option value="45">Before 45 Mins</option>
                          <option value="60">Before 60 Mins</option>
                          <option value="at">At</option>
                          <option value="other">Other</option>
                        </select>
                      </div>
                    </div>

                    <!-- notification_date : START -->
                    <div
                      class="col-md-6 col-xs-12"
                      v-show="showNotificationSection"
                    >
                      <div class="form-group">
                        <label for="notification_date">Notification Date</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative; top: 3px"
                        >
                          <date-picker
                            :format="'MM/DD/YYYY HH:mm'"
                            :value-type="'MM/DD/YYYY HH:mm'"
                            :minute-step="5"
                            type="datetime"
                            :clearable="false"
                            placeholder="Notification Date"
                            v-model="form.notification_date"
                            id="notification_date"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('notification_date'),
                            }"
                            @change="delete errors.notification_date"
                            :title="errors.notification_date"
                          ></date-picker>
                        </fieldset>
                        <small
                          id="notification_date-error"
                          class="form-text invalid-feedback"
                          >{{ errors.notification_date }}</small
                        >
                      </div>
                    </div>

                    <!-- notification_date : END -->

                    <!-- Notification banner : start -->
                    <small
                      class="col-md-12 col-xs-12 form-group font-weight-bold"
                      :class="showHumanizeClass"
                      v-show="showNotificationSection"
                    >
                      {{ showHumanizeDifference }}
                    </small>
                    <!-- Notification banner : end -->

                    <!-- notification_method : start -->
                    <div
                      class="col-md-6 col-xs-12 form-group"
                      v-show="showNotificationSection"
                    >
                      <label for="notification_method"
                        >Notification Method</label
                      >

                      <v-select
                        id="notification_method"
                        placeholder="Notification Method"
                        v-model="form.notification_method"
                        multiple
                        :options="getNotificationMethods"
                        clearable
                      >
                      </v-select>

                      <small
                        id="notification_method-error"
                        class="form-text invalid-feedback"
                        >{{ errors.notification_method }}</small
                      >
                    </div>
                    <!-- notification_method : end -->
                  </div>
                  <!-- notify : END -->
                  <div class="w-100"></div>

                  <!-- description : START -->
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label for="activity_description">Description</label>
                      <textarea
                        rows="6"
                        type="text"
                        :class="{
                          'is-invalid': errors.hasOwnProperty('description'),
                        }"
                        @keyup="delete errors.description"
                        :title="errors.description"
                        v-model="form.description"
                        placeholder="Description"
                        class="form-control"
                        id="activity_description"
                      ></textarea>
                    </div>
                  </div>
                  <!-- description : END -->
                </div>
              </div>
            </form>
          </div>
          <!-- modal footer : START -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button type="button" class="btn btn-primary" @click="save">
              Save
            </button>
          </div>
          <!-- modal footer : END -->
        </div>
      </div>
    </div>
    <!-- Model Activity : END -->
  </div>
</template>
<script>
import Toast from "../../library/Toast";
import { mapGetters, mapActions } from "vuex";
import vSelect from "vue-select";
import moment from "moment";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

import ActivityMixin from "../../mixins/activity";

export default {
  props: {
    id: { required: true },
    app_metadata: { required: true },
    time_zone: String,
    module: { required: true },
  },
  name: "ModuleWiseEntityActivity",
  data() {
    return {
      activity_id: null,
      activities: [],
      errors: [],
      load: false,
      form: {
        title: "",
        description: "",
        start_date: null,
        end_date: null,
        notify: "0",
        notification_date: null,
        notification_method: ["Push Notification"],
        notification_at: "at",
        assignee: "",
        status: "Pending",
        priority: "Normal",
        module_id: this.id,
        activity_type: "Task",
        activity_method: "",
        all_day: "0",
      },
    };
  },
  mixins: [ActivityMixin],
  components: {
    DatePicker,
    "v-select": vSelect,
  },
  watch: {
    timeZoneName() {
      moment.tz.setDefault(this.timeZoneName);
    },
    "form.start_date": function (newValue, oldValue) {
      if (!this.load) {
        let start_date = moment(this.form.start_date, "MM/DD/YYYY HH:mm");

        if (this.form.notification_at == "other") {
          this.form.notification_at = "at";
        }

        this.notificationChange();
        this.form.end_date = start_date
          .add("30", "m")
          .format("MM/DD/YYYY HH:mm");
      }
    },
    dateTimeChecker() {},
    "form.activity_type": function (newValue, oldValue) {
      if (!this.load) {
        if (newValue !== oldValue) {
          this.form.activity_method = "";
        }

        if (newValue === "Log") {
          this.form.notify = "0";
        }
      }
    },
    activitiesMapper() {},
  },
  computed: {
    ...mapGetters("users", ["allUsers"]),
    ...mapGetters("calendarView", [
      "getViewId",
      "getEntities",
      "getLoadState",
      "getFilters",
      "getActivityTypes",
      "getStatuses",
      "getPriorities",
      "getModules",
      "getFilterFlag",
      "getNotificationMethods",
      "getAppointmentMethods",
      "getLogMethods",
    ]),
    dateTimeChecker() {
      let start_date = moment(this.form.start_date, "MM/DD/YYYY HH:mm");
      let end_date = moment(this.form.end_date, "MM/DD/YYYY HH:mm");

      if (end_date.isBefore(start_date, "minute")) {
        this.form.end_date = start_date
          .add("30", "m")
          .format("MM/DD/YYYY HH:mm");
      }
    },
    modalTitle() {
      return this.activity_id === null
        ? `Create ${this.module} Activity`
        : `Update ${this.module} Activity`;
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    timeZoneName() {
      return this.time_zone ? this.time_zone : "Canada/Eastern";
    },
    activityMethodLabel() {
      if (this.form.activity_type == "Appointment") {
        return "Appointment Type";
      } else if (this.form.activity_type == "Log") {
        return "Log Type";
      }
      return "Type";
    },
    activityMethodTypes() {
      if (this.form.activity_type == "Appointment") {
        return this.getAppointmentMethods;
      } else if (this.form.activity_type == "Log") {
        return this.getLogMethods;
      }

      return [];
    },
    isTask() {
      return this.form.activity_type == "Task" ? true : false;
    },
    showNotificationSection() {
      return this.form.notify == "0" ? false : true;
    },
    activitiesMapper() {
      if (this.activities.length > 0) {
        for (var index in this.activities) {
          this.activities[index].start = this.$helpers.changeUTCtoLocalDate(
            this.activities[index].start_date,
            "MM/DD/YYYY hh:mm:ss A",
            this.timeZoneName
          );
          this.activities[index].end = this.$helpers.changeUTCtoLocalDate(
            this.activities[index].end_date,
            "MM/DD/YYYY hh:mm:ss A",
            this.timeZoneName
          );

          this.activities[index].category =
            this.activities[index].all_day == "0" ? "time" : "allday";

          this.activities[index].category =
            this.activities[index].activity_type === "Task"
              ? "task"
              : this.activities[index].category;

          this.activities[index].isAllDay =
            this.activities[index].all_day == "0" ? false : true;
        }
      }

      return this.activities;
    },
    showHumanizeClass() {
      let start_date = moment(this.form.start_date, "MM/DD/YYYY HH:mm");
      let notification_date = moment(
        this.form.notification_date,
        "MM/DD/YYYY HH:mm"
      );
      let difference = notification_date.diff(start_date);
      return difference <= 0 ? "text-info" : "text-warning";
    },
    showHumanizeDifference() {
      let start_date = moment(this.form.start_date, "MM/DD/YYYY HH:mm");
      let notification_date = moment(
        this.form.notification_date,
        "MM/DD/YYYY HH:mm"
      );
      let difference = notification_date.diff(start_date);
      if (difference <= 0) {
        return `Notification set about ${notification_date.from(
          start_date,
          true
        )} before activity start date`;
      } else {
        return `Notification set about ${notification_date.from(
          start_date,
          true
        )} after activity start date`;
      }
    },
  },
  methods: {
    ...mapActions("users", ["getAllUsers"]),
    ...mapActions("calendarView", [
      "commitOnlyEntities",
      "commitTodayCount",
      "getSetUpAttributes",
    ]),
    showDeleteUpdateButtons(author_id) {
      return (
        author_id == this.app_metadata.id || this.app_metadata.role == "Owner"
      );
    },
    changePriorityClass(priority) {
      switch (priority) {
        case "High":
          return "text-danger";
          break;

        case "Moderate":
          return "text-warning";
          break;

        case "Normal":
          return "text-success";
          break;

        case "Low":
          return "text-secondary";
          break;

        default:
          return "text-secondary";
          break;
      }
    },
    changeIconClass(type) {
      switch (type) {
        case "Task":
          return "bx-task";
          break;

        case "Appointment":
          return "bxs-calendar-check";
          break;

        case "Log":
          return "bx-list-check";
          break;

        default:
          break;
      }
    },
    changeBadgeClass(status) {
      switch (status) {
        case "Pending":
          return "badge-light-success";
          break;

        case "Started":
          return "badge-light-warning";
          break;

        case "Completed":
          return "badge-light-secondary";
          break;

        case "Cancelled":
          return "badge-light-danger";
          break;

        default:
          return "badge-light-primary";
          break;
      }
    },
    activityAuthor(author_id) {
      return this.$helpers.assigneeName(this.allUsers, author_id);
    },
    activityData(date) {
      return this.$helpers.changeUTCtoLocalDate(
        date,
        "MM/DD/YYYY hh:mm:ss A",
        this.timeZoneName
      );
    },
    save() {
      const vm = this;
      this.errors = [];

      let formData = new FormData();

      let start_date = moment(this.form.start_date);

      start_date = start_date.isValid()
        ? this.$helpers.changeLocalDateToUTC(
            this.form.start_date,
            "MM/DD/YYYY HH:mm",
            this.timeZoneName
          )
        : null;

      formData.append("start_date", start_date);

      let end_date = moment(this.form.end_date);
      end_date = end_date.isValid()
        ? this.$helpers.changeLocalDateToUTC(
            this.form.end_date,
            "MM/DD/YYYY HH:mm",
            this.timeZoneName
          )
        : null;
      formData.append("end_date", end_date);

      let notification_date = moment(this.form.notification_date);
      notification_date = notification_date.isValid()
        ? this.$helpers.changeLocalDateToUTC(
            this.form.notification_date,
            "MM/DD/YYYY HH:mm",
            this.timeZoneName
          )
        : null;
      formData.append("notification_date", notification_date);

      const date_keys = ["start_date", "end_date", "notification_date"];

      if (Array.isArray(this.form["notification_method"])) {
        formData.append(
          "notification_method",
          JSON.stringify(this.form["notification_method"])
        );
      } else {
        formData.append("notification_method", "[]");
      }

      for (const key in this.form) {
        if (
          this.form.hasOwnProperty(key) &&
          this.form[key] != null &&
          !(date_keys.includes(key) || key === "notification_method")
        ) {
          formData.append(key, this.form[key]);
        }
      }

      formData.append("module", this.module);

      let request_url = window.location.origin;
      let update_request = !isNaN(this.activity_id) && this.activity_id != null;

      request_url = update_request
        ? `${request_url}/api/business/activities/update/${this.activity_id}`
        : `${request_url}/api/business/activities/create`;

      let request_message = update_request
        ? `Updating activity details ...`
        : `Adding new activity details ...`;

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
          vm.resetForm();
          vm.fetchAllActivities();
          document.getElementById("genericActivityModelClose").click();
          toast.success(data.success);
          toast.close();
          vm.commitOnlyEntities();
          vm.commitTodayCount(this.app_metadata.id);
        })
        .catch(function (error) {
          let update_request = !isNaN(vm.activity_id) && vm.activity_id != null;

          let error_message = update_request
            ? "There were errors while updating the activity details."
            : "There were some errors while adding activity details.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });
    },
    resetForm() {
      this.form.title = "";
      this.form.description = "";
      this.form.start_date = moment().format("MM/DD/YYYY HH:mm");
      this.form.end_date = moment().add("30", "m").format("MM/DD/YYYY HH:mm");
      this.form.notify = "0";
      this.form.notification_date = null;
      this.form.notification_method = ["Push Notification"];
      this.form.notification_at = "at";
      this.form.assignee = this.app_metadata.id;
      this.form.status = "Pending";
      this.form.priority = "Normal";
      this.form.activity_type = "Task";
      this.form.activity_method = "";
      this.form.all_day = "0";
      this.errors = [];
      this.activity_id = null;
    },
    async openActivity(id, override_date = true) {
      let vm = this;
      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/activities/show/${id}`,
      })
        .then(({ data }) => {
          vm.load = true;

          let date_keys = ["start_date", "end_date", "notification_date"];
          for (const key in vm.form) {
            if (!date_keys.includes(key)) {
              vm.form[key] = data[key];
            }
          }

          this.form.notification_method = [];

          if (this.$helpers.isValidJsonString(data.notification_method)) {
            this.form.notification_method = JSON.parse(
              data.notification_method
            );
          }

          if (override_date) {
            vm.form.start_date = this.$helpers.changeUTCtoLocalDate(
              data.start_date,
              "MM/DD/YYYY HH:mm",
              this.timeZoneName
            );
            vm.form.end_date = this.$helpers.changeUTCtoLocalDate(
              data.end_date,
              "MM/DD/YYYY HH:mm",
              this.timeZoneName
            );
            vm.form.notification_date = this.$helpers.changeUTCtoLocalDate(
              data.notification_date,
              "MM/DD/YYYY HH:mm",
              this.timeZoneName
            );
          }

          vm.activity_id = data.id;
          jQuery("#genericActivityModel").modal("show");
        })
        .catch((e) => {
          console.log(e);
          vm.load = false;
        });

      vm.load = false;
    },
    async deleteActivity(id) {
      let vm = this;
      const toast = new Toast(`Deleting activity ...`);
      toast.fire();

      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/activities/delete/${id}`,
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
          vm.fetchAllActivities();
          vm.commitOnlyEntities();
          vm.commitTodayCount(this.app_metadata.id);
        })
        .catch(({ response }) => {
          toast.fail(response.data);
          toast.close();
        });
    },
    fetchAllActivities() {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          `/api/business/activities?paginate=false&column=start_date&order=DESC&zm_module[]=${this.module}&zm_module_id[]=${this.id}`,
      }).then((response) => {
        this.activities = response.data.activity_listing;
        Event.fire("activity_count", this.activities.length);
      });
    },
    notificationChange() {
      let event_date = moment(this.form.start_date, "MM/DD/YYYY HH:mm").format(
        "YYYY-MM-DDTHH:mm:ss"
      );
      switch (this.form.notification_at) {
        case "10":
          this.form.notification_date = moment
            .tz(event_date, this.timeZoneName)
            .subtract("10", "m")
            .format("MM/DD/YYYY HH:mm");
          break;

        case "15":
          this.form.notification_date = moment
            .tz(event_date, this.timeZoneName)
            .subtract("15", "m")
            .format("MM/DD/YYYY HH:mm");
          break;

        case "30":
          this.form.notification_date = moment
            .tz(event_date, this.timeZoneName)
            .subtract("30", "m")
            .format("MM/DD/YYYY HH:mm");
          break;

        case "45":
          this.form.notification_date = moment
            .tz(event_date, this.timeZoneName)
            .subtract("45", "m")
            .format("MM/DD/YYYY HH:mm");
          break;

        case "60":
          this.form.notification_date = moment
            .tz(event_date, this.timeZoneName)
            .subtract("60", "m")
            .format("MM/DD/YYYY HH:mm");
          break;

        case "at":
          this.form.notification_date = moment
            .tz(event_date, this.timeZoneName)
            .format("MM/DD/YYYY HH:mm");
          break;

        default:
          break;
      }
    },
  },
  created() {
    const vm = this;
    this.getSetUpAttributes();
    this.getActivityConfiguration();
    this.form.assignee = this.app_metadata.id;
    this.fetchAllActivities();
  },
  mounted() {
    this.getAllUsers();
    this.form.start_date = moment().format("MM/DD/YYYY HH:mm");
    this.form.end_date = moment().add("30", "m").format("MM/DD/YYYY HH:mm");

    const vm = this;
    jQuery("#genericActivityModel").on("hidden.bs.modal", function () {
      vm.resetForm();
    });
  },
  beforeDestroy() {
    Event.vue.$off("activity_count", null);
  },
};
</script>
<style lang="scss">
#generic-view-activities {
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

  .generic-note-buttons i {
    transition-timing-function: linear;
    vertical-align: top;
  }
  .generic-note-buttons i:hover {
    font-size: 1.8em !important;
    cursor: pointer;
  }
}
</style>
