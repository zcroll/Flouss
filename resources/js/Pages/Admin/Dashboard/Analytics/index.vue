<script setup>
import { ref, onMounted, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
  CardDescription,
} from '@/Components/ui/card'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import { 
  BarChart,
  LineChart,
  PieChart,
  Activity,
  Users,
  Globe,
  Eye,
  Clock,
  ArrowUp,
  ArrowDown,
} from 'lucide-vue-next'
import ProfileDropdown from '@/Components/ADMIN/profileDropdown.vue'
import Search from '@/Components/ADMIN/search.vue'
import ThemeSwitch from '@/Components/ADMIN/themeSwitch.vue'
import SideBar from '@/Pages/Admin/SideBar.vue'
import ThemeProvider from '@/Components/ADMIN/ThemeProvider.vue'
import { useToast } from '@/Components/ui/toast/use-toast'

const props = defineProps({
  analytics: {
    type: Object,
    required: true
  },
  dateRange: {
    type: Object,
    required: true
  }
})

const { toast } = useToast()
const timeRangeOptions = [
  { value: 'today', label: 'Today' },
  { value: 'yesterday', label: 'Yesterday' },
  { value: 'last7days', label: 'Last 7 Days' },
  { value: 'last30days', label: 'Last 30 Days' },
  { value: 'thisMonth', label: 'This Month' },
  { value: 'lastMonth', label: 'Last Month' },
]

const selectedTimeRange = ref('last7days')

const statsCards = computed(() => [
  {
    title: 'Total Visits',
    value: props.analytics.overview.total_visits,
    change: props.analytics.overview.percent_change,
    icon: Eye,
    trend: props.analytics.overview.percent_change >= 0 ? 'up' : 'down'
  },
  {
    title: 'Unique Pages',
    value: props.analytics.overview.unique_pages,
    icon: Globe,
    change: null
  },
  {
    title: 'Average Daily Visits',
    value: props.analytics.overview.average_daily_visits,
    icon: Activity,
    change: null
  },
  {
    title: 'Top Country',
    value: `${props.analytics.overview.top_country} (${props.analytics.overview.top_country_visits} visits)`,
    icon: Globe,
    change: null
  }
])

const updateTimeRange = async (value) => {
  try {
    const response = await axios.post(route('admin.analytics.update-time'), {
      timeRange: value
    })
    
    props.analytics = response.data.analytics
    props.dateRange = response.data.dateRange
    
    toast({
      title: 'Time range updated',
      description: 'Analytics data has been refreshed successfully.',
    })
  } catch (error) {
    toast({
      title: 'Error',
      description: 'Failed to update time range. Please try again.',
      variant: 'destructive'
    })
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: '2-digit'
  })
}

const getTrendColor = (trend) => {
  return trend === 'up' ? 'text-green-500' : 'text-red-500'
}

const getTrendIcon = (trend) => {
  return trend === 'up' ? ArrowUp : ArrowDown
}
</script>

