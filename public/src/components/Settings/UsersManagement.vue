<template>
  <!-- BEGIN: Content-->
  <div class="app-content content" v-if="tenantApp && isAuthorized">
    <div class="content-overlay"></div>
    <div class="content-body">
      <div class="webapp-form">
        <section id="syndication-table-transactions">
          <div
            class="border-bottom mt-3 bg-white header-navbar set-dable-layer"
            style="background-color: transparent; z-index: 2"
          >
            <div class="content-wrapper pt-0 pb-1 mt-0">
              <div class="card shadow-none mb-0">
                <div class="row">
                  <h2 class="h1 mt-1 mb-1 col-12">
                    User Management
                    <button
                      type="button"
                      class="btn btn-outline-primary float-right"
                      data-toggle="modal"
                      data-target="#userCreateModal"
                    >
                      + Add User
                    </button>
                  </h2>
                  <p class="col-12 text-danger">
                    NOTE: This section is only available to Account Owner and
                    ZOP Staff.
                  </p>
                  <div class="col-md-12" v-show="!submit_configuration">
                    Loading Saved Configurations ...
                  </div>
                  <div class="w-100" v-show="submit_configuration">
                    <!-- Allow Staff to view all the leads : START -->
                    <div class="row">
                      <div class="col-md-4">
                        <label>Allow Staff to view all the leads? </label>
                      </div>
                      <div class="col-md-8 form-group">
                        <div class="custom-control custom-switch">
                          <input
                            type="checkbox"
                            class="custom-control-input"
                            v-model="
                              global_configuration.authorize_staff_to_view_all_leads
                            "
                            :true-value="'1'"
                            :false-value="'0'"
                            @change="saveConfiguration"
                            name="authorize_staff_to_view_all_leads"
                            id="authorize_staff_to_view_all_leads"
                          />
                          <label
                            class="custom-control-label"
                            for="authorize_staff_to_view_all_leads"
                          >
                            <span class="switch-icon-left">ON</span>
                            <span class="switch-icon-right">OFF</span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <!-- Allow Staff to view all the leads : END -->

                    <!-- Allow Staff to view all the customers : START -->
                    <div class="row">
                      <div class="col-md-4">
                        <label>Allow Staff to view all the customers? </label>
                      </div>
                      <div class="col-md-8 form-group">
                        <div class="custom-control custom-switch">
                          <input
                            type="checkbox"
                            class="custom-control-input"
                            v-model="
                              global_configuration.authorize_staff_to_view_all_customers
                            "
                            :true-value="'1'"
                            :false-value="'0'"
                            @change="saveConfiguration"
                            name="authorize_staff_to_view_all_customers"
                            id="authorize_staff_to_view_all_customers"
                          />
                          <label
                            class="custom-control-label"
                            for="authorize_staff_to_view_all_customers"
                          >
                            <span class="switch-icon-left">ON</span>
                            <span class="switch-icon-right">OFF</span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <!-- Allow Staff to view all the customers : END -->

                    <!-- Allow Staff to view all the deals : START -->
                    <div class="row">
                      <div class="col-md-4">
                        <label>Allow Staff to view all the deals? </label>
                      </div>
                      <div class="col-md-8 form-group">
                        <div class="custom-control custom-switch">
                          <input
                            type="checkbox"
                            class="custom-control-input"
                            v-model="
                              global_configuration.authorize_staff_to_view_all_deals
                            "
                            :true-value="'1'"
                            :false-value="'0'"
                            @change="saveConfiguration"
                            name="authorize_staff_to_view_all_deals"
                            id="authorize_staff_to_view_all_deals"
                          />
                          <label
                            class="custom-control-label"
                            for="authorize_staff_to_view_all_deals"
                          >
                            <span class="switch-icon-left">ON</span>
                            <span class="switch-icon-right">OFF</span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <!-- Allow Staff to view all the deals : END -->
                  </div>
                  <hr />
                  <table class="table" id="user-management-table">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col">User ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email Address</th>
                        <th scope="col">Title</th>
                        <th scope="col">Registration No</th>
                        <th scope="col">Role</th>
                        <th scope="col">Lead Auto Assign</th>
                        <th scope="col">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="user in users" :key="user.email">
                        <td>{{ user.user_id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.title }}</td>
                        <td>{{ user.registration_number }}</td>
                        <td>{{ user.role }}</td>
                        <td>{{ userAssignFlag(user.lead_auto_assign) }}</td>
                        <td>
                          <i
                            @click.prevent="openEditUser(user.user_id)"
                            style="
                              font-size: 1.4em;
                              background: whitesmoke;
                              cursor: pointer;
                            "
                            class="bx text-info bx bx-edit mr-1"
                          ></i>
                          <i
                            @click.prevent="
                              openUserRole(user.user_id, user.role, user.name)
                            "
                            title="Change Role"
                            style="
                              font-size: 1.4em;
                              background: whitesmoke;
                              cursor: pointer;
                            "
                            class="bx text-success bx bxs-user-badge mr-1"
                          ></i>
                          <i
                            v-if="user.is_active == 1"
                            @click.prevent="
                              warningForStatusChangeForTheUser(
                                user.user_id,
                                user.name,
                                0
                              )
                            "
                            :title="`Block ${user.name}`"
                            style="
                              font-size: 1.4em;
                              background: whitesmoke;
                              cursor: pointer;
                            "
                            class="bx text-danger bx bx-block mr-1"
                          ></i>
                          <i
                            v-else
                            @click.prevent="
                              warningForStatusChangeForTheUser(
                                user.user_id,
                                user.name,
                                1
                              )
                            "
                            :title="`Unblock ${user.name}`"
                            style="
                              font-size: 1.4em;
                              background: whitesmoke;
                              cursor: pointer;
                            "
                            class="bx text-success bx bxs-group mr-1"
                          ></i>
                          <i
                            @click.prevent="
                              warningForDeletingTheUser(user.user_id, user.name)
                            "
                            :title="`Delete ${user.name}`"
                            style="
                              font-size: 1.4em;
                              background: whitesmoke;
                              cursor: pointer;
                            "
                            class="bx text-danger bx bxs-trash mr-1"
                          ></i>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
    <!-- Model User : START -->
    <div
      class="modal fade"
      id="userCreateModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="User Create Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <!-- Modal Header : START -->
          <div class="modal-header">
            <h5 class="modal-title">Create User</h5>
            <button
              type="button"
              id="userCreateModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- Modal Header : END -->
          <!--Modal Body : START -->
          <div class="modal-body d-flex">
            <div class="col-6">
              <!-- name : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label style="color: red" for="user_name">Name : *</label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('name'),
                    }"
                    @keyup="delete errors.name"
                    :title="errors.name"
                    v-model="form.name"
                    id="user_name"
                    placeholder="Name"
                  />
                </div>
              </div>
              <!-- name : END -->

              <!-- email : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="user_email" style="color: red">Email : *</label>
                  <input
                    type="email"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('email'),
                    }"
                    @keyup="delete errors.email"
                    :title="errors.email"
                    v-model="form.email"
                    id="user_email"
                    placeholder="Email"
                  />
                </div>
              </div>
              <!-- email : END -->

              <!-- password : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="user_password" style="color: red"
                    >Password : *</label
                  >
                  <input
                    type="password"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('password'),
                    }"
                    @keyup="delete errors.password"
                    :title="errors.password"
                    v-model="form.password"
                    id="user_password"
                    placeholder="Password"
                  />
                </div>
              </div>
              <!-- password : END -->

              <!-- confirm_password : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="user_confirm_password" style="color: red"
                    >Confirm Password : *</label
                  >
                  <input
                    type="password"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('confirm_password'),
                    }"
                    @keyup="delete errors.confirm_password"
                    :title="errors.confirm_password"
                    v-model="form.confirm_password"
                    id="user_confirm_password"
                    placeholder="Repeat Password"
                  />
                </div>
              </div>
              <!-- confirm_password : END -->

              <!-- role_id : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="role_id">Role : </label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('role_id'),
                    }"
                    @keyup="delete errors.role_id"
                    :title="errors.role_id"
                    v-model="form.role_id"
                    placeholder="Role"
                    id="role_id"
                  >
                    <option value="" disabled>Role</option>
                    <option
                      :value="role.id"
                      :key="role.name"
                      v-for="role in roles"
                    >
                      {{ role.name }}
                    </option>
                  </select>
                </div>
                <small class="col-md-12 text-danger" v-if="form.role_id === '1'"
                  >User with role of Owner will have access to other accounts
                  and have ability to modify other user details.</small
                >
              </div>
              <!-- role_id : END -->

              <!-- lead_auto_assign : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="lead_auto_assign">Auto Assign : </label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('lead_auto_assign'),
                    }"
                    @keyup="delete errors.lead_auto_assign"
                    :title="errors.lead_auto_assign"
                    v-model="form.lead_auto_assign"
                    placeholder="Auto Assign Lead"
                    id="lead_auto_assign"
                  >
                    <option value="" disabled>-- Select --</option>
                    <option value="0">OFF</option>
                    <option value="1">ON</option>
                  </select>
                </div>
                <small class="col-md-12 text-danger"
                  >Leads which has Auto Assign marked ON are auto assigned
                  according to the enabled lead auto assign strategy.</small
                >
              </div>
              <!-- lead_auto_assign : END -->
            </div>
            <div class="col-6">
              <!-- title : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="user_title">Title : </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty('title'),
                    }"
                    @keyup="delete errors.title"
                    :title="errors.title"
                    v-model="form.title"
                    id="user_title"
                    placeholder="Ex: Salesperson"
                  />
                </div>
              </div>
              <!-- title : END -->

              <!-- Registration Number : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="user_registration_number"
                    >Registration # :
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty(
                        'registration_number'
                      ),
                    }"
                    @keyup="delete errors.registration_number"
                    :title="errors.registration_number"
                    v-model="form.registration_number"
                    id="user_registration_number"
                    placeholder="RTXXX"
                  />
                </div>
              </div>
              <!-- Registration Number : END -->

              <!-- communication_phone : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="communication_phone"
                    >Phone # : (10 Digits)
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty(
                        'communication_phone'
                      ),
                    }"
                    @keyup="delete errors.communication_phone"
                    :title="errors.communication_phone"
                    v-model="form.communication_phone"
                    id="communication_phone"
                    placeholder="Notification Phone"
                  />
                </div>
              </div>
              <!-- communication_phone : END -->

              <!-- communication_email : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="communication_email">CommunicatiOn Email </label>
                  <input
                    type="email"
                    class="form-control"
                    :class="{
                      'is-invalid': errors.hasOwnProperty(
                        'communication_email'
                      ),
                    }"
                    @keyup="delete errors.communication_email"
                    :title="errors.communication_email"
                    v-model="form.communication_email"
                    id="communication_email"
                    placeholder="Notification Email"
                  />
                </div>
              </div>
              <!-- communication_email : END -->

              <!-- timezone : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="user_timezone">Select Timezone : </label>
                  <v-select
                    id="user_timezone"
                    placeholder="Timezone"
                    v-model="form.timezone"
                    :options="getTimeZones"
                  ></v-select>
                </div>
              </div>
              <!-- timezone : END -->
            </div>
          </div>
          <!-- Modal Body : END -->

          <!-- modal footer : START -->
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
              @click.prevent="save"
              :disabled="!submit_button"
            >
              Save
            </button>
          </div>
          <!-- modal footer : END -->
        </div>
      </div>
    </div>
    <!-- Model User : END -->

    <!-- Model Edit User : START -->
    <div
      class="modal fade"
      id="userEditModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="User Edit Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <!-- Modal Header : START -->
          <div class="modal-header">
            <h5 class="modal-title">Edit Existing User</h5>
            <button
              type="button"
              id="userEditModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- Modal Header : END -->
          <!--Modal Body : START -->
          <div class="modal-body d-flex">
            <div class="col-12">
              <!-- name : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="user_edit_name">Name : </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': user_errors.hasOwnProperty('name'),
                    }"
                    @keyup="delete user_errors.name"
                    :title="user_errors.name"
                    v-model="user.name"
                    id="user_edit_name"
                    placeholder="Name"
                  />
                </div>
              </div>
              <!-- name : END -->

              <!-- title : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="user_edit_title">Title : </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': user_errors.hasOwnProperty('title'),
                    }"
                    @keyup="delete user_errors.title"
                    :title="user_errors.title"
                    v-model="user.title"
                    id="user_edit_title"
                    placeholder="Ex: Salesperson"
                  />
                </div>
              </div>
              <!-- title : END -->

              <!-- Registration Number : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="user_edit_registration_number"
                    >Registration # :
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': user_errors.hasOwnProperty(
                        'registration_number'
                      ),
                    }"
                    @keyup="delete user_errors.registration_number"
                    :title="user_errors.registration_number"
                    v-model="user.registration_number"
                    id="user_edit_registration_number"
                    placeholder="RTXXX"
                  />
                </div>
              </div>
              <!-- Registration Number : END -->

              <!-- Lead auto assign : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="user_lead_auto_assign">Auto Assign : </label>
                  <select
                    class="custom-select form-control mr-1"
                    :class="{
                      'is-invalid':
                        user_errors.hasOwnProperty('lead_auto_assign'),
                    }"
                    @keyup="delete user_errors.lead_auto_assign"
                    :title="user_errors.lead_auto_assign"
                    v-model="user.lead_auto_assign"
                    placeholder="Auto Assign Lead"
                    id="user_lead_auto_assign"
                  >
                    <option value="" disabled>-- Select --</option>
                    <option value="0">OFF</option>
                    <option value="1">ON</option>
                  </select>
                </div>
                <small class="col-md-12 text-danger"
                  >Leads which has Auto Assign marked ON are auto assigned
                  according to the enabled lead auto assign strategy.</small
                >
              </div>
              <!-- Lead auto assign : END -->

              <!-- communication_phone : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="user_edit_communication_phone"
                    >Phone # : (10 Digits)
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': user_errors.hasOwnProperty('phone'),
                    }"
                    @keyup="delete user_errors.phone"
                    :title="user_errors.phone"
                    v-model="user.phone"
                    id="user_edit_communication_phone"
                    placeholder="Notification Phone"
                  />
                </div>
              </div>
              <!-- communication_phone : END -->

              <!-- communication_email : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="user_edit_communication_email"
                    >Communication Email
                  </label>
                  <input
                    type="email"
                    class="form-control"
                    :class="{
                      'is-invalid': user_errors.hasOwnProperty('email'),
                    }"
                    @keyup="delete user_errors.email"
                    :title="user_errors.email"
                    v-model="user.email"
                    id="user_edit_communication_email"
                    placeholder="Notification Email"
                  />
                </div>
              </div>
              <!-- communication_email : END -->

              <!-- timezone : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="user_edit_timezone">Select Timezone : </label>
                  <v-select
                    id="user_edit_timezone"
                    placeholder="Timezone"
                    v-model="user.timezone"
                    :options="getTimeZones"
                  ></v-select>
                </div>
              </div>
              <!-- timezone : END -->
            </div>
          </div>
          <!-- Modal Body : END -->

          <!-- modal footer : START -->
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
              @click.prevent="updateUser"
              :disabled="!submit_button"
            >
              Save
            </button>
          </div>
          <!-- modal footer : END -->
        </div>
      </div>
    </div>
    <!-- Model Edit User : END -->

    <!-- Model User Role : START -->
    <div
      class="modal fade"
      id="userRoleModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="User Role Change Modal"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <!-- Modal Header : START -->
          <div class="modal-header">
            <h5 class="modal-title">
              Updating User Role for {{ user_role.name }}
            </h5>
            <button
              type="button"
              id="userRoleModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- Modal Header : END -->
          <!--Modal Body : START -->
          <div class="modal-body d-flex">
            <div class="col-12">
              <!-- role_id : START -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="role_id">Role : </label>
                  <select
                    class="custom-select form-control mr-1"
                    v-model="user_role.role_id"
                    placeholder="Role"
                    id="role_id"
                  >
                    <option value="" disabled>Role</option>
                    <option
                      :value="role.id"
                      :key="role.name"
                      v-for="role in roles"
                    >
                      {{ role.name }}
                    </option>
                  </select>
                </div>
                <small
                  class="col-md-12 text-danger"
                  v-if="user_role.role_id === '1'"
                  >User with role of Owner will have access to other accounts
                  and have ability to modify other user details.</small
                >
              </div>
              <!-- role_id : END -->
            </div>
          </div>
          <!-- Modal Body : END -->

          <!-- modal footer : START -->
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
              @click.prevent="updateUserRole"
              :disabled="!submit_button"
            >
              Save
            </button>
          </div>
          <!-- modal footer : END -->
        </div>
      </div>
    </div>
    <!-- Model User Role : END -->
  </div>
