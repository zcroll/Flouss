<template>
    <Radar :data="data" :options="options" />
</template>

<script lang="ts">
import {
    Chart as ChartJS,
    RadialLinearScale,
    PointElement,
    LineElement,
    Tooltip,
    Legend
} from 'chart.js'
import { Radar } from 'vue-chartjs'
import * as chartConfig from './chartConfig'

ChartJS.register(
    RadialLinearScale,
    PointElement,
    LineElement,
    Tooltip,
    Legend
)

export default {
    name: 'App',
    components: {
        Radar
    },
    data() {
        return chartConfig
    }
}
</script>
