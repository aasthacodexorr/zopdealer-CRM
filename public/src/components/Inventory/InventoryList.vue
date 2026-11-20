<template>
  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-body" id="inventory-response-section">
      <div class="inventory-module-page">
        <!-- TIER 1: Page Title & Add Inventory Split Button (matching Image 2 & 3) -->
        <div class="inventory-tier1-row">
          <h1 class="inventory-page-title">Inventory</h1>

          <!-- Add Inventory Split Dropdown Button (matching Image 2 & 3) -->
          <div class="btn-group split-inventory-btn-group dropdown" :class="{ show: isAddMenuOpen }">
            <router-link
              :to="{ name: 'inventory-create' }"
              class="btn-split-main"
            >
              <i class="bx bx-plus mr-50"></i> Add Inventory
            </router-link>
            <button
              type="button"
              class="btn-split-toggle dropdown-toggle dropdown-toggle-split"
              data-toggle="dropdown"
              aria-haspopup="true"
              :aria-expanded="isAddMenuOpen ? 'true' : 'false'"
              title="Inventory Actions Menu"
              @click.stop="isAddMenuOpen = !isAddMenuOpen"
            >
              <i class="bx bxs-down-arrow" style="font-size: 8px;"></i>
            </button>
            <div
              class="dropdown-menu dropdown-menu-right shadow-sm border-0"
              :class="{ show: isAddMenuOpen }"
              style="min-width: 210px; margin-top: 4px;"
            >
              <router-link
                v-if="isAuthorized"
                :to="{ name: 'inventory-updates-pricing' }"
                class="dropdown-item d-flex align-items-center"
                @click="isAddMenuOpen = false"
              >
                <i class="bx bxs-dollar-circle mr-1 text-primary"></i> Bulk Price Updates
              </router-link>
              <router-link
                v-if="isAuthorized"
                :to="{ name: 'inventory-updates-description' }"
                class="dropdown-item d-flex align-items-center"
                @click="isAddMenuOpen = false"
              >
                <i class="bx bx-text mr-1 text-info"></i> Bulk Description Updates
              </router-link>
              <a
                v-if="isAuthorized"
                href="javascript:void(0)"
                class="dropdown-item d-flex align-items-center text-danger"
                @click="isAddMenuOpen = false; warningForPurgeDeletingInventories()"
              >
                <i class="bx bxs-eraser mr-1"></i> Empty Recycle Bin
              </a>
              <div class="dropdown-divider"></div>
              <router-link
                :to="{ name: 'third-party-inventory' }"
                class="dropdown-item d-flex align-items-center"
                @click="isAddMenuOpen = false"
              >
                <i class="bx bxs-file-import mr-1 text-secondary"></i> Bulk Import CSV
              </router-link>
              <router-link
                :to="{ name: 'inventory-import' }"
                class="dropdown-item d-flex align-items-center"
                @click="isAddMenuOpen = false"
              >
                <i class="bx bx-upload mr-1 text-success"></i> Quick Inventory Import
              </router-link>
            </div>
          </div>
        </div>

        <!-- TIER 2: Views Selector Dropdown (Left) & 5 Action Icons (Right) -->
        <div class="inventory-tier2-row">
          <!-- Views Dropdown -->
          <div class="inventory-views-dropdown dropdown">
            <button
              class="btn-views-toggle dropdown-toggle"
              type="button"
              id="inventoryViewsMenu"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
              title="Select View"
            >
              <i class="bx bx-list-ul mr-50 text-primary"></i>
              <span>{{ selected_inventory }}</span>
            </button>
            <div class="dropdown-menu shadow-sm" aria-labelledby="inventoryViewsMenu">
              <h6 class="dropdown-header text-uppercase font-small-2 font-weight-bold">
                Default Views
              </h6>
              <a
                v-for="inventory_view in getDefaultViews"
                :key="inventory_view.id"
                class="dropdown-item d-flex justify-content-between align-items-center"
                href="javascript:void(0)"
                @click="changeInventoryView(inventory_view.id)"
              >
                <span>{{ $helpers.truncate(inventory_view.header, 25, "...") }}</span>
                <router-link
                  :to="{ name: 'inventory-view-update', params: { id: inventory_view.id } }"
                  class="text-muted ml-2"
                  @click.stop
                >
                  <i class="bx bx-edit-alt"></i>
                </router-link>
              </a>

              <div class="dropdown-divider"></div>
              <h6 class="dropdown-header text-uppercase font-small-2 font-weight-bold">
                Custom Views
              </h6>
              <div class="custom-views-scrollable">
                <a
                  v-for="inventory_view in getCustomViews"
                  :key="inventory_view.id"
                  class="dropdown-item d-flex justify-content-between align-items-center"
                  href="javascript:void(0)"
                  @click="changeInventoryView(inventory_view.id)"
                >
                  <span>{{ $helpers.truncate(inventory_view.header, 25, "...") }}</span>
                  <router-link
                    :to="{ name: 'inventory-view-update', params: { id: inventory_view.id } }"
                    class="text-muted ml-2"
                    @click.stop
                  >
                    <i class="bx bx-edit-alt"></i>
                  </router-link>
                </a>
              </div>

              <div class="dropdown-divider"></div>
              <router-link
                :to="{ name: 'inventory-view-create' }"
                class="dropdown-item text-primary font-weight-bold d-flex align-items-center"
              >
                <i class="bx bx-plus mr-1"></i> New Custom View
              </router-link>
            </div>
          </div>

          <!-- 5 Action Icons on Right: Filter, Grid, Expand, Syndication, Settings -->
          <div class="inventory-tier2-actions">
            <button
              type="button"
              class="btn-tier2-icon"
              data-toggle="modal"
              data-target="#filters"
              :class="{ active: getFilterState }"
              title="Filters"
            >
              <i class="bx bx-filter"></i>
            </button>

            <button
              type="button"
              class="btn-tier2-icon"
              data-toggle="modal"
              data-target="#grid-list"
              title="Customize Columns"
            >
              <i class="bx bx-grid-alt"></i>
            </button>

            <button
              type="button"
              class="btn-tier2-icon"
              @click="changeCompact"
              :title="getCompact ? 'Normal View' : 'Compact View'"
            >
              <i class="bx" :class="getCompact ? 'bx-collapse' : 'bx-expand'"></i>
            </button>

            <button
              v-if="isAuthorized"
              type="button"
              class="btn-tier2-icon"
              :class="{ active: getCurrentSyndicationViewStatus }"
              @click="changeSyndicationViewStatus"
              title="Bulk Syndication"
            >
              <i class="bx bx-transfer"></i>
            </button>

            <button
              type="button"
              class="btn-tier2-icon"
              data-toggle="modal"
              data-target="#grid-list"
              title="Settings"
            >
              <i class="bx bx-cog"></i>
            </button>
          </div>
        </div>

        <!-- TIER 3: Search Bar + 4 Dropdowns (Normal Mode, Image 2) OR Bulk Actions Bar (Selected Mode, Image 3) -->
        <div class="inventory-tier3-card">
          <!-- Normal Mode (Image 2): Search Input on Left, 4 Quick Dropdowns on Right -->
          <div v-if="getInventoryIds.length === 0" class="tier3-normal-mode">
            <div class="tier3-search-wrap" :class="{ 'is-loading': isTableLoading }">
              <i
                class="bx search-icon"
                :class="isTableLoading ? 'bx-loader-alt bx-spin text-primary' : 'bx-search'"
              ></i>
              <input
                type="text"
                id="searchText"
                class="tier3-search-input"
                v-debounce:600ms="searchAction"
                v-model="searchString"
                placeholder="Search by make, model, year, stock # or VIN..."
              />
              <button
                v-if="searchString"
                type="button"
                class="clear-search-btn"
                title="Clear search"
                @click="clearSearch"
              >
                <i class="bx bx-x"></i>
              </button>
            </div>

            <div class="tier3-filters-wrap">
              <div class="tier3-select-box">
                <select
                  class="tier3-select"
                  :value="makeFilter"
                  @change="onToolbarFilterChange('make', $event.target.value)"
                >
                  <option value="">All Makes</option>
                  <option v-for="make in getMakes" :key="make" :value="make">{{ make }}</option>
                </select>
                <i class="bx bx-chevron-down select-caret"></i>
              </div>

              <div class="tier3-select-box">
                <select
                  class="tier3-select"
                  :value="modelFilter"
                  @change="onToolbarFilterChange('model', $event.target.value)"
                >
                  <option value="">All Models</option>
                  <option v-for="model in getModels" :key="model" :value="model">{{ model }}</option>
                </select>
                <i class="bx bx-chevron-down select-caret"></i>
              </div>

              <div class="tier3-select-box">
                <select
                  class="tier3-select"
                  :value="yearFilter"
                  @change="onToolbarFilterChange('year', $event.target.value)"
                >
                  <option value="">All Years</option>
                  <option v-for="year in getYears" :key="year" :value="year">{{ year }}</option>
                </select>
                <i class="bx bx-chevron-down select-caret"></i>
              </div>

              <div class="tier3-select-box">
                <select
                  class="tier3-select"
                  :value="statusFilter"
                  @change="onToolbarFilterChange('status', $event.target.value)"
                >
                  <option value="">All Statuses</option>
                  <option v-for="st in getStatuses" :key="st" :value="st">{{ st }}</option>
                </select>
                <i class="bx bx-chevron-down select-caret"></i>
              </div>

              <button
                v-if="hasActiveFilters"
                type="button"
                class="btn-reset-filters"
                title="Clear all applied filters"
                @click="resetAllFilters"
              >
                <i class="bx bx-x mr-25"></i> Clear
              </button>
            </div>
          </div>

          <!-- Bulk Selection Mode (Image 3): 1 Selected Pill, Cancel Selection, Delete, Bulk Update, Bulk Syndication -->
          <div v-else class="tier3-bulk-mode">
            <div class="bulk-pill-badge">
              <i class="bx bx-check-circle"></i>
              <span>{{ getInventoryIds.length }} selected</span>
            </div>

            <button
              type="button"
              class="btn-bulk-text btn-bulk-cancel"
              @click="clearBulkSelection"
            >
              <i class="bx bx-x"></i> Cancel Selection
            </button>

            <button
              type="button"
              class="btn-bulk-text btn-bulk-delete"
              @click="confirmDeleteAction"
            >
              <i class="bx bx-trash"></i> Delete
            </button>

            <button
              type="button"
              class="btn-bulk-text btn-bulk-update"
              data-toggle="modal"
              data-target="#bulk-list"
            >
              <i class="bx bx-edit"></i> Bulk Update
            </button>

            <button
              type="button"
              class="btn-bulk-text btn-bulk-syndication"
              data-toggle="modal"
              data-target="#bulk-syndication-update"
            >
              <i class="bx bx-transfer"></i> Bulk Syndication
            </button>
          </div>
        </div>

        <!-- 4. MAIN WORKSPACE: TABLE + PREVIEW PANEL (Side-by-Side) -->
          <div class="inventory-main-layout" :class="{ 'with-preview-open': isPreviewOpen }">
            <!-- LEFT: Table & Pagination Container -->
            <div class="inventory-table-container">
              <div class="inventory-table-card shadow-sm" style="position: relative;">
                <!-- Loading Overlay when filtering, searching, or paging -->
                <div v-if="isTableLoading" class="zd-inventory-loading-overlay">
                  <div class="zd-loading-indicator-card">
                    <i class="bx bx-loader-alt bx-spin text-primary"></i>
                    <span>Loading inventory...</span>
                  </div>
                </div>

                <div class="table-responsive inventory-table-responsive" :class="{ compact: getCompact }">
                  <table class="inventory-main-table">
                    <thead>
                      <tr class="inventory-table-head-row">
                        <!-- 1. Checkbox Column -->
                        <th class="th-checkbox">
                          <label class="custom-chk-container">
                            <input
                              class="custom-chk-input"
                              type="checkbox"
                              v-model="bulk_select"
                              :true-value="'1'"
                              :false-value="'0'"
                            />
                            <span class="custom-chk-box"></span>
                          </label>
                        </th>

                        <!-- 2. Photo Column -->
                        <th class="th-photo">Photo</th>

                        <!-- 3. Combined Vehicle Identity Column with Sort -->
                      <th class="th-vehicle th-sortable" @click="toggleSort('year')">
                        <span class="lr-sort">
                          <span class="vehicle-th-labels">
                            <span>YEAR</span>
                            <span>MAKE</span>
                            <span>MODEL</span>
                            <span>TRIM</span>
                            <span>VIN</span>
                            <span>STOCK #</span>
                          </span>
                          <i v-if="isSortAsc('year')" class="bx bx-up-arrow-alt sort-active"></i>
                          <i v-else-if="isSortDesc('year')" class="bx bx-down-arrow-alt sort-active"></i>
                          <span v-else class="sort-idle">⇅</span>
                        </span>
                      </th>

                      <!-- 4. DIS Column with Sort -->
                      <th class="th-dis th-sortable text-left" @click="toggleSort('days_in_stock')">
                        <span class="lr-sort justify-content-start">
                          <span>DIS</span>
                          <i v-if="isSortAsc('days_in_stock')" class="bx bx-up-arrow-alt sort-active"></i>
                          <i v-else-if="isSortDesc('days_in_stock')" class="bx bx-down-arrow-alt sort-active"></i>
                          <span v-else class="sort-idle">⇅</span>
                        </span>
                      </th>

                      <!-- 5. Odometer Column with Sort -->
                      <th class="th-odometer th-sortable text-left" @click="toggleSort('odometer')">
                        <span class="lr-sort justify-content-start">
                          <span>ODOMETER</span>
                          <i v-if="isSortAsc('odometer')" class="bx bx-up-arrow-alt sort-active"></i>
                          <i v-else-if="isSortDesc('odometer')" class="bx bx-down-arrow-alt sort-active"></i>
                          <span v-else class="sort-idle">⇅</span>
                        </span>
                      </th>

                      <!-- 6. Special Price Column with Sort -->
                      <th class="th-special-price th-sortable text-left" @click="toggleSort('special_price')">
                        <span class="lr-sort justify-content-start">
                          <span>SPECIAL PRICE</span>
                          <i v-if="isSortAsc('special_price')" class="bx bx-up-arrow-alt sort-active"></i>
                          <i v-else-if="isSortDesc('special_price')" class="bx bx-down-arrow-alt sort-active"></i>
                          <span v-else class="sort-idle">⇅</span>
                        </span>
                      </th>

                      <!-- 7. Price Column with Sort -->
                      <th class="th-price th-sortable text-left" @click="toggleSort('price')">
                        <span class="lr-sort justify-content-start">
                          <span>PRICE</span>
                          <i v-if="isSortAsc('price')" class="bx bx-up-arrow-alt sort-active"></i>
                          <i v-else-if="isSortDesc('price')" class="bx bx-down-arrow-alt sort-active"></i>
                          <span v-else class="sort-idle">⇅</span>
                        </span>
                      </th>

                      <!-- 8. Status Column with Sort -->
                      <th class="th-status th-sortable text-center" @click="toggleSort('status')">
                        <span class="lr-sort justify-content-center">
                          <span>STATUS</span>
                          <i v-if="isSortAsc('status')" class="bx bx-up-arrow-alt sort-active"></i>
                          <i v-else-if="isSortDesc('status')" class="bx bx-down-arrow-alt sort-active"></i>
                          <span v-else class="sort-idle">⇅</span>
                        </span>
                      </th>

                      <!-- 9. Creation Date Column with Sort -->
                      <th class="th-creation-date th-sortable" @click="toggleSort('created_at')">
                        <span class="lr-sort">
                          <span>CREATION DATE</span>
                          <i v-if="isSortAsc('created_at')" class="bx bx-up-arrow-alt sort-active"></i>
                          <i v-else-if="isSortDesc('created_at')" class="bx bx-down-arrow-alt sort-active"></i>
                          <span v-else class="sort-idle">⇅</span>
                        </span>
                      </th>

                      <!-- 10. Body Type Column with Sort -->
                      <th class="th-body-type th-sortable" @click="toggleSort('body_type')">
                        <span class="lr-sort">
                          <span>BODY TYPE</span>
                          <i v-if="isSortAsc('body_type')" class="bx bx-up-arrow-alt sort-active"></i>
                          <i v-else-if="isSortDesc('body_type')" class="bx bx-down-arrow-alt sort-active"></i>
                          <span v-else class="sort-idle">⇅</span>
                        </span>
                      </th>

                      <!-- 11. Last Sync Column with Sort -->
                      <th class="th-last-sync th-sortable" @click="toggleSort('updated_at')">
                        <span class="lr-sort">
                          <span>LAST SYNC</span>
                          <i v-if="isSortAsc('updated_at')" class="bx bx-up-arrow-alt sort-active"></i>
                          <i v-else-if="isSortDesc('updated_at')" class="bx bx-down-arrow-alt sort-active"></i>
                          <span v-else class="sort-idle">⇅</span>
                        </span>
                      </th>

                      <!-- 12. Website / VDP Column -->
                      <th class="th-website-vdp text-center">WEBSITE/ VDP</th>


                      <!-- 14. Repair Report Column -->
                      <th class="th-repair-report text-center">REPAIR REPORT</th>

                      <!-- 15. Modified At Column with Sort (Kept at last per instruction) -->
                      <th class="th-modified-at th-sortable" @click="toggleSort('updated_at')">
                        <span class="lr-sort">
                          <span>MODIFIED AT</span>
                          <i v-if="isSortAsc('updated_at')" class="bx bx-up-arrow-alt sort-active"></i>
                          <i v-else-if="isSortDesc('updated_at')" class="bx bx-down-arrow-alt sort-active"></i>
                          <span v-else class="sort-idle">⇅</span>
                        </span>
                      </th>

                      <!-- Dynamic Syndication Headers -->
                      <template v-if="getCurrentSyndicationViewStatus">
                        <th
                          class="text-center th-syndication-header"
                          v-for="active_syndication in getActiveSyndicationList"
                          :key="`inventory_${active_syndication.slug}_header`"
                        >
                          {{ active_syndication.label }}
                        </th>
                      </template>
                    </tr>
                  </thead>

                  <!-- Table Body -->
                  <tbody>
                    <!-- Loading Indicator (Initial load or loading when empty) -->
                    <tr v-if="!getLoadState || (isTableLoading && getEntities.length === 0)">
                      <td colspan="20" class="text-center py-5 text-muted">
                        <div class="d-flex flex-column align-items-center justify-content-center py-4">
                          <i class="bx bx-loader-alt bx-spin text-primary mb-2" style="font-size: 32px;"></i>
                          <span style="font-size: 14px; font-weight: 500; color: #4b5563;">Loading vehicles...</span>
                        </div>
                      </td>
                    </tr>

                    <!-- Empty State -->
                    <tr v-else-if="getEntities.length === 0">
                      <td colspan="20" class="text-center py-4 text-muted">
                        <i class="bx bx-search-alt mb-1" style="font-size: 28px;"></i>
                        <p class="mb-0">No vehicles found matching current criteria.</p>
                      </td>
                    </tr>

                    <!-- Vehicle Rows -->
                    <template v-else>
                      <inventory-item
                        v-for="item in getEntities"
                        :key="item.inventory_id + '_item_listing_view'"
                        :item="item"
                        :time_zone="timeZoneName"
                        :childCheck="bulk_select"
                        :placeholder_image="placeholder_image"
                        :app_metadata="app_metadata"
                        :isSelected="selectedInventoryId === item.inventory_id"
                        :isCompact="Boolean(getCompact)"
                        :showCarfaxColumn="hasCarfaxInListing"
                        @select-row="handleOpenPreview"
                        @open-preview="handleOpenPreview"
                        @close-preview-if-selected="handleClosePreviewIfSelected"
                      ></inventory-item>
                    </template>
                    </tbody>
                  </table>
                </div>

                <!-- 5. PAGINATION FOOTER: Docked & Always Visible -->
                <div class="inventory-pagination-footer">
                  <div class="pagination-info">
                    Showing {{ paginationStart }}–{{ paginationEnd }} of {{ totalInventoryCount }} vehicles
                  </div>

                  <!-- Center/Right Pagination Buttons -->
                  <div class="pagination-controls">
                    <button
                      type="button"
                      class="pagination-nav-btn"
                      :disabled="getPager.previous == null"
                      @click="paginationWithUrl(getPager.previous)"
                      title="Previous Page"
                    >
                      <i class="bx bx-chevron-left"></i>
                    </button>

                    <!-- Page Number Buttons -->
                    <button
                      v-for="p in visiblePageNumbers"
                      :key="'page_' + p"
                      type="button"
                      class="pagination-num-btn"
                      :class="{ active: p === getCurrentPage, ellipsis: p === '...' }"
                      :disabled="p === '...'"
                      @click="p !== '...' && pagination(p)"
                    >
                      {{ p }}
                    </button>

                    <button
                      type="button"
                      class="pagination-nav-btn"
                      :disabled="getPager.next == null"
                      @click="paginationWithUrl(getPager.next)"
                      title="Next Page"
                    >
                      <i class="bx bx-chevron-right"></i>
                    </button>
                  </div>

                  <!-- Page Size Selector -->
                  <div class="pagination-page-size">
                    <span class="mr-1 text-muted">Rows per page</span>
                    <select
                      class="page-size-select"
                      v-model="getNumberOfEntities"
                    >
                      <option value="10">10</option>
                      <option value="15">15</option>
                      <option value="25">25</option>
                      <option value="50">50</option>
                      <option value="100">100</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- 6. PRESERVED MODALS: Custom Columns Draggable List Modal -->
          <div
            class="modal fade text-left"
            id="grid-list"
            tabindex="-1"
            role="dialog"
            aria-labelledby="gridListLabel"
            aria-hidden="true"
          >
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header pl-1 pr-1">
                  <h4 class="modal-title font-weight-bold" id="gridListLabel">Column List</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                  </button>
                </div>
                <div class="modal-body p-0">
                  <draggable
                    tag="transition-group"
                    :component-data="{ tag: 'ul', type: 'transition', name: 'flip-list' }"
                    :list="list"
                    v-bind="dragOptions"
                    @start="isDragging = true"
                    @end="isDragging = false"
                    class="list-group"
                    @change="unsavedView"
                    handle=".handle"
                    item-key="header"
                  >
                    <template #item="{ element, index }">
                      <li class="list-group-item d-flex align-items-center">
                        <span style="cursor: pointer" class="bx bx-grid-vertical pr-1 handle text-muted"></span>
                        <div class="checkbox mb-0">
                          <input
                            type="checkbox"
                            @change="unsavedView"
                            v-model="element.visibility"
                            class="checkbox-input"
                            :id="'dragging_' + index"
                            :true-value="'1'"
                            :false-value="'0'"
                          />
                          <label :for="'dragging_' + index" class="mb-0 ml-1">{{ element.header }}</label>
                        </div>
                      </li>
                    </template>
                  </draggable>
                </div>
              </div>
            </div>
          </div>

          <!-- 7. PRESERVED MODALS: Advanced Filters Modal -->
          <div
            class="modal fade text-left"
            id="filters"
            tabindex="-1"
            role="dialog"
            aria-labelledby="filtersModalLabel"
            aria-hidden="true"
          >
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
              <div class="modal-content" id="filterInventoryModel">
                <div class="modal-header pl-1 pr-1">
                  <h4 class="modal-title font-weight-bold" id="filtersModalLabel">Advanced Filters</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-5"><label>Year</label></div>
                    <div class="col-md-7 form-group">
                      <v-select
                        id="filter-year"
                        placeholder="Year"
                        v-model="yearFilter"
                        clearable
                        :options="getYears"
                      ></v-select>
                    </div>

                    <div class="col-md-5"><label>Make</label></div>
                    <div class="col-md-7 form-group">
                      <v-select
                        id="filter-make"
                        placeholder="Make"
                        v-model="makeFilter"
                        clearable
                        :options="getMakes"
                      ></v-select>
                    </div>

                    <div class="col-md-5"><label>Model</label></div>
                    <div class="col-md-7 form-group">
                      <v-select
                        id="filter-model"
                        placeholder="Model"
                        v-model="modelFilter"
                        clearable
                        :options="getModels"
                      ></v-select>
                    </div>

                    <div class="col-md-5"><label>Status</label></div>
                    <div class="col-md-7 form-group">
                      <v-select
                        id="filter-status"
                        placeholder="Status"
                        v-model="statusFilter"
                        clearable
                        :options="getStatuses"
                      ></v-select>
                    </div>

                    <div class="col-md-5"><label>Visibility</label></div>
                    <div class="col-md-7 form-group">
                      <v-select
                        id="filter-visibility"
                        placeholder="Visibility"
                        v-model="visibilityFilter"
                        clearable
                        :reduce="(visibility) => visibility.value"
                        :options="[{ label: 'ON', value: '1' }, { label: 'OFF', value: '0' }]"
                      ></v-select>
                    </div>

                    <div class="col-sm-12 d-flex justify-content-left pt-1">
                      <button
                        type="button"
                        @click="filterInventory"
                        class="btn btn-primary mr-1"
                        data-dismiss="modal"
                      >
                        Apply Filters
                      </button>
                      <button
                        type="button"
                        @click="resetFilters"
                        class="btn btn-light-secondary"
                        data-dismiss="modal"
                      >
                        Reset
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- 8. PRESERVED MODALS: Bulk Update Modal -->
          <div
            class="modal fade text-left"
            id="bulk-list"
            tabindex="-1"
            role="dialog"
            aria-labelledby="bulkListModalLabel"
            aria-hidden="true"
          >
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
              <div class="modal-content">
                <div class="modal-header pl-1 pr-1">
                  <h4 class="modal-title font-weight-bold" id="bulkListModalLabel">Bulk Update Vehicles</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-5"><label>Featured Vehicle</label></div>
                    <div class="col-md-7 form-group">
                      <select class="custom-select form-control" v-model="form.feature_listing">
                        <option value="" selected disabled>- Select Featured -</option>
                        <option value="1">Featured</option>
                        <option value="0">Not Featured</option>
                      </select>
                    </div>

                    <div class="col-md-5"><label>Status</label></div>
                    <div class="col-md-7 form-group">
                      <select class="custom-select form-control" v-model="form.status">
                        <option value="" selected disabled>- Select Status -</option>
                        <option :value="status" v-for="status in getStatuses" :key="status">
                          {{ status }}
                        </option>
                      </select>
                    </div>

                    <div class="col-md-5"><label>Sub Status</label></div>
                    <div class="col-md-7 form-group">
                      <select class="custom-select form-control" v-model="form.sub_status">
                        <option value="" selected disabled>- Select Sub Status -</option>
                        <option :value="status" v-for="status in getSubStatues" :key="status">
                          {{ status }}
                        </option>
                      </select>
                    </div>

                    <div class="col-md-5"><label>Vehicle Type</label></div>
                    <div class="col-md-7 form-group">
                      <select class="custom-select form-control" v-model="form.vehicle_type">
                        <option value="" selected disabled>- Select Vehicle Type -</option>
                        <option :value="vt" v-for="vt in getVehicleTypes" :key="vt">
                          {{ vt }}
                        </option>
                      </select>
                    </div>

                    <div class="col-md-5"><label>Visibility</label></div>
                    <div class="col-md-7 form-group">
                      <select class="custom-select form-control" v-model="form.visibility">
                        <option value="" selected disabled>- Select Visibility -</option>
                        <option value="1">ON</option>
                        <option value="0">OFF</option>
                      </select>
                    </div>

                    <div class="col-sm-12 d-flex justify-content-left pt-1">
                      <button
                        type="button"
                        @click="applyBulkOptions"
                        class="btn btn-primary mr-1"
                        data-dismiss="modal"
                      >
                        Update Fields
                      </button>
                      <button
                        type="button"
                        @click="resetBulkColumns"
                        class="btn btn-light-secondary"
                        data-dismiss="modal"
                      >
                        Cancel
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- 9. PRESERVED MODALS: Bulk Syndication Modal -->
          <div
            class="modal fade text-left"
            id="bulk-syndication-update"
            tabindex="-1"
            role="dialog"
            aria-labelledby="bulkSyndicationModalLabel"
            aria-hidden="true"
          >
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
              <div class="modal-content">
                <div class="modal-header pl-1 pr-1">
                  <h4 class="modal-title font-weight-bold" id="bulkSyndicationModalLabel">Bulk Syndication Update</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-5"><label>Action</label></div>
                    <div class="col-md-7 form-group">
                      <select class="custom-select form-control" v-model="syndication.action">
                        <option value="ADD">ADD</option>
                        <option value="REMOVE">REMOVE</option>
                      </select>
                    </div>

                    <div class="col-md-5"><label>Syndication Partners</label></div>
                    <div class="col-md-7 form-group">
                      <v-select
                        multiple
                        v-model="syndication.slugs"
                        :reduce="(partner) => partner.slug"
                        :options="getActiveSyndicationList"
                      ></v-select>
                    </div>

                    <div class="col-sm-12 d-flex justify-content-left pt-1">
                      <button
                        type="button"
                        @click="confirmBulkSyndicationUpdate"
                        class="btn mr-1"
                        :class="getBulkSydnicationConfirmButtonClass"
                        data-dismiss="modal"
                      >
                        Confirm
                      </button>
                      <button
                        type="button"
                        @click="resetSyndicationColumns"
                        class="btn btn-light-secondary"
                        data-dismiss="modal"
                      >
                        Cancel
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT: INVENTORY PREVIEW PANEL (Opened when a row is clicked, sidebar to the whole page) -->
      <transition name="slide-panel">
        <inventory-preview-panel
          v-if="isPreviewOpen && selectedVehicleItem"
          :item="selectedVehicleItem"
          :app_metadata="app_metadata"
          :time_zone="timeZoneName"
          :placeholder_image="placeholder_image"
          @close="closePreviewPanel"
          @open-vdp="openInventoryNewTab"
        ></inventory-preview-panel>
      </transition>
    </div>
  <!-- END: Content-->
