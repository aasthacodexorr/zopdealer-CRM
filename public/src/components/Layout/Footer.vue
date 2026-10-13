<template>
  <!-- BEGIN: Footer-->
  <footer
    class="footer footer-static footer-light p-0 mt-1 mb-1"
    style="position: fixed; bottom: 0px; right: 1em"
  >
    <p class="clearfix mb-0">
      <span
        class="float-right d-inline-block"
        style="font-size: 0.8em; font-weight: 600"
      >{{ getCurrentYear }} &copy; ZOPDEALER Ver. 31.14.2</span
      >
    </p>
  </footer>
  <!-- END: Footer-->
</template>

<script>
export default {
  name: "Footer",
  computed: {
    getCurrentYear() {
      return new Date().getFullYear();
    },
  },
};
</script>

<style scoped></style>
