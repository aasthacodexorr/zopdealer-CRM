<template>
  <div
    class="tab-pane"
    id="generic-view-activities"
    aria-labelledby="generic-view-activities"
    role="tabpanel"
  >
    <section id="generic-view-activities-section" class="ac-page">
      <div class="ac-card">
        <!-- Header : START -->
        <div class="ac-header">
          <div>
            <h4 class="ac-title">Activities</h4>
            <p class="ac-subtitle">
              {{ activities.length }}
              {{ activities.length == 1 ? "activity" : "activities" }}
              <template v-if="upcomingCount > 0">
                · {{ upcomingCount }} upcoming</template
              >
            </p>
          </div>
          <button
            type="button"
            data-toggle="modal"
            data-target="#genericActivityModel"
            class="btn btn-primary ac-primary-btn"
          >
            <i class="bx bx-plus"></i> Add Activity
          </button>
        </div>
        <div class="ac-filters" v-if="activities.length > 0">
          <button
            type="button"
            class="ac-filter-chip"
            :class="{ active: type_filter === '' }"
            @click="type_filter = ''"
          >
            All <span class="ac-chip-count">{{ activities.length }}</span>
          </button>
          <button
            type="button"
            class="ac-filter-chip"
            v-for="type in activityTypeFilters"
            :key="'ac_filter_' + type"
            :class="{ active: type_filter === type }"
            @click="type_filter = type"
          >
            <i class="bx" :class="changeIconClass(type)"></i>
            {{ pluralType(type) }}
            <span class="ac-chip-count">{{ countByType(type) }}</span>
          </button>
        </div>
        <!-- Header : END -->

        <!-- Timeline : START -->
        <ul class="ac-timeline" v-if="filteredActivities.length > 0">
          <li
            class="ac-item"
            :key="activity.id"
            v-for="activity in filteredActivities"
            :class="{ 'is-done': activity.status === 'Completed' || activity.status === 'Cancelled' }"
          >
            <span class="ac-node" :class="typeColorClass(activity.activity_type)">
              <i class="bx" :class="changeIconClass(activity.activity_type)"></i>
            </span>
            <div class="ac-item-card">
              <div class="ac-item-top">
                <div class="ac-item-heading">
                  <h6 :title="activity.title" class="ac-item-title">
                    {{ activity.title }}
                  </h6>
                  <div class="ac-item-tags">
                    <span class="ac-tag" :class="typeColorClass(activity.activity_type)">{{
                      activity.activity_type
                    }}</span>
                    <span class="ac-tag ac-tag--plain" v-if="activity.activity_method">{{
                      humanize(activity.activity_method)
                    }}</span>
                    <span
                      class="ac-pill"
                      :title="'Status - ' + activity.status"
                      :class="statusPillClass(activity.status)"
                      >{{ activity.status }}</span
                    >
                    <span
                      class="ac-priority"
                      :title="'Priority - ' + activity.priority"
                      :class="changePriorityClass(activity.priority)"
                      ><i class="bx bxs-flag-alt"></i>{{ activity.priority }}</span
                    >
                  </div>
                </div>
                <div
                  v-if="showDeleteUpdateButtons(activity.assignee)"
                  class="generic-note-buttons ac-item-actions"
                >
                  <button
                    type="button"
                    class="ac-icon-btn"
                    title="Edit activity"
                    @click.prevent="openActivity(activity.id)"
                  >
                    <i class="bx bx-edit-alt"></i>
                  </button>
                  <button
                    type="button"
                    class="ac-icon-btn ac-icon-btn--danger"
                    title="Delete activity"
                    @click.prevent="deleteActivity(activity.id)"
                  >
                    <i class="bx bx-trash"></i>
                  </button>
                </div>
              </div>
              <p
                v-if="activity.description"
                :title="activity.description"
                class="ac-item-desc"
              >
                {{ $helpers.truncate(activity.description, 250) }}
              </p>
              <div class="ac-item-meta">
                <span class="ac-meta" title="Scheduled Time"
                  ><i class="bx bx-time-five"></i>
                  {{ activityData(activity.start_date) }}
                  <span
                    class="ac-when"
                    v-if="relativeTime(activity.start_date)"
                    >· {{ relativeTime(activity.start_date) }}</span
                  >
                </span>
                <span class="ac-meta ac-assignee" title="Assignee">
                  <span class="ac-avatar">{{
                    initialsFor(activityAuthor(activity.assignee))
                  }}</span>
                  {{ activityAuthor(activity.assignee) || "Unassigned" }}
                </span>
              </div>
            </div>
          </li>
        </ul>
        <!-- Timeline : END -->

        <div class="ac-empty" v-else>
          <span class="ac-empty-icon"><i class="bx bx-calendar-check"></i></span>
          <p class="ac-empty-title">
            {{ type_filter ? "No " + pluralType(type_filter).toLowerCase() + " yet" : "No activities yet" }}
          </p>
          <p class="ac-empty-text">
            Schedule a task, book an appointment or log a call to keep track
            of this {{ String(module).toLowerCase() }}.
          </p>
          <button
            type="button"
            data-toggle="modal"
            data-target="#genericActivityModel"
            class="btn btn-outline-primary ac-outline-btn"
          >
            <i class="bx bx-plus"></i> Add Activity
          </button>
        </div>
      </div>
    </section>

    <!-- Model Activity : START -->
    <div
      class="modal fade ac-modal"
      id="genericActivityModel"
      tabindex="-1"
      role="dialog"
      :aria-labelledby="`${module} Activity Modal`"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
          <div class="ac-modal-header">
            <div class="ac-modal-heading">
              <span class="ac-modal-icon" :class="typeColorClass(form.activity_type)"
                ><i class="bx" :class="changeIconClass(form.activity_type) || 'bx-task'"></i
              ></span>
              <div>
                <h5 class="ac-modal-title">{{ modalTitle }}</h5>
                <p class="ac-modal-subtitle">
                  {{ activity_id === null ? "Plan what happens next with this " + String(module).toLowerCase() + "." : "Update the details of this activity." }}
                </p>
              </div>
            </div>
            <button
              type="button"
              id="genericActivityModelClose"
              class="ac-close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <i class="bx bx-x"></i>
            </button>
          </div>
          <div class="modal-body ac-modal-body">
            <form @submit.prevent>
              <div class="ac-form-grid">
                <!-- Main column : START -->
                <div class="ac-form-main">
                  <!-- type : START -->
                  <div class="ac-field">
                    <label class="ac-label" for="activity_type">Type</label>
                    <div class="ac-segmented" role="radiogroup" aria-label="Activity type">
                      <button
                        type="button"
                        v-for="activity in getActivityTypes"
                        :key="'seg_' + activity"
                        class="ac-segment"
                        :class="{ active: form.activity_type === activity }"
                        role="radio"
                        :aria-checked="form.activity_type === activity"
                        @click="
                          form.activity_type = activity;
                          delete errors.activity_type;
                        "
                      >
                        <i class="bx" :class="changeIconClass(activity)"></i>
                        {{ activity }}
                      </button>
                    </div>
                    <!-- hidden select keeps the original control / id for validation & tests -->
                    <select
                      class="d-none"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('activity_type'),
                      }"
                      :title="errors.activity_type"
                      v-model="form.activity_type"
                      id="activity_type"
                      tabindex="-1"
                      aria-hidden="true"
                    >
                      <option value="" disabled>Type</option>
                      <option
                        :value="activity"
                        v-for="activity in getActivityTypes"
                        :key="activity"
                      >
                        {{ activity }}
                      </option>
                    </select>
                    <small class="ac-error" v-if="errors.activity_type">{{
                      errors.activity_type
                    }}</small>
                  </div>
                  <!-- type : END -->

                  <!-- title : START -->
                  <div class="ac-field">
                    <label class="ac-label" for="activity_title"
                      >Title <span class="ac-required">*</span></label
                    >
                    <input
                      type="text"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('title'),
                      }"
                      @keyup="delete errors.title"
                      :title="errors.title"
                      v-model="form.title"
                      placeholder="e.g. Follow up on financing options"
                      class="form-control ac-input"
                      id="activity_title"
                    />
                    <small class="ac-error" v-if="errors.title">{{
                      errors.title
                    }}</small>
                  </div>
                  <!-- title : END -->

                  <div v-if="form.is_private === '0'" class="ac-notice">
                    <i class="bx bx-group"></i>
                    This task can be viewed and modified by other team members.
                  </div>

                  <!-- activity_method : START -->
                  <div class="ac-field" v-show="!isTask">
                    <label class="ac-label" for="activity_method">{{
                      activityMethodLabel
                    }}</label>
                    <div class="ac-select-wrap">
                      <select
                        class="custom-select form-control ac-input"
                        :class="{
                          'is-invalid': errors.hasOwnProperty('activity_method'),
                        }"
                        @keyup="delete errors.activity_method"
                        :title="errors.activity_method"
                        v-model="form.activity_method"
                        id="activity_method"
                      >
                        <option value="" selected disabled>
                          Select {{ activityMethodLabel.toLowerCase() }}
                        </option>
                        <option
                          :value="type"
                          v-for="type in activityMethodTypes"
                          :key="type"
                        >
                          {{ humanize(type) }}
                        </option>
                      </select>
                    </div>
                  </div>
                  <!-- activity_method : END -->

                  <!-- dates : START -->
                  <div class="ac-row">
                    <!-- start_date : START -->
                    <div class="ac-field">
                      <label class="ac-label" for="start_date">{{
                        isTask ? "Due Date" : "Start Date"
                      }}</label>
                      <date-picker
                        :format="'MM/DD/YYYY HH:mm'"
                        :value-type="'MM/DD/YYYY HH:mm'"
                        :minute-step="5"
                        type="datetime"
                        :clearable="false"
                        placeholder="Start Date"
                        v-model="form.start_date"
                        id="start_date"
                        class="ac-date"
                        :class="{
                          'is-invalid': errors.hasOwnProperty('start_date'),
                        }"
                        @change="delete errors.start_date"
                        :title="errors.start_date"
                      ></date-picker>
                      <small id="start_date-error" class="ac-error" v-if="errors.start_date">{{
                        errors.start_date
                      }}</small>
                    </div>
                    <!-- start_date : END -->
                    <!-- end_date : START -->
                    <div class="ac-field" v-show="!isTask">
                      <label class="ac-label" for="end_date">End Date</label>
                      <date-picker
                        :format="'MM/DD/YYYY HH:mm'"
                        :value-type="'MM/DD/YYYY HH:mm'"
                        :minute-step="5"
                        type="datetime"
                        :clearable="false"
                        placeholder="End Date"
                        v-model="form.end_date"
                        id="end_date"
                        class="ac-date"
                        :class="{
                          'is-invalid': errors.hasOwnProperty('end_date'),
                        }"
                        @change="delete errors.end_date"
                        :title="errors.end_date"
                      ></date-picker>
                      <small id="end_date-error" class="ac-error" v-if="errors.end_date">{{
                        errors.end_date
                      }}</small>
                    </div>
                    <!-- end_date : END -->
                  </div>
                  <!-- dates : END -->

                  <!-- description : START -->
                  <div class="ac-field mb-0">
                    <label class="ac-label" for="activity_description"
                      >Description</label
                    >
                    <textarea
                      rows="5"
                      type="text"
                      :class="{
                        'is-invalid': errors.hasOwnProperty('description'),
                      }"
                      @keyup="delete errors.description"
                      :title="errors.description"
                      v-model="form.description"
                      placeholder="Add notes, talking points or next steps..."
                      class="form-control ac-input ac-textarea"
                      id="activity_description"
                    ></textarea>
                    <small class="ac-error" v-if="errors.description">{{
                      errors.description
                    }}</small>
                  </div>
                  <!-- description : END -->
                </div>
                <!-- Main column : END -->

                <!-- Side column : START -->
                <aside class="ac-form-side">
                  <p class="ac-side-heading">Details</p>

                  <!-- status : start -->
                  <div class="ac-field">
                    <label class="ac-label" for="status">Status</label>
                    <select
                      id="status"
                      class="custom-select ac-input"
                      name="status"
                      v-model="form.status"
                    >
                      <option disabled value="">Select status</option>
                      <option
                        :value="status"
                        v-for="status in getStatuses"
                        :key="status"
                      >
                        {{ status }}
                      </option>
                    </select>
                    <small id="status-error" class="ac-error" v-if="errors.status">{{
                      errors.status
                    }}</small>
                  </div>
                  <!-- status : end -->

                  <!-- priority : start -->
                  <div class="ac-field">
                    <label class="ac-label" for="priority">Priority</label>
                    <div class="ac-priority-picker" role="radiogroup" aria-label="Priority">
                      <button
                        type="button"
                        v-for="priority in orderedPriorities"
                        :key="'prio_' + priority"
                        class="ac-priority-option"
                        :class="[
                          changePriorityClass(priority),
                          { active: form.priority === priority },
                        ]"
                        role="radio"
                        :aria-checked="form.priority === priority"
                        @click="form.priority = priority"
                      >
                        <i class="bx bxs-flag-alt"></i>{{ priority }}
                      </button>
                    </div>
                    <select
                      id="priority"
                      class="d-none"
                      name="priority"
                      v-model="form.priority"
                      tabindex="-1"
                      aria-hidden="true"
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
                    <small id="priority-error" class="ac-error" v-if="errors.priority">{{
                      errors.priority
                    }}</small>
                  </div>
                  <!-- priority : end -->

                  <!-- assignee : start -->
                  <div class="ac-field">
                    <label class="ac-label" for="assignee">Assignee</label>
                    <select
                      id="assignee"
                      class="custom-select ac-input"
                      name="assignee"
                      v-model="form.assignee"
                    >
                      <option disabled value="">Select assignee</option>
                      <option
                        :value="user.user_id"
                        v-for="user in allUsers"
                        :key="user.user_id + '_assignee'"
                        v-text="user.name"
                      ></option>
                    </select>
                    <small id="assignee-error" class="ac-error" v-if="errors.assignee">{{
                      errors.assignee
                    }}</small>
                  </div>
                  <!-- assignee : end -->

                  <div class="ac-divider"></div>

                  <!-- is_private : start -->
                  <label class="ac-toggle" for="isPrivate">
                    <span class="ac-toggle-text">
                      <span class="ac-toggle-title">Visible to all staff</span>
                      <span class="ac-toggle-hint">Team members can view and edit</span>
                    </span>
                    <span class="ac-switch">
                      <input
                        type="checkbox"
                        id="isPrivate"
                        name="is_private"
                        v-model="form.is_private"
                        :true-value="'0'"
                        :false-value="'1'"
                        @change="changeActivityIsPrivateDefault"
                      />
                      <span class="ac-switch-track"></span>
                    </span>
                  </label>
                  <!-- is_private : end -->

                  <!-- notify : START -->
                  <label
                    class="ac-toggle"
                    for="notifySwitch"
                    v-show="form.activity_type !== 'Log'"
                  >
                    <span class="ac-toggle-text">
                      <span class="ac-toggle-title">Reminder</span>
                      <span class="ac-toggle-hint">Get notified before it starts</span>
                    </span>
                    <span class="ac-switch">
                      <input
                        type="checkbox"
                        id="notifySwitch"
                        name="Visibility"
                        v-model="form.notify"
                        :true-value="'1'"
                        :false-value="'0'"
                      />
                      <span class="ac-switch-track"></span>
                    </span>
                  </label>
                  <!-- notify : END -->

                  <!-- allday : start -->
                  <label class="ac-toggle" for="allDay">
                    <span class="ac-toggle-text">
                      <span class="ac-toggle-title">All day</span>
                      <span class="ac-toggle-hint">No specific time</span>
                    </span>
                    <span class="ac-switch">
                      <input
                        type="checkbox"
                        id="allDay"
                        name="Visibility"
                        v-model="form.all_day"
                        :true-value="'1'"
                        :false-value="'0'"
                      />
                      <span class="ac-switch-track"></span>
                    </span>
                  </label>
                  <!-- allday : end -->

                  <!-- notification settings : START -->
                  <div
                    class="ac-reminder"
                    v-show="form.activity_type !== 'Log' && showNotificationSection"
                  >
                    <div class="ac-field">
                      <label class="ac-label" for="notification_at">Notify</label>
                      <select
                        class="custom-select form-control ac-input"
                        :class="{
                          'is-invalid': errors.hasOwnProperty('notification_at'),
                        }"
                        @keyup="delete errors.notification_at"
                        :title="errors.notification_at"
                        v-model="form.notification_at"
                        id="notification_at"
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

                    <!-- notification_date : START -->
                    <div class="ac-field">
                      <label class="ac-label" for="notification_date"
                        >Notification Date</label
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
                        class="ac-date"
                        :class="{
                          'is-invalid': errors.hasOwnProperty('notification_date'),
                        }"
                        @change="delete errors.notification_date"
                        :title="errors.notification_date"
                      ></date-picker>
                      <small
                        id="notification_date-error"
                        class="ac-error"
                        v-if="errors.notification_date"
                        >{{ errors.notification_date }}</small
                      >
                    </div>
                    <!-- notification_date : END -->

                    <!-- Notification banner : start -->
                    <p class="ac-reminder-note" :class="showHumanizeClass">
                      <i class="bx bx-bell"></i> {{ showHumanizeDifference }}
                    </p>
                    <!-- Notification banner : end -->

                    <!-- notification_method : start -->
                    <div class="ac-field mb-0">
                      <label class="ac-label" for="notification_method"
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
                        class="ac-error"
                        v-if="errors.notification_method"
                        >{{ errors.notification_method }}</small
                      >
                    </div>
                    <!-- notification_method : end -->
                  </div>
                  <!-- notification settings : END -->
                </aside>
                <!-- Side column : END -->
              </div>
            </form>
          </div>
          <!-- modal footer : START -->
          <div class="ac-modal-footer">
            <button
              type="button"
              class="btn ac-ghost-btn"
              data-dismiss="modal"
            >
              Cancel
            </button>
            <button type="button" class="btn btn-primary ac-primary-btn" @click="save">
              <i class="bx bx-check"></i>
              {{ activity_id === null ? "Save Activity" : "Update Activity" }}
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
import DatePicker from "../Common/DatePickerAdapter.vue";
import "vue-datepicker-next/index.css";

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
      type_filter: "",
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
    /** Types shown as filter chips: configured types plus any present in the data */
    activityTypeFilters() {
      const types = [...(this.getActivityTypes || [])];
      this.activities.forEach((activity) => {
        if (activity.activity_type && !types.includes(activity.activity_type)) {
          types.push(activity.activity_type);
        }
      });
      return types.filter((type) => this.countByType(type) > 0);
    },
    filteredActivities() {
      return this.type_filter === ""
        ? this.activities
        : this.activities.filter(
            (activity) => activity.activity_type === this.type_filter
          );
    },
    upcomingCount() {
      const now = moment.utc();
      return this.activities.filter(
        (activity) =>
          activity.start_date &&
          moment.utc(activity.start_date).isAfter(now) &&
          activity.status !== "Completed" &&
          activity.status !== "Cancelled"
      ).length;
    },
    orderedPriorities() {
      const order = ["High", "Moderate", "Normal", "Low"];
      return [...(this.getPriorities || [])].sort(
        (a, b) =>
          (order.indexOf(a) === -1 ? 99 : order.indexOf(a)) -
          (order.indexOf(b) === -1 ? 99 : order.indexOf(b))
      );
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
    countByType(type) {
      return this.activities.filter((activity) => activity.activity_type === type)
        .length;
    },
    pluralType(type) {
      const value = String(type || "");
      return value.endsWith("s") ? value : value + "s";
    },
    typeColorClass(type) {
      switch (type) {
        case "Task":
          return "ac-type--task";
        case "Appointment":
          return "ac-type--appointment";
        case "Log":
          return "ac-type--log";
        default:
          return "ac-type--other";
      }
    },
    statusPillClass(status) {
      switch (status) {
        case "Pending":
          return "ac-pill--amber";
        case "Started":
          return "ac-pill--blue";
        case "Completed":
          return "ac-pill--green";
        case "Cancelled":
          return "ac-pill--red";
        default:
          return "ac-pill--gray";
      }
    },
    /** Splits joined words for display: "TestDrive" -> "Test Drive", "log_call" -> "Log call" */
    humanize(value) {
      const text = String(value || "")
        .replace(/([a-z])([A-Z])/g, "$1 $2")
        .replace(/_/g, " ");
      return text.charAt(0).toUpperCase() + text.slice(1);
    },
    relativeTime(date) {
      if (!date) return "";
      const value = moment.utc(date);
      return value.isValid() ? value.fromNow() : "";
    },
    initialsFor(name) {
      const parts = String(name || "")
        .trim()
        .split(/\s+/)
        .filter((part) => part);
      const letters = (parts[0] || "").charAt(0) + (parts[1] || "").charAt(0);
      return letters ? letters.toUpperCase() : "?";
    },
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
          return "bx-calendar-event";
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
      if (!author_id) return "";
      return this.$helpers.assigneeName(this.allUsers, author_id) || "";
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
  beforeUnmount() {
    Event.vue.$off("activity_count", null);
  },
};
</script>
<style lang="scss">
/* ==========================================================================
   Activities tab + Add/Edit Activity modal. Scoped to #generic-view-activities
   (shared by Lead and Customer detail pages).
   ========================================================================== */
