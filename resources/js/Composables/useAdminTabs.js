import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

// Create a singleton state outside the function
const activeTab = ref('overview')
const activeSidebarItem = ref('/adminn/dashboard')

export function useAdminTabs(defaultTab = 'overview') {
  // Set the initial active tab if provided
  if (defaultTab) {
    activeTab.value = defaultTab
  }

  const handleTabChange = (tab) => {
    if (tab === activeTab.value) return // Prevent unnecessary navigation
    
    activeTab.value = tab
    switch (tab) {
      case 'overview':
        if (window.location.pathname !== '/adminn/dashboard') {
          router.visit('/adminn/dashboard')
        }
        activeSidebarItem.value = '/adminn/dashboard'
        break
      case 'analytics':
        if (window.location.pathname !== '/adminn/analytics') {
          router.visit('/adminn/analytics')
        }
        activeSidebarItem.value = '/adminn/analytics'
        break
      // Add more cases as needed
    }
  }

  const handleSidebarNavigation = (path) => {
    if (path === activeSidebarItem.value) return // Prevent unnecessary updates
    
    activeSidebarItem.value = path
    switch (path) {
      case '/adminn/dashboard':
        activeTab.value = 'overview'
        break
      case '/adminn/analytics':
        activeTab.value = 'analytics'
        break
      // Add more cases as needed
    }
  }

  return {
    activeTab,
    activeSidebarItem,
    handleTabChange,
    handleSidebarNavigation
  }
}