</template>

<script>
const moment = require("moment");
import draggable from "vuedraggable";
import InventoryItem from "./InventoryItem.vue";
import InventoryPreviewPanel from "./InventoryPreviewPanel.vue";
import { mapGetters, mapActions } from "vuex";
import Toast from "../../library/Toast";
import vSelect from "vue-select";

export default {
  name: "InventoryList",
  props: {
    application: { required: true },
    app_metadata: { required: true },
    user_metadata: { required: true },
  },
  components: {
    "v-select": vSelect,
    InventoryItem,
    InventoryPreviewPanel,
    draggable,
  },
  data() {
    return {
      load: false,
      page: 1,
      bulk_select: "0",
      selected_inventory: "All Inventories",
      inventory_views: [],
      placeholder_image: "",
      sort_column: { created_at: "DESC" },
      body_types: [],
      transmissions: [],
      isDragging: false,
      isAddMenuOpen: false,
      isTableLoading: false,
      // Toolbar Filter State
      selectedMake: "",
      selectedModel: "",
      selectedYear: "",
      selectedStatus: "",
      // Right-side preview panel state
      isPreviewOpen: false,
      selectedInventoryId: null,
      selectedVehicleItem: null,
      // Bulk update forms
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
        action: "ADD",
        slugs: [],
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
    hasCarfaxInListing() {
      if (this.getCurrentSyndicationViewStatus) return false;
      return Boolean(
        this.getEntities &&
        this.getEntities.some(
          (item) =>
            item.carfax_url ||
            (item.carfax && item.carfax !== "0" && item.carfax !== 0) ||
            item.has_carfax === 1 ||
            item.has_carfax === "1"
        )
      );
    },
    totalInventoryCount() {
      return (this.getPager && this.getPager.total != null) ? this.getPager.total : this.getEntities.length;
    },
    paginationStart() {
      if (this.totalInventoryCount === 0) return 0;
      return (this.getCurrentPage - 1) * this.getEntitiesCount + 1;
    },
    paginationEnd() {
      return Math.min(this.getCurrentPage * this.getEntitiesCount, this.totalInventoryCount);
    },
    visiblePageNumbers() {
      const pageCount = (this.getPager && this.getPager.pageCount) ? this.getPager.pageCount : 1;
      const current = this.getCurrentPage || 1;
      const delta = 2;
      const range = [];
      const rangeWithDots = [];
      let l;

      for (let i = 1; i <= pageCount; i++) {
        if (i === 1 || i === pageCount || (i >= current - delta && i <= current + delta)) {
          range.push(i);
        }
      }

      for (const i of range) {
        if (l) {
          if (i - l === 2) {
            rangeWithDots.push(l + 1);
          } else if (i - l !== 1) {
            rangeWithDots.push("...");
          }
        }
        rangeWithDots.push(i);
        l = i;
      }

      return rangeWithDots;
    },
    hasActiveFilters() {
      return (
        Boolean(this.selectedMake) ||
        Boolean(this.selectedModel) ||
        Boolean(this.selectedYear) ||
        Boolean(this.selectedStatus) ||
        Boolean(this.searchString) ||
        this.getFilterState
      );
    },
    filteredModels() {
      return this.getModels;
    },
    getDefaultViews() {
      return this.inventory_views.filter((view) => view.default === "1");
    },
    getCustomViews() {
      return this.inventory_views.filter((view) => view.default === "0");
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
    getYears() {
      if (this.getAvailableFilters.hasOwnProperty("year") && Array.isArray(this.getAvailableFilters.year)) {
        return this.getAvailableFilters.year.filter((x) => x.name !== "").map((x) => x.name);
      }
      return [];
    },
    getMakes() {
      if (this.getAvailableFilters.hasOwnProperty("make") && Array.isArray(this.getAvailableFilters.make)) {
        return this.getAvailableFilters.make.filter((x) => x.name !== "").map((x) => x.name);
      }
      return [];
    },
    getModels() {
      if (this.getAvailableFilters.hasOwnProperty("model") && Array.isArray(this.getAvailableFilters.model)) {
        return this.getAvailableFilters.model.filter((x) => x.name !== "").map((x) => x.name);
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
        this.selectedYear = value || "";
        this.commitFilters({ name: "year", value: value });
      },
    },
    makeFilter: {
      get() {
        return this.getFilters.make;
      },
      set(value) {
        this.selectedMake = value || "";
        this.commitFilters({ name: "make", value: value });
      },
    },
    modelFilter: {
      get() {
        return this.getFilters.model;
      },
      set(value) {
        this.selectedModel = value || "";
        this.commitFilters({ name: "model", value: value });
      },
    },
    statusFilter: {
      get() {
        return this.getFilters.status;
      },
      set(value) {
        this.selectedStatus = value || "";
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
  watch: {
    getViewTitle: {
      immediate: true,
      handler(val) {
        if (val) {
          this.selected_inventory = val.length > 20 ? val.substring(0, 18) + "..." : val;
        }
      },
    },
    getEntities: {
      handler(newEntities) {
        if (this.selectedInventoryId && newEntities && newEntities.length > 0) {
          const match = newEntities.find((e) => e.inventory_id === this.selectedInventoryId);
          if (match) {
            this.selectedVehicleItem = match;
          }
        }
      },
    },
    bulk_select(newVal) {
      if (newVal === "1" && this.getEntities && this.getEntities.length > 0) {
        this.handleOpenPreview(this.getEntities[0]);
      } else if (newVal === "0") {
        this.closePreviewPanel();
      }
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
    handleRowSelect(item) {
      this.handleOpenPreview(item);
    },
    handleOpenPreview(item) {
      this.selectedInventoryId = item.inventory_id;
      this.selectedVehicleItem = item;
      this.isPreviewOpen = true;
    },
    handleClosePreviewIfSelected(inventoryId) {
      if (this.selectedInventoryId === inventoryId) {
        const remaining = this.getInventoryIds.filter((id) => id !== inventoryId);
        if (remaining.length > 0) {
          const nextId = remaining[remaining.length - 1];
          const match = this.getEntities.find((e) => e.inventory_id === nextId);
          if (match) {
            this.selectedInventoryId = match.inventory_id;
            this.selectedVehicleItem = match;
            return;
          }
        }
        this.closePreviewPanel();
      }
    },
    closePreviewPanel() {
      this.isPreviewOpen = false;
      this.selectedInventoryId = null;
      this.selectedVehicleItem = null;
    },
    openInventoryNewTab(id) {
      const routeData = this.$router.resolve({
        name: "inventory-update",
        params: { id: id },
      });
      window.open(routeData.href, "_blank");
    },
    searchAction(value) {
      const q = value !== undefined ? value : this.searchString;
      if (q && q.length >= 3) {
        this.commitSearchState(true);
        this.searchInventory(q);
      } else {
        if (this.getSearch) {
          this.inventoryListing(true);
          this.commitSearchState(false);
        }
      }
    },
    clearSearch() {
      this.searchString = "";
      this.commitSearchString("");
      if (this.getSearch) {
        this.commitSearchState(false);
        this.inventoryListing(true);
      }
    },
    onToolbarFilterChange(filterName, value) {
      this.commitFilters({ name: filterName, value: value || "" });
      this.filterInventory();
    },
    resetAllFilters() {
      this.selectedMake = "";
      this.selectedModel = "";
      this.selectedYear = "";
      this.selectedStatus = "";
      this.searchString = "";
      this.commitSearchString("");
      this.commitSearchState(false);
      this.commitFilterState(false);
      this.commitFilters({ name: "model", value: "" });
      this.commitFilters({ name: "year", value: "" });
      this.commitFilters({ name: "make", value: "" });
      this.commitFilters({ name: "status", value: "" });
      this.commitFilters({ name: "visibility", value: "" });
      this.inventoryListing(true);
    },
    resetFilters() {
      this.resetAllFilters();
    },
    changeCompact() {
      this.commitCompactFlag(!this.getCompact);
    },
    changeSyndicationViewStatus() {
      this.commitCurrentSyndicationViewStatus(!this.getCurrentSyndicationViewStatus);
    },
    toggleSort(column) {
      let currentOrder = (this.getSortColumn && this.getSortColumn[column]) || "";
      let nextOrder = currentOrder === "ASC" ? "DESC" : "ASC";
      this.updateSortColumn(column, nextOrder);
    },
    isSortAsc(column) {
      return Boolean(this.getSortColumn && this.getSortColumn[column] === "ASC");
    },
    isSortDesc(column) {
      return Boolean(this.getSortColumn && this.getSortColumn[column] === "DESC");
    },
    toggleAddMenu() {
      this.isAddMenuOpen = !this.isAddMenuOpen;
    },
    filterInventory() {
      let url = `${window.location.origin}/api/business/inventory?all=true`;
      if (this.getSearch && this.getSearchString) {
        url += `&search=${this.getSearchString}`;
      }
      for (const key in this.getFilters) {
        if (this.getFilters.hasOwnProperty(key) && this.getFilters[key]) {
          url += `&zm_${key}[]=${this.getFilters[key]}`;
        }
      }
      this.isTableLoading = true;
      axios({
        method: "get",
        headers: { Authorization: "Bearer " + window.$cookies.get("_token") },
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data.inventory);
        this.commitRequestUrl(url);

        let pager = response.data.pager;
        if (pager) {
          pager.next = pager.next == null ? null : "/api" + pager.next.split("api")[1];
          pager.previous = pager.previous == null ? null : "/api" + pager.previous.split("api")[1];
          this.commitViewPager(pager);
          this.commitCurrentPage(parseInt(pager.currentPage));
          this.commitEntitiesCount(parseInt(pager.perPage));
        }
        if (response.data.sort) {
          this.commitSortColumn(JSON.parse(JSON.stringify(response.data.sort)));
        }
        this.commitFilterState(true);

        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: "Inventory has been filtered.",
        });
      }).catch((err) => {
        console.error("Filter inventory error:", err);
      }).finally(() => {
        this.isTableLoading = false;
      });
    },
    searchInventory(search) {
      let url = `${window.location.origin}/api/business/inventory?all=true&search=${this.getSearchString}`;
      if (this.getFilterState) {
        for (const key in this.getFilters) {
          if (this.getFilters.hasOwnProperty(key) && this.getFilters[key]) {
            url += `&zm_${key}[]=${this.getFilters[key]}`;
          }
        }
      }
      this.isTableLoading = true;
      axios({
        method: "get",
        headers: { Authorization: "Bearer " + window.$cookies.get("_token") },
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data.inventory);
        this.commitRequestUrl(url);

        let pager = response.data.pager;
        if (pager) {
          pager.next = pager.next == null ? null : "/api" + pager.next.split("api")[1];
          pager.previous = pager.previous == null ? null : "/api" + pager.previous.split("api")[1];
          this.commitViewPager(pager);
          this.commitCurrentPage(parseInt(pager.currentPage));
          this.commitEntitiesCount(parseInt(pager.perPage));
        }
        if (response.data.sort) {
          this.commitSortColumn(JSON.parse(JSON.stringify(response.data.sort)));
        }

        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: `Search for ${this.getSearchString} has been completed.`,
        });
      }).catch((err) => {
        console.error("Search inventory error:", err);
      }).finally(() => {
        this.isTableLoading = false;
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
          title: "Sort order cannot be updated while search or filter is active.",
        });
        return;
      }
      let formData = new FormData();
      this.sort_column = {};
      this.sort_column[column] = order;
      formData.append("sort", JSON.stringify(this.sort_column));
      this.isTableLoading = true;
      axios({
        method: "post",
        data: formData,
        headers: { Authorization: "Bearer " + window.$cookies.get("_token") },
        url: `${window.location.origin}/api/business/inventories/views/update/${this.getViewId}`,
      }).then(() => {
        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: "Inventory View Changed.",
        });
        this.commitLoadState(false);
        this.inventoryListing();
      }).catch((err) => {
        console.error("Update sort column error:", err);
        this.isTableLoading = false;
      });
    },
    changeInventoryView(id) {
      this.resetAllFilters();
      this.clearBulkSelection();
      this.isTableLoading = true;
      axios({
        method: "get",
        headers: { Authorization: "Bearer " + window.$cookies.get("_token") },
        url: `${window.location.origin}/api/business/inventories/views/result/${id}`,
      }).then((response) => {
        this.commitViewId(response.data.inventory_id);
        this.commitViewTitle(response.data.header);
        this.commitViewEntities(response.data.inventory);
        this.commitRequestUrl(`${window.location.origin}/api/business/inventories/views/result/${id}`);

        let pager = response.data.pager;
        if (pager) {
          pager.next = pager.next == null ? null : "/api" + pager.next.split("api")[1];
          pager.previous = pager.previous == null ? null : "/api" + pager.previous.split("api")[1];
          this.commitViewPager(pager);
          this.commitCurrentPage(parseInt(pager.currentPage));
          this.commitEntitiesCount(parseInt(pager.perPage));
        }
        if (response.data.view) {
          this.updateView(Object.values(response.data.view));
        }
        if (response.data.sort) {
          this.commitSortColumn(JSON.parse(JSON.stringify(response.data.sort)));
        }

        this.$swal({
          showConfirmButton: false,
          icon: "success",
          toast: true,
          timer: 2000,
          position: "bottom",
          title: "Inventory View Changed.",
        });

        axios({
          method: "get",
          headers: { Authorization: "Bearer " + window.$cookies.get("_token") },
          url: `${window.location.origin}/api/business/user/inventory/update?id=${this.getViewId}`,
        });
      }).catch((err) => {
        console.error("Change inventory view error:", err);
      }).finally(() => {
        this.isTableLoading = false;
      });
    },
    pagination(n = 1) {
      let url = window.location.origin;
      if (this.getSearch || this.getFilterState) {
        url += `/api/business/inventory?all=true`;
        if (this.getSearch) {
          url += `&search=${this.getSearchString}`;
        }
        if (this.getFilterState) {
          for (const key in this.getFilters) {
            if (this.getFilters.hasOwnProperty(key) && this.getFilters[key]) {
              url += `&zm_${key}[]=${this.getFilters[key]}`;
            }
          }
        }
        url += `&page=`;
      } else {
        url += `/api/business/inventories/views/result/${this.getViewId}?page=`;
      }
      url += n;
      url += "&items=" + this.getEntitiesCount;
      this.clearBulkSelection();
      this.fetchPageData(url);
    },
    paginationWithUrl(url) {
      if (!url) return;
      this.clearBulkSelection();
      this.fetchPageData(url);
    },
    fetchPageData(url) {
      this.isTableLoading = true;
      axios({
        method: "get",
        headers: { Authorization: "Bearer " + window.$cookies.get("_token") },
        url: url,
      }).then((response) => {
        this.commitViewEntities(response.data.inventory);
        this.commitRequestUrl(url);

        let pager = response.data.pager;
        if (pager) {
          pager.next = pager.next == null ? null : "/api" + pager.next.split("api")[1];
          pager.previous = pager.previous == null ? null : "/api" + pager.previous.split("api")[1];
          this.commitViewPager(pager);
          this.commitCurrentPage(parseInt(pager.currentPage));
          this.commitEntitiesCount(parseInt(pager.perPage));
        }
      }).catch((err) => {
        console.error("Fetch page data error:", err);
      }).finally(() => {
        this.isTableLoading = false;
      });
    },
    async inventoryListing(refresh = false) {
      if (refresh || !this.getLoadState) {
        this.isTableLoading = true;
        try {
          await axios({
            method: "get",
            headers: { Authorization: "Bearer " + window.$cookies.get("_token") },
            url: `${window.location.origin}/api/business/user/inventory`,
          }).then((response) => {
            this.commitViewId(response.data.inventory_id);
            this.commitViewTitle(response.data.header);
            this.commitViewEntities(response.data.inventory);
            this.commitRequestUrl(`${window.location.origin}/api/business/user/inventory`);

            let pager = response.data.pager;
            if (pager) {
              pager.next = pager.next == null ? null : "/api" + pager.next.split("api")[1];
              pager.previous = pager.previous == null ? null : "/api" + pager.previous.split("api")[1];
              this.commitViewPager(pager);
              this.commitCurrentPage(parseInt(pager.currentPage));
              this.commitEntitiesCount(parseInt(pager.perPage));
            }
            if (response.data.sort) {
              this.commitSortColumn(JSON.parse(JSON.stringify(response.data.sort)));
            }
            if (response.data.view) {
              this.updateView(Object.values(response.data.view));
            }
          });
          this.commitLoadState(true);
        } catch (err) {
          console.error("Inventory listing error:", err);
        } finally {
          this.isTableLoading = false;
        }
      }
    },
    clearBulkSelection() {
      this.bulk_select = "0";
      this.removeEveryInventoryId();
    },
    closeDropdowns(e) {
      if (!this.$el?.querySelector(".split-inventory-btn-group")?.contains(e.target)) {
        this.isAddMenuOpen = false;
      }
    },
    view_inventories() {
      axios({
        method: "get",
        headers: { Authorization: "Bearer " + window.$cookies.get("_token") },
        url: `${window.location.origin}/api/business/inventories/views`,
      }).then((response) => {
        this.inventory_views = response.data.inventory_views || [];
      });
    },
    getBodyTypes() {
      axios.get("/json/body_type.json").then(({ data }) => (this.body_types = data)).catch(() => {});
    },
    getTransmissions() {
      axios.get("/json/transmission.json").then(({ data }) => (this.transmissions = data)).catch(() => {});
    },
    getPlaceholder() {
      axios({
        method: "get",
        url: `${window.location.origin}/api/business/image/placeholder`,
        headers: { Authorization: "Bearer " + window.$cookies.get("_token") },
      })
        .then(({ data }) => {
          this.placeholder_image = this.$helpers.parseAbsoluteOrRelativeUrl(window.global.cdn, data.image_url);
        })
        .catch(() => {});
    },
    resetBulkColumns() {
      Object.keys(this.form).forEach((k) => (this.form[k] = ""));
    },
    resetSyndicationColumns() {
      this.syndication.slugs = [];
      this.syndication.action = "ADD";
    },
    applyBulkOptions() {
      let formData = new FormData();
      formData.append("inventory_ids", this.getInventoryIds);
      for (const key in this.form) {
        if (this.form.hasOwnProperty(key)) {
          formData.append(key, this.form[key]);
        }
      }
      const toast = new Toast("Updating selected inventories...");
      toast.fire();
      axios({
        method: "post",
        data: formData,
        headers: { Authorization: "Bearer " + window.$cookies.get("_token") },
        url: `${window.location.origin}/api/business/inventory/updates`,
      })
        .then(() => {
          this.resetBulkColumns();
          this.bulk_select = "0";
          setTimeout(() => {
            this.pagination(this.getCurrentPage);
            toast.success("Inventories updated successfully.");
            toast.close();
          }, 1000);
        })
        .catch(() => {
          this.resetBulkColumns();
          this.bulk_select = "0";
          toast.fail("No columns or rows were selected.");
        });
    },
    confirmBulkSyndicationUpdate() {
      this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Confirm",
        text: `Do you want to ${this.syndication.action} ${this.syndication.slugs.join(", ").toUpperCase()} for selected inventories?`,
      }).then((result) => {
        if (result.isConfirmed) {
          this.updateBulkSyndication(this.syndication.slugs, this.getInventoryIds, this.syndication.action);
        }
      });
    },
    updateBulkSyndication(slugs, inventory_ids, action) {
      let formData = new FormData();
      formData.append("slugs", slugs);
      formData.append("inventory_ids", inventory_ids);
      formData.append("action", action);
      const toast = new Toast("Updating syndication...");
      toast.fire();
      axios({
        method: "post",
        data: formData,
        headers: { Authorization: "Bearer " + window.$cookies.get("_token") },
        url: `${window.location.origin}/api/business/inventory/syndication/updates`,
      })
        .then(() => {
          this.resetSyndicationColumns();
          this.bulk_select = "0";
          setTimeout(() => {
            this.pagination(this.getCurrentPage);
            toast.success("Syndication updated successfully.");
            toast.close();
          }, 1000);
        })
        .catch(() => {
          this.resetSyndicationColumns();
          this.bulk_select = "0";
          toast.fail("Failed to update syndication.");
        });
    },
    confirmDeleteAction() {
      this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Confirm Delete",
        confirmButtonColor: "#ef4444",
        text: "Please confirm if you want to delete the selected inventories.",
      }).then((result) => {
        if (result.isConfirmed) {
          this.applyBulkDelete();
        }
      });
    },
    applyBulkDelete() {
      let formData = new FormData();
      formData.append("inventory_ids", this.getInventoryIds);
      axios({
        method: "post",
        data: formData,
        headers: { Authorization: "Bearer " + window.$cookies.get("_token") },
        url: `${window.location.origin}/api/business/inventory/deletes`,
      })
        .then(() => {
          this.resetBulkColumns();
          this.bulk_select = "0";
          setTimeout(() => {
            this.pagination(this.getCurrentPage);
            this.$swal({
              showConfirmButton: false,
              icon: "success",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "Selected inventories have been deleted.",
            });
          }, 1000);
        })
        .catch(() => {
          this.resetBulkColumns();
          this.bulk_select = "0";
          this.$swal({
            showConfirmButton: false,
            icon: "error",
            toast: true,
            timer: 2000,
            position: "bottom",
            title: "Errors occurred while deleting data.",
          });
        });
    },
    warningForPurgeDeletingInventories() {
      this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Cancel",
        confirmButtonText: "Empty Recycle Bin",
        confirmButtonColor: "#ef4444",
        text: "This action will permanently delete all vehicles in the recycle bin. Are you sure?",
      }).then((result) => {
        if (result.isConfirmed) {
          axios({
            method: "delete",
            headers: { Authorization: "Bearer " + window.$cookies.get("_token") },
            url: `${window.location.origin}/api/business/inventory/trash/empty`,
          }).then(() => {
            this.inventoryListing(true);
            this.$swal({
              showConfirmButton: false,
              icon: "success",
              toast: true,
              timer: 2000,
              position: "bottom",
              title: "Recycle bin has been emptied.",
            });
          });
        }
      });
    },
    unsavedView() {
      let formData = new FormData();
      formData.append("view", JSON.stringify(this.list));
      axios({
        method: "post",
        data: formData,
        headers: { Authorization: "Bearer " + window.$cookies.get("_token") },
        url: `${window.location.origin}/api/business/inventories/views/update/${this.getViewId}`,
      });
    },
  },
  created() {
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
    Event.listen("update_bulk_syndication", ({ slugs, inventory_ids, action }) => {
      this.updateBulkSyndication(slugs, inventory_ids, action);
    });
    document.addEventListener("click", this.closeDropdowns);
  },
  beforeUnmount() {
    Event.vue.$off("update_bulk_syndication", null);
    document.removeEventListener("click", this.closeDropdowns);
  },
};
</script>

