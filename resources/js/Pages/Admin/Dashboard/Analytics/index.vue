<script setup>
import { ref, onMounted, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import Overview from '@/Components/ADMIN/Charts/Overview.vue'
import TopPagesCard from '@/Components/ADMIN/List/TopPagesCard.vue'
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from '@/Components/ui/card'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
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
    icon: 'eye',
    change: '+12%',
    changeText: 'from previous period'
  },
  {
    title: 'Unique Visitors',
    value: props.analytics.overview.unique_visitors,
    icon: 'users',
    change: '+5.2%',
    changeText: 'from previous period'
  },
  {
    title: 'Total Actions',
    value: props.analytics.overview.total_actions,
    icon: 'activity',
    change: '+8.1%',
    changeText: 'from previous period'
  },
  {
    title: 'Total Logins',
    value: props.analytics.overview.total_logins,
    icon: 'log-in',
    change: '+3.2%',
    changeText: 'from previous period'
  }
])

const updateTimeRange = async (value) => {
  try {
    const response = await axios.post(route('admin.analytics.update-time'), {
      timeRange: value
    })
    
    // Update the analytics data
    props.analytics = response.data.analytics
    props.dateRange = response.data.dateRange
    
    toast({
      title: 'Time range updated',
      description: 'The analytics data has been updated successfully.',
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
          <header class="bg-card border-b border-border">
            <div class="px-4 py-3 flex justify-between items-center">
              <div class="flex items-center space-x-4">
                <h1 class="text-xl font-semibold">User Monitoring Analytics</h1>
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
            <div class="container mx-auto p-6">
              <!-- Header with Actions -->
              <div class="mb-8 space-y-4">
                <div class="flex items-center justify-between">
                  <div>
                    <h1 class="text-3xl font-bold tracking-tight">Analytics Overview</h1>
                    <p class="text-muted-foreground">
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
                      Export Data
                    </Button>
                  </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                  <Card v-for="stat in statsCards" :key="stat.title">
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                      <CardTitle class="text-sm font-medium">{{ stat.title }}</CardTitle>
                      <i :class="`fa-solid fa-${stat.icon} text-muted-foreground`"></i>
                    </CardHeader>
                    <CardContent>
                      <div class="text-2xl font-bold">{{ stat.value }}</div>
                      <p class="text-xs text-muted-foreground">
                        <span :class="stat.change.startsWith('+') ? 'text-green-500' : 'text-red-500'">
                          {{ stat.change }}
                        </span>
                        {{ stat.changeText }}
                      </p>
                    </CardContent>
                  </Card>
                </div>
              </div>

              <!-- Analytics Content -->
              <div class="grid gap-6 grid-cols-1 lg:grid-cols-7">
                <!-- Visit Trends Chart -->
                <div class="lg:col-span-4">
                  <Overview 
                    :data="analytics.visits"
                    title="Visit Trends"
                    description="User visits over time"
                  />
                </div>

                <!-- Top Pages -->
                <div class="lg:col-span-3">
                  <TopPagesCard 
                    :data="analytics.topPages"
                    title="Most Visited Pages"
                    description="Top pages by number of visits"
                  />
                </div>
              </div>

              <!-- User Activity Table -->
              <div class="mt-6">
                <Card>
                  <CardHeader>
                    <CardTitle>Recent User Activity</CardTitle>
                  </CardHeader>
                  <CardContent>
                    <div class="relative overflow-x-auto">
                      <table class="w-full text-sm text-left">
                        <thead class="text-xs uppercase bg-muted">
                          <tr>
                            <th scope="col" class="px-6 py-3">User</th>
                            <th scope="col" class="px-6 py-3">Page</th>
                            <th scope="col" class="px-6 py-3">Method</th>
                            <th scope="col" class="px-6 py-3">IP</th>
                            <th scope="col" class="px-6 py-3">Time</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="activity in analytics.userActivity" 
                              :key="activity.id"
                              class="border-b">
                            <td class="px-6 py-4">
                              {{ activity.user?.name || 'Guest' }}
                            </td>
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
