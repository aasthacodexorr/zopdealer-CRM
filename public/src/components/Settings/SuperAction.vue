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
                <button class="btn btn-success col-12 m-1" @click="updateCache">
                  Update Cache
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
    name: "SuperAction",
    methods: {
      updateCache() {
        let domain = window.location.origin;
        const vm = this;
        let url = domain;
        url += `/api/clients/cache/update`;
  
        let message = "Update cache...";
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
            let success_message = `Cache has been successfully updated.`;
            toast.success(success_message);
            toast.close();
            //this.pagination(this.page);
          })
          .catch(({ error }) => {
            let error_message = `Couldn't perform update cache.`;
            toast.fail(error_message);
            toast.close();
          });
      },
    },
  };
  </script>
  