<style scoped lang="scss">
/* Page container inside CRM - matching Leads page spacing */
.inventory-module-page {
  /* top padding clears the fixed theme navbar (~45px) */
  /* bottom padding leaves room for the theme's fixed footer */
  padding: 4rem 1.75rem 2.75rem;
  background-color: #ffffff;
  font-family: inherit;
  height: 100vh;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

/* 1. Header Section */
.inventory-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
  margin-bottom: 0.85rem;
  flex-shrink: 0;
}

.inventory-title-wrap {
  display: flex;
  flex-direction: column;
}

.inventory-page-title {
  font-size: 1.65rem;
  font-weight: 700;
  color: #0f172a;
  line-height: 1.2;
  margin: 0;
  letter-spacing: -0.02em;
}

.inventory-subtitle {
  font-size: 13.5px;
  color: #64748b;
  font-weight: 500;
  margin-top: 2px;
}

/* Header Right Controls */
.inventory-header-right {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 10px;
}

.header-search-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  min-width: 280px;
  max-width: 380px;

  .search-icon {
    position: absolute;
    left: 10px;
    font-size: 16px;
    color: #94a3b8;
    pointer-events: none;
  }

  .header-search-input {
    width: 100%;
    height: 34px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0 28px 0 32px;
    font-size: 12.5px;
    color: #1e293b;
    outline: none;
    transition: all 0.15s ease;

    &::placeholder {
      color: #94a3b8;
    }

    &:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
    }
  }

  .clear-search-btn {
    position: absolute;
    right: 8px;
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;

    &:hover {
      color: #475569;
    }
  }
}

