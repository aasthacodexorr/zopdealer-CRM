<template>
  <div
    class="tab-pane"
    id="workflow-template-steps"
    aria-labelledby="information-tab"
    role="tabpanel"
  >
    <!-- WorkFlow Template Step : START -->
    <section id="expenses">
      <div class="row">
        <div class="col-12">
          <div class="card shadow-none">
            <div class="card-content">
              <div class="card-body pt-0">
                <div class="row p-1">
                  <div class="col-xs-12 col-12 mb-1">
                    <button
                      style="font-weight: 800"
                      type="button"
                      class="btn btn-primary"
                      data-toggle="modal"
                      data-target="#workFlowTemplateStep"
                    >
                      <i
                        style="font-size: 1.4em; padding-right: 0.5em"
                        class="bx bx-list-plus"
                      ></i>
                      Add Step
                    </button>
                  </div>

                  <div class="col-xs-12 col-12">
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th
                              class="text-left"
                              style="width: 100px"
                              scope="col"
                            >
                              Step
                            </th>
                            <th
                              class="text-left"
                              style="width: 100px"
                              scope="col"
                            >
                              Time
                            </th>
                            <th
                              class="text-left"
                              style="width: 100px"
                              scope="col"
                            >
                              Action
                            </th>
                            <th
                              class="text-center"
                              scope="col"
                              style="width: 100px"
                            ></th>
                          </tr>
                        </thead>
                        <tr v-show="!is_loaded">
                          <td colspan="4">Loading ...</td>
                        </tr>
                        <draggable
                          tag="tbody"
                          :list="workflow_template_step_list"
                          v-bind="dragOptions"
                          @start="isDragging = true"
                          @end="isDragging = false"
                          @change="sortIndex"
                          draggable="tr"
                        >
                          <tr
                            v-for="(workflow_template_step,
                            wft_index) in workflow_template_step_list"
                            :key="
                              `workflow_template_step_${workflow_template_step.id}`
                            "
                            v-show="is_loaded"
                          >
                            <td class="p-1">
                              <span
                                style="cursor: pointer"
                                class="bx bx-grid-vertical pr-1 handle"
                              ></span>
                              #{{ wft_index + 1 }}
                            </td>
                            <td class="p-1">
                              <span v-show="wft_index === 0">After</span>
                              {{ workflow_template_step.wait_time }}
                              {{ workflow_template_step.wait_time_unit }}
                              <span v-show="wft_index > 0"
                                >After Step #{{ wft_index }}</span
                              >
                            </td>
                            <td class="p-1">
                              {{ workflow_template_step.action }}
                            </td>
                            <td class="p-1">
                              <button
                                class="btn btn-info"
                                @click.prevent="
                                  editWorkFlowTemplateStep(
                                    workflow_template_step.id,
                                  )
                                "
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                "
                              >
                                <i class="bx bx-edit"></i>
                              </button>
                              <button
                                class="btn btn-danger"
                                @click.prevent="
                                  deleteWorkFlowTemplateStep(
                                    workflow_template_step.id,
                                  )
                                "
                                style="
                                  padding-right: 0.8em;
                                  padding-left: 0.8em;
                                "
                              >
                                <i class="bx bx-trash-alt"></i>
                              </button>
                            </td>
                          </tr>
                        </draggable>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- WorkFlow Template Step : END -->
    <!-- Add Step Model : Start -->
    <div
      class="modal fade"
      id="workFlowTemplateStep"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Add WorkFlow Step"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ title }}
            </h5>
            <button
              type="button"
              id="workFlowTemplateStepClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label>Wait Time</label>
                  <input
                    type="text"
                    placeholder="Time"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('wait_time'),
                    }"
                    @keyup="delete errors.wait_time"
                    :title="errors.wait_time"
                    v-model="form.wait_time"
                    class="form-control"
                  />
                </div>
                <div class="form-group col-md-4">
                  <label>Unit</label>
                  <select
                    name=""
                    id=""
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('wait_time_unit'),
                    }"
                    @keyup="delete errors.wait_time_unit"
                    :title="errors.wait_time_unit"
                    v-model="form.wait_time_unit"
                    placeholder="Unit"
                  >
                    <option value="MINUTES">MINUTES</option>
                    <option value="HOURS">HOURS</option>
                    <option value="DAYS">DAYS</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label>Action</label>
                  <select
                    name="action"
                    id="workTemplateStepAction"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('action'),
                    }"
                    @keyup="delete errors.action"
                    :title="errors.action"
                    class="form-control"
                    placeholder="Action"
                    v-model="form.action"
                  >
                    <option value="" selected disabled="true">
                      -- Select An Action --
                    </option>
                    <option value="ACTIVITY">ACTIVITY</option>
                    <option value="EMAIL">EMAIL</option>
                    <option value="SMS">SMS</option>
                  </select>
                </div>
              </div>

              <!-- Primary Recipients: START -->
              <div class="form-row" v-show="form.action === 'SMS'">
                <div class="form-group col-md-12">
                  <label>Primary Recipients</label>
                  <v-select
                    id="primary_recipients"
                    placeholder="Primary Recipients"
                    @change="delete errors.primary_recipients"
                    :title="errors.primary_recipients"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('primary_recipients'),
                    }"
                    v-model="sms_form.primary_recipients"
                    multiple
                    clearable
                    :options="getCurrentMobilePlaceholderMappings"
                    :reduce="(map_columns) => map_columns.value"
                    label="label"
                  >
                  </v-select>
                  <small
                    id="primary_recipients-error"
                    class="form-text invalid-feedback"
                    >{{ errors.primary_recipients }}</small
                  >
                </div>
              </div>
              <!-- Primary Recipients: END-->

              <!-- Additional Recipients: START -->
              <div class="form-row" v-show="form.action === 'SMS'">
                <div class="form-group col-md-12">
                  <label>Additional Recipients</label>
                  <v-select
                    id="additional_recipients"
                    placeholder="Additional Recipients"
                    v-model="sms_form.additional_recipients"
                    @change="delete errors.additional_recipients"
                    :title="errors.additional_recipients"
                    :class="{
                      'is-invalid': errors.hasOwnProperty(
                        'additional_recipients',
                      ),
                    }"
                    multiple
                    taggable
                    clearable
                    :options="[]"
                  >
                  </v-select>
                  <small
                    id="additional_recipients-error"
                    class="form-text invalid-feedback"
                    >{{ errors.additional_recipients }}</small
                  >
                </div>
              </div>
              <!-- Additional Recipients: END-->

              <!-- Custom Template Override: START -->
              <div class="form-row" v-show="form.action === 'SMS'">
                <div class="form-group col-md-12">
                  <label>Use Template</label>
                  <div class="custom-control custom-switch">
                    <input
                      type="checkbox"
                      class="custom-control-input"
                      id="useSMSCustomTemplate"
                      :class="{
                        'is-invalid': errors.hasOwnProperty(
                          'override_custom_template',
                        ),
                      }"
                      @keyup="delete errors.override_custom_template"
                      :title="errors.override_custom_template"
                      v-model="sms_form.override_custom_template"
                      :true-value="'1'"
                      :false-value="'0'"
                      checked
                    />
                    <label
                      class="custom-control-label"
                      for="useSMSCustomTemplate"
                    >
                      <span class="switch-icon-left"> ON</span>
                      <span class="switch-icon-right">OFF</span>
                    </label>
                  </div>
                </div>
              </div>
              <!-- Custom Template Override: END-->

              <!-- Custom SMS Template: START -->
              <div
                class="form-row"
                v-show="
                  form.action === 'SMS' &&
                    sms_form.override_custom_template === '1'
                "
              >
                <div class="form-group col-md-12">
                  <label>SMS Template</label>
                  <select
                    name="sms_custom_template"
                    id="sms_custom_template"
                    class="form-control"
                    placeholder="SMS Template"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('custom_template'),
                    }"
                    @keyup="delete errors.custom_template"
                    :title="errors.custom_template"
                    v-model="sms_form.custom_template"
                  >
                    <option value="" selected disabled="true">
                      -- Select SMS Template --
                    </option>
                    <option
                      :value="sms_template.file_name"
                      v-for="(sms_template, index) in getWorkFlowSMSTemplates"
                      :key="`workflow_sms_template_${index}`"
                    >
                      {{ sms_template.label }}
                    </option>
                  </select>
                </div>
              </div>
              <!-- Custom SMS Template: END-->

              <!-- SMS Content: START -->
              <div
                class="form-row sms-dom"
                v-show="
                  form.action === 'SMS' &&
                    sms_form.override_custom_template === '0'
                "
              >
                <div class="form-group col-md-12">
                  <label>Content</label>
                  <generic-text-editor
                    name="sms_content"
                    :key="`sms_content_${current_module}`"
                    :elementId="'sms_content_1'"
                    :options="getCurrentPlaceholders"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('content'),
                    }"
                    @keyup="delete errors.content"
                    :title="errors.content"
                    v-model="sms_form.content"
                  >
                    <template #placeholder>
                      <select class="ql-placeholder">
                        <option
                          :value="placeholder.label"
                          v-for="placeholder in getCurrentPlaceholderMappings"
                          :key="placeholder.label"
                        >
                          {{ placeholder.label }}
                        </option>
                      </select>
                    </template>
                  </generic-text-editor>
                </div>
              </div>
              <!-- SMS Content: END-->

              <!-- Primary Recipients: START -->
              <div class="form-row" v-show="form.action === 'EMAIL'">
                <div class="form-group col-md-12">
                  <label>Primary Recipients</label>
                  <v-select
                    id="email_primary_recipients"
                    placeholder="Primary Recipients"
                    v-model="email_form.primary_recipients"
                    @change="delete errors.primary_recipients"
                    :title="errors.primary_recipients"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('primary_recipients'),
                    }"
                    multiple
                    clearable
                    :options="getCurrentEmailPlaceholderMappings"
                    :reduce="(map_columns) => map_columns.value"
                    label="label"
                  >
                  </v-select>
                  <small
                    id="email_primary_recipients-error"
                    class="form-text invalid-feedback"
                    >{{ errors.primary_recipients }}</small
                  >
                </div>
              </div>
              <!-- Primary Recipients: END-->

              <!-- Additional Recipients: START -->
              <div class="form-row" v-show="form.action === 'EMAIL'">
                <div class="form-group col-md-12">
                  <label>Additional Recipients</label>
                  <v-select
                    id="email_additional_recipients"
                    placeholder="Additional Recipients"
                    v-model="email_form.additional_recipients"
                    @change="delete errors.additional_recipients"
                    :title="errors.additional_recipients"
                    :class="{
                      'is-invalid': errors.hasOwnProperty(
                        'additional_recipients',
                      ),
                    }"
                    multiple
                    taggable
                    clearable
                    :options="[]"
                  >
                  </v-select>
                  <small
                    id="email_additional_recipients-error"
                    class="form-text invalid-feedback"
                    >{{ errors.additional_recipients }}</small
                  >
                </div>
              </div>
              <!-- Additional Recipients: END-->

              <!-- Custom Template Override: START -->
              <div class="form-row" v-show="form.action === 'EMAIL'">
                <div class="form-group col-md-12">
                  <label>Use Template</label>
                  <div class="custom-control custom-switch">
                    <input
                      type="checkbox"
                      class="custom-control-input"
                      id="useEmailCustomTemplate"
                      v-model="email_form.override_custom_template"
                      :true-value="'1'"
                      :false-value="'0'"
                      checked
                    />
                    <label
                      class="custom-control-label"
                      for="useEmailCustomTemplate"
                    >
                      <span class="switch-icon-left"> ON</span>
                      <span class="switch-icon-right">OFF</span>
                    </label>
                  </div>
                </div>
              </div>
              <!-- Custom Template Override: END-->

              <!-- Custom Email Template: START -->
              <div
                class="form-row"
                v-show="
                  form.action === 'EMAIL' &&
                    email_form.override_custom_template === '1'
                "
              >
                <div class="form-group col-md-12">
                  <label>Email Template</label>
                  <select
                    name=""
                    id=""
                    v-model="email_form.custom_template"
                    class="form-control"
                    placeholder="Email Template"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('custom_template'),
                    }"
                    @keyup="delete errors.custom_template"
                    :title="errors.custom_template"
                  >
                    <option value="" selected disabled="true">
                      -- Select Email Template --
                    </option>
                    <option
                      :value="email_template.file_name"
                      v-for="(email_template,
                      index) in getWorkFlowEmailTemplates"
                      :key="`workflow_email_template_${index}`"
                    >
                      {{ email_template.label }}
                    </option>
                  </select>
                </div>
              </div>
              <!-- Custom Email Template: END-->

              <!-- Email Subject: START -->
              <div class="form-row email-dom" v-show="form.action === 'EMAIL'">
                <div class="form-group col-md-12">
                  <label>Subject</label>
                  <generic-text-editor
                    name="email_subject"
                    :key="`email_subject_1_${current_module}`"
                    :elementId="'email_subject_1'"
                    :options="getCurrentPlaceholders"
                    v-model="email_form.subject"
                    @keyup="delete errors.subject"
                    :title="errors.subject"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('subject'),
                    }"
                  >
                    <template #placeholder>
                      <select class="ql-placeholder">
                        <option
                          :value="placeholder.label"
                          v-for="placeholder in getCurrentPlaceholderMappings"
                          :key="`email_subject_${placeholder.label}`"
                        >
                          {{ placeholder.label }}
                        </option>
                      </select>
                    </template>
                  </generic-text-editor>
                </div>
              </div>
              <!-- Email Subject: END-->

              <!-- Email Content: START -->
              <div
                class="form-row"
                v-show="
                  form.action === 'EMAIL' &&
                    email_form.override_custom_template === '0'
                "
              >
                <div class="form-group col-md-12">
                  <label>Content</label>
                  <generic-text-editor
                    name="email_content"
                    :key="`email_content_${current_module}`"
                    :elementId="'email_content_1'"
                    :options="getCurrentPlaceholders"
                    v-model="email_form.content"
                  >
                    <template #placeholder>
                      <select class="ql-placeholder">
                        <option
                          :value="placeholder.label"
                          v-for="placeholder in getCurrentPlaceholderMappings"
                          :key="`email_content_1_${placeholder.label}`"
                        >
                          {{ placeholder.label }}
                        </option>
                      </select>
                    </template>
                  </generic-text-editor>
                </div>
              </div>
              <!-- Email Content: END-->

              <!-- Activity Title: START -->
              <div
                class="form-row activity-dom"
                v-show="form.action === 'ACTIVITY'"
              >
                <div class="form-group col-md-12">
                  <label>Title</label>
                  <generic-text-editor
                    name="activity_title"
                    :elementId="'activity_title_1'"
                    :key="`activity_title_1${current_module}`"
                    :options="getCurrentPlaceholders"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('title'),
                    }"
                    @keyup="delete errors.title"
                    :title="errors.title"
                    v-model="activity_form.title"
                  >
                    <template #placeholder>
                      <select class="ql-placeholder">
                        <option
                          :value="placeholder.label"
                          v-for="placeholder in getCurrentPlaceholderMappings"
                          :key="`activity_title_1${placeholder.label}`"
                        >
                          {{ placeholder.label }}
                        </option>
                      </select>
                    </template>
                  </generic-text-editor>
                </div>
              </div>
              <!-- Activity Title: END-->

              <!-- Activity Description: START -->
              <div
                class="form-row activity-dom"
                v-show="form.action === 'ACTIVITY'"
              >
                <div class="form-group col-md-12">
                  <label>Description</label>
                  <generic-text-editor
                    name="activity_description"
                    :elementId="'activity_description_1'"
                    :key="`activity_description_1_${current_module}`"
                    :options="getCurrentPlaceholders"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('description'),
                    }"
                    @keyup="delete errors.description"
                    :title="errors.description"
                    v-model="activity_form.description"
                  >
                    <template #placeholder>
                      <select class="ql-placeholder">
                        <option
                          :value="placeholder.label"
                          v-for="placeholder in getCurrentPlaceholderMappings"
                          :key="`activity_description_1_${placeholder.label}`"
                        >
                          {{ placeholder.label }}
                        </option>
                      </select>
                    </template>
                  </generic-text-editor>
                </div>
              </div>
              <!-- Activity Description: END-->

              <!-- Notify: START -->
              <div class="form-row" v-show="form.action === 'ACTIVITY'">
                <div class="form-group col-md-12">
                  <label>Notify</label>
                  <div class="custom-control custom-switch">
                    <input
                      type="checkbox"
                      class="custom-control-input"
                      id="notifyCustomButton"
                      v-model="activity_form.notify"
                      :true-value="'1'"
                      :false-value="'0'"
                      checked
                    />
                    <label
                      class="custom-control-label"
                      for="notifyCustomButton"
                    >
                      <span class="switch-icon-left"> ON</span>
                      <span class="switch-icon-right">OFF</span>
                    </label>
                  </div>
                </div>
              </div>
              <!-- Notify: END-->

              <!-- Notify: START -->
              <div
                class="form-row"
                v-show="
                  form.action === 'ACTIVITY' && activity_form.notify === '1'
                "
              >
                <div class="form-group col-md-12">
                  <label>Notification Method</label>
                  <v-select
                    id="notification_method"
                    placeholder="Notification Method"
                    v-model="activity_form.notification_method"
                    multiple
                    :options="['Push Notification', 'SMS', 'Email']"
                    clearable
                  >
                  </v-select>
                  <small
                    id="notification_method-error"
                    class="form-text invalid-feedback"
                    >{{ errors.notification_method }}</small
                  >
                </div>
              </div>
              <!-- Notify: END-->

              <!-- Automate Assignment: START -->
              <div class="form-row" v-show="form.action === 'ACTIVITY'">
                <div class="form-group col-md-12">
                  <label>Default Assignment</label>
                  <div class="custom-control custom-switch">
                    <input
                      type="checkbox"
                      class="custom-control-input"
                      id="automateAssignment"
                      v-model="activity_form.automate_assignment"
                      :true-value="'1'"
                      :false-value="'0'"
                      checked
                    />
                    <label
                      class="custom-control-label"
                      for="automateAssignment"
                    >
                      <span class="switch-icon-left"> ON</span>
                      <span class="switch-icon-right">OFF</span>
                    </label>
                  </div>
                </div>
              </div>
              <!-- Automate Assignment: END-->

              <!-- Default Assignment: START -->
              <div class="form-row" v-show="form.action === 'ACTIVITY'">
                <div class="form-group col-md-12">
                  <label>Modify Assignment</label>
                  <select
                    name="default_assignee"
                    id="default_assignee"
                    class="form-control"
                    placeholder="Default Assignment"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('default_assignee'),
                    }"
                    @keyup="delete errors.default_assignee"
                    :title="errors.default_assignee"
                    v-model="activity_form.default_assignee"
                  >
                    <option value="" selected disabled="true">
                      -- Select User --
                    </option>
                    <option
                      :value="user.user_id"
                      v-for="user in allUsers"
                      :key="user.user_id + '_assignee'"
                      v-text="user.name"
                    ></option>
                  </select>
                </div>
              </div>
              <!-- Default Assignment: END-->

              <!-- Priority: START -->
              <div class="form-row" v-show="form.action === 'ACTIVITY'">
                <div class="form-group col-md-12">
                  <label>Priority</label>
                  <select
                    name="priority"
                    id="Priority"
                    class="form-control"
                    placeholder="Priority"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('priority'),
                    }"
                    @keyup="delete errors.priority"
                    :title="errors.priority"
                    v-model="activity_form.priority"
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
                </div>
              </div>
              <!-- Priority: END-->

              <!-- Activity Type: START -->
              <div class="form-row" v-show="form.action === 'ACTIVITY'">
                <div class="form-group col-md-12">
                  <label>Activity Type</label>
                  <select
                    name="activity_type"
                    id="activity_type"
                    class="form-control"
                    placeholder="Activity Type"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('activity_type'),
                    }"
                    @keyup="delete errors.activity_type"
                    :title="errors.activity_type"
                    v-model="activity_form.activity_type"
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
              <!-- Activity Type: END-->

              <!-- Activity Method: START -->
              <div
                class="form-row"
                v-show="
                  form.action === 'ACTIVITY' &&
                    activity_form.activity_type !== 'Task'
                "
              >
                <div class="form-group col-md-12">
                  <label>Activity Method</label>
                  <select
                    name="activity_method"
                    id="activity_method"
                    class="form-control"
                    placeholder="Activity Method"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('activity_method'),
                    }"
                    @keyup="delete errors.activity_method"
                    :title="errors.activity_method"
                    v-model="activity_form.activity_method"
                  >
                    <option value="" selected disabled="true">
                      -- Select Method --
                    </option>
                    <option
                      :value="activity_method"
                      v-for="activity_method in activityMethodTypes"
                      :key="activity_method"
                    >
                      {{ activity_method }}
                    </option>
                  </select>
                </div>
              </div>
              <!-- Activity Method: END-->
            </form>
          </div>
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
              class="btn btn-primary"
              @click.prevent="saveWorkFlowTemplateStep"
            >
              Save
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Add Step Model : END -->
  </div>