<template>
  <Head title="Analytics Dashboard" />
  
  <ThemeProvider>
    <div class="min-h-screen bg-background">
      <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex">
          <SideBar />
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
          <!-- Top Header -->
          <header class="bg-card border-b border-border sticky top-0 z-10">
            <div class="px-6 py-4 flex justify-between items-center">
              <div class="flex items-center space-x-4">
                <h1 class="text-2xl font-bold tracking-tight">Analytics Dashboard</h1>
              </div>
              <div class="flex items-center space-x-4">
                <Search />
                <ThemeSwitch />
                <ProfileDropdown />
              </div>
            </div>
          </header>

          <!-- Main Content Area -->
          <main class="flex-1 overflow-x-hidden overflow-y-auto bg-background">
            <div class="container mx-auto p-6 space-y-8">
              <!-- Header with Actions -->
              <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                  <h2 class="text-3xl font-bold tracking-tight">Overview</h2>
                  <p class="text-muted-foreground mt-1">
                    {{ formatDate(dateRange.start) }} - {{ formatDate(dateRange.end) }}
                  </p>
                </div>
                <div class="flex items-center gap-4">
                  <Select v-model="selectedTimeRange" @update:modelValue="updateTimeRange">
                    <SelectTrigger class="w-[180px]">
                      <SelectValue :placeholder="timeRangeOptions.find(o => o.value === selectedTimeRange)?.label" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem v-for="option in timeRangeOptions" 
                                :key="option.value" 
                                :value="option.value">
                        {{ option.label }}
                      </SelectItem>
                    </SelectContent>
                  </Select>
                  <Button variant="outline">
                    <i class="fa-regular fa-download mr-2"></i>
                    Export
                  </Button>
                </div>
              </div>

              <!-- Stats Cards Grid -->
              <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card v-for="stat in statsCards" :key="stat.title">
                  <CardHeader class="flex flex-row items-center justify-between pb-2 space-y-0">
                    <CardTitle class="text-sm font-medium">{{ stat.title }}</CardTitle>
                    <component :is="stat.icon" 
                             class="h-4 w-4 text-muted-foreground" />
                  </CardHeader>
                  <CardContent>
                    <div class="text-2xl font-bold">{{ stat.value }}</div>
                    <div v-if="stat.change !== null" 
                         class="flex items-center text-xs mt-1">
                      <component :is="getTrendIcon(stat.trend)"
                               :class="[getTrendColor(stat.trend), 'mr-1 h-3 w-3']" />
                      <span :class="getTrendColor(stat.trend)">
                        {{ Math.abs(stat.change) }}% from previous period
                      </span>
                    </div>
                  </CardContent>
                </Card>
              </div>

              <!-- Analytics Content -->
              <div class="grid gap-6">
                <!-- Charts Row -->
                <div class="grid gap-6 lg:grid-cols-7">
                  <!-- Visit Trends Chart -->
                  <Card class="lg:col-span-4">
                    <CardHeader>
                      <CardTitle>Visit Trends</CardTitle>
                      <CardDescription>User visits over time</CardDescription>
                    </CardHeader>
                    <CardContent>
                      <Overview 
                        :data="analytics.visits"
                        class="h-[350px]"
                      />
                    </CardContent>
                  </Card>

                  <!-- Top Pages -->
                  <Card class="lg:col-span-3">
                    <CardHeader>
                      <CardTitle>Most Visited Pages</CardTitle>
                      <CardDescription>Top pages by number of visits</CardDescription>
                    </CardHeader>
                    <CardContent>
                      <div class="space-y-4">
                        <div v-for="page in analytics.topPages" 
                             :key="page.url"
                             class="flex items-center justify-between py-2">
                          <div class="space-y-1">
                            <p class="text-sm font-medium leading-none">
                              {{ page.name || page.url }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                              {{ page.last_visit }}
                            </p>
                          </div>
                          <div class="text-sm font-medium">{{ page.visits }} visits</div>
                        </div>
                      </div>
                    </CardContent>
                  </Card>
                </div>

                <!-- Location Stats -->
                <Card v-if="analytics.geoip_status.working">
                  <CardHeader>
                    <CardTitle>Morocco Cities Distribution</CardTitle>
                    <CardDescription>Visitor locations across Moroccan cities</CardDescription>
                  </CardHeader>
                  <CardContent>
                    <div class="grid gap-6 lg:grid-cols-2">
                      <!-- Map Visualization would go here -->
                      <div class="space-y-4">
                        <div v-for="(stats, city) in analytics.location_analysis.visit_locations.filter(loc => loc.location.includes('Morocco'))" 
                             :key="city"
                             class="flex items-center justify-between">
                          <div>
                            <p class="font-medium">{{ stats.location.split(',')[0] }}</p>
                            <p class="text-sm text-muted-foreground">
                              {{ stats.coordinates.lat.toFixed(2) }}, {{ stats.coordinates.lng.toFixed(2) }}
                            </p>
                          </div>
                          <div class="text-sm">
                            {{ stats.count }} visits
                          </div>
                        </div>
                      </div>
                    </div>
                  </CardContent>
                </Card>

                <!-- Recent Activity -->
                <Card>
                  <CardHeader>
                    <CardTitle>Recent Activity</CardTitle>
                    <CardDescription>Latest user interactions</CardDescription>
                  </CardHeader>
                  <CardContent>
                    <div class="relative overflow-x-auto">
                      <table class="w-full text-sm">
                        <thead class="text-xs uppercase bg-muted">
                          <tr>
                            <th class="px-6 py-3 text-left">Page</th>
                            <th class="px-6 py-3 text-left">Method</th>
                            <th class="px-6 py-3 text-left">IP</th>
                            <th class="px-6 py-3 text-left">Time</th>
                          </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                          <tr v-for="activity in analytics.userActivity" 
                              :key="activity.url + activity.created_at"
                              class="bg-card">
                            <td class="px-6 py-4">{{ activity.url }}</td>
                            <td class="px-6 py-4">{{ activity.method }}</td>
                            <td class="px-6 py-4">{{ activity.ip }}</td>
                            <td class="px-6 py-4">{{ activity.created_at }}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </CardContent>
                </Card>
              </div>
            </div>
          </main>
        </div>
      </div>
    </div>
  </ThemeProvider>
</template>

<style scoped>
.container {
  max-width: 1400px;
}
</style>