.header-bulk-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: #eff6ff;
  border: 1px solid #bfdbfe;
  border-radius: 6px;
  padding: 3px 8px;

  .bulk-count-badge {
    font-size: 12px;
    font-weight: 600;
    color: #1e40af;
    display: inline-flex;
    align-items: center;
  }

  .btn-bulk-icon {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    width: 26px;
    height: 26px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.15s ease;

    &:hover {
      background: #f8fafc;
      transform: scale(1.05);
    }
  }
}

.header-tools-group {
  display: flex;
  align-items: center;
  gap: 6px;

  .btn-tool-icon {
    width: 34px;
    height: 34px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #475569;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.15s ease;

    &:hover {
      background: #f8fafc;
      border-color: #cbd5e1;
      color: #0f172a;
    }

    &.active {
      background: #eff6ff;
      color: #2563eb;
      border-color: #bfdbfe;
    }
  }
}

.split-inventory-btn-group {
  display: inline-flex;
  position: relative;
  box-shadow: 0 1px 3px rgba(37, 99, 235, 0.2);

  .btn-split-main {
    display: inline-flex;
    align-items: center;
    background: #2563eb;
    color: #ffffff !important;
    font-size: 14.5px;
    font-weight: 600;
    padding: 8px 16px;
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
    border: none;
    text-decoration: none;
    transition: background 0.15s ease;

    &:hover {
      background: #1d4ed8;
    }
  }

  .btn-split-toggle {
    background: #2563eb;
    color: #ffffff;
    border: none;
    border-left: 1px solid rgba(255, 255, 255, 0.35);
    padding: 8px 12px;
    font-size: 14px;
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s ease;

    &:hover {
      background: #1d4ed8;
    }

    &::after {
      display: none; /* Hide default bootstrap caret, boxicons chevron is used */
    }
  }

  .dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    right: 0 !important;
    left: auto !important;
    transform: none !important;
    margin-top: 6px !important;
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 8px !important;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
    min-width: 220px !important;
    z-index: 1050 !important;
    padding: 6px 0 !important;
    display: none;

    &.show {
      display: block !important;
    }

    .dropdown-item {
      padding: 9px 16px;
      font-size: 13.5px;
      color: #374151;
      display: flex;
      align-items: center;
      transition: background-color 0.15s ease;

      &:hover {
        background-color: #f3f4f6;
        color: #111827;
      }
    }
  }
}

