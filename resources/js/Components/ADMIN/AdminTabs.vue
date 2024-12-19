<script setup>
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/Components/ui/tabs'
import { useAdminTabs } from '@/Composables/useAdminTabs'
import { onMounted, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const page = usePage()

const props = defineProps({
  showContent: {
    type: Boolean,
    default: true
  },
  defaultTab: {
    type: String,
    default: 'overview'
  },
  tabs: {
    type: Array,
    default: () => [
      { value: 'overview', label: 'Overview', disabled: false },
      { value: 'analytics', label: 'Analytics', disabled: false },
      { value: 'reports', label: 'Reports', disabled: false },
      { value: 'notifications', label: 'Notifications', disabled: true }
    ]
  }
})

const { activeTab, handleTabChange } = useAdminTabs(props.defaultTab)

// Handle tab changes with proper navigation
const onTabChange = (tab) => {
  handleTabChange(tab)
  
  // Map tab values to their corresponding routes
  const tabRoutes = {
    overview: '/adminn/dashboard',
    analytics: '/adminn/dashboard/analytics',
    reports: '/adminn/dashboard/reports',
    notifications: '/adminn/dashboard/notifications'
  }

  const route = tabRoutes[tab] || '/adminn/dashboard'
  
  // Use Inertia visit to properly fetch the data for each route
  router.visit(route, {
    preserveState: false, // Don't preserve state to ensure fresh data
    preserveScroll: true,
    replace: true
  })
}

// Compute the current tab based on URL and active tab
const currentTab = computed(() => {
  const path = window.location.pathname
  
  // Map routes to tab values
  const routeToTab = {
    '/adminn/dashboard': 'overview',
    '/adminn/dashboard/analytics': 'analytics',
    '/adminn/dashboard/reports': 'reports',
    '/adminn/dashboard/notifications': 'notifications'
  }

  return routeToTab[path] || 'overview'
})

onMounted(() => {
  // Set initial tab based on current route
  const path = window.location.pathname
  const initialTab = currentTab.value
  handleTabChange(initialTab)
})

defineEmits(['tabChanged'])
</script>

<template>
  <Tabs 
    :default-value="currentTab"
    :value="currentTab"
    class="space-y-6" 
    @update:modelValue="onTabChange"
  >
    <div class="w-full overflow-x-auto border-b">
      <TabsList class="inline-flex h-10 items-center justify-center rounded-md bg-muted p-1">
        <TabsTrigger 
          v-for="tab in tabs" 
          :key="tab.value"
          :value="tab.value"
          :disabled="tab.disabled"
          :class="[
            'inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm',
            currentTab === tab.value ? 'bg-background text-foreground shadow-sm' : 'text-muted-foreground hover:text-foreground'
          ]"
        >
          {{ tab.label }}
        </TabsTrigger>
      </TabsList>
    </div>
    
    <div v-if="showContent">
      <TabsContent 
        v-for="tab in tabs" 
        :key="tab.value"
        :value="tab.value"
        class="space-y-6"
      >
        <slot 
          name="content" 
          :active-tab="currentTab"
        />
      </TabsContent>
    </div>
  </Tabs>
</template>

<style scoped>
.border-b {
  border-color: hsl(var(--border));
}

:deep([data-state='active']) {
  background-color: hsl(var(--background));
  color: hsl(var(--foreground));
  box-shadow: var(--shadow-sm);
}

:deep([data-state='inactive']) {
  color: hsl(var(--muted-foreground));
}

:deep([data-state='inactive']:hover) {
  color: hsl(var(--foreground));
}
</style>