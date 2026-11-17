<template>
  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <!-- form layout section start -->
    <div class="content-body" id="lead-response-section">
      <form class="webapp-form" @submit.prevent>
        <section id="table-transactions" class="lr-page">
          <!-- Page header : START -->
          <div class="lr-header">
            <div class="lr-header-text">
              <h1 class="lr-title">Leads</h1>
              <p class="lr-subtitle">
                {{ totalRecords }} {{ totalRecords == 1 ? "lead" : "leads" }}
                <span class="lr-subtitle-sep" v-if="getViewTitle">·</span>
                <span v-if="getViewTitle">{{ getViewTitle }}</span>
              </p>
            </div>
            <div class="lr-header-actions">
              <button
                type="button"
                class="lr-icon-btn d-none d-md-inline-flex"
                :class="{ 'is-active': getFilterState }"
                data-toggle="modal"
                data-target="#filters"
                title="Filters"
              >
                <i class="bx bx-filter-alt"></i>
                <span class="lr-dot" v-if="getFilterState"></span>
              </button>
              <button
                type="button"
                class="lr-icon-btn d-none d-md-inline-flex"
                data-toggle="modal"
                data-target="#grid-list"
                title="Modify View"
              >
                <i class="bx bx-columns"></i>
              </button>
              <button
                type="button"
                class="lr-icon-btn"
                :title="getCompact ? 'Comfortable View' : 'Compact View'"
                @click="changeCompact"
              >
                <i class="bx" :class="getCompact ? 'bx-expand' : 'bx-collapse'"></i>
              </button>
              <router-link
                :to="{ name: 'lead-response-create' }"
                class="btn btn-primary lr-primary-btn"
                exact
              >
                <i class="bx bx-plus"></i> Create New Lead
              </router-link>
            </div>
          </div>
          <!-- Page header : END -->

          <!-- Toolbar : START -->
          <!-- v-show (not v-if): mounted() binds a keydown listener to #searchText -->
          <div class="lr-toolbar" v-show="getLeadResponseIds.length == 0">
            <div class="lr-search">
              <i class="bx bx-search"></i>
              <input
                type="text"
                id="searchText"
                v-debounce:1000ms="searchAction"
                v-model="searchString"
                placeholder="Search leads by name, email, phone, stock # or VIN..."
                autocomplete="off"
              />
            </div>

            <!-- Views dropdown -->
            <div class="dropdown lr-select-wrap d-none d-md-block">
              <button
                class="lr-select lr-select-btn"
                type="button"
                id="dropdownMenuButton"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
                :title="getViewTitle"
              >
                <i class="bx bx-layer lr-select-icon"></i>
                <span>{{ selected_lead_response }}</span>
                <i class="bx bx-chevron-down lr-chevron"></i>
              </button>
              <div
                class="dropdown-menu lr-views-menu"
                aria-labelledby="dropdownMenuButton"
              >
                <p class="lr-menu-heading">Default Views</p>
                <a
                  class="dropdown-item lr-view-item"
                  :class="{ 'is-current': lead_response_view.id == getViewId }"
                  v-for="lead_response_view in getDefaultViews"
                  :key="lead_response_view.id"
                  ><span
                    class="flex-fill"
                    @click="changeLeadResponseView(lead_response_view.id)"
                    >{{
                      $helpers.truncate(lead_response_view.header, 25, "...")
                    }}</span
                  ><router-link
                    class="lr-view-edit"
                    :to="{
                      name: 'lead-response-view-update',
                      params: { id: lead_response_view.id },
                    }"
                    exact
                    :key="`lead_response_view_${lead_response_view.id}`"
                    title="Edit view"
                  >
                    <i class="bx bx-edit-alt smart-view-edit-action"></i>
                  </router-link
                ></a>
                <p class="lr-menu-heading">Custom Views</p>
                <div class="lr-views-scroll">
                  <a
                    class="dropdown-item lr-view-item"
                    :class="{ 'is-current': lead_response_view.id == getViewId }"
                    v-for="lead_response_view in getCustomViews"
                    :key="lead_response_view.id"
                    ><span
                      class="flex-fill"
                      @click="changeLeadResponseView(lead_response_view.id)"
                      >{{
                        $helpers.truncate(lead_response_view.header, 25, "...")
                      }}</span
                    ><router-link
                      class="lr-view-edit"
                      :to="{
                        name: 'lead-response-view-update',
                        params: { id: lead_response_view.id },
                      }"
                      exact
                      :key="`lead_response_default_view_${lead_response_view.id}`"
                      title="Edit view"
                    >
                      <i class="bx bx-edit-alt smart-view-edit-action"></i>
                    </router-link
                  ></a>
                  <p
                    class="lr-menu-empty"
                    v-if="getCustomViews.length == 0"
                  >
                    No custom views yet
                  </p>
                </div>
                <div class="lr-menu-footer">
                  <router-link
                    :to="{ name: 'lead-response-view-create' }"
                    class="btn btn-primary btn-sm w-100"
                    exact
                  >
                    + New Custom View
                  </router-link>
                </div>
              </div>
            </div>

            <!-- Quick filters (share state with the Filters modal) -->
            <div class="lr-select-wrap d-none d-lg-block">
              <select
                class="lr-select"
                v-model="statusFilter"
                @change="applyQuickFilters"
                aria-label="Filter by status"
              >
                <option value="">All Statuses</option>
                <option
                  v-for="status in getLeadStatuses"
                  :key="`qf_status_${status}`"
                  :value="status"
                >
                  {{ status }}
                </option>
              </select>
              <i class="bx bx-chevron-down lr-chevron"></i>
            </div>
            <div class="lr-select-wrap d-none d-lg-block">
              <select
                class="lr-select"
                v-model="sourceFilter"
                @change="applyQuickFilters"
                aria-label="Filter by source"
              >
                <option value="">All Sources</option>
                <option
                  v-for="source in getLeadSources"
                  :key="`qf_source_${source}`"
                  :value="source"
                >
                  {{ source }}
                </option>
              </select>
              <i class="bx bx-chevron-down lr-chevron"></i>
            </div>
            <div class="lr-select-wrap d-none d-xl-block">
              <select
                class="lr-select"
                v-model="assigneeFilter"
                @change="applyQuickFilters"
                aria-label="Filter by assignee"
              >
                <option value="">All Assignees</option>
                <option
                  v-for="user in allUsers"
                  :key="`qf_user_${user.user_id}`"
                  :value="user.user_id"
                >
                  {{ user.name }}
                </option>
              </select>
              <i class="bx bx-chevron-down lr-chevron"></i>
            </div>
            <button
              type="button"
              class="lr-link-btn"
              v-if="getFilterState"
              @click="resetFilters"
            >
              <i class="bx bx-x"></i> Clear filters
            </button>
          </div>
          <!-- Toolbar : END -->

          <!-- Bulk action bar : START (replaces the toolbar while rows are selected) -->
          <div class="lr-bulkbar" v-if="getLeadResponseIds.length > 0">
            <div class="lr-bulk-actions">
              <span class="lr-bulk-count">
                <i class="bx bx-check-circle"></i>
                {{ getLeadResponseIds.length }} selected
              </span>
              <button
                type="button"
                class="lr-bulk-btn lr-bulk-btn--danger"
                @click="confirmDeleteAction"
              >
                <i class="bx bx-trash"></i> Delete
              </button>
              <a
                v-if="isAuthorized"
                href="#"
                data-toggle="modal"
                data-target="#bulk-list"
                class="lr-bulk-btn lr-bulk-btn--primary"
                ><i class="bx bx-edit"></i> Bulk Update</a
              >
            </div>
            <button
              type="button"
              class="lr-bulk-btn lr-bulk-btn--ghost"
              @click="clearBulkSelection"
            >
              <i class="bx bx-x"></i> Cancel Selection
            </button>
          </div>
          <!-- Bulk action bar : END -->
          <!--grid list modal -->
          <div
            class="modal fade text-left lr-modal"
            id="grid-list"
            tabindex="-1"
            role="dialog"
            aria-labelledby="lrColumnsTitle"
            aria-hidden="true"
          >
            <div
              class="modal-dialog modal-dialog-centered modal-dialog-scrollable lr-modal-sm"
              role="document"
            >
              <div class="modal-content">
                <div class="lr-modal-header">
                  <div>
                    <h3 class="lr-modal-title" id="lrColumnsTitle">Columns</h3>
                    <p class="lr-modal-subtitle">
                      Drag to reorder · switch to show or hide
                    </p>
                  </div>
                  <button
                    type="button"
                    class="lr-modal-close"
                    data-dismiss="modal"
                    aria-label="Close"
                  >
                    <i class="bx bx-x"></i>
                  </button>
                </div>
                <div class="lr-modal-meta">
                  <span
                    >{{ visibleColumnCount }} of {{ list.length }} columns
                    visible</span
                  >
                </div>
                <div class="modal-body lr-modal-body p-0">
                  <!-- Draggable List : OPEN -->
                  <draggable
                    tag="transition-group"
                    :component-data="{
                      tag: 'ul',
                      type: 'transition',
                      name: 'flip-list'
                    }"
                    :list="list"
                    v-bind="dragOptions"
                    @start="isDragging = true"
                    @end="isDragging = false"
                    class="lr-column-list"
                    @change="unsavedView"
                    handle=".handle"
                    item-key="header"
                  >
                    <template #item="{ element, index }">
                      <li
                        class="lr-column-item"
                        :class="{ 'is-hidden': element.visibility != '1' }"
                      >
                        <span
                          class="bx bx-grid-vertical handle lr-drag-handle"
                          title="Drag to reorder"
                        ></span>
                        <label
                          class="lr-column-label"
                          :for="'dragging_' + index"
                          >{{ formatHeader(element.header) }}</label
                        >
                        <span class="lr-switch">
                          <input
                            type="checkbox"
                            @change="unsavedView"
                            v-model="element.visibility"
                            class="checkbox-input"
                            value="photodrag"
                            :id="'dragging_' + index"
                            :true-value="'1'"
                            :false-value="'0'"
                            :aria-label="'Show ' + formatHeader(element.header)"
                          />
                          <span class="lr-switch-track"></span>
                        </span>
                      </li>
                    </template>
                  </draggable>
                  <!-- Draggable List : CLOSE -->
                </div>
                <div class="lr-modal-footer">
                  <button
                    type="button"
                    class="btn btn-primary lr-modal-btn"
                    data-dismiss="modal"
                  >
                    Done
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Filter Model : START -->
          <div
            class="modal fade text-left lr-modal"
            id="filters"
            tabindex="-1"
            role="dialog"
            aria-labelledby="lrFiltersTitle"
            aria-hidden="true"
          >
            <div
              class="modal-dialog modal-dialog-centered"
              role="document"
            >
              <div class="modal-content" id="filterLeadModel">
                <div class="lr-modal-header">
                  <div>
                    <h3 class="lr-modal-title" id="lrFiltersTitle">Filters</h3>
                    <p class="lr-modal-subtitle">
                      Narrow down the leads shown in this list
                    </p>
                  </div>
                  <button
                    type="button"
                    class="lr-modal-close"
                    data-dismiss="modal"
                    aria-label="Close"
                  >
                    <i class="bx bx-x"></i>
                  </button>
                </div>
                <div class="modal-body lr-modal-body lr-filter-body">
                  <!-- status : START -->
                  <div class="lr-field">
                    <label class="lr-field-label" for="filter-status"
                      >Status</label
                    >
                    <v-select
                      id="filter-status"
                      placeholder="All statuses"
                      v-model="statusFilter"
                      clearable
                      :options="getLeadStatuses"
                    >
                    </v-select>
                  </div>
                  <!-- status : END -->

                  <!-- source : START -->
                  <div class="lr-field">
                    <label class="lr-field-label" for="filter-source"
                      >Source</label
                    >
                    <v-select
                      id="filter-source"
                      placeholder="All sources"
                      v-model="sourceFilter"
                      clearable
                      :options="getLeadSources"
                    >
                    </v-select>
                  </div>
                  <!-- source : END -->

                  <!-- assignee: START -->
                  <div class="lr-field">
                    <label class="lr-field-label" for="filter-assignee"
                      >Assignee</label
                    >
                    <v-select
                      id="filter-assignee"
                      placeholder="All assignees"
                      v-model="assigneeFilter"
                      clearable
                      :options="allUsers"
                      :reduce="(user) => user.user_id"
                      label="name"
                    >
                    </v-select>
                  </div>
                  <!-- assignee : END -->

                  <!-- dates: START -->
                  <div class="lr-field">
                    <label class="lr-field-label">Lead Date Range</label>
                    <div class="lr-date-range">
                      <div class="lr-date-field">
                        <span class="lr-date-caption">From</span>
                        <date-picker
                          :inputAttributes="{ readonly: true }"
                          :format="'MM/DD/YYYY'"
                          :value-type="'MM/DD/YYYY'"
                          v-model="dateStartFilter"
                          placeholder="MM/DD/YYYY"
                        >
                        </date-picker>
                      </div>
                      <span class="lr-date-sep">–</span>
                      <div class="lr-date-field">
                        <span class="lr-date-caption">To</span>
                        <date-picker
                          :inputAttributes="{ readonly: true }"
                          :format="'MM/DD/YYYY'"
                          :value-type="'MM/DD/YYYY'"
                          v-model="endStartFilter"
                          placeholder="MM/DD/YYYY"
                        >
                        </date-picker>
                      </div>
                    </div>
                  </div>
                  <!-- dates : END -->
                </div>
                <div class="lr-modal-footer lr-modal-footer--split">
                  <button
                    type="button"
                    @click="resetFilters"
                    class="btn lr-btn-ghost"
                    data-dismiss="modal"
                    aria-label="Reset"
                  >
                    <i class="bx bx-reset"></i> Reset
                  </button>
                  <button
                    type="button"
                    @click="filterLeads"
                    class="btn btn-primary lr-modal-btn"
                    data-dismiss="modal"
                    aria-label="Apply Filters"
                  >
                    Apply Filters
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- Filter Model : END -->

          <!-- bulk grid list modal -->
          <div
            class="modal fade text-left"
            id="bulk-list"
            tabindex="-1"
            role="dialog"
            aria-labelledby="myModalLabel19"
            aria-hidden="true"
          >
            <div
              class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
              role="document"
            >
              <div class="modal-content">
                <div class="modal-header pl-1 pr-1">
                  <h3 class="content-header-title" id="myModalLabel19">
                    Lead Response Bulk Update
                  </h3>
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
                    <div class="col-md-5">
                      <label>Status</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <fieldset>
                        <div class="input-group">
                          <select
                            class="custom-select form-control"
                            v-model="form.status"
                            id="status"
                            name="status"
                          >
                            <option value="" selected disabled>
                              - Select Status -
                            </option>
                            <option
                              :value="status"
                              v-for="status in getLeadStatuses"
                              :key="status"
                              v-text="status"
                            ></option>
                          </select>
                        </div>
                      </fieldset>
                    </div>
                    <div class="col-md-5">
                      <label>Source</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <fieldset>
                        <div class="input-group">
                          <select
                            class="custom-select form-control"
                            v-model="form.source"
                            id="source"
                            name="source"
                          >
                            <option disabled value="">
                              -- Select Source --
                            </option>
                            <option
                              :value="source"
                              v-for="source in getLeadSources"
                              :key="source"
                              v-text="source"
                            ></option>
                          </select>
                        </div>
                      </fieldset>
                    </div>
                    <div class="col-md-5">
                      <label>Assignee</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <fieldset>
                        <div class="input-group">
                          <select
                            class="custom-select form-control"
                            v-model="form.assignee"
                            id="assignee"
                            name="assignee"
                          >
                            <option disabled value="">
                              -- Select Assignee --
                            </option>
                            <option
                              :value="user.user_id"
                              v-for="user in allUsers"
                              :key="`user_${user.user_id}`"
                              v-text="user.name"
                            ></option>
                          </select>
                        </div>
                      </fieldset>
                    </div>
                    <div class="col-sm-12 d-flex justify-content-left pb-1">
                      <button
                        type="close"
                        @click="applyBulkOptions"
                        class="btn btn-primary mr-1 mt-1"
                        data-dismiss="modal"
                        aria-label="Close"
                      >
                        Update Fields
                      </button>
                      <button
                        type="close"
                        @click="resetBulkColumns"
                        class="btn btn-light-secondary mr-1 mt-1"
                        data-dismiss="modal"
                        aria-label="Close"
                      >
                        Cancel
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>


          <!-- Table card : START -->
          <div class="lr-card">
            <div
              class="table-responsive lr-table-wrap check-bulk"
              :class="getCompact ? 'compact' : ''"
            >
              <table
                id="table-extended-transactions"
                class="lr-table list-page-content-p mt-0 mb-0"
              >
                <thead>
                  <tr>
                    <th class="lr-col-check" id="d-list">
                      <input
                        class="select_check lr-checkbox"
                        type="checkbox"
                        v-model="bulk_select"
                        :true-value="'1'"
                        :false-value="'0'"
                        aria-label="Select all leads on this page"
                      />
                    </th>
                    <th class="lr-col-avatar">Lead</th>
                    <template v-for="element in list">
                      <th
                        :class="{
                          'lr-col-wide':
                            element.header == 'EMAIL' ||
                            element.header == 'STATUS' ||
                            element.header == 'ASSIGNEE',
                          'is-sorted': getSortColumn.hasOwnProperty(
                            element.column
                          ),
                        }"
                        :key="element.header"
                        v-if="element.visibility == '1'"
                      >
                        <span
                          class="lr-sort"
                          v-if="getSortColumn.hasOwnProperty(element.column)"
                        >
                          <span
                            @click="updateSortColumn(element.column, 'ASC')"
                            v-if="getSortColumn[element.column] == 'DESC'"
                            title="Sorted descending — click to sort ascending"
                            >{{ formatHeader(element.header) }}
                            <i class="bx bx-down-arrow-alt"></i>
                          </span>
                          <span
                            @click="updateSortColumn(element.column, 'DESC')"
                            v-else-if="getSortColumn[element.column] == 'ASC'"
                            title="Sorted ascending — click to sort descending"
                            >{{ formatHeader(element.header) }}
                            <i class="bx bx-up-arrow-alt"></i>
                          </span>
                        </span>
                        <span
                          class="lr-sort"
                          @click="updateSortColumn(element.column, 'ASC')"
                          v-else
                          title="Sort"
                          >{{ formatHeader(element.header) }}
                          <i class="bx bx-sort lr-sort-idle"></i>
                        </span>
                      </th>
                    </template>
                    <th class="lr-col-actions"></th>
                  </tr>
                </thead>
                <tbody>
                  <lead-response-item
                    v-for="item in getEntities"
                    :allUsers="allUsers"
                    :item="item"
                    :childCheck="bulk_select"
                    v-bind:key="item.id + '_item_listing_view'"
                  ></lead-response-item>
                  <tr v-if="getEntities.length == 0" class="lr-empty-row">
                    <td :colspan="visibleColumnCount + 3">
                      <div class="lr-empty">
                        <i class="bx bx-user-x"></i>
                        <p>No leads found</p>
                        <span v-if="getSearch || getFilterState"
                          >Try a different search or clear the filters.</span
                        >
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- datatable ends -->

            <!-- Navigation Pagination : START -->
            <div class="lr-footer">
              <div
                class="lr-footer-info"
                id="table-extended-transactions_info"
                role="status"
                aria-live="polite"
              >
                <template v-if="totalRecords > 0">
                  Showing {{ rangeStart }}–{{ rangeEnd }} of
                  {{ totalRecords }} leads
                </template>
                <template v-else>Total Records 0</template>
              </div>

              <div class="lr-footer-right">
                <ul class="lr-pagination" id="table-extended-transactions_paginate">
                  <li>
                    <button
                      type="button"
                      class="lr-page-btn"
                      :disabled="getPager.previous == null"
                      @click="paginationWithUrl(getPager.previous)"
                      aria-label="Previous page"
                    >
                      <i class="bx bx-chevron-left"></i>
                    </button>
                  </li>
                  <li v-for="(n, idx) in pageList" :key="'nav_id' + idx">
                    <span v-if="n === '…'" class="lr-page-gap">…</span>
                    <button
                      v-else
                      type="button"
                      class="lr-page-btn"
                      :class="{ active: n === getCurrentPage }"
                      @click="pagination(n)"
                    >
                      {{ n }}
                    </button>
                  </li>
                  <li>
                    <button
                      type="button"
                      class="lr-page-btn"
                      :disabled="getPager.next == null"
                      @click="paginationWithUrl(getPager.next)"
                      aria-label="Next page"
                    >
                      <i class="bx bx-chevron-right"></i>
                    </button>
                  </li>
                </ul>

                <label class="lr-per-page" id="table-extended-transactions_length">
                  <span>Rows per page</span>
                  <span class="lr-select-wrap">
                    <select
                      v-model="getNumberOfEntities"
                      name="table-extended-transactions_length"
                      aria-controls="table-extended-transactions"
                      class="lr-select lr-select-sm"
                    >
                      <option value="10">10</option>
                      <option value="25">25</option>
                      <option value="50">50</option>
                      <option value="100">100</option>
                      <option value="200">200</option>
                      <option value="250">250</option>
                    </select>
                    <i class="bx bx-chevron-down lr-chevron"></i>
                  </span>
                </label>
              </div>
            </div>
            <!-- Navigation Pagination : END -->
          </div>
          <!-- Table card : END -->
        </section>
        <!-- form layout section end -->
      </form>
    </div>
    <lead-response-snap-shot></lead-response-snap-shot>
  </div>
  <!-- END: Content-->