/* 2. Subbar (Above Search Bar): Views Dropdown + Action Icons */
.inventory-subbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 8px;
}

.btn-views-toggle {
  display: inline-flex;
  align-items: center;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  padding: 5px 12px;
  height: 32px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 500;
  color: #334155;
  cursor: pointer;
  transition: all 0.15s ease;

  &:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
    color: #0f172a;
  }
}

.custom-views-scrollable {
  max-height: 200px;
  overflow-y: auto;
}

/* Action Icons Toolbar with Tooltips */
.inventory-actions-toolbar {
  display: flex;
  align-items: center;
  gap: 6px;
}

/* Custom Tooltip Styling matching Screenshot */
.custom-tooltip-wrapper {
  position: relative;
  display: inline-flex;

  .btn-action-icon {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #475569;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.15s ease;
    text-decoration: none;

    &:hover {
      border-color: #cbd5e1;
      background: #f8fafc;
      color: #0f172a;
    }

    &.active {
      background: #eff6ff;
      color: #2563eb;
      border-color: #bfdbfe;
    }
  }

  .custom-tooltip-text {
    visibility: hidden;
    opacity: 0;
    position: absolute;
    top: calc(100% + 7px);
    left: 50%;
    transform: translateX(-50%);
    background-color: #1e293b;
    color: #ffffff;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
    padding: 5px 9px;
    border-radius: 6px;
    z-index: 100;
    transition: opacity 0.15s ease, transform 0.15s ease;
    pointer-events: none;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);

    &::after {
      content: "";
      position: absolute;
      bottom: 100%;
      left: 50%;
      margin-left: -4px;
      border-width: 4px;
      border-style: solid;
      border-color: transparent transparent #1e293b transparent;
    }
  }

  &:hover .custom-tooltip-text {
    visibility: visible;
    opacity: 1;
  }
}