</template>
<script>
import Toast from "../../library/Toast";
import vSelect from "vue-select";

export default {
  props: ["application", "app_metadata"],
  name: "UsersManagement",
  data() {
    return {
      submit_button: true,
      users: [],
      roles: [],
      submit_configuration: false,
      global_configuration: {
        authorize_staff_to_view_all_deals: "1",
        authorize_staff_to_view_all_leads: "1",
        authorize_staff_to_view_all_customers: "1",
      },
      form: {
        email: "",
        password: "",
        confirm_password: "",
        role_id: "3",
        name: "",
        title: "",
        registration_number: "",
        communication_phone: "",
        communication_email: "",
        timezone: "",
        lead_auto_assign: "1",
      },
      user: {
        name: "",
        title: "",
        registration_number: "",
        lead_auto_assign: "1",
        phone: "",
        email: "",
        timezone: "",
        user_id: null,
      },
      user_role: {
        user_name: null,
        user_id: null,
        role_id: null,
      },
      user_errors: [],
      errors: [],
    };
  },
  components: {
    "v-select": vSelect,
  },
  computed: {
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role, [
        "Super Owner",
        "Owner",
      ]);
    },
    tenantApp() {
      return this.application == "tenant";
    },
    getTimeZones() {
      return moment.tz.names();
    },
  },
  methods: {
    saveConfiguration() {
      const vm = this;
      this.submit_configuration = false;
      let formData = new FormData();
      for (const key in this.global_configuration) {
        formData.append(key, this.global_configuration[key]);
      }

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
            vm.$data.submit_configuration = true;
          }, 500);
        })
        .catch((error) => {
          let error_message = "There were some errors in saving settings.";
          toast.fail(error_message);
          toast.close();

          setTimeout(() => {
            vm.$data.submit_configuration = true;
          }, 500);
        });
    },
    async getModuleConfiguration(module = "global") {
      let request_url =
        window.location.origin + "/api/business/configurations/" + module;

      await axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          for (const key in data) {
            if (data.hasOwnProperty(key)) {
              const element = data[key];
              this.global_configuration.hasOwnProperty(element.key)
                ? (this.global_configuration[element.key] = element.value)
                : null;
            }
          }
        })
        .catch((e) => {
          console.log(e);
        });
    },
    resetForm() {
      this.form.email =
        this.form.password =
        this.form.confirm_password =
        this.form.name =
        this.form.title =
        this.form.registration_number =
        this.form.communication_phone =
        this.form.communication_email =
        this.form.timezone =
          "";
      this.form.role_id = "3";
      this.form.lead_auto_assign = "1";
    },
    resetUserEditForm() {
      this.user.name =
        this.user.title =
        this.user.registration_number =
        this.user.phone =
        this.user.email =
        this.user.timezone =
          "";
      this.user.user_id = null;
      this.user_errors = [];
      this.user.lead_auto_assign = "1";
    },
    async getCurrentUsersOfSystem() {
      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/user/list`,
      })
        .then(({ data }) => {
          this.users = data.users;
        })
        .catch(() => {});
    },
    setUserActiveStatus(user_id, status) {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          `/api/business/user/update/${user_id}/status/${status}`,
      })
        .then(({ data }) => {
          this.getCurrentUsersOfSystem();
        })
        .catch(() => {});
    },
    async getCurrentUserRoles() {
      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/user/roles`,
      })
        .then(({ data }) => {
          this.roles = data.roles;
        })
        .catch(() => {});
    },
    async save() {
      const vm = this;
      this.submit_button = false;
      let formData = new FormData();
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key)) {
          formData.append(key, this.form[key]);
        }
      }

      let message = "Creating a new user ...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "post",
        url: window.location.origin + "/api/business/user/create",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then((response) => {
          let success_message = "New user created successfully.";
          toast.success(success_message);
          toast.close();

          vm.resetForm();
          vm.$data.submit_button = true;
          vm.getCurrentUsersOfSystem();
        })
        .catch(function (error) {
          vm.$data.errors = error.response.data;

          let error_message = "There were some errors while creating new user.";
          toast.fail(error_message);
          toast.close();
          vm.$data.submit_button = true;
        });
    },
    async updateUser() {
      const vm = this;
      this.submit_button = false;
      let formData = new FormData();
      for (const key in this.user) {
        if (this.user.hasOwnProperty(key)) {
          formData.append(key, this.user[key]);
        }
      }

      const user_id = this.user.user_id;

      let message = "Updating the user profile ...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "post",
        url: window.location.origin + `/api/business/user/update/${user_id}`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();

          vm.resetUserEditForm();
          vm.$data.submit_button = true;
          vm.getCurrentUsersOfSystem();
          $("#userEditModal").modal("hide");
        })
        .catch(function (error) {
          vm.$data.user_errors = error.response.data;

          let error_message = "There were some errors while updating user.";
          toast.fail(error_message);
          toast.close();
          vm.$data.submit_button = true;
        });
    },
    async openEditUser(user_id) {
      const vm = this;
      await axios({
        method: "get",
        url: window.location.origin + `/api/business/user/view/${user_id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          for (const key in data) {
            data = data[key];
          }
          console.log(data);

          for (const key in this.user) {
            console.log(key);
            if (this.user.hasOwnProperty(key) && data.hasOwnProperty(key)) {
              this.user[key] = data[key];
            }
          }

          $("#userEditModal").modal("show");
        })
        .catch((error) => {});
    },
    async openUserRole(user_id, current_role, name) {
      const role_id =
        this.roles.filter((role) => role.name === current_role)[0].id || 3;
      console.log(role_id);
      this.user_role.user_id = user_id;
      this.user_role.role_id = role_id;
      this.user_role.name = name;
      $("#userRoleModal").modal("show");
    },
    async updateUserRole() {
      const vm = this;
      this.submit_button = false;
      let formData = new FormData();
      for (const key in this.user_role) {
        if (this.user_role.hasOwnProperty(key)) {
          formData.append(key, this.user_role[key]);
        }
      }

      const user_id = this.user_role.user_id;

      let message = "Updating the user role ...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "post",
        url:
          window.location.origin + `/api/business/user/role/update/${user_id}`,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();

          this.user_role.role_id = null;
          this.user_role.user_id = null;
          this.user_role.name = null;

          vm.$data.submit_button = true;
          vm.getCurrentUsersOfSystem();
          $("#userRoleModal").modal("hide");
        })
        .catch(function (error) {
          let error_message = "There were some errors while updating user.";
          toast.fail(error_message);
          toast.close();
          vm.$data.submit_button = true;
        });
    },
    async warningForDeletingTheUser(user_id, user_name) {
      const vm = this;
      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Back",
        confirmButtonText: "I Confirm",
        toast: false,
        position: "center",
        text: `Please confirm if you would like to remove access to ${user_name}. This will unassign any deals or leads.`,
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.deleteAUser(user_id);
        }
      });
    },
    async warningForStatusChangeForTheUser(user_id, user_name, status) {
      let message =
        status == 0
          ? `Please confirm if you would like to block access for ${user_name} for all the data.You can unblock the user later if needed.`
          : `Please confirm if you would like to unblock ${user_name} and restore the access.`;
      const vm = this;
      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Back",
        confirmButtonText: "I Confirm",
        toast: false,
        position: "center",
        text: message,
      }).then((result) => {
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.setUserActiveStatus(user_id, status);
        }
      });
    },
    async deleteAUser(user_id) {
      let message = "Deleting the user ...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/user/delete/${user_id}`,
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
          this.getCurrentUsersOfSystem();
        })
        .catch(() => {
          let error_message = "User has been deleted.";
          toast.fail(error_message);
          toast.close();
        });
    },
    userAssignFlag(flag) {
      return parseInt(flag) === 1 ? "ON" : "OFF";
    },
  },
  async created() {
    await this.getCurrentUsersOfSystem();
    await this.getCurrentUserRoles();
  },
  async mounted() {
    const vm = this;
    this.submit_configuration = false;
    await this.getModuleConfiguration();
    this.submit_configuration = true;

    jQuery("#userCreateModal").on("hidden.bs.modal", function () {
      vm.resetForm();
    });

    jQuery("#userEditModal").on("hidden.bs.modal", function () {
      vm.resetUserEditForm();
    });

    jQuery("#userRoleModal").on("hidden.bs.modal", function () {
      vm.user_role.role_id = vm.user_role.user_id = vm.user_role.name = null;
    });
  },
};
</script>
<style lang="scss" scoped>
#user-management-table {
  tr:hover {
    background: #eee;
  }
}
</style>
