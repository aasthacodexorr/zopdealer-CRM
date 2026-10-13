<template>
  <!-- BEGIN: Content-->
  <div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="alert alert-light col-12 mb-50" role="alert">
          Version 20.3.1
        </div>
        <div class="col-12 m-50">
          Ability to add or remove the document for the expense has been added.
          <div class="w-100"></div>
          <iframe
            src="https://iframe.mediadelivery.net/embed/140457/193cf448-7ec4-45b0-9419-fc50b1709e7b?autoplay=true&loop=false&muted=false&preload=true"
            loading="lazy"
            width="560"
            height="315"
            allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;"
            allowfullscreen="true"
          ></iframe>
        </div>
      </div>
    </div>
  </div>
  <!-- END: Content-->
</template>
<script>
export default {
  props: ["application", "app_metadata"],
  name: "UpdateCenter",
  computed: {
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
  },
};
</script>