</template>
<script>
import draggable from "vuedraggable";
import LeadResponseItem from "./LeadResponseItem";
import { mapGetters, mapActions } from "vuex";
import vSelect from "vue-select";
import DatePicker from "../../Common/DatePickerAdapter.vue";
import "vue-datepicker-next/index.css";

import LeadResponseSnapShot from "./LeadResponseSnapShot.vue";

export default {
  props: ["application", "app_metadata"],
  name: "LeadResponseList",
  components: {
    LeadResponseItem,
    draggable,
    "v-select": vSelect,
    LeadResponseSnapShot,
    DatePicker,
  },
  data() {
    return {
      load: false,
      single_search_lead: null,
      page: 1,
      number_of_items: 100,
      bulk_select: "0",
      lead_response_heading: "Leads",
      lead_response_listing: "",
      lead_responses: [],
      pager: "",
      lead_response_views: [],
      lead_response_id: 1,
      dragging: true,
      lead_response_view_flag: 1,
      lead_response_view_default: [],
      sort_column: { created_at: "DESC" },
      form: {
        source: "",
        status: "",
        assignee: "",
      },
    };
  },
  watch: {
    number_of_items: function(newVal, oldVal) {
      if (parseInt(newVal) != parseInt(this.getEntitiesCount)) {
        this.commitEntitiesCount(newVal);
        this.pagination();
      }
    },
    single_search_lead: function(newVal, oldVal) {
      if (newVal.hasOwnProperty("id")) {
        if (!isNaN(newVal.id)) {
          this.$router.push({
            name: "lead-response-show",
            params: { id: newVal.id },
          });
        }
      }
    },
  },
  computed: {
    ...mapGetters("leadResponse", [
      "getLeadResponseIds",
      "getLeadSources",
      "getLeadStatuses",
    ]),
    ...mapGetters("leadResponseView", [
      "list",
      "getViewId",
      "getViewTitle",
      "getEntities",
      "getPager",
      "getSortColumn",
      "getLoadState",
      "getEntitiesCount",
      "getCurrentPage",
      "getCurrentEntity",
      "getSearchString",
      "getSearch",
      "getCompact",
      "getFilters",
      "getFilterState",
    ]),
    ...mapGetters("users", ["allUsers"]),
    getDefaultViews() {
      return this.lead_response_views.filter((view) => view.default == "1");
    },
    getCustomViews() {
      return this.lead_response_views.filter((view) => view.default == "0");
    },
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost",
      };
    },
    selected_lead_response() {
      return this.getViewTitle.length > 12
        ? this.getViewTitle.substring(0, 12 - 3) + "..."
        : this.getViewTitle;
    },
    getNumberOfEntities: {
      get() {
        return this.getEntitiesCount;
      },
      set(value) {
        this.commitEntitiesCount(value);
        this.pagination();
      },
    },
    statusFilter: {
      get() {
        return this.getFilters.status;
      },
      set(value) {
        this.commitFilters({ name: "status", value: value });
      },
    },
    sourceFilter: {
      get() {
        return this.getFilters.source;
      },
      set(value) {
        this.commitFilters({ name: "source", value: value });
      },
    },
    assigneeFilter: {
      get() {
        return this.getFilters.assignee;
      },
      set(value) {
        this.commitFilters({ name: "assignee", value: value });
      },
    },
    dateStartFilter: {
      get() {
        return this.getFilters.date_start;
      },
      set(value) {
        this.commitFilters({ name: "date_start", value: value });
      },
    },
    endStartFilter: {
      get() {
        return this.getFilters.date_end;
      },
      set(value) {
        this.commitFilters({ name: "date_end", value: value });
      },
    },
    searchString: {
      get() {
        return this.getSearchString;
      },
      set(value) {
        this.commitSearchString(value);
      },
    },
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    totalRecords() {
      return this.getPager && this.getPager.total
        ? parseInt(this.getPager.total)
        : 0;
    },
    rangeStart() {
      if (this.totalRecords == 0) return 0;
      return (
        (parseInt(this.getCurrentPage) - 1) * parseInt(this.getEntitiesCount) +
        1
      );
    },
    rangeEnd() {
      return Math.min(
        this.rangeStart + this.getEntities.length - 1,
        this.totalRecords
      );
    },
    visibleColumnCount() {
      return this.list.filter((element) => element.visibility == "1").length;
    },
    /** Page numbers with gaps, e.g. [1, 2, 3, 4, 5, "…", 19] */
    pageList() {
      const total = parseInt(this.getPager.pageCount) || 0;
      const current = parseInt(this.getCurrentPage) || 1;
      if (total <= 7) {
        return Array.from({ length: total }, (_, i) => i + 1);
      }
      const pages = [1];
      let start = Math.max(2, current - 1);
      let end = Math.min(total - 1, current + 1);
      if (current <= 4) {
        start = 2;
        end = 5;
      } else if (current >= total - 3) {
        start = total - 4;
        end = total - 1;
      }
      if (start > 2) pages.push("…");
      for (let n = start; n <= end; n++) pages.push(n);
      if (end < total - 1) pages.push("…");
      pages.push(total);
      return pages;
    },
  },
  methods: {
    ...mapActions("leadResponse", [
      "removeEveryLeadResponseId",
      "addAllLeadResponseId",
      "getLeadConfigurations",
    ]),
    ...mapActions("leadResponseView", [
      "updateView",

      "commitViewId",
      "commitViewTitle",
      "commitViewEntities",
      "commitViewPager",
      "commitSortColumn",
      "commitLoadState",
      "commitEntitiesCount",
      "commitCurrentPage",
      "commitRequestUrl",
      "commitSearchState",
      "commitSearchString",
      "commitCompactFlag",
      "commitFilters",
      "commitFilterState",
    ]),
    ...mapActions("users", ["getAllUsers"]),
    searchAction(value, e) {
      if (value.length >= 3) {
        this.commitSearchState(true);
        this.searchLeadResponses(this.getSearchString);
      } else {
        if (this.getSearch) {
          this.leadResponseListing(true);
          this.commitSearchState(false);
        }
      }
    },
    async searchLeadResponses(search) {
      let url = window.location.origin;
      url += `/api/business/leads/responses?all=true`;

      url += `&search=${this.getSearchString}`;

      if (this.getFilterState) {
        for (const key in this.getFilters) {
          if (this.getFilters.hasOwnProperty(key)) {
            if (this.getFilters[key]) {
              url += `&zm_${key}[]=${this.getFilters[key]}`;
            }
          }
        }
      }

      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data.lead_response_listing);
        this.commitRequestUrl(url);

        let pager = response.data.pager;
        pager.next =
          pager.next == null ? null : "/api" + pager.next.split("api")[1];
        pager.previous =
          pager.previous == null
            ? null
            : "/api" + pager.previous.split("api")[1];

        this.commitViewPager(pager);
        this.commitCurrentPage(parseInt(pager.currentPage));
        this.commitEntitiesCount(parseInt(pager.perPage));
        this.commitSortColumn(JSON.parse(JSON.stringify(response.data.sort)));

        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: `Search for ${search} has been completed.`,
        });
      });
    },
    changeCompact() {
      this.commitCompactFlag(!this.getCompact);
    },
    /** "FIRST NAME" -> "First Name", keeps short acronyms like VIN as-is */
    formatHeader(header) {
      if (!header) return "";
      return String(header)
        .split(" ")
        .map((word) =>
          word.length <= 3 && word === word.toUpperCase() && word !== "NO"
            ? word
            : word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
        )
        .join(" ");
    },
    /** Toolbar dropdowns reuse the same filter state as the Filters modal. */
    applyQuickFilters() {
      const hasFilter = Object.values(this.getFilters).some((value) => value);
      this.clearBulkSelection();
      if (hasFilter) {
        this.filterLeads();
      } else {
        this.resetFilters();
      }
    },
    unsavedView() {
      const vm = this;
      //check for the flag if flag is 0 that means the pop up is there and dont do any thing.
      // If flag is 1 that means reset the value to 0, show the popup
      if (this.lead_response_view_flag == 1) {
        this.lead_response_view_flag = 0;
        this.$swal({
          showConfirmButton: true,
          showCancelButton: true,
          cancelButtonText: "Revert",
          confirmButtonText: "Save",
          toast: true,
          position: "bottom",
          text: "Do you like the view ? Save It.",
        }).then((result) => {
          if (result.hasOwnProperty("dismiss")) {
            this.updateView(
              JSON.parse(JSON.stringify(this.lead_response_view_default)),
            );
          } else if (result.hasOwnProperty("value") && result.value) {
            //Save the inventory view.
            let formData = new FormData();
            formData.append("view", JSON.stringify(vm.list));
            axios({
              method: "post",
              data: formData,
              headers: {
                Authorization: "Bearer " + window.$cookies.get("_token"),
              },
              url:
                window.location.origin +
                `/api/business/leads/responses/views/update/${this.getViewId}}`,
            }).then((response) => {
              this.$swal({
                showConfirmButton: false,
                icon: "success",
                toast: true,
                timer: 2000,
                position: "bottom",
                title: "Lead Response View Updated.",
              });
            });
          }
          this.lead_response_view_flag = 1;
        });
      }
    },
    updateSortColumn(column, order) {
      if (this.getSearch || this.getFilterState) {
        this.$swal({
          showConfirmButton: false,
          icon: "error",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: "Sort order for view cannot be updated while searching.",
        });
        return;
      }

      const vm = this;
      let formData = new FormData();
      vm.sort_column = {};
      vm.sort_column[column] = order;
      formData.append("sort", JSON.stringify(vm.sort_column));
      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          `/api/business/leads/responses/views/update/${this.getViewId}}`,
      }).then((response) => {
        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: "Lead Response View Changed.",
        });

        this.commitLoadState(false);
        vm.leadResponseListing();
      });
    },
    filterLeads() {
      let url = window.location.origin;
      url += `/api/business/leads/responses?all=true`;

      if (this.getSearch) {
        url += `&search=${this.getSearchString}`;
      }

      for (const key in this.getFilters) {
        if (this.getFilters.hasOwnProperty(key)) {
          if (this.getFilters[key]) {
            url += `&zm_${key}[]=${this.getFilters[key]}`;
          }
        }
      }

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data.lead_response_listing);
        this.commitRequestUrl(url);

        let pager = response.data.pager;
        pager.next =
          pager.next == null ? null : "/api" + pager.next.split("api")[1];
        pager.previous =
          pager.previous == null
            ? null
            : "/api" + pager.previous.split("api")[1];

        this.commitViewPager(pager);
        this.commitCurrentPage(parseInt(pager.currentPage));
        this.commitEntitiesCount(parseInt(pager.perPage));
        this.commitSortColumn(JSON.parse(JSON.stringify(response.data.sort)));
        this.commitFilterState(true);

        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: `Lead has been filtered.`,
        });
      });
    },
    resetFilters() {
      this.commitFilters({ name: "status", value: "" });
      this.commitFilters({ name: "source", value: "" });
      this.commitFilters({ name: "assignee", value: "" });
      this.commitFilters({ name: "date_start", value: "" });
      this.commitFilters({ name: "date_end", value: "" });

      if (this.getSearch) {
        this.searchLeadResponses(this.getSearchString);
      } else {
        this.leadResponseListing(true);
      }
      this.commitFilterState(false);
    },
    changeLeadResponseView(id) {
      this.commitSearchString("");
      this.commitSearchState(false);

      this.commitFilterState(false);
      this.commitFilters({ name: "status", value: "" });
      this.commitFilters({ name: "source", value: "" });
      this.commitFilters({ name: "assignee", value: "" });
      this.commitFilters({ name: "date_start", value: "" });
      this.commitFilters({ name: "date_end", value: "" });

      let vm = this;
      this.clearBulkSelection();
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          `/api/business/leads/responses/views/result/${id}`,
      }).then((response) => {
        this.commitViewId(response.data.lead_response_id);
        this.commitViewTitle(response.data.header);
        this.commitViewEntities(response.data.lead_response_listing);
        this.commitRequestUrl(
          window.location.origin +
            `/api/business/leads/responses/views/result/${id}`,
        );

        let pager = response.data.pager;
        pager.next =
          pager.next == null ? null : "/api" + pager.next.split("api")[1];
        pager.previous =
          pager.previous == null
            ? null
            : "/api" + pager.previous.split("api")[1];

        this.commitViewPager(pager);
        this.commitCurrentPage(parseInt(pager.currentPage));
        this.commitEntitiesCount(parseInt(pager.perPage));

        this.updateView(Object.values(response.data.view));
        this.lead_response_view_default = JSON.parse(
          JSON.stringify(Object.values(response.data.view)),
        );

        this.commitSortColumn(JSON.parse(JSON.stringify(response.data.sort)));

        axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url:
            window.location.origin +
            `/api/business/user/lead-response/update?id=${this.getViewId}`,
        }).then((response) => {
          this.$swal({
            showConfirmButton: false,
            icon: "success",
            toast: true,
            timer: 2000,
            position: "bottom",
            title: "Lead User View Changed.",
          });
        });
      });
    },

    pagination(n = 1) {
      const vm = this;
      let url = window.location.origin;

      if (this.getSearch || this.getFilterState) {
        url += `/api/business/leads/responses?all=true`;

        if (this.getSearch) {
          url += `&search=${this.getSearchString}`;
        }

        if (this.getFilterState) {
          for (const key in this.getFilters) {
            if (this.getFilters.hasOwnProperty(key)) {
              if (this.getFilters[key]) {
                url += `&zm_${key}[]=${this.getFilters[key]}`;
              }
            }
          }
        }

        url += `&page=`;
      } else {
        url += `/api/business/leads/responses/views/result/${this.getViewId}?page=`;
      }

      url += n;
      url += "&items=";
      url += this.getEntitiesCount;
      this.clearBulkSelection();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data.lead_response_listing);
        this.commitRequestUrl(url);

        let pager = response.data.pager;
        pager.next =
          pager.next == null ? null : "/api" + pager.next.split("api")[1];
        pager.previous =
          pager.previous == null
            ? null
            : "/api" + pager.previous.split("api")[1];

        this.commitViewPager(pager);
        this.commitCurrentPage(parseInt(pager.currentPage));
        this.commitEntitiesCount(parseInt(pager.perPage));
      });
    },
    paginationWithUrl(url) {
      const vm = this;
      this.clearBulkSelection();
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data.lead_response_listing);
        this.commitRequestUrl(url);

        let pager = response.data.pager;
        pager.next =
          pager.next == null ? null : "/api" + pager.next.split("api")[1];
        pager.previous =
          pager.previous == null
            ? null
            : "/api" + pager.previous.split("api")[1];

        this.commitViewPager(pager);
        this.commitCurrentPage(parseInt(pager.currentPage));
        this.commitEntitiesCount(parseInt(pager.perPage));
      });
    },
    lead_responses_views() {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + "/api/business/leads/responses/views",
      }).then((response) => {
        vm.$data.lead_response_views = response.data.lead_response_views;
      });
    },
    async leadResponseListing(refresh = false) {
      let vm = this;

      if (refresh || !this.getLoadState) {
        await axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: window.location.origin + `/api/business/user/lead-response`,
        }).then((response) => {
          this.commitViewId(response.data.lead_response_id);
          this.commitViewTitle(response.data.header);
          this.commitViewEntities(response.data.lead_response_listing);
          this.commitRequestUrl(
            window.location.origin + `/api/business/user/lead-response`,
          );

          let pager = response.data.pager;
          pager.next =
            pager.next == null ? null : "/api" + pager.next.split("api")[1];
          pager.previous =
            pager.previous == null
              ? null
              : "/api" + pager.previous.split("api")[1];

          this.commitViewPager(pager);
          this.commitCurrentPage(parseInt(pager.currentPage));
          this.commitEntitiesCount(parseInt(pager.perPage));
          this.commitSortColumn(JSON.parse(JSON.stringify(response.data.sort)));

          this.updateView(Object.values(response.data.view));
          this.lead_response_view_default = JSON.parse(
            JSON.stringify(Object.values(response.data.view)),
          );
        });

        this.commitLoadState(true);
      }
    },
    async onlyListingSmartView(refresh = false) {
      let vm = this;

      if (refresh || !this.getLoadState) {
        await axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: window.location.origin + `/api/business/user/lead-response`,
        }).then((response) => {
          this.commitViewId(response.data.lead_response_id);
          this.commitViewTitle(response.data.header);
          this.updateView(Object.values(response.data.view));
          this.lead_response_view_default = JSON.parse(
            JSON.stringify(Object.values(response.data.view)),
          );
        });

        this.commitLoadState(true);
      }
    },
    clearBulkSelection() {
      /** Toggle through 0 (integer) so row checkboxes always receive the reset, then back to "0". */
      this.bulk_select = 0;
      this.$nextTick(() => {
        this.bulk_select = "0";
      });
      this.removeEveryLeadResponseId();
    },
    resetBulkColumns() {
      Object.keys(this.form).forEach((key) => {
        this.form[key] = "";
      });
    },
    applyBulkOptions() {
      const vm = this;
      let formData = new FormData();
      formData.append("lead_response_ids", this.getLeadResponseIds);
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key)) {
          formData.append(key, this.form[key]);
        }
      }

      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/leads/responses/updates`,
      })
        .then((response) => {
          this.resetBulkColumns();
          /** Purposefully set to 0 (integer) to fail the prop check so subcomponents become reactive to change */
          vm.$data.bulk_select = 0;

          setTimeout(() => {
            this.pagination(this.getCurrentPage);
            this.$swal({
              showConfirmButton: false,
              icon: "success",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "Columns Are Updated Successfully.",
            });
          }, 1000);
        })
        .catch(function(error) {
          vm.resetBulkColumns();
          vm.$data.bulk_select = 0;
          setTimeout(() => {
            vm.$swal({
              showConfirmButton: false,
              icon: "error",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "No Columns Or Rows Were Selected.",
            });
          }, 1000);
        });
    },
    applyBulkDelete() {
      const vm = this;
      let formData = new FormData();
      formData.append("lead_response_ids", this.getLeadResponseIds);
      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/leads/responses/deletes`,
      })
        .then((response) => {
          this.resetBulkColumns();
          /** Purposefully set to 0 (integer) to fail the prop check so subcomponents become reactive to change */
          vm.$data.bulk_select = 0;

          setTimeout(() => {
            this.pagination(this.getCurrentPage);
            this.$swal({
              showConfirmButton: false,
              icon: "success",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "Selected data has been deleted.",
            });
          }, 1000);
        })
        .catch(function(error) {
          vm.resetBulkColumns();
          vm.$data.bulk_select = 0;
          setTimeout(() => {
            vm.$swal({
              showConfirmButton: false,
              icon: "error",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "There were some errors in deleting data.",
            });
          }, 1000);
        });
    },
    confirmDeleteAction() {
      const vm = this;
      this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Confirm",
        toast: false,
        position: "center",
        text: "Please confirm if you want to delete the leads.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.applyBulkDelete();
        }
      });
    },
  },
  async created() {
    this.getLeadConfigurations();

    await this.lead_responses_views();

    if (this.getSearch && !this.getLoadState) {
      await this.onlyListingSmartView();
      await this.searchLeadResponses(this.searchString);
    } else {
      await this.leadResponseListing();
    }
  },
  async mounted() {
    const vm = this;
    await this.getAllUsers();

    if (Number.isInteger(parseInt(this.getCurrentEntity))) {
      let rows = document.querySelector(
        "#lead_response_" + this.getCurrentEntity,
      );

      // line is zero-based
      // line is the row number that you want to see into view after scroll
      if (rows) {
        rows.classList.add("is-selected");
        rows.scrollIntoView({
          behavior: "smooth",
          block: "center",
        });
      }
    }

    var input = document.getElementById("searchText");

    input.addEventListener("keydown", function(event) {
      if (event.keyCode === 13) {
        event.preventDefault();

        vm.searchAction();
      }
    });
  },
};
</script>
<style lang="scss">
/* ==========================================================================
   Leads listing — modern table layout
   All rules are scoped to #lead-response-section so other modules are untouched.
   ========================================================================== */
