<template>
  <!-- BEGIN: Content-->
  <div
    class="app-content content"
    v-if="app_metadata.organization == 'zopdealer'"
  >
    <div class="content-overlay"></div>
    <!-- form layout section start -->
    <div class="content-body">
      <form class="webapp-form needs-validation">
        <section id="add-lead-form">
          <div
            class="border-bottom mt-3 bg-white header-navbar fixed-top"
            style="background-color: transparent"
          >
            <div class="content-wrapper pt-0 pb-1 mt-0">
              <div class="card shadow-none mb-0">
                <div class="row">
                  <div class="col-md-4 col-12">
                    <h3 class="content-header-title float-left mt-2 mb-0">
                      {{ title }}
                    </h3>
                  </div>
                  <div class="col-md-8 col-12">
                    <ul
                      class="
                        ml-0
                        mt-1
                        list-group list-group-horizontal
                        text-right
                      "
                    >
                      <li class="d-inline-block">
                        <div
                          class="btn-feild-box"
                          style="top: 16px; right: 15px"
                        >
                          <ul>
                            <li class="d-inline-block">
                              <button
                                type="reset"
                                class="btn btn-light-secondary mr-1"
                              >
                                Cancel
                              </button>
                              <button
                                v-on:click.prevent="save()"
                                button
                                type="submit"
                                class="btn btn-primary"
                              >
                                Save
                              </button>
                            </li>
                            <li class="d-inline-block mr-1"></li>
                          </ul>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Template Selection for prefilling Block : OPEN -->
          <div v-show="showPrefillBlock" class="content-wrapper mt-7 pt-1">
            <div class="row">
              <div class="col-md-12 col-12">
                <div class="pt-1 pb-1 border-bottom">
                  <div class="row">
                    <div class="col-md-3 col-xs-12">
                      <label
                        >Select Lead Template
                        <sub class="fixed-color-set">*</sub></label
                      >
                    </div>
                    <div class="col-md-3 col-xs-12 form-group">
                      <fieldset>
                        <div class="input-group">
                          <select
                            class="custom-select form-control mr-1"
                            v-model="select_id"
                            @change.prevent="
                              showLeadTemplateFormWithElements(
                                $event.target.value,
                                true
                              )
                            "
                            id="select_lead"
                            name="select_lead"
                          >
                            <option value="" selected>
                              -- Select Lead Template --
                            </option>
                            <option
                              v-for="i in lead_templates"
                              :key="`leads_` + i.id"
                              :value="i.id"
                            >
                              {{ i.name }}
                            </option>
                          </select>
                        </div>
                      </fieldset>
                    </div>
                    <div class="col-md-3 col-xs-12">
                      <fieldset>
                        <div class="input-group">
                          <div
                            class="btn-group"
                            role="group"
                            aria-label="Basic example"
                          >
                            <button
                              type="button"
                              v-show="select_id != ''"
                              class="btn btn-success"
                            >
                              Select
                            </button>
                            <button
                              type="button"
                              @click.prevent="show_prefilling_block = false"
                              class="btn btn-info"
                            >
                              Skip
                            </button>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Template Selection for prefilling Block : END -->
          <div v-show="!showPrefillBlock" class="content-wrapper mt-7 pt-1">
            <div class="row">
              <div class="col-md-12 col-12">
                <div class="pt-1 pb-1 border-bottom">
                  <div class="row">
                    <div class="col-md-3">
                      <label
                        >Template Name
                        <sub class="fixed-color-set">*</sub></label
                      >
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="name"
                            v-model="form.name"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('name'),
                            }"
                            @keydown="delete errors.name"
                            :title="errors.name"
                            class="form-control"
                            placeholder="Template Name"
                            name="name"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Description </label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <textarea
                            name="description"
                            v-model="form.description"
                            class="form-control"
                            placeholder="Description of Template"
                            id="description"
                          ></textarea>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Type of Template</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="type"
                            v-model="form.type"
                            class="form-control"
                            placeholder="Type of template"
                            name="type"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Source <sub class="fixed-color-set">*</sub></label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <select
                            type="text"
                            id="source"
                            v-model="form.source"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('source'),
                            }"
                            @change="delete errors.source"
                            :title="errors.source"
                            class="custom-select form-control mr-1"
                            placeholder="Source"
                            name="source"
                          >
                            <option value="">-- Select A Source --</option>
                            <option
                              :value="source"
                              v-for="source in getLeadSources"
                              :key="source"
                            >
                              {{ source }}
                            </option>
                          </select>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Disclosure </label>
                    </div>
                    <div class="col-md-4 form-group">
                      <quill-editor
                        name="disclosure"
                        ref="myTextEditor"
                        :options="editorOption"
                        v-model="form.disclosure"
                      >
                      </quill-editor>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Recipient Email (Comma-Seperated)</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="recipient_email"
                            v-model="form.recipient_email"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('recipient_email'),
                            }"
                            @keydown="delete errors.recipient_email"
                            :title="errors.recipient_email"
                            placeholder="Recipient Email(s)"
                            name="recipient_email"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Recipient Adf Email (Comma-Seperated)</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="recipient_adf_email"
                            v-model="form.recipient_adf_email"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('recipient_adf_email'),
                            }"
                            @keydown="delete errors.recipient_adf_email"
                            :title="errors.recipient_adf_email"
                            placeholder="Recipient Adf Email(s)"
                            name="recipient_adf_email"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Recipient Phone (Comma-Seperated)</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="recipient_phone"
                            v-model="form.recipient_phone"
                            class="form-control"
                            :class="{
                              'is-invalid':
                                errors.hasOwnProperty('recipient_phone'),
                            }"
                            @keydown="delete errors.recipient_phone"
                            :title="errors.recipient_phone"
                            placeholder="Recipient Number(s), Each 10 Digits."
                            name="recipient_phone"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Custom CSS URL (Comma-Seperated)</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="custom_css"
                            v-model="form.custom_css"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('custom_css'),
                            }"
                            @keydown="delete errors.custom_css"
                            :title="errors.custom_css"
                            placeholder="Custom CSS URL(s)"
                            name="custom_css"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Custom LESS URL (Comma-Seperated)</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="custom_less"
                            v-model="form.custom_less"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('custom_less'),
                            }"
                            @keydown="delete errors.custom_less"
                            :title="errors.custom_less"
                            placeholder="Custom LESS URL(s)"
                            name="custom_less"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Slug <sub class="fixed-color-set">*</sub></label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="slug"
                            v-model="form.slug"
                            class="form-control"
                            :class="{
                              'is-invalid': errors.hasOwnProperty('slug'),
                            }"
                            @keydown="delete errors.slug"
                            :title="errors.slug"
                            placeholder="Slug"
                            name="slug"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Default Terms and Condition</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <div class="custom-control custom-switch">
                            <input
                              type="checkbox"
                              class="custom-control-input"
                              id="default_term_and_conditions"
                              name="default_terms_and_condition"
                              v-model="form.default_terms_and_condition"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label
                              class="custom-control-label"
                              for="default_term_and_conditions"
                            >
                              <span class="switch-icon-left"> ON</span>
                              <span class="switch-icon-right">OFF</span>
                            </label>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Include VOI</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <div class="custom-control custom-switch">
                            <input
                              type="checkbox"
                              class="custom-control-input"
                              id="voi"
                              name="voi"
                              v-model="form.VOI"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label class="custom-control-label" for="voi">
                              <span class="switch-icon-left"> ON</span>
                              <span class="switch-icon-right">OFF</span>
                            </label>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Override with custom template</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <div class="custom-control custom-switch">
                            <input
                              type="checkbox"
                              class="custom-control-input"
                              id="override_custom_template"
                              name="override_custom_template"
                              v-model="form.override_custom_template"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label
                              class="custom-control-label"
                              for="override_custom_template"
                            >
                              <span class="switch-icon-left"> ON</span>
                              <span class="switch-icon-right">OFF</span>
                            </label>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div
                    class="row"
                    v-show="form.override_custom_template == '1'"
                  >
                    <div class="col-md-3">
                      <label>Custom Template Name</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="custom_template"
                            v-model="form.custom_template"
                            class="form-control"
                            placeholder="Custom Template"
                            name="custom_template"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <!-- Email Template Variables -->
                  <div class="row">
                    <div class="col-md-3">
                      <label>Auto Reply</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <div class="custom-control custom-switch">
                            <input
                              type="checkbox"
                              class="custom-control-input"
                              id="auto_reply"
                              name="auto_reply"
                              v-model="form.auto_reply"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label
                              class="custom-control-label"
                              for="auto_reply"
                            >
                              <span class="switch-icon-left"> ON</span>
                              <span class="switch-icon-right">OFF</span>
                            </label>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row" v-show="form.auto_reply == '1'">
                    <div class="col-md-3">
                      <label>Auto Reply Template</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="auto_reply_template"
                            v-model="form.auto_reply_template"
                            class="form-control"
                            placeholder="Auto Reply Template"
                            name="auto_reply_template"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label
                        >Admin Reply Template
                        <sub data-v-24c0faff="" class="fixed-color-set"
                          >*</sub
                        ></label
                      >
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <input
                            type="text"
                            id="admin_reply_template"
                            v-model="form.admin_reply_template"
                            :class="{
                              'is-invalid': errors.hasOwnProperty(
                                'admin_reply_template'
                              ),
                            }"
                            @keydown="delete errors.admin_reply_template"
                            :title="errors.admin_reply_template"
                            class="form-control"
                            placeholder="Admin Reply Template"
                            name="admin_reply_template"
                          />
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="row" v-show="form.auto_reply == '1'">
                    <div class="col-md-3">
                      <label>Override Auto Reply Content</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <div class="custom-control custom-switch">
                            <input
                              type="checkbox"
                              class="custom-control-input"
                              id="override_auto_reply_content"
                              name="override_auto_reply_content"
                              v-model="form.override_auto_reply_content"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <label
                              class="custom-control-label"
                              for="override_auto_reply_content"
                            >
                              <span class="switch-icon-left"> ON</span>
                              <span class="switch-icon-right">OFF</span>
                            </label>
                          </div>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div
                    class="row"
                    v-show="form.override_auto_reply_content == '1'"
                    style="padding-bottom: 5em"
                  >
                    <div class="col-md-3">
                      <label>Auto Reply Content</label>
                    </div>
                    <div class="col-md-4 form-group">
                      <fieldset>
                        <div class="input-group">
                          <quill-editor
                            id="auto_reply_content"
                            v-model="form.auto_reply_content"
                            :options="editorOption"
                            placeholder="Auto Reply Content"
                            name="auto_reply_content"
                          ></quill-editor>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <!-- Email Template Variables : END -->
                  <div class="row">
                    <div class="col-12">
                      <button
                        @click.prevent="addNewFormElement()"
                        class="btn btn-primary"
                        style="float: right"
                      >
                        <i class="bx bxs-plus-circle"> Add Element</i>
                      </button>
                    </div>
                  </div>
                  <hr class="row" />
                  <div class="row">
                    <h5 class="col-xs-12">Form Elements</h5>

                    <!-- Form Element Builer : OPEN -->
                    <div
                      class="row"
                      v-for="i in total_dom_elements"
                      :key="'form_element_' + i"
                      :id="'form_element_' + i"
                    >
                      <div class="col-md-1 col-xs-12">
                        <div class="col-xs-12" style="padding: 0.1em">
                          <button
                            v-if="i - 1 !== 0"
                            @click.prevent="moveFormElement(i - 1, 'down')"
                            class="btn btn-info"
                          >
                            <i
                              style="font-weight: 800"
                              class="bx bx-up-arrow"
                              aria-hidden="true"
                            ></i>
                          </button>
                        </div>
                        <div class="col-xs-12" style="padding: 0.1em">
                          <button
                            v-if="i != total_dom_elements"
                            @click.prevent="moveFormElement(i - 1, 'up')"
                            class="btn btn-info"
                          >
                            <i
                              style="font-weight: 800"
                              class="bx bx-down-arrow"
                              aria-hidden="true"
                            ></i>
                          </button>
                        </div>
                        <div class="col-xs-12" style="padding: 0.1em">
                          <button
                            @click.prevent="removeFormElement(i - 1)"
                            class="btn btn-danger"
                          >
                            <i
                              style="font-weight: 800"
                              class="bx bx-trash-alt"
                            ></i>
                          </button>
                        </div>
                      </div>
                      <div class="col-md-11 col-xs-12">
                        <div class="pt-1 pb-1 border-bottom">
                          <div class="row">
                            <div class="col-md-3 col-xs-12 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <input
                                    type="text"
                                    class="form-control"
                                    @keydown="
                                      delete errors['dom_elements'][i - 1][
                                        'dom_name'
                                      ]
                                    "
                                    @change="autocompleteFields(i - 1)"
                                    v-model="form.dom_elements[i - 1].dom_label"
                                    placeholder="Label *"
                                    name="dom_label[]"
                                  />
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-3 col-xs-12 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <input
                                    type="text"
                                    class="form-control"
                                    v-model="
                                      form.dom_elements[i - 1].dom_placeholder
                                    "
                                    placeholder="Placeholder"
                                    name="dom_placeholder[]"
                                  />
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-3 col-xs-12 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                      'is-invalid':
                                        typeof errors['dom_elements'] !=
                                          'undefined' &&
                                        typeof errors['dom_elements'][i - 1] !=
                                          'undefined' &&
                                        errors['dom_elements'][i - 1] != null &&
                                        typeof errors['dom_elements'][i - 1][
                                          'dom_name'
                                        ] != 'undefined',
                                    }"
                                    @keydown="
                                      delete errors['dom_elements'][i - 1][
                                        'dom_name'
                                      ]
                                    "
                                    v-model="form.dom_elements[i - 1].dom_name"
                                    placeholder="Name *"
                                    name="dom_name[]"
                                  />
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-3 col-xs-12 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <input
                                    :type="
                                      form.dom_elements[i - 1].dom_value_type
                                    "
                                    :disabled="
                                      form.dom_elements[i - 1].dom_type ==
                                        'checkbox' ||
                                      form.dom_elements[i - 1].dom_type ==
                                        'radio' ||
                                      form.dom_elements[i - 1].dom_type ==
                                        'select'
                                      || form.dom_elements[i - 1].dom_type ==
                                        'file'
                                    "
                                    class="form-control"
                                    v-model="form.dom_elements[i - 1].dom_value"
                                    placeholder="Default Value"
                                    name="dom_value[]"
                                  />
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-2 col-xs-12 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.dom_elements[i - 1].dom_id"
                                    placeholder="Dom ID"
                                    name="dom_id[]"
                                  />
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-2 col-xs-12 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <input
                                    type="text"
                                    class="form-control"
                                    v-model="form.dom_elements[i - 1].dom_class"
                                    placeholder="Dom Class"
                                    name="dom_class[]"
                                  />
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-2 col-xs-12 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <select
                                    class="custom-select form-control mr-1"
                                    v-model="form.dom_elements[i - 1].dom_type"
                                    @change="typeChange(i - 1)"
                                    id="status"
                                    name="dom_type[]"
                                  >
                                    <option value="checkbox">Checkbox</option>
                                    <option value="color">Color</option>
                                    <option value="date">Date</option>
                                    <option value="email">Email</option>
                                    <option value="file">File</option>
                                    <option value="number">Number</option>
                                    <option value="largeHeading">
                                      Large Heading
                                    </option>
                                    <option value="smallHeading">
                                      Small Heading
                                    </option>
                                    <option value="radio">Radio</option>
                                    <option value="paragraph">Paragraph</option>
                                    <option value="select">Select</option>
                                    <option value="section">Section</option>
                                    <option value="tel">Phone</option>
                                    <option value="time">Time</option>
                                    <option selected value="text">Text</option>
                                    <option value="textarea">Text Area</option>
                                    <option value="url">Url</option>
                                  </select>
                                </div>
                              </fieldset>
                            </div>
                            <div class="col-md-2 col-xs-12 form-group">
                              <fieldset>
                                <div class="input-group">
                                  <input
                                    type="text"
                                    class="form-control"
                                    v-model="
                                      form.dom_elements[i - 1].dom_validation
                                    "
                                    placeholder="Validation String"
                                    name="dom_validation[]"
                                  />
                                </div>
                              </fieldset>
                            </div>
                          </div>
                          <h6
                            class="col-xs-12"
                            v-if="
                              form.dom_elements[i - 1].dom_type == 'checkbox' ||
                              form.dom_elements[i - 1].dom_type == 'radio' ||
                              form.dom_elements[i - 1].dom_type == 'select'
                            "
                          >
                            Options
                          </h6>
                          <!-- options for the dropdown : START -->
                          <div
                            class="row"
                            v-if="
                              form.dom_elements[i - 1].dom_type == 'checkbox' ||
                              form.dom_elements[i - 1].dom_type == 'radio' ||
                              form.dom_elements[i - 1].dom_type == 'select'
                            "
                          >
                            <div class="col-md-6 col-xs-12">
                              <div
                                class="row"
                                v-for="k in form.dom_elements[i - 1].options
                                  .length"
                                :key="'element_' + i + 'options_' + k"
                              >
                                <div class="col-md-4 col-xs-12 form-group">
                                  <fieldset>
                                    <div class="input-group">
                                      <input
                                        type="text"
                                        v-model="
                                          form.dom_elements[i - 1].options[
                                            k - 1
                                          ].label
                                        "
                                        class="form-control"
                                        placeholder="Label"
                                        name="dom_label[]"
                                      />
                                    </div>
                                  </fieldset>
                                </div>
                                <div class="col-md-4 col-xs-12 form-group">
                                  <fieldset>
                                    <div class="input-group">
                                      <input
                                        type="text"
                                        v-model="
                                          form.dom_elements[i - 1].options[
                                            k - 1
                                          ].value
                                        "
                                        class="form-control"
                                        placeholder="Value"
                                        name="dom_value[]"
                                      />
                                    </div>
                                  </fieldset>
                                </div>
                                <div
                                  class="
                                    col-md-2 col-xs-12
                                    form-check form-check-inline form-group
                                  "
                                >
                                  <input
                                    :true-value="'1'"
                                    :false-value="'0'"
                                    @change="optionsChanging(i - 1, k - 1)"
                                    type="checkbox"
                                    :id="'element_' + i + '_options'"
                                    v-model="
                                      form.dom_elements[i - 1].options[k - 1]
                                        .default
                                    "
                                    class="form-check-input"
                                    placeholder="Value"
                                    :name="'element_' + i + '_options'"
                                  />
                                  <label class="form-check-label" for="default"
                                    >Default</label
                                  ><br />
                                </div>
                                <div
                                  class="
                                    col-md-1 col-xs-12
                                    form-check form-check-inline form-group
                                  "
                                >
                                  <button
                                    @click.prevent="
                                      addOptionsForElement(i - 1, k - 1)
                                    "
                                    class="btn btn-success"
                                    style="margin-right: 1em"
                                  >
                                    <i
                                      style="font-weight: 800"
                                      class="bx bxs-plus-square"
                                      aria-hidden="true"
                                    ></i>
                                  </button>
                                  <button
                                    v-if="k - 1 != 0"
                                    @click.prevent="
                                      removeOptionsForElement(i - 1, k - 1)
                                    "
                                    class="btn btn-danger"
                                  >
                                    <i
                                      style="font-weight: 800"
                                      class="bx bxs-minus-square"
                                      aria-hidden="true"
                                    ></i>
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- options for the dropdown : END -->
                        </div>
                      </div>
                    </div>
                    <!-- Form Element Builder : CLOSE -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </form>
    </div>

    <!-- validation div : OPEN-->
    <div class="row d-none d-sm-block" id="validator_div">
      <h3 class="btn-primary col-12 text-center">
        Validator
        <i
          @click.prevent="changeValidatorDisplay()"
          class="bx dropdown-button"
          :class="[show_validator ? 'bx-chevron-down' : 'bx-chevron-up']"
        ></i>
      </h3>
      <!-- <div class="col-12 form-group">
                <fieldset>
                    <div class="input-group">
                    <input type="text"  class="form-control" placeholder="Field Name" >
                    </div>
                </fieldset>
            </div> -->
      <div class="col-12 form-group" :class="{ 'd-none': !show_validator }">
        <input
          type="checkbox"
          v-model="validator.required_check"
          :true-value="'1'"
          :false-value="'0'"
          id="val_required"
        />
        <label style="vertical-align: super" for="val_required">
          Required
        </label>
      </div>
      <div class="col-12 form-group" :class="{ 'd-none': !show_validator }">
        <input
          type="checkbox"
          v-model="validator.permit_empty_check"
          :true-value="'1'"
          :false-value="'0'"
          id="val_permit_empty"
        />
        <label style="vertical-align: super" for="val_permit_empty">
          Permit Empty
        </label>
      </div>
      <div class="col-12 form-group" :class="{ 'd-none': !show_validator }">
        <input
          type="checkbox"
          v-model="validator.is_natural_check"
          :true-value="'1'"
          :false-value="'0'"
          id="val_is_natural"
        />
        <label style="vertical-align: super" for="val_is_natural">
          Natural Number (0,1,2...)
        </label>
      </div>
      <div class="col-12 form-group" :class="{ 'd-none': !show_validator }">
        <input
          type="checkbox"
          v-model="validator.valid_email_check"
          :true-value="'1'"
          :false-value="'0'"
          id="val_valid_email"
        />
        <label style="vertical-align: super" for="val_valid_email">
          Valid Email
        </label>
      </div>
      <div class="col-12 form-group" :class="{ 'd-none': !show_validator }">
        <input
          type="checkbox"
          v-model="validator.alpha_numeric_check"
          :true-value="'1'"
          :false-value="'0'"
          id="val_alphanumeric"
        />
        <label style="vertical-align: super" for="val_alphanumeric">
          Alphanumeric
        </label>
      </div>
      <div class="col-12 form-group" :class="{ 'd-none': !show_validator }">
        <input
          type="checkbox"
          v-model="validator.alpha_numeric_space_check"
          :true-value="'1'"
          :false-value="'0'"
          id="val_alphanumeric_dash"
        />
        <label style="vertical-align: super" for="val_alphanumeric_dash">
          Alphanumeric Space
        </label>
      </div>
      <div class="col-12 form-group" :class="{ 'd-none': !show_validator }">
        <input
          type="checkbox"
          :true-value="'1'"
          :false-value="'0'"
          v-model="validator.exact_length_check"
          id="val_exact_length"
        />
        <label style="vertical-align: super" for="val_exact_length">
          Exact Length
        </label>
      </div>
      <div
        class="col-12 form-group"
        :class="{ 'd-none': !show_validator }"
        v-show="validator.exact_length_check == '1'"
      >
        <input
          type="number"
          min="0"
          placeholder="Exact Length"
          v-model="validator.exact_length_input"
          value=""
          id="val_exact_length_input"
        />
      </div>
      <div class="col-12 form-group" :class="{ 'd-none': !show_validator }">
        <button
          @click.prevent="generateValidationString()"
          class="btn btn-primary form-control"
        >
          Generate Validations
        </button>
      </div>
      <div
        class="col-12 form-group"
        :class="{ 'd-none': !show_validator }"
        style="background: #fff; word-wrap: anywhere"
        v-show="validation_string != ''"
        v-text="validation_string"
      ></div>
    </div>
    <!-- validation div : END -->
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import Toast from "../../library/Toast";
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";
import { quillEditor } from "vue-quill-editor";

