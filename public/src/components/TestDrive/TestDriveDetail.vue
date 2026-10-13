<template>
  <div v-if="isAuthorized">
    <div class="app-content content fixed-height-block">
      <div class="">
        <ul class="right-scroll-list nav nav-tabs" role="tablist">
          <li class="nav-item pr-0 pl-0">
            <router-link
                title="Back To List"
                tag="a"
                :to="{ name: 'test-drive-list' }"
                class="nav-link pr-0 pl-0 d-flex align-items-center"
                exact
            >
              <i class="bx bx-caret-left" style="font-size: 1.4em"></i>
            </router-link>
          </li>
          <li class="nav-item">
            <a
                class="nav-link d-flex align-items-center active"
                id="test-drive-detail-tab"
                data-toggle="tab"
                href="#test-drive-detail"
                aria-controls="test-drive-detail"
                role="tab"
                aria-selected="true"
            >
              <span class="">Test Drive Details</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="">
        <div class="content-body mb-3">
          <div class="tab-content">
            <div
                class="tab-pane active"
                id="test-drive-detail"
                aria-labelledby="test-drive-detail"
                role="tabpanel"
            >
              <!-- Loading -->
              <div v-if="isLoadingPrintData" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading test drive details...</p>
              </div>

              <!-- Full detail view using printData -->
              <section v-else-if="printData">
                <div class="row">
                  <!-- Page header -->
                  <div class="col-12 mb-2">
                    <h3 class="m-width-s display-8 ml-1" style="vertical-align: super">
                      <i data-v-c6dd3404="" data-icon="inventory" class="bx bxs-car bx-md mr-1"></i>
                      {{ getVehicleDescription(printData.inventory) }} #{{ printData.inventory.stock_no || 'N/A' }}
                      ({{ printData.inventory.vin || 'N/A' }})
                      <span class="pl-1" style="font-size: 0.4em">Created At : {{
                          printData.test_drive.created_at.date
                        }} Last Updated : {{ printData.test_drive.updated_at.date }}</span>
                    </h3>

                    <span class="badge mr-2"
                          :class="getStatusClass(printData.test_drive.status)">{{ printData.test_drive.status }}</span>
                    <button @click="exportCSV" class="btn btn-sm btn-outline-secondary">
                      <i class="bx bx-export"></i> Export CSV
                    </button>
                    <button @click="exportPDF" class="btn btn-sm btn-outline-primary ml-1">
                      <i class="bx bx-file-pdf"></i> Export PDF
                    </button>
                  </div>

                  <!-- 1. Test Drive Information -->
                  <div class="col-12 mb-2">
                    <div class="card-body pb-1 mx-25">
                      <h4 class="card-title">
                        Test Drive Information</h4>
                      <hr/>
                      <div class="row">
                        <div class="col-12 col-md-4 mb-1"><label class="text-bold-500">Start Time</label>
                          <p>{{ formatDateTime(printData.test_drive.start_time) }}</p></div>
                        <div class="col-12 col-md-4 mb-1"><label class="text-bold-500">End Time</label>
                          <p>{{ formatDateTime(printData.test_drive.end_time) }}</p></div>
                        <div class="col-12 col-md-4 mb-1"><label class="text-bold-500">Total Duration</label>
                          <p>{{ printData.duration_minutes || 'N/A' }} minutes</p></div>
                        <!--                            <div class="col-12 col-md-4 mb-1"><label class="text-bold-500">Sales Rep</label><p>{{ printData.sales_person.name || 'N/A' }}</p></div>-->
                        <div class="col-12 col-md-4 mb-1"><label class="text-bold-500">Starting Odometer</label>
                          <p>{{ formatNumber(printData.test_drive.odo_start) }} km</p></div>
                        <div class="col-12 col-md-4 mb-1"><label class="text-bold-500">Ending Odometer</label>
                          <p>{{ formatNumber(printData.test_drive.odo_end) }} km</p></div>
                        <div class="col-12 col-md-4 mb-1"><label class="text-bold-500">Distance Traveled</label>
                          <p>{{ getDistanceTraveled() }} km</p></div>
                      </div>
                    </div>
                  </div>


                  <!-- 2. Customer Information -->
                  <div class="col-12 mb-2" v-if="printData.customer">
                    <div class="card-body pb-1 mx-25">
                      <h4 class="card-title">Customer Information</h4>
                      <hr/>
                      <div class="row">
                        <div class="col-12 col-md-4 mb-1"><label class="text-bold-500">Name</label>
                          <p>{{ getCustomerName(printData.customer) }}</p></div>
                        <div class="col-12 col-md-4 mb-1"><label class="text-bold-500">Phone</label>
                          <p>{{ printData.customer.phone || 'N/A' }}</p></div>
                        <div class="col-12 col-md-4 mb-1"><label class="text-bold-500">Email</label>
                          <p>{{ printData.customer.email || 'N/A' }}</p></div>
                        <div class="col-12 col-md-4 mb-1"><label class="text-bold-500">Driver License</label>
                          <p>{{ printData.customer.driver_license || 'N/A' }}</p></div>
                        <div class="col-12 col-md-4 mb-1"><label class="text-bold-500">Driver License Expiry</label>
                          <p>{{ formatDateTime(printData.customer.license_expiry) || 'N/A' }}</p></div>
                        <div class="col-12 col-md-4 mb-1">
                          <label class="text-bold-500">Address</label>
                          <p>{{ printData.customer.apt }} , {{ printData.customer.street_address || 'N/A' }} </p>
                          <p>{{
                              [printData.customer.city, printData.customer.province, printData.customer.postal_code].filter(Boolean).join(', ') || ''
                            }}</p>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-4 col-md-4 mb-2">
                          <div class="photo-item">
                            <p class="mb-1"><strong>Driver License (Front)</strong></p>
                            <div class="image-box"
                                 style="width: 100%;height: 300px; overflow: hidden; border-radius: 6px; background: #f8f9fa;">
                              <img :src="parseAbsoluteOrRelativeUrl(printData.customer.license_front)" alt="photo"
                                   style="width: 100%;height: 100%; object-fit: contain; display: block;"/>
                            </div>
                            <p class="text-muted small mb-0">Uploaded:
                              {{ formatDateTime(printData.customer.created_at.date) }}</p>
                          </div>
                        </div>

                        <div class="col-4 col-md-4 mb-2">
                          <div class="photo-item">
                            <p class="mb-1"><strong>Driver License (Back)</strong></p>
                            <div class="image-box"
                                 style="width: 100%;height: 300px; overflow: hidden; border-radius: 6px; background: #f8f9fa;">
                              <img :src="parseAbsoluteOrRelativeUrl(printData.customer.license_back)" alt="photo"
                                   style="width: 100%;height: 100%; object-fit: contain; display: block;"/>
                            </div>
                            <p class="text-muted small mb-0">Uploaded:
                              {{ formatDateTime(printData.customer.created_at.date) }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>


                  <!-- 6. Photos -->
                  <div class="col-12 mb-2" v-if="printData.photos && printData.photos.length > 0">
                    <div class="card-body pb-1 mx-25">
                      <h4 class="card-title">Photos</h4>
                      <hr/>
                      <div class="row">
                        <div class="col-2 col-md-2 mb-2" v-for="photo in printData.photos" :key="photo.id">
                          <div class="photo-item">
                            <p class="mb-1"><strong>{{ photo.photo_type === 'pre' ? 'Pre-Drive' : 'Post-Drive' }}
                              Photo</strong></p>
                            <div class="image-box"
                                 style="width: 100%;height: 160px; overflow: hidden; border-radius: 6px; background: #f8f9fa;">
                              <img :src="parseAbsoluteOrRelativeUrl(photo.photo_url)" alt="photo"
                                   style="width: 100%;height: 100%; object-fit: cover; display: block;"/>
                            </div>
                            <p class="text-muted small mb-0">Uploaded: {{ formatDateTime(photo.created_at.date) }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- 7. Documents -->
                  <div class="col-12 mb-2" v-if="printData.documents && printData.documents.length > 0">
                    <div class="card-body pb-1 mx-25">
                      <h4 class="card-title">Documents</h4>
                      <hr/>
                      <div class="row">
                        <div class="col-4 col-md-4 mb-2" v-for="document in printData.documents" :key="document.id">
                          <div class="document-item">
                            <p class="mb-1"><strong>{{ formatDocumentType(document.document_type) }}</strong></p>
                            <div class="image-box"
                                 style="width: 100%;height: 300px; overflow: hidden; border-radius: 6px; background: #f8f9fa;">
                              <img :src="parseAbsoluteOrRelativeUrl(document.document_url)" alt="photo"
                                   style="width: 100%;height: 100%; object-fit: cover; display: block;"/>
                            </div>
                            <p class="text-muted small mb-0">Uploaded: {{
                                formatDateTime(document.created_at.date)
                              }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>


                  <!-- 8. Notes -->
                  <div class="col-12 mb-2" v-if="printData.test_drive.notes">
                    <div class="card-body pb-1 mx-25">
                      <h4 class="card-title">Notes</h4>
                      <hr/>
                      <div class="notes-content">
                        <p>{{ printData.test_drive.notes }}</p>
                      </div>
                    </div>
                  </div>


                  <!-- 5. GPS Tracking Summary -->
                  <div class="col-12 mb-2" v-if="printData.gps_points && printData.gps_points.length > 0">
                    <div class="card-body pb-1 mx-25">
                      <h4 class="card-title">GPS Tracking Summary</h4>
                      <hr/>
                      <div class="row">
                        <div class="col-12 col-md-4 mb-1"><label class="text-bold-500">Total GPS Points</label>
                          <p>{{ printData.gps_points.length }}</p></div>
                        <div class="col-12 col-md-4 mb-1"><label class="text-bold-500">GPS Distance</label>
                          <p>{{ printData.distance_km || 'N/A' }} km</p></div>
                        <div class="col-12 col-md-4 mb-1"><label class="text-bold-500">Route Tracked</label>
                          <p>Yes</p></div>
                      </div>
                    </div>
                  </div>


                  <!-- 9. Audit Trail -->
                  <div class="col-12 mb-2" v-if="printData.events && printData.events.length > 0">
                    <div class="card-body pb-1 mx-25">
                      <h4 class="card-title">Audit Trail</h4>
                      <hr/>
                      <div class="table-responsive">
                        <table class="table table-sm">
                          <thead>
                          <tr>
                            <th>Date/Time</th>
                            <th>Event</th>
                            <th>User</th>
                          </tr>
                          </thead>
                          <tbody>
                          <tr v-for="event in printData.events" :key="event.id">
                            <td>{{ formatDateTime(event.timestamp.date) }}</td>
                            <td>{{ event.event }}</td>
                            <td>{{ printData.sales_person.name || 'System' }}</td>
                          </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </section>

              <!-- Fallback: basic info before printData loads -->
              <section v-else>
                <div class="row match-height">
                  <div class="col-12 col-xs-12 col-md-8">
                    <div class="card-body pb-1 mx-25">
                      <h4 class="card-title col-12">
                        Test Drive Information
                        <span style="float: right; font-size: 0.8em">
                              <span class="badge" :class="statusBadgeClass">{{ form.status }}</span>
                            </span>
                      </h4>
                      <hr/>
                      <div class="row">
                        <div class="col-12 col-md-6 mb-1"><label class="text-bold-500">Customer Name</label>
                          <p>{{ form.first_name }} {{ form.last_name }}</p></div>
                        <div class="col-12 col-md-6 mb-1"><label class="text-bold-500">Phone</label>
                          <p>{{ form.phone }}</p></div>
                        <div class="col-12 col-md-6 mb-1"><label class="text-bold-500">Email</label>
                          <p>{{ form.email }}</p></div>
                        <div class="col-12 col-md-6 mb-1"><label class="text-bold-500">Vehicle (Stock #)</label>
                          <p>{{ form.stock_no }}</p></div>
                        <div class="col-12 col-md-6 mb-1"><label class="text-bold-500">Created At</label>
                          <p>{{ parseDate(form.created_at) }}</p></div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>
    </div>


  </div>
</template>
<script>
const moment = require("moment");
import axios from "axios";

export default {
  props: {
    id: {required: true},
    application: {required: false},
    app_metadata: {required: true},
  },
  name: "TestDriveDetail",
  data() {
    return {
      form: {
        first_name: "",
        last_name: "",
        phone: "",
        email: "",
        stock_no: "",
        status: "",
        created_at: "",
        updated_at: "",
      },
      printData: null,
      isLoadingPrintData: true,
    };
  },
  computed: {
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
    statusBadgeClass() {
      const s = (this.form.status || "").toLowerCase();
      if (s === "completed") return "badge-light-success";
      if (s === "in_progress" || s === "active") return "badge-light-warning";
      return "badge-light-secondary";
    },
    currentDate() {
      return moment().format("MMMM Do YYYY, h:mm a");
    },
  },
  methods: {
    parseDate(date) {
      if (!date || date === "" || date === null || date === undefined) {
        return "";
      }

      const momentDate = moment(date);
      if (!momentDate.isValid()) {
        return "";
      }

      return momentDate.format("MMMM Do YYYY, h:mm a");
    },
    async getTestDrive(id) {
      try {
        const response = await axios({
          method: "get",
          url: `${window.location.origin}/api/business/test-drives/show/${id}`,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
        });
        console.log(response);
        if (response.data.success) {
          this.printData = response.data.data;
        } else {
          throw new Error(response.data.message || 'Failed to load data');
        }
      } catch (error) {
        console.error('Error loading data:', error);
        this.$swal({
          showConfirmButton: false,
          icon: "error",
          toast: true,
          timer: 3000,
          position: "bottom",
          title: "Error loading print data. Please try again.",
        });
      } finally {
        this.isLoadingPrintData = false;
      }

    },
    parseAbsoluteOrRelativeUrl(file_url) {
      return this.$helpers.parseAbsoluteOrRelativeUrl(
          window.global.cdn,
          file_url
      );
    },
    async exportPDF() {
      try {
        const response = await axios({
          method: 'get',
          url: `${window.location.origin}/api/business/test-drives/${this.id}/export/pdf`,
          headers: {Authorization: 'Bearer ' + window.$cookies.get('_token')},
          responseType: 'blob',
        });
        this._triggerDownload(response.data, `test_drive_${this.id}.pdf`, 'application/pdf');
      } catch (error) {
        console.error('Error exporting PDF:', error);
        this.$swal({
          showConfirmButton: false,
          icon: 'error',
          toast: true,
          timer: 3000,
          position: 'bottom',
          title: 'Failed to export PDF. Please try again.'
        });
      }
    },
    async exportCSV() {
      try {
        const response = await axios({
          method: 'get',
          url: `${window.location.origin}/api/business/test-drives/${this.id}/export/csv`,
          headers: {Authorization: 'Bearer ' + window.$cookies.get('_token')},
          responseType: 'blob',
        });
        this._triggerDownload(response.data, `test_drive_${this.id}.csv`, 'text/csv');
      } catch (error) {
        console.error('Error exporting CSV:', error);
        this.$swal({
          showConfirmButton: false,
          icon: 'error',
          toast: true,
          timer: 3000,
          position: 'bottom',
          title: 'Failed to export CSV. Please try again.'
        });
      }
    },
    _triggerDownload(data, filename, mimeType) {
      const url = window.URL.createObjectURL(new Blob([data], {type: mimeType}));
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', filename);
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      window.URL.revokeObjectURL(url);
    },
    formatDateTime(dateTime) {
      if (!dateTime) return 'N/A';
      return moment(dateTime).format('MMMM Do YYYY, h:mm a');
    },
    formatDate(date) {
      if (!date) return 'N/A';
      return moment(date).format('MMMM Do YYYY');
    },
    formatNumber(number) {
      if (!number && number !== 0) return 'N/A';
      return new Intl.NumberFormat().format(number);
    },
    getStatusClass(status) {
      const s = (status || "").toLowerCase();
      if (s === "completed") return "badge-success";
      if (s === "in_progress" || s === "active") return "badge-warning";
      if (s === "pending") return "badge-info";
      return "badge-secondary";
    },
    getCustomerName(customer) {
      if (!customer) return 'N/A';
      const firstName = customer.first_name || '';
      const lastName = customer.last_name || '';
      return `${firstName} ${lastName}`.trim() || 'N/A';
    },
    getVehicleDescription(inventory) {
      if (!inventory) return 'N/A';
      const year = inventory.year || '';
      const make = inventory.make || '';
      const model = inventory.model || '';
      const trim = inventory.trim || '';
      return `${year} ${make} ${model} ${trim}`.trim() || 'N/A';
    },
    getDistanceTraveled() {
      if (!this.printData || !this.printData.test_drive) return 'N/A';
      const start = this.printData.test_drive.odo_start;
      const end = this.printData.test_drive.odo_end;
      if (start && end && end > start) {
        return this.formatNumber(end - start);
      }
      return 'N/A';
    },
    formatDocumentType(type) {
      const types = {
        'driver_license': 'Driver License',
        'signature': 'Customer Signature',
        'insurance': 'Insurance Document',
        'gps': 'GPS Tracking'
      };
      return types[type] || type;
    },
  },
  mounted() {
    this.getTestDrive(this.id);
  },
};
</script>

<style scoped>
.print-content {
  font-family: Arial, sans-serif;
  line-height: 1.6;
  color: #333;
}

.print-header {
  border-bottom: 2px solid #007bff;
  padding-bottom: 20px;
  margin-bottom: 30px;
}

.section-title {
  color: #007bff;
  border-bottom: 1px solid #dee2e6;
  padding-bottom: 8px;
  margin-bottom: 15px;
  font-size: 1.2rem;
  font-weight: 600;
}

.print-section {
  page-break-inside: avoid;
  margin-bottom: 25px;
}

.photo-item, .document-item {
  border: 1px solid #dee2e6;
  padding: 10px;
  border-radius: 4px;
  background-color: #f8f9fa;
}

.notes-content {
  background-color: #f8f9fa;
  padding: 15px;
  border-radius: 4px;
  border-left: 4px solid #007bff;
}

/* Print-specific styles */
@media print {
  .d-print-none {
    display: none !important;
  }

  .modal-dialog {
    max-width: none !important;
    margin: 0 !important;
  }

  .modal-content {
    border: none !important;
    box-shadow: none !important;
  }

  .print-content {
    font-size: 12px;
  }

  .section-title {
    font-size: 14px;
    color: #000 !important;
  }

  .print-header h2 {
    font-size: 18px;
    color: #000 !important;
  }

  .badge {
    border: 1px solid #000 !important;
    color: #000 !important;
    background-color: transparent !important;
  }

  .table {
    font-size: 10px;
  }

  .table th, .table td {
    padding: 4px !important;
    border: 1px solid #000 !important;
  }

  .photo-item, .document-item {
    border: 1px solid #000 !important;
    background-color: transparent !important;
  }

  .notes-content {
    background-color: transparent !important;
    border: 1px solid #000 !important;
  }
}
</style>

