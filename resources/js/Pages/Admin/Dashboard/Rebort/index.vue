<script setup>
import { ref, onMounted } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import { Button } from '@/Components/ui/button'
import Overview from '@/Components/ADMIN/Charts/Overview.vue'
import RecentSales from '@/Components/ADMIN/List/TopPagesCard.vue'
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

defineOptions({
  layout: null
})

const { activeTab, handleTabChange, handleSidebarNavigation } = useAdminTabs('reports')

onMounted(() => {
  handleSidebarNavigation('/adminn/reports')
})
</script>

<template>
  <Head title="Reports" />
  
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
          <header class="bg-card shadow-sm z-10">
            <div class="px-4 py-3 flex justify-between items-center">
              <div class="flex items-center space-x-4">
                <h1 class="text-xl font-semibold text-foreground">Admin Dashboard</h1>
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
            <div class="container mx-auto px-6 py-8">
              <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold tracking-tight text-foreground">Reports Dashboard</h1>
                <div class="flex items-center space-x-2">
                  <Button>Download</Button>
                </div>
              </div>

              <AdminTabs :default-tab="'reports'">
                <template #content>
                <TabsContent value="overview">
                  <!-- Overview content will be handled by navigation -->
                </TabsContent>
                <TabsContent value="analytics">
                  <!-- Analytics content will be handled by navigation -->
                </TabsContent>
                <TabsContent value="reports" class="space-y-6">
                  <!-- Stats Cards -->
                  <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <Card>
                      <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Reports</CardTitle>
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="currentColor"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          class="h-4 w-4 text-muted-foreground"
                        >
                          <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                      </CardHeader>
                      <CardContent>
                        <div class="text-2xl font-bold">1,234</div>
                        <p class="text-xs text-muted-foreground">+20.1% from last month</p>
                      </CardContent>
                    </Card>

                    <Card>
                      <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Active Reports</CardTitle>
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="currentColor"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          class="h-4 w-4 text-muted-foreground"
                        >
                          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                          <circle cx="9" cy="7" r="4" />
                          <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                      </CardHeader>
                      <CardContent>
                        <div class="text-2xl font-bold">892</div>
                        <p class="text-xs text-muted-foreground">+180.1% from last month</p>
                      </CardContent>
                    </Card>

                    <Card>
                      <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Completed Reports</CardTitle>
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="currentColor"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          class="h-4 w-4 text-muted-foreground"
                        >
                          <rect width="20" height="14" x="2" y="5" rx="2" />
                          <path d="M2 10h20" />
                        </svg>
                      </CardHeader>
                      <CardContent>
                        <div class="text-2xl font-bold">342</div>
                        <p class="text-xs text-muted-foreground">+19% from last month</p>
                      </CardContent>
                    </Card>

                    <Card>
                      <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Pending Reports</CardTitle>
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="currentColor"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          class="h-4 w-4 text-muted-foreground"
                        >
                          <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                        </svg>
                      </CardHeader>
                      <CardContent>
                        <div class="text-2xl font-bold">573</div>
                        <p class="text-xs text-muted-foreground">+201 since last hour</p>
                      </CardContent>
                    </Card>
                  </div>

                  <!-- Reports Charts Section -->
                  <div class="grid gap-6 lg:grid-cols-7">
                    <Card class="col-span-4">
                      <CardHeader>
                        <CardTitle>Reports Overview</CardTitle>
                      </CardHeader>
                      <CardContent class="pl-2">
                        <Overview />
                      </CardContent>
                    </Card>

                    <Card class="col-span-3">
                      <CardHeader>
                        <CardTitle>Recent Reports</CardTitle>
                        <CardDescription>Reports generated in the last 30 days</CardDescription>
                      </CardHeader>
                      <CardContent>
                        <RecentSales />
                      </CardContent>
                    </Card>
                  </div>
                </TabsContent>
                <TabsContent value="notifications">
                  <!-- Notifications content -->
                </TabsContent>
                </template>
              </AdminTabs>
            </div>
          </main>
        </div>
      </div>
    </div>
  </ThemeProvider>
</template>

<style>
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
