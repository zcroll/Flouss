<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/Components/ui/tabs'

const props = defineProps({
  currentTab: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['tabChange'])

const tabs = [
  {
    value: 'overview',
    label: 'Overview',
    disabled: false,
    route: '/adminn/dashboard'
  },
  {
    value: 'analytics',
    label: 'Analytics',
    disabled: false,
    route: '/adminn/analytics'
  },
  {
    value: 'reports',
    label: 'Reports',
    disabled: true,
    route: '/adminn/reports'
  },
  {
    value: 'notifications',
    label: 'Notifications',
    disabled: true,
    route: '/adminn/notifications'
  }
]

const handleTabChange = (tab) => {
  const selectedTab = tabs.find(t => t.value === tab)
  if (selectedTab && !selectedTab.disabled) {
    emit('tabChange', tab)
    router.visit(selectedTab.route)
  }
}
</script>

<template>
  <Tabs 
    :value="currentTab" 
    class="space-y-6" 
    @update:modelValue="handleTabChange"
  >
    <div class="w-full overflow-x-auto">
      <TabsList>
        <TabsTrigger 
          v-for="tab in tabs" 
          :key="tab.value"
          :value="tab.value"
          :disabled="tab.disabled"
        >
          {{ tab.label }}
        </TabsTrigger>
      </TabsList>
    </div>
    <slot></slot>
  </Tabs>
</template> 