/* 3. Toolbar Card (Normal Filters vs Bulk Actions) */
.inventory-toolbar-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 6px 10px;
  margin-bottom: 8px;
  transition: all 0.2s ease;
}

/* State A: Bulk Mode */
.toolbar-bulk-mode {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 10px;
  width: 100%;
}

.bulk-mode-left {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
}

.bulk-count-pill {
  background: #eff6ff;
  font-size: 12.5px;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 6px;
  border: 1px solid #bfdbfe;
  display: inline-flex;
  align-items: center;
}

.btn-bulk-item {
  display: inline-flex;
  align-items: center;
  font-size: 12px;
  font-weight: 500;
  padding: 4px 10px;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.15s ease;
  border: 1px solid #e2e8f0;

  &.btn-bulk-danger {
    background: #ffffff;
    border-color: #fca5a5;
    color: #dc2626;

    &:hover {
      background: #fee2e2;
    }
  }

  &.btn-bulk-primary,
  &.btn-bulk-purple {
    background: #ffffff;
    border-color: #e2e8f0;
    color: #475569;

    i {
      color: #64748b;
    }

    &:hover {
      background: #f8fafc;
      border-color: #cbd5e1;
      color: #0f172a;

      i {
        color: #0f172a;
      }
    }
  }
}

.btn-bulk-cancel {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  color: #475569;
  font-size: 12px;
  font-weight: 500;
  padding: 4px 10px;
  border-radius: 6px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  transition: all 0.15s ease;

  i {
    color: #64748b;
  }

  &:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;

    i {
      color: #0f172a;
    }
  }
}

