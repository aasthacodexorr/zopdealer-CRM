<template>
  <div
    class="tab-pane"
    id="inventory-inspection-panel"
    aria-labelledby="Inventory Inspection Panel"
    role="tabpanel"
  >
    <section id="inventory-inspection-section" class="ins-page">
      <div class="ins-wrap">
        <div>
          <ul
            class="nav nav-pills ins-pills"
            id="pills-tab"
            role="tablist"
          >
            <li class="nav-item">
              <a
                class="nav-link"
                id="internal-inspection-tab"
                data-toggle="pill"
                href="#internal-inspection"
                role="tab"
                aria-controls="internal-inspection"
                aria-selected="true"
                ><i class="bx bx-clipboard"></i>Internal Inspection</a
              >
            </li>
            <li class="nav-item">
              <a
                class="nav-link"
                id="web-inspection-tab"
                data-toggle="pill"
                href="#web-inspection"
                role="tab"
                aria-controls="web-inspection"
                aria-selected="false"
                ><i class="bx bx-globe"></i>Web Inspection</a
              >
            </li>
            <li class="nav-item">
              <a
                class="nav-link active"
                id="dvi-tab"
                data-toggle="pill"
                href="#dvi"
                role="tab"
                aria-controls="dvi"
                aria-selected="false"
                ><i class="bx bx-search-alt"></i>Digital Vehicle Inspection</a
              >
            </li>
          </ul>
          <div class="tab-content" id="pills-tabContent">
            <div
              class="tab-pane fade"
              id="internal-inspection"
              role="tabpanel"
              aria-labelledby="internal-inspection-tab"
            >
              <inventory-manage-internal-inspection
                :allUsers="allUsers"
                :app_metadata="app_metadata"
                :id="$route.params.id"
                :time_zone="time_zone"
              ></inventory-manage-internal-inspection>
            </div>
            <div
              class="tab-pane fade"
              id="web-inspection"
              role="tabpanel"
              aria-labelledby="web-inspection-tab"
            >
              <inventory-manage-web-inspection
                :allUsers="allUsers"
                :app_metadata="app_metadata"
                :id="$route.params.id"
                :time_zone="time_zone"
              ></inventory-manage-web-inspection>
            </div>
            <div
              class="tab-pane fade show active"
              id="dvi"
              role="tabpanel"
              aria-labelledby="dvi-tab"
            >
              <inventory-manage-digital-vehicle-inspection
                :allUsers="allUsers"
                :app_metadata="app_metadata"
                :stock_no="stock_no"
                :time_zone="time_zone"
              ></inventory-manage-digital-vehicle-inspection>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import InventoryManageWebInspection from "./InventoryManageWebInspection";
import InventoryManageInternalInspection from "./InventoryManageInternalInspection";
import InventoryManageDigitalVehicleInspection from "./InventoryManageDigitalVehicleInspection";
export default {
  name: "InventoryManageInspection",
  props: {
    id: { required: true },
    allUsers: { required: true },
    app_metadata: { required: true },
    stock_no: { required: true },
    time_zone: String,
  },
  components: {
    InventoryManageWebInspection,
    InventoryManageInternalInspection,
    InventoryManageDigitalVehicleInspection,
  },
};
</script>
<style lang="scss" scoped>
#inventory-inspection-panel {
  .ins-page {
    padding: 8px 20px 96px;
  }
  .ins-pills {
    display: inline-flex;
    flex-wrap: wrap;
    gap: 4px;
    padding: 4px;
    margin: 0 0 18px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #f1f5f9;
    .nav-item { margin: 0; }
    a {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      height: 36px;
      padding: 0 16px;
      border-radius: 9px;
      font-size: 13px;
      font-weight: 500;
      color: #475569;
      text-decoration: none;
      background: transparent;
      box-shadow: none;
      transition: background 0.15s, color 0.15s;
      i { font-size: 16px; }
      &:hover { color: #0f172a; background: rgba(255, 255, 255, 0.6); }
    }
    a.active {
      color: #2563eb;
      background: #ffffff;
      box-shadow: 0 1px 3px rgba(15, 23, 42, 0.12);
    }
  }
}
</style>
