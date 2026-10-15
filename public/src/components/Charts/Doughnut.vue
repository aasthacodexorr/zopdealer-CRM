<template>
  <Doughnut
    :data="normalizedData"
    :options="normalizedOptions"
    v-bind="$attrs"
  />
</template>

<script>
import { Doughnut } from "vue-chartjs";
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  ArcElement,
} from "chart.js";

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  ArcElement
);

export default {
  name: "DoughnutChart",
  components: {
    Doughnut,
  },
  props: {
    chartData: {
      type: Object,
      default: undefined,
    },
    data: {
      type: Object,
      default: undefined,
    },
    options: {
      type: Object,
      default: () => ({}),
    },
  },
  computed: {
    normalizedData() {
      const d = this.chartData || this.data;
      if (d && Array.isArray(d.datasets)) {
        return d;
      }
      return { labels: [], datasets: [] };
    },
    normalizedOptions() {
      const opts = Object.assign(
        { responsive: true },
        this.options || {}
      );
      const plugins = Object.assign({}, opts.plugins || {});
      if (opts.legend && !plugins.legend) {
        plugins.legend = opts.legend;
      }
      opts.plugins = plugins;
      return opts;
    },
  },
};
</script>

<style lang="scss" scoped>
</style>