/* State B: Normal Filter Mode */
.toolbar-filter-mode {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 8px;
  width: 100%;
}

.toolbar-search-wrapper {
  position: relative;
  flex: 1;
  min-width: 220px;
  max-width: 360px;
  display: flex;
  align-items: center;
}

.search-icon {
  position: absolute;
  left: 9px;
  font-size: 15px;
  color: #94a3b8;
  pointer-events: none;
}

.toolbar-search-input {
  width: 100%;
  height: 30px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  padding: 0 26px 0 30px;
  font-size: 12.5px;
  color: #1e293b;
  outline: none;
  transition: border-color 0.15s ease, box-shadow 0.15s ease;

  &::placeholder {
    color: #94a3b8;
  }

  &:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
  }
}

.clear-search-btn {
  position: absolute;
  right: 6px;
  background: none;
  border: none;
  color: #94a3b8;
  font-size: 15px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;

  &:hover {
    color: #475569;
  }
}

.toolbar-filters-group {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 6px;
}

.filter-dropdown-wrapper {
  position: relative;
  display: inline-flex;
  align-items: center;
}

.toolbar-select {
  appearance: none;
  -webkit-appearance: none;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  padding: 0 22px 0 9px;
  height: 30px;
  line-height: 28px;
  font-size: 12px;
  font-weight: 500;
  color: #334155;
  cursor: pointer;
  outline: none;
  transition: border-color 0.15s ease;
  min-width: 95px;

  &:hover {
    border-color: #cbd5e1;
  }

  &:focus {
    border-color: #2563eb;
  }
}

.select-chevron {
  position: absolute;
  right: 7px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 13px;
  line-height: 1;
  color: #94a3b8;
  pointer-events: none;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn-reset-filters {
  background: #f1f5f9;
  border: 1px solid #e2e8f0;
  color: #475569;
  font-size: 11.5px;
  font-weight: 600;
  height: 30px;
  padding: 4px 9px;
  border-radius: 6px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  transition: all 0.15s ease;

  &:hover {
    background: #e2e8f0;
    color: #0f172a;
  }
}


/* TIER 1: Header Row (Title & Add Inventory) */
.inventory-tier1-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
  flex-wrap: wrap;
  gap: 10px;

  .inventory-page-title {
    font-size: 1.65rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.2;
    margin: 0;
    letter-spacing: -0.02em;
  }
}

/* TIER 2: Controls Row (Views Dropdown & Action Icons) */
.inventory-tier2-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
  flex-wrap: wrap;
  gap: 8px;
}

.inventory-tier2-actions {
  display: flex;
  align-items: center;
  gap: 6px;

  .btn-tier2-icon {
    width: 32px;
    height: 32px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #475569;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.15s ease;

    &:hover {
      background: #f8fafc;
      border-color: #cbd5e1;
      color: #0f172a;
    }

    &.active {
      background: #eff6ff;
      color: #2563eb;
      border-color: #bfdbfe;
    }
  }
}

/* TIER 3: Card Box (Normal Filter Mode & Bulk Action Mode) */
.inventory-tier3-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 8px 12px;
  margin-bottom: 12px;
  box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
}

.tier3-normal-mode {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 10px;
  width: 100%;
}

.tier3-search-wrap {
  position: relative;
  display: flex;
  align-items: center;
  min-width: 280px;
  max-width: 380px;
  flex: 1;

  .search-icon {
    position: absolute;
    left: 10px;
    font-size: 15px;
    color: #94a3b8;
    pointer-events: none;
  }

  .tier3-search-input {
    width: 100%;
    height: 32px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0 28px 0 32px;
    font-size: 12.5px;
    color: #1e293b;
    outline: none;
    transition: all 0.15s ease;

    &::placeholder {
      color: #94a3b8;
    }

    &:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
    }
  }

  .clear-search-btn {
    position: absolute;
    right: 8px;
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 15px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;

    &:hover {
      color: #475569;
    }
  }
}

