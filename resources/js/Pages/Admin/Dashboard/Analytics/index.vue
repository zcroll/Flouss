<script setup>
import { ref, onMounted, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import Overview from '@/Components/ADMIN/Charts/Overview.vue'
import TopPagesCard from '@/Components/ADMIN/List/TopPagesCard.vue'
import { useAdminTabs } from '@/Composables/useAdminTabs'
import {
  Card,
  CardContent, 
  CardDescription,
  CardHeader,
  CardTitle
} from '@/Components/ui/card'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/Components/ui/tabs'
import ProfileDropdown from '@/Components/ADMIN/profileDropdown.vue'
import Search from '@/Components/ADMIN/search.vue'
import ThemeSwitch from '@/Components/ADMIN/themeSwitch.vue'
import SideBar from '@/Pages/Admin/SideBar.vue'
import ThemeProvider from '@/Components/ADMIN/ThemeProvider.vue'
import AdminTabs from '@/Components/ADMIN/AdminTabs.vue'
import TimeAnalysisCard from '@/Components/Admin/Analytics/TimeAnalysisCard.vue'
import { useAnalytics } from '@/Composables/useAnalytics'

defineOptions({
  layout: null
})

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: '2-digit'
  })
}

const props = defineProps({
  analytics: {
    type: Object,
    required: true,
    default: () => ({
      total_visits: 0,
      most_visited_pages: [],
      visit_trends: []
    })
  },
  dateRange: {
    type: Object,
    required: true,
    default: () => ({
      start: new Date().toISOString().split('T')[0],
      end: new Date().toISOString().split('T')[0]
    })
  }
})

const { activeTab, handleTabChange, handleSidebarNavigation } = useAdminTabs('analytics')

// Computed properties for analytics data
const totalVisits = computed(() => props.analytics.total_visits)
const visitTrends = computed(() => props.analytics.visit_trends)
const mostVisitedPages = computed(() => props.analytics.most_visited_pages)

// Stats cards data
const statsCards = computed(() => [
  {
    title: 'Total Revenue',
    value: '$45,231.89',
    change: '+20.1%',
    changeText: 'from last month',
    icon: 'currency-dollar'
  },
  {
    title: 'Subscriptions',
    value: '+2350',
    change: '+180.1%',
    changeText: 'from last month',
    icon: 'users'
  },
  {
    title: 'Sales',
    value: '+12,234',
    change: '+19%',
    changeText: 'from last month',
    icon: 'credit-card'
  },
  {
    title: 'Active Now',
    value: totalVisits.value.toString(),
    change: '+201',
    changeText: 'since last hour',
    icon: 'activity'
  }
])

// Date range state
const selectedDateRange = ref({
  start: props.dateRange.start,
  end: props.dateRange.end
})

// Methods
const handleDateRangeChange = () => {
  // Implement date range change logic
}

const handleDownload = () => {
  // Implement analytics data export
}

const handleViewAllPages = () => {
  // Implement view all pages logic
  // Could navigate to a full page view or open a modal
}

const {
  analytics,
  dateRange,
  filters,
  isLoading,
  updateTimeRange
} = useAnalytics()

onMounted(() => {
  handleSidebarNavigation('/adminn/dashboard/analytics')
})
</script>

<template>
  <Head title="Analytics Dashboard" />
  
  <ThemeProvider v-slot="{ theme, toggleTheme }">
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
                <h1 class="text-xl font-semibold text-foreground">Analytics Dashboard</h1>
              </div>
              <div class="flex items-center space-x-4">
                <Search />
                <ThemeSwitch @click="toggleTheme" />
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
                    <p class="text-muted-foreground mt-1">
                      Detailed analytics from {{ formatDate(selectedDateRange.start) }} 
                      to {{ formatDate(selectedDateRange.end) }}
                    </p>
                  </div>
                  <div class="flex items-center gap-4">
                    <!-- Date Range Picker Here -->
                    <Button variant="outline" class="gap-2">
                      <i class="fa-regular fa-calendar"></i>
                      Select Range
                    </Button>
                    <Button>
                      <i class="fa-regular fa-download mr-2"></i>
                      Download Report
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
                      <p class="text-xs text-muted-foreground flex items-center gap-1">
                        <i :class="`fa-solid fa-arrow-${stat.change >= 0 ? 'up text-green-500' : 'down text-red-500'}`"></i>
                        {{ Math.abs(stat.change) }}% {{ stat.changeText }}
                      </p>
                    </CardContent>
                  </Card>
                </div>
              </div>

              <!-- Analytics Content -->
              <div class="space-y-6">
                <!-- Stats Cards -->
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                  <Card v-for="stat in statsCards" :key="stat.title">
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                      <CardTitle class="text-sm font-medium">{{ stat.title }}</CardTitle>
                      <i :class="`fa-solid fa-${stat.icon} text-muted-foreground`"></i>
                    </CardHeader>
                    <CardContent>
                      <div class="text-2xl font-bold">{{ stat.value }}</div>
                      <p class="text-xs text-muted-foreground flex items-center gap-1">
                        <i :class="`fa-solid fa-arrow-${stat.change >= 0 ? 'up text-green-500' : 'down text-red-500'}`"></i>
                        {{ Math.abs(stat.change) }}% {{ stat.changeText }}
                      </p>
                    </CardContent>
                  </Card>
                </div>

                <!-- Charts Section -->
                <div class="grid gap-6 grid-cols-1 lg:grid-cols-7">
                  <div class="lg:col-span-4 space-y-6">
                    <Overview 
                      :data="visitTrends" 
                      title="Visit Trends"
                      description="Traffic patterns over time"
                    />
                    <TimeAnalysisCard />
                  </div>

                  <div class="lg:col-span-3">
                    <TopPagesCard 
                      :data="mostVisitedPages" 
                      @view-all="handleViewAllPages"
                    />
                  </div>
                </div>
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

@media (max-width: 640px) {
  .container {
    padding-left: 1rem;
    padding-right: 1rem;
  }
}
</style>
