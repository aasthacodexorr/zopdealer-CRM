<template>
  <button
    class="btn btn-primary btn-primary-glowing-button"
    type="button"
    data-toggle="modal"
    data-target="#vehicleDescriptionReWriterModal"
  >
    <i class="bx bxs-pencil bx-burst-hover pr-50"></i> AI Rewriter
  </button>
</template>
<script>
export default {
  name: "InventoryVehicleDescriptionReWriterCTA",
};
</script>
<style lang="scss">
.btn-primary-glowing-button {
  /* Basic button styling (can be inherited from Bootstrap) */
  position: relative;
  overflow: hidden; /* Ensures glow stays within button boundaries */
}

@keyframes glowing {
  0% {
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.7); /* Initial glow color and intensity */
  }
  50% {
    box-shadow: 0 0 20px rgba(0, 123, 255, 1); /* Brighter glow at midpoint */
  }
  100% {
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.7); /* Returns to initial glow */
  }
}

.btn-primary-glowing-button {
  animation: glowing 1.5s infinite alternate; /* Apply the animation */
}
</style>