<template>
  <button
    v-show="is_active && isAuthorized"
    class="btn btn-link"
    type="button"
    data-toggle="modal"
    data-target="#carfaxValuationModal"
  >
    <img
      src="https://zopsoftware-asset.b-cdn.net/media/carfax-marketvaluation.png"
      alt="Carfax Valuation"
      style="width: 150px;"
    />
  </button>
</template>
<script>
export default {
  props: ["app_metadata"],
  name: "CarfaxValuationCTA",
  data() {
    return {
      is_active: false,
    };
  },
  computed: {
    isAuthorized() {
      return this.$helpers.isAuthorized(this.app_metadata.role);
    },
  },
  methods: {
    async getConfigurationKey(key) {
      try {
        let result = await axios({
          method: "get",
          url: `${window.location.origin}/api/business/configurations/key/${key}`,
          headers: {
            Authorization: "Bearer " + window.$cookies.get("_token"),
          },
        });

        console.log("get configuration key");
        let value = result?.data?.value || "0";

        if (parseInt(value) === 1) {
          this.is_active = true;
        }
      } catch (error) {
        console.log(error);
      }
    },
  },
  created() {
    this.getConfigurationKey("carfax_market_valuation_api");
  },
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
