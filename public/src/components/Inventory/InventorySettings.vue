<template>
  <!-- BEGIN: Content-->
  <div class="app-content content zd-inventory-settings-page">
    <div class="content-overlay"></div>
    <!-- form layout section start -->
    <div class="content-body">
      <div class="content-wrapper pt-0 pr-0 pl-0">
        <div class="app-content-overlay"></div>
        <form class="grid-list-form">
          <section id="grid-list" class="zd-settings-shell">
            <div class="sidebar-left border-right">
              <div class="sidebar">
                <div class="todo-sidebar d-flex">
                  <!-- todo app menu -->
                  <div class="todo-app-menu">
                    <div class="form-group text-center border-bottom mb-0">
                      <!-- new task button -->
                      <div class="row w-100 m-0">
                        <div class="col-md-12 col-12 p-0">
                          <h3
                            class="content-header-title mb-0"
                          >
                            Inventory Settings
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
                              <i class="bx bx-slider-alt"></i>
                              <span>Setting</span>
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
                              <i class="bx bx-image"></i>
                              <span>Images</span>
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
                              <i class="bx bx-transfer"></i>
                              <span>Syndication</span>
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
                              <i class="bx bx-map-pin"></i>
                              <span>Locations</span>
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
<style lang="scss">
.zd-inventory-settings-page {
  background: #f8fafc;

  .zd-settings-shell {
    display: flex;
    align-items: stretch;
    min-height: calc(100vh - 68px);
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
  }

  .sidebar-left {
    width: 248px;
    min-width: 248px;
    background: #ffffff;
    border-right: 1px solid #e2e8f0 !important;
    flex-shrink: 0;

    .sidebar,
    .todo-sidebar,
    .todo-app-menu {
      width: 100%;
      margin: 0 !important;
      padding: 0 !important;
      position: static !important;
      height: auto !important;
    }

    .border-bottom {
      border-bottom: 1px solid #e2e8f0 !important;
      height: 60px !important;
      min-height: 60px !important;
      max-height: 60px !important;
      box-sizing: border-box !important;
      display: flex !important;
      align-items: center !important;
      padding: 0 1.25rem !important;
      margin: 0 !important;
    }

    h3.content-header-title {
      float: none !important;
      padding: 0 !important;
      margin: 0 !important;
      font-size: 1.05rem !important;
      font-weight: 700 !important;
      color: #0f172a !important;
      letter-spacing: -0.01em;
      text-align: left;
    }

    .sidebar-menu-list {
      padding: 0.75rem !important;
    }

    .team-setting-tab {
      display: flex;
      flex-direction: column;
      gap: 4px;

      .nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0.65rem 0.9rem;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 500;
        color: #475569;
        transition: background-color 0.15s ease, color 0.15s ease;

        i {
          font-size: 1.15rem;
          color: #64748b;
        }

        &:hover {
          background: #f1f5f9;
          color: #0f172a;

          i {
            color: #0f172a;
          }
        }

        &.active {
          background: #eff6ff !important;
          color: #2563eb !important;
          font-weight: 600;

          i {
            color: #2563eb !important;
          }
        }
      }
    }
  }

  .content-right {
    flex: 1 1 auto;
    min-width: 0;
    background: #f8fafc;

    .todo-app-list,
    #team-form,
    .tab-content,
    .tab-pane {
      width: 100%;
      margin: 0 !important;
      padding: 0 !important;
    }

    .tab-pane > .content-wrapper.border-bottom {
      background: #ffffff;
      border-bottom: 1px solid #e2e8f0 !important;
      height: 60px !important;
      min-height: 60px !important;
      max-height: 60px !important;
      box-sizing: border-box !important;
      display: flex !important;
      align-items: center !important;
      padding: 0 1.75rem !important;
      margin: 0 !important;
      position: sticky;
      top: 0;
      z-index: 15;

      > form,
      > .row {
        width: 100%;
        margin: 0;
        align-items: center;
      }

      .row {
        align-items: center;
      }

      .title-sizeincrease-font {
        font-size: 1.2rem !important;
        font-weight: 700 !important;
        color: #0f172a !important;
      }

      ul {
        margin-bottom: 0;
      }

      .btn {
        height: 36px;
        padding: 0 1.15rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
      }
    }

    .list-right-task-list {
      padding: 1.5rem 1.75rem 3rem !important;
      background: #f8fafc !important;
      height: auto !important;
      max-height: none !important;
      border: none !important;

      .content-wrapper.border-bottom {
        background: #ffffff;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px;
        padding: 1.5rem !important;
        margin-bottom: 1.25rem !important;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
      }

      h6.text-danger {
        display: flex;
        align-items: center;
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e !important;
        border-radius: 8px;
        padding: 0.65rem 1rem;
        font-size: 0.82rem !important;
        font-weight: 500;
        margin-bottom: 1.25rem !important;
      }

      .col-md-4 > label {
        font-size: 0.84rem;
        font-weight: 600;
        color: #334155;
        min-height: 38px;
        display: flex;
        align-items: center;
        margin-bottom: 0;
      }

      .form-control,
      .custom-select {
        height: 38px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #0f172a;

        &:focus {
          border-color: #3b82f6;
          box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.14);
        }
      }

      .quill-editor {
        width: 100%;
      }

      .text-right {
        display: flex;
        justify-content: flex-end;
        gap: 0.65rem;
        margin-bottom: 1rem;
      }
    }

    .list-right-task-syndication {
      padding: 16px 24px 30px !important;
    }

    .list-right-task-locations,
    #image-setting > .content-wrapper:not(.border-bottom),
    #locations-setting > .content-wrapper:not(.border-bottom) {
      padding: 1.5rem 1.75rem 2rem !important;
    }

    .list-right-task-syndication .list-image-content {
      height: calc(100vh - 168px) !important;
      max-height: none !important;
      overflow: auto !important;
    }

    .dataTables_paginate,
    #table-extended-transactions_paginate {
      margin-top: 1rem;
      padding: 0.25rem 0;

      .pagination {
        display: flex !important;
        justify-content: flex-end !important;
        align-items: center !important;
        gap: 6px !important;
        margin: 0 !important;
      }

      .page-item {
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        background: transparent !important;

        .page-link {
          min-width: 32px;
          height: 32px;
          padding: 0 10px !important;
          border-radius: 8px !important;
          border: 1px solid #e2e8f0 !important;
          background: #ffffff !important;
          color: #334155 !important;
          font-size: 13px !important;
          font-weight: 500 !important;
          display: inline-flex !important;
          align-items: center !important;
          justify-content: center !important;
          box-shadow: none !important;
          transition: all 0.15s ease;

          &:hover {
            background: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
          }
        }

        &.active .page-link {
          background: #2563eb !important;
          border-color: #2563eb !important;
          color: #ffffff !important;
          font-weight: 600 !important;
          box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25) !important;
        }

        &.disabled .page-link {
          opacity: 0.45 !important;
          background: #f8fafc !important;
          color: #94a3b8 !important;
          cursor: not-allowed !important;
        }
      }
    }

    .list-right-task-syndication,
    .list-image-content {
      table {
        background: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e2e8f0;

        thead tr {
          background: #f8fafc !important;
          border-bottom: 1px solid #e2e8f0;

          th {
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #475569;
            padding: 0.75rem 1rem !important;
          }
        }

        tbody tr {
          border-bottom: 1px solid #f1f5f9;
          transition: background-color 0.15s ease;

          &:hover {
            background: #f8fafc;
          }

          td {
            padding: 0.75rem 1rem !important;
            vertical-align: middle;
          }
        }
      }
    }
  }
}

@media (max-width: 991px) {
  .zd-inventory-settings-page .zd-settings-shell {
    flex-direction: column;
  }

  .zd-inventory-settings-page .sidebar-left {
    width: 100%;
    min-width: 100%;
    border-right: none !important;
    border-bottom: 1px solid #e2e8f0;
  }

  .zd-inventory-settings-page .sidebar-left .team-setting-tab {
    flex-direction: row;
    overflow-x: auto;
  }
}
</style>
