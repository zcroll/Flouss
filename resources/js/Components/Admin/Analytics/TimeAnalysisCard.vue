<script setup>
import { computed } from 'vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card'
import { useAnalytics } from '@/Composables/useAnalytics'

const { timeAnalysis, formatHour } = useAnalytics()

const peakHourFormatted = computed(() => {
    const hour = timeAnalysis.value.peak_hour
    if (hour === null || hour === undefined) return 'No data'
    return formatHour(hour)
})

const quietHourFormatted = computed(() => {
    const hour = timeAnalysis.value.quiet_hour
    if (hour === null || hour === undefined) return 'No data'
    return formatHour(hour)
})

const dailyAverage = computed(() => {
    const avg = timeAnalysis.value.daily_average
    if (!avg && avg !== 0) return 'No data'
    return Math.round(avg)
})

const weeklyChange = computed(() => {
    const comparison = timeAnalysis.value.weekly_comparison
    if (!comparison) {
        return {
            value: 0,
            direction: 'right',
            class: 'text-muted-foreground',
            text: 'No change'
        }
    }

    const change = comparison.percentage_change
    return {
        value: Math.abs(change),
        direction: change >= 0 ? 'up' : 'down',
        class: change === 0 
            ? 'text-muted-foreground' 
            : change > 0 
                ? 'text-green-600 dark:text-green-500'
                : 'text-red-600 dark:text-red-500',
        text: `${Math.abs(change)}% ${change >= 0 ? 'increase' : 'decrease'}`
    }
})
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Time Analysis</CardTitle>
            <CardDescription>
                Traffic patterns and peak hours
            </CardDescription>
        </CardHeader>
        <CardContent>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <!-- Peak Hour -->
                <div class="space-y-2">
                    <p class="text-sm font-medium text-muted-foreground">Peak Hour</p>
                    <p class="text-2xl font-bold">{{ peakHourFormatted }}</p>
                </div>

                <!-- Quiet Hour -->
                <div class="space-y-2">
                    <p class="text-sm font-medium text-muted-foreground">Quiet Hour</p>
                    <p class="text-2xl font-bold">{{ quietHourFormatted }}</p>
                </div>

                <!-- Daily Average -->
                <div class="space-y-2">
                    <p class="text-sm font-medium text-muted-foreground">Daily Average</p>
                    <p class="text-2xl font-bold">
                        {{ typeof dailyAverage === 'number' ? dailyAverage : 'No data' }}
                        <span v-if="typeof dailyAverage === 'number'" class="text-sm text-muted-foreground">visits</span>
                    </p>
                </div>

                <!-- Weekly Change -->
                <div class="space-y-2">
                    <p class="text-sm font-medium text-muted-foreground">Weekly Change</p>
                    <p class="text-2xl font-bold" :class="weeklyChange.class">
                        <i :class="`fa-solid fa-arrow-${weeklyChange.direction}`" class="mr-1"></i>
                        {{ weeklyChange.text }}
                    </p>
                </div>
            </div>
        </CardContent>
    </Card>
</template> 