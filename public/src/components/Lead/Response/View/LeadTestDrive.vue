<template>
  <div
      class="tab-pane"
      v-show="show"
      id="lead-view-testdrive"
      aria-labelledby="lead-view-testdrive"
      role="tabpanel"
  >
    <section id="lead-view-testdrive-section">
      <div class="row match-height m-1">
        <div class="col-12 col-xs-12">
                <h4 class="card-title col-12">Test Drive</h4>
                 <hr/>
                <div v-if="isLoading" class="text-center py-5">
                  <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                  <p class="mt-2 text-muted">Loading test drive details...</p>
                </div>
                <div v-else>
                  <div v-if="!testDrives.length" class="text-muted">
                    No test drive records found for this lead.
                  </div>
                  <div v-for="testDrive in testDrives" :key="`lead_test_drive_${testDrive.id}`"
                       class="p-2 mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <div>
                        <strong>{{ testDrive.status || 'N/A' }}</strong>
                        <div class="text-muted small">{{ formatDate(testDrive.created_at) }}</div>
                      </div>
                      <router-link
                          :to="{ name: 'test-drive-detail', params: { id: testDrive.id } }"
                          class="btn btn-sm btn-outline-primary"
                      >
                        View Test Drive
                      </router-link>
                    </div>
                    <div class="row">
<!--                      <div class="col-12 col-md-4 mb-1">-->
<!--                        <label class="text-bold-500">Customer</label>-->
<!--                        <p class="mb-0">{{ customerName(testDrive) }}</p>-->
<!--                      </div>-->
<!--                      <div class="col-12 col-md-4 mb-1">-->
<!--                        <label class="text-bold-500">Phone</label>-->
<!--                        <p class="mb-0">{{ testDrive.phone || 'N/A' }}</p>-->
<!--                      </div>-->
<!--                      <div class="col-12 col-md-4 mb-1">-->
<!--                        <label class="text-bold-500">Email</label>-->
<!--                        <p class="mb-0">{{ testDrive.email || 'N/A' }}</p>-->
<!--                      </div>-->
                      <div class="col-12 col-md-3 mb-1">
                        <label class="text-bold-500">Vehicle</label>
                        <p class="mb-0">{{ testDrive.vehicle || 'N/A' }}</p>
                      </div>
                      <div class="col-12 col-md-3 mb-1">
                        <label class="text-bold-500">Stock#</label>
                        <p class="mb-0">{{ testDrive.stock_no || 'N/A' }}</p>
                      </div>
                      <div class="col-12 col-md-3 mb-1">
                        <label class="text-bold-500">VIN</label>
                        <p class="mb-0">{{ testDrive.vin || 'N/A' }}</p>
                      </div>
                      <div class="col-12 col-md-3 mb-1">
                        <label class="text-bold-500">Number Plate</label>
                        <p class="mb-0">{{ testDrive.number_plate || 'N/A' }}</p>
                      </div>
<!--                      <div class="col-12 col-md-4 mb-1">-->
<!--                        <label class="text-bold-500">Sales Rep</label>-->
<!--                        <p class="mb-0">{{ testDrive.sales_rep_id || 'N/A' }}</p>-->
<!--                      </div>-->
<!--                      <div class="col-12 col-md-4 mb-1">-->
<!--                        <label class="text-bold-500">Issue</label>-->
<!--                        <p class="mb-0">{{ testDrive.issue || 'No' }}</p>-->
<!--                      </div>-->
                    </div>
                    <hr/>
                  </div>
                </div>
              </div>

      </div>
    </section>
  </div>
</template>

<script>
export default {
  props: {
    id: {required: true},
    allUsers: {required: true},
    app_metadata: {required: true},
    time_zone: {required: false},
    module: {required: true},
    show: {required: false, default: false},
    module_id: {required: true},
  },
  name: "LeadTestDrive",
  data() {
    return {
      testDrives: [],
      isLoading: false,
    };
  },
  methods: {
    formatDate(value) {
      if (!value) {
        return "N/A";
      }

      if (typeof value === "string") {
        return value;
      }

      if (value && value.date) {
        return value.date;
      }

      return "N/A";
    },
    customerName(testDrive) {
      return [testDrive.first_name, testDrive.last_name].filter(Boolean).join(" ") || "N/A";
    },
    async getLeadTestDrives(module, module_id) {
      this.isLoading = true;
      await axios({
        method: "get",
        url: `${window.location.origin}/api/business/test-drives/${module.toLowerCase()}/show/${module_id}`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
      })
          .then(({data}) => {
            this.testDrives = data;
            Event.fire("testdrive_count", data.length);
            this.isLoading = false;
          })
          .catch(({response}) => {
            console.log(response);
            Event.fire("testdrive_count", 0);
            this.isLoading = false;
          });
    },
  },
  async created() {
    await this.getLeadTestDrives(this.module, this.module_id);
  },
  beforeDestroy() {
    Event.vue.$off("testdrive_count", null);
  },
};
</script>