#lead-response-section {
  --lr-bg: #f6f8fb;
  --lr-surface: #ffffff;
  --lr-border: #e6eaf0;
  --lr-border-strong: #d6dce5;
  --lr-head-bg: #f8fafc;
  --lr-text: #1f2937;
  --lr-text-2: #4b5563;
  --lr-muted: #8a94a6;
  --lr-primary: #5a8dee;
  --lr-primary-soft: #eef4ff;
  --lr-row-hover: #f9fbff;
  --lr-radius: 12px;

  .lr-page {
    /* top padding clears the fixed theme navbar (~45px) */
    /* bottom padding leaves room for the theme's fixed footer */
    padding: calc(45px + 1.5rem) 1.75rem 2.75rem;
    background: var(--lr-bg);
    /* full-height column: header/toolbar fixed, table area takes the rest and scrolls */
    display: flex;
    flex-direction: column;
    height: 100vh;
    box-sizing: border-box;
  }

  /* ---------- Header ---------- */
  .lr-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.25rem;
  }
  .lr-title {
    font-size: 2rem;
    font-weight: 700;
    letter-spacing: -0.02em;
    color: var(--lr-text);
    margin: 0;
    line-height: 1.15;
  }
  .lr-subtitle {
    margin: 0.25rem 0 0;
    font-size: 1rem;
    color: var(--lr-muted);
  }
  .lr-subtitle-sep {
    margin: 0 0.35rem;
  }
  .lr-header-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  .lr-icon-btn {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    border: 1px solid var(--lr-border);
    background: var(--lr-surface);
    color: var(--lr-text-2);
    font-size: 1.25rem;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s, color 0.15s;
    &:hover {
      border-color: var(--lr-border-strong);
      color: var(--lr-text);
      background: #fbfcfe;
    }
    &.is-active {
      color: var(--lr-primary);
      border-color: var(--lr-primary);
      background: var(--lr-primary-soft);
    }
  }
  .lr-dot {
    position: absolute;
    top: 7px;
    right: 7px;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--lr-primary);
  }
  .lr-primary-btn {
    height: 40px;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0 1.1rem;
    border-radius: 10px;
    font-weight: 500;
    white-space: nowrap;
    line-height: 1;
    i {
      /* theme sets `.btn i { position: relative; top: 3px }` — reset so the icon centres with the label */
      position: static;
      top: 0;
      font-size: 1.15rem;
      line-height: 1;
      display: inline-flex;
    }
  }
  .lr-bulk-actions .btn i,
  .lr-icon-btn i {
    position: static;
    top: 0;
    line-height: 1;
  }

  /* ---------- Toolbar ---------- */
  .lr-toolbar {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
  }
  .lr-search {
    position: relative;
    flex: 1 1 320px;
    max-width: 520px;
    i {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1.2rem;
      color: var(--lr-muted);
      pointer-events: none;
    }
    input {
      width: 100%;
      height: 44px;
      padding: 0 1rem 0 2.6rem;
      border: 1px solid var(--lr-border);
      border-radius: 10px;
      background: var(--lr-surface);
      color: var(--lr-text);
      font-size: 0.95rem;
      outline: none;
      transition: border-color 0.15s, box-shadow 0.15s;
      &::placeholder {
        color: var(--lr-muted);
      }
      &:focus {
        border-color: var(--lr-primary);
        box-shadow: 0 0 0 3px rgba(90, 141, 238, 0.15);
      }
    }
  }
  .lr-select-wrap {
    position: relative;
    display: inline-block;
  }
  .lr-select {
    appearance: none;
    -webkit-appearance: none;
    height: 44px;
    min-width: 170px;
    max-width: 230px;
    padding: 0 2.25rem 0 0.95rem;
    border: 1px solid var(--lr-border);
    border-radius: 10px;
    background: var(--lr-surface);
    color: var(--lr-text);
    font-size: 0.95rem;
    cursor: pointer;
    outline: none;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    &:hover {
      border-color: var(--lr-border-strong);
    }
    &:focus {
      border-color: var(--lr-primary);
      box-shadow: 0 0 0 3px rgba(90, 141, 238, 0.15);
    }
  }
  .lr-select-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    text-align: left;
    span {
      overflow: hidden;
      text-overflow: ellipsis;
    }
  }
  .lr-select-icon {
    color: var(--lr-muted);
    font-size: 1.1rem;
  }
  .lr-chevron {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--lr-muted);
    font-size: 1.15rem;
    pointer-events: none;
  }
  .lr-select-sm {
    height: 36px;
    min-width: 76px;
    font-size: 0.9rem;
  }
  .lr-link-btn {
    border: 0;
    background: transparent;
    color: var(--lr-primary);
    font-size: 0.9rem;
    font-weight: 500;
    padding: 0.25rem 0.4rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    &:hover {
      text-decoration: underline;
    }
  }

  /* Views dropdown */
  .lr-views-menu {
    min-width: 270px;
    padding: 0.4rem 0;
    border: 1px solid var(--lr-border);
    border-radius: 12px;
    box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
    margin-top: 6px;
  }
  .lr-menu-heading {
    margin: 0.35rem 0 0.15rem;
    padding: 0.25rem 1rem;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--lr-muted);
  }
  .lr-menu-empty {
    margin: 0;
    padding: 0.4rem 1rem;
    font-size: 0.85rem;
    color: var(--lr-muted);
  }
  .lr-views-scroll {
    max-height: 200px;
    overflow-y: auto;
  }
  .lr-view-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    cursor: pointer;
    color: var(--lr-text);
    &.is-current {
      background: var(--lr-primary-soft);
      color: var(--lr-primary);
      font-weight: 500;
    }
  }
  .lr-view-edit {
    color: var(--lr-muted);
    &:hover {
      color: var(--lr-primary);
    }
  }
  .lr-menu-footer {
    border-top: 1px solid var(--lr-border);
    margin-top: 0.4rem;
    padding: 0.6rem 0.75rem 0.25rem;
  }

  /* ---------- Bulk bar (shown in place of the toolbar) ---------- */
  .lr-bulkbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    flex-wrap: wrap;
    min-height: 60px;
    padding: 0.55rem 0.75rem;
    margin-bottom: 1rem;
    background: var(--lr-surface);
    border: 1px solid var(--lr-border);
    border-radius: 12px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
  }
  .lr-bulk-actions {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    flex-wrap: wrap;
  }
  .lr-bulk-count {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    height: 36px;
    padding: 0 0.9rem;
    border: 1px solid #cfe0ff;
    border-radius: 999px;
    background: var(--lr-primary-soft);
    color: #2f62d6;
    font-size: 0.9rem;
    font-weight: 600;
    white-space: nowrap;
    i {
      font-size: 1.15rem;
    }
  }
  .lr-bulk-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    height: 36px;
    padding: 0 0.95rem;
    border-radius: 8px;
    border: 1px solid var(--lr-border);
    background: var(--lr-surface);
    font-size: 0.9rem;
    font-weight: 500;
    line-height: 1;
    white-space: nowrap;
    cursor: pointer;
    text-decoration: none !important;
    transition: background 0.15s, border-color 0.15s, color 0.15s;
    i {
      position: static;
      top: 0;
      font-size: 1.1rem;
      line-height: 1;
    }
  }
  .lr-bulk-btn--danger {
    border-color: #f3b4b4;
    color: #d64545;
    &:hover {
      background: #fdeeee;
      border-color: #e05252;
      color: #c23434;
    }
  }
  .lr-bulk-btn--primary {
    border-color: #a9c4f7;
    color: #2f62d6;
    &:hover {
      background: var(--lr-primary-soft);
      border-color: var(--lr-primary);
      color: #2f62d6;
    }
  }
  .lr-bulk-btn--ghost {
    color: var(--lr-text-2);
    &:hover {
      background: #f6f8fb;
      border-color: var(--lr-border-strong);
      color: var(--lr-text);
    }
  }

  /* ---------- Table card ---------- */
  .lr-card {
    background: var(--lr-surface);
    border: 1px solid var(--lr-border);
    border-radius: var(--lr-radius);
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    overflow: hidden;
    flex: 1 1 auto;
    min-height: 0;
    display: flex;
    flex-direction: column;
  }
  .lr-table-wrap {
    flex: 1 1 auto;
    min-height: 240px;
    overflow: auto;
  }
  .lr-header,
  .lr-toolbar,
  .lr-bulkbar,
  .lr-footer {
    flex-shrink: 0;
  }
  .lr-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.95rem;
    color: var(--lr-text);

    thead th {
      position: sticky;
      top: 0;
      z-index: 2;
      background: var(--lr-head-bg);
      color: var(--lr-text-2);
      font-weight: 500;
      font-size: 0.9rem;
      text-align: left;
      text-transform: none;
      letter-spacing: normal;
      white-space: nowrap;
      padding: 0.95rem 1rem;
      border-bottom: 1px solid var(--lr-border);
      min-width: 150px;
      &.lr-col-wide {
        min-width: 230px;
      }
      &.is-sorted {
        color: var(--lr-text);
      }
    }
    th.lr-col-check,
    td.lr-col-check {
      width: 52px;
      min-width: 52px;
      padding-left: 1.25rem;
      padding-right: 0.25rem;
    }
    th.lr-col-avatar,
    td.lr-col-avatar {
      width: 92px;
      min-width: 92px;
    }
    th.lr-col-actions,
    td.lr-col-actions {
      width: 56px;
      min-width: 56px;
      text-align: center;
    }

    .lr-sort {
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
      user-select: none;
      > span {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
      }
      i {
        font-size: 1rem;
        color: var(--lr-primary);
      }
      .lr-sort-idle {
        color: #b4bccb;
      }
      &:hover .lr-sort-idle {
        color: var(--lr-text-2);
      }
    }

    tbody tr {
      transition: background 0.12s;
      &:hover {
        background: var(--lr-row-hover);
      }
      &.is-selected {
        background: var(--lr-primary-soft);
      }
    }
    tbody td {
      padding: 0.85rem 1rem;
      border-bottom: 1px solid #eef1f5;
      vertical-align: middle;
      text-align: left;
    }
    tbody tr:last-child td {
      border-bottom: 0;
    }
  }

  .lr-checkbox,
  .lr-table .select_check {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--lr-primary);
    vertical-align: middle;
  }

  /* Cells */
  .lr-lead-cell {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }
  .lr-avatar {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.85rem;
    letter-spacing: 0.02em;
    flex-shrink: 0;
    cursor: pointer;
  }
  .lr-avatar-sm {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    font-size: 0.7rem;
    cursor: inherit;
  }
  .lr-snapshot-btn {
    border: 0;
    background: transparent;
    color: var(--lr-muted);
    font-size: 1.15rem;
    padding: 0.2rem;
    border-radius: 6px;
    cursor: pointer;
    line-height: 1;
    &:hover {
      color: var(--lr-primary);
      background: var(--lr-primary-soft);
    }
  }
  .lr-cell-strong {
    font-weight: 500;
    color: var(--lr-text);
  }
  .lr-cell-muted {
    color: var(--lr-muted);
  }
  .lr-cell-icon {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    white-space: nowrap;
    i {
      font-size: 1.05rem;
    }
  }
  .lr-icon-phone {
    color: #0ea5c6;
  }
  .lr-icon-mail {
    color: #22a35a;
  }
  .lr-date {
    display: flex;
    flex-direction: column;
    line-height: 1.25;
    white-space: nowrap;
    small {
      color: var(--lr-muted);
      font-size: 0.8rem;
    }
  }
  .lr-mono {
    font-variant-numeric: tabular-nums;
    color: var(--lr-text-2);
    letter-spacing: 0.01em;
  }
  .lr-user {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    white-space: nowrap;
  }
  .lr-unassigned {
    color: var(--lr-muted);
    font-style: italic;
  }

  /* Status pills */
  .lr-pill {
    display: inline-flex;
    align-items: center;
    padding: 0.3rem 0.7rem;
    border-radius: 7px;
    font-size: 0.85rem;
    font-weight: 500;
    white-space: nowrap;
    line-height: 1.2;
    background: #eef1f5;
    color: #4b5563;
  }
  .lr-pill--blue { background: #e6efff; color: #2f62d6; }
  .lr-pill--green { background: #e3f6e8; color: #1e8a43; }
  .lr-pill--amber { background: #fff3d1; color: #a26a00; }
  .lr-pill--red { background: #fde6e6; color: #c23434; }
  .lr-pill--purple { background: #efe8ff; color: #6b46c1; }
  .lr-pill--teal { background: #def6f3; color: #11806f; }
  .lr-pill--gray { background: #eef1f5; color: #5b6575; }

  /* Inline editors (status / assignee) */
  .lr-inline-edit {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    cursor: pointer;
    .lr-edit-hint {
      font-size: 1rem;
      color: var(--lr-muted);
      opacity: 0;
      transition: opacity 0.15s;
    }
    &:hover .lr-edit-hint {
      opacity: 1;
    }
  }
  .lr-inline-form {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    select {
      height: 36px;
      min-width: 160px;
      border-radius: 8px;
      font-size: 0.9rem;
    }
    i {
      font-size: 1.35rem;
      cursor: pointer;
    }
  }

  /* Row actions */
  .lr-row-menu-btn {
    border: 0;
    background: transparent;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    color: var(--lr-text-2);
    font-size: 1.3rem;
    line-height: 1;
    cursor: pointer;
    &:hover {
      background: #eef1f5;
      color: var(--lr-text);
    }
  }
  .lr-row-menu {
    border: 1px solid var(--lr-border);
    border-radius: 10px;
    box-shadow: 0 10px 28px rgba(15, 23, 42, 0.12);
    padding: 0.35rem 0;
    .dropdown-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 1rem;
      cursor: pointer;
    }
  }

  /* Empty state */
  .lr-empty {
    padding: 3.5rem 1rem;
    text-align: center;
    color: var(--lr-muted);
    i {
      font-size: 2.5rem;
      color: #c3cad6;
    }
    p {
      margin: 0.5rem 0 0.2rem;
      font-size: 1.05rem;
      font-weight: 500;
      color: var(--lr-text-2);
    }
  }
  .lr-empty-row:hover {
    background: transparent !important;
  }

  /* Compact mode */
  .compact .lr-table {
    font-size: 0.875rem;
    thead th {
      padding-top: 0.6rem;
      padding-bottom: 0.6rem;
    }
    tbody td {
      padding-top: 0.4rem;
      padding-bottom: 0.4rem;
    }
    .lr-avatar {
      width: 30px;
      height: 30px;
      font-size: 0.75rem;
    }
    .lr-date small {
      display: none;
    }
  }

  /* ---------- Footer / pagination ---------- */
  .lr-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    padding: 0.9rem 1.25rem;
    border-top: 1px solid var(--lr-border);
    background: var(--lr-surface);
  }
  .lr-footer-info {
    color: var(--lr-text-2);
    font-size: 0.95rem;
  }
  .lr-footer-right {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
  }
  .lr-pagination {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    list-style: none;
    margin: 0;
    padding: 0.25rem;
    border: 1px solid var(--lr-border);
    border-radius: 10px;
  }
  .lr-page-btn {
    min-width: 36px;
    height: 36px;
    padding: 0 0.6rem;
    border: 1px solid transparent;
    border-radius: 8px;
    background: transparent;
    color: var(--lr-text-2);
    font-size: 0.95rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    i {
      font-size: 1.25rem;
    }
    &:hover:not(:disabled):not(.active) {
      background: #f1f4f8;
      color: var(--lr-text);
    }
    &.active {
      border-color: var(--lr-primary);
      color: var(--lr-primary);
      background: var(--lr-primary-soft);
      font-weight: 600;
    }
    &:disabled {
      opacity: 0.4;
      cursor: not-allowed;
    }
  }
  .lr-page-gap {
    display: inline-block;
    min-width: 24px;
    text-align: center;
    color: var(--lr-muted);
  }
  .lr-per-page {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    margin: 0;
    color: var(--lr-text-2);
    font-size: 0.95rem;
    text-transform: none;
  }

  /* ---------- Modals (Columns / Filters) ---------- */
  .lr-modal {
    .modal-content {
      border: 1px solid var(--lr-border);
      border-radius: 16px;
      box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
      overflow: hidden;
    }
    .modal-dialog {
      max-width: 520px;
    }
    .lr-modal-sm {
      max-width: 400px;
    }
  }
  /* Filters: let dropdown menus and date popups extend past the dialog */
  #filters .modal-content {
    overflow: visible;
  }
  #filters .lr-modal-header {
    border-radius: 16px 16px 0 0;
  }
  #filters .lr-modal-footer {
    border-radius: 0 0 16px 16px;
  }
  #filters .lr-modal-body {
    max-height: none;
  }
  .lr-modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.25rem 1.4rem 1rem;
    border-bottom: 1px solid var(--lr-border);
  }
  .lr-modal-title {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--lr-text);
    letter-spacing: -0.01em;
  }
  .lr-modal-subtitle {
    margin: 0.2rem 0 0;
    font-size: 0.875rem;
    color: var(--lr-muted);
  }
  .lr-modal-close {
    flex-shrink: 0;
    width: 34px;
    height: 34px;
    border: 0;
    border-radius: 8px;
    background: transparent;
    color: var(--lr-muted);
    font-size: 1.4rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    &:hover {
      background: #f1f4f8;
      color: var(--lr-text);
    }
    i {
      position: static;
      top: 0;
    }
  }
  .lr-modal-meta {
    padding: 0.6rem 1.4rem;
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--lr-text-2);
    background: var(--lr-head-bg);
    border-bottom: 1px solid var(--lr-border);
  }
  .lr-modal-body {
    max-height: 60vh;
  }
  .lr-modal-footer {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 0.5rem;
    padding: 0.9rem 1.4rem;
    border-top: 1px solid var(--lr-border);
    background: var(--lr-surface);
  }
  .lr-modal-footer--split {
    justify-content: space-between;
  }
  .lr-modal-btn {
    height: 40px;
    padding: 0 1.25rem;
    border-radius: 10px;
    font-weight: 500;
  }
  .lr-btn-ghost {
    height: 40px;
    padding: 0 0.9rem;
    border-radius: 10px;
    border: 1px solid var(--lr-border);
    background: var(--lr-surface);
    color: var(--lr-text-2);
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-weight: 500;
    i {
      position: static;
      top: 0;
      font-size: 1.1rem;
    }
    &:hover {
      background: #f6f8fb;
      color: var(--lr-text);
    }
  }

  /* Column list */
  .lr-column-list {
    list-style: none;
    margin: 0;
    padding: 0.4rem 0;
  }
  .lr-column-item {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.55rem 1.4rem 0.55rem 1rem;
    background: var(--lr-surface);
    transition: background 0.12s;
    &:hover {
      background: var(--lr-row-hover);
    }
    &.is-hidden .lr-column-label {
      color: var(--lr-muted);
    }
    &.ghost {
      opacity: 0.6;
      background: var(--lr-primary-soft);
    }
  }
  .lr-drag-handle {
    cursor: grab;
    color: #b4bccb;
    font-size: 1.2rem;
    padding: 0.2rem;
    border-radius: 6px;
    &:hover {
      color: var(--lr-text-2);
      background: #eef1f5;
    }
    &:active {
      cursor: grabbing;
    }
  }
  .lr-column-label {
    flex: 1 1 auto;
    margin: 0;
    font-size: 0.95rem;
    font-weight: 500;
    color: var(--lr-text);
    text-transform: none;
    cursor: pointer;
    user-select: none;
  }

  /* Toggle switch (wraps the existing checkbox) */
  .lr-switch {
    position: relative;
    display: inline-block;
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
    .lr-switch-track {
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
    input:checked + .lr-switch-track {
      background: var(--lr-primary);
      &::after {
        transform: translateX(16px);
      }
    }
    input:focus-visible + .lr-switch-track {
      box-shadow: 0 0 0 3px rgba(90, 141, 238, 0.25);
    }
  }

  /* Filters */
  .lr-filter-body {
    padding: 1.25rem 1.4rem 0.5rem;
    overflow: visible;
  }
  .lr-field {
    margin-bottom: 1.1rem;
  }
  .lr-field-label {
    display: block;
    margin-bottom: 0.4rem;
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--lr-text-2);
    text-transform: none;
    letter-spacing: 0;
  }
  .lr-filter-body .v-select {
    .vs__dropdown-toggle {
      min-height: 44px;
      padding: 0 0.5rem 0 0.4rem;
      border: 1px solid var(--lr-border);
      border-radius: 10px;
      background: var(--lr-surface);
      transition: border-color 0.15s, box-shadow 0.15s;
    }
    &:hover .vs__dropdown-toggle {
      border-color: var(--lr-border-strong);
    }
    &.vs--open .vs__dropdown-toggle {
      border-color: var(--lr-primary);
      box-shadow: 0 0 0 3px rgba(90, 141, 238, 0.15);
    }
    .vs__search,
    .vs__selected {
      font-size: 0.95rem;
      color: var(--lr-text);
    }
    .vs__search::placeholder {
      color: var(--lr-muted);
    }
    .vs__dropdown-menu {
      border: 1px solid var(--lr-border);
      border-radius: 10px;
      box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
      margin-top: 4px;
      padding: 0.3rem 0;
    }
    .vs__dropdown-option {
      padding: 0.5rem 0.9rem;
    }
    .vs__dropdown-option--highlight {
      background: var(--lr-primary-soft);
      color: var(--lr-primary);
    }
    .vs__clear,
    .vs__open-indicator {
      fill: var(--lr-muted);
    }
  }
  .lr-date-range {
    display: flex;
    align-items: flex-end;
    gap: 0.6rem;
  }
  .lr-date-field {
    flex: 1 1 0;
    min-width: 0;
  }
  .lr-date-caption {
    display: block;
    margin-bottom: 0.25rem;
    font-size: 0.75rem;
    color: var(--lr-muted);
  }
  .lr-date-sep {
    padding-bottom: 0.7rem;
    color: var(--lr-muted);
  }
  .lr-date-field .mx-datepicker {
    width: 100%;
  }
  .lr-date-field .mx-input {
    height: 44px;
    padding: 0 2.2rem 0 0.9rem;
    border: 1px solid var(--lr-border);
    border-radius: 10px;
    box-shadow: none;
    background: var(--lr-surface) !important;
    font-size: 0.95rem;
    color: var(--lr-text);
    &:hover {
      border-color: var(--lr-border-strong);
    }
    &:focus {
      border-color: var(--lr-primary);
      box-shadow: 0 0 0 3px rgba(90, 141, 238, 0.15);
    }
  }

  /* ---------- Responsive ---------- */
  @media (max-width: 767.98px) {
    .lr-page {
      padding: calc(45px + 1rem) 1rem 2.75rem;
      height: auto;
    }
    .lr-table-wrap {
      max-height: 70vh;
    }
    .lr-title {
      font-size: 1.6rem;
    }
    .lr-search {
      max-width: none;
      flex-basis: 100%;
    }
    .lr-footer {
      justify-content: center;
    }
    .lr-footer-right {
      justify-content: center;
    }
  }

  /* ---------- Legacy rules kept for modals / date pickers ---------- */
  .smart-view-edit-action {
    &:hover {
      font-weight: 800;
    }
  }
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
    display: inline-block;
    width: 80%;
    background: white !important;
    padding-right: 0em;
    font-size: 1.4em;
    border: 0px;
    padding-left: 0px !important;
    margin-left: 3em !important;
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
