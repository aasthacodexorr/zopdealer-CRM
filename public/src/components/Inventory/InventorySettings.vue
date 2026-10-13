<template>
  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <!-- form layout section start -->
    <div class="content-body">
      <div class="content-wrapper mt-3 pt-0 pr-0 pl-0">
        <div class="app-content-overlay"></div>
        <form class="grid-list-form">
          <section id="grid-list">
            <div class="sidebar-left border-right">
              <div class="sidebar pt-1">
                <div class="todo-sidebar d-flex">
                  <!-- todo app menu -->
                  <div class="todo-app-menu">
                    <div class="form-group text-center border-bottom mb-0">
                      <!-- new task button -->
                      <div class="row">
                        <div class="col-md-12 col-12">
                          <h3
                            class="content-header-title float-left pl-2 pb-2 mb-0"
                          >
                            Inventory settings
                          </h3>
                        </div>
                      </div>
                    </div>
                    <!-- sidebar list start -->
                    <div class="sidebar-menu-list p-0">
                      <div class="list-grid">
                        <ul
                          class="nav flex-column text-center text-md-left team-setting-tab"
                        >
                          <li class="nav-item m-0">
                            <a
                              class="nav-link active"
                              id="team-pill-1"
                              data-toggle="pill"
                              href="#inventory-setting-file"
                              aria-expanded="true"
                            >
                              Setting
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                              class="nav-link"
                              id="team-pill-2"
                              data-toggle="pill"
                              href="#image-setting"
                              aria-expanded="false"
                            >
                              Images
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                              class="nav-link"
                              id="team-pill-2"
                              data-toggle="pill"
                              href="#syndication-setting"
                              aria-expanded="false"
                            >
                              Syndication
                            </a>
                          </li>
                          <li class="nav-item m-0">
                            <a
                              class="nav-link"
                              id="team-pill-2"
                              data-toggle="pill"
                              href="#locations-setting"
                              aria-expanded="false"
                            >
                              Locations
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <!-- sidebar list end -->
                  </div>
                </div>
              </div>
            </div>
            <div class="content-right">
              <div class="todo-app-list">
                <div class="webapp-form" id="team-form">
                  <div class="tab-content">
                    <!-- InventorySettingsGeneral -->
                    <inventory-settings-general></inventory-settings-general>
                    <!-- End InventorySettingsGeneral -->
                    <!-- InventorySettingImage -->
                    <inventory-settings-image></inventory-settings-image>
                    <!-- End InventorySettingsImage -->
                    <!-- InventorySettingSyndication -->
                    <inventory-settings-syndication></inventory-settings-syndication> 
                    <!-- End InventorySettingSyndication -->

                    <inventory-settings-location></inventory-settings-location>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <!-- form layout section end -->
        </form>
      </div>
    </div>
  </div>
  <!-- END: Content-->
</template>
<script>
import InventorySettingsGeneral from "./InventorySettingsGeneral";
import InventorySettingsLocation from "./InventorySettingsLocation";
import InventorySettingsImage from "./InventorySettingsImage";
import InventorySettingsSyndication from './InventorySettingsSyndication';
export default {
  name: "InventorySettings",
  components: {
    InventorySettingsGeneral,
    InventorySettingsLocation,
    InventorySettingsImage,
    InventorySettingsSyndication
  },
};
</script>
