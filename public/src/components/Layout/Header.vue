<template>
  <article>
    <nav
      class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top"
    >
      <div class="navbar-wrapper">
        <div class="navbar-container content">
          <div class="navbar-collapse" id="navbar-mobile">
            <div
              class="mr-auto float-left bookmark-wrapper d-flex align-items-center"
            >
              <ul class="nav navbar-nav">
                <li class="nav-item mobile-menu d-xl-none mr-auto">
                  <a
                    class="nav-link nav-menu-main menu-toggle hidden-xs"
                    id="hidden-main-menu-button"
                    href="#"
                    style="padding-top: 0.5em"
                    ><i class="ficon bx bx-menu"></i
                  ></a>
                </li>
              </ul>
              <ul class="nav navbar-nav bookmark-icons">
                <router-link
                  tag="li"
                  :to="{ name: 'calendar' }"
                  class="nav-item d-none d-lg-block mr-1"
                  data-toggle="tooltip"
                  data-placement="top"
                  title="Calendar"
                  style="position: relative"
                  exact
                >
                  <i class="ficon bx bx-calendar-alt"></i>
                  <span
                    v-if="getTodayCount != 0"
                    class="badge badge-pill badge-danger badge-up"
                    style="top: -5px; right: -7px"
                    >{{ getTodayCount }}</span
                  >
                </router-link>

                <router-link
                  tag="li"
                  :to="{ name: 'sms' }"
                  class="nav-item d-none d-lg-block"
                  data-toggle="tooltip"
                  data-placement="top"
                  title="SMS"
                  style="position: relative; cursor: pointer"
                  exact
                >
                  <i class="ficon bx bxs-message"></i>
                  <span
                    v-if="unReadSMS != 0"
                    class="badge badge-pill badge-danger badge-up"
                    style="top: -5px; right: -7px"
                    >{{ unReadSMS }}</span
                  >
                </router-link>

                <li class="nav-item d-none d-lg-block ml-1">
                  <a
                    type="button"
                    data-toggle="modal"
                    data-target="#universalCalculator"
                  >
                    <i class="ficon bx bxs-calculator"></i>
                  </a>
                </li>

                <router-link
                  tag="li"
                  :to="{ name: 'voucher-list' }"
                  class="nav-item d-none d-lg-block ml-1"
                  exact
                >
                  <i class="ficon bx bxs-coupon"></i>
                </router-link>
              </ul>
            </div>
            <ul class="nav navbar-nav float-right">
              <li
                class="nav-item d-none d-md-inline-block d-lg-inline-block"
                v-if="
                  form.show_pastduenotification == '1' &&
                    zlist.invoice.length > 0 &&
                    (app_metadata.role == 'Super Owner' ||
                      app_metadata.role == 'Owner')
                "
              >
                <a
                  class="dropdown-item text-danger"
                  type="button"
                  data-toggle="modal"
                  data-target="#overDueInvoiceModal"
                  ><i
                    class="bx bxs-credit-card mr-50"
                    style="vertical-align: middle"
                  ></i>
                  <span class="">OVERDUE INVOICE(S)</span>
                </a>
              </li>
              <li class="nav-item d-none d-md-inline-block d-lg-inline-block">
                <router-link
                    tag="a"
                    class="dropdown-item btn-success"
                    type="button"
                    :to="{ name: 'download-center' }"
                    exact
                ><i
                    class="bx bxs-download mr-50"
                    style="vertical-align: middle"
                ></i>
                  <span class="">DOWNLOADS</span>
                </router-link>
              </li>
              <li class="nav-item d-none d-md-inline-block d-lg-inline-block">
                <a
                  class="dropdown-item"
                  type="button"
                  data-toggle="modal"
                  data-target="#helpCenter"
                  href="https://help.zopmedia.com/portal/en/home"
                  ><i
                    class="bx bx-support mr-50"
                    style="vertical-align: middle"
                  ></i>
                  <span class="">CUSTOMER SUPPORT</span>
                </a>
              </li>
              <li class="nav-item d-none d-md-inline-block d-lg-inline-block">
                <a
                  class="dropdown-item"
                  type="button"
                  data-toggle="modal"
                  data-target="#referAndSave"
                  ><i
                    class="bx bxs-gift mr-50"
                    style="vertical-align: middle"
                  ></i>
                  <span class="">REFER & SAVE</span>
                </a>
              </li>
              <li class="nav-item d-none">
                <a class="dropdown-item"
                  ><i class="bx bxs-data" style="vertical-align: middle"></i>
                  <span class=""> {{ getAvailableSMSCredits }} </span></a
                >
              </li>

              <li class="nav-item d-none d-md-inline-block d-lg-inline-block">
                <a
                  class="dropdown-item"
                  :href="form.business_website"
                  target="_blank"
                  ><i
                    class="bx bx-link-external mr-50"
                    style="vertical-align: middle"
                  ></i>
                  <span class="">VISIT WEBSITE </span></a
                >
              </li>

              <li class="dropdown nav-item">
                <a
                  class="dropdown-toggle nav-link"
                  id="dropdown-user"
                  href="#"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  style="padding: 0.8rem 1.6rem"
                >
                  <i
                    class="bx bxs-user-rectangle mr-50"
                    style="vertical-align: middle"
                  ></i
                  >{{ global.user_metadata.name.toLocaleUpperCase() }}
                </a>
                <div
                  class="mt-0 dropdown-menu dropdown-menu-right pb-0"
                  aria-labelledby="dropdown-user"
                >
                  <a class="dropdown-item" @click="doCopy(rooftop_id)"
                    ><i
                      class="bx bx-hash mr-50"
                      style="vertical-align: middle"
                    ></i>
                    {{ rooftop_id }}</a
                  >
                  <a
                    v-show="sms.sms_phone_number != null"
                    class="dropdown-item"
                    @click="copyPhoneNumber"
                    ><i
                      class="bx bxs-phone mr-50"
                      style="vertical-align: middle"
                    ></i>
                    {{ formatNumber }}</a
                  >

                  <router-link
                    tag="a"
                    v-if="
                      form.show_billing == '1' &&
                        (app_metadata.role == 'Super Owner' ||
                          app_metadata.role == 'Owner')
                    "
                    :to="{ name: 'invoice-list' }"
                    class="dropdown-item"
                    exact
                  >
                    <i
                      class="bx bx-spreadsheet mr-50"
                      style="vertical-align: middle"
                    ></i>
                    Billing
                  </router-link>

                  <router-link
                    tag="a"
                    v-if="tenantApp && isAuthorizedForUserManagement"
                    :to="{ name: 'user-management' }"
                    class="dropdown-item"
                    exact
                  >
                    <i
                      class="bx bx-group mr-50"
                      style="vertical-align: middle"
                    ></i>
                    Manage Team
                  </router-link>

                  <router-link
                    tag="a"
                    v-if="isAuthorized"
                    :to="{ name: 'settings-account-v2' }"
                    class="dropdown-item"
                    exact
                  >
                    <i
                      class="bx bxs-cog mr-50"
                      style="vertical-align: middle"
                    ></i>
                    Account Settings
                  </router-link>

                  <router-link
                    v-if="tenantApp && app_metadata.organization != 'zopdealer'"
                    tag="a"
                    :to="{ name: 'settings-user-account' }"
                    class="dropdown-item"
                    exact
                  >
                    <i
                      class="bx bxs-user-detail mr-50"
                      style="vertical-align: middle"
                    ></i>
                    User Settings
                  </router-link>

                  <a class="dropdown-item" href="api/admin/logout"
                    ><i
                      class="bx bx-power-off mr-50"
                      style="vertical-align: middle"
                    ></i>
                    Logout</a
                  >
                </div>
              </li>
              <li class="nav-item d-lg-block"></li>
            </ul>
          </div>
        </div>
        <div
          class="alert alert-danger"
          v-show="show_delete_notification"
          role="alert"
        >
          Your subscription has ended. Your data will be deleted in the next few
          days. Please contact our support team if you would like to continue
          with the subscription.
        </div>
      </div>
    </nav>
    <!-- Help Center : START  -->
    <div
      class="modal fade"
      id="helpCenter"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Help Center"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <!-- Modal Header : START -->
          <div class="modal-header">
            <h5 class="modal-title" id="helpCenterHeader">Support</h5>
            <button
              type="button"
              id="helpCenterClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              @click="show_contact_support_form = false"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- Modal Header : END -->
          <!-- Modal Body : START -->
          <div class="modal-body">
            <form>
              <div class="form-row">
                <h6
                  class="form-group col-md-12 font-italic"
                  v-if="!show_contact_support_form"
                >
                  The Zop Help Center will support you as you learn about and
                  use Zop Software. We have documentation and videos to answer
                  your questions. <br /><br />Can’t find the answers you’re
                  looking for?<br /><br />
                  Please submit a support ticket & our support agent will be in
                  touch with in one business day.
                </h6>
                <div
                  class="form-group col-md-12"
                  v-if="!show_contact_support_form"
                >
                  <div class="row">
                    <div class="col-md-6">
                      <div
                        class="card-body text-center p-50"
                        @click="show_contact_support_form = true"
                        style="border: 1px solid #777; border-radius: 1px"
                      >
                        <div class="avatar avatar-md mx-auto mb-1">
                          <span
                            class="avatar-initial rounded-circle bg-danger p-1"
                            ><i class="bx bx-support fs-3"></i
                          ></span>
                        </div>
                        <span
                          style="
                            font-size: 0.8em;
                            font-weight: bold;
                            letter-spacing: 1px;
                          "
                          class="d-block text-uppercase mb-1 text-nowrap"
                          >CONTACT SUPPORT</span
                        >
                      </div>
                    </div>
                    <div class="col-md-6">
                      <a
                        href="https://help.zopmedia.com/portal/en/home"
                        target="_blank"
                        rel="noopener noreferrer"
                      >
                        <div
                          class="card-body text-center p-50"
                          style="border: 1px solid #777; border-radius: 1px"
                        >
                          <div class="avatar avatar-md mx-auto mb-1">
                            <span
                              class="avatar-initial rounded-circle bg-primary p-1"
                              ><i class="bx bx-support fs-3"></i
                            ></span>
                          </div>
                          <span
                            style="
                              font-size: 0.8em;
                              font-weight: bold;
                              letter-spacing: 1px;
                            "
                            class="d-block text-uppercase mb-1 text-nowrap"
                            >KNOWLEDGE BASE</span
                          >
                        </div>
                      </a>
                    </div>
                  </div>
                </div>
                <template v-if="show_contact_support_form">
                  <!-- Name : START -->
                  <div class="form-group col-md-12">
                    <label for="help_center_name">Full Name: </label>
                    <input
                      type="text"
                      class="form-control"
                      :class="{
                        'is-invalid': help_center_errors.hasOwnProperty('name'),
                      }"
                      @keyup="delete help_center_errors.name"
                      :title="help_center_errors.name"
                      v-model="help_center_form.name"
                      id="help_center_name"
                      placeholder="Name"
                    />
                  </div>
                  <!-- Name : END -->
                  <!-- Email : START -->
                  <div class="form-group col-md-12">
                    <label for="help_center_email">Email: </label>
                    <input
                      type="text"
                      class="form-control"
                      :class="{
                        'is-invalid': help_center_errors.hasOwnProperty(
                          'email',
                        ),
                      }"
                      @keyup="delete help_center_errors.email"
                      :title="help_center_errors.email"
                      v-model="help_center_form.email"
                      id="help_center_email"
                      placeholder="Email"
                    />
                  </div>
                  <!-- Email : END -->
                  <!-- Phone : START -->
                  <div class="form-group col-md-12">
                    <label for="help_center_phone">Phone: </label>
                    <input
                      type="text"
                      class="form-control"
                      :class="{
                        'is-invalid': help_center_errors.hasOwnProperty(
                          'phone',
                        ),
                      }"
                      @keyup="delete help_center_errors.phone"
                      :title="help_center_errors.phone"
                      v-model="help_center_form.phone"
                      id="help_center_phone"
                      placeholder="Phone"
                    />
                  </div>
                  <!-- Phone : END -->
                  <!-- Subject : START -->
                  <div class="form-group col-md-12">
                    <label for="help_center_subject">Subject: </label>
                    <input
                      type="text"
                      class="form-control"
                      :class="{
                        'is-invalid': help_center_errors.hasOwnProperty(
                          'subject',
                        ),
                      }"
                      @keyup="delete help_center_errors.subject"
                      :title="help_center_errors.subject"
                      v-model="help_center_form.subject"
                      id="help_center_subject"
                      placeholder="Subject"
                    />
                  </div>
                  <!-- Subject : END -->
                  <!-- Description : START -->
                  <div class="form-group col-md-12">
                    <label for="help_center_description">Description: </label>
                    <textarea
                      type="text"
                      class="form-control"
                      :class="{
                        'is-invalid': help_center_errors.hasOwnProperty(
                          'description',
                        ),
                      }"
                      @keyup="delete help_center_errors.description"
                      :title="help_center_errors.description"
                      v-model="help_center_form.description"
                      id="help_center_description"
                      placeholder="Please provide more details"
                    ></textarea>
                  </div>
                  <!-- Description : END -->
                </template>
              </div>
            </form>
          </div>
          <!-- Modal Body : END -->
          <!-- Modal Footer : START -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              @click="show_contact_support_form = false"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              type="button"
              v-if="show_contact_support_form"
              class="btn btn-primary"
              @click.prevent="helpCenter"
              :disabled="
                help_center_form.email == '' ||
                  help_center_form.description == ''
              "
            >
              Submit
            </button>
          </div>
          <!-- Modal Footer : END -->
        </div>
      </div>
    </div>
    <!-- Help Center : END  -->

    <!-- Refer & Save : START  -->
    <div
      class="modal fade"
      id="referAndSave"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Refer & Save"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <!-- Modal Header : START -->
          <div class="modal-header">
            <h5 class="modal-title" id="referAndSaveHeader">Refer & Save</h5>
            <button
              type="button"
              id="referAndSaveClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- Modal Header : END -->
          <!-- Modal Body : START -->
          <div class="modal-body">
            <form>
              <div class="form-row">
                <h6 class="form-group col-md-12 font-italic">
                  Tell your friends about zop and receive $10 off your monthly
                  bill for each referral that signs up.
                </h6>
                <!-- Name : START -->
                <div class="form-group col-md-12">
                  <label for="refer_and_save_full_name"
                    >Contact Full Name:
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': refer_and_save_errors.hasOwnProperty(
                        'full_name',
                      ),
                    }"
                    @keyup="delete refer_and_save_errors.full_name"
                    :title="refer_and_save_errors.full_name"
                    v-model="refer_and_save_form.full_name"
                    id="refer_and_save_full_name"
                    placeholder="Name"
                  />
                </div>
                <!-- Name : END -->
                <!-- Email : START -->
                <div class="form-group col-md-12">
                  <label for="refer_and_save_email">Email: </label>
                  <input
                    type="email"
                    class="form-control"
                    :class="{
                      'is-invalid': refer_and_save_errors.hasOwnProperty(
                        'email',
                      ),
                    }"
                    @keyup="delete refer_and_save_errors.email"
                    :title="refer_and_save_errors.email"
                    v-model="refer_and_save_form.email"
                    id="refer_and_save_email"
                    placeholder="Email"
                  />
                </div>
                <!-- Email : END -->
                <!-- Phone : START -->
                <div class="form-group col-md-12">
                  <label for="refer_and_save_phone">Phone: </label>
                  <input
                    type="tel"
                    class="form-control"
                    :class="{
                      'is-invalid': refer_and_save_errors.hasOwnProperty(
                        'phone',
                      ),
                    }"
                    @keyup="delete refer_and_save_errors.phone"
                    :title="refer_and_save_errors.phone"
                    v-model="refer_and_save_form.phone"
                    id="refer_and_save_phone"
                    placeholder="Phone"
                  />
                </div>
                <!-- Phone : END -->
                <!-- Name : START -->
                <div class="form-group col-md-12">
                  <label for="refer_and_save_dealership_name"
                    >Dealership Name:
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': refer_and_save_errors.hasOwnProperty(
                        'dealership_name',
                      ),
                    }"
                    @keyup="delete refer_and_save_errors.dealership_name"
                    :title="refer_and_save_errors.dealership_name"
                    v-model="refer_and_save_form.dealership_name"
                    id="refer_and_save_dealership_name"
                    placeholder="Dealership Name"
                  />
                </div>
                <!-- Name : END -->
              </div>
            </form>
          </div>
          <!-- Modal Body : END -->
          <!-- Modal Footer : START -->
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
              @click.prevent="referAndSave"
              :disabled="
                refer_and_save_form.email == '' ||
                  refer_and_save_form.dealership_name == '' ||
                  refer_and_save_form.full_name == ''
              "
            >
              Refer
            </button>
          </div>
          <!-- Modal Footer : END -->
        </div>
      </div>
    </div>
    <!-- Refer & Save : END  -->

    <!-- Request Carfax Integration : START  -->
    <div
      class="modal fade"
      id="carfaxRequestModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Request Carfax Integration"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <!-- Modal Header : START -->
          <div class="modal-header">
            <h5 class="modal-title" id="carfaxRequestModalHeader">
              Request Carfax Integration
            </h5>
            <button
              type="button"
              id="carfaxRequestModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- Modal Header : END -->
          <!-- Modal Body : START -->
          <div class="modal-body">
            <form>
              <div class="form-row">
                <!-- Logos Row -->
                <div class="text-center w-100 my-50">
                  <img
                    src="https://zopsoftware.com/dist/app-assets/images/logo/zop-logo.png"
                    alt="Zop"
                    style="height: 40px"
                  />

                  <div class="integration-connector mx-1 d-inline">
                    <span class="connector-line"></span>
                    <span class="connector-icon">&#8596;</span>
                    <span class="connector-line"></span>
                  </div>

                  <img
                    src="https://zopsoftware-asset.b-cdn.net/phaeton/themes/theme-1/images/CFC-Logo_small.png"
                    alt="Carfax Canada"
                    style="height: 40px"
                  />
                </div>
                <h6 class="form-group col-md-12 font-italic">
                  Get started with the integration by filling out the form
                  below.
                </h6>
                <!-- Name : START -->
                <div class="form-group col-md-12">
                  <label for="carfax_integration_request_name">Name: </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': carfax_integration_request_errors.hasOwnProperty(
                        'name',
                      ),
                    }"
                    @keyup="delete carfax_integration_request_errors.name"
                    :title="carfax_integration_request_errors.name"
                    v-model="carfax_integration_request_form.name"
                    id="carfax_integration_request_name"
                    placeholder="Name"
                  />
                </div>
                <!-- Name : END -->
                <!-- Email : START -->
                <div class="form-group col-md-12">
                  <label for="carfax_integration_request_email">Email: </label>
                  <input
                    type="email"
                    class="form-control"
                    :class="{
                      'is-invalid': carfax_integration_request_errors.hasOwnProperty(
                        'email',
                      ),
                    }"
                    @keyup="delete carfax_integration_request_errors.email"
                    :title="carfax_integration_request_errors.email"
                    v-model="carfax_integration_request_form.email"
                    id="carfax_integration_request_email"
                    placeholder="Email"
                  />
                </div>
                <!-- Email : END -->
                <!-- Phone : START -->
                <div class="form-group col-md-12">
                  <label for="carfax_integration_request_phone">Phone: </label>
                  <input
                    type="tel"
                    class="form-control"
                    :class="{
                      'is-invalid': carfax_integration_request_errors.hasOwnProperty(
                        'phone',
                      ),
                    }"
                    @keyup="delete carfax_integration_request_errors.phone"
                    :title="carfax_integration_request_errors.phone"
                    v-model="carfax_integration_request_form.phone"
                    id="carfax_integration_request_phone"
                    placeholder="Phone"
                  />
                </div>
                <!-- Phone : END -->
                <!-- Name : START -->
                <div class="form-group col-md-12">
                  <label for="carfax_integration_request_carfax_dealer_id"
                    >Carfax Dealer Id:
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': carfax_integration_request_errors.hasOwnProperty(
                        'carfax_dealer_id',
                      ),
                    }"
                    @keyup="
                      delete carfax_integration_request_errors.carfax_dealer_id
                    "
                    :title="carfax_integration_request_errors.carfax_dealer_id"
                    v-model="carfax_integration_request_form.carfax_dealer_id"
                    id="carfax_integration_request_carfax_dealer_id"
                    placeholder="Carfax Dealer Id"
                  />
                </div>
                <!-- Name : END -->
              </div>
            </form>
          </div>
          <!-- Modal Body : END -->
          <!-- Modal Footer : START -->
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
              @click.prevent="requestCarfaxIntegration"
              :disabled="
                carfax_integration_request_form.carfax_dealer_id == '' ||
                  carfax_integration_request_form.name == '' ||
                  carfax_integration_request_form.email == '' ||
                  carfax_integration_request_form.phone == ''
              "
            >
              Send Request
            </button>
          </div>
          <!-- Modal Footer : END -->
        </div>
      </div>
    </div>
    <!-- Request Carfax Integration : END  -->

    <!-- Overdue Invoices : START  -->
    <div
      class="modal fade"
      id="overDueInvoiceModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Refer & Save"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <!-- Modal Header : START -->
          <div class="modal-header">
            <h5 class="modal-title" id="overDueInvoiceModalHeader">
              Overdue Invoice(s)
            </h5>
            <button
              type="button"
              id="overDueInvoiceModalClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- Modal Header : END -->
          <!-- Modal Body : START -->
          <div class="modal-body">
            <form>
              <div class="form-row">
                <h6 class="form-group col-md-12 font-italic">
                  Below are your outstanding invoices. Please remit payment at
                  your earliest convenience. Feel free to call accounting at
                  416-840-6606 ext. 2
                </h6>
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>DATE</th>
                      <th>INVOICE ID</th>
                      <th>AMOUNT</th>
                      <th>ACTION</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="invoice_history in zlist.invoice"
                      :key="invoice_history.invoice_id"
                    >
                      <td>{{ invoice_history.invoice_date }}</td>
                      <td>{{ invoice_history.invoice_no }}</td>
                      <td>{{ invoice_history.amount }}</td>
                      <td>
                        <button
                          class="btn btn-success"
                          @click="
                            downloadZinvoice(invoice_history.invoice_fullid)
                          "
                        >
                          Pay Now
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </form>
          </div>
          <!-- Modal Body : END -->
          <!-- Modal Footer : START -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
          </div>
          <!-- Modal Footer : END -->
        </div>
      </div>
    </div>
    <!-- Overdue Invoices : END  -->

    <!-- Cancel Subscription : START -->
    <div
      class="modal fade"
      id="cancelSubscription"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Cancel Subscription"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <!-- Modal Header : START -->
          <div class="modal-header">
            <h5 class="modal-title" id="cancelSubscriptionHeader">
              Cancel Subscription
            </h5>
            <button
              type="button"
              id="cancelSubscriptionClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- Modal Header : END -->
          <!-- Modal Body : START -->
          <div class="modal-body">
            <form>
              <div class="form-row" v-if="!show_cancel_subscription_form">
                <h4>Do you really want to end your subscription?</h4>
                <h5 class="text-danger">
                  You will no longer have access to the account after the
                  request is received, and the data will be removed permanently.
                </h5>
                <div class="form-group col-md-12">
                  <div class="row">
                    <button
                      class="btn btn-light-danger col-md-5"
                      @click="show_cancel_subscription_form = true"
                    >
                      Yes, delete my data
                    </button>
                    <div class="col-md-2"></div>
                    <button
                      data-dismiss="modal"
                      class="btn btn-secondary col-md-5"
                    >
                      Go Back
                    </button>
                  </div>
                </div>
              </div>
              <div class="form-row" v-else>
                <!-- Description : START -->
                <div class="form-group col-md-12">
                  <label for="cancel_subscription_feedback">Feedback: </label>
                  <textarea
                    type="text"
                    class="form-control"
                    :class="{
                      'is-invalid': cancel_subscription_errors.hasOwnProperty(
                        'feedback',
                      ),
                    }"
                    @keyup="delete cancel_subscription_errors.feedback"
                    :title="cancel_subscription_errors.feedback"
                    v-model="cancel_subscription_form.feedback"
                    id="cancel_subscription_feedback"
                    placeholder="Tell Us Where We Went Wrong and How we can improve it."
                  ></textarea>
                </div>
                <!-- Description : END -->
              </div>
            </form>
          </div>
          <!-- Modal Body : END -->
          <!-- Modal Footer : START -->
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
            <button
              v-show="show_cancel_subscription_form"
              type="button"
              class="btn btn-primary"
              @click.prevent="closeAccountRequest"
            >
              Submit
            </button>
          </div>
          <!-- Modal Footer : END -->
        </div>
      </div>
    </div>
    <!-- Cancel Subscription : END -->

    <!-- Calculator : START -->
    <div
      class="modal fade"
      id="universalCalculator"
      tabindex="-1"
      role="dialog"
      aria-labelledby="Universal Payment Calculator"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <!-- Modal Header : START -->
          <div class="modal-header">
            <h5 class="modal-title" id="universalCalculatorHeader">
              Payment Calculator
            </h5>
            <button
              type="button"
              id="universalCalculatorClose"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <!-- Modal Header : END -->

          <!-- Modal Body : START -->
          <div class="modal-body row">
            <form class="col-sm-12 col-md-8">
              <!-- Form Group : START -->
              <div class="form-group row">
                <label
                  for="universalCalculatorPrice"
                  class="col-md-3 col-sm-12 col-form-label"
                  >Vehicle Price</label
                >
                <vue-slider
                  :height="12"
                  :max="200000"
                  style="padding-top: 1em"
                  class="col-md-6 col-sm-12"
                  :tooltip="'always'"
                  :tooltip-formatter="amountFormatter"
                  v-model="universal_calculator.vehicle_price"
                  v-debounce:1000ms="calculateFinancing"
                />
                <div class="col-md-3 col-sm-12">
                  <input
                    type="text"
                    class="form-control"
                    id="universalCalculatorPrice"
                    placeholder="Price"
                    v-model="universal_calculator.vehicle_price"
                    v-debounce:1000ms="calculateFinancing"
                  />
                </div>
              </div>
              <!-- Form Group : END -->

              <!-- Form Group : START -->
              <div class="form-group row">
                <label
                  for="universalCalculatorAdditionalFees"
                  class="col-md-3 col-sm-12 col-form-label"
                  >Additional Fees</label
                >
                <vue-slider
                  :height="12"
                  :max="200000"
                  style="padding-top: 1em"
                  class="col-md-6 col-sm-12"
                  :tooltip="'always'"
                  :tooltip-formatter="amountFormatter"
                  v-model="universal_calculator.additional_fees"
                  v-debounce:1000ms="calculateFinancing"
                />
                <div class="col-md-3 col-sm-12">
                  <input
                    type="text"
                    class="form-control"
                    id="universalCalculatorAdditionalFees"
                    placeholder="Additional Fees"
                    v-model="universal_calculator.additional_fees"
                    v-debounce:1000ms="calculateFinancing"
                  />
                </div>
              </div>
              <!-- Form Group : END -->

              <!-- Form Group : START -->
              <div class="form-group row">
                <label
                  for="universalCalculatorTradeInValue"
                  class="col-md-3 col-sm-12 col-form-label"
                  >Trade-in Value</label
                >
                <vue-slider
                  :height="12"
                  :max="200000"
                  style="padding-top: 1em"
                  class="col-md-6 col-sm-12"
                  :tooltip="'always'"
                  :tooltip-formatter="amountFormatter"
                  v-model="universal_calculator.trade_in_value"
                  v-debounce:1000ms="calculateFinancing"
                />
                <div class="col-md-3 col-sm-12">
                  <input
                    type="text"
                    class="form-control"
                    id="universalCalculatorTradeInValue"
                    placeholder="Trade-in Value"
                    v-model="universal_calculator.trade_in_value"
                    v-debounce:1000ms="calculateFinancing"
                  />
                </div>
              </div>
              <!-- Form Group : END -->

              <!-- Form Group : START -->
              <div class="form-group row">
                <label
                  for="universalCalculatorLienValue"
                  class="col-md-3 col-sm-12 col-form-label"
                  >Lien Value</label
                >
                <vue-slider
                  :height="12"
                  :max="200000"
                  style="padding-top: 1em"
                  class="col-md-6 col-sm-12"
                  :tooltip="'always'"
                  :tooltip-formatter="amountFormatter"
                  v-model="universal_calculator.lien_value"
                  v-debounce:1000ms="calculateFinancing"
                />
                <div class="col-md-3 col-sm-12">
                  <input
                    type="text"
                    class="form-control"
                    id="universalCalculatorLienValue"
                    placeholder="Lien Value"
                    v-model="universal_calculator.lien_value"
                    v-debounce:1000ms="calculateFinancing"
                  />
                </div>
              </div>
              <!-- Form Group : END -->

              <!-- Form Group : START -->
              <div class="form-group row">
                <label
                  for="universalCalculatorDownPayment"
                  class="col-md-3 col-sm-12 col-form-label"
                  >Downpayment</label
                >
                <vue-slider
                  :height="12"
                  :max="200000"
                  style="padding-top: 1em"
                  class="col-md-6 col-sm-12"
                  :tooltip="'always'"
                  :tooltip-formatter="amountFormatter"
                  v-model="universal_calculator.downpayment"
                  v-debounce:1000ms="calculateFinancing"
                />
                <div class="col-md-3 col-sm-12">
                  <input
                    type="text"
                    class="form-control"
                    id="universalCalculatorDownPayment"
                    placeholder="Downpayment"
                    v-model="universal_calculator.downpayment"
                    v-debounce:1000ms="calculateFinancing"
                  />
                </div>
              </div>
              <!-- Form Group : END -->

              <!-- Form Group : START -->
              <div class="form-group row">
                <label
                  for="universalCalculatorDuration"
                  class="col-md-3 col-sm-12 col-form-label"
                  >Duration</label
                >
                <vue-slider
                  :height="12"
                  :max="96"
                  style="padding-top: 1em"
                  class="col-md-6 col-sm-12"
                  :tooltip="'always'"
                  :tooltip-formatter="'{value} Months'"
                  v-model="universal_calculator.duration"
                  v-debounce:1000ms="calculateFinancing"
                />
                <div class="col-md-3 col-sm-12">
                  <input
                    type="text"
                    class="form-control"
                    id="universalCalculatorDuration"
                    placeholder="Duration"
                    v-model="universal_calculator.duration"
                    v-debounce:1000ms="calculateFinancing"
                  />
                </div>
              </div>
              <!-- Form Group : END -->
              <hr class="w-100" />

              <!-- Form Group : START -->
              <div class="form-group row">
                <label
                  for="universalCalculatorTax1"
                  class="col-md-3 col-sm-12 col-form-label"
                  >Tax 1</label
                >
                <vue-slider
                  :height="12"
                  :max="18"
                  :interval="0.01"
                  style="padding-top: 1em"
                  class="col-md-6 col-sm-12"
                  :tooltip="'always'"
                  :tooltip-formatter="'{value} %'"
                  v-model="universal_calculator.tax_1"
                  v-debounce:1000ms="calculateFinancing"
                />
                <div class="col-md-3 col-sm-12">
                  <input
                    type="text"
                    class="form-control"
                    id="universalCalculatorTax1"
                    placeholder="Tax 1"
                    v-model="universal_calculator.tax_1"
                    v-debounce:1000ms="calculateFinancing"
                  />
                </div>
              </div>
              <!-- Form Group : END -->

              <!-- Form Group : START -->
              <div class="form-group row">
                <label
                  for="universalCalculatorTax2"
                  class="col-md-3 col-sm-12 col-form-label"
                  >Tax 2</label
                >
                <vue-slider
                  :height="12"
                  :max="18"
                  :interval="0.01"
                  style="padding-top: 1em"
                  class="col-md-6 col-sm-12"
                  :tooltip="'always'"
                  :tooltip-formatter="'{value} %'"
                  v-model="universal_calculator.tax_2"
                  v-debounce:1000ms="calculateFinancing"
                />
                <div class="col-md-3 col-sm-12">
                  <input
                    type="text"
                    class="form-control"
                    id="universalCalculatorTax2"
                    placeholder="Tax 2"
                    v-model="universal_calculator.tax_2"
                    v-debounce:1000ms="calculateFinancing"
                  />
                </div>
              </div>
              <!-- Form Group : END -->

              <hr class="w-100" />
              <!-- Form Group : START -->
              <div class="form-group row">
                <label
                  for="universalCalculatorInterest"
                  class="col-md-3 col-sm-12 col-form-label"
                  >Interest Rate</label
                >
                <vue-slider
                  :height="12"
                  :interval="0.01"
                  :max="36"
                  style="padding-top: 1em"
                  class="col-md-6 col-sm-12"
                  :tooltip="'always'"
                  :tooltip-formatter="'{value} %'"
                  v-model="universal_calculator.interest_rate"
                  v-debounce:1000ms="calculateFinancing"
                />
                <div class="col-md-3 col-sm-12">
                  <input
                    type="text"
                    class="form-control"
                    id="universalCalculatorInterest"
                    placeholder="Interest Rate"
                    v-model="universal_calculator.interest_rate"
                    v-debounce:1000ms="calculateFinancing"
                  />
                </div>
              </div>
              <!-- Form Group : END -->
            </form>
            <div class="col-sm-12 col-md-4">
              <div class="row text-center">
                <div
                  class="col-12"
                  v-show="!is_calculating && this.finance.financing_amount"
                >
                  Total Financed Amount :
                </div>
                <h2
                  style="letter-spacing: 1px; font-weight: 600"
                  class="col-12"
                  v-show="!is_calculating && this.finance.financing_amount"
                >
                  {{ finance.financing_amount }}
                </h2>
                <div
                  class="col-12"
                  v-show="!is_calculating && this.finance.financing_amount"
                >
                  Total Repayment Amount :
                </div>
                <h2
                  style="letter-spacing: 1px; font-weight: 600"
                  class="col-12"
                  v-show="!is_calculating && this.finance.financing_amount"
                >
                  {{ finance.total_repayment_amount }}
                </h2>
                <div
                  class="col-12"
                  v-show="!is_calculating && this.finance.financing_amount"
                >
                  Monthly :
                </div>
                <h2
                  style="letter-spacing: 1px; font-weight: 600"
                  class="col-12"
                  v-show="!is_calculating && this.finance.financing_amount"
                >
                  {{ finance.monthly_amount }}
                </h2>
                <div
                  class="col-12"
                  v-show="!is_calculating && this.finance.financing_amount"
                >
                  Bi-Weekly :
                </div>
                <h2
                  style="letter-spacing: 1px; font-weight: 600"
                  class="col-12"
                  v-show="!is_calculating && this.finance.financing_amount"
                >
                  {{ finance.biweekly_amount }}
                </h2>
                <div
                  class="col-12"
                  v-show="!is_calculating && this.finance.financing_amount"
                >
                  Weekly :
                </div>
                <h2
                  style="letter-spacing: 1px; font-weight: 600"
                  class="col-12"
                  v-show="!is_calculating && this.finance.financing_amount"
                >
                  {{ finance.weekly_amount }}
                </h2>
                <h2 class="col-12" v-show="is_calculating">
                  <i class="bx bx-loader-circle bx-lg bx-spin"></i>
                </h2>
              </div>
            </div>
          </div>
          <!-- Modal Body : END -->

          <!-- Modal Footer : START -->
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
              @click.prevent="calculateFinancing"
              class="btn btn-primary"
            >
              Calculate
            </button>
          </div>
          <!-- Modal Footer : END -->
        </div>
      </div>
    </div>
    <!-- Calculator : END -->
  </article>
