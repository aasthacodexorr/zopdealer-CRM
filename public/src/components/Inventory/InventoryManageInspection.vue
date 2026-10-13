<template>
  <div
    class="tab-pane"
    id="inventory-inspection-panel"
    aria-labelledby="Inventory Inspection Panel"
    role="tabpanel"
  >
    <section id="inventory-inspection-section">
      <div class="row match-height">
        <div class="col-md-12 col-12">
          <ul
            class="nav nav-pills"
            id="pills-tab"
            role="tablist"
            style="justify-content: center;"
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
                >Internal Inspection</a
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
                >Web Inspection</a
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
                >Digital Vehicle Inspection</a
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
  ul.nav {
    a {
      text-decoration: none;
    }
    a.active {
      color: white;
    }
  }
}
</style>
