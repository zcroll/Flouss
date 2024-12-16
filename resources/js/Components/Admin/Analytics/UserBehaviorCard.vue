<script setup>
import { computed } from 'vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card'
import { ScrollArea } from '@/Components/ui/scroll-area'
import { Progress } from '@/Components/ui/progress'

const props = defineProps({
  data: {
    type: Object,
    required: true,
    default: () => ({
      event_distribution: {},
      page_engagement: [],
      user_flow: {},
      interaction_patterns: {
        hourly_distribution: {},
        most_clicked_elements: []
      }
    })
  }
})

const totalEvents = computed(() => 
  Object.values(props.data.event_distribution).reduce((a, b) => a + b, 0)
)

const eventPercentages = computed(() => 
  Object.entries(props.data.event_distribution).map(([type, count]) => ({
    type,
    count,
    percentage: Math.round((count / totalEvents.value) * 100)
  }))
)

const formatTime = (seconds) => {
  if (seconds < 60) return `${seconds}s`
  const minutes = Math.floor(seconds / 60)
  return `${minutes}m ${seconds % 60}s`
}
</script>

<template>
  <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
    <!-- Event Distribution -->
    <Card class="col-span-1">
      <CardHeader>
        <CardTitle>Event Distribution</CardTitle>
        <CardDescription>User interaction breakdown</CardDescription>
      </CardHeader>
      <CardContent>
        <ScrollArea class="h-[200px] pr-4">
          <div class="space-y-4">
            <div v-for="event in eventPercentages" :key="event.type" class="space-y-2">
              <div class="flex items-center justify-between text-sm">
                <span class="font-medium capitalize">{{ event.type }}</span>
                <span class="text-muted-foreground">{{ event.count }}</span>
              </div>
              <Progress :value="event.percentage" class="h-2" />
            </div>
          </div>
        </ScrollArea>
      </CardContent>
    </Card>

    <!-- Page Engagement -->
    <Card class="col-span-2">
      <CardHeader>
        <CardTitle>Page Engagement</CardTitle>
        <CardDescription>Average time spent on pages</CardDescription>
      </CardHeader>
      <CardContent>
        <ScrollArea class="h-[200px]">
          <div class="space-y-4">
            <div 
              v-for="page in data.page_engagement" 
              :key="page.path"
              class="flex items-center justify-between border-b pb-2 last:border-0"
            >
              <div class="space-y-1">
                <p class="text-sm font-medium">{{ page.path }}</p>
                <div class="flex gap-2 text-xs text-muted-foreground">
                  <span>{{ page.total_events }} events</span>
                  <span>•</span>
                  <span>{{ formatTime(page.average_time) }}</span>
                </div>
              </div>
              <div class="flex items-center gap-2">
                <div 
                  v-for="(count, type) in page.event_breakdown" 
                  :key="type"
                  class="flex items-center gap-1 text-xs"
                >
                  <i :class="`fa-solid fa-${type === 'click' ? 'mouse' : 'eye'} text-muted-foreground`"></i>
                  <span>{{ count }}</span>
                </div>
              </div>
            </div>
          </div>
        </ScrollArea>
      </CardContent>
    </Card>

    <!-- User Flow -->
    <Card class="col-span-3">
      <CardHeader>
        <CardTitle>User Flow</CardTitle>
        <CardDescription>Most common user paths</CardDescription>
      </CardHeader>
      <CardContent>
        <ScrollArea class="h-[200px]">
          <div class="space-y-4">
            <div 
              v-for="(count, path) in data.user_flow" 
              :key="path"
              class="flex items-center justify-between"
            >
              <div class="flex-1 space-y-1">
                <div class="flex items-center gap-2">
                  <div 
                    v-for="(step, index) in path.split(' → ')" 
                    :key="index"
                    class="flex items-center"
                  >
                    <span class="text-sm">{{ step }}</span>
                    <i 
                      v-if="index < path.split(' → ').length - 1"
                      class="fa-solid fa-chevron-right mx-2 text-muted-foreground"
                    ></i>
                  </div>
                </div>
              </div>
              <div class="text-sm text-muted-foreground">
                {{ count }} users
              </div>
            </div>
          </div>
        </ScrollArea>
      </CardContent>
    </Card>
  </div>
</template> 