</template>

<script>
import { io } from "socket.io-client";
import Notification from "../../library/Notification";
import { mapGetters, mapActions } from "vuex";
import Toast from "../../library/Toast";
import VueSlider from "vue-slider-component";

export default {
  props: {
    application: { required: true },
    app_metadata: { required: true },
    user_metadata: { required: true },
  },
  name: "Header",
  data() {
    return {
      show_delete_notification: false,
      show_contact_support_form: false,
      show_cancel_subscription_form: false,
      rooftop_id: "",
      today_activity_count: 0,
      global: window.global,
      cancel_subscription_form: {
        feedback: "",
      },
      cancel_subscription_errors: [],
      form: {
        business_website: "",
        enable_sms_package: "0",
        available_sms_credit: "0",
        show_billing: "0",
        show_pastduenotification: "0",
        vehicle_default_term: 0,
        vehicle_default_interest_rate: 0,
        account_email_name: "",

        // zoho_pending_invoice: [],
      },
      sms: {
        unread: 0,
        available_sms_credit: "0",
        sms_phone_number: null,
        unread: 0,
      },
      help_center_form: {
        name: "",
        email: "",
        phone: "",
        subject: "",
        description: "",
      },
      carfax_integration_request_form: {
        carfax_dealer_id: "",
        name: "",
        email: "",
        phone: "",
      },
      carfax_integration_request_errors: [],
      refer_and_save_form: {
        full_name: "",
        email: "",
        phone: "",
        dealership_name: "",
      },
      refer_and_save_errors: [],
      help_center_errors: [],
      zlist: {
        invoice: [],
      },
      universal_calculator: {
        vehicle_price: 0,
        additional_fees: 0,
        trade_in_value: 0,
        lien_value: 0,
        downpayment: 0,
        duration: 0,
        tax_1: 0,
        tax_2: 0,
        interest_rate: 0,
      },
      finance: {
        monthly_amount: "",
        biweekly_amount: "",
        weekly_amount: "",
        financing_amount: "",
        total_repayment_amount: "",
      },
      amountFormatter: (v) =>
        `$${("" + v).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`,
      is_calculating: false,
    };
  },
  watch: {
    "universal_calculator.duration": function(newVal, oldVal) {
      let duration =
        !isNaN(this.universal_calculator.duration) &&
        this.universal_calculator.duration != ""
          ? parseInt(this.universal_calculator.duration, 10)
          : 0;

      duration = duration < 0 || duration > 96 ? oldVal : duration;
      this.universal_calculator.duration = duration;
    },
    "universal_calculator.vehicle_price": function(newVal, oldVal) {
      let vehicle_price =
        !isNaN(this.universal_calculator.vehicle_price) &&
        this.universal_calculator.vehicle_price != ""
          ? parseInt(this.universal_calculator.vehicle_price, 10)
          : 0;

      vehicle_price =
        vehicle_price < 0 || vehicle_price > 200000 ? oldVal : vehicle_price;
      this.universal_calculator.vehicle_price = vehicle_price;
    },
    "universal_calculator.additional_fees": function(newVal, oldVal) {
      let additional_fees =
        !isNaN(this.universal_calculator.additional_fees) &&
        this.universal_calculator.additional_fees != ""
          ? parseInt(this.universal_calculator.additional_fees, 10)
          : 0;

      additional_fees =
        additional_fees < 0 || additional_fees > 200000
          ? oldVal
          : additional_fees;
      this.universal_calculator.additional_fees = additional_fees;
    },
    "universal_calculator.trade_in_value": function(newVal, oldVal) {
      let trade_in_value =
        !isNaN(this.universal_calculator.trade_in_value) &&
        this.universal_calculator.trade_in_value != ""
          ? parseInt(this.universal_calculator.trade_in_value, 10)
          : 0;

      trade_in_value =
        trade_in_value < 0 || trade_in_value > 200000 ? oldVal : trade_in_value;
      this.universal_calculator.trade_in_value = trade_in_value;
    },
    "universal_calculator.lien_value": function(newVal, oldVal) {
      let lien_value =
        !isNaN(this.universal_calculator.lien_value) &&
        this.universal_calculator.lien_value != ""
          ? parseInt(this.universal_calculator.lien_value, 10)
          : 0;

      lien_value = lien_value < 0 || lien_value > 200000 ? oldVal : lien_value;
      this.universal_calculator.lien_value = lien_value;
    },
    "universal_calculator.downpayment": function(newVal, oldVal) {
      let downpayment =
        !isNaN(this.universal_calculator.downpayment) &&
        this.universal_calculator.downpayment != ""
          ? parseInt(this.universal_calculator.downpayment, 10)
          : 0;

      downpayment =
        downpayment < 0 || downpayment > 200000 ? oldVal : downpayment;
      this.universal_calculator.downpayment = downpayment;
    },
    "universal_calculator.tax_1": function(newVal, oldVal) {
      let tax_1 =
        !isNaN(this.universal_calculator.tax_1) &&
        this.universal_calculator.tax_1 != ""
          ? this.universal_calculator.tax_1
          : "0";

      tax_1 = tax_1 < 0 || tax_1 > 100 ? oldVal : tax_1;

      this.universal_calculator.tax_1 = tax_1;
    },
    "universal_calculator.tax_2": function(newVal, oldVal) {
      let tax_2 =
        !isNaN(this.universal_calculator.tax_2) &&
        this.universal_calculator.tax_2 != ""
          ? this.universal_calculator.tax_2
          : "0";

      tax_2 = tax_2 < 0 || tax_2 > 100 ? oldVal : tax_2;

      this.universal_calculator.tax_2 = tax_2;
    },
    "universal_calculator.interest_rate": function(newVal, oldVal) {
      let interest_rate =
        !isNaN(this.universal_calculator.interest_rate) &&
        this.universal_calculator.interest_rate != ""
          ? this.universal_calculator.interest_rate
          : "0";

      interest_rate =
        interest_rate < 0 || interest_rate > 100 ? oldVal : interest_rate;

      this.universal_calculator.interest_rate = interest_rate;
    },
  },
  computed: {
    ...mapGetters("configuration", ["businessTaxSettings"]),
    ...mapGetters("calendarView", ["getTodayCount"]),
    ...mapGetters("leadResponseView", ["getLoadState"]),
    getAvailableSMSCredits() {
      return this.form.enable_sms_package == "1" &&
        this.sms.sms_phone_number !== null
        ? this.sms.available_sms_credit
        : "0";
    },
    unReadSMS() {
      return this.$helpers.isNumeric(`${this.sms.unread}`)
        ? this.sms.unread
        : 0;
    },
    formatNumber() {
      return this.$helpers.formatPhoneNumber(`${this.sms.sms_phone_number}`);
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    getCurrentRouteName() {
      return this.$route.name;
    },
    getCurrentRouteParam() {
      return this.$route.params.id || "";
    },
    isOwner() {
      return this.$helpers.isAuthorized(this.app_metadata.role, ["Owner"]);
    },
    isAuthorizedForUserManagement() {
      return this.$helpers.isAuthorized(this.app_metadata.role, [
        "Super Owner",
        "Owner",
      ]);
    },
    tenantApp() {
      return this.application == "tenant";
    },
  },
  components: {
    VueSlider,
  },
  methods: {
    ...mapActions("inventoryView", [
      "commitInventoryVehicleTags",
      "commitInventoryMakes",
    ]),
    ...mapActions("configuration", ["getBusinessTaxSettings"]),
    ...mapActions("calendarView", ["commitTodayCount"]),
    ...mapActions("leadResponseView", [
      "commitOnlyEntities",
      "commitLoadState",
    ]),
    ...mapActions("paymentView", {
      commitPaymentEntities: "commitOnlyEntities",
    }),
    async copyText(data) {
      this.$helpers.copyText(data);
    },
    moneyFormatter(value) {
      return this.$helpers.moneyFormatter(value);
    },
    copyPhoneNumber() {
      this.$helpers.doCopy(this, `${this.sms.sms_phone_number}`);
    },
    doCopy(current_string) {
      this.$helpers.doCopy(this, current_string);
    },
    socketIoListener(data) {
      const vm = this;
      if (data.hasOwnProperty("user_id")) {
        if (data.user_id == window.global.app_metadata.id) {
          const notification = new Notification(data.subject, data.content);
          notification.fire();
        }

        if (vm.sms.sms_phone_number !== null) {
          if (
            data.user_id === "*" &&
            data.subject == `SMS:NOTIFY:${vm.sms.sms_phone_number}`
          ) {
            console.log(`SMS:NOTIFY:${vm.sms.sms_phone_number}`);
            vm.userSMSStats();
          }
        }

        if (data.user_id === "*" && data.subject === `RPA:FBMP:NOTIFICATION`) {
          if (this.$helpers.isAuthorized(window.global.app_metadata.role)) {
            this.$helpers.playNotificationSound();
            const rpaNotification = new Notification(
              "FBMP Notification",
              data.content,
            );
            rpaNotification.fire();
          }
        }

        if (
          data.user_id === "*" &&
          (data.subject === `LEAD:RECEIVED` ||
            data.subject === `PAYMENT:RECEIVED`)
        ) {
          this.$helpers.playNotificationSound();

          const toast = new Toast(data.content);
          vm.commitOnlyEntities();
          vm.commitPaymentEntities();
          Event.fire("reload_payments", null);

          toast.reset({
            iconColor: "#fff",
            showClass: {
              popup: "bg-primary",
              icon: "text-white",
            },
            customClass: {
              title: "text-white",
            },
            position: "bottom-end",
            icon: "info",
            title: data.content,
            showConfirmButton: false,
            timer: 5000,
            toast: true,
          });
          toast.fire();
        } else if (
          data.user_id === "*" &&
          data.subject === `REVIEW:FEEDBACK:RECEIVED`
        ) {
          this.$helpers.playNotificationSound();
          const toast = new Toast(data.content);
          toast.reset({
            iconColor: "#fff",
            showClass: {
              popup: "bg-dark",
              icon: "text-white",
            },
            customClass: {
              title: "text-white",
            },
            position: "bottom-end",
            icon: "warning",
            title: data.content,
            showConfirmButton: false,
            timer: 5000,
            toast: true,
          });
          toast.fire();
        }

        if (
          data.subject === `DEAL:UPDATE:${this.getCurrentRouteParam}` &&
          this.getCurrentRouteName === "deal-update"
        ) {
          this.$router.go();
        }
      }
      //console.log(data);
    },
    async getConfigurations(module = "global") {
      let request_url =
        window.location.origin + `/api/business/configurations/${module}`;

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
            this.form.hasOwnProperty(element.key)
              ? (this.form[element.key] = element.value)
              : null;
          }
        }
      });
    },
    async userSMSStats() {
      let request_url =
        window.location.origin + "/api/business/conversation/sms/stats";

      await axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          for (const key in this.sms) {
            if (data.hasOwnProperty(key)) {
              this.sms[key] = data[key];
            }
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    async helpCenter() {
      const vm = this;
      let formData = new FormData();

      let body = `Full Name : ${this.help_center_form.name} <br>`;
      body += `Email : ${this.help_center_form.email} <br>`;
      body += `Phone : ${this.help_center_form.phone} <br>`;
      body += `Description : ${this.help_center_form.description} <br>`;

      formData.append("recipient_email", "support@zopmedia.com");
      formData.append("recipient_name", "Support");
      formData.append(
        "additional_recipient_email",
        this.help_center_form.email,
      );
      formData.append(
        "subject",
        `PORTAL SUPPORT : ${this.form.account_email_name} : ${this.help_center_form.subject}`,
      );
      formData.append("body", body);

      //success message setting
      let success_message = "Support Ticket has been created.";

      let request_url = `${window.location.origin}/api/business/real-time/notification/emails`;

      let request_message = `Generating Support Ticket...`;

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
          toast.success(success_message);
          toast.close();
          this.help_center_errors = [];
          this.help_center_form.subject = "";
          this.help_center_form.description = "";
          document.getElementById("helpCenterClose").click();
        })
        .catch(function(error) {
          toast.fail(error.response.data.errors);
          toast.close();
          this.help_center_errors = [];
        });
    },
    async requestCarfaxIntegration() {
      const vm = this;
      let formData = new FormData();

      let body = `Name : ${this.carfax_integration_request_form.name} <br>`;
      body += `Email : ${this.carfax_integration_request_form.email} <br>`;
      body += `Phone : ${this.carfax_integration_request_form.phone} <br>`;
      body += `Carfax Dealer # : ${this.carfax_integration_request_form.carfax_dealer_id} <br>`;

      formData.append("recipient_email", "support@zopmedia.com");
      formData.append("recipient_name", "Support");
      formData.append(
        "additional_recipient_email",
        this.carfax_integration_request_form.email,
      );
      formData.append(
        "subject",
        `CARFAX ORDER INTEGRATION: #${this.carfax_integration_request_form.carfax_dealer_id}`,
      );
      formData.append("body", body);
      let success_message = "Carfax integration request has been sent.";
      let request_url = `${window.location.origin}/api/business/real-time/notification/emails`;
      let request_message = `Sending carfax integration request...`;
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
          toast.success(success_message);
          toast.close();
          this.carfax_integration_request_errors = [];
          this.carfax_integration_request_form.carfax_dealer_id = "";
          document.getElementById("carfaxRequestModalClose").click();
        })
        .catch(function(error) {
          toast.fail(error.response.data.errors);
          toast.close();
          this.carfax_integration_request_errors = [];
        });
    },
    async referAndSave() {
      const vm = this;
      let formData = new FormData();

      if (!this.$helpers.validateEmail(this.refer_and_save_form.email)) {
        this.refer_and_save_errors = { email: "Invalid Email Address" };
        throw new Error(`Invalid Email.`);
      }

      let body = ``;

      if (window.global.user.user_metadata) {
        body += `Referral By : ${window.global.user.user_metadata.name} from ${window.global.app_metadata.organization} <br>`;
      }

      body += `Referral Details : <br>`;
      body += `Full Name : ${this.refer_and_save_form.full_name} <br>`;
      body += `Email : ${this.refer_and_save_form.email} <br>`;
      body += `Phone : ${this.refer_and_save_form.phone} <br>`;
      body += `Dealership Name : ${this.refer_and_save_form.dealership_name} <br>`;

      formData.append("recipient_email", "support@zopmedia.com");
      formData.append("recipient_name", "Support");
      formData.append("subject", "Referral");
      formData.append("body", body);

      //success message setting
      let success_message = "Referral Has Been Sent Successfully.";

      let request_url = `${window.location.origin}/api/business/real-time/notification/emails`;

      let request_message = `Sending Referral...`;

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
          toast.success(success_message);
          toast.close();

          for (const key in this.refer_and_save_form) {
            this.refer_and_save_form[key] = "";
          }

          this.refer_and_save_errors = [];
          document.getElementById("referAndSaveClose").click();
        })
        .catch(function(error) {
          toast.fail(error.response.data.errors);
          toast.close();
        });
    },
    async closeAccountRequest() {
      const vm = this;
      let formData = new FormData();
      formData.append("feedback", vm.cancel_subscription_form.feedback);
      //success message setting
      let success_message =
        "We have sent your request for Account Cancellation.";

      let request_url = `${window.location.origin}/api/business/account/request/close`;
      let request_message = `Sending Request...`;
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
          toast.success(success_message);
          toast.close();
          this.cancel_subscription_form.feedback = "";
          this.cancel_subscription_errors = [];
          document.getElementById("cancelSubscriptionClose").click();
        })
        .catch(function(error) {
          vm.$data.cancel_subscription_errors = error.response.data;

          let error_message = "There was some error in sending the Request.";

          if (typeof error.response.data.errors === "string") {
            error_message = error.response.data.errors;
          }
          toast.fail(error_message);
          toast.close();
        });
    },
    getZinvoiceHistory() {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/invoice/overdue`,
      })
        .then(({ data }) => {
          this.zlist = data.data;
        })
        .catch(() => {});
    },
    downloadZinvoice($id) {
      const toast = new Toast(`Opening Invoice...`);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/invoice/download/` + $id,
      })
        .then(({ data }) => {
          if (data.hasOwnProperty("url")) {
            window.open(data["url"], "_blank");
          }
          toast.success(data.success);
          toast.close();
        })
        .catch(() => {
          toast.fail(`There were some errors in opening invoice.`);
          toast.close();
        });
    },
    getRoofTopId() {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/admin/get/connection`,
      })
        .then(({ data }) => {
          this.rooftop_id = data.connection_id;
          this.rooftop_id = this.rooftop_id.replace(/.*?(.*).*?(?=Z)/, "");
        })
        .catch(() => {});
    },
    async calculateFinancing() {
      const vm = this;
      this.is_calculating = true;
      let formData = new FormData();
      for (const key in this.universal_calculator) {
        formData.append(key, this.universal_calculator[key]);
      }

      let request_url =
        window.location.origin + "/api/business/calculator/finance";

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
          this.finance = data;
          for (const key in this.finance) {
            this.finance[key] = this.$helpers.moneyFormatter(this.finance[key]);
          }
        })
        .catch((error) => {})
        .finally(() => {
          this.is_calculating = false;
        });
    },
    async resetUniversalFinanceCalculator() {
      this.universal_calculator.vehicle_price = this.universal_calculator.additional_fees = this.universal_calculator.trade_in_value = this.universal_calculator.lien_value = this.universal_calculator.downpayment = 0;
      this.universal_calculator.duration = this.form.vehicle_default_term;
      this.universal_calculator.interest_rate = this.form.vehicle_default_interest_rate;

      this.universal_calculator.tax_1 = this.universal_calculator.tax_2 = 0;

      let selected_tax = this.businessTaxSettings.filter(
        (tax) => tax.default == "1",
      );

      if (selected_tax.length > 0) {
        console.log(selected_tax);
        if (!isNaN(selected_tax[0].value)) {
          this.universal_calculator.tax_1 = selected_tax[0].value;
        }

        if (selected_tax.length > 1) {
          if (!isNaN(selected_tax[1].value)) {
            this.universal_calculator.tax_2 = selected_tax[1].value;
          }
        }
      }
    },
    async checkClientActiveStatus() {
      try {
        await axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: window.location.origin + `/api/clients/status/active`,
        })
          .then(({ data }) => {
            this.show_delete_notification = data.deleted;
          })
          .catch(() => {});
      } catch (error) {}
    },
  },
  async created() {
    const vm = this;
    await this.getRoofTopId();
    await this.getConfigurations();
    await this.getConfigurations("inventory");
    await this.userSMSStats();
    await this.commitTodayCount(this.app_metadata.id);
    await this.commitInventoryVehicleTags();
    await this.commitInventoryMakes();
    await this.getBusinessTaxSettings();
    this.getZinvoiceHistory();
    await this.checkClientActiveStatus();
    this.help_center_form.name = this.user_metadata.name;
    this.carfax_integration_request_form.name = this.user_metadata.name;

    if (window.global.app_metadata.organization) {
      this.socket = io(
        `wss://zopnotify.com:3001/` + window.global.app_metadata.organization,
      );
      this.socket.on("notification", this.socketIoListener);
    }

    if (window.global.user.email) {
      this.help_center_form.email = window.global.user.email;
      this.carfax_integration_request_form.email = window.global.user.email;
    }
  },
  async mounted() {
    const vm = this;

    jQuery("#universalCalculator").on("hidden.bs.modal", function() {
      vm.resetUniversalFinanceCalculator();
    });

    jQuery("#cancelSubscription").on("hidden.bs.modal", function() {
      vm.show_cancel_subscription_form = false;
      vm.cancel_subscription_form.feedback = "";
    });

    jQuery("#universalCalculator").on("shown.bs.modal", function() {
      vm.resetUniversalFinanceCalculator();
    });

    setTimeout(() => {
      if (window.global.user.email) {
        this.help_center_form.email = window.global.user.email;
      }
    }, 2000);
  },
  updated() {},
};
</script>

<style>
.integration-connector {
  display: flex;
  align-items: center;
}

.connector-line {
  display: inline-block;
  width: 22px;
  height: 2px;
  background-color: #28c76f;
  border-radius: 2px;
}

.connector-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  margin: 0 8px;
  background: rgba(40, 199, 111, 0.12);
  color: #155724;
  border: 1px solid rgba(40, 199, 111, 0.3);
  border-radius: 50%;
  font-size: 14px;
  font-weight: 600;
}
</style>
