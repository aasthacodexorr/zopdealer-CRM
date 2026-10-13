<template>
  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <!-- form layout section start -->
    <div class="content-body">
      <form class="webapp-form">
        <section id="table-transactions">
          <div
            class="border-bottom mt-3 bg-white header-navbar set-dable-layer"
            style="background-color: transparent; z-index: 2"
          >
            <div class="content-wrapper pt-0 pb-1 mt-0">
              <div class="card shadow-none mb-0">
                <div class="row">
                  <div class="d-none d-sm-none d-md-block col-md-3">
                    <ul
                      class="ml-0 mt-1 list-group list-group-horizontal"
                      v-if="getInventoryIds.length == 0"
                    >
                      <li class="d-inline-block mr-1">
                        <div
                          class="btn-group mb-0 drop-value-set list-size-fillter"
                        >
                          <div class="dropdown">
                            <div class="dropdown">
                              <button
                                class="btn btn-outline-light dropdown-toggle mr-1"
                                type="button"
                                id="dropdownMenuButton"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                {{ selected_inventory }}
                              </button>
                              <div
                                class="dropdown-menu"
                                aria-labelledby="dropdownMenuButton"
                              >
                                <p class="border-bottom">
                                  <strong>Views</strong>
                                </p>
                                <p class="border-bottom border-top">
                                  <strong>Default Views</strong>
                                </p>
                                <a
                                  style="cursor: pointer"
                                  class="dropdown-item dropdown-item d-flex justify-content-between flex-row"
                                  v-for="inventory_view in getDefaultViews"
                                  :key="inventory_view.id"
                                  ><span
                                    class="d-inline-block flex-fill"
                                    @click="
                                      changeInventoryView(inventory_view.id)
                                    "
                                    >{{
                                      inventory_view.header
                                        | truncate(25, "...")
                                    }}</span
                                  ><router-link
                                    tag="td"
                                    class="d-inline-block"
                                    :to="{
                                      name: 'inventory-view-update',
                                      params: { id: inventory_view.id },
                                    }"
                                    exact
                                    :key="
                                      `inventory_default_view_${inventory_view.id}`
                                    "
                                  >
                                    <i
                                      class="bx bx-edit-alt smart-view-edit-action"
                                    ></i> </router-link
                                ></a>

                                <p class="border-bottom border-top">
                                  <strong>Custom Views</strong>
                                </p>
                                <span
                                  style="
                                    max-height: 200px;
                                    overflow-y: auto;
                                    width: 100%;
                                    display: inline-block;
                                  "
                                >
                                  <a
                                    style="cursor: pointer"
                                    class="dropdown-item dropdown-item d-flex justify-content-between flex-row"
                                    v-for="inventory_view in getCustomViews"
                                    :key="inventory_view.id"
                                    ><span
                                      class="d-inline-block flex-fill"
                                      @click="
                                        changeInventoryView(inventory_view.id)
                                      "
                                      >{{
                                        inventory_view.header
                                          | truncate(25, "...")
                                      }}</span
                                    ><router-link
                                      tag="td"
                                      class="d-inline-block"
                                      :to="{
                                        name: 'inventory-view-update',
                                        params: { id: inventory_view.id },
                                      }"
                                      exact
                                      :key="
                                        `inventory_custom_view_${inventory_view.id}`
                                      "
                                    >
                                      <i
                                        class="bx bx-edit-alt smart-view-edit-action"
                                      ></i> </router-link
                                  ></a>
                                </span>
                                <p class="border-top">
                                  <router-link
                                    tag="a"
                                    :to="{ name: 'inventory-view-create' }"
                                    class="btn btn-primary w-100 text-center"
                                    exact
                                  >
                                    + New Custom View</router-link
                                  >
                                </p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
                    <!-- bulk update buttons -->
                    <div
                      class="ml-0 mt-1 list-group list-group-horizontal"
                      role="alert"
                      v-else-if="!getCurrentSyndicationViewStatus"
                    >
                      <div class="d-inline-block mr-1">
                        <ul class="bulk-list" style="margin-bottom: 0px">
                          <li class="d-inline-block mr-1">
                            <span
                              class="form-control btn-outline-danger"
                              style="cursor: pointer"
                              @click="confirmDeleteAction"
                            >
                              Delete
                            </span>
                          </li>
                          <li class="d-inline-block mr-1">
                            <a
                              href="#"
                              data-toggle="modal"
                              data-target="#bulk-list"
                              class="btn btn-outline-primary"
                              >Bulk Update</a
                            >
                          </li>
                          <li class="mr-1 d-none">
                            <select class="form-control btn-outline-primary">
                              <option selected="" disabled="">
                                Bulk Actions
                              </option>
                              <option>Print Window Sticker</option>
                              <option>Print Buyer Guide</option>
                            </select>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <!-- end bulk update buttons -->
                    <!-- bulk syndication button : START -->
                    <div
                      class="ml-0 mt-1 list-group list-group-horizontal"
                      role="alert"
                      v-else-if="getCurrentSyndicationViewStatus"
                    >
                      <div class="d-inline-block mr-1">
                        <ul class="bulk-list" style="margin-bottom: 0px">
                          <li class="d-inline-block mr-1">
                            <a
                              href="#"
                              data-toggle="modal"
                              data-target="#bulk-syndication-update"
                              class="btn btn-outline-primary"
                              >Bulk Syndication</a
                            >
                          </li>
                        </ul>
                      </div>
                    </div>
                    <!-- bulk syndication button : END -->
                  </div>
                  <div class="col-md-4 col-xs-12 col-xl-5">
                    <div class="col-12">
                      <fieldset
                        class="form-label-group form-group position-relative has-icon-right mt-1"
                      >
                        <input
                          type="text"
                          id="searchText"
                          v-debounce:1000ms="searchAction"
                          v-model="searchString"
                          class="form-control round"
                          style="height: 2.6em"
                          placeholder="Search Anything ..."
                        />

                        <div class="form-control-position">
                          <i class="bx bx-search"></i>
                        </div>
                      </fieldset>
                    </div>
                  </div>
                  <div class="col-md-5 col-xl-4">
                    <ul class="mt-1 mt-1">
                      <li class="d-inline-block">
                        <div
                          class="btn-feild-box"
                          style="top: 16px; right: 15px"
                        >
                          <ul>
                            <router-link
                              tag="li"
                              :to="{ name: 'inventory-create' }"
                              class="d-inline-block"
                              exact
                            >
                              <a class="btn btn-primary">Add Inventory</a>
                            </router-link>
                            <li class="d-none d-sm-none d-md-inline-block">
                              <span
                                href="#"
                                :class="
                                  getFilterState
                                    ? 'bg-secondary bx-tada text-white'
                                    : ''
                                "
                                class="list-space border bx bxs-filter-alt"
                                title="Filters"
                                data-toggle="modal"
                                data-target="#filters"
                              ></span>
                            </li>
                            <li class="d-none d-sm-none d-md-inline-block">
                              <span
                                href="#"
                                title="Modify View"
                                class="list-space border bx bxs-grid"
                                data-toggle="modal"
                                data-target="#grid-list"
                              ></span>
                            </li>

                            <li class="d-inline-block" @click="changeCompact">
                              <span
                                title="Compact View"
                                class="list-space border bx"
                                :class="
                                  getCompact ? 'bx-expand' : 'bx-collapse'
                                "
                              ></span>
                            </li>
                            <li
                              v-if="
                                isAuthorized && !getCurrentSyndicationViewStatus
                              "
                              @click="changeSyndicationViewStatus"
                              class="d-inline-block"
                              style="cursor: pointer"
                            >
                              <span
                                title="Bulk Syndication Change"
                                class="list-space border bx bx-transfer"
                              ></span>
                            </li>
                            <li
                              v-else-if="getCurrentSyndicationViewStatus"
                              @click="changeSyndicationViewStatus"
                              class="d-inline-block"
                              style="cursor: pointer"
                            >
                              <span
                                title="Inventory View"
                                class="list-space border bx bx-transfer bg-secondary bx-tada text-white"
                              ></span>
                            </li>
                            <router-link
                              tag="li"
                              title="Settings"
                              :to="{ name: 'inventory-settings' }"
                              class="d-inline-block"
                              exact
                            >
                              <span
                                style="cursor: pointer"
                                class="list-space border bx bxs-cog"
                              ></span>
                            </router-link>
                            <li class="d-inline-block">
                              <div class="dropdown">
                                <span
                                  class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer list-space border"
                                  data-toggle="dropdown"
                                  aria-haspopup="true"
                                  aria-expanded="false"
                                  role="menu"
                                ></span>
                                <div class="dropdown-menu dropdown-menu-right">
                                  <router-link
                                    v-if="isAuthorized"
                                    tag="a"
                                    title="Bulk Price Updates"
                                    :to="{ name: 'inventory-updates-pricing' }"
                                    class="dropdown-item"
                                    exact
                                  >
                                    <span
                                      style="cursor: pointer"
                                      class="bx bxs-dollar-circle mr-1"
                                    ></span>
                                    Bulk Price Updates
                                  </router-link>
                                  <router-link
                                    v-if="isAuthorized"
                                    tag="a"
                                    title="Bulk Description Updates"
                                    :to="{
                                      name: 'inventory-updates-description',
                                    }"
                                    class="dropdown-item"
                                    exact
                                  >
                                    <span
                                      style="cursor: pointer"
                                      class="bx bx-text mr-1"
                                    ></span>
                                    Bulk Description Updates
                                  </router-link>
                                  <a
                                    v-if="isAuthorized"
                                    class="dropdown-item"
                                    @click="warningForPurgeDeletingInventories"
                                    style="cursor: pointer"
                                    title="Empty Recycle Bin"
                                  >
                                    <span class="bx bxs-eraser mr-1"></span>
                                    Empty Recycle Bin
                                  </a>
                                  <router-link
                                    tag="a"
                                    title="Bulk Import from Third Party Using CSV"
                                    :to="{ name: 'third-party-inventory' }"
                                    class="dropdown-item"
                                    exact
                                  >
                                    <span
                                      style="cursor: pointer"
                                      class="bx bxs-file-import mr-1"
                                    ></span>
                                    Bulk Import from Third Party Using CSV
                                  </router-link>
                                  <router-link
                                    tag="a"
                                    title="Quick Inventory Import Manually or via PDF"
                                    :to="{ name: 'inventory-import' }"
                                    class="dropdown-item"
                                    exact
                                  >
                                    <span
                                      style="cursor: pointer"
                                      class="bx bx-upload mr-1"
                                    ></span>
                                    Quick Inventory Import
                                  </router-link>
                                </div>
                              </div>
                            </li>
                          </ul>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--grid list modal -->
          <div
            class="modal fade text-left"
            id="grid-list"
            tabindex="-1"
            role="dialog"
            aria-labelledby="myModalLabel19"
            aria-hidden="true"
          >
            <div
              class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm"
              role="document"
            >
              <div class="modal-content">
                <div class="modal-header pl-1 pr-1">
                  <h3 class="content-header-title" id="myModalLabel19">
                    Column List
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
                <div class="modal-body p-0">
                  <!-- Draggable List : OPEN -->
                  <draggable
                    tag="ul"
                    :list="list"
                    v-bind="dragOptions"
                    @start="isDragging = true"
                    @end="isDragging = false"
                    class="list-group"
                    @change="unsavedView"
                    handle=".handle"
                  >
                    <transition-group type="transition" name="flip-list">
                      <li
                        class="list-group-item"
                        v-for="(element, idx) in list"
                        :key="element.header"
                      >
                        <span
                          style="cursor: pointer"
                          class="bx bx-grid-vertical pr-1 handle"
                        ></span>
                        <div class="checkbox">
                          <input
                            type="checkbox"
                            @change="unsavedView"
                            v-model="element.visibility"
                            class="checkbox-input"
                            value="photodrag"
                            :id="'dragging_' + idx"
                            :true-value="'1'"
                            :false-value="'0'"
                          />
                          <label :for="'dragging_' + idx">{{
                            element.header
                          }}</label>
                        </div>
                      </li>
                    </transition-group>
                  </draggable>
                  <!-- Draggable List : CLOSE -->
                </div>
              </div>
            </div>
          </div>

          <!-- bulk syndication : START -->
          <div
            class="modal fade text-left"
            id="bulk-syndication-update"
            tabindex="-1"
            role="dialog"
            aria-labelledby="Bulk Syndication Modal"
            aria-hidden="true"
          >
            <div
              class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
              role="document"
            >
              <div class="modal-content">
                <div class="modal-header pl-1 pr-1">
                  <h3 class="content-header-title" id="myModalLabel19">
                    Bulk Syndication Update
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
                      <label>Action</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <select
                        class="custom-select form-control"
                        v-model="syndication.action"
                        id="feature_listing"
                        name="feature_listing"
                      >
                        <option value="" disabled="">- Select Action -</option>
                        <option value="ADD">ADD</option>
                        <option value="REMOVE">REMOVE</option>
                      </select>
                    </div>
                    <div class="col-md-12">
                      <label>Available List</label>
                    </div>
                    <div
                      class="col-md-6"
                      :key="`inventory_${active_syndication.slug}_checkbox`"
                      v-for="active_syndication in getActiveSyndicationList"
                    >
                      <div class="checkbox">
                        <input
                          type="checkbox"
                          class="checkbox-input"
                          :id="
                            active_syndication.slug + '_syndication_checkbox'
                          "
                          v-model="syndication.slugs"
                          :value="active_syndication.slug"
                        />
                        <label
                          :for="
                            active_syndication.slug + '_syndication_checkbox'
                          "
                        >
                          {{ active_syndication.label }}
                        </label>
                      </div>
                    </div>
                    <div class="col-sm-12 d-flex justify-content-left pb-1">
                      <button
                        type="close"
                        @click="confirmBulkSyndicationUpdate"
                        class="btn mr-1 mt-1"
                        :class="getBulkSydnicationConfirmButtonClass"
                        data-dismiss="modal"
                        aria-label="Close"
                      >
                        {{ syndication.action }}
                      </button>
                      <button
                        type="close"
                        @click="resetSyndicationColumns"
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
          <!-- bulk syndication : END -->

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
                    Inventory Bulk Update
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
                      <label>Featured Vehicle</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <select
                        class="custom-select form-control"
                        v-model="form.feature_listing"
                        id="feature_listing"
                        name="feature_listing"
                      >
                        <option value="" selected="" disabled="">
                          - Select Featured -
                        </option>
                        <option value="1">Featured</option>
                        <option value="0">Not Featured</option>
                      </select>
                    </div>
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
                              v-for="status in getStatuses"
                              :key="status"
                            >
                              {{ status }}
                            </option>
                          </select>
                        </div>
                      </fieldset>
                    </div>
                    <div class="col-md-5">
                      <label>Sub Status</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <fieldset>
                        <div class="input-group">
                          <select
                            class="custom-select form-control"
                            v-model="form.sub_status"
                            id="sub_status"
                            name="sub_status"
                          >
                            <option value="" selected disabled>
                              - Select Sub Status -
                            </option>
                            <option
                              :value="status"
                              v-for="status in getSubStatues"
                              :key="status"
                            >
                              {{ status }}
                            </option>
                          </select>
                        </div>
                      </fieldset>
                    </div>
                    <div class="col-md-5">
                      <label>Vehicle Type</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <select
                        class="custom-select form-control"
                        v-model="form.vehicle_type"
                        id="vehicle_type"
                        name="vehicle_type"
                      >
                        <option value="" selected disabled>
                          - Select Vehicle Type -
                        </option>
                        <option
                          :value="vehicle_tyoe"
                          v-for="vehicle_tyoe in getVehicleTypes"
                          :key="vehicle_tyoe"
                        >
                          {{ vehicle_tyoe }}
                        </option>
                      </select>
                    </div>
                    <div class="col-md-5">
                      <label>Visibility</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <select
                        class="custom-select form-control"
                        v-model="form.visibility"
                        id="visibility"
                        name="visibility"
                      >
                        <option value="" selected="" disabled="">
                          - Select Visibility -
                        </option>
                        <option value="1">ON</option>
                        <option value="0">OFF</option>
                      </select>
                    </div>
                    <div class="col-md-5">
                      <label>Body Type</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <select
                        class="custom-select form-control mr-1"
                        v-model="form.body_type"
                        id="body_type"
                        name="body_type"
                      >
                        <option value="" selected disabled>
                          - Select Body Type -
                        </option>
                        <option
                          :value="body_type"
                          v-for="body_type in body_types"
                          :key="body_type"
                          v-text="body_type"
                        ></option>
                      </select>
                    </div>
                    <div class="col-md-5">
                      <label>Transmission</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <select
                        class="custom-select form-control mr-1"
                        id="transmission"
                        v-model="form.transmission"
                        name="transmission"
                      >
                        <option value="" selected disabled>
                          - Select Transmission -
                        </option>
                        <option
                          :value="transmission"
                          v-for="transmission in transmissions"
                          :key="transmission"
                          v-text="transmission"
                        ></option>
                      </select>
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
          <!-- Filter Model : START -->
          <div
            class="modal fade text-left"
            id="filters"
            tabindex="-1"
            role="dialog"
            aria-labelledby="myModalLabel19"
            aria-hidden="true"
          >
            <div
              class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
              role="document"
            >
              <div class="modal-content" id="filterInventoryModel">
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
                    <!-- year : START -->
                    <div class="col-md-5">
                      <label>Year</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <v-select
                        id="filter-year"
                        placeholder="Year"
                        v-model="yearFilter"
                        clearable
                        :options="getYears"
                      >
                      </v-select>
                    </div>
                    <!-- year : END -->

                    <!-- make : START -->
                    <div class="col-md-5">
                      <label>Make</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <v-select
                        id="filter-make"
                        placeholder="Make"
                        v-model="makeFilter"
                        clearable
                        :options="getMakes"
                      >
                      </v-select>
                    </div>
                    <!-- make : END -->

                    <!-- model : START -->
                    <div class="col-md-5">
                      <label>Model</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <v-select
                        id="filter-model"
                        placeholder="Model"
                        v-model="modelFilter"
                        clearable
                        :options="getModels"
                      >
                      </v-select>
                    </div>
                    <!-- model : END -->

                    <!-- status : START -->
                    <div class="col-md-5">
                      <label>Status</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <v-select
                        id="filter-status"
                        placeholder="Status"
                        v-model="statusFilter"
                        clearable
                        :options="getStatuses"
                      >
                      </v-select>
                    </div>
                    <!-- status : END -->

                    <!-- visibility : START -->
                    <div class="col-md-5">
                      <label>Visibility</label>
                    </div>
                    <div class="col-md-7 form-group">
                      <v-select
                        id="filter-visibility"
                        placeholder="Visibility"
                        v-model="visibilityFilter"
                        clearable
                        :reduce="(visibility) => visibility.value"
                        :options="[
                          { label: 'ON', value: '1' },
                          { label: 'OFF', value: '0' },
                        ]"
                      >
                      </v-select>
                    </div>
                    <!-- visibility : END -->

                    <div class="col-xs-12 p-1"></div>

                    <div class="col-sm-12 d-flex justify-content-left pb-1">
                      <button
                        type="close"
                        @click="filterInventory"
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
          <div class="pr-0 pl-0">
            <div class="content-body">
              <div class="card shadow-none mb-0">
                <div
                  class="table-responsive tab-wid check-bulk"
                  :class="getCompact ? 'compact' : ''"
                  style="height: 70vh"
                >
                  <table
                    id="table-extended-transactions"
                    class="list-page-content-p mt-0 mb-0"
                  >
                    <thead>
                      <tr class="bg-grey">
                        <th
                          class="left-space-box"
                          id="d-list"
                          style="width: 40px"
                        >
                          <input
                            class="select_check"
                            type="checkbox"
                            v-model="bulk_select"
                            :true-value="'1'"
                            :false-value="'0'"
                          />
                        </th>
                        <th style="width: 20px"></th>
                        <th style="width: 100px"></th>
                        <th style="width: 90px" class="left-set">
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            v-if="getSortColumn.hasOwnProperty('year')"
                          >
                            <span
                              @click="updateSortColumn('year', 'ASC')"
                              v-if="getSortColumn['year'] == 'DESC'"
                              >Year <i class="bx bx-sort-down"></i>
                            </span>
                            <span
                              @click="updateSortColumn('year', 'DESC')"
                              v-else-if="getSortColumn['year'] == 'ASC'"
                              >Year <i class="bx bx-sort-up"></i>
                            </span>
                          </span>
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            @click="updateSortColumn('year', 'ASC')"
                            v-else
                            >Year <i class="bx bx-menu"></i>
                          </span>
                        </th>
                        <th style="width: 90px" class="left-set">
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            v-if="getSortColumn.hasOwnProperty('make')"
                          >
                            <span
                              @click="updateSortColumn('make', 'ASC')"
                              v-if="getSortColumn['make'] == 'DESC'"
                              >Make <i class="bx bx-sort-down"></i>
                            </span>
                            <span
                              @click="updateSortColumn('make', 'DESC')"
                              v-else-if="getSortColumn['make'] == 'ASC'"
                              >Make <i class="bx bx-sort-up"></i>
                            </span>
                          </span>
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            @click="updateSortColumn('make', 'ASC')"
                            v-else
                            >Make <i class="bx bx-menu"></i>
                          </span>
                        </th>
                        <th style="width: 100px" class="left-set">
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            v-if="getSortColumn.hasOwnProperty('model')"
                          >
                            <span
                              @click="updateSortColumn('model', 'ASC')"
                              v-if="getSortColumn['model'] == 'DESC'"
                              >Model <i class="bx bx-sort-down"></i>
                            </span>
                            <span
                              @click="updateSortColumn('model', 'DESC')"
                              v-else-if="getSortColumn['model'] == 'ASC'"
                              >Model <i class="bx bx-sort-up"></i>
                            </span>
                          </span>
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            @click="updateSortColumn('model', 'ASC')"
                            v-else
                            >Model <i class="bx bx-menu"></i>
                          </span>
                        </th>
                        <th style="width: 90px" class="left-set">
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            v-if="getSortColumn.hasOwnProperty('trim')"
                          >
                            <span
                              @click="updateSortColumn('trim', 'ASC')"
                              v-if="getSortColumn['trim'] == 'DESC'"
                              >Trim <i class="bx bx-sort-down"></i>
                            </span>
                            <span
                              @click="updateSortColumn('trim', 'DESC')"
                              v-else-if="getSortColumn['trim'] == 'ASC'"
                              >Trim <i class="bx bx-sort-up"></i>
                            </span>
                          </span>
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            @click="updateSortColumn('trim', 'ASC')"
                            v-else
                            >Trim <i class="bx bx-menu"></i>
                          </span>
                        </th>
                        <th style="width: 90px" class="left-set">
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            v-if="getSortColumn.hasOwnProperty('vin')"
                          >
                            <span
                              @click="updateSortColumn('vin', 'ASC')"
                              v-if="getSortColumn['vin'] == 'DESC'"
                              >VIN <i class="bx bx-sort-down"></i>
                            </span>
                            <span
                              @click="updateSortColumn('vin', 'DESC')"
                              v-else-if="getSortColumn['vin'] == 'ASC'"
                              >VIN <i class="bx bx-sort-up"></i>
                            </span>
                          </span>
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            @click="updateSortColumn('vin', 'ASC')"
                            v-else
                            >VIN <i class="bx bx-menu"></i>
                          </span>
                        </th>
                        <th style="width: 160px" class="left-set">
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            v-if="getSortColumn.hasOwnProperty('stock_no')"
                          >
                            <span
                              @click="updateSortColumn('stock_no', 'ASC')"
                              v-if="getSortColumn['stock_no'] == 'DESC'"
                              >Stock <i class="bx bx-sort-down"></i>
                            </span>
                            <span
                              @click="updateSortColumn('stock_no', 'DESC')"
                              v-else-if="getSortColumn['stock_no'] == 'ASC'"
                              >Stock <i class="bx bx-sort-up"></i>
                            </span>
                          </span>
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            @click="updateSortColumn('stock_no', 'ASC')"
                            v-else
                            >Stock <i class="bx bx-menu"></i>
                          </span>
                        </th>
                        <th style="min-width: 150px" class="text-center">
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            v-if="getSortColumn.hasOwnProperty('days_in_stock')"
                          >
                            <span
                              @click="updateSortColumn('days_in_stock', 'ASC')"
                              v-if="getSortColumn['days_in_stock'] == 'DESC'"
                              >DIS <i class="bx bx-sort-down"></i>
                            </span>
                            <span
                              @click="updateSortColumn('days_in_stock', 'DESC')"
                              v-else-if="
                                getSortColumn['days_in_stock'] == 'ASC'
                              "
                              >DIS <i class="bx bx-sort-up"></i>
                            </span>
                          </span>
                          <span
                            style="cursor: pointer"
                            class="btn btn-link p-0"
                            @click="updateSortColumn('days_in_stock', 'ASC')"
                            v-else
                            >DIS <i class="bx bx-menu"></i>
                          </span>
                        </th>
                        <template v-for="element in list">
                          <th
                            class="text-center"
                            style="min-width: 150px"
                            :key="element.header"
                            v-if="
                              element.visibility == '1' &&
                                !getCurrentSyndicationViewStatus
                            "
                          >
                            <span
                              style="cursor: pointer"
                              class="btn btn-link p-0"
                              v-if="
                                getSortColumn.hasOwnProperty(element.column)
                              "
                            >
                              <span
                                @click="updateSortColumn(element.column, 'ASC')"
                                v-if="getSortColumn[element.column] == 'DESC'"
                                >{{ element.header }}
                                <i class="bx bx-sort-down"></i>
                              </span>
                              <span
                                @click="
                                  updateSortColumn(element.column, 'DESC')
                                "
                                v-else-if="
                                  getSortColumn[element.column] == 'ASC'
                                "
                                >{{ element.header }}
                                <i class="bx bx-sort-up"></i>
                              </span>
                            </span>
                            <span
                              style="cursor: pointer"
                              class="btn btn-link p-0"
                              @click="updateSortColumn(element.column, 'ASC')"
                              v-else
                              >{{ element.header }} <i class="bx bx-menu"></i>
                            </span>
                          </th>
                        </template>
                        <template v-if="getCurrentSyndicationViewStatus">
                          <th
                            class="text-center"
                            style="min-width: 150px"
                            :key="`inventory_${active_syndication.slug}_header`"
                            v-for="active_syndication in getActiveSyndicationList"
                          >
                            {{ active_syndication.label }}
                          </th>
                        </template>
                      </tr>
                    </thead>
                    <tbody>
                      <inventory-item
                        v-for="item in getEntities"
                        :placeholder_image="placeholder_image"
                        :item="item"
                        :time_zone="timeZoneName"
                        :childCheck="bulk_select"
                        :app_metadata="app_metadata"
                        v-bind:key="item.inventory_id + '_item_listing_view'"
                      ></inventory-item>
                    </tbody>
                  </table>
                </div>
                <!-- datatable ends -->

                <!-- Navigation Pagination : START -->
                <div class="row content-wrapper mt-1 pt-0">
                  <div class="col-sm-4">
                    <div
                      class="dataTables_info"
                      id="table-extended-transactions_info"
                      role="status"
                      aria-live="polite"
                    >
                      Total Records {{ getPager.total }}
                    </div>
                  </div>
                  <div class="col-sm-5 text-right d-list-box">
                    <div
                      class="dataTables_length"
                      id="table-extended-transactions_length"
                    >
                      <label>
                        <select
                          v-model="getNumberOfEntities"
                          name="table-extended-transactions_length"
                          aria-controls="table-extended-transactions"
                          class="custom-select custom-select-sm form-control form-control-sm"
                        >
                          <option value="10">10</option>
                          <option value="25">25</option>
                          <option value="50">50</option>
                          <option value="100">100</option>
                          <option value="200">200</option>
                          <option value="250">250</option>
                        </select>
                        per Page
                      </label>
                    </div>
                  </div>
                  <div class="col-sm-3 text-right">
                    <div
                      class="dataTables_paginate paging_simple_numbers"
                      id="table-extended-transactions_paginate"
                    >
                      <ul class="pagination">
                        <li
                          style="cursor: pointer; padding-right: 0.2em"
                          class="paginate_button page-item previous"
                          @click="paginationWithUrl(getPager.previous)"
                          :class="{ disabled: getPager.previous == null }"
                          id="table-extended-transactions_previous"
                        >
                          <a
                            aria-controls="table-extended-transactions"
                            data-dt-idx="0"
                            tabindex="0"
                            class="page-link"
                            >&lt;</a
                          >
                        </li>
                        <li
                          style="cursor: pointer; padding-right: 0.2em"
                          class="paginate_button page-item"
                          :class="{ active: n === getCurrentPage }"
                          v-show="
                            n >= getCurrentPage - 2 && n <= getCurrentPage + 2
                          "
                          v-for="n in getPager.pageCount"
                          :key="'nav_id' + n"
                        >
                          <a
                            aria-controls="table-extended-transactions"
                            @click="pagination(n)"
                            :data-dt-idx="n"
                            tabindex="0"
                            class="page-link"
                            >{{ n }}</a
                          >
                        </li>
                        <li
                          style="cursor: pointer; padding-right: 0.2em"
                          class="paginate_button page-item next"
                          :class="{ disabled: getPager.next == null }"
                          id="table-extended-transactions_next"
                        >
                          <a
                            aria-controls="table-extended-transactions"
                            @click="paginationWithUrl(getPager.next)"
                            data-dt-idx="3"
                            tabindex="0"
                            class="page-link"
                            >&gt;</a
                          >
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <!-- Navigation Pagination : END -->
              </div>
            </div>
          </div>
        </section>
        <!-- form layout section end -->
      </form>
    </div>
    <inventory-snap-shot></inventory-snap-shot>
  </div>
  <!-- END: Content-->
