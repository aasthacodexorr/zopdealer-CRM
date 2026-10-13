<template>
  <div
    class="tab-pane"
    id="customer-credit-application-panel"
    aria-labelledby="information-tab"
    role="tabpanel"
  >
    <!-- company credit application : START -->
    <section id="company-credit-application" v-show="customer_type == '1'">
      <div class="row match-height">
        <div class="col-12 col-xs-12">
          <div class="card">
            <div class="card-content">
              <div class="card-body pb-1 mx-25">
                <h4 class="card-title col-12">Business Credit Application</h4>
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Business</h5>
                  </div>
                  <div class="row">
                    <div class="col-xs-12 col-md-4">
                      <!-- years at present address : START -->
                      <div class="form-group">
                        <label for="company-years-at-present-address"
                          >Years at present address</label
                        >
                        <input
                          type="text"
                          class="form-control"
                          id="company-years-at-present-address"
                          name="years_at_present_address"
                          v-model="
                            company_credit_application.years_at_present_address
                          "
                          :class="{
                            'is-invalid': errors.hasOwnProperty(
                              'years_at_present_address'
                            ),
                          }"
                          @keyup="delete errors.years_at_present_address"
                          :title="errors.years_at_present_address"
                          aria-describedby="Years"
                          placeholder="Years"
                        />
                        <small
                          id="years_at_present_address_error"
                          class="form-text invalid-feedback"
                          >{{ errors.years_at_present_address }}</small
                        >
                      </div>
                    </div>
                    <!-- years at present address : END -->
                    <div class="w-100"></div>
                    <!-- business type : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="buisness-type">Business Type</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            type="text"
                            placeholder="Business Type"
                            v-model="company_credit_application.business_type"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('business_type'),
                            }"
                            @keyup="delete errors.business_type"
                            :title="errors.business_type"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-factory font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="business_type-error"
                          class="form-text invalid-feedback"
                          >{{ errors.business_type }}</small
                        >
                      </div>
                    </div>
                    <!-- business type : END -->

                    <!-- number_of_employees : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="number_of_employees">No of Employees</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="number_of_employees"
                            type="text"
                            placeholder="Number of Employees"
                            v-model="
                              company_credit_application.number_of_employees
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'number_of_employees'
                              ),
                            }"
                            @keyup="delete errors.number_of_employees"
                            :title="errors.number_of_employees"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-group font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="number_of_employees-error"
                          class="form-text invalid-feedback"
                          >{{ errors.number_of_employees }}</small
                        >
                      </div>
                    </div>
                    <!-- number_of_employees : END -->

                    <!-- years_in_business : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="years_in_business">Years in Business</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="years_in_business"
                            type="text"
                            placeholder="Years in Business"
                            v-model="
                              company_credit_application.years_in_business
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('years_in_business'),
                            }"
                            @keyup="delete errors.years_in_business"
                            :title="errors.years_in_business"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-calendar font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="years_in_business-error"
                          class="form-text invalid-feedback"
                          >{{ errors.years_in_business }}</small
                        >
                      </div>
                    </div>
                    <!-- years_in_business : END -->
                  </div>
                </div>

                <!-- Accountant : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Accountant</h5>
                  </div>
                  <div class="row">
                    <!-- accountant_firm : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="accountant_firm">Accountant Firm</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="accountant_firm"
                            type="text"
                            placeholder="Accountant Firm"
                            v-model="company_credit_application.accountant_firm"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('accountant_firm'),
                            }"
                            @keyup="delete errors.accountant_firm"
                            :title="errors.accountant_firm"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-building font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="accountant_firm-error"
                          class="form-text invalid-feedback"
                          >{{ errors.accountant_firm }}</small
                        >
                      </div>
                    </div>
                    <!-- accountant_firm : END -->

                    <!-- accountant_personal : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="accountant_personal"
                          >Accountant Personal</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="accountant_personal"
                            type="text"
                            placeholder="Accountant Personal"
                            v-model="
                              company_credit_application.accountant_personal
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'accountant_personal'
                              ),
                            }"
                            @keyup="delete errors.accountant_personal"
                            :title="errors.accountant_personal"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-id-card font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="accountant_personal-error"
                          class="form-text invalid-feedback"
                          >{{ errors.accountant_personal }}</small
                        >
                      </div>
                    </div>
                    <!-- accountant_personal : END -->

                    <!-- accountant_phone : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="accountant_phone">Accountant Phone</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="accountant_phone"
                            type="tel"
                            placeholder="Accountant Phone"
                            v-model="
                              company_credit_application.accountant_phone
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('accountant_phone'),
                            }"
                            @keyup="delete errors.accountant_phone"
                            :title="errors.accountant_phone"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-phone font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="accountant_phone-error"
                          class="form-text invalid-feedback"
                          >{{ errors.accountant_phone }}</small
                        >
                      </div>
                    </div>
                    <!-- accountant_phone : END -->

                    <!-- accountant_phone_ext : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="accountant_phone_ext"
                          >Accountant Phone Ext</label
                        >
                        <fieldset class="" style="position: relative">
                          <input
                            id="accountant_phone_ext"
                            type="tel"
                            placeholder="Accountant Phone Ext"
                            v-model="
                              company_credit_application.accountant_phone_ext
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'accountant_phone_ext'
                              ),
                            }"
                            @keyup="delete errors.accountant_phone_ext"
                            :title="errors.accountant_phone_ext"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="accountant_phone_ext-error"
                          class="form-text invalid-feedback"
                          >{{ errors.accountant_phone_ext }}</small
                        >
                      </div>
                    </div>
                    <!-- accountant_phone_ext : END -->
                  </div>
                </div>
                <!-- Accountant : END -->
                <!-- Ownership details : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Ownership</h5>
                  </div>
                  <div class="row">
                    <!-- principal_owner : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="principal_owner">Principal Owner</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="principal_owner"
                            type="text"
                            placeholder="Principal Owner"
                            v-model="company_credit_application.principal_owner"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('principal_owner'),
                            }"
                            @keyup="delete errors.principal_owner"
                            :title="errors.principal_owner"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-user font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="principal_owner-error"
                          class="form-text invalid-feedback"
                          >{{ errors.principal_owner }}</small
                        >
                      </div>
                    </div>
                    <!-- principal_owner : END -->

                    <!-- officer_name : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="officer_name">Officer Name</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="officer_name"
                            type="text"
                            placeholder="Officer Name"
                            v-model="company_credit_application.officer_name"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('officer_name'),
                            }"
                            @keyup="delete errors.officer_name"
                            :title="errors.officer_name"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-id-card font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="officer_name-error"
                          class="form-text invalid-feedback"
                          >{{ errors.officer_name }}</small
                        >
                      </div>
                    </div>
                    <!-- officer_name : END -->

                    <!-- officer_position : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="officer_position">Officer Position</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="officer_position"
                            type="text"
                            placeholder="Officer Position"
                            v-model="
                              company_credit_application.officer_position
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('officer_position'),
                            }"
                            @keyup="delete errors.officer_position"
                            :title="errors.officer_position"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-briefcase-alt-2 font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="officer_position-error"
                          class="form-text invalid-feedback"
                          >{{ errors.officer_position }}</small
                        >
                      </div>
                    </div>
                    <!-- officer_position : END -->

                    <!-- ownership_percentage : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="ownership_percentage"
                          >Ownership Percentage</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="ownership_percentage"
                            type="text"
                            placeholder="Ownership Percentage %"
                            v-model="
                              company_credit_application.ownership_percentage
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'ownership_percentage'
                              ),
                            }"
                            @keyup="delete errors.ownership_percentage"
                            :title="errors.ownership_percentage"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-pie-chart-alt-2 font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="ownership_percentage-error"
                          class="form-text invalid-feedback"
                          >{{ errors.ownership_percentage }}</small
                        >
                      </div>
                    </div>
                    <!-- ownership_percentage : END -->

                    <!-- years_in_business : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="officer_phone">Officer Phone</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="officer_phone"
                            type="tel"
                            placeholder="Officer Phone"
                            v-model="company_credit_application.officer_phone"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('officer_phone'),
                            }"
                            @keyup="delete errors.officer_phone"
                            :title="errors.officer_phone"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-phone font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="officer_phone-error"
                          class="form-text invalid-feedback"
                          >{{ errors.officer_phone }}</small
                        >
                      </div>
                    </div>
                    <!-- officer_phone : END -->

                    <!-- officer_phone_ext : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="officer_phone_ext">Officer Phone Ext</label>
                        <fieldset class="" style="position: relative">
                          <input
                            id="officer_phone_ext"
                            type="tel"
                            placeholder="Officer Phone Ext"
                            v-model="
                              company_credit_application.officer_phone_ext
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('officer_phone_ext'),
                            }"
                            @keyup="delete errors.officer_phone_ext"
                            :title="errors.officer_phone_ext"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="officer_phone_ext-error"
                          class="form-text invalid-feedback"
                          >{{ errors.officer_phone_ext }}</small
                        >
                      </div>
                    </div>
                    <!-- officer_phone_ext : END -->

                    <!-- years_in_business : START -->
                    <div class="col-xs-12 col-md-8">
                      <div class="form-group">
                        <label for="officer_address">Officer Address</label>
                        <textarea
                          id="officer_address"
                          placeholder="Officer Address"
                          v-model="company_credit_application.officer_address"
                          class="form-control"
                          :class="{
                            'is-invalid':
                              errors.hasOwnProperty('officer_address'),
                          }"
                          @keyup="delete errors.officer_address"
                          :title="errors.officer_address"
                        ></textarea>

                        <small
                          id="officer_address-error"
                          class="form-text invalid-feedback"
                          >{{ errors.officer_address }}</small
                        >
                      </div>
                    </div>
                    <!-- years_in_business : END -->
                  </div>
                </div>
                <!-- Ownership details : END -->
                <!-- Banking details: START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Banking</h5>
                  </div>
                  <div class="row">
                    <!-- bank_name : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="bank_name">Bank Name</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="bank_name"
                            type="text"
                            placeholder="Bank Name"
                            v-model="company_credit_application.bank_name"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('bank_name'),
                            }"
                            @keyup="delete errors.bank_name"
                            :title="errors.bank_name"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-bank font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="bank_name-error"
                          class="form-text invalid-feedback"
                          >{{ errors.bank_name }}</small
                        >
                      </div>
                    </div>
                    <!-- bank_name : END -->

                    <!-- bank_number : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="bank_number">Bank Number</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="bank_number"
                            type="text"
                            placeholder="Bank Number"
                            v-model="company_credit_application.bank_number"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('bank_number'),
                            }"
                            @keyup="delete errors.bank_number"
                            :title="errors.bank_number"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-hash font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="bank_number-error"
                          class="form-text invalid-feedback"
                          >{{ errors.bank_number }}</small
                        >
                      </div>
                    </div>
                    <!-- bank_number : END -->

                    <!-- bank_account_number : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="bank_account_number">Account Number</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="bank_account_number"
                            type="text"
                            placeholder="Account Number"
                            v-model="
                              company_credit_application.bank_account_number
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'bank_account_number'
                              ),
                            }"
                            @keyup="delete errors.bank_account_number"
                            :title="errors.bank_account_number"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-hash font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="bank_account_number-error"
                          class="form-text invalid-feedback"
                          >{{ errors.bank_account_number }}</small
                        >
                      </div>
                    </div>
                    <!-- bank_account_number : END -->

                    <!-- bank_account_type : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="bank_account_type">Account Type</label>
                        <fieldset class="" style="position: relative">
                          <input
                            id="bank_account_type"
                            type="text"
                            placeholder="Account Type"
                            v-model="
                              company_credit_application.bank_account_type
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('bank_account_type'),
                            }"
                            @keyup="delete errors.bank_account_type"
                            :title="errors.bank_account_type"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="bank_account_type-error"
                          class="form-text invalid-feedback"
                          >{{ errors.bank_account_type }}</small
                        >
                      </div>
                    </div>
                    <!-- bank_account_type : END -->

                    <!-- bank_branch_number : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="bank_branch_number">Branch Number</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="bank_branch_number"
                            type="text"
                            placeholder="Branch Number"
                            v-model="
                              company_credit_application.bank_branch_number
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('bank_branch_number'),
                            }"
                            @keyup="delete errors.bank_branch_number"
                            :title="errors.bank_branch_number"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-landmark font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="bank_branch_number-error"
                          class="form-text invalid-feedback"
                          >{{ errors.bank_branch_number }}</small
                        >
                      </div>
                    </div>
                    <!-- bank_branch_number : END -->

                    <!-- bank_contact_personal : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="bank_contact_personal">Bank Personal</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="bank_contact_personal"
                            type="text"
                            placeholder="Bank Personal"
                            v-model="
                              company_credit_application.bank_contact_personal
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'bank_contact_personal'
                              ),
                            }"
                            @keyup="delete errors.bank_contact_personal"
                            :title="errors.bank_contact_personal"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-id-card font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="bank_contact_personal-error"
                          class="form-text invalid-feedback"
                          >{{ errors.bank_contact_personal }}</small
                        >
                      </div>
                    </div>
                    <!-- bank_contact_personal : END -->

                    <!-- bank_phone : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="bank_phone">Bank Phone</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="bank_phone"
                            type="tel"
                            placeholder="Bank Phone"
                            v-model="company_credit_application.bank_phone"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('bank_phone'),
                            }"
                            @keyup="delete errors.bank_phone"
                            :title="errors.bank_phone"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-phone font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="bank_phone-error"
                          class="form-text invalid-feedback"
                          >{{ errors.bank_phone }}</small
                        >
                      </div>
                    </div>
                    <!-- bank_phone : END -->

                    <!-- bank_phone_ext : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="bank_phone_ext">Bank Phone Ext</label>
                        <fieldset class="" style="position: relative">
                          <input
                            id="bank_phone_ext"
                            type="tel"
                            placeholder="Bank Phone Ext"
                            v-model="company_credit_application.bank_phone_ext"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('bank_phone_ext'),
                            }"
                            @keyup="delete errors.bank_phone_ext"
                            :title="errors.bank_phone_ext"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="bank_phone_ext-error"
                          class="form-text invalid-feedback"
                          >{{ errors.bank_phone_ext }}</small
                        >
                      </div>
                    </div>
                    <!-- bank_phone_ext : END -->

                    <div class="col-xs-12 col-md-12">
                      <suggest-location
                        :elementId="'bank_address'"
                      ></suggest-location>
                    </div>

                    <!-- bank_street_address : START -->
                    <div class="col-xs-12 col-md-6">
                      <div class="form-group">
                        <label for="bank_street_address">Street Address</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="bank_street_address"
                            type="text"
                            placeholder="Street Address"
                            v-model="
                              company_credit_application.bank_street_address
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'bank_street_address'
                              ),
                            }"
                            @keyup="delete errors.bank_street_address"
                            :title="errors.bank_street_address"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-directions font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="bank_street_address-error"
                          class="form-text invalid-feedback"
                          >{{ errors.bank_street_address }}</small
                        >
                      </div>
                    </div>
                    <!-- bank_street_address : END -->

                    <!-- bank_apt : START -->
                    <div class="col-xs-12 col-md-6">
                      <div class="form-group">
                        <label for="bank_apt">Suite / Apt</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="bank_apt"
                            type="text"
                            placeholder="Suite/Apt"
                            v-model="company_credit_application.bank_apt"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('bank_apt'),
                            }"
                            @keyup="delete errors.bank_apt"
                            :title="errors.bank_apt"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-hash font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="bank_apt-error"
                          class="form-text invalid-feedback"
                          >{{ errors.bank_apt }}</small
                        >
                      </div>
                    </div>
                    <!-- bank_apt : END -->

                    <!-- bank_city : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="bank_city">City</label>
                        <fieldset class="" style="position: relative">
                          <input
                            id="bank_city"
                            type="text"
                            placeholder="City"
                            v-model="company_credit_application.bank_city"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('bank_city'),
                            }"
                            @keyup="delete errors.bank_city"
                            :title="errors.bank_city"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="bank_city-error"
                          class="form-text invalid-feedback"
                          >{{ errors.bank_city }}</small
                        >
                      </div>
                    </div>
                    <!-- bank_city : END -->

                    <!-- bank_province : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="bank_province">Province / State</label>
                        <fieldset class="" style="position: relative">
                          <input
                            id="bank_province"
                            type="text"
                            placeholder="Province"
                            v-model="company_credit_application.bank_province"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('bank_province'),
                            }"
                            @keyup="delete errors.bank_province"
                            :title="errors.bank_province"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="bank_province-error"
                          class="form-text invalid-feedback"
                          >{{ errors.bank_province }}</small
                        >
                      </div>
                    </div>
                    <!-- bank_province : END -->

                    <!-- bank_postal_code : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="bank_postal_code">Postal Code / ZIP</label>
                        <fieldset class="" style="position: relative">
                          <input
                            id="bank_postal_code"
                            type="text"
                            placeholder="Postal Code / ZIP"
                            v-model="
                              company_credit_application.bank_postal_code
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('bank_postal_code'),
                            }"
                            @keyup="delete errors.bank_postal_code"
                            :title="errors.bank_postal_code"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="bank_postal_code-error"
                          class="form-text invalid-feedback"
                          >{{ errors.bank_postal_code }}</small
                        >
                      </div>
                    </div>
                    <!-- bank_postal_code : END -->

                    <!-- bank_postal_code : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="bank_country">Country</label>
                        <fieldset class="" style="position: relative">
                          <input
                            id="bank_country"
                            type="text"
                            placeholder="Country"
                            v-model="company_credit_application.bank_country"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('bank_country'),
                            }"
                            @keyup="delete errors.bank_country"
                            :title="errors.bank_country"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="bank_country-error"
                          class="form-text invalid-feedback"
                          >{{ errors.bank_country }}</small
                        >
                      </div>
                    </div>
                    <!-- bank_country : END -->
                  </div>
                </div>
                <!-- Banking details: END -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- company credit application : END -->

    <!-- individual credit application : START -->
    <section id="individual-credit-application" v-show="customer_type == '0'">
      <div class="row match-height">
        <div class="col-12 col-xs-12">
          <div class="card">
            <div class="card-content">
              <div class="card-body pb-1 mx-25">
                <h4 class="card-title col-12">individual Credit Application</h4>
                <!-- Personal Details : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Personal Details</h5>
                  </div>
                  <div class="row">
                    <!-- salutation : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="salutation">Salutation</label>
                        <select
                          id="salutation"
                          class="custom-select"
                          v-model="individual_credit_application.salutation"
                          name="source"
                        >
                          <option disabled value="">
                            -- Select Salutation --
                          </option>
                          <option value="Mr">Mr</option>
                          <option value="Mrs">Mrs</option>
                          <option value="Miss">Miss</option>
                          <option value="Ms">Ms</option>
                          <option value="Dr">Dr</option>
                          <option value="Prof">Prof</option>
                          <option value="Rev">Rev</option>
                          <option value="Other">Other</option>
                        </select>
                        <small
                          id="salutation-error"
                          class="form-text invalid-feedback"
                          >{{ errors.salutation }}</small
                        >
                      </div>
                    </div>
                    <!-- salutation : END -->

                    <!-- gender : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="gender">Gender</label>
                        <select
                          id="gender"
                          class="custom-select"
                          v-model="individual_credit_application.gender"
                          name="source"
                        >
                          <option disabled value="">-- Select Gender --</option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                          <option value="Other">Other</option>
                        </select>
                        <small
                          id="gender-error"
                          class="form-text invalid-feedback"
                          >{{ errors.gender }}</small
                        >
                      </div>
                    </div>
                    <!-- gender : END -->

                    <!-- marital_status : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="marital_status">Marital Status</label>
                        <select
                          id="marital_status"
                          class="custom-select"
                          v-model="individual_credit_application.marital_status"
                          name="source"
                        >
                          <option disabled value="">
                            -- Select Marital Status --
                          </option>
                          <option value="Divorced">Divorced</option>
                          <option value="Married">Married</option>
                          <option value="Common Law">Common Law</option>
                          <option value="Widow/Widower">Widow/Widower</option>
                          <option value="Separated">Separated</option>
                          <option value="Single">Single</option>
                        </select>
                        <small
                          id="marital_status-error"
                          class="form-text invalid-feedback"
                          >{{ errors.marital_status }}</small
                        >
                      </div>
                    </div>
                    <!-- marital_status : END -->

                    <div class="col-xs-12 col-md-4">
                      <!-- sin : START -->
                      <div class="form-group">
                        <label for="sin">SIN / SSN</label>
                        <input
                          type="text"
                          class="form-control"
                          id="sin"
                          name="SIN"
                          v-model="individual_credit_application.sin"
                          :class="{
                            'is-invalid': errors.hasOwnProperty('sin'),
                          }"
                          @keyup="delete errors.sin"
                          :title="errors.sin"
                          aria-describedby="SIN / SSN"
                          placeholder="SIN / SSN"
                        />
                        <small
                          id="sin_error"
                          class="form-text invalid-feedback"
                          >{{ errors.sin }}</small
                        >
                      </div>
                    </div>
                    <!-- sin : END -->
                  </div>
                </div>
                <!-- Personal Details : END -->

                <!-- Home / Mortgage : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Home/Mortgage Details</h5>
                  </div>
                  <div class="row">
                    <!-- Residence Ownership : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="residence_ownership"
                          >Residence Ownership</label
                        >
                        <select
                          id="residence_ownership"
                          class="custom-select"
                          v-model="
                            individual_credit_application.residence_ownership
                          "
                          name="source"
                        >
                          <option value="" disabled>
                            -- Select Ownership --
                          </option>
                          <option value="Rent">Rent</option>
                          <option value="Own">Own</option>
                          <option value="Own Free Clear">Own Free Clear</option>
                          <option value="With Family">With Family</option>
                          <option value="Other">Other</option>
                        </select>
                        <small
                          id="residence_ownership-error"
                          class="form-text invalid-feedback"
                          >{{ errors.residence_ownership }}</small
                        >
                      </div>
                    </div>
                    <!-- Residence Ownership : END -->
                    <div class="w-100"></div>

                    <!-- market_value : START -->
                    <div
                      class="col-xs-12 col-md-4"
                      v-show="
                        individual_credit_application.residence_ownership ==
                        'Own'
                      "
                    >
                      <div class="form-group">
                        <label for="market_value">Market Value</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="market_value"
                            type="text"
                            placeholder="Market Value"
                            v-model="individual_credit_application.market_value"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('market_value'),
                            }"
                            @keyup="delete errors.market_value"
                            :title="errors.market_value"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="market_value-error"
                          class="form-text invalid-feedback"
                          >{{ errors.market_value }}</small
                        >
                      </div>
                    </div>
                    <!-- market_value : END -->

                    <!-- mortgage_amount : START -->
                    <div
                      class="col-xs-12 col-md-4"
                      v-show="
                        individual_credit_application.residence_ownership ==
                        'Own'
                      "
                    >
                      <div class="form-group">
                        <label for="mortgage_amount">Mortgage Amount</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="mortgage_amount"
                            type="text"
                            placeholder="Mortgage Amount"
                            v-model="
                              individual_credit_application.mortgage_amount
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('mortgage_amount'),
                            }"
                            @keyup="delete errors.mortgage_amount"
                            :title="errors.mortgage_amount"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="mortgage_amount-error"
                          class="form-text invalid-feedback"
                          >{{ errors.mortgage_amount }}</small
                        >
                      </div>
                    </div>
                    <!-- mortgage_amount : END -->

                    <!-- monthly_payment : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="monthly_payment">Monthly Payment</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="monthly_payment"
                            type="text"
                            placeholder="Monthly Payment"
                            v-model="
                              individual_credit_application.monthly_payment
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('monthly_payment'),
                            }"
                            @keyup="delete errors.monthly_payment"
                            :title="errors.monthly_payment"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="monthly_payment-error"
                          class="form-text invalid-feedback"
                          >{{ errors.monthly_payment }}</small
                        >
                      </div>
                    </div>
                    <!-- monthly_payment : END -->
                    <div class="w-100"></div>
                    <!-- mortgage_bank : START -->
                    <div
                      class="col-xs-12 col-md-4"
                      v-show="
                        individual_credit_application.residence_ownership ==
                        'Own'
                      "
                    >
                      <div class="form-group">
                        <label for="mortgage_bank">Mortgage Bank</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="mortgage_bank"
                            type="text"
                            placeholder="Mortgage Bank"
                            v-model="
                              individual_credit_application.mortgage_bank
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('mortgage_bank'),
                            }"
                            @keyup="delete errors.mortgage_bank"
                            :title="errors.mortgage_bank"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-bank font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="mortgage_bank-error"
                          class="form-text invalid-feedback"
                          >{{ errors.mortgage_bank }}</small
                        >
                      </div>
                    </div>
                    <!-- mortgage_bank : END -->

                    <div class="w-100"></div>

                    <!-- years_in_business : START -->
                    <div
                      class="col-xs-12 col-md-4"
                      v-show="
                        individual_credit_application.residence_ownership ==
                        'Rent'
                      "
                    >
                      <div class="form-group">
                        <label for="property_owner">Property Owner</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="property_owner"
                            type="text"
                            placeholder="Property Owner"
                            v-model="
                              individual_credit_application.property_owner
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('property_owner'),
                            }"
                            @keyup="delete errors.property_owner"
                            :title="errors.property_owner"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-user-badge font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="property_owner-error"
                          class="form-text invalid-feedback"
                          >{{ errors.property_owner }}</small
                        >
                      </div>
                    </div>
                    <!-- property_owner : END -->

                    <div class="w-100"></div>
                    <!-- years_at_present_address : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="company_years_at_present_address"
                          >Years at Present Address</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="company_years_at_present_address"
                            type="text"
                            placeholder="Years"
                            v-model="
                              individual_credit_application.years_at_present_address
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'years_at_present_address'
                              ),
                            }"
                            @keyup="delete errors.years_at_present_address"
                            :title="errors.years_at_present_address"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-calendar font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="years_at_present_address-error"
                          class="form-text invalid-feedback"
                          >{{ errors.years_at_present_address }}</small
                        >
                      </div>
                    </div>
                    <!-- years_at_present_address : END -->
                  </div>
                </div>
                <!-- Home / Mortgage : END -->
                <!-- Previous Address : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">
                      Previous Address (If less than 2 years at Current
                      Residence)
                    </h5>
                  </div>
                  <div class="row">
                    <div class="col-xs-12 col-md-12">
                      <suggest-location
                        :elementId="'previous_address'"
                      ></suggest-location>
                    </div>
                    <!-- previous_address : START -->
                    <div class="col-xs-12 col-md-12">
                      <div class="form-group">
                        <label for="previous_address">Street Address</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="previous_address"
                            type="text"
                            placeholder="Street Address"
                            v-model="
                              individual_credit_application.previous_address
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('previous_address'),
                            }"
                            @keyup="delete errors.previous_address"
                            :title="errors.previous_address"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-directions font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="previous_address-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_address }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_address : END -->

                    <!-- previous_address_city : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="previous_address_city">City</label>
                        <fieldset class="" style="position: relative">
                          <input
                            id="previous_address_city"
                            type="text"
                            placeholder="City"
                            v-model="
                              individual_credit_application.previous_address_city
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_address_city'
                              ),
                            }"
                            @keyup="delete errors.previous_address_city"
                            :title="errors.previous_address_city"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="previous_address_city-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_address_city }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_address_city : END -->

                    <!-- previous_address_province : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="previous_address_province"
                          >Province / State</label
                        >
                        <fieldset class="" style="position: relative">
                          <input
                            id="previous_address_province"
                            type="text"
                            placeholder="Province / State"
                            v-model="
                              individual_credit_application.previous_address_province
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_address_province'
                              ),
                            }"
                            @keyup="delete errors.previous_address_province"
                            :title="errors.previous_address_province"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="previous_address_province-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_address_province }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_address_province : END -->

                    <!-- previous_address_postal_code : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="previous_address_postal_code"
                          >Postal Code / ZIP</label
                        >
                        <fieldset class="" style="position: relative">
                          <input
                            id="previous_address_postal_code"
                            type="text"
                            placeholder="Postal Code / ZIP"
                            v-model="
                              individual_credit_application.previous_address_postal_code
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_address_postal_code'
                              ),
                            }"
                            @keyup="delete errors.previous_address_postal_code"
                            :title="errors.previous_address_postal_code"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="previous_address_postal_code-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_address_postal_code }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_address_postal_code : END -->

                    <!-- previous_residence_monthly_payment : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="previous_residence_monthly_payment"
                          >Monthly Payment</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="previous_residence_monthly_payment"
                            type="text"
                            placeholder="Monthly Payment"
                            v-model="
                              individual_credit_application.previous_residence_monthly_payment
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_residence_monthly_payment'
                              ),
                            }"
                            @keyup="
                              delete errors.previous_residence_monthly_payment
                            "
                            :title="errors.previous_residence_monthly_payment"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="previous_residence_monthly_payment-error"
                          class="form-text invalid-feedback"
                          >{{
                            errors.previous_residence_monthly_payment
                          }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_residence_monthly_payment : END -->

                    <!-- previous_residence_address_period_years : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="previous_residence_address_period_years"
                          >Residence Years</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="previous_residence_address_period_years"
                            type="text"
                            placeholder="Years"
                            v-model="
                              individual_credit_application.previous_residence_address_period_years
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_residence_address_period_years'
                              ),
                            }"
                            @keyup="
                              delete errors.previous_residence_address_period_years
                            "
                            :title="
                              errors.previous_residence_address_period_years
                            "
                          />
                          <div class="form-control-position">
                            <i class="bx bx-calendar font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="previous_residence_address_period_years-error"
                          class="form-text invalid-feedback"
                          >{{
                            errors.previous_residence_address_period_years
                          }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_residence_address_period_years : END -->

                    <!-- previous_residence_period_months : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="previous_residence_period_months"
                          >Residence Months</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="previous_residence_period_months"
                            type="text"
                            placeholder="Months"
                            v-model="
                              individual_credit_application.previous_residence_period_months
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_residence_period_months'
                              ),
                            }"
                            @keyup="
                              delete errors.previous_residence_period_months
                            "
                            :title="errors.previous_residence_period_months"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-calendar font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="previous_residence_period_months-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_residence_period_months }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_residence_period_months : END -->
                  </div>
                </div>
                <!-- Previous Address : END -->

                <!-- Primary Employment & Income : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Employment & Income</h5>
                  </div>
                  <div class="row">
                    <!-- employment_type : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="employment_type">Employment Type</label>
                        <fieldset class="" style="position: relative">
                          <input
                            id="employment_type"
                            type="text"
                            placeholder="Employment Type"
                            v-model="
                              individual_credit_application.employment_type
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('employment_type'),
                            }"
                            @keyup="delete errors.employment_type"
                            :title="errors.employment_type"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="employment_type-error"
                          class="form-text invalid-feedback"
                          >{{ errors.employment_type }}</small
                        >
                      </div>
                    </div>
                    <!-- employment_type : END -->

                    <!-- job_title : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="job_title">Job Title</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="job_title"
                            type="text"
                            placeholder="Job Title"
                            v-model="individual_credit_application.job_title"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('job_title'),
                            }"
                            @keyup="delete errors.job_title"
                            :title="errors.job_title"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-briefcase-alt-2 font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="job_title-error"
                          class="form-text invalid-feedback"
                          >{{ errors.job_title }}</small
                        >
                      </div>
                    </div>
                    <!-- job_title : END -->

                    <!-- occupation : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="occupation">Occupation</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="occupation"
                            type="text"
                            placeholder="Occupation"
                            v-model="individual_credit_application.occupation"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('occupation'),
                            }"
                            @keyup="delete errors.occupation"
                            :title="errors.occupation"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-user-badge font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="occupation-error"
                          class="form-text invalid-feedback"
                          >{{ errors.occupation }}</small
                        >
                      </div>
                    </div>
                    <!-- occupation : END -->

                    <!-- employer_name : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="employer_name">Employer Name</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="employer_name"
                            type="text"
                            placeholder="Employer Name"
                            v-model="
                              individual_credit_application.employer_name
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('employer_name'),
                            }"
                            @keyup="delete errors.employer_name"
                            :title="errors.employer_name"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-id-card font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="employer_name-error"
                          class="form-text invalid-feedback"
                          >{{ errors.employer_name }}</small
                        >
                      </div>
                    </div>
                    <!-- employer_name : END -->

                    <!-- employer_work_phone : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="employer_work_phone">Work Phone</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="employer_work_phone"
                            type="tel"
                            placeholder="Work Phone"
                            v-model="
                              individual_credit_application.employer_work_phone
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'employer_work_phone'
                              ),
                            }"
                            @keyup="delete errors.employer_work_phone"
                            :title="errors.employer_work_phone"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-phone font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="employer_work_phone-error"
                          class="form-text invalid-feedback"
                          >{{ errors.employer_work_phone }}</small
                        >
                      </div>
                    </div>
                    <!-- employer_work_phone : END -->

                    <!-- employer_work_phone_ext : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="employer_work_phone_ext">Work Ext</label>
                        <fieldset class="" style="position: relative">
                          <input
                            id="employer_work_phone_ext"
                            type="tel"
                            placeholder="Work Ext"
                            v-model="
                              individual_credit_application.employer_work_phone_ext
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'employer_work_phone_ext'
                              ),
                            }"
                            @keyup="delete errors.employer_work_phone_ext"
                            :title="errors.employer_work_phone_ext"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="employer_work_phone_ext-error"
                          class="form-text invalid-feedback"
                          >{{ errors.employer_work_phone_ext }}</small
                        >
                      </div>
                    </div>
                    <!-- employer_work_phone_ext : END -->

                    <!-- employment_period_years : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="employment_period_years"
                          >Employment Years</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="employment_period_years"
                            type="text"
                            placeholder="Years"
                            v-model="
                              individual_credit_application.employment_period_years
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'employment_period_years'
                              ),
                            }"
                            @keyup="delete errors.employment_period_years"
                            :title="errors.employment_period_years"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-calendar font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="employment_period_years-error"
                          class="form-text invalid-feedback"
                          >{{ errors.employment_period_years }}</small
                        >
                      </div>
                    </div>
                    <!-- employment_period_years : END -->

                    <!-- employment_period_months : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="employment_period_months"
                          >Employment Months</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="employment_period_months"
                            type="text"
                            placeholder="Months"
                            v-model="
                              individual_credit_application.employment_period_months
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'employment_period_months'
                              ),
                            }"
                            @keyup="delete errors.employment_period_months"
                            :title="errors.employment_period_months"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-calendar font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="employment_period_months-error"
                          class="form-text invalid-feedback"
                          >{{ errors.employment_period_months }}</small
                        >
                      </div>
                    </div>
                    <!-- employment_period_months : END -->
                    <div class="w-100"></div>

                    <div class="col-xs-12 col-md-12">
                      <suggest-location
                        :elementId="'employer_address'"
                      ></suggest-location>
                    </div>

                    <!-- employer_address : START -->
                    <div class="col-xs-12 col-md-12">
                      <div class="form-group">
                        <label for="employer_address">Address</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="employer_address"
                            type="text"
                            placeholder="Address"
                            v-model="
                              individual_credit_application.employer_address
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('employer_address'),
                            }"
                            @keyup="delete errors.employer_address"
                            :title="errors.employer_address"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-directions font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="employer_address-error"
                          class="form-text invalid-feedback"
                          >{{ errors.employer_address }}</small
                        >
                      </div>
                    </div>
                    <!-- employer_address : END -->

                    <!-- employer_city : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="employer_city">City</label>
                        <fieldset class="" style="position: relative">
                          <input
                            id="employer_city"
                            type="text"
                            placeholder="City"
                            v-model="
                              individual_credit_application.employer_city
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('employer_city'),
                            }"
                            @keyup="delete errors.employer_city"
                            :title="errors.employer_city"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="employer_city-error"
                          class="form-text invalid-feedback"
                          >{{ errors.employer_city }}</small
                        >
                      </div>
                    </div>
                    <!-- employer_city : END -->

                    <!-- employer_province : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="employer_province">Province / State</label>
                        <fieldset class="" style="position: relative">
                          <input
                            id="employer_province"
                            type="text"
                            placeholder="Province / State"
                            v-model="
                              individual_credit_application.employer_province
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('employer_province'),
                            }"
                            @keyup="delete errors.employer_province"
                            :title="errors.employer_province"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="employer_province-error"
                          class="form-text invalid-feedback"
                          >{{ errors.employer_province }}</small
                        >
                      </div>
                    </div>
                    <!-- employer_province : END -->

                    <!-- employer_postal_code : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="employer_postal_code"
                          >Postal Code / ZIP</label
                        >
                        <fieldset class="" style="position: relative">
                          <input
                            id="employer_postal_code"
                            type="text"
                            placeholder="Postal Code / ZIP"
                            v-model="
                              individual_credit_application.employer_postal_code
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'employer_postal_code'
                              ),
                            }"
                            @keyup="delete errors.employer_postal_code"
                            :title="errors.employer_postal_code"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="employer_postal_code-error"
                          class="form-text invalid-feedback"
                          >{{ errors.employer_postal_code }}</small
                        >
                      </div>
                    </div>
                    <!-- employer_postal_code : END -->

                    <!-- employer_country : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="employer_country">Country</label>
                        <fieldset class="" style="position: relative">
                          <input
                            id="employer_country"
                            type="text"
                            placeholder="Country"
                            v-model="
                              individual_credit_application.employer_country
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('employer_country'),
                            }"
                            @keyup="delete errors.employer_country"
                            :title="errors.employer_country"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="employer_country-error"
                          class="form-text invalid-feedback"
                          >{{ errors.employer_country }}</small
                        >
                      </div>
                    </div>
                    <!-- employer_country : END -->

                    <!-- rate_per_hour : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="rate_per_hour">Rate Per Hour</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="rate_per_hour"
                            type="text"
                            placeholder="Rate Per Hour"
                            v-model="
                              individual_credit_application.rate_per_hour
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('rate_per_hour'),
                            }"
                            @keyup="delete errors.rate_per_hour"
                            :title="errors.rate_per_hour"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="rate_per_hour-error"
                          class="form-text invalid-feedback"
                          >{{ errors.rate_per_hour }}</small
                        >
                      </div>
                    </div>
                    <!-- rate_per_hour : END -->

                    <!-- hours_per_week : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="hours_per_week">Hours Per Week</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="hours_per_week"
                            type="text"
                            placeholder="Rate Per Hour"
                            v-model="
                              individual_credit_application.hours_per_week
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('hours_per_week'),
                            }"
                            @keyup="delete errors.hours_per_week"
                            :title="errors.hours_per_week"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="hours_per_week-error"
                          class="form-text invalid-feedback"
                          >{{ errors.hours_per_week }}</small
                        >
                      </div>
                    </div>
                    <!-- hours_per_week : END -->

                    <!-- monthly_gross : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="monthly_gross">Monthly Gross</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="monthly_gross"
                            type="text"
                            placeholder="Monthly Gross"
                            v-model="
                              individual_credit_application.monthly_gross
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('monthly_gross'),
                            }"
                            @keyup="delete errors.monthly_gross"
                            :title="errors.monthly_gross"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="monthly_gross-error"
                          class="form-text invalid-feedback"
                          >{{ errors.monthly_gross }}</small
                        >
                      </div>
                    </div>
                    <!-- monthly_gross : END -->

                    <!-- annual_gross : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="annual_gross">Annual Gross</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="annual_gross"
                            type="text"
                            placeholder="Annual Gross"
                            v-model="individual_credit_application.annual_gross"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('annual_gross'),
                            }"
                            @keyup="delete errors.annual_gross"
                            :title="errors.annual_gross"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="annual_gross-error"
                          class="form-text invalid-feedback"
                          >{{ errors.annual_gross }}</small
                        >
                      </div>
                    </div>
                    <!-- annual_gross : END -->
                  </div>
                </div>
                <!-- Primary Employment & Income : END -->

                <!-- Secondary Employment & Income : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Previous Employment & Income</h5>
                  </div>
                  <div class="row">
                    <!-- previous_employment_type : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="previous_employment_type"
                          >Employment Type</label
                        >
                        <fieldset class="" style="position: relative">
                          <input
                            id="previous_employment_type"
                            type="text"
                            placeholder="Employment Type"
                            v-model="
                              individual_credit_application.previous_employment_type
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_employment_type'
                              ),
                            }"
                            @keyup="delete errors.previous_employment_type"
                            :title="errors.previous_employment_type"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="previous_employment_type-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_employment_type }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_employment_type : END -->

                    <!-- previous_job_title : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="previous_job_title">Job Title</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="previous_job_title"
                            type="text"
                            placeholder="Job Title"
                            v-model="
                              individual_credit_application.previous_job_title
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('previous_job_title'),
                            }"
                            @keyup="delete errors.previous_job_title"
                            :title="errors.previous_job_title"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-briefcase-alt-2 font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="previous_job_title-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_job_title }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_job_title : END -->

                    <!-- previous_occupation : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="previous_occupation">Occupation</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="previous_occupation"
                            type="text"
                            placeholder="Occupation"
                            v-model="
                              individual_credit_application.previous_occupation
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_occupation'
                              ),
                            }"
                            @keyup="delete errors.previous_occupation"
                            :title="errors.previous_occupation"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-user-badge font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="previous_occupation-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_occupation }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_occupation : END -->

                    <!-- previous_employer_name : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="previous_employer_name"
                          >Employer Name</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="previous_employer_name"
                            type="text"
                            placeholder="Employer Name"
                            v-model="
                              individual_credit_application.previous_employer_name
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_employer_name'
                              ),
                            }"
                            @keyup="delete errors.previous_employer_name"
                            :title="errors.previous_employer_name"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-id-card font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="previous_employer_name-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_employer_name }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_employer_name : END -->

                    <!-- previous_employer_work_phone : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="previous_employer_work_phone"
                          >Work Phone</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="previous_employer_work_phone"
                            type="tel"
                            placeholder="Work Phone"
                            v-model="
                              individual_credit_application.previous_employer_work_phone
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_employer_work_phone'
                              ),
                            }"
                            @keyup="delete errors.previous_employer_work_phone"
                            :title="errors.previous_employer_work_phone"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-phone font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="previous_employer_work_phone-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_employer_work_phone }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_employer_work_phone : END -->

                    <!-- previous_employer_work_phone_ext : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="previous_employer_work_phone_ext"
                          >Work Ext</label
                        >
                        <fieldset class="" style="position: relative">
                          <input
                            id="previous_employer_work_phone_ext"
                            type="tel"
                            placeholder="Work Ext"
                            v-model="
                              individual_credit_application.previous_employer_work_phone_ext
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_employer_work_phone_ext'
                              ),
                            }"
                            @keyup="
                              delete errors.previous_employer_work_phone_ext
                            "
                            :title="errors.previous_employer_work_phone_ext"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="previous_employer_work_phone_ext-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_employer_work_phone_ext }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_employer_work_phone_ext : END -->

                    <!-- previous_employment_period_years : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="previous_employment_period_years"
                          >Employment Years</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="previous_employment_period_years"
                            type="text"
                            placeholder="Years"
                            v-model="
                              individual_credit_application.previous_employment_period_years
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_employment_period_years'
                              ),
                            }"
                            @keyup="
                              delete errors.previous_employment_period_years
                            "
                            :title="errors.previous_employment_period_years"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-calendar font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="previous_employment_period_years-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_employment_period_years }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_employment_period_years : END -->

                    <!-- previous_employment_period_months : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="previous_employment_period_months"
                          >Employment Months</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="previous_employment_period_months"
                            type="text"
                            placeholder="Months"
                            v-model="
                              individual_credit_application.previous_employment_period_months
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_employment_period_months'
                              ),
                            }"
                            @keyup="
                              delete errors.previous_employment_period_months
                            "
                            :title="errors.previous_employment_period_months"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-calendar font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="previous_employment_period_months-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_employment_period_months }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_employment_period_months : END -->
                    <div class="w-100"></div>

                    <div class="col-xs-12 col-md-12">
                      <suggest-location
                        :elementId="'previous_employer_address'"
                      ></suggest-location>
                    </div>

                    <!-- previous_employer_address : START -->
                    <div class="col-xs-12 col-md-12">
                      <div class="form-group">
                        <label for="previous_employer_address">Address</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="previous_employer_address"
                            type="text"
                            placeholder="Address"
                            v-model="
                              individual_credit_application.previous_employer_address
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_employer_address'
                              ),
                            }"
                            @keyup="delete errors.previous_employer_address"
                            :title="errors.previous_employer_address"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-directions font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="previous_employer_address-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_employer_address }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_employer_address : END -->

                    <!-- previous_employer_city : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="previous_employer_city">City</label>
                        <fieldset class="" style="position: relative">
                          <input
                            id="previous_employer_city"
                            type="text"
                            placeholder="City"
                            v-model="
                              individual_credit_application.previous_employer_city
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_employer_city'
                              ),
                            }"
                            @keyup="delete errors.previous_employer_city"
                            :title="errors.previous_employer_city"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="previous_employer_city-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_employer_city }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_employer_city : END -->

                    <!-- previous_employer_province : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="previous_employer_province"
                          >Province / State</label
                        >
                        <fieldset class="" style="position: relative">
                          <input
                            id="previous_employer_province"
                            type="text"
                            placeholder="Province / State"
                            v-model="
                              individual_credit_application.previous_employer_province
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_employer_province'
                              ),
                            }"
                            @keyup="delete errors.previous_employer_province"
                            :title="errors.previous_employer_province"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="previous_employer_province-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_employer_province }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_employer_province : END -->

                    <!-- previous_employer_postal_code : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="previous_employer_postal_code"
                          >Postal Code / ZIP</label
                        >
                        <fieldset class="" style="position: relative">
                          <input
                            id="previous_employer_postal_code"
                            type="text"
                            placeholder="Postal Code / ZIP"
                            v-model="
                              individual_credit_application.previous_employer_postal_code
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_employer_postal_code'
                              ),
                            }"
                            @keyup="delete errors.previous_employer_postal_code"
                            :title="errors.previous_employer_postal_code"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="previous_employer_postal_code-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_employer_postal_code }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_employer_postal_code : END -->

                    <!-- previous_employer_country : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="previous_employer_country">Country</label>
                        <fieldset class="" style="position: relative">
                          <input
                            id="previous_employer_country"
                            type="text"
                            placeholder="Country"
                            v-model="
                              individual_credit_application.previous_employer_country
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_employer_country'
                              ),
                            }"
                            @keyup="delete errors.previous_employer_country"
                            :title="errors.previous_employer_country"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="previous_employer_country-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_employer_country }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_employer_country : END -->

                    <!-- previous_rate_per_hour : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="previous_rate_per_hour"
                          >Rate Per Hour</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="previous_rate_per_hour"
                            type="text"
                            placeholder="Rate Per Hour"
                            v-model="
                              individual_credit_application.previous_rate_per_hour
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_rate_per_hour'
                              ),
                            }"
                            @keyup="delete errors.previous_rate_per_hour"
                            :title="errors.previous_rate_per_hour"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="previous_rate_per_hour-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_rate_per_hour }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_rate_per_hour : END -->

                    <!-- previous_hours_per_week : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="previous_hours_per_week"
                          >Hours Per Week</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="previous_hours_per_week"
                            type="text"
                            placeholder="Rate Per Hour"
                            v-model="
                              individual_credit_application.previous_hours_per_week
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_hours_per_week'
                              ),
                            }"
                            @keyup="delete errors.previous_hours_per_week"
                            :title="errors.previous_hours_per_week"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="previous_hours_per_week-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_hours_per_week }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_hours_per_week : END -->

                    <!-- previous_monthly_gross : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="previous_monthly_gross"
                          >Monthly Gross</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="previous_monthly_gross"
                            type="text"
                            placeholder="Monthly Gross"
                            v-model="
                              individual_credit_application.previous_monthly_gross
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_monthly_gross'
                              ),
                            }"
                            @keyup="delete errors.previous_monthly_gross"
                            :title="errors.previous_monthly_gross"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="previous_monthly_gross-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_monthly_gross }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_monthly_gross : END -->

                    <!-- previous_annual_gross : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="previous_annual_gross">Annual Gross</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="previous_annual_gross"
                            type="text"
                            placeholder="Annual Gross"
                            v-model="
                              individual_credit_application.previous_annual_gross
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'previous_annual_gross'
                              ),
                            }"
                            @keyup="delete errors.previous_annual_gross"
                            :title="errors.previous_annual_gross"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="previous_annual_gross-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_annual_gross }}</small
                        >
                      </div>
                    </div>
                    <!-- previous_annual_gross : END -->
                  </div>
                </div>
                <!-- Secondary Employment & Income : END -->

                <!-- Other Source : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Other Income Source</h5>
                  </div>
                  <div class="row">
                    <!-- other_income_source : START -->
                    <div class="col-xs-12 col-md-12">
                      <div class="form-group">
                        <label for="other_income_source">Other Source</label>
                        <fieldset class="" style="position: relative">
                          <input
                            id="other_income_source"
                            type="text"
                            placeholder="Other Source"
                            v-model="
                              individual_credit_application.other_income_source
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'other_income_source'
                              ),
                            }"
                            @keyup="delete errors.other_income_source"
                            :title="errors.other_income_source"
                          />
                          <!-- <div class="form-control-position">
                                  <i class="bx bx-calendar font-medium-3"></i>
                                </div> -->
                        </fieldset>
                        <small
                          id="other_income_source-error"
                          class="form-text invalid-feedback"
                          >{{ errors.other_income_source }}</small
                        >
                      </div>
                    </div>
                    <!-- other_income_source : END -->

                    <!-- other_rate_per_hour : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="other_rate_per_hour">Rate Per Hour</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="other_rate_per_hour"
                            type="text"
                            placeholder="Rate Per Hour"
                            v-model="
                              individual_credit_application.other_rate_per_hour
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'other_rate_per_hour'
                              ),
                            }"
                            @keyup="delete errors.other_rate_per_hour"
                            :title="errors.other_rate_per_hour"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="other_rate_per_hour-error"
                          class="form-text invalid-feedback"
                          >{{ errors.other_rate_per_hour }}</small
                        >
                      </div>
                    </div>
                    <!-- other_rate_per_hour : END -->

                    <!-- other_hours_per_week : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="other_hours_per_week">Hours Per Week</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="other_hours_per_week"
                            type="text"
                            placeholder="Rate Per Hour"
                            v-model="
                              individual_credit_application.other_hours_per_week
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'other_hours_per_week'
                              ),
                            }"
                            @keyup="delete errors.other_hours_per_week"
                            :title="errors.other_hours_per_week"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="other_hours_per_week-error"
                          class="form-text invalid-feedback"
                          >{{ errors.other_hours_per_week }}</small
                        >
                      </div>
                    </div>
                    <!-- other_hours_per_week : END -->

                    <!-- other_monthly_gross : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="other_monthly_gross">Monthly Gross</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="other_monthly_gross"
                            type="text"
                            placeholder="Monthly Gross"
                            v-model="
                              individual_credit_application.other_monthly_gross
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'other_monthly_gross'
                              ),
                            }"
                            @keyup="delete errors.other_monthly_gross"
                            :title="errors.other_monthly_gross"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="other_monthly_gross-error"
                          class="form-text invalid-feedback"
                          >{{ errors.other_monthly_gross }}</small
                        >
                      </div>
                    </div>
                    <!-- other_monthly_gross : END -->

                    <!-- other_annual_gross : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <label for="other_annual_gross">Annual Gross</label>
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="other_annual_gross"
                            type="text"
                            placeholder="Annual Gross"
                            v-model="
                              individual_credit_application.other_annual_gross
                            "
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('other_annual_gross'),
                            }"
                            @keyup="delete errors.other_annual_gross"
                            :title="errors.other_annual_gross"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-dollar-circle font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="other_annual_gross-error"
                          class="form-text invalid-feedback"
                          >{{ errors.other_annual_gross }}</small
                        >
                      </div>
                    </div>
                    <!-- other_annual_gross : END -->
                  </div>
                </div>
                <!-- Other Source : END -->

                <!-- Other details : START -->
                <div class="m-width-s">
                  <div class="card-header border-line-bottom-title">
                    <h5 class="card-title">Other Details</h5>
                  </div>
                  <div class="row">
                    <div class="col-xs-12 col-md-3">
                      <label>Bankrupcy Declared ?</label>
                    </div>
                    <!-- bankrupcy declaration flag : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <fieldset>
                          <div class="input-group">
                            <div class="custom-control custom-switch">
                              <input
                                type="checkbox"
                                class="custom-control-input"
                                id="bankruptcy_declared"
                                name="bankruptcy_declared"
                                v-model="
                                  individual_credit_application.bankruptcy_declared
                                "
                                :true-value="'1'"
                                :false-value="'0'"
                              />
                              <label
                                class="custom-control-label"
                                for="bankruptcy_declared"
                              >
                                <span class="switch-icon-left"> YES</span>
                                <span class="switch-icon-right">NO</span>
                              </label>
                            </div>
                          </div>
                        </fieldset>
                      </div>
                    </div>
                    <!-- bankrupcy declaration flag : END -->
                    <!-- bankrupcy information : START -->
                    <div
                      class="col-xs-12 col-md-6"
                      v-show="
                        individual_credit_application.bankruptcy_declared == '1'
                      "
                    >
                      <div class="form-group">
                        <label for="bankruptcy_information"
                          >Bankrupcy Information</label
                        >
                        <textarea
                          id="bankruptcy_information"
                          placeholder="Bankrupcy Information"
                          v-model="
                            individual_credit_application.bankruptcy_information
                          "
                          class="form-control"
                          :class="{
                            'is-invalid': errors.hasOwnProperty(
                              'bankruptcy_information'
                            ),
                          }"
                          @keyup="delete errors.bankruptcy_information"
                          :title="errors.bankruptcy_information"
                        ></textarea>

                        <small
                          id="bankruptcy_information-error"
                          class="form-text invalid-feedback"
                          >{{ errors.bankruptcy_information }}</small
                        >
                      </div>
                    </div>

                    <!-- bankrupcy information : END -->
                    <div class="w-100"></div>
                    <div class="col-xs-12 col-md-3">
                      <label>Previous Collections or Write Offs ?</label>
                    </div>

                    <!-- previous_write_off flag : START -->
                    <div class="col-xs-12 col-md-3">
                      <div class="form-group">
                        <fieldset>
                          <div class="input-group">
                            <div class="custom-control custom-switch">
                              <input
                                type="checkbox"
                                class="custom-control-input"
                                id="previous_write_off"
                                name="previous_write_off"
                                v-model="
                                  individual_credit_application.previous_write_off
                                "
                                :true-value="'1'"
                                :false-value="'0'"
                              />
                              <label
                                class="custom-control-label"
                                for="previous_write_off"
                              >
                                <span class="switch-icon-left"> YES</span>
                                <span class="switch-icon-right">NO</span>
                              </label>
                            </div>
                          </div>
                        </fieldset>
                      </div>
                    </div>
                    <!-- previous_write_off flag : END -->

                    <!-- previous_write_off_information : START -->
                    <div
                      class="col-xs-12 col-md-6"
                      v-show="
                        individual_credit_application.previous_write_off == '1'
                      "
                    >
                      <div class="form-group">
                        <label for="previous_write_off_information"
                          >Details</label
                        >
                        <textarea
                          id="previous_write_off_information"
                          placeholder="Previous Collections or Write Off Details"
                          v-model="
                            individual_credit_application.previous_write_off_information
                          "
                          class="form-control"
                          :class="{
                            'is-invalid': errors.hasOwnProperty(
                              'previous_write_off_information'
                            ),
                          }"
                          @keyup="delete errors.previous_write_off_information"
                          :title="errors.previous_write_off_information"
                        ></textarea>

                        <small
                          id="previous_write_off_information-error"
                          class="form-text invalid-feedback"
                          >{{ errors.previous_write_off_information }}</small
                        >
                      </div>
                    </div>

                    <!-- previous_write_off_information : END -->

                    <div class="w-100"></div>
                    <!-- financial_institution : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="financial_institution"
                          >Financial Institution</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="financial_institution"
                            type="text"
                            placeholder="Financial Institution"
                            v-model="
                              individual_credit_application.financial_institution
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'financial_institution'
                              ),
                            }"
                            @keyup="delete errors.financial_institution"
                            :title="errors.financial_institution"
                          />
                          <div class="form-control-position">
                            <i class="bx bxs-landmark font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="financial_institution-error"
                          class="form-text invalid-feedback"
                          >{{ errors.financial_institution }}</small
                        >
                      </div>
                    </div>
                    <!-- financial_institution : END -->

                    <!-- desired_monthly_payment : START -->
                    <div class="col-xs-12 col-md-4">
                      <div class="form-group">
                        <label for="desired_monthly_payment"
                          >Desired Monthly Payment</label
                        >
                        <fieldset
                          class="has-icon-left"
                          style="position: relative"
                        >
                          <input
                            id="desired_monthly_payment"
                            type="text"
                            placeholder="Desired Monthly Payment"
                            v-model="
                              individual_credit_application.desired_monthly_payment
                            "
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'desired_monthly_payment'
                              ),
                            }"
                            @keyup="delete errors.desired_monthly_payment"
                            :title="errors.desired_monthly_payment"
                          />
                          <div class="form-control-position">
                            <i class="bx bx-money font-medium-3"></i>
                          </div>
                        </fieldset>
                        <small
                          id="desired_monthly_payment-error"
                          class="form-text invalid-feedback"
                          >{{ errors.desired_monthly_payment }}</small
                        >
                      </div>
                    </div>
                    <!-- desired_monthly_payment : END -->
                  </div>
                </div>
                <!-- Other details : END -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import Toast from "../../../library/Toast";
