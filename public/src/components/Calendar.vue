<template>
  <!-- BEGIN: Content-->
  <div class="app-content content" id="dashboard" v-if="tenantApp">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="col-12">
          <div class="col-md-5 form-group d-inline-block" style="width: auto">
            <select
              class="custom-select form-control"
              v-model="calendar_view"
              id="calendar_view"
              name="calendar_view"
              style="width: auto"
              @change="changeView"
            >
              <option value="" selected disabled>Select Calendar View</option>
              <option value="day">Day</option>
              <option value="week">Week</option>
              <option value="month">Month</option>
            </select>
          </div>
          <i
            class="bx bx-chevron-left bx-md"
            style="cursor: pointer; vertical-align: middle"
            @click="previousPeriod"
          ></i>
          <i
            class="bx bx-chevron-right bx-md"
            style="cursor: pointer; vertical-align: middle"
            @click="nextPeriod"
          ></i>
          <button class="btn btn-info" @click="resetToToday">Today</button>
          <h5 class="d-inline-block pl-1">{{ calendar_title }}</h5>
          <button
            class="btn float-right p-0"
            data-toggle="modal"
            data-target="#activity_filters"
            title="Filters"
          >
            <i
              class="list-space border bx bxs-filter-alt"
              :class="getFilterFlag ? 'bg-secondary bx-tada text-white' : ''"
            ></i>
          </button>
        </div>
        <div class="col-12">
          <calendar
            :view="'month'"
            ref="tuiCalendar"
            :scheduleView="scheduleView"
            :schedules="activitiesMapper"
            :calendars="calendarList"
            :useCreationPopup="false"
            :useDetailPopup="false"
            @beforeCreateSchedule="onBeforeCreateSchedule"
            @beforeDeleteSchedule="onBeforeDeleteSchedule"
            @beforeUpdateSchedule="onBeforeUpdateSchedule"
            @clickSchedule="onClickSchedule"
            :month="month"
            :template="template"
            :taskView="['task']"
            :timezones="timeZones"
          />
        </div>
      </div>
    </div>
    <!-- END: Content-->
    <!-- Model Activity : START -->
    <div
      class="modal fade"
      id="activityModel"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Activity Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ modalTitle }}</h5>
            <button
              type="button"
              id="activityModelClose"
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
                      <label
                        for=""
                        style="display: inline-block; vertical-align: super"
                        class="mr-1"
                      >
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
                      <label
                        for=""
                        style="display: inline-block; vertical-align: super"
                        class="mr-1"
                      >
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
                      <label
                        for=""
                        style="display: inline-block; vertical-align: super"
                        class="mr-1"
                      >
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
                          :value="atype"
                          v-for="atype in activityMethodTypes"
                          :key="atype"
                        >
                          {{ atype }}
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
                          @change="notificationChange"
                          placeholder=""
                          id="notification_at"
                          style="vertical-align: text-top"
                        >
                          <option value="" selected disabled>Prior</option>
                          <option value="10">Before 10 Mins</option>
                          <option value="15">Before 15 Mins</option>
                          <option value="30">Before 30 Mins</option>
                          <option value="45">Before 45 Mins</option>
                          <option value="60">Before 60 Mins</option>
                          <option value="at">At</option>
                          <option value="-10">After 10 Mins</option>
                          <option value="-15">After 15 Mins</option>
                          <option value="-30">After 30 Mins</option>
                          <option value="-45">After 45 Mins</option>
                          <option value="-60">After 60 Mins</option>
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

                  <div class="form-row">
                    <!-- module : start -->
                    <div class="col-md-4 col-xs-12 form-group">
                      <label for="module">Related To</label>
                      <select
                        id="module"
                        class="custom-select"
                        name="module"
                        v-model="form.module"
                        :readonly="disable_change_module"
                        @change="moduleChange"
                      >
                        <option value="">-- Select --</option>
                        <option
                          :value="current_module"
                          v-for="current_module in getModules"
                          :key="current_module"
                        >
                          {{ current_module }}
                        </option>
                      </select>

                      <small
                        id="module-error"
                        class="form-text invalid-feedback"
                        >{{ errors.module }}</small
                      >
                    </div>

                    <!-- module : start -->
                    <div class="col-md-8 col-xs-12 form-group">
                      <label for="" class="pb-25">
                        {{ getSavedModuleTitle }}
                      </label>
                      <div>
                        <small
                          style="font-weight: 800"
                          :style="{
                            visibility: current_saved_entity ? '' : 'hidden',
                          }"
                        >
                          <calendar-entity-option
                            :current_module="current_saved_module"
                            :option="current_saved_entity"
                          ></calendar-entity-option>
                        </small>
                      </div>
                    </div>
                    <!-- module : end -->

                    <div class="w-100"></div>

                    <!-- search : start -->
                    <div class="col-md-12 col-xs-12 form-group">
                      <label for="search_list" class="pb-25">{{
                        getModuleListTitle
                      }}</label>
                      <v-select
                        id="app-search-id"
                        placeholder="Search"
                        label="search_keyword"
                        v-model="selected_entity"
                        clearable
                        :options="search_index"
                        @search="
                          (query, loading) => searchIndexes(query, loading)
                        "
                      >
                        <template slot="option" slot-scope="option">
                          <calendar-entity-option
                            :current_module="form.module"
                            :option="option"
                          ></calendar-entity-option>
                        </template>

                        <!-- Selected Option Slot : START -->
                        <template slot="selected-option" slot-scope="option">
                          <calendar-entity-option
                            :current_module="form.module"
                            :option="option"
                          ></calendar-entity-option>
                        </template>

                        <!-- Selected Option Slot : END -->

                        <!-- No Option Slot : START -->
                        <template v-slot:no-options="{ search, searching }">
                          <template v-if="searching">
                            No results found for <em>{{ search }}</em
                            >.
                          </template>
                          <em v-else style="opacity: 0.5"
                            >Start typing to search ...</em
                          >
                        </template>

                        <!-- No Option Slot : END -->
                      </v-select>
                    </div>
                    <!-- search : end -->
                  </div>

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
              v-show="activity_id !== null"
              class="btn btn-danger"
              @click="deleteActivity"
            >
              Delete
            </button>
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

    <!-- Filter Model : START -->
    <div
      class="modal fade text-left"
      id="activity_filters"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Activity Filters"
      aria-hidden="true"
    >
      <div
        class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
        role="document"
      >
        <div class="modal-content" id="filterActivityModel">
          <div class="modal-header pl-1 pr-1">
            <h3 class="content-header-title">Filters</h3>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <i class="bx bx-x"></i>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <!-- Type : START -->
              <div class="col-md-5">
                <label>Type</label>
              </div>
              <div class="col-md-7 form-group">
                <v-select
                  id="filter-activity_type"
                  placeholder="Type"
                  v-model="activityTypeFilter"
                  clearable
                  :options="getActivityTypes"
                  multiple
                >
                </v-select>
              </div>
              <!-- Type : END -->

              <!-- Status : START -->
              <div class="col-md-5">
                <label>Status</label>
              </div>
              <div class="col-md-7 form-group">
                <v-select
                  id="filter-status"
                  placeholder="Status"
                  v-model="activityStatusFilter"
                  clearable
                  multiple
                  :options="getStatuses"
                >
                </v-select>
              </div>
              <!-- Status : END -->

              <!-- Priority : START -->
              <div class="col-md-5">
                <label>Priority</label>
              </div>
              <div class="col-md-7 form-group">
                <v-select
                  id="filter-priority"
                  placeholder="Priority"
                  v-model="activityPriorityFilter"
                  clearable
                  :options="getPriorities"
                  multiple
                >
                </v-select>
              </div>
              <!-- Priority : END -->

              <!-- Module : START -->
              <div class="col-md-5">
                <label>Module</label>
              </div>
              <div class="col-md-7 form-group">
                <v-select
                  id="filter-module"
                  placeholder="Module"
                  v-model="activityModuleFilter"
                  clearable
                  :options="getModules"
                >
                </v-select>
              </div>

              <!-- Module : END -->

              <!-- Assignee : START -->
              <div class="col-md-5" v-show="isAuthorized">
                <label>Assignee</label>
              </div>
              <div class="col-md-7 form-group" v-show="isAuthorized">
                <select
                  id="filter-assignee"
                  class="custom-select"
                  name="assignee"
                  v-model="activityAssigneeFilter"
                >
                  <option value="">-- Select Assignee --</option>
                  <option
                    :value="user.user_id"
                    v-for="user in allUsers"
                    :key="user.user_id + '_assignee'"
                    v-text="user.name"
                  ></option>
                </select>
              </div>
              <!-- Assignee : END -->

              <div class="col-xs-12 p-1"></div>
              <div class="col-xs-12 p-1"></div>
              <div class="col-xs-12 p-1"></div>

              <div class="col-sm-12 d-flex justify-content-left pb-1">
                <button
                  type="close"
                  @click="activityFilters"
                  class="btn btn-primary mr-1 mt-1"
                  data-dismiss="modal"
                  aria-label="Close"
                >
                  Apply Filters
                </button>
                <button
                  type="close"
                  @click="resetFilters"
                  class="btn btn-light-secondary mr-1 mt-1"
                  data-dismiss="modal"
                  aria-label="Reset"
                >
                  Reset
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Filter Model : END -->
  </div>