</template>
<script>
import Toast from "../../../library/Toast";
import { mapGetters, mapActions } from "vuex";
import draggable from "vuedraggable";
import vSelect from "vue-select";
import GenericTextEditor from "../../WebComponents/GenericTextEditor.vue";

export default {
  props: ["application", "app_metadata", "current_module"],
  name: "WorkFlowTemplateStep",
  data() {
    return {
      is_loaded: true,
      componentKey: 0,
      dragging: true,
      email_form: {
        subject: "",
        content: "",
        override_custom_template: "0",
        custom_template: "",
        primary_recipients: [],
        additional_recipients: [],
      },
      sms_form: {
        content: "",
        primary_recipients: [],
        additional_recipients: [],
        override_custom_template: "0",
        custom_template: "",
      },
      activity_form: {
        title: "",
        description: "",
        notify: "0",
        notification_method: ["Push Notification"],
        automate_assignment: "0",
        default_assignee: "",
        priority: "Normal",
        activity_type: "Task",
        activity_method: "",
      },
      form: {
        wait_time: "0",
        wait_time_unit: "HOURS",
        action: "",
      },
      workflow_template_step_id: null,
      title: "Add Step",
      workflow_template_step_list: [],
      errors: [],
      workflow_sms_templates: [],
      workflow_email_templates: [],
    };
  },
  components: {
    GenericTextEditor,
    "v-select": vSelect,
    draggable,
  },
  computed: {
    ...mapGetters("users", ["allUsers"]),
    ...mapGetters("workFlow", [
      "getAvailableCommonPlaceholders",
      "getAvailableInventoryPlaceholders",
      "getAvailableLeadPlaceholders",
      "getAvailableCustomerPlaceholders",
      "getAvailableDealPlaceholders",

      "getAvailableCommonEmailPlaceholders",
      "getAvailableInventoryEmailPlaceholders",
      "getAvailableLeadEmailPlaceholders",
      "getAvailableCustomerEmailPlaceholders",
      "getAvailableDealEmailPlaceholders",

      "getAvailableCommonMobilePlaceholders",
      "getAvailableInventoryMobilePlaceholders",
      "getAvailableLeadMobilePlaceholders",
      "getAvailableCustomerMobilePlaceholders",
      "getAvailableDealMobilePlaceholders",
    ]),
    ...mapGetters("calendarView", [
      "getActivityTypes",
      "getStatuses",
      "getPriorities",
    ]),
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost",
      };
    },
    activityMethodTypes() {
      if (this.activity_form.activity_type == "Appointment") {
        return [
          "Phone Call",
          "Store Visit",
          "Test Drive",
          "Vehicle Delivery",
          "Service Appointment",
          "Other",
          "None",
        ];
      } else if (this.activity_form.activity_type == "Log") {
        return ["Log Call", "Store Visit", "Test Drive", "Other"];
      }

      return [];
    },
    getCurrentPlaceholderMappings() {
      let current_placeholders = null;

      switch (this.current_module) {
        case "Inventory":
          current_placeholders = [
            ...this.getAvailableCommonPlaceholders,
            ...this.getAvailableInventoryPlaceholders,
          ];
          break;
        case "Lead":
          current_placeholders = [
            ...this.getAvailableCommonPlaceholders,
            ...this.getAvailableLeadPlaceholders,
          ];
          break;
        case "Customer":
          current_placeholders = [
            ...this.getAvailableCommonPlaceholders,
            ...this.getAvailableCustomerPlaceholders,
          ];
          break;
        case "Deal":
          current_placeholders = [
            ...this.getAvailableCommonPlaceholders,
            ...this.getAvailableDealPlaceholders,
          ];
          break;

        default:
          break;
      }

      return current_placeholders || [];
    },
    getCurrentPlaceholders() {
      return {
        modules: {
          placeholder: {
            placeholders: this.getCurrentPlaceholderMappings.map(
              (placeholder) => {
                return { id: placeholder.value, label: placeholder.label };
              },
            ),
          },
        },
      };
    },
    getCurrentPlaceholdersDOM() {
      let html = `<select class="ql-placeholder">`;
      for (const placeholder of this.getCurrentPlaceholderMappings) {
        html += `<option value="${placeholder.value}">${placeholder.label}</option>`;
      }
      html += `</select>`;
      return html;
    },
    getCurrentEmailPlaceholderMappings() {
      let current_placeholders = [];

      switch (this.current_module) {
        case "Inventory":
          current_placeholders = [
            ...this.getAvailableCommonEmailPlaceholders,
            ...this.getAvailableInventoryEmailPlaceholders,
          ];
          break;
        case "Lead":
          current_placeholders = [
            ...this.getAvailableCommonEmailPlaceholders,
            ...this.getAvailableLeadEmailPlaceholders,
          ];
          break;
        case "Customer":
          current_placeholders = [
            ...this.getAvailableCommonEmailPlaceholders,
            ...this.getAvailableCustomerEmailPlaceholders,
          ];
          break;
        case "Deal":
          current_placeholders = [
            ...this.getAvailableCommonEmailPlaceholders,
            ...this.getAvailableDealEmailPlaceholders,
          ];
          break;

        default:
          break;
      }

      return current_placeholders || [];
    },
    getCurrentMobilePlaceholderMappings() {
      let current_placeholders = [];

      switch (this.current_module) {
        case "Inventory":
          current_placeholders = [
            ...this.getAvailableCommonMobilePlaceholders,
            ...this.getAvailableInventoryMobilePlaceholders,
          ];
          break;
        case "Lead":
          current_placeholders = [
            ...this.getAvailableCommonMobilePlaceholders,
            ...this.getAvailableLeadMobilePlaceholders,
          ];
          break;
        case "Customer":
          current_placeholders = [
            ...this.getAvailableCommonMobilePlaceholders,
            ...this.getAvailableCustomerMobilePlaceholders,
          ];
          break;
        case "Deal":
          current_placeholders = [
            ...this.getAvailableCommonMobilePlaceholders,
            ...this.getAvailableDealMobilePlaceholders,
          ];
          break;

        default:
          break;
      }

      return current_placeholders || [];
    },
    getWorkFlowSMSTemplates() {
      return this.workflow_sms_templates.filter((template) =>
        template.modules.includes(this.current_module.toUpperCase()),
      );
    },
    getWorkFlowEmailTemplates() {
      return this.workflow_email_templates.filter((template) =>
        template.modules.includes(this.current_module.toUpperCase()),
      );
    },
    updateView() {
      return (
        this.$route.params.id && this.$route.name == "workflow-template-update"
      );
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
    async getWorkFlowConfigurations() {
      let request_url =
        window.location.origin + "/api/business/configurations/active/workflow";
      await axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      }).then(({ data }) => {
        for (const key in data) {
          if (data.hasOwnProperty(key)) {
            const element = data[key];
            if (element.key == "workflow_sms_templates") {
              if (this.$helpers.isValidJsonString(element.value)) {
                this.workflow_sms_templates = JSON.parse(element.value);
              }
            } else if (element.key == "workflow_email_templates") {
              if (this.$helpers.isValidJsonString(element.value)) {
                this.workflow_email_templates = JSON.parse(element.value);
              }
            }
          }
        }
      });
    },
    async saveWorkFlowTemplateStep() {
      //save or update the purchase data
      const vm = this;
      //create data
      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key) && this.form[key] != null) {
          formData.append(key, this.form[key]);
        }
      }

      if (this.form.action === "EMAIL") {
        for (const key in this.email_form) {
          if (
            this.email_form.hasOwnProperty(key) &&
            this.email_form[key] != null
          ) {
            formData.append(key, this.email_form[key]);
          }
        }

        const attributes = ["primary_recipients", "additional_recipients"];

        for (const attribute of attributes) {
          if (Array.isArray(this.email_form[attribute])) {
            formData.append(
              attribute,
              JSON.stringify(this.email_form[attribute]),
            );
          } else {
            formData.append(attribute, "[]");
          }
        }
      }

      if (this.form.action === "SMS") {
        for (const key in this.sms_form) {
          if (this.sms_form.hasOwnProperty(key) && this.sms_form[key] != null) {
            formData.append(key, this.sms_form[key]);
          }
        }

        const attributes = ["primary_recipients", "additional_recipients"];

        for (const attribute of attributes) {
          if (Array.isArray(this.sms_form[attribute])) {
            formData.append(
              attribute,
              JSON.stringify(this.sms_form[attribute]),
            );
          } else {
            formData.append(attribute, "[]");
          }
        }
      }

      if (this.form.action === "ACTIVITY") {
        for (const key in this.activity_form) {
          if (
            this.activity_form.hasOwnProperty(key) &&
            this.activity_form[key] != null &&
            key !== "notification_method"
          ) {
            formData.append(key, this.activity_form[key]);
          }

          if (key === "notification_method") {
            if (Array.isArray(this.activity_form["notification_method"])) {
              formData.append(
                "notification_method",
                JSON.stringify(this.activity_form["notification_method"]),
              );
            } else {
              formData.append("notification_method", "[]");
            }
          }
        }
      }

      let request_url = window.location.origin;

      let update_request =
        !isNaN(this.workflow_template_step_id) &&
        this.workflow_template_step_id != null;

      let index = this.workflow_template_step_list.length || 1;
      ++index;
      if (!update_request) {
        formData.append("index", index);
      }

      //success message setting
      let success_message = update_request
        ? `Workflow template updated.`
        : `Workflow template added.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/workflow-templates/${this.$route.params.id}/common/steps/update/${this.workflow_template_step_id}`
        : `${request_url}/api/business/workflow-templates/${this.$route.params.id}/common/steps/create`;

      //message during request
      let request_message = update_request
        ? `Updating workflow template details ...`
        : `Adding new workflow template details ...`;

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
          vm.resetStepForm();
          vm.resetActivityForm();
          vm.resetEmailForm();
          vm.resetSMSForm();
          document.getElementById("workFlowTemplateStepClose").click();
          toast.success(data.success);
          toast.close();

          if (
            vm.$route.params.id &&
            vm.$route.name == "workflow-template-update"
          ) {
            vm.getWorkFlowTemplateStepList(vm.$route.params.id);
          }
        })
        .catch(function(error) {
          let update_request =
            !isNaN(vm.workflow_template_step_id) &&
            vm.workflow_template_step_id != null;

          let error_message = update_request
            ? "There were errors while updating the workflow steps."
            : "There were some errors while adding workflow steps.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();
        });

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "workflow_template",
        "steps",
        "save",
      );
    },
    async editWorkFlowTemplateStep(id) {
      const vm = this;
      let url = `${window.location.origin}/api/business/workflow-templates/${this.$route.params.id}/common/steps/show/${id}`;

      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          this.errors = [];
          for (const key in this.form) {
            if (this.form.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }

          if (data["action"] === "EMAIL") {
            for (const key in this.email_form) {
              if (
                this.email_form.hasOwnProperty(key) &&
                data.hasOwnProperty(key)
              ) {
                this.email_form[key] = data[key];
              }
            }

            const attributes = ["primary_recipients", "additional_recipients"];

            for (const attribute of attributes) {
              if (this.$helpers.isValidJsonString(data[attribute])) {
                this.email_form[attribute] = JSON.parse(data[attribute]);
              } else {
                this.email_form[attribute] = [];
              }
            }
          }

          if (data["action"] === "SMS") {
            for (const key in this.sms_form) {
              if (
                this.sms_form.hasOwnProperty(key) &&
                data.hasOwnProperty(key)
              ) {
                this.sms_form[key] = data[key];
              }
            }

            const attributes = ["primary_recipients", "additional_recipients"];

            for (const attribute of attributes) {
              if (this.$helpers.isValidJsonString(data[attribute])) {
                this.sms_form[attribute] = JSON.parse(data[attribute]);
              } else {
                this.sms_form[attribute] = [];
              }
            }
          }

          if (data["action"] === "ACTIVITY") {
            for (const key in this.activity_form) {
              if (
                this.activity_form.hasOwnProperty(key) &&
                data.hasOwnProperty(key) &&
                key !== `notification_method`
              ) {
                this.activity_form[key] = data[key];
              }
            }

            if (this.$helpers.isValidJsonString(data.notification_method)) {
              this.activity_form.notification_method = JSON.parse(
                data.notification_method,
              );
            } else {
              this.activity_form.notification_method = ["Push Notification"];
            }
          }

          this.workflow_template_step_id = data.id;
          this.title = "Update Step";

          jQuery("#workFlowTemplateStep").modal("show");
        })
        .catch(({ error }) => {
          let error_message = `Error in fetching workflow template step.`;
          const toast = new Toast("");
          toast.fire();
          toast.fail(error_message);
          toast.close();
        });
    },
    async deleteWorkFlowTemplateStep(id) {
      const vm = this;
      let url = `${window.location.origin}/api/business/workflow-templates/${this.$route.params.id}/common/steps/delete/${id}`;

      let message = "Deleting workflow template step...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `Workflow Template has been deleted.`;
          toast.success(success_message);
          toast.close();

          setTimeout(() => {
            if (
              vm.$route.params.id &&
              vm.$route.name == "workflow-template-update"
            ) {
              vm.getWorkFlowTemplateStepList(vm.$route.params.id, true);
            }
          }, 1000);
        })
        .catch(({ error }) => {
          let error_message = `Error in deleting Workflow Template.`;
          toast.fail(error_message);
          toast.close();
        });

      await this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "workflow_template",
        "step",
        "delete",
      );
    },
    async getWorkFlowTemplateStepList(wft_step_id, sort = false) {
      const vm = this;
      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/workflow-templates/${wft_step_id}/common/steps`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.workflow_template_step_list = data;
          if (sort) {
            vm.sortIndex();
          }
        })
        .catch(({ response }) => {
          this.workflow_template_step_list = [];
          console.log(response);
        });
    },
    async resetEmailForm() {
      this.email_form.subject = this.email_form.content = this.email_form.custom_template =
        "";
      this.email_form.override_custom_template = "0";
      this.email_form.primary_recipients = this.email_form.additional_recipients = [];
    },
    async resetSMSForm() {
      this.sms_form.content = this.sms_form.custom_template = "";
      this.sms_form.override_custom_template = "0";
      this.sms_form.primary_recipients = this.sms_form.additional_recipients = [];
    },
    async resetActivityForm() {
      this.activity_form.title = this.activity_form.description = this.activity_form.default_assignee = this.activity_form.activity_method =
        "";
      this.activity_form.notify = this.activity_form.automate_assignment = "0";
      this.activity_form.notification_method = ["Push Notification"];
      this.activity_form.priority = "Normal";
      this.activity_form.activity_type = "Task";
    },
    async resetStepForm() {
      this.form.wait_time = "0";
      this.form.wait_time_unit = "HOURS";
      this.form.action = "";
      this.title = "Add Step";
      this.workflow_template_step_id = null;
    },
    async sortIndex() {
      const vm = this;
      this.is_loaded = false;
      let formData = new FormData();
      formData.append(
        "steps",
        JSON.stringify(this.workflow_template_step_list),
      );

      let message = "Sorting workflow template steps...";
      const toast = new Toast(message);
      toast.fire();
      if (this.updateView) {
        await axios({
          method: "post",
          data: formData,
          url: `${window.location.origin}/api/business/workflow-templates/${vm.$route.params.id}/common/steps/sort`,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
            "Content-Type": "multipart/form-data",
          },
        })
          .then(({ data }) => {
            let success_message = "Workflow template steps have been sorted.";
            vm.getWorkFlowTemplateStepList(vm.$route.params.id);
            toast.success(success_message);
            toast.close();
            vm.is_loaded = true;
          })
          .catch((error) => {
            let error_message =
              "There were errors while sorting workflow template steps.";
            toast.fail(error_message);
            toast.close();
            vm.is_loaded = true;
          });
      }
    },
  },
  async created() {
    this.getSetUpAttributes();

    if (
      this.$route.params.id &&
      this.$route.name == "workflow-template-update"
    ) {
      this.getWorkFlowTemplateStepList(this.$route.params.id);
      this.getWorkFlowConfigurations();
    }
  },
  async mounted() {
    const vm = this;
    this.getAllUsers();
    jQuery("#workFlowTemplateStep").on("hidden.bs.modal", function() {
      vm.resetStepForm();
      vm.resetActivityForm();
      vm.resetEmailForm();
      vm.resetSMSForm();
      vm.errors = [];
    });
  },
};
</script>
<style lang="scss">
.quill-editor.is-invalid {
  border: 1px solid #ff5b5c;
  border-radius: 0.267rem;
}

.v-select.is-invalid {
  border: 1px solid #ff5b5c;
  border-radius: 0.267rem;
}

.is-invalid + .invalid-feedback {
  display: block !important;
}

#workflow-template-steps
  .sms-dom
  .quill-editor
  .ql-snow
  > span.ql-formats:not(:last-child),
#workflow-template-steps
  .activity-dom
  .quill-editor
  .ql-snow
  > span.ql-formats:not(:last-child),
#workflow-template-steps
  .email-dom
  .quill-editor
  .ql-snow
  > span.ql-formats:not(:last-child) {
  display: none;
}
</style>
