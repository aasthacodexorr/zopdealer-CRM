<template>
  <div
    class="tab-pane"
    id="lead-view-files"
    aria-labelledby="lead-view-notes"
    role="tabpanel"
  >
    <!-- form layout section start -->
    <section id="lead-view-main">
      <div class="row match-height">
        <div class="col-12 col-xs-12 col-md-8">
          <div class="card">
            <div class="card-content">
              <div class="card-body pb-0 mx-25">
                <h4 class="card-title col-12">Files</h4>
                <div class="row m-50" v-for="file in files" :key="file.id">
                    <div class="col-6" style="text-transform:capitalize">{{formatString(file.header)}}</div>
                    <div class="col-6">
                        <a :href="file.file_url" target="_blank" class="btn btn-primary">
                        <i class="bx bx-link-external pr-50"></i>View Attachment</a> </div>
                </div>
              </div>
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
    id: { required: true },
    allUsers: { required: true },
    app_metadata: { required: true },
  },
  name: "LeadFile",
  data(){
      return {
          files: []
      }
  },
  methods: {
      formatString(title){
          return title.replace(/_/g, ' ');
      },
      allFiles() {
      axios({
        method: "get",
        url:
          window.location.origin +
          `/api/business/leads/responses/${this.id}/files`,
        headers: {
          Authorization: "Bearer " + window.$cookies.get("_token"),
          // 'X-Requested-With' : 'XMLHttpRequest',
        },
      })
        .then(({ data }) => {
          this.files = data.files;
          Event.fire("attachment_count", this.files.length);
        })
        .catch((response) => {});
    }
  },
  created(){
      this.allFiles();
  },
  beforeDestroy() {
    Event.vue.$off("attachment_count", null);
  }
};
</script>