</template>

<script>
import { Calendar } from "@toast-ui/vue-calendar";
import "tui-calendar/dist/tui-calendar.css";

import vSelect from "vue-select";
import { mapGetters, mapActions } from "vuex";
import Toast from "../library/Toast";
import ActivityMixin from "../mixins/activity";

import moment from "moment-timezone";
import CalendarEntityOption from "./CalendarEntityOption.vue";

import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

export default {
  props: {
    application: { required: true },
    app_metadata: { required: true },
    user_metadata: { required: true },
  },
  name: "Calendar",
  data() {
    return {
      errors: [],
      search_index: [],
      activity_id: null,
      is_activity_modal_loading: false,
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
        module: "",
        module_id: "",
        activity_type: "Task",
        activity_method: "",
        all_day: "0",
        is_private: "1",
      },

      calendar_view: "month",
      calendar_title: "",
      calendar_start_date: "",
      calendar_end_date: "",
      calendarList: [
        {
          id: "0",
          name: "Task",
          color: "#000",
          bgColor: "#ffadad",
          dragBgColor: "#ffadad",
          borderColor: "#ffadad",
        },
        {
          id: "1",
          name: "Appointment",
          color: "#000",
          bgColor: "#9bf6ff",
          dragBgColor: "#9bf6ff",
          borderColor: "#9bf6ff",
        },
        {
          id: "2",
          name: "Log",
          color: "#000",
          bgColor: "#caffbf",
          dragBgColor: "#caffbf",
          borderColor: "#caffbf",
        },
      ],
      scheduleView: ["time", "allday"],
      month: {
        scheduleFilter(schedule) {
          return Boolean(schedule.isVisible);
        },
      },
      current_saved_entity: null,
      current_saved_module: "",
      selected_entity: null,
      disable_change_module: false,
      inventory_items: [],
      lead_responses: [],
      customers: [],
      deals: [],
      vendors: [],
      activities: [],
      template: {
        time: function (schedule) {
          return (
            "<strong>" +
            moment(schedule.start.getTime()).format("HH:mm") +
            "</strong> " +
            schedule.title
          );
        },
      },
    };
  },
  mixins: [ActivityMixin],
  components: {
    calendar: Calendar,
    "v-select": vSelect,
    CalendarEntityOption,
    DatePicker,
  },
  watch: {
    "form.start_date": function (newValue, oldValue) {
      if (!this.is_activity_modal_loading) {
        let start_date = moment(this.form.start_date, "MM/DD/YYYY HH:mm");
        this.form.end_date = start_date
          .add("30", "m")
          .format("MM/DD/YYYY HH:mm");

        if (this.form.notification_at == "other") {
          this.form.notification_at = "at";
        }

        this.notificationChange();
        this.form.end_date = start_date
          .add("30", "m")
          .format("MM/DD/YYYY HH:mm");
      }
    },
    timeZoneName() {
      moment.tz.setDefault(this.timeZoneName);
    },
    dateTimeChecker() {},
    search_index() {},
    activitiesMapper() {},
    selected_entity: function (newVal) {
      this.selected_entity = newVal;
    },
    "form.activity_type": function (newValue, oldValue) {
      if (!this.is_activity_modal_loading) {
        if (newValue !== oldValue) {
          this.form.activity_method = "";
        }

        if (newValue === "Log") {
          this.form.notify = "0";
        }
      }
    },
  },
  computed: {
    ...mapGetters("users", ["allUsers"]),
    ...mapGetters("calendarView", [
      "getViewId",
      "getRequestUrl",
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
        )} before event start date`;
      } else {
        return `Notification set about ${notification_date.from(
          start_date,
          true
        )} after event start date`;
      }
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    activityTypeFilter: {
      get() {
        return this.getFilters.activity_type;
      },
      set(value) {
        this.commitFilters({ name: "activity_type", value: value });
      },
    },
    activityStatusFilter: {
      get() {
        return this.getFilters.status;
      },
      set(value) {
        this.commitFilters({ name: "status", value: value });
      },
    },
    activityPriorityFilter: {
      get() {
        return this.getFilters.priority;
      },
      set(value) {
        this.commitFilters({ name: "priority", value: value });
      },
    },
    activityModuleFilter: {
      get() {
        return this.getFilters.module;
      },
      set(value) {
        this.commitFilters({ name: "module", value: value });
      },
    },
    activityAssigneeFilter: {
      get() {
        return this.getFilters.assignee;
      },
      set(value) {
        this.commitFilters({ name: "assignee", value: value });
      },
    },
    getModuleListTitle() {
      return "Select From " + this.form.module + " List";
    },
    getSavedModuleTitle() {
      return "Current Saved " + this.current_saved_module;
    },
    currentSelectedTitle() {
      return "Selected " + this.form.module;
    },
    timeZoneName() {
      return this.user_metadata && this.user_metadata.hasOwnProperty("timezone")
        ? this.user_metadata.timezone
        : "Canada/Eastern";
    },
    timeZones() {
      return [
        {
          timezoneName: this.timeZoneName,
          displayLabel: this.timeZoneName,
          tooltip: this.timeZoneName,
        },
      ];
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
    tenantApp() {
      return this.application == "tenant";
    },
    modalTitle() {
      return this.activity_id === null ? "Create Activity" : "Update Activity";
    },
    isTask() {
      return this.form.activity_type == "Task" ? true : false;
    },
    showNotificationSection() {
      return this.form.notify == "0" ? false : true;
    },
    activitiesMapper() {
      if (this.getEntities.length > 0) {
        for (var index in this.getEntities) {
          this.getEntities[index].start = this.$helpers.changeUTCtoLocalDate(
            this.getEntities[index].start_date,
            "MM/DD/YYYY HH:mm",
            this.timeZoneName
          );
          this.getEntities[index].end = this.$helpers.changeUTCtoLocalDate(
            this.getEntities[index].end_date,
            "MM/DD/YYYY HH:mm",
            this.timeZoneName
          );
          this.getEntities[index].calendarId = this.mapActivityToCalendarId(
            this.getEntities[index].activity_type
          );
          this.getEntities[index].category =
            this.getEntities[index].all_day == "0" ? "time" : "allday";

          this.getEntities[index].category =
            this.getEntities[index].activity_type === "Task"
              ? "task"
              : this.getEntities[index].category;

          this.getEntities[index].isAllDay =
            this.getEntities[index].all_day == "0" ? false : true;
          this.getEntities[index].customStyle = this.switchPriorityStyle(
            this.getEntities[index].priority
          );
          this.getEntities[index].bgColor = this.switchBackgroundColorByStatus(
            this.getEntities[index].status
          );
          this.getEntities[index].borderColor =
            this.switchBackgroundColorByStatus(this.getEntities[index].status);
        }

        this.commitTodayCount(this.app_metadata.id);
      }

      return this.getEntities;
    },
  },
  methods: {
    ...mapActions("users", ["getAllUsers"]),
    ...mapActions("calendarView", [
      "commitViewId",
      "commitViewEntities",
      "commitLoadState",
      "commitRequestUrl",
      "commitOnlyEntities",
      "commitFilters",
      "commitFilterFlag",
      "commitTodayCount",
      "getSetUpAttributes",
    ]),
    activityFilters() {
      let vm = this;
      let url = window.location.origin;
      url += `/api/business/activities?paginate=false&zm_date_start[]=${this.calendar_start_date}&zm_date_end[]=${this.calendar_end_date}`;

      for (const key in this.getFilters) {
        if (this.getFilters.hasOwnProperty(key)) {
          const paramter = this.getFilters[key];

          if (paramter) {
            if (typeof paramter === "string") {
              url += `&zm_${key}[]=${this.getFilters[key]}`;
            } else {
              for (const single_paramter of paramter) {
                url += `&zm_${key}[]=${single_paramter}`;
              }
            }
          }
        }
      }

      const toast = new Toast(`Filtering activities ...`);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          this.commitViewEntities(data.activity_listing);
          this.commitRequestUrl(url);
          this.commitFilterFlag(true);

          toast.success("Activities filtered successfully.");
          toast.close();
        })
        .catch((e) => {
          toast.success("Some errors in filtering activities.");
          toast.close();
        });
    },
    resetFilters() {
      this.commitFilters({ name: "assignee", value: "" });
      this.commitFilters({ name: "priority", value: "" });
      this.commitFilters({ name: "module", value: "" });
      this.commitFilters({ name: "activity_type", value: "" });
      this.commitFilters({ name: "status", value: "" });
      this.fetchAllActivities(true);
    },
    mapActivityToCalendarId(activity) {
      switch (activity) {
        case "Task":
          return "0";
          break;
        case "Appointment":
          return "1";
          break;
        case "Log":
          return "2";
          break;
      }
    },
    moduleChange(reset_module_id = true) {
      if (reset_module_id) {
        this.form.module_id = "";
        this.selected_entity = null;
      }

      this.setModuleBasedSearch(this.form.module);
    },
    setModuleBasedSearch(selected_module) {
      switch (selected_module) {
        case "Inventory":
          this.search_index = this.inventory_items;
          break;

        case "Lead":
          this.search_index = this.lead_responses;
          break;

        case "Customer":
          this.search_index = this.customers;
          break;

        case "Deal":
          this.search_index = this.deals;
          break;

        case "Vendor":
          this.search_index = this.vendors;
          break;

        default:
          this.search_index = [];
          break;
      }
    },
    searchIndexes(query, loading) {
      switch (this.form.module) {
        case "Inventory":
          this.fetchEntities(
            "inventory",
            "inventory_items",
            "inventory",
            query,
            loading
          );
          break;

        case "Lead":
          this.fetchEntities(
            "leads/responses",
            "lead_responses",
            "lead_response_listing",
            query,
            loading
          );
          break;

        case "Customer":
          this.fetchEntities(
            "customers",
            "customers",
            "customer_listing",
            query,
            loading
          );
          break;

        case "Deal":
          this.fetchEntities("deals", "deals", "deal_listing", query, loading);
          break;

        case "Vendor":
          this.fetchEntities(
            "vendors",
            "vendors",
            "vendor_listing",
            query,
            loading
          );
          break;

        default:
          break;
      }
    },
    async fetchAnEntity(current_module, url, entity_id = null) {
      let vm = this;

      let request_url = `${window.location.origin}/api/business/${url}/show/${entity_id}`;

      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: request_url,
      })
        .then(({ data }) => {
          vm.current_saved_entity = data;
          vm.current_saved_module = current_module;
        })
        .catch((e) => {
          console.log(e);
        });
    },
    async fetchEntities(module, key, map, search = "", cb = null) {
      let vm = this;

      if (typeof cb === "function") {
        cb(true);
        vm.disable_change_module = true;
      }

      let request_url = `${window.location.origin}/api/business/${module}?items=30`;

      if (search !== "") {
        request_url += `&search=${search}`;
      }

      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: request_url,
      })
        .then(({ data }) => {
          vm[key] = data[map];

          if (vm.form.module !== module) {
            vm.setModuleBasedSearch(vm.form.module);
          } else {
            vm.search_index = data[map];
          }
        })
        .catch((e) => {
          console.log(e);
        })
        .finally(() => {
          if (typeof cb === "function") {
            cb(false);
            vm.disable_change_module = false;
          }
        });
    },
    fetchAllActivities(refresh = false) {
      let request_url = `${window.location.origin}/api/business/activities/views/result/1?paginate=false&zm_date_start[]=${this.calendar_start_date}&zm_date_end[]=${this.calendar_end_date}`;

      if (refresh || !this.getLoadState) {
        axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: request_url,
        }).then((response) => {
          this.commitViewEntities(response.data.activity_listing);
          this.commitRequestUrl(request_url);
        });
        this.commitLoadState(true);
        this.commitFilterFlag(false);
      }
    },
    fetchWithURL() {
      let url = this.getRequestUrl;
      url = new URL(url);
      url.searchParams.delete("zm_date_start[]");
      url.searchParams.delete("zm_date_end[]");

      url.searchParams.append("zm_date_start[]", this.calendar_start_date);
      url.searchParams.append("zm_date_end[]", this.calendar_end_date);

      url = decodeURIComponent(url);

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then(({ data }) => {
        this.commitViewEntities(data.activity_listing);
        this.commitRequestUrl(url);
      });
    },
    async getActivity(id, override_date = true) {
      this.is_activity_modal_loading = true;
      let vm = this;
      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/activities/show/${id}`,
      })
        .then(({ data }) => {
          vm.moduleChange();

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

          if (vm.form.module_id) {
            switch (vm.form.module) {
              case "Inventory":
                vm.search_index = vm.inventory_items;
                vm.fetchAnEntity("Inventory", "inventory", vm.form.module_id);
                break;

              case "Lead":
                vm.search_index = vm.lead_responses;
                vm.fetchAnEntity("Lead", "leads/responses", vm.form.module_id);
                break;

              case "Customer":
                vm.search_index = vm.customers;
                vm.fetchAnEntity("Customer", "customers", vm.form.module_id);
                break;

              case "Vendor":
                vm.search_index = vm.deals;
                vm.fetchAnEntity("Vendor", "vendors", vm.form.module_id);
                break;

              case "Deal":
                vm.search_index = vm.vendors;
                vm.fetchAnEntity("Deal", "deals/summary", vm.form.module_id);
                break;

              default:
                vm.search_index = [];
                break;
            }
          }

          jQuery("#activityModel").modal("show");
        })
        .catch((e) => {
          console.log(e);
        });

      this.is_activity_modal_loading = false;
    },
    resetForm() {
      this.form.title = "";
      this.form.description = "";
      this.form.start_date = null;
      this.form.end_date = null;
      this.form.notify = "0";
      this.form.notification_date = null;
      this.form.notification_method = ["Push Notification"];
      this.form.notification_at = "at";
      this.form.assignee = this.app_metadata.id;
      this.form.status = "Pending";
      this.form.priority = "Normal";
      this.form.module = "";
      this.form.module_id = "";
      this.form.activity_type = "Task";
      this.form.activity_method = "";
      this.form.all_day = "0";
      this.errors = [];
      this.activity_id = null;
      this.search_index = [];
      this.current_saved_entity = null;
      this.current_saved_module = "";
      this.$refs.tuiCalendar.invoke("render");
    },
    onBeforeCreateSchedule(e) {
      console.log(e);
      console.log(typeof e);
      if (e.hasOwnProperty("start")) {
        this.form.start_date = moment(e.start.toDate())
          // .minutes(0)
          // .hours(0)
          .format("MM/DD/YYYY HH:mm");
        this.form.notification_date = moment(e.start.toDate())
          // .minutes(0)
          // .hours(0)
          .format("MM/DD/YYYY HH:mm");
      }

      if (e.hasOwnProperty("end")) {
        this.form.end_date = moment(e.end.toDate())
          // .minutes(0)
          // .hours(23)
          .format("MM/DD/YYYY HH:mm");
      }

      jQuery("#activityModel").modal("show");
    },
    onBeforeDeleteSchedule(e) {
      console.log(e);
      alert("Delete Schedule");
    },
    onBeforeUpdateSchedule(e) {
      console.log(e);
      if (e.hasOwnProperty("start")) {
        this.form.start_date = moment(e.start.toDate())
          // .minutes(0)
          // .hours(0)
          .format("MM/DD/YYYY HH:mm");
        this.form.notification_date = moment(e.start.toDate())
          // .minutes(0)
          // .hours(0)
          .format("MM/DD/YYYY HH:mm");
      }

      if (e.hasOwnProperty("end")) {
        this.form.end_date = moment(e.end.toDate())
          // .minutes(0)
          // .hours(23)
          .format("MM/DD/YYYY HH:mm");
      }
      this.getActivity(e.schedule.id, false);
    },
    onClickSchedule(e) {
      console.log(e);
      this.getActivity(e.schedule.id, true);
    },
    previousPeriod() {
      this.$refs.tuiCalendar.invoke("prev");
      this.changeCalendarTitle();
    },
    nextPeriod() {
      this.$refs.tuiCalendar.invoke("next");
      this.changeCalendarTitle();
    },
    changeView() {
      this.$refs.tuiCalendar.invoke("changeView", this.calendar_view);
      this.changeCalendarTitle();
    },
    resetToToday() {
      this.$refs.tuiCalendar.invoke("today");
      this.changeCalendarTitle();
    },
    changeCalendarTitle(update_results = true) {
      let calender_start_date =
        this.$refs.tuiCalendar.invoke("getDateRangeStart");
      let calender_end_date = this.$refs.tuiCalendar.invoke("getDateRangeEnd");

      switch (this.calendar_view) {
        case "day":
          this.calendar_title = moment(
            this.$refs.tuiCalendar.invoke("getDateRangeStart")
          ).format("MM/DD/YYYY");
          break;
        default:
          let start = moment(calender_start_date.toDate()).format(
            "MMM DD, YYYY"
          );
          let end = moment(calender_end_date.toDate()).format("MMM DD, YYYY");

          this.calendar_title = `${start} - ${end}`;
          break;
      }

      this.calendar_start_date = moment(calender_start_date.toDate()).format(
        "MM/DD/YYYY"
      );
      this.calendar_end_date = moment(calender_end_date.toDate()).format(
        "MM/DD/YYYY"
      );

      if (update_results) {
        this.fetchWithURL();
      }
    },
    async deleteActivity() {
      if (this.activity_id !== null) {
        let vm = this;
        const toast = new Toast(`Deleting activity ...`);
        toast.fire();

        await axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url:
            window.location.origin +
            `/api/business/activities/delete/${this.activity_id}`,
        })
          .then(({ data }) => {
            vm.resetForm();
            document.getElementById("activityModelClose").click();
            toast.success(data.success);
            toast.close();

            vm.commitOnlyEntities();
          })
          .catch(({ response }) => {
            let errorObject = response.data;

            if (errorObject.hasOwnProperty("errors")) {
              if (typeof errorObject.errors === "string") {
                toast.fail(errorObject.errors);
              } else {
                toast.fail(errorObject);
              }
            }

            toast.close();
          });
      }
    },
    save() {
      const vm = this;
      this.errors = [];

      let formData = new FormData();

      let start_date = moment(this.form.start_date);

      console.log(start_date);
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

      if (typeof this.selected_entity === "object" && this.selected_entity) {
        let module_id =
          this.form.module === "Inventory"
            ? this.selected_entity.inventory_id
            : this.selected_entity.id;

        this.form.module_id = module_id;
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
          document.getElementById("activityModelClose").click();
          toast.success(data.success);
          toast.close();

          vm.commitOnlyEntities();

          if (vm.form.module_id) {
            switch (vm.form.module) {
              case "Inventory":
                vm.fetchAnEntity("Inventory", "inventory", vm.form.module_id);
                break;

              case "Lead":
                vm.fetchAnEntity("Lead", "leads/responses", vm.form.module_id);
                break;

              case "Customer":
                vm.fetchAnEntity("Customer", "customers", vm.form.module_id);
                break;

              case "Vendor":
                vm.fetchAnEntity("Vendor", "vendors", vm.form.module_id);
                break;

              case "Deal":
                vm.fetchAnEntity("Deal", "deals", vm.form.module_id);
                break;

              default:
                break;
            }
          }
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
    switchPriorityStyle(style) {
      switch (style) {
        case "High":
          return "border-style : dashed; border-color: #000";
          break;

        default:
          return "";
          break;
      }
    },
    switchBackgroundColorByStatus(status) {
      switch (status) {
        case "Pending":
          return "#ffe97f";
          break;

        case "Started":
          return "#9ac180";
          break;

        case "Completed":
          return "#d6d6d6";
          break;

        case "Cancelled":
          return "#eb7e83";
          break;
        default:
          break;
      }
    },
    notificationChange() {
      console.log(this.form.start_date);
      let event_date = moment(this.form.start_date, "MM/DD/YYYY HH:mm").format(
        "YYYY-MM-DDTHH:mm:ss"
      );
      console.log(event_date);
      switch (this.form.notification_at) {
        case "10":
          this.form.notification_date = moment
            .tz(event_date, this.timeZoneName)
            .subtract("10", "m")
            .format("MM/DD/YYYY HH:mm");
          break;

        case "-10":
          this.form.notification_date = moment
            .tz(event_date, this.timeZoneName)
            .add("10", "m")
            .format("MM/DD/YYYY HH:mm");
          break;

        case "15":
          this.form.notification_date = moment
            .tz(event_date, this.timeZoneName)
            .subtract("15", "m")
            .format("MM/DD/YYYY HH:mm");
          break;

        case "-15":
          this.form.notification_date = moment
            .tz(event_date, this.timeZoneName)
            .add("15", "m")
            .format("MM/DD/YYYY HH:mm");
          break;

        case "30":
          this.form.notification_date = moment
            .tz(event_date, this.timeZoneName)
            .subtract("30", "m")
            .format("MM/DD/YYYY HH:mm");
          break;

        case "-30":
          this.form.notification_date = moment
            .tz(event_date, this.timeZoneName)
            .add("30", "m")
            .format("MM/DD/YYYY HH:mm");
          break;

        case "45":
          this.form.notification_date = moment
            .tz(event_date, this.timeZoneName)
            .subtract("45", "m")
            .format("MM/DD/YYYY HH:mm");
          break;

        case "-45":
          this.form.notification_date = moment
            .tz(event_date, this.timeZoneName)
            .add("45", "m")
            .format("MM/DD/YYYY HH:mm");
          break;

        case "60":
          this.form.notification_date = moment
            .tz(event_date, this.timeZoneName)
            .subtract("60", "m")
            .format("MM/DD/YYYY HH:mm");
          break;

        case "-60":
          this.form.notification_date = moment
            .tz(event_date, this.timeZoneName)
            .add("60", "m")
            .format("MM/DD/YYYY HH:mm");
          break;

        case "at":
          this.form.notification_date = moment
            .tz(event_date, this.timeZoneName)
            .format("MM/DD/YYYY HH:mm");
          console.log(this.form.notification_date);
          break;

        default:
          break;
      }
    },
  },
  async created() {
    const vm = this;
    this.form.assignee = this.app_metadata.id;

    this.getSetUpAttributes();
    this.getActivityConfiguration();

    await this.fetchEntities("inventory", "inventory_items", "inventory");
    await this.fetchEntities(
      "leads/responses",
      "lead_responses",
      "lead_response_listing"
    );
    await this.fetchEntities("customers", "customers", "customer_listing");
    await this.fetchEntities("deals", "deals", "deal_listing");
    await this.fetchEntities("vendors", "vendors", "vendor_listing");
    this.search_index = [];
  },
  mounted() {
    let calender_start_date =
      this.$refs.tuiCalendar.invoke("getDateRangeStart");
    let calender_end_date = this.$refs.tuiCalendar.invoke("getDateRangeEnd");

    this.calendar_start_date = moment(calender_start_date.toDate()).format(
      "MM/DD/YYYY"
    );
    this.calendar_end_date = moment(calender_end_date.toDate()).format(
      "MM/DD/YYYY"
    );

    this.fetchAllActivities();

    this.getAllUsers();

    this.changeCalendarTitle(false);
    const vm = this;
    jQuery("#activityModel").on("hidden.bs.modal", function () {
      vm.resetForm();
    });
  },
};
</script>

<style lang="scss">
thead tr:first-child th {
  position: sticky;
  top: 0;
  background: white;
  z-index: 2;
}

.border-line-bottom-title {
  background-color: #f9f7f7;
}
#dashboard {
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
}
</style>