import Inputmask from "inputmask";
import SuggestLocation from "../../WebComponents/SuggestLocation.vue";

export default {
  props: {
    customer_type: {
      required: true,
    },
    customer_loaded: {
      required: true,
    },
  },
  watch: {
    currentIncome: function (newVal, oldVal) {},
    previousIncome: function (newVal, oldVal) {},
    otherIncome: function (newVal, oldVal) {},
    customer_type: function (newVal, oldVal) {
      if (newVal != oldVal && this.credit_application_id != null) {
        this.request_type = this.request_type == "create" ? "update" : "create";
      }
    },
    customer_loaded: function (newVal, oldVal) {
      if (newVal) {
        this.getCreditApplication();
      }
    },
  },
  name: "CreditApplication",
  components: {
    Inputmask,
    SuggestLocation,
  },
  data() {
    return {
      credit_application_id: null,
      request_type: "create",
      errors: [],
      company_credit_application: {
        years_at_present_address: "",
        business_type: "",
        number_of_employees: "",
        years_in_business: "",
        accountant_firm: "",
        accountant_personal: "",
        accountant_phone: "",
        accountant_phone_ext: "",
        principal_owner: "",
        officer_name: "",
        officer_position: "",
        ownership_percentage: "",
        officer_phone: "",
        officer_phone_ext: "",
        officer_address: "",
        bank_name: "",
        bank_number: "",
        bank_account_number: "",
        bank_account_type: "",
        bank_branch_number: "",
        bank_contact_personal: "",
        bank_phone: "",
        bank_phone_ext: "",
        bank_street_address: "",
        bank_apt: "",
        bank_city: "",
        bank_province: "",
        bank_postal_code: "",
        bank_country: "",
        is_encrypted: 0,
      },
      individual_credit_application: {
        salutation: "",
        gender: "",
        marital_status: "",
        sin: "",
        residence_ownership: "Own",
        market_value: "0.00",
        mortgage_amount: "0.00",
        monthly_payment: "0.00",
        mortgage_bank: "",
        years_at_present_address: "",
        property_owner: "",
        employment_type: "",
        job_title: "",
        occupation: "",
        employer_name: "",
        employer_work_phone: "",
        employer_work_phone_ext: "",
        employment_period_years: "",
        employment_period_months: "",
        employer_address: "",
        employer_city: "",
        employer_province: "",
        employer_postal_code: "",
        employer_country: "",
        rate_per_hour: "0.00",
        hours_per_week: "0",
        monthly_gross: "0.00",
        annual_gross: "0.00",

        previous_employment_type: "",
        previous_job_title: "",
        previous_occupation: "",
        previous_employer_name: "",
        previous_employer_work_phone: "",
        previous_employer_work_phone_ext: "",
        previous_employment_period_years: "",
        previous_employment_period_months: "",
        previous_employer_address: "",
        previous_employer_city: "",
        previous_employer_province: "",
        previous_employer_postal_code: "",
        previous_employer_country: "",
        previous_rate_per_hour: "0.00",
        previous_hours_per_week: "0",
        previous_monthly_gross: "0.00",
        previous_annual_gross: "0.00",

        other_income_source: "",
        other_rate_per_hour: "0.00",
        other_hours_per_week: "0",
        other_monthly_gross: "0.00",
        other_annual_gross: "0.00",

        bankruptcy_declared: "0",
        bankruptcy_information: "",
        previous_write_off: "0",
        previous_write_off_information: "",
        financial_institution: "",
        desired_monthly_payment: "",

        previous_address: "",
        previous_address_city: "",
        previous_address_province: "",
        previous_address_postal_code: "",
        previous_residence_address_period_years: "",
        previous_residence_period_months: "",
        previous_residence_monthly_payment: "0.00",
        is_encrypted: 0,
      },
    };
  },
  computed: {
    updateRequest() {
      return (
        this.$route.params.id &&
        this.$route.name == "customer-update" &&
        this.credit_application_id != null &&
        this.request_type == "update"
      );
    },
    currentIncome: {
      get: function () {
        let rate_per_hour =
          !isNaN(this.individual_credit_application.rate_per_hour) &&
          this.individual_credit_application.rate_per_hour != ""
            ? parseFloat(this.individual_credit_application.rate_per_hour)
            : 0;
        this.individual_credit_application.rate_per_hour = rate_per_hour;

        let hours_per_week =
          !isNaN(this.individual_credit_application.hours_per_week) &&
          this.individual_credit_application.hours_per_week != ""
            ? parseInt(this.individual_credit_application.hours_per_week)
            : 0;
        this.individual_credit_application.hours_per_week = hours_per_week;

        let annual_gross = rate_per_hour * hours_per_week * 52;
        this.individual_credit_application.annual_gross =
          annual_gross.toFixed(2);

        let monthly_gross = annual_gross / 12;
        this.individual_credit_application.monthly_gross =
          monthly_gross.toFixed(2);
      },
    },
    previousIncome: {
      get: function () {
        let rate_per_hour =
          !isNaN(this.individual_credit_application.previous_rate_per_hour) &&
          this.individual_credit_application.previous_rate_per_hour != ""
            ? parseFloat(
                this.individual_credit_application.previous_rate_per_hour
              )
            : 0;
        this.individual_credit_application.previous_rate_per_hour =
          rate_per_hour;

        let hours_per_week =
          !isNaN(this.individual_credit_application.previous_hours_per_week) &&
          this.individual_credit_application.previous_hours_per_week != ""
            ? parseInt(
                this.individual_credit_application.previous_hours_per_week
              )
            : 0;
        this.individual_credit_application.previous_hours_per_week =
          hours_per_week;

        let annual_gross = rate_per_hour * hours_per_week * 52;
        this.individual_credit_application.previous_annual_gross =
          annual_gross.toFixed(2);

        let monthly_gross = annual_gross / 12;
        this.individual_credit_application.previous_monthly_gross =
          monthly_gross.toFixed(2);
      },
    },
    otherIncome: {
      get: function () {
        let rate_per_hour =
          !isNaN(this.individual_credit_application.other_rate_per_hour) &&
          this.individual_credit_application.other_rate_per_hour != ""
            ? parseFloat(this.individual_credit_application.other_rate_per_hour)
            : 0;
        this.individual_credit_application.other_rate_per_hour = rate_per_hour;

        let hours_per_week =
          !isNaN(this.individual_credit_application.other_hours_per_week) &&
          this.individual_credit_application.other_hours_per_week != ""
            ? parseInt(this.individual_credit_application.other_hours_per_week)
            : 0;
        this.individual_credit_application.other_hours_per_week =
          hours_per_week;

        let annual_gross = rate_per_hour * hours_per_week * 52;
        this.individual_credit_application.other_annual_gross =
          annual_gross.toFixed(2);

        let monthly_gross = annual_gross / 12;
        this.individual_credit_application.other_monthly_gross =
          monthly_gross.toFixed(2);
      },
    },
  },
  methods: {
    save() {
      const vm = this;
      let formData = new FormData();

      if (parseInt(vm.customer_type) == 0) {
        for (const key in this.individual_credit_application) {
          if (
            this.individual_credit_application.hasOwnProperty(key) &&
            this.individual_credit_application[key] != null
          ) {
            formData.append(key, this.individual_credit_application[key]);
          }
        }
      } else {
        for (const key in this.company_credit_application) {
          if (
            this.company_credit_application.hasOwnProperty(key) &&
            this.company_credit_application[key] != null
          ) {
            formData.append(key, this.company_credit_application[key]);
          }
        }
      }

      let request_url = window.location.origin;
      let update_request = this.updateRequest;
      //success message setting
      let success_message = update_request
        ? `Credit Application for the customer has been updated.`
        : `New Credit Application for the customer has been created.`;

      //api setting
      request_url = update_request
        ? `${request_url}/api/business/customers/${this.$route.params.id}/credit-application/update/${this.credit_application_id}`
        : `${request_url}/api/business/customers/${this.$route.params.id}/credit-application/create`;

      //message during request
      let request_message = update_request
        ? `Updating customer credit application ...`
        : `Creating new customer credit application ...`;

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
          vm.getCreditApplication();

          setTimeout(function () {
            Event.fire("save_credit_application_complete", data);
          }, 500);
        })
        .catch(function (error) {
          let update_request = vm.updateRequest;
          let error_message = update_request
            ? "There were errors while updating the customer credit application."
            : "There were some errors while creating new customer credit application.";

          vm.$data.errors = error.response.data;

          toast.fail(error_message);
          toast.close();
          Event.fire("save_complete", null);
        });
    },
    getCreditApplication() {
      const customer_id = this.$route.params.id;
      const vm = this;
      axios({
        method: "get",
        url:
          window.location.origin +
          "/api/business/customers/" +
          customer_id +
          "/credit-application",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          if (data.hasOwnProperty("id")) {
            vm.credit_application_id = data["id"];
            vm.request_type = "update";
          }

          if (parseInt(vm.customer_type) == 0) {
            for (const key in this.individual_credit_application) {
              if (
                this.individual_credit_application.hasOwnProperty(key) &&
                data.hasOwnProperty(key)
              ) {
                this.individual_credit_application[key] = data[key];
              }
            }
          } else {
            for (const key in this.company_credit_application) {
              if (
                this.company_credit_application.hasOwnProperty(key) &&
                data.hasOwnProperty(key)
              ) {
                this.company_credit_application[key] = data[key];
              }
            }
          }
          setTimeout(() => {
            data.hasOwnProperty("monthly_gross")
              ? (vm.individual_credit_application["monthly_gross"] =
                  data["monthly_gross"])
              : null;
            data.hasOwnProperty("annual_gross")
              ? (vm.individual_credit_application["annual_gross"] =
                  data["annual_gross"])
              : null;

            data.hasOwnProperty("previous_monthly_gross")
              ? (vm.individual_credit_application["previous_monthly_gross"] =
                  data["previous_monthly_gross"])
              : null;
            data.hasOwnProperty("previous_annual_gross")
              ? (vm.individual_credit_application["previous_annual_gross"] =
                  data["previous_annual_gross"])
              : null;

            data.hasOwnProperty("other_monthly_gross")
              ? (vm.individual_credit_application["other_monthly_gross"] =
                  data["other_monthly_gross"])
              : null;
            data.hasOwnProperty("other_annual_gross")
              ? (vm.individual_credit_application["other_annual_gross"] =
                  data["other_annual_gross"])
              : null;
          }, 500);
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
  },
  mounted() {
    const vm = this;
    Event.listen("save_credit_application", (data) => {
      vm.save();
    });

    Inputmask({
      mask: "(999) 999-9999",
      autoUnmask: true,
      onBeforePaste: function (pastedValue, opts) {
        pastedValue = pastedValue.toLowerCase();
        return pastedValue.replace(/\D/g, "");
      },
    }).mask(document.getElementById("accountant_phone"));

    Inputmask({
      mask: "(999) 999-9999",
      autoUnmask: true,
      onBeforePaste: function (pastedValue, opts) {
        pastedValue = pastedValue.toLowerCase();
        return pastedValue.replace(/\D/g, "");
      },
    }).mask(document.getElementById("officer_phone"));

    Inputmask({
      mask: "(999) 999-9999",
      autoUnmask: true,
      onBeforePaste: function (pastedValue, opts) {
        pastedValue = pastedValue.toLowerCase();
        return pastedValue.replace(/\D/g, "");
      },
    }).mask(document.getElementById("bank_phone"));

    Inputmask({
      mask: "(999) 999-9999",
      autoUnmask: true,
      onBeforePaste: function (pastedValue, opts) {
        pastedValue = pastedValue.toLowerCase();
        return pastedValue.replace(/\D/g, "");
      },
    }).mask(document.getElementById("employer_work_phone"));

    Inputmask({
      mask: "(999) 999-9999",
      autoUnmask: true,
      onBeforePaste: function (pastedValue, opts) {
        pastedValue = pastedValue.toLowerCase();
        return pastedValue.replace(/\D/g, "");
      },
    }).mask(document.getElementById("previous_employer_work_phone"));

    Event.listen("autopopulate_location_previous_address", (data) => {
      if (data.hasOwnProperty("place")) {
        vm.$data.individual_credit_application.previous_address_postal_code =
          data.place.postal_code;
        vm.$data.individual_credit_application.previous_address_province =
          data.place.province;
        vm.$data.individual_credit_application.previous_address_city =
          data.place.city;

        if (data.place.street !== "") {
          vm.$data.individual_credit_application.previous_address = `${data.place.street_number} ${data.place.street}`;
        } else {
          vm.$data.individual_credit_application.previous_address =
            data.place.street;
        }
      }
    });

    Event.listen("autopopulate_location_previous_employer_address", (data) => {
      if (data.hasOwnProperty("place")) {
        vm.$data.individual_credit_application.previous_employer_country =
          data.place.country;
        vm.$data.individual_credit_application.previous_employer_postal_code =
          data.place.postal_code;
        vm.$data.individual_credit_application.previous_employer_province =
          data.place.province;
        vm.$data.individual_credit_application.previous_employer_city =
          data.place.city;

        if (data.place.street !== "") {
          vm.$data.individual_credit_application.previous_employer_address = `${data.place.street_number} ${data.place.street}`;
        } else {
          vm.$data.individual_credit_application.previous_employer_address =
            data.place.street;
        }
      }
    });

    Event.listen("autopopulate_location_employer_address", (data) => {
      if (data.hasOwnProperty("place")) {
        vm.$data.individual_credit_application.employer_country =
          data.place.country;
        vm.$data.individual_credit_application.employer_postal_code =
          data.place.postal_code;
        vm.$data.individual_credit_application.employer_province =
          data.place.province;
        vm.$data.individual_credit_application.employer_city = data.place.city;

        if (data.place.street !== "") {
          vm.$data.individual_credit_application.employer_address = `${data.place.street_number} ${data.place.street}`;
        } else {
          vm.$data.individual_credit_application.employer_address =
            data.place.street;
        }
      }
    });

    Event.listen("autopopulate_location_bank_address", (data) => {
      if (data.hasOwnProperty("place")) {
        vm.$data.company_credit_application.bank_country =
          data.place.country;
        vm.$data.company_credit_application.bank_postal_code =
          data.place.postal_code;
        vm.$data.company_credit_application.bank_province = data.place.province;
        vm.$data.company_credit_application.bank_city = data.place.city;
        vm.$data.company_credit_application.bank_apt = "";

        if (data.place.street !== "") {
          vm.$data.company_credit_application.bank_street_address = `${data.place.street_number} ${data.place.street}`;
        } else {
          vm.$data.company_credit_application.bank_street_address =
            data.place.street;
        }
      }
    });
  },
  beforeDestroy() {
    Event.vue.$off("save_credit_application_complete", null);
    Event.vue.$off("autopopulate_location_previous_address", null);
    Event.vue.$off("autopopulate_location_previous_employer_address", null);
    Event.vue.$off("autopopulate_location_employer_address", null);
    Event.vue.$off("autopopulate_location_bank_address", null);
  },
};
</script>
