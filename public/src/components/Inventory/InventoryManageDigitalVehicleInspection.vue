<template>
  <section id="customer-deals">
    <div class="row match-height m-1">
      <div class="col-12 col-xs-12">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th scope="col" class="text-center">Title</th>
                <th scope="col" class="text-center">Status</th>
                <th scope="col" class="text-center">Assignee</th>
                <th scope="col" class="text-center">Issues</th>
                <th scope="col" class="text-center">Creation Date</th>
                <th scope="col" class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="digital_vehicle_inspection in digital_vehicle_inspection_listing"
                :key="digital_vehicle_inspection.id"
              >
                <td>{{ digital_vehicle_inspection.title }}</td>
                <td class="text-center">
                  <span>
                    <span
                      v-if="digital_vehicle_inspection.status == 'INCOMPLETE'"
                      class="badge badge-light-danger"
                      >{{ digital_vehicle_inspection.status }}</span
                    >
                    <span
                      v-else-if="digital_vehicle_inspection.status == 'ARCHIVE'"
                      class="badge badge-light-warning"
                      >{{ digital_vehicle_inspection.status }}</span
                    >
                    <span
                      v-else-if="
                        digital_vehicle_inspection.status == 'COMPLETE'
                      "
                      class="badge badge-light-success"
                      >{{ digital_vehicle_inspection.status }}</span
                    >
                    <span v-else class="badge badge-light-secondary">{{
                      digital_vehicle_inspection.status
                    }}</span>
                  </span>
                </td>
                <td class="text-center">
                  {{ assigneeName(digital_vehicle_inspection.assignee) }}
                </td>
                <td class="text-center">
                  <span>
                    <span
                      v-if="digital_vehicle_inspection.issues_count > 0"
                      class="badge badge-pill badge-danger text-bold"
                      >{{ digital_vehicle_inspection.issues_count }}</span
                    >
                  </span>
                </td>
                <td class="text-center">
                  {{ parseDate(digital_vehicle_inspection.created_at) }}
                </td>
                <td class="text-center">
                  <router-link
                    tag="i"
                    title="View DVI"
                    style="cursor: pointer; display: inline-block"
                    :to="{
                      name: 'dvi-update',
                      params: { id: digital_vehicle_inspection.id },
                    }"
                    exact
                    class="bx bx-link-external btn btn-primary mr-1"
                  >
                  </router-link>

                  <i
                  @click.prevent="downloadPDF(digital_vehicle_inspection.id)"
                    style="cursor: pointer; display: inline-block"
                    class="bx bx-download btn btn-info mr-1"
                  ></i>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
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
