<script setup>
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/Components/ui/tabs'
import { useAdminTabs } from '@/Composables/useAdminTabs'
import { onMounted, watch } from 'vue'
import { router } from '@inertiajs/vue3'

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
      { value: 'reports', label: 'Reports', disabled: true },
      { value: 'notifications', label: 'Notifications', disabled: true }
    ]
  }
})

const { activeTab, handleTabChange } = useAdminTabs(props.defaultTab)

onMounted(() => {
  if (props.defaultTab) {
    activeTab.value = props.defaultTab
  }
})

// Watch for tab changes and handle navigation
watch(activeTab, (newTab) => {
  if (newTab === 'overview' && window.location.pathname !== '/adminn/dashboard') {
    router.visit('/adminn/dashboard')
  } else if (newTab === 'analytics' && window.location.pathname !== '/adminn/analytics') {
    router.visit('/adminn/analytics')
  }
})

defineEmits(['tabChanged'])
</script>

<template>
  <Tabs 
    :default-value="defaultTab"
    :value="activeTab"
    class="space-y-6" 
    @update:modelValue="handleTabChange"
  >
    <div class="w-full overflow-x-auto border-b">
      <TabsList class="inline-flex h-10 items-center justify-center rounded-md bg-muted p-1">
        <TabsTrigger 
          v-for="tab in tabs" 
          :key="tab.value"
          :value="tab.value"
          :disabled="tab.disabled"
          class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm"
        >
          {{ tab.label }}
        </TabsTrigger>
      </TabsList>
    </div>
    
    <slot v-if="showContent" name="content" />
  </Tabs>
</template>

<style scoped>
.border-b {
  border-color: hsl(var(--border));
}
</style>