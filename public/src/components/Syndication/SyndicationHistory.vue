<template>
  <!-- BEGIN: Content-->
  <div class="app-content content" v-if="tenantApp">
    <div class="content-overlay"></div>
    <div class="content-body">
      <div class="webapp-form">
        <section id="syndication-table-transactions">
          <div
            class="border-bottom mt-3 bg-white header-navbar set-dable-layer"
            style="background-color: transparent; z-index: 2"
          >
            <div class="content-wrapper pt-0 pb-1 mt-0">
              <div class="card shadow-none mb-0">
                <div class="row">
                  <h2 class="h1 mt-1 mb-1 col-12">Syndication History</h2>
                  <div class="col-12 p-1">
                    <button
                      class="btn btn-light-primary"
                      @click="openManageSyndication"
                    >
                      Manage Inventory Syndication
                    </button>
                  </div>
                  <div class="col-6 table-responsive">
                    <table class="table">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">Syndication Feed</th>
                          <th scope="col">Count</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          scope="row"
                          v-for="(item, key) in syndication.count"
                          :key="`syndication_${key}`"
                        >
                          <td>{{ syndication.syndication[key] }}</td>
                          <td>{{ item }}</td>
                          <td>
                            <button
                              class="btn btn-secondary"
                              @click="showSyndication(key)"
                            >
                              Show Details
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="col-6 table-responsive">
                    <table class="table table-striped">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col text-center">History</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          scope="row"
                          v-for="each_history in syndication.logs"
                          :key="`each_syndication_history_${each_history.feed_id}`"
                          v-show="each_history.source == source"
                        >
                          <td>
                            {{ each_history.invcount }}
                            {{ each_history.message }}
                            {{ each_history.source }} on
                            {{ formatDate(each_history.updated_at) }} UTC
                            <span
                              v-if="app_metadata.organization == 'zopdealer'"
                            >
                              <a
                                :href="each_history.file_name"
                                target="_blank"
                                rel="noopener noreferrer"
                                >DOWNLOAD</a
                              >
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
  props: ["application", "app_metadata"],
  name: "SyndicationHistory",
  data() {
    return {
      syndication: {},
      source: null,
    };
  },
  computed: {
    tenantApp() {
      return this.application == "tenant";
    },
  },
  methods: {
    ...mapActions("inventoryView", ["commitCurrentSyndicationViewStatus"]),
    openManageSyndication() {
      this.commitCurrentSyndicationViewStatus(true);
      this.$router.push({
        name: "inventory-list",
      });
    },
    showSyndication(source) {
      this.source = source;
    },
    formatDate(date) {
      return moment(date).format("Do MMMM YYYY h:mm:ss A");
    },
    getSyndicationHistory() {
      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: window.location.origin + `/api/business/syndication`,
      })
        .then(({ data }) => {
          this.syndication = data.data;
        })
        .catch(() => {});
    },
  },
  mounted() {
    this.getSyndicationHistory();
  },
};
</script>

<style lang="scss" scoped></style>