.tier3-filters-wrap {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
}

.tier3-select-box {
  position: relative;
  display: inline-flex;
  align-items: center;

  .tier3-select {
    appearance: none;
    -webkit-appearance: none;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0 24px 0 10px;
    height: 32px;
    line-height: 30px;
    font-size: 12.5px;
    font-weight: 500;
    color: #334155;
    cursor: pointer;
    outline: none;
    transition: border-color 0.15s ease;
    min-width: 105px;

    &:hover {
      border-color: #cbd5e1;
    }

    &:focus {
      border-color: #2563eb;
    }
  }

  .select-caret {
    position: absolute;
    right: 7px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 14px;
    color: #94a3b8;
    pointer-events: none;
  }
}

/* Bulk Selection Mode in Tier 3 */
.tier3-bulk-mode {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
  width: 100%;
}

.bulk-pill-badge {
  background: #eff6ff;
  border: 1px solid #bfdbfe;
  color: #2563eb;
  padding: 5px 12px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 500;
  display: inline-flex;
  align-items: center;
  gap: 6px;

  i {
    font-size: 15px;
  }
}

.btn-bulk-text {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 5px 12px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  color: #475569;
  transition: all 0.15s ease;

  i {
    font-size: 14px;
    color: #64748b;
  }

  &:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;

    i {
      color: #0f172a;
    }
  }

  &.btn-bulk-delete {
    background: #ffffff;
    border: 1px solid #fecaca;
    color: #ef4444;

    i {
      color: #ef4444;
    }

    &:hover {
      background: #fef2f2;
      border-color: #f87171;
    }
  }
}

/* 4. Main Workspace: Table + Preview Panel */
.inventory-main-layout {
  display: flex;
  gap: 16px;
  align-items: stretch;
  width: 100%;
  flex: 1 1 auto;
  min-height: 0;
  overflow: hidden;
}

.inventory-table-container {
  flex: 1;
  min-width: 0;
  width: 100%;
  display: flex;
  flex-direction: column;
  min-height: 0;
  height: 100%;
}

.inventory-table-card {
  position: relative;
  background: #ffffff;
  border: 1px solid #e6eaf0;
  border-radius: 12px;
  box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  flex: 1 1 auto;
  min-height: 0;
  height: 100%;
  margin-bottom: 0;
}

/* Loading Overlay for Inventory Table */
.zd-inventory-loading-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.76);
  backdrop-filter: blur(2px);
  z-index: 50;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
  pointer-events: all;

  .zd-loading-indicator-card {
    display: inline-flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.85rem 1.6rem;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    color: #374151;
    font-size: 0.95rem;
    font-weight: 600;

    i {
      font-size: 1.45rem;
      color: #5a8dee;
    }
  }
}

.inventory-table-responsive {
  flex: 1 1 auto;
  width: 100%;
  overflow-x: auto;
  overflow-y: auto;
  min-height: 200px;

  &.compact {
    .inventory-table-head-row th {
      padding: 0.35rem 0.65rem !important;
      font-size: 0.825rem !important;
    }
    .inventory-table-row td {
      padding: 0.15rem 0.65rem !important;
      font-size: 0.825rem !important;
    }
  }

  /* Custom slim scrollbar */
  &::-webkit-scrollbar {
    width: 6px;
    height: 6px;
  }
  &::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
  }
  &::-webkit-scrollbar-track {
    background: transparent;
  }
}

.inventory-main-table {
  width: 100%;
  min-width: 1150px;
  border-collapse: separate;
  border-spacing: 0;
  margin: 0;
  font-size: 0.8125rem;
  color: #1e293b;
  background: #ffffff;
}

.inventory-table-head-row {
  background: #f8fafc;
  position: sticky;
  top: 0;
  z-index: 2;

  th {
    padding: 0.6rem 0.55rem;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.04em;
    color: #475569;
    border-top: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
    background: #f8fafc;
    text-transform: uppercase;
  }

  .th-checkbox {
    width: 36px;
    min-width: 36px;
    text-align: center;
  }

  .th-photo {
    width: 56px;
    min-width: 56px;
    text-align: center;
  }

  .th-vehicle {
    min-width: 290px;
    max-width: 360px;
  }

  .th-dis {
    width: 55px;
    min-width: 55px;
    text-align: left;
  }

  .th-odometer {
    width: 110px;
    min-width: 110px;
    text-align: left;
  }

  .th-special-price {
    width: 105px;
    min-width: 105px;
    text-align: left;
  }

  .th-price {
    width: 105px;
    min-width: 105px;
    text-align: left;
  }

  .th-status {
    width: 110px;
    min-width: 110px;
    text-align: center;
  }

  .th-creation-date {
    width: 105px;
    min-width: 105px;
  }

  .th-body-type {
    width: 85px;
    min-width: 85px;
  }

  .th-last-sync {
    width: 100px;
    min-width: 100px;
  }

  .th-website-vdp,
  .th-repair-report,
  .th-action {
    width: 65px;
    min-width: 65px;
    text-align: center;
  }

  .th-modified-at {
    width: 105px;
    min-width: 105px;
  }

  .th-syndication-header {
    min-width: 100px;
  }

  .th-sortable {
    cursor: pointer;
    user-select: none;
    transition: color 0.15s ease;

    &:hover {
      color: #0f172a;
    }
  }

  .lr-sort {
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    user-select: none;
    font-weight: 700;
    font-size: 11px;
    letter-spacing: 0.04em;
    color: #475569;

    > span {
      display: inline-flex;
      align-items: center;
    }
    .vehicle-th-labels {
      display: inline-flex;
      align-items: center;
      gap: 12px;
    }
    .sort-active {
      font-size: 14px;
      color: #2563eb;
      font-weight: 800;
    }
    .sort-idle {
      color: #94a3b8;
      font-size: 12px;
      margin-left: 2px;
    }
    &:hover .sort-idle {
      color: #0f172a;
    }
  }
}

/* Custom Checkbox */
.custom-chk-container {
  display: inline-flex;
  position: relative;
  cursor: pointer;
  user-select: none;
  margin: 0;

  .custom-chk-input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;

    &:checked ~ .custom-chk-box {
      background-color: #2563eb;
      border-color: #2563eb;

      &:after {
        display: block;
      }
    }
  }

  .custom-chk-box {
    position: relative;
    display: inline-block;
    height: 15px;
    width: 15px;
    background-color: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 3px;
    transition: all 0.15s ease;

    &:after {
      content: "";
      position: absolute;
      display: none;
      left: 4.5px;
      top: 1.5px;
      width: 4px;
      height: 8px;
      border: solid white;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg);
    }
  }

  &:hover .custom-chk-box {
    border-color: #94a3b8;
  }
}

/* 5. Pagination Footer */
.inventory-pagination-footer {
  flex-shrink: 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
  padding: 0.25rem 0.85rem;
  border-top: 1px solid #e2e8f0;
  background: #ffffff;
  z-index: 5;
}

.pagination-info {
  font-size: 0.8rem;
  color: #64748b;
  font-weight: 500;
}

.pagination-controls {
  display: flex;
  align-items: center;
  gap: 2px;
}

.pagination-nav-btn {
  width: 25px;
  height: 25px;
  border-radius: 4px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  color: #475569;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.85rem;
  cursor: pointer;
  transition: all 0.15s ease;

  &:hover:not(:disabled) {
    background: #f1f5f9;
    color: #0f172a;
  }

  &:disabled {
    opacity: 0.35;
    cursor: not-allowed;
  }
}

.pagination-num-btn {
  min-width: 25px;
  height: 25px;
  padding: 0 4px;
  border-radius: 4px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  color: #475569;
  font-size: 0.775rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.15s ease;

  &:hover:not(:disabled):not(.active) {
    background: #f1f5f9;
    color: #0f172a;
  }

  &.active {
    background: #eff6ff;
    border-color: #2563eb;
    color: #2563eb;
    font-weight: 600;
  }

  &.ellipsis {
    border: none;
    background: transparent;
    cursor: default;
    color: #94a3b8;
  }
}

.pagination-page-size {
  display: flex;
  align-items: center;
  font-size: 0.8rem;
  color: #64748b;
}

.page-size-select {
  border: 1px solid #e2e8f0;
  border-radius: 4px;
  padding: 1px 4px;
  font-size: 0.775rem;
  height: 25px;
  color: #1e293b;
  outline: none;
  background: #ffffff;
  cursor: pointer;

  &:focus {
    border-color: #2563eb;
  }
}

/* Transitions for Right Preview Panel (Sidebar to whole page) */
.slide-panel-enter-active,
.slide-panel-leave-active {
  transition: transform 0.3s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.2s ease;
}

.slide-panel-enter-from,
.slide-panel-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

@media (max-width: 1200px) {
  .inventory-main-layout {
    flex-direction: column;
  }
}
</style>

<style lang="scss">
/* Suppress pace progress and header shadow */
.pace,
.pace-progress {
  display: none !important;
}
.header-navbar-shadow {
  display: none !important;
}
</style>