#generic-view-activities {
  --ac-border: #e6eaf0;
  --ac-surface: #ffffff;
  --ac-bg: #f6f8fb;
  --ac-text: #1f2937;
  --ac-text-2: #4b5563;
  --ac-muted: #8a94a6;
  --ac-primary: #5a8dee;
  --ac-primary-soft: #eef4ff;

  .ac-page {
    padding: 1.5rem 1.75rem 6rem;
    background: var(--ac-bg);
    min-height: 100%;
  }
  .ac-card {
    background: var(--ac-surface);
    border: 1px solid var(--ac-border);
    border-radius: 14px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
  }

  /* Header */
  .ac-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.2rem 1.4rem 0.9rem;
  }
  .ac-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    letter-spacing: -0.01em;
    color: var(--ac-text);
  }
  .ac-subtitle {
    margin: 0.15rem 0 0;
    font-size: 0.875rem;
    color: var(--ac-muted);
  }
  .ac-primary-btn {
    height: 40px;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0 1.1rem;
    border-radius: 10px;
    font-weight: 500;
    line-height: 1;
    white-space: nowrap;
    i {
      position: static;
      top: 0;
      font-size: 1.15rem;
      line-height: 1;
    }
  }
  .ac-outline-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    border-radius: 10px;
    i {
      position: static;
      top: 0;
    }
  }
  .ac-ghost-btn {
    height: 40px;
    padding: 0 1rem;
    border-radius: 10px;
    border: 1px solid var(--ac-border);
    background: var(--ac-surface);
    color: var(--ac-text-2);
    font-weight: 500;
    &:hover {
      background: #f6f8fb;
      color: var(--ac-text);
    }
  }

  /* Filter chips */
  .ac-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    padding: 0 1.4rem 1rem;
    border-bottom: 1px solid var(--ac-border);
  }
  .ac-filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    height: 32px;
    padding: 0 0.75rem;
    border-radius: 999px;
    border: 1px solid var(--ac-border);
    background: var(--ac-surface);
    color: var(--ac-text-2);
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s, color 0.15s;
    i {
      font-size: 1rem;
    }
    &:hover {
      border-color: #d6dce5;
      color: var(--ac-text);
    }
    &.active {
      background: var(--ac-primary-soft);
      border-color: var(--ac-primary);
      color: var(--ac-primary);
    }
  }
  .ac-chip-count {
    min-width: 20px;
    height: 20px;
    padding: 0 6px;
    border-radius: 999px;
    background: #eef1f5;
    color: var(--ac-text-2);
    font-size: 0.72rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }
  .ac-filter-chip.active .ac-chip-count {
    background: #fff;
    color: var(--ac-primary);
  }

  /* Type colours */
  .ac-type--task { background: #e6efff; color: #2f62d6; }
  .ac-type--appointment { background: #efe8ff; color: #6b46c1; }
  .ac-type--log { background: #def6f3; color: #11806f; }
  .ac-type--other { background: #f1f4f8; color: #5b6575; }

  /* Timeline */
  .ac-timeline {
    list-style: none;
    margin: 0;
    padding: 1.2rem 1.4rem 1.4rem;
  }
  .ac-item {
    position: relative;
    display: flex;
    gap: 1rem;
    padding-bottom: 1rem;
    &::before {
      content: "";
      position: absolute;
      left: 19px;
      top: 42px;
      bottom: 0;
      width: 2px;
      background: #eef1f5;
    }
    &:last-child {
      padding-bottom: 0;
      &::before {
        display: none;
      }
    }
    &.is-done .ac-item-title {
      color: var(--ac-muted);
    }
  }
  .ac-node {
    position: relative;
    z-index: 1;
    width: 40px;
    height: 40px;
    flex-shrink: 0;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    box-shadow: 0 0 0 4px var(--ac-surface);
  }
  .ac-item-card {
    flex: 1 1 auto;
    min-width: 0;
    padding: 0.9rem 1rem;
    border: 1px solid var(--ac-border);
    border-radius: 12px;
    background: var(--ac-surface);
    transition: border-color 0.15s, box-shadow 0.15s;
    &:hover {
      border-color: #d6dce5;
      box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
      .ac-item-actions {
        opacity: 1;
      }
    }
  }
  .ac-item-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
  }
  .ac-item-heading {
    min-width: 0;
  }
  .ac-item-title {
    margin: 0 0 0.4rem;
    font-size: 1rem;
    font-weight: 600;
    color: var(--ac-text);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .ac-item-tags {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.4rem;
  }
  .ac-tag {
    display: inline-flex;
    align-items: center;
    padding: 0.18rem 0.55rem;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 500;
  }
  .ac-tag--plain {
    background: #f1f4f8;
    color: var(--ac-text-2);
  }
  .ac-pill {
    display: inline-flex;
    align-items: center;
    padding: 0.18rem 0.55rem;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 500;
  }
  .ac-pill--amber { background: #fff3d1; color: #a26a00; }
  .ac-pill--blue { background: #e6efff; color: #2f62d6; }
  .ac-pill--green { background: #e3f6e8; color: #1e8a43; }
  .ac-pill--red { background: #fde6e6; color: #c23434; }
  .ac-pill--gray { background: #eef1f5; color: #5b6575; }
  .ac-priority {
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    font-size: 0.8rem;
    font-weight: 600;
    i {
      font-size: 0.95rem;
    }
  }
  .ac-item-actions {
    display: flex;
    gap: 0.25rem;
    flex-shrink: 0;
    opacity: 0.65;
    transition: opacity 0.15s;
  }
  .ac-icon-btn {
    width: 32px;
    height: 32px;
    border: 0;
    border-radius: 8px;
    background: transparent;
    color: var(--ac-text-2);
    font-size: 1.15rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    &:hover {
      background: var(--ac-primary-soft);
      color: var(--ac-primary);
    }
  }
  .ac-icon-btn--danger:hover {
    background: #fde6e6;
    color: #c23434;
  }
  .ac-item-desc {
    margin: 0.65rem 0 0;
    color: var(--ac-text-2);
    font-size: 0.9rem;
    line-height: 1.5;
    white-space: pre-line;
  }
  .ac-item-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem 1rem;
    margin-top: 0.75rem;
    padding-top: 0.65rem;
    border-top: 1px dashed var(--ac-border);
  }
  .ac-meta {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.85rem;
    color: var(--ac-text-2);
    i {
      font-size: 1.05rem;
      color: var(--ac-muted);
    }
  }
  .ac-when {
    color: var(--ac-muted);
  }
  .ac-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--ac-primary-soft);
    color: #2f62d6;
    font-size: 0.65rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  /* Empty state */
  .ac-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 3rem 1.5rem 3.25rem;
    border-top: 1px solid var(--ac-border);
  }
  .ac-empty-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: var(--ac-primary-soft);
    color: var(--ac-primary);
    font-size: 1.7rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
  }
  .ac-empty-title {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 600;
    color: var(--ac-text);
  }
  .ac-empty-text {
    margin: 0.3rem 0 1rem;
    max-width: 380px;
    color: var(--ac-muted);
    font-size: 0.9rem;
  }

  /* ---------- Modal ---------- */
  .ac-modal {
    .modal-dialog {
      max-width: 980px;
    }
    .modal-content {
      border: 1px solid var(--ac-border);
      border-radius: 16px;
      box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
    }
  }
  .ac-modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.2rem 1.5rem 1rem;
    border-bottom: 1px solid var(--ac-border);
  }
  .ac-modal-heading {
    display: flex;
    align-items: center;
    gap: 0.85rem;
  }
  .ac-modal-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
  }
  .ac-modal-title {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--ac-text);
  }
  .ac-modal-subtitle {
    margin: 0.15rem 0 0;
    font-size: 0.85rem;
    color: var(--ac-muted);
  }
  .ac-close {
    width: 34px;
    height: 34px;
    border: 0;
    border-radius: 8px;
    background: transparent;
    color: var(--ac-muted);
    font-size: 1.4rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    &:hover {
      background: #f1f4f8;
      color: var(--ac-text);
    }
  }
  .ac-modal-body {
    padding: 0;
  }
  .ac-form-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 320px;
  }
  .ac-form-main {
    padding: 1.3rem 1.5rem 1.5rem;
  }
  .ac-form-side {
    padding: 1.3rem 1.4rem 1.5rem;
    background: var(--ac-bg);
    border-left: 1px solid var(--ac-border);
  }
  .ac-side-heading {
    margin: 0 0 0.85rem;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--ac-muted);
  }
  .ac-field {
    margin-bottom: 1.05rem;
  }
  .ac-row {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0 1rem;
  }
  .ac-label {
    display: block;
    margin-bottom: 0.4rem;
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--ac-text-2);
    text-transform: none;
    letter-spacing: 0;
  }
  .ac-required {
    color: #c23434;
  }
  .ac-input,
  .ac-form-side .custom-select {
    height: 44px;
    border: 1px solid var(--ac-border);
    border-radius: 10px;
    background-color: var(--ac-surface);
    color: var(--ac-text);
    font-size: 0.95rem;
    box-shadow: none;
    transition: border-color 0.15s, box-shadow 0.15s;
    &:hover {
      border-color: #d6dce5;
    }
    &:focus {
      border-color: var(--ac-primary);
      box-shadow: 0 0 0 3px rgba(90, 141, 238, 0.15);
    }
    &.is-invalid {
      border-color: #e05252;
    }
  }
  .ac-textarea {
    height: auto;
    min-height: 120px;
    padding: 0.7rem 0.9rem;
    line-height: 1.5;
    resize: vertical;
  }
  .ac-error {
    display: block;
    margin-top: 0.3rem;
    font-size: 0.8rem;
    color: #c23434;
  }
  .ac-notice {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    margin: -0.25rem 0 1rem;
    padding: 0.55rem 0.8rem;
    border-radius: 10px;
    background: #fff3d1;
    color: #8a5a00;
    font-size: 0.85rem;
    i {
      font-size: 1.1rem;
    }
  }

  /* Segmented type control */
  .ac-segmented {
    display: inline-flex;
    gap: 0.25rem;
    padding: 0.25rem;
    border-radius: 12px;
    background: #f1f4f8;
  }
  .ac-segment {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    height: 36px;
    padding: 0 1rem;
    border: 0;
    border-radius: 9px;
    background: transparent;
    color: var(--ac-text-2);
    font-weight: 500;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background 0.15s, color 0.15s, box-shadow 0.15s;
    i {
      font-size: 1.1rem;
    }
    &:hover {
      color: var(--ac-text);
    }
    &.active {
      background: var(--ac-surface);
      color: var(--ac-primary);
      box-shadow: 0 1px 3px rgba(15, 23, 42, 0.12);
    }
  }

  /* Priority picker */
  .ac-priority-picker {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.4rem;
  }
  .ac-priority-option {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
    height: 36px;
    border: 1px solid var(--ac-border);
    border-radius: 9px;
    background: var(--ac-surface);
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.15s, border-color 0.15s, box-shadow 0.15s;
    &:hover {
      opacity: 1;
    }
    &.active {
      opacity: 1;
      border-color: currentColor;
      box-shadow: 0 0 0 1px currentColor inset;
    }
  }

  .ac-divider {
    height: 1px;
    margin: 0.4rem 0 0.6rem;
    background: var(--ac-border);
  }

  /* Toggle rows */
  .ac-toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin: 0;
    padding: 0.6rem 0;
    cursor: pointer;
    text-transform: none;
    letter-spacing: 0;
  }
  .ac-toggle-text {
    display: flex;
    flex-direction: column;
  }
  .ac-toggle-title {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--ac-text);
  }
  .ac-toggle-hint {
    font-size: 0.78rem;
    color: var(--ac-muted);
  }
  .ac-switch {
    position: relative;
    width: 38px;
    height: 22px;
    flex-shrink: 0;
    input {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      margin: 0;
      opacity: 0;
      cursor: pointer;
      z-index: 1;
    }
    .ac-switch-track {
      position: absolute;
      inset: 0;
      border-radius: 999px;
      background: #d6dce5;
      transition: background 0.15s;
      &::after {
        content: "";
        position: absolute;
        top: 3px;
        left: 3px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.2);
        transition: transform 0.15s;
      }
    }
    input:checked + .ac-switch-track {
      background: var(--ac-primary);
      &::after {
        transform: translateX(16px);
      }
    }
    input:focus-visible + .ac-switch-track {
      box-shadow: 0 0 0 3px rgba(90, 141, 238, 0.25);
    }
  }

  /* Reminder panel */
  .ac-reminder {
    margin-top: 0.6rem;
    padding: 0.9rem;
    border: 1px solid var(--ac-border);
    border-radius: 12px;
    background: var(--ac-surface);
  }
  .ac-reminder-note {
    display: flex;
    align-items: flex-start;
    gap: 0.35rem;
    margin: -0.35rem 0 0.9rem;
    font-size: 0.8rem;
    font-weight: 500;
    i {
      font-size: 1rem;
    }
  }

  /* Date pickers & v-select inside the modal */
  .ac-date,
  .ac-date.mx-datepicker {
    width: 100%;
  }
  .ac-date .mx-input {
    height: 44px;
    padding: 0 2.2rem 0 0.9rem;
    border: 1px solid var(--ac-border);
    border-radius: 10px;
    box-shadow: none;
    font-size: 0.95rem;
    color: var(--ac-text);
    &:hover {
      border-color: #d6dce5;
    }
    &:focus {
      border-color: var(--ac-primary);
      box-shadow: 0 0 0 3px rgba(90, 141, 238, 0.15);
    }
  }
  .ac-date.is-invalid .mx-input {
    border-color: #e05252;
  }
  .ac-reminder .v-select {
    .vs__dropdown-toggle {
      min-height: 44px;
      border: 1px solid var(--ac-border);
      border-radius: 10px;
      padding: 0 0.4rem;
    }
    .vs__selected {
      border: 0;
      border-radius: 6px;
      background: var(--ac-primary-soft);
      color: #2f62d6;
      font-size: 0.8rem;
    }
  }

  .ac-modal-footer {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 0.5rem;
    padding: 0.9rem 1.5rem;
    border-top: 1px solid var(--ac-border);
  }

  @media (max-width: 991.98px) {
    .ac-form-grid {
      grid-template-columns: 1fr;
    }
    .ac-form-side {
      border-left: 0;
      border-top: 1px solid var(--ac-border);
    }
  }
  @media (max-width: 767.98px) {
    .ac-page {
      padding: 1rem 1rem 6rem;
    }
    .ac-row {
      grid-template-columns: 1fr;
    }
    .ac-item-actions {
      opacity: 1;
    }
  }
}
</style>
