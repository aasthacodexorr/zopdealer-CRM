<template>
  <section id="customer-deals" class="dvi-page">
    <div class="dvi-card">
      <div class="table-responsive">
        <table class="table dvi-table">
          <thead>
            <tr>
              <th scope="col">Title</th>
              <th scope="col" class="text-center">Status</th>
              <th scope="col">Assignee</th>
              <th scope="col" class="text-center">Issues</th>
              <th scope="col">Creation Date</th>
              <th scope="col" class="text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="digital_vehicle_inspection in digital_vehicle_inspection_listing"
              :key="digital_vehicle_inspection.id"
            >
              <td class="dvi-title">{{ digital_vehicle_inspection.title }}</td>
              <td class="text-center">
                <span>
                  <span
                    v-if="digital_vehicle_inspection.status == 'INCOMPLETE'"
                    class="badge badge-light-danger dvi-badge"
                    >{{ digital_vehicle_inspection.status }}</span
                  >
                  <span
                    v-else-if="digital_vehicle_inspection.status == 'ARCHIVE'"
                    class="badge badge-light-warning dvi-badge"
                    >{{ digital_vehicle_inspection.status }}</span
                  >
                  <span
                    v-else-if="
                      digital_vehicle_inspection.status == 'COMPLETE'
                    "
                    class="badge badge-light-success dvi-badge"
                    >{{ digital_vehicle_inspection.status }}</span
                  >
                  <span v-else class="badge badge-light-secondary dvi-badge">{{
                    digital_vehicle_inspection.status
                  }}</span>
                </span>
              </td>
              <td class="dvi-muted">
                {{ assigneeName(digital_vehicle_inspection.assignee) }}
              </td>
              <td class="text-center">
                <span>
                  <span
                    v-if="digital_vehicle_inspection.issues_count > 0"
                    class="badge badge-pill badge-danger text-bold dvi-issues"
                    >{{ digital_vehicle_inspection.issues_count }}</span
                  >
                </span>
              </td>
              <td class="dvi-muted">
                {{ parseDate(digital_vehicle_inspection.created_at) }}
              </td>
              <td class="text-right dvi-actions">
                <router-link
                  tag="i"
                  title="View DVI"
                  style="cursor: pointer; display: inline-block"
                  :to="{
                    name: 'dvi-update',
                    params: { id: digital_vehicle_inspection.id },
                  }"
                  exact
                  class="bx bx-link-external btn btn-primary dvi-btn dvi-btn--primary"
                >
                </router-link>

                <i
                @click.prevent="downloadPDF(digital_vehicle_inspection.id)"
                  style="cursor: pointer; display: inline-block"
                  class="bx bx-download btn btn-info dvi-btn dvi-btn--ghost"
                ></i>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>
<script>
export default {
  props: {
    allUsers: { required: true },
    app_metadata: { required: true },
    time_zone: String,
    stock_no: { required: true },
  },
  name: "InventoryManageDigitalVehicleInspection",
  data() {
    return {
      digital_vehicle_inspection_listing: [],
    };
  },
  watch: {
    stock_no: function(newVal) {
      const vm = this;
      if (vm.updateView && newVal) {
        vm.getDigitalVehicleInspectionByStockNumber(newVal);
      }
    },
  },
  computed: {
    updateView() {
      return this.$route.params.id && this.$route.name == "inventory-update";
    },
  },
  methods: {
    parseDate(date) {
      return date == null || date == ""
        ? ""
        : moment(date).format("MMMM Do YYYY");
    },
    assigneeName(assignee) {
      return this.$helpers.assigneeName(this.allUsers, assignee, "");
    },
    getDigitalVehicleInspectionByStockNumber(stock_no) {
      axios({
        method: "get",
        url: `${window.location.origin}/api/business/dvi?all=true&paginate=false&zm_stock_no[]=${stock_no}`,

        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then(({ data }) => {
          this.digital_vehicle_inspection_listing =
            data.digital_vehicle_inspection_listing;
        })
        .catch(({ response }) => {
          console.log(response);
        });
    },
    downloadPDF(entity_id) {
      axios({
        method: "get",
        url: `${window.location.origin}/api/business/dvi/pdf/dvi-complete/generate/${entity_id}`,
        responseType: "blob",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
        .then((response) => {
          const disposition = response.headers["content-disposition"];

          let fileName = "DVI.pdf";

          if (disposition) {
            const match = disposition.match(/filename="?([^"]+)"?/);
            if (match && match[1]) {
              fileName = match[1];
            }
          }

          const blob = new Blob([response.data], {
            type: response.headers["content-type"] || "application/pdf",
          });

          const url = window.URL.createObjectURL(blob);
          window.open(url);
        })
        .catch(() => {
          console.log("There were some errors in retrieving the DVI");
        });
    },
  },
};
</script>
<style lang="scss">
/* Inventory — Inspection › Digital Vehicle Inspection (look only). */
#inventory-inspection-panel .dvi-page {
  .dvi-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
  }
  .table-responsive { margin: 0; }
  .dvi-table {
    margin: 0;
    th {
      padding: 12px 16px;
      background: #f8fafc;
      border-top: 0;
      border-bottom: 1px solid #e2e8f0;
      font-size: 12px;
      font-weight: 600;
      color: #475569;
      text-transform: none;
      letter-spacing: 0;
    }
    td {
      padding: 12px 16px;
      border-top: 1px solid #f1f5f9;
      vertical-align: middle;
      font-size: 13px;
      color: #1e293b;
    }
    tbody tr:first-child td { border-top: 0; }
    tbody tr:hover td { background: #fafbfd; }
  }
  .dvi-title { font-weight: 500; }
  .dvi-muted { color: #64748b; }
  .dvi-badge {
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.02em;
  }
  .dvi-issues {
    min-width: 24px;
    padding: 4px 8px;
    font-size: 12px;
  }
  .dvi-actions { white-space: nowrap; }
  .dvi-btn {
    width: 34px;
    height: 34px;
    padding: 0;
    margin-left: 6px;
    border-radius: 8px;
    font-size: 16px;
    line-height: 34px;
    text-align: center;
    box-shadow: none !important;
    &--primary {
      background: #2563eb !important;
      border-color: #2563eb !important;
      color: #ffffff !important;
    }
    &--ghost {
      background: #ffffff !important;
      border: 1px solid #e2e8f0 !important;
      color: #475569 !important;
      &:hover { background: #f8fafc !important; color: #0f172a !important; }
    }
  }
}
</style>

