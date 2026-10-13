<template>
  <div
    class="tab-pane"
    id="deal-dealertrack-panel"
    aria-labelledby="DealerTrack Logs"
    role="tabpanel"
  >
    <section id="deal-dealertrack-list">
      <div class="row match-height m-1">
        <div class="col-12 col-xs-12">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col" class="text-center">Date</th>
                  <th scope="col" class="text-center">DT #</th>
                  <th scope="col" class="text-center">Status</th>
                  <th scope="col" class="text-center">Direction</th>
                  <th scope="col" class="text-center">Complete Log</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="dealertrack_log in dealertrack_logs"
                  :key="`dealertrack_log_${dealertrack_log.id}`"
                >
                  <td class="text-center">
                    {{ convertUnixToDate(dealertrack_log.created_at.date) }}
                  </td>
                  <td class="text-center">
                    <b>{{ dealertrack_log.application_profile_id }}</b>
                  </td>
                  <td
                    class="text-center"
                    v-html="badgeDOM(dealertrack_log.status)"
                  ></td>
                  <td class="text-center">{{ dealertrack_log.direction }}</td>
                  <td class="text-center">
                    <i
                      class="bx bx-md bx-file"
                      type="button"
                      @click.prevent="
                        openLogModal(dealertrack_log.complete_response)
                      "
                      style="cursor: pointer"
                    ></i>
                  </td>
                </tr>
              </tbody>
            </table>
            <!-- Log Modal : START -->
            <div
              class="modal fade"
              id="dealerTrackLogModal"
              tabindex="-1"
              role="dialog"
              aria-labelledby="Dealertrack Log Modal"
              aria-hidden="true"
            >
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <!-- Modal Header : START -->
                  <div class="modal-header">
                    <h5 class="modal-title">Log File</h5>
                    <button
                      type="button"
                      id="dealerTrackLogModalClose"
                      class="close"
                      data-dismiss="modal"
                      aria-label="Close"
                    >
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <!-- Modal Header : END -->

                  <!-- Modal Body : START -->
                  <div
                    class="modal-body"
                    style="
                      word-wrap: anywhere;
                      max-height: 70vh;
                      overflow-y: auto;
                    "
                  >
                    <span>
                      {{ complete_response }}
                    </span>
                  </div>
                  <!-- Modal Body : END -->
                </div>
              </div>
            </div>
            <!-- Log Modal : END -->
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
const moment = require("moment");
export default {
  props: {
    dealertrack_logs: {
      required: true,
    },
  },
  name: "DealDealertrackLog",
  data() {
    return {
      complete_response: "",
    };
  },
  methods: {
    badgeDOM(text) {
      switch (text) {
        case "SUCCESS":
          return `<span class='badge badge-light-success'>${text}</span>`;
          break;

        case "FAILED":
          return `<span class='badge badge-light-danger'>${text}</span>`;
          break;

        default:
          return `<span class='badge badge-light-secondary'>${text}</span>`;
          break;
      }
    },
    convertUnixToDate(unix_date) {
      return moment(unix_date).format("MM/DD/YYYY hh:mm A");
    },
    openLogModal(response) {
      this.complete_response = response;
      $("#dealerTrackLogModal").modal("show");
    },
  },
  mounted() {
    const vm = this;
    jQuery("#dealerTrackLogModal").on("hidden.bs.modal", function () {
      vm.$data.complete_response = "";
    });
  },
};
</script>
  