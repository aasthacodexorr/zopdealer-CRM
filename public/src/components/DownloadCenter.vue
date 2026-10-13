<template>
  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-header row download-center">
        <div class="col-12">
          <!-- Download : START -->
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Downloads</h5>
            </div>

            <div class="list-group list-group-flush">

              <!-- Each Download : START -->
              <a
                  v-for="download in downloads"
                  :key="download.key"
                  :href="download.url"
                  class="list-group-item list-group-item-action d-flex align-items-center justify-content-between"
                  target="_blank"
              >
                <div class="d-flex align-items-center">
                  <div class="download-icon mr-3">
                    <i :class="download.icon"></i>
                  </div>

                  <div>
                    <div class="font-weight-bold">
                      {{ download.label }}
                    </div>

                    <small class="text-muted">
                      {{ download.description }}
                    </small>
                  </div>
                </div>

                <i class="bx bx-link-external font-medium-4"></i>
              </a>
              <!-- Each Download : END -->

            </div>
          </div>
          <!-- Download : END -->
        </div>

      </div>
    </div>
  </div>
  <!-- END: Content-->
</template>
<script>
export default {
  props: ["application", "app_metadata"],
  name: "DownloadCenter",
  data() {
    return {
      downloads: [
        {
          key: "android",
          label: "Android Application",
          description: "Download the latest Android application",
          icon: "bx bxl-android",
          url: "https://play.google.com/store/apps/details?id=com.zopdealer.android&pcampaignid=web_share",
        },
        {
          key: "ios",
          label: "iPhone Application",
          description: "Download the latest iPhone application",
          icon: "bx bxl-apple",
          url: "https://apps.apple.com/ca/app/zop-dealer/id1533132018",
        },
        {
          key: "marketplace-assistant",
          label: "Marketplace Assistant",
          description: "Download the Chrome extension for Facebook Marketplace",
          icon: "bx bxl-chrome",
          url: "https://chromewebstore.google.com/detail/labihpaoiepipcpcgbepcakbiifebhbb?utm_source=item-share-cb",
        },
        {
          key: "zop-flow",
          label: "Zop Flow",
          description: "Download the Chrome extension to transfer deals from DT and other supported platforms.",
          icon: "bx bxl-chrome",
          url: "https://chromewebstore.google.com/detail/hhfcfjldiljljmpgmmbnjmdbgfflgdlh?utm_source=item-share-cb",
        }
      ]
    }
  }
};
</script>
<style lang="scss">
.download-center {
  .download-item {
    border-bottom: 1px solid #ebe9f1;
  }

  .download-item:last-child {
    border-bottom: 0;
  }

  .download-icon {
    width: 42px;
    height: 42px;
    border-radius: 6px;
    background: #f3f2f7;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 24px;
  }
}
</style>
