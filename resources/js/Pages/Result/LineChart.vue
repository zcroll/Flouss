<template>
  <div>
    <Radar :data="chartData" :options="chartOptions" />
  </div>
</template>

<script lang="ts">
import { Radar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  RadialLinearScale,
  PointElement,
  LineElement,
  Tooltip,
  Legend
} from 'chart.js'

ChartJS.register(
  RadialLinearScale,
  PointElement,
  LineElement,
  Tooltip,
  Legend
)

export default {
  name: 'RadarChart',
  components: {
    Radar
  },
  props: {
    data: {
      type: Object,
      required: true
    },
    options: {
      type: Object,
      required: true
    }
  },
  computed: {
    chartData() {
      return this.data
    },
    chartOptions() {
      return this.options
    }
  }
}
</script>
