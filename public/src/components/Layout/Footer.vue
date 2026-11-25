<template>
  <!-- Version moved to left sidebar footer -->
  <footer class="footer footer-static footer-light d-none"></footer>
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
