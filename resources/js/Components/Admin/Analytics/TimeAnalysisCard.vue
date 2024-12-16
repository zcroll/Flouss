<script setup lang="ts">
import { computed } from 'vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card'
import { Skeleton } from '@/Components/ui/skeleton'

const props = defineProps<{
  data?: {
    hourly: number[]
    peak_hour: number
    quiet_hour: number
    daily_average: number
    weekly_comparison: {
      current_week: number
      previous_week: number
      percentage_change: number
    }
  }
  loading?: boolean
}>()

const formatHour = (hour: number) => {
  return new Date(2000, 0, 1, hour).toLocaleTimeString('en-US', {
    hour: 'numeric',
    hour12: true
  })
}

const weeklyTrend = computed(() => {
  if (!props.data) return { type: 'neutral', text: '0%' }
  
  const change = props.data.weekly_comparison.percentage_change
  return {
    type: change > 0 ? 'positive' : change < 0 ? 'negative' : 'neutral',
    text: `${Math.abs(change)}%`
  }
})
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle>Time Analysis</CardTitle>
    </CardHeader>
    <CardContent>
      <div class="space-y-4">
        <!-- Peak Hours -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <h4 class="text-sm font-medium text-muted-foreground mb-1">Peak Hour</h4>
            <p v-if="!loading && data" class="text-2xl font-bold">
              {{ formatHour(data.peak_hour) }}
            </p>
            <Skeleton v-else class="h-8 w-24" />
          </div>
          <div>
            <h4 class="text-sm font-medium text-muted-foreground mb-1">Quiet Hour</h4>
            <p v-if="!loading && data" class="text-2xl font-bold">
              {{ formatHour(data.quiet_hour) }}
            </p>
            <Skeleton v-else class="h-8 w-24" />
          </div>
        </div>

        <!-- Weekly Comparison -->
        <div>
          <h4 class="text-sm font-medium text-muted-foreground mb-1">Weekly Trend</h4>
          <div class="flex items-center gap-2">
            <span 
              v-if="!loading && data" 
              class="text-2xl font-bold"
              :class="{
                'text-green-500': weeklyTrend.type === 'positive',
                'text-red-500': weeklyTrend.type === 'negative'
              }"
            >
              {{ weeklyTrend.text }}
            </span>
            <Skeleton v-else class="h-8 w-24" />
            <span class="text-sm text-muted-foreground">vs last week</span>
          </div>
        </div>

        <!-- Daily Average -->
        <div>
          <h4 class="text-sm font-medium text-muted-foreground mb-1">Daily Average</h4>
          <p v-if="!loading && data" class="text-2xl font-bold">
            {{ data.daily_average }} visits
          </p>
          <Skeleton v-else class="h-8 w-32" />
        </div>
      </div>
    </CardContent>
  </Card>
</template> 