export default {
  name: "leadCreateTemplate",
  props: ["application", "app_metadata"],
  computed: {
    ...mapGetters("leadResponse", ["getLeadSources"]),
    mainApp() {
      return this.application == "main";
    },
    showPrefillBlock() {
      return (
        this.application == "tenant" &&
        this.show_prefilling_block &&
        this.$route.name != "lead-template-update"
      );
    },
  },
  data() {
    return {
      show_validator: true,
      total_dom_elements: 1,
      validation_string: "",
      show_prefilling_block: true,
      select_id: "",
      title: "Create Lead Template",
      errors: [],
      lead_templates: [],
      main_domain: "",
      form: {
        name: "",
        description: "",
        type: "",
        slug: "",
        source: "",
        disclosure: "",
        recipient_email: "",
        recipient_adf_email: "",
        recipient_phone: "",
        custom_css: "",
        custom_less: "",
        default_terms_and_condition: 0,
        VOI: 0,
        override_custom_template: 0,
        custom_template: "",
        auto_reply: 1,
        auto_reply_template: "",
        admin_reply_template: "",
        override_auto_reply_content: "",
        auto_reply_content: "",
        dom_elements: [
          {
            dom_label: "",
            dom_placeholder: "",
            dom_name: "",
            dom_value: "",
            dom_value_type: "text",
            dom_id: "",
            dom_class: "col-md-6 mb-3",
            dom_type: "text",
            dom_validation: "",
            options: [{ label: "", value: "", default: "1" }],
            dom_order: 0,
          },
        ],
      },
      editorOption: {
        modules: {
          toolbar: [
            ["bold", "italic", "underline", "strike"],
            ["blockquote", "code-block"],
            [{ list: "ordered" }, { list: "bullet" }],
            [{ script: "sub" }, { script: "super" }],
            [{ indent: "-1" }, { indent: "+1" }],
            [{ direction: "rtl" }],
            ["link", "image"],
            [{ size: ["small", false, "large", "huge"] }],
            [{ header: [1, 2, 3, 4, 5, 6, false] }],
            [{ font: [] }],
            [{ color: [] }, { background: [] }],
            [{ align: [] }],
            ["clean"],
          ],
        },
      },
      validator: {
        required_check: "0",
        is_natural_check: "0",
        valid_email_check: "0",
        alpha_numeric_check: "0",
        alpha_numeric_space_check: "0",
        permit_empty_check: "0",
        exact_length_check: "0",
        exact_length_input: "",
      },
    };
  },
  components: {
    quillEditor,
  },
  methods: {
    generateValidationString() {
      let val_string = "";
      let val_without_param = [
        "required_check",
        "is_natural_check",
        "valid_email_check",
        "alpha_numeric_check",
        "alpha_numeric_space_check",
        "permit_empty_check",
      ];

      for (const validator_property of val_without_param) {
        if (this.validator[validator_property] == "1") {
          let validation_string_for_property = validator_property.replace(
            "_check",
            ""
          );
          val_string =
            val_string == ""
              ? validation_string_for_property
              : val_string + "|" + validation_string_for_property;
        }
      }

      if (
        this.validator.exact_length_check == "1" &&
        !isNaN(this.validator.exact_length_input) &&
        this.validator.exact_length_input != ""
      ) {
        let validation_string_for_property =
          "exact_length[" + this.validator.exact_length_input + "]";
        val_string =
          val_string == ""
            ? validation_string_for_property
            : val_string + "|" + validation_string_for_property;
      }

      this.validation_string = val_string;
    },
    typeChange(index) {
      if (
        this.form.dom_elements[index].dom_type == "checkbox" ||
        this.form.dom_elements[index].dom_type == "radio" ||
        this.form.dom_elements[index].dom_type == "select"
      ) {
        this.form.dom_elements[index].dom_value = "";
      } else if (this.form.dom_elements[index].dom_type == "file") {
        this.form.dom_elements[index].dom_value_type = "text";
        this.form.dom_elements[index].dom_value = "";
      } else {
        if (this.form.dom_elements[index].dom_type != "textarea") {
          this.form.dom_elements[index].dom_value_type =
            this.form.dom_elements[index].dom_type;
        }
      }
      this.form.dom_elements[index].dom_validation = "";
    },
    addNewFormElement() {
      this.form.dom_elements.push({
        dom_label: "",
        dom_placeholder: "",
        dom_name: "",
        dom_value: "",
        dom_value_type: "text",
        dom_id: "",
        dom_class: "col-md-6 mb-3",
        dom_type: "text",
        dom_validation: "",
        options: [{ label: "", value: "", default: "1" }],
        dom_order: this.total_dom_elements,
      });
      this.total_dom_elements += 1;
    },
    moveFormElement(current_index, direction) {
      let neighbour_index =
        direction == "down" ? current_index - 1 : current_index + 1;
      let temp_dom_element = {};
      for (const property in this.form.dom_elements[current_index]) {
        temp_dom_element[property] =
          this.form.dom_elements[current_index][property];
      }

      for (const property in this.form.dom_elements[neighbour_index]) {
        this.form.dom_elements[current_index][property] =
          this.form.dom_elements[neighbour_index][property];
      }

      this.form.dom_elements[current_index].dom_order = current_index;

      for (const property in temp_dom_element) {
        this.form.dom_elements[neighbour_index][property] =
          temp_dom_element[property];
      }
      this.form.dom_elements[neighbour_index].dom_order = neighbour_index;
    },
    removeFormElement(index) {
      this.form.dom_elements.splice(index, 1);
      this.total_dom_elements -= 1;
    },
    addOptionsForElement(element_index, options_index) {
      let option = { label: "", value: "", default: "0" };
      this.form.dom_elements[element_index]["options"].splice(
        options_index + 1,
        0,
        option
      );
    },
    removeOptionsForElement(element_index, options_index) {
      // if(this.form.dom_elements[element_index]['options'][options_index]['default'] == '1'){
      //     this.form.dom_elements[element_index]['options'][0]['default'] = '1';
      // }

      this.form.dom_elements[element_index]["options"].splice(options_index, 1);
    },
    optionsChanging(element_index, options_index) {
      for (const property in this.form.dom_elements[element_index]["options"]) {
        if (property == options_index) {
          //  this.form.dom_elements[element_index]['options'][property]['default'] = '1';
        } else {
          this.form.dom_elements[element_index]["options"][property][
            "default"
          ] = "0";
        }
      }
    },
    autocompleteFields(element_index) {
      let replacement_string = this.form.dom_elements[element_index][
        "dom_label"
      ].replace(/[^\w\s]/gi, "");
      replacement_string = replacement_string
        .toLowerCase()
        .replace(/\s+/g, "_");

      this.form.dom_elements[element_index]["dom_name"] = replacement_string;
      this.form.dom_elements[element_index]["dom_id"] =
        replacement_string + "_id";
      //this.form.dom_elements[element_index]['dom_class'] = replacement_string + '_class';
    },
    changeValidatorDisplay() {
      this.show_validator = !this.show_validator;
    },
    showLeadTemplateFormWithElements($id, $main_domain = false) {
      const vm = this;
      this.show_prefilling_block = false;
      let url = $main_domain ? this.main_domain : window.location.origin;
      url += "/api/templates/show/" + $id;
      axios({
        method: "get",
        url: url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          for (const property in data["dom_elements"]) {
            data["dom_elements"][property]["options"] = JSON.parse(
              data["dom_elements"][property]["options"]
            );
          }

          vm.$data.form = data;
          vm.$data.total_dom_elements = data["dom_elements"].length;

          //vm.$data.form = data;
        })
        .catch(function (error) {});
    },
    getAllTemplates(main_domain) {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: main_domain + "/api/templates/all?paginate=false",
      }).then((response) => {
        vm.$data.lead_templates = response.data.lead_templates;
      });
    },
    save() {
      const vm = this;
      let formData = new FormData();
      formData.append("name", this.form.name);
      formData.append("description", this.form.description);
      formData.append("type", this.form.type);
      formData.append("slug", this.form.slug);
      formData.append("source", this.form.source);
      formData.append("disclosure", this.form.disclosure);
      formData.append("recipient_email", this.form.recipient_email);
      formData.append("recipient_adf_email", this.form.recipient_adf_email);
      formData.append("recipient_phone", this.form.recipient_phone);
      formData.append("custom_css", this.form.custom_css);
      formData.append("custom_less", this.form.custom_less);
      formData.append(
        "default_terms_and_condition",
        this.form.default_terms_and_condition
      );
      formData.append("VOI", this.form.VOI);
      formData.append(
        "override_custom_template",
        this.form.override_custom_template
      );
      formData.append("custom_template", this.form.custom_template);
      formData.append("auto_reply", this.form.auto_reply);
      formData.append("auto_reply_template", this.form.auto_reply_template);
      formData.append("admin_reply_template", this.form.admin_reply_template);
      formData.append(
        "override_auto_reply_content",
        this.form.override_auto_reply_content
      );
      formData.append("auto_reply_content", this.form.auto_reply_content);

      dom_elements: [
        {
          dom_label: "",
          dom_placeholder: "",
          dom_name: "",
          dom_value: "",
          dom_value_type: "text",
          dom_id: "",
          dom_class: "",
          dom_type: "text",
          dom_validation: "",
          options: [{ label: "", value: "", default: "1" }],
          dom_order: 0,
        },
      ];
      for (const key in this.form.dom_elements) {
        formData.append(
          "dom_label[]",
          this.form.dom_elements[key]["dom_label"]
        );
        formData.append(
          "dom_placeholder[]",
          this.form.dom_elements[key]["dom_placeholder"]
        );
        formData.append("dom_name[]", this.form.dom_elements[key]["dom_name"]);
        formData.append(
          "dom_value[]",
          this.form.dom_elements[key]["dom_value"]
        );
        formData.append(
          "dom_value_type[]",
          this.form.dom_elements[key]["dom_value_type"]
        );
        formData.append("dom_id[]", this.form.dom_elements[key]["dom_id"]);
        formData.append(
          "dom_class[]",
          this.form.dom_elements[key]["dom_class"]
        );
        formData.append("dom_type[]", this.form.dom_elements[key]["dom_type"]);
        formData.append(
          "dom_validation[]",
          this.form.dom_elements[key]["dom_validation"]
        );
        formData.append(
          "options[]",
          JSON.stringify(this.form.dom_elements[key]["options"])
        );
        formData.append(
          "dom_order[]",
          this.form.dom_elements[key]["dom_order"]
        );
      }

      let request_url = window.location.origin;
      let update_request =
        this.$route.params.id && this.$route.name == "lead-template-update";
      let success_message = update_request
        ? `Lead Template Updated Successfully.`
        : `New Template Created.`;

      request_url = update_request
        ? `${request_url}/api/templates/update/${this.$route.params.id}`
        : `${request_url}/api/templates/create`;

      let request_message = update_request
        ? `Updating Lead Template.`
        : `Creating Lead Template`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
        method: "post",
        url: request_url,
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();

          setTimeout(() => {
            if (!update_request) {
              vm.$router.push({ name: "lead-template-list" });
            }
          }, 500);
        })
        .catch(function (error) {
          let update_request =
            vm.$route.params.id && vm.$route.name == "lead-template-update";
          let error_message = update_request
            ? "There were errors while updating the template"
            : "There were some errors while creating new template.";

          vm.$data.errors = error.response.data.errors;

          toast.fail(error_message);
          toast.close();
        });
    },
  },
  created() {},
  mounted() {
    const main_domain = psl.parse(window.location.host);
    this.main_domain = "https://" + main_domain.domain;
    this.getAllTemplates("https://" + main_domain.domain);
    if (this.$route.params.id && this.$route.name == "lead-template-update") {
      this.showLeadTemplateFormWithElements(this.$route.params.id);
      this.title = "Update Template";
    }
  },
};
</script>
<style lang="scss" scoped>
#add-lead-form {
  input:disabled {
    background: #ccc;
  }
}
#validator_div {
  background: #f2eeee;
  width: 300px;
  position: fixed;
  bottom: 1em;
  right: 10em;
  padding: 1em;
  z-index: 999;
  padding-top: 4em;

  h3 {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    line-height: 2em;

    i.dropdown-button {
      float: right;
      margin-top: 0.2em;
      cursor: pointer;
      font-size: 1.4em;
    }
  }
}

#auto_reply_content .ql-container {
  height: 200px;
}
</style>