</template>
<script>
import draggable from "vuedraggable";
import InventoryItem from "./InventoryItem.vue";
import { mapGetters, mapActions } from "vuex";
import Toast from "../../library/Toast";
import vSelect from "vue-select";
import InventorySnapShot from "./InventorySnapShot.vue";

export default {
  props: {
    application: { required: true },
    app_metadata: { required: true },
    user_metadata: { required: true },
  },
  name: "InventoryList",
  components: {
    "v-select": vSelect,
    InventoryItem,
    draggable,
    InventorySnapShot,
  },
  watch: {
    single_search_inventory: function(newVal, oldVal) {
      if (newVal.hasOwnProperty("inventory_id")) {
        if (!isNaN(newVal.inventory_id)) {
          this.$router.push({
            name: "inventory-update",
            params: { id: newVal.inventory_id },
          });
        }
      }
    },
  },
  data() {
    return {
      load: false,
      page: 1,
      bulk_select: "0",
      inventory_heading: "Inventories",
      inventory_listing: "",
      pager: "",
      inventory_views: [],
      inventory_items: [],
      single_search_inventory: null,
      inventory_id: 1,
      dragging: true,
      inventory_view_flag: 1,
      inventory_view_default: [],
      body_types: [],
      transmissions: [],
      sort_column: { created_at: "DESC" },
      placeholder_image: "",
      search: false,
      search_string: "",
      form: {
        feature_listing: "",
        status: "",
        sub_status: "",
        vehicle_type: "",
        visibility: "",
        body_type: "",
        transmission: "",
      },
      syndication: {
        slugs: [],
        action: "ADD",
      },
    };
  },
  computed: {
    ...mapGetters("inventory", ["getInventoryIds"]),
    ...mapGetters("inventoryView", [
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
      "getStatuses",
      "getSubStatues",
      "getVehicleTypes",
      "getAvailableFilters",
      "getFilters",
      "getFilterState",
      "getCurrentSyndicationViewStatus",
      "getActiveSyndicationList",
    ]),
    getDefaultViews() {
      return this.inventory_views.filter((view) => view.default == "1");
    },
    getCustomViews() {
      return this.inventory_views.filter((view) => view.default == "0");
    },
    timeZoneName() {
      return this.user_metadata && this.user_metadata.hasOwnProperty("timezone")
        ? this.user_metadata.timezone
        : "Canada/Eastern";
    },
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: false,
        ghostClass: "ghost",
      };
    },
    getBulkSydnicationConfirmButtonClass() {
      return this.syndication.action === "ADD" ? "btn-primary" : "btn-danger";
    },
    selected_inventory() {
      return this.getViewTitle.length > 12
        ? this.getViewTitle.substring(0, 12 - 3) + "..."
        : this.getViewTitle;
    },
    getYears() {
      if (this.getAvailableFilters.hasOwnProperty("year")) {
        if (Array.isArray(this.getAvailableFilters["year"])) {
          return this.getAvailableFilters["year"]
            .filter((x) => x.name != "")
            .map((x) => x.name);
        }
      }

      return [];
    },
    getMakes() {
      if (this.getAvailableFilters.hasOwnProperty("make")) {
        if (Array.isArray(this.getAvailableFilters["make"])) {
          return this.getAvailableFilters["make"]
            .filter((x) => x.name != "")
            .map((x) => x.name);
        }
      }

      return [];
    },
    getModels() {
      if (this.getAvailableFilters.hasOwnProperty("model")) {
        if (Array.isArray(this.getAvailableFilters["model"])) {
          return this.getAvailableFilters["model"]
            .filter((x) => x.name != "")
            .map((x) => x.name);
        }
      }

      return [];
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
    yearFilter: {
      get() {
        return this.getFilters.year;
      },
      set(value) {
        this.commitFilters({ name: "year", value: value });
      },
    },
    makeFilter: {
      get() {
        return this.getFilters.make;
      },
      set(value) {
        this.commitFilters({ name: "make", value: value });
      },
    },
    modelFilter: {
      get() {
        return this.getFilters.model;
      },
      set(value) {
        this.commitFilters({ name: "model", value: value });
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
    visibilityFilter: {
      get() {
        return this.getFilters.visibility;
      },
      set(value) {
        this.commitFilters({ name: "visibility", value: value });
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
  },
  methods: {
    ...mapActions("inventory", [
      "removeEveryInventoryId",
      "addEveryInventoryId",
    ]),
    ...mapActions("inventoryView", [
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
      "commitAvailableFilters",
      "commitFilters",
      "commitFilterState",
      "commitCurrentSyndicationViewStatus",
      "commitActiveSyndicationList",
    ]),
    searchAction(value, e) {
      if (value.length >= 3) {
        this.commitSearchState(true);
        // this.commitSearchString(value);
        this.searchInventory(this.getSearchString);
      } else {
        if (this.getSearch) {
          this.inventoryListing(true);
          this.commitSearchState(false);
        }
      }
    },
    changeCompact() {
      this.commitCompactFlag(!this.getCompact);
    },
    changeSyndicationViewStatus() {
      this.commitCurrentSyndicationViewStatus(
        !this.getCurrentSyndicationViewStatus,
      );
    },
    unsavedView() {
      const vm = this;
      //check for the flag if flag is 0 that means the pop up is there and dont do any thing.
      // If flag is 1 that means reset the value to 0, show the popup
      if (this.inventory_view_flag == 1) {
        this.inventory_view_flag = 0;
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
              JSON.parse(JSON.stringify(this.inventory_view_default)),
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
                `/api/business/inventories/views/update/${this.getViewId}`,
            }).then((response) => {
              this.$swal({
                showConfirmButton: false,
                icon: "success",
                toast: true,
                timer: 2000,
                position: "bottom",
                title: "Inventory View Changed.",
              });
            });
          }
          this.inventory_view_flag = 1;
        });
      }
    },
    resetFilters() {
      this.commitFilters({ name: "model", value: "" });
      this.commitFilters({ name: "year", value: "" });
      this.commitFilters({ name: "make", value: "" });
      this.commitFilters({ name: "status", value: "" });
      this.commitFilters({ name: "visibility", value: "" });

      if (this.getSearch) {
        this.searchInventory(this.getSearchString);
      } else {
        this.inventoryListing(true);
      }
      this.commitFilterState(false);
    },
    purgeDeletedInventories() {
      const vm = this;

      //message during request
      let request_message = `Cleaning deleted inventories...`;

      const toast = new Toast(request_message);
      toast.fire();

      axios({
        method: "post",
        url: window.location.origin + "/api/business/inventory/delete/purge",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          "Content-Type": "multipart/form-data",
        },
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
        })
        .catch(function(error) {
          let error_message = error.response.data;
          toast.fail(error_message);
          toast.close();
        });
    },
    async warningForPurgeDeletingInventories() {
      const vm = this;
      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Back",
        confirmButtonText: "I Understand",
        toast: false,
        position: "center",
        text: `This action will clean the old deleted inventories. This action is permanent and inventories cannot be recovered back once cleaned.`,
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.purgeDeletedInventories();
        }
      });
    },
    filterInventory() {
      let vm = this;
      let url = window.location.origin;
      url += `/api/business/inventory?all=true`;

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
        this.commitViewEntities(response.data.inventory);
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
          title: `Inventory has been filtered.`,
        });
      });
    },
    searchInventory(search) {
      let vm = this;

      let url = window.location.origin;
      url += `/api/business/inventory?all=true`;

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

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data.inventory);
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
          `/api/business/inventories/views/update/${this.getViewId}`,
      }).then((response) => {
        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: "Inventory View Changed.",
        });
        this.commitLoadState(false);
        vm.inventoryListing();
      });
    },
    changeInventoryView(id) {
      this.commitSearchString("");
      this.commitSearchState(false);
      this.commitFilterState(false);
      this.commitFilters({ name: "model", value: "" });
      this.commitFilters({ name: "year", value: "" });
      this.commitFilters({ name: "make", value: "" });
      this.commitFilters({ name: "status", value: "" });
      this.commitFilters({ name: "visibility", value: "" });

      //this.getViewId = id;
      let vm = this;
      this.clearBulkSelection();
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url:
          window.location.origin +
          `/api/business/inventories/views/result/${id}`,
      }).then((response) => {
        this.commitViewId(response.data.inventory_id);
        this.commitViewTitle(response.data.header);
        this.commitViewEntities(response.data.inventory);
        this.commitRequestUrl(
          window.location.origin +
            `/api/business/inventories/views/result/${id}`,
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
        this.inventory_view_default = JSON.parse(
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
            `/api/business/user/inventory/update?id=${this.getViewId}`,
        }).then((response) => {
          this.$swal({
            showConfirmButton: false,
            icon: "success",
            toast: true,
            timer: 2000,
            position: "bottom",
            title: "Inventory View Changed.",
          });
        });
      });
    },

    pagination(n = 1) {
      const vm = this;
      let url = window.location.origin;
      if (this.getSearch || this.getFilterState) {
        url += `/api/business/inventory?all=true`;

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
        url += `/api/business/inventories/views/result/${this.getViewId}?page=`;
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
        this.commitViewEntities(response.data.inventory);
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
        this.commitViewEntities(response.data.inventory);
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
    view_inventories() {
      let vm = this;
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + "/api/business/inventories/views",
      }).then((response) => {
        vm.$data.inventory_views = response.data.inventory_views;
      });
    },
    async inventoryListing(refresh = false) {
      let vm = this;

      if (refresh || !this.getLoadState) {
        await axios({
          method: "get",
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
          url: window.location.origin + `/api/business/user/inventory`,
        }).then((response) => {
          this.commitViewId(response.data.inventory_id);
          this.commitViewTitle(response.data.header);
          this.commitViewEntities(response.data.inventory);
          this.commitRequestUrl(
            window.location.origin + `/api/business/user/inventory`,
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
          this.inventory_view_default = JSON.parse(
            JSON.stringify(Object.values(response.data.view)),
          );
        });

        this.commitLoadState(true);
      }
    },
    clearBulkSelection() {
      this.bulk_select = "0";
      this.removeEveryInventoryId();
    },
    getBodyTypes() {
      axios
        .get("/json/body_type.json")
        .then(({ data }) => (this.body_types = data));
    },
    getTransmissions() {
      axios
        .get("/json/transmission.json")
        .then(({ data }) => (this.transmissions = data));
    },
    resetBulkColumns() {
      Object.keys(this.form).forEach((key) => {
        this.form[key] = "";
      });
    },
    resetSyndicationColumns() {
      this.syndication.slugs = [];
      this.syndication.action = "ADD";
    },
    applyBulkOptions() {
      const vm = this;
      let formData = new FormData();
      formData.append("inventory_ids", this.getInventoryIds);
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key)) {
          formData.append(key, this.form[key]);
        }
      }

      let message = `Updating selected inventories...`;
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/inventory/updates`,
      })
        .then((response) => {
          this.resetBulkColumns();
          /** Purposefully set to 0 (integer) to fail the prop check so subcomponents become reactive to change */
          vm.$data.bulk_select = 0;

          setTimeout(() => {
            this.pagination(this.getCurrentPage);
            toast.success(`Inventories are updated successfully.`);
            toast.close();
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
              title: "No columns or rows were selected.",
            });
          }, 1000);
        });
    },
    confirmBulkSyndicationUpdate() {
      const vm = this;
      this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Confirm",
        toast: false,
        position: "center",
        text: `Do you want to ${
          this.syndication.action
        } ${this.syndication.slugs.join(", ").toUpperCase()} for inventories?`,
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.updateBulkSyndication(
            vm.syndication.slugs,
            vm.getInventoryIds,
            vm.syndication.action,
          );
        }
      });
    },
    updateBulkSyndication(slugs, inventory_ids, action) {
      const vm = this;
      let formData = new FormData();
      formData.append("slugs", slugs);
      formData.append("inventory_ids", inventory_ids);
      formData.append("action", action);

      let message = `Updating selected inventories...`;
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: `${window.location.origin}/api/business/inventory/syndication/updates`,
      })
        .then((response) => {
          this.resetSyndicationColumns();
          /** Purposefully set to 0 (integer) to fail the prop check so subcomponents become reactive to change */
          vm.$data.bulk_select = 0;

          setTimeout(() => {
            this.pagination(this.getCurrentPage);
            toast.success(`Inventories are updated successfully.`);
            toast.close();
          }, 1000);
        })
        .catch(function(error) {
          vm.resetSyndicationColumns();
          vm.$data.bulk_select = 0;
          setTimeout(() => {
            vm.$swal({
              showConfirmButton: false,
              icon: "error",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "No columns or rows were selected.",
            });
          }, 1000);
        });

      this.$helpers.gTagClickEvent(
        this,
        "zd_action",
        "inventory",
        "inventory_bulk_syndication",
        "",
      );
    },
    applyBulkDelete() {
      const vm = this;
      let formData = new FormData();
      formData.append("inventory_ids", this.getInventoryIds);
      axios({
        method: "post",
        data: formData,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/inventory/deletes`,
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
        text: "Please confirm if you want to delete the inventories.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
          //@TODO   Reset the selection
        } else if (result.isConfirmed) {
          vm.applyBulkDelete();
        }
      });
    },
    getPlaceholder() {
      let request_url =
        window.location.origin + "/api/business/image/placeholder";
      axios({
        method: "get",
        url: request_url,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      }).then(({ data }) => {
        this.placeholder_image = this.$helpers.parseAbsoluteOrRelativeUrl(
          window.global.cdn,
          data.image_url,
        );
      });
    },
  },
  created() {
    let vm = this;
    moment.tz.setDefault(this.timeZoneName);
    this.view_inventories();

    if (this.getSearch && !this.getLoadState) {
      this.searchAction(this.getSearchString);
    } else {
      this.inventoryListing();
    }
    
    this.getBodyTypes();
    this.getTransmissions();
    this.getPlaceholder();
    this.commitActiveSyndicationList();

    if (Object.keys(this.getAvailableFilters).length === 0) {
      this.commitAvailableFilters();
    }
  },
  mounted() {
    const vm = this;
    if (Number.isInteger(parseInt(this.getCurrentEntity))) {
      let rows = document.querySelector("#inventory_" + this.getCurrentEntity);

      // line is zero-based
      // line is the row number that you want to see into view after scroll
      rows.style.background = "#e7e7e7";
      rows.scrollIntoView({
        behavior: "smooth",
        block: "center",
      });
    }

    var input = document.getElementById("searchText");
    input.addEventListener("keydown", function(event) {
      if (event.keyCode === 13) {
        event.preventDefault();
        this.searchAction();
      }
    });

    Event.listen(
      "update_bulk_syndication",
      ({ slugs, inventory_ids, action }) => {
        vm.updateBulkSyndication(slugs, inventory_ids, action);
      },
    );
  },
  beforeDestroy() {
    Event.vue.$off("update_bulk_syndication", null);
  },
};
</script>

