<template>
  <div
    class="app-content content"
    v-if="app_metadata.organization == 'zopdealer'"
  >
    <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="col-xs-12 col-md-3">
          <div class="card">
            <div
              class="card-header d-flex justify-content-between align-items-center pb-50"
            >
              <h4 class="card-title">Actions</h4>
            </div>
            <div class="card-content">
              <button class="btn btn-success col-12 m-1" @click="migrate">
                Migrate
              </button>
              <button class="btn btn-success col-12 m-1" @click="seed">
                Seed
              </button>
              <button
                class="btn btn-success col-12 m-1"
                @click="reindexInventory"
              >
                Update Inventory Index
              </button>
              <button
                class="btn btn-success col-12 m-1"
                @click="processOverlayImages"
              >
                Process Overlay Images
              </button>
              <button
                class="btn btn-secondary col-12 m-1"
                @click="updateDefaultFBMPCategories"
              >
                Update FBMP Default Categories
              </button>
              <button
                class="btn btn-warning col-12 m-1"
                @click="warningForUpdatingFacebookMarketplaceLogs"
              >
                Change Update Status FBMP Logs
              </button>
              <button
                class="btn btn-danger col-12 m-1"
                @click="warningForPrugeFacebookMarketplaceLogs"
              >
                Purge FBMP Logs
              </button>
              <button
                class="btn btn-danger col-12 m-1"
                @click="warningForPrugeCraigslistLogs"
              >
                Purge Craigslist Logs
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Toast from "../../library/Toast";
export default {
  props: ["application", "app_metadata"],
  name: "AccountAction",
  methods: {
    migrate() {
      let domain = window.location.origin;
      const vm = this;
      let url = domain;
      url += `/api/business/update`;

      let message = "Update client...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `${domain} has been successfully migrated.`;
          toast.success(success_message);
          toast.close();
        })
        .catch(({ error }) => {
          let error_message = `Couldn't perform migration for ${domain}.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    seed() {
      let domain = window.location.origin;
      const vm = this;
      let url = domain;
      url += `/api/business/seeds`;

      let message = "Seeding client...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `${domain} has been successfully seeded.`;
          toast.success(success_message);
          toast.close();
        })
        .catch(({ error }) => {
          let error_message = `Couldn't seed ${domain}.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    reindexInventory() {
      let domain = window.location.origin;
      const vm = this;
      let url = domain;
      url += `/api/business/inventory/search-index/update`;

      let message = "Reindexing inventory for client...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `Inventory has been reindex for ${domain}`;
          toast.success(success_message);
          toast.close();
        })
        .catch(({ error }) => {
          let error_message = `Couldn't reindex ${domain}.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    updateDefaultFBMPCategories() {
      let domain = window.location.origin;
      const vm = this;
      let url = domain;
      url += `/api/business/facebook-marketplace/update-default-categories`;

      let message = "Updating default FBMP Categories...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          let success_message = `Default FBMP categories updated.`;
          toast.success(success_message);
          toast.close();
        })
        .catch(({ error }) => {
          let error_message = `Couldn't update default FBMP categories.`;
          toast.fail(error_message);
          toast.close();
        });
    },
    processOverlayImages() {
      let domain = window.location.origin;
      const vm = this;
      let url = domain;
      url += `/api/business/image/overlay/process`;

      let message = "Processing Overlay Images for client...";
      const toast = new Toast(message);
      toast.fire();

      axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
        })
        .catch((error) => {
          let error_message = error.response.data;
          toast.fail(error_message);
          toast.close();
        });
    },
    async warningForPrugeCraigslistLogs() {
      const vm = this;
      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Back",
        confirmButtonText: "I Understand",
        toast: false,
        position: "center",
        text: "Be careful before proceeding with this action. Recheck client's Craigslist Page for the published inventories. This action will delete all the Craigslist Logs.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.prugeCraigslistLogs();
        }
      });
    },
    async prugeCraigslistLogs() {
      let domain = window.location.origin;
      const vm = this;
      let url = domain;
      url += `/api/business/craigslist/purge`;

      let message = "Craigslist Logs are being deleted ...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "post",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
        })
        .catch((error) => {
          let error_message = error.response.data;
          toast.fail(error_message);
          toast.close();
        });
    },
    async warningForUpdatingFacebookMarketplaceLogs() {
      const vm = this;
      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Back",
        confirmButtonText: "I Understand",
        toast: false,
        position: "center",
        text: "Be careful before proceeding with this action. Recheck client's FBMP Page for the published inventories. This action will change status to UPDATED.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.FacebookMarketplaceChangingStatusToUpdated();
        }
      });
    },
    async FacebookMarketplaceChangingStatusToUpdated() {
      let domain = window.location.origin;
      const vm = this;
      let url = domain;
      url += `/api/business/facebook-marketplace/status/updated`;

      let message =
        "Facebook Marketplace Logs status are being changed to Updated ...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "get",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
        })
        .catch((error) => {
          let error_message = error.response.data;
          toast.fail(error_message);
          toast.close();
        });
    },
    async warningForPrugeFacebookMarketplaceLogs() {
      const vm = this;
      await this.$swal({
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonText: "Back",
        confirmButtonText: "I Understand",
        toast: false,
        position: "center",
        text: "Be careful before proceeding with this action. Recheck client's FBMP Page for the published inventories. This action will delete all the FBMP Logs.",
      }).then((result) => {
        console.log(result);
        if (result.isDismissed) {
        } else if (result.isConfirmed) {
          vm.prugeFacebookMarketplaceLogs();
        }
      });
    },
    async prugeFacebookMarketplaceLogs() {
      let domain = window.location.origin;
      const vm = this;
      let url = domain;
      url += `/api/business/facebook-marketplace/purge`;

      let message = "Facebook Marketplace Logs are being deleted ...";
      const toast = new Toast(message);
      toast.fire();

      await axios({
        method: "post",
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
        },
        url: url,
      })
        .then(({ data }) => {
          toast.success(data.success);
          toast.close();
        })
        .catch((error) => {
          let error_message = error.response.data;
          toast.fail(error_message);
          toast.close();
        });
    },
  },
};
</script>