<style lang="scss">
.compact > table.list-page-content-p th,
.compact > table.list-page-content-p th {
  padding: 2px !important;
  margin: 0px !important;
  min-width: auto !important;
  width: auto !important;
  font-size: 0.9em;
  span,
  a {
    font-size: 0.9em;
  }
}

.compact > table.list-page-content-p th,
.compact > table.list-page-content-p td {
  padding: 2px !important;
  margin: 0px !important;
  min-width: auto !important;
  width: auto !important;
  font-size: 0.9em;
  span,
  a {
    font-size: 0.9em;
  }
}

@media (max-width: 1280px) {
  table.list-page-content-p th,
  table.list-page-content-p th {
    padding: 2px !important;
    margin: 0px !important;
    min-width: auto !important;
    width: auto !important;
    font-size: 0.9em;
    span,
    a {
      font-size: 0.9em;
    }
  }

  table.list-page-content-p th,
  table.list-page-content-p td {
    padding: 5px !important;
    margin: 5px !important;
    min-width: auto !important;
    width: auto !important;
    font-size: 0.9em;
    span,
    a {
      font-size: 0.9em;
    }
  }
}

#filterInventoryModel .vs__dropdown-menu {
  max-height: 200px;
}

.smart-view-edit-action {
  &:hover {
    font-weight: 800;
  